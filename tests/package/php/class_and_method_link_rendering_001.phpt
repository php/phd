--TEST--
Class and method link rendering 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$config->setXml_file(
    __DIR__ . "/data/class_and_method_link_rendering_001.xml"
);

$indices = [
    [
        "docbook_id" => "class.extension-namespace-existing-class",
        "filename"   => "extensionname.classpage",
        "element"    => "phpdoc:classref",
    ],
    [
        "docbook_id" => "extension-namespace-classname.existingmethodname",
        "filename"   => "extension-namespace-classname.methodpage",
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

$format->addClassname("class.extension-namespace-existing-class", "extension\\namespace\\existing_class");
$format->addRefname("extension-namespace-classname.existingmethodname", "extension\\namespace\\classname::existingmethodname");

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECTF--
Filename: class_and_method_link_rendering.html
Content:
<div id="class_and_method_link_rendering" class="chapter">

 <div class="section">
  <p class="para">1. Class linking</p>
  <span class="classname"><a href="class.extension-namespace-existing-class.html" class="classname">Extension\Namespace\Existing_Class</a></span>
  <span class="classname"><a href="class.extension-namespace-existing-class.html" class="classname">\Extension\Namespace\Existing_Class</a></span>
 </div>
 
 <div class="section">
  <p class="para">2. Method/Function linking</p>
  <span class="methodname"><a href="extension-namespace-classname.existingmethodname.html" class="methodname">Extension\Namespace\Classname::existingMethodName()</a></span>
  <span class="methodname"><a href="extension-namespace-classname.existingmethodname.html" class="methodname">\Extension\Namespace\Classname::existingMethodName()</a></span>
 </div>
 
 <div class="section">
  <p class="para">3. Class linking (non-FQN) in method/function parameter and return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>method_name</strong></span>(<span class="methodparam"><span class="type"><a href="extensionname.classpage.html" class="type Extension\Namespace\Existing_Class">Extension\Namespace\Existing_Class</a></span> <code class="parameter">$paramName</code></span>): <span class="type"><a href="extensionname.classpage.html" class="type Extension\Namespace\Existing_Class">Extension\Namespace\Existing_Class</a></span></div>

 </div>
 
 <div class="section">
  <p class="para">4. Class linking (FQN) in method/function parameter and return type</p>
  <div class="methodsynopsis dc-description"><span class="methodname"><strong>method_name</strong></span>(<span class="methodparam"><span class="type"><a href="extensionname.classpage.html" class="type Extension\Namespace\Existing_Class">\Extension\Namespace\Existing_Class</a></span> <code class="parameter">$paramName</code></span>): <span class="type"><a href="extensionname.classpage.html" class="type Extension\Namespace\Existing_Class">\Extension\Namespace\Existing_Class</a></span></div>

 </div>

</div>
