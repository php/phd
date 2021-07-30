<?php
namespace phpdotnet\phd\Package\IDE;

use phpdotnet\phd\Format\Factory as AbstractFactory;

class Factory extends AbstractFactory {
    private $formats = array(
        'xml'               => 'IDE\\XML',
        'funclist'          => 'IDE\\Funclist',
        'json'              => 'IDE\\JSON',
        'php'               => 'IDE\\PHP',
        'phpstub'           => 'IDE\\PHPStub',
        'sqlite'            => 'IDE\\SQLite',
    );

    /**
     * The package version
     */
    private $version = '@phd_ide_version@';

    public function __construct() {
        parent::setPackageName("IDE");
        parent::setPackageVersion($this->version);
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
