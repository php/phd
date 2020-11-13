<?php
namespace phpdotnet\phd;

abstract class Package_PHP_XHTML extends Package_Generic_XHTML {
    private $myelementmap = array(
        'acronym'               => 'format_suppressed_tags',
        'appendix'              => 'format_container_chunk',
        'article'               => 'format_container_chunk',
        'book'                  => 'format_root_chunk',
        'classname'             => 'format_suppressed_tags',
        'chapter'               => 'format_container_chunk',
        'colophon'              => 'format_chunk',
        'function'              => 'format_function',
        'methodname'            => 'format_function',
        'methodsynopsis'        => 'format_methodsynopsis',
        'legalnotice'           => 'format_chunk',
        'part'                  => 'format_container_chunk',
        'partintro'             => 'format_partintro',
        'preface'               => 'format_chunk',
        'phpdoc:classref'       => 'format_class_chunk',
        'phpdoc:exceptionref'   => 'format_class_chunk',
        'phpdoc:varentry'       => 'format_varentry_chunk',
        'refentry'              => 'format_chunk',
        'reference'             => 'format_container_chunk',
        'refpurpose'            => 'format_refpurpose',
        'refsynopsisdiv'        => 'format_refsynopsisdiv',
        'set'                   => 'format_root_chunk',
        'setindex'              => 'format_chunk',
        'sidebar'               => 'blockquote',
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
                'note'          => 'format_note_title',
                'informaltable' => 'format_table_title',
                'table'         => 'format_table_title',
            ),
            'formalpara'        => 'h5',
            'indexdiv'          => 'dt',
            'legalnotice'       => 'h4',
            'note'              => 'format_note_title',
            'phd:toc'           => 'strong',
            'procedure'         => 'strong',
            'refsect1'          => 'h3',
            'refsect2'          => 'h4',
            'refsect3'          => 'h5',
            'section'           => 'h2',
            'sect1'             => 'h2',
            'sect2'             => 'h3',
            'sect3'             => 'h4',
            'sect4'             => 'h5',
            'segmentedlist'     => 'format_table_title',
            'simplesect'        => 'h3',
            'table'             => 'format_table_title',
            'variablelist'      => 'strong',
            'varname'               => array(
                /* DEFAULT */          'format_suppressed_tags',
                'fieldsynopsis'     => 'format_fieldsynopsis_varname',
            ),
        ),
        'type'                  => array(
            /* DEFAULT */          'format_type',
            'methodsynopsis'    => 'format_suppressed_tags',
            'type'              => array(
                /* DEFAULT */       'format_type',
                'methodsynopsis' => 'format_suppressed_tags',
            ),
        ),
        'varname'               => array(
            /* DEFAULT */          'format_suppressed_tags',
            'fieldsynopsis'     => 'format_fieldsynopsis_varname',
        ),
    );
    private $mytextmap = array(
        'acronym'               => 'format_acronym_text',
        'function'              => 'format_function_text',
        'interfacename'         => 'format_classname_text',
        'exceptionname'         => 'format_classname_text',
        'classname'            => array(
            /* DEFAULT */         'format_classname_text',
            'ooclass'          => array(
                /* DEFAULT */     'format_classname_text',
                'classsynopsis' => 'format_classsynopsis_ooclass_classname_text',
            ),
        ),
        'methodname'            => array(
            /* DEFAULT */          'format_function_text',
            'constructorsynopsis' => array(
                /* DEFAULT */      'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'methodsynopsis'    => array(
                /* DEFAULT */      'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'destructorsynopsis' => array(
                /* DEFAULT */      'format_classsynopsis_methodsynopsis_methodname_text',
            ),
        ),
        'refname'               => 'format_refname_text',
        'type'                  => array(
            /* DEFAULT */          'format_type_text',
            'classsynopsisinfo' => false,
            'fieldsynopsis'     => 'format_type_text',
            'methodparam'       => 'format_type_text',
            'methodsynopsis'    => 'format_type_methodsynopsis_text',
            'type'              => array(
                /* DEFAULT */       'format_type_text',
                'methodsynopsis' => 'format_type_methodsynopsis_text',
            ),
        ),
        'titleabbrev'           => array(
            /* DEFAULT */          'format_suppressed_text',
            'phpdoc:classref'   => 'format_grep_classname_text',
            'phpdoc:exceptionref'  => 'format_grep_classname_text',
        ),
         'varname'               => array(
            /* DEFAULT */          'format_varname_text',
            'fieldsynopsis'     => array(
                /* DEFAULT */      false,
                'classsynopsis' => 'format_classsynopsis_fieldsynopsis_varname_text',
            ),
        ),
    );

    private $versions = array();
    private $acronyms = array();
    protected $deprecated = array();

    /* Current Chunk settings */
    protected $cchunk          = array();

    /* Default Chunk settings */
    protected $dchunk          = array(
        "phpdoc:classref"              => null,
        "args"                         => null,
        "fieldsynopsis"                => array(
            "modifier"                 => "public",
        ),
        "container_chunk"              => null,
        "qandaentry"                   => array(
        ),
        "examples"                     => 0,
        "verinfo"                      => false,
        "refname"                      => array(),
        "alternatives"                 => array(),
        "refsynopsisdiv"               => null,
    );

    /** @var int|null Number of already formatted types in the current union type */
    private $num_types = null;

    protected $pihandlers = array(
        'dbhtml'        => 'PI_DBHTMLHandler',
        'dbtimestamp'   => 'PI_DBHTMLHandler',
        'phpdoc'        => 'PI_PHPDOCHandler',
    );

    public function __construct() {
        parent::__construct();
        $this->myelementmap = array_merge(parent::getDefaultElementMap(), static::getDefaultElementMap());
        $this->mytextmap = array_merge(parent::getDefaultTextMap(), static::getDefaultTextMap());
        $this->dchunk = array_merge(parent::getDefaultChunkInfo(), static::getDefaultChunkInfo());
        $this->registerPIHandlers($this->pihandlers);
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

    public function loadVersionAcronymInfo() {
        $this->versions = self::generateVersionInfo(Config::phpweb_version_filename());
        $this->deprecated = self::generateDeprecatedInfo(Config::phpweb_version_filename());
        $this->acronyms = self::generateAcronymInfo(Config::phpweb_acronym_filename());
    }

    public static function generateVersionInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        if (!is_file($filename)) {
            v("Can't find Version information file (%s), skipping!", $filename, E_USER_WARNING);
            return array();
        }

        $r = new \XMLReader;
        if (!$r->open($filename)) {
            v("Can't open the version info file (%s)", $filename, E_USER_ERROR);
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

    // Note that this function differs from generateVersionInfo()!
    // Special characters in functions names are replaced to underscores (_),
    // not hyphens (-).
    protected static function generateDeprecatedInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        if (!is_file($filename)) {
            v("Can't find Version information file (%s), skipping!", $filename, E_USER_WARNING);
            return array();
        }

        $r = new \XMLReader;
        if (!$r->open($filename)) {
            v("Can't open the version info file (%s)", $filename, E_USER_ERROR);
        }
        $deprecated = array();
        while($r->read()) {
            if (
                $r->moveToAttribute("name")
                && ($funcname = str_replace(
                    array("::", "->", "__", "_", '$'),
                    array("_",  "_",  "_",  "_", ""),
                    $r->value))
                && $r->moveToAttribute("deprecated")
                && ($value = $r->value)
            ) {
                $deprecated[strtolower($funcname)] = $value;
                $r->moveToElement();
            }
        }
        $r->close();
        $info = $deprecated;
        return $deprecated;
    }

    public static function generateAcronymInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        if (!is_file($filename)) {
            v("Can't find acronym file (%s), skipping", $filename, E_USER_WARNING);
            return array();
        }

        $r = new \XMLReader;
        if (!$r->open($filename)) {
            v("Could not open file for accessing acronym information (%s)", $filename, E_USER_ERROR);
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

    public function autogenVersionInfo($refnames) {
        $verinfo = null;
        foreach((array)$refnames as $refname) {
            $verinfo = $this->versionInfo($refname);

            if ($verinfo) {
                break;
            }
        }
        if (!$verinfo) {
            $verinfo = $this->autogen("unknownversion");
        }

        $retval = '<p class="verinfo">(' .(htmlspecialchars($verinfo, ENT_QUOTES, "UTF-8")). ')</p>';
        return $retval;
    }
    public function format_type($open, $tag, $attrs, $props) {
        $retval = '';
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["class"])) {
                $this->num_types = 0;
            } elseif (isset($this->num_types)) {
                if ($this->num_types > 0) $retval .= '|';
                $this->num_types++;
            }
            $retval .= '<span class="type">';
        } else {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["class"])) {
                $this->num_types = null;
            }
            $retval .= '</span>';
        }
        return $retval;
    }
    public function format_refpurpose($open, $tag, $attrs, $props) {
        if ($open) {
            $retval = "";
            if ($this->cchunk["verinfo"]) {
                $retval = $this->autogenVersionInfo($this->cchunk["refname"]);
            }
            $refnames = implode('</span> -- <span class="refname">', $this->cchunk["refname"]);

            $retval .= '<p class="refpurpose"><span class="refname">'. $refnames. '</span> &mdash; <span class="dc-title">';
            return $retval;
        }
        return "</span></p>\n";
    }
    public function format_refsynopsisdiv($open, $tag, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->cchunk["refsynopsisdiv"] = $attrs[Reader::XMLNS_DOCBOOK]["role"];
                $id = $this->CURRENT_ID . "-" . $attrs[Reader::XMLNS_DOCBOOK]["role"];
                return '<div id="' . $id . '" class="' . $attrs[Reader::XMLNS_DOCBOOK]["role"] . '">';
            }

            $id = $this->CURRENT_ID . "-" . $tag;
            return '<div id="' . $id . '">';
        }
        $this->cchunk["refsynopsisdiv"] = $this->dchunk["refsynopsisdiv"];

        return "</div>";
    }

    public function format_partintro($open, $tag, $attrs, $props) {
        if ($open) {
            $retval = "";
            if ($this->cchunk["verinfo"]) {
                $retval = $this->autogenVersionInfo($this->cchunk["phpdoc:classref"]);
            }
            return '<div class="' . $tag . '">' . $retval;
        }

        return '</div>';
    }

    public function format_refname_text($value, $tag) {
        $this->cchunk["refname"][] = $this->TEXT($value);
        return false;
    }

    public function format_fieldsynopsis_varname($open, $name, $attrs) {
        if ($open) {
            $href = "";
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["linkend"])) {
                $linkto = $attrs[Reader::XMLNS_DOCBOOK]["linkend"];
                $href = Format::getFilename($linkto);

                if ($this->chunked) {
                    if ($href != $linkto) {
                        $href .= "{$this->ext}#{$linkto}";
                    } else {
                        $href .= $this->ext;
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

    public function format_varname_text($value, $tag) {
        $var = $value;
        if (($pos = strpos($value, "[")) !== false) {
            $var = substr($value, 0, $pos);
        }
        if (($filename = $this->getVarnameLink($var)) !== null && !in_array($var, $this->cchunk["refname"])) {
            $href = $this->chunked ? $filename.$this->ext : "#$filename";
            return '<var class="varname"><a href="'.$href.'" class="classname">' .$value. '</a></var>';
        }
        return '<var class="varname">' .$value. '</var>';

    }

    public function format_methodsynopsis($open, $name, $attrs) {
        if ($open) {
            return parent::format_methodsynopsis($open, $name, $attrs);
        }

        $content = "";
        if ($this->params["opt"]) {
            $content = str_repeat("]", $this->params["opt"]);
        }
        $content .= " )";

        if ($this->cchunk["methodsynopsis"]["returntypes"]) {
            $types = [];
            foreach ($this->cchunk["methodsynopsis"]["returntypes"] as $return_type) {
                $formatted_type = self::format_type_text($return_type, "type");
                if ($formatted_type === false) {
                    $formatted_type = $return_type;
                }
                $types[] = '<span class="type">' . $formatted_type . '</span>';
            }
            $type = implode('|', $types);
            if (count($types) > 1) {
                $type = '<span class="type">' . $type . '</span>';
            }
            $content .= ' : ' . $type;
        }

        $content .= "</div>\n";
        $this->cchunk["methodsynopsis"] = $this->dchunk["methodsynopsis"];

        return $content;
    }

    public function format_type_methodsynopsis_text($type, $tagname) {
        $this->cchunk["methodsynopsis"]["returntypes"][] = $type;
        return "";
    }

    public function format_type_if_object_or_pseudo_text($type, $tagname) {
        if (in_array(strtolower($type), array("bool", "int", "double", "boolean", "integer", "float", "string", "array", "object", "resource", "null"))) {
            return false;
        }
        return self::format_type_text($type, $tagname);
    }

    public function format_type_text($type, $tagname) {
        $t = strtr(strtolower($type), ["_" => "-", "\\" => "-"]);
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
        // old name for callable
        case "callback":
            $href = "language.types.callable";
            break;
        case "boolean":
        case "integer":
        case "float":
        case "string":
        case "array":
        case "object":
        case "resource":
        case "null":
        case "callable":
        case "iterable":
            $href = "language.types.$t";
            break;
        case "mixed":
            $href = "language.types.declarations";
            $fragment = "language.types.declarations.base";
            break;
        case "void":
            $href = "language.types.declarations";
            $fragment = "language.types.declarations.void";
            break;
        default:
            /* Check if its a classname. */
            $href = Format::getFilename("class.$t");
        }

        if ($href && $this->chunked) {
            return '<a href="' .$href. $this->getExt().($fragment ? "#$fragment" : ""). '" class="' .$tagname. ' ' .$type. '">' .$type. '</a>';
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
            return "<p><strong>" . ($this->autogen('example', $props['lang']) . ++$this->cchunk["examples"]) . " ";
        }
        return "</strong></p>";
    }

    public function versionInfo($funcname) {
        $funcname = str_replace(
                array("::", "-&gt;", "->", "__", "_", '$', '()'),
                array("-",  "-",     "-",  "-",  "-", "",  ''),
                strtolower($funcname));
        if(isset($this->versions[$funcname])) {
           return $this->versions[$funcname];
        }
        v("No version info for %s", $funcname, VERBOSE_NOVERSION);
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

    public function format_classsynopsis_fieldsynopsis_varname_text($value, $tag) {
        if ($this->cchunk["classsynopsis"]["classname"]) {
          if (strpos($value, "::") === false && strpos($value, "->") === false) {
                $value = $this->cchunk["classsynopsis"]["classname"] . "->" . $value;
            }
        }

        $display_value = parent::format_classsynopsis_methodsynopsis_methodname_text($value, $tag);
        return $this->format_varname_text($display_value, $tag);
    }
    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        if ($this->cchunk["classsynopsis"]["classname"]) {
          if (strpos($value, "::") === false && strpos($value, "->") === false) {
                $value = $this->cchunk["classsynopsis"]["classname"] . "::" . $value;
            }
        }

        $display_value = parent::format_classsynopsis_methodsynopsis_methodname_text($value, $tag);
        return $this->format_function_text($value, $tag, $display_value);
    }

    public function format_function($open, $tag, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_PHD]["args"])) {
                $this->cchunk["args"] = $attrs[Reader::XMLNS_PHD]["args"];
            }
            return '<span class="' . $tag . '">';
        }
        return "</span>";
    }

    public function format_function_text($value, $tag, $display_value = null) {
        static $non_functions = array(
            "echo" => true, "print" => true,
            "include" => true, "include_once" => true,
            "require" => true, "require_once" => true,
            "return" => true,
        );

        if ($display_value === null) {
            $display_value = $value;
            if (!isset($non_functions[$value])) {
                $args = $this->cchunk["args"];
                $this->cchunk["args"] = $this->dchunk["args"];
                $display_value .= "($args)";
            }
        }

        if (isset($non_functions[$value])) {
            $filename = "function." . str_replace("_", "-", $value);
        } else {
            $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $value));
            $filename = $this->getRefnameLink($ref);
        }
        if ($filename !== null) {
            if ($this->CURRENT_ID !== $filename) {
                $rel = $desc = "";
                if ($this->role == "seealso") {
                    $rel  = ' rel="rdfs-seeAlso"';
                    $desc = " - " . Format::getLongDescription($filename);
                }
                if ($this->cchunk["refsynopsisdiv"] === "soft-deprecation-notice") {
                    $this->cchunk["alternatives"][] = $value;
                }

                $href = $this->chunked ? $filename.$this->ext : "#$filename";
                return '<a href="'.$href. '" class="' . $tag . '"'.$rel.'>' .$display_value. '</a>'.$desc;
            }
        } elseif ($this->CURRENT_ID !== $filename) {
            v("No link found for %s", $value, VERBOSE_BROKEN_LINKS);
        }

        return '<strong>' .$display_value. '</strong>';
    }

    public function format_grep_classname_text($value, $tag) {
        $this->cchunk["phpdoc:classref"] = strtolower($value);
    }

    public function format_classsynopsis_ooclass_classname_text($value, $tag) {
        /* intentionally not return the value, it will be printed out by <methodname> "soon" */
        parent::format_classsynopsis_ooclass_classname_text($value, $tag);
    }

    public function format_classname_text($value, $tag) {
        if (($filename = $this->getClassnameLink(strtolower($value))) !== null && $this->cchunk["phpdoc:classref"] !== strtolower($value)) {
            $href = $this->chunked ? $filename.$this->ext : "#$filename";
            return '<a href="'.$href. '" class="' .$tag. '">' .$value. '</a>';
        }
        return '<strong class="' .$tag. '">' .$value. '</strong>';
    }


    /*Chunk Functions*/

    public function format_container_chunk($open, $name, $attrs, $props) {
        $this->CURRENT_CHUNK = $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]["id"];
        if (!isset($attrs[Reader::XMLNS_PHD]["chunk"]) || $attrs[Reader::XMLNS_PHD]["chunk"] == "true") {
            $this->cchunk = $this->dchunk;
        }

        if ($open) {
            $this->notify(Render::CHUNK, Render::OPEN);
            if ($name != "reference") {
                $chunks = Format::getChildren($id);
                if (!count($chunks)) {
                    return '<div id="'.$id.'" class="'.$name.'">';
                }
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_'.$name.'">';
                foreach($chunks as $chunkid) {
                    $href = $this->chunked ? $chunkid . $this->ext : "#$chunkid";
                    $content .= '<li><a href="'.$href. '">' .(Format::getShortDescription($chunkid)). '</a></li>';
                }
                $content .= "</ul>\n";
                $this->cchunk["container_chunk"] = $content;
            }
            return '<div id="'.$id.'" class="'.$name.'">';
        }
        $this->notify(Render::CHUNK, Render::CLOSE);

        $content = "";
        if ($name == "reference") {
            $chunks = Format::getChildren($id);
            if (count($chunks)) {
                $content = '<h2>'.$this->autogen("toc", $props["lang"]). '</h2><ul class="chunklist chunklist_reference">';
                foreach($chunks as $chunkid) {
                    $href = $this->chunked ? $chunkid . $this->ext : "#$chunkid";
                    $content .= '<li><a href="'.$href. '">' .(Format::getShortDescription($chunkid)). '</a> — ' .(Format::getLongDescription($chunkid)). '</li>';
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
        $chunks = Format::getChildren($id);
        $content = '<ul class="chunklist chunklist_'.$name.'">';
        foreach($chunks as $chunkid) {
            $href = $this->chunked ? $chunkid . $this->ext : "#$chunkid";
            $long = Format::getLongDescription($chunkid);
            $short = Format::getShortDescription($chunkid);
            if ($long && $short && $long != $short) {
                $content .= '<li><a href="' .$href. '">' .$short. '</a> — ' .$long;
            } else {
                $content .= '<li><a href="' .$href. '">' .($long ? $long : $short). '</a>';
            }
            $children = Format::getChildren($chunkid);
            if (count($children)) {
                $content .= '<ul class="chunklist chunklist_'.$name.' chunklist_children">';
                foreach(Format::getChildren($chunkid) as $childid) {
                    $href = $this->chunked ? $childid . $this->ext : "#$childid";
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
            else {
                $id = uniqid();
                v("Uhm. Can't find an ID for a chunk? - Generating a random one (%s)\n%s", $id, $this->getDebugTree($name, $props), E_USER_WARNING);
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
            if ($name == "legalnotice") {
                return '<div id="legalnotice">';
            }
            return '<div id="'.$id.'" class="'.$name.'">';
        }
        if (!isset($attrs[Reader::XMLNS_PHD]["chunk"]) || $attrs[Reader::XMLNS_PHD]["chunk"] == "true") {
            $this->notify(Render::CHUNK, Render::CLOSE);
        }
        return '</div>';
    }

    public function format_container_chunk_title($open, $name, $attrs, $props) {
        if ($open) {
            return $props["empty"] ? '' : '<h1>';
        }
        $ret = "";
        if (isset($this->cchunk["container_chunk"]) && $this->cchunk["container_chunk"]) {
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
        if ($open) {
            $retval = $this->format_container_chunk($open, "reference", $attrs, $props);
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->cchunk["verinfo"] = !($attrs[Reader::XMLNS_DOCBOOK]["role"] == "noversion");
            } else {
                $this->cchunk["verinfo"] = true;
            }
            return $retval;
        }
        return $this->format_container_chunk($open, "reference", $attrs, $props);
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
