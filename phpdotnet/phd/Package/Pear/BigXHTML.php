<?php
namespace phpdotnet\phd;

class Package_Pear_BigXHTML extends Package_Pear_XHTML {
    protected $formatname = "Pear-BigXHTML";
    protected $title = "Pear Manual";
   
    public function __construct() {
        parent::__construct();
        parent::registerFormatName($this->formatname);
        $this->chunked = false;
    }

    public function __destruct() {
        $this->close();
    }

    public function header() {
        return <<<HEADER
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>$this->title</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
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
        fwrite($this->fp, $this->footer(true));
        fclose($this->fp);
    }

    public function appendData($data) {
        if ($this->appendToBuffer) {
            $this->buffer .= $data;
            return;
        }
        if ($this->flags & Render::CLOSE) {
            fwrite($this->fp, $data);

            /* Append footer */
            fwrite($this->fp, $this->footer());
            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            fwrite($this->fp, $data."<hr />");
            $this->flags ^= Render::OPEN;
        } else {
            fwrite($this->fp, $data);
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
                if (!is_resource($this->fp)) {
                    $filename = Config::output_dir() . strtolower($this->getFormatName()) . '.' . $this->ext;
                    $this->fp = fopen($filename, "w+");
                    fwrite($this->fp, $this->header());
                }
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

    public function format_qandaset($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["qandaentry"] = array();
            $this->ostream = $this->fp;
            $this->fp = fopen("php://temp/maxmemory", "r+");
            return '';
        }

        $stream = $this->fp;
        $this->fp = $this->ostream;
        unset($this->ostream);
        rewind($stream);

        return parent::qandaset($stream);
    }

}

