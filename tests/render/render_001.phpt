--TEST--
Render 001 - Smoke test render.php
--ARGS--
--memoryindex --forceindex --package PHP --format php --docbook tests/render/data/render_001.xml
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../render.php";
?>
--EXPECTF--
%s[%d:%d:%d - Indexing              ]%s Indexing...
%s[%d:%d:%d - Rendering Style       ]%s Running full build
%s[%d:%d:%d - Indexing              ]%s Indexing done
%s[%d:%d:%d - Rendering Style       ]%s Running full build
%s[%d:%d:%d - Rendering Format      ]%s Starting PHP-Web rendering
%s[%d:%d:%d - Rendering Format      ]%s Writing search indexes..
%s[%d:%d:%d - Rendering Format      ]%s Index written
%s[%d:%d:%d - Rendering Format      ]%s Combined Index written
%s[%d:%d:%d - Rendering Format      ]%s Finished rendering
