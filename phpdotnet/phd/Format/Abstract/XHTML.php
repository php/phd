<?php
namespace phpdotnet\phd;

abstract class Format_Abstract_XHTML extends Format
{
    private $formatname = "XHTML";
    
    protected $title; 
    protected $flags;
    protected $ext = "html";
    protected $fp = array();
    protected $outputdir = __DIR__;

    public $role        = false;
    /* Current Chunk variables */
    protected $cchunk      = array();
    /* Default Chunk variables */
    protected $dchunk      = array(
        "classsynopsis"            => array(
            "close"                         => false,
            "classname"                     => false,
        ),
        "classsynopsisinfo"        => array(
            "implements"                    => false,
            "ooclass"                       => false,
        ),
        "examples"                 => 0,
        "fieldsynopsis"            => array(
            "modifier"                      => "public",
        ),
        "co"                       => 0,
        "callouts"                 => 0,
        "segmentedlist"            => array(
            "seglistitem"                   => 0,
            "segtitle"                      => array(
            ),
        ),
        "table"                    => false,
        "procedure"                => false,
        "mediaobject"              => array(
            "alt"                           => false,
        ),
        "footnote"                 => array(
        ),
        "tablefootnotes"           => array(
        ),
    );

    public function __construct() {
        parent::__construct();
        parent::registerFormatName($this->formatname);
    }

    public abstract function header($id);
    public abstract function footer($id);

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        if ($open) {
            $idstr = "";
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
                $idstr = ' id="' .$id. '" name="' .$id. '"';
            }
            return '<' .$tag. ' class="' .$name. '"' . $idstr . ($props["empty"] ? '/' : "") . '>';
        }
        return '</' .$tag. '>';
    }

    public function appendData($data) {
    	if ($this->appendToBuffer) {
    		$this->buffer .= $data;
    		return;
    	} elseif ($this->flags & Render::CLOSE) {
            $fp = array_pop($this->fp);
            fwrite($fp, $data);
            $this->writeChunk($this->CURRENT_CHUNK, $fp);
            fclose($fp);

            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            $this->fp[] = $fp = fopen("php://temp/maxmemory", "r+");
            fwrite($fp, $data);

            $this->flags ^= Render::OPEN;
        } else {
            $fp = end($this->fp);
            fwrite($fp, $data);
        }
    }

    public function writeChunk($id, $fp) {
        $filename = $this->outputdir . $id . '.' .$this->ext;

        rewind($fp);
        file_put_contents($filename, $this->header($id));
        file_put_contents($filename, $fp, FILE_APPEND);
        file_put_contents($filename, $this->footer($id), FILE_APPEND);
    }

    public function close() {
        foreach ($this->fp as $fp) {
            fclose($fp);
        }
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {
        $retval = null;
        if (isset($this->indexes[$for])) {
            $rsl = $this->indexes[$for];
            $retval = $rsl["filename"] . "." . $this->ext . '#' . $rsl["docbook_id"];
            $desc = $rsl["sdesc"] ?: $rsl["ldesc"];
        }
        return $retval;
    }

    public function TEXT($str) {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }

    public function CDATA($str) {
        switch($this->role) {
        case "php":
            return '<div class="phpcode">' .(highlight_string(trim($str), 1)). '</div>';
            break;
        default:
            return '<div class="cdata"><pre>' .(htmlspecialchars($str, ENT_QUOTES, "UTF-8")). '</pre></div>';
        }
    }

    public function UNDEF($open, $name, $attrs, $props) {
        if ($open) {
            trigger_error("No mapper found for '{$name}'", E_USER_WARNING);
        }
    }

    protected function createTOC($id, $lang) {
        if (!$this->getChildrens($id)) {
            return "";
        }
        $toc = '<h2>' . $this->autogen('toc', $lang) . '</h2><ol>';
        foreach ($this->getChildrens($id) as $child) {
            $isLDesc = null;
            $isSDesc = null;
            $long = $this->parse($this->getLongDescription($child, $isLDesc));
            $short = $this->getShortDescription($child, $isSDesc);
            $link = $this->createLink($child);

            $list = "";
            if ($this->cchunk["name"] === "book" || $this->cchunk["name"] === "set") {
                if ($this->getChildrens($child)) {
                    $list = "<ol>";
                    foreach ($this->getChildrens($child) as $subChild) {
                        $isSubLDesc = null;
                        $isSubSDesc = null;
                        $subLong = $this->parse($this->getLongDescription($subChild, $isLDesc));
                        $subShort = $this->getShortDescription($subChild, $isSubSDesc);

                        $href = $this->createLink($subChild);
                        if ($isSubLDesc && $isSubSDesc) {
                            $list .= '<li><a href="' . $href . '">' . $subShort . '</a> — ' . $subLong . "</li>\n";
                        } else {
                            $list .= '<li><a href="' . $href . '">' . ($subLong ?: $subShort) . '</a>' . "</li>\n";
                        }
                    }
                    $list .="</ol>";
                }
            }
            if ($isLDesc && $isSDesc) {
                $toc .= '<li><a href="' . $link . '">' . $short . '</a> — ' . $long . $list . "</li>\n";
            } else {
                $toc .= '<li><a href="' . $link . '">' . ($long ?: $short) . '</a>' . $list .  "</li>\n";
            }
        }
        $toc .= "</ol>\n";
        return $toc;
    }


    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
            $this->flags = $val;
            break;

        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap(static::getDefaultElementMap());
                $this->registerTextMap(static::getDefaultTextMap());
            }
            break;

        case Render::INIT:
            $this->outputdir = $tmp = Config::output_dir() . strtolower($this->getFormatName()) . '/';
            if (file_exists($tmp)) {
                if (!is_dir($tmp)) {
                    v("Output directory is a file?", E_USER_ERROR);
                }
            } else {
                if (!mkdir($tmp)) {
                    v("Can't create output directory", E_USER_ERROR);
                }
            }
            break;
        case Render::VERBOSE:
        	v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
        	break;
        }
    }

    public function getChunkInfo() {
        return $this->cchunk;
    }

}
