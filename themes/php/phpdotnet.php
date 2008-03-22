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
        'varname'               => array(
            /* DEFAULT */          false,
            'fieldsynopsis'     => 'format_fieldsynopsis_varname',
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
            'fieldsynopsis'     => 'format_type_if_object_or_pseudo_text',
            'methodparam'       => 'format_type_if_object_or_pseudo_text',
            'methodsynopsis'    => array(
                /* DEFAULT */      'format_type_if_object_or_pseudo_text',
                'classsynopsis' => false,
            ),
        ),
        'refname'               => 'format_refname_text',

        'titleabbrev'           => 'format_suppressed_tags',
    );
    private   $versions = array();
    private   $acronyms = array();
    protected $chunked = true;
    protected $lang = "en";

    protected $CURRENT_ID = "";
    protected $refname;

    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array(
        "fieldsynopsis"                => array(
            "modifier"                          => "public",
        ),
        "container_chunk"              => null,
        "qandaentry"                   => array(
        ),
        "examples"                     => 0,
    );

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
                if ($this->chunked) {
                    $href .= ".".$this->ext;
                }
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
                    $link = $href.$fragment;
                } elseif(isset($linkto)) {
                    if ($fragment) {
                        $link = $fragment;
                    } else {
                        $link = "#$href";
                    }
                } else {
                    $link = $href;
                }
                return '<a href="' .$link. '" class="' .$class. '">' .$content;
            }
        }
        return "</a>";
    }
    public function format_fieldsynopsis_varname($open, $name, $attrs) {
        if ($open) {
            $href = "";
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])) {
                $linkto = $attrs[PhDReader::XMLNS_DOCBOOK]["linkend"];
                $href = PhDHelper::getFilename($linkto);

                if ($this->chunked) {
                    if ($href != $linkto) {
                        $href .= ".{$this->ext}#{$linkto}";
                    } else {
                        $href .= '.' .$this->ext;
                    }
                } else {
                    $href = '#' .$linkto;
                }
                $href = '<a href="' .$href. '">';
            }
            if ($this->cchunk["fieldsynopsis"]["modifier"] == "const") {
                return '<var class="fieldsynopsis_varname">'.$href;
            }
            return '<var class="'.$name.'">'.$href.'$';
        }
        if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])) {
            return '</a></var>';
        }
        return '</var>';
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
            return '<p class="verinfo">(' .(htmlspecialchars($this->versionInfo($this->refname), ENT_QUOTES, "UTF-8")). ')</p><p class="refpurpose dc-title">'. $this->refname. ' — ';
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
        }
        if ($props["isChunk"]) {
            $this->cchunk = $this->dchunk;
        }
        if (isset($props["lang"])) {
            $this->lang = $props["lang"];
        }
        return false;
    }
    public function format_container_chunk($open, $name, $attrs, $props) {
        $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];
        if ($open) {
            if ($props["isChunk"]) {
                $this->cchunk = $this->dchunk;
            }
            if ($name != "reference") {
                $chunks = PhDHelper::getChildren($id);
                if (!count($chunks)) {
                    return "<div>";
                }
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_'.$name.'">';
                foreach($chunks as $chunkid => $junk) {
                    if ($this->chunked) {
                        $content .= '<li><a href="'.$chunkid. '.' .$this->ext. '">' .(PhDHelper::getDescription($chunkid, true)). '</a></li>';
                    } else {
                        $content .= '<li><a href="#'.$chunkid. '">' .(PhDHelper::getDescription($chunkid, true)). '</a></li>';
                    }
                }
                $content .= "</ul>\n";
                $this->cchunk["container_chunk"] = $content;
            }
            return "<div>";
        }

        $content = "";
        if ($name == "reference") {
            $chunks = PhDHelper::getChildren($id);
            if (count($chunks)) {
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_reference">';
                foreach($chunks as $chunkid => $junk) {
                    if ($this->chunked) {
                        $content .= '<li><a href="'.$chunkid. '.' .$this->ext. '">' .(PhDHelper::getDescription($chunkid, false)). '</a> — ' .(PhDHelper::getDescription($chunkid, true)). '</li>';
                    } else {
                        $content .= '<li><a href="#'.$chunkid.'">' .(PhDHelper::getDescription($chunkid, false)). '</a> — ' .(PhDHelper::getDescription($chunkid, true)). '</li>';
                    }
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
        if ($this->cchunk["container_chunk"]) {
            $ret = $this->cchunk["container_chunk"];
            $this->cchunk["container_chunk"] = null;
        }
        return "</h1>\n" .$ret;
    }
    public function format_root_chunk($open, $name, $attrs) {
        $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];
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
        $rel = "";
        if ($this->format->role == "seealso") {
            $rel = ' rel="rdfs-seeAlso"';
        }
        
        $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $value));
        if (($filename = $this->getRefnameLink($ref)) !== null && $this->CURRENT_ID !== $filename) {
            if ($this->chunked) {
                return '<a href="'.$filename. '.' .$this->ext. '" class="function"'.$rel.'>' .$display_value.($tag == "function" ? "()" : ""). '</a>';
            }
            return '<a href="#'.$filename. '" class="function"'.$rel.'>' .$display_value.($tag == "function" ? "()" : ""). '</a>';
        }
        return '<b>' .$display_value.($tag == "function" ? "()" : ""). '</b>';
    }
    public function format_type_if_object_or_pseudo_text($type, $tagname) {
        if (in_array(strtolower($type), array("bool", "int", "double", "boolean", "integer", "float", "string", "array", "object", "resource", "null"))) {
            return false;
        }
        return self::format_type_text($type, $tagname);
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
            return "<p><b>" . ($this->autogen('example', $props['lang']) . ++$this->cchunk["examples"]) . " ";
        }
        return "</b></p>";
    }
 
    /* FIXME: This function is a crazy performance killer */
    public function qandaset($stream) {
        $xml = stream_get_contents($stream);

        $old = libxml_use_internal_errors(true);
        $doc = new DOMDocument("1.0", "UTF-8");
        $doc->preserveWhitespace = false;
        $doc->loadXML(html_entity_decode(str_replace("&", "&amp;amp;", "<div>$xml</div>"), ENT_QUOTES, "UTF-8"));
        if ($err = libxml_get_errors()) {
            print_r($err);
            libxml_clear_errors();
        }
        fclose($stream);
        libxml_use_internal_errors($old);

        $xpath = new DOMXPath($doc);
        $nlist = $xpath->query("//div/dl/dt");
        $ret = '<div class="qandaset"><ol class="qandaset_questions">';
        $i = 0;
        foreach($nlist as $node) {
            $ret .= '<li><a href="#' .($this->cchunk["qandaentry"][$i++]). '">' .($node->textContent). '</a></li>';
        }

        return $ret.'</ol>'.$xml.'</div>';
    }
    public function format_qandaentry($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["qandaentry"][] = $attrs[PhDReader::XMLNS_XML]["id"];
            return '<dl>';
        }
        return '</dl>';
    }
    public function format_answer($open, $name, $attrs) {
        if ($open) {
            return '<dd><a name="' .end($this->cchunk["qandaentry"]).'"></a>';
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

