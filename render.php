<?php

require "include/PhDObjectStorage.class.php";
require "include/PhDConfig.class.php";

require "include/PhDReader.class.php";
require "include/PhDRender.class.php";
require "include/PhDFormat.class.php";
require "include/PhDIndex.class.php";

require "formats/xhtml.php";
require "formats/bigxhtml.php";
require "formats/php.php";

$INDEX    = "/home/bjori/php/doc/.manual.xml";
$FILENAME = "/home/bjori/php/doc/.manual.xml";
//$INDEX = $FILENAME = "/home/bjori/php/cleandocs/json.xml";
define("NO_SQLITE", false);

PhDConfig::init(array(
	"verbose"                 => VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING),
	"lang_dir"                => __DIR__ . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "langs" . DIRECTORY_SEPARATOR,
	"output_dir"              => __DIR__ . DIRECTORY_SEPARATOR . "output" . DIRECTORY_SEPARATOR,

	"phpweb_version_filename" => dirname($FILENAME) . DIRECTORY_SEPARATOR . 'phpbook' . DIRECTORY_SEPARATOR . 'phpbook-xsl/' . 'version.xml',
	"phpweb_acronym_filename" => dirname($FILENAME) . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'acronyms.xml',
));

$render = new PhDRender();

$reader = new PhDReader();

foreach(range(0, 0) as $i) {
	var_dump(date(DATE_RSS));

	if (1) {
	// Create indexer
	$format = new PhDIndex();
	$render->attach($format);

	$reader->open($INDEX);
	$render->render($reader);

	$format->commit();
	$render->detach($format);

	var_dump(date(DATE_RSS));
	}

	// Standalone phpweb Format
	$xhtml = new PhDPHPFormat();
	$render->attach($xhtml);

	/*
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
	$reader->open($FILENAME);
	$render->render($reader);
}

