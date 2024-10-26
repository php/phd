<?php
namespace phpdotnet\phd;

class Package_PHP_BigPDF extends Package_PHP_PDF {
    public function __construct(Config $config) {
        parent::__construct($config);
        $this->registerFormatName("PHP-BigPDF");
    }

    public function update($event, $value = null) {
        switch($event) {
        case Render::STANDALONE:
            if ($value) {
                $this->registerElementMap(parent::getDefaultElementMap());
                $this->registerTextMap(parent::getDefaultTextMap());
            }
            break;

        case Render::INIT:
            $this->setOutputDir($this->config->output_dir());
            break;

        case Render::VERBOSE:
        	v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
        	break;
        }
    }

    // Do nothing
    public function appendData($data) {}

    public function format_root_set($open, $name, $attrs, $props) {
        if ($open) {
            parent::newChunk();
            $this->cchunk = $this->dchunk;
            $pdfDoc = new PdfWriter();
            try {
                $pdfDoc->setCompressionMode(\HaruDoc::COMP_ALL);
            } catch (\HaruException $e) {
                trigger_error("PDF Compression failed, you need to compile libharu with Zlib...", E_USER_WARNING);
            }
            parent::setPdfDoc($pdfDoc);

            if (isset($attrs[Reader::XMLNS_XML]["base"]) && $base = $attrs[Reader::XMLNS_XML]["base"])
                parent::setChunkInfo("xml-base", $base);
            $id = $attrs[Reader::XMLNS_XML]["id"];
            $this->cchunk["root-outline"] = $this->cchunk["id-to-outline"][$id] =
                $pdfDoc->createOutline(Format::getShortDescription($id), null, true);
            $this->setIdToPage($id);
        } else {
            $this->resolveLinks($this->cchunk["setname"]);
            $pdfDoc = parent::getPdfDoc();
            v("Writing Full PDF Manual (%s)", $this->cchunk["setname"], VERBOSE_TOC_WRITING);

            $filename = $this->getOutputDir();
            if ($this->config->output_filename()) {
                $filename .= $this->config->output_filename();
            } else {
                $filename .= strtolower($this->getFormatName()) . $this->getExt();
            }

            $pdfDoc->saveToFile($filename);
            unset($pdfDoc);
        }
        return "";
    }

    public function format_set($open, $name, $attrs, $props) {
        return $this->format_tocnode_newpage($open, $name, $attrs, $props);
    }

    public function format_book($open, $name, $attrs, $props) {
        return $this->format_tocnode_newpage($open, $name, $attrs, $props);
    }
}


