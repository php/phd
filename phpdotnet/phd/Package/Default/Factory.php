<?php
namespace phpdotnet\phd;

class Package_Default_Factory extends Format_Factory
{
    public function createXhtmlFormat() {
        return new Package_Default_XHTML();
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
