<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_IDE_Funclist extends Format {
    protected $elementmap   = array(
        'refname'               => 'format_refname',
        'set'                   => 'format_set',
    );
    protected $textmap      = array(
        'refname'               => 'format_refname_text',
    );

    protected $isFunctionRefSet = false;
    protected $isRefname = false;

    public function __construct() {
        $this->registerFormatName("IDE-Funclist");
        $this->setExt("txt");
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {}
    public function UNDEF($open, $name, $attrs, $props) {}
    public function TEXT($value) {}
    public function CDATA($value) {}        
    public function transformFromMap($open, $tag, $name, $attrs, $props) {}    

    public function appendData($data) {
        if ($this->isFunctionRefSet && $this->isRefname) {
            fwrite($this->getFileStream(), $data ? $data . "\n" : "");
        }
    }

    public function update($event, $value = null) {
        switch($event) {
        case Render::STANDALONE:
            $this->registerElementMap($this->elementmap);
            $this->registerTextMap($this->textmap);
            break;
        case Render::INIT:
            if ($value) {
                if (!is_resource($this->getFileStream())) {
                    $filename = Config::output_dir() . strtolower($this->getFormatName()) . '.' . $this->getExt();
                    $this->setFileStream(fopen($filename, "w+"));
                }
            } 
            break;
        case Render::FINALIZE:            
            fclose($this->getFileStream());
            break;
        case Render::VERBOSE:            
            v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
            break;
        }
    }

    public function format_set($open, $name, $attrs, $props) {
        if (isset($attrs[Reader::XMLNS_XML]["id"]) && $attrs[Reader::XMLNS_XML]["id"] == "funcref") {
            $this->isFunctionRefSet = $open;
        }
        return "";
    }

    public function format_refname($open, $name, $attrs, $props) {
        $this->isRefname = $open;
        return ""; 
    }

    public function format_refname_text($value, $tag) {
        return str_replace(array("::", "->", "()"), array(".", ".", ""), trim($value));
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

