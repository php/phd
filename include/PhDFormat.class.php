<?php

abstract class PhDFormat extends PhDObjectStorage {
    const SDESC = 1;
    const LDESC = 2;

    private $elementmap = array();
    private $textmap = array();
    private $formatname = "UNKNOWN";
    protected $sqlite;

    private static $autogen = array();

    public function __construct() {
        if (file_exists(PhDConfig::output_dir() . "index.sqlite")) {
            $this->sqlite = new SQLite3(PhDConfig::output_dir() . 'index.sqlite');
            $this->sortIDs();
        }
    }

    abstract public function transformFromMap($open, $tag, $name, $attrs, $props);
    abstract public function UNDEF($open, $name, $attrs, $props);
    abstract public function TEXT($value);
    abstract public function CDATA($value);
    abstract public function createLink($for, &$desc = null, $type = PhDFormat::SDESC);
    abstract public function appendData($data);
    abstract public function update($event, $value = null);

    public function sortIDs() {
        $this->sqlite->createAggregate("idx", array($this, "SQLiteIndex"), array($this, "SQLiteFinal"), 6);
        $this->sqlite->query('SELECT idx(docbook_id, filename, parent_id, sdesc, ldesc, element) FROM ids');
    }
    public function SQLiteIndex(&$context, $index, $id, $filename, $parent, $sdesc, $ldesc, $element) {
        $this->idx[$id] = array(
            "docbook_id" => $id,
            "filename"   => $filename,
            "parent_id"  => $parent,
            "sdesc"      => $sdesc,
            "ldesc"      => $ldesc,
            "element"    => $element
        );
        if ($element == "refentry") {
            $this->refs[$sdesc] = $id;
        }

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
        $obj->notify(PhDRender::STANDALONE, false);
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

    final public static function autogen($text, $lang) {
        if (isset(self::$autogen[$lang])) {
            if (isset(self::$autogen[$lang][$text])) {
                return self::$autogen[$lang][$text];
            }
            if ($lang == PhDConfig::fallback_language()) {
                throw new InvalidArgumentException("Cannot autogenerate text for '$text'");
            }
            return self::autogen($text, PhDConfig::fallback_language());
        }

        $filename = PhDConfig::lang_dir() . $lang . ".xml";

        $r = new XMLReader;
        if (!file_exists($filename) || !$r->open($filename)) {
            if ($lang == PhDConfig::fallback_language()) {
                throw new Exception("Cannot open $filename");
            }
            return self::autogen($text, PhDConfig::fallback_language());
        }
        $autogen = array();
        while ($r->read()) {
            if ($r->nodeType != XMLReader::ELEMENT) {
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
        $row = $this->sqlite->query($q = "SELECT filename FROM ids WHERE docbook_id='$id'")->fetchArray(SQLITE3_ASSOC);
        if (!is_array($row)) {
            var_dump($q);
            return false;
        }
        return $row["filename"];
    }
    final public function getParent($id) {
        $row = $this->sqlite->query($q = "SELECT parent_id FROM ids WHERE docbook_id='$id'")->fetchArray(SQLITE3_ASSOC);
        if (!is_array($row)) {
            var_dump($q);
            return false;
        }
        return $row["parent_id"];
    }
    final public function getLongDescription($for) {
        $row = $this->sqlite->query($q = "SELECT sdesc, ldesc FROM ids WHERE docbook_id='$for'")->fetchArray(SQLITE3_ASSOC);
        if (!is_array($row)) {
            var_dump($q);
            return false;
        }
        return $row["ldesc"] ?: $row["sdesc"];
    }
    final public function getShortDescription($for) {
        $row = $this->sqlite->query($q = "SELECT sdesc, ldesc FROM ids WHERE docbook_id='$for'")->fetchArray(SQLITE3_ASSOC);
        if (!is_array($row)) {
            var_dump($q);
            return false;
        }
        return $row["sdesc"] ?: $row["ldesc"];
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
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

