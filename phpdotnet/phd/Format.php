<?php
namespace phpdotnet\phd;

abstract class Format extends ObjectStorage {
    const SDESC = 1;
    const LDESC = 2;

    private $elementmap = array();
    private $textmap = array();
    private $formatname = "UNKNOWN";
    protected $sqlite;

    protected $title;
    protected $fp = array();
    protected $ext;
    protected $outputdir;
    protected $chunked;

    /* Indexing maps */
    protected $indexes = array();
    protected $childrens = array();

    protected $refs = array();
    protected $vars = array();
    protected $classes = array();

    private static $autogen = array();

    /* PhDHelper */
    private static $highlighters = array();

    public function __construct() {
        if (file_exists(Config::output_dir() . "index.sqlite")) {
            $this->sqlite = new \SQLite3(Config::output_dir() . 'index.sqlite');
            $this->sortIDs();
        }
    }

    abstract public function transformFromMap($open, $tag, $name, $attrs, $props);
    abstract public function UNDEF($open, $name, $attrs, $props);
    abstract public function TEXT($value);
    abstract public function CDATA($value);
    abstract public function createLink($for, &$desc = null, $type = Format::SDESC);
    abstract public function appendData($data);
    abstract public function update($event, $value = null);

    public function sortIDs() {
        $this->sqlite->createAggregate("indexes", array($this, "SQLiteIndex"), array($this, "SQLiteFinal"), 8);
        $this->sqlite->createAggregate("childrens", array($this, "SQLiteChildrens"), array($this, "SQLiteFinal"), 2);
        $this->sqlite->createAggregate("refname", array($this, "SQLiteRefname"), array($this, "SQLiteFinal"), 2);
        $this->sqlite->createAggregate("varname", array($this, "SQLiteVarname"), array($this, "SQLiteFinal"), 2);
        $this->sqlite->createAggregate("classname", array($this, "SQLiteClassname"), array($this, "SQLiteFinal"), 2);
        $this->sqlite->query('SELECT indexes(docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next) FROM ids');
        $this->sqlite->query('SELECT childrens(docbook_id, parent_id) FROM ids');
        $this->sqlite->query('SELECT refname(docbook_id, sdesc) FROM ids WHERE element=\'refentry\'');
        $this->sqlite->query('SELECT varname(docbook_id, sdesc) FROM ids WHERE element=\'phpdoc:varentry\'');
        $this->sqlite->query('SELECT classname(docbook_id, sdesc) FROM ids WHERE element=\'phpdoc:exceptionref\' OR element=\'phpdoc:classref\'');
    }

    public function SQLiteIndex(&$context, $index, $id, $filename, $parent, $sdesc, $ldesc, $element, $previous, $next) {
        $this->indexes[$id] = array(
            "docbook_id" => $id,
            "filename"   => $filename,
            "parent_id"  => $parent,
            "sdesc"      => $sdesc,
            "ldesc"      => $ldesc,
            "element"    => $element,
            "previous"   => $previous,
            "next"       => $next
        );
    }

    public function SQLiteChildrens(&$context, $index, $id, $parent) {
        if (!isset($this->childrens[$parent]) || !is_array($this->childrens[$parent])) {
            $this->childrens[$parent] = array();
        }
        $this->childrens[$parent][] = $id;
    }

    public function SQLiteRefname(&$context, $index, $id, $sdesc) {
        $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $sdesc));
        $this->refs[$ref] = $id;
    }

    public function SQLiteVarname(&$context, $index, $id, $sdesc) {
        $this->vars[$sdesc] = $id;
    }

    public function SQLiteClassname(&$context, $index, $id, $sdesc) {
        $this->classes[strtolower($sdesc)] = $id;
    }

    public static function SQLiteFinal(&$context) {
        return $context;
    }

    final public function notify($event, $val = null) {
        $this->update($event, $val);
        foreach($this as $format) {
            $format->update($event, $val);
        }
    }   

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setExt($ext) {
        $this->ext = $ext;
    }

    public function getExt() {
        return $this->ext;
    }

    public function setOutputDir($outputdir) {
        $this->outputdir = $outputdir;
    }

    public function getOutputDir() {
        return $this->outputdir;
    }

    public function setChunked($chunked) {
        $this->chunked = $chunked;
    }

    public function isChunked() {
        return $this->chunked;
    }

    public function setFileStream($stream) {
        $this->fp = $stream;
    }

    public function getFileStream() {
        return $this->fp;
    }

    public function pushFileStream($stream) {
        $this->fp[] = $stream;
    }

    public function popFileStream() {
        return array_pop($this->fp);
    }

    public function getRefnameLink($ref) {
        return isset($this->refs[$ref]) ? $this->refs[$ref] : null;
    }
    public function getClassnameLink($class) {
        return isset($this->classes[$class]) ? $this->classes[$class] : null;
    }
    public function getVarnameLink($var) {
        return isset($this->vars[$var]) ? $this->vars[$var] : null;
    }
    final public function registerElementMap(array $map) {
        $this->elementmap = $map;
    }
    final public function registerTextMap(array $map) {
        $this->textmap = $map;
    }
    final public function attach($obj, $inf = array()) {
        if (!($obj instanceof $this) && get_class($obj) != get_class($this)) {
            throw new InvalidArgumentException(get_class($this) . " themes *MUST* _inherit_ " .get_class($this). ", got " . get_class($obj));
        }
        $obj->notify(Render::STANDALONE, false);
        return parent::attach($obj, $inf);
    }
    final public function getElementMap() {
        return $this->elementmap;
    }
    final public function getTextMap() {
        return $this->textmap;
    }
    final public function registerFormatName($name) {
        $this->formatname = $name;
    }
    public function getFormatName() {
        return $this->formatname;
    }

	/* Buffer where append data instead of the standard stream (see format's appendData()) */
    protected $appendToBuffer = false;
	protected $buffer = "";

    final public function parse($xml) {
        $parsed = "";
        $reader = new Reader();
        $render = new Render();

        $reader->XML("<notatag>" . $xml . "</notatag>");

        $this->appendToBuffer = true;
        $render->attach($this);
        $render->render($reader);
        $this->appendToBuffer = false;
        $parsed = $this->buffer;
        $this->buffer = "";

        return $parsed;
    }

    final public static function autogen($text, $lang) {
        if ($lang == NULL) {
            $lang = Config::language();
        }
        if (isset(self::$autogen[$lang])) {
            if (isset(self::$autogen[$lang][$text])) {
                return self::$autogen[$lang][$text];
            }
            if ($lang == Config::fallback_language()) {
                throw new \InvalidArgumentException("Cannot autogenerate text for '$text'");
            }
            return self::autogen($text, Config::fallback_language());
        }

        $filename = Config::lang_dir() . $lang . ".xml";

        $r = new \XMLReader;
        if (!file_exists($filename) || !$r->open($filename)) {
            if ($lang == Config::fallback_language()) {
                throw new \Exception("Cannot open $filename");
            }
            return self::autogen($text, Config::fallback_language());
        }
        $autogen = array();
        while ($r->read()) {
            if ($r->nodeType != \XMLReader::ELEMENT) {
                continue;
            }
            if ($r->name == "term") {
                $r->read();
                $k = $r->value;
                $autogen[$k] = "";
            } else if ($r->name == "simpara") {
                $r->read();
                $autogen[$k] = $r->value;
            }
        }
        self::$autogen[$lang] = $autogen;
        return self::autogen($text, $lang);
    }

/* {{{ TOC helper functions */
    final public function getFilename($id) {
        return isset($this->indexes[$id]["filename"]) ? $this->indexes[$id]["filename"] : false;
    }
    final public function getPrevious($id) {
        return $this->indexes[$id]["previous"];
    }
    final public function getNext($id) {
        return $this->indexes[$id]["next"];
    }
    final public function getParent($id) {
        return $this->indexes[$id]["parent_id"];
    }
    final public function getLongDescription($id, &$isLDesc = null) {
        if ($this->indexes[$id]["ldesc"]) {
            $isLDesc = true;
            return $this->indexes[$id]["ldesc"];
        } else {
            $isLDesc = false;
            return $this->indexes[$id]["sdesc"];
        }
    }
    final public function getShortDescription($id, &$isSDesc = null) {
        if ($this->indexes[$id]["sdesc"]) {
            $isSDesc = true;
            return $this->indexes[$id]["sdesc"];
        } else {
            $isSDesc = false;
            return $this->indexes[$id]["ldesc"];
        }
    }
    final public function getChildrens($id) {
        if (!isset($this->childrens[$id]) || !is_array($this->childrens[$id]) || count($this->childrens[$id]) == 0) {
            return null;
        }
        return $this->childrens[$id];
    }
/* }}} */

/* {{{ Table helper functions */
    public function tgroup($attrs) {
        if (isset($attrs["cols"])) {
            $this->TABLE["cols"] = $attrs["cols"];
            unset($attrs["cols"]);
        }

        $this->TABLE["defaults"] = $attrs;
        $this->TABLE["colspec"] = array();
    }
    public function colspec(array $attrs) {
        $colspec = self::getColSpec($attrs);
        $this->TABLE["colspec"][$colspec["colnum"]] = $colspec;
        return $colspec;
    }
    public function getColspec(array $attrs) {
/* defaults */
        $defaults["colname"] = count($this->TABLE["colspec"])+1;
        $defaults["colnum"]  = count($this->TABLE["colspec"])+1;
        $defaults["align"]   = "left";

        return array_merge($defaults, $this->TABLE["defaults"], $attrs);
    }
    public function getColCount() {
        return $this->TABLE["cols"];
    }
    public function valign($attrs) {
        return isset($attrs["valign"]) ? $attrs["valign"] : "middle";
    }
    public function initRow() {
        $this->TABLE["next_colnum"] = 1;
    }
    public function getEntryOffset(array $attrs) {
        $curr = $this->TABLE["next_colnum"];
        foreach($this->TABLE["colspec"] as $col => $spec) {
            if ($spec["colname"] == $attrs["colname"]) {
                $colnum = $spec["colnum"];
                $this->TABLE["next_colnum"] += $colnum-$curr;
                return $colnum-$curr;
            }
        }
        return -1;
    }
    public function colspan(array $attrs) {
        if (isset($attrs["namest"])) {
            foreach($this->TABLE["colspec"] as $colnum => $spec) {
                if ($spec["colname"] == $attrs["namest"]) {
                    $from = $spec["colnum"];
                    continue;
                }
                if ($spec["colname"] == $attrs["nameend"]) {
                    $to = $spec["colnum"];
                    continue;
                }
            }
            $colspan = $to-$from+1;
            $this->TABLE["next_colnum"] += $colspan;
            return $colspan;
        }
        $this->TABLE["next_colnum"]++;
        return 1;
    }
    public function rowspan($attrs) {
        if (isset($attrs["morerows"])) {
            return $attrs["morerows"]+1;
        }
        return 1;
    }
/* }}} */

    /*PhDHelper functions*/

    /**
    * Highlight (color) the given piece of source code
    *
    * @param string $text   Text to highlight
    * @param string $role   Source code role to use (php, xml, html, ...)
    * @param string $format Format to highlight (pdf, xhtml, troff, ...)
    *
    * @return string Highlighted code
    */
    public function highlight($text, $role = 'php', $format = 'xhtml')
    {
        if (!isset(self::$highlighters[$format])) {
            $class = Config::highlighter();            
            self::$highlighters[$format] = $class::factory($format);
        }

        return self::$highlighters[$format]->highlight(
            $text, $role, $format
        );
    }

}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

