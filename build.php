<?php
/*  $Id$ */

if (isset($argc) && $argc == 3) {
    $manual = $argv[1];
    $version = $argv[2];
} else if (file_exists("./config.php")) {
    include "./config.php";
}
if (!file_exists($manual) || !file_exists($version)) {
    die ("Missing path/to .manual.xml and/or version.xml");
}


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

if(!file_exists("php") || is_file("php")) mkdir("php") or die("Can't create the cache directory");
if(!file_exists("html") || is_file("html")) mkdir("html") or die("Can't create the cache directory");

require "include/PhDReader.class.php";
require "include/PhDHelper.class.php";
require "include/PhDFormat.class.php";
require "formats/xhtml.php";
require "themes/php/phpdotnet.php";
require "themes/php/phpweb.php";
require "themes/php/bightml.php";
require "themes/php/chunkedhtml.php";
require "mktoc.php";

if ($err) {
	$mktoc = microtime(true);
	$notify
		->update("mktoc finished", sprintf("mktoc ran for <b>%d</b> sec", $mktoc-$start))
		->show();
}

$themes = array();
$reader = new PhDReader($manual);
$format = new XHTMLPhDFormat($IDs, $IDMap, "php");
$themes["phpweb"] = new phpweb($IDs, $IDMap, $version);
$themes["bightml"] = new bightml($IDs, $IDMap, $version);
$themes["chunkedhtml"] = new chunkedhtml($IDs, $IDMap, $version);

$formatmap = $format->getMap();
$maps = array();
$textmaps = array();
foreach($themes as $name => $theme) {
    $maps[$name] = $theme->getMap();
    $textmaps[$name] = $theme->getTextMap();
}


while($reader->read()) {
    $nodetype = $reader->nodeType;
    $nodename = $reader->name;

    switch($nodetype) {
    case XMLReader::ELEMENT:
    case XMLReader::END_ELEMENT:
        $open = $nodetype == XMLReader::ELEMENT;

        $funcname = "format_$nodename";
        $skip = array();
        foreach($maps as $theme => $map) {
            if (isset($map[$nodename])) {
                $tag = $map[$nodename];
                if (is_array($tag)) {
                    $tag = $reader->notXPath($tag);
                }
                if ($tag) {
                    if (strncmp($tag, "format_", 7)) {
                        $retval = $themes[$theme]->transformFromMap($open, $tag, $nodename);
                        if ($retval !== false) {
                            $themes[$theme]->appendData($retval, $reader->isChunk);
                            $skip[] = $theme;
                        }
                        continue;
                    }
                    $funcname = $tag;
                    $retval = $themes[$theme]->{$funcname}($open, $nodename, $reader->getAttributes());
                    if ($retval !== false) {
                        $themes[$theme]->appendData($retval, $reader->isChunk);
                        $skip[] = $theme;
                    }
                    continue;
                }
            }
        }

        if (count($skip) < count($themes)) {
            if (isset($formatmap[$nodename])) {
                $tag = $formatmap[$nodename];
                if (is_array($tag)) {
                    $tag = $reader->notXPath($tag);
                }
                if (strncmp($tag, "format_", 7)) {
                    $retval = $format->transformFromMap($open, $tag, $nodename);
                    foreach($themes as $name => $theme) {
                        if (!in_array($name, $skip)) {
                            $theme->appendData($retval, $reader->isChunk);
                        }
                    }
                    break;
                }
                $funcname = $tag;
                $retval = $format->{$funcname}($open, $nodename, $reader->getAttributes());
                foreach($themes as $name => $theme) {
                    if (!in_array($name, $skip)) {
                        $theme->appendData($retval, $reader->isChunk);
                    }
                }
            }
        }
        break;

    case XMLReader::TEXT:
        $skip = array();
        $value = $reader->value;
        $tagname = $reader->getParentTagName();
        foreach($textmaps as $theme => $map) {
            if (isset($map[$tagname])) {
                $tagname = $map[$tagname];
                if (is_array($tagname)) {
                    $tagname = $reader->notXPath($tagname, $reader->depth-1);
                }

                if ($tagname) {
                    $retval = $themes[$theme]->{$tagname}($value, $tagname);
                    if ($retval !== false) {
                        $skip[] = $theme;
                        $themes[$theme]->appendData($retval, false);
                    }
                }
            }
        }
        if (count($skip) < count($themes)) {
            $retval = htmlspecialchars($value, ENT_QUOTES);
            foreach ($themes as $name => $theme) {
                if (!in_array($name, $skip)) {
                    $theme->appendData($retval, false);
                }
            }
        }
        break;

    case XMLReader::CDATA:
        $value = $reader->value;
        $retval = $format->CDATA($value);
        foreach($themes as $name => $theme) {
            $theme->appendData($retval, false);
        }
        break;

    case XMLReader::COMMENT:
    case XMLReader::WHITESPACE:
    case XMLReader::SIGNIFICANT_WHITESPACE:
    case XMLReader::DOC_TYPE:
        /* swallow it */
        continue 2;

    default:
        trigger_error("Don't know how to handle {$nodename} {$nodetype}", E_USER_ERROR);
        return;
    }
}

copy("php/manual.php", "php/index.php");
copy("html/manual.html", "html/index.html");
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

