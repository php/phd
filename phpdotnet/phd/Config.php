<?php
/* $Id$ */

/* {{{ Print messages to stderr: v("printf-format-text", $arg1, ...) */
function v($msg) {
    $args = func_get_args();
    $args[0] = date($GLOBALS["OPTIONS"]["date_format"]);
    vfprintf(STDERR, "[%s] $msg", $args);
}
/* }}} */

define('VERBOSE_INDEXING',               0x01);
define('VERBOSE_FORMAT_RENDERING',       0x02);
define('VERBOSE_THEME_RENDERING',        0x04);
define('VERBOSE_RENDER_STYLE',           0x08);
define('VERBOSE_PARTIAL_READING',        0x10);
define('VERBOSE_PARTIAL_CHILD_READING',  0x20);
define('VERBOSE_TOC_WRITING',            0x40);
define('VERBOSE_CHUNK_WRITING',          0x80);

define('VERBOSE_ALL',                    0xFF);

define("PHD_VERSION", "0.2.3");

/* {{{ Default $OPTIONS */
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
  'verbose' => VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING),
  'date_format' => "H:i:s",
  'render_ids' => array(
  ),
  'skip_ids' => array(
  ),
  'output_dir' => '.',
);
/* }}} */


/* {{{ getopt() options */
$opts = array(
    "format:"  => "f:", // The format to render (xhtml, pdf...)
    "theme:"   => "t:", // The theme to render (phpweb, bightml..)
    "index:"   => "i:", // Re-index or load from cache
    "docbook:" => "d:", // The Docbook XML file to render from (.manual.xml)
    "output:"  => "o:", // The output directory
    "partial:" => "p:", // The ID to render (optionally ignoring its children)
    "skip:"    => "s:", // The ID to skip (optionally skipping its children too)
    "verbose:" => "v",  // Adjust the verbosity level
    "list::"   => "l::", // List supported themes/formats
    "version"  => "V",  // Print out version information
    "help"     => "h",  // Print out help
);
/* }}} */

/* {{{ Workaround/fix for Windows prior to PHP5.3 */
if (!function_exists("getopt")) {
    function getopt($short, $long) {
        v("I'm sorry, you are running an operating system that does not support getopt()\n");
        v("Please either upgrade to PHP5.3 or try '%s /path/to/your/docbook.xml'\n", $argv[0]);

        return array();
    }
}
/* }}} */

$args = getopt(implode("", array_values($opts)), array_keys($opts));
if($args === false) {
    v("Something happend with getopt(), please report a bug\n");
    exit(1);
}

$verbose = 0;
$docbook = false;

foreach($args as $k => $v) {
    switch($k) {
    /* {{{ Docbook file */
    case "d":
    case "docbook":
        if (is_array($v)) {
            v("Can only parse one file at a time\n");
            exit(1);
        }
        if (!file_exists($v) || is_dir($v) || !is_readable($v)) {
            v("'%s' is not a readable docbook file\n", $v);
            exit(1);
        }
        $OPTIONS["xml_root"] = dirname($v);
        $OPTIONS["xml_file"] = $v;
        $docbook = true;
        break;
    /* }}} */

    /* {{{ Output location */
    case "o":
    case "output":
        if (is_array($v)) {
            v("Only a single location can be supplied\n");
            exit(1);
        }
        @mkdir($v, 0777, true);
        if (!is_dir($v) || !is_readable($v)) {
            v("'%s' is not a valid directory\n", $v);
            exit(1);
        }
        $OPTIONS["output_dir"] = $v;
        break;
    /* }}} */

    /* {{{ Build format */
    case "f":
    case "format":
        if ($v != "xhtml") {
            v("Only xhtml is supported at this time\n");
            exit(1);
        }
        break;
    /* }}} */

    /* {{{ Run indexer or not */
    case "i":
    case "index":
        if (is_array($v)) {
            v("You cannot pass %s more than once\n", $k);
            exit(1);
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
            v("yes/no || true/false || 1/0 expected\n");
            exit(1);
        }
        break;
    /* }}} */

    /* {{{ Print out a list of formats/themes */
    case "l":
    case "list":
        /* FIXME: This list should be created dynamically */
        foreach((array)$v as $val) {
            switch($val) {
            case "f":
            case "format":
            case "formats":
                echo "Supported formats:\n";
                echo "\txhtml\n";
                break;

            case "t":
            case "theme":
            case "themes":
                echo "Supported themes:\n";
                echo "\tphpweb\n";
                echo "\tchunkedhtml\n";
                echo "\tbightml\n";
                break;

            default:
                echo "Unknown list type '$val'\n";
                /* break omitted intentionally */

            case false:
                echo "Supported formats:\n";
                echo "\txhtml\n";
                echo "Supported themes:\n";
                echo "\tphpweb\n";
                echo "\tchunkedhtml\n";
                echo "\tbightml\n";
                break;
            }
        }
        exit(0);
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

    /* {{{ Skip list */
    case "s":
    case "skip":
        foreach((array)$v as $i => $val) {
            $recursive = true;
            if (strpos($val, "=") !== false) {
                list($val, $recursive) = explode("=", $val);

                if (!is_numeric($recursive) && defined($recursive)) {
                    $recursive = constant($recursive);
                }
                $recursive = (bool) $recursive;
            }
            $OPTIONS["skip_ids"][$val] = $recursive;
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
                v("Unknown theme '%s'\n", $val);
                exit(1);
            }
        }
        break;
    /* }}} */

    /* {{{ Verbosity level */
    case "verbose":
        foreach((array)$v as $i => $val) {
            foreach(explode("|", $val) as $const) {
                if (defined($const)) {
                    $verbose |= (int)constant($const);
                } elseif (is_numeric($const)) {
                    $verbose |= (int)$const;
                } else {
                    v("Unknown option passed to --$k, $const\n");
                }
            }
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
    
    /* {{{ Version info */
    case "V":
    case "version":
        v("PhD version: %s\n", PHD_VERSION);
        v("Copyright (c) 2008 The PHP Documentation Group\n");
        exit(0);
    /* }}} */

    /* {{{ Help/usage info */
    case "usage":
    case "help":
    case "h":
        echo "PhD version: " .PHD_VERSION;
        echo "\nCopyright (c) 2008 The PHP Documentation Group\n
  -v
  --verbose <int>            Adjusts the verbosity level
  -f <formatname>
  --format <formatname>      The build format to use
  -t <themename>
  --theme <themename>        The theme to use
  -i <bool>
  --index <bool>             Index before rendering (default) or load from cache (false)
  -d <filename>
  --docbook <filename>       The Docbook file to render from
  -p <id[=bool]>
  --partial <id[=bool]>      The ID to render, optionally skipping its children chunks (default to true; render children)
  -s <id[=bool]>
  --skip <id[=bool]>         The ID to skip, optionally skipping its children chunks (default to true; skip children)
  -l <formats/themes>
  --list <formats/themes>    Print out the supported formats/themes (default: both)
  -o <directory>
  --output <directory>       The output directory (default: .)
  -V
  --version                  Print the PhD version information
  -h
  --help                     This help

Most options can be passed multiple times for greater affect.
NOTE: Long options are only supported using PHP5.3\n";
        exit(0);
        break;
    /* }}} */

    /* {{{ Unsupported option this should *never* happen */
    default:
        v("Hmh, something weird has happend, I don't know this option");
        var_dump($k, $v);
        exit(1);
    /* }}} */
    }
}


/* {{{ BC for PhD 0.0.* (and PHP5.2 on Windows)
       i.e. `phd path/to/.manual.xml */
if (!$docbook && $argc > 1) {
    $arg = $argv[$argc-1];
    if (is_dir($arg)) {
        $OPTIONS["xml_root"] = $arg;
        $OPTIONS["xml_file"] = $arg . DIRECTORY_SEPARATOR . ".manual.xml";
    } elseif (is_file($arg)) {
        $OPTIONS["xml_root"] = dirname($arg);
        $OPTIONS["xml_file"] = $arg;
    }
}
/* }}} */

/* {{{ If no docbook file was passed, ask for it
       This loop should be removed in PhD 0.3.0, and replaced with a fatal errormsg */
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
/* }}} */

/* This needs to be done in *all* cases! */
$OPTIONS["output_dir"] = realpath($OPTIONS["output_dir"]) . DIRECTORY_SEPARATOR;

$OPTIONS["version_info"] = $OPTIONS["xml_root"]."/phpbook/phpbook-xsl/version.xml";
$OPTIONS["acronyms_file"] = $OPTIONS["xml_root"]."/entities/acronyms.xml";

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
