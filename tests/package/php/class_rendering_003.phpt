--TEST--
Class rendering 003 - compare output of phpdoc:classref and reference element with role="class" rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFilePhpdoc = __DIR__ . "/data/class_rendering_001.xml";

$config->xmlFile = $xmlFilePhpdoc;

$formatPhpdoc = new TestPHPChunkedXHTML($config, $outputHandler);
$renderPhpdoc = new TestRender(new Reader($outputHandler), $config, $formatPhpdoc);

ob_start();
$renderPhpdoc->run();
$phpdocOutput = ob_get_clean();


$xmlFileReferenceWithRole = __DIR__ . "/data/class_rendering_002.xml";

$config->xmlFile = $xmlFileReferenceWithRole;

$formatReferenceWithRole = new TestPHPChunkedXHTML($config, $outputHandler);
$renderReferenceWithRole = new TestRender(new Reader($outputHandler), $config, $formatReferenceWithRole);

ob_start();
$renderReferenceWithRole->run();
$referenceWithRoleOutput = ob_get_clean();

var_dump($phpdocOutput === $referenceWithRoleOutput);
?>
--EXPECT--
bool(true)
