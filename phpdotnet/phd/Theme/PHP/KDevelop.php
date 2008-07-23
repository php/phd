<?php

require_once $ROOT . '/themes/php/chunkedhtml.php';
class phpkdevelop extends PhDTheme {
    const DEFAULT_TITLE = "PHP Manual";
    const DEFAULT_HREF = "http://www.php.net/manual/en/";
    
    protected $elementmap = array(
        'book'                  => 'format_tocsect1',
        'bibliography'          => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'article'               => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'appendix'              => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'colophon'              => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'chapter'               => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'glossary'              => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'index'                 => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'part'                  => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'preface'               => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'reference'             => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'phpdoc:exception'      => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'refentry'              => 'format_refentry',
        'refname'               => 'format_suppressed_tags',
        'refpurpose'            => 'format_suppressed_tags',
        'refsection'            => 'format_suppressed_tags',
        


    );
    protected $textmap =        array(
        'refname'               => 'format_refname_text',
    );
    
    // Kdevelop TOC file
    protected $tocStream;
    // TOC Output directory
    protected $tocDir;

    protected $ext;
    
    protected $currentEntryName;
    protected $index = array();
    
    // CHM Table of contents
    protected $currentTocDepth = 0;
    protected $lastContent = null;
    protected $toc;
    // CHM Index Map
    protected $hhkStream;
    
    public function __construct(array $IDs, $filename, $ext = "php") {
        parent::__construct($IDs, $ext);
        $this->tocDir = PhDConfig::output_dir();

        $this->ext = $ext;
        
        $this->tocStream = fopen($this->tocDir . "php.toc", "w");
    
        self::headerToc();
    }
    
    public function __destruct() {
        self::footerToc();
        fclose($this->tocStream);
    }
    
    public function format_suppressed_tags($open, $name, $attrs) {
        return "";
    }
    
    
    protected function headerToc() {
        fwrite($this->tocStream, "<!DOCTYPE kdeveloptoc>\n<kdeveloptoc>\n<title>" . self::DEFAULT_TITLE . "</title>\n" .
          "<base href=\"" . self::DEFAULT_HREF . "\"/>\n");
    }
    
    protected function footerToc() {
        fwrite($this->tocStream, "<index>\n");
        foreach ($this->index as $name => $url)
            fwrite($this->tocStream, "<entry name=\"{$name}\" url=\"{$url}\"/>\n");
        fwrite($this->tocStream, "</index>\n</kdeveloptoc>\n");
    }
    
    public function appendData($data, $isChunk) {
    }
    
    public function format_tocsect1($open, $name, $attrs) {
        if (!isset($attrs[PhDReader::XMLNS_XML]["id"])) return "";
        $id = $attrs[PhDReader::XMLNS_XML]["id"];
        $hasChild = (count(PhDHelper::getChildren($id)) > 0);
        if ($open) {
            $name = htmlspecialchars(PhDHelper::getDescription($id), ENT_QUOTES, 'UTF-8');
            $url = (PhDHelper::getFilename($id) ? PhDHelper::getFilename($id) : $id) . "." . $this->ext;
            fwrite($this->tocStream, "<tocsect1 name=\"{$name}\" url=\"{$url}\"" . ($hasChild ? "" : "/") . ">\n");
        } else {
            if ($hasChild)
                fwrite($this->tocStream, "</tocsect1>\n");
        }
        return "";
    }
    
    public function format_tocsect2($open, $name, $attrs) {
        if (!isset($attrs[PhDReader::XMLNS_XML]["id"])) return "";
        $id = $attrs[PhDReader::XMLNS_XML]["id"];
        $hasChild = (count(PhDHelper::getChildren($id)) > 0);
        if ($open) {
            $name = htmlspecialchars(PhDHelper::getDescription($id), ENT_QUOTES, 'UTF-8');
            $url = (PhDHelper::getFilename($id) ? PhDHelper::getFilename($id) : $id) . "." . $this->ext;
            fwrite($this->tocStream, "    <tocsect2 name=\"{$name}\" url=\"{$url}\"/>\n");
        }
        return "";
    }
    
    public function format_refentry($open, $name, $attrs) {
        if (!isset($attrs[PhDReader::XMLNS_XML]["id"])) return "";
        $id = $attrs[PhDReader::XMLNS_XML]["id"];
        if ($open) {
            $this->currentEntryName = null;
        }
        if (!$open && $this->currentEntryName) {
            $url = (PhDHelper::getFilename($id) ? PhDHelper::getFilename($id) : $id) . "." . $this->ext;
            $this->index[$this->currentEntryName] = $url;
        }
        return "";
    }
    
    public function format_refname_text($value, $tag) {
        $this->currentEntryName = $value;
        return "";
    }

}
