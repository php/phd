<?php
namespace phpdotnet\phd;

/*  $Id$ */

abstract class Theme extends Helper implements iTheme
{
    protected $format;



    /**
    * Creates the theme object.
    *
    * @param array $a Array with ID array as first value, ref array as second.
    */
    public function __construct(array $a)
    {
        parent::__construct($a);
    }



    /**
    * Overwritten in xhtml themes only.
    *
    * @see Theme_XHTML::postConstruct()
    */
    public function postConstruct() {}



    public function registerFormat($format) {
        $this->format = $format;
    }
}

interface iTheme {
    public function appendData($data, $isChunk);
}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

