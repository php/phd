--TEST--
GH-181 Empty title should not swallow sibling content
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/empty_title_001.xml";

$config->xmlFile = $xmlFile;

$format = new TestGenericChunkedXHTML($config, $outputHandler);
$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECTF--
Filename: empty-title-section.html
Content:
<div id="empty-title-section" class="section">
  <h2 class="title"></h2>
  <p class="simpara">Some content after empty title</p>
 </div>
Filename: empty_title_test.html
Content:
<div id="empty_title_test" class="article">
%A
</div>
