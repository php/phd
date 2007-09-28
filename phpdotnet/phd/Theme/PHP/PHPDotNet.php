<?php
/*  $Id$ */

class phpdotnet extends PhDHelper {
    protected $elementmap = array(
        'acronym'               => 'format_suppressed_tags',
        'function'              => 'format_suppressed_tags',
        'link'                  => 'format_link',
        'refpurpose'            => 'format_refpurpose',
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
    );
    protected $textmap =        array(
        'acronym'               => 'format_acronym_text',
        'classsynopsisinfo'     => 'format_comment_multiline',
        'function'              => 'format_function_text',
        'methodname'            => 'format_function_text',
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

    protected $CURRENT_ID = "";
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
                return sprintf('<a href="%s%s" class="%s">%s</a>',
                    $this->chunked ? "" : "#",
                    $this->chunked ?
                        $href : (isset($linkto) ? $linkto : $href),
                    $class, $content . PhDHelper::getDescription($id, false));
            } elseif ($props["empty"]) {
                return sprintf('<a href="%s%s%s" class="%s">%s%2$s</a>', $this->chunked ? "" : "#", $href, $fragment, $class, $content);
            } else {
                return sprintf('<a href="%s%s%s" class="%s">%s', $this->chunked ? "" : "#", $href, $fragment, $class, $content);
            }
        }
        return "</a>";
    }



    public function versionInfo($funcname) {
        $funcname = str_replace(
                array("::", "->", "__", "_", '$'),
                array("-",  "-",  "-",  "-", ""),
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
            return sprintf('<p class="verinfo">(%s)</p><p class="refpurpose">%s — ', $this->versionInfo($this->refname), $this->refname);
        }
        return "</p>\n";
    }
    public function format_refname_text($value, $tag) {
        $this->refname = $value;
        return false;
    }
    public function format_chunk($open, $name, $attrs) {
        if (isset($attrs[PhDReader::XMLNS_XML]["id"])) {
            $this->CURRENT_ID = $attrs[PhDReader::XMLNS_XML]["id"];
        }
        return false;
    }
    public function format_container_chunk($open, $name, $attrs) {
        $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];
        if ($open) {
            $content = "<div>";

            if ($name != "reference") {
                $chunks = PhDHelper::getChildren($id);
                $content .= '<ul class="chunklist chunklist_'.$name.'">';
                foreach($chunks as $chunkid => $junk) {
                    $content .= sprintf('<li><a href="%s%s.%s">%s</a></li>', $this->chunked ? "" : "#", $chunkid, $this->ext, PhDHelper::getDescription($chunkid, true));
                }
                $content .= "</ul>\n";
            }
            return $content;
        }

        $content = "";
        if ($name == "reference") {
            $chunks = PhDHelper::getChildren($id);
            if (count($chunks) > 1) {
                $content = '<ul class="chunklist chunklist_reference">';
                foreach($chunks as $chunkid => $junk) {
                    $content .= sprintf('<li><a href="%s%s.%s">%s</a> — %s</li>', $this->chunked ? "" : "#", $chunkid, $this->ext, PhDHelper::getDescription($chunkid, false), PhDHelper::getDescription($chunkid, true));
                }
                $content .= "</ul>\n";
            }
        }
        $content .= "</div>\n";
        
        return $content;
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
                $content .= sprintf('<li><a href="%s">%s</a> — %s', $href, $short, $long);
            } else {
                $content .= sprintf('<li><a href="%s">%s</a>', $href, $long ? $long : $short);
            }
            $children = PhDHelper::getChildren($chunkid);
            if (count($children)) {
                $content .= '<ul class="chunklist chunklist_'.$name.' chunklist_children">';
                foreach(PhDHelper::getChildren($chunkid) as $childid => $junk) {
                    $href = $this->chunked ? $childid .'.'. $this->ext : "#$childid";
                    $long = PhDHelper::getDescription($childid, true);
                    $short = PhDHelper::getDescription($childid, false);
                    if ($long && $short && $long != $short) {
                        $content .= sprintf('<li><a href="%s">%s</a> — %s</li>', $href, $short, $long);
                    } else {
                        $content .= sprintf('<li><a href="%s">%s</a></li>', $href, $long ? $long : $short);
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
    public function format_function_text($value, $tag) {
        $link = strtolower(str_replace(array("__", "_", "::", "->"), array("", "-", "-", "-"), $value));

        if (!substr_compare($this->CURRENT_ID, $link, -strlen($link)) || !($filename = PhDHelper::getFilename("function.$link"))) {
            return sprintf("<b>%s%s</b>", $value, $tag == "function" ? "()" : "");
        }

        return sprintf('<a href="%s%s.%s" class="function">%s%s</a>', $this->chunked ? "" : "#", $filename, $this->ext, $value, $tag == "function" ? "()" : "");
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
        }
        if ($href && $this->chunked) {
            return sprintf('<a href="%s.%s%s" class="%s %s">%5$s</a>', $href, $this->ext, ($fragment ? "#$fragment" : ""), $tagname, $type);
        }
        if ($href) {
            return sprintf('<a href="#%s" class="%s %s">%3$s</a>', $fragment ? $fragment : $href, $tagname, $type);
        }
        return sprintf('<span class="%s %s">%2$s</span>', $tagname, $type);
    }
    public function format_comment_multiline($value, $tag) {
        return '<span class="php_comment '.$tag. '_comment">/* ' .$value. ' */</span>';
    }

}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

