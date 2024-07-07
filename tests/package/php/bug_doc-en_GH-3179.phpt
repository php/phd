--TEST--
Bug doc-en GH-3197
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/bug_doc-en_GH-3197.xml";

$config->setXml_file($xml_file);

$format = new TestPHPChunkedXHTML($config);
$render = new TestRender(new Reader, $config, $format);

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
