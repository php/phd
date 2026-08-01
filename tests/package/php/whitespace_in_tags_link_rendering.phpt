--TEST--
GH-80 Whitespace in function/method/class tags should not break link resolution
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$config->xmlFile = __DIR__ . "/data/whitespace_in_tags_link_rendering.xml";

$indices = [
    [
        "docbook_id" => "function.strlen",
        "filename"   => "function.strlen",
    ],
    [
        "docbook_id" => "class.extension-namespace-existing-class",
        "filename"   => "extensionname.classpage",
        "element"    => "phpdoc:classref",
    ],
    [
        "docbook_id" => "extension-namespace-classname.existingmethodname",
        "filename"   => "extension-namespace-classname.methodpage",
    ],
    [
        "docbook_id" => "reserved.variables.server",
        "filename"   => "reserved.variables.server",
    ],
];

$format = new TestPHPChunkedXHTML($config, $outputHandler);

foreach ($indices as $index) {
    $format->SQLiteIndex(
        null, // $context,
        null, // $index,
        $index["docbook_id"] ?? "", // $id,
        $index["filename"] ?? "", // $filename,
        $index["parent_id"] ?? "", // $parent,
        $index["sdesc"] ?? "", // $sdesc,
        $index["ldesc"] ?? "", // $ldesc,
        $index["element"] ?? "", // $element,
        $index["previous"] ?? "", // $previous,
        $index["next"] ?? "", // $next,
        $index["chunk"] ?? 0, // $chunk
    );
}

$format->addRefname("function.strlen", "strlen");
$format->addClassname("class.extension-namespace-existing-class", "extension\\namespace\\existing_class");
$format->addRefname("extension-namespace-classname.methodpage", "extension\\namespace\\classname::existingmethodname");
$format->addVarname("reserved.variables.server", "_SERVER");

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECTF--
%s
	Whitespace found in <function> tag content, this should be fixed in the XML source
%s
	Whitespace found in <methodname> tag content, this should be fixed in the XML source
%s
	Whitespace found in <classname> tag content, this should be fixed in the XML source
%s
	Whitespace found in <varname> tag content, this should be fixed in the XML source
Filename: whitespace_in_tags_link_rendering.html
Content:
<div id="whitespace_in_tags_link_rendering" class="chapter">

 <div class="section">
  <p class="para">1. Function with leading/trailing whitespace</p>
  <p class="para">
   <span class="function"><a href="function.strlen.html" class="function">strlen()</a></span>
  </p>
 </div>

 <div class="section">
  <p class="para">2. Methodname with leading/trailing whitespace</p>
  <p class="para">
   <span class="methodname"><a href="extension-namespace-classname.methodpage.html" class="methodname">Extension\Namespace\Classname::existingMethodName()</a></span>
  </p>
 </div>

 <div class="section">
  <p class="para">3. Classname with leading/trailing whitespace</p>
  <p class="para">
   <span class="classname"><a href="class.extension-namespace-existing-class.html" class="classname">Extension\Namespace\Existing_Class</a></span>
  </p>
 </div>

 <div class="section">
  <p class="para">4. Varname with leading/trailing whitespace</p>
  <p class="para">
   <var class="varname"><a href="reserved.variables.server.html" class="varname">_SERVER</a></var>
  </p>
 </div>

</div>
