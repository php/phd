--TEST--
Reader 001 - Read contents of current empty and non-empty elements
--FILE--
<?php
namespace phpdotnet\phd;

use XMLReader;

require_once __DIR__ . "/../setup.php";

$xml = <<<XML
<section>
 <emptyNode></emptyNode>
 <nonEmptyNode>
  <title>Title here</title>
  <content>Some content with <node>nodes</node></content>
 </nonEmptyNode>
</section>

XML;

$reader = new Reader;
$reader->XML($xml);

echo "Read current node contents\n";
while ($reader->read()) {
    if ($reader->nodeType === XMLReader::ELEMENT
        && ($reader->name === "emptyNode" || $reader->name === "nonEmptyNode")
    ) {
        var_dump($reader->readContent());
    }
}

$reader->XML($xml);
$reader->read();

echo "\nRead named node contents\n";
var_dump($reader->readContent("nonEmptyNode"));
var_dump($reader->readContent("emptyNode"));
?>
--EXPECT--
Read current node contents
string(0) ""
string(41) "
  Title here
  Some content with nodes
 "

Read named node contents
string(45) "
 
 
  Title here
  Some content with nodes
 "
string(1) "
"
