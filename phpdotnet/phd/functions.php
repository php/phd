<?php
/* $Id$ */

/* {{{ PhD error & message handler */

$error_map = array(
    E_RECOVERABLE_ERROR             => 'PHP Error',
    E_WARNING                       => 'PHP Warning',
    E_NOTICE                        => 'PHP Notice',
    E_STRICT                        => 'PHP Strict Standards Warning',
    E_DEPRECATED                    => 'PHP Deprecated Construct Warning',
    E_USER_ERROR                    => 'User Error',
    E_USER_WARNING                  => 'User Warning',
    E_USER_NOTICE                   => 'User Notice',
);

function define_error($name, $explanation) {
    static $lastErrorValue = E_DEPRECATED;

    define($name, $lastErrorValue <<= 1);
    $GLOBALS['error_map'][$lastErrorValue] = $explanation;
}

// PhD verbose flags
define_error('VERBOSE_INDEXING',               'PhD Indexer');
define_error('VERBOSE_FORMAT_RENDERING',       'PhD Output Format');
define_error('VERBOSE_THEME_RENDERING',        'PhD Output Theme');
define_error('VERBOSE_RENDER_STYLE',           'PhD Rendering Style');
define_error('VERBOSE_PARTIAL_READING',        'PhD Partial Reader');
define_error('VERBOSE_PARTIAL_CHILD_READING',  'PhD Partial Child Reader');
define_error('VERBOSE_TOC_WRITING',            'PhD TOC Writer');
define_error('VERBOSE_CHUNK_WRITING',          'PhD Chunk Writer');
define_error('VERBOSE_NOVERSION',              'Missing Version Information');
define_error('VERBOSE_DONE',                   'PhD Processing Completion');

define('VERBOSE_ALL',                          (VERBOSE_DONE            << 1)-1);
define('VERBOSE_DEFAULT',                      (VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING|VERBOSE_NOVERSION|VERBOSE_DONE)));

$olderrrep = error_reporting();
error_reporting($olderrrep | VERBOSE_DEFAULT);

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

/* {{{ Function to get a color escape sequence */
function term_color($text, $color)
{
    return $color === false ? $text : "\033[" . $color . "m" . $text . "\033[m";
}
/* }}} */

/* {{{ The PhD errorhandler */
function errh($errno, $msg, $file, $line, $ctx = null) {
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

    $time = date(PhDConfig::date_format());
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
        case VERBOSE_DONE:
            $color = PhDConfig::phd_info_color();
            $output = PhDConfig::phd_info_output();
            $data = $msg;
            break;

        // User triggered errors
        case E_USER_ERROR:
        case E_USER_WARNING:
        case E_USER_NOTICE:
            $color = PhDConfig::user_error_color();
            $output = PhDConfig::user_error_output();
            $data = sprintf("%s:%d\n\t%s", $file, $line, $msg);
            break;

        // PHP triggered errors
        case E_DEPRECATED:
        case E_RECOVERABLE_ERROR:
        case E_STRICT:
        case E_WARNING:
        case E_NOTICE:
            $color = PhDConfig::php_error_color();
            $output = PhDConfig::php_error_output();
            $data = sprintf("%s:%d\n\t%s", $file, $line, $msg);
            break;

        default:
            $recursive = false;
            return false;
    }

    $timestamp = term_color(sprintf("[%s - %s]", $time, $GLOBALS['error_map'][$errno]), $color);
    fprintf($output, "%s %s\n", $timestamp, $data);

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

?>
