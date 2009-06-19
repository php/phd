<?php
namespace phpdotnet\phd;
/*  $Id$ */

class Format_XHTML extends Format
{
    protected $elementmap = array( /* {{{ */
        'abstract'              => 'format_div', /* Docbook-xsl prints "abstract"... */
        'abbrev'                => 'abbr',
        'acronym'               => 'acronym',
        'affiliation'           => 'format_suppressed_tags',
        'alt'                   => 'format_suppressed_tags',
        'arg'                   => 'format_suppressed_tags',
        'article'               => 'format_container_chunk',
        'author'                => array(
            /* DEFAULT */          'format_author',
            'authorgroup'       => 'format_authorgroup_author',
        ),
        'authorgroup'           => 'format_div',
        'appendix'              => 'format_container_chunk',
        'application'           => 'span',
        'blockquote'            => 'blockquote',
        'bibliography'          => array(
            /* DEFAULT */          'format_div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'book'                  => 'format_container_chunk',
        'chapter'               => 'format_container_chunk',
        'citetitle'             => 'i',
        'cmdsynopsis'           => 'format_cmdsynopsis',
        'co'                    => 'format_co',
        'colophon'              => 'format_chunk',
        'copyright'             => 'format_copyright',
        'date'                  => 'p',
        'editor'                => 'format_editor',
        'email'                 => 'format_suppressed_tags',
        'firstname'             => 'format_name',
        'footnote'              => 'format_footnote',
        'footnoteref'           => 'format_footnoteref',
        'funcdef'               => 'format_suppressed_tags',
        'funcsynopsis'          => 'format_div',
        'funcsynopsisinfo'      => 'pre',
        'function'              => 'span',
        'funcprototype'         => 'code',
        'surname'               => 'format_name',
        'othername'             => 'format_name',
        'optional'              => 'span',
        'honorific'             => 'span',
        'glossary'              => array(
            /* DEFAULT */          'format_div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'calloutlist'           => 'format_calloutlist',
        'callout'               => 'format_callout',
        'caution'               => 'format_admonition',
        'citation'              => 'format_citation',
        'citerefentry'          => 'span',
        'classname'             => array(
            /* DEFAULT */          'span',
            'ooclass'           => array(
                /* DEFAULT */      'b',
                'classsynopsisinfo' => 'format_classsynopsisinfo_ooclass_classname',
            ),
        ),
        'classsynopsis'         => 'format_classsynopsis',
        'classsynopsisinfo'     => 'format_classsynopsisinfo',
        'code'                  => 'code',
        'collab'                => 'span',
        'collabname'            => 'span',
        'contrib'               => 'format_suppressed_tags',
        'colspec'               => 'format_colspec',
        'command'               => 'strong',
        'computeroutput'        => 'span',
        /* FIXME: This is one crazy stupid workaround for footnotes */
        'constant'              => array(
            /* DEFAULT */          'format_constant',
            'para'              => array(
                /* DEFAULT */      'format_constant',
                'footnote'      => 'format_footnote_constant',
            ),
        ),
        'constructorsynopsis'   => 'format_methodsynopsis',
        'destructorsynopsis'    => 'format_methodsynopsis',
        'emphasis'              => 'em',
        'enumname'              => 'span',
        'entry'                 => array (
            /* DEFAULT */          'format_entry',
            'row'               => array(
                /* DEFAULT */      'format_entry',
                'thead'         => 'format_th_entry',
                'tfoot'         => 'format_th_entry',
                'tbody'         => 'format_entry',
            ),
        ),
        'envar'                 => 'span',
        'errortype'             => 'span',
        'errorcode'             => 'span',
        'example'               => 'format_div',
        'formalpara'            => 'p',
        'fieldsynopsis'         => array(
            /* DEFAULT */          'format_fieldsynopsis',
            'entry'             => 'format_div',
        ),
        'figure'                => 'format_div',
        'filename'              => 'var',
        'glossentry'            => 'li',
        'glossdef'              => 'p',
        'glosslist'             => 'format_itemizedlist',
        'glossterm'             => 'span',
        'holder'                => 'span',
        'imageobject'           => 'format_div',
        'important'             => 'format_admonition',
        'index'                 => array(
            /* DEFAULT */          'format_div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'info'                  => array(
            /* DEFAULT */         'format_div',
            'note'              => 'span',
        ),
        'informalexample'       => 'format_div',
        'informaltable'         => 'format_table',
        'indexdiv'              => 'format_dl',
        'indexentry'            => 'dd',
        'initializer'           => 'format_initializer',
        'itemizedlist'          => 'format_itemizedlist',
        'legalnotice'           => 'format_legalnotice_chunk',
        'listitem'              => array(
            /* DEFAULT */          'li',
            'varlistentry'      => 'format_varlistentry_listitem',
        ),
        'link'                  => 'a',
        'literal'               => 'format_literal',
        'literallayout'         => 'pre',
        'manvolnum'             => 'format_manvolnum',
        'mediaobject'           => 'format_mediaobject',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'methodname'            => 'format_methodname',
        'member'                => 'li',
        'modifier'              => 'span',
        'note'                  => 'format_note',
        'orgname'               => 'span',
        'othercredit'           => 'format_div',
        'ooclass'               => array(
            /* DEFAULT */          'span',
            'classsynopsis'     => 'format_div',
        ),
        'oointerface'           => array(
            /* DEFAULT */          'span',
            'classsynopsisinfo'    => 'format_classsynopsisinfo_oointerface',
        ),
        'interfacename'         => 'span',
        'option'                => 'span',
        'orderedlist'           => 'format_orderedlist',
        'para'                  => array(
            /* DEFAULT */          'format_para',
            'example'           => 'format_example_content',
            'note'              => 'format_note_content',
            'footnote'          => 'format_footnote_para',
            'refsect1'          => 'format_refsect1_para',
        ),
        'paramdef'              => 'format_suppressed_tags',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
        ),
        'part'                  => 'format_container_chunk',
        'partintro'             => 'format_div',
        'personname'            => 'format_personname',
        'personblurb'           => 'format_div',
        'phrase'                => 'span',
        'preface'               => 'format_chunk',
        'primaryie'             => 'format_suppressed_tags',
        'procedure'             => 'format_procedure',
        'productname'           => 'span',
        'programlisting'        => 'format_programlisting',
        'prompt'                => 'span',
        'propname'              => 'span',
        'property'              => array(
            /* DEFAULT */          'span',
            'classsynopsisinfo' => 'format_varname',
        ),
        'proptype'              => 'span',
        'pubdate'               => 'format_div', /* Docbook-XSL prints "published" */
        /* hack for phpdoc:varentry */
        'phpdoc:varentry'       => 'format_varentry_chunk',
        'refentry'              => 'format_chunk',
        'refentrytitle'         => 'span',
        'reference'             => 'format_container_chunk',
        'refsect1'              => 'format_refsect',
        'refsect2'              => 'format_refsect',
        'refsect3'              => 'format_refsect',
        'refsynopsisdiv'        => 'format_div',
        'refname'               => 'h1',
        'refnamediv'            => 'format_div',
        'releaseinfo'           => 'format_div',
        'replaceable'           => 'span',
        'row'                   => 'format_row',
        'screen'                => 'format_screen',
        'screenshot'            => 'div',
        'sect1'                 => 'format_chunk',
        'sect2'                 => 'format_chunk',
        'sect3'                 => 'format_chunk',
        'sect4'                 => 'format_chunk',
        'sect5'                 => 'format_chunk',
        'section'               => 'format_chunk',
        'seg'                   => 'format_seg',
        'segmentedlist'         => 'format_segmentedlist',
        'seglistitem'           => 'format_seglistitem',
        'segtitle'              => 'format_suppressed_tags',
        'set'                   => 'format_chunk',
        'setindex'              => 'format_chunk',
        'shortaffil'            => 'format_suppressed_tags',
        'simplelist'            => 'format_itemizedlist', /* FIXME: simplelists has few attributes that need to be implemented */
        'simplesect'            => 'format_div',
        'simpara'               => array(
            /* DEFAULT */          'format_para',
            'note'              => 'span',
            'listitem'          => 'span',
            'entry'             => 'span',
            'example'           => 'format_example_content',
        ),
        'spanspec'              => 'format_suppressed_tags',
        'step'                  => 'format_step',
        'superscript'           => 'sup',
        'subscript'             => 'sub',
        'systemitem'            => 'format_systemitem',
        'symbol'                => 'span',
        'synopsis'              => 'pre',
        'tag'                   => 'format_tag',
        'table'                 => 'format_table',
        'term'                  => 'format_term',
        'tfoot'                 => 'format_th',
        'tbody'                 => 'format_tbody',
        'thead'                 => 'format_th',
        'tgroup'                => 'format_tgroup',
        'tip'                   => 'format_admonition',
        'title'                 => array(
            /* DEFAULT */          'h1',
            'example'           => 'format_bold_paragraph',
            'formalpara'        => 'h5',
            'info'              => array(
                /* DEFAULT */      'h1',
                'example'       => 'format_bold_paragraph',
                'note'          => 'format_note_title',
                'table'         => 'format_table_title',
                'informaltable' => 'format_table_title',
            ),
            'indexdiv'          => 'dt',
            'legalnotice'       => 'h4',
            'note'              => 'format_note_title',
            'phd:toc'           => 'strong',
            'procedure'         => 'b',
            'refsect1'          => 'h3',
            'refsect2'          => 'h4',
            'refsect3'          => 'h5',
            'section'           => 'h2',
            'sect1'             => 'h2',
            'sect2'             => 'h3',
            'sect3'             => 'h4',
            'sect4'             => 'h5',
            'segmentedlist'     => 'strong',
            'simplesect'        => 'h3',
            'table'             => 'format_table_title',
            'variablelist'      => 'strong',
        ),
        'titleabbrev'           => 'format_suppressed_tags',
        'token'                 => 'tt',
        'type'                  => 'span',
        'userinput'             => 'format_userinput',
        'uri'                   => 'tt',
        'variablelist'          => 'format_variablelist',
        'varlistentry'          => 'format_varlistentry',
        'varname'               => array(
            /* DEFAULT */          'var',
            'fieldsynopsis'     => 'format_fieldsynopsis_varname',
        ),
        'void'                  => 'format_void',
        'warning'               => 'format_admonition',
        'xref'                  => 'a',
        'year'                  => 'span',
        'quote'                 => 'format_quote',
        'qandadiv'              => 'format_div',
        'qandaset'              => 'format_div',
        'qandaentry'            => 'format_dl',
        'question'              => 'dt',
        'answer'                => 'dd',
    ); /* }}} */
    protected $textmap = array(
        'segtitle'             => 'format_segtitle_text',
        'affiliation'          => 'format_suppressed_text',
        'contrib'              => 'format_suppressed_text',
        'shortaffil'           => 'format_suppressed_text',
        'titleabbrev'          => 'format_suppressed_text',
        'programlisting'       => 'format_programlisting_text',
        'screen'               => 'format_screen_text',
        'alt'                  => 'format_alt_text',
        'modifier'             => array(
            /* DEFAULT */         false,
            'fieldsynopsis'    => 'format_fieldsynopsis_modifier_text',
        ),
        'classname'            => array(
            /* DEFAULT */         false,
            'ooclass'          => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_ooclass_classname_text',
            ),
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
        'para'                  => array(
            /* DEFAULT */          false,
            'footnote'             => 'format_footnote_para_text',
        ),
        /* FIXME: This is one crazy stupid workaround for footnotes */
        'constant'              => array(
            /* DEFAULT */          false,
            'para'              => array(
                /* DEFAULT */      false,
                'footnote'      => 'format_footnote_constant_text',
            ),
        ),
        'literal'               => 'format_literal_text',
        'email'                 => 'format_email_text',

    );

    /**
    * Determines if we are in a paragraph or not, and if, in which level.
    * Useful to know for HTML tags that may not be in a <p> tag.
    *
    * @see format_para()
    *
    * @var integer
    */
    public $openPara = 0;

    /**
    * Which para levels we already escaped
    *
    * @var array
    */
    public $escapedPara = array();

    /**
    * Programlisting role. Necessary to highlight the code properly.
    * String when role is set, false if not.
    *
    * @var string
    *
    * @see format_programlisting()
    * @see CDATA()
    */
    public $role        = false;

    /* Current Chunk variables */
    public $cchunk      = array();
    /* Default Chunk variables */
    protected $dchunk      = array(
        "classsynopsis"            => array(
            "close"                         => false,
            "classname"                     => false,
        ),
        "classsynopsisinfo"        => array(
            "implements"                    => false,
            "ooclass"                       => false,
        ),
        "fieldsynopsis"            => array(
            "modifier"                      => "public",
        ),
        "co"                       => 0,
        "callouts"                 => 0,
        "segmentedlist"            => array(
            "seglistitem"                   => 0,
            "segtitle"                      => array(
            ),
        ),
        "table"                    => false,
        "procedure"                => false,
        "mediaobject"              => array(
            "alt"                           => false,
        ),
        "footnote"                 => array(
        ),
        "tablefootnotes"           => array(
        ),
        "chunk_id"                 => null,
        "varlistentry"             => array(
            "listitems"                     => array(),
        ),
    );

    public function __call($func, $args) {
        if ($args[0]) {
            trigger_error("No mapper found for '{$func}'", E_USER_WARNING);
            return "<font color='red' size='+3'>{$args[1]}</font>";
        }
        return "<font color='red' size='+3'>/{$args[1]}</font>";
    }
    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        if ($open) {
            $idstr = "";
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
                $idstr = ' id="' .$id. '" name="' .$id. '"';
            }
            return '<' .$tag. ' class="' .$name. '"' . ($props["empty"] ? '/' : "") . $idstr. '>';
        }
        return '</' .$tag. '>';
    }
    public function CDATA($str) {
        switch($this->role) {
        case '':
            return '<div class="cdata"><pre>'
                . htmlspecialchars($str, ENT_QUOTES, "UTF-8")
                . '</pre></div>';
        default:
            return '<div class="' . $this->role . 'code">'
                . $this->highlight(trim($str), $this->role, 'xhtml')
                . '</div>';
        }
    }
    public function TEXT($str) {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }
    public function getChunkInfo() {
        return $this->cchunk;
    }

    /**
    * Closes a para tag when we are already in a paragraph.
    *
    * @return string HTML code
    *
    * @see $openPara
    * @see restorePara()
    */
    public function escapePara()
    {
        if (!$this->openPara) {
            return '';
        }

        if (!isset($this->escapedPara[$this->openPara])) {
            $this->escapedPara[$this->openPara] = 1;
            return '</p>';
        } else {
            ++$this->escapedPara[$this->openPara];
            return '';
        }
    }

    /**
    * Opens a para tag again when we escaped one before.
    *
    * @return string HTML code
    *
    * @see $openPara
    * @see escapePara()
    */
    public function restorePara()
    {
        if (!$this->openPara || !isset($this->escapedPara[$this->openPara])) {
            return '';
        }

        if ($this->escapedPara[$this->openPara] == 1) {
            unset($this->escapedPara[$this->openPara]);
            return '<p>';
        } else {
            --$this->escapedPara[$this->openPara];
            return '';
        }
    }

    public function format_suppressed_tags($open, $name, $attrs) {
        /* Ignore it */
        return "";
    }
    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }

    public function format_literal($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = false;
            }
            return '<i>';
        }
        $this->role = false;
        return '</i>';
    }
    public function format_literal_text($value, $tag) {
        switch ($this->role) {
            case 'infdec':
                $value = (float)$value;
                $p = strpos($value, '.');
                $str = substr($value, 0, $p + 1);
                $str .= '<span style="text-decoration: overline;">';
                $str .= substr($value, $p + 1);
                $str .= '</span>';
                return $str;
            default:
                return $this->TEXT($value);
        }
    }

    public function format_copyright($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">&copy; ';
        }
        return '</div>';
    }
    public function format_author($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="' .$name. ' vcard">';
        }
        return "</div>";
    }
    public function format_personname($open, $name, $attrs, $props) {
        if ($open) {
            return '<span class="' .$name. ' fn">';
        }
        return "</span>";
    }
    public function format_name($open, $name, $attrs) {
        if ($open) {
            $class = "";
            switch($name) {
            case "firstname":
                $class = " given-name";
                break;

            case "surname":
                $class = " family-name";
                break;

            case "othername":
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                    /* We maight want to add support for other roles */
                    switch($attrs[Reader::XMLNS_DOCBOOK]["role"]) {
                    case "nickname":
                        $class = " nickname";
                        break;
                    }
                }
                break;
            }

            return ' <span class="' . $name . $class . '">';
        }
        return '</span> ';
    }

    public function format_container_chunk($open, $name, $attrs) {
        $this->cchunk = $this->dchunk;
        if ($open) {
            return '<div id="' .$attrs[Reader::XMLNS_XML]["id"]. '" class="' .$name. '">';
        }
        return "</div>";
    }
    public function format_legalnotice_chunk($open, $name, $attrs) {
        if ($open) {
            return '<div id="legalnotice">';
        }
        return "</div>\n";
    }
    public function format_chunk($open, $name, $attrs) {
        if ($open) {
            $this->cchunk = $this->dchunk;
            if(isset($attrs[Reader::XMLNS_XML]["id"])) {
                $class = $name;
                if ($name === "refentry") {
                    //$class .= " -rel-posting";
                }
                $this->cchunk["chunk_id"] = $attrs[Reader::XMLNS_XML]["id"];
                return '<div id="' .$attrs[Reader::XMLNS_XML]["id"]. '" class="' .$class. '">';
            }
            return '<div class="' .$name. '">';
        }
        $str = "";
        foreach ($this->cchunk["footnote"] as $k => $note) {
            $str .= '<div class="footnote">';
            $str .= '<a name="fnid' .$note["id"]. '" href="#fn' .$note["id"]. '"><sup>[' .($k + 1). ']</sup></a>';
            $str .= $note["str"];
            $str .= "</div>\n";
        }
        $this->cchunk["footnote"] = $this->dchunk["footnote"];

        return $str. "</div>";
    }
    public function format_varentry_chunk($open, $name, $attrs, $props) {
        return $this->format_chunk($open, "refentry", $attrs, $props);
    }
    public function format_refsect1_para($open, $name, $attrs, $props) {
        if ($props['empty']) {
            return '';
        }
        if ($open) {
            switch ($props["sibling"]) {
            case "methodsynopsis":
            case "constructorsynopsis":
            case "destructorsynopsis":
                ++$this->openPara;
                return '<p class="'.$name.' rdfs-comment">';
                break;

            default:
                ++$this->openPara;
                return '<p class="'.$name.'">';
            }

        }
        --$this->openPara;
        return '</p>';
    }
    public function format_refsect($open, $name, $attrs) {
        if ($open) {
            if(!isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $attrs[Reader::XMLNS_DOCBOOK]["role"] = "unknown";
            }
            $this->role = $role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            return '<a name="' .$this->cchunk["chunk_id"]. '.' .$role. '"></a><div class="' .$name.' ' .$role. '">';
        }
        $this->role = null;
        return "</div>\n";
    }

    public function format_classsynopsisinfo_oointerface($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["implements"] === false) {
                $this->cchunk["classsynopsisinfo"]["implements"] = true;
                return '<span class="'.$name.'">implements ';
            }
            return '<span class="'.$name.'">, ';
        }

        return "</span>";
    }
    public function format_classsynopsisinfo_ooclass_classname($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["ooclass"] === false) {
                $this->cchunk["classsynopsisinfo"]["ooclass"] = true;
                return ' class <b class="'.$name.'">';
            }
            return '<b class="'.$name.'"> ';
        }
        return "</b>";
    }
    public function format_classsynopsisinfo($open, $name, $attrs) {
        $this->cchunk["classsynopsisinfo"] = $this->dchunk["classsynopsisinfo"];
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "comment") {
                return '<div class="'.$name.' classsynopsisinfo_comment">/* ';
            }
            return '<div class="'.$name.'">';
        }

        if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "comment") {
            return ' */</div>';
        }
        $this->cchunk["classsynopsis"]["close"] = true;
        return ' {</div>';
    }

    public function format_classsynopsis($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">';
        }

        if ($this->cchunk["classsynopsis"]["close"] === true) {
            $this->cchunk["classsynopsis"]["close"] = false;
            return "}</div>";
        }
        return "</div>";
    }

    public function format_classsynopsis_ooclass_classname_text($value, $tag) {
        $this->cchunk["classsynopsis"]["classname"] = $value;
        return $this->TEXT($value);
    }

    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        $value = $this->TEXT($value);
        if ($this->cchunk["classsynopsis"]["classname"] === false) {
            return $value;
        }
        if (strpos($value, '::')) {
            $explode = '::';
        } elseif (strpos($value, '->')) {
            $explode = '->';
        } else {
            return $value;
        }

        list($class, $method) = explode($explode, $value);
        if ($class !== $this->cchunk["classsynopsis"]["classname"]) {
            return $value;
        }
        return $method;
    }

    public function format_cmdsynopsis($open, $name, $attrs)
    {
        if ($open) {
            return '<span style="background-color:#eee">';
        }
        return '</span>';
    }

    public function format_fieldsynopsis($open, $name, $attrs) {
        $this->cchunk["fieldsynopsis"] = $this->dchunk["fieldsynopsis"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return ";</div>\n";
    }
    public function format_fieldsynopsis_modifier_text($value, $tag) {
        $this->cchunk["fieldsynopsis"]["modifier"] = trim($value);
        return $this->TEXT($value);
    }
    public function format_methodsynopsis($open, $name, $attrs) {
        if ($open) {
            $this->params = array("count" => 0, "opt" => 0, "content" => "");
            return '<div class="'.$name.' dc-description">';
        }
        $content = "";
        if ($this->params["opt"]) {
            $content = str_repeat("]", $this->params["opt"]);
        }
        $content .= " )";

        $content .= "</div>\n";

        return $content;
    }
    public function format_methodparam_parameter($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                return ' <tt class="parameter reference">&amp;$';
            }
            return ' <tt class="parameter">$';
        }
        return "</tt>";
    }
    public function format_initializer($open, $name, $attrs) {
        if ($open) {
            return '<span class="'.$name.'">= ';
        }
        return '</span>';
    }
    public function format_parameter($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                return '<i><tt class="parameter reference">&amp;';
            }
            return '<i><tt class="parameter">';
        }
        return "</tt></i>\n";
    }

    public function format_void($open, $name, $attrs) {
        return ' ( <span class="methodparam">void</span>';
    }
    public function format_methodparam($open, $name, $attrs) {
        if ($open) {
            $content = '';
                if ($this->params["count"] == 0) {
                    $content .= " (";
                }
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["choice"]) && $attrs[Reader::XMLNS_DOCBOOK]["choice"] == "opt") {
                    $this->params["opt"]++;
                    $content .= "[";
                } else if($this->params["opt"]) {
                    $content .= str_repeat("]", $this->params["opt"]);
                    $this->params["opt"] = 0;
                }
                if ($this->params["count"]) {
                    $content .= ",";
                }
                $content .= ' <span class="methodparam">';
                ++$this->params["count"];
                return $content;
        }
        return "</span>";
    }
    public function format_methodname($open, $name, $attr) {
        if ($open) {
            return ' <span class="methodname"><b>';
        }
        return '</b></span>';
    }

    public function format_varname($open, $name, $attrs) {
        if ($open) {
            return '<var class="'.$name.'">$';
        }
        return "</var>\n";
    }
    public function format_fieldsynopsis_varname($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["fieldsynopsis"]["modifier"] === "const") {
                return '<var class="fieldsynopsis_varname">';
            }
            return '<var class="'.$name.'">$';
        }
        return '</var>';
    }

    public function format_footnoteref($open, $name, $attrs, $props) {
        if ($open) {
            $linkend = $attrs[Reader::XMLNS_DOCBOOK]["linkend"];
            $found = false;
            foreach($this->cchunk["footnote"] as $k => $note) {
                if ($note["id"] === $linkend) {
                    return '<a href="#fnid' .$note["id"]. '"><sup>[' .($k + 1). ']</sup></a>';
                }
            }
            trigger_error("footnoteref ID '$linkend' not found", E_USER_WARNING);
            return "";
        }
    }
    public function format_footnote($open, $name, $attrs, $props) {
        if ($open) {
            $count = count($this->cchunk["footnote"]);
            $noteid = isset($attrs[Reader::XMLNS_XML]["id"]) ? $attrs[Reader::XMLNS_XML]["id"] : $count + 1;
            $note = array("id" => $noteid, "str" => "");
            $this->cchunk["footnote"][$count] = $note;
            if ($this->cchunk["table"]) {
                $this->cchunk["tablefootnotes"][$count] = $noteid;
            }
            return '<a href="#fnid' .$noteid. '" name="fn'.$noteid.'"><sup>[' .($count + 1). ']</sup></a>';
        }
        return "";
    }

    /* {{{ FIXME: These are crazy workarounds :( */
    public function format_footnote_constant($open, $name, $attrs, $props) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= self::format_constant($open, $name, $attrs, $props);
        return "";
    }
    public function format_footnote_constant_text($value, $tag) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= $value;
        return "";
    }
    public function format_footnote_para($open, $name, $attrs, $props) {
        $k = count($this->cchunk["footnote"]) - 1;
        if ($open) {
            $this->cchunk["footnote"][$k]["str"] .= '<span class="para footnote">';
            return "";
        }

        $this->cchunk["footnote"][$k]["str"] .= "</span>";
        return "";
    }
    public function format_footnote_para_text($value, $tag) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= $value;
        return "";
    }

    /* }}} */

    public function format_co($open, $name, $attrs, $props) {
        if (($open || $props["empty"]) && isset($attrs[Reader::XMLNS_XML]["id"])) {
            $co = ++$this->cchunk["co"];
            return '<a name="'.$attrs[Reader::XMLNS_XML]["id"].'" id="'.$attrs[Reader::XMLNS_XML]["id"].'">' .str_repeat("*", $co) .'</a>';
        }
        /* Suppress closing tag if any */
        return "";
    }
    public function format_calloutlist($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["callouts"] = 0;
            return '<table class="callout">';
        }
        return '</table>';
    }
    public function format_callout($open, $name, $attrs) {
        if ($open) {
            return '<tr><td><a href="#'.$attrs[Reader::XMLNS_DOCBOOK]["arearefs"].'">' .str_repeat("*", ++$this->cchunk["callouts"]). '</a></td><td>';
        }
        return "</td></tr>\n";
    }

    public function format_quote($open, $name, $attrs) {
        if ($open) {
            return '"<span class="'.$name.'">';
        }
        return '</span>"';
    }
    public function format_manvolnum($open, $name, $attrs) {
        if ($open) {
            return '<span class="'.$name.'">(';
        }
        return ")</span>";
    }

    /**
    * Displays a <div> tag with the proper class name.
    * Closes and re-openes <p> tags if necessary.
    */
    public function format_div($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara()
                . '<div class="' . $name . '">';
        }
        return '</div>' . $this->restorePara();
    }

    /**
    * Additionally to generating a <p>, the internal $openPara variable gets
    * in-/decremented
    *
    * @return string HTML code
    *
    * @see $openPara
    */
    public function format_para($open, $name, $attrs, $props)
    {
        if ($props['empty']) {
            return '';
        }
        if ($open) {
            ++$this->openPara;
            return '<p class="' . $name . '">';
        }

        --$this->openPara;
        return '</p>';
    }

    public function format_segmentedlist($open, $name, $attrs) {
        $this->cchunk["segmentedlist"] = $this->dchunk["segmentedlist"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_segtitle_text($value, $tag) {
        $this->cchunk["segmentedlist"]["segtitle"][count($this->cchunk["segmentedlist"]["segtitle"])] = $value;
        /* Suppress the text */
        return "";
    }
    public function format_seglistitem($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["segmentedlist"]["seglistitem"] = 0;
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_seg($open, $name, $attrs) {
        if ($open) {
            return '<div class="seg"><strong><span class="segtitle">' .$this->cchunk["segmentedlist"]["segtitle"][$this->cchunk["segmentedlist"]["seglistitem"]++]. ':</span></strong>';
        }
        return '</div>';
    }
    public function format_procedure($open, $name, $attrs) {
        $this->cchunk["procedure"] = false;
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</ol></div>';
    }
    public function format_step($open, $name, $attrs) {
        if ($open) {
            $ret = "";
            if ($this->cchunk["procedure"] === false) {
                $this->cchunk["procedure"] = true;
                $ret = '<ol type="1">';
            }
            return $ret . "<li>";
        }
        return '</li>';
    }
    public function format_variablelist($open, $name, $attrs) {
        if ($open) {
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $idstr = ' id="'. $attrs[Reader::XMLNS_XML]['id']. '"';
            }
            return $this->escapePara() . "<dl" . $idstr . ">\n";
        }
        return "</dl>\n" . $this->restorePara();
    }
    public function format_varlistentry($open, $name, $attrs) {
        if ($open) {
            return isset($attrs[Reader::XMLNS_XML]["id"]) ? '<dt id="'.$attrs[Reader::XMLNS_XML]["id"]. '" class="varlistentry">' : "<dt class=\"varlistentry\">\n";
        }

        // Listitems close the the dt themselfs
        if ($this->cchunk["varlistentry"]["listitems"] && array_pop($this->cchunk["varlistentry"]["listitems"])) {
            return "";
        }
        return "</dt>\n";
    }
    public function format_varlistentry_listitem($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["varlistentry"]["listitems"][] = 1;
            return "</dt><dd class=\"listitem\">\n";
        }
        return "</dd>\n";
    }
    public function format_term($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] == $name) {
                return '<br /><span class="' .$name. '">';
            }
            return '<span class="' .$name. '">';
        }
        return "</span>\n";
    }
    public function format_userinput($open, $name, $attrs) {
        if ($open) {
            return '<strong class="' .$name. '"><code>';
        }
        return "</code></strong>\n";
    }
    public function format_systemitem($open, $name, $attrs) {
        if ($open) {
            $val = isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) ? $attrs[Reader::XMLNS_DOCBOOK]["role"] : null;
            switch($val) {
            case "directive":
            /* FIXME: Different roles should probably be handled differently */
            default:
                return '<code class="systemitem ' .$name. '">';
            }
        }
        return "</code>\n";
    }
    public function format_example_content($open, $name, $attrs, $props) {
        if ($props['empty']) {
            return '';
        }
        if ($open) {
            $retval = $this->escapePara() . '<div class="example-contents ' .$name. '"><p>';
            ++$this->openPara;
            return $retval;
        }
        --$this->openPara;
        return "</p></div>" . $this->restorePara();
    }
    public function format_programlisting($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = false;
            }

            return $this->escapePara() . '<div class="example-contents ' .$name. '">';
        }
        $this->role = false;
        return "</div>\n" . $this->restorePara();
    }
    public function format_programlisting_text($value, $tag) {
        return nl2br($this->TEXT($value));
    }
    public function format_screen($open, $name, $attrs) {
        if ($open) {
            return '<div class="example-contents ' .$name. '">';
        }
        return '</div>';
    }
    public function format_screen_text($value, $tag) {
        return nl2br($this->TEXT($value));
    }
    public function format_constant($open, $name, $attrs) {
        if ($open) {
            return "<b><tt class=\"constant\">";
        }
        return "</tt></b>";
    }
    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]['id'])) {
                $idstr = 'id="'. $attrs[Reader::XMLNS_XML]['id']. '" ';
            }
            return '<div '. $idstr. 'class="'. $name. '">'
                . $this->admonition_title($name, $props['lang']);
        }
        return "</div>";
    }
    public function format_authorgroup_author($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] !== $name) {
                return '<div class="'.$name.' vcard">' .$this->admonition_title("by", $props["lang"]). ':<br />';
            }
            return '<div class="'.$name.' vcard">';
        }
        return "</div>\n";
    }
    public function format_editor($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="editor vcard">' .$this->admonition_title("editedby", $props["lang"]). ': ';
        }
        return "</div>\n";
    }
    public function format_note($open, $name, $attrs, $props) {
        if ($open) {
            $retval = $this->escapePara() . '<blockquote><p>'.$this->admonition_title("note", $props["lang"]). ': ';
            ++$this->openPara;
            return $retval;
        }

        --$this->openPara;
        return "</p></blockquote>" . $this->restorePara();
    }
    public function format_note_title($open, $name, $attrs) {
        if ($open) {
            return '<b>';
        }
        return '</b><br />';
    }
    public function format_note_content($open, $name, $attrs) {
        if ($open) {
            /* Ignore the open tag */
            return "";
        }
        return "<br />";
    }
    public function format_bold_paragraph($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            ++$this->openPara;
            return "<p><b>";
        }
        --$this->openPara;
        return "</b></p>";
    }
    public function format_table_title($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            return "<caption><b>";
        }
        return "</b></caption>";
    }

    /**
    * Renders  a <tag class=""> tag.
    *
    * @return string HTML code
    */
    public function format_tag($open, $name, $attrs, $props)
    {
        static $arFixes = array(
            'attribute'     => array('', ''),
            'attvalue'      => array('"', '"'),
            'comment'       => array('&lt;!--', '--&gt;'),
            'element'       => array('', ''),
            'emptytag'      => array('&lt;', '/&gt;'),
            'endtag'        => array('&lt;/', '&gt;'),
            'genentity'     => array('&amp;', ';'),
            'localname'     => array('', ''),
            'namespace'     => array('', ''),
            'numcharref'    => array('&amp;#', ';'),
            'paramentity'   => array('%', ';'),
            'pi'            => array('&lt;?', '?&gt;'),
            'prefix'        => array('', ''),
            'starttag'      => array('&lt;', '&gt;'),
            'xmlpi'         => array('&lt;?', '?&gt;'),
        );
        if ($props['empty']) {
            return '';
        }
        $class = $attrs['class'];
        if (!isset($arFixes[$class])) {
            trigger_error('Unknown tag class "' . $class . '"', E_USER_WARNING);
            $class = 'starttag';
        }
        if (!$open) {
            return $arFixes[$class][1] . '</code>';
        }

        return '<code>' . $arFixes[$class][0];
    }

    public function format_mediaobject($open, $name, $attrs) {
        $this->cchunk["mediaobject"] = $this->dchunk["mediaobject"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_alt_text($value, $tag) {
        $this->cchunk["mediaobject"]["alt"] = $value;
    }

    public function format_dl($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara() . '<dl class="' . $name . '">';
        }
        return '</dl>' . $this->restorePara();
    }

    public function format_itemizedlist($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara() . '<ul class="' . $name . '">';
        }
        return '</ul>' . $this->restorePara();
    }

    public function format_orderedlist($open, $name, $attrs, $props)
    {
        if ($open) {
            $numeration = "1";
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["numeration"])) {
                switch($attrs[Reader::XMLNS_DOCBOOK]["numeration"]) {
                case "upperalpha":
                    $numeration = "A";
                    break;
                case "loweralpha":
                    $numeration = "a";
                    break;
                case "upperroman":
                    $numeration = "I";
                    break;
                case "lowerroman":
                    $numeration = "i";
                    break;
                }
            }
            return $this->escapePara(). '<ol type="' .$numeration. '">';
        }
        return '</ol>' . $this->restorePara();
    }

    public function format_table($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["table"] = true;
            return $this->escapePara() . '<table class="doctable '.$name. '">';
        }
        $this->cchunk["table"] = false;
        $str = "";
        if ($this->cchunk["tablefootnotes"]) {
            $opts = array(Reader::XMLNS_DOCBOOK => array());

            $str =  $this->format_tbody(true, "footnote", $opts, $props);
            $str .= $this->format_row(true, "footnote", $opts, $props);
            $str .= $this->format_entry(true, "footnote", $opts, $props+array("colspan" => $this->getColCount()));

            foreach ($this->cchunk["tablefootnotes"] as $k => $noteid) {
                $str .= '<div class="footnote">';
                $str .= '<a name="fnid' .$noteid. '" href="#fn' .$noteid .'"><sup>[' .($k + 1). ']</sup></a>' .$this->cchunk["footnote"][$k]["str"] . "\n";
                unset($this->cchunk["footnote"][$k]);
                $str .= "</div>\n";

            }
            $str .= $this->format_entry(false, "footnote", $opts, $props);
            $str .= $this->format_row(false, "footnote", $opts, $props);
            $str .= $this->format_tbody(false, "footnote", $opts, $props);

            $this->cchunk["tablefootnotes"] = $this->dchunk["tablefootnotes"];
        }
        return "$str</table>\n" . $this->restorePara();
    }
    public function format_tgroup($open, $name, $attrs) {
        if ($open) {
            Format::tgroup($attrs[Reader::XMLNS_DOCBOOK]);
            return '';
        }
        return '';
    }
    private function parse_table_entry_attributes($attrs) {
        $retval = 'align="' .$attrs["align"]. '"';
        if ($attrs["align"] == "char" && isset($attrs["char"])) {
            $retval .= ' char="' .(htmlspecialchars($attrs["char"], ENT_QUOTES)). '"';
            if (isset($attrs["charoff"])) {
                $retval .= ' charoff="' .(htmlspecialchars($attrs["charoff"], ENT_QUOTES)). '"';
            }
        }
        if (isset($attrs["valign"])) {
            $retval .= ' valign="' .$attrs["valign"]. '"';
        }
        if (isset($attrs["colwidth"])) {
            $width = $attrs["colwidth"];
            if (is_numeric($width)) {
                $retval .= ' width="' .((int)$width). '"';
            }
            // N*
            elseif(($pos = strpos($width, "*")) !== false) {
                $cols = $this->getColCount();
                $length = 100/$cols;
                if (substr($width, -1) !== "*") {
                    trigger_error("Mixing proportion and fixed measure not implemented", E_USER_WARNING);
                }
                // Standard length
                elseif($width == "*" || $width == "1*") {
                }
                else {
                    $width = (int)substr($width, 0, $pos);
                    $length *= $width;
                }
                $retval = ' width="' .$length. '%"';

            }
            // Npt or Npx or other weird format
            else {
                $retval .= ' width="' .htmlentities($attrs["colwidth"], ENT_QUOTES, "UTF-8"). '"';
            }
        }
        return $retval;
    }
    public function format_colspec($open, $name, $attrs) {
        if ($open) {
            $str = self::parse_table_entry_attributes(Format::colspec($attrs[Reader::XMLNS_DOCBOOK]));

            return '<col '.$str. ' />';
        }
        /* noop */
    }
    public function format_th($open, $name, $attrs) {
        if ($open) {
            $valign = Format::valign($attrs[Reader::XMLNS_DOCBOOK]);
            return '<' .$name. ' valign="' .$valign. '">';
        }
        return "</$name>\n";
    }
    public function format_tbody($open, $name, $attrs) {
        if ($open) {
            $valign = Format::valign($attrs[Reader::XMLNS_DOCBOOK]);
            return '<tbody valign="' .$valign. '" class="' .$name. '">';
        }
        return "</tbody>";
    }
    public function format_row($open, $name, $attrs) {
        if ($open) {
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]['id'])) {
                $idstr = ' id="'. $attrs[Reader::XMLNS_XML]['id']. '"';
            }
            Format::initRow();
            $valign = Format::valign($attrs[Reader::XMLNS_DOCBOOK]);
            return '<tr'.$idstr.' valign="' .$valign. '">';
        }
        return "</tr>\n";
    }
    public function format_th_entry($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return '<th class="empty">&nbsp;</th>';
        }
        if ($open) {
            $colspan = Format::colspan($attrs[Reader::XMLNS_DOCBOOK]);
            if ($colspan == 1) {
                return '<th>';
            } else {
                return '<th colspan="' .((int)$colspan). '">';
            }
        }
        return '</th>';
    }
    public function format_entry($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return '<td class="empty">&nbsp;</td>';
        }
        if ($open) {
            $dbattrs = Format::getColspec($attrs[Reader::XMLNS_DOCBOOK]);

            $retval = "";
            if (isset($dbattrs["colname"])) {
                for($i=Format::getEntryOffset($dbattrs); $i>0; --$i) {
                    $retval .= '<td class="empty">&nbsp;</td>';
                }
            }

            /*
             * "colspan" is *not* an standard prop, only used to overwrite the
             * colspan for <footnote>s in tables
             */
            if (isset($props["colspan"])) {
                $colspan = $props["colspan"];
            } else {
                $colspan = Format::colspan($dbattrs);
            }

            $rowspan = Format::rowspan($dbattrs);
            $moreattrs = self::parse_table_entry_attributes($dbattrs);

            $sColspan = $colspan == 1 ? '' : ' colspan="' .((int)$colspan) . '"';
            $sRowspan = $rowspan == 1 ? '' : ' rowspan="' .((int)$rowspan). '"';
            return $retval. '<td' . $sColspan . $sRowspan . ' ' . $moreattrs. '>';
        }
        return "</td>";
    }

    public function admonition_title($title, $lang) {
        return '<b class="' .(strtolower($title)). '">' .($this->autogen($title, $lang)). '</b>';
    }

    public function format_citation($open, $name, $attrs, $props) {
        if ($open) {
            return '[<span class="citation">';
        }
        return '</span>]';
    }

    public function format_email_text($value) {
        return '&lt;<a href="mailto:' . $value . '">' . $value . '</a>&gt;';
    }
}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

