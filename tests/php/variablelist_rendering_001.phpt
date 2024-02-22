--TEST--
Variablelist rendering 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$formatclass = "TestChunkedXHTML";
$xml_file = __DIR__ . "/data/variablelist_rendering_001.xml";

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

$render = new TestRender($formatclass, $opts, $extra);

if (Index::requireIndexing() && !file_exists($opts["output_dir"])) {
    mkdir($opts["output_dir"], 0755);
}

$render->run();
?>
--EXPECTF--
Filename: variablelist-rendering.html
Content:
<div id="variablelist-rendering" class="chapter">

 <div class="section">
  <p class="para">%d. Variablelist with no role</p>
  <dl>
   
    <dt id="constant.variablelist-test-constant"><strong><code>VARIABLELIST_TEST_CONSTANT</code></strong></dt>
    <dd>
     <span class="simpara">
      Description of constant
     </span>
    </dd>
   
  </dl>
 </div>

 <div class="section">
  <p class="para">%d. Variablelist with role=&quot;constant_list&quot;</p>
  <table class="doctable table">
   <tr>
    <th>Constants</th>
    <th>Description</th>
   </tr>
   <tr>
    <td id="constant.variablelist-test-constant-in-constant-list"><strong><code>VARIABLELIST_TEST_CONSTANT_IN_CONSTANT_LIST</code></strong></td>
    <td>
     <span class="simpara">
      <code class="literal">literal</code> will add its own &quot;role&quot;
     </span>
    </td>
   </tr>
   <tr>
    <td id="constant.variablelist-test-constant-in-constant-list2"><strong><code>VARIABLELIST_TEST_CONSTANT_IN_CONSTANT_LIST2</code></strong></td>
    <td>
     <span class="simpara">
      Role should have not been overwritten by literal
     </span>
    </td>
   </tr>
  </table>
 </div>

</div>
