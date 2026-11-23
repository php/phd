--TEST--
Redirects parse 004 - A redirect with a manual attribute targets that manual and is not validated
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/../setup.php';

$maps = Redirects::parse(__DIR__ . '/data/parse_004_extensions.xml');
echo json_encode($maps, JSON_UNESCAPED_SLASHES) . PHP_EOL;

$ids = Redirects::collectXmlIds(__DIR__ . '/data/docs');
echo 'Broken targets: ' . count(Redirects::findBrokenTargets($maps, $ids)) . PHP_EOL;

foreach (['manual_value', 'manual_serve'] as $name) {
    try {
        Redirects::parse(__DIR__ . "/data/parse_004_$name.xml");
        echo 'no error' . PHP_EOL;
    } catch (\Error $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}
?>
--EXPECTF--
{"shortcut":{"tut.php":"install","mysql.trace-mode":"extensions/mysql.configuration#ini.mysql.trace-mode"},"in_manual":{"pool.submitto":"extensions/pool.submitTo","only.french":"fr/seulement"},"both":{"set.mongodb":"extensions/book.mongodb"},"in_place":[]}
Broken targets: 0
Invalid manual in %sparse_004_manual_value.xml: ../pecl
Cannot serve a page of another manual in place in %sparse_004_manual_serve.xml: apcu -> book.apcu
