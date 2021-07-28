<?php
namespace phpdotnet\phd\Package\PHP;

use phpdotnet\phd\Format\Factory as FactoryAbstract;

class Factory extends FactoryAbstract
{
    private $formats = array(
        'xhtml'         => 'PHP\\ChunkedXHTML',
        'bigxhtml'      => 'PHP\\BigXHTML',
        'php'           => 'PHP\\Web',
        'howto'         => 'PHP\\HowTo',
        'manpage'       => 'PHP\\Manpage',
        'pdf'           => 'PHP\\PDF',
        'bigpdf'        => 'PHP\\BigPDF',
        'kdevelop'      => 'PHP\\KDevelop',
        'chm'           => 'PHP\\CHM',
        'tocfeed'       => 'PHP\\TocFeed',
        'epub'          => 'PHP\\Epub',
        'enhancedchm'   => 'PHP\\EnhancedCHM',
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
