#!@php_bin@
<?php
/*  $Id$ */

/* {{{ Find the $ROOT directory of PhD. @php_dir@ will be replaced by the PEAR
       package manager. If for some reaosn this hasn't happened, fallback to the
       dir containing this file */
$ROOT = "@php_dir@/phd";
if ($ROOT == "@php_dir"."@/phd") {
    $ROOT = dirname(__FILE__);
}
/* }}} */

require_once ($ROOT . "/config.php");

(include $ROOT . "/include/PhDCompileOptions.class.php")
    or die("Invalid configuration.\nThis should never happen, did you edit config.php yourself?\nRe-run phd-setup.\n");

?>
