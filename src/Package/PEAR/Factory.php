<?php
namespace phpdotnet\phd\Package\PEAR;

use phpdotnet\phd\Format\Factory as FactoryAbstract;

class Factory extends FactoryAbstract
{
    private $formats = array(
        'xhtml'         => 'PEAR\\ChunkedXHTML',
        'bigxhtml'      => 'PEAR\\BigXHTML',
        'php'           => 'PEAR\\Web',
        'chm'           => 'PEAR\\CHM',
        'tocfeed'       => 'PEAR\\TocFeed',
    );

    /**
     * The package version
     */
    private $version = '@phd_pear_version@';

    public function __construct() {
        parent::setPackageName("PEAR");
        parent::setPackageVersion($this->version);
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
