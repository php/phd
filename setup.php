#!@php_bin@
<?php
/*  $Id$ */
$base_revision = '$Rev$';

/* {{{ Find the $ROOT directory of PhD. @php_dir@ will be replaced by the PEAR
       package manager. If for some reaosn this hasn't happened, fallback to the
       dir containing this file */
$ROOT = "@php_dir@/phd";
if ($ROOT == "@php_dir"."@/phd") {
    $ROOT = dirname(__FILE__);
}
/* }}} */

require_once $ROOT . "/config.php";
require_once $ROOT . "/include/PhDSetupOptions.class.php";

?>
