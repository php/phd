<?php

namespace phpdotnet\phd;

/**
 * Renders a language directory's redirects.xml sidecar into
 * redirects.json for consumption by web-php/error.php.
 *
 * If the language root (the parent of --output) has no redirects.xml,
 * this is a no-op.
 *
 * Every `to=` value is validated against xml:ids collected from the
 * language root and the docbook root (typically doc-base); a missing
 * target aborts the build. A redirect with a manual= attribute leads to
 * another manual (e.g. manual="extensions" for doc-extensions), which is
 * built on its own: its target is not validated.
 */
final class Redirects
{
    /**
     * Prefix fallbacks used when resolving a target xml:id. Mirrors
     * the fallback list in web-php/include/manual-lookup.inc so build
     * validation matches runtime dispatch.
     */
    public const PREFIXES = [
        '',
        'book.',
        'ref.',
        'function.',
        'class.',
        'enum.',
        'features.',
        'control-structures.',
        'language.',
        'about.',
        'faq.',
    ];

    private const OUTPUT_SUBDIR = 'php-web';
    private const OUTPUT_FILE = 'redirects.json';

    public static function renderTo(string $outputDir, string $xmlRoot, OutputHandler $out): void
    {
        $outputDir = rtrim($outputDir, DIRECTORY_SEPARATOR);
        $langRoot = dirname($outputDir);
        $input = $langRoot . DIRECTORY_SEPARATOR . 'redirects.xml';
        if (!file_exists($input)) {
            return;
        }

        $maps = self::parse($input);
        $ids = self::collectXmlIds($langRoot) + self::collectXmlIds($xmlRoot);
        $broken = self::findBrokenTargets($maps, $ids);

        if ($broken !== []) {
            $lines = array_map(
                static fn(array $b): string => sprintf('[%s] %s -> %s', $b[0], $b[1], $b[2]),
                $broken
            );

            throw new \Error(sprintf(
                "Broken redirect targets in %s:\n  %s",
                $outputDir,
                implode("\n  ", $lines),
            ));
        }

        $output = $outputDir . DIRECTORY_SEPARATOR . self::OUTPUT_SUBDIR;
        if (!is_dir($output) && !mkdir($output, 0777, true) && !is_dir($output)) {
            throw new \Error('Could not create' . $output);
        }

        $file = $output . DIRECTORY_SEPARATOR . self::OUTPUT_FILE;
        if (file_put_contents($file, json_encode($maps) . "\n") === false) {
            throw new \Error('Could not write ' . $file);
        }

        $out->v(
            sprintf(
                'Rendered %s (shortcut=%d, in_place=%d, in_manual=%d, both=%d)',
                $file,
                count($maps['shortcut']),
                count($maps['in_place']),
                count($maps['in_manual']),
                count($maps['both'])
            ),
            VERBOSE_FORMAT_RENDERING,
        );
    }

    /**
     * Parse a redirects.xml file into scope-keyed maps.
     *
     * A shortcut group marked serve="in-place" is an expansion, not a
     * redirect: its entries go to `in_place` so that the page is served at
     * the requested URL instead of answering a 301.
     *
     * The target of a redirect with a manual= attribute is a page of that
     * manual, served from /manual/{manual}/: it is emitted as `{manual}/{to}`.
     *
     * @return array{shortcut: array<string,string>, in_manual: array<string,string>, both: array<string,string>, in_place: array<string,string>}
     */
    public static function parse(string $path): array
    {
        $content = @file_get_contents($path);
        if ($content === false) {
            throw new \Error("Could not read $path");
        }

        $prev = libxml_use_internal_errors(true);
        libxml_clear_errors();
        $xml = simplexml_load_string($content);
        libxml_use_internal_errors($prev);

        if ($xml === false) {
            $err = libxml_get_last_error();
            throw new \Error(
                sprintf(
                    'Could not parse %s: %s',
                    $path,
                    $err ? trim($err->message) : 'unknown parse error'
                )
            );
        }

        $maps = ['shortcut' => [], 'in_manual' => [], 'both' => [], 'in_place' => []];

        foreach ($xml->group as $group) {
            $scope = (string)$group['scope'];
            $key = $scope === 'in-manual' ? 'in_manual' : $scope;
            if ($key === 'in_place' || !array_key_exists($key, $maps)) {
                throw new \Error("Unknown scope in $path: $scope");
            }

            $serve = (string)$group['serve'];
            if ($serve !== '') {
                if ($serve !== 'in-place') {
                    throw new \Error("Unknown serve value in $path: $serve");
                }
                if ($key !== 'shortcut') {
                    throw new \Error("serve=\"in-place\" only applies to the shortcut scope in $path");
                }
                $key = 'in_place';
            }

            foreach ($group->redirect as $r) {
                $from = (string)$r['from'];
                $to = (string)$r['to'];
                if ($from === '' || $to === '') {
                    throw new \Error(sprintf('Empty from/to in %s (scope=%s)', $path, $scope));
                }
                $manual = (string)$r['manual'];
                if ($manual !== '' && !preg_match('/^[a-z][a-z0-9_-]*$/', $manual)) {
                    throw new \Error(sprintf('Invalid manual in %s: %s', $path, $manual));
                }
                // Only a page of this manual can be served at the requested URL.
                if ($manual !== '' && $key === 'in_place') {
                    throw new \Error(sprintf('Cannot serve a page of another manual in place in %s: %s -> %s', $path, $from, $to));
                }
                // A bare URI is either redirected or served in place, never both.
                $duplicate = $key === 'shortcut' || $key === 'in_place'
                    ? isset($maps['shortcut'][$from]) || isset($maps['in_place'][$from])
                    : isset($maps[$key][$from]);
                if ($duplicate) {
                    throw new \Error(sprintf('Duplicate key in %s (scope=%s): %s', $path, $scope, $from));
                }
                // The anchor of a page that is not redirected to is lost.
                if ($key === 'in_place' && str_contains($to, '#')) {
                    throw new \Error(sprintf('Cannot serve an anchor in place in %s: %s -> %s', $path, $from, $to));
                }
                $maps[$key][$from] = $manual === '' ? $to : "$manual/$to";
            }
        }

        return $maps;
    }

    /**
     * @return array<string,true>
     */
    public static function collectXmlIds(string $root): array
    {
        if (!is_dir($root)) {
            return [];
        }

        $ids = [];
        $iter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iter as $file) {
            /** @var \SplFileInfo $file */
            if (!$file->isFile() || $file->getExtension() !== 'xml') {
                continue;
            }
            $content = @file_get_contents($file->getPathname());
            if ($content === false) {
                continue;
            }
            if (preg_match_all('/xml:id="([^"]+)"/', $content, $m)) {
                foreach ($m[1] as $id) {
                    $ids[$id] = true;
                }
            }
        }

        return $ids;
    }

    /**
     * @param array<string,array<string,string>> $maps
     * @param array<string,mixed> $ids
     * @param array<string> $prefixes
     * @return list<array{0:string,1:string,2:string}>
     */
    public static function findBrokenTargets(array $maps, array $ids, array $prefixes = self::PREFIXES): array
    {
        $broken = [];
        foreach ($maps as $scope => $entries) {
            foreach ($entries as $from => $to) {
                // A page of another manual (`{manual}/{to}`) cannot be checked here.
                if (str_contains($to, '/')) {
                    continue;
                }
                $base = explode('#', $to, 2)[0];
                foreach ($prefixes as $p) {
                    $key = $p . $base;
                    if (isset($ids[$key])) {
                        continue 2;
                    }
                }
                $broken[] = [$scope, $from, $to];
            }
        }

        return $broken;
    }
}
