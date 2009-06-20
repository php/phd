<?php
namespace phpdotnet\phd;

$ROOT = __DIR__;
function autoload($name)
{
    require_once str_replace(array('\\', '_'), '/', $name) . '.php';
}
spl_autoload_register(__NAMESPACE__ . '\\autoload');
require_once 'phpdotnet/phd/functions.php';

$optparser = new BuildOptionsParser();
$optparser->getopt();

define("NO_SQLITE", false);

/* If no docbook file was passed, die */
if (!is_dir(Config::xml_root()) || !is_file(Config::xml_file())) {
    trigger_error("No '.manual.xml' file was given. Specify it on the command line with --docbook.", E_USER_ERROR);
}
if (!file_exists(Config::output_dir())) {
    v("Creating output directory..", E_USER_NOTICE);
    if (!mkdir(Config::output_dir())) {
        v("Can't create output directory", E_USER_ERROR);
    }
} elseif (!is_dir(Config::output_dir())) {
    v("Output directory is not a file?", E_USER_ERROR);
}

Config::init(array(
    "verbose"                 => VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING),
    "lang_dir"                => $ROOT . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "langs" . DIRECTORY_SEPARATOR,

    "phpweb_version_filename" => Config::xml_root() . DIRECTORY_SEPARATOR . 'version.xml',
    "phpweb_acronym_filename" => Config::xml_root() . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'acronyms.xml',
    ));

$render = new Render();
$reader = new Reader();
$factory = Format_Factory::createFactory();

// Indexing & registering formats
foreach(range(0, 0) as $i) {
    if (Index::requireIndexing()) {
        v("Indexing...", VERBOSE_INDEXING);
        // Create indexer
        $format = new Index();
        $render->attach($format);

        $reader->open(Config::xml_file());
        $render->render($reader);

        $format->commit();
        $render->detach($format);

        v("Indexing done", VERBOSE_INDEXING);
    } else {
        v("Skipping indexing", VERBOSE_INDEXING);
    }

    foreach (Config::output_format() as $format) {
        switch($format) {
            case "xhtml": // Standalone Chunked xHTML Format
            $render->attach($factory->createXhtmlFormat());
            break;
            case "php": // Standalone phpweb Format
            $render->attach($factory->createPHPFormat());
            break;
            case "bigxhtml": // Standalone Big xHTML Format
            $render->attach($factory->createBigXhtmlFormat());
            break;
        }
    }
}

// Rendering formats
foreach(range(0, 0) as $i) {
	$reader->open(Config::xml_file());
    foreach($render as $format) {
        $format->notify(Render::VERBOSE, true);
    }
    $render->render($reader);
}

v("Finished rendering", VERBOSE_FORMAT_RENDERING);

