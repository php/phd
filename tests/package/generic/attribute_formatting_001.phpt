--TEST--
Attribute formatting 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/attribute_formatting_001.xml";

Config::init(["xml_file" => $xml_file]);

$format = new TestGenericChunkedXHTML;

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

$render = new TestRender(new Reader, new Config, $format);

$render->run();
?>
--EXPECT--
Filename: attribute-formatting.html
Content:
<div id="attribute-formatting" class="chapter">
 <div class="section">
  <p class="para">1. Class methodparameter with unknown attribute</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">public</span>  <span class="methodname">mysqli::__construct</span>(<span class="methodparam"><span class="attribute">#[\UnknownAttribute]</span><span class="type"><span class="type">string</span><span class="type">null</span></span> <code class="parameter">$password</code><span class="initializer"> = <span class="type">null</span></span></span>)</div>

 </div>

 <div class="section">
  <p class="para">2. Class methodparameter with known attribute</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">public</span>  <span class="methodname">mysqli::__construct</span>(<span class="methodparam"><span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><span class="type"><span class="type">string</span><span class="type">null</span></span> <code class="parameter">$password</code><span class="initializer"> = <span class="type">null</span></span></span>)</div>

 </div>

 <div class="section">
  <p class="para">3. Function parameter with unknown attribute</p>
  <div class="methodsynopsis dc-description"><span class="type">bool</span> <span class="methodname">password_verify</span><span class="attribute">#[\UnknownAttribute]</span>(<span class="methodparam"><span class="type">string</span> <code class="parameter">$password</code></span>, <span class="methodparam"><span class="type">string</span> <code class="parameter">$hash</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">4. Function parameter with known attribute</p>
  <div class="methodsynopsis dc-description"><span class="type">bool</span> <span class="methodname">password_verify</span><span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span>(<span class="methodparam"><span class="type">string</span> <code class="parameter">$password</code></span>, <span class="methodparam"><span class="type">string</span> <code class="parameter">$hash</code></span>)</div>

 </div>
</div>
