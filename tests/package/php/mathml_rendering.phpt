--TEST--
MathML rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/mathml_rendering.xml";

$config->xmlFile = $xmlFile;

$format = new TestPHPChunkedXHTML($config, $outputHandler);

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: mathml_rendering.html
Content:
<div id="mathml_rendering" class="chapter">

 <div class="section">
  <p class="para">1. Inline MathML equation</p>
  <p class="para">
   The quadratic formula is
   <math xmlns="http://www.w3.org/1998/Math/MathML" display="inline"><mi>x</mi><mo>=</mo><mfrac><mrow><mo>-</mo><mi>b</mi><mo>±</mo><msqrt><mrow><msup><mi>b</mi><mn>2</mn></msup><mo>-</mo><mn>4</mn><mi>a</mi><mi>c</mi></mrow></msqrt></mrow><mrow><mn>2</mn><mi>a</mi></mrow></mfrac></math>.
  </p>
 </div>

 <div class="section">
  <p class="para">2. Self-closing mspace element</p>
  <p class="para">
   <math xmlns="http://www.w3.org/1998/Math/MathML"><mi>a</mi><mspace width="1em"/><mi>b</mi></math>
  </p>
 </div>

 <div class="section">
  <p class="para">3. Element with mathvariant attribute</p>
  <p class="para">
   <math xmlns="http://www.w3.org/1998/Math/MathML"><mi mathvariant="bold">x</mi></math>
  </p>
 </div>

</div>
