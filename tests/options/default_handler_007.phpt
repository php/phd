--TEST--
Default options handler 007 - Save config (short option) and quit (short option)
--ARGS--
--docbook tests/options/default_handler_007.phpt -S -Q
--SKIPIF--
<?php
if (file_exists(__DIR__ . "/../../phd.config.php")) {
    die("Skipped: existing phd.config.php file will overwrite command line options.");
}
?>
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../render.php";
?>
--CLEAN--
<?php
unlink(__DIR__ . "/../../phd.config.php");
?>
--EXPECTF--
%s[%d:%d:%d - Heads up              ]%s Writing the config file
