<?php
/*  $Id$ */

class XHTMLPhDFormat extends PhDFormat {
    protected $elementmap = array( /* {{{ */
        'abstract'              => 'div', /* Docbook-xsl prints "abstract"... */
        'acronym'               => 'acronym',
        'article'               => 'format_container_chunk',
        'author'                => 'div',
        'authorgroup'           => 'div', /* DocBook-xsl prints out "by" (i.e. "PHP Manual by ...") */
        'appendix'              => 'format_container_chunk',
        'application'           => 'span',
        'bibliography'          => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'book'                  => 'format_container_chunk',
        'chapter'               => 'format_container_chunk',
        'colophon'              => 'format_chunk',
        'firstname'             => 'span',
        'surname'               => 'span',
        'othername'             => 'span',
        'honorific'             => 'span',
        'glossary'              => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'caution'               => 'div',
        'classname'             => array(
            /* DEFAULT */          'span',
            'ooclass'           => 'b',
        ),
        'classsynopsis'         => 'format_classsynopsis',
        'code'                  => 'code',
        'collab'                => 'span',
        'collabname'            => 'span',
        'colspec'               => 'format_colspec',
        'command'               => 'span',
        'computeroutput'        => 'span',
        'constant'              => 'format_constant',
        'constructorsynopsis'   => 'format_methodsynopsis',
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
        'example'               => 'div',
        'fieldsynopsis'         => 'format_fieldsynopsis',
        'filename'              => 'var',
        'glossterm'             => 'span',
        'holder'                => 'span',
        'index'                 => array(
            /* DEFAULT */          'div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'info'                  => 'div',
        'informalexample'       => 'div',
        'informaltable'         => 'table',
        'itemizedlist'          => 'ul',
        'listitem'              => array(
            /* DEFAULT */          'li',
            'varlistentry'      => 'format_varlistentry_listitem',
        ),
        'literal'               => 'i',
        'mediaobject'           => 'div',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'methodname'            => 'format_methodname',
        'member'                => 'li',
        'modifier'              => 'span',
        'note'                  => 'format_note',
        'ooclass'               => array(
            /* DEFAULT */          'span',
            'classsynopsis'     => 'format_classsynopsis_ooclass',
        ),
        'option'                => 'span',    
        'orderedlist'           => 'ol',
        'para'                  => array(
            /* DEFAULT */          'p',
            'example'           => 'format_example_content',
        ),
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
        ),
        'part'                  => 'format_container_chunk',
        'partintro'             => 'div',
        'personname'            => 'span',
        'preface'               => 'format_chunk',
        'productname'           => 'span',
        'programlisting'        => 'format_programlisting',
        'propname'              => 'span',
        'property'              => 'span',
        'proptype'              => 'span',
        'refentry'              => 'format_chunk',
        'reference'             => 'format_container_chunk',
        'refsect1'              => 'format_refsect',
        'refsect2'              => 'format_refsect',
        'refsect3'              => 'format_refsect',
        'refname'               => 'h1',
        'refnamediv'            => 'div',
        'row'                   => 'format_row',
        'screen'                => 'format_screen',
        'sect1'                 => 'format_chunk',
        'sect2'                 => 'format_chunk',
        'sect3'                 => 'format_chunk',
        'sect4'                 => 'format_chunk',
        'sect5'                 => 'format_chunk',
        'section'               => 'format_chunk',
        'set'                   => 'format_chunk',
        'setindex'              => 'format_chunk',
        'simplelist'            => 'ul',
        'simpara'               => array(
            /* DEFAULT */          'p',
            'note'              => 'span',
            'listitem'          => 'span',
            'entry'             => 'span',
            'example'           => 'format_example_content',
        ),
        'systemitem'            => 'format_systemitem',
        'table'                 => 'format_table',
        'term'                  => 'span',
        'tfoot'                 => 'format_th',
        'thead'                 => 'format_th',
        'tgroup'                => 'format_tgroup',
        'tip'                   => 'div',
        'title'                 => array(
            /* DEFAULT */          'h1',
            'example'           => 'format_bold_paragraph',
            'info'              => array(
                /* DEFAULT */      'h1',
                'example'       => 'format_bold_paragraph',
            ),
            'legalnotice'       => 'h4',
            'note'              => 'format_note_title',
            'refsect1'          => 'h3',
            'refsect2'          => 'h4',
            'refsect3'          => 'h5',
            'section'           => 'h2',
            'sect1'             => 'h2',
            'sect2'             => 'h3',
            'sect3'             => 'h4',
            'table'             => 'format_bold_paragraph',
        ),
        'type'                  => 'span',
        'userinput'             => 'format_userinput',
        'variablelist'          => 'format_variablelist',
        'varlistentry'          => 'format_varlistentry',
        'varname'               => array(
            /* DEFAULT */          'var',
            'fieldsynopsis'     => 'format_varname',
        ),
        'void'                  => 'format_void',
        'warning'               => 'div',
        'year'                  => 'span',
    ); /* }}} */
    protected $textmap = array(
    );


    protected $role        = false;
    
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
    public function transformFromMap($open, $tag, $name) {
        if ($open) {
            return sprintf('<%s class="%s">', $tag, $name);
        }
        return "</$tag>";
    }
    public function CDATA($str) {
        switch($this->role) {
        case "php":
            return sprintf('<div class="phpcode">%s</div>', highlight_string(trim($str), 1));
            break;
        default:
            return sprintf('<div class="cdata"><pre>%s</pre></div>', htmlspecialchars($str, ENT_QUOTES, "UTF-8"));
        }
    }
    public function TEXT($str) {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }

    public function format_container_chunk($open, $name, $attrs) {
        if ($open) {
            return sprintf('<div id="%s" class="%s">', $attrs[PhDReader::XMLNS_XML]["id"], $name);
        }
        return "</div>";
    }
    public function format_chunk($open, $name, $attrs) {
        if ($open) {
            if(isset($attrs[PhDReader::XMLNS_XML]["id"])) {
                return sprintf('<div id="%s" class="%s">', $attrs[PhDReader::XMLNS_XML]["id"], $name);
            }
            return sprintf('<div class="%s">', $name);
        }
        return "</div>";
    }
    public function format_refsect($open, $name, $attrs) {
        if ($open) {
            if(!isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                $attrs[PhDReader::XMLNS_DOCBOOK] = "unkown";
            }
            return sprintf('<div class="%s %s">', $name, $attrs[PhDReader::XMLNS_DOCBOOK]["role"]);
        }
        return "</div>\n";
    }

    public function format_classsynopsis_ooclass($open, $name, $attrs) {
        if ($open) {
            return '<span class="'.$name.'">class ';
        }

        return "</span> {";
    }
    public function format_classsynopsis($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">';
        }

        return "}</div>";
    }
    public function format_fieldsynopsis($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return ";</div>\n";
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
            return '<var>$';
        }
        return "</var>\n";
    }
    public function format_variablelist($open, $name, $attrs) {
        if ($open) {
            return "<dl>\n";
        }
        return "</dl>\n";
    }
    public function format_varlistentry($open, $name, $attrs) {
        if ($open) {
            return isset($attrs[PhDReader::XMLNS_XML]["id"]) ? sprintf('<dt id="%s">', $attrs[PhDReader::XMLNS_XML]["id"]) : "<dt>\n";
        }
        return "</dt>\n";
    }
    public function format_varlistentry_listitem($open, $name, $attrs) {
        if ($open) {
            return "<dd>\n";
        }
        return "</dd>\n";
    }
    public function format_userinput($open, $name, $attrs) {
        if ($open) {
            return sprintf('<strong class="%s"><code>', $name);
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
                return sprintf('<code class="systemitem %s">', $name);
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
    public function format_note($open, $name, $attrs) {
        if ($open) {
            return '<blockquote><p>';
        }
        return "</p></blockquote>";
    }
    public function format_note_title($open, $name, $attrs) {
        if ($open) {
            return '<b>';
        }
        return '</b>';
    }
    public function format_bold_paragraph($open, $name, $attrs) {
        if ($open) {
            return "<p><b>";
        }
        return "</b></p>";
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
        $retval = sprintf('align="%s"', $attrs["align"]);
        if ($attrs["align"] == "char" && isset($attrs["char"])) {
            $retval .= sprintf(' char="%s"', htmlspecialchars($attrs["char"], ENT_QUOTES));
            if (isset($attrs["charoff"])) {
                $retval .= sprintf(' charoff="%s"', htmlspecialchars($attrs["charoff"], ENT_QUOTES));
            }
        }
        if (isset($attrs["valign"])) {
            $retval .= sprintf(' valign="%s"', $attrs["valign"]);
        }
        if (isset($attrs["colwidth"])) {
            $retval .= sprintf(' width="%d"', $attrs["colwidth"]);
        }
        return $retval;
    }
    public function format_colspec($open, $name, $attrs) {
        if ($open) {
            $str = self::parse_table_entry_attributes(PhDFormat::colspec($attrs[PhDReader::XMLNS_DOCBOOK]));

            return sprintf('<col %s />', $str);
        }
        /* noop */
    }
    public function format_th($open, $name, $attrs) {
        if ($open) {
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
            return sprintf('<%s valign="%s">', $name, $valign);
        }
        return "</$name>\n";
    }
    public function format_tbody($open, $name, $attrs) {
        if ($open) {
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
            return sprintf('<tbody valign="%s">', $valign);
        }
        return "</tbody>";
    }
    public function format_row($open, $name, $attrs) {
        if ($open) {
            PhDFormat::initRow();
            $valign = PhDFormat::valign($attrs[PhDReader::XMLNS_DOCBOOK]);
            return sprintf('<tr valign="%s">', $valign);
        }
        return "</tr>\n";
    }
    public function format_th_entry($open, $name, $attrs = array()) {
        if ($open) {
            $colspan = PhDFormat::colspan($attrs[PhDReader::XMLNS_DOCBOOK]);
            return sprintf('<th colspan="%d">', $colspan);
        }
        return '</th>';
    }
    public function format_entry($open, $name, $attrs) {
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
            return sprintf('%s<td colspan="%d" rowspan="%d" %s>', $retval, $colspan, $rowspan, $moreattrs);
        }
        return "</td>";
    }

}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

