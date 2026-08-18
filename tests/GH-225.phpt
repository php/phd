--TEST--
GH-225 - SaveConfig tries to overwrite readonly property
--ARGS--
--docbook tests/data/bug-GH-225.xml --quit
--CONFLICTS--
all
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/helpers.php";
ensureOutputFolder();

if (\file_exists(__DIR__ . "/../phd.config.php")) {
    \unlink(__DIR__ . "/../phd.config.php");
}

\file_put_contents(__DIR__ . "/../phd.config.php",    
"<?php
return array (
  'copyright' => 'Should not be imported',
);");

require_once __DIR__ . "/../render.php";
?>
--CLEAN--
<?php
\unlink(__DIR__ . "/../phd.config.php");

require_once __DIR__ . "/helpers.php";
\phpdotnet\phd\removeOutputFolder();
?>
--EXPECTF--
%s[%d:%d:%d - Heads up              ]%s Loaded config from existing file
