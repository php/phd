<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Format_Abstract_Manpage extends Format {
    public $role = false;

    /* If a chunk is being processed */
    protected $chunkOpen = false;

    public function UNDEF($open, $name, $attrs, $props) {        
        if ($open) {
            trigger_error("No mapper found for '{$name}'", E_USER_WARNING);
        }
        return "\n.B [NOT PROCESSED] $name [/NOT PROCESSED]";        
    }    

    public function CDATA($str) {
        return $this->highlight(trim($str), $this->role, 'troff');
    }

    public function TEXT($str) {
        $ret = trim(preg_replace( '/[ \n\t]+/', ' ', $str));
        // No newline if current line begins with ',', ';', ':', '.'
        if (strncmp($ret, ",", 1) && strncmp($ret, ";", 1) && strncmp($ret, ":", 1) && strncmp($ret, ".", 1))
            $ret = "\n" . $ret;
        return $ret;
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        if ($tag === '') return $tag;
        $isMacro = (strncmp($tag, ".", 1) == 0);
        if ($open) {
            return "\n" . $tag . ($isMacro ? "\n" : "");
        }
        return ($isMacro ? "" : "\\fP");
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

