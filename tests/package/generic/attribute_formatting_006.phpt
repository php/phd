--TEST--
Attribute formatting 006 - methodparam attribute rendering (inline)
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/attribute_formatting_006.xml";

$config->xmlFile = $xmlFile;

$format = new TestGenericChunkedXHTML($config, $outputHandler);

$format->SQLiteIndex(
    null, null,
    "class.sensitiveparameter",
    "class.sensitiveparameter",
    "", "", "", "", "", "", 0,
);
$format->SQLiteIndex(
    null, null,
    "class.deprecated",
    "class.deprecated",
    "", "", "", "", "", "", 0,
);
$format->SQLiteIndex(
    null, null,
    "class.attribute",
    "class.attribute",
    "", "", "", "", "", "", 0,
);
$format->SQLiteIndex(
    null, null,
    "attribute.constants.target-parameter",
    "class.attribute",
    "", "", "", "", "", "", 0,
);
$format->SQLiteIndex(
    null, null,
    "attribute.constants.target-property",
    "class.attribute",
    "", "", "", "", "", "", 0,
);

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: attribute-formatting-006.html
Content:
<div id="attribute-formatting-006" class="chapter">
 <div class="section">
  <p class="para">1. methodparam with unknown attribute</p>
  <div class="methodsynopsis dc-description"><span class="type">void</span> <span class="methodname">example1</span>(<span class="methodparam"><span class="attribute">#[\UnknownAttribute]</span><span class="type">string</span> <code class="parameter">$password</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">2. methodparam with known attribute</p>
  <div class="methodsynopsis dc-description"><span class="type">void</span> <span class="methodname">example2</span>(<span class="methodparam"><span class="attribute"><a href="class.sensitiveparameter.html">#[\SensitiveParameter]</a> </span><span class="type">string</span> <code class="parameter">$password</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">3. methodparam with attribute with named args (rendered inline)</p>
  <div class="methodsynopsis dc-description"><span class="type">void</span> <span class="methodname">example3</span>(<span class="methodparam"><span class="attribute">#[<a href="class.deprecated.html">\Deprecated</a>(<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">since</code>: <span class="type string">'8.5'</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">message</code>: <span class="type string">'Use foo()'</span>,<br>)]</span><span class="type">string</span> <code class="parameter">$password</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">4. methodparam with attribute with single constant arg</p>
  <div class="methodsynopsis dc-description"><span class="type">void</span> <span class="methodname">example4</span>(<span class="methodparam"><span class="attribute">#[<a href="class.attribute.html">\Attribute</a>(<a href="class.attribute.html#attribute.constants.target-parameter">Attribute::TARGET_PARAMETER</a>)]</span><span class="type">string</span> <code class="parameter">$password</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">5. methodparam with attribute with pipe-separated constants (rendered inline)</p>
  <div class="methodsynopsis dc-description"><span class="type">void</span> <span class="methodname">example5</span>(<span class="methodparam"><span class="attribute">#[<a href="class.attribute.html">\Attribute</a>(<br>&nbsp;&nbsp;&nbsp;&nbsp;<a href="class.attribute.html#attribute.constants.target-parameter">Attribute::TARGET_PARAMETER</a><br>&nbsp;&nbsp;&nbsp;&nbsp;| <a href="class.attribute.html#attribute.constants.target-property">Attribute::TARGET_PROPERTY</a>,<br>)]</span><span class="type">string</span> <code class="parameter">$password</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">6. methodparam with attribute with literal args (rendered inline)</p>
  <div class="methodsynopsis dc-description"><span class="type">void</span> <span class="methodname">example6</span>(<span class="methodparam"><span class="attribute">#[\UnknownAttribute(<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">enabled</code>: <span class="type true">true</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">default</code>: <span class="type null">null</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">count</code>: <span class="type int">42</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">ratio</code>: <span class="type float">3.14</span>,<br>)]</span><span class="type">string</span> <code class="parameter">$password</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">7. methodparam with multiple attributes</p>
  <div class="methodsynopsis dc-description"><span class="type">void</span> <span class="methodname">example7</span>(<span class="methodparam"><span class="attribute"><a href="class.sensitiveparameter.html">#[\SensitiveParameter]</a> </span><span class="attribute">#[<a href="class.deprecated.html">\Deprecated</a>(<code class="parameter">since</code>: <span class="type string">'8.5'</span>)]</span><span class="type">string</span> <code class="parameter">$password</code></span>)</div>

 </div>
</div>
