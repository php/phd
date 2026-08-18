--TEST--
Class and method link rendering 002: bare methodname colliding with a global function
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$config->xmlFile = __DIR__ . "/data/class_and_method_link_rendering_002.xml";

$indices = [
    [
        "docbook_id" => "function.conflictingname",
        "filename"   => "function.conflictingname",
    ],
    [
        "docbook_id" => "myclass.conflictingname",
        "filename"   => "myclass.conflictingname",
    ],
    [
        "docbook_id" => "function.onlyfunction",
        "filename"   => "function.onlyfunction",
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

$format->addRefname("function.conflictingname", "conflictingname");
$format->addRefname("myclass.conflictingname", "myclass::conflictingname");
$format->addRefname("function.onlyfunction", "onlyfunction");

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECTF--
Filename: class.myclass.html
Content:
<div id="class.myclass" class="reference">

 <h1 class="title">The MyClass class</h1>
 

 <div class="partintro"><p class="verinfo">(No version information available, might only be in Git)</p>

  <div class="section">
   <p class="simpara">1. Bare methodname colliding with a global function links to the method of the current class</p>
   <span class="methodname"><a href="myclass.conflictingname.html" class="methodname">conflictingName()</a></span>
  </div>

  <div class="section">
   <p class="simpara">2. Function with the same name still links to the global function</p>
   <span class="function"><a href="function.conflictingname.html" class="function">conflictingName()</a></span>
  </div>

  <div class="section">
   <p class="simpara">3. Bare methodname without a matching method falls back to the global function</p>
   <span class="methodname"><a href="function.onlyfunction.html" class="methodname">onlyFunction()</a></span>
  </div>

 </div>

</div>
