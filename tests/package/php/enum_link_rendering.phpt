--TEST--
Enum link rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$config->xmlFile = __DIR__ . "/data/enum_link_rendering.xml";

$format = new TestPHPChunkedXHTML($config, $outputHandler);
$format->SQLiteIndex(
    null, // $context,
    null, // $index,
    "enum.enum-namespace-existing-enum", // $id,
    "enumname.enumpage", // $filename,
    "", // $parent,
    "", // $sdesc,
    "", // $ldesc,
    "phpdoc:classref", // $element,
    "", // $previous,
    "", // $next,
    0, // $chunk
);

$format->addClassname("enum.enum-namespace-existing-enum", "enum\\namespace\\existing_enum");

$render = new TestRender(new Reader($outputHandler), $config, $format);
$render->run();
?>
--EXPECT--
Filename: enum_link_rendering.html
Content:
<div id="enum_link_rendering" class="chapter">

 <div class="section">
  <p class="para">1. Existing Enum linking</p>
  <span class="enumname"><a href="enum.enum-namespace-existing-enum.html" class="enumname">Enum\Namespace\Existing_Enum</a></span>
  <span class="enumname"><a href="enum.enum-namespace-existing-enum.html" class="enumname">\Enum\Namespace\Existing_Enum</a></span>
 </div>

 <div class="section">
  <p class="para">2. Enum linking (non-FQN) in method/function parameter and return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>method_name</strong></span>(<span class="methodparam"><span class="type"><a href="enumname.enumpage.html" class="type Enum\Namespace\Existing_Enum">Enum\Namespace\Existing_Enum</a></span> <code class="parameter">$paramName</code></span>): <span class="type"><a href="enumname.enumpage.html" class="type Enum\Namespace\Existing_Enum">Enum\Namespace\Existing_Enum</a></span></div>

 </div>
 
 <div class="section">
  <p class="para">3. Enum linking (FQN) in method/function parameter and return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>method_name</strong></span>(<span class="methodparam"><span class="type"><a href="enumname.enumpage.html" class="type Enum\Namespace\Existing_Enum">\Enum\Namespace\Existing_Enum</a></span> <code class="parameter">$paramName</code></span>): <span class="type"><a href="enumname.enumpage.html" class="type Enum\Namespace\Existing_Enum">\Enum\Namespace\Existing_Enum</a></span></div>

 </div>

</div>
