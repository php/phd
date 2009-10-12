<?php
namespace phpdotnet\phd;
/* $Id$ */

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
        'php'           => 'Package_Generic_PHP',
    );

    public function __construct()
    {
        parent::setPackageName('Generic');
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

