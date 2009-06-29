<?php
namespace phpdotnet\phd;

class Package_Pear_Factory extends Format_Factory {
    public function createBigXhtmlFormat() {
        return new Package_Pear_BigXHTML();
    }    

    public function createXhtmlFormat() {
        return new Package_Pear_ChunkedXHTML();
    }

    public function createPHPFormat() {
        return new Package_Pear_Web();
    }
    
    public function createChmFormat() {
        return new Package_Pear_CHM();
    }

}

?>
