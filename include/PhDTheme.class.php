<?php
/*  $Id$ */
require $ROOT. "/include/PhDMediaManager.class.php";

abstract class PhDTheme extends PhDHelper implements iPhDTheme {
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
    * @see PhDThemeXhtml::postConstruct()
    */
    public function postConstruct($relative_path) {}



    public function registerFormat($format) {
        $this->format = $format;
    }
}

interface iPhDTheme {
    public function appendData($data, $isChunk);
}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

