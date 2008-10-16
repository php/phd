<?php

abstract class peartheme extends PhDTheme {
    protected $elementmap = array(
        'acronym'               => 'span',
        'article'               => 'format_container_chunk',
        'answer'                => 'format_answer',
        'appendix'              => 'format_container_chunk',
        'author'                => array(
            /* DEFAULT */          'format_editedby',
            'authorgroup'       => 'format_suppressed_tags',
        ),
        'authorgroup'           => 'format_editedby',
        'bibliography'          => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'book'                  => 'format_root_chunk',
        'callout'               => 'format_callout',
        'calloutlist'           => 'format_calloutlist',
        'copyright'             => 'format_copyright',
        'coref'                 => 'format_suppressed_tags',
        'chapter'               => 'format_container_chunk',
        'classname'             => 'b',
        'colophon'              => 'format_chunk',
        'constant'              => 'b',
        'emphasis'              => 'format_emphasis',
        'filename'              => array(
            /* DEFAULT */          'tt',
            'titleabbrev'       => 'format_suppressed_tags',
        ),
        'firstname'             => 'format_suppressed_tags',
        'funcdef'               => 'format_funcdef',
        'funcprototype'         => 'format_funcprototype',

        'funcsynopsisinfo'      => 'format_programlisting',
        'funcsynopsis'          => 'div',
        'function'              => 'b',
        'editor'                => 'div',
        'email'                 => 'tt',
        'glossary'              => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'glossentry'            => 'format_suppressed_tags',
        'glossdef'              => 'format_glossdef',
        'glosslist'             => 'dl',
        'glossterm'             => 'format_glossterm',
        'guimenu'               => 'format_guimenu',
        'holder'                => 'format_holder',
        'important'             => 'format_admonition',
        'info'                  => array(
            /* DEFAULT */          false,
//             'chapter'           => 'format_comment',
            'refsynopsisdiv'    => 'format_comment',
            'warning'           => 'format_suppressed_tags',
        ),
        'index'                 => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'informalexample'       => 'div',
        'informaltable'         => array(
            /* DEFAULT */          'format_table',
            'para'              => 'format_para_informaltable',
        ),
        'legalnotice'           => 'format_chunk',
        'link'					=> 'format_link',
        'listitem'              => array(
            /* DEFAULT */          false,
            'varlistentry'      => 'dd',
            'itemizedlist'      => 'li',
        ),
        'literal'               => 'tt',
        'literallayout'         => 'p',
        'menuchoice'            => 'format_suppressed_tags',
        'methodname'            => 'tt',
        'note'                  => 'format_admonition',
        'para'                  => array(
            /* DEFAULT */          false,
            'warning'           => 'format_warning_para',
            'important'         => 'format_suppressed_tags',
        ),
        'paramdef'              => 'format_paramdef',
        'parameter'             => array(
            /* DEFAULT */          false,
            'paramdef'           => 'format_suppressed_tags',
        ),
        'part'                  => 'format_container_chunk',
        'preface'               => 'format_container_chunk',
        'programlisting'        => 'format_programlisting',
        'prompt'                => 'tt',
        'pubdate'               => 'p',
        'qandaentry'            => 'format_qandaentry',
        'qandaset'              => 'format_qandaset',
        'question'              => 'format_question',
        'replaceable'           => 'format_replaceable',
        'refentry'              => 'format_chunk',
        'reference'             => 'format_container_chunk',
        'phpdoc:exception'      => 'format_exception_chunk',
        'refname'               => 'h1',
        'refnamediv'            => 'format_suppressed_tags',
        'refpurpose'            => 'format_refpurpose',
        'refsection'            => 'format_container_chunk',
        'refsynopsisdiv'        => 'format_refsynopsisdiv',
        'screen'                => 'format_screen',
        'sect1'                 => 'format_chunk',
        'sect2'                 => 'format_chunk',
        'sect3'                 => 'format_chunk',
        'sect4'                 => 'format_chunk',
        'sect5'                 => 'format_chunk',
        'section'               => 'format_container_chunk',
        'set'                   => 'format_root_chunk',
        'setindex'              => 'format_chunk',
        'simpara'               => array(
            /* DEFAULT */          false,
            'entry'             => 'p',
            'listitem'          => 'p',
            'warning'           => 'format_warning_para',
        ),
        'subtitle'              => 'format_subtitle',
        'surname'               => 'format_surname',
        'synopsis'              => 'format_programlisting',
        'table'                 => 'format_table',
        'title'                 => array(
            /* DEFAULT */          false,
            'article'           => 'format_container_chunk_title',
            'appendix'          => 'format_container_chunk_title',
            'chapter'           => 'format_container_chunk_title',
            //'example'           => 'format_example_title',
            'part'              => 'format_container_chunk_title',
            'preface'           => 'format_container_chunk_title',
            'info'              => array(
                /* DEFAULT */      false,
                'article'       => 'format_container_chunk_title',
                'appendix'      => 'format_container_chunk_title',
                'chapter'       => 'format_container_chunk_title',
                //'example'       => 'format_example_title',
                'part'          => 'format_container_chunk_title',
                'section'       => array(
                    /* DEFAULT */  'format_container_chunk_title',
                    'section'   => array(
                                   'h2',
                      'section' => array(
                                   'h3',
                       'section'=> array(
                                   'h4',
                       'section'=> 'h5'
                       ),
                      ),
                    ),
                ),
                'warning'       => 'format_warning_title',
            ),
            'refsect1'          => 'h2',
            'refsect2'          => 'h3',
            'refsect3'          => 'h4',
            'section'           => array(
                /* DEFAULT */      'format_container_chunk_title',
                'section'       => array(
                    /* DEFAULT */  'h2',
                    'section'   => array(
                     /* DEFAULT */ 'h3',
                     'section'  => array(
                      /* DEFAULT */'h4',
                      'section' => 'h5'
                     ),
                    ),
                ),
            ),
            'table'             => 'format_table_title',
            'warning'           => 'format_warning_title',
        ),
        'tbody'                 => 'tbody',
        'term'                  => 'dt',
        'uri'                   => 'format_uri',
        'userinput'             => 'format_userinput',
        'variablelist'          => 'div',
        'varlistentry'          => 'dl',
        'varname'               => 'tt',
        'warning'               => 'format_warning',
        'xref'                  => 'format_link',
    );

    protected $textmap =        array(
        'classname'             => array(
            /* DEFAULT */          false,
            'refname'           => 'format_refname_classname_text',
        ),
        'filename'              => array(
            /* DEFAULT */          false,
            'titleabbrev'       => 'format_suppressed_text',
        ),
        'function'              => array(
            /* DEFAULT */          'format_function_text',
            'funcdef'           => false,
            'refname'           => 'format_refname_function_text',
        ),
        'programlisting'        => 'format_programlisting_text',
        'refname'               => 'format_refname_text',
        'year'                  => 'format_year',
    );

    public $role        = false;
    protected $chunked = true;
    protected $lang = "en";

    protected $CURRENT_ID = "";

    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array(
        "fieldsynopsis"                => array(
            "modifier"                          => "public",
        ),
        "container_chunk"              => null,
        "qandaentry"                   => array(
        ),
        "examples"                     => 0,
        "verinfo"                      => false,
        "refname"                      => array(),
    );

    public function __construct(array $IDs, $ext = "php", $chunked = true) {
        parent::__construct($IDs, $ext);
        $this->ext = $ext;
        $this->chunked = $chunked;
    }

    public function format_chunk($open, $name, $attrs, $props) {
        $id = null;
        if (isset($attrs[PhDReader::XMLNS_XML]["id"])) {
            $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];
        }
        if ($props["isChunk"]) {
            $this->cchunk = $this->dchunk;
        }
        if (isset($props["lang"])) {
            $this->lang = $props["lang"];
        }
        if ($name == "refentry") {
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                $this->cchunk["verinfo"] = !($attrs[PhDReader::XMLNS_DOCBOOK]["role"] == "noversion");
            } else {
                $this->cchunk["verinfo"] = true;
            }
        }
        if ($name == "legalnotice") {
            if ($open) {
                return '<div class="' . $name . '" ' . ($id ? "id=\"{$id}\"" : "") . '">';
            }
            return "</div>\n";
        }
        return false;
    }

    public function format_container_chunk($open, $name, $attrs, $props) {
        if (!isset($attrs[PhDReader::XMLNS_XML]['id'])) {
            if ($open) {
                return "<div class=\"{$name}\">";
            } else {
                return "</div>\n";
            }
        }
        $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];

        if (!$open) {
            return "</div>\n";
        }

        if ($props["isChunk"]) {
            $this->cchunk = $this->dchunk;
        }
        $chunks = PhDHelper::getChildren($id);
        if (count($chunks)) {
            $content = "<div class=\"TOC\">\n <dl>\n  <dt><b>" . $this->autogen("toc", $props["lang"]) . "</b></dt>\n";
            foreach ($chunks as $chunkid => $junk) {
                $long = $this->format->TEXT(PhDHelper::getDescription($chunkid, true));
                $short = $this->format->TEXT(PhDHelper::getDescription($chunkid, false));
                if ($long && $short && $long != $short) {
                    $desc = $short. '</a> -- ' .$long;
                } else {
                    $desc = ($long ? $long : $short). '</a>';
                }
                if ($this->chunked) {
                    $content .= "  <dt><a href=\"{$chunkid}.{$this->ext}\">" . $desc . "</dt>\n";
                } else {
                    $content .= "  <dt><a href=\"#{$chunkid}\">" . $this->format->TEXT(PhDHelper::getDescription($chunkid, false)) . "</a></dt>\n";
                }
            }
            $content .= " </dl>\n</div>\n";
            $this->cchunk["container_chunk"] = $content;
        }
        return "<div class=\"{$name}\" id=\"{$id}\">";
    }

    public function format_exception_chunk($open, $name, $attrs, $props) {
        return $this->format_container_chunk($open, "reference", $attrs, $props);
    }

    public function format_root_chunk($open, $name, $attrs, $props) {
        $this->CURRENT_ID = $id = $attrs[PhDReader::XMLNS_XML]["id"];
        if ($open) {
            return "<div class=\"{$name}\">";
        }

        $chunks = PhDHelper::getChildren($id);
        $content = '<p><b>' . $this->autogen("toc", $props["lang"]) . "</b></p>\n";
        if (count($chunks)) {
            $content .= '<ul class="chunklist chunklist_'.$name.'">' . "\n";
            foreach ($chunks as $chunkid => $junk) {
                $href = $this->chunked ? $chunkid .'.'. $this->ext : "#$chunkid";
                $long = $this->format->TEXT(PhDHelper::getDescription($chunkid, true));
                $short = $this->format->TEXT(PhDHelper::getDescription($chunkid, false));
                $content .= ' <li><a href="' .$href. '">' .($long ? $long : $short). '</a>';
                $children = PhDHelper::getChildren($chunkid);
                if (count($children)) {
                    $content .= "\n" . '  <ul class="chunklist chunklist_'.$name.' chunklist_children">' . "\n";
                    foreach (PhDHelper::getChildren($chunkid) as $childid => $junk) {
                        $href = $this->chunked ? $childid .'.'. $this->ext : "#$childid";
                        $long = $this->format->TEXT(PhDHelper::getDescription($childid, true));
                        $short = $this->format->TEXT(PhDHelper::getDescription($childid, false));
                        $content .= '   <li><a href="' .$href. '">' .($long ? $long : $short). "</a></li>\n";
                    }
                    $content .= "  </ul>\n ";
                }
                $content .= "</li>\n";
            }
            $content .= "</ul>\n";
        }
        $content .= "</div>\n";

        return $content;
    }

    public function format_link($open, $name, $attrs, $props) {
        if ($open) {
            $content = $fragment = "";
            $class = $name;

            if(isset($attrs[PhDReader::XMLNS_DOCBOOK]["linkend"])) {
                $linkto = $attrs[PhDReader::XMLNS_DOCBOOK]["linkend"];
                $id = $href = PhDHelper::getFilename($linkto);

                if ($id != $linkto) {
                    $fragment = "#$linkto";
                }
                if ($this->chunked) {
                    $href .= ".".$this->ext;
                }
            } elseif(isset($attrs[PhDReader::XMLNS_XLINK]["href"])) {
                $href = $attrs[PhDReader::XMLNS_XLINK]["href"];
                $class .= " external";
            }
            if ($name == "xref") {
                if ($this->chunked) {
                    $link = $href;
                } else {
                    $link = "#";
                    if (isset($linkto)) {
                        $link .= $linkto;
                    } else {
                        $link .= $href;
                    }
                }
                return '<a href="' .$link. '" class="' .$class. '">' .($content.PhDHelper::getDescription($id, false)). '</a>';
            } elseif ($props["empty"]) {
                if ($this->chunked) {
                    $link = "";
                } else {
                    $link = "#";
                }
                return '<a href="' .$link.$href.$fragment. '" class="' .$class. '">' .$content.$href.$fragment. '</a>';
            } else {
                if ($this->chunked) {
                    $link = $href.$fragment;
                } elseif(isset($linkto)) {
                    if ($fragment) {
                        $link = $fragment;
                    } else {
                        $link = "#$href";
                    }
                } else {
                    $link = $href;
                }
                return '<a href="' .$link. '" class="' .$class. '">' .$content;
            }
        }
        return "</a>";
    }

    public function format_container_chunk_title($open, $name, $attrs) {
        if ($open) {
            return "<h1>";
        }
        $ret = "";
        if ($this->cchunk["container_chunk"]) {
            $ret = $this->cchunk["container_chunk"];
            $this->cchunk["container_chunk"] = null;
        }
        return "</h1>\n" .$ret;
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        if ($open) {
            $idstr = "";
            if (isset($attrs[PhDReader::XMLNS_XML]["id"])) {
                $id = $attrs[PhDReader::XMLNS_XML]["id"];
                $idstr = ' id="' .$id. '" name="' .$id. '"';
            }
            return '<' .$tag. ' class="' .$name. '"' . $idstr. '>' . ($props["empty"] ? "</{$tag}>" : "");
        }
        return '</' .$tag. '>';
    }

    public function format_para_informaltable($open, $name, $attrs, $props) {
        if ($open) {
            return '</p>' . $this->format_table($open, $name, $attrs, $props);
        }
        return $this->format_table($open, $name, $attrs, $props) . '<p>';
    }

    public function format_programlisting($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"])) {
                $this->role = $attrs[PhDReader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = "default";
            }

            return '<table class="EXAMPLE-CODE" bgcolor="#eeeeee" border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><pre class="'. ($this->role ? $this->role : 'programlisting') .'">';
        }
        $this->role = false;
        return "</pre></td></tr></table>\n";
    }

    public function format_programlisting_text($value, $tag) {
        switch($this->role) {
        case "php":
            if ( strrpos($value, "<?php") || strrpos($value, "?>") )
                return highlight_string(trim($value), 1);
            else return highlight_string("<?php\n" . trim($value) . "\n?>", 1);
            break;
        default:
            return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
        }
    }

    public function format_screen($open, $name, $attrs) {
        if ($open) {
            return '<table class="EXAMPLE-CODE" bgcolor="#eeeeee" border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><pre class="screen">';
        }
        return "</pre></td></tr></table>\n";
    }

    public function CDATA($str) {
        if (!$this->role)
            return str_replace(array("\n", " "), array("<br/>", "&nbsp;"), htmlspecialchars($str, ENT_QUOTES, "UTF-8"));
        switch($this->role) {
        case "php":
            if ( strrpos($str, "<?php") || strrpos($str, "?>") )
                return (highlight_string(trim($str), 1));
            else return (highlight_string("<?php\n" . trim($str) . "\n?>", 1));
            break;
        default:
            return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
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

    public function format_surname($open, $name, $attrs) {
        /* Add a space before it, so firstname and surname are separated */
        return ' ';
    }

    public function format_subtitle($open, $name, $attrs) {
        if ($open)
            return '<p><font color="red">';
        return '</font></p>';
    }

    public function format_editedby($open, $name, $attrs, $props) {
        if ($open)
            return "<h2 class=\"EDITEDBY\">" . $this->autogen("editedby", $props["lang"]) . "</h2>";

    }

    public function format_copyright($open, $name, $attrs) {
        if ($open) {
            if ($this->chunked) {
                return '<p class="'.$name.'"><a href="copyright.' . $this->ext . '">Copyright</a> &copy; ';
            } else {
                return '<p class="'.$name.'"><a href="#copyright">Copyright</a> &copy; ';
            }
        }
        return '</p>';
    }

    public function format_comment($open, $name, $attrs) {
        if ($open) {
            return '<!-- ';
        }
        return '-->';
    }

    public function format_holder($open, $name, $attrs, $props) {
        if ($open)
            return $this->autogen("by", $props["lang"]) . " ";
    }

    public function format_year($value) {
        return $value . ", ";
    }

    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            return '<blockquote class="' . $name . '"><p><b>'.$this->autogen($name, $props["lang"]). ': </b>';
        }
        return "</p></blockquote>\n";
    }

    public function format_table($open, $name, $attrs, $props) {
        if ($open) {
            return '<table border="1" class="'.$name.'">';
        }
        return "</table>\n";
    }

    public function format_entry($open, $name, $attrs, $props) {
        if ($open) {
            if ($props['empty']) {
                return '<td></td>';
            }
            return '<td>';
        }
        return "</td>";
    }

    public function format_th_entry($open, $name, $attrs) {
        if ($open) {
            $colspan = PhDFormat::colspan($attrs[PhDReader::XMLNS_DOCBOOK]);
            return '<th colspan="' .((int)$colspan). '">';
        }
        return '</th>';
    }

    public function format_table_title($open, $name, $attrs, $props) {
        if ($props["empty"])
            return "";
        if ($open) {
            return '<p><b>';
        }
        return '</b></p>';
    }

    public function format_userinput($open, $name, $attrs, $props) {
        if ($open) {
            return '<tt class="'.$name.'"><b>';
        }
        return "</b></tt>";
    }

    function format_replaceable($open, $name, $attrs, $props) {
        if ($open) {
            return '<tt class="'.$name.'"><i>';
        }
        return "</i></tt>";
    }

    public function format_warning($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="warning" style="border: 3px double black; padding: 5px">' . "\n";
        }
        return "</div>\n";
    }

    public function format_warning_title($open, $name, $attrs, $props) {
        if ($open) {
            return '<strong class="warning_title" style="display:block; text-align: center; width:100%">';
        }
        return "</strong>\n";
    }

    public function format_warning_para($open, $name, $attrs, $props) {
        if ($open) {
            if (!$props['sibling']) {
                return '<strong>' . $this->autogen("warning", $props["lang"]) . "</strong>\n"
                    . '<p>';
            }
            return '<p>';
        }
        return "</p>\n";
    }

    public function format_refname_function_text($value) {
        $this->cchunk["refname"][] = '<b class="function">' . $this->format->TEXT($value . '()') . '</b>';
        return false;
    }

    public function format_refname_classname_text($value) {
        $this->cchunk["refname"][] = '<b class="classname">' . $this->format->TEXT($value) . '</b>';
        return false;
    }

    public function format_refpurpose($open, $tag, $attrs) {
        if ($open) {
            $refnames = implode(' ', $this->cchunk["refname"]);
            return '<div class="refnamediv">'. $refnames. ' -- ';
        }
        return "</div>\n";
    }
    public function format_refname_text($value, $tag) {
        $this->cchunk["refname"][] = $this->format->TEXT($value);
        return false;
    }

    public function format_function_text($value) {
        return $this->format->TEXT($value."()");
    }

    public function format_paramdef($open, $name, $attrs, $props) {
        if ($open && $props["sibling"] == 'paramdef')
            return ' , ';
        return false;
    }

    public function format_funcdef($open, $name, $attrs, $props) {
        if (!$open)
            return ' ( ';
        return false;
    }

    public function format_funcprototype($open, $name, $attrs, $props) {
        if ($open) {
            return '<p><code class="' . $name . '">';
        }
        else return ")</code></p>";
    }

    public function format_uri($open, $name, $attrs, $props) {
        if ($open) {
            return '<font color="red">';
        }
        else return "</font>";
    }

    public function format_refsynopsisdiv($open, $name, $attrs, $props) {
        if ($open) {
            return '<h2 class="refsynopsisdiv">Synopsis</h2><p>';
        }
        return '</p>';
    }

    public function format_guimenu($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"])
                return '-&gt;<span class="guimenu"><i>';
            return '<span class="guimenu"><i>';
        }
        return '</i></span>';
    }

    /* FIXME: This function is a crazy performance killer */
    public function qandaset($stream) {
        $xml = stream_get_contents($stream);

        $old = libxml_use_internal_errors(true);
        $doc = new DOMDocument("1.0", "UTF-8");
        $doc->preserveWhitespace = false;
        $doc->loadXML(html_entity_decode(str_replace("&", "&amp;amp;", "<div>$xml</div>"), ENT_QUOTES, "UTF-8"));
        if ($err = libxml_get_errors()) {
            print_r($err);
            libxml_clear_errors();
        }
        fclose($stream);
        libxml_use_internal_errors($old);

        $xpath = new DOMXPath($doc);
        $nlist = $xpath->query("//div/dl/dt/strong");
        $ret = '<div class="qandaset"><ol class="qandaset_questions">';
        $i = 0;
        foreach($nlist as $node) {
            $ret .= '<li><a href="#' .($this->cchunk["qandaentry"][$i++]). '">' .($node->textContent). '</a></li>';
        }

        return $ret.'</ol>'.$xml.'</div>';
    }
    public function format_qandaentry($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["qandaentry"][] = $this->CURRENT_ID . ".entry" . count($this->cchunk["qandaentry"]);
            return '<dl>';
        }
        return '</dl>';
    }
    public function format_answer($open, $name, $attrs) {
        if ($open) {
            return '<dd><a name="' .end($this->cchunk["qandaentry"]).'"></a>';
        }
        return "</dd>";
    }
    public function format_question($open, $name, $attrs) {
        if ($open) {
            return '<dt><strong>';
        }
        return '</strong></dt>';
    }

    public function format_emphasis($open, $name, $attrs) {
        if (isset($attrs[PhDReader::XMLNS_DOCBOOK]["role"]) && $attrs[PhDReader::XMLNS_DOCBOOK]["role"] == "bold")
            $role = "b";
        else $role = "i";
        if ($open) {
            return '<' . $role . ' class="' . $name . '">';
        }
        return "</{$role}>";
    }

    public function format_glossterm($open, $name, $attrs) {
        if ($open) {
            return '<dt><b>';
        }
        return "</b></dt>";
    }

    public function format_glossdef($open, $name, $attrs) {
        if ($open) {
            return '<dd><p>';
        }
        return "</p></dd>";
    }

    public function format_calloutlist($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["callouts"] = 0;
            return '<table>';
        }
        return '</table>';
    }
    public function format_callout($open, $name, $attrs) {
        if ($open) {
            return '<tr><td><a href="#'.$attrs[PhDReader::XMLNS_DOCBOOK]["arearefs"].'">(' .++$this->cchunk["callouts"]. ')</a></td><td>';
        }
        return "</td></tr>\n";
    }
}
