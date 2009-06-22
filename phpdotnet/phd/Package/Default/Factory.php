<?php
namespace phpdotnet\phd;

class Package_Default_Factory extends Format_Factory {
    public function createXhtmlFormat() {
        return new Package_Default_ChunkedXHTML();
    }
    
    public function createBigXhtmlFormat() {
        return new Package_Default_BigXHTML();
    }
   
    public function createPHPFormat() {
        return new Package_Default_PHP();
    }

}

?>
