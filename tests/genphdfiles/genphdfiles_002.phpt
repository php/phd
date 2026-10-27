--TEST--
genphdfiles 002 - translation branch: sources.xml includes and overrides with the target language
--FILE--
<?php
$root = sys_get_temp_dir() . '/genphd_002_' . getmypid();
@mkdir("$root/en/reference/foo", 0777, true);
@mkdir("$root/fr/reference/foo", 0777, true);

file_put_contents("$root/manual.xml",
    "<?xml version=\"1.0\"?>\n<set xml:id=\"manual\" xmlns=\"http://docbook.org/ns/docbook\"/>\n");
file_put_contents("$root/funcindex.xml",
    "<?xml version=\"1.0\"?>\n<reference xml:id=\"indexes\" xmlns=\"http://docbook.org/ns/docbook\"/>\n");
file_put_contents("$root/en/reference/foo/versions.xml",
    "<?xml version=\"1.0\"?>\n<versions>\n <function name=\"foo\" from=\"PHP 8\"/>\n</versions>\n");
file_put_contents("$root/en/reference/foo/foo.xml",
    "<?xml version=\"1.0\"?>\n<refentry xml:id=\"function.foo\" xmlns=\"http://docbook.org/ns/docbook\"/>\n");
// The fr translation carries the same xml:id, so it must win in the source map.
file_put_contents("$root/fr/reference/foo/foo.xml",
    "<?xml version=\"1.0\"?>\n<refentry xml:id=\"function.foo\" xmlns=\"http://docbook.org/ns/docbook\"/>\n");

$conf = [
    'rootdir' => $root, 'srcdir' => $root, 'lang' => 'fr',
    'enDir' => 'en', 'langDir' => 'fr', 'generate' => 'no',
    'xpointerReporting' => true, 'stderrToStdout' => false,
    'outputs' => ['version' => true, 'sources' => true, 'history' => true],
];
file_put_contents("$root/phd-conf.json", json_encode($conf));

exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/../../genphdfiles.php')
    . ' ' . escapeshellarg("$root/phd-conf.json"), $out, $rc);

echo "rc=$rc\n";
echo "== version.xml ==\n" . file_get_contents("$root/version.xml");
echo "== sources.xml ==\n" . file_get_contents("$root/sources.xml");

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST);
foreach ($it as $f) { $f->isDir() ? rmdir($f) : unlink($f); }
rmdir($root);
?>
--EXPECT--
rc=0
== version.xml ==
<?xml version="1.0"?>
<versions>
  <function name="foo" from="PHP 8"/>
</versions>
== sources.xml ==
<?xml version="1.0"?>
<sources>
  <item id="indexes" lang="base" path="funcindex.xml"/>
  <item id="manual" lang="base" path="manual.xml"/>
  <item id="function.foo" lang="fr" path="reference/foo/foo.xml"/>
</sources>
