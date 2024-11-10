--TEST--
GH-87 Broken links for constants in table rows
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/setup.php";

$xml_file = __DIR__ . "/data/bug_GH-87.xml";

$config->setForce_index(true);
$config->setXml_file($xml_file);

$indexRepository = new IndexRepository(new \SQLite3(":memory:"));
$indexRepository->init();
$config->set_indexcache($indexRepository);

$index = new TestIndex($indexRepository, $config, $outputHandler);

$render = new TestRender(new Reader($outputHandler), $config, null, $index);

$render->run();

$render = new TestRender(new Reader($outputHandler), $config);

$format = new TestGenericChunkedXHTML($config, $outputHandler);

$render->attach($format);

$render->run();

?>
--EXPECT--
Filename: constants.html
Content:
<div id="constants" class="chapter">
 <div class="section">
  <p class="para">Constant within a table (GH-87)</p>
  <p class="para">
   <table class="doctable informaltable">
    
     <thead>
      <tr>
       <th>Header</th>
      </tr>

     </thead>

     <tbody class="tbody">
      <tr id="constant.defined">
       <td><strong><code>CONSTANT_IS_DEFINED</code></strong></td>
      </tr>

     </tbody>
    
   </table>

  </p>
 </div>
 <div class="section">
  <p class="para">
   <a href="constants.html#constant.defined" class="link">CONSTANT_IS_DEFINED</a>
  </p>
 </div>
</div>
