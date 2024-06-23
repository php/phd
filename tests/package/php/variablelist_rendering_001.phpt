--TEST--
Variablelist rendering 001
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/variablelist_rendering_001.xml";

$config->setXml_file($xml_file);

$format = new TestPHPChunkedXHTML($config);
$render = new TestRender(new Reader, $config, $format);

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
