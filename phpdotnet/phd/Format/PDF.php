<?php

class PDFPhDFormat extends PhDFormat {
    protected $elementmap = array( /* {{{ */
        'abstract'              => 'format_suppressed_tags',
        'abbrev'                => 'format_suppressed_tags',
        'acronym'               => 'format_suppressed_tags',
        'alt'                   => 'format_suppressed_tags',
        'application'           => 'format_suppressed_tags',
        'author'                => array(
            /* DEFAULT */          'format_newline',
            'authorgroup'       => 'format_authorgroup_author',
        ),
        'authorgroup'           => 'format_shifted_para',
        'blockquote'            => 'format_framed_block',
        'book'                  => 'format_suppressed_tags',
        'callout'               => 'format_callout',
        'calloutlist'           => 'format_calloutlist',
        'caution'               => 'format_admonition',
        'citerefentry'          => 'format_suppressed_tags',
        'classname'             => array(
            /* DEFAULT */          'format_suppressed_tags',
            'ooclass'           => array(
                /* DEFAULT */      'format_bold',
                'classsynopsisinfo' => false,
            ),
        ),
        'co'                    => 'format_co',
        'command'               => 'format_italic',
        'computeroutput'        => 'format_suppressed_tags',
        'constant'              => 'format_bold',
        'code'                  => 'format_verbatim_inline',
        'copyright'             => 'format_copyright',
        'editor'                => 'format_editor',
        'example'               => 'format_example',
        'emphasis'              => 'format_italic',
        'envar'                 => 'format_suppressed_tags',
        'errortype'             => 'format_suppressed_tags',
        'figure'                => 'format_suppressed_tags',
        'filename'              => 'format_italic',
        'firstname'             => 'format_suppressed_tags',
        'formalpara'            => 'format_para',
        'footnote'              => 'format_footnote',
        'footnoteref'           => 'format_footnoteref',
        'funcdef'               => 'format_bold',
        'function'              => 'format_suppressed_tags',
        'glossterm'             => 'format_suppressed_tags',
        'holder'                => 'format_suppressed_tags',
        'imagedata'             => 'format_imagedata',
        'imageobject'           => 'format_shifted_para',
        'index'                 => 'format_para',
        'indexdiv'              => 'format_para',
        'indexentry'            => 'format_shifted_line',
        'info'                  => 'format_suppressed_tags',
        'informalexample'       => 'format_para',
        'itemizedlist'          => 'format_shifted_para',
        'legalnotice'           => 'format_suppressed_tags',
        'listitem'              => array(
            /* DEFAULT */          'format_listitem',
            'varlistentry'      => 'format_shifted_para',
        ),
        'literal'               => 'format_italic',
        'literallayout'         => 'format_verbatim_inline',
        'manvolnum'             => 'format_manvolnum',
        'mediaobject'           => 'format_suppressed_tags',
        'member'                => 'format_member',
        'note'                  => 'format_admonition',
        'option'                => 'format_italic',
        'optional'              => 'format_suppressed_tags',
        'orderedlist'           => 'format_shifted_para',
        'othercredit'           => 'format_newline',
        'othername'             => 'format_suppressed_tags',
        'para'                  => array(
            /* DEFAULT */          'format_para',
            'callout'           => 'format_suppressed_tags',
            'listitem'          => 'format_suppressed_tags',
            'step'              => 'format_suppressed_tags',
        ),
        'partintro'             => 'format_para',
        'personname'            => 'format_suppressed_tags',
        'preface'               => 'format_suppressed_tags',
        'primaryie'             => 'format_suppressed_tags',
        'procedure'             => 'format_procedure',
        'productname'           => 'format_suppressed_tags',
        'programlisting'        => 'format_verbatim_block',
        'property'              => array(
            /* DEFAULT */          'format_suppressed_tags',
            'classsynopsisinfo' => 'format_italic',
        ),
        'pubdate'               => 'format_para',
        'quote'                 => 'format_suppressed_tags',
        'refentrytitle'         => 'format_bold',
        'refname'               => 'format_title',
        'refnamediv'            => 'format_suppressed_tags',
        'refpurpose'            => 'format_refpurpose',
        'refsect1'              => 'format_suppressed_tags',
        'refsection'            => 'format_refsection', // DUMMY REFSECTION DELETION
        'refsynopsisdiv'        => 'format_para',
        'replaceable'           => 'format_italic',
        'screen'                => 'format_verbatim_block',
        'section'               => 'format_suppressed_tags',
        'seg'                   => 'format_seg',
        'segmentedlist'         => 'format_segmentedlist',
        'seglistitem'           => 'format_seglistitem',
        'segtitle'              => 'format_suppressed_tags',
        'set'                   => 'format_suppressed_tags',
        'simpara'               => array(
            /* DEFAULT */          'format_para',
            'callout'           => 'format_suppressed_tags',
            'listitem'          => 'format_suppressed_tags',
            'step'              => 'format_suppressed_tags',
        ),
        'simplelist'            => 'format_shifted_para',
        'subscript'             => 'format_indice',
        'superscript'           => 'format_indice',
        'surname'               => 'format_suppressed_tags',
        'synopsis'              => 'format_verbatim_block',
        'systemitem'            => 'format_verbatim_inline',
        'tag'                   => 'format_verbatim_inline',
        'term'                  => 'format_suppressed_tags',
        'title'                 => array(
            /* DEFAULT */          'format_title',
            'example'           => 'format_example_title',
            'formalpara'        => 'format_title3',
            'info'              => array(
                /* DEFAULT */      'format_title',
                'example'       => 'format_example_title',
                'note'          => 'format_title3',
                'table'         => 'format_title3',
                'informaltable' => 'format_title3',
                'warning'           => 'format_title3',
            ),
            'informaltable'     => 'format_title3',
            'legalnotice'       => 'format_title2',
            'note'              => 'format_title3',
            'preface'           => 'format_title',
            'procedure'         => 'format_bold',
            'refsect1'          => 'format_title2',
            'refsect2'          => 'format_title3',
            'refsect3'          => 'format_title3',
            'section'           => 'format_title2',
            'sect1'             => 'format_title2',
            'sect2'             => 'format_title3',
            'sect3'             => 'format_title3',
            'sect4'             => 'format_title3',
            'segmentedlist'     => 'format_bold',
            'table'             => 'format_title3',
            'variablelist'      => 'format_bold',
            'warning'           => 'format_title3',
        ),
        'tip'                   => 'format_admonition',
        'titleabbrev'           => 'format_suppressed_tags',
        'type'                  => 'format_suppressed_tags',
        'userinput'             => 'format_bold',
        'variablelist'          => 'format_suppressed_tags',
        'varlistentry'          => 'format_newline',
        'varname'               => 'format_italic',
        'warning'               => 'format_admonition',
        'xref'                  => 'format_link',
        'year'                  => 'format_suppressed_tags',
        // TABLES
        'informaltable'         => 'format_table',
        'table'                 => 'format_table',
        'tgroup'                => 'format_tgroup',
        'colspec'               => 'format_colspec',
        'spanspec'              => 'format_suppressed_tags',
        'thead'                 => 'format_thead',
        'tbody'                 => 'format_tbody',
        'row'                   => 'format_row',
        'entry'                 => array (
            /* DEFAULT */          'format_entry',
            'row'               => array(
                /* DEFAULT */      'format_entry',
                'thead'         => 'format_th_entry',
                'tfoot'         => 'format_th_entry',
                'tbody'         => 'format_entry',
            ),
        ),
        // SYNOPSISES & OO STUFF
//        'void'                  => 'format_void',
        'methodname'            => 'format_bold',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
        ),
        'interfacename'         => 'format_suppressed_tags',
        'ooclass'               => array(
            /* DEFAULT */          'format_suppressed_tags',
            'classsynopsis'     => 'format_framed_para',
        ),
        'oointerface'           => array(
            /* DEFAULT */          'format_suppressed_tags',
            'classsynopsisinfo'    => 'format_classsynopsisinfo_oointerface',
        ),
        'classsynopsis'         => 'format_classsynopsis',
        'classsynopsisinfo'     => 'format_classsynopsisinfo',
        'fieldsynopsis'         => array(
            /* DEFAULT */          'format_fieldsynopsis',
            'entry'             => 'format_para',
        ),
        'modifier'              => 'format_suppressed_tags',
        'constructorsynopsis'   => 'format_methodsynopsis',
        'destructorsynopsis'    => 'format_methodsynopsis',
        'initializer'           => 'format_initializer',
        // FAQ
        'qandaset'              => 'format_para',
        'qandaentry'            => 'format_para',
        'question'              => 'format_bold',
        'answer'                => 'format_shifted_para',

    ); /* }}} */

    protected $textmap = array(
        'function'              => 'format_function_text',
        'link'                  => 'format_link_text',
        'quote'                 => 'format_quote_text',
        'refname'               => 'format_refname_text',
        'titleabbrev'           => 'format_suppressed_text',
        'segtitle'              => 'format_segtitle_text',
        'modifier'             => array(
            /* DEFAULT */         false,
            'fieldsynopsis'    => 'format_fieldsynopsis_modifier_text',
        ),
        'methodname'           => array(
            /* DEFAULT */         false,
            'constructorsynopsis' => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'methodsynopsis'    => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'destructorsynopsis' => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
        ),
    );

    protected $lang = "";

    /* Current Chunk variables */
    protected $cchunk      = array();
    /* Default Chunk variables */
    protected $dchunk      = array(
        "xml-base"              => "",
        "refsection"            => false,
        "examplenumber"         => 0,
        "href"                  => "",
        "is-xref"               => false,
        "linkend"               => "",
        "links-to-resolve"      => array(
            /* $id => array( $target ), */
        ),
        "refname"               => "",
        "table"                 => false,
        "verbatim-block"        => false,
        "segmentedlist"         => array(
            "seglistitem"       => 0,
            "segtitle"          => array(
            ),
        ),
        "classsynopsis"         => array(
            "close"             => false,
            "classname"         => false,
        ),
        "classsynopsisinfo"     => array(
            "implements"        => false,
            "ooclass"           => false,
        ),
        "fieldsynopsis"         => array(
            "modifier"          => "public",
        ),
        "footnote"              => array(
        ),
        "tablefootnotes"        => array(
        ),
        "footrefs"              => array(),
        "co"                    => 0,
        "corefs"                => array(),
        "callouts"              => 0,
    );

    private $pdfDoc;

    public function __construct(array $IDs) {
        parent::__construct($IDs);
        $this->pdfDoc = new PdfWriter();
    }

    public function __destruct() {
        unset($this->pdfDoc);
    }

    public function getChunkInfo($info) {
        if (isset($this->cchunk[$info]))
            return $this->cchunk[$info];
        else return null;
    }

    public function setChunkInfo($info, $value) {
        $this->cchunk[$info] = $value;
    }

    public function __call($func, $args) {
        if ($args[0]) {
            trigger_error("No mapper found for '{$func}'", E_USER_WARNING);
        }
        $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 14, array(1, 0, 0)); // Helvetica 14 red
        $this->pdfDoc->appendText(($args[0] ? "<" : "</") . $args[1] . ">");
        $this->pdfDoc->revertFont();
        return "";
    }
    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        return "";
    }

    public function CDATA($str) {
        $this->pdfDoc->appendText(utf8_decode(trim($str)));
        return "";
    }

    public function TEXT($str) {
        if (isset($this->cchunk["refsection"]) && $this->cchunk["refsection"]) // DUMMY REFSECTION DELETION
            return "";

        if (isset($this->cchunk["verbatim-block"]) && $this->cchunk["verbatim-block"]) {
            $this->pdfDoc->appendText(utf8_decode($str));
            return "";
        }

        $ret = utf8_decode(trim(ereg_replace( "[ \n\t]+", ' ', $str)));
        // No whitespace if current text value begins with ',', ';', ':', '.'
        if (strncmp($ret, ",", 1) && strncmp($ret, ";", 1) && strncmp($ret, ":", 1) && strncmp($ret, ".", 1))
            $this->pdfDoc->appendText(" " . $ret);
        else $this->pdfDoc->appendText($ret);
        return "";
    }

    public function format_suppressed_tags($open, $name, $attrs) {
        /* Ignore it */
        return "";
    }

    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }

    public function getPdfDoc() {
        return $this->pdfDoc;
    }

    public function setPdfDoc($pdfDoc) {
        $this->pdfDoc = $pdfDoc;
    }

    public function newChunk() {
        $this->cchunk = $this->dchunk;
    }

    // DUMMY REFSECTION DELETION
    public function format_refsection($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["refsection"] = true;
        } else {
            $this->cchunk["refsection"] = false;
        }
        return "";
    }

    public function format_para($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PARA);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    public function format_shifted_para($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->shift();
            $this->pdfDoc->add(PdfWriter::PARA);
        } else {
            $this->pdfDoc->unshift();
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    public function format_shifted_line($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->shift();
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        } else {
            $this->pdfDoc->unshift();
        }
        return "";
    }

    public function format_title($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_title2($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE2);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_title3($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE3);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_bold($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_italic($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_ITALIC);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::ADMONITION);
            $this->pdfDoc->appendText($this->autogen($name, $props["lang"]));
            $this->pdfDoc->add(PdfWriter::ADMONITION_CONTENT);
        } else {
            $this->pdfDoc->add(PdfWriter::END_ADMONITION);
        }
        return "";
    }

    public function format_example($open, $name, $attrs, $props) {
        if ($open) {
            $this->lang = $props["lang"];
            $this->cchunk["examplenumber"]++;
            $this->pdfDoc->add(PdfWriter::ADMONITION);

        } else {
            $this->pdfDoc->add(PdfWriter::END_ADMONITION);
        }
        return "";
    }

    public function format_example_title($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            $this->pdfDoc->appendText($this->autogen("example", $this->lang) .
                $this->cchunk["examplenumber"]);
            $this->pdfDoc->add(PdfWriter::ADMONITION_CONTENT);
        } elseif ($open) {
            $this->pdfDoc->appendText($this->autogen("example", $this->lang) .
                $this->cchunk["examplenumber"] . " -");
        } else {
            $this->pdfDoc->add(PdfWriter::ADMONITION_CONTENT);
        }
        return "";
    }

    public function format_newpage($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PAGE);
        }
        return "";
    }

    public function format_verbatim_block($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["verbatim-block"] = true;
            $this->pdfDoc->add(PdfWriter::VERBATIM_BLOCK);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
            $this->cchunk["verbatim-block"] = false;
        }
        return "";
    }

    public function format_verbatim_inline($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM, 10);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_framed_block($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
        } else {
            $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK);
        }
        return "";
    }

    public function format_framed_para($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
            $this->format_para($open, $name, $attrs, $props);
        } else {
            $this->format_para($open, $name, $attrs, $props);
            $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK);
        }
        return "";
    }

    public function format_newline($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
    }

    public function format_link($open, $name, $attrs, $props) {
        if ($open && ! $props["empty"]) {
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])) {
                $this->cchunk["linkend"] = $attrs[PhDReader::XMLNS_DOCBOOK]["linkend"];
            } elseif(isset($attrs[PhDReader::XMLNS_XLINK]["href"])) {
                $this->cchunk["href"] = $attrs[PhDReader::XMLNS_XLINK]["href"];
            }
        } elseif ($open && $name == "xref" && isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])
            && $linkend = $attrs[PhDReader::XMLNS_DOCBOOK]["linkend"]) {
            $this->cchunk["linkend"] = $linkend;
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            $this->format_link_text(PhDHelper::getDescription($linkend), $name);
            $this->pdfDoc->revertFont();
            $this->cchunk["linkend"] = "";
        } elseif (!$open) {
            $this->cchunk["href"] = "";
            $this->cchunk["linkend"] = "";
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_link_text($value, $tag) {
        $value = trim(ereg_replace( "[ \n\t]+", ' ', $value));
        if (isset($this->cchunk["href"]) && $this->cchunk["href"]) {
            $this->pdfDoc->add(PdfWriter::URL_ANNOTATION, array(chr(187) . chr(160) . $value, $this->cchunk["href"])); // links with >> symbol
        } elseif (isset($this->cchunk["linkend"]) && $linkend = $this->cchunk["linkend"]) {
            $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, $value);
            if (!isset($this->cchunk["links-to-resolve"][$linkend]))
                $this->cchunk["links-to-resolve"][$linkend] = array();
            foreach ($linkAreas as $area)
                $this->cchunk["links-to-resolve"][$linkend][] = $area;
        }
        return "";
    }

    public function format_function_text($value, $tag, $display_value = null) {
        $value = trim(ereg_replace( "[ \n\t]+", ' ', $value));
        if ($display_value === null) {
            $display_value = $value;
        }

        $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $value));
        if (($linkend = $this->getRefnameLink($ref)) !== null) {
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, $display_value.($tag == "function" ? "()" : ""));
            if (!isset($this->cchunk["links-to-resolve"][$linkend]))
                $this->cchunk["links-to-resolve"][$linkend] = array();
            foreach ($linkAreas as $area)
                $this->cchunk["links-to-resolve"][$linkend][] = $area;
        } else {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
            $this->pdfDoc->appendText(" " . $display_value.($tag == "function" ? "()" : ""));
        }
        $this->pdfDoc->revertFont();
        return "";
    }

    public function format_authorgroup_author($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] !== $name) {
                $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
                $this->pdfDoc->appendText($this->autogen("by", $props["lang"]));
                $this->pdfDoc->revertFont();
            }
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    public function format_editor($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
            $this->pdfDoc->appendText($this->autogen("editedby", $props["lang"]));
            $this->pdfDoc->revertFont();
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    public function format_copyright($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PARA);
            $this->pdfDoc->appendText(utf8_decode("©"));
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    // Lists {{{
    public function format_listitem($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->add(PdfWriter::ADD_BULLET);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP, 0.5);
        }
        return "";
    }

    public function format_procedure($open, $name, $attrs, $props) {
        $this->cchunk["step"] = 0;
        return $this->format_shifted_para($open, $name, $attrs, $props);
    }

    public function format_step($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->add(PdfWriter::ADD_NUMBER_ITEM, (++$this->cchunk["step"]).".");
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP, 0.5);
        }
        return "";
    }

    public function format_member($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->add(PdfWriter::ADD_BULLET);
        }
        return "";
    }

    public function format_segmentedlist($open, $name, $attrs, $props) {
        $this->cchunk["segmentedlist"] = $this->dchunk["segmentedlist"];
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_segtitle_text($value, $tag) {
        $this->cchunk["segmentedlist"]["segtitle"][count($this->cchunk["segmentedlist"]["segtitle"])] = $value;
        return '';
    }
    public function format_seglistitem($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["segmentedlist"]["seglistitem"] = 0;
        }
        return '';
    }
    public function format_seg($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
            $this->pdfDoc->appendText($this->cchunk["segmentedlist"]["segtitle"][$this->cchunk["segmentedlist"]["seglistitem"]++].":");
            $this->pdfDoc->revertFont();
        }
        return '';
    }
    // }}} Lists

    // Tables {{{
    public function format_table($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["table"] = true;
            $this->pdfDoc->add(PdfWriter::PARA);
        } else {
            $this->cchunk["table"] = false;
            $this->pdfDoc->add(PdfWriter::END_TABLE);

            if ($this->cchunk["tablefootnotes"]) {
                $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
                $this->pdfDoc->add(PdfWriter::LINE_JUMP);
                $this->pdfDoc->appendBufferNow();
                foreach ($this->cchunk["footrefs"] as $ref)
                    foreach ($ref as $area)
                        $this->pdfDoc->resolveInternalLink($area[0], array($area[1], $area[2], $area[3], $area[4]), $this->pdfDoc->getCurrentPage());
                $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK, array(2)); // With Dash line
                $this->cchunk["tablefootnotes"] = $this->dchunk["tablefootnotes"];
            }
        }
        return "";
    }

    public function format_colspec($open, $name, $attrs) {
        if ($open) {
            PhDFormat::colspec($attrs[PhDReader::XMLNS_DOCBOOK]);
        }
        return "";
    }
    public function format_thead($open, $name, $attrs) {
        if ($open) {
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }
    public function format_tbody($open, $name, $attrs) {
        if ($open) {
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
        }
        return "";
    }
    public function format_row($open, $name, $attrs) {
        if ($open) {
            PhDFormat::initRow();
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
            $colCount = PhDFormat::getColCount();
            $this->pdfDoc->add(PdfWriter::TABLE_ROW, array($colCount, $valign));
        } else {
            $this->pdfDoc->add(PdfWriter::TABLE_END_ROW);
        }
        return "";
    }
    public function format_th_entry($open, $name, $attrs, $props) {
        $align = (isset($attrs["align"]) ? $attrs["align"] : "center");
        if ($props["empty"]) {
            $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array(1, 1, $align));
            $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
        }
        if ($open) {
            $dbattrs = PhDFormat::getColspec($attrs[PhDReader::XMLNS_DOCBOOK]);
            $align = (isset($dbattrs["align"]) ? $dbattrs["align"] : $align);
            $colspan = PhDFormat::colspan($attrs[PhDReader::XMLNS_DOCBOOK]);
            $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array($colspan, 1, $align));
        } else {
            $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
        }
        return "";
    }
    public function format_entry($open, $name, $attrs, $props) {
        $align = (isset($attrs["align"]) ? $attrs["align"] : "left");
        if ($props["empty"]) {
            $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array(1, 1, $align));
            $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
            return;
        }
        if ($open) {
            $dbattrs = PhDFormat::getColspec($attrs[PhDReader::XMLNS_DOCBOOK]);
            $align = (isset($dbattrs["align"]) ? $dbattrs["align"] : $align);
            $retval = "";
            if (isset($dbattrs["colname"])) {
                for($i=PhDFormat::getEntryOffset($dbattrs); $i>0; --$i) {
                    $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array(1, 1, $align));
                    $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
                }
            }

            /*
             * "colspan" is *not* an standard prop, only used to overwrite the
             * colspan for <footnote>s in tables
             */
            if (isset($props["colspan"])) {
                $colspan = $props["colspan"];
            } else {
                $colspan = PhDFormat::colspan($dbattrs);
            }

            $rowspan = PhDFormat::rowspan($dbattrs);
            $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array($colspan, $rowspan, $align));
        } else {
            $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
        }
        return "";
    }

    public function format_tgroup($open, $name, $attrs, $props) {
        if ($open) {
            PhDFormat::tgroup($attrs[PhDReader::XMLNS_DOCBOOK]);
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["cols"]))
                $this->pdfDoc->add(PdfWriter::TABLE, $attrs[PhDReader::XMLNS_DOCBOOK]["cols"]);
        } else {
        }
        return "";
    }
    // }}} Tables

    // Synopsises {{{
    public function format_methodsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            $this->params = array("count" => 0, "opt" => 0, "content" => "");
            return $this->format_para($open, $name, $attrs, $props);
        }
        $content = "";
        if ($this->params["opt"]) {
            $content = str_repeat(" ]", $this->params["opt"]);
        }
        $content .= " )";

        $this->pdfDoc->appendText($content);
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        $value = $this->TEXT($value);
        if ($this->cchunk["classsynopsis"]["classname"] === false) {
            $this->pdfDoc->appendText($value);
            return '';
        }
        if (strpos($value, '::')) {
            $explode = '::';
        } elseif (strpos($value, '->')) {
            $explode = '->';
        } else {
            $this->pdfDoc->appendText($value);
            return '';
        }

        list($class, $method) = explode($explode, $value);
        if ($class !== $this->cchunk["classsynopsis"]["classname"]) {
            $this->pdfDoc->appendText($value);
            return '';
        }
        $this->pdfDoc->appendText($method);
        return '';
    }

    public function format_methodparam_parameter($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM, 10);
                $this->pdfDoc->appendText(" &$");
                return '';
            }
            $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM, 10);
            $this->pdfDoc->appendText(" $");
            return '';
        }
        $this->pdfDoc->revertFont();
        return '';
    }

    public function format_parameter($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM_ITALIC, 10);
                $this->pdfDoc->appendText(" &");
                return '';
            }
            $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM_ITALIC, 10);
            return '';
        }
        $this->pdfDoc->revertFont();
        return '';
    }

    public function format_methodparam($open, $name, $attrs) {
        if ($open) {
            $content = '';
                if ($this->params["count"] == 0) {
                    $content .= " (";
                }
                if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["choice"]) && $attrs[PhDReader::XMLNS_DOCBOOK]["choice"] == "opt") {
                    $this->params["opt"]++;
                    $content .= " [";
                } else if($this->params["opt"]) {
                    $content .= str_repeat(" ]", $this->params["opt"]);
                    $this->params["opt"] = 0;
                }
                if ($this->params["count"]) {
                    $content .= ",";
                }
                $content .= '';
                ++$this->params["count"];
                $this->pdfDoc->appendText($content);
                return '';
        }
        return '';
    }

    public function format_void($open, $name, $attrs) {
        $this->pdfDoc->appendText(" ( void");
        return '';
    }

    public function format_classsynopsisinfo($open, $name, $attrs, $props) {
        $this->cchunk["classsynopsisinfo"] = $this->dchunk["classsynopsisinfo"];
        if ($open) {
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"]) && $attrs[PhDReader::XMLNS_DOCBOOK]["role"] == "comment") {
                $this->format_para($open, $name, $attrs, $props);
                $this->pdfDoc->appendText("/* ");
                return '';
            }
            return $this->format_para($open, $name, $attrs, $props);
        }

        if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"]) && $attrs[PhDReader::XMLNS_DOCBOOK]["role"] == "comment") {
            $this->pdfDoc->appendText(" */");
            return $this->format_para($open, $name, $attrs, $props);
        }
        $this->cchunk["classsynopsis"]["close"] = true;
        $this->pdfDoc->appendText(" {");
        $this->pdfDoc->shift();
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_classsynopsisinfo_oointerface($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["implements"] === false) {
                $this->cchunk["classsynopsisinfo"]["implements"] = true;
                $this->pdfDoc->appendText(" implements");
                return '';
            }
            $this->pdfDoc->appendText(",");
            return '';
        }
        return '';
    }

    public function format_classsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            return $this->format_para($open, $name, $attrs, $props);
        }

        if ($this->cchunk["classsynopsis"]["close"] === true) {
            $this->cchunk["classsynopsis"]["close"] = false;
            $this->pdfDoc->unshift();
            $this->pdfDoc->appendText("}");
        }
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_fieldsynopsis_modifier_text($value, $tag) {
        $this->cchunk["fieldsynopsis"]["modifier"] = trim($value);
        $this->pdfDoc->appendText($this->TEXT($value));
        return '';
    }

    public function format_fieldsynopsis($open, $name, $attrs, $props) {
        $this->cchunk["fieldsynopsis"] = $this->dchunk["fieldsynopsis"];
        if ($open) {
            return $this->format_para($open, $name, $attrs, $props);
        }
        $this->pdfDoc->appendText(";");
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_initializer($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->appendText(" =");
        }
        return '';
    }
    // }}} Synopsises


    // Footnotes & Callouts {{{
    public function format_footnoteref($open, $name, $attrs, $props) {
        if ($open) {
            $linkend = $attrs[PhDReader::XMLNS_DOCBOOK]["linkend"];
            $found = false;
            foreach($this->cchunk["footnote"] as $k => $note) {
                if ($note["id"] === $linkend) {
                    $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0,0,1));
                    $this->cchunk["footrefs"][] = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, "[".($k + 1)."]");
                    $this->pdfDoc->revertFont();
                }
            }
            return '';
        }
    }

    public function format_footnote($open, $name, $attrs, $props) {
        if ($open) {
            $count = count($this->cchunk["footnote"]);
            $noteid = isset($attrs[PhDReader::XMLNS_XML]["id"]) ? $attrs[PhDReader::XMLNS_XML]["id"] : $count + 1;
            $note = array("id" => $noteid, "str" => "");
            $this->cchunk["footnote"][$count] = $note;
            if ($this->cchunk["table"]) {
                $this->cchunk["tablefootnotes"][$count] = $noteid;
            }
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0,0,1));
            $this->cchunk["footrefs"][] = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, "[".($count + 1)."]");
            $this->pdfDoc->revertFont();
            $this->pdfDoc->setAppendToBuffer(true);
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD, 12, array(0,0,1));
            $this->pdfDoc->appendText("[".($count + 1)."]");
            $this->pdfDoc->revertFont();
            return "";
        }
        $this->pdfDoc->appendText("\n");
        $this->pdfDoc->setAppendToBuffer(false);
        return "";
    }

    public function format_co($open, $name, $attrs, $props) {
        if (($open || $props["empty"]) && isset($attrs[PhDReader::XMLNS_XML]["id"]) && $id = $attrs[PhDReader::XMLNS_XML]["id"]) {
            $co = ++$this->cchunk["co"];
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0,0,1));
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkends"]) && $linkends = $attrs[PhDReader::XMLNS_DOCBOOK]["linkends"]) {
                $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, "[{$co}]");
                if (!isset($this->cchunk["links-to-resolve"][$linkends]))
                    $this->cchunk["links-to-resolve"][$linkends] = array();
                foreach ($linkAreas as $area)
                    $this->cchunk["links-to-resolve"][$linkends][] = $area;
            }
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_calloutlist($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->cchunk["co"] = 0;
        } else {
            $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK, array(2)); // With Dash line
            $this->cchunk["co"] = 0;
        }
        return '';
    }

    public function format_callout($open, $name, $attrs) {
        if ($open) {
            $co = ++$this->cchunk["co"];
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD, 12, array(0,0,1));
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["arearefs"]) && $ref = $attrs[PhDReader::XMLNS_DOCBOOK]["arearefs"]) {
                $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, "[{$co}]");
                if (!isset($this->cchunk["links-to-resolve"][$ref]))
                    $this->cchunk["links-to-resolve"][$ref] = array();
                foreach ($linkAreas as $area)
                    $this->cchunk["links-to-resolve"][$ref][] = $area;
            }
            $this->pdfDoc->revertFont();
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return '';
    }
    // }}} Footnotes & Callouts

    public function format_quote_text($value, $tag) {
        $value = trim(ereg_replace( "[ \n\t]+", ' ', $value));
        $this->pdfDoc->appendText(' "'.$value.'"');
        return "";
    }

    public function format_refname_text($value, $tag) {
        $this->cchunk["refname"][] = $value;
        $this->pdfDoc->appendText(trim(ereg_replace( "[ \n\t]+", ' ', $value)));
        return "";
    }

    public function format_refpurpose($open, $tag, $attrs, $props) {
        if ($props["empty"]) {
            $this->pdfDoc->add(PdfWriter::PARA);
            foreach($this->cchunk["refname"] as $refname) {
                $this->pdfDoc->appendText(" " . $refname . " --");
            }
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->cchunk["refname"] = array();
        } elseif ($open) {
            $this->pdfDoc->add(PdfWriter::PARA);
            foreach($this->cchunk["refname"] as $refname) {
                $this->pdfDoc->appendText(" " . $refname . " --");
            }
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->cchunk["refname"] = array();
        }
        return "";
    }

    public function format_manvolnum($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->appendText(")");
            return '';
        }
        $this->pdfDoc->appendText(")");
        return '';
        return ")</span>";
    }

    public function format_indice($open, $name, $attrs) {
        if (($open && $name == "subscript") || (!$open && $name == "superscript")) {
            $this->pdfDoc->vOffset("-4");
            return '';
        }
        $this->pdfDoc->vOffset("4");
        return '';
    }

    public function format_imagedata($open, $name, $attrs, $props) {
        if ($props["empty"] && isset($this->cchunk["xml-base"]) && ($base = $this->cchunk["xml-base"]) &&
            isset($attrs[PhDReader::XMLNS_DOCBOOK]["fileref"]) && ($fileref = $attrs[PhDReader::XMLNS_DOCBOOK]["fileref"])) {
            $imagePath = PhDConfig::xml_root() . DIRECTORY_SEPARATOR . $base . $fileref;
            if (file_exists($imagePath))
                $this->pdfDoc->add(PdfWriter::IMAGE, $imagePath);

        }
        return '';
    }

}

class PdfWriter {
    // Font type constants (for setFont())
    const FONT_NORMAL = 0x01;
    const FONT_ITALIC = 0x02;
    const FONT_BOLD = 0x03;
    const FONT_VERBATIM = 0x04;
    const FONT_VERBATIM_ITALIC = 0x05;
    const FONT_MANUAL = 0x06;

    // "Objects" constants (for add())
    const PARA = 0x10;
    const INDENTED_PARA = 0x11;
    const TITLE = 0x12;
    const DRAW_LINE = 0x13;
    const LINE_JUMP = 0x14;
    const PAGE = 0x15;
    const TITLE2 = 0x16;
    const VERBATIM_BLOCK = 0x17;
    const ADMONITION = 0x18;
    const ADMONITION_CONTENT = 0x19;
    const END_ADMONITION = 0x1A;
    const URL_ANNOTATION = 0x1B;
    const LINK_ANNOTATION = 0x1C;
    const ADD_BULLET = 0x1D;
    const FRAMED_BLOCK = 0x1E;
    const END_FRAMED_BLOCK = 0x1F;
    const TITLE3 = 0x20;
    const TABLE = 0x21;
    const TABLE_ROW = 0x22;
    const TABLE_ENTRY = 0x23;
    const TABLE_END_ENTRY = 0x24;
    const END_TABLE = 0x25;
    const TABLE_END_ROW = 0x26;
    const ADD_NUMBER_ITEM = 0x27;
    const IMAGE = 0x28;

    // Page format
    const VMARGIN = 56.7; // = 1 centimeter
    const HMARGIN = 56.7; // = 1 centimeter
    const LINE_SPACING = 2; // nb of points between two lines
    const INDENT_SPACING = 10; // nb of points for indent
    const DEFAULT_SHIFT = 20; // default value (points) for shifted paragraph
    private $SCALE; // nb of points for 1 centimeter
    private $PAGE_WIDTH; // in points
    private $PAGE_HEIGHT; // in points

    private $haruDoc;
    private $pages = array();
    private $currentPage;
    private $currentPageNumber;
    private $currentBookName;

    private $currentFont;
    private $currentFontSize;
    private $currentFontColor;
    private $fonts;
    private $oldFonts = array();
    private $text;

    private $vOffset = 0;
    private $hOffset = 0;
    private $lastPage = array(
        "vOffset" => 0,
        "hOffset" => 0,
    );
    private $permanentLeftSpacing = 0;
    private $permanentRightSpacing = 0;

    private $appendToBuffer = false;
    // To append afterwards
    private $buffer = array(
        /* array(
            'text'       => "",
            'font'       => "",
            'size'       => "",
            'color'      => "",
        )*/
    );

    private $current = array(
        "leftSpacing"       => 0,
        "rightSpacing"      => 0,
        "oldVPosition"      => 0,
        "vOffset"           => 0,
        "newVOffset"        => 0,
        "pages"             => array(),
        "row"               => array(),
        "align"             => "",
        "char"              => "",
        "charOffset"        => 0,
    );

    // To temporarily store $current(s)
    private $old = array();

    function __construct($pageWidth = 210, $pageHeight = 297) {
    	// Initialization of properties
    	$this->haruDoc = new HaruDoc;
    	$this->haruDoc->addPageLabel(1, HaruPage::NUM_STYLE_DECIMAL, 1, "Page ");

        $this->haruDoc->setPageMode(HaruDoc::PAGE_MODE_USE_OUTLINE);
        $this->haruDoc->setPagesConfiguration(2);

    	// Page format
    	$this->SCALE = 72/25.4;
    	$this->PAGE_WIDTH = $pageWidth * $this->SCALE;
    	$this->PAGE_HEIGHT = $pageHeight * $this->SCALE;

    	// Set fonts
    	$this->fonts["Helvetica"] = $this->haruDoc->getFont("Helvetica", "WinAnsiEncoding");
    	$this->fonts["Helvetica-Bold"] = $this->haruDoc->getFont("Helvetica-Bold", "WinAnsiEncoding");
    	$this->fonts["Helvetica-Oblique"] = $this->haruDoc->getFont("Helvetica-Oblique", "WinAnsiEncoding");
    	$this->fonts["Courier"] = $this->haruDoc->getFont("Courier", "WinAnsiEncoding");
    	$this->fonts["Courier-Oblique"] = $this->haruDoc->getFont("Courier-Oblique", "WinAnsiEncoding");

    	// Add first page and default font settings
    	$this->currentFont = $this->fonts["Helvetica"];
    	$this->currentFontSize = 12;
    	$this->currentFontColor = array(0, 0, 0); // Black
    	$this->nextPage();
    	$this->haruDoc->addPageLabel(1, HaruPage::NUM_STYLE_DECIMAL, 1, "Page ");
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function setCompressionMode($mode) {
        $this->haruDoc->setCompressionMode($mode);
    }

    // Append text into the current position
    public function appendText($text) {
//        if ($this->vOffset > $this->current["charOffset"] + 3*LINE_SPACING + 3*$this->currentFontSize)
//            $this->vOffset = $this->current["charOffset"] + 3*LINE_SPACING + 3*$this->currentFontSize;
        if ($this->appendToBuffer) {
            array_push($this->buffer, array(
                "text" => $text,
                "font" => $this->currentFont,
                "size" => $this->currentFontSize,
                "color" => $this->currentFontColor
            ));
            return;
        }

        $this->currentPage->beginText();
        do {
            // Clear the whitespace if it begins the line or if last char is a special char
            if (strpos($text, " ") === 0 && ($this->hOffset == 0 || in_array($this->current["char"], array("&", "$")))) {
                $text = substr($text, 1);
            }

            // Number of chars allowed in the current line
            $nbCarac = $this->currentFont->measureText($text,
                ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                $this->currentFontSize, $this->currentPage->getCharSpace(),
                $this->currentPage->getWordSpace(), true);

            // If a the text content can't be appended (either there is no whitespaces,
            // either the is not enough space in the line)
            if ($nbCarac === 0) {
                $isEnoughSpaceOnNextLine = $this->currentFont->measureText($text,
                    ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                    $this->currentFontSize, $this->currentPage->getCharSpace(),
                    $this->currentPage->getWordSpace(), true);
                if ($isEnoughSpaceOnNextLine) {
                    $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
                    $this->hOffset = 0;
                    $isLastLine = false;
                    continue;
                } else {
                    $nbCarac = $this->currentFont->measureText($text,
                        ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                        $this->currentFontSize, $this->currentPage->getCharSpace(),
                        $this->currentPage->getWordSpace(), false);
                }
            }
            $isLastLine = ($nbCarac == strlen($text));

            $textToAppend = substr($text, 0, $nbCarac);
            $text = substr($text, $nbCarac);

            // Append text (in a new page if needed) with align
            if ($this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset) < self::VMARGIN) {
                $this->currentPage->endText();
                $this->current["pages"][] = $this->currentPage;
                $this->nextPage();
                $this->currentPage->beginText();
            }
            if ($this->current["align"] == "center") {
                $spacing = $this->PAGE_WIDTH - 2*self::HMARGIN -
                    $this->permanentLeftSpacing - $this->permanentRightSpacing - $this->currentPage->getTextWidth($textToAppend);
                $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing + $spacing/2,
                    $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
            } elseif ($this->current["align"] == "right") {
                $spacing = $this->PAGE_WIDTH - 2*self::HMARGIN -
                    $this->permanentLeftSpacing - $this->permanentRightSpacing - $this->currentPage->getTextWidth($textToAppend);
                $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing + $spacing,
                    $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
            } else { // left
                $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing,
                    $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
            }
            if ($textToAppend)
                $this->current["char"] = $textToAppend{strlen($textToAppend)-1};

            // Offsets for next line
            if (!$isLastLine) {
                $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
                $this->hOffset = 0;
            } else {
                $this->hOffset += $this->currentPage->getTextWidth($textToAppend);
            }

        }
        while(!$isLastLine); // While it remains chars to append
        $this->currentPage->endText();
        $this->current["charOffset"] = $this->vOffset;
    }

    // Same function one line at a time
    public function appendOneLine($text) {
        if (strpos($text, " ") === 0 && ($this->hOffset == 0 || in_array($this->current["char"], array("&", "$")))) {
            $text = substr($text, 1);
        }

        $this->currentPage->beginText();
        $nbCarac = $this->currentFont->measureText($text,
            ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing),
            $this->currentFontSize, $this->currentPage->getCharSpace(),
            $this->currentPage->getWordSpace(), true);

        // If a the text content can't be appended (either there is no whitespaces,
        // either the is not enough space in the line)
        if ($nbCarac === 0) {
            $isEnoughSpaceOnNextLine = $this->currentFont->measureText($text,
                ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                $this->currentFontSize, $this->currentPage->getCharSpace(),
                $this->currentPage->getWordSpace(), true);
            if ($isEnoughSpaceOnNextLine) {
                $this->currentPage->endText();
                return $text;
            } else {
                $nbCarac = $this->currentFont->measureText($text,
                    ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                    $this->currentFontSize, $this->currentPage->getCharSpace(),
                    $this->currentPage->getWordSpace(), false);
            }
        }

        $isLastLine = ($nbCarac == strlen($text));

        $textToAppend = substr($text, 0, $nbCarac);
        $text = substr($text, $nbCarac);

        // Append text (in a new page if needed)
        if ($this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset) < self::VMARGIN) {
            $this->currentPage->endText();
            $this->current["pages"][] = $this->currentPage;
            $this->nextPage();
            $this->currentPage->beginText();
        }
        $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
        if ($textToAppend)
            $this->current["char"] = $textToAppend{strlen($textToAppend)-1};

        $this->hOffset += $this->currentPage->getTextWidth($textToAppend);

        $this->currentPage->endText();
        $this->current["charOffset"] = $this->vOffset;

        return ($isLastLine ? null : $text);
    }

    public function setAppendToBuffer($appendToBuffer) {
        $this->appendToBuffer = $appendToBuffer;
    }

    public function appendBufferNow() {
        foreach($this->buffer as $row) {
            if ($row["text"] == "\n") {
                $this->lineJump();
            } else {
                $this->setFont(self::FONT_MANUAL, $row["size"], $row["color"], $row["font"]);
                $this->appendText($row["text"]);
                $this->revertFont();
            }
        }
        $this->buffer = array();
    }

    public function add($type, $option = null) {
        if ($this->appendToBuffer) return;
        switch ($type) {
            case self::INDENTED_PARA:
                $this->lineJump();
                $this->indent();
                break;
            case self::PARA:
                $this->lineJump();
                break;
            case self::VERBATIM_BLOCK:
                $this->setFont(self::FONT_VERBATIM, 10, array(0.3, 0.3, 0.3));
                $this->lineJump();
                break;
            case self::TITLE:
                $this->setFont(self::FONT_BOLD, 20);
                $this->lineJump();
                break;
            case self::TITLE2:
                $this->setFont(self::FONT_BOLD, 14);
                $this->lineJump();
                break;
            case self::TITLE3:
                $this->setFont(self::FONT_BOLD, 12);
                $this->lineJump();
                break;
            case self::DRAW_LINE:
                $this->traceLine($option);
                break;
            case self::LINE_JUMP:
                if ($option)
                    $this->lineJump($option);
                else $this->lineJump();
                break;
            case self::PAGE:
                $this->nextPage();
                break;
            case self::ADMONITION:
                $this->beginAdmonition();
                break;
            case self::ADMONITION_CONTENT:
                $this->admonitionContent();
                break;
            case self::END_ADMONITION:
                $this->endAdmonition();
                break;
            case self::URL_ANNOTATION:
                $this->appendUrlAnnotation($option[0], $option[1]);
                break;
            case self::LINK_ANNOTATION:
                return $this->prepareInternalLinkAnnotation($option);
                break;
            case self::ADD_BULLET:
                $this->indent(-self::INDENT_SPACING-$this->currentPage->getTextWidth(chr(149)));
                $this->appendText(chr(149)); // ANSI Bullet
                $this->indent(0);
                break;
            case self::ADD_NUMBER_ITEM:
                $this->indent(-self::INDENT_SPACING-$this->currentPage->getTextWidth($option));
                $this->appendText($option);
                $this->indent(0);
                break;
            case self::FRAMED_BLOCK:
                $this->beginFrame();
                break;
            case self::END_FRAMED_BLOCK:
                $this->endFrame($option);
                break;
            case self::TABLE:
                $this->addTable($option);
                break;
            case self::END_TABLE:
                $this->endTable();
                break;
            case self::TABLE_ROW:
                $this->newTableRow($option[0], $option[1]);
                break;
            case self::TABLE_END_ROW:
                $this->endTableRow();
                break;
            case self::TABLE_ENTRY:
                $this->beginTableEntry($option[0], $option[1], $option[2]);
                break;
            case self::TABLE_END_ENTRY:
                $this->endTableEntry();
                break;
            case self::IMAGE:
                $this->addImage($option);
                break;
            default:
                trigger_error("Unknown object type : {$type}", E_USER_WARNING);
                break;
        }
    }

    // Switch font on-the-fly
    public function setFont($type, $size = null, $color = null, $font = null) {
        if ($this->currentPage == null)
            return false;
        $this->oldFonts[] = array($this->currentFont, $this->currentFontSize, $this->currentFontColor);
        $this->currentFontSize = ($size ? $size : $this->currentFontSize);
        if ($color && count($color) === 3) {
            $this->setColor($color[0], $color[1], $color[2]);
            $this->currentFontColor = $color;
        }
        else
            $this->setColor($this->currentFontColor[0], $this->currentFontColor[1], $this->currentFontColor[2]);
        switch ($type) {
            case self::FONT_NORMAL:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica"],
                    $this->currentFontSize);
                break;
            case self::FONT_ITALIC:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica-Oblique"],
                    $this->currentFontSize);
                break;
            case self::FONT_BOLD:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica-Bold"],
                    $this->currentFontSize);
                break;
            case self::FONT_VERBATIM:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Courier"],
                    $this->currentFontSize);
                break;
            case self::FONT_VERBATIM_ITALIC:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Courier-Oblique"],
                    $this->currentFontSize);
                break;
            case self::FONT_MANUAL:
                $this->currentPage->setFontAndSize($this->currentFont = $font, $this->currentFontSize);
                break;
            default:
                trigger_error("Unknown font type : {$type}", E_USER_WARNING);
                break;
        }
    }

    // Back to the last used font
    public function revertFont() {
        $lastFont = array_pop($this->oldFonts);
        $this->currentFont = $lastFont[0];
        $this->currentFontSize = $lastFont[1];
        $this->currentFontColor = $lastFont[2];
        $this->currentPage->setFontAndSize($lastFont[0], $lastFont[1]);
        $this->setColor($lastFont[2][0], $lastFont[2][1], $lastFont[2][2]);
    }

    // Change font color (1, 1, 1 = white, 0, 0, 0 = black)
    public function setColor($r, $g, $b) {
        if ($r < 0 || $r > 1 || $g < 0 || $g > 1 || $b < 0 || $b > 1)
            return false;
        $this->currentPage->setRGBStroke($r, $g, $b);
        $this->currentPage->setRGBFill($r, $g, $b);
        $this->currentFontColor = array($r, $g, $b);
        return true;
    }

    // Save the current PDF Document to a file
    public function saveToFile($filename) {
        $this->haruDoc->save($filename);
    }

    public function createOutline($description, $parentOutline = null, $opened = false) {
        $outline = $this->haruDoc->createOutline($description, $parentOutline);
        $dest = $this->currentPage->createDestination();
        $dest->setXYZ(0, $this->currentPage->getHeight(), 1);
        $outline->setDestination($dest);
        $outline->setOpened($opened);
        return $outline;
    }

    public function shift($offset = self::DEFAULT_SHIFT) {
        $this->permanentLeftSpacing += $offset;
    }

    public function unshift($offset = self::DEFAULT_SHIFT) {
        $this->permanentLeftSpacing -= $offset;
    }

    public function vOffset($offset) {
        $this->vOffset += $offset;
    }

    private function indent($offset = self::INDENT_SPACING) {
        $this->hOffset = $offset;
    }

    // Jump to next page (or create a new one if none exists)
    private function nextPage() {
        $this->lastPage = array(
            "vOffset" => $this->vOffset,
            "hOffset" => $this->hOffset,
        );
        $footerToAppend = false;
        $this->currentPageNumber++;
        if (isset($this->pages[$this->currentPageNumber])) {
            $this->currentPage = $this->pages[$this->currentPageNumber];
            $this->vOffset = $this->currentFontSize;
            $this->hOffset = 0;
        } else {
            $this->pages[$this->currentPageNumber] = $this->haruDoc->addPage();
            $this->currentPage = $this->pages[$this->currentPageNumber];
            $this->currentPage->setTextRenderingMode(HaruPage::FILL);
            $this->vOffset = $this->currentFontSize;
            $this->hOffset = ($this->hOffset ? $this->hOffset : 0);
            $footerToAppend = true;
        }
        if ($this->currentFont && $this->currentFontSize && $this->currentFontColor) {
            $this->currentPage->setFontAndSize($this->currentFont, $this->currentFontSize);
            $this->setColor($this->currentFontColor[0], $this->currentFontColor[1], $this->currentFontColor[2]);
        }
        if ($footerToAppend && $this->currentPageNumber > 1) {
            $this->currentPage->beginText();
            $this->setFont(self::FONT_NORMAL, 12, array(0,0,0));
            $this->currentPage->textOut($this->PAGE_WIDTH - self::HMARGIN - $this->currentPage->getTextWidth($this->currentPageNumber),
                self::VMARGIN - 30, $this->currentPageNumber);
            $this->revertFont();
            $this->setFont(self::FONT_BOLD, 12, array(0,0,0));
            $this->currentPage->textOut(self::HMARGIN,
                self::VMARGIN - 30, $this->currentBookName);
            $this->revertFont();
            $this->currentPage->endText();

        }

    }

    public function setCurrentBookName($currentBookName) {
        $this->currentBookName = $currentBookName;
    }

    // Set last page as the current page
    private function lastPage() {
        $this->currentPageNumber--;
        $this->currentPage = $this->pages[$this->currentPageNumber];
        $this->vOffset = $this->lastPage["vOffset"];
        $this->hOffset = $this->lastPage["hOffset"];
    }

    // Returns true if a next page exists
    private function isNextPage() {
        return isset($this->pages[$this->currentPageNumber + 1]);
    }

    // Jump a line
    private function lineJump($nbLines = 1) {
        $this->vOffset += $nbLines * ($this->currentFontSize + self::LINE_SPACING);
        $this->hOffset = 0;
    }

    // Trace a line from the current position
    private function traceLine() {
        $this->lineJump();
        $this->currentPage->rectangle(self::HMARGIN + $this->hOffset, $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset, $this->PAGE_WIDTH - 2*$this->hOffset - 2*self::HMARGIN, 1);
        $this->currentPage->stroke();
    }

    private function beginAdmonition() {
        // If this admonition is inside another frame
        array_push($this->old, $this->current);

        $this->setFont(self::FONT_BOLD, 12);
        $this->lineJump();
        // If no space for admonition title + interleave + admonition first line on this page, then creates a new one
        if (($this->PAGE_HEIGHT - 2*self::VMARGIN - $this->vOffset) < (3*$this->currentFontSize + 3*self::LINE_SPACING))
            $this->nextPage();
        $this->current["vOffset"] = $this->vOffset;
        $this->lineJump();
        $this->permanentLeftSpacing += self::INDENT_SPACING;
        $this->permanentRightSpacing += self::INDENT_SPACING;
        $this->current["pages"] = array();
    }

    private function admonitionContent() {
        if ($this->current["pages"])
            $this->current["vOffset"] = 0;
        $this->beginFrame();
        $this->revertFont();
        $this->currentPage->rectangle(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset,
            $this->PAGE_WIDTH - 2*self::HMARGIN - ($this->permanentLeftSpacing - self::INDENT_SPACING) - ($this->permanentRightSpacing - self::INDENT_SPACING),
            $this->vOffset - $this->current["vOffset"]);
        $this->currentPage->stroke();
    }

    private function endAdmonition() {
        $this->endFrame();
        $this->permanentLeftSpacing -= self::INDENT_SPACING;
        $this->permanentRightSpacing -= self::INDENT_SPACING;
        $current = array_pop($this->old);
        $current["pages"] = array_merge($current["pages"], $this->current["pages"]);
        $this->current = $current;
    }

    private function beginFrame() {
        $this->lineJump();
        $this->current["newVOffset"] = $this->vOffset;
        $this->current["pages"] = array();
    }

    private function endFrame($dash = null) {
        $onSinglePage = true;
        foreach ($this->current["pages"] as $page) {
            $page->setRGBStroke(0, 0, 0);
            $page->setLineWidth(1.0);
            $page->setDash($dash, 0);
            // left border
            $page->moveTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
                self::VMARGIN);
            $page->lineTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
                $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
            // right border
            $page->moveTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
                self::VMARGIN);
            $page->lineTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
                $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
            $page->stroke();
            $page->setDash(null, 0);
            $this->current["newVOffset"] = 0;
            $onSinglePage = false;
        }
        $this->currentPage->setRGBStroke(0, 0, 0);
        $this->currentPage->setLineWidth(1.0);
        $this->currentPage->setDash($dash, 0);
        // left border
        $this->currentPage->moveTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->lineTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
        // right border
        $this->currentPage->moveTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
        // bottom border
        $this->currentPage->moveTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        // top border (if frame's on a single page)
        if ($onSinglePage) {
            $this->currentPage->moveTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
                $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
            $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
                $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
        }

        $this->currentPage->stroke();
        $this->lineJump();
        $this->currentPage->setDash(null, 0);
        $this->current["oldVPosition"] = 0;
    }

    // Append $text with an underlined blue style with a link to $url
    private function appendUrlAnnotation($text, $url) {
        $this->appendText(" ");
        $fromHOffset = $this->hOffset;

        // If more than one text line to append
        while ($text = $this->appendOneLine($text)) {
            // Trace the underline
            $this->currentPage->setLineWidth(1.0);
            $this->currentPage->setDash(null, 0);
            $this->currentPage->moveTo(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
            $this->currentPage->lineTo(self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
            $this->currentPage->stroke();

            // Create link
            $annotationArea = array(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));
            $this->currentPage->createURLAnnotation($annotationArea, $url)->setBorderStyle(0, 0, 0);

            // Prepare the next line
            $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
            $this->hOffset = 0;
            $fromHOffset = $this->hOffset;
        }

        // Trace the underline
        $this->currentPage->setLineWidth(1.0);
        $this->currentPage->setDash(null, 0);
        $this->currentPage->moveTo(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
        $this->currentPage->lineTo(self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
        $this->currentPage->stroke();

        // Create link
        $annotationArea = array(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
            self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));
        $this->currentPage->createURLAnnotation($annotationArea, $url)->setBorderStyle(0, 0, 0);

    }

    // Append $text with an underlined blue style and prepare an internal link (which will be resolved later)
    private function prepareInternalLinkAnnotation($text) {
        $this->appendText(" ");
        $fromHOffset = $this->hOffset;
        $linkAreas = array(/* page, left, bottom, right, top */);

        // If more than one text line to append
        while ($text = $this->appendOneLine($text)) {
           // Create link
            $linkAreas[] = array($this->currentPage,
                self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));

            // Prepare the next line
            $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
            $this->hOffset = 0;
            $fromHOffset = $this->hOffset;
        }

        // Prepare link
        $linkAreas[] = array($this->currentPage,
                self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));

        return $linkAreas;
    }

    public function resolveInternalLink($page, $rectangle, $destPage) {
        $page->setRGBStroke(0, 0, 1); // blue
        // Trace the underline
        $page->setLineWidth(1.0);
        $page->setDash(array(2), 1);
        $page->moveTo($rectangle[0], $rectangle[1]);
        $page->lineTo($rectangle[2], $rectangle[1]);
        $page->stroke();
        // Create link
        $page->createLinkAnnotation($rectangle, $destPage->createDestination())
            ->setBorderStyle(0, 0, 0);
        $page->setDash(null, 0);
    }

    public function addTable($colCount) {
        // If this table is inside another table or frame
        array_push($this->old, $this->current);
        $this->current["leftSpacing"] = $this->permanentLeftSpacing;
        $this->current["rightSpacing"] = $this->permanentRightSpacing;
        // First horizontal line
        $this->currentPage->moveTo(self::HMARGIN + $this->current["leftSpacing"], $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - $this->current["rightSpacing"], $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->stroke();
    }

    public function endTable() {
        $this->permanentLeftSpacing = $this->current["leftSpacing"];
        $this->permanentRightSpacing = $this->current["rightSpacing"];
        $this->lineJump();
        $current = array_pop($this->old);
        $current["pages"] = array_merge($current["pages"], $this->current["pages"]);
        $this->current = $current;
    }

    public function newTableRow($colCount, $valign) {
        $this->current["vOffset"] = $this->vOffset;
        $this->current["row"]["cellCount"] = $colCount;
        $this->current["row"]["activeCell"] = 0;
        $this->current["row"]["hSize"] = ($this->PAGE_WIDTH - 2*self::HMARGIN -
            $this->current["leftSpacing"] - $this->current["rightSpacing"]) / $this->current["row"]["cellCount"];
        $this->current["row"]["vPosition"] = 0;
        $this->current["row"]["pages"] = array();
        $this->current["row"]["cutPolicy"] = array(1);
        $this->current["pages"] = array();
    }

    public function beginTableEntry($colspan, $rowspan, $align) {
        $this->permanentLeftSpacing = ($this->current["row"]["activeCell"]++) * $this->current["row"]["hSize"] +
            self::LINE_SPACING + $this->current["leftSpacing"];
        $this->permanentRightSpacing = $this->PAGE_WIDTH - 2*self::HMARGIN -
            ($this->current["row"]["activeCell"] + $colspan - 1) * $this->current["row"]["hSize"] -
            $this->current["leftSpacing"] + self::LINE_SPACING;

        foreach ($this->current["pages"] as $page) {
            $this->lastPage();
        }
        $this->current["pages"] = array();

        $this->hOffset = 0;
        $this->vOffset = $this->current["vOffset"] + $this->currentFontSize + self::LINE_SPACING;
        $this->current["align"] = $align;

        array_push($this->current["row"]["cutPolicy"], $colspan);
    }

    public function endTableEntry() {
        $this->current["align"] = "";
        $newOffset = $this->vOffset + $this->currentFontSize + self::LINE_SPACING;
        if ($newOffset + $this->PAGE_HEIGHT * count($this->current["pages"]) > $this->current["row"]["vPosition"]) {
            $this->current["row"]["vPosition"] = $newOffset + $this->PAGE_HEIGHT * count($this->current["pages"]);
        }
        if (count($this->current["pages"]) > count($this->current["row"]["pages"])) {
            $this->current["row"]["pages"] = $this->current["pages"];
        }
    }

    public function endTableRow() {
        $vOffset = $this->current["vOffset"];
        while($this->isNextPage())
            $this->nextPage();
        // Vertical lines
        for ($i = 0, $x = self::HMARGIN + $this->current["leftSpacing"]; $i <= $this->current["row"]["cellCount"]; $i++, $x += $this->current["row"]["hSize"]) {

            // Don't trace vertical line if colspan
            if (($cellCount = array_shift($this->current["row"]["cutPolicy"])) > 1) {
                array_unshift($this->current["row"]["cutPolicy"], $cellCount - 1);
                continue;
            }

            foreach ($this->current["row"]["pages"] as $page) {
                $page->setRGBStroke(0, 0, 0);
                $page->moveTo($x, self::VMARGIN);
                $page->lineTo($x, $this->PAGE_HEIGHT - self::VMARGIN - $this->current["vOffset"]);
                $page->stroke();
                $this->current["vOffset"] = 0;
            }

            $this->currentPage->moveTo($x, $this->PAGE_HEIGHT - self::VMARGIN - ($this->current["vOffset"]));
            $this->currentPage->lineTo($x, $this->PAGE_HEIGHT - self::VMARGIN - ($this->current["row"]["vPosition"] % $this->PAGE_HEIGHT));
            $this->currentPage->stroke();
            $this->current["vOffset"] = $vOffset;
        }
        // Horizontal line
        $this->currentPage->moveTo(self::HMARGIN + $this->current["leftSpacing"], $this->PAGE_HEIGHT - self::VMARGIN - ($this->current["row"]["vPosition"] % $this->PAGE_HEIGHT));
        $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - $this->current["rightSpacing"],
            $this->PAGE_HEIGHT - self::VMARGIN - ($this->current["row"]["vPosition"] % $this->PAGE_HEIGHT));
        $this->currentPage->stroke();

        // Store position
        $this->vOffset = $this->current["row"]["vPosition"] % $this->PAGE_HEIGHT;

        // Store pages
        $last = array_pop($this->old);
        $last["pages"] = array_merge($last["pages"], $this->current["row"]["pages"]);
        array_push($this->old, $last);

        // Erase current properties
        $this->current["row"] = array();
    }

    private function endsWith($str, $sub) {
        return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
    }

    private function addImage($url) {
        $image = null;
        if ($this->endsWith(strtolower($url), ".png")) {
            $image = $this->haruDoc->loadPNG($url);
        } elseif ($this->endsWith(strtolower($url), ".jpg") || $this->endsWith(strtolower($url), ".jpeg")) {
            $image = $this->haruDoc->loadJPEG($url);
        }
        if ($image) {
            if ($this->PAGE_HEIGHT - $this->vOffset - 2*self::VMARGIN < $image->getHeight())
                $this->nextPage();
            $this->currentPage->drawImage($image,
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - self::HMARGIN - $this->vOffset - $image->getHeight(),
                $image->getWidth(),
                $image->getHeight());

            $this->hOffset = 0;
            $this->vOffset += $image->getWidth();
        }
    }
}

?>