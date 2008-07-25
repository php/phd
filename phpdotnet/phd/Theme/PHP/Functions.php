<?php

class phpfunctions extends PhDTheme {
    const OPEN_CHUNK = 0x01;
    const CLOSE_CHUNK = 0x02;
    const OPENED_CHUNK = 0x03;
    
    protected $elementmap = array(
        'set'                   => 'format_set',
        'refentry'              => 'format_refentry',
        'refname'               => 'format_refname',
    );

    protected $textmap =        array(
        'pubdate'               => 'format_pubdate',
        'refname'               => 'format_refname_text',
        'title'                 => array(
            /* DEFAULT */          false,
            'set'               => array(
                /* DEFAULT */      'format_bookname',
                'set'           => false,
            ),
        ),
    );
    
    public $role        = false;
    protected $chunked = true;
    
    protected $CURRENT_ID = "";
    
    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array(
        "funcname"                  => array(),
        "firstrefname"              => true,
    );
    
    protected $isChunk = false;
    
    /* Common properties for all functions pages */
    protected $bookName = "";
    protected $date = "";
    protected $outputdir = "";
    protected $isFunctionRefSet = false;
    
    public function __construct(array $IDs, array $filenames, $format = "man", $chunked = true) {
        parent::__construct($IDs);
        $this->format = $format;
        $this->outputdir = $GLOBALS['OPTIONS']['output_dir'] . $this->format . DIRECTORY_SEPARATOR;
        if(!file_exists($this->outputdir) || is_file($this->outputdir)) mkdir($this->outputdir) or die("Can't create the cache directory");
    }
    
    public function header($index) {
        return ".TH " . strtoupper($this->cchunk["funcname"][$index]) . " 3 \"" . $this->date . "\" \"PHP Documentation Group\" \"" . $this->bookName . "\"";
    }
    
    public function writeChunk($stream) {
        if (!isset($this->cchunk["funcname"][0]))
             return;
        $index = 0;
        rewind($stream);
        
        $filename = $this->cchunk["funcname"][$index] . '.3.gz';
        $gzfile = gzopen($this->outputdir . $filename, "w9");

        gzwrite($gzfile, $this->header($index));
        gzwrite($gzfile, stream_get_contents($stream));
        gzclose($gzfile);
        v("Wrote %s", $this->outputdir . $filename, VERBOSE_CHUNK_WRITING);

        while(isset($this->cchunk["funcname"][++$index])) {
            $filename = $this->cchunk["funcname"][$index] . '.3.gz';
            rewind($stream);
            // Replace the default function name by the alternative one
            $content = preg_replace('/'.$this->cchunk["funcname"][0].'/', 
                $this->cchunk["funcname"][$index], stream_get_contents($stream), 1); 
            
            $gzfile = gzopen($this->outputdir . $filename, "w9");
            gzwrite($gzfile, $this->header($index));
            gzwrite($gzfile, $content);
            gzclose($gzfile);
            v("Wrote %s", $this->outputdir . $filename, VERBOSE_CHUNK_WRITING);
        }
    }
    
    public function appendData($data, $isChunk) {
        if (!$this->isFunctionRefSet)
            return 0;
        switch($this->isChunk) {
        case self::CLOSE_CHUNK:
            $stream = array_pop($this->streams);
            $retval = (trim($data) === "") ? false : fwrite($stream, $data);
            $this->writeChunk($stream);
            fclose($stream);

            $this->isChunk = null;
            $this->cchunk = array();
            return $retval;
            break;

        case self::OPEN_CHUNK:
            $this->streams[] = fopen("php://temp/maxmemory", "r+");
            $this->isChunk = self::OPENED_CHUNK;
            
        case self::OPENED_CHUNK:
            $stream = end($this->streams);
            // Remove whitespace nodes
            $retval = ($data != "\n" && trim($data) === "") ? false : fwrite($stream, $data);
            return $retval;
        default:
            return 0;
        }
    }
    
    public function format_bookname($value, $tag) {
        $this->bookName = $value;
        return false;
    }
    
    public function format_pubdate($value, $tag) {
        $this->date = $value;
        return false;
    }
    
    public function format_set($open, $name, $attrs, $props) {
        if (isset($attrs[PhDReader::XMLNS_XML]["id"]) && $attrs[PhDReader::XMLNS_XML]["id"] == "funcref") {
            $this->isFunctionRefSet = $open;
        }
        return false;
    }
            
    public function format_refentry($open, $name, $attrs, $props) {
        if (!$this->isFunctionRefSet)
            return false;
        if ($open) {
            $this->isChunk = self::OPEN_CHUNK;
            $this->format->newChunk();
            $this->cchunk = $this->dchunk;
        } else {
            $this->isChunk = self::CLOSE_CHUNK;
            $this->format->closeChunk();
        }
        return false;
    }
    
    public function format_refname($open, $name, $attrs, $props) {
        if ($open) {
            return (isset($this->cchunk["firstrefname"]) && $this->cchunk["firstrefname"]) ? false : "";
        } else {
            if (isset($this->cchunk["firstrefname"]) && $this->cchunk["firstrefname"]) {
                $this->cchunk["firstrefname"] = false;
                return false;
            }
            return "";
        }
    }
    
    public function format_refname_text($value, $tag) {
        $this->cchunk["funcname"][] = $this->format->toValidName(trim($value));
        if (isset($this->cchunk["firstrefname"]) && $this->cchunk["firstrefname"]) {
            return false;
        }
        return "";
    }
    
    

}
