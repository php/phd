--TEST--
Attribute formatting 002 - Class and method attributes
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/attribute_formatting_002.xml";

$config->setXml_file($xml_file);

$format = new TestGenericChunkedXHTML($config);

$format->SQLiteIndex(
    null, // $context,
    null, // $index,
    "class.knownattribute", // $id,
    "file.knownattribute.is.in", // $filename,
    "", // $parent,
    "", // $sdesc,
    "", // $ldesc,
    "", // $element,
    "", // $previous,
    "", // $next,
    0, // $chunk
);
$format->SQLiteIndex(
    null, // $context,
    null, // $index,
    "class.anotherknownattribute", // $id,
    "file.anotherknownattribute.is.in", // $filename,
    "", // $parent,
    "", // $sdesc,
    "", // $ldesc,
    "", // $element,
    "", // $previous,
    "", // $next,
    0, // $chunk
);

$render = new TestRender(new Reader, $config, $format);

$render->run();
?>
--EXPECT--
Filename: attribute-formatting-002.html
Content:
<div id="attribute-formatting-002" class="chapter">
 <div class="section">
  <p class="para">1. Class with unknown attributes</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[\UnknownAttribute]</span><br>
    <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
    <span class="modifier">class</span> <strong class="classname">DateTime</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">2. Class with known attributes</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
    <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
    <span class="modifier">class</span> <strong class="classname">DateTime</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">3. Method with unknown attributes</p>
  <div class="methodsynopsis dc-description">
   <span class="attribute">#[\UnknownAttribute]</span><br>
   <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
   <span class="modifier">public</span>  <span class="methodname">ClassName::methodName</span>()</div>

 </div>

 <div class="section">
  <p class="para">4. Method with known attributes</p>
  <div class="methodsynopsis dc-description"><span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
   <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
   <span class="modifier">public</span>  <span class="methodname">ClassName::methodName</span>()</div>

 </div>

 <div class="section">
  <p class="para">5. Constructor with unknown attributes</p>
  <div class="constructorsynopsis dc-description"><span class="attribute">#[\UnknownAttribute]</span><br>
   <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
   <span class="modifier">public</span>  <span class="methodname">ClassName::__construct</span>()</div>

 </div>

 <div class="section">
  <p class="para">6. Constructor with known attributes</p>
  <div class="constructorsynopsis dc-description"><span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
   <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
   <span class="modifier">public</span>  <span class="methodname">ClassName::__construct</span>()</div>

 </div>

 <div class="section">
  <p class="para">7. Class, constructor and methods with unknown attributes</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[\UnknownAttribute]</span><br>
    <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
    <span class="modifier">class</span> <strong class="classname">DateTime</strong>
    {</div>
   <div class="constructorsynopsis dc-description">
    <span class="attribute">#[\UnknownAttribute]</span><br>
    <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
    <span class="modifier">public</span>  <span class="methodname">ClassName::__construct</span>()</div>

   <div class="methodsynopsis dc-description"><span class="attribute">#[\UnknownAttribute]</span><br>
    <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
    <span class="modifier">public</span>  <span class="methodname">ClassName::methodName1</span>()</div>

   <div class="methodsynopsis dc-description"><span class="attribute">#[\UnknownAttribute]</span><br>
    <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
    <span class="modifier">public</span>  <span class="methodname">ClassName::methodName2</span>()</div>

  }</div>
 </div>

 <div class="section">
  <p class="para">8. Class, constructor and methods with known attributes</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
    <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
    <span class="modifier">class</span> <strong class="classname">DateTime</strong>
    {</div>
   <div class="constructorsynopsis dc-description">
    <span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
    <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
    <span class="modifier">public</span>  <span class="methodname">ClassName::__construct</span>()</div>

   <div class="methodsynopsis dc-description"><span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
    <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
    <span class="modifier">public</span>  <span class="methodname">ClassName::methodName1</span>()</div>

   <div class="methodsynopsis dc-description"><span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
    <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
    <span class="modifier">public</span>  <span class="methodname">ClassName::methodName2</span>()</div>

  }</div>
 </div>

 <div class="section">
  <p class="para">9. Function with unknown attributes</p>
  <div class="methodsynopsis dc-description">
   <span class="attribute">#[\UnknownAttribute]</span><br>
   <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
   <span class="type">void</span> <span class="methodname">function_name</span>)</div>

 </div>

 <div class="section">
  <p class="para">10. Function with known attributes</p>
  <div class="methodsynopsis dc-description"><span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
   <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
   <span class="type">void</span> <span class="methodname">function_name</span>)</div>

 </div>

</div>
