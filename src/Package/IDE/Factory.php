<?php
namespace phpdotnet\phd\Package\IDE;

class Factory extends \phpdotnet\phd\Format\Factory {
    private $formats = array(
        'xml'               => XML::class,
        'funclist'          => Funclist::class,
        'json'              => JSON::class,
        'php'               => PHP::class,
        'phpstub'           => PHPStub::class,
        'sqlite'            => SQLite::class,
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
