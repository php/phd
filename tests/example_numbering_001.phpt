--TEST--
Example numbering 001 - indexing and rendering examples with and without an xml:id
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/setup.php";

$xml_file = __DIR__ . "/data/example_numbering_001.xml";

$config->setForce_index(true);
$config->setXml_file($xml_file);

$indexRepository = new IndexRepository(new \SQLite3(":memory:"));
$indexRepository->init();
$config->set_indexcache($indexRepository);

$index = new TestIndex($indexRepository);

$render = new TestRender(new Reader, $config, null, $index);

$render->run();

$indexes = array_keys($index->getNfo());

echo "Indexes stored:\n";

var_dump($indexes);

$format = new TestGenericChunkedXHTML;

$render = new TestRender(new Reader, $config, $format);

$render->run();

?>
--EXPECT--
Indexes stored:
array(6) {
  [0]=>
  string(17) "example-numbering"
  [1]=>
  string(9) "example-1"
  [2]=>
  string(9) "example-2"
  [3]=>
  string(16) "third-example-id"
  [4]=>
  string(9) "example-4"
  [5]=>
  string(9) "example-5"
}
Filename: example-numbering.html
Content:
<div id="example-numbering" class="chapter">
 <div class="section">
  <div class="example" id="example-1">
    <p><strong>Example #1 - 1. example without an xml:id</strong></p>
  </div>
 </div>

 <div class="section">
  <div class="example" id="example-2">
    <p><strong>Example #2 - 2. example without an xml:id</strong></p>
  </div>
 </div>

 <div class="section">
  <div class="example" id="third-example-id">
    <p><strong>Example #3 - 3. example with an xml:id</strong></p>
  </div>
 </div>

 <div class="section">
  <div class="example" id="example-4">
    <p><strong>Example #4 - 4. example without an xml:id</strong></p>
  </div>
 </div>

 <div class="section">
  <div class="example" id="example-5">
    <p><strong>Example #5 - 5. example without an xml:id</strong></p>
  </div>
 </div>
</div>
