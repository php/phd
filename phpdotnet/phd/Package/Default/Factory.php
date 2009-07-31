<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_Default_Factory extends Format_Factory {
    private $formats = array(
        'xhtml'         => 'Package_Default_ChunkedXHTML',
        'bigxhtml'      => 'Package_Default_BigXHTML',
        'php'           => 'Package_Default_PHP',
    );

    public function __construct() {
        parent::setPackageName("Default");
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

