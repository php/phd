--TEST--
Redirects 002 - Broken targets are reported by scope + from -> to
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/../setup.php';

$maps = Redirects::parse(__DIR__ . '/data/render_002_broken.xml');
$ids = Redirects::collectXmlIds(__DIR__ . '/data/docs');

foreach (Redirects::findBrokenTargets($maps, $ids) as [$scope, $from, $to]) {
    echo $scope . ': ' . $from . ' -> ' . $to . PHP_EOL;
}
?>
--EXPECT--
shortcut: legacy -> gone.page
in_manual: oldid -> still.missing
