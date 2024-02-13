--TEST--
Type rendering 001 - Methodsynopsis return types
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$formatclass = "TestChunkedXHTML";
$xml_file = __DIR__ . "/data/type_rendering_methodsynopsis_return_types.xml";

$opts = array(
    "index"             => true,
    "xml_root"          => dirname($xml_file),
    "xml_file"          => $xml_file,
    "output_dir"        => __DIR__ . "/output/",
);

$extra = array(
    "lang_dir" => __PHDDIR__ . "phpdotnet/phd/data/langs/",
    "phpweb_version_filename" => dirname($xml_file) . '/version.xml',
    "phpweb_acronym_filename" => dirname($xml_file) . '/acronyms.xml',
);

$render = new TestRender($formatclass, $opts, $extra);

if (Index::requireIndexing() && !file_exists($opts["output_dir"])) {
    mkdir($opts["output_dir"], 0755);
}

$render->run();
?>
--EXPECT--
Filename: type-rendering-methodsynopsis-return-types.html
Content:
<div id="type-rendering-methodsynopsis-return-types" class="chapter">

 <div class="section">
  <p class="para">1. Function/method with no return type</p>
  <div class="methodsynopsis dc-description">
   <span class="methodname"><strong>function_name</strong></span>()</div>

 </div>

 <div class="section">
  <p class="para">2. Function/method with one return type - never</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><a href="language.types.never.html" class="type never">never</a></span></div>

 </div>

 <div class="section">
  <p class="para">3. Function/method with one return type - void</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><a href="language.types.void.html" class="type void">void</a></span></div>

 </div>

 <div class="section">
  <p class="para">4. Function/method with one return type - void element</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><a href="language.types.void.html" class="type void">void</a></span></div>

 </div>

 <div class="section">
  <p class="para">5. Function/method with one return type - mixed</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span></div>

 </div>

 <div class="section">
  <p class="para">6. Function/method with union return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><span class="type"><a href="language.types.integer.html" class="type int">int</a></span>|<span class="type"><a href="language.types.float.html" class="type float">float</a></span>|<span class="type"><a href="language.types.value.html" class="type false">false</a></span></span></div>

 </div>

 <div class="section">
  <p class="para">7. Function/method with nullable return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><span class="type"><a href="language.types.null.html" class="type null">?</a></span><span class="type"><a href="language.types.object.html" class="type object">object</a></span></span></div>

 </div>

 <div class="section">
  <p class="para">8. Function/method with nullable union return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type"><span class="type"><a href="language.types.string.html" class="type string">string</a></span>|<span class="type"><a href="language.types.array.html" class="type array">array</a></span>|<span class="type"><a href="language.types.resource.html" class="type resource">resource</a></span>|<span class="type"><a href="language.types.callable.html" class="type callable">callable</a></span>|<span class="type"><a href="language.types.iterable.html" class="type iterable">iterable</a></span>|<span class="type"><a href="language.types.value.html" class="type true">true</a></span>|<span class="type"><a href="language.types.null.html" class="type null">null</a></span></span></div>

 </div>

 <div class="section">
  <p class="para">9. Function/method with unknown return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>function_name</strong></span>(): <span class="type">UnknownType</span></div>

 </div>

</div>
