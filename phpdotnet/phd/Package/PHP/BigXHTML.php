<?php
namespace phpdotnet\phd;

class Package_PHP_BigXHTML extends Package_PHP_XHTML {
    public function __construct(Config $config) {
        parent::__construct($config);
        $this->registerFormatName("PHP-BigXHTML");
        $this->setTitle("PHP Manual");
        $this->setChunked(false);
    }

    public function __destruct() {
        $this->close();
    }

    public function header() {
        $style = $this->createCSSLinks();
        $style = $style ? "\n".$style : false;
        return <<<HEADER
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>{$this->getTitle()}</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">{$style}
 </head>
 <body>
HEADER;
    }

    public function footer($eof = false) {
        return !$eof ?  "\n<hr />\n" : "</body>\n</html>";
    }

    public function close() {
        if ($this->getFileStream()) {
            fwrite($this->getFileStream(), $this->footer(true));
            fclose($this->getFileStream());
        }
    }

    public function createFileName() {
        $filename = $this->config->output_dir();
        if ($this->config->output_filename()) {
            $filename .= $this->config->output_filename();
        } else {
            $filename .= strtolower($this->getFormatName()) . $this->getExt();
        }
        return $filename;
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
            fwrite($this->getFileStream(), $this->footer());
            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            fwrite($this->getFileStream(), $data."<hr />");
            $this->flags ^= Render::OPEN;
        } elseif ($data !== null) {
            fwrite($this->getFileStream(), $data);
        }

    }

    public function update($event, $value = null) {
        switch($event) {
        case Render::CHUNK:
            $this->flags = $value;
            break;

        case Render::STANDALONE:
            if ($value) {
                $this->registerElementMap(parent::getDefaultElementMap());
                $this->registerTextMap(parent::getDefaultTextMap());
            }
            break;

        case Render::INIT:
            if ($value) {
                $this->loadVersionAcronymInfo();
                $this->postConstruct();
                if ($this->config->css()) {
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


