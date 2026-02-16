--TEST--
Package synopsis rendering (GH-194 packagesynopsis and package elements)
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/packagesynopsis_rendering.xml";

$config->xmlFile = $xmlFile;

$format = new TestPHPChunkedXHTML($config, $outputHandler);

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: packagesynopsis_rendering.html
Content:
<div id="packagesynopsis_rendering" class="chapter">

 <div class="section">
  <p class="para">1. Namespaced class with packagesynopsis</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   <span class="modifier">namespace</span> <strong class="package">BcMath</strong>;</div>
   <div class="classsynopsisinfo">
    
     <span class="modifier">final</span>
     <span class="modifier">readonly</span>
     <span class="modifier">class</span> <strong class="classname"><strong class="classname">Number</strong></strong>
    
    
     <span class="modifier">implements</span>
      <strong class="interfacename">Stringable</strong> {</div>
   }
  </div>
 </div>

 <div class="section">
  <p class="para">2. Namespaced enum with packagesynopsis</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   <span class="modifier">namespace</span> <strong class="package">Pcntl</strong>;</div>
   <div class="classsynopsisinfo">
    <span class="modifier">enum</span> <strong class="classname"><strong class="enumname">QosClass</strong></strong><br/>{</div>
    <div class="fieldsynopsis">
         <span class="modifier">case</span>  <span class="classname">Background</span>
     ; //Background QoS<br><br>
    </div>
   }
  </div>
 </div>

 <div class="section">
  <p class="para">3. Standalone package element</p>
  <p class="para">The <span class="package">Dom</span> namespace.</p>
 </div>

</div>
