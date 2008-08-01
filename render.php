<?php

$ROOT = __DIR__;

require $ROOT . "/include/PhDObjectStorage.class.php";
require $ROOT . "/config.php";
include $ROOT . "/include/PhDBuildOptions.class.php";

require $ROOT . "/include/PhDReader.class.php";
require $ROOT . "/include/PhDRender.class.php";
require $ROOT . "/include/PhDFormat.class.php";
require $ROOT . "/include/PhDIndex.class.php";

require $ROOT . "/formats/xhtml.php";
require $ROOT . "/formats/bigxhtml.php";
require $ROOT . "/formats/php.php";

//$INDEX    = "/Users/loudi/Travail/phpdoc/.manual.xml";
//$FILENAME = "/Users/loudi/Travail/phpdoc/.manual.xml";
//$INDEX = $FILENAME = "/home/bjori/php/cleandocs/json.xml";
define("NO_SQLITE", false);

/* If no docbook file was passed, die */
if (!is_dir(PhDConfig::xml_root()) || !is_file(PhDConfig::xml_file())) {
    trigger_error("No '.manual.xml' file was given. Specify it on the command line with --docbook.", E_USER_ERROR);
}

PhDConfig::init(array(
    "verbose"                 => VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING),
    "lang_dir"                => $ROOT . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "langs" . DIRECTORY_SEPARATOR,

    "phpweb_version_filename" => PhDConfig::xml_root() . DIRECTORY_SEPARATOR . 'phpbook' . DIRECTORY_SEPARATOR . 'phpbook-xsl' . DIRECTORY_SEPARATOR . 'version.xml',
    "phpweb_acronym_filename" => PhDConfig::xml_root() . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'acronyms.xml',
    ));

$render = new PhDRender();
$reader = new PhDReader();

foreach(range(0, 0) as $i) {
    //var_dump(date(DATE_RSS));

    if (PhDConfig::index()) {
        v("Indexing...", VERBOSE_INDEXING);
        // Create indexer
        $format = new PhDIndex();
        $render->attach($format);

        $reader->open(PhDConfig::xml_file());
        $render->render($reader);

        $format->commit();
        $render->detach($format);

        v("Indexing done", VERBOSE_INDEXING);
        //var_dump(date(DATE_RSS));
    } else {
        v("Skipping indexing", VERBOSE_INDEXING);
    }

    foreach (PhDConfig::output_format() as $format) {
        switch($format) {
            case "xhtml": // Standalone Chunked xHTML Format
            $render->attach(new PhDXHTMLFormat());
            break;
            case "php": // Standalone phpweb Format
            $render->attach(new PhDPHPFormat());
            break;
            case "bigxhtml": // Standalone Big xHTML Format
            $render->attach(new PhDBigXHTMLFormat());
            break;
        }
    }
/*
// Standalone phpweb Format
//$xhtml = new PhDPHPFormat();
//$render->attach($xhtml);


// Standalone Chunked xHTML Format
$xhtml = new PhDXHTMLFormat();
$render->attach($xhtml);

// Use the markup from the Chunked xHTML Format to produce Big xHTML
$bightml = new PhDBigXHTMLFormat();
$xhtml->attach($bightml);

// Standalone Big xHTML Format
$bightml = new PhDBigXHTMLFormat();
$render->attach($bightml);
*/
}

foreach(range(0, 0) as $i) {
    $reader->open(PhDConfig::xml_file());
    $render->render($reader);
}

