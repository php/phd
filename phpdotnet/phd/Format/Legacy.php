<?php
/*  $Id$ */

abstract class PhDFormat {
    private $reader;
    private $IDs            = array();
    private $IDMap          = array();
    private $TABLE          = array();
    protected $ext          = "";
    /* abstract */ protected $map          = array();

    public function __construct(PhDReader $reader, array $IDs, array $IDMap, $ext) {
        $this->reader = $reader;
        $this->IDs = $IDs;
        $this->IDMap = $IDMap;
        $this->ext = $ext;
    }
    final public function getFilename($id) {
        return isset($this->IDs[$id]) ? $this->IDs[$id]["filename"] : false;
    }
    final public function getDescription($id, $long = false) {
        return $long ?
            ($this->IDs[$id]["ldesc"] ? $this->IDs[$id]["ldesc"] : $this->IDs[$id]["sdesc"]) :
            ($this->IDs[$id]["sdesc"] ? $this->IDs[$id]["sdesc"] : $this->IDs[$id]["ldesc"]);
    }
    final public function getContainer($id) {
        return $this->IDMap[$id];
    }
    final public function getParent($id) {
        return $this->IDMap[$id]["parent"];
    }
    final public function getMap() {
        return $this->map;
    }

    /* PhDReader wrapper functions */
    public function getID() {
        return $this->reader->getID();
    }
    public function readContent($node = null) {
        return $this->reader->readContent($node);
    }
    public function readAttribute($attr) {
        return $this->reader->readAttribute($attr);
    }
    public function readAttributeNs($attr, $ns) {
        return $this->reader->readAttributeNs($attr, $ns);
    }
    public function getAttributes() {
        return $this->reader->getAttributes();
    }
    public function getNextChild($node) {
        return $this->reader->readNode($node) ? array("type" => $this->reader->nodeType, "name" => $this->reader->name) : false;
    }
    /* abstract functions */
    abstract public function transformFromMap($open, $tag, $name);
    abstract public function CDATA($data);
    abstract public function __call($func, $args);

    /* Table helper functions */
    public function tgroup() {
        $attrs = self::getAttributes();

        $this->TABLE["cols"] = $attrs["cols"];
        unset($attrs["cols"]);

        $this->TABLE["defaults"] = $attrs;
        $this->TABLE["colspec"] = array();

        return $attrs;
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

    public function valign() {
        $valign = self::readAttribute("valign");
        return $valign ? $valign : "middle";
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

}
/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

