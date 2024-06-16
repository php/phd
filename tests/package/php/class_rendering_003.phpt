--TEST--
Class rendering 003 - compare output of phpdoc:classref and reference element with role="class" rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_filePhpdoc = __DIR__ . "/data/class_rendering_001.xml";

$config->setXml_file($xml_filePhpdoc);

$formatPhpdoc = new TestPHPChunkedXHTML;
$renderPhpdoc = new TestRender(new Reader, $config, $formatPhpdoc);

ob_start();
$renderPhpdoc->run();
$phpdocOutput = ob_get_clean();


$xml_fileReferenceWithRole = __DIR__ . "/data/class_rendering_002.xml";

$config->setXml_file($xml_fileReferenceWithRole);

$formatReferenceWithRole = new TestPHPChunkedXHTML;
$renderReferenceWithRole = new TestRender(new Reader, $config, $formatReferenceWithRole);

ob_start();
$renderReferenceWithRole->run();
$referenceWithRoleOutput = ob_get_clean();

var_dump($phpdocOutput === $referenceWithRoleOutput);
?>
--EXPECT--
bool(true)
