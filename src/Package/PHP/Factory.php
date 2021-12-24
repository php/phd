<?php
namespace phpdotnet\phd\Package\PHP;

use phpdotnet\phd\Package\PHP\BigXHTML;
use phpdotnet\phd\Package\PHP\CHM;
use phpdotnet\phd\Package\PHP\ChunkedXHTML;
use phpdotnet\phd\Package\PHP\EnhancedCHM;
use phpdotnet\phd\Package\PHP\Epub;

class Factory extends \phpdotnet\phd\Format\Factory {
    private $formats = array(
        'xhtml'         => ChunkedXHTML::class,
        'bigxhtml'      => BigXHTML::class,
        'php'           => Web::class,
        'howto'         => HowTo::class,
        'manpage'       => Manpage::class,
        'kdevelop'      => KDevelop::class,
        'chm'           => CHM::class,
        'tocfeed'       => TocFeed::class,
        'epub'          => Epub::class,
        'enhancedchm'   => EnhancedCHM::class,
    );

    /**
     * The package version
     */
    private $version = '@phd_php_version@';

    public function __construct() {
        parent::setPackageName("PHP");
        parent::setPackageVersion($this->version);
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
