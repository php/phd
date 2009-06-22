<?php
namespace phpdotnet\phd;
/* $Id$ */

define("PHD_VERSION", "phd-from-cvs");

class Config
{
    private static $optionArray = array(
        'output_format' => array(
            'xhtml',
            'php',
            'bigxhtml',
            'manpage',
            'pdf',
        ),
        'chunk_extra' => array(
            "legalnotice" => true,
            "phpdoc:exception" => true,
        ),
        'index' => true,
        'xml_root' => '.',
        'xml_file' => "./.manual.xml",
        'lang_dir' => './',
        'language' => 'en',
        'verbose' => VERBOSE_DEFAULT,
        'date_format' => "H:i:s",
        'render_ids' => array(
        ),
        'skip_ids' => array(
        ),
        'color_output' => false,
        'output_dir' => './output/',
        'intermediate_output_dir' => '.',
        'php_error_output' => NULL,
        'php_error_color' => false,
        'user_error_output' => NULL,
        'user_error_color' => false,
        'phd_info_output' => NULL,
        'phd_info_color' => false,
        'highlighter'    => "phpdotnet\\phd\\Highlighter",
        'package' => 'PHP',
    );

    public static function init(array $a) {
        self::$optionArray = array_merge(self::$optionArray, (array)$a);
    }

    public static function __callStatic($name, $params) {
        $name = strtolower($name); // FC if this becomes case-sensitive
        if (strncmp($name, 'set', 3) === 0) {
            $name = substr($name, 3);
            if ($name[0] === '_') {
                $name = substr($name, 1);
            }
            if (strlen($name) < 1 || count($params) !== 1) { // assert
                trigger_error("Misuse of config option setter", E_USER_ERROR);
            }
            self::$optionArray[$name] = $params[0];
            // no return, intentional
        }
        return isset(self::$optionArray[$name]) ? self::$optionArray[$name] : NULL;
    }
}

Config::set_php_error_output(STDERR);
Config::set_user_error_output(STDERR);
Config::set_phd_info_output(STDOUT);

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

/* {{{ Can't function_call()['key'], so val(function_call(), 'key') */
function val($a, $k)
{
    return $a[$k];
}
/* }}} */

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
