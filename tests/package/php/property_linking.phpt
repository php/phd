--TEST--
Property linking 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/property_linking.xml";

$config->setXml_file($xml_file);

$indices = [
    [
        "docbook_id" => "vendor-namespace.props.definitely-exists",
        "filename"   => "extensionname.page",
    ],
    [
        "docbook_id" => "vendor-namespace.props.definitelyexists2",
        "filename"   => "extensionname.page",
    ],
    [
        "docbook_id" => "extension-class.props.leading-and-trailing-undescores",
        "filename"   => "extensionname2.page2",
    ],
    [
        "docbook_id" => "extension-class.props.leadingandtrailingundescores2",
        "filename"   => "extensionname2.page2",
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

$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECTF--
Filename: property_linking.html
Content:
<div id="property_linking" class="chapter">

 <div class="section">
  <p class="para">%d. Existing property</p>
  <p class="para">
   <span class="property"><a href="extensionname.page.html#vendor-namespace.props.definitely-exists">Vendor\Namespace::$definitely_exists</a></span>
  </p>
  <p class="para">
   <span class="property"><a href="extensionname.page.html#vendor-namespace.props.definitelyexists2">Vendor\Namespace::$definitelyExists2</a></span>
  </p>
 </div>

 <div class="section">
  <p class="para">%d. Nonexistent properties</p>
  <p class="para">
   <span class="property">Vendor\Namespace::$this_does_not_exist</span>
  </p>
  <p class="para">
   <span class="property">Vendor\Namespace::$thisDoesNotExist2</span>
  </p>
 </div>

 <div class="section">
  <p class="para">%d. Properties with leading and trailing underscores in ID</p>
  <p class="para">
   <span class="property"><a href="extensionname2.page2.html#extension-class.props.leading-and-trailing-undescores">Extension\Class::$__leading_and_trailing_undescores__</a></span>
  </p>
  <p class="para">
   <span class="property"><a href="extensionname2.page2.html#extension-class.props.leadingandtrailingundescores2">Extension\Class::$__leadingAndTrailingUndescores2__</a></span>
  </p>
 </div>

</div>
