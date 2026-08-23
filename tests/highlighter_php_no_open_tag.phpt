--TEST--
Highlighter colors PHP snippets that lack a <?php open tag
--SKIPIF--
<?php if (PHP_VERSION_ID < 80300) die("skip highlight_string() output differs before PHP 8.3"); ?>
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/setup.php";

$highlighter = Highlighter::factory("xhtml");

echo $highlighter->highlight('$kitty->eat($banana);', "php", "xhtml"), "\n";
echo $highlighter->highlight("<?php\n\$kitty->eat(\$banana);", "php", "xhtml"), "\n";
?>
--EXPECT--
<pre><code style="color: #000000"><span style="color: #0000BB">$kitty</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">eat</span><span style="color: #007700">(</span><span style="color: #0000BB">$banana</span><span style="color: #007700">);</span></code></pre>
<pre><code style="color: #000000"><span style="color: #0000BB">&lt;?php
$kitty</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">eat</span><span style="color: #007700">(</span><span style="color: #0000BB">$banana</span><span style="color: #007700">);</span></code></pre>
