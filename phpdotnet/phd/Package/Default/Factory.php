<?php

require $ROOT . "/packages/Default/xhtml.php";
//require $ROOT . "/packages/Default/bigxhtml.php";
//require $ROOT . "/packages/Default/php.php";

class DefaultFactory extends PhDFormatFactory {
    public function createXhtmlFormat() {
        return new DefaultXHTMLFormat();
    }
/*    
    public function createBigXhtmlFormat() {
        return new DefaultBigXHTMLFormat();
    }
    
    public function createPHPFormat() {
        return new DefaultPHPFormat();
    }
*/
}

?>
