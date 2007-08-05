#!/home/bjori/.apps/bin/php
<?php
/*  $Id$ */

function err($no, $str, $file, $line) {
	global $notify;
	if (strpos($str, "No mapper") !== false) {
//		$notify->update("Another missing function", strstr($str, "'"))->show();
		return false;
	}

	$err = new PHNotify("Something wrong!", "$str\n$file:$line\n", "dialog-error");
	$err
		->urgency(PHNotify::URGENCY_CRITICAL)
		->timeout(PHNotify::EXPIRES_NEVER)
		->hint("x", 1680/2)->hint("y", 1050/2)
		->show();
	return false;
}

if ($err = extension_loaded("phnotify")) {
	$notify = new PHNotify("Starting build");
	$notify->urgency(PHNotify::URGENCY_LOW)->hint("x", 1680)->hint("y", 10)->show();
	$start = microtime(true);
	set_error_handler("err");
}

require "include/PhDReader.class.php";
require "include/PhDFormat.class.php";
require "formats/xhtml.php";
require "formats/php.php";
require "themes/phpweb.php";
require "mktoc.php";

if ($err) {
	$mktoc = microtime(true);
	$notify
		->update("mktoc finished", sprintf("mktoc ran for <b>%d</b> sec", $mktoc-$start))
		->show();
}

$reader = new PhDReader("/home/bjori/php/doc/.manual.xml");
$format = new phpweb($reader, $IDs, $IDMap);

$map = $format->getMap();

while($reader->read()) {
    $type = $reader->nodeType;
    $name = $reader->name;

    switch($type) {
    case XMLReader::ELEMENT:
    case XMLReader::END_ELEMENT:
        $open = $type == XMLReader::ELEMENT;

        $funcname = "format_$name";
        if (isset($map[$name])) {
            $tag = $map[$name];
            if (is_array($tag)) {
                $tag = $reader->notXPath($tag);
            }
            if (strncmp($tag, "format_", 7)) {
                $retval = $format->transformFromMap($open, $tag, $name);
                break;
            }
            $funcname = $tag;
        }

        $retval = $format->{$funcname}($open, $name);
        break;

    case XMLReader::TEXT:
        $retval = htmlspecialchars($reader->value, ENT_QUOTES);
        break;

    case XMLReader::CDATA:
        $retval = $format->CDATA($reader->value);
        break;

    case XMLReader::COMMENT:
    case XMLReader::WHITESPACE:
    case XMLReader::SIGNIFICANT_WHITESPACE:
    case XMLReader::DOC_TYPE:
        /* swallow it */
        continue 2;

    default:
        trigger_error("Don't know how to handle {$name} {$type}", E_USER_ERROR);
        return;
    }
    $format->appendData($retval, $reader->isChunk);
}

copy("cache/manual.php", "cache/index.php");
$reader->close();

if ($err) {
	$end = microtime(true);
	$notify
		->update(
				"PhD build finished",
				sprintf("mktoc build: <b>%d</b> sec\nPhD build   : <b>%d</b> sec\n--\nTotal time: <b>%d</b> seconds\n", $mktoc-$start, $end-$mktoc, $end-$start))
		->show();
}
/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

