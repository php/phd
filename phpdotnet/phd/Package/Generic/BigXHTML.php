<?php
namespace phpdotnet\phd;

class Package_Generic_BigXHTML extends Package_Generic_XHTML {
    public function __construct(Config $config) {
        parent::__construct($config);
        $this->registerFormatName("Big-XHTML");
        $this->setTitle("Index");
        $this->setChunked(false);
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
            fwrite($this->getFileStream(), $data);

            /* Append footer */
            fwrite($this->getFileStream(), $this->footer());
            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            fwrite($this->getFileStream(), "\n".$data);
            $this->flags ^= Render::OPEN;
        } elseif ($data !== null) {
            fwrite($this->getFileStream(), $data);
        }

    }

    public function header() {
        $root = Format::getRootIndex();
        $style = $this->createCSSLinks();
        return <<<HEADER
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>{$root["ldesc"]}</title>
{$style}
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
                if (!is_resource($this->getFileStream())) {
                    $filename = $this->config->output_dir();
                    if ($this->config->output_filename()) {
                        $filename .= $this->config->output_filename();
                    } else {
                        $filename .= strtolower($this->getFormatName()) . $this->getExt();
                    }

                    $this->postConstruct();
                    if ($this->config->css()) {
                        $this->fetchStylesheet();
                    }
                    $this->setFileStream(fopen($filename, "w+"));
                    fwrite($this->getFileStream(), $this->header());
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


