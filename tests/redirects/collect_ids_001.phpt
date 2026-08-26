--TEST--
Redirects collect_ids 001 - Walk a directory tree and gather xml:ids
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/../setup.php';

$ids = Redirects::collectXmlIds(__DIR__ . '/data/docs');
$keys = array_keys($ids);
sort($keys);
foreach ($keys as $id) {
    echo $id . PHP_EOL;
}
?>
--EXPECT--
book.bc
class.newname
control-structures.if
install
page.foo
some.anchor
