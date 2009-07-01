<?php
namespace phpdotnet\phd;

abstract class Package_PHP_XHTML extends Package_Default_XHTML {
    private $myelementmap = array(
        'acronym'               => 'format_suppressed_tags',
        'function'              => 'format_suppressed_tags',
        'methodname'            => 'format_methodname',
        'classname'             => 'format_suppressed_tags',
        'interfacename'         => 'format_suppressed_tags',
        'link'                  => 'format_link',
        'refpurpose'            => 'format_refpurpose',
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
            /* DEFAULT */          'format_suppressed_tags',
            'fieldsynopsis'     => 'format_fieldsynopsis_varname',
        ),
        'xref'                  => 'format_link',



        'article'               => 'format_container_chunk',
        'appendix'              => 'format_container_chunk',
        'bibliography'          => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'book'                  => 'format_root_chunk',
        'chapter'               => 'format_container_chunk',
        'colophon'              => 'format_chunk',
        'glossary'              => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'imagedata'             => 'format_imagedata',
        'index'                 => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'legalnotice'           => 'format_chunk',
        'part'                  => 'format_container_chunk',
        'preface'               => 'format_chunk',
        'refentry'              => 'format_chunk',
        'phpdoc:varentry'       => 'format_varentry_chunk',
        'reference'             => 'format_container_chunk',
        'phd:toc'               => 'format_phd_toc',
        'phpdoc:exceptionref'   => 'format_exception_chunk',
        'phpdoc:classref'       => 'format_class_chunk',
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
        'uri'                   => 'tt',
        'varlistentry'          => 'format_varlistentry',
    );
    private $mytextmap =        array(
        'acronym'               => 'format_acronym_text',
        'function'              => 'format_function_text',
        'classname'             => 'format_classname_text',
        'classname'            => array(
            /* DEFAULT */         'format_classname_text',
            'ooclass'          => array(
                /* DEFAULT */     'format_classname_text',
                'classsynopsis' => 'format_classsynopsis_ooclass_classname_text',
            ),
        ),

        'interfacename'         => 'format_classname_text',
        'varname'               => array(
            /* DEFAULT */          'format_varname_text',
            'fieldsynopsis'     => false,
        ),
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

        'titleabbrev'           => array(
            /* DEFAULT */          'format_suppressed_tags',
            'phpdoc:classref'   => 'format_grep_classname_text',
            'phpdoc:exceptionref'  => 'format_grep_classname_text',
        ),
    );
    private   $versions = array();
    private   $acronyms = array();

    /**
    * Name of the ID currently being processed
    *
    * @var string
    */
    protected $CURRENT_ID = "";

    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array(
        "phpdoc:classref"              => null,
        "fieldsynopsis"                => array(
            "modifier"                          => "public",
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
    }

    public function getDefaultElementMap() {
        return $this->myelementmap;
    }

    public function getDefaultTextMap() {
        return $this->mytextmap;
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

    public function format_link($open, $name, $attrs, $props) {
        if ($open) {
            $content = $fragment = "";
            $class = $name;

            if(isset($attrs[Reader::XMLNS_DOCBOOK]["linkend"])) {
                $linkto = $attrs[Reader::XMLNS_DOCBOOK]["linkend"];
                $id = $href = Format::getFilename($linkto);

                if ($id != $linkto) {
                    $fragment = "#$linkto";
                }
                if ($this->chunked) {
                    $href .= ".".$this->ext;
                }
            } elseif(isset($attrs[Reader::XMLNS_XLINK]["href"])) {
                $href = $attrs[Reader::XMLNS_XLINK]["href"];
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
                return '<a href="' .$link. '" class="' .$class. '">' .($content.Format::getLongDescription($id)). '</a>';
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
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["linkend"])) {
                $linkto = $attrs[Reader::XMLNS_DOCBOOK]["linkend"];
                $href = Format::getFilename($linkto);

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

            if (
                $this->cchunk["fieldsynopsis"]["modifier"] == "const" ||
                (
                    $nfo = $this->getChunkInfo() AND $nfo["fieldsynopsis"]["modifier"] == "const"
                )
            ) {
                return ' <var class="fieldsynopsis_varname">'.$href;
            }
            return ' <var class="'.$name.'">'.$href.'$';
        }
        if (isset($attrs[Reader::XMLNS_DOCBOOK]["linkend"])) {
            return '</a></var>';
        }
        return '</var>';
    }

    function format_methodname($open, $tag) {
        if ($open) {
            return ' <span class="' . $tag. '">';
        }
        return "</span>";
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

    public function format_refpurpose($open, $tag, $attrs, $props) {
        if ($open) {
            $retval = "";
            if ($this->cchunk["verinfo"]) {
                $verinfo = "";
                foreach($this->cchunk["refname"] as $refname) {
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
            $this->cchunk["refname"] = $this->dchunk["refname"];
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
    
    public function format_suppressed_tags($open, $name) {
        /* ignore it */
        return "";
    }
/*
    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        $display_value = $this->format_classsynopsis_methodsynopsis_methodname_text($value, $tag);
        return $this->format_function_text($value, $tag, $display_value);
    }
*/
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
/*
    public function format_classsynopsis_ooclass_classname_text($value, $tag) {
        return $this->format_classname_text($this->format_classsynopsis_ooclass_classname_text($value, $tag), $tag);
    }
*/
    public function format_classname_text($value, $tag) {
        if (($filename = $this->getClassnameLink(strtolower($value))) !== null && $this->cchunk["phpdoc:classref"] !== strtolower($value)) {
            if ($this->chunked) {
                return '<a href="'.$filename. '.' .$this->ext. '" class="' .$tag. '">' .$value. '</a>';
            }
            return '<a href="#'.$filename. '" class="' .$tag. '">'.$value.'</a>';
        }
        return '<b class="' .$tag. '">' .$value. '</b>';
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
            $href = Format::getFilename("class.$t");
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

    public function format_qandaset($open, $name, $attrs, $props) {
        if ($open) {
            $node = $this->getReader()->expand();
            $doc = new \DOMDocument;
            $doc->appendChild($node);

            $xp = new \DOMXPath($doc);
            $xp->registerNamespace("db", Reader::XMLNS_DOCBOOK);

            $questions = $xp->query("//db:qandaentry/db:question");

            $xml = '<questions xmlns="' .Reader::XMLNS_PHD. '">';
            foreach($questions as $node) {
                $id = $xp->evaluate("ancestor::db:qandaentry", $node)->item(0)->getAttributeNs(Reader::XMLNS_XML, "id");

                /* FIXME: No ID? How can we create an anchor for it then? */
                if (!$id) {
                    $id = uniqid("phd");
                }

                $node->setAttribute("xml:id", $id);
                $xml .= $doc->saveXML($node);
            }
            $xml .= "</questions>";

            $r = new Reader();
            $r->XML($xml);

            $render = new Render;
            $render->attach($this);
            $render->render($r);
        }
    }

    public function format_qandaentry($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["qandaentry"][] = $attrs[Reader::XMLNS_XML]["id"];
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

    public function format_varlistentry($open, $name, $attrs) {
        return false;
    }

   /**
    * Functions from the old XHTMLPhDFormat
    */
    public function format_literal($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = false;
            }
            return '<i>';
        }
        $this->role = false;
        return '</i>';
    }
    public function format_literal_text($value, $tag) {
        switch ($this->role) {
            case 'infdec':
                $value = (float)$value;
                $p = strpos($value, '.');
                $str = substr($value, 0, $p + 1);
                $str .= '<span style="text-decoration: overline;">';
                $str .= substr($value, $p + 1);
                $str .= '</span>';
                return $str;
            default:
                return $this->TEXT($value);
        }
    }

    public function format_author($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="' .$name. ' vcard">';
        }
        return "</div>";
    }
    public function format_personname($open, $name, $attrs, $props) {
        if ($open) {
            return '<span class="' .$name. ' fn">';
        }
        return "</span>";
    }
    public function format_name($open, $name, $attrs) {
        if ($open) {
            $class = "";
            switch($name) {
            case "firstname":
                $class = " given-name";
                break;

            case "surname":
                $class = " family-name";
                break;

            case "othername":
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                    /* We maight want to add support for other roles */
                    switch($attrs[Reader::XMLNS_DOCBOOK]["role"]) {
                    case "nickname":
                        $class = " nickname";
                        break;
                    }
                }
                break;
            }

            return ' <span class="' . $name . $class . '">';
        }
        return '</span> ';
    }

    public function format_legalnotice_chunk($open, $name, $attrs) {
        if ($open) {
            return '<div id="legalnotice">';
        }
        return "</div>\n";
    }
    public function format_varentry_chunk($open, $name, $attrs, $props) {
        return $this->format_chunk($open, "refentry", $attrs, $props);
    }
    public function format_refsect1_para($open, $name, $attrs, $props) {
        if ($props['empty']) {
            return '';
        }
        if ($open) {
            switch ($props["sibling"]) {
            case "methodsynopsis":
            case "constructorsynopsis":
            case "destructorsynopsis":
                ++$this->openPara;
                return '<p class="'.$name.' rdfs-comment">';
                break;

            default:
                ++$this->openPara;
                return '<p class="'.$name.'">';
            }

        }
        --$this->openPara;
        return '</p>';
    }
/*
    public function format_refsect($open, $name, $attrs) {
        if ($open) {
            if(!isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $attrs[Reader::XMLNS_DOCBOOK]["role"] = "unknown";
            }
            $this->role = $role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            return '<a name="' .$this->cchunk["chunk_id"]. '.' .$role. '"></a><div class="' .$name.' ' .$role. '">';
        }
        $this->role = null;
        return "</div>\n";
    }
*/
    public function format_classsynopsisinfo_oointerface($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["implements"] === false) {
                $this->cchunk["classsynopsisinfo"]["implements"] = true;
                return '<span class="'.$name.'">implements ';
            }
            return '<span class="'.$name.'">, ';
        }

        return "</span>";
    }
    public function format_classsynopsisinfo_ooclass_classname($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["ooclass"] === false) {
                $this->cchunk["classsynopsisinfo"]["ooclass"] = true;
                return ' class <b class="'.$name.'">';
            }
            return '<b class="'.$name.'"> ';
        }
        return "</b>";
    }
    public function format_classsynopsisinfo($open, $name, $attrs) {
        $this->cchunk["classsynopsisinfo"] = $this->dchunk["classsynopsisinfo"];
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "comment") {
                return '<div class="'.$name.' classsynopsisinfo_comment">/* ';
            }
            return '<div class="'.$name.'">';
        }

        if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "comment") {
            return ' */</div>';
        }
        $this->cchunk["classsynopsis"]["close"] = true;
        return ' {</div>';
    }

    public function format_classsynopsis($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">';
        }

        if ($this->cchunk["classsynopsis"]["close"] === true) {
            $this->cchunk["classsynopsis"]["close"] = false;
            return "}</div>";
        }
        return "</div>";
    }

    public function format_classsynopsis_ooclass_classname_text($value, $tag) {
        $this->cchunk["classsynopsis"]["classname"] = $value;
        return $this->TEXT($value);
    }

    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        $value = $this->TEXT($value);
        if ($this->cchunk["classsynopsis"]["classname"] === false) {
            return $value;
        }
        if (strpos($value, '::')) {
            $explode = '::';
        } elseif (strpos($value, '->')) {
            $explode = '->';
        } else {
            return $value;
        }

        list($class, $method) = explode($explode, $value);
        if ($class !== $this->cchunk["classsynopsis"]["classname"]) {
            return $value;
        }
        return $method;
    }

    public function format_cmdsynopsis($open, $name, $attrs)
    {
        if ($open) {
            return '<span style="background-color:#eee">';
        }
        return '</span>';
    }

    public function format_fieldsynopsis($open, $name, $attrs) {
        $this->cchunk["fieldsynopsis"] = $this->dchunk["fieldsynopsis"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return ";</div>\n";
    }
    public function format_fieldsynopsis_modifier_text($value, $tag) {
        $this->cchunk["fieldsynopsis"]["modifier"] = trim($value);
        return $this->TEXT($value);
    }
    public function format_methodsynopsis($open, $name, $attrs) {
        if ($open) {
            $this->params = array("count" => 0, "opt" => 0, "content" => "");
            return '<div class="'.$name.' dc-description">';
        }
        $content = "";
        if ($this->params["opt"]) {
            $content = str_repeat("]", $this->params["opt"]);
        }
        $content .= " )";

        $content .= "</div>\n";

        return $content;
    }
    public function format_methodparam_parameter($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                return ' <tt class="parameter reference">&amp;$';
            }
            return ' <tt class="parameter">$';
        }
        return "</tt>";
    }
    public function format_initializer($open, $name, $attrs) {
        if ($open) {
            return '<span class="'.$name.'">= ';
        }
        return '</span>';
    }
    public function format_parameter($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                return '<i><tt class="parameter reference">&amp;';
            }
            return '<i><tt class="parameter">';
        }
        return "</tt></i>\n";
    }

    public function format_void($open, $name, $attrs) {
        return ' ( <span class="methodparam">void</span>';
    }
    public function format_methodparam($open, $name, $attrs) {
        if ($open) {
            $content = '';
                if ($this->params["count"] == 0) {
                    $content .= " (";
                }
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["choice"]) && $attrs[Reader::XMLNS_DOCBOOK]["choice"] == "opt") {
                    $this->params["opt"]++;
                    $content .= "[";
                } else if($this->params["opt"]) {
                    $content .= str_repeat("]", $this->params["opt"]);
                    $this->params["opt"] = 0;
                }
                if ($this->params["count"]) {
                    $content .= ",";
                }
                $content .= ' <span class="methodparam">';
                ++$this->params["count"];
                return $content;
        }
        return "</span>";
    }
/*
    public function format_methodname($open, $name, $attr) {
        if ($open) {
            return ' <span class="methodname"><b>';
        }
        return '</b></span>';
    }
*/
    public function format_varname($open, $name, $attrs) {
        if ($open) {
            return '<var class="'.$name.'">$';
        }
        return "</var>\n";
    }
/*
    public function format_fieldsynopsis_varname($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["fieldsynopsis"]["modifier"] === "const") {
                return '<var class="fieldsynopsis_varname">';
            }
            return '<var class="'.$name.'">$';
        }
        return '</var>';
    }
*/
    public function format_footnoteref($open, $name, $attrs, $props) {
        if ($open) {
            $linkend = $attrs[Reader::XMLNS_DOCBOOK]["linkend"];
            $found = false;
            foreach($this->cchunk["footnote"] as $k => $note) {
                if ($note["id"] === $linkend) {
                    return '<a href="#fnid' .$note["id"]. '"><sup>[' .($k + 1). ']</sup></a>';
                }
            }
            trigger_error("footnoteref ID '$linkend' not found", E_USER_WARNING);
            return "";
        }
    }
    public function format_footnote($open, $name, $attrs, $props) {
        if ($open) {
            $count = count($this->cchunk["footnote"]);
            $noteid = isset($attrs[Reader::XMLNS_XML]["id"]) ? $attrs[Reader::XMLNS_XML]["id"] : $count + 1;
            $note = array("id" => $noteid, "str" => "");
            $this->cchunk["footnote"][$count] = $note;
            if ($this->cchunk["table"]) {
                $this->cchunk["tablefootnotes"][$count] = $noteid;
            }
            return '<a href="#fnid' .$noteid. '" name="fn'.$noteid.'"><sup>[' .($count + 1). ']</sup></a>';
        }
        return "";
    }

    /* {{{ FIXME: These are crazy workarounds :( */
    public function format_footnote_constant($open, $name, $attrs, $props) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= self::format_constant($open, $name, $attrs, $props);
        return "";
    }
    public function format_footnote_constant_text($value, $tag) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= $value;
        return "";
    }
    public function format_footnote_para($open, $name, $attrs, $props) {
        $k = count($this->cchunk["footnote"]) - 1;
        if ($open) {
            $this->cchunk["footnote"][$k]["str"] .= '<span class="para footnote">';
            return "";
        }

        $this->cchunk["footnote"][$k]["str"] .= "</span>";
        return "";
    }
    public function format_footnote_para_text($value, $tag) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= $value;
        return "";
    }

    /* }}} */

    public function format_co($open, $name, $attrs, $props) {
        if (($open || $props["empty"]) && isset($attrs[Reader::XMLNS_XML]["id"])) {
            $co = ++$this->cchunk["co"];
            return '<a name="'.$attrs[Reader::XMLNS_XML]["id"].'" id="'.$attrs[Reader::XMLNS_XML]["id"].'">' .str_repeat("*", $co) .'</a>';
        }
        /* Suppress closing tag if any */
        return "";
    }

    public function format_quote($open, $name, $attrs) {
        if ($open) {
            return '"<span class="'.$name.'">';
        }
        return '</span>"';
    }
    public function format_manvolnum($open, $name, $attrs) {
        if ($open) {
            return '<span class="'.$name.'">(';
        }
        return ")</span>";
    }

    public function format_segmentedlist($open, $name, $attrs) {
        $this->cchunk["segmentedlist"] = $this->dchunk["segmentedlist"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_segtitle_text($value, $tag) {
        $this->cchunk["segmentedlist"]["segtitle"][count($this->cchunk["segmentedlist"]["segtitle"])] = $value;
        /* Suppress the text */
        return "";
    }
    public function format_seglistitem($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["segmentedlist"]["seglistitem"] = 0;
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_seg($open, $name, $attrs) {
        if ($open) {
            return '<div class="seg"><strong><span class="segtitle">' .$this->cchunk["segmentedlist"]["segtitle"][$this->cchunk["segmentedlist"]["seglistitem"]++]. ':</span></strong>';
        }
        return '</div>';
    }
    public function format_procedure($open, $name, $attrs) {
        $this->cchunk["procedure"] = false;
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</ol></div>';
    }
    public function format_step($open, $name, $attrs) {
        if ($open) {
            $ret = "";
            if ($this->cchunk["procedure"] === false) {
                $this->cchunk["procedure"] = true;
                $ret = '<ol type="1">';
            }
            return $ret . "<li>";
        }
        return '</li>';
    }
    public function format_variablelist($open, $name, $attrs) {
        if ($open) {
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $idstr = ' id="'. $attrs[Reader::XMLNS_XML]['id']. '"';
            }
            return $this->escapePara() . "<dl" . $idstr . ">\n";
        }
        return "</dl>\n" . $this->restorePara();
    }
/*
    public function format_varlistentry($open, $name, $attrs) {
        if ($open) {
            return isset($attrs[Reader::XMLNS_XML]["id"]) ? '<dt id="'.$attrs[Reader::XMLNS_XML]["id"]. '" class="varlistentry">' : "<dt class=\"varlistentry\">\n";
        }

        // Listitems close the the dt themselfs
        if ($this->cchunk["varlistentry"]["listitems"] && array_pop($this->cchunk["varlistentry"]["listitems"])) {
            return "";
        }
        return "</dt>\n";
    }
*/
    public function format_varlistentry_listitem($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["varlistentry"]["listitems"][] = 1;
            return "</dt><dd class=\"listitem\">\n";
        }
        return "</dd>\n";
    }
    public function format_term($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] == $name) {
                return '<br /><span class="' .$name. '">';
            }
            return '<span class="' .$name. '">';
        }
        return "</span>\n";
    }
    public function format_systemitem($open, $name, $attrs) {
        if ($open) {
            $val = isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) ? $attrs[Reader::XMLNS_DOCBOOK]["role"] : null;
            switch($val) {
            case "directive":
            /* FIXME: Different roles should probably be handled differently */
            default:
                return '<code class="systemitem ' .$name. '">';
            }
        }
        return "</code>\n";
    }
    public function format_example_content($open, $name, $attrs, $props) {
        if ($props['empty']) {
            return '';
        }
        if ($open) {
            $retval = $this->escapePara() . '<div class="example-contents ' .$name. '"><p>';
            ++$this->openPara;
            return $retval;
        }
        --$this->openPara;
        return "</p></div>" . $this->restorePara();
    }
       
    public function format_screen_text($value, $tag) {
        return nl2br($this->TEXT($value));
    }
    public function format_constant($open, $name, $attrs) {
        if ($open) {
            return "<b><tt class=\"constant\">";
        }
        return "</tt></b>";
    }
    
    public function format_authorgroup_author($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] !== $name) {
                return '<div class="'.$name.' vcard">' .$this->admonition_title("by", $props["lang"]). ':<br />';
            }
            return '<div class="'.$name.' vcard">';
        }
        return "</div>\n";
    }
    public function format_editor($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="editor vcard">' .$this->admonition_title("editedby", $props["lang"]). ': ';
        }
        return "</div>\n";
    }
    public function format_note($open, $name, $attrs, $props) {
        if ($open) {
            $retval = $this->escapePara() . '<blockquote><p>'.$this->admonition_title("note", $props["lang"]). ': ';
            ++$this->openPara;
            return $retval;
        }

        --$this->openPara;
        return "</p></blockquote>" . $this->restorePara();
    }
    public function format_note_title($open, $name, $attrs) {
        if ($open) {
            return '<b>';
        }
        return '</b><br />';
    }
    public function format_note_content($open, $name, $attrs) {
        if ($open) {
            /* Ignore the open tag */
            return "";
        }
        return "<br />";
    }
    public function format_bold_paragraph($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            ++$this->openPara;
            return "<p><b>";
        }
        --$this->openPara;
        return "</b></p>";
    }
    
    /**
    * Renders  a <tag class=""> tag.
    *
    * @return string HTML code
    */
    public function format_tag($open, $name, $attrs, $props)
    {
        static $arFixes = array(
            'attribute'     => array('', ''),
            'attvalue'      => array('"', '"'),
            'comment'       => array('&lt;!--', '--&gt;'),
            'element'       => array('', ''),
            'emptytag'      => array('&lt;', '/&gt;'),
            'endtag'        => array('&lt;/', '&gt;'),
            'genentity'     => array('&amp;', ';'),
            'localname'     => array('', ''),
            'namespace'     => array('', ''),
            'numcharref'    => array('&amp;#', ';'),
            'paramentity'   => array('%', ';'),
            'pi'            => array('&lt;?', '?&gt;'),
            'prefix'        => array('', ''),
            'starttag'      => array('&lt;', '&gt;'),
            'xmlpi'         => array('&lt;?', '?&gt;'),
        );
        if ($props['empty']) {
            return '';
        }
        $class = $attrs['class'];
        if (!isset($arFixes[$class])) {
            trigger_error('Unknown tag class "' . $class . '"', E_USER_WARNING);
            $class = 'starttag';
        }
        if (!$open) {
            return $arFixes[$class][1] . '</code>';
        }

        return '<code>' . $arFixes[$class][0];
    }

    public function format_mediaobject($open, $name, $attrs) {
        $this->cchunk["mediaobject"] = $this->dchunk["mediaobject"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_alt_text($value, $tag) {
        $this->cchunk["mediaobject"]["alt"] = $value;
    }

    public function format_itemizedlist($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara() . '<ul class="' . $name . '">';
        }
        return '</ul>' . $this->restorePara();
    }

    public function format_orderedlist($open, $name, $attrs, $props)
    {
        if ($open) {
            $numeration = "1";
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["numeration"])) {
                switch($attrs[Reader::XMLNS_DOCBOOK]["numeration"]) {
                case "upperalpha":
                    $numeration = "A";
                    break;
                case "loweralpha":
                    $numeration = "a";
                    break;
                case "upperroman":
                    $numeration = "I";
                    break;
                case "lowerroman":
                    $numeration = "i";
                    break;
                }
            }
            return $this->escapePara(). '<ol type="' .$numeration. '">';
        }
        return '</ol>' . $this->restorePara();
    }

    public function format_tgroup($open, $name, $attrs) {
        if ($open) {
            Format::tgroup($attrs[Reader::XMLNS_DOCBOOK]);
            return '';
        }
        return '';
    }
    private function parse_table_entry_attributes($attrs) {
        $retval = 'align="' .$attrs["align"]. '"';
        if ($attrs["align"] == "char" && isset($attrs["char"])) {
            $retval .= ' char="' .(htmlspecialchars($attrs["char"], ENT_QUOTES)). '"';
            if (isset($attrs["charoff"])) {
                $retval .= ' charoff="' .(htmlspecialchars($attrs["charoff"], ENT_QUOTES)). '"';
            }
        }
        if (isset($attrs["valign"])) {
            $retval .= ' valign="' .$attrs["valign"]. '"';
        }
        if (isset($attrs["colwidth"])) {
            $width = $attrs["colwidth"];
            if (is_numeric($width)) {
                $retval .= ' width="' .((int)$width). '"';
            }
            // N*
            elseif(($pos = strpos($width, "*")) !== false) {
                $cols = $this->getColCount();
                $length = 100/$cols;
                if (substr($width, -1) !== "*") {
                    trigger_error("Mixing proportion and fixed measure not implemented", E_USER_WARNING);
                }
                // Standard length
                elseif($width == "*" || $width == "1*") {
                }
                else {
                    $width = (int)substr($width, 0, $pos);
                    $length *= $width;
                }
                $retval = ' width="' .$length. '%"';

            }
            // Npt or Npx or other weird format
            else {
                $retval .= ' width="' .htmlentities($attrs["colwidth"], ENT_QUOTES, "UTF-8"). '"';
            }
        }
        return $retval;
    }
    public function format_colspec($open, $name, $attrs) {
        if ($open) {
            $str = self::parse_table_entry_attributes(Format::colspec($attrs[Reader::XMLNS_DOCBOOK]));

            return '<col '.$str. ' />';
        }
        /* noop */
    }
    public function format_th($open, $name, $attrs) {
        if ($open) {
            $valign = Format::valign($attrs[Reader::XMLNS_DOCBOOK]);
            return '<' .$name. ' valign="' .$valign. '">';
        }
        return "</$name>\n";
    }
    public function format_tbody($open, $name, $attrs) {
        if ($open) {
            $valign = Format::valign($attrs[Reader::XMLNS_DOCBOOK]);
            return '<tbody valign="' .$valign. '" class="' .$name. '">';
        }
        return "</tbody>";
    }
    public function format_row($open, $name, $attrs) {
        if ($open) {
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]['id'])) {
                $idstr = ' id="'. $attrs[Reader::XMLNS_XML]['id']. '"';
            }
            Format::initRow();
            $valign = Format::valign($attrs[Reader::XMLNS_DOCBOOK]);
            return '<tr'.$idstr.' valign="' .$valign. '">';
        }
        return "</tr>\n";
    }
    
    public function admonition_title($title, $lang) {
        return '<b class="' .(strtolower($title)). '">' .($this->autogen($title, $lang)). '</b>';
    }

    public function format_citation($open, $name, $attrs, $props) {
        if ($open) {
            return '[<span class="citation">';
        }
        return '</span>]';
    }

    public function format_email_text($value) {
        return '&lt;<a href="mailto:' . $value . '">' . $value . '</a>&gt;';
    }

    public function format_imagedata($open, $name, $attrs) {
        if ($this->cchunk["mediaobject"]["alt"] !== false) {
            return '<img src="' .$attrs[Reader::XMLNS_DOCBOOK]["fileref"]. '" alt="' .$this->cchunk["mediaobject"]["alt"]. '" />';
        }
        return '<img src="' .$attrs[Reader::XMLNS_DOCBOOK]["fileref"]. '" />';
    }

    /* Chunk Functions */    

    public function format_chunk($open, $name, $attrs, $props) {
        if (isset($attrs[Reader::XMLNS_XML]["id"])) {
            $this->CURRENT_CHUNK = $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]["id"];
            $this->notify(Render::CHUNK, $open ? Render::OPEN : Render::CLOSE);
        }
        if ($props["isChunk"]) {
            $this->cchunk = $this->dchunk;
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
        return false;
    }
/*
    public function format_varentry_chunk($open, $name, $attrs, $props) {
        return $this->format_chunk($open, "refentry", $attrs, $props);
    }
*/
    public function format_container_chunk($open, $name, $attrs, $props) {
        $this->CURRENT_CHUNK = $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]["id"];
        $this->notify(Render::CHUNK, $open ? Render::OPEN : Render::CLOSE);
        if ($open) {
            if ($props["isChunk"]) {
                $this->cchunk = $this->dchunk;
            }
            if ($name != "reference") {
                $chunks = Format::getChildrens($id);
                if (!count($chunks)) {
                    return "<div>";
                }
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_'.$name.'">';
                foreach($chunks as $chunkid => $junk) {
                    if ($this->chunked) {
                        $content .= '<li><a href="'.$chunkid. '.' .$this->ext. '">' .(Format::getLongDescription($chunkid)). '</a></li>';
                    } else {
                        $content .= '<li><a href="#'.$chunkid. '">' .(Format::getLongDescription($chunkid)). '</a></li>';
                    }
                }
                $content .= "</ul>\n";
                $this->cchunk["container_chunk"] = $content;
            }
            return "<div>";
        }

        $content = "";
        if ($name == "reference") {
            $chunks = Format::getChildrens($id);
            if (count($chunks)) {
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_reference">';
                foreach($chunks as $chunkid => $junk) {
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

    public function format_exception_chunk($open, $name, $attrs, $props) {
        return $this->format_container_chunk($open, "reference", $attrs, $props);
    }

    public function format_class_chunk($open, $name, $attrs, $props) {
        return $this->format_container_chunk($open, "reference", $attrs, $props);
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

    public function format_root_chunk($open, $name, $attrs) {
        $this->CURRENT_CHUNK = $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]["id"];
        $this->notify(Render::CHUNK, $open ? Render::OPEN : Render::CLOSE);
        if ($open) {
            return "<div>";
        }

        $chunks = Format::getChildrens($id);
        $content = '<ul class="chunklist chunklist_'.$name.'">';
        foreach($chunks as $chunkid => $junk) {
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
                foreach(Format::getChildrens($chunkid) as $childid => $junk) {
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

}

?>
