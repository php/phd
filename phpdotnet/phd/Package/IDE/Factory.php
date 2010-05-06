<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_IDE_Factory extends Format_Factory {
    private $formats = array(
        'xml'               => 'Package_IDE_XML',
        'funclist'          => 'Package_IDE_Funclist',
        'json'              => 'Package_IDE_JSON',
        'php'               => 'Package_IDE_PHP',
    );

    public function __construct() {
        parent::setPackageName("IDE");
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

