--TEST--
Simplelist rendering 001 - Types and columns
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$formatclass = "TestChunkedXHTML";
$xml_file = __DIR__ . "/data/simplelist.xml";

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
Filename: simpelist.html
Content:
<div id="simpelist" class="chapter">

 <div class="section">
  <p class="para">1. Simplelist with no type</p>
  <ul class="simplelist">
   <li>First</li>
   <li>Second</li>
   <li>Third</li>
   <li>Fourth</li>
   <li>Fifth</li>
   <li>Sixth</li>
   <li>Seventh</li>
  </ul>
 </div>

 <div class="section">
  <p class="para">2. Simplelist with &quot;inline&quot; type</p>
  <span class="simplelist">First, Second, Third, Fourth, Fifth, Sixth, Seventh</span>
 </div>

 <div class="section">
  <p class="para">3. Simplelist with &quot;vert&quot; type, 3 columns</p>
  <table class="simplelist">
   <tbody>
    <tr>
     <td>First</td>
     <td>Fourth</td>
     <td>Seventh</td>
    </tr>
    <tr>
     <td>Second</td>
     <td>Fifth</td>
     <td></td>
    </tr>
    <tr>
     <td>Third</td>
     <td>Sixth</td>
     <td></td>
    </tr>
   </tbody>
  </table>
 </div>

 <div class="section">
  <p class="para">4. Simplelist with &quot;horiz&quot; type, 4 columns</p>
  <table class="simplelist">
   <tbody>
    <tr>
     <td>First</td>
     <td>Second</td>
     <td>Third</td>
     <td>Fourth</td>
    </tr>
    <tr>
     <td>Fifth</td>
     <td>Sixth</td>
     <td>Seventh</td>
     <td></td>
    </tr>
   </tbody>
  </table>
 </div>

</div>
