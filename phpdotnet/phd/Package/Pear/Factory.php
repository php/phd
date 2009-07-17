<?php
namespace phpdotnet\phd;

class Package_Pear_Factory extends Format_Factory {
    private $formats = array(
        'xhtml'         => 'Package_Pear_ChunkedXHTML',
        'bigxhtml'      => 'Package_Pear_BigXHTML',
        'php'           => 'Package_Pear_Web',
    );
    
    public function __construct() {
        parent::registerOutputFormats($this->formats);
    }
}

?>
