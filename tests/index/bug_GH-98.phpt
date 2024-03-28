--TEST--
Bug GH-98 - Non-chunked section is not linked
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";

$xml_file = __DIR__ . "/data/bug_GH-98.xml";

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

var_dump(in_array("non-chunked.element.id", $indexes));
var_dump(in_array("another.non-chunked.element.id", $indexes));
var_dump(in_array("chunked.element.id", $indexes));
var_dump(in_array("another.chunked.element.id", $indexes));
var_dump(in_array("bug-GH-98", $indexes));

unlink(Config::output_dir() . 'index.sqlite');
?>
--EXPECT--
Indexes stored:
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
