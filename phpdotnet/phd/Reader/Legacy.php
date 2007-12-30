<?php
/*  $Id$ */
//6271

class PhDReader extends XMLReader {
    const XMLNS_XML   = "http://www.w3.org/XML/1998/namespace";
    const XMLNS_XLINK = "http://www.w3.org/1999/xlink";
    const XMLNS_PHD   = "http://www.php.net/ns/phd";
    const XMLNS_DOCBOOK = "http://docbook.org/ns/docbook";
    const OPEN_CHUNK  = 0x01;
    const CLOSE_CHUNK = 0x02;

    private $STACK    = array();
    private $LAST_DEPTH = -1;
    private $lastChunkDepth = -1;
    private $PREVIOUS_SIBLING = "";

    public $isChunk = false;

    protected $CHUNK_ME = array( /* {{{ */
        'article'               => true,
        'appendix'              => true,
        'bibliography'          => array(
            /* DEFAULT */          false,
            'article'           => true,
            'book'              => true,
            'part'              => true,
       ),
        'book'                  => true,
        'chapter'               => true,
        'colophon'              => true,
        'glossary'              => array(
            /* DEFAULT */          false,
            'article'           => true,
            'book'              => true,
            'part'              => true,
       ),
        'index'                 => array(
            /* DEFAULT */          false,
            'article'           => true,
            'book'              => true,
            'part'              => true,
       ),
        'legalnotice'           => false,
        'part'                  => true,
        'preface'               => true,
        'refentry'              => true,
        'reference'             => true,
        'sect1'                 => 'isSectionChunk',
        /*
        'sect2'                 => 'format_section_chunk',
        'sect3'                 => 'format_section_chunk',
        'sect4'                 => 'format_section_chunk',
        'sect5'                 => 'format_section_chunk',
        */
        'section'               => 'isSectionChunk',
        'set'                   => true,
        'setindex'              => true,
   ); /* }}} */
    protected $opts = array();

    public function __construct($opts, $encoding = "UTF-8", $xml_opts = NULL) {
        if (!XMLReader::open($opts["xml_file"], $encoding, $xml_opts)) {
            throw new Exception("Cannot open {$opts["xml_file"]}");
        }
        if (isset($opts["chunk_extra"]) && is_array($opts["chunk_extra"])) {
            foreach($opts["chunk_extra"] as $el => $v) {
                $this->CHUNK_ME[$el] = $v;
            }
        }
        $this->opts = $opts;
    }

    public function notXPath($tag, $depth = 0) {
        if(!$depth) {
            $depth = $this->depth;
        }
        do {
            if (isset($tag[$this->STACK[--$depth]])) {
                $tag = $tag[$this->STACK[$depth]];
            } else {
                $tag = $tag[0];
            }
        } while (is_array($tag));
        return $tag;
    }

    /* Seek to an ID within the file. */
    public function seek($id) {
        while(XMLReader::read()) {
            if ($this->nodeType === XMLREADER::ELEMENT && $this->hasAttributes && XMLReader::moveToAttributeNs("id", self::XMLNS_XML) && $this->value === $id) {
                return XMLReader::moveToElement();
            }
        }
        return false;
    }

    /* Get the ID of current node */
    public function getID() {
        if ($this->hasAttributes && XMLReader::moveToAttributeNs("id", self::XMLNS_XML)) {
            $id = $this->value;
            XMLReader::moveToElement();
            return $id;
        }
        return "";
    }

    public function getParentTagName() {
        return $this->STACK[$this->depth-1];
    }
    public function getPreviousSiblingTagName() {
        return $this->PREVIOUS_SIBLING;
    }

    public function read() {
        $this->isChunk = false;
        if(XMLReader::read()) {
            $type = $this->nodeType;
            switch($type) {
            case XMLReader::ELEMENT:
                $name = $this->name;
                $depth = $this->depth;
                if ($this->LAST_DEPTH >= $depth) {
                    $this->PREVIOUS_SIBLING = $this->STACK[$depth];
                }
                $this->STACK[$depth] = $name;
                $isChunk = $this->isChunk($name);
                if ($isChunk) {
                    $this->isChunk = PhDReader::OPEN_CHUNK;
                    $this->chunkDepths[] = $this->lastChunkDepth = $depth;
                }
                break;

            case XMLReader::END_ELEMENT:
                $depth = $this->depth;
                if ($this->lastChunkDepth == $depth) {
                    array_pop($this->chunkDepths);
                    $this->lastChunkDepth = end($this->chunkDepths);
                    $this->isChunk = PhDReader::CLOSE_CHUNK;
                }
                $this->LAST_DEPTH = $depth;
                break;
            }
            return true;
        }
        return false;
    }
   
    /* Get the attribute value by name, if exists. */
    public function readAttribute($attr) {
        $retval = XMLReader::moveToAttribute($attr) ? $this->value : "";
        XMLReader::moveToElement();
        return $retval;
    }
    public function readAttributeNs($attr, $ns) {
        $retval = XMLReader::moveToAttributeNs($attr, $ns) ? $this->value : "";
        XMLReader::moveToElement();
        return $retval;
    }
    /* Get all attributes of current node */
    public function getAttributes() {
        $attrs = array(PhDReader::XMLNS_DOCBOOK => array(), PhDReader::XMLNS_XML => array());
        if ($this->hasAttributes) {
            XMLReader::moveToFirstAttribute();
            do {
                $k = $this->namespaceURI;
                $attrs[!empty($k) ? $k : PhDReader::XMLNS_DOCBOOK][$this->localName] = $this->value;
                $attrs[$this->name] = $this->value;
            } while (XMLReader::moveToNextAttribute());
            XMLReader::moveToElement();
        }
        return $attrs;
    }


    /* Get the content of a named node, or the current node. */
    public function readContent($node = null) {
        $retval = "";

        if($this->isEmptyElement) {
            return $retval;
        }
        if (!$node) {
            $node = $this->name;
        }
        $retval = "";
        while (PhDReader::readNode($node)) {
            $retval .= $this->value;
        }
        return $retval;
    }
    /* Read $nodeName until END_ELEMENT */
    public function readNode($nodeName) {
        return XMLReader::read() && !($this->nodeType === XMLReader::END_ELEMENT && $this->name == $nodeName);
    }

  
    public function isChunk($tag) {
        if (isset($this->CHUNK_ME[$tag])) {
            $isChunk = $this->CHUNK_ME[$tag];
            if (is_array($isChunk)) {
                $isChunk = $this->notXPath($isChunk);
            }
            if (!is_bool($isChunk)) {
                return call_user_func(array($this, $isChunk), $tag);
            }
            return $isChunk;
        }
        return false;
    }
    public function isSectionChunk($tag) {
        if ($this->PREVIOUS_SIBLING == $tag && $this->checkSectionDepth()) {
            return true;
        }
        return false;
    }
    protected function checkSectionDepth() {
        static $allowedParents = array("section", "sect2", "sect3", "sect4", "sect5");
        static $chunkers       = array(
            "sect1", "preface", "chapter", "appendix", "article", "part", "reference", "refentry",
            "index", "bibliography", "glossary", "colopone", "book", "set", "setindex", "legalnotice",
       );
        
        $nodeDepth = $this->depth;
        $i = 1;
        do {
            if (in_array($this->STACK[$nodeDepth-$i], $allowedParents)) {
                ++$i;
                continue;
            }
            break;
        } while(true);
        if ($i <= 1 && in_array($this->STACK[$nodeDepth-$i], $chunkers)) {
            return true;
        }
        return false;
    }
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

