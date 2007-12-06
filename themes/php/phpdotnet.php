<?php
/*  $Id$ */

abstract class phpdotnet extends PhDTheme {
    protected $elementmap = array(
        'acronym'               => 'format_suppressed_tags',
        'function'              => 'format_suppressed_tags',
        'link'                  => 'format_link',
        'refpurpose'            => 'format_refpurpose',
        'title'                 => array(
            /* DEFAULT */          false,
            'article'           => 'format_container_chunk_title',
            'appendix'          => 'format_container_chunk_title',
            'chapter'           => 'format_container_chunk_title',
            'example'           => 'format_example_title',
            'part'              => 'format_container_chunk_title',
            'info'              => array(
                /* DEFAULT */      false,
                'article'       => 'format_container_chunk_title',
                'appendix'      => 'format_container_chunk_title',
                'chapter'       => 'format_container_chunk_title',
                'example'       => 'format_example_title',
                'part'          => 'format_container_chunk_title',
            ),
        ),

        'titleabbrev'           => 'format_suppressed_tags',
        'type'                  => array(
            /* DEFAULT */          'format_suppressed_tags',
            'classsynopsisinfo' => false,
            'fieldsynopsis'     => false,
            'methodparam'       => false,
            'methodsynopsis'    => false,
        ),
        'xref'                  => 'format_link',



        'article'               => 'format_container_chunk',
        'appendix'              => 'format_container_chunk',
        'bibliography'          => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'book'                  => 'format_root_chunk',
        'chapter'               => 'format_container_chunk',
        'colophon'              => 'format_chunk',
        'glossary'              => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'index'                 => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'legalnotice'           => 'format_chunk',
        'part'                  => 'format_container_chunk',
        'preface'               => 'format_chunk',
        'refentry'              => 'format_chunk',
        'reference'             => 'format_container_chunk',
        'sect1'                 => 'format_chunk',
        'sect2'                 => 'format_chunk',
        'sect3'                 => 'format_chunk',
        'sect4'                 => 'format_chunk',
        'sect5'                 => 'format_chunk',
        'section'               => 'format_chunk',
        'set'                   => 'format_root_chunk',
        'setindex'              => 'format_chunk',
        'qandaset'              => 'format_qandaset',
        'qandaentry'            => 'format_qandaentry',
        'question'              => 'format_question',
        'answer'                => 'format_answer',
    );
    protected $textmap =        array(
        'acronym'               => 'format_acronym_text',
        'function'              => 'format_function_text',
        'methodname'            => array(
            /* DEFAULT */          'format_function_text',
            'constructorsynopsis' => array(
                /* DEFAULT */      'format_function_text',
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'methodsynopsis'    => array(
                /* DEFAULT */      'format_function_text',
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'destructorsynopsis' => array(
                /* DEFAULT */      'format_function_text',
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
        ),
        'type'                  => array(
            /* DEFAULT */          'format_type_text',
            'classsynopsisinfo' => false,
            'fieldsynopsis'     => false,
            'methodparam'       => false,
            'methodsynopsis'    => false,
        ),
        'refname'               => 'format_refname_text',

        'titleabbrev'           => 'format_suppressed_tags',
    );
    private   $versions = array();
    private   $acronyms = array();
    protected $chunked = true;
    protected $lang = "en";

    protected $CURRENT_ID = "";
    protected $CURRENT_FUNCTION = null;
    protected $refname;

    public function __construct(array $IDs, array $filenames, $ext = "php", $chunked = true) {
        parent::__construct($IDs, $ext);
        $this->ext = $ext;
        if (isset($filenames["version"], $filenames["acronym"])) {
            $this->versions = self::generateVersionInfo($filenames["version"]);
            $this->acronyms = self::generateAcronymInfo($filenames["acronym"]);
        }
        $this->chunked = $chunked;
    }
    public static function generateVersionInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        $r = new XMLReader;
        if (!$r->open($filename)) {
            throw new Exception;
        }
        $versions = array();
        while($r->read()) {
            if (
                $r->moveToAttribute("name")
                && ($funcname = str_replace(
                    array("::", "->", "__", "_", '$'),
                    array("-",  "-",  "-",  "-", ""),
                    $r->value))
                && $r->moveToAttribute("from")
                && ($from = $r->value)
            ) {
                $versions[strtolower($funcname)] = $from;
                $r->moveToElement();
            }
        }
        $r->close();
        $info = $versions;
        return $versions;
    }
    public static function generateAcronymInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        $r = new XMLReader;
        if (!$r->open($filename)) {
            throw new Exception("Could not open $filename");
        }
        $acronyms = array();
        while ($r->read()) {
            if ($r->nodeType != XMLReader::ELEMENT) {
                continue;
            }
            if ($r->name == "term") {
                $r->read();
                $k = $r->value;
                $acronyms[$k] = "";
            } else if ($r->name == "simpara") {
                $r->read();
                $acronyms[$k] = $r->value;
            }
        }
        $info = $acronyms;
        return $acronyms;
    }
    public function format_link($open, $name, $attrs, $props) {
        if ($open) {
            $content = $fragment = "";
            $class = $name;
            if(isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])) {
                $linkto = $attrs[PhDReader::XMLNS_DOCBOOK]["linkend"];
                $id = $href = PhDHelper::getFilename($linkto);
                if ($id != $linkto) {
                    $fragment = "#$linkto";
                }
                $href .= ".".$this->ext;
            } elseif(isset($attrs[PhDReader::XMLNS_XLINK]["href"])) {
                $href = $attrs[PhDReader::XMLNS_XLINK]["href"];
                $content = "&raquo; ";
                $class .= " external";
            }
            if ($name == "xref") {
                if ($this->chunked) {
                    $link = $href;
                } else {
                    $link = "#";
                    if (isset($linkto)) {
                        $link .= $linkto;
                    } else {
                        $link .= $href;
                    }
                }
                return '<a href="' .$link. '" class="' .$class. '">' .($content.PhDHelper::getDescription($id, false)). '</a>';
            } elseif ($props["empty"]) {
                if ($this->chunked) {
                    $link = "";
                } else {
                    $link = "#";
                }
                return '<a href="' .$link.$href.$fragment. '" class="' .$class. '">' .$content.$href.$fragment. '</a>';
            } else {
                if ($this->chunked) {
                    $link = "";
                } else {
                    $link = "#";
                }
                return '<a href="' .$link.$href.$fragment. '" class="' .$class. '">' .$content;
            }
        }
        return "</a>";
    }



    public function versionInfo($funcname) {
        $funcname = str_replace(
                array("::", "->", "__", "_", '$', '()'),
                array("-",  "-",  "-",  "-", "",  ''),
                strtolower($funcname));
        return isset($this->versions[$funcname]) ? $this->versions[$funcname] : "No version information available, might be only in CVS";
    }
    public function acronymInfo($acronym) {
        return isset($this->acronyms[$acronym]) ? $this->acronyms[$acronym] : false;
    }

    public function format_acronym_text($value, $tag) {
        $resolved = $this->acronymInfo($value);
        if ($resolved) {
            return '<acronym title="' .$resolved. '">' .$value. '</acronym>';
        }
        return '<acronym>'.$value.'</acronym>';
    }
    public function format_refpurpose($open, $tag, $attrs) {
        if ($open) {
            return '<p class="verinfo">(' .(htmlspecialchars($this->versionInfo($this->refname), ENT_QUOTES, "UTF-8")). ')</p><p class="refpurpose">'. $this->refname. ' — ';
        }
        return "</p>\n";
    }
    public function format_refname_text($value, $tag) {
        $this->refname = $value;
        return false;
    }
    public function format_chunk($open, $name, $attrs, $props) {
        if (isset($attrs[PhDReader::XMLNS_XML]["id"])) {
            $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];
            if ($name == "refentry") {
                if(strpos($id, "function.") !== false) {
                    $id = substr($id, 9);
                }
                $this->CURRENT_FUNCTION = $id;
            } else {
                $this->CURRENT_FUNCTION = null;
            }
        }
        if ($props["isChunk"]) {
            $this->tmp["chunk"] = array("examples" => 0);
        }
        if (isset($props["lang"])) {
            $this->lang = $props["lang"];
        }
        return false;
    }
    public function format_container_chunk($open, $name, $attrs, $props) {
        $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];
        $this->CURRENT_FUNCTION = null;
        if ($open) {
            if ($props["isChunk"]) {
                $this->tmp["chunk"] = array("examples" => 0);
            }
            if ($name != "reference") {
                $chunks = PhDHelper::getChildren($id);
                if (!count($chunks)) {
                    return "<div>";
                }
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_'.$name.'">';
                foreach($chunks as $chunkid => $junk) {
                    $content .= '<li><a href="' .($this->chunked ? "" : "#").$chunkid. '.' .$this->ext. '">' .(PhDHelper::getDescription($chunkid, true)). '</a></li>';
                }
                $content .= "</ul>\n";
                $this->tmp["container_chunk"] = $content;
            }
            return "<div>";
        }

        $content = "";
        if ($name == "reference") {
            $chunks = PhDHelper::getChildren($id);
            if (count($chunks) > 1) {
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_reference">';
                foreach($chunks as $chunkid => $junk) {
                    $content .= '<li><a href="' .($this->chunked ? "" : "#").$chunkid. '.' .$this->ext. '">' .(PhDHelper::getDescription($chunkid, false)). '</a> — ' .(PhDHelper::getDescription($chunkid, true)). '</li>';
                }
                $content .= "</ul>\n";
            }
        }
        $content .= "</div>\n";
        
        return $content;
    }
    public function format_container_chunk_title($open, $name, $attrs) {
        if ($open) {
            return "<h1>";
        }
        $ret = "";
        if ($this->tmp["container_chunk"]) {
            $ret = $this->tmp["container_chunk"];
            $this->tmp["container_chunk"] = null;
        }
        return "</h1>\n" .$ret;
    }
    public function format_root_chunk($open, $name, $attrs) {
        $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];
        $this->CURRENT_FUNCTION = null;
        if ($open) {
            return "<div>";
        }

        $chunks = PhDHelper::getChildren($id);
        $content = '<ul class="chunklist chunklist_'.$name.'">';
        foreach($chunks as $chunkid => $junk) {
            $href = $this->chunked ? $chunkid .'.'. $this->ext : "#$chunkid";
            $long = PhDHelper::getDescription($chunkid, true);
            $short = PhDHelper::getDescription($chunkid, false);
            if ($long && $short && $long != $short) {
                $content .= '<li><a href="' .$href. '">' .$short. '</a> — ' .$long;
            } else {
                $content .= '<li><a href="' .$href. '">' .($long ? $long : $short). '</a>';
            }
            $children = PhDHelper::getChildren($chunkid);
            if (count($children)) {
                $content .= '<ul class="chunklist chunklist_'.$name.' chunklist_children">';
                foreach(PhDHelper::getChildren($chunkid) as $childid => $junk) {
                    $href = $this->chunked ? $childid .'.'. $this->ext : "#$childid";
                    $long = PhDHelper::getDescription($childid, true);
                    $short = PhDHelper::getDescription($childid, false);
                    if ($long && $short && $long != $short) {
                        $content .= '<li><a href="' .$href. '">' .$short. '</a> — ' .$long. '</li>';
                    } else {
                        $content .= '<li><a href="' .$href. '">' .($long ? $long : $short). '</a></li>';
                    }
                }
                $content .="</ul>";
            }
            $content .= "</li>";
        }
        $content .= "</ul>";

        return $content;
    }

    public function format_suppressed_tags($open, $name) {
        /* ignore it */
        return "";
    }
    
    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        $display_value = $this->format->format_classsynopsis_methodsynopsis_methodname_text($value, $tag);
        return $this->format_function_text($value, $tag, $display_value);
    }
    
    public function format_function_text($value, $tag, $display_value = null) {
        if ($display_value === null) {
            $display_value = $value;
        }
        
        $link = strtolower(str_replace(array("__", "_", "::", "->"), array("", "-", "-", "-"), $value));
        $oop_link = strtolower(str_replace(array("_", "::", "->"), array("", ".", "."), $value));
        
        if (
            (
                $this->CURRENT_FUNCTION === $link ||
                !($filename = PhDHelper::getFilename("function.$link"))
            ) &&
            (
                $this->CURRENT_ID === $oop_link ||
                !($filename = PhDHelper::getFilename($oop_link))
            )
        ) {
            return '<b>' .$display_value.($tag == "function" ? "()" : ""). '</b>';
        }

        return '<a href="' .($this->chunked ? "" : "#").$filename. '.' .$this->ext. '" class="function">' .$display_value.($tag == "function" ? "()" : ""). '</a>';
    }
    public function format_type_text($type, $tagname) {
        $t = strtolower($type);
        $href = $fragment = "";

        switch($t) {
        case "bool":
            $href = "language.types.boolean";
            break;
        case "int":
            $href = "language.types.integer";
            break;
        case "double":
            $href = "language.types.float";
            break;
        case "boolean":
        case "integer":
        case "float":
        case "string":
        case "array":
        case "object":
        case "resource":
        case "null":
            $href = "language.types.$t";
            break;
        case "mixed":
        case "number":
        case "callback":
            $href = "language.pseudo-types";
            $fragment = "language.types.$t";
            break;
        default:
            /* Check if its a classname. */
            $href = PhDTheme::getFilename("class.$t");
        }

        if ($href && $this->chunked) {
            return '<a href="' .$href. '.' .$this->ext.($fragment ? "#$fragment" : ""). '" class="' .$tagname. ' ' .$type. '">' .$type. '</a>';
        }
        if ($href) {
            return '<a href="#' .($fragment ? $fragment : $href). '" class="' .$tagname. ' ' .$type. '">' .$type. '</a>';
        }
        return '<span class="' .$tagname. ' ' .$type. '">' .$type. '</span>';
    }

    public function format_example_title($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            return "<p><b>Example#" .++$this->tmp["chunk"]["examples"]. " ";
        }
        return "</b></p>";
    }
 
    /* FIXME: This function is a crazy performance killer */
    public function qandaset($stream) {
        $xml = stream_get_contents($stream);

        libxml_use_internal_errors(true);
        $doc = new DOMDocument("1.0", "UTF-8");
        $doc->preserveWhitespace = false;
        $doc->loadHTML(html_entity_decode('<div>' .str_replace("&lt;", "&amp;lt;", $xml) .'</div>', ENT_QUOTES, "UTF-8"));
        fclose($stream);
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        $xpath = new DOMXPath($doc);
        $nlist = $xpath->query("//html/body/div/dl/dt");
        $ret = '<div class="qandaset"><ol class="qandaset_questions">';
        $i = 0;
        foreach($nlist as $node) {
            $ret .= '<li><a href="#' .($this->tmp["qandaentry"][$i++]). '">' .(htmlspecialchars($node->textContent,ENT_QUOTES, "UTF-8")). '</a></li>';
        }

        return $ret.'</ul>'.$xml.'</div>';
    }
    public function format_qandaentry($open, $name, $attrs) {
        if ($open) {
            $this->tmp["qandaentry"][] = $attrs[PhDReader::XMLNS_XML]["id"];
            return '<dl>';
        }
        return '</dl>';
    }
    public function format_answer($open, $name, $attrs) {
        if ($open) {
            return '<dd><a name="' .end($this->tmp["qandaentry"]).'"></a>';
        }
        return "</dd>";
    }
    public function format_question($open, $name, $attrs) {
        if ($open) {
            return '<dt><strong>';
        }
        return '</strong></dt>';
    }

}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

