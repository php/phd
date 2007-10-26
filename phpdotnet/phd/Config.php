<?php
/* $Id$ */

function v($msg) {
    $args = func_get_args();
    $args[0] = date($GLOBALS["OPTIONS"]["date_format"]);
    vfprintf(STDERR, "[%s] $msg", $args);
}

define('VERBOSE_INDEXING',               0x01);
define('VERBOSE_FORMAT_RENDERING',       0x02);
define('VERBOSE_THEME_RENDERING',        0x04);
define('VERBOSE_RENDER_STYLE',           0x08);
define('VERBOSE_PARTIAL_READING',        0x10);
define('VERBOSE_PARTIAL_CHILD_READING',  0x20);
define('VERBOSE_TOC_WRITING',            0x40);
define('VERBOSE_CHUNK_WRITING',          0x80);

define('VERBOSE_ALL',VERBOSE_INDEXING|VERBOSE_FORMAT_RENDERING|VERBOSE_THEME_RENDERING|VERBOSE_RENDER_STYLE|VERBOSE_PARTIAL_READING|VERBOSE_PARTIAL_CHILD_READING|VERBOSE_TOC_WRITING|VERBOSE_CHUNK_WRITING);


$OPTIONS = array (
  'output_format' => array('xhtml'),
  'output_theme' => array(
    'xhtml' => array(
      'php' => array(
        'phpweb',
        'chunkedhtml',
        'bightml',
      ),
    ),
  ),
  'chunk_extra' => array(
    "legalnotice" => true,
  ),
  'index' => true,
  'xml_root' => '.',
  'xml_file' => './.manual.xml',
  'language' => 'en',
  'fallback_language' => 'en',
  'enforce_revisions' => false,
  'compatibility_mode' => true,
  'build_log_file' => 'none',
  'debug' => true,
  'verbose' => VERBOSE_INDEXING|VERBOSE_FORMAT_RENDERING|VERBOSE_THEME_RENDERING|VERBOSE_RENDER_STYLE|VERBOSE_PARTIAL_READING|VERBOSE_TOC_WRITING,
  'date_format' => "H:i:s",
  'render_ids' => array(
  ),
);


$short = "vf:t:i:d:p:";
$long = array(
    "The build format to use"       => "format:",
    "The output theme to use"       => "theme:",
    "Regenerate the index or not"   => "index:",
    "The file to render from"       => "docbook:",
    "The IDs to render"             => "partial:",
    "Adjust the verbosity level"    => "verbose:",
);

$verbose = 0;
$args = getopt($short, $long);
foreach($args as $k => $v) {
    switch($k) {
    /* {{{ Docbook file */
    case "d":
    case "docbook":
        if (is_array($v)) {
            v("Can only parse one file at a time");
            exit(-1);
        }
        if (!file_exists($v) || is_dir($v) || !is_readable($v)) {
            v("'%s' is not a readable docbook file", $v);
            exit(-1);
        }
        $OPTIONS["xml_root"] = dirname($v);
        $OPTIONS["xml_file"] = $v;
        break;
    /* }}} */

    /* {{{ Build format */
    case "f":
    case "format":
        if ($v != "xhtml") {
            v("Only xhtml is supported at this time");
            exit(-1);
        }
        break;
    /* }}} */

    /* {{{ Run indexer or not */
    case "i":
    case "index":
        if (is_array($v)) {
            v("You cannot pass %s more than once", $k);
            exit(-1);
        }
        switch ($v) {
        case "yes":
        case "true":
        case "1":
            $OPTIONS["index"] = true;
            break;
        case "no":
        case "false":
        case "0":
            $OPTIONS["index"] = false;
            break;
        default:
            v("yes/no || true/false || 1/0 expected");
            exit(-1);
        }
        break;
    /* }}} */

    /* {{{ Partial rendering */
    case "p":
    case "partial":
        foreach((array)$v as $i => $val) {
            $recursive = true;
            if (strpos($val, "=") !== false) {
                list($val, $recursive) = explode("=", $val);

                if (!is_numeric($recursive) && defined($recursive)) {
                    $recursive = constant($recursive);
                }
                $recursive = (bool) $recursive;
            }
            $OPTIONS["render_ids"][$val] = $recursive;
        }
        break;
    /* }}} */

    /* {{{ Output themes */
    case "t":
    case "theme":
        /* Remove the default themes */
        $OPTIONS["output_theme"]["xhtml"]["php"] = array();

        foreach((array)$v as $i => $val) {
            switch($val) {
            case "phpweb":
            case "chunkedhtml":
            case "bightml":
                if (!in_array($val, $OPTIONS["output_theme"]["xhtml"]["php"])) {
                    $OPTIONS["output_theme"]["xhtml"]["php"][] = $val;
                }
                break;
            default:
                v("Unkown theme '%s'", $val);
                exit(-1);
            }
        }
        break;
    /* }}} */

    /* {{{ Verbosity level */
    case "verbose":
        if (is_array($v)) {
            foreach($v as $i => $val) {
                $verbose |= (int)$val;
            }
        } else {
            $verbose |= (int)$v;
        }
        $OPTIONS["verbose"] = $verbose;
        break;

    case "v":
        if (is_array($v)) {
            foreach($v as $i => $val) {
                $verbose |= pow(2, $i);
            }
        } else {
            $verbose |= 1;
        }
        $OPTIONS["verbose"] = $verbose;
        break;
    /* }}} */

    default:
        v("Hmh, something weird has happend, I don't know this option");
        var_dump($k, $v);
        exit(-1);
    }
}


if ($argc == 2) {
    $OPTIONS["xml_root"] = $argv[1];
} elseif (is_file($argv[$argc-1]) && $OPTIONS["xml_file"] != $argv[$argc-1]) {
    $OPTIONS["xml_file"] = $argv[$argc-1];
    $OPTIONS["xml_root"] = dirname($argv[$argc-1]);
}

while (!is_dir($OPTIONS["xml_root"]) || !is_file($OPTIONS["xml_file"])) {
    print "I need to know where you keep your '.manual.xml' file (I didn't find it in " . $OPTIONS["xml_root"] . "): ";
    $root = trim(fgets(STDIN));
    if (is_file($root)) {
        $OPTIONS["xml_file"] = $root;
        $OPTIONS["xml_root"] = dirname($root);
    } else {
        $OPTIONS["xml_file"] = $root . "/.manual.xml";
        $OPTIONS["xml_root"] = $root;
    }
}

$OPTIONS["version_info"] = $OPTIONS["xml_root"]."/phpbook/phpbook-xsl/version.xml";
$OPTIONS["acronyms_file"] = $OPTIONS["xml_root"]."/entities/acronyms.xml";

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

