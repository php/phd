<?php
namespace phpdotnet\phd\Package\PEAR;

use phpdotnet\phd\Package\PEAR\BigXHTML;
use phpdotnet\phd\Package\PEAR\CHM;
use phpdotnet\phd\Package\PEAR\ChunkedXHTML;

class Factory extends \phpdotnet\phd\Format\Factory {
    private $formats = array(
        'xhtml'         => ChunkedXHTML::class,
        'bigxhtml'      => BigXHTML::class,
        'php'           => Web::class,
        'chm'           => CHM::class,
        'tocfeed'       => TocFeed::class,
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
