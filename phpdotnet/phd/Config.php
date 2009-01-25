<?php
/* $Id$ */

require $ROOT . "/include/PhDErrors.php";

define("PHD_VERSION", "phd-from-cvs");

class PhDConfig
{
    private static $optionArray = array(
        'output_format' => array(
            'xhtml',
            'manpage',
            'pdf',
        ),
        'output_theme' => array(
            'xhtml' => array(
                'php' => array(
                    'phpweb',
                    'chunkedhtml',
                    'bightml',
                    'chmsource',
                ),
            ),
            'manpage' => array(
                'php' => array(
                    'phpfunctions',
                ),
            ),
            'pdf' => array(
                'php' => array(
                 ),
            ),
        ),
        'chunk_extra' => array(
            "legalnotice" => true,
            "phpdoc:exception" => true,
        ),
        'index' => true,
        'xml_root' => '.',
        'xml_file' => './.manual.xml',
        'language' => 'en',
        'verbose' => VERBOSE_DEFAULT,
        'date_format' => "H:i:s",
        'render_ids' => array(
        ),
        'skip_ids' => array(
        ),
        'color_output' => false,
        'output_dir' => '.',
        'php_error_output' => NULL,
        'php_error_color' => false,
        'user_error_output' => NULL,
        'user_error_color' => false,
        'phd_info_output' => NULL,
        'phd_info_color' => false,
        'highlighter'    => 'PhDHighlighter',
    );

    public static function __callStatic($name, $params)
    {
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

PhDConfig::set_php_error_output(STDERR);
PhDConfig::set_user_error_output(STDERR);
PhDConfig::set_phd_info_output(STDOUT);

/* {{{ Workaround/fix for Windows prior to PHP5.3 */
if (!function_exists('getopt')) {
    //Use PEAR's PHP_Compat package
    @include_once('PHP/Compat/Function/getopt.php');
}
if (!function_exists('getopt')) {
    function getopt($short, $long) {
        global $argv;
        printf("I'm sorry, you are running an operating system that does not support getopt()\n");
        printf("Please install PEAR's PHP_Compat package.");

        return array();
    }
}
/* }}} */

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

abstract class PhDOptionParser
{
    abstract public function getOptionList();

    public function handlerForOption($opt)
    {
        if (method_exists($this, "option_{$opt}")) {
            return array($this, "option_{$opt}");
        } else {
            return NULL;
        }
    }

    public function getopt()
    {
        $opts = $this->getOptionList();
        $args = getopt(implode("", array_values($opts)), array_keys($opts));
        if ($args === false) {
            trigger_error("Something happend with getopt(), please report a bug", E_USER_ERROR);
        }

        foreach ($args as $k => $v) {
            $handler = $this->handlerForOption($k);
            if (is_callable($handler)) {
                call_user_func($handler, $k, $v);
            } else {
                var_dump($k, $v);
                trigger_error("Hmh, something weird has happend, I don't know this option", E_USER_ERROR);
            }
        }
    }
}

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
