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
 * target aborts the build.
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

        if (file_put_contents($output, json_encode($maps) . "\n") === false) {
            throw new \Error('Could not write' . $output);
        }

        $out->v(
            sprintf(
                'Rendered %s (shortcut=%d, in_manual=%d, both=%d)',
                $output,
                count($maps['shortcut']),
                count($maps['in_manual']),
                count($maps['both'])
            ),
            VERBOSE_FORMAT_RENDERING,
        );
    }

    /**
     * Parse a redirects.xml file into three scope-keyed maps.
     *
     * @return array{shortcut: array<string,string>, in_manual: array<string,string>, both: array<string,string>}
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

        $maps = ['shortcut' => [], 'in_manual' => [], 'both' => []];

        foreach ($xml->group as $group) {
            $scope = (string)$group['scope'];
            $key = $scope === 'in-manual' ? 'in_manual' : $scope;
            if (!array_key_exists($key, $maps)) {
                throw new \Error("Unknown scope in $path: $scope");
            }

            foreach ($group->redirect as $r) {
                $from = (string)$r['from'];
                $to = (string)$r['to'];
                if ($from === '' || $to === '') {
                    throw new \Error(sprintf('Empty from/to in %s (scope=%s)', $path, $scope));
                }
                if (isset($maps[$key][$from])) {
                    throw new \Error(sprintf('Duplicate key in %s (scope=%s): %s', $path, $scope, $from));
                }
                $maps[$key][$from] = $to;
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
