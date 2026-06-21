--TEST--
Attribute formatting 004 - Attribute with constant arguments
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/attribute_formatting_004.xml";

$config->xmlFile = $xmlFile;

$format = new TestGenericChunkedXHTML($config, $outputHandler);

$format->SQLiteIndex(
    null, null,
    "class.attribute",
    "class.attribute",
    "", "", "", "", "", "", 0,
);
$format->SQLiteIndex(
    null, null,
    "attribute.constants.target-class",
    "class.attribute",
    "", "", "", "", "", "", 0,
);
$format->SQLiteIndex(
    null, null,
    "attribute.constants.target-class-constant",
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
Filename: attribute-formatting-004.html
Content:
<div id="attribute-formatting-004" class="chapter">
 <div class="section">
  <p class="para">1. Attribute with one constant argument</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.attribute.html">\Attribute</a>(<a href="class.attribute.html#attribute.constants.target-class">Attribute::TARGET_CLASS</a>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Attribute</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">2. Attribute with multiple constant arguments</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.attribute.html">\Attribute</a>(<a href="class.attribute.html#attribute.constants.target-class-constant">Attribute::TARGET_CLASS_CONSTANT</a> | <a href="class.attribute.html#attribute.constants.target-property">Attribute::TARGET_PROPERTY</a>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Attribute</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">3. Attribute with unknown constant argument</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.attribute.html">\Attribute</a>(Attribute::TARGET_UNKNOWN)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Attribute</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">4. Unknown attribute with constant argument</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[\UnknownAttribute(<a href="class.attribute.html#attribute.constants.target-class">Attribute::TARGET_CLASS</a>)]</span><br>
    <span class="modifier">final</span>
    <span class="modifier">class</span> <strong class="classname">Attribute</strong>
    {</div>
  }</div>
 </div>
</div>
