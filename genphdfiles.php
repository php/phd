<?php
/**
 * Generate the auxiliary files that the PHP package of PhD consumes:
 *
 *   - version.xml           (merged <function> version banners)
 *   - sources.xml           (xml:id -> {lang, path} map)
 *   - fileModHistory.php    (file modification history)
 *
 * These were historically produced as a tail step of doc-base/configure.php.
 * They are now generated here, from the doc-base/temp/phd-conf.json handoff,
 * so that configure.php can focus solely on assembling and validating the XML.
 *
 * Usage:
 *   php genphdfiles.php [path/to/phd-conf.json]
 *
 * The logic mirrors the former configure.php functions verbatim, to keep the
 * output byte-identical.
 */

$confPath = $argv[1] ?? (__DIR__ . '/../doc-base/temp/phd-conf.json');
if (!is_file($confPath)) {
    fwrite(STDERR, "phd-conf.json not found: {$confPath}\n");
    exit(1);
}
$conf = json_decode(file_get_contents($confPath), true);
if (!is_array($conf)) {
    fwrite(STDERR, "phd-conf.json is not valid JSON: {$confPath}\n");
    exit(1);
}

if (!empty($conf['outputs']['history'])) {
    phd_history($conf);
}
if (!empty($conf['outputs']['sources'])) {
    phd_sources($conf);
}
if (!empty($conf['outputs']['version'])) {
    phd_version($conf);
}

exit(0);

// -----------------------------------------------------------------------------

function phd_history(array $conf): void
{
    echo 'PhD history:';

    $lang_mod_file = (($conf['lang'] !== 'en')
        ? "{$conf['rootdir']}/{$conf['enDir']}"
        : "{$conf['rootdir']}/{$conf['langDir']}") . "/fileModHistory.php";
    $doc_base_mod_file = $conf['srcdir'] . "/fileModHistory.php";

    $history_file = null;
    if (file_exists($lang_mod_file)) {
        $history_file = include $lang_mod_file;
        if (is_array($history_file)) {
            echo ' copy,';
            $isFileCopied = copy($lang_mod_file, $doc_base_mod_file);
            echo $isFileCopied ? "" : " failed,";
        } else {
            echo " corrupted file '$lang_mod_file,'";
        }
    } else {
        echo " not found,";
    }

    if (!is_array($history_file)) {
        $history_file = [];
        echo " creating empty,";
        file_put_contents($doc_base_mod_file, "<?php\n\nreturn [];\n");
    }
    echo " done.\n";
}

function phd_sources(array $conf): void
{
    echo 'PhD sources:';

    $dest  = $conf['srcdir'] . '/sources.xml';
    $cache = $conf['srcdir'] . '/temp/phd-sources.xml';
    // The source scan below reads every XML file of the manual, so it is by far
    // the most expensive of these generators. configure.php wipes temp/ on
    // every run, so a copy stashed there can be reused by repeated PhD runs
    // without ever going out of sync with the manual.xml generated alongside it.
    $cacheable = is_dir(dirname($cache));

    if ($cacheable && is_file($cache)) {
        echo ' cached,';
        echo copy($cache, $dest) ? " done.\n" : " fail!\n";
        return;
    }

    echo ' reading,';
    $source_map = array();
    $en_dir = "{$conf['rootdir']}/{$conf['enDir']}";
    $source_langs = array(
        array('base', $conf['srcdir'], array('manual.xml', 'funcindex.xml')),
        array('en', $en_dir, find_xml_files($en_dir)),
    );
    if ($conf['lang'] !== 'en') {
        $lang_dir = "{$conf['rootdir']}/{$conf['langDir']}";
        $source_langs[] = array($conf['lang'], $lang_dir, find_xml_files($lang_dir));
    }
    foreach ($source_langs as list($source_lang, $source_dir, $source_files)) {
        foreach ($source_files as $source_path) {
            $source = file_get_contents("{$source_dir}/{$source_path}");
            if (preg_match_all('/ xml:id=(["\'])([^"]+)\1/', $source, $matches)) {
                foreach ($matches[2] as $xml_id) {
                    $source_map[$xml_id] = array(
                        'lang' => $source_lang,
                        'path' => $source_path,
                    );
                }
            }
        }
    }
    asort($source_map);
    echo ' transforming,';
    $dom = new DOMDocument;
    $dom->formatOutput = true;
    $sources_elem = $dom->appendChild($dom->createElement("sources"));
    foreach ($source_map as $id => $source) {
        $el = $dom->createElement('item');
        $el->setAttribute('id', $id);
        $el->setAttribute('lang', $source["lang"]);
        $el->setAttribute('path', $source["path"]);
        $sources_elem->appendChild($el);
    }
    echo " saving,";
    if ($dom->save($dest)) {
        if ($cacheable) {
            copy($dest, $cache);
        }
        echo " done.\n";
    } else {
        echo " fail!\n";
    }
}

function phd_version(array $conf): void
{
    echo 'PhD version:';

    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput       = true;

    $tmp = new DOMDocument;
    $tmp->preserveWhiteSpace = false;

    $versions = $dom->appendChild($dom->createElement("versions"));

    echo ' reading,';
    if ($conf["generate"] != "no") {
        $globdir = dirname($conf["generate"]) . "/{../../}versions.xml";
    }
    else {
        $globdir = $conf['rootdir'] . '/en';
        $globdir .= "/*/*/versions.xml";
    }
    echo ' transforming,';
    if (!defined('GLOB_BRACE')) {
        define('GLOB_BRACE', 0);
    }
    foreach(glob($globdir, GLOB_BRACE) as $file) {
        if($tmp->load($file)) {
            foreach($tmp->getElementsByTagName("function") as $function) {
                $function = $dom->importNode($function, true);
                $versions->appendChild($function);
            }
        } else {
            print_xml_errors($conf);
            errors_are_bad(1);
        }
    }
    echo ' saving,';

    if ($dom->save($conf['srcdir'] . '/version.xml')) {
        echo " done.\n";
    } else {
        echo " fail!\n";
    }
}

// -----------------------------------------------------------------------------
// Helpers ported from doc-base/configure.php
// -----------------------------------------------------------------------------

function find_xml_files($path)
{
    $path = rtrim($path, '/');
    $prefix_len = strlen($path . '/');
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($files as $fileinfo) {
        if ($fileinfo->getExtension() === 'xml') {
            yield substr($fileinfo->getPathname(), $prefix_len);
        }
    }
}

function print_xml_errors(array $conf): void
{
    $report = $conf['lang'] == 'en' || !empty($conf['xpointerReporting']);
    $output = !empty($conf['stderrToStdout']) ? STDOUT : STDERR;

    $errors = libxml_get_errors();
    libxml_clear_errors();

    $filePrefix = "file:///";
    $tempPrefix = realpath($conf['srcdir'] . "/temp") . "/";
    $rootPrefix = realpath($conf['rootdir']) . "/";

    if (count($errors) > 0) {
        fprintf($output, "\n");
    }

    foreach ($errors as $error) {
        $mssg = rtrim($error->message);
        $file = $error->file;
        $line = $error->line;
        $clmn = $error->column;

        if (str_starts_with($mssg, 'XPointer evaluation failed:') && !$report) {
            continue;
        }

        if (str_starts_with($file, $filePrefix)) {
            $file = substr($file, strlen($filePrefix));
        }
        if (str_starts_with($file, $tempPrefix)) {
            $file = substr($file, strlen($tempPrefix));
        }
        if (str_starts_with($file, $rootPrefix)) {
            $file = substr($file, strlen($rootPrefix));
        }

        $prefix = $error->level === LIBXML_ERR_FATAL ? "FATAL" : "error";

        fwrite($output, "[$prefix $file {$line}:{$clmn}] {$mssg}\n");
    }
}

function errors_are_bad($status)
{
    echo "\nEyh man. No worries. Happ shittens. Try again after fixing the errors above.\n";
    exit($status);
}
