<?php

class PDFPhDFormat extends PhDFormat {
    protected $elementmap = array( /* {{{ */
        'abstract'              => 'format_suppressed_tags',
        'acronym'               => 'format_suppressed_tags',
        'article'               => false,
        'appendix'              => false,
        'author'                => array(
            /* DEFAULT */          'format_newline',
            'authorgroup'       => 'format_authorgroup_author',
        ),
        'authorgroup'           => 'format_shifted_para',
        'blockquote'            => 'format_framed_block',
        'book'                  => 'format_suppressed_tags',
        'classsynopsis'         => 'format_para',
        'caution'               => 'format_admonition',
        'chapter'               => false,
        'command'               => 'format_italic',
        'constant'              => 'format_bold',
        'code'                  => 'format_verbatim_inline',
        'copyright'             => 'format_copyright',
        'editor'                => 'format_editor',
        'example'               => 'format_example',
        'emphasis'              => 'format_italic',
        'filename'              => 'format_italic',
        'firstname'             => 'format_suppressed_tags',
        'formalpara'            => 'format_para',
        'funcdef'               => 'format_bold',
        'function'              => 'format_suppressed_tags',
        'holder'                => 'format_suppressed_tags',
        'info'                  => 'format_suppressed_tags',
        'informalexample'       => 'format_para',
        'itemizedlist'          => 'format_shifted_para',
        'legalnotice'           => 'format_suppressed_tags',
        'listitem'              => 'format_listitem',
        'literal'               => 'format_italic',
        'literallayout'         => 'format_verbatim_inline',
        'member'                => 'format_member',
        'methodname'            => 'format_bold',
        'note'                  => 'format_admonition',
        'option'                => 'format_italic',
        'orderedlist'           => 'format_shifted_para',
        'othercredit'           => 'format_newline',
        'othername'             => 'format_suppressed_tags',
        'para'                  => array(
            /* DEFAULT */          'format_para',
            'listitem'          => 'format_suppressed_tags',
        ),
        'parameter'             => array(
            /* DEFAULT */          false,
            'code'              => 'format_italic',
        ),
        'part'                  => false,
        'personname'            => 'format_suppressed_tags',
        'preface'               => 'format_suppressed_tags',
        'programlisting'        => 'format_verbatim_block',
        'pubdate'               => 'format_para',
        'refentrytitle'         => 'format_bold',
        'reference'             => false,
        'replaceable'           => 'format_italic',
        'screen'                => 'format_verbatim_block',
        'section'               => 'format_suppressed_tags',
        'set'                   => 'format_suppressed_tags',
        'simpara'               => array(
            /* DEFAULT */          'format_para',
            'listitem'          => false,
        ),
        'simplelist'            => 'format_shifted_para',
        'surname'               => 'format_suppressed_tags',
        'systemitem'            => 'format_bold',
        'title'                 => array(
            /* DEFAULT */          'format_title',
            'example'           => 'format_example_title',
            'formalpara'        => 'format_title3',
            'info'              => array(
                /* DEFAULT */      'format_title',
                'example'       => 'format_example_title',
                'note'          => 'format_title3',
                'table'         => 'format_title3',
                'informaltable' => 'format_title3',
            ),
            'indexdiv'          => false,
            'informaltable'     => 'format_title3',
            'legalnotice'       => 'format_title2',
            'note'              => 'format_title3',
            'preface'           => 'format_title',
            'procedure'         => 'format_bold',
            'refsect1'          => false,
            'refsect2'          => false,
            'refsect3'          => false,
            'section'           => 'format_title2',
            'sect1'             => 'format_title2',
            'sect2'             => 'format_title3',
//            'sect3'             => 'format_title3',
//            'sect4'             => 'format_title3',
            'segmentedlist'     => 'format_bold',
            'table'         => 'format_title3',
            'variablelist'      => 'format_bold',
        ), 
        'tip'                   => 'format_admonition',
        'titleabbrev'           => 'format_suppressed_tags',
        'type'                  => array(
            /* DEFAULT */          'format_suppressed_tags',
            'methodparam'       => false
        ),
        'userinput'             => 'format_bold',
        'varname'               => 'format_italic',
        'warning'               => 'format_admonition',
        'xref'                  => 'format_link',
        'year'                  => 'format_suppressed_tags',
    ); /* }}} */
    
    protected $textmap = array(
        'function'              => 'format_function_text',
        'link'                  => 'format_link_text',
        'titleabbrev'           => 'format_suppressed_text',
    );

    protected $lang = "";
    
    /* Current Chunk variables */
    protected $cchunk      = array();
    /* Default Chunk variables */
    protected $dchunk      = array(
        "examplenumber"         => 0,
        "href"                  => "",
        "is-xref"               => false,
        "linkend"               => "",
        "links-to-resolve"      => array(
            /* $id => array( $target ), */
        ),
        "table"                 => false,
    );
    
    private $pdfDoc;
    
    public function __construct(array $IDs) {
        parent::__construct($IDs);
        $this->pdfDoc = new PdfWriter();
    }
    
    public function __destruct() {
        $this->pdfDoc->saveToFile("php_manual_en.pdf");
        unset($this->pdfDoc);
    }
    
    public function getChunkInfo($info) {
        if (isset($this->cchunk[$info]))
            return $this->cchunk[$info];
        else return null;
    }
    
    public function setChunkInfo($info, $value) {
        $this->cchunk[$info] = $value;
    }
    
    public function __call($func, $args) {
//        if ($args[0]) {
//            trigger_error("No mapper found for '{$func}'", E_USER_WARNING);
//        }
        $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 14, array(1, 0, 0)); // Helvetica 14 red
        $this->pdfDoc->appendText(($args[0] ? "<" : "</") . $args[1] . ">");
        $this->pdfDoc->revertFont();
        return "";
    }
    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        return "";
    }
    
    public function CDATA($str) {
        $this->pdfDoc->appendText(utf8_decode(trim($str)));
        return "";
    }

    public function TEXT($str) {
        $ret = utf8_decode(trim(ereg_replace( "[ \n\t]+", ' ', $str)));
        // No whitespace if current text value begins with ',', ';', ':', '.'
        if (strncmp($ret, ",", 1) && strncmp($ret, ";", 1) && strncmp($ret, ":", 1) && strncmp($ret, ".", 1))
            $this->pdfDoc->appendText(" " . $ret);
        else $this->pdfDoc->appendText($ret);
        return "";
    }
    
    public function format_suppressed_tags($open, $name, $attrs) {
        /* Ignore it */
        return "";
    }
    
    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }

    public function getPdfDoc() {
        return $this->pdfDoc;
    }
    
    public function setPdfDoc($pdfDoc) {
        $this->pdfDoc = $pdfDoc;
    }
    
    public function newChunk() {
        $this->cchunk = $this->dchunk;
    }
    
    public function format_para($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PARA);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_shifted_para($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->shift();
            $this->pdfDoc->add(PdfWriter::PARA);
        } else {
            $this->pdfDoc->unshift();
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_title($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_title2($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE2);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_title3($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE3);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_bold($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_italic($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_ITALIC);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::ADMONITION);
            $this->pdfDoc->appendText($this->autogen($name, $props["lang"]));
            $this->pdfDoc->add(PdfWriter::ADMONITION_CONTENT);
        } else {
            $this->pdfDoc->add(PdfWriter::END_ADMONITION);
        }
        return "";
    }
    
    public function format_example($open, $name, $attrs, $props) {
        if ($open) {
            $this->lang = $props["lang"];
            $this->cchunk["examplenumber"]++;
            $this->pdfDoc->add(PdfWriter::ADMONITION);
            
        } else {
            $this->pdfDoc->add(PdfWriter::END_ADMONITION);
        }
        return "";
    }
    
    public function format_example_title($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->appendText($this->autogen("example", $this->lang) . 
                $this->cchunk["examplenumber"] . " -");
        } else {
            $this->pdfDoc->add(PdfWriter::ADMONITION_CONTENT);
        }
        return "";
    }
    
    public function format_newpage($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PAGE);
        }
        return "";
    }
    
    public function format_verbatim_block($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::VERBATIM_BLOCK);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_verbatim_inline($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM, 10);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_framed_block($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
        } else {
            $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK);
        }
        return "";
    }
        
    public function format_newline($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
    }
    
    public function format_link($open, $name, $attrs, $props) {
        if ($open && ! $props["empty"]) {
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])) {
                $this->cchunk["linkend"] = $attrs[PhDReader::XMLNS_DOCBOOK]["linkend"];
            } elseif(isset($attrs[PhDReader::XMLNS_XLINK]["href"])) {
                $this->cchunk["href"] = $attrs[PhDReader::XMLNS_XLINK]["href"];
            }
        } elseif ($open && $name == "xref" && isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])
            && $linkend = $attrs[PhDReader::XMLNS_DOCBOOK]["linkend"]) {
            $this->cchunk["linkend"] = $linkend;
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            $this->format_link_text(PhDHelper::getDescription($linkend), $name);
            $this->pdfDoc->revertFont();
            $this->cchunk["linkend"] = "";
        } elseif (!$open) {
            $this->cchunk["href"] = "";
            $this->cchunk["linkend"] = "";
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_link_text($value, $tag) {
        $value = trim(ereg_replace( "[ \n\t]+", ' ', $value));
        if (isset($this->cchunk["href"]) && $this->cchunk["href"]) {
            $this->pdfDoc->add(PdfWriter::URL_ANNOTATION, array(chr(187) . chr(160) . $value, $this->cchunk["href"])); // links with >> symbol
        } elseif (isset($this->cchunk["linkend"]) && $linkend = $this->cchunk["linkend"]) {
            $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, $value);
            if (!isset($this->cchunk["links-to-resolve"][$linkend]))
                $this->cchunk["links-to-resolve"][$linkend] = array();
            foreach ($linkAreas as $area)
                $this->cchunk["links-to-resolve"][$linkend][] = $area;
        }
        return "";
    }
    
    public function format_function_text($value, $tag, $display_value = null) {
        $value = trim(ereg_replace( "[ \n\t]+", ' ', $value));
        if ($display_value === null) {
            $display_value = $value;
        }
        
        $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $value));
        if (($linkend = $this->getRefnameLink($ref)) !== null) {
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, $display_value.($tag == "function" ? "()" : ""));
            if (!isset($this->cchunk["links-to-resolve"][$linkend]))
                $this->cchunk["links-to-resolve"][$linkend] = array();
            foreach ($linkAreas as $area)
                $this->cchunk["links-to-resolve"][$linkend][] = $area;
        } else {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
            $this->pdfDoc->appendText(" " . $display_value.($tag == "function" ? "()" : ""));
        }
        $this->pdfDoc->revertFont();
        return "";
    }
    
    public function format_authorgroup_author($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] !== $name) {
                $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
                $this->pdfDoc->appendText($this->autogen("by", $props["lang"]));
                $this->pdfDoc->revertFont();
            }
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }
   
    public function format_editor($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
            $this->pdfDoc->appendText($this->autogen("editedby", $props["lang"]));
            $this->pdfDoc->revertFont();
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }
    
    public function format_copyright($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PARA);
            $this->pdfDoc->appendText(utf8_decode("©"));
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    
    public function format_listitem($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->add(PdfWriter::ADD_BULLET);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP, 0.5);
        }
        return "";
    }
    
    public function format_member($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->add(PdfWriter::ADD_BULLET);
        }
        return "";
    }
}

/* Haru PDF Wrapping class
 * Includes text appending, auto line wrap, on-the-fly font change, tables, frames, ...
 */
class PdfWriter {
    // Font type constants (for setFont())
    const FONT_NORMAL = 0x01;
    const FONT_ITALIC = 0x02;
    const FONT_BOLD = 0x03;
    const FONT_VERBATIM = 0x04;  
    
    // "Objects" constants (for add())
    const PARA = 0x05;
    const INDENTED_PARA = 0x06;
    const TITLE = 0x07;
    const DRAW_LINE = 0x08;
    const LINE_JUMP = 0x09;
    const PAGE = 0x0A;
    const TITLE2 = 0x0B;
    const VERBATIM_BLOCK = 0x0C;
    const ADMONITION = 0x0D;
    const ADMONITION_CONTENT = 0x0E;
    const END_ADMONITION = 0x0F;
    const URL_ANNOTATION = 0x10;
    const LINK_ANNOTATION = 0x11;
    const ADD_BULLET = 0x12;
    const FRAMED_BLOCK = 0x13;
    const END_FRAMED_BLOCK = 0x14;
    const TITLE3 = 0x15;
    const TABLE = 0x16;
    const TABLE_ROW = 0x17;
    const TABLE_ENTRY = 0x18;
    const TABLE_END_ENTRY = 0x19;
    const END_TABLE = 0x1A;

    // Page format
    const VMARGIN = 56.7; // = 1 centimeter
    const HMARGIN = 56.7; // = 1 centimeter
    const LINE_SPACING = 2; // nb of points between two lines
    const INDENT_SPACING = 10; // nb of points for indent
    const DEFAULT_SHIFT = 20; // default value (points) for shifted paragraph
    private $SCALE; // nb of points for 1 centimeter
    private $PAGE_WIDTH; // in points
    private $PAGE_HEIGHT; // in points
    
    private $haruDoc;
    private $currentPage;
    private $currentFont;
    private $currentFontSize;
    private $currentFontColor;
    private $fonts;
    private $oldFonts = array();
    private $text;

    private $vOffset = 0;
    private $hOffset = 0;
    private $permanentLeftSpacing = 0;
    private $permanentRightSpacing = 0;
    
    private $current = array(
        "oldVPosition"      => 0,
        "vOffset"           => 0,
        "pages"             => array(),
        "row"               => array(
            "vSize"         => 0,
            "cellCount"     => 0,
            "activeCell"    => 0,
        ),
    );
    
    function __construct($pageWidth = 210, $pageHeight = 297) {
    	//Initialization of properties
    	$this->haruDoc = new HaruDoc;
    	$this->haruDoc->addPageLabel(1, HaruPage::NUM_STYLE_DECIMAL, 1, "Page ");

        $this->haruDoc->setPageMode(HaruDoc::PAGE_MODE_USE_OUTLINE);
        $this->haruDoc->setPagesConfiguration(2);

    	// Page format
    	$this->SCALE = 72/25.4;
    	$this->PAGE_WIDTH = $pageWidth * $this->SCALE;
    	$this->PAGE_HEIGHT = $pageHeight * $this->SCALE;
    	    	    	
    	// Set fonts
    	$this->fonts["Helvetica"] = $this->haruDoc->getFont("Helvetica", "WinAnsiEncoding");
    	$this->fonts["Helvetica-Bold"] = $this->haruDoc->getFont("Helvetica-Bold", "WinAnsiEncoding");
    	$this->fonts["Helvetica-Oblique"] = $this->haruDoc->getFont("Helvetica-Oblique", "WinAnsiEncoding");
    	$this->fonts["Courier"] = $this->haruDoc->getFont("Courier", "WinAnsiEncoding");
    	
    	// Add first page and default font settings
    	$this->currentFont = $this->fonts["Helvetica"];
    	$this->currentFontSize = 12;
    	$this->currentFontColor = array(0, 0, 0); // Black
    	$this->newPage();
    	$this->haruDoc->addPageLabel(1, HaruPage::NUM_STYLE_DECIMAL, 1, "Page ");
    }
    
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    // Append text into the current position
    public function appendText($text) {
        $this->currentPage->beginText();
        do {
            // Clear the whitespace if it begins the line
            if (strpos($text, " ") === 0 && $this->hOffset == 0)
                $text = substr($text, 1);
            
            // Number of chars allowed in the current line
            $nbCarac = $this->currentFont->measureText($text, 
                ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing), 
                $this->currentFontSize, $this->currentPage->getCharSpace(),
                $this->currentPage->getWordSpace(), true);
            
            // If a the text content can't be appended (either there is no whitespaces,
            // either the is not enough space in the line)
            if ($nbCarac === 0) {
                $isEnoughSpaceOnNextLine = $this->currentFont->measureText($text, 
                    ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->permanentLeftSpacing - $this->permanentRightSpacing), 
                    $this->currentFontSize, $this->currentPage->getCharSpace(),
                    $this->currentPage->getWordSpace(), true);
                if ($isEnoughSpaceOnNextLine) {
                    $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
                    $this->hOffset = 0;
                    $isLastLine = false;
                    continue;
                } else {
                    $nbCarac = $this->currentFont->measureText($text, 
                        ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing), 
                        $this->currentFontSize, $this->currentPage->getCharSpace(),
                        $this->currentPage->getWordSpace(), false);
                }                    
            }
            $isLastLine = ($nbCarac == strlen($text));
            
            $textToAppend = substr($text, 0, $nbCarac);
            $text = substr($text, $nbCarac);

            // Append text (in a new page if needed)
            if ($this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset) < self::VMARGIN) {
                $this->currentPage->endText();
                $this->current["pages"][] = $this->currentPage;
                $this->newPage();
                $this->currentPage->beginText();
            }
            $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing, 
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
            
            // Offsets for next line
            if (!$isLastLine) {
                $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
                $this->hOffset = 0;
            } else {
                $this->hOffset += $this->currentPage->getTextWidth($textToAppend);
            }
        }
        while(!$isLastLine); // While it remains chars to append
        $this->currentPage->endText();
    }

    // Same function one line at a time
    public function appendOneLine($text) {
        if (strpos($text, " ") === 0 && $this->hOffset == 0)
                $text = substr($text, 1);
        
        $this->currentPage->beginText();
        $nbCarac = $this->currentFont->measureText($text, 
            ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing), 
            $this->currentFontSize, $this->currentPage->getCharSpace(),
            $this->currentPage->getWordSpace(), true);
        
        // If a the text content can't be appended (either there is no whitespaces,
        // either the is not enough space in the line)
        if ($nbCarac === 0) {
            $isEnoughSpaceOnNextLine = $this->currentFont->measureText($text, 
                ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->permanentLeftSpacing - $this->permanentRightSpacing), 
                $this->currentFontSize, $this->currentPage->getCharSpace(),
                $this->currentPage->getWordSpace(), true);
            if ($isEnoughSpaceOnNextLine) {
                $this->currentPage->endText();
                return $text;
            } else {
                $nbCarac = $this->currentFont->measureText($text, 
                    ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing), 
                    $this->currentFontSize, $this->currentPage->getCharSpace(),
                    $this->currentPage->getWordSpace(), false);
            }                    
        }
    
        $isLastLine = ($nbCarac == strlen($text));
        
        $textToAppend = substr($text, 0, $nbCarac);
        $text = substr($text, $nbCarac);

        // Append text (in a new page if needed)
        if ($this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset) < self::VMARGIN) {
            $this->currentPage->endText();
            $this->current["pages"][] = $this->currentPage;
            $this->newPage();
            $this->currentPage->beginText();
        }
        $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing, 
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
        
        $this->hOffset += $this->currentPage->getTextWidth($textToAppend);
        
        $this->currentPage->endText();
        return ($isLastLine ? null : $text);
    }
    
    public function add($type, $option = null) {
        switch ($type) {
            case self::INDENTED_PARA:
                $this->setFont(self::FONT_NORMAL, 12);
                $this->lineJump();
                $this->indent();
                break;
            case self::PARA:
                $this->setFont(self::FONT_NORMAL, 12);
                $this->lineJump();
                break;
            case self::VERBATIM_BLOCK:
                $this->setFont(self::FONT_VERBATIM, 10, array(0.3, 0.3, 0.3));
                $this->lineJump();
                break;
            case self::TITLE:
                $this->setFont(self::FONT_BOLD, 20);
                $this->lineJump();
                break;
            case self::TITLE2:
                $this->setFont(self::FONT_BOLD, 14);
                $this->lineJump();
                break;
            case self::TITLE3:
                $this->setFont(self::FONT_BOLD, 12);
                $this->lineJump();
                break;
            case self::DRAW_LINE:
                $this->traceLine($option);
                break;
            case self::LINE_JUMP:
                if ($option)
                    $this->lineJump($option);
                else $this->lineJump();
                break;
            case self::PAGE:
                $this->newPage();
                break;
            case self::ADMONITION:
                $this->beginAdmonition();
                break;
            case self::ADMONITION_CONTENT:
                $this->admonitionContent();
                break;
            case self::END_ADMONITION:
                $this->endAdmonition();
                break;
            case self::URL_ANNOTATION:
                $this->appendUrlAnnotation($option[0], $option[1]);
                break;
            case self::LINK_ANNOTATION:
                return $this->prepareInternalLinkAnnotation($option);
                break;
            case self::ADD_BULLET:
                $this->indent(-self::INDENT_SPACING);
                $this->appendText(chr(149)); // ANSI Bullet
                $this->indent(0);
                break;
            case self::FRAMED_BLOCK:
                $this->beginFrame();
                break;
            case self::END_FRAMED_BLOCK:
                $this->endFrame();
                break;
            case self::TABLE:
                $this->addTable($option);
                break;
            case self::END_TABLE:
                $this->endTable();
                break;
            case self::TABLE_ROW:
                $this->newTableRow($option[0], $option[1]);
                break;
            case self::TABLE_ENTRY:
                $this->beginTableEntry($option[0], $option[1]);
                break;
            case self::TABLE_END_ENTRY:
                $this->endTableEntry();
                break;
            default:
                trigger_error("Unknown object type : {$type}", E_USER_WARNING);
                break;
        }
    }
     
    // Switch font on-the-fly
    public function setFont($type, $size = null, $color = null) {
        if ($this->currentPage == null)
            return false;
        $this->oldFonts[] = array($this->currentFont, $this->currentFontSize, $this->currentFontColor);
        $this->currentFontSize = ($size ? $size : $this->currentFontSize);
        if ($color && count($color) === 3) {
            $this->setColor($color[0], $color[1], $color[2]);
            $this->currentFontColor = $color;
        }
        else 
            $this->setColor($this->currentFontColor[0], $this->currentFontColor[1], $this->currentFontColor[2]);
        switch ($type) {
            case self::FONT_NORMAL:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica"],
                    $this->currentFontSize);
                break;
            case self::FONT_ITALIC:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica-Oblique"],
                    $this->currentFontSize);
                break;
            case self::FONT_BOLD:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica-Bold"],
                    $this->currentFontSize);
                break;
            case self::FONT_VERBATIM:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Courier"],
                    $this->currentFontSize);
                break;
            default:
                trigger_error("Unknown font type : {$type}", E_USER_WARNING);
                break;
        }
    }
    
    // Back to the last used font
    public function revertFont() {
        $lastFont = array_pop($this->oldFonts);
        $this->currentFont = $lastFont[0];
        $this->currentFontSize = $lastFont[1];
        $this->currentFontColor = $lastFont[2];
        $this->currentPage->setFontAndSize($lastFont[0], $lastFont[1]);
        $this->setColor($lastFont[2][0], $lastFont[2][1], $lastFont[2][2]); 
    }
    
    // Change font color (1, 1, 1 = white, 0, 0, 0 = black) 
    public function setColor($r, $g, $b) {
        if ($r < 0 || $r > 1 || $g < 0 || $g > 1 || $b < 0 || $b > 1)
            return false;
        $this->currentPage->setRGBStroke($r, $g, $b);
        $this->currentPage->setRGBFill($r, $g, $b);
        $this->currentFontColor = array($r, $g, $b);
        return true;
    }
    
    // Save the current PDF Document to a file
    public function saveToFile($filename) {
        $this->haruDoc->save($filename);
    }
    
    public function createOutline($description, $parentOutline = null, $opened = false) {
        $outline = $this->haruDoc->createOutline($description, $parentOutline);
        $dest = $this->currentPage->createDestination();
        $dest->setXYZ(0, $this->currentPage->getHeight(), 1);
        $outline->setDestination($dest);
        $outline->setOpened($opened);
        return $outline;
    }
    
    public function shift($offset = self::DEFAULT_SHIFT) {
        $this->permanentLeftSpacing += $offset;
    }
    
    public function unshift($offset = self::DEFAULT_SHIFT) {
        $this->permanentLeftSpacing -= $offset;
    }
    
    private function indent($offset = self::INDENT_SPACING) {
        $this->hOffset = $offset;
    }
    
    // Jump to a new page    
    private function newPage() {
        $this->currentPage = $this->haruDoc->addPage();
        $this->currentPage->setTextRenderingMode(HaruPage::FILL);
        if ($this->currentFont && $this->currentFontSize && $this->currentFontColor) {
            $this->currentPage->setFontAndSize($this->currentFont, $this->currentFontSize);
            $this->setColor($this->currentFontColor[0], $this->currentFontColor[1], $this->currentFontColor[2]);
        }
        $this->vOffset = $this->currentFontSize;
        $this->hOffset = ($this->hOffset ? $this->hOffset : 0);
    } 
    
    // Jump a line
    private function lineJump($nbLines = 1) {
        $this->vOffset += $nbLines * ($this->currentFontSize + self::LINE_SPACING);
        $this->hOffset = 0;
    }
    
    // Trace a line from the current position
    private function traceLine() {
        $this->lineJump();
        $this->currentPage->rectangle(self::HMARGIN + $this->hOffset, $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset, $this->PAGE_WIDTH - 2*$this->hOffset - 2*self::HMARGIN, 1);
        $this->currentPage->stroke();
    }
    
    private function beginAdmonition() {
        $this->setFont(self::FONT_BOLD, 12);
        $this->lineJump();
        $this->current["vOffset"] = $this->vOffset;
        $this->lineJump();
        $this->permanentLeftSpacing += self::INDENT_SPACING;
        $this->current["pages"] = array();
    }
    
    private function admonitionContent() {
        if ($this->current["pages"])
            $this->current["vOffset"] = 0;
        $this->beginFrame();
        $this->revertFont();
        $this->currentPage->rectangle(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING), $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset, $this->PAGE_WIDTH - 2*self::HMARGIN - ($this->permanentLeftSpacing - self::INDENT_SPACING), $this->vOffset - $this->current["vOffset"]); 
        $this->currentPage->stroke();
    }
    
    private function endAdmonition() {
        $this->endFrame();
        $this->permanentLeftSpacing -= self::INDENT_SPACING;
    }
    
    private function beginFrame() {
        $this->lineJump();
        $this->current["newVOffset"] = $this->vOffset;
        $this->current["pages"] = array();
    }
    
    private function endFrame() {
        foreach ($this->current["pages"] as $page) {
            $page->rectangle(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING), self::VMARGIN, $this->PAGE_WIDTH - 2*self::HMARGIN - ($this->permanentLeftSpacing - self::INDENT_SPACING), ($this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]) - self::VMARGIN);
            $page->stroke();
            $this->current["newVOffset"] = 0;
        }
        
        $this->currentPage->rectangle(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING), $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset, $this->PAGE_WIDTH - 2*self::HMARGIN - ($this->permanentLeftSpacing - self::INDENT_SPACING), $this->vOffset - $this->current["newVOffset"]);
        $this->currentPage->stroke();
        $this->lineJump();
        $this->current["oldVPosition"] = 0;
    }
    
    // Append $text with an underlined blue style with a link to $url
    private function appendUrlAnnotation($text, $url) {
        $this->appendText(" ");
        $fromHOffset = $this->hOffset;
        
        // If more than one text line to append 
        while ($text = $this->appendOneLine($text)) {
            // Trace the underline
            $this->currentPage->setLineWidth(1.0);
            $this->currentPage->setDash(null, 0);
            $this->currentPage->moveTo(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset, 
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
            $this->currentPage->lineTo(self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset, 
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
            $this->currentPage->stroke();
            
            // Create link
            $annotationArea = array(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));
            $this->currentPage->createURLAnnotation($annotationArea, $url)->setBorderStyle(0, 0, 0);
            
            // Prepare the next line
            $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
            $this->hOffset = 0;
            $fromHOffset = $this->hOffset;
        }
        
        // Trace the underline
        $this->currentPage->setLineWidth(1.0);
        $this->currentPage->setDash(null, 0);
        $this->currentPage->moveTo(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset, 
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
        $this->currentPage->lineTo(self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset, 
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
        $this->currentPage->stroke();
        
        // Create link
        $annotationArea = array(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
            self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));
        $this->currentPage->createURLAnnotation($annotationArea, $url)->setBorderStyle(0, 0, 0);
        
    }
    
    // Append $text with an underlined blue style and prepare an internal link (which will be resolved later)
    private function prepareInternalLinkAnnotation($text) {
        $this->appendText(" ");
        $fromHOffset = $this->hOffset;
        $linkAreas = array(/* page, left, bottom, right, top */);
        
        // If more than one text line to append 
        while ($text = $this->appendOneLine($text)) {
           // Create link
            $linkAreas[] = array($this->currentPage,
                self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));
            
            // Prepare the next line
            $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
            $this->hOffset = 0;
            $fromHOffset = $this->hOffset;
        }
        
        // Prepare link
        $linkAreas[] = array($this->currentPage,
                self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));
        
        return $linkAreas;
    }
    
    public function resolveInternalLink($page, $rectangle, $destPage) {
        $page->setRGBStroke(0, 0, 1); // blue
        // Trace the underline
        $page->setLineWidth(1.0);
        $page->setDash(array(2), 1);
        $page->moveTo($rectangle[0], $rectangle[1]);
        $page->lineTo($rectangle[2], $rectangle[1]);
        $page->stroke();
        // Create link
        $page->createLinkAnnotation($rectangle, $destPage->createDestination())
            ->setBorderStyle(0, 0, 0);
    }
}
?>