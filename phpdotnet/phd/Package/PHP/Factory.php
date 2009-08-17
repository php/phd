<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PHP_Factory extends Format_Factory {
    private $formats = array(
        'xhtml'         => 'Package_PHP_ChunkedXHTML',
        'bigxhtml'      => 'Package_PHP_BigXHTML',
        'php'           => 'Package_PHP_Web',
        'howto'         => 'Package_PHP_HowTo',
        'manpage'       => 'Package_PHP_Functions',
        'pdf'           => 'Package_PHP_PDF',
        'bigpdf'        => 'Package_PHP_BigPDF',
        'kdevelop'      => 'Package_PHP_KDevelop',
        'chm'           => 'Package_PHP_CHM',
    );
    
    public function __construct() {
        parent::setPackageName("PHP");
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

