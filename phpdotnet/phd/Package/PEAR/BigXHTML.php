<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PEAR_BigXHTML extends Package_PEAR_XHTML {
    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PEAR-BigXHTML");
        $this->setTitle("PEAR Manual");
        $this->setExt("html");
        $this->setChunked(false);
    }

    public function __destruct() {
        $this->close();
    }

    public function header() {
        $style = $this->createCSSLinks();
        return <<<HEADER
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>{$this->getTitle()}</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
{$style}
 </head>
 <body>
  <div id="doc3">
   <div id="body">
HEADER;
    }

    public function footer($eof = false) {
        return !$eof ?  "\n<hr />\n" : "</div></div></body>\n</html>";
    }

    public function close() {
        if ($this->getFileStream()) {
            fwrite($this->getFileStream(), $this->footer(true));
            fclose($this->getFileStream());
        }
    }

    public function createFileName() {
        return Config::output_dir() . strtolower($this->getFormatName()) . '.' . $this->getExt();
    }

    public function createOutputFile() {
        if (!is_resource($this->getFileStream())) {
            $this->setFileStream(fopen($this->createFileName(), "w+"));
            fwrite($this->getFileStream(), $this->header());
        }
    }

    public function appendData($data) {
        if ($this->appendToBuffer) {
            $this->buffer .= $data;
            return;
        }
        if ($this->flags & Render::CLOSE) {
            fwrite($this->getFileStream(), $data);

            /* Append footer */
            fwrite($this->getFileStream(), $this->footer());
            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            fwrite($this->getFileStream(), $data."<hr />");
            $this->flags ^= Render::OPEN;
        } else {
            fwrite($this->getFileStream(), $data);
        }
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
            $this->flags = $val;
            break;

        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap(parent::getDefaultElementMap());
                $this->registerTextMap(parent::getDefaultTextMap());
            }            
            break;

        case Render::INIT:
            if ($val) {
                $this->postConstruct();
                if (Config::css()) {
                    $this->fetchStylesheet();
                }
                $this->createOutputFile();
            } 
            break;

        case Render::VERBOSE:
            v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
            break;
        }
    }

    public function createLink($for, &$desc = null, $type = self::SDESC) {
        $retval = '#' . $for;
        if (isset($this->indexes[$for])) {
            $result = $this->indexes[$for];
            if ($type === self::SDESC) {
                $desc = $result["sdesc"] ?: $result["ldesc"];
            } else {
                $desc = $result["ldesc"] ?: $result["sdesc"];
            }
        }
        return $retval;
    }
}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

