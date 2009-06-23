<?php
namespace phpdotnet\phd;

class Package_Pear_Factory extends Format_Factory {

    public function createBigXhtmlFormat() {
        return new Package_Pear_BigXHTML();
    }
}

?>
