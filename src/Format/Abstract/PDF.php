<?php
namespace phpdotnet\phd\Format\Abstract;

use phpdotnet\phd\Format;

abstract class PDF extends Format {
    protected $pdfDoc;

    public function getPdfDoc() {
        return $this->pdfDoc;
    }

    public function setPdfDoc($pdfDoc) {
        $this->pdfDoc = $pdfDoc;
    }

    public function UNDEF($open, $name, $attrs, $props) {
        if ($open) {
            trigger_error("No mapper found for '{$name}'", E_USER_WARNING);
        }
        $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 14, array(1, 0, 0)); // Helvetica 14 red
        $this->pdfDoc->appendText(($open ? "<" : "</") . $name . ">");
        $this->pdfDoc->revertFont();
        return "";
    }

    public function CDATA($str) {
        $this->pdfDoc->appendText(utf8_decode(trim($str)));
        return "";
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        return "";
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC){}

    public function TEXT($str) {}

}
