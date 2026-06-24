--TEST--
Attribute formatting 005 - Attribute with literal parameter arguments
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/attribute_formatting_005.xml";

$config->xmlFile = $xmlFile;

$format = new TestGenericChunkedXHTML($config, $outputHandler);

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
    "attribute.constants.target-function",
    "class.attribute",
    "", "", "", "", "", "", 0,
);
$format->SQLiteIndex(
    null, null,
    "attribute.constants.target-method",
    "class.attribute",
    "", "", "", "", "", "", 0,
);
$format->SQLiteIndex(
    null, null,
    "attribute.constants.target-class",
    "class.attribute",
    "", "", "", "", "", "", 0,
);

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: attribute-formatting-005.html
Content:
<div id="attribute-formatting-005" class="chapter">
 <div class="section">
  <p class="para">1. Attribute with literal named arguments</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.deprecated.html">\Deprecated</a>(<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">since</code>: <span class="type string">'8.5'</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">message</code>: <span class="type string">'Deprecated since PHP 8.4'</span>,<br>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Deprecated</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">2. Attribute with single literal positional argument</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.deprecated.html">\Deprecated</a>(<span class="type string">'Deprecated since PHP 8.4'</span>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Deprecated</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">3. Unknown attribute with literal argument</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[\UnknownAttribute(<code class="parameter">foo</code>: <span class="type string">'bar'</span>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Deprecated</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">4. Namespaced attribute with literal argument</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[\Some\Namespaced\Attribute(<code class="parameter">value</code>: <span class="type int">42</span>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Deprecated</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">5. Multi-line attribute with literal class constant arguments</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.attribute.html">\Attribute</a>(<br>&nbsp;&nbsp;&nbsp;&nbsp;<a href="class.attribute.html#attribute.constants.target-function">Attribute::TARGET_FUNCTION</a><br>&nbsp;&nbsp;&nbsp;&nbsp;| <a href="class.attribute.html#attribute.constants.target-method">Attribute::TARGET_METHOD</a><br>&nbsp;&nbsp;&nbsp;&nbsp;| <a href="class.attribute.html#attribute.constants.target-class">Attribute::TARGET_CLASS</a>,<br>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Deprecated</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">6. Attribute with mix of known and unknown class constants</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.attribute.html">\Attribute</a>(<br>&nbsp;&nbsp;&nbsp;&nbsp;<a href="class.attribute.html#attribute.constants.target-class">Attribute::TARGET_CLASS</a><br>&nbsp;&nbsp;&nbsp;&nbsp;| Unknown::CONST,<br>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Deprecated</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">7. Attribute with bool, null, int and float literal arguments</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[\UnknownAttribute(<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">enabled</code>: <span class="type true">true</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">fallback</code>: <span class="type false">false</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">default</code>: <span class="type null">null</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">count</code>: <span class="type int">42</span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<code class="parameter">ratio</code>: <span class="type float">3.14</span>,<br>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Deprecated</strong>
    {</div>
  }</div>
 </div>
</div>
