--TEST--
genphdfiles 003 - sources.xml is cached under temp/ and reused on a second run
--FILE--
<?php
$root = sys_get_temp_dir() . '/genphd_003_' . getmypid();
@mkdir("$root/en/reference/foo", 0777, true);
@mkdir("$root/temp", 0777, true);

file_put_contents("$root/manual.xml",
    "<?xml version=\"1.0\"?>\n<set xml:id=\"manual\" xmlns=\"http://docbook.org/ns/docbook\"/>\n");
file_put_contents("$root/funcindex.xml",
    "<?xml version=\"1.0\"?>\n<reference xml:id=\"indexes\" xmlns=\"http://docbook.org/ns/docbook\"/>\n");
file_put_contents("$root/en/reference/foo/versions.xml",
    "<?xml version=\"1.0\"?>\n<versions>\n <function name=\"foo\" from=\"PHP 8\"/>\n</versions>\n");
file_put_contents("$root/en/reference/foo/foo.xml",
    "<?xml version=\"1.0\"?>\n<refentry xml:id=\"function.foo\" xmlns=\"http://docbook.org/ns/docbook\"/>\n");

$conf = [
    'rootdir' => $root, 'srcdir' => $root, 'lang' => 'en',
    'enDir' => 'en', 'langDir' => 'en', 'generate' => 'no',
    'xpointerReporting' => true, 'stderrToStdout' => false,
    'outputs' => ['version' => false, 'sources' => true, 'history' => false],
];
file_put_contents("$root/phd-conf.json", json_encode($conf));

$cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/../../genphdfiles.php')
    . ' ' . escapeshellarg("$root/phd-conf.json");

// First run: cold, scans the sources and stashes the copy in temp/.
exec($cmd, $out1, $rc1);
echo "run1: $rc1 " . implode('', $out1) . "\n";
echo "cache exists: " . (is_file("$root/temp/phd-sources.xml") ? "yes" : "no") . "\n";
$first = file_get_contents("$root/sources.xml");

// Delete the sources so a scan-based run could not recreate them identically by
// accident, then remove the input files so only the cache can serve run 2.
unlink("$root/sources.xml");
unlink("$root/en/reference/foo/foo.xml");

exec($cmd, $out2, $rc2);
echo "run2: $rc2 " . implode('', $out2) . "\n";
$second = file_get_contents("$root/sources.xml");

echo "identical: " . ($first === $second ? "yes" : "no") . "\n";
echo "== sources.xml ==\n" . $second;

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST);
foreach ($it as $f) { $f->isDir() ? rmdir($f) : unlink($f); }
rmdir($root);
?>
--EXPECT--
run1: 0 PhD sources: reading, transforming, saving, done.
cache exists: yes
run2: 0 PhD sources: cached, done.
identical: yes
== sources.xml ==
<?xml version="1.0"?>
<sources>
  <item id="indexes" lang="base" path="funcindex.xml"/>
  <item id="manual" lang="base" path="manual.xml"/>
  <item id="function.foo" lang="en" path="reference/foo/foo.xml"/>
</sources>
