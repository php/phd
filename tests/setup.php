<?php
namespace phpdotnet\phd;

define("__PHDDIR__", __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);
define("__INSTALLDIR__", "@php_dir@" == "@"."php_dir@" ? dirname(dirname(__DIR__)) : "@php_dir@");

require_once __PHDDIR__ . "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR . "Autoloader.php";
require_once __PHDDIR__ . "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR . "functions.php";
require_once __PHDDIR__ . "tests" . DIRECTORY_SEPARATOR . "TestRender.php";

spl_autoload_register(["phpdotnet\\phd\\Autoloader", "autoload"]);

Config::init([
    "lang_dir"  => __PHDDIR__ . DIRECTORY_SEPARATOR . "phpdotnet" . DIRECTORY_SEPARATOR
                    . "phd" . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR
                    . "langs" . DIRECTORY_SEPARATOR,
    "phpweb_version_filename" => Config::xml_root() . DIRECTORY_SEPARATOR . 'version.xml',
    "phpweb_acronym_filename" => Config::xml_root() . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'acronyms.xml',
    "phpweb_sources_filename" => Config::xml_root() . DIRECTORY_SEPARATOR . 'sources.xml',
    "package_dirs" => [__PHDDIR__, __INSTALLDIR__],
]);

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
