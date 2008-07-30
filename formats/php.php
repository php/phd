<?php
class PhDPHPFormat extends PhDXHTMLFormat {
    private $myelementmap = array(
        'acronym'               => 'format_suppressed_tags',
        'function'              => 'format_suppressed_tags',
        'link'                  => 'format_link',
        'refpurpose'            => 'format_refpurpose',
        'varname'               => array(
            /* DEFAULT */          'var',
            'fieldsynopsis'     => 'format_fieldsynopsis_varname',
        ),
        'xref'                  => 'format_xref',


        '.title'                 => array(
            /* DEFAULT */          false,
            'info'              => array(
                /* DEFAULT */      false,
                'article'       => 'format_container_chunk_top_title',
                'appendix'      => 'format_container_chunk_top_title',
                'book'          => 'format_container_chunk_top_title',
                'chapter'       => 'format_container_chunk_top_title',
                'part'          => 'format_container_chunk_top_title',
                'set'           => 'format_container_chunk_top_title',
            ),
            'article'           => 'format_container_chunk_top_title',
            'appendix'          => 'format_container_chunk_top_title',
            'book'              => 'format_container_chunk_top_title',
            'chapter'           => 'format_container_chunk_top_title',
            'part'              => 'format_container_chunk_top_title',
            'set'               => 'format_container_chunk_top_title',
        ),
        '.reference'             => 'format_container_chunk_below',
        '.question'              => array(
            /* DEFAULT */          false,
            'questions'         => 'format_phd_question', // From the PhD namespace
        ),
    );
    private $mytextmap = array(
        'acronym'               => 'format_acronym_text',
        'function'              => 'format_function_text',
        'methodname'            => array(
            /* DEFAULT */         'format_function_text',
            'constructorsynopsis' => array(
                /* DEFAULT */     'format_function_text',
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'methodsynopsis'    => array(
                /* DEFAULT */     'format_function_text',
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'destructorsynopsis' => array(
                /* DEFAULT */     'format_function_text',
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
        ),

        'refname'               => 'format_refname_text',
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

    );
    protected $flags;
    protected $ext = "php";
    private   $acronyms = array();
    private   $versions = array();
    private   $refname  = "";

    public function __construct() {
        parent::__construct();
        $this->versions = self::generateVersionInfo(PhDConfig::phpweb_version_filename());
        $this->acronyms = self::generateAcronymInfo(PhDConfig::phpweb_acronym_filename());

    }

    public function header($id) {
        /* Yes. This is scary. I know. */
        return '<?php
include_once $_SERVER[\'DOCUMENT_ROOT\'] . \'/include/shared-manual.inc\';
$setup = array(
    "home"    => array("index.php", "PHP Manual"),
    "head"    => array("UTF-8", "en"),
    "this"    => array(null, null),
    "up"      => array(null, null),
    "prev"    => array(null, null),
    "next"    => array(null, null),
    "toc"     => array(),
    "parents" => array(),
);
manual_setup($setup);

manual_header();
?>
';
    }
    public function footer($id) {
        return "<?php manual_footer(); ?>";
    }


    public function getDefaultElementMap() {
        return $this->myelementmap;
    }
    public function getDefaultTextMap() {
        return $this->mytextmap;
    }


    public function format_suppressed_tags($open, $name, $attrs, $props) {
        return "";
    }
    public function update($event, $val = null) {
        switch($event) {
        case PhDRender::CHUNK:
            $this->flags = $val;
            break;

        case PhDRender::STANDALONE:
            if ($val) {
                $this->registerElementMap(array_merge(parent::getDefaultElementMap(), static::getDefaultElementMap()));
                $this->registerTextMap(array_merge(parent::getDefaultTextMap(), static::getDefaultTextMap()));
            } else {
                $this->registerElementMap(static::getDefaultElementMap());
                $this->registerTextMap(static::getDefaultTextMap());
            }
            break;
        }
    }
    public function format_suppressed_text($value, $tag) {
        return "";
    }

    protected function lookupRefname($for) {
        return $this->refs[$for];
        return NO_SQLITE;
        $rsl = $this->sqlite->query("SELECT filename, ldesc, sdesc FROM ids WHERE sdesc='$for' AND element='refentry'")->fetchArray(SQLITE3_ASSOC);
        if (isset($rsl[0])) {
            return $rsl[0]["filename"];
        }
        return false;
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
    public function format_refpurpose($open, $tag, $attrs, $props) {
        if ($open) {
            return '<p class="verinfo">(' .(htmlspecialchars($this->versionInfo($this->refname), ENT_QUOTES, "UTF-8")). ')</p><p class="refpurpose dc-title">'. $this->refname. ' — ';
        }
        return "</p>\n";
    }
    public function format_fieldsynopsis_varname($open, $name, $attrs) {
        if ($open) {
            $href = "";
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])) {
                $href = $this->createLink($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"]);

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


    public function format_refname_text($value, $tag) {
        $this->refname = $value;
        return false;
    }
    public function format_function_text($value, $tag, $display_value = null) {
        if ($display_value === null) {
            $display_value = $value;
        }

        $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $value));

        $filename = $this->lookupRefname($ref);

        if (($filename !== false) && $this->CURRENT_CHUNK !== $filename) {
            $desc = "";
            $href = $this->createLink($filename, $desc);
            return '<a href="' . $href . '" class="function" title="' . $desc . '">' . $display_value . ($tag == "function" ? "()" : ""). '</a>';
        }
        return '<strong>' . $display_value . ($tag === "function" ? "()" : ""). '</strong>';
    }
    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        $value = $this->TEXT($value);
        if ($this->cchunk["classsynopsis"]["classname"] === false) {
            return $this->format_function_text($value, $tag);
        }
        if (strpos($value, '::')) {
            $explode = '::';
        } elseif (strpos($value, '->')) {
            $explode = '->';
        } else {
            return $this->format_function_text($value, $tag);
        }

        list($class, $method) = explode($explode, $value);
        if ($class !== $this->cchunk["classsynopsis"]["classname"]) {
            return $this->format_function_text($value, $tag);
        }
        return $this->format_function_text($value, $tag, $method);
    }
    public function format_type_if_object_or_pseudo_text($value, $tag) {
        if (in_array(strtolower($value), array("bool", "int", "double", "boolean", "integer", "float", "string", "array", "object", "resource", "null"))) {
            return false;
        }
        return self::format_type_text($value, $tag);
    }
    public function format_type_text($value, $tag) {
        $t = strtolower($value);

        switch($t) {
        case "bool":
            $id = "language.types.boolean";
            break;

        case "int":
        case "long":
            $id = "language.types.integer";
            break;

        case "double":
            $id = "language.types.float";
            break;

        /* FIXME: These aren't documented yet */
        case "binary":
        case "unicode":
            $id = "language.types.string";
            break;

        case "void":
        case "boolean":
        case "integer":
        case "float":
        case "string":
        case "array":
        case "object":
        case "resource":
        case "null":
            $id = "language.types.$t";
            break;

        case "mixed":
        case "number":
        case "callback":
            $id = "language.types.$t";
            break;

        default:
            /* Check if its a classname. */
            $t = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $t));
            $id = "class.$t";
        }

        $desc = "";
        $href = $this->createLink($id, $desc);

        if ($href) {
            return '<a href="' .$href. '" class="' .$tag. ' ' .$value. '" title="' . $desc . '">' .$value. '</a>';
        }
        return '<span class="' .$tag. ' ' .$value. '">' .$value. '</span>';
    }

}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

