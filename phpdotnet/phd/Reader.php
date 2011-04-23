<?php
namespace phpdotnet\phd;
/*  $Id$ */
//6271

class Reader extends \XMLReader
{
    const XMLNS_XML     = "http://www.w3.org/XML/1998/namespace";
    const XMLNS_XLINK   = "http://www.w3.org/1999/xlink";
    const XMLNS_PHD     = "http://www.php.net/ns/phd";
    const XMLNS_DOCBOOK = "http://docbook.org/ns/docbook";

    public function __construct() {
    }

    /* Get the content of a named node, or the current node. */
    public function readContent($node = null) { /* {{{ */
        $retval = "";

        if($this->isEmptyElement) {
            return $retval;
        }
        if (!$node) {
            $node = $this->name;
        }
        $retval = "";
        while (self::readNode($node)) {
            $retval .= $this->value;
        }
        return $retval;
    } /* }}} */
    /* Read $nodeName until END_ELEMENT */
    public function readNode($nodeName) { /* {{{ */
        return self::read() && !($this->nodeType === self::END_ELEMENT && $this->name == $nodeName);
    } /* }}} */

}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

