--TEST--
IDE type rendering 001 - Union, intersection and DNF types
--CONFLICTS--
all
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";
require_once __DIR__ . "/../../helpers.php";
ensureOutputFolder();

$config->xmlFile = __DIR__ . "/data/type_rendering.xml";
$config->phpwebVersionFilename = __DIR__ . "/data/version.xml";
$config->outputDir = dirname(__DIR__, 2) . "/output/";

$render = new TestRender(new Reader($outputHandler), $config);
$render->attach(new Package_IDE_JSON($config, $outputHandler));
$render->attach(new Package_IDE_XML($config, $outputHandler));
$render->run();

foreach (["array_key_first", "type_rendering"] as $function) {
    $json = json_decode(file_get_contents($config->outputDir . "ide-json/" . $function . ".json"), true);
    echo $function, " return: ", $json["return"]["type"], "\n";
    foreach ($json["params"] as $param) {
        echo $function, " param ", $param["name"], ": ", $param["type"], "\n";
    }
}

$xml = simplexml_load_file($config->outputDir . "ide-xml/type_rendering.xml");
foreach ($xml->params->param as $param) {
    echo "XML param ", $param->name, ": ", $param->type, "\n";
}
?>
--CLEAN--
<?php
require_once __DIR__ . "/../../helpers.php";
\phpdotnet\phd\removeOutputFolder();
?>
--EXPECT--
array_key_first return: int|string|null
array_key_first param array: array
type_rendering return: void
type_rendering param union: array|string
type_rendering param intersection: Countable&Traversable
type_rendering param dnf: (Countable&Traversable)|null
XML param union: array|string
XML param intersection: Countable&Traversable
XML param dnf: (Countable&Traversable)|null
