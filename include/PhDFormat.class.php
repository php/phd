<?php
/*  $Id$ */

abstract class PhDFormat {
    private $reader;
    private $IDs            = array();
    private $IDMap          = array();
    protected $ext = "";
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
}
/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

