--TEST--
Enum case (enumidentifier) link rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$config->xmlFile = __DIR__ . "/data/enumidentifier_link_rendering.xml";

$format = new TestPHPChunkedXHTML($config, $outputHandler);
$format->SQLiteIndex(
    null, // $context,
    null, // $index,
    "enum.enum-namespace-existing-enum", // $id,
    "enum.enum-namespace-existing-enum", // $filename,
    "", // $parent,
    "", // $sdesc,
    "", // $ldesc,
    "phpdoc:classref", // $element,
    "", // $previous,
    "", // $next,
    0, // $chunk
);

$render = new TestRender(new Reader($outputHandler), $config, $format);
$render->run();
?>
--EXPECT--
Filename: enumidentifier_link_rendering.html
Content:
<div id="enumidentifier_link_rendering" class="chapter">

 <div class="section">
  <p class="para">1. Enum case inside enumsynopsis (no link - definition context)</p>
  <div class="classsynopsis"><div class="classsynopsisinfo">
   <span class="modifier">enum</span> <strong class="classname"><strong class="enumname">TestEnum</strong></strong><br/>{</div>
   <div class="fieldsynopsis">
        <span class="modifier">case</span>  <span class="classname">CaseDefinition</span>
   </div>
  }</div>
 </div>

 <div class="section">
  <p class="para">2. Enum case reference with FQN (linked)</p>
      <span class="modifier">case</span>  <span class="classname"><a href="enum.enum-namespace-existing-enum.html" class="enumidentifier">Enum\Namespace\Existing_Enum::SomeCase</a></span>
 </div>

 <div class="section">
  <p class="para">3. Enum case reference with FQN and leading backslash (linked)</p>
      <span class="modifier">case</span>  <span class="classname"><a href="enum.enum-namespace-existing-enum.html" class="enumidentifier">\Enum\Namespace\Existing_Enum::AnotherCase</a></span>
 </div>

 <div class="section">
  <p class="para">4. Enum case without namespace separator (no link)</p>
      <span class="modifier">case</span>  <span class="classname">JustCaseName</span>
 </div>

 <div class="section">
  <p class="para">5. Enum case for non-existent enum (no link)</p>
      <span class="modifier">case</span>  <span class="classname">NonExistent\Enum::SomeCase</span>
 </div>

</div>
