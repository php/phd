--TEST--
Type rendering 002 - Methodsynopsis parameters and parameter types
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/type_rendering_methodsynopsis_parameters.xml";

$config->xmlFile = $xmlFile;

$format = new TestPHPChunkedXHTML($config, $outputHandler);
$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: type-rendering-methodsynopsis-parameters.html
Content:
<div id="type-rendering-methodsynopsis-parameters" class="chapter">

 <div class="section">
  <p class="para">1. Function/method with no parameters</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>()</div>

 </div>

 <div class="section">
  <p class="para">2. Function/method with one parameter</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span> <code class="parameter">$anything</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">3. Function/method with optional parameter</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.integer.html" class="type int">int</a></span> <code class="parameter">$count</code><span class="initializer"> = 0</span></span>)</div>

 </div>

 <div class="section">
  <p class="para">4. Function/method with nullable parameter</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<span class="methodparam"><span class="type"><span class="type"><a href="language.types.null.html" class="type null">?</a></span><span class="type"><a href="language.types.float.html" class="type float">float</a></span></span> <code class="parameter">$value</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">5. Function/method with nullable optional parameter</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<span class="methodparam"><span class="type"><span class="type"><a href="language.types.null.html" class="type null">?</a></span><span class="type"><a href="language.types.string.html" class="type string">string</a></span></span> <code class="parameter">$options</code><span class="initializer"> = &quot;&quot;</span></span>)</div>

 </div>

 <div class="section">
  <p class="para">6. Function/method with reference parameter</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.array.html" class="type array">array</a></span> <code class="parameter reference">&$reference</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">7. Function/method with union type parameter</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<span class="methodparam"><span class="type"><span class="type"><a href="language.types.iterable.html" class="type iterable">iterable</a></span>|<span class="type"><a href="language.types.resource.html" class="type resource">resource</a></span>|<span class="type"><a href="language.types.callable.html" class="type callable">callable</a></span>|<span class="type"><a href="language.types.null.html" class="type null">null</a></span></span> <code class="parameter">$option</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">8. Function/method with intersection type parameter</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<span class="methodparam"><span class="type"><span class="type">Countable</span>&amp;<span class="type">Traversable</span></span> <code class="parameter">$option</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">9. Function/method with DNF (Disjunctive Normal Form) type parameter</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<span class="methodparam"><span class="type">(<span class="type">Countable</span>&amp;<span class="type">Traversable</span>)|<span class="type">DOMAttr</span></span> <code class="parameter">$option</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">10. Function/method with more than three parameters</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="methodparam"><span class="type"><a href="language.types.integer.html" class="type int">int</a></span> <code class="parameter">$count</code></span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="methodparam"><span class="type"><a href="language.types.string.html" class="type string">string</a></span> <code class="parameter">$name</code></span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="methodparam"><span class="type"><a href="language.types.boolean.html" class="type bool">bool</a></span> <code class="parameter">$isSomething</code></span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="methodparam"><span class="type"><a href="language.types.array.html" class="type array">array</a></span> <code class="parameter">$list</code></span><br>)</div>

 </div>

</div>
