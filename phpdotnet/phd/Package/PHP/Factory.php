<?php
namespace phpdotnet\phd;

class Package_PHP_Factory extends Format_Factory {

    public function createBigXhtmlFormat() {
        return new Package_PHP_BigXHTML();
    }    

    public function createXhtmlFormat() {
        return new Package_PHP_ChunkedXHTML();
    }

    public function createPHPFormat() {
        return new Package_PHP_Web();
    }    

    public function createHowToFormat() {
        return new Package_PHP_HowTo();
    }

    public function createManpageFormat() {
        return new Package_PHP_Functions();
    }

    public function createKDevelopFormat() {
        return new Package_PHP_KDevelop();
    }

    public function createPDFFormat() {
        return new Package_PHP_PDF();
    }

    public function createBigPDFFormat() {
        return new Package_PHP_BigPDF();
    }
/*    
    public function createChmFormat() {
        return new Package_PHP_CHM();
    }
*/
}

?>
