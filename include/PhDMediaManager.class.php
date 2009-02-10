<?php
/* $Id$ */

/**
* Handles images (and other media objects) referenced in the docbook files.
* Copies over, returns proper filenames etc.
* Useful for xhtml output.
*
* @author Christian Weiske <cweiske@php.net>
*/
class PhDMediaManager
{
    /**
    * Directory where files are put
    *
    * @var string
    */
    public $output_dir = null;

    /**
    * If the image media directory exists
    *
    * @var boolean
    */
    protected $media_dir_exists = false;



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

        return $newpath;
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

        if (file_exists($fullpath)) {
            //no need to copy over again
            return;
        }

        if (!file_exists($filename)) {
            trigger_error('Image does not exist: ' . $filename, E_USER_WARNING);
            return;
        }

        if (!$this->media_dir_exists) {
            $dir = dirname($fullpath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $this->media_dir_exists = true;
        }

        copy($filename, $fullpath);
    }//protected function copyOver(..)

}//class PhDMediaManager

?>