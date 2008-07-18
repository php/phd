<?php

class phppdf extends PhDTheme {
   
    protected $elementmap = array(
        'article'               => 'format_tocnode_newpage',
        'appendix'              => 'format_tocnode_newpage',
        'book'                  => 'format_book',
        'bibliography'          => array(
            /* DEFAULT */          false,
            'article'           => 'format_tocnode_newpage',
            'book'              => 'format_tocnode_newpage',
            'part'              => 'format_tocnode_newpage',
        ),
        'chapter'               => 'format_tocnode_newpage',
        'colophon'              => 'format_tocnode_newpage',
        'glossary'              => array(
            /* DEFAULT */          false,
            'article'           => 'format_tocnode_newpage',
            'book'              => 'format_tocnode_newpage',
            'part'              => 'format_tocnode_newpage',
        ),
        'index'                 => array(
            /* DEFAULT */          false,
            'article'           => 'format_tocnode_newpage',
            'book'              => 'format_tocnode_newpage',
            'part'              => 'format_tocnode_newpage',
        ),
        'legalnotice'           => 'format_tocnode_newpage',
        'part'                  => 'format_tocnode_newpage',
        'preface'               => 'format_tocnode_newpage',
        'refentry'              => 'format_tocnode_newpage',
        'reference'             => 'format_tocnode_newpage',
        'sect1'                 => 'format_tocnode',
        'sect2'                 => 'format_tocnode',
        'sect3'                 => 'format_tocnode',
        'sect4'                 => 'format_tocnode',
        'sect5'                 => 'format_tocnode',
        'section'               => 'format_tocnode',
        'set'                   => array(
            /* DEFAULT */          'format_root_set',
            'set'               => 'format_set',
        ),
        'setindex'              => 'format_tocnode_newpage',

    );

    protected $textmap =        array(
        'title'                 => array(
            /* DEFAULT */          false,
            'book'              => 'format_bookname',
            'set'               => array(
                /* DEFAULT */      'format_setname',
                'set'           => false,
            ),
        ),
    );
    
    
    private   $acronyms = array();
    
    /* Common properties for all functions pages */
    protected $bookName = "";
    protected $outputdir = "";
    
    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array(
        "bookname"                  => null,
        "setname"                   => null,
        "toc-root"                  => null,
        "id-to-outline"             => array(),
        "id-to-page"                => array(),
    );
    
    public function __construct(array $IDs, array $filenames, $format = "pdf", $chunked = true) {
        parent::__construct($IDs);
        $this->format = $format;

        $this->outputdir = PhDConfig::output_dir() . $this->format . DIRECTORY_SEPARATOR;
        if(!file_exists($this->outputdir) || is_file($this->outputdir)) mkdir($this->outputdir) or die("Can't create the cache directory");
    }
    
    public function __destruct() {}
        
    
    // Do nothing
    public function appendData($data, $isChunk) {}
    
    // To override
    public function format_root_set($open, $name, $attrs, $props) {}
    public function format_set($open, $name, $attrs, $props) {}
    
    public function format_book($open, $name, $attrs, $props) {
        if ($open) {
            $this->format->newChunk();
            $pdfDoc = new PdfWriter();
            $this->format->setPdfDoc($pdfDoc);
            $this->cchunk = $this->dchunk;
            $id = $attrs[PhDReader::XMLNS_XML]["id"];
            $this->cchunk["id-to-outline"][$id] = 
                $pdfDoc->createOutline(PhDHelper::getDescription($id), null, true);
            $this->setIdToPage($id);
        } else {
            $this->resolveLinks($this->cchunk["bookname"]);
            $pdfDoc = $this->format->getPdfDoc();
            v("Writing PDF Manual (%s)", $this->cchunk["bookname"], VERBOSE_TOC_WRITING);
            $pdfDoc->saveToFile($this->outputdir . $this->toValidName($this->cchunk["bookname"]) . ".pdf");
            unset($pdfDoc);
        }
        return false;
    }
    
    public function format_bookname($value, $tag) {
        $this->cchunk["bookname"] = trim($value);
        return false;
    }
    
    public function format_setname($value, $tag) {
        $this->cchunk["setname"] = trim($value);
        return false;
    }
    
    public function format_tocnode($open, $name, $attrs, $props, $newpage = false) {
        if ($open) {
            if ($newpage)
                $this->format->getPdfDoc()->add(PdfWriter::PAGE);
            else 
                $this->format->getPdfDoc()->add(PdfWriter::LINE_JUMP);
            if (isset($attrs[PhDReader::XMLNS_XML]["id"]) && $id = $attrs[PhDReader::XMLNS_XML]["id"]) {
                $parentId = PhDHelper::getParent($id);
                $this->cchunk["id-to-outline"][$id] = $this->format->getPdfDoc()->createOutline
                    (PhDHelper::getDescription($id), $this->cchunk["id-to-outline"][$parentId], false);
                $this->setIdToPage($id);
            }
        }
        return "";
    }
    
    public function format_tocnode_newpage($open, $name, $attrs, $props) {
        return $this->format_tocnode($open, $name, $attrs, $props, true);
    }    
    
    // Convert the function name to a Unix valid filename
    protected function toValidName($functionName) {
        return str_replace(array(":", "::", "->", "/", "\\", " "), array(".", ".", ".", "-", "-", "-"), $functionName);
    } 
    
    protected function setIdToPage($id) {
        if (isset($this->cchunk["id-to-page"]) && is_array($this->cchunk["id-to-page"])) {
            $this->cchunk["id-to-page"][$id] = $this->format->getPdfDoc()->getCurrentPage();
        }
    }
    
    protected function resolveLinks($name) {
        v("Resolving Internal Links... (%s)", $name, VERBOSE_TOC_WRITING);
        $linksToResolve = $this->format->getChunkInfo("links-to-resolve");
        foreach ($linksToResolve as $link => $targets) {
            if (isset($this->cchunk["id-to-page"][$link]) && $destPage = $this->cchunk["id-to-page"][$link]) {
                foreach ($targets as $target) {
                    $page = $target[0];
                    $rectangle = array($target[1], $target[2], $target[3], $target[4]);
                    $this->format->getPdfDoc()->resolveInternalLink($page, $rectangle, $destPage);

                }
            }
        }
    }    
}
