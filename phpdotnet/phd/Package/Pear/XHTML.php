<?php
namespace phpdotnet\phd;

abstract class Package_Pear_XHTML extends Format_Abstract_XHTML {
    private $myelementmap = array(
        'abstract'              => 'format_div',
        'abbrev'                => 'abbr',
        'acronym'               => 'span',
        'article'               => 'format_container_chunk',
        'alt'                   => 'format_suppressed_tags',
        'answer'                => 'format_answer',
        'appendix'              => 'format_container_chunk',
        'application'           => 'span',
        'arg'                   => 'format_suppressed_tags',
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
        'blockquote'            => 'blockquote',
        'book'                  => 'format_root_chunk',
        'caution'               => 'format_admonition',
        'callout'               => 'format_callout',
        'calloutlist'           => 'format_calloutlist',
        'citetitle'             => 'i',
        'colspec'               => 'format_colspec',
        'copyright'             => 'format_copyright',
        'coref'                 => 'format_suppressed_tags',
        'chapter'               => 'format_container_chunk',
        'classname'             => 'strong',
        'co'                    => 'format_co',
        'colophon'              => 'format_chunk',
        'command'               => 'strong',
        'cmdsynopsis'           => 'format_cmdsynopsis',
        'computeroutput'        => 'span',
        'constant'              => 'strong',
        'date'                  => 'p',
        'emphasis'              => 'format_emphasis',
        'entry'                 => array (
            /* DEFAULT */          'format_entry',
            'row'               => array(
                /* DEFAULT */      'format_entry',
                'thead'         => 'format_th_entry',
                'tfoot'         => 'format_th_entry',
                'tbody'         => 'format_entry',
            ),
        ),
        'example'               => 'format_div',
        'editor'                => 'format_div',
        'email'                 => 'tt',
        'figure'                => 'format_div',
        'filename'              => array(
            /* DEFAULT */          'tt',
            'titleabbrev'       => 'format_suppressed_tags',
        ),
        'firstname'             => 'format_suppressed_tags',
        'formalpara'            => 'p',
        'funcdef'               => 'format_funcdef',
        'funcprototype'         => 'format_funcprototype',
        'funcsynopsisinfo'      => 'format_programlisting',
        'funcsynopsis'          => 'format_div',
        'function'              => 'strong',
        'glossary'              => array(
            /* DEFAULT */          'format_div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'glossentry'            => 'format_suppressed_tags',
        'glossdef'              => 'format_glossdef',
        'glosslist'             => 'format_dl',
        'glossterm'             => 'format_glossterm',
        'guimenu'               => 'format_guimenu',
        'holder'                => 'format_holder',
        'imagedata'             => 'format_imagedata',
        'imageobject'           => 'format_div',
        'important'             => 'format_admonition',
        'info'                  => array(
            /* DEFAULT */          'format_div',
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
        'informalexample'       => 'format_div',
        'informaltable'         => array(
            /* DEFAULT */          'format_table',
            'para'              => 'format_para_informaltable',
        ),
        'itemizedlist'          => 'format_itemizedlist',
        'initializer'           => 'format_initializer',
        'legalnotice'           => 'format_chunk',
        'link'					=> 'format_link',
        'listitem'              => array(
            /* DEFAULT */          'li',
            'varlistentry'      => 'dd',
            'itemizedlist'      => 'li',
        ),
        'literal'               => 'tt',
        'literallayout'         => 'format_literallayout',
        'mediaobject'           => 'format_mediaobject',
        'member'                => 'li',
        'menuchoice'            => 'format_suppressed_tags',
        'methodparam'           => 'format_methodparam',
        'methodname'            => 'tt',
        'methodsynopsis'        => 'format_methodsynopsis',
        'modifier'              => 'span',
        'note'                  => 'format_admonition',
        'orderedlist'           => 'format_orderedlist',
        'package'               => 'strong',
        'para'                  => array(
            /* DEFAULT */          'format_para',
            'question'          => 'span',//can't ignore it since it's defined in format
            'warning'           => 'format_warning_para',
            'important'         => 'format_suppressed_tags',
        ),
        'paramdef'              => 'format_paramdef',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
            'paramdef'          => 'format_suppressed_tags',
        ),
        'part'                  => 'format_container_chunk',
        'partintro'             => 'format_div',
        'personname'            => 'format_personname',
        'personblurb'           => 'format_div',
        'phrase'                => 'span',
        'phd:pearapi'           => 'format_phd_pearapi',
        'preface'               => 'format_chunk',
        'programlisting'        => 'format_programlisting',
        'prompt'                => 'tt',
        'pubdate'               => 'p',
        'qandadiv'              => 'format_div',
        'qandaentry'            => 'format_qandaentry',
        'qandaset'              => 'format_qandaset',
        'question'              => 'format_question',
        'quote'                 => 'format_quote',
        'replaceable'           => 'format_replaceable',
        'refentry'              => 'format_chunk',
        'reference'             => 'format_container_chunk',
        'phd:toc'               => 'format_phd_toc',
        'phpdoc:exception'      => 'format_exception_chunk',
        'releaseinfo'           => 'format_div',
        'replaceable'           => 'span',
        'refname'               => 'format_refname',
        'refnamediv'            => 'format_suppressed_tags',
        'refpurpose'            => 'format_refpurpose',
        'refsection'            => 'format_container_chunk',
        'refsynopsisdiv'        => 'format_refsynopsisdiv',
        'row'                   => 'format_row',
        'screen'                => 'format_screen',
        'screenshot'            => 'div',
        'sect1'                 => 'format_chunk',
        'sect2'                 => 'format_chunk',
        'sect3'                 => 'format_chunk',
        'sect4'                 => 'format_chunk',
        'sect5'                 => 'format_chunk',
        'section'               => 'format_chunk',
        'set'                   => 'format_root_chunk',
        'setindex'              => 'format_chunk',
        'simpara'               => array(
            /* DEFAULT */          'format_para',
            'entry'             => 'p',
            'listitem'          => 'p',
            'warning'           => 'format_warning_para',
        ),
        'simplelist'            => 'format_itemizedlist', /* FIXME: simplelists has few attributes that need to be implemented */
        'spanspec'              => 'format_suppressed_tags',
        'subtitle'              => 'format_subtitle',
        'superscript'           => 'sup',
        'subscript'             => 'sub',
        'surname'               => 'format_surname',
        'symbol'                => 'span',
        'synopsis'              => 'format_programlisting',
        'tag'                   => 'format_tag',
        'table'                 => 'format_table',
        'tbody'                 => 'tbody',
        'tgroup'                => 'format_tgroup',
        'tfoot'                 => 'format_th',
        'thead'                 => 'format_th',
        'title'                 => array(
            /* DEFAULT */          'h1',
            'article'           => 'format_container_chunk_title',
            'appendix'          => 'format_container_chunk_title',
            'chapter'           => 'format_container_chunk_title',
            //'example'           => 'format_example_title',
            'part'              => 'format_container_chunk_title',
            'preface'           => 'format_container_chunk_title',
            'info'              => array(
                /* DEFAULT */      'h1',
                'article'       => 'format_container_chunk_title',
                'appendix'      => 'format_container_chunk_title',
                'chapter'       => 'format_container_chunk_title',
                //'example'       => 'format_example_title',
                'informaltable' => 'format_table_title',
                'part'          => 'format_container_chunk_title',
                'refsection'    => 'format_container_chunk_title',
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
                'table'         => 'format_table_title',
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
        'tip'                   => 'format_admonition',
        'token'                 => 'tt',
        'type'                  => 'span',
        'titleabbrev'           => 'format_suppressed_tags',
        'term'                  => 'dt',
        'uri'                   => 'tt',
        'userinput'             => 'format_userinput',
        'variablelist'          => 'format_div',
        'varlistentry'          => 'format_dl',
        'varname'               => 'tt',
        'void'                  => 'format_void',
        'warning'               => 'format_warning',
        'xref'                  => 'format_link',
        'year'                  => 'span',
    );

    private $mytextmap =        array(
        'classname'             => array(
            /* DEFAULT */          false,
            'refname'           => 'format_refname_classname_text',
            'ooclass'          => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_ooclass_classname_text',
            ),

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
        'segtitle'             => 'format_segtitle_text',
        'affiliation'          => 'format_suppressed_text',
        'contrib'              => 'format_suppressed_text',
        'shortaffil'           => 'format_suppressed_text',
        'titleabbrev'          => 'format_suppressed_text',
        'screen'               => 'format_screen_text',
        'alt'                  => 'format_alt_text',
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
        'phd:pearapi'           => 'format_phd_pearapi_text',
        'programlisting'        => 'format_programlisting_text',
        'refname'               => 'format_refname_text',
        'year'                  => 'format_year',
    );

    /**
    * If whitespace should be trimmed.
    * Helpful for programlistings that are encapsulated in <pre> tags
    *
    * @var boolean
    *
    * @see CDATA()
    */
    public $trim    = false;

    /**
    * URL prefix for all API doc link generated with <phd:pearapi>
    *
    * @var string
    */
    public $phd_pearapi_urlprefix = 'http://pear.php.net/package/';

    /**
    * Name of the ID currently being processed
    *
    * @var string
    */
    protected $CURRENT_ID = "";

    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array(
        'fieldsynopsis'                => array(
            'modifier'                          => 'public',
        ),
        'container_chunk'              => null,
        'qandaentry'                   => array(
        ),
        'examples'                     => 0,
        'verinfo'                      => false,
        'refname'                      => array(),
    );

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    public function getDefaultElementMap() {
        return $this->myelementmap;
    }

    public function getDefaultTextMap() {
        return $this->mytextmap;
    }

    protected function createTOC($id, $lang) {
        if (!$this->getChildrens($id)) {
            return "";
        }
        $toc = '<strong>' . $this->autogen('toc', $lang) . '</strong><ul>';
        foreach ($this->getChildrens($id) as $child) {
            $isLDesc = null;
            $isSDesc = null;
            $long = $this->parse($this->getLongDescription($child, $isLDesc));
            $short = $this->getShortDescription($child, $isSDesc);
            $link = $this->createLink($child);

            $list = "";
            if ($this->cchunk["name"] === "book" || $this->cchunk["name"] === "set") {
                if ($this->getChildrens($child)) {
                    $list = "<ul>";
                    foreach ($this->getChildrens($child) as $subChild) {
                        $isSubLDesc = null;
                        $isSubSDesc = null;
                        $subLong = $this->parse($this->getLongDescription($subChild, $isLDesc));
                        $subShort = $this->getShortDescription($subChild, $isSubSDesc);

                        $href = $this->createLink($subChild);
                        if ($isSubLDesc && $isSubSDesc) {
                            $list .= '<li><a href="' . $href . '">' . $subShort . '</a> — ' . $subLong . "</li>\n";
                        } else {
                            $list .= '<li><a href="' . $href . '">' . ($subLong ?: $subShort) . '</a>' . "</li>\n";
                        }
                    }
                    $list .="</ul>";
                }
            }
            if ($isLDesc && $isSDesc) {
                $toc .= '<li><a href="' . $link . '">' . $short . '</a> — ' . $long . $list . "</li>\n";
            } else {
                $toc .= '<li><a href="' . $link . '">' . ($long ?: $short) . '</a>' . $list .  "</li>\n";
            }
        }
        $toc .= "</ul>\n";
        return $toc;
    }

    /**
    * Clean up HTML from empty paragraph tags (<p>).
    *
    * @param string $str String to clean up
    *
    * @return string Cleaned up string.
    */
    protected function cleanHtml($str)
    {
        return preg_replace('#<p>\\s*</p>#s', '', $str);
    }

    public function format_chunk($open, $name, $attrs, $props)
    {
        $id = null;
        if (isset($attrs[Reader::XMLNS_XML]['id'])) {
            $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]['id'];
        }
        if ($props['isChunk']) {
            $this->cchunk = $this->dchunk;
        }
        if (isset($props['lang'])) {
            $this->lang = $props['lang'];
        }
        if ($name == 'refentry') {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['role'])) {
                $this->cchunk['verinfo'] = !($attrs[Reader::XMLNS_DOCBOOK]['role'] == 'noversion');
            } else {
                $this->cchunk['verinfo'] = true;
            }
        }
        $this->CURRENT_CHUNK = $id;
        $this->notify(Render::CHUNK, $open ? Render::OPEN : Render::CLOSE);
        if ($name == 'legalnotice') {
            if ($open) {
                return '<div class="' . $name . '" ' . ($id ? "id=\"{$id}\"" : '') . '">';
            }
            return "</div>\n";
        }
        return false;
    }

    public function format_container_chunk($open, $name, $attrs, $props)
    {                
        if (!isset($attrs[Reader::XMLNS_XML]['id'])) {
            if ($open) {
                return "<div class=\"{$name}\">";
            } else {
                return "</div>\n";
            }
        }
        $this->CURRENT_ID = $id = $attrs[Reader::XMLNS_XML]['id'];
        $this->CURRENT_CHUNK = $id;
        $this->notify(Render::CHUNK, $open ? Render::OPEN : Render::CLOSE);
        if (!$open) {
            return "</div>\n";
        }

        if ($props['isChunk']) {
            $this->cchunk = $this->dchunk;
        }
        $toc = $this->createTOC($id, $props["lang"]);
        if ($toc) {
            $toc = "<div class=\"TOC\">\n" . $toc . "</div>\n";
        }
        $this->cchunk['container_chunk'] = $toc;

        return "<div class=\"{$name}\" id=\"{$id}\">";        
    }

    public function format_div($open, $name, $attrs, $props)
    {
       if ($open) {
            return $this->escapePara()
                . '<div class="' . $name . '">';
        }
        return '</div>' . $this->restorePara();
    }
    
    public function format_exception_chunk($open, $name, $attrs, $props)
    {
        return $this->format_container_chunk($open, 'reference', $attrs, $props);
    }

    /**
     * Formatting for the root element of a chunk.
     *
     * @param bool   $open  Whether we should open or close this element.
     * @param string $name  Name of the element
     * @param array  $attrs Attributes present for the element. Array keys are the attribute namespaces.
     * @param array  $props Associative array of additional properties
     *
     * @return string
     */
    public function format_root_chunk($open, $name, $attrs, $props)
    {
        $this->cchunk = $this->dchunk;
        $this->cchunk["name"] = $name;
        if(isset($attrs[Reader::XMLNS_XML]["id"])) {
            $id = $attrs[Reader::XMLNS_XML]["id"];
        } else {
            $id = uniqid("phd");
        }

        if ($open) {
            $this->CURRENT_CHUNK = $id;
            $this->notify(Render::CHUNK, Render::OPEN);

            return '<div id="' .$id. '" class="' .$name. '">';
        }

        $this->CURRENT_CHUNK = $id;
        $this->notify(Render::CHUNK, Render::CLOSE);
        /*
        $toc = "";
        $toc = $this->createTOC($id, $props["lang"]);

        return $toc."</div>";
        */
        return "</div>";
    }

    /**
     * Format a link for an element
     *
     * @param bool   $open  If the link should be opened.
     * @param string $name  Name of the element.
     * @param array  $attrs Attributes present for the element. Array keys are the attribute namespaces.
     * @param array  $props Properties
     *
     * @return string
     */
    public function format_link($open, $name, $attrs, $props)
    {
        if ($open) {
            $content = $fragment = '';
            $class = $name;

            if (isset($attrs[Reader::XMLNS_DOCBOOK]['linkend'])) {
                $linkto = $attrs[Reader::XMLNS_DOCBOOK]['linkend'];
                $id = $href = Format::getFilename($linkto);

                if ($id != $linkto) {
                    $fragment = "#$linkto";
                }
                if ($this->chunked) {
                    $href .= '.'.$this->ext;
                }
            } elseif (isset($attrs[Reader::XMLNS_XLINK]['href'])) {
                $href = $attrs[Reader::XMLNS_XLINK]['href'];
                $class .= ' external';
            }
            if ($name == 'xref') {
                if ($this->chunked) {
                    $link = $href;
                } else {
                    $link = '#';
                    if (isset($linkto)) {
                        $link .= $linkto;
                    } else {
                        $link .= $href;
                    }
                }
                return '<a href="' . htmlspecialchars($link). '" class="' .$class. '">' .($content.Format::getShortDescription($id)). '</a>';
            } elseif ($props['empty']) {
                if ($this->chunked) {
                    $link = '';
                } else {
                    $link = '#';
                }
                return '<a href="' .$link.$href.$fragment. '" class="' .$class. '">' .$content.$href.$fragment. '</a>';
            } else {
                if ($this->chunked) {
                    $link = $href.$fragment;
                } elseif (isset($linkto)) {
                    if ($fragment) {
                        $link = $fragment;
                    } else {
                        $link = "#$href";
                    }
                } else {
                    $link = $href;
                }
                return '<a href="' .htmlspecialchars($link). '" class="' .$class. '">' .$content;
            }
        }
        return '</a>';
    }

    public function format_container_chunk_title($open, $name, $attrs, $props)
    {        
        if ($open) {
            return $props["empty"] ? '' : '<h1>';
        }
        $ret = '';
        if (isset($this->cchunk['container_chunk']) && $this->cchunk['container_chunk']) {
            $ret = $this->cchunk['container_chunk'];
            $this->cchunk['container_chunk'] = null;
        }

        return "</h1>\n" .$ret;        
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props)
    {
        if ($open) {
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]['id'])) {
                $id = $attrs[Reader::XMLNS_XML]['id'];
                $idstr = ' id="' .$id. '" name="' .$id. '"';
            }
            return '<' .$tag. ' class="' .$name. '"' . $idstr. '>' . ($props['empty'] ? "</{$tag}>" : '');
        }
        return '</' .$tag. '>';
    }

    public function format_para_informaltable($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara()
                . $this->format_table($open, $name, $attrs, $props);
        }
        return $this->format_table($open, $name, $attrs, $props) . $this->restorePara();
    }

    /**
    * Creates a link to the PEAR API documentation.
    * Uses the tag text as well as the optional attributes package, class,
    * method and var.
    */
    public function format_phd_pearapi($open, $name, $attrs, $props)
    {
        if ($open && !$props['empty']) {
            return '';
        }

        $text      = $props['empty'] ? '' : $this->phd_pearapi_text;
        $package   = $attrs[Reader::XMLNS_PHD]['package'];
        $linkend   = isset($attrs[Reader::XMLNS_PHD]['linkend'])
                   ? $attrs[Reader::XMLNS_PHD]['linkend'] : null;
        $arLinkend = explode('::', $linkend);
        $class     = null;
        $method    = null;
        $variable  = null;

        if ($linkend === null) {
            //link to package
            if ($props['empty']) {
                $text    = $package;
            }
            $linktpl = '{$package}/docs/latest/li_{$package}.html';
        } else {
            $class = $arLinkend[0];
            if ($props['empty']) {
                $text = $linkend;
            }
            if (count($arLinkend) == 1) {
                //link to class
                $linktpl = '{$package}/docs/latest/{$package}/{$class}.html';
            } else if ($arLinkend[1]{0} == '$') {
                //link to class variable
                $variable = $arLinkend[1];
                $linktpl = '{$package}/docs/latest/{$package}/{$class}.html#var{$variable}';
            } else {
                //link to method
                if ($props['empty']) {
                    $text   .= '()';
                }
                $method  = $arLinkend[1];
                $linktpl = '{$package}/docs/latest/{$package}/{$class}.html#method{$method}';
            }
        }

        $uri = $this->phd_pearapi_urlprefix . str_replace(
            array('{$package}', '{$class}', '{$method}', '{$variable}'),
            array($package, $class, $method, $variable),
            $linktpl
        );

        return '<a href="' . htmlspecialchars($uri) . '"'
            . ' class="apidoclink">' . $text . '</a>';
    }

    public function format_phd_pearapi_text($value, $tag)
    {
        $this->phd_pearapi_text = $value;
    }

    /**
    * Format a &lt;programlisting&gt; tag.
    * Highlighting an such is done in format_programlisting_text()
    *
    * @param string $value Value of the text to format.
    * @param string $tag   Tag name
    * @param array  $attrs Array of attributes
    *
    * @return string Generated programlisting html
    */
    public function format_programlisting($open, $name, $attrs)
    {
        if ($open) {
            $this->trim = true;
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['role'])) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]['role'];
            } else {
                $this->role = '';
            }

            return $this->escapePara()
                . '<div class="'. ($this->role ? $this->role . 'code' : 'programlisting')
                . '" style="background-color:#EEE; width: 100%">';
        }
        $this->role = false;
        $this->trim = false;

        return '</div>' . $this->restorePara();
    }

    /**
    * Format the text within a program listing section.
    * Highlighting is done via the external highlighter.
    * programlisting without php tags get them appended
    *
    * @param string $value Value of the text to format.
    * @param string $tag   Tag name
    *
    * @return string Highlighted text.
    */
    public function format_programlisting_text($value, $tag)
    {
        switch($this->role) {
        case 'php':
            if (strrpos($value, '<?php') || strrpos($value, '?>')) {
                return $this->highlight(trim($value), 'php', 'xhtml');
            } else {
                return $this->highlight("<?php\n" . trim($value) . "\n?>", 'php', 'xhtml');
            }
            break;
        default:
            return $this->highlight(trim($value), $this->role, 'xhtml');
        }
    }

    public function format_screen($open, $name, $attrs)
    {
        if ($open) {
            return $this->escapePara()
                . '<pre class="screen" style="background-color:#EEE; width: 100%">';
        }
        return "</pre>\n" . $this->restorePara();
    }

    public function format_literallayout($open, $name, $attrs)
    {
        //FIXME: add support for attributes like class, continuation etc
        if ($open) {
            return $this->escapePara()
                . '<p class="literallayout">';
        }
        return "</p>\n" . $this->restorePara();
    }

    /**
    * Format a CDATA section. Automatically trims and highlights
    * the text when necessary.
    *
    * @param string $str CDATA content
    *
    * @return string Formatted string
    *
    * @see $trim
    * @see $role
    */
    public function CDATA($str)
    {
        if ($this->trim) {
            $str = rtrim($str);
        }
        if (!$this->role) {
            return str_replace(
                array("\n", ' '), array('<br/>', '&nbsp;'),
                htmlspecialchars($str, ENT_QUOTES, 'UTF-8')
            );
        }

        switch ($this->role) {
        case 'php':
            if (strrpos($str, '<?php') || strrpos($str, '?>')) {
                $str = $this->highlight(trim($str), $this->role, 'xhtml');
            } else {
                $str = $this->highlight("<?php\n" . trim($str) . "\n?>", $this->role, 'xhtml');
            }
            break;
        case '':
            $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
            break;
        default:
            $str = $this->highlight($str, $this->role, 'xhtml');
            break;
        }

        return $str;
    }

    public function format_suppressed_tags($open, $name, $attrs)
    {
        /* Ignore it */
        return '';
    }

    public function format_suppressed_text($value, $tag)
    {
        /* Suppress any content */
        return '';
    }

    public function format_surname($open, $name, $attrs)
    {
        /* Add a space before it, so firstname and surname are separated */
        return ' ';
    }

    public function format_subtitle($open, $name, $attrs)
    {
        if ($open)
            return '<p><font color="red">';
        return '</font></p>';
    }

    public function format_editedby($open, $name, $attrs, $props)
    {
        if ($open) {
            return '<h2 class="EDITEDBY">' . $this->autogen('editedby', $props['lang']) . '</h2>';
        }

    }

    public function format_copyright($open, $name, $attrs)
    {
        if ($open) {
            if ($this->chunked) {
                return '<p class="'.$name.'"><a href="copyright.' . $this->ext . '">Copyright</a> &copy; ';
            } else {
                return '<p class="'.$name.'"><a href="#copyright">Copyright</a> &copy; ';
            }
        }
        return '</p>';
    }

    public function format_comment($open, $name, $attrs)
    {
        if ($open) {
            return '<!-- ';
        }
        return '-->';
    }

    public function format_holder($open, $name, $attrs, $props)
    {
        if ($open)
            return $this->autogen('by', $props['lang']) . " ";
    }

    public function format_year($value)
    {
        return $value . ', ';
    }

    public function format_admonition($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara()
                . '<blockquote class="' . $name . '"><strong>'.$this->autogen($name, $props['lang']). ': </strong>';
        }
        return "</blockquote>\n" . $this->restorePara();
    }

    public function format_table($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara() . '<table border="1" class="'.$name.'">';
        }
        return "</table>\n" . $this->restorePara();
    }

    public function format_entry($open, $name, $attrs, $props)
    {
        if ($open) {
            if ($props['empty']) {
                return '<td></td>';
            }
            return '<td>';
        }
        return '</td>';
    }

    public function format_th_entry($open, $name, $attrs)
    {
        if ($open) {
            $colspan = Format::colspan($attrs[Reader::XMLNS_DOCBOOK]);
            return '<th colspan="' .((int)$colspan). '">';
        }
        return '</th>';
    }

    public function format_table_title($open, $name, $attrs, $props)
    {
        if ($props['empty'])
            return '';
        if ($open) {
            return '<caption><strong>';
        }
        return '</strong></caption>';
    }

    public function format_userinput($open, $name, $attrs, $props)
    {
        if ($open) {
            return '<tt class="'.$name.'"><strong>';
        }
        return '</strong></tt>';
    }

    public function format_replaceable($open, $name, $attrs, $props)
    {
        if ($open) {
            return '<tt class="'.$name.'"><em>';
        }
        return '</em></tt>';
    }

    public function format_warning($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara()
                . '<div class="warning" style="border: 3px double black; padding: 5px">' . "\n";
        }
        return "</div>\n" . $this->restorePara();
    }

    public function format_warning_title($open, $name, $attrs, $props)
    {
        if ($open) {
            return '<strong class="warning_title" style="display:block; text-align: center; width:100%">';
        }
        return "</strong>\n";
    }

    public function format_warning_para($open, $name, $attrs, $props)
    {
        if ($open) {
            if (!$props['sibling']) {
                return '<strong>' . $this->autogen('warning', $props['lang']) . "</strong>\n"
                    . '<p>';
            }
            return '<p>';
        }
        return "</p>\n";
    }

    public function format_refname_function_text($value)
    {
        $this->cchunk['refname'][] = '<b class="function">' . $this->TEXT($value . '()') . '</b>';
        return false;
    }

    public function format_refname_classname_text($value)
    {
        $this->cchunk['refname'][] = '<b class="classname">' . $this->TEXT($value) . '</b>';
        return false;
    }

    public function format_refpurpose($open, $tag, $attrs)
    {
        if ($open) {
            $refnames = implode(' ', $this->cchunk['refname']);
            $this->cchunk['refname'] = $this->dchunk['refname'];
            return '<div class="refnamediv">'. $refnames. ' &ndash; ';
        }
        return "</div>\n";
    }

    public function format_refname($open, $name, $attrs, $props) {
        if ($open) {
            return '<h1 class="' . $name . '">';
        }
        return "</h1>";
    }

    public function format_refname_text($value, $tag)
    {
        $this->cchunk['refname'][] = $this->TEXT($value);
        return $this->TEXT($value);
    }

    public function format_function_text($value)
    {
        return $this->TEXT($value.'()');
    }

    public function format_paramdef($open, $name, $attrs, $props)
    {
        if ($open && $props['sibling'] == 'paramdef') {
            return ' , ';
        }
        return false;
    }

    public function format_funcdef($open, $name, $attrs, $props)
    {
        if (!$open) {
            return ' ( ';
        }
        return false;
    }

    public function format_funcprototype($open, $name, $attrs, $props)
    {
        if ($open) {
            return '<p><code class="' . $name . '">';
        }
        return ')</code></p>';
    }

    public function format_refsynopsisdiv($open, $name, $attrs, $props)
    {
        if ($open) {
            return '<h2 class="refsynopsisdiv">Synopsis</h2>';
        }
        return '';
    }

    public function format_guimenu($open, $name, $attrs, $props)
    {
        if ($open) {
            if ($props['sibling'])
                return '-&gt;<span class="guimenu"><i>';
            return '<span class="guimenu"><i>';
        }
        return '</i></span>';
    }

    public function format_dl($open, $name, $attrs, $props)
    {
        if ($open) {
            return $this->escapePara() . '<dl class="' . $name . '">';
        }
        return '</dl>' . $this->restorePara();
    }

    /**
     * FIXME: This function is a crazy performance killer
     *
     * @param resource $stream Stream containing the contents of a Q&A section
     *
     * @return string
     */
    public function qandaset($stream)
    {
        $xml = stream_get_contents($stream);

        $old = libxml_use_internal_errors(true);
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhitespace = false;
        $doc->loadXML(html_entity_decode(str_replace('&', '&amp;amp;', "<div>$xml</div>"), ENT_QUOTES, 'UTF-8'));
        if ($err = libxml_get_errors()) {
            echo 'qandaset xml problem in ' . $this->CURRENT_ID . "\n";
            print_r($err);
            libxml_clear_errors();
        }
        fclose($stream);
        libxml_use_internal_errors($old);

        $xpath = new \DOMXPath($doc);
        $nlist = $xpath->query('//div/dl/dt/strong');
        $ret = '<div class="qandaset"><ol class="qandaset_questions">';
        $i = 0;
        foreach ($nlist as $node) {
            $ret .= '<li><a href="#' .($this->cchunk['qandaentry'][$i++]). '">' .($node->textContent). '</a></li>';
        }

        return $ret.'</ol>'.$xml.'</div>';
    }

    public function format_qandaentry($open, $name, $attrs)
    {
        if ($open) {
            $this->cchunk['qandaentry'][] = $this->CURRENT_ID . '.entry' . count($this->cchunk['qandaentry']);
            return '<dl>';
        }
        return '</dl>';
    }

    public function format_answer($open, $name, $attrs)
    {
        if ($open) {
            return '<dd><a name="' .end($this->cchunk['qandaentry']).'"></a>';
        }
        return '</dd>';
    }

    public function format_question($open, $name, $attrs)
    {
        if ($open) {
            return '<dt><strong>';
        }
        return '</strong></dt>';
    }

    public function format_emphasis($open, $name, $attrs)
    {
        if (isset($attrs[Reader::XMLNS_DOCBOOK]['role']) && $attrs[Reader::XMLNS_DOCBOOK]['role'] == "bold")
            $role = "b";
        else $role = "i";
        if ($open) {
            return '<' . $role . ' class="' . $name . '">';
        }
        return "</{$role}>";
    }

    public function format_glossterm($open, $name, $attrs)
    {
        if ($open) {
            return '<dt><strong>';
        }
        return '</strong></dt>';
    }

    public function format_glossdef($open, $name, $attrs)
    {
        if ($open) {
            return '<dd><p>';
        }
        return '</p></dd>';
    }

    public function format_calloutlist($open, $name, $attrs)
    {
        if ($open) {
            $this->cchunk['callouts'] = 0;
            return $this->escapePara() . '<table>';
        }
        return '</table>' . $this->restorePara();
    }

    public function format_callout($open, $name, $attrs)
    {
        if ($open) {
            return '<tr><td><a href="#'.$attrs[Reader::XMLNS_DOCBOOK]['arearefs'].'">(' .++$this->cchunk['callouts']. ')</a></td><td>';
        }
        return "</td></tr>\n";
    }

    public function format_imagedata($open, $name, $attrs) {
        if ($this->cchunk["mediaobject"]["alt"] !== false) {
            return '<img src="' .$attrs[Reader::XMLNS_DOCBOOK]["fileref"]. '" alt="' .$this->cchunk["mediaobject"]["alt"]. '" />';
        }
        return '<img src="' .$attrs[Reader::XMLNS_DOCBOOK]["fileref"]. '" />';
    }

    public function format_para($open, $name, $attrs, $props) {    
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

   /**
    * Functions from the old XHTMLPhDFormat
    */
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

    public function format_legalnotice_chunk($open, $name, $attrs) {
        if ($open) {
            return '<div id="legalnotice">';
        }
        return "</div>\n";
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
       
    public function format_screen_text($value, $tag) {
        return nl2br($this->TEXT($value));
    }
    public function format_constant($open, $name, $attrs) {
        if ($open) {
            return "<b><tt class=\"constant\">";
        }
        return "</tt></b>";
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
