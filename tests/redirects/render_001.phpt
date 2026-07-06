--TEST--
Redirects 001 - Parse a well-formed redirects.xml and emit the JSON
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/../setup.php';

$maps = Redirects::parse(__DIR__ . '/data/render_001.xml');
$ids = Redirects::collectXmlIds(__DIR__ . '/data/docs');
$broken = Redirects::findBrokenTargets($maps, $ids);

echo 'Broken targets: ' . count($broken) . PHP_EOL;
echo json_encode($maps) . PHP_EOL;
?>
--EXPECT--
Broken targets: 0
{"shortcut":{"if":"control-structures.if","anchored":"page.foo#some.anchor","bcmath":"bc"},"in_manual":{"class.oldname":"class.newname"},"both":{"installation":"install"}}
