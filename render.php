<?php

$ROOT = __DIR__;

require $ROOT . "/include/PhDObjectStorage.class.php";
require $ROOT . "/include/PhDHelper.class.php";
require $ROOT . "/config.php";
include $ROOT . "/include/PhDBuildOptions.class.php";

require $ROOT . "/include/PhDReader.class.php";
require $ROOT . "/include/PhDFormat.class.php";
require $ROOT . "/include/PhDTheme.class.php";
require $ROOT . "/include/PhDThemeXhtml.class.php";

//Enterprise
require $ROOT . "/include/PhDEnterpriseReader.class.php";
require $ROOT . "/include/PhDRender.class.php";
require $ROOT . "/include/PhDEnterpriseFormat.class.php";
require $ROOT . "/include/PhDIndex.class.php";
require $ROOT . "/include/PhDFormatFactory.php";

require $ROOT . "/formats/AbstractXHTMLFormat.php";
//require $ROOT . "/formats/AbstractBigXHTMLFormat.php";
//require $ROOT . "/formats/AbstractPHPFormat.php";
require $ROOT . "/formats/xhtml.php";
require $ROOT . "/formats/manpage.php";
require $ROOT . "/formats/pdf.php";

//$INDEX    = "/Users/loudi/Travail/phpdoc/.manual.xml";
//$FILENAME = "/Users/loudi/Travail/phpdoc/.manual.xml";
//$INDEX = $FILENAME = "/home/bjori/php/cleandocs/json.xml";
define("NO_SQLITE", false);

/* If no docbook file was passed, die */
if (!is_dir(PhDConfig::xml_root()) || !is_file(PhDConfig::xml_file())) {
    trigger_error("No '.manual.xml' file was given. Specify it on the command line with --docbook.", E_USER_ERROR);
}
if (!file_exists(PhDConfig::output_dir())) {
    v("Creating output directory..", E_USER_NOTICE);
    if (!mkdir(PhDConfig::output_dir())) {
        v("Can't create output directory", E_USER_ERROR);
    }
} elseif (!is_dir(PhDConfig::output_dir())) {
    v("Output directory is not a file?", E_USER_ERROR);
}

PhDConfig::init(array(
    "verbose"                 => VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING),
    "lang_dir"                => $ROOT . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "langs" . DIRECTORY_SEPARATOR,

    "phpweb_version_filename" => PhDConfig::xml_root() . DIRECTORY_SEPARATOR . 'version.xml',
    "phpweb_acronym_filename" => PhDConfig::xml_root() . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'acronyms.xml',
    ));

$render = new PhDRender();
$reader = new PhDEnterpriseReader();
$factory = PhDFormatFactory::createFactory();

// Indexing & registering formats
foreach(range(0, 0) as $i) {
    if (PhDIndex::requireIndexing()) {
        v("Indexing...", VERBOSE_INDEXING);
        // Create indexer
        $format = new PhDIndex();
        $render->attach($format);

        $reader->open(PhDConfig::xml_file());
        $render->render($reader);

        $format->commit();
        $render->detach($format);

        v("Indexing done", VERBOSE_INDEXING);
    } else {
        v("Skipping indexing", VERBOSE_INDEXING);
    }

    foreach (PhDConfig::output_format() as $format) {
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
	$reader->open(PhDConfig::xml_file());
    foreach($render as $format) {
        $format->notify(PhDRender::VERBOSE, true);
    }
    $render->render($reader);
}

v("Finished rendering", VERBOSE_FORMAT_RENDERING);

