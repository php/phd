<?php
namespace phpdotnet\phd;
/* $Id$ */

class Render extends ObjectStorage
{
    const CHUNK        = 0x001;
    const OPEN         = 0x002;
    const CLOSE        = 0x004;
    const STANDALONE   = 0x008;
    const INIT         = 0x010;
    const FINALIZE     = 0x020;
    const VERBOSE      = 0x040;

    private   $STACK      = array();

    public function __construct() { /* {{{ */
    } /* }}} */

    public function notXPath($tag, $depth) { /* {{{ */
        do {
            if (isset($tag[$this->STACK[--$depth]])) {
                $tag = $tag[$this->STACK[$depth]];
            } else {
                $tag = $tag[0];
            }
        } while (is_array($tag));
        return $tag;
    } /* }}} */

    public function attach($obj, $inf = array()) { /* {{{ */
        if (!($obj instanceof Format)) {
            throw new InvalidArgumentException(
                'All formats *MUST* inherit ' . __NAMESPACE__ . '\\Format'
            );
        }
        $obj->notify(Render::STANDALONE, true);

        return parent::attach($obj, $inf);
    } /* }}} */

    public function render(Reader $r) { /* {{{ */
        ObjectStorage::setReader($r);

        foreach($this as $format) {
            $format->notify(Render::INIT, true);
        }

        $lastdepth = -1;
        while($r->read()) {
            $type = $r->nodeType;
            $data = $retval = $name = $open = false;

            switch($type) {
                case \XMLReader::ELEMENT: /* {{{ */
                $open  = true;
                    /* break intentionally omitted */
                case \XMLReader::END_ELEMENT:
                $name  = $r->name;
                $depth = $r->depth;
                $attrs = array(
                    Reader::XMLNS_DOCBOOK => array(),
                    Reader::XMLNS_XML     => array(),
                );

                if ($r->hasAttributes) {
                    $r->moveToFirstAttribute();
                    do {
                        $k = $r->namespaceURI;
                        $attrs[!empty($k) ? $k : Reader::XMLNS_DOCBOOK][$r->localName] = $r->value;
                    } while ($r->moveToNextAttribute());
                    $r->moveToElement();
                }

                $props    = array(
                    "empty"    => $r->isEmptyElement,
                    "isChunk"  => false,
                    "lang"     => $r->xmlLang,
                    "ns"       => $r->namespaceURI,
                    "sibling"  => $lastdepth >= $depth ? $this->STACK[$depth] : "",
                    "depth"    => $depth,
                );

                $this->STACK[$depth] = $name;

                if ($name == "notatag")
                    continue;
                
                foreach($this as $format) {
                    $map = $this[$format][\XMLReader::ELEMENT];

                    if (isset($map[$name]) === false) {
                        $data = $format->UNDEF($open, $name, $attrs, $props);
                        $format->appendData($data);
                        continue;
                    }

                    $tag = $map[$name];
                    if (is_array($tag)) {
                        $tag = $this->notXPath($tag, $depth);
                    }

                    if ($tag === false) {
                        $data = $format->UNDEF($open, $name, $attrs, $props);
                        $format->appendData($data);
                        continue;
                    }

                    if (strncmp($tag, "format_", 7) !== 0) {
                        $data = $retval = $format->transformFromMap($open, $tag, $name, $attrs, $props);
                    } else {
                        $data = $retval = $format->{$tag}($open, $name, $attrs, $props);
                    }
                    $format->appendData($data);

                    foreach($format as $theme) {
                        $map = $format[$theme][\XMLReader::ELEMENT];
                        if (isset($map[$name])) {
                            $tag = $map[$name];

                            if (is_array($tag)) {
                                $tag = $this->notXPath($tag, $depth);
                            }

                            if ($tag !== false) {
                                $retval = $theme->{$tag}($open, $name, $attrs, $props);

                                if ($retval !== false) {
                                    $data = $retval;
                                }
                            }
                        }
                        $theme->appendData($data);
                    }
                }

                $lastdepth = $depth;
                break;
                    /* }}} */

                case \XMLReader::TEXT: /* {{{ */
                $value = $r->value;
                $eldepth = $r->depth - 1;
                $name  = $this->STACK[$eldepth];

                foreach($this as $format) {
                    $map = $this[$format][\XMLReader::TEXT];
                    if (isset($map[$name])) {
                        $tag = $map[$name];

                        if (is_array($tag)) {
                            $tag = $this->notXPath($tag, $eldepth);
                        }

                        if ($tag !== false) {
                            $data = $retval = $format->{$tag}($value, $name);
                        } else {
                            $data = $retval = $format->TEXT($value);
                        }
                    } else {
                        $data = $retval = $format->TEXT($value);
                    }

                    if ($data === false) {
                        $format->appendData($value);
                    } else {
                        $format->appendData($data);
                    }

                    foreach($format as $theme) {
                        $map = $format[$theme][\XMLReader::TEXT];
                        if (isset($map[$name])) {
                            $tag = $map[$name];

                            if (is_array($tag)) {
                                $tag = $this->notXPath($tag, $eldepth);
                            }

                            if ($tag !== false) {
                                $retval = $format->{$tag}($value, $name);
                                if ($retval !== false) {
                                    $data = $retval;
                                }
                            }
                        }
                        $theme->appendData($data);
                    }
                }
                break;
                    /* }}} */

                case \XMLReader::CDATA: /* {{{ */
                            /* Different formats may want to escape the CDATA sections differently */
                $value  = $r->value;
                foreach($this as $format) {
                    $retval = $format->CDATA($value);
                    $format->appendData($retval);
                    foreach($format as $theme) {
                        $theme->appendData($retval);
                    }
                }
                break;
                    /* }}} */

                case \XMLReader::WHITESPACE: /* {{{ */
                case \XMLReader::SIGNIFICANT_WHITESPACE:
                            /* WS is always WS */
                $retval  = $r->value;
                foreach($this as $format) {
                    $format->appendData($retval);
                    foreach($format as $theme) {
                        $theme->appendData($retval);
                    }
                }
                break;
                    /* }}} */

                case \XMLReader::PI:
                $target = $r->name;
                $data = $r->value;
                foreach ($this as $format) {
                    $format->parsePI($target, $data);
                }
                break;
            }
        }

        /* Closing time */
        foreach($this as $format) {
            $format->notify(Render::FINALIZE, true);
        }
        $r->close();

        ObjectStorage::popReader();

    } /* }}} */

}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

