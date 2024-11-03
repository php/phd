--TEST--
Reader_Partial 001 - Read and skip elements based on their ID
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";

$xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<section>
 <emptyNode xml:id="renderThis1"></emptyNode>

 <nonEmptyNode xml:id="renderThis2">
  <title>Title here</title>
  <content>Some content with <node xml:id="skipThis1">nodes</node></content>
 </nonEmptyNode>

 <anotherNonEmptyNode>
  <content></content>
  <content xml:id="renderThis3">
   Some more content <node xml:id="skipThis2">but this is not read</node>
  </content>
  <content></content>
  <content xml:id="renderThis4">
  Some more content <node>and this is read</node>
  </content>
 </anotherNonEmptyNode>
</section>

XML;

try {
    $reader = new Reader_Partial($outputHandler, []);
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

$renderIds = [
    "renderThis1" => 1,
    "renderThis2" => 1,
    "renderThis3" => 1,
    "renderThis4" => 1
];
$skipIds = [
    "skipThis1" => 1,
    "skipThis2" => 1
];
$parentIds = [];

$reader = new Reader_Partial($outputHandler, $renderIds, $skipIds, $parentIds);
$reader->XML($xml);

while ($reader->read()) {
}
?>
--EXPECTF--
string(26) "Didn't get any IDs to seek"
%s[%d:%d:%d - Partial Reading       ]%s Starting renderThis1...
%s[%d:%d:%d - Partial Reading       ]%s renderThis1 done
%s[%d:%d:%d - Partial Reading       ]%s Starting renderThis2...
%s[%d:%d:%d - Partial Reading       ]%s Skipping skipThis1...
%s[%d:%d:%d - Partial Reading       ]%s skipThis1 done
%s[%d:%d:%d - Partial Reading       ]%s renderThis2 done
%s[%d:%d:%d - Partial Reading       ]%s Starting renderThis3...
%s[%d:%d:%d - Partial Reading       ]%s Skipping skipThis2...
%s[%d:%d:%d - Partial Reading       ]%s skipThis2 done
%s[%d:%d:%d - Partial Reading       ]%s renderThis3 done
%s[%d:%d:%d - Partial Reading       ]%s Starting renderThis4...
%s[%d:%d:%d - Partial Reading       ]%s renderThis4 done
