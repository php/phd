<?php
/*  $Id$ */
require $ROOT. "/include/PhDMediaManager.class.php";

abstract class PhDTheme extends PhDHelper implements iPhDTheme {
    protected $format;

    /**
    * Media manager object
    *
    * @var PhDMediaManager
    */
    public $mediamanager = null;



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
    * Called after the constructor finished.
    * This is needed since themes set their outputdir and outputfile
    * in the constructor. That file/dir is used for mediamanager.
    * That means we cannot instantiate and complete the manager in our
    * constructor centrally.
    *
    * Each theme needs its own media manager, since the manager contains
    * the output path.
    *
    * @return void
    */
    public function postConstruct()
    {
        $this->mediamanager = new PhDMediaManager();

        if (isset($this->outputdir)) {
            $this->mediamanager->output_dir = $this->outputdir;
        } else {
            $this->mediamanager->output_dir    = $this->outputfile . '-data/';
            $this->mediamanager->relative_path = basename($this->mediamanager->output_dir) . '/';
        }
    }//public function postConstruct()



    public function registerFormat($format) {
        $this->format = $format;
        $this->format->registerTheme($this);
    }
}

interface iPhDTheme {
    public function appendData($data, $isChunk);
}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

