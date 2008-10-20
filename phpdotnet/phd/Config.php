<?php
/* $Id$ */

/* {{{ PhD error & message handler */

// FC For PHP5.3
if (!defined("E_DEPRECATED")) {
    define("E_DEPRECATED",               E_RECOVERABLE_ERROR           << 1);
}

// PhD verbose flags
define('VERBOSE_INDEXING',               E_DEPRECATED                  << 1);
define('VERBOSE_FORMAT_RENDERING',       VERBOSE_INDEXING              << 1);
define('VERBOSE_THEME_RENDERING',        VERBOSE_FORMAT_RENDERING      << 1);
define('VERBOSE_RENDER_STYLE',           VERBOSE_THEME_RENDERING       << 1);
define('VERBOSE_PARTIAL_READING',        VERBOSE_RENDER_STYLE          << 1);
define('VERBOSE_PARTIAL_CHILD_READING',  VERBOSE_PARTIAL_READING       << 1);
define('VERBOSE_TOC_WRITING',            VERBOSE_PARTIAL_CHILD_READING << 1);
define('VERBOSE_CHUNK_WRITING',          VERBOSE_TOC_WRITING           << 1);
define('VERBOSE_NOVERSION',              VERBOSE_CHUNK_WRITING         << 1);

define('VERBOSE_ALL',                    (VERBOSE_NOVERSION        << 1)-1);


/* {{{ Print info messages: v("printf-format-text" [, $arg1, ...], $verbose-level) */
// trigger_error() only accepts E_USER_* errors :(
function v($msg, $errno) {
    $args = func_get_args();
    $errno = array_pop($args);

    $msg = vsprintf(array_shift($args), $args);

    $bt = debug_backtrace();
    return errh($errno, $msg, $bt[0]["file"], $bt[0]["line"]);
}
/* }}} */

/* {{{ The PhD errorhandler */
function errh($errno, $msg, $file, $line, $ctx = null) {
    global $OPTIONS;
    static $err = array(
        E_DEPRECATED                  => 'E_DEPRECATED',
        E_RECOVERABLE_ERROR           => 'E_RECOVERABLE_ERROR',
        E_STRICT                      => 'E_STRICT',
        E_WARNING                     => 'E_WARNING',
        E_NOTICE                      => 'E_NOTICE',

        E_USER_ERROR                  => 'E_USER_ERROR',
        E_USER_WARNING                => 'E_USER_WARNING',
        E_USER_NOTICE                 => 'E_USER_NOTICE',

        VERBOSE_INDEXING              => 'VERBOSE_INDEXING',
        VERBOSE_FORMAT_RENDERING      => 'VERBOSE_FORMAT_RENDERING',
        VERBOSE_THEME_RENDERING       => 'VERBOSE_THEME_RENDERING',
        VERBOSE_RENDER_STYLE          => 'VERBOSE_RENDER_STYLE',
        VERBOSE_PARTIAL_READING       => 'VERBOSE_PARTIAL_READING',
        VERBOSE_PARTIAL_CHILD_READING => 'VERBOSE_PARTIAL_CHILD_READING',
        VERBOSE_TOC_WRITING           => 'VERBOSE_TOC_WRITING',
        VERBOSE_CHUNK_WRITING         => 'VERBOSE_CHUNK_WRITING',
        VERBOSE_NOVERSION             => 'VERBOSE_NOVERSION',
    );
    static $recursive = false;

    // Respect the error_reporting setting
    if (!(error_reporting() & $errno)) {
        return false;
    }

    // Recursive protection
    if ($recursive) {
        // Fallback to the default errorhandler
        return false;
    }
    $recursive = true;

    $time = date($OPTIONS["date_format"]);
    switch($errno) {
    case VERBOSE_INDEXING:
    case VERBOSE_FORMAT_RENDERING:
    case VERBOSE_THEME_RENDERING:
    case VERBOSE_RENDER_STYLE:
    case VERBOSE_PARTIAL_READING:
    case VERBOSE_PARTIAL_CHILD_READING:
    case VERBOSE_TOC_WRITING:
    case VERBOSE_CHUNK_WRITING:
    case VERBOSE_NOVERSION:
        $cl1 = $OPTIONS['color_output'] ? "\033[01;{$OPTIONS['color_output']}m" : '';
        $cr = $OPTIONS['color_output'] ? "\033[m" : '';
        fprintf($OPTIONS["phd_info_output"], "{$cl1}[%s - %s]{$cr} %s\n", $time, $err[$errno], $msg);
        break;

    // User triggered errors
    case E_USER_ERROR:
    case E_USER_WARNING:
    case E_USER_NOTICE:
        fprintf($OPTIONS["user_error_output"], "[%s - %s] %s:%d\n\t%s\n", $time, $err[$errno], $file, $line, $msg);
        break;

    // PHP triggered errors
    case E_DEPRECATED:
    case E_RECOVERABLE_ERROR:
    case E_STRICT:
    case E_WARNING:
    case E_NOTICE:
        fprintf($OPTIONS["php_error_output"], "[%s - %s] %s:%d\n\t%s\n", $time, $err[$errno], $file, $line, $msg);
        break;

    default:
        // Unknown error level.. let PHP handle it
        $recursive = false;
        return false;
    }

    // Abort on fatal errors
    if ($errno & (E_USER_ERROR|E_RECOVERABLE_ERROR)) {
        exit(1);
    }

    $recursive = false;
    return true;
}
/* }}} */
set_error_handler("errh");
/* }}} */

define("PHD_VERSION", "0.4.1-dev");

/* {{{ Default $OPTIONS */
$OPTIONS = array (
  'output_format' => array('xhtml', /* 'manpage', 'pdf' */ ),
  'output_theme' => array(
    'xhtml' => array(
      'php' => array(
        'phpweb',
        'chunkedhtml',
        'bightml',
        'chmsource',
        /* 'howto', */
        /* 'phpkdevelop', */
      ),
    ),
    'manpage' => array(
      'php' => array(
        /* 'phpfunctions', */
      ),
    ),
    'pdf' => array(
      'php' => array(
        /* 'phppdf', 'phpbigpdf' */
      )
    )
  ),
  'chunk_extra' => array(
    "legalnotice" => true,
    "phpdoc:exceptionref" => true,
    "phpdoc:classref" => true,
    "phpdoc:varentry" => true,
  ),
  'index' => true,
  'xml_root' => '.',
  'xml_file' => './.manual.xml',
  'language' => 'en',
  'fallback_language' => 'en',
  'enforce_revisions' => false,
  'compatibility_mode' => true,
  'build_log_file' => 'none',
  'verbose' => VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING|VERBOSE_NOVERSION),
  'date_format' => "H:i:s",
  'render_ids' => array(
  ),
  'skip_ids' => array(
  ),
  'color_output' => false,
  'output_dir' => '.',
  'php_error_output' => STDERR,
  'user_error_output' => STDERR,
  'phd_info_output' => STDOUT,
);
/* }}} */

$olderr = error_reporting();
error_reporting($olderr | $OPTIONS["verbose"]);

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
    "color::"  => "c::", // Use color output if possible
    "version"  => "V",  // Print out version information
    "help"     => "h",  // Print out help
);
/* }}} */

/* {{{ Workaround/fix for Windows prior to PHP5.3 */
if (!function_exists("getopt")) {
    function getopt($short, $long) {
        global $argv;
        printf("I'm sorry, you are running an operating system that does not support getopt()\n");
        printf("Please either upgrade to PHP5.3 or try '%s /path/to/your/docbook.xml'\n", $argv[0]);

        return array();
    }
}
/* }}} */

$args = getopt(implode("", array_values($opts)), array_keys($opts));
if($args === false) {
    trigger_error("Something happend with getopt(), please report a bug", E_USER_ERROR);
}

$verbose = 0;
$docbook = false;

/* {{{ phd_bool($var) Returns boolean true/false on success, null on failure */
function phd_bool($val) {
    if (!is_string($val)) {
        return null;
    }

    switch ($val) {
    case "on":
    case "yes":
    case "true":
    case "1":
        return true;
        break;

    case "off":
    case "no":
    case "false":
    case "0":
        return false;
        break;

    default:
        return null;
    }
}
/* }}} */

foreach($args as $k => $v) {
    switch($k) {
    /* {{{ Docbook file */
    case "d":
    case "docbook":
        if (is_array($v)) {
            trigger_error("Can only parse one file at a time", E_USER_ERROR);
        }
        if (!file_exists($v) || is_dir($v) || !is_readable($v)) {
            trigger_error(sprintf("'%s' is not a readable docbook file", $v), E_USER_ERROR);
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
            trigger_error("Only a single output location can be supplied", E_USER_ERROR);
        }
        @mkdir($v, 0777, true);
        if (!is_dir($v) || !is_readable($v)) {
            trigger_error(sprintf("'%s' is not a valid directory", $v), E_USER_ERROR);
        }
        $OPTIONS["output_dir"] = $v;
        break;
    /* }}} */

    /* {{{ Build format */
    case "f":
    case "format":
        $formats = array();
        foreach((array)$v as $i => $val) {
            switch($val) {
                case "xhtml":
                case "manpage":
                case "pdf":
                    if (!in_array($val, $formats)) {
                        $formats[] = $val;
                    }
                    break;
                default:
                    trigger_error("Only xhtml, pdf and manpage are supported at this time", E_USER_ERROR);
            }
        }
        $OPTIONS["output_format"] = $formats;
        break;
    /* }}} */

    /* {{{ Run indexer or not */
    case "i":
    case "index":
        if (is_array($v)) {
            trigger_error(sprintf("You cannot pass %s more than once", $k), E_USER_ERROR);
        }
        $val = phd_bool($v);
        if (is_bool($val)) {
            $OPTIONS["index"] = $val;
        } else {
            trigger_error("yes/no || on/off || true/false || 1/0 expected", E_USER_ERROR);
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
                echo "\tmanpage\n";
                echo "\tpdf:\n";
                break;

            case "t":
            case "theme":
            case "themes":
                echo "Supported themes:\n";
                echo "\txhtml:\n";
                echo "\t\tphpweb\n";
                echo "\t\tchunkedhtml\n";
                echo "\t\tbightml\n";
                echo "\t\tchmsource\n";
                echo "\t\tphpkdevelop\n";
                echo "\t\thowto\n";
                echo "\t\tpearweb\n";
                echo "\t\tpearbightml\n";
                echo "\t\tpearchunkedhtml\n";
                echo "\t\tpearchm\n";
                echo "\tmanpage:\n";
                echo "\t\tphpfunctions\n";
                echo "\tpdf:\n";
                echo "\t\tphppdf\n";
                echo "\t\tphpbigpdf\n";
                break;

            default:
                echo "Unknown list type '$val'\n";
                /* break omitted intentionally */

            case false:
                echo "Supported formats:\n";
                echo "\txhtml\n";
                echo "\tmanpage\n";
                echo "\tpdf\n";
                echo "Supported themes:\n";
                echo "\txhtml:\n";
                echo "\t\tphpweb\n";
                echo "\t\tchunkedhtml\n";
                echo "\t\tbightml\n";
                echo "\t\tchmsource\n";
                echo "\t\tphpkdevelop\n";
                echo "\t\thowto\n";
                echo "\t\tpearweb\n";
                echo "\t\tpearbightml\n";
                echo "\t\tpearchunkedhtml\n";
                echo "\t\tpearchm\n";
                echo "\tmanpage:\n";
                echo "\t\tphpfunctions\n";
                echo "\tpdf:\n";
                echo "\t\tphppdf\n";
                echo "\t\tphpbigpdf\n";
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
        $OPTIONS["output_theme"]["manpage"]["php"] = array();
        $OPTIONS["output_theme"]["xhtml"]["pear"] = array();
        foreach((array)$v as $i => $val) {
            switch($val) {
            case "phpweb":
            case "chunkedhtml":
            case "bightml":
            case "chmsource":
            case "phpkdevelop":
            case "howto":
                if (!in_array($val, $OPTIONS["output_theme"]["xhtml"]["php"])) {
                    $OPTIONS["output_theme"]["xhtml"]["php"][] = $val;
                }
                break;
            case "phpfunctions":
                if (!in_array($val, $OPTIONS["output_theme"]["manpage"]["php"])) {
                    $OPTIONS["output_theme"]["manpage"]["php"][] = $val;
                }
                break;
            case "phppdf":
            case "phpbigpdf":
                if (!in_array($val, $OPTIONS["output_theme"]["pdf"]["php"])) {
                    $OPTIONS["output_theme"]["pdf"]["php"][] = $val;
                }
                break;
            case "pear":
                $OPTIONS["output_theme"]["xhtml"]["pear"] = array(
                    'pearweb',
                    'pearchunkedhtml',
                    'pearbightml',
                    'pearchm'
                );
                break;
            case "pearweb":
            case "pearchunkedhtml":
            case "pearbightml":
            case "pearchm":
                if (!in_array($val, $OPTIONS["output_theme"]["xhtml"]["pear"])) {
                    $OPTIONS["output_theme"]["xhtml"]["pear"][] = $val;
                }
                break;
            default:
                trigger_error(sprintf("Unknown theme '%s'", $val), E_USER_ERROR);
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
                    trigger_error("Unknown option passed to --$k, $const", E_USER_ERROR);
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

    /* {{{ Color output */
    case "c":
    case "color":
        if (is_array($v)) {
            trigger_error(sprintf("You cannot pass %s more than once", $k), E_USER_ERROR);
        }
        if (function_exists('posix_isatty') && !posix_isatty($OPTIONS['phd_info_output'])) {
            // Terminal doesn't support colors
            break;
        }

        $bool = phd_bool($v);
        // No option or color specified
        if ($v === false || ($bool === true && $v != 1)) {
            // Fallback to default
            $OPTIONS["color_output"] = 34;
        } elseif ($bool === false) {
            // Explicitly turning coloring off
            $OPTIONS["color_output"] = false;
        } elseif (is_numeric($v)) {
            // Color specified
            $OPTIONS["color_output"] = (int)$v;
        } else {
            trigger_error("yes/no || on/off || true/false || 1/0 expected", E_USER_ERROR);
        }
        break;
    /* }}} */

    /* {{{ Version info */
    case "V":
    case "version":
        printf("PhD version: %s\n", PHD_VERSION);
        printf("Copyright (c) 2008 The PHP Documentation Group\n");
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
  -c <color>
  --color <color>            Enable color output when output is to a terminal, optionally specify numerical color value (default: false)
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
        var_dump($k, $v);
        trigger_error("Hmh, something weird has happend, I don't know this option", E_USER_ERROR);
    /* }}} */
    }
}

error_reporting($olderr | $OPTIONS["verbose"]);

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

// Those files really should be moved into phd/
$OPTIONS["version_info"] = $OPTIONS["xml_root"]."/phpbook/phpbook-xsl/version.xml";
$OPTIONS["acronyms_file"] = $OPTIONS["xml_root"]."/entities/acronyms.xml";

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
