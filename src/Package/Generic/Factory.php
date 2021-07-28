<?php
namespace phpdotnet\phd\Package\Generic;

use phpdotnet\phd\Format\Factory as AbstractFactory;

class Factory extends AbstractFactory
{
    /**
     * List of cli format names and their corresponding class.
     *
     * @var array
     */
    private $formats = array(
        'xhtml'         => 'Generic\\ChunkedXHTML',
        'bigxhtml'      => 'Generic\\BigXHTML',
        'manpage'       => 'Generic\\Manpage',
    );

    /**
     * The package version
     */
    private $version = '@phd_generic_version@';

    public function __construct()
    {
        parent::setPackageName('Generic');
        parent::setPackageVersion($this->version);
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
