<?php
namespace phpdotnet\phd;

class Package_Default_Factory extends Format_Factory {
    private $formats = array(
        'xhtml'         => 'Package_Default_ChunkedXHTML',
        'bigxhtml'      => 'Package_Default_BigXHTML',
        'php'           => 'Package_Default_PHP',
    );

    public function __construct() {
        parent::registerOutputFormats($this->formats);
    }
}

?>
