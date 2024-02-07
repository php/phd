<?php
namespace phpdotnet\phd;

class Package_Generic_Factory extends Format_Factory
{
    /**
     * List of cli format names and their corresponding class.
     *
     * @var array
     */
    private $formats = array(
        'xhtml'         => 'Package_Generic_ChunkedXHTML',
        'bigxhtml'      => 'Package_Generic_BigXHTML',
        'manpage'       => 'Package_Generic_Manpage',
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


