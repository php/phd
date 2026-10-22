--TEST--
Acronym info 001 - Acronyms are read from acronym.expansion.* entity elements
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$acronyms = Package_PHP_XHTML::generateAcronymInfo(__DIR__ . "/data/entities.acronyms.ent");

var_dump($acronyms);
?>
--EXPECT--
array(4) {
  ["API"]=>
  string(33) "Application Programming Interface"
  ["CSPRNG"]=>
  string(54) "Cryptographically Secure PseudoRandom Number Generator"
  ["PHP"]=>
  string(27) "PHP: Hypertext Preprocessor"
  ["cURL"]=>
  string(18) "Client URL Library"
}
