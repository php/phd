--TEST--
Whitespace formatting 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/caption_001.xml";

$config->setXml_file($xml_file);

$format = new TestGenericChunkedXHTML($config, $outputHandler);
$format->postConstruct();
$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: caption_001.html
Content:
<div id="caption_001" class="chapter">

 <div class="section">
  <div class="mediaobject">
   <div class="imageobject">
   </div>
   <div class="caption">
    <p class="para">
     Insightful caption
    </p>
   </div>
  </div>
 </div>

</div>
