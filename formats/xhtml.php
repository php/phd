<?php
/*  $Id$ */

class XHTMLPhDFormat extends PhDFormat {
    protected $elementmap = array( /* {{{ */
        'abstract'              => 'div', /* Docbook-xsl prints "abstract"... */
        'abbrev'                => 'abbr',
        'acronym'               => 'acronym',
        'affiliation'           => 'format_suppressed_tags',
        'alt'                   => 'format_suppressed_tags',
        'article'               => 'format_container_chunk',
        'author'                => 'div',
        'authorgroup'           => 'div', /* DocBook-xsl prints out "by" (i.e. "PHP Manual by ...") */
        'appendix'              => 'format_container_chunk',
        'application'           => 'span',
        'blockquote'            => 'blockquote',
        'bibliography'          => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'book'                  => 'format_container_chunk',
        'chapter'               => 'format_container_chunk',
        'co'                    => 'format_co',
        'colophon'              => 'format_chunk',
        'copyright'             => 'format_copyright',
        'editor'                => 'div', /* Docbook-xsl prints "edited by" */
        'firstname'             => 'format_name',
        'surname'               => 'format_name',
        'othername'             => 'format_name',
        'optional'              => 'span',
        'honorific'             => 'span',
        'glossary'              => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'calloutlist'           => 'format_calloutlist',
        'callout'               => 'format_callout',
        'caution'               => 'format_admonition',
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
        'constant'              => 'format_constant',
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
        'example'               => 'div',
        'formalpara'            => 'p',
        'fieldsynopsis'         => array(
            /* DEFAULT */          'format_fieldsynopsis',
            'entry'             => 'div',
        ),
        'figure'                => 'div',
        'filename'              => 'var',
        'glossterm'             => 'span',
        'holder'                => 'span',
        'imageobject'           => 'div',
        'imagedata'             => 'format_imagedata',
        'index'                 => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'info'                  => array(
            /* DEFAULT */         'div',
            'note'              => 'span',
        ),
        'informalexample'       => 'div',
        'informaltable'         => 'table',
        'indexdiv'              => 'dl',
        'indexentry'            => 'dd',
        'initializer'           => 'format_initializer',
        'itemizedlist'          => 'ul',
        'legalnotice'           => 'format_legalnotice_chunk',
        'listitem'              => array(
            /* DEFAULT */          'li',
            'varlistentry'      => 'format_varlistentry_listitem',
        ),
        'literal'               => 'i',
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
        'othercredit'           => 'div',
        'ooclass'               => array(
            /* DEFAULT */          'span',
            'classsynopsis'     => 'div',
        ),
        'oointerface'           => array(
            /* DEFAULT */          'span',
            'classsynopsisinfo'    => 'format_classsynopsisinfo_oointerface',
        ),
        'interfacename'         => 'span',
        'option'                => 'span',    
        'orderedlist'           => 'ol',
        'para'                  => array(
            /* DEFAULT */          'p',
            'example'           => 'format_example_content',
            'note'              => 'format_note_content',
        ),
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
        ),
        'part'                  => 'format_container_chunk',
        'partintro'             => 'div',
        'personname'            => 'span',
        'preface'               => 'format_chunk',
        'primaryie'             => 'format_suppressed_tags',
        'procedure'             => 'format_procedure',
        'productname'           => 'span',
        'programlisting'        => 'format_programlisting',
        'propname'              => 'span',
        'property'              => array(
            /* DEFAULT */          'span',
            'classsynopsisinfo' => 'format_varname',
        ),
        'proptype'              => 'span',
        'pubdate'               => 'div', /* Docbook-XSL prints "published" */
        'refentry'              => 'format_chunk',
        'refentrytitle'         => 'span',
        'reference'             => 'format_container_chunk',
        'refsect1'              => 'format_refsect',
        'refsect2'              => 'format_refsect',
        'refsect3'              => 'format_refsect',
        'refsynopsisdiv'        => 'div',
        'refname'               => 'h1',
        'refnamediv'            => 'div',
        'replaceable'           => 'span',
        'row'                   => 'format_row',
        'screen'                => 'format_screen',
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
        'simplelist'            => 'ul', /* FIXME: simplelists has few attributes that need to be implemented */
        'simpara'               => array(
            /* DEFAULT */          'p',
            'note'              => 'span',
            'listitem'          => 'span',
            'entry'             => 'span',
            'example'           => 'format_example_content',
        ),
        'step'                  => 'format_step',
        'superscript'           => 'sup',
        'subscript'             => 'sub',
        'systemitem'            => 'format_systemitem',
        'symbol'                => 'span',
        'synopsis'              => 'pre',
        'tag'                   => 'code',
        'table'                 => 'format_table',
        'term'                  => 'format_term',
        'tfoot'                 => 'format_th',
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
            'procedure'         => 'b',
            'refsect1'          => 'h3',
            'refsect2'          => 'h4',
            'refsect3'          => 'h5',
            'section'           => 'h2',
            'sect1'             => 'h2',
            'sect2'             => 'h3',
            'sect3'             => 'h4',
            'segmentedlist'     => 'strong',
            'table'             => 'format_table_title',
            'variablelist'      => 'strong',
        ),
        'type'                  => 'span',
        'userinput'             => 'format_userinput',
        'variablelist'          => 'format_variablelist',
        'varlistentry'          => 'format_varlistentry',
        'varname'               => array(
            /* DEFAULT */          'var',
            'fieldsynopsis'     => 'format_fieldsynopsis_varname',
        ),
        'void'                  => 'format_void',
        'warning'               => 'format_admonition',
        'year'                  => 'span',
        'quote'                 => 'format_quote',
        'qandaset'              => 'div',
        'qandaentry'            => 'dl',
        'question'              => 'dt',
        'answer'                => 'dd',
    ); /* }}} */
    protected $textmap = array(
        'segtitle'             => 'format_segtitle_text',
        'affiliation'          => 'format_suppressed_text',
        'contrib'              => 'format_suppressed_text',
        'shortaffil'           => 'format_suppressed_text',
        'programlisting'       => 'format_programlisting_text',
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
    );


    protected $role        = false;
    protected $tmp         = array();
    
    public function __construct(array $IDs) {
        parent::__construct($IDs);
    }
    public function __call($func, $args) {
        if ($args[0]) {
            trigger_error("No mapper found for '{$func}'", E_USER_WARNING);
            return "<font color='red' size='+3'>{$args[1]}</font>";
        }
        return "<font color='red' size='+3'>/{$args[1]}</font>";
    }
    public function transformFromMap($open, $tag, $name, $props) {
        if ($open) {
            return '<' .$tag. ' class="' .$name. '"' . ($props["empty"] ? '/' : "") . '>';
        }
        return '</' .$tag. '>';
    }
    public function CDATA($str) {
        switch($this->role) {
        case "php":
            return '<div class="phpcode">' .(highlight_string(trim($str), 1)). '</div>';
            break;
        default:
            return '<div class="cdata"><pre>' .(htmlspecialchars($str, ENT_QUOTES, "UTF-8")). '</pre></div>';
        }
    }
    public function TEXT($str) {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }
    public function format_suppressed_tags($open, $name, $attrs) {
        /* Ignore it */
        return "";
    }
    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }

    public function format_copyright($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">&copy; ';
        }
        return '</div>';
    }
    public function format_name($open, $name, $attrs) {
        if ($open) {
            return ' <span class="'.$name.'">';
        }
        return '</span> ';
    }

    public function format_container_chunk($open, $name, $attrs) {
        if ($open) {
            return '<div id="' .$attrs[PhDReader::XMLNS_XML]["id"]. '" class="' .$name. '">';
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
            if(isset($attrs[PhDReader::XMLNS_XML]["id"])) {
                return '<div id="' .$attrs[PhDReader::XMLNS_XML]["id"]. '" class="' .$name. '">';
            }
            return '<div class="' .$name. '">';
        }
        return "</div>";
    }
    public function format_refsect($open, $name, $attrs) {
        if ($open) {
            if(!isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                $attrs[PhDReader::XMLNS_DOCBOOK] = "unkown";
            }
            return '<div class="' .$name.' ' .$attrs[PhDReader::XMLNS_DOCBOOK]["role"]. '">';
        }
        return "</div>\n";
    }

    public function format_classsynopsisinfo_oointerface($open, $name, $attrs) {
        if ($open) {
            if (isset($this->tmp["classsynopsisinfo"]) && !isset($this->tmp["classsynopsisinfo"]["implements"])) {
                $this->tmp["classsynopsisinfo"]["implements"] = true;
                return '<span class="'.$name.'">implements ';
            }
            return '<span class="'.$name.'">, ';
        }

        return "</span>";
    }
    public function format_classsynopsisinfo_ooclass_classname($open, $name, $attrs) {
        if ($open) {
            if (isset($this->tmp["classsynopsisinfo"]) && !isset($this->tmp["classsynopsisinfo"]["ooclass"])) {
                $this->tmp["classsynopsisinfo"]["ooclass"] = true;
                return ' class <b class="'.$name.'">';
            }
            return '<b class="'.$name.'"> ';
        }
        return "</b>";
    }
    public function format_classsynopsisinfo($open, $name, $attrs) {
        $this->tmp["classsynopsisinfo"] = array();
        if ($open) {
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"]) && $attrs[PhDReader::XMLNS_DOCBOOK]["role"] == "comment") {
                return '<div class="'.$name.' classsynopsisinfo_comment">/* ';
            }
            return '<div class="'.$name.'">';
        }

        if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"]) && $attrs[PhDReader::XMLNS_DOCBOOK]["role"] == "comment") {
            return ' */</div>';
        }
        $this->tmp["classsynopsisinfo"]["close"] = true;
        return ' {</div>';
    }

    public function format_classsynopsis($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">';
        }

        if (isset($this->tmp["classsynopsisinfo"]) && isset($this->tmp["classsynopsisinfo"]["close"]) && $this->tmp["classsynopsisinfo"]["close"]) {
            $this->tmp["classsynopsisinfo"]["close"] = false;
            return "}</div>";
        }
        return "</div>";
    }
    
    public function format_classsynopsis_ooclass_classname_text($value, $tag) {
        $this->tmp["classsynopsis"]["classname"] = $value;
        return $value;
    }
    
    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        if (!isset($this->tmp["classsynopsis"]["classname"])) {
            return $value;
        }
        if (strpos($value, '::')) {
            $explode = '::';
        } else if (strpos($value, '->')) {
            $explode = '->';
        } else {
            return $value;
        }

        list($class, $method) = explode($explode, $value);
        if ($class !== $this->tmp["classsynopsis"]["classname"]) {
            return $value;
        }
        return $method;
    }
    
    public function format_fieldsynopsis($open, $name, $attrs) {
        $this->tmp["fieldsynopsis"] = array();
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return ";</div>\n";
    }
    public function format_fieldsynopsis_modifier_text($value, $tag) {
        $this->tmp["fieldsynopsis"]["modifier"] = trim($value);
        return $value;
    }
    public function format_methodsynopsis($open, $name, $attrs) {
        if ($open) {
            $this->params = array("count" => 0, "opt" => 0, "content" => "");
            return '<div class="'.$name.'">';
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
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                return ' <tt class="parameter reference">&$';
            }
            return ' <tt class="parameter">$';
        }
        return "</tt>";
    }
    public function format_initializer($open, $name, $attrs) {
        if ($open) {
            return '<span class="'.$name.'">=';
        }
        return '</span>';
    }
    public function format_parameter($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                return '<i><tt class="parameter reference">&';
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
                if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["choice"]) && $attrs[PhDReader::XMLNS_DOCBOOK]["choice"] == "opt") {
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
            if (isset($this->tmp["fieldsynopsis"]["modifier"]) && $this->tmp["fieldsynopsis"]["modifier"] == "const") {
                return '<var class="fieldsynopsis_varname">';
            }
            return '<var class="'.$name.'">$';
        }
        return '</var>';
    }

    public function format_co($open, $name, $attrs, $props) {
        if (($open || $props["empty"]) && isset($attrs[PhDReader::XMLNS_XML]["id"])) {
            if(isset($this->tmp["co"])) {
                ++$this->tmp["co"];
            } else {
                $this->tmp["co"] = 1;
            }
            return '<a name="'.$attrs[PhDReader::XMLNS_XML]["id"].'" id="'.$attrs[PhDReader::XMLNS_XML]["id"].'">' .str_repeat("*", $this->tmp["co"]) .'</a>';
        }
        /* Suppress closing tag if any */
        return "";
    }
    public function format_calloutlist($open, $name, $attrs) {
        if ($open) {
            $this->tmp["callouts"] = 0;
            return '<table>';
        }
        return '</table>';
    }
    public function format_callout($open, $name, $attrs) {
        if ($open) {
            return '<tr><td><a href="#'.$attrs[PhDReader::XMLNS_DOCBOOK]["arearefs"].'">' .str_repeat("*", ++$this->tmp["callouts"]). '</a></td><td>';
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
    public function format_segmentedlist($open, $name, $attrs) {
        $this->tmp["segmentedlist"] = array("segtitle" => array());
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_segtitle_text($value, $tag) {
        $this->tmp["segmentedlist"]["segtitle"][count($this->tmp["segmentedlist"]["segtitle"])] = $value;
        /* Suppress the text */
        return "";
    }
    public function format_seglistitem($open, $name, $attrs) {
        if ($open) {
            $this->tmp["segmentedlist"]["seglistitem"] = 0;
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_seg($open, $name, $attrs) {
        if ($open) {
            return '<div class="seg"><strong><span class="segtitle">' .$this->tmp["segmentedlist"]["segtitle"][$this->tmp["segmentedlist"]["seglistitem"]++]. ':</span></strong>';
        }
        return '</div>';
    }
    public function format_procedure($open, $name, $attrs) {
        $this->tmp["procedure"] = false;
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</ol></div>';
    }
    public function format_step($open, $name, $attrs) {
        if ($open) {
            $ret = "";
            if (!$this->tmp["procedure"]) {
                $this->tmp["procedure"] = true;
                $ret = '<ol type="1">';
            }
            return $ret . "<li>";
        }
        return '</li>';
    }
    public function format_variablelist($open, $name, $attrs) {
        if ($open) {
            return "<dl>\n";
        }
        return "</dl>\n";
    }
    public function format_varlistentry($open, $name, $attrs) {
        if ($open) {
            return isset($attrs[PhDReader::XMLNS_XML]["id"]) ? '<dt id="'.$attrs[PhDReader::XMLNS_XML]["id"]. '">' : "<dt>\n";
        }
        return "</dt>\n";
    }
    public function format_varlistentry_listitem($open, $name, $attrs) {
        if ($open) {
            return "<dd>\n";
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
            $val = isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"]) ? $attrs[PhDReader::XMLNS_DOCBOOK]["role"] : null;
            switch($val) {
            case "directive":
            /* FIXME: Different roles should probably be handled differently */
            default:
                return '<code class="systemitem ' .$name. '">';
            }
        }
        return "</code>\n";
    }
    public function format_example_content($open, $name, $attrs) {
        if ($open) {
            return '<div class="example-contents"><p>';
        }
        return "</p></div>";
    }
    public function format_programlisting($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                $this->role = $attrs[PhDReader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = false;
            }

            return '<div class="example-contents">';
        }
        $this->role = false;
        return "</div>\n";
    }
    public function format_programlisting_text($value, $tag) {
        return $this->CDATA($value);
    }
    public function format_screen($open, $name, $attrs) {
        if ($open) {
            return '<div class="example-contents"><pre>';
        }
        return '</pre></div>';
    }
    public function format_constant($open, $name, $attrs) {
        if ($open) {
            return "<b><tt>";
        }
        return "</tt></b>";
    }
    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="'. $name. '">' .$this->admonition_title($name, $props["lang"]);
        }
        return "</div>";
    }
    public function format_note($open, $name, $attrs, $props) {
        if ($open) {
            return '<blockquote><p>'.$this->admonition_title("note", $props["lang"]). ': ';
        }
        return "</p></blockquote>";
    }
    public function format_note_title($open, $name, $attrs) {
        if ($open) {
            return '<b>';
        }
        return '</b>';
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
            return "<p><b>";
        }
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

    public function format_mediaobject($open, $name, $attrs) {
        $this->tmp["mediaobject"] = array();
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_alt_text($value, $tag) {
        $this->tmp["mediaobject"]["alt"] = $value;
    }
    public function format_imagedata($open, $name, $attrs) {
        if (isset($this->tmp["mediaobject"]["alt"])) {
            return '<img src="' .$attrs[PhDReader::XMLNS_DOCBOOK]["fileref"]. '" alt="' .$this->tmp["mediaobject"]["alt"]. '" />';
        }
        return '<img src="' .$attrs[PhDReader::XMLNS_DOCBOOK]["fileref"]. '" />';
    }

    public function format_table($open, $name, $attrs) {
        if ($open) {
            return '<table border="5">';
        }
        return "</table>\n";
    }
    public function format_tgroup($open, $name, $attrs) {
        if ($open) {
            PhDFormat::tgroup($attrs[PhDReader::XMLNS_DOCBOOK]);
            return "<colgroup>\n";
        }
        return "</colgroup>\n";
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
            $retval .= ' width="' .((int)$attrs["colwidth"]). '"';
        }
        return $retval;
    }
    public function format_colspec($open, $name, $attrs) {
        if ($open) {
            $str = self::parse_table_entry_attributes(PhDFormat::colspec($attrs[PhDReader::XMLNS_DOCBOOK]));

            return '<col '.$str. ' />';
        }
        /* noop */
    }
    public function format_th($open, $name, $attrs) {
        if ($open) {
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
            return '<' .$name. ' valign="' .$valign. '">';
        }
        return "</$name>\n";
    }
    public function format_tbody($open, $name, $attrs) {
        if ($open) {
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
            return '<tbody valign="' .$valign. '">';
        }
        return "</tbody>";
    }
    public function format_row($open, $name, $attrs) {
        if ($open) {
            PhDFormat::initRow();
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
            return '<tr valign="' .$valign. '">';
        }
        return "</tr>\n";
    }
    public function format_th_entry($open, $name, $attrs = array()) {
        if ($open) {
            $colspan = PhDFormat::colspan($attrs[PhDReader::XMLNS_DOCBOOK]);
            return '<th colspan="' .((int)$colspan). '">';
        }
        return '</th>';
    }
    public function format_entry($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return '<td class="empty">&nbsp;</td>';
        }
        if ($open) {
            $dbattrs = PhDFormat::getColspec($attrs[PhDReader::XMLNS_DOCBOOK]);

            $retval = "";
            if (isset($dbattrs["colname"])) {
                for($i=PhDFormat::getEntryOffset($dbattrs); $i>0; --$i) {
                    $retval .= '<td class="empty">&nbsp;</td>';
                }
            }

            $colspan = PhDFormat::colspan($dbattrs);
            $rowspan = PhDFormat::rowspan($dbattrs);
            $moreattrs = self::parse_table_entry_attributes($dbattrs);
            return $retval. '<td colspan="' .((int)$colspan). '" rowspan="' .((int)$rowspan). '" ' .$moreattrs. '>';
        }
        return "</td>";
    }

    public function admonition_title($title, $lang) {
        return '<b class="' .(strtolower($title)). '">' .($this->autogen($title, $lang)). '</b>';
    }
}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

