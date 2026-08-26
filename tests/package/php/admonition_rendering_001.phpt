--TEST--
Admonition rendering (GH-152 id propagation + GH-213 semantic title)
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/admonition_rendering.xml";

$config->xmlFile = $xmlFile;

$format = new TestPHPChunkedXHTML($config, $outputHandler);

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: admonition_rendering.html
Content:
<div id="admonition_rendering" class="chapter">

 <div class="section">
  <p class="para">1. Warning with xml:id</p>
  <div class="warning" id="warn.test"><h5 class="warning">Warning</h5>
   <p class="para">This is a warning with an id.</p>
  </div>
 </div>

 <div class="section">
  <p class="para">2. Caution without xml:id</p>
  <div class="caution"><h5 class="caution">Caution</h5>
   <p class="para">This is a caution without an id.</p>
  </div>
 </div>

 <div class="section">
  <p class="para">3. Tip with xml:id</p>
  <div class="tip" id="tip.test"><h5 class="tip">Tip</h5>
   <p class="para">This is a tip with an id.</p>
  </div>
 </div>

 <div class="section">
  <p class="para">4. Note with xml:id</p>
  <blockquote class="note" id="note.test"><p><h5 class="note">Note</h5>: 
   <p class="para">This is a note with an id.</p>
  </p></blockquote>
 </div>

 <div class="section">
  <p class="para">5. Important without xml:id</p>
  <div class="important"><h5 class="important">Important</h5>
   <p class="para">This is an important without an id.</p>
  </div>
 </div>

 <div class="section">
  <p class="para">6. Warning with constructorsynopsis</p>
  <div class="warning"><h5 class="warning">Warning</h5>
   <p class="para">This constructor is deprecated:</p>
   <div class="constructorsynopsis dc-description"><span class="modifier">public</span>
    <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.string.html" class="type string">string</a></span> <code class="parameter">$param</code></span>)</div>

  </div>
 </div>

</div>
