<?php
namespace phpdotnet\phd;

abstract class Package_PHP_XHTML extends Package_Default_XHTML {
    private $myelementmap = array(
        'appendix'              => 'format_container_chunk',
        'article'               => 'format_container_chunk',
        'book'                  => 'format_root_chunk',
        'chapter'               => 'format_container_chunk',
        'colophon'              => 'format_chunk',
        'legalnotice'           => 'format_chunk',
        'part'                  => 'format_container_chunk',
        'preface'               => 'format_chunk',
        'phpdoc:classref'       => 'format_class_chunk',
        'phpdoc:exceptionref'   => 'format_exception_chunk',
        'phpdoc:varentry'       => 'format_varentry_chunk',
        'refentry'              => 'format_chunk',
        'reference'             => 'format_container_chunk',
        'refpurpose'            => 'format_refpurpose',
        'set'                   => 'format_root_chunk',
        'setindex'              => 'format_chunk',
        'title'                 => array(
            /* DEFAULT */          'h1',
            'article'           => 'format_container_chunk_title',
            'appendix'          => 'format_container_chunk_title',
            'chapter'           => 'format_container_chunk_title',
            'example'           => 'format_example_title',
            'part'              => 'format_container_chunk_title',
            'info'              => array(
                /* DEFAULT */      'h1',
                'article'       => 'format_container_chunk_title',
                'appendix'      => 'format_container_chunk_title',
                'chapter'       => 'format_container_chunk_title',
                'example'       => 'format_example_title',
                'part'          => 'format_container_chunk_title',
            ),            
            'indexdiv'          => 'dt',
            'legalnotice'       => 'h4',
            'note'              => 'format_note_title',
            'phd:toc'           => 'strong',
            'procedure'         => 'b',
            'refsect1'          => 'h3',
            'refsect2'          => 'h4',
            'refsect3'          => 'h5',
            'section'           => 'h2',
            'sect1'             => 'h2',
            'sect2'             => 'h3',
            'sect3'             => 'h4',
            'sect4'             => 'h5',
            'segmentedlist'     => 'strong',
            'simplesect'        => 'h3',
            'table'             => 'format_table_title',
            'variablelist'      => 'strong',
        ),        
    );
    private $mytextmap = array(
        'acronym'               => 'format_acronym_text',
        'classname'             => 'format_classname_text',
        'function'              => 'format_function_text',
        'interfacename'         => 'format_classname_text',
        'refname'               => 'format_refname_text', 

        'methodname'            => array(
            /* DEFAULT */          'format_function_text',
//            'constructorsynopsis' => array(
//                /* DEFAULT */      'format_function_text',
//                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
//            ),
//            'methodsynopsis'    => array(
//                /* DEFAULT */      'format_function_text',
//                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
//            ),
//            'destructorsynopsis' => array(
//                /* DEFAULT */      'format_function_text',
//                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
//            ),
        ),
//        'type'                  => array(
//            /* DEFAULT */          'format_type_text',
//            'classsynopsisinfo' => false,
//            'fieldsynopsis'     => 'format_type_if_object_or_pseudo_text',
//            'methodparam'       => 'format_type_if_object_or_pseudo_text',
//            'methodsynopsis'    => array(
//                /* DEFAULT */      'format_type_if_object_or_pseudo_text',
//                'classsynopsis' => false,
//            ),
//        ),

        'titleabbrev'           => array(
            /* DEFAULT */          'format_suppressed_text',
            'phpdoc:classref'   => 'format_grep_classname_text',
            'phpdoc:exceptionref'  => 'format_grep_classname_text',
        ),
         'varname'               => array(
            /* DEFAULT */          'format_varname_text',
            'fieldsynopsis'     => false,
        ),        
    );

    private $versions = array();
    private $acronyms = array();
    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */

    private $dchunk          = array(
        "phpdoc:classref"              => null,
        "fieldsynopsis"                => array(
            "modifier"                 => "public",
        ),
        "container_chunk"              => null,
        "qandaentry"                   => array(
        ),
        "examples"                     => 0,
        "verinfo"                      => false,
        "refname"                      => array(),
    );

    public function __construct() {
        parent::__construct();
        $this->versions = self::generateVersionInfo(Config::phpweb_version_filename());
        $this->acronyms = self::generateAcronymInfo(Config::phpweb_acronym_filename());
        
        $this->myelementmap = array_merge(parent::getDefaultElementMap(), static::getDefaultElementMap());
        $this->mytextmap = array_merge(parent::getDefaultTextMap(), static::getDefaultTextMap());
        $this->dchunk = array_merge(parent::getDefaultChunkInfo(), static::getDefaultChunkInfo());
    }

    public function getDefaultElementMap() {
        return $this->myelementmap;
    }

    public function getDefaultTextMap() {
        return $this->mytextmap;
    }

    public function getDefaultChunkInfo() {
        return $this->dchunk;
    }

    public static function generateVersionInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        $r = new \XMLReader;
        if (!$r->open($filename)) {
            throw new \Exception;
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
        $r = new \XMLReader;
        if (!$r->open($filename)) {
            throw new \Exception("Could not open $filename");
        }
        $acronyms = array();
        while ($r->read()) {
            if ($r->nodeType != \XMLReader::ELEMENT) {
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

    public function format_imagedata($open, $name, $attrs) {
        $file    = $attrs[Reader::XMLNS_DOCBOOK]["fileref"];
        $newpath = $this->mediamanager->handleFile($file);

        if ($this->cchunk["mediaobject"]["alt"] !== false) {
            return '<img src="' . $newpath . '" alt="' .$this->cchunk["mediaobject"]["alt"]. '" />';
        }
        return '<img src="' . $newpath . '" />';
    }

    public function format_refpurpose($open, $tag, $attrs, $props) {
        if ($open) {
            $retval = "";
            if ($this->cchunk["verinfo"]) {
                $verinfo = "";
                foreach((array)$this->cchunk["refname"] as $refname) {
                    $verinfo = $this->versionInfo($refname);

                    if ($verinfo) {
                        break;
                    }
                }
                if (!$verinfo) {
                    $verinfo = $this->autogen("unknownversion", $props["lang"]);
                }

                $retval = '<p class="verinfo">(' .(htmlspecialchars($verinfo, ENT_QUOTES, "UTF-8")). ')</p>';
            }
            $refnames = implode('</span> -- <span class="refname">', $this->cchunk["refname"]);

            $retval .= '<p class="refpurpose"><span class="refname">'. $refnames. '</span> &mdash; <span class="dc-title">';
            return $retval;
        }
        return "</span></p>\n";
    }

    public function format_refname_text($value, $tag) {
        $this->cchunk["refname"][] = $this->TEXT($value);
        return false;
    }

    public function format_varname_text($value, $tag) {
        $var = $value;
        if (($pos = strpos($value, "[")) !== false) {
            $var = substr($value, 0, $pos);
        }
        if (($filename = $this->getVarnameLink($var)) !== null && !in_array($var, $this->cchunk["refname"])) {
            if ($this->chunked) {
                return '<var class="varname"><a href="'.$filename. '.' .$this->ext. '" class="classname">' .$value. '</a></var>';
            }
            return '<var><a href="#'.$filename. '" class="classname">'.$value.'</a></var>';
        }
        return '<var class="varname">' .$value. '</var>';

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

    public function versionInfo($funcname) {
        $funcname = str_replace(
                array("::", "-&gt;", "->", "__", "_", '$', '()'),
                array("-",  "-",     "-",  "-",  "-", "",  ''),
                strtolower($funcname));
        if(isset($this->versions[$funcname])) {
           return $this->versions[$funcname];
        }
        v("No version info for $funcname", VERBOSE_NOVERSION);
        return false;
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

    public function format_function_text($value, $tag, $display_value = null) {
        if ($display_value === null) {
            $display_value = $value;
        }

        $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $value));
        if (($filename = $this->getRefnameLink($ref)) !== null) {
            if ($this->CURRENT_ID !== $filename) {
                $rel = $desc = "";
                if ($this->role == "seealso") {
                    $rel  = ' rel="rdfs-seeAlso"';
                    $desc = " - " . Format::getShortDescription($filename);
                }

                if ($this->chunked) {
                    return '<a href="'.$filename. '.' .$this->ext. '" class="function"'.$rel.'>' .$display_value.($tag == "function" ? "()" : ""). '</a>'.$desc;
                }
                return '<a href="#'.$filename. '" class="function"'.$rel.'>' .$display_value.($tag == "function" ? "()" : ""). '</a>'.$desc;
            }
        } elseif ($this->CURRENT_ID !== $filename) {
            v("No link found for $value", VERBOSE_BROKEN_LINKS);
        }

        return '<b>' .$display_value.($tag == "function" ? "()" : ""). '</b>';
    }

    public function format_grep_classname_text($value, $tag) {
        $this->cchunk["phpdoc:classref"] = strtolower($value);
    }

    public function format_classname_text($value, $tag) {
        if (($filename = $this->getClassnameLink(strtolower($value))) !== null && $this->cchunk["phpdoc:classref"] !== strtolower($value)) {
            if ($this->chunked) {
                return '<a href="'.$filename. '.' .$this->ext. '" class="' .$tag. '">' .$value. '</a>';
            }
            return '<a href="#'.$filename. '" class="' .$tag. '">'.$value.'</a>';
        }
        return '<b class="' .$tag. '">' .$value. '</b>';
    }


    /*Chunk Functions*/

    public function format_container_chunk($open, $name, $attrs, $props) {
        $this->CURRENT_CHUNK = $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]["id"];
        
        if ($open) {
            $this->notify(Render::CHUNK, Render::OPEN);
            if ($props["isChunk"]) {
                $this->cchunk = $this->dchunk;
            }
            if ($name != "reference") {
                $chunks = Format::getChildrens($id);
                if (!count($chunks)) {
                    return '<div id="'.$id.'" class="'.$name.'">';
                }
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_'.$name.'">';
                foreach($chunks as $chunkid) {
                    if ($this->chunked) {
                        $content .= '<li><a href="'.$chunkid. '.' .$this->ext. '">' .(Format::getShortDescription($chunkid)). '</a></li>';
                    } else {
                        $content .= '<li><a href="#'.$chunkid. '">' .(Format::getShortDescription($chunkid)). '</a></li>';
                    }
                }
                $content .= "</ul>\n";
                $this->cchunk["container_chunk"] = $content;
            }
            return '<div id="'.$id.'" class="'.$name.'">';
        }
        $this->notify(Render::CHUNK, Render::CLOSE);

        $content = "";
        if ($name == "reference") {
            $chunks = Format::getChildrens($id);
            if (count($chunks)) {
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_reference">';
                foreach($chunks as $chunkid) {
                    if ($this->chunked) {
                        $content .= '<li><a href="'.$chunkid. '.' .$this->ext. '">' .(Format::getShortDescription($chunkid)). '</a> — ' .(Format::getLongDescription($chunkid)). '</li>';
                    } else {
                        $content .= '<li><a href="#'.$chunkid.'">' .(Format::getShortDescription($chunkid)). '</a> — ' .(Format::getLongDescription($chunkid)). '</li>';
                    }
                }
                $content .= "</ul>\n";
            }
        }
        $content .= "</div>\n";

        return $content;
    }

    public function format_root_chunk($open, $name, $attrs) {
        $this->CURRENT_CHUNK = $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]["id"];
        if ($open) {
            $this->notify(Render::CHUNK, Render::OPEN);
            return '<div id="'.$id.'" class="'.$name.'">';
        }
        $this->notify(Render::CHUNK, Render::CLOSE);
        $chunks = Format::getChildrens($id);
        $content = '<ul class="chunklist chunklist_'.$name.'">';
        foreach($chunks as $chunkid) {
            $href = $this->chunked ? $chunkid .'.'. $this->ext : "#$chunkid";
            $long = Format::getLongDescription($chunkid);
            $short = Format::getShortDescription($chunkid);
            if ($long && $short && $long != $short) {
                $content .= '<li><a href="' .$href. '">' .$short. '</a> — ' .$long;
            } else {
                $content .= '<li><a href="' .$href. '">' .($long ? $long : $short). '</a>';
            }
            $children = Format::getChildrens($chunkid);
            if (count($children)) {
                $content .= '<ul class="chunklist chunklist_'.$name.' chunklist_children">';
                foreach(Format::getChildrens($chunkid) as $childid) {
                    $href = $this->chunked ? $childid .'.'. $this->ext : "#$childid";
                    $long = Format::getLongDescription($childid);
                    $short = Format::getShortDescription($childid);
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
        $content .= "</ul></div>";

        return $content;
    }

    public function format_chunk($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
            }
            $this->CURRENT_CHUNK = $this->CURRENT_ID = $id;
            if (!isset($attrs[Reader::XMLNS_PHD]["chunk"]) || $attrs[Reader::XMLNS_PHD]["chunk"] == "true") {            
                $this->cchunk = $this->dchunk;
                $this->notify(Render::CHUNK, Render::OPEN);
            }
            if (isset($props["lang"])) {
                $this->lang = $props["lang"];
            }
            if ($name == "refentry") {
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                    $this->cchunk["verinfo"] = !($attrs[Reader::XMLNS_DOCBOOK]["role"] == "noversion");
                } else {
                    $this->cchunk["verinfo"] = true;
                }
            }
            return '<div id="'.$id.'" class="'.$name.'">';
        }
        $this->notify(Render::CHUNK, Render::CLOSE);
        return '</div>';
    }

    public function format_container_chunk_title($open, $name, $attrs, $props) {
        if ($open) {
            return $props["empty"] ? '' : '<h1>';
        }
        $ret = "";
        if ($this->cchunk["container_chunk"]) {
            $ret = $this->cchunk["container_chunk"];
            $this->cchunk["container_chunk"] = null;
        }
        return "</h1>\n" .$ret;
    }

    public function format_varentry_chunk($open, $name, $attrs, $props) {
        return $this->format_chunk($open, "refentry", $attrs, $props);
    }

    public function format_exception_chunk($open, $name, $attrs, $props) {
        return $this->format_container_chunk($open, "reference", $attrs, $props);
    }

    public function format_class_chunk($open, $name, $attrs, $props) {
        return $this->format_container_chunk($open, "reference", $attrs, $props);
    }

}

?>
