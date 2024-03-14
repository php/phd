--TEST--
Default options handler 001 - List packages - short option
--ARGS--
-l
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
--EXPECT--
Supported packages:
	Generic
		xhtml
		bigxhtml
		manpage
	IDE
		xml
		funclist
		json
		php
		phpstub
		sqlite
	PEAR
		xhtml
		bigxhtml
		php
		chm
		tocfeed
	PHP
		xhtml
		bigxhtml
		php
		howto
		manpage
		pdf
		bigpdf
		kdevelop
		chm
		tocfeed
		epub
		enhancedchm
