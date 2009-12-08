<?php
namespace phpdotnet\phd;

class Autoloader
{
    public static function autoload($name)
    {
        // Only try autoloading classes we know about (i.e. from our own namespace)
        if (strncmp('phpdotnet\phd\\', $name, 14) === 0) {
            $file = __INSTALLDIR__ . DIRECTORY_SEPARATOR . str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $name) . '.php';

            // Using fopen() because it has use_include_path parameter.
            if (!$fp = @fopen($file, 'r', true)) {
                v('Cannot find file for %s: %s', $name, $file, E_USER_ERROR);
            }
            fclose($fp);
            require $file;
        }

        return false;
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

