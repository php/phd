--TEST--
Preface is not chunked by default
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";

$xmlFile = __DIR__ . "/data/preface_no_chunk.xml";

$config->forceIndex = true;
$config->xmlFile = $xmlFile;

$indexRepository = new IndexRepository(new \SQLite3(":memory:"));
$indexRepository->init();

$index = new TestIndex($indexRepository, $config, $outputHandler);
$render = new TestRender(new Reader($outputHandler), $config, null, $index);

$render->run();

$nfo = $index->getNfo();

echo "All IDs stored:\n";
var_dump(isset($nfo["preface-default"]));
var_dump(isset($nfo["preface-chunked"]));
var_dump(isset($nfo["preface-not-chunked"]));
var_dump(isset($nfo["test-chapter"]));

echo "Chunk status:\n";
echo "preface-default: ";
var_dump($nfo["preface-default"]["chunk"]);
echo "preface-chunked: ";
var_dump($nfo["preface-chunked"]["chunk"]);
echo "preface-not-chunked: ";
var_dump($nfo["preface-not-chunked"]["chunk"]);
echo "test-chapter: ";
var_dump($nfo["test-chapter"]["chunk"]);
?>
--EXPECT--
All IDs stored:
bool(true)
bool(true)
bool(true)
bool(true)
Chunk status:
preface-default: bool(false)
preface-chunked: bool(true)
preface-not-chunked: bool(false)
test-chapter: bool(true)
