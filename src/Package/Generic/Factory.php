<?php
namespace phpdotnet\phd\Package\Generic;

use phpdotnet\phd\Package\Generic\BigXHTML;
use phpdotnet\phd\Package\Generic\ChunkedXHTML;

class Factory extends \phpdotnet\phd\Format\Factory
{
    /**
     * List of cli format names and their corresponding class.
     *
     * @var array
     */
    private $formats = array(
        'xhtml'         => ChunkedXHTML::class,
        'bigxhtml'      => BigXHTML::class,
        'manpage'       => Manpage::class,
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
