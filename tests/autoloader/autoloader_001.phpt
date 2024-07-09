--TEST--
Autoloader 001 - Autoload format from external directory
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";

Autoloader::setPackageDirs([
    realpath(__DIR__ . DIRECTORY_SEPARATOR . "external_directory_test")
]);

$format = new TestFormat;

echo "Done"
?>
--EXPECT--
Done
