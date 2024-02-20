--TEST--
Bug doc-en GH-3197
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$formatclass = "TestChunkedXHTML";
$xml_file = __DIR__ . "/data/Bug_doc-en_GH-3197.xml";

$opts = array(
    "index"             => true,
    "xml_root"          => dirname($xml_file),
    "xml_file"          => $xml_file,
    "output_dir"        => __DIR__ . "/output/",
);

$extra = array(
    "lang_dir" => __PHDDIR__ . "phpdotnet/phd/data/langs/",
    "phpweb_version_filename" => dirname($xml_file) . '/version.xml',
    "phpweb_acronym_filename" => dirname($xml_file) . '/acronyms.xml',
);

$render = new TestRender($formatclass, $opts, $extra);

if (Index::requireIndexing() && !file_exists($opts["output_dir"])) {
    mkdir($opts["output_dir"], 0755);
}

$render->run();
?>
--EXPECT--
Filename: bug_doc-en_GH-3179.html
Content:
<div id="bug_doc-en_GH-3179" class="refentry">
 <div class="refsect1 unknown-1" id="refsect1-bug_doc-en_GH-3179-unknown-1">
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>method_name</strong></span>(): <span class="type"><a href="language.types.void.html" class="type void">void</a></span></div>

 </div>

</div>