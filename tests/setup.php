<?php
namespace phpdotnet\phd;

define("__INSTALLDIR__", "@php_dir@" == "@"."php_dir@" ? dirname(__DIR__) : "@php_dir@");

require_once __INSTALLDIR__ . DIRECTORY_SEPARATOR . "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR . "Autoloader.php";
spl_autoload_register(["phpdotnet\\phd\\Autoloader", "autoload"]);

require_once __INSTALLDIR__ . DIRECTORY_SEPARATOR . "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR . "functions.php";

$config = new Config;

$config->init([]);
$config->setLang_dir(__INSTALLDIR__ . DIRECTORY_SEPARATOR
. "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR
. "data" . DIRECTORY_SEPARATOR . "langs" . DIRECTORY_SEPARATOR);
$config->setPackage_dirs([__INSTALLDIR__]);
