<?php
namespace phpdotnet\phd;

class Autoloader
{
    /**
     * @var array<string> Absolute pathnames to package directories
     */
    private static array $package_dirs = [];

    public static function autoload(string $name): void
    {
        // Only try autoloading classes we know about (i.e. from our own namespace)
        if (strncmp('phpdotnet\phd\\', $name, 14) === 0) {
            $filename = DIRECTORY_SEPARATOR . str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $name) . '.php';
            foreach(self::$package_dirs as $dir) {
                $file = $dir . $filename;

                // Using fopen() because it has use_include_path parameter.
                if (!$fp = @fopen($file, 'r', true)) {
                    continue;
                }

                fclose($fp);
                require $file;

                return;
            }
            trigger_error(vsprintf('Cannot find file for %s: %s', [$name, $file ?? $filename]), E_USER_ERROR);
        }
    }

    /**
     * @param array<string> $dirs Absolute pathnames to package directories
     */
    public static function setPackageDirs(array $dirs): void {
        self::$package_dirs = $dirs;
    }
}
