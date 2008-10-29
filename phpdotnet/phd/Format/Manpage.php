<?php

class ManpagePhDFormat extends PhDFormat {
    protected $elementmap = array( /* {{{ */
        'acronym'               => 'format_suppressed_tags',
        'abbrev'                => 'format_suppressed_tags',
        'caution'               => 'format_admonition',
        'citerefentry'          => 'format_suppressed_tags',
        'classname'             => 'format_suppressed_tags',
        'classsynopsis'         => '.PP',
        'classsynopsisinfo'     => 'format_suppressed_tags',
        'code'                  => 'format_suppressed_tags',
        'command'               => '\\fI',
        'constant'              => '\\fB',
        'constructorsynopsis'   => 'format_methodsynopsis',
        'destructorsynopsis'    => 'format_methodsynopsis',
        'emphasis'              => '\\fI',
        'envar'                 => 'format_suppressed_tags',
        'errortype'             => 'format_suppressed_tags',
        'example'               => 'format_example',
        'fieldsynopsis'         => 'format_suppressed_tags',
        'filename'              => '\\fI',
        'formalpara'            => 'format_indent',
        /*'funcsynopsis'          => '.SH SYNOPSIS',
        'funcsynopsisinfo'      => '',
        'funcprototype'         => '',*/
        'funcdef'               => '.B',
        'function'              => array(
            /* DEFAULT */          'format_suppressed_tags',
            'member'            => 'format_suppressed_tags',
        ),
        'glossterm'             => 'format_suppressed_tags',
        'imagedata'             => 'format_suppressed_tags',
        'imageobject'           => 'format_suppressed_tags',
        'informalexample'       => '.PP',
        'initializer'           => array(
            /* DEFAULT */          false,
            'methodparam'       => 'format_suppressed_tags',
        ),
        'interfacename'         => 'format_suppressed_tags',
        'itemizedlist'          => 'format_itemizedlist',
        'link'                  => 'format_suppressed_tags',
        'listitem'              => array(
            /* DEFAULT */          false,
            'varlistentry'      => 'format_suppressed_tags',
            'itemizedlist'      => ".TP 0.2i\n\\(bu",
            'orderedlist'       => ".TP 0.2i\n\\(bu",
        ),
        'literal'               => '\\fI',
        'literallayout'         => 'format_verbatim',
        'manvolnum'             => 'format_manvolnum',
        'mediaobject'           => '\\fB[NOT DISPLAYABLE MEDIA]',
        'member'                => 'format_member',
        'methodname'            => '\\fB',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'modifier'              => 'format_suppressed_tags',
        'note'                  => 'format_admonition',
        'ooclass'               => 'format_suppressed_tags',
        'option'                => '\\fI',
        'orderedlist'           => 'format_itemizedlist',
        'para'                  => array(
            /* DEFAULT */          '.PP',
            'listitem'          => 'format_suppressed_tags',
            'entry'             => 'format_suppressed_tags',
        ),
        'paramdef'              => 'format_suppressed_tags',
        'parameter'             => array(
            /* DEFAULT */          'format_suppressed_tags',
            'methodparam'       => 'format_suppressed_tags',
            'code'              => '\\fI',
        ),
        'productname'           => 'format_suppressed_tags',
        'programlisting'        => 'format_verbatim',
        'property'              => 'format_suppressed_tags',
        'refentry'              => 'format_suppressed_tags',
        'refentrytitle'         => '\\fB',
        'refname'               => '.SH NAME',
        'refnamediv'            => 'format_suppressed_tags',
        'refpurpose'            => 'format_refpurpose',
        'refsect1'              => 'format_refsect',
        'refsection'            => 'format_refsect',
        'refsynopsisdiv'        => 'format_refsynopsisdiv',
        'replaceable'           => '\\fI',
        'screen'                => 'format_verbatim',
        'seg'                   => 'format_seg',
        'seglistitem'           => 'format_seglistitem',
        'segmentedlist'         => 'format_segmentedlist',
        'segtitle'              => 'format_suppressed_tags',
        'simpara'               => array(
            /* DEFAULT */          '.PP',
            'listitem'          => '',
        ),
        'simplelist'            => 'format_simplelist',
        'subscript'             => 'format_suppressed_tags',
        'synopsis'              => 'format_suppressed_tags',
        'systemitem'            => '\\fB',
        'tag'                   => 'format_suppressed_tags',
        'term'                  => 'format_term',
        'title'                 => array(
            /* DEFAULT */          '.B',
            'segmentedlist'     => '.B',
            'refsect1'          => 'format_refsect_title',
            'refsection'        => 'format_refsect_title',
        ),
        'tip'                   => 'format_admonition',
        'type'                  => array(
            /* DEFAULT */          '\\fR',
            'methodparam'       => 'format_suppressed_tags'
        ),
        'userinput'             => '\\fB',
        'variablelist'          => 'format_indent',
        'varlistentry'          => ".TP 0.2i\n\\(bu",
        'varname'               => 'format_suppressed_tags',
        'void'                  => 'format_void',
        'warning'               => 'format_admonition',
        'xref'                  => 'format_xref',
        // GROFF (tbl) ARRAYS
        'informaltable'         => '.P',
        'table'                 => '.P',
        'tgroup'                => 'format_tgroup',
        'colspec'               => 'format_suppressed_tags',
        'thead'                 => 'format_thead',
        'tbody'                 => 'format_suppressed_tags',
        'row'                   => 'format_row',
        'entry'                 => 'format_entry',
    ); /* }}} */
    
    protected $textmap = array(
        'classname'             => array(
            /* DEFAULT */          false,
            'ooclass'           => 'format_ooclass_name_text',
        ),
        'function'              => 'format_function_text',
        'initializer'           => array(
            /* DEFAULT */          false,
            'methodparam'       => 'format_initializer_method_text',
        ),
        'literallayout'         => 'format_verbatim_text',
        'manvolnum'             => 'format_text',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter_text',
            'code'              => false,
            //'term'              => 'format_parameter_term_text',
            'methodparam'       => 'format_parameter_method_text',
        ),
        'programlisting'        => 'format_verbatim_text',
        'refname'               => 'format_text',
        'refpurpose'            => 'format_text',
        'screen'                => 'format_verbatim_text',
        'segtitle'              => 'format_segtitle_text',
      //  'term'                  => array(
        //    /* DEFAULT */          false,
          //  'varlistentry'      => 'format_term_text',
       // ),
        'title'                 => array(
            /* DEFAULT */          false,
            'refsect'           => 'format_refsect_text',
            'refsect1'          => 'format_refsect_text',
        ),
        'tag'                   => 'format_tag_text',
        'type'                  => array(
            /* DEFAULT */          false,
            'methodparam'       => 'format_type_method_text',
        ),
        'varname'               => 'format_parameter_text',
    );

    /* Current Chunk variables */
    protected $cchunk      = array();
    /* Default Chunk variables */
    protected $dchunk      = array(
        "appendlater"           => false,
        "firstitem"             => false,
        "buffer"                => array(),
        "examplenumber"         => 0,
        "methodsynopsis"        => array(
            "params"            => array(),
            "firstsynopsis"     => true,
        ),
        "open"                  => false,
        "ooclass"               => null,
        "role"                  => null,
        "segtitle"              => array(),
        "segindex"              => 0,
    );
    
    /* If a chunk is being processed */
    protected $chunkOpen = false;
    
    public function __construct(array $IDs) {
        parent::__construct($IDs);
    }
    
    public function __call($func, $args) {
        if ($args[0] && $this->chunkOpen) {
            trigger_error("No mapper found for '{$func}'", E_USER_WARNING);
        }
        return "\n.B [NOT PROCESSED] $args[1] [/NOT PROCESSED]";
    }
    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        if ($tag === '') return $tag;
        $isMacro = (strncmp($tag, ".", 1) == 0);
        if ($open) {
            return "\n" . $tag . ($isMacro ? "\n" : "");
        }
        return ($isMacro ? "" : "\\fP");
    }
    
    public function CDATA($str) {
        return str_replace("\\", "\\\\", trim($str)); // Replace \ with \\ after trimming
    }
    
    public function TEXT($str) {
        $ret = trim(ereg_replace( "[ \n\t]+", ' ', $str));
        // No newline if current line begins with ',', ';', ':', '.'
        if (strncmp($ret, ",", 1) && strncmp($ret, ";", 1) && strncmp($ret, ":", 1) && strncmp($ret, ".", 1))
            $ret = "\n" . $ret;
        return $ret;
    }
    
    public function getChunkInfo() {
        return $this->cchunk;
    }
    
    public function format_suppressed_tags($open, $name, $attrs) {
        /* Ignore it */
        return "";
    }
    
    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }
    
    public function format_refsect_text($value, $tag) {
        if ($this->cchunk["appendlater"] && isset($this->cchunk["buffer"]))
            array_push($this->cchunk["buffer"], strtoupper('"'.$value.'"'));
        else
            return strtoupper('"'.$value.'"');
    }
    
    public function format_refsect_title($open, $name, $attrs, $props) {
        if ($open) {
            if ($this->cchunk["appendlater"] && isset($this->cchunk["buffer"]))
                array_push($this->cchunk["buffer"], "\n.SH ");
            else return "\n.SH ";
        }
        return "";
    }
    
    public function format_refpurpose($open, $name, $attrs, $props) {
        if ($open) {
            return " \- ";
        }
    }

    public function format_function_text($value, $tag) {
        return "\n\\fB" . $this->toValidName($value) . "\\fP(3)";
    }
        
    public function format_parameter_text($value, $tag) {
        return "\n\\fI$" . $value . "\\fP";
    }
    
    public function format_parameter_term_text($value, $tag) {
        return "\n\\fI$" . $value . "\\fP\n\-";
    }
    
    public function format_term($open, $name, $attrs, $props) {
        if ($open)
            return "";
        return "\n\-";
    }
      
    public function format_simplelist($open, $name, $attrs, $props) {
        if (isset($this->cchunk["role"]) && $this->cchunk["role"] == "seealso") {
            if ($open) {
                $this->cchunk["firstitem"] = true;
                return "";
            }
            return ".";
        } else {
            if ($open) {
                $this->cchunk["firstitem"] = true;
                return "\n.PP\n.RS\n";
            }
            return "\n.RE\n.PP\n";
        }
    }
    
    public function format_member($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($this->cchunk["role"]) && $this->cchunk["role"] == "seealso") {
                if ($this->cchunk["firstitem"]) {
                    $ret = "";
                    $this->cchunk["firstitem"] = false;
                } else $ret = ",";
                return $ret;
            }
            if ($this->cchunk["firstitem"]) {
                $ret = "\n.TP 0.2i\n\\(bu";
                $this->cchunk["firstitem"] = false;
            } else $ret = "\n.TP 0.2i\n\\(bu";
            return $ret;
        }
        return "";
    }
    
    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.PP\n\\fB" . $this->autogen($name, $props["lang"]) . "\\fR\n.RS";
        }
        return "\n.RE\n.PP";
    }
    
    public function format_example($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["examplenumber"])) {
            return "\n.PP\n\\fB" . $this->autogen($name, $props["lang"]) . ++$this->cchunk["examplenumber"] . "\\fR\n.RS";
        }
        return "\n.RE";
    }
    
    public function format_itemizedlist($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.PP\n.RS";
        }
        return "\n.RE\n.PP";
    }
    
    public function format_methodparam($open, $name, $attrs, $props) {
        if ($open) {
            $opt = isset($attrs[PhDReader::XMLNS_DOCBOOK]["choice"]) && 
                $attrs[PhDReader::XMLNS_DOCBOOK]["choice"] == "opt";
            $this->cchunk["methodsynopsis"]["params"][] = array(
                "optional" => $opt,
                "type" => "",
                "name" => "",
                "initializer" => "");
        }
        return "";
    }
    
    public function format_void($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["methodsynopsis"]["params"][] = array(
                "optional" => false,
                "type" => "void",
                "name" => "",
                "initializer" => "");
        }
        return "";
    }
    
    public function format_type_method_text($value, $tag) {
        $this->cchunk['methodsynopsis']['params'][count($this->cchunk['methodsynopsis']['params'])-1]['type'] = $value;
        return "";
    }
    
    public function format_parameter_method_text($value, $tag) {
        $this->cchunk['methodsynopsis']['params'][count($this->cchunk['methodsynopsis']['params'])-1]['name'] = $value;
        return "";
    }
    
    public function format_initializer_method_text($value, $tag) {
        $this->cchunk['methodsynopsis']['params'][count($this->cchunk['methodsynopsis']['params'])-1]['initializer'] = $value;
        return "";
    }
    
    public function format_refsynopsisdiv($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["methodsynopsis"]["firstsynopsis"]) 
            && $this->cchunk["methodsynopsis"]["firstsynopsis"]) {
            return "\n.SH SYNOPSIS\n";
        }
        if (!$open && isset($this->cchunk["methodsynopsis"]["firstsynopsis"]))
            $this->cchunk["methodsynopsis"]["firstsynopsis"] = false;
        return "";
    }
    
    public function format_methodsynopsis($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["methodsynopsis"]["firstsynopsis"]) 
            && $this->cchunk["methodsynopsis"]["firstsynopsis"] && $this->cchunk["appendlater"]) {
            $this->cchunk["appendlater"] = false;
            return "\n.SH SYNOPSIS\n";
        }
        if ($open)
            return "\n.br";
        $params = array();
        // write the formatted synopsis
        foreach ($this->cchunk['methodsynopsis']['params'] as $parameter)
            array_push( $params, ($parameter['optional'] ? "[" : "") . $parameter['type'] . 
                ($parameter['name'] ? " \\fI$" . $parameter['name'] . "\\fP" : "") . ($parameter['initializer'] ? " = " . $parameter['initializer'] : "") . ($parameter['optional'] ? "]" : "") );
        $ret = "\n(" . join($params, ", ") . ")";
        $this->cchunk['methodsynopsis']['params'] = array();
        
        // finally write what is in the buffer
        if (isset($this->cchunk["buffer"])) {
            $ret .= implode("", $this->cchunk["buffer"]);
            $this->cchunk["buffer"] = array();
        }
        if (isset($this->cchunk["methodsynopsis"]["firstsynopsis"]))
            $this->cchunk["methodsynopsis"]["firstsynopsis"] = false;
        return $ret;
    }
    
    public function newChunk() {
        $this->cchunk = $this->dchunk;
        $this->chunkOpen = true;
    }
    
    public function closeChunk() {
        $this->chunkOpen = false;
    }
    
    public function format_xref($open, $name, $attrs, $props) {
        if ($props['empty'])
            return "\n\"" . PhDHelper::getDescription($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"]) . "\"";
        return "";
    }
    
    public function format_verbatim($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.PP\n.nf";
        }
        return "\n.fi";
    }
    
    public function format_verbatim_text($value, $tag) {
        return "\n" . trim($value) . "\n";
    }
    
    public function format_refsect($open, $name, $attrs, $props) {
        if ($open && isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
            $this->cchunk["role"] = $attrs[PhDReader::XMLNS_DOCBOOK]["role"];
            if ($this->cchunk["role"] == "description") {
                $this->cchunk["appendlater"] = true;
            }
        }
        if (!$open)
            $this->cchunk["role"] = null;
        return "";  
    }
    
    // Returns the unformatted value without whitespaces (nor new lines) 
    public function format_text($value, $tag) {
        return trim(ereg_replace("[ \n\t]+", ' ', $value));
    }
    
    public function format_tgroup($open, $name, $attrs, $props) {
        if ($open) {
            $nbCols = $attrs[PhDReader::XMLNS_DOCBOOK]["cols"];
            $ret = "\n.TS\nbox, tab (|);\n";
            for ($i = 0; $i < $nbCols; $i++)
                $ret .= "c | ";
            $ret .= ".";
            return $ret;
        }
        return "\n.TE\n.PP";
    }
    
    public function format_thead($open, $name, $attrs, $props) {
        if ($open) {
            return "";
        }
        return "\n=";
    }
    
    public function format_row($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["firstitem"] = true;
            return "\n";
        }
        return "";
    }
    
    public function format_entry($open, $name, $attrs, $props) {
        if ($open) {
            if ($this->cchunk["firstitem"]) {
                $this->cchunk["firstitem"] = false;
                return "T{\n";
            }
            return "|T{\n";
        }
        return "\nT}";
    }
    
    public function format_segmentedlist($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.P";
        }
        $this->cchunk["segtitle"] = array();
        return "\n";
    }
    
    public function format_seglistitem($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["segindex"]))
            $this->cchunk["segindex"] = 0;
        return "";
    }
    
    public function format_seg($open, $name, $attrs, $props) {
        if (! (isset($this->cchunk["segtitle"]) && isset($this->cchunk["segtitle"][$this->cchunk["segindex"]])) )
            return "";
        if ($open) {
            return "\n.br\n\\fB" . $this->cchunk["segtitle"][$this->cchunk["segindex"]] . ":\\fR";
        }
        $this->cchunk["segindex"]++;
        return "";
    }
    
    public function format_segtitle_text($value, $tag) {
        if (isset($this->cchunk["segtitle"]))
            $this->cchunk["segtitle"][] = $value;
        return "";
    }
    
    public function format_manvolnum($open, $name, $attrs, $props) {
        if ($open) {
            return "(";
        }
        return ")";
    }
    
    public function format_ooclass_name_text($value, $tag) {
        return "\n.br\n\\fI" . $value . "\\fP";
    }
    
    public function format_indent($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.PP\n.RS";
        }
        return "\n.RE\n.PP";
    }
    
    public function format_tag_text($value, $tag) {
        return "\n<" . $value . ">";
    }
    
    // Convert the function name to a Unix valid filename
    public function toValidName($functionName) {
        return str_replace(array("::", "->", "()"), array(".", ".", ""), $functionName);
    }
}