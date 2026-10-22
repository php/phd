--TEST--
Attribute formatting 007 - Attribute names that are not fully qualified
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/attribute_formatting_007.xml";

$config = new Config;

$config->xmlFile = $xmlFile;

$format = new TestGenericChunkedXHTML($config, $outputHandler);

$format->SQLiteIndex(
    null, // $context,
    null, // $index,
    "class.deprecated", // $id,
    "class.deprecated", // $filename,
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
--EXPECTF--
%s[%d:%d:%d - Heads up              ]%s Attribute name "Deprecated" is not fully qualified
%s[%d:%d:%d - Heads up              ]%s Attribute name "Deprecated" is not fully qualified
Filename: attribute-formatting-007.html
Content:
<div id="attribute-formatting-007" class="chapter">
 <div class="section">
  <p class="para">1. Known attribute without leading backslash</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.deprecated.html">Deprecated</a>]</span><br>
    <span class="modifier">class</span> <strong class="classname">ClassName</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">2. Known attribute with arguments without leading backslash</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.deprecated.html">Deprecated</a>(<code class="parameter">since</code>: <span class="type string">'8.4'</span>)]</span><br>
    <span class="modifier">class</span> <strong class="classname">ClassName</strong>
    {</div>
  }</div>
 </div>

 <div class="section">
  <p class="para">3. Fully qualified attribute does not warn</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="attribute">#[<a href="class.deprecated.html">\Deprecated</a>]</span><br>
    <span class="modifier">class</span> <strong class="classname">ClassName</strong>
    {</div>
  }</div>
 </div>

</div>
