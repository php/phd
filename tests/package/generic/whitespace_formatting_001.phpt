--TEST--
Whitespace formatting 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/whitespace_formatting_001.xml";

$config->xml_file = $xml_file;

$format = new TestGenericChunkedXHTML($config, $outputHandler);
$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: whitespace-formatting-001.html
Content:
<div id="whitespace-formatting-001" class="chapter">

 <div class="section">
  <p class="para">1. Function/method with whitespace between name, parameters and return types</p>
  <div class="methodsynopsis dc-description"><span class="type"><span class="type">int</span><span class="type">float</span><span class="type">false</span></span> <span class="methodname">function_name</span>(<span class="methodparam"><span class="type"><span class="type">iterable</span><span class="type">resource</span><span class="type">callable</span><span class="type">null</span></span> <code class="parameter">$option</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">2. Constructor with whitespace between name, parameters and return types</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">public</span>  <span class="methodname">ClassName::__construct</span>(<span class="methodparam"><span class="type"><span class="type">iterable</span><span class="type">resource</span><span class="type">callable</span><span class="type">null</span></span> <code class="parameter">$option</code></span><span class="type"><a href="language.types.void.html" class="type void">void</a></span>)</div>

 </div>

 <div class="section">
  <p class="para">3. Destructor with whitespace between name, parameters and return types</p>
  <div class="destructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">public</span>  <span class="methodname">ClassName::__construct</span>(<span class="methodparam"><span class="type"><span class="type">iterable</span><span class="type">resource</span><span class="type">callable</span><span class="type">null</span></span> <code class="parameter">$option</code></span><span class="type"><a href="language.types.void.html" class="type void">void</a></span>)</div>

 </div>

 <div class="section">
  <p class="para">4. Class constant with whitespace after varname and initializer</p>
  <div class="fieldsynopsis">
   <span class="modifier">const</span>
   <span class="type">int</span>
   <var class="fieldsynopsis_varname">CONSTANT_NAME</var><span class="initializer"> = 1</span>;</div>

 </div>

 <div class="section">
  <p class="para">5. Implements list with whitespace</p>
  <div class="classsynopsisinfo">
   <span class="ooclass">
    <span class="modifier">class</span> SomeClass
   </span>
   <span class="oointerface"><span class="modifier">implements</span> 
     FirstInterface</span><span class="oointerface">,  SecondInterface</span><span class="oointerface">,  ThirdInterface</span> {</div>
 </div>

</div>
