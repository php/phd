<?php
namespace phpdotnet\phd;
/* $Id$ */

if (!defined("__INSTALLDIR__")) {
    define("__INSTALLDIR__", "@php_dir@" == "@"."php_dir@" ? dirname(dirname(__DIR__)) : "@php_dir@");
}

class Config
{
    const VERSION = 'phd-from-svn';

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
        'process_xincludes' => false,
        'ext'               => null,
        'package_dirs'      => array(__INSTALLDIR__),
        'saveconfig'        => false,
    );

    public static function init(array $a) {
        // add the include-path to the package dirs
        $include_path = explode(PATH_SEPARATOR, get_include_path());
        foreach ($include_path as $dir) {
            if ($dir != __INSTALLDIR__) {
                self::$optionArray['package_dirs'][] = $dir;
            }
        }
        
        // now merge other options
        self::$optionArray = array_merge(self::$optionArray, (array)$a);
    }

    public static function getAllFiltered() {
        $retval = self::$optionArray;
        return self::exportable($retval);
    }
    public static function exportable($val) {
        foreach($val as $k => &$opt) {
            if (is_array($opt)) {
                $opt = self::exportable($opt);
                continue;
            }
            if (is_resource($opt)) {
                unset($val[$k]);
            }
        }
        return $val;
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
            foreach(Config::package_dirs() as $dir) {
                foreach (glob($dir . "/phpdotnet/phd/Package/*", GLOB_ONLYDIR) as $item) {
                    $baseitem = basename($item);
                    if ($baseitem[0] != '.') {
                        $packageList[] = $baseitem;
                    }
                }
            }
        }
        return $packageList;
    }

    public static function setColor_output($color_output)
    {
        // Disable colored output if the terminal doesn't support colours
        if ($color_output && function_exists('posix_isatty')) {
            if (!posix_isatty(Config::phd_info_output())) {
                Config::setPhd_info_color(false);
            }
            if (!posix_isatty(Config::phd_warning_output())) {
                Config::setPhd_warning_color(false);
            }
            if (!posix_isatty(Config::php_error_output())) {
                Config::setPhd_error_color(false);
            }
            if (!posix_isatty(Config::user_error_output())) {
                Config::setUser_error_color(false);
            }
        }
        self::$optionArray['color_output'] = $color_output;
    }

    public static function set_color_output($color_output)
    {
        trigger_error('Use setColor_output()', E_USER_DEPRECATED);
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

