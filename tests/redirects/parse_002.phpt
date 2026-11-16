--TEST--
Redirects parse 002 - A shortcut group marked serve="in-place" goes to its own map
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/../setup.php';

echo json_encode(Redirects::parse(__DIR__ . '/data/parse_002_in_place.xml')) . PHP_EOL;
?>
--EXPECT--
{"shortcut":{"tut.php":"tutorial","memory-limit":"ini.core#ini.memory-limit"},"in_manual":[],"both":[],"in_place":{"try":"language.exceptions"}}
