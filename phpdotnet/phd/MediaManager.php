<?php
namespace phpdotnet\phd;
/* $Id$ */

/**
* Handles images (and other media objects) referenced in the docbook files.
* Copies over, returns proper filenames etc.
* Useful for xhtml output.
*
* @author Christian Weiske <cweiske@php.net>
*/
class MediaManager
{
    /**
    * Directory where files are put
    *
    * @var string
    */
    public $output_dir = null;

    /**
    * Path the media files are referenced relative to in html.
    * Trailing slash required.
    *
    * @var string
    */
    public $relative_ref_path = '';

    /**
    * Path the media files are referenced relative to in the xml file.
    * This is nearly always the directory the xml file is located in.
    *
    * @var string
    */
    public $relative_source_path = '';

    /**
    * If the image media directory exists
    *
    * @var boolean
    */
    protected $media_dir_exists = false;



    public function __construct($relative_source_path)
    {
        $this->relative_source_path = rtrim($relative_source_path, '/\\') . '/';
    }//public function __construct(..)



    /**
    * Handles the file:
    * - Generate proper filename (short version with only one directory)
    * - Copy file over to this directory
    * - Return filename relative to output directory
    *
    * @param string $filename File name relative to docbook document root
    *
    * @return string New file path that should be used in xhtml document
    */
    public function handleFile($filename)
    {
        $basename = basename($filename);
        $newname  = md5(substr($filename, 0, -strlen($basename))) . '-' . $basename;
        //FIXME: make images dynamic according to file type (e.g. video)
        $newpath  = 'images/' . $newname;

        $this->copyOver($filename, $newpath);

        return $this->relative_ref_path . $newpath;
    }//public function handleFile(..)



    /**
    * Copies the file referenced with $filename into
    * $output_dir/$newpath.
    *
    * @param string $filename Original filename
    * @param string $newpath  New path relative to output directory
    *
    * @return void
    */
    protected function copyOver($filename, $newpath)
    {
        $fullpath = $this->output_dir . '/' . $newpath;
        $fullfilename = $this->relative_source_path . $filename;
        $altfullfilename = $this->relative_source_path . '../' . $filename;

        if (file_exists($fullpath)) {
            //no need to copy over again
            return;
        }

        if (!file_exists($fullfilename)) {
            if (!file_exists($altfullfilename)) {
                trigger_error('Image does not exist: ' . $fullfilename, E_USER_WARNING);
                return;
            }

            $fullfilename = $altfullfilename;
        }

        if (!$this->media_dir_exists) {
            $dir = dirname($fullpath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $this->media_dir_exists = true;
        }

        copy($fullfilename, $fullpath);
    }//protected function copyOver(..)

}//class MediaManager

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

