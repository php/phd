--TEST--
Attribute formatting 003 - Class properties/constants
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/attribute_formatting_003.xml";

$config = new Config;

$config->setXml_file($xml_file);

$format = new TestGenericChunkedXHTML($config, $outputHandler);

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

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: attribute-formatting-003.html
Content:
<div id="attribute-formatting-003" class="chapter">
 <div class="section">
  <p class="para">1. Property/Constant with unknown attributes</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="modifier">class</span> <strong class="classname">ClassName</strong>
    {</div>
   <div class="classsynopsisinfo classsynopsisinfo_comment">/* Properties/Constants */</div>
   <div class="fieldsynopsis">
    <span class="attribute">#[\UnknownAttribute]</span><br>
    <span class="attribute">#[\AnotherUnknownAttribute]</span><br>
    <span class="modifier">public</span>
    <span class="modifier">readonly</span>
    <span class="type">string</span>
    <var class="varname">$CONSTANT_NAME</var>;</div>

  }</div>
 </div>

 <div class="section">
  <p class="para">2. Property/Constant with known attributes</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="modifier">class</span> <strong class="classname">ClassName</strong>
    {</div>
   <div class="classsynopsisinfo classsynopsisinfo_comment">/* Properties/Constants */</div>
   <div class="fieldsynopsis">
    <span class="attribute"><a href="file.knownattribute.is.in.html">#[\KnownAttribute]</a> </span><br>
    <span class="attribute"><a href="file.anotherknownattribute.is.in.html">#[\AnotherKnownAttribute]</a> </span><br>
    <span class="modifier">public</span>
    <span class="modifier">readonly</span>
    <span class="type">string</span>
    <var class="varname">$propertyName</var>;</div>

  }</div>
 </div>

</div>
