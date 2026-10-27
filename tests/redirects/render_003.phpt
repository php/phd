--TEST--
Redirects 003 - renderTo writes redirects.json into the php-web directory
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/../setup.php';

$lang = __DIR__ . '/data/render_003_lang';
$output = $lang . '/output';
mkdir($output, 0777, true);
copy(__DIR__ . '/data/render_001.xml', $lang . '/redirects.xml');

// renderTo() reports the written path (absolute) through the verbose channel.
$reporting = error_reporting(error_reporting() & ~VERBOSE_FORMAT_RENDERING);
Redirects::renderTo($output, __DIR__ . '/data/docs', $outputHandler);
error_reporting($reporting);

$file = $output . '/php-web/redirects.json';
echo is_file($file) ? file_get_contents($file) : 'missing' . PHP_EOL;

unlink($file);
rmdir($output . '/php-web');
rmdir($output);
unlink($lang . '/redirects.xml');
rmdir($lang);
?>
--EXPECT--
{"shortcut":{"if":"control-structures.if","anchored":"page.foo#some.anchor","bcmath":"bc"},"in_manual":{"class.oldname":"class.newname"},"both":{"installation":"install"}}
