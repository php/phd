<?php
namespace phpdotnet\phd;

class Package_IDE_Factory extends Format_Factory {
    private $formats = array(
        'functions'         => 'Package_IDE_Functions',
        'funclist'          => 'Package_IDE_Funclist',
    );

    public function __construct() {
        parent::registerOutputFormats($this->formats);
    }
}

?>
