--TEST--
Redirects parse 001 - Duplicate `from` inside a scope throws
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/../setup.php';

try {
    Redirects::parse(__DIR__ . '/data/parse_001_duplicate.xml');
    echo 'no error' . PHP_EOL;
} catch (\Error $e) {
    echo $e->getMessage() . PHP_EOL;
}
?>
--EXPECTF--
Duplicate key in %sparse_001_duplicate.xml (scope=shortcut): if
