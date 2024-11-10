--TEST--
Default options handler 005 - Show version - short option
--ARGS--
-V
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
%s	Generic: %s
%s	IDE: %s
%s	PEAR: %s
%s	PHP: %s
%sPHP Version: %s
%sCopyright(c) %d-%d The PHP Documentation Group%s
