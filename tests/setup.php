<?php
namespace phpdotnet\phd;

define("__PHDDIR__", __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);
define("__INSTALLDIR__", "@php_dir@" == "@"."php_dir@" ? dirname(dirname(__DIR__)) : "@php_dir@");

require_once __PHDDIR__ . "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR . "Autoloader.php";
spl_autoload_register(["phpdotnet\\phd\\Autoloader", "autoload"]);

require_once __PHDDIR__ . "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR . "functions.php";

Config::init([
    "lang_dir"  => __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR
                    . "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR
                    . "data" . DIRECTORY_SEPARATOR . "langs" . DIRECTORY_SEPARATOR,
    "package_dirs" => [__PHDDIR__, __INSTALLDIR__],
]);
