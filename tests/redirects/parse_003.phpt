--TEST--
Redirects parse 003 - serve="in-place" is only valid for shortcuts without an anchor, and never twice
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/../setup.php';

foreach (['serve_anchor', 'serve_value', 'serve_scope', 'serve_duplicate'] as $name) {
    try {
        Redirects::parse(__DIR__ . "/data/parse_003_$name.xml");
        echo 'no error' . PHP_EOL;
    } catch (\Error $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}
?>
--EXPECTF--
Cannot serve an anchor in place in %sparse_003_serve_anchor.xml: memory-limit -> ini.core#ini.memory-limit
Unknown serve value in %sparse_003_serve_value.xml: inplace
serve="in-place" only applies to the shortcut scope in %sparse_003_serve_scope.xml
Duplicate key in %sparse_003_serve_duplicate.xml (scope=shortcut): try
