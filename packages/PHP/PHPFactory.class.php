<?php

require $ROOT . "/packages/PHP/xhtml.php";
require $ROOT . "/packages/PHP/bigxhtml.php";
require $ROOT . "/packages/PHP/php.php";

class PHPFactory extends PhDFormatFactory {
    public function createXhtmlFormat() {
        return new PhDXHTMLFormat();
    }
    
    public function createBigXhtmlFormat() {
        return new PhDBigXHTMLFormat();
    }
    
    public function createPHPFormat() {
        return new PhDPHPFormat();
    }
}

?>
