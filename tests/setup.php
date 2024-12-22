<?php
namespace phpdotnet\phd;

require_once dirname(__DIR__) . '/phpdotnet/phd/constants.php';
require_once __INSTALLDIR__ . DIRECTORY_SEPARATOR . "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR . "Autoloader.php";

Autoloader::setPackageDirs([__INSTALLDIR__]);
spl_autoload_register(["phpdotnet\\phd\\Autoloader", "autoload"]);

$config = new Config;

$outputHandler = new OutputHandler($config);

$errorHandler = new ErrorHandler($outputHandler);
$olderrrep = error_reporting();
error_reporting($olderrrep | VERBOSE_DEFAULT);
set_error_handler($errorHandler->handleError(...));

$config->init([]);
$config->lang_dir = __INSTALLDIR__ . DIRECTORY_SEPARATOR
. "phpdotnet" . DIRECTORY_SEPARATOR . "phd" . DIRECTORY_SEPARATOR
. "data" . DIRECTORY_SEPARATOR . "langs" . DIRECTORY_SEPARATOR;
$config->package_dirs = [__INSTALLDIR__];
