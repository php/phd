<?php
namespace phpdotnet\phd;
/* $Id$ */

class Config
{
    const VERSION = '1.0.0-dev';

    private static $optionArray = array(
        'output_format'     => array(),
        'chunk_extra'       => array(
            'legalnotice'      => true,
            'phpdoc:exception' => true,
        ),
        'no_index'          => false,
        'force_index'       => false,
        'xml_root'          => '.',
        'xml_file'          => './.manual.xml',
        'lang_dir'          => './',
        'language'          => 'en',
        'verbose'           => VERBOSE_DEFAULT,
        'date_format'       => 'H:i:s',
        'render_ids'        => array(
        ),
        'skip_ids'          => array(
        ),
        'color_output'      => true,
        'output_dir'        => './output/',
        'intermediate_output_dir' => '.',
        'php_error_output'  => STDERR,
        'php_error_color'   => '01;31', // Red
        'user_error_output' => STDERR,
        'user_error_color'  => '01;33', // Yellow
        'phd_info_output'   => STDOUT,
        'phd_info_color'    => '01;32', // Green
        'phd_warning_output' => STDOUT,
        'phd_warning_color' => '01;35', // Magenta
        'highlighter'       => 'phpdotnet\\phd\\Highlighter',
        'package'           => array(
            'Generic',
        ),
        'css'               => array(),
    );

    public static function init(array $a) {
        self::$optionArray = array_merge(self::$optionArray, (array)$a);
    }

    /**
     * Maps static function calls to config option setter/getters.
     *
     * To set an option, call "Config::setOptionname($value)".
     * To retrieve an option, call "Config::optionname()".
     *
     * It is also possible, but deprecated, to call
     * "Config::set_optionname($value)".
     *
     * @param string $name   Name of called function
     * @param array  $params Array of function parameters
     *
     * @return mixed Config value that was requested or set.
     */
    public static function __callStatic($name, $params)
    {
        $name = strtolower($name); // FC if this becomes case-sensitive

        if (strncmp($name, 'set', 3) === 0) {
            $name = substr($name, 3);
            if ($name[0] === '_') {
                $name = substr($name, 1);
            }
            if (strlen($name) < 1 || count($params) !== 1) { // assert
                trigger_error('Misuse of config option setter', E_USER_ERROR);
            }
            self::$optionArray[$name] = $params[0];
            // no return, intentional
        }
        return isset(self::$optionArray[$name])
            ? self::$optionArray[$name]
            : NULL;
    }

    public static function getSupportedPackages() {
        static $packageList = array();
        if (!$packageList) {
            foreach (glob(__DIR__ . "/Package/*", GLOB_ONLYDIR) as $item) {
                $baseitem = basename($item);
                if ($baseitem[0] != '.') {
                    $packageList[] = $baseitem;
                }
            }
        }
        return $packageList;
    }

}

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
