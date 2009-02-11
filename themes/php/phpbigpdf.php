<?php
require_once $ROOT . '/themes/php/phppdf.php';
class phpbigpdf extends phppdf {

    public function __construct(array $IDs, array $filenames, $format = "pdf", $chunked = true) {
        parent::__construct($IDs, $filenames, $format, $chunked);
        $this->outputdir = $GLOBALS['OPTIONS']['output_dir'];
    }

    // Do nothing
    public function appendData($data, $isChunk) {}

    public function format_root_set($open, $name, $attrs, $props) {
        if ($open) {
            $this->format->newChunk();
            $this->cchunk = $this->dchunk;
            $pdfDoc = new PdfWriter();
            try {
                $pdfDoc->setCompressionMode(HaruDoc::COMP_ALL);
            } catch (HaruException $e) {
                v("PDF Compression failed, you need to compile libharu with Zlib...", E_USER_WARNING);
            }
            $this->format->setPdfDoc($pdfDoc);

            if (isset($attrs[PhDReader::XMLNS_XML]["base"]) && $base = $attrs[PhDReader::XMLNS_XML]["base"])
                $this->format->setChunkInfo("xml-base", $base);
            $id = $attrs[PhDReader::XMLNS_XML]["id"];
            $this->cchunk["root-outline"] = $this->cchunk["id-to-outline"][$id] =
                $pdfDoc->createOutline(PhDHelper::getDescription($id), null, true);
            $this->setIdToPage($id);
        } else {
            $this->resolveLinks($this->cchunk["setname"]);
            $pdfDoc = $this->format->getPdfDoc();
            v("Writing Full PDF Manual (%s)", $this->cchunk["setname"], VERBOSE_TOC_WRITING);
            $pdfDoc->saveToFile($this->outputdir . "php_manual_en.pdf");
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
