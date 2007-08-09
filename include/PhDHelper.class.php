<?php
/*  $Id$ */

class PhDHelper {
    private $IDs            = array();
    private $IDMap          = array();
    /* abstract */ protected $elementmap  = array();
    /* abstract */ protected $textmap     = array();

    public function __construct(array $IDs, array $IDMap) {
        $this->IDs = $IDs;
        $this->IDMap = $IDMap;
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
    final public function getElementMap() {
        return $this->elementmap;
    }
    final public function getTextMap() {
        return $this->textmap;
    }
}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

