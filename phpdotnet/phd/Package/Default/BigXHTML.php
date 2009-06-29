<?php
namespace phpdotnet\phd;

class Package_Default_BigXHTML extends Package_Default_XHTML {
    protected $formatname = "Big-XHTML";
    protected $title = "Index";

    public function __construct() {
        parent::__construct();
        parent::registerFormatName($this->formatname);
        $this->chunked = false;
    }

    public function __destruct() {
        $this->close();
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
            fwrite($this->fp, "\n".$data);
            $this->flags ^= Render::OPEN;
        } else {
            fwrite($this->fp, $data);
        }

    }

    public function header() {
        return <<<HEADER
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>$this->title</title>
</head>
<body>
HEADER;
    }

    public function footer($eof = false) {
        return !$eof ?  "\n<hr />\n" : "</body>\n</html>";
    }

    public function close() {
        fwrite($this->fp, $this->footer(true));
        fclose($this->fp);
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

}

?>
