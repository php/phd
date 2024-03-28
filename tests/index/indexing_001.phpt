--TEST--
Indexing 001 - Basic indexing
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";

$xml_file = __DIR__ . "/data/indexing_001.xml";

Config::init([
    "force_index"    => true,
    "xml_file" => $xml_file,
]);

$indexRepository = new IndexRepository(new \SQLite3(
    Config::output_dir() . 'index.sqlite',
    \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE
));
$indexRepository->init();

$index = new TestIndex($indexRepository);
$render = new TestRender(new Reader, new Config, null, $index);

$render->run();

$indexes = array_keys($index->getNfo());

echo "Indexes stored:\n";

var_dump($indexes);

unlink(Config::output_dir() . 'index.sqlite');
?>
--EXPECT--
Indexes stored:
array(15) {
  [0]=>
  string(5) "index"
  [1]=>
  string(8) "bookinfo"
  [2]=>
  string(7) "authors"
  [3]=>
  string(9) "copyright"
  [4]=>
  string(6) "manual"
  [5]=>
  string(7) "preface"
  [6]=>
  string(12) "contributors"
  [7]=>
  string(13) "mongodb.setup"
  [8]=>
  string(20) "mongodb.requirements"
  [9]=>
  string(13) "chapterInBook"
  [10]=>
  string(12) "introduction"
  [11]=>
  string(12) "intro-whatis"
  [12]=>
  string(14) "apcu.constants"
  [13]=>
  string(19) "reserved.interfaces"
  [14]=>
  string(17) "class.traversable"
}
