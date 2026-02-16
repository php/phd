<?php
namespace phpdotnet\phd;

class Render extends ObjectStorage
{
    const int CHUNK        = 0x001;
    const int OPEN         = 0x002;
    const int CLOSE        = 0x004;
    const int STANDALONE   = 0x008;
    const int INIT         = 0x010;
    const int FINALIZE     = 0x020;
    const int VERBOSE      = 0x040;

    private   $STACK      = array();

    public function __construct() { /* {{{ */
    } /* }}} */

    public function notXPath($tag, $depth) { /* {{{ */
        do {
            if ((--$depth >= 0) && isset($tag[$this->STACK[$depth]])) {
                $tag = $tag[$this->STACK[$depth]];
            } else {
                $tag = $tag[0];
            }
        } while (is_array($tag));
        return $tag;
    } /* }}} */

    public function attach(object $object, mixed $info = array()): void { /* {{{ */
        if (!($object instanceof Format)) {
            throw new \InvalidArgumentException(
                'All formats *MUST* inherit ' . __NAMESPACE__ . '\\Format'
            );
        }
        $object->notify(Render::STANDALONE, true);

        parent::attach($object, $info);
    } /* }}} */

    public function execute(Reader $r) { /* {{{ */
        ReaderKeeper::setReader($r);

        foreach($this as $format) {
            $format->notify(Render::INIT, true);
        }

        $lastdepth = -1;
        while($r->read()) {
            $type = $r->nodeType;
            $open = false;

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
                        $attrs[$r->namespaceURI ?: Reader::XMLNS_DOCBOOK][$r->localName] = $r->value;
                    } while ($r->moveToNextAttribute());
                    $r->moveToElement();
                }

                $innerXml = "";
                if (
                    $open &&
                    (
                        $r->name === "type" ||
                        $r->name === "classsynopsis" ||
                        $r->name === "qandaset" ||
                        in_array($r->name, ["methodsynopsis", "constructorsynopsis", "destructorsynopsis", "constant", "function"], true)
                    )
                ) {
                    $innerXml = $r->readInnerXml();
                }

                $props    = array(
                    "empty"    => $r->isEmptyElement,
                    "isChunk"  => false,
                    "lang"     => $r->xmlLang,
                    "ns"       => $r->namespaceURI,
                    "sibling"  => $lastdepth >= $depth ? $this->STACK[$depth] : "",
                    "innerXml" => $innerXml,
                    "depth"    => $depth,
                );

                $this->STACK[$depth] = $name;

                if ($name == "notatag")
                    break;

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

                    // Current doc box uses PHP 7.3 so cannot use PHP 8 functions
                    //if (/* !($tag instanceof \Closure) && */ !str_starts_with($tag ?? '', "format_")) {
                    if (strncmp($tag ?? '', "format_", 7) !== 0) {
                        $data = $format->transformFromMap($open, $tag, $name, $attrs, $props);
                    } else {
                        $data = $format->{$tag}($open, $name, $attrs, $props);
                    }
                    $format->appendData($data);
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
                            $data = $format->{$tag}($value, $name);
                        } else {
                            $data = $format->TEXT($value);
                        }
                    } else {
                        $data = $format->TEXT($value);
                    }

                    if ($data === false) {
                        $format->appendData($value);
                    } else {
                        $format->appendData($data);
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
                }
                break;
                    /* }}} */

                case \XMLReader::WHITESPACE: /* {{{ */
                case \XMLReader::SIGNIFICANT_WHITESPACE:

                $retval  = $r->value;
                foreach($this as $format) {

                    if (method_exists($format, 'format_whitespace')) {
                        $retval = $format->format_whitespace($retval, $this->STACK, $r->depth);

                        if ($retval === false) {
                            continue;
                        }
                    }

                    $format->appendData($retval);
                }
                break;
                    /* }}} */

                case \XMLReader::PI:
                $target = $r->name;
                $data = $r->value;
                foreach ($this as $format) {
                    $retval = $format->parsePI($target, $data);
                    if ($retval) {
                        $format->appendData($retval);
                    }
                }
                break;
            }
        }

        /* Closing time */
        foreach($this as $format) {
            $format->notify(Render::FINALIZE, true);
        }
        $r->close();

        ReaderKeeper::popReader();

    } /* }}} */

}
