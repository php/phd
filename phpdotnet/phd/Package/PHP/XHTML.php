<?php
namespace phpdotnet\phd;

abstract class Package_PHP_XHTML extends Package_Generic_XHTML {
    private $myelementmap = array(
        'acronym'               => 'format_suppressed_tags',
        'appendix'              => 'format_container_chunk',
        'article'               => 'format_container_chunk',
        'book'                  => 'format_root_chunk',
        'chapter'               => 'format_container_chunk',
        'colophon'              => 'format_chunk',
        'function'              => 'format_function',
        'methodname'            => 'format_function',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'legalnotice'           => 'format_chunk',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
        ),
        'part'                  => 'format_container_chunk',
        'partintro'             => 'format_partintro',
        'preface'               => 'format_chunk',
        'phpdoc:classref'       => 'format_class_chunk',
        'phpdoc:exceptionref'   => 'format_class_chunk',
        'phpdoc:varentry'       => 'format_varentry_chunk',
        'refentry'              => 'format_refentry',
        'reference'             => 'format_reference',
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
            'methodsynopsis'    => 'format_methodsynopsis_type',
            'methodparam'       => 'format_type_methodparam',
            'type'              => array(
                /* DEFAULT */       'format_type',
                'methodsynopsis' => 'format_methodsynopsis_type',
                'methodparam'   => 'format_type_methodparam',
                'type'              => array(
                    /* DEFAULT */       'format_type',
                    'methodsynopsis' => 'format_methodsynopsis_type',
                    'methodparam'   => 'format_type_methodparam',
                ),
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
        /** Those are used to retrieve the class/interface name to be able to remove it from method names */
        'classname' => [
            /* DEFAULT */ 'format_classname_text',
            'ooclass' => [
                /* DEFAULT */ 'format_classname_text',
                /** This is also used by the legacy display to not display the class name at all */
                'classsynopsis' => 'format_classsynopsis_ooclass_classname_text',
            ]
        ],
        'exceptionname' => [
            /* DEFAULT */ 'format_classname_text',
            'ooexception' => [
                /* DEFAULT */     'format_classname_text',
                'classsynopsis' => 'format_classsynopsis_oo_name_text',
            ]
        ],
        'interfacename' => [
            /* DEFAULT */ 'format_classname_text',
            'oointerface' => [
                /* DEFAULT */     'format_classname_text',
                'classsynopsis' => 'format_classsynopsis_oo_name_text',
            ]
        ],
        'enumname' => [
            /* DEFAULT */ 'format_classname_text',
            'ooenum' => [
                /* DEFAULT */     'format_classname_text',
                'classsynopsis' => 'format_classsynopsis_oo_name_text',
            ]
        ],
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
            'methodparam'       => 'format_type_methodparam_text',
            'methodsynopsis'    => 'format_type_methodsynopsis_text',
            'type'              => array(
                /* DEFAULT */       'format_type_text',
                'methodsynopsis' => 'format_type_methodsynopsis_text',
                'methodparam'   => 'format_type_methodparam_text',
                'type'              => array(
                    /* DEFAULT */       'format_type_text',
                    'methodsynopsis' => 'format_type_methodsynopsis_text',
                    'methodparam'   => 'format_type_methodparam_text',
                ),
            ),
        ),
        'titleabbrev'           => array(
            /* DEFAULT */          'format_suppressed_text',
            'phpdoc:classref'   => 'format_grep_classname_text',
            'phpdoc:exceptionref'  => 'format_grep_classname_text',
            'reference'            => 'format_reference_titleabbrev_text',
            'refentry' => 'format_grep_classname_text',
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
        "class_name_ref"               => null,
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
        "methodparam"                  => array(
            "type_separator"           => array(),
            "type_separator_stack"     => array(),
            "paramtypes"               => array(),
        ),
    );

    /** @var int|null Number of already formatted types in the current compound type */
    private $num_types = null;

    /** @var array<string> The characters to separate the current compound types, i.e. "|" or "&amp;" */
    private $type_separator = [];

    /** @var bool|null Decides whether the union type can be displayed by using "?" */
    private $simple_nullable = null;

    protected $pihandlers = array(
        'dbhtml'        => 'PI_DBHTMLHandler',
        'dbtimestamp'   => 'PI_DBHTMLHandler',
        'phpdoc'        => 'PI_PHPDOCHandler',
    );

    public function __construct(
        Config $config,
        OutputHandler $outputHandler
    ) {
        parent::__construct($config, $outputHandler);
        $this->myelementmap = array_merge(parent::getDefaultElementMap(), $this->getDefaultElementMap());
        $this->mytextmap = array_merge(parent::getDefaultTextMap(), $this->getDefaultTextMap());
        $this->dchunk = array_merge(parent::getDefaultChunkInfo(), $this->getDefaultChunkInfo());
        $this->registerPIHandlers($this->pihandlers);

        if ($this->config->css === []) {
            $this->config->css = [
                "http://www.php.net/styles/theme-base.css",
                "http://www.php.net/styles/theme-medium.css",
            ];
        }
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
        $this->versions = self::generateVersionInfo($this->config->phpwebVersionFilename);
        $this->deprecated = self::generateDeprecatedInfo($this->config->phpwebVersionFilename);
        $this->acronyms = self::generateAcronymInfo($this->config->phpwebAcronymFilename);
    }

    public static function generateVersionInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        if (!is_file($filename)) {
            trigger_error(vsprintf("Can't find Version information file (%s), skipping!", [$filename]), E_USER_WARNING);
            return array();
        }

        $r = new \XMLReader;
        if (!$r->open($filename)) {
            trigger_error(vsprintf("Can't open the version info file (%s)", [$filename]), E_USER_ERROR);
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
            trigger_error(vsprintf("Can't find Version information file (%s), skipping!", [$filename]), E_USER_WARNING);
            return array();
        }

        $r = new \XMLReader;
        if (!$r->open($filename)) {
            trigger_error(vsprintf("Can't open the version info file (%s)", [$filename]), E_USER_ERROR);
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
            trigger_error(vsprintf("Can't find acronym file (%s), skipping", [$filename]), E_USER_WARNING);
            return array();
        }

        $r = new \XMLReader;
        if (!$r->open($filename)) {
            trigger_error(vsprintf("Could not open file for accessing acronym information (%s)", [$filename]), E_USER_ERROR);
        }

        $acronyms = array();
        $k = "";
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
                $this->simple_nullable = false;
                $isUnionType = $attrs[Reader::XMLNS_DOCBOOK]["class"] === "union";
                if (
                    $isUnionType &&
                    substr_count($props["innerXml"], '<type xmlns="http://docbook.org/ns/docbook">') === 2 &&
                    strpos($props["innerXml"], '<type xmlns="http://docbook.org/ns/docbook">null</type>') !== false
                ) {
                    $this->simple_nullable = true;
                    $this->type_separator[] = "";
                    $retval .= '<span class="type">?</span>';
                } else {
                    $typeSeparator = match ($attrs[Reader::XMLNS_DOCBOOK]["class"]) {
                        "union"        => "|",
                        "intersection" => "&amp;",
                        default        => "",
                    };
                    $this->type_separator[] = $typeSeparator;
                }
            } elseif (isset($this->num_types)) {
                if ($this->num_types > 0) {
                    $retval .= end($this->type_separator);
                }

                $this->num_types++;
            }

            $retval .= '<span class="type">';
        } else {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["class"])) {
                $this->num_types = null;
                array_pop($this->type_separator);
                $this->simple_nullable = null;
            }
            $retval .= '</span>';
        }

        return $retval;
    }

    public function format_methodsynopsis_type($open, $tag, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["class"])) {
                $this->cchunk["methodsynopsis"]["type_separator_stack"][] = match ($attrs[Reader::XMLNS_DOCBOOK]["class"]) {
                    "union"        => "|",
                    "intersection" => "&amp;",
                };
            }
        } else {
            if (
                isset($attrs[Reader::XMLNS_DOCBOOK]["class"])
                && ($attrs[Reader::XMLNS_DOCBOOK]["class"] === "union"
                || $attrs[Reader::XMLNS_DOCBOOK]["class"] === "intersection")
                ) {
                $lastSeparator = array_pop($this->cchunk["methodsynopsis"]["type_separator_stack"]);
                $this->cchunk["methodsynopsis"]["type_separator"][count($this->cchunk["methodsynopsis"]["type_separator"]) - 1] = end($this->cchunk["methodsynopsis"]["type_separator_stack"]) ?: $lastSeparator;
            }
        }

        return "";
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
                $retval = $this->autogenVersionInfo($this->cchunk["class_name_ref"]);
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

    public function format_methodsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            return parent::format_methodsynopsis($open, $name, $attrs, $props);
        }

        $content = "";
        if ($this->params["paramCount"] > 3) {
            $content .= "<br>";
        } else if ($this->params["paramCount"] === 0) {
            $content .= "(";
        }

        $content .= ")";

        if ($this->cchunk["methodsynopsis"]["returntypes"]) {
            $type = $this->format_types(
                $this->cchunk["methodsynopsis"]["type_separator"],
                $this->cchunk["methodsynopsis"]["returntypes"]
            );

            $content .= ': ' . $type;
        }

        $content .= "</div>\n";
        $this->cchunk["methodsynopsis"] = $this->dchunk["methodsynopsis"];

        return $content;
    }

    private function format_types($type_separators, $paramOrReturnType) {
        $types = [];

        if (
            $type_separators[0] === "|" &&
            count($paramOrReturnType) === 2 &&
            in_array("null", $paramOrReturnType, true)
        ) {
            $this->simple_nullable = true;
            $type_separators[0] = "";
            $formatted_type = self::format_type_text("?", "type");
            $types[] = '<span class="type">' . $formatted_type .'</span>';
        }

        foreach ($paramOrReturnType as $individualType) {
            $formatted_type = self::format_type_text($individualType, "type");
            if ($formatted_type === false) {
                $formatted_type = $individualType;
            }
            if ($formatted_type !== "") {
                if ($individualType === "void") {
                    $types[] = $formatted_type;
                } else {
                    $types[] = '<span class="type">' . $formatted_type . '</span>';
                }
            }
        }

        $type = "";
        $isDnfType = (in_array("&amp;", $type_separators, true) && in_array("|", $type_separators, true));
        for ($i = 0; $i < count($types); $i++) {
            $previousSeparator = ($i > 0 ) ? $type_separators[$i - 1] : "";
            $openingParenthesis = ($isDnfType && $type_separators[$i] === "&amp;" && $previousSeparator !== "&amp;") ? "(" : "";
            $closingParenthesis = ($isDnfType && $type_separators[$i] !== "&amp;" && $previousSeparator === "&amp;") ? ")" : "";

            $type .= $openingParenthesis . $types[$i] . $closingParenthesis . $type_separators[$i];
        }
        $type = rtrim($type, end($type_separators));

        if (count($types) > 1) {
            $type = '<span class="type">' . $type . '</span>';
        }
        $this->simple_nullable = null;

        return $type;
    }

    public function format_type_methodsynopsis_text($type, $tagname) {
        $this->cchunk["methodsynopsis"]["returntypes"][] = $type;
        $this->cchunk["methodsynopsis"]["type_separator"][] = end($this->cchunk["methodsynopsis"]["type_separator_stack"]);

        return "";
    }

    public function format_methodparam_parameter($open, $name, $attrs, $props) {
        $type = "";
        if ($open) {
            if ($this->cchunk["methodparam"]["paramtypes"]) {
                $type = $this->format_types(
                    $this->cchunk["methodparam"]["type_separator"],
                    $this->cchunk["methodparam"]["paramtypes"]
                );
            }
            $this->cchunk["methodparam"] = $this->dchunk["methodparam"];
        }
        return $type . parent::format_methodparam_parameter($open, $name, $attrs, $props);
    }

    public function format_methodparam($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["methodparam"] = $this->dchunk["methodparam"];
            return parent::format_methodparam($open, $name, $attrs);
        }

        if ($this->params["opt"] && !$this->params["init"]) {
            return '<span class="initializer"> = ?</span></span>';
        }
        $this->params["init"] = false;
        $this->cchunk["methodparam"] = $this->dchunk["methodparam"];
        return "</span>";
    }

    public function format_type_methodparam($open, $tag, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["class"])) {
                $this->cchunk["methodparam"]["type_separator_stack"][] = match ($attrs[Reader::XMLNS_DOCBOOK]["class"]) {
                    "union"        => "|",
                    "intersection" => "&amp;",
                };
            }
        } else {
            if (
                isset($attrs[Reader::XMLNS_DOCBOOK]["class"])
                && ($attrs[Reader::XMLNS_DOCBOOK]["class"] === "union"
                || $attrs[Reader::XMLNS_DOCBOOK]["class"] === "intersection")
            ) {
                $lastSeparator = array_pop($this->cchunk["methodparam"]["type_separator_stack"]);
                $this->cchunk["methodparam"]["type_separator"][count($this->cchunk["methodparam"]["type_separator"]) - 1] = end($this->cchunk["methodparam"]["type_separator_stack"]) ?: $lastSeparator;
            }
        }

        return "";
    }

    public function format_type_methodparam_text($type, $tagname) {
        $this->cchunk["methodparam"]["paramtypes"][] = $type;
        $this->cchunk["methodparam"]["type_separator"][] = end($this->cchunk["methodparam"]["type_separator_stack"]);

        return "";
    }

    public function format_type_text($type, $tagname) {
        $t = strtr($this->normalizeFQN($type), ["_" => "-", "\\" => "-"]);

        switch($t) {
        case "void":
            return $this->format_void(false, '', [], []);
        case "bool":
            $href = "language.types.boolean";
            break;
        case "int":
            $href = "language.types.integer";
            break;
        case "double":
            $href = "language.types.float";
            break;
        case "?":
            $href = "language.types.null";
            break;
        case "false":
        case "true":
            $href = "language.types.value";
            break;
        case "null":
            if ($this->simple_nullable) {
                return "";
            }
        case "boolean":
        case "integer":
        case "float":
        case "string":
        case "array":
        case "object":
        case "resource":
        case "callable":
        case "iterable":
        case "mixed":
        case "never":
            $href = "language.types.$t";
            break;
        default:
            /* Check if its a classname. */
            $href = Format::getFilename("class.$t");
            if (!$href) {
                /* Check if its an enumname. */
                $href = Format::getFilename("enum.$t");
            }
        }

        if ($href === false) {
            return false;
        }

        $classNames = ($type === "?") ? ($tagname . ' null') : ($tagname . ' ' . ltrim($type, "\\"));
        if ($href && $this->chunked) {
            return '<a href="' .$href. $this->getExt(). '" class="' . $classNames . '">' .$type. '</a>';
        }
        if ($href) {
            return '<a href="#' .$href. '" class="' . $classNames . '">' .$type. '</a>';
        }
        return '<span class="' . $classNames . '">' .$type. '</span>';
    }

    public function format_void($open, $name, $attrs, $props) {
        if (isset($props['sibling']) && $props['sibling'] == 'methodname') {
            return '';
        }
        return parent::format_void($open, $name, $attrs, $props);
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

    public function configureVerInfoAttribute($attrs) {
        /* Note role attribute also has usage with "noversion" to not check version availability */
        /* TODO This should be migrated to the annotations attribute */
        if (isset($attrs[Reader::XMLNS_DOCBOOK]["annotations"])) {
            $this->cchunk["verinfo"] = !str_contains($attrs[Reader::XMLNS_DOCBOOK]["annotations"], 'verify_info:false');
        } else if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
            $this->cchunk["verinfo"] = !($attrs[Reader::XMLNS_DOCBOOK]["role"] == "noversion");
        } else {
            $this->cchunk["verinfo"] = true;
        }
    }

    public function versionInfo($funcname) {
        $funcname = str_replace(
                array("::", "-&gt;", "->", "__", "_", '$', '()'),
                array("-",  "-",     "-",  "-",  "-", "",  ''),
                strtolower($funcname));
        if(isset($this->versions[$funcname])) {
           return $this->versions[$funcname];
        }
        $this->outputHandler->v("No version info for %s", $funcname, VERBOSE_NOVERSION);
        return false;
    }

    public function deprecationInfo($funcname) {
        $funcname = str_replace(
                array("::", "-&gt;", "->", "__", "_", '$', '()'),
                array("_",  "_",     "_",  "_",  "_", "",  ''),
                strtolower($funcname));
        if(isset($this->deprecated[$funcname])) {
           return $this->deprecated[$funcname];
        }
        return false;
    }


    public function acronymInfo($acronym) {
        return isset($this->acronyms[$acronym]) ? $this->acronyms[$acronym] : false;
    }

    public function format_acronym_text($value, $tag) {
        $resolved = $this->acronymInfo($value);
        if ($resolved) {
            return '<abbr title="' .$resolved. '">' .$value. '</abbr>';
        }
        return '<abbr>'.$value.'</abbr>';
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
            $ref = $this->normalizeFQN($value);
            $filename = $this->getRefnameLink($ref);
        }
        if ($filename !== null) {
            if ($this->CURRENT_ID !== $filename) {
                $rel = $desc = "";
                if ($this->getRole() === "seealso") {
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
            $this->outputHandler->v("No link found for %s", $value, VERBOSE_BROKEN_LINKS);
        }

        return '<strong>' .$display_value. '</strong>';
    }

    public function format_grep_classname_text($value, $tag) {
        $this->cchunk["class_name_ref"] = strtolower($value);
    }

    public function format_classsynopsis_ooclass_classname_text($value, $tag) {
        $content = parent::format_classsynopsis_ooclass_classname_text($value, $tag);
        /** Legacy behaviour for crappy markup */
        if ($content === '') {
            return '';
        }
        return $this->format_classname_text($content, $tag);
    }

    public function format_classsynopsis_oo_name_text($value, $tag) {
        $content = parent::format_classsynopsis_oo_name_text($value, $tag);
        return $this->format_classname_text($content, $tag);
    }

    public function format_classname_text($value, $tag) {
        if (($filename = $this->getClassnameLink($this->normalizeFQN($value))) !== null && $this->cchunk["class_name_ref"] !== strtolower($value)) {
            $href = $this->chunked ? $filename.$this->ext : "#$filename";
            return '<a href="'.$href. '" class="' .$tag. '">' .$value. '</a>';
        }
        return '<strong class="' .$tag. '">' .$value. '</strong>';
    }


    /*Chunk Functions*/
    private function isChunkedByAttributes(array $attributes): bool {
        /* Legacy way to mark chunks */
        if (isset($attributes[Reader::XMLNS_PHD]['chunk'])) {
            return $attributes[Reader::XMLNS_PHD]['chunk'] != 'false';
        } elseif (isset($attributes[Reader::XMLNS_DOCBOOK]['annotations'])) {
            /** Annotations attribute is a standard DocBook attribute and could be used for various things */
            return !str_contains($attributes[Reader::XMLNS_DOCBOOK]['annotations'], 'chunk:false');
        } else {
            /* Chunked by default */
            return true;
        }
    }

    public function format_container_chunk($open, $name, $attrs, $props) {
        $hasAnnotations = array_key_exists('annotations', $attrs[Reader::XMLNS_DOCBOOK]);

        $this->CURRENT_CHUNK = $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]["id"] ?? '';

        if ($this->isChunkedByAttributes($attrs)) {
            $this->cchunk = $this->dchunk;
        }

        if ($open) {
            if ($hasAnnotations) {
                $this->pushAnnotations($attrs[Reader::XMLNS_DOCBOOK]["annotations"]);
            }

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
        if ($hasAnnotations) {
            $this->popAnnotations();
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
        $hasAnnotations = array_key_exists('annotations', $attrs[Reader::XMLNS_DOCBOOK]);

        $this->CURRENT_CHUNK = $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]["id"] ?? '';
        if ($open) {
            if ($hasAnnotations) {
                $this->pushAnnotations($attrs[Reader::XMLNS_DOCBOOK]["annotations"]);
            }

            $this->notify(Render::CHUNK, Render::OPEN);
            return '<div id="'.$id.'" class="'.$name.'">';
        }

        if ($hasAnnotations) {
            $this->popAnnotations();
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
                $content .= '<li><a href="' .$href. '">' . $long . '</a>';
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
                        $content .= '<li><a href="' .$href. '">' . $long . '</a></li>';
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
                trigger_error(vsprintf("Uhm. Can't find an ID for a chunk? - Generating a random one (%s)\n%s", [$id, $this->getDebugTree($name, $props)]), E_USER_WARNING);
            }

            $this->CURRENT_CHUNK = $this->CURRENT_ID = $id;
            if ($this->isChunkedByAttributes($attrs)) {
                $this->cchunk = $this->dchunk;
                $this->notify(Render::CHUNK, Render::OPEN);
            }
            if (isset($props["lang"])) {
                $this->lang = $props["lang"];
            }
            if ($name == "refentry") {
                $this->configureVerInfoAttribute($attrs);
            }
            if ($name == "legalnotice") {
                return '<div id="legalnotice">';
            }
            return '<div id="'.$id.'" class="'.$name.'">';
        }
        if ($this->isChunkedByAttributes($attrs)) {
            $this->notify(Render::CHUNK, Render::CLOSE);
        }
        return '</div>';
    }

    public function format_container_chunk_title($open, $name, $attrs, $props) {
        if ($open) {
            return $props["empty"] ? '' : '<h1 class="title">';
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

    public function format_refentry($open, $name, $attrs, $props) {
       $this->configureVerInfoAttribute($attrs);

        /* We overwrite the tag name to continue working with the usual indexing */
        if (isset($attrs[Reader::XMLNS_DOCBOOK]['role'])) {
            return match ($attrs[Reader::XMLNS_DOCBOOK]['role']) {
                'class', 'enum', 'exception' => $this->format_class_chunk($open, 'reference', $attrs, $props),
                'variable' => $this->format_chunk($open, 'refentry', $attrs, $props),
                default => $this->format_chunk($open, $name, $attrs, $props),
            };
        }
        return $this->format_chunk($open, $name, $attrs, $props);
    }

    public function format_class_chunk($open, $name, $attrs, $props) {
        if ($open) {
            $retval = $this->format_container_chunk($open, "reference", $attrs, $props);
            /* Classes must have version availability information */
            $this->cchunk["verinfo"] = true;
            return $retval;
        }
        return $this->format_container_chunk($open, "reference", $attrs, $props);
    }

    public function format_reference($open, $name, $attrs, $props) {
        if (isset($attrs[Reader::XMLNS_DOCBOOK]['role'])) {
            if ($attrs[Reader::XMLNS_DOCBOOK]['role'] === "class"
                || $attrs[Reader::XMLNS_DOCBOOK]['role'] === "enum"
                || $attrs[Reader::XMLNS_DOCBOOK]['role'] === "exception") {
                if ($open) {
                    $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]['role']);
                } else {
                    $this->popRole();
                }
                return $this->format_class_chunk($open, $name, $attrs, $props);
            }
        }
        return $this->format_container_chunk($open, $name, $attrs, $props);
    }

    public function format_reference_titleabbrev_text($value, $tag) {
        if ($this->getRole() === "class"
            || $this->getRole() === "enum"
            || $this->getRole() === "exception") {
            return $this->format_grep_classname_text($value, $tag);
        }
        return $this->format_suppressed_text($value, $tag);
    }
}
