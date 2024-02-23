--TEST--
Constant links 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$formatclass = "TestChunkedXHTML";
$xml_file = __DIR__ . "/data/constant_links.xml";

$opts = array(
    "index"             => true,
    "xml_root"          => dirname($xml_file),
    "xml_file"          => $xml_file,
    "output_dir"        => __DIR__ . "/output/",
);

$extra = array(
    "lang_dir" => __PHDDIR__ . "phpdotnet/phd/data/langs/",
    "phpweb_version_filename" => dirname($xml_file) . '/version.xml',
    "phpweb_acronym_filename" => dirname($xml_file) . '/acronyms.xml',
);

$indeces = [
    [
        "docbook_id" => "constant.definitely-exists",
        "filename"   => "extensionname.constantspage",
    ],
    [
        "docbook_id" => "vendor-namespace.constants.definitely-exists2",
        "filename"   => "extensionname2.constantspage2",
    ],
];

$render = new TestRender($formatclass, $opts, $extra, $indeces);

if (Index::requireIndexing() && !file_exists($opts["output_dir"])) {
    mkdir($opts["output_dir"], 0755);
}

$render->run();
?>
--EXPECTF--
Filename: constant_links.html
Content:
<div id="constant_links" class="chapter">

 <div class="section">
  <p class="para">%d. Existing constants</p>
  <strong><code><a href="extensionname.constantspage.html#constant.definitely-exists">DEFINITELY_EXISTS</a></code></strong>
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

</div>
