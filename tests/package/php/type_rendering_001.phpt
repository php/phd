--TEST--
Type rendering 001 - Methodsynopsis return types
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/type_rendering_methodsynopsis_return_types.xml";

Config::init(["xml_file" => $xml_file]);

$format = new TestPHPChunkedXHTML;
$render = new TestRender(new Reader, new Config, $format);

$render->run();
?>
--EXPECTF--
Filename: type-rendering-methodsynopsis-return-types.html
Content:
<div id="type-rendering-methodsynopsis-return-types" class="chapter">

 <div class="section">
  <p class="para">%d. Function/method with no return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>()</div>

 </div>

 <div class="section">
  <p class="para">%d. Function/method with one return type - never</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><a href="language.types.never.html" class="type never">never</a></span></div>

 </div>

 <div class="section">
  <p class="para">%d. Function/method with one return type - void</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><a href="language.types.void.html" class="type void">void</a></span></div>

 </div>

 <div class="section">
  <p class="para">%d. Function/method with one return type - mixed</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span></div>

 </div>

 <div class="section">
  <p class="para">%d. Function/method with union return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><span class="type"><a href="language.types.integer.html" class="type int">int</a></span>|<span class="type"><a href="language.types.float.html" class="type float">float</a></span>|<span class="type"><a href="language.types.value.html" class="type false">false</a></span></span></div>

 </div>

 <div class="section">
  <p class="para">%d. Function/method with nullable return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><span class="type"><a href="language.types.null.html" class="type null">?</a></span><span class="type"><a href="language.types.object.html" class="type object">object</a></span></span></div>

 </div>

 <div class="section">
  <p class="para">%d. Function/method with nullable union return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><span class="type"><a href="language.types.string.html" class="type string">string</a></span>|<span class="type"><a href="language.types.array.html" class="type array">array</a></span>|<span class="type"><a href="language.types.resource.html" class="type resource">resource</a></span>|<span class="type"><a href="language.types.callable.html" class="type callable">callable</a></span>|<span class="type"><a href="language.types.iterable.html" class="type iterable">iterable</a></span>|<span class="type"><a href="language.types.value.html" class="type true">true</a></span>|<span class="type"><a href="language.types.null.html" class="type null">null</a></span></span></div>

 </div>

 <div class="section">
  <p class="para">%d. Function/method with unknown return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type">UnknownType</span></div>

 </div>

</div>
