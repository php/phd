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


(@include "./config.php")
    && isset($OPTIONS)
    && is_array($OPTIONS)
    && isset($OPTIONS["output_format"], $OPTIONS["output_theme"])
    && is_array($OPTIONS["output_theme"])
    or die("Invalid configuration/file not found.\nYou need to run setup/setup.php first\n");
require "./include/PhDReader.class.php";
require "./include/PhDHelper.class.php";
require "./include/PhDFormat.class.php";
if ($OPTIONS["index"]) {
    require "./mktoc.php";
    if ($err) {
        $mktoc = microtime(true);
        $notify
            ->update("mktoc finished", sprintf("mktoc ran for <b>%d</b> sec", $mktoc-$start))
            ->show();
    }
} else {
    /* FIXME: Load from sqlite db? */
}

foreach($OPTIONS["output_format"] as $output_format) {
    switch($output_format) {
    case "xhtml":
        $classname = "XHTMLPhDFormat";
        break;
    }

    require "./formats/$output_format.php";
    $format = new $classname($IDs, $IDMap);
    $formatmap = $format->getMap();

    $themes = $elementmaps = $textmaps = array();
foreach($OPTIONS["output_theme"][$output_format] as $theme => $array) {
    is_dir("./themes/$theme") or die("Can't find the '$theme' theme");
    
    /* Maybe other themes will need additional includes? */
    switch($theme) {
    case "php":
        require "./themes/php/phpdotnet.php";
        break;
    }

    foreach($array as $themename) {
        $themename = basename($themename);
        require "./themes/$theme/$themename.php";
        switch($theme) {
            case "php":
                $themes[$themename] = new $themename($IDs, $IDMap, $OPTIONS["xml_root"]."/phpbook/phpbook-xsl/version.xml");
                break;
            default:
                $themes[$themename] = new $themename($IDs, $IDMap);
        }
        $elementmaps[$themename] = $themes[$themename]->getMap();
        $textmaps[$themename]    = $themes[$themename]->getTextMap();
    }
}

if(!file_exists("php") || is_file("php")) mkdir("php") or die("Can't create the cache directory");
if(!file_exists("html") || is_file("html")) mkdir("html") or die("Can't create the cache directory");

$reader = new PhDReader($OPTIONS["xml_root"] . "/.manual.xml");
while($reader->read()) {
    $nodetype = $reader->nodeType;
    $nodename = $reader->name;

    switch($nodetype) {
    case XMLReader::ELEMENT:
    case XMLReader::END_ELEMENT:
        $open = $nodetype == XMLReader::ELEMENT;

        $funcname = "format_$nodename";
        $skip = array();
        foreach($elementmaps as $theme => $map) {
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
} // foreach($OPTIONS["output_thtemes"])
/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

