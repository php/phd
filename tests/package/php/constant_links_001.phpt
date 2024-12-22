--TEST--
Constant links 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/constant_links.xml";

$config->xml_file = $xml_file;

$indices = [
    [
        "docbook_id" => "constant.extension-namespace-definitely-exists",
        "filename"   => "extensionname.constantspage",
    ],
    [
        "docbook_id" => "vendor-namespace.constants.definitely-exists2",
        "filename"   => "extensionname2.constantspage2",
    ],
    [
        "docbook_id" => "constant.leading-and-trailing-undescores",
        "filename"   => "extensionname3.constantspage3",
    ],
    [
        "docbook_id" => "extension-class.constants.leading-and-trailing-undescores2",
        "filename"   => "extensionname4.constantspage4",
    ],
    [
        "docbook_id" => "constant.extension-namespace-definitely-exists3",
        "filename"   => "extensionname.constantspage",
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
Filename: constant_links.html
Content:
<div id="constant_links" class="chapter">

 <div class="section">
  <p class="para">%d. Existing constants</p>
  <strong><code><a href="extensionname.constantspage.html#constant.extension-namespace-definitely-exists">Extension\Namespace\DEFINITELY_EXISTS</a></code></strong>
  <p class="para">
   <strong><code><a href="extensionname2.constantspage2.html#vendor-namespace.constants.definitely-exists2">Vendor\Namespace::DEFINITELY_EXISTS2</a></code></strong>
  </p>
 </div>

 <div class="section">
  <p class="para">%d. Nonexistent constants</p>
  <strong><code>THIS_DOES_NOT_EXIST</code></strong>
  <p class="para">
   <strong><code>Vendor\Namespace::THIS_DOES_NOT_EXIST_EITHER</code></strong>
  </p>
 </div>

 <div class="section">
  <p class="para">%d. Constant with leading and trailing underscores in ID</p>
  <strong><code><a href="extensionname3.constantspage3.html#constant.leading-and-trailing-undescores">__LEADING_AND_TRAILING_UNDESCORES__</a></code></strong>
  <p class="para">
   <strong><code><a href="extensionname4.constantspage4.html#extension-class.constants.leading-and-trailing-undescores2">Extension\Class::__LEADING_AND_TRAILING_UNDESCORES2__</a></code></strong>
  </p>
 </div>
 
 <div class="section">
  <p class="para">4. Constant with replacable parts links to first ID in the index</p>
  <p class="para">
   <strong><code><a href="extensionname.constantspage.html#constant.extension-namespace-definitely-exists">Extension\Namespace\DEFINITELY_<span class="replaceable">SHOULD_EXIST</span></a></code></strong>
  </p>
 </div>

</div>
