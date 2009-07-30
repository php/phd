<?php
namespace phpdotnet\phd;
/*  $Id$ */

abstract class Format_Legacy extends Helper
{
    private $TABLE   = array();

    /* abstract functions */
    abstract public function transformFromMap($open, $tag, $name, $attrs, $props);
    abstract public function CDATA($data);
    abstract public function TEXT($data);
    abstract public function __call($func, $args);

    /* Table helper functions */
    public function tgroup($attrs) {
        if (isset($attrs["cols"])) {
            $this->TABLE["cols"] = $attrs["cols"];
            unset($attrs["cols"]);
        }

        $this->TABLE["defaults"] = $attrs;
        $this->TABLE["colspec"] = array();
    }
    public function colspec(array $attrs) {
        $colspec = self::getColspec($attrs, false);
        $this->TABLE["colspec"][$colspec["colnum"]] = $colspec;
        return $colspec;
    }
    public function getColspec(array $attrs, $forRow = true) {
        /* defaults */
        $defaults = array(
            "colname" => count($this->TABLE["colspec"])+1,
            "colnum"  => count($this->TABLE["colspec"])+1,
            "align"   => "left",
        );

        $retval = array_merge($defaults, $this->TABLE["defaults"], $attrs);
        if ($forRow) {
            foreach($this->TABLE["colspec"] as $colspec) {
                if ($colspec["colname"] == $retval["colname"]) {
                    $retval = array_merge($retval, $colspec);
                    break;
                }
            }
        }
        return $retval;
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

}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

