--TEST--
Function with replaceable rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/function_replaceable_rendering.xml";

$config->xmlFile = $xmlFile;

$format = new TestPHPChunkedXHTML($config, $outputHandler);

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECTF--
Filename: function_replaceable_rendering.html
Content:
<div id="function_replaceable_rendering" class="chapter">

 <div class="section">
  <p class="para">1. Function with replaceable</p>
  <p class="para">
   <span class="function">xml_set_<span class="replaceable">*</span></span>
  </p>
 </div>

 <div class="section">
  <p class="para">2. Normal function (no replaceable)</p>
  <p class="para">
   <span class="function"><strong>strlen()</strong></span>
  </p>
 </div>

</div>
