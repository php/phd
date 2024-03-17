--TEST--
Default options handler 006 - Show version - long option
--ARGS--
--version
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
--EXPECTF--
%sPhD Version: %s
	%sGeneric%s: %s
	%sIDE%s: %s
	%sPEAR%s: %s
	%sPHP%s: %s
%sPHP Version: %s
%sCopyright(c) %d-%d The PHP Documentation Group%s
