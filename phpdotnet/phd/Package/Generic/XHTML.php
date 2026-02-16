<?php
namespace phpdotnet\phd;

abstract class Package_Generic_XHTML extends Format_Abstract_XHTML {
    private $myelementmap = array( /* {{{ */
        'abstract'              => 'div', /* Docbook-xsl prints "abstract"... */
        'abbrev'                => 'abbr',
        'acronym'               => 'acronym',
        'affiliation'           => 'format_suppressed_tags',
        'alt'                   => 'format_suppressed_tags',
        'arg'                   => 'format_suppressed_tags',
        'article'               => 'format_container_chunk_top',
        'author'                => array(
            /* DEFAULT */          'format_author',
            'authorgroup'       => 'format_authorgroup_author',
        ),
        'authorgroup'           => 'div',
        'authorinitials'        => 'format_entry',
        'appendix'              => 'format_container_chunk_top',
        'application'           => 'span',
        'blockquote'            => 'blockquote',
        'bibliography'          => array(
            /* DEFAULT */          'format_div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'book'                  => 'format_container_chunk_top',
        'caption'               => 'format_caption',
        'chapter'               => 'format_container_chunk_top',
        'citetitle'             => 'em',
        'cmdsynopsis'           => 'format_cmdsynopsis',
        'co'                    => 'format_co',
        'colophon'              => 'format_chunk',
        'copyright'             => 'format_copyright',
        'date'                  => array(
            /* DEFAULT */          'p',
           'revision'           => 'format_entry',
        ),
        'editor'                => 'format_editor',
        'edition'               => 'format_suppressed_tags',
        'email'                 => 'format_suppressed_tags',
        'errortext'             => 'code',
        'firstname'             => 'format_name',
        'footnote'              => 'format_footnote',
        'footnoteref'           => 'format_footnoteref',
        'funcdef'               => 'format_suppressed_tags',
        'funcsynopsis'          => 'div',
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
        'envar'                 => 'var',
        'errortype'             => 'span',
        'errorcode'             => 'span',
        'example'               => 'format_example',
        'formalpara'            => 'p',
        'fieldsynopsis'         => array(
            /* DEFAULT */          'format_fieldsynopsis',
            'entry'             => 'format_div',
        ),
        'figure'                => 'div',
        'filename'              => 'var',
        'glossentry'            => 'li',
        'glossdef'              => 'p',
        'glosslist'             => 'format_itemizedlist',
        'glossterm'             => 'span',
        'holder'                => 'span',
        'imageobject'           => 'format_div',
        'imagedata'             => 'format_imagedata',
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
        'keycap'                => 'format_keycap',
        'keycombo'              => 'format_keycombo',
        'legalnotice'           => 'format_chunk',
        'listitem'              => array(
            /* DEFAULT */          'li',
            'varlistentry'      => 'format_varlistentry_listitem',
        ),
        'literal'               => 'format_literal',
        'literallayout'         => 'pre',
        'link'                  => 'format_link',
        'manvolnum'             => 'format_manvolnum',
        'inlinemediaobject'     => 'format_mediaobject',
        'mediaobject'           => 'format_mediaobject',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'methodname'            => 'format_methodname',
        'member'                => 'format_member',
        'modifier'              => array(
            /* DEFAULT */          'format_modifier',
            'methodparam'       => 'format_methodparam_modifier',
        ),
        'note'                  => 'format_note',
        'orgname'               => 'span',
        'othercredit'           => 'format_div',
        /** Package/Namespace synopsis related tags */
        'packagesynopsis'       => 'format_packagesynopsis',
        'package'               => [
            /* DEFAULT */          'span',
            'packagesynopsis'   => 'format_packagesynopsis_package',
        ],
        /** Class Synopsis related tags */
        'classsynopsis'         => 'format_classsynopsis',
        'classsynopsisinfo'     => 'format_classsynopsisinfo',
        'ooclass'               => array(
            /* DEFAULT */          'span',
            'classsynopsis'     => 'format_classsynopsis_generic_oo_tag',
        ),
        'ooexception'           => [
            /* DEFAULT */          'span',
            'classsynopsis'     => 'format_classsynopsis_generic_oo_tag',
        ],
        'oointerface'           => array(
            /* DEFAULT */          'span',
            'classsynopsis'     => 'format_classsynopsis_generic_oo_tag',
            'classsynopsisinfo' => 'format_classsynopsisinfo_oointerface',
        ),
        'classname'             => [
            /* DEFAULT */          'span',
            'ooclass'           => [
                /* DEFAULT */          'span',
                'classsynopsis'     => 'format_classsynopsis_ooclass_classname',
                'classsynopsisinfo' => 'format_classsynopsisinfo_ooclass_classname',
            ],
        ],
        'exceptionname'             => [
            /* DEFAULT */          'span',
            'ooexception'           => [
                /* DEFAULT */          'span',
                'classsynopsis'     => 'format_classsynopsis_ooclass_classname',
            ],
        ],
        'interfacename'         => array(
            /* DEFAULT */          'span',
            'oointerface'       => array(
                /* DEFAULT */          'span',
                'classsynopsis' => 'format_classsynopsis_oointerface_interfacename',
                'classsynopsisinfo' => 'format_classsynopsisinfo_oointerface_interfacename',
            ),
        ),
        /** Enum synopsis related */
        'enumsynopsis' => 'format_enumsynopsis',
        'enumname'              => [
            /* DEFAULT */          'span',
            'enumsynopsis'      => 'format_enumsynopsis_enumname'
        ],
        'enumitem'              => 'format_enumitem',
        'enumidentifier'        => 'format_enumidentifier',
        'enumvalue'             => 'format_enumvalue',
        'enumitemdescription'   => 'format_enumitemdescription',
        'option'                => 'format_option',
        'orderedlist'           => 'format_orderedlist',
        'para'                  => array(
            /* DEFAULT */          'p',
            'example'           => 'format_example_content',
            'footnote'          => 'format_footnote_para',
            'refsect1'          => 'format_refsect1_para',
            'question'          => 'format_suppressed_tags',
        ),
        'paramdef'              => 'format_suppressed_tags',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
        ),
        'part'                  => 'format_container_chunk_top',
        'partintro'             => 'format_div',
        'personname'            => 'format_personname',
        'personblurb'           => 'format_div',
        'phrase'                => 'span',
        'preface'               => 'format_chunk',
        'printhistory'          => 'format_div',
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
        'refentry'              => 'format_chunk',
        'refentrytitle'         => 'span',
        'refpurpose'            => 'p',
        'reference'             => 'format_container_chunk_below',
        'refsect1'              => 'format_refsect',
        'refsect2'              => 'format_refsect',
        'refsect3'              => 'format_refsect',
        'refsynopsisdiv'        => 'div',
        'refname'               => 'h1',
        'refnamediv'            => 'div',
        'releaseinfo'           => 'div',
        'replaceable'           => array(
            /* DEFAULT */          'span',
            'constant'          => 'format_replaceable',
        ),
        'revhistory'            => 'format_table',
        'revision'              => 'format_row',
        'revremark'             => 'format_entry',
        'row'                   => 'format_row',
        'screen'                => 'format_screen',
        'screenshot'            => 'format_div',
        'sect1'                 => 'format_chunk',
        'sect2'                 => 'div',
        'sect3'                 => 'div',
        'sect4'                 => 'div',
        'sect5'                 => 'div',
        'section'               => array(
            /* DEFAULT */          'div',
            'sect1'                => 'format_section_chunk',
            'chapter'              => 'format_section_chunk',
            'appendix'             => 'format_section_chunk',
            'article'              => 'format_section_chunk',
            'part'                 => 'format_section_chunk',
            'reference'            => 'format_section_chunk',
            'refentry'             => 'format_section_chunk',
            'index'                => 'format_section_chunk',
            'bibliography'         => 'format_section_chunk',
            'glossary'             => 'format_section_chunk',
            'colopone'             => 'format_section_chunk',
            'book'                 => 'format_section_chunk',
            'set'                  => 'format_section_chunk',
            'setindex'             => 'format_section_chunk',
            'legalnotice'          => 'format_section_chunk',
        ),
        'seg'                   => 'format_seg',
        'segmentedlist'         => 'format_segmentedlist',
        'seglistitem'           => 'format_seglistitem',
        'segtitle'              => 'format_suppressed_tags',
        'set'                   => 'format_container_chunk_top',
        'setindex'              => 'format_chunk',
        'shortaffil'            => 'format_suppressed_tags',
        'sidebar'               => 'format_note',
        'simplelist'            => 'format_simplelist',
        'simplesect'            => 'div',
        'simpara'               => array(
            /* DEFAULT */          'p',
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
        'tag'                   => 'code',
        'table'                 => 'format_table',
        'firstterm'             => 'format_term',
        'term'                  => array(
            /* DEFAULT */          'format_term',
            'varlistentry'      => 'format_varlistentry_term'
        ),
        'tfoot'                 => 'format_th',
        'tbody'                 => 'format_tbody',
        'td'                    => 'format_th',
        'th'                    => 'format_th',
        'thead'                 => 'format_th',
        'tgroup'                => 'format_tgroup',
        'tip'                   => 'format_admonition',
        'title'                 => array(
            /* DEFAULT */          'h1',
            'example'           => 'format_example_title',
            'formalpara'        => 'h5',
            'info'              => array(
                /* DEFAULT */      'h1',
                'example'       => 'format_example_title',
                'note'          => 'format_note_title',
                'table'         => 'format_table_title',
                'informaltable' => 'format_table_title',

                'article'       => 'format_container_chunk_top_title',
                'appendix'      => 'format_container_chunk_top_title',
                'book'          => 'format_container_chunk_top_title',
                'chapter'       => 'format_container_chunk_top_title',
                'part'          => 'format_container_chunk_top_title',
                'set'           => 'format_container_chunk_top_title',

            ),
            'indexdiv'          => 'dt',
            'legalnotice'       => 'h4',
            'note'              => 'format_note_title',
            'phd:toc'           => 'strong',
            'procedure'         => 'strong',
            'refsect1'          => 'h3',
            'refsect2'          => 'h4',
            'refsect3'          => 'h5',
            'section'           => 'h2',
            'sect1'             => 'h2',
            'sect2'             => 'h3',
            'sect3'             => 'h4',
            'sect4'             => 'h5',
            'segmentedlist'     => 'format_table_title',
            'table'             => 'format_table_title',
            'variablelist'      => 'format_variablelist_title',
            'article'           => 'format_container_chunk_top_title',
            'appendix'          => 'format_container_chunk_top_title',
            'book'              => 'format_container_chunk_top_title',
            'chapter'           => 'format_container_chunk_top_title',
            'part'              => 'format_container_chunk_top_title',
            'set'               => 'format_container_chunk_top_title',
        ),
        'titleabbrev'           => 'format_suppressed_tags',
        'token'                 => 'code',
        'tr'                    => 'format_row',
        'trademark'             => 'format_trademark',
        'type'                  => 'span',
        'userinput'             => 'format_userinput',
        'uri'                   => 'code',
        'variablelist'          => 'format_variablelist',
        'varlistentry'          => 'format_varlistentry',
        'varname'               => array(
            /* DEFAULT */          'var',
            'fieldsynopsis'     => 'format_fieldsynopsis_varname',
        ),
        'void'                  => 'format_void',
        'warning'               => 'format_admonition',
        'xref'                  => 'format_xref',
        'year'                  => 'span',
        'quote'                 => 'q',
        'qandaset'              => 'format_qandaset',
        'qandaentry'            => 'dl',
        'question'              => array(
            /* DEFAULT */          'format_question',
            'questions'         => 'format_phd_question', // From the PhD namespace
        ),
        'questions'             => 'ol', // From the PhD namespace
        'answer'                => 'dd',

        //phpdoc: implemented in the PHP Package
        'phpdoc:classref'       => 'format_suppressed_tags',
        'phpdoc:exception'      => 'format_suppressed_tags',
        'phpdoc:exceptionref'   => 'format_suppressed_tags',
        'phpdoc:varentry'       => 'format_suppressed_tags',

        //phd
        'phd:toc'               => 'format_phd_toc',

        // MathML (namespace http://www.w3.org/1998/Math/MathML)
        'mml:math'              => 'format_mml_element',
        // Token
        'mml:mi'                => 'format_mml_element',
        'mml:mn'                => 'format_mml_element',
        'mml:mo'                => 'format_mml_element',
        'mml:mtext'             => 'format_mml_element',
        'mml:mspace'            => 'format_mml_element',
        'mml:ms'                => 'format_mml_element',
        // Layout
        'mml:mrow'              => 'format_mml_element',
        'mml:mfrac'             => 'format_mml_element',
        'mml:msqrt'             => 'format_mml_element',
        'mml:mroot'             => 'format_mml_element',
        'mml:mstyle'            => 'format_mml_element',
        'mml:merror'            => 'format_mml_element',
        'mml:mpadded'           => 'format_mml_element',
        'mml:mphantom'          => 'format_mml_element',
        'mml:mfenced'           => 'format_mml_element',
        'mml:menclose'          => 'format_mml_element',
        // Scripts and limits
        'mml:msub'              => 'format_mml_element',
        'mml:msup'              => 'format_mml_element',
        'mml:msubsup'           => 'format_mml_element',
        'mml:munder'            => 'format_mml_element',
        'mml:mover'             => 'format_mml_element',
        'mml:munderover'        => 'format_mml_element',
        'mml:mmultiscripts'     => 'format_mml_element',
        'mml:mprescripts'       => 'format_mml_element',
        'mml:none'              => 'format_mml_element',
        // Tables
        'mml:mtable'            => 'format_mml_element',
        'mml:mtr'               => 'format_mml_element',
        'mml:mtd'               => 'format_mml_element',
        'mml:mlabeledtr'        => 'format_mml_element',
        // Semantics
        'mml:semantics'         => 'format_mml_element',
        'mml:annotation'        => 'format_mml_element',
        'mml:annotation-xml'    => 'format_mml_element',
        // Actions
        'mml:maction'           => 'format_mml_element',

    ); /* }}} */

    private $mytextmap = array(
        'segtitle'             => 'format_segtitle_text',
        'affiliation'          => 'format_suppressed_text',
        'contrib'              => 'format_suppressed_text',
        'shortaffil'           => 'format_suppressed_text',
        'edition'              => 'format_suppressed_text',

        'programlisting'       => 'format_programlisting_text',
        'screen'               => 'format_screen_text',
        'alt'                  => 'format_alt_text',
        'modifier'             => array(
            /* DEFAULT */         false,
            'fieldsynopsis'    => 'format_fieldsynopsis_modifier_text',
            'methodparam'      => 'format_modifier_text',
            'methodsynopsis'   => 'format_modifier_text',
            'constructorsynopsis' => 'format_modifier_text',
            'ooclass'          => 'format_modifier_text',
            'ooexception'      => 'format_modifier_text',
            'oointerface'      => 'format_modifier_text',
        ),
        'replaceable'          => array(
            /* DEFAULT */         false,
            'constant'         => 'format_suppressed_text',
        ),
        /** Those are used to retrieve the class/interface name to be able to remove it from method names */
        'classname' => [
            /* DEFAULT */ false,
            'ooclass' => [
                /* DEFAULT */     false,
                /** This is also used by the legacy display to not display the class name at all */
                'classsynopsis' => 'format_classsynopsis_ooclass_classname_text',
            ]
        ],
        'exceptionname' => [
            /* DEFAULT */ false,
            'ooexception' => [
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_oo_name_text',
            ]
        ],
        'interfacename' => [
            /* DEFAULT */ false,
            'oointerface' => [
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_oo_name_text',
            ]
        ],
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
        'property'              => [
           /* DEFAULT */           'format_property_text',
        ],
        /* FIXME: This is one crazy stupid workaround for footnotes */
        'constant'              => array(
            /* DEFAULT */          'format_constant_text',
            'para'              => array(
                /* DEFAULT */      'format_constant_text',
                'footnote'      => 'format_footnote_constant_text',
            ),
        ),
        'literal'               => 'format_literal_text',
        'email'                 => 'format_email_text',
        'titleabbrev'           => 'format_suppressed_text',
        'member'                => 'format_member_text',
    );

    /** @var array */
    protected $TOC_WRITTEN = [];

    /** @var string */
    protected $CURRENT_LANG = "";

    /** @var string */
    protected $CURRENT_CHUNK = "";

     /* Current Chunk variables */
    protected $cchunk      = array();
    /* Default Chunk variables */
    private $dchunk      = array(
        "packagesynopsis" => false,
        "classsynopsis" => [
            "close"        => false,
            "classname"    => false,
            "interface"    => false, // bool: true when in interface
            "ooclass"      => false,
            "oointerface"  => false,
            "legacy"       => true, // legacy rendering
        ],
        "classsynopsisinfo"        => array(
            "implements"                    => false,
            "ooclass"                       => false,
        ),
        "examples"                 => 0,
        "fieldsynopsis"            => array(
            "modifier"                      => "public",
        ),
        "methodsynopsis"           => array(
            "returntypes"          => array(),
            "type_separator"       => array(),
            "type_separator_stack" => array(),
        ),
        "co"                       => 0,
        "callouts"                 => 0,
        "segmentedlist"            => array(
            "segtitleclosed"       => false,
            "segtitleopened"       => false,
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
        "simplelist"               => array(
            "members"              => array(),
            "type"                 => null,
            "columns"              => null,
        ),
    );

    protected $pihandlers = array(
        'dbhtml'        => 'PI_DBHTMLHandler',
        'dbtimestamp'   => 'PI_DBHTMLHandler',
    );

    protected $stylesheets = array();
    protected $isSectionChunk = array();
    protected $params = array();

    protected int $exampleCounter = 0;

    protected int $perPageExampleCounter = 0;

    protected bool $exampleCounterIsPerPage = false;

    protected array $perPageExampleIds = [];

    public function __construct(
        Config $config,
        OutputHandler $outputHandler
    ) {
        parent::__construct($config, $outputHandler);
        $this->registerPIHandlers($this->pihandlers);
        $this->setExt($this->config->ext === null ? ".html" : $this->config->ext);
    }

    public function getDefaultElementMap() {
        return $this->myelementmap;
    }

    public function getDefaultTextMap() {
        return $this->mytextmap;
    }

    public function getChunkInfo() {
        return $this->cchunk;
    }

    public function getDefaultChunkInfo() {
        return $this->dchunk;
    }

    protected function createTOC($id, $name, $props, $depth = 1, $header = true) {
        if (!$this->getChildren($id) || $depth == 0) {
            return "";
        }
        $toc = '';
        if ($header) {
            $toc .= $this->getTocHeader($props);
        }
        $toc .= "<ul class=\"chunklist chunklist_$name\">\n";
        foreach ($this->getChildren($id) as $child) {
            $isLDesc = null;
            $isSDesc = null;
            $long = $this->getLongDescription($child, $isLDesc);
            $short = $this->getShortDescription($child, $isSDesc);
            $link = $this->createLink($child);

            $list = "";
            if ($depth > 1 ) {
                $list = $this->createTOC($child, $name, $props, $depth -1, false);
            }
            if ($isLDesc && $isSDesc) {
                $toc .= '<li><a href="' . $link . '">' . $short . '</a> — ' . $long . $list . "</li>\n";
            } else {
                $toc .= '<li><a href="' . $link . '">' . $long . '</a>' . $list .  "</li>\n";
            }
        }
        $toc .= "</ul>\n";
        return $toc;
    }

    protected function getTocHeader($props)
    {
        return '<strong>' . $this->autogen('toc', $props['lang']) . '</strong>';
    }

    /**
    * Handle a <phd:toc> tag.
    */
    function format_phd_toc($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="phd-toc">';
        }
        return $this->createToc(
            $attrs[Reader::XMLNS_PHD]['element'],
            'phd-toc',
            $props,
            isset($attrs[Reader::XMLNS_PHD]['toc-depth'])
                ? (int)$attrs[Reader::XMLNS_PHD]['toc-depth'] : 1,
            false
        ) . "</div>\n";
    }

    /**
    * Handle MathML elements (mml:* namespace).
    * Strips the "mml:" prefix and outputs the HTML5 local name.
    */
    public function format_mml_element($open, $name, $attrs, $props) {
        $localName = substr($name, 4);

        if ($open) {
            $attrStr = '';

            // Add xmlns on the <math> root element for XHTML compatibility
            if ($localName === 'math') {
                $attrStr .= ' xmlns="' . Reader::XMLNS_MATHML . '"';
            }

            // Preserve MathML attributes (stored under XMLNS_DOCBOOK as they have no namespace)
            foreach ($attrs[Reader::XMLNS_DOCBOOK] as $attr => $val) {
                $attrStr .= ' ' . $attr . '="' . $this->TEXT($val) . '"';
            }

            // Preserve xml:id as id
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $attrStr .= ' id="' . $attrs[Reader::XMLNS_XML]["id"] . '"';
            }

            return '<' . $localName . $attrStr . ($props["empty"] ? '/>' : '>');
        }

        return '</' . $localName . '>';
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {
        $retval = null;
        if (isset($this->indexes[$for])) {
            $rsl = $this->indexes[$for];
            $retval = $rsl["filename"] . $this->ext;
            if ($rsl["filename"] != $rsl["docbook_id"]) {
                if (isset($this->perPageExampleIds[$for])) {
                    $retval .= '#' . $this->perPageExampleIds[$for];
                } else {
                    $retval .= '#' . $rsl["docbook_id"];
                }
            }
            $desc = $rsl["sdesc"] ?: $rsl["ldesc"];
        }
        return $retval;
    }

    protected function createCSSLinks() {
        $cssLinks = '';
        foreach ((array)$this->stylesheets as $css) {
            if ($this->isChunked()) {
                $cssLinks .= "<link media=\"all\" rel=\"stylesheet\" type=\"text/css\" href=\"styles/".$css."\" />\n";
            } else {
                $cssLinks .= "<style type=\"text/css\">\n" . $css . "\n</style>\n";
            }
        }
        return $cssLinks;
    }

    protected function fetchStylesheet($name = null) {
        if (!$this->isChunked()) {
            foreach ((array)$this->config->css as $css) {
                if ($style = file_get_contents($css)) {
                    $this->stylesheets[] = $style;
                } else {
                    trigger_error(vsprintf("Stylesheet %s not fetched.", [$css]), E_USER_WARNING);
                }
            }
            return;
        }
        $stylesDir = $this->getOutputDir();
        if (!$stylesDir) {
            $stylesDir = $this->config->outputDir;
        }
        $stylesDir .= 'styles/';
        if (file_exists($stylesDir)) {
            if (!is_dir($stylesDir)) {
                throw new \Error('The styles/ directory is a file?');
            }
        } else {
            if (!mkdir($stylesDir, 0777, true)) {
                throw new \Error("Can't create the styles/ directory.");
            }
        }
        foreach ((array)$this->config->css as $css) {
            $basename = basename($css);
            $dest = md5(substr($css, 0, -strlen($basename))) . '-' . $basename;
            if (@copy($css, $stylesDir . $dest)) {
                $this->stylesheets[] = $dest;
            } else {
                trigger_error(vsprintf('Impossible to copy the %s file.', [$css]), E_USER_WARNING);
            }
        }
    }

/* Functions format_* */
    public function format_suppressed_tags($open, $name, $attrs, $props) {
        /* Ignore it */
        return "";
    }
    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }

    public function format_link($open, $name, $attrs, $props) {
        if ($open) {
            $link = $class = $content = "";

            if (isset($attrs[Reader::XMLNS_DOCBOOK]["linkend"])) {
                $link = $this->createLink($attrs[Reader::XMLNS_DOCBOOK]["linkend"]);
            }
            elseif (isset($attrs[Reader::XMLNS_XLINK]["href"])) {
                $link = $attrs[Reader::XMLNS_XLINK]["href"];
                $class = " external";
                $content = "&raquo;&nbsp;";
            }
            if ($props["empty"]) {
                $content .= $link ."</a>";
            }

            return '<a href="' . $link . '" class="' . $name . $class . '">' . $content;
        }
        return "</a>";
    }

    public function format_xref($open, $name, $attrs, $props) {
        if ($open) {
            $desc = "";
            $link = $this->createLink($attrs[Reader::XMLNS_DOCBOOK]["linkend"], $desc);

            $ret = '<a href="' .$link. '" class="' .$name. '">' .$desc;

            if ($props["empty"]) {
                return $ret. "</a>";
            }
            return $ret;
        }
        return "</a>";
    }

    public function format_option($open, $name, $attrs) {
        if ($open) {
            if(!isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $attrs[Reader::XMLNS_DOCBOOK]["role"] = "unknown";
            }
            $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]["role"]);
            return '<strong class="' . $name . ' ' . $this->getRole() . '">';
        }
        $this->popRole();
        return "</strong>";
    }

    public function format_literal($open, $name, $attrs)
    {
        if ($open) {
            $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]["role"] ?? null);
            return '<code class="literal">';
        }
        $this->popRole();
        return '</code>';
    }

    public function format_literal_text($value, $tag) {
        switch ($this->getRole()) {
            case 'infdec':
                $value = (string) (float)$value;
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
                    /* We might want to add support for other roles */
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

    public function format_container_chunk_top($open, $name, $attrs, $props) {
        $hasAnnotations = array_key_exists('annotations', $attrs[Reader::XMLNS_DOCBOOK]);

        $this->cchunk = $this->dchunk;
        $this->cchunk["name"] = $name;
        if(isset($attrs[Reader::XMLNS_XML]["id"])) {
            $id = $attrs[Reader::XMLNS_XML]["id"];
        } else {
            $id = uniqid("phd");
        }

        if ($open) {
            if ($hasAnnotations) {
                $this->pushAnnotations($attrs[Reader::XMLNS_DOCBOOK]["annotations"]);
            }

            $this->CURRENT_CHUNK = $id;
            $this->notify(Render::CHUNK, Render::OPEN);

            return '<div id="' .$id. '" class="' .$name. '">';
        }

        if ($hasAnnotations) {
            $this->popAnnotations();
        }

        $this->CURRENT_CHUNK = $id;
        $this->notify(Render::CHUNK, Render::CLOSE);
        $toc = "";
        if (!in_array($id, $this->TOC_WRITTEN)) {
            $toc = $this->createTOC($id, $name, $props);
        }

        return $toc."</div>";
    }
    public function format_container_chunk_top_title($open, $name, $attrs, $props) {
        if ($open) {
            return '<h1>';
        }

        $id = $this->CURRENT_CHUNK;

        $toc = $this->createTOC($id, $name, $props, 2);

        $this->TOC_WRITTEN[] = $id;

        return '</h1>'.$toc;
    }
    public function format_container_chunk_below($open, $name, $attrs, $props) {
        $this->cchunk = $this->dchunk;
        $this->cchunk["name"] = $name;
        if(isset($attrs[Reader::XMLNS_XML]["id"])) {
            $id = $attrs[Reader::XMLNS_XML]["id"];
        } else {
            /* FIXME: This will obviously not exist in the db.. */
            $id = uniqid("phd");
        }

        if ($open) {
            $this->CURRENT_CHUNK = $id;
            $this->notify(Render::CHUNK, Render::OPEN);

            return '<div id="' .$attrs[Reader::XMLNS_XML]["id"]. '" class="' .$name. '">';
        }

        $toc = '<ol>';
        if (!in_array($id, $this->TOC_WRITTEN)) {
            $toc = $this->createTOC($id, $name, $props);
        }
        $toc .= "</ol>\n";

        $this->CURRENT_CHUNK = $id;
        $this->notify(Render::CHUNK, Render::CLOSE);
        return $toc . '</div>';
    }
    public function format_exception_chunk($open, $name, $attrs, $props) {
        return $this->format_container_chunk_below($open, "reference", $attrs, $props);
    }
    public function format_section_chunk($open, $name, $attrs, $props) {
        if ($open) {
            if (!isset($attrs[Reader::XMLNS_XML]["id"])) {
                $this->isSectionChunk[] = false;
                return $this->transformFromMap($open, "div", $name, $attrs, $props);
            }
            $this->isSectionChunk[] = true;
            return $this->format_chunk($open, $name, $attrs, $props);
        }
        if (array_pop($this->isSectionChunk)) {
            return $this->format_chunk($open, $name, $attrs, $props);
        }
        return $this->transformFromMap($open, "div", $name, $attrs, $props);
    }
    public function format_chunk($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk = $this->dchunk;
            if(isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
            } else {
                $id = uniqid("phd");
            }

            $class = $name;
            if ($name === "refentry") {
                //$class .= " -rel-posting";
            }

            $this->CURRENT_CHUNK = $id;
            $this->CURRENT_LANG  = $props["lang"];

            $this->notify(Render::CHUNK, Render::OPEN);
            return '<div id="' .$id. '" class="' .$class. '">';
        }
        $this->notify(Render::CHUNK, Render::CLOSE);

        $str = "";
        foreach ($this->cchunk["footnote"] as $k => $note) {
            $str .= '<div class="footnote">';
            $str .= '<a name="fnid' .$note["id"]. '" href="#fn' .$note["id"]. '"><sup>[' .($k + 1). ']</sup></a>';
            $str .= $note["str"];
            $str .= "</div>\n";
        }
        $this->cchunk = $this->dchunk;

        return $str. "</div>";
    }
    public function format_refsect1_para($open, $name, $attrs, $props) {
        if ($open) {
            switch ($props["sibling"]) {
            case "methodsynopsis":
            case "constructorsynopsis":
            case "destructorsynopsis":
                return '<p class="'.$name.' rdfs-comment">';
                break;

            default:
                return '<p class="'.$name.'">';
            }

        }
        return '</p>';
    }
    public function format_refsect($open, $name, $attrs) {
        static $role = 0;

        if ($open) {
            if(!isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $attrs[Reader::XMLNS_DOCBOOK]["role"] = "unknown-" . ++$role;
            }
            $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]["role"]);

            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
            }
            else {
                $id = $name. "-" . $this->CURRENT_CHUNK . "-" . $this->getRole();
            }

            return '<div class="' .$name.' ' .$this->getRole(). '" id="' . $id . '">';
        }
        $this->popRole();
        return "</div>\n";
    }

    /** Legacy rendering functions for class synopsis tags that wraps the definition in a class synopsis info tag */
    public function format_classsynopsisinfo_oointerface($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["ooclass"] === false) {
                return '<span class="' . $name . '">';
            }

            if ($this->cchunk["classsynopsisinfo"]["implements"] === false) {
                $this->cchunk["classsynopsisinfo"]["implements"] = true;
                if ($this->cchunk["classsynopsis"]["interface"]) {
                    return '<span class="'.$name.'"><span class="modifier">extends</span> ';
                }

                return '<span class="'.$name.'"><span class="modifier">implements</span> ';
            }

            return '<span class="'.$name.'">, ';
        }

        return "</span>";
    }

    public function format_classsynopsisinfo_ooclass_classname($open, $name, $attrs)
    {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["ooclass"] === false) {
                $this->cchunk["classsynopsisinfo"]["ooclass"] = true;
                if ($this->cchunk["classsynopsis"]["interface"]) {
                    return '<span class="modifier">interface</span> ';
                }

                return '<span class="modifier">class</span> ';
            }

            return ' ';
        }

        if ($this->cchunk["classsynopsisinfo"]["ooclass"] === true) {
            $this->cchunk["classsynopsisinfo"]["ooclass"] = null;
        }

        return "";
    }

    public function format_classsynopsisinfo_oointerface_interfacename($open, $name, $attrs)
    {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["ooclass"] === false) {
                $this->cchunk["classsynopsisinfo"]["ooclass"] = true;
                return '<span class="modifier">interface</span> ';
            }

            return ' ';
        }

        if ($this->cchunk["classsynopsisinfo"]["ooclass"] === true) {
            $this->cchunk["classsynopsisinfo"]["ooclass"] = null;
        }

        return "";
    }

    public function format_classsynopsisinfo($open, $name, $attrs)
    {
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

        assert($this->cchunk["classsynopsis"]["legacy"] === true);
        $this->cchunk["classsynopsis"]["close"] = true;
        return ' {</div>';
    }

    /** This method is common between both legacy and new rendering for setting up the classname in the current chunk */
    public function format_classsynopsis_ooclass_classname_text($value, $tag) {
        /** If this is not defined this is the first ooclass/oointerface/ooexception and thus needs to
         *  set the class name to be able to remove it from the methods
         */
        if (!$this->cchunk["classsynopsis"]["classname"]) {
            $this->cchunk["classsynopsis"]["classname"] = $value;
        }
        // Do not render outside ooclass class name in legacy rendering.
        if ($this->cchunk["classsynopsis"]["legacy"]) {
            return '';
        }
        return $this->TEXT($value);
    }

    /** Class synopsis rendering for new/better markup */
    public function format_classsynopsis_oo_name_text($value, $tag) {
        /** If this is not defined this is the first ooclass/oointerface/ooexception and thus needs to
         *  set the class name to be able to remove it from the methods
         */
        if (!$this->cchunk["classsynopsis"]["classname"]) {
            $this->cchunk["classsynopsis"]["classname"] = $value;
        }
        return $this->TEXT($value);
    }

    public function format_classsynopsis_oointerface_interfacename($open, $name, $attrs, $props)
    {
        if ($this->cchunk["classsynopsis"]["legacy"] === true) {
            return $this->transformFromMap($open, 'strong', $name, $attrs, $props);
        }

        if ($open) {
            /* If there has been a class prior this means that we are the first implementing interface
             * thus mark the oointerface as already been rendered as the primary tag */
            if ($this->cchunk["classsynopsis"]["ooclass"] === true) {
                $this->cchunk["classsynopsis"]["oointerface"] = true;
            }
            /** Actual interface name in bold */
            if ($this->cchunk["classsynopsis"]["oointerface"] === false) {

                return '<span class="modifier">interface</span> ' . $this->transformFromMap($open, 'strong', $name, $attrs, $props);
            }
            /* Whitespace for next word */
            return ' ';
        }
        /** Actual interface name in bold */
        if ($this->cchunk["classsynopsis"]["oointerface"] === false) {
            $this->cchunk["classsynopsis"]["oointerface"] = true;
            return '</strong>';
        }
        /** We don't wrap extended interface in a tag */
        if ($this->cchunk["classsynopsis"]['nb_list'] > 1) {
            return ',';
        }
        return '';
    }

    public function format_classsynopsis_ooclass_classname($open, $name, $attrs, $props)
    {
        if ($this->cchunk["classsynopsis"]["legacy"] === true) {
            return $this->transformFromMap($open, 'strong', $name, $attrs, $props);
        }

        if ($open) {
            /** Actual class name in bold */
            if ($this->cchunk["classsynopsis"]["ooclass"] === false) {
                /** We force the name: parameter to 'classname' to not break CSS expectations for exceptionanme tags */
                return '<span class="modifier">class</span> ' . $this->transformFromMap($open, 'strong', 'classname', $attrs, $props);
            }
            /* Whitespace for next word */
            return ' ';
        }
        /** Actual class name in bold */
        if ($this->cchunk["classsynopsis"]["ooclass"] === false) {
            $this->cchunk["classsynopsis"]["ooclass"] = true;
            return '</strong>';
        }
        /** We don't wrap extended class in a tag */
        return '';
    }

    public function format_classsynopsis_generic_oo_tag($open, $name, $attrs, $props)
    {
        if ($this->cchunk["classsynopsis"]["legacy"] === true) {
            return $this->transformFromMap($open, 'span', $name, $attrs, $props);
        }

        /* Close list of classes + interfaces by "opening" class def with { */
        if (!$open) {
            if (--$this->cchunk["classsynopsis"]['nb_list'] === 0) {
                return ' {</div>';
            }
        }
        return '';
    }

    public function format_classsynopsis($open, $name, $attrs, $props) {
        $this->cchunk["classsynopsis"] = $this->dchunk["classsynopsis"];

        /** Legacy presentation does not use the class attribute */
        $this->cchunk["classsynopsis"]['legacy'] = !isset($attrs[Reader::XMLNS_DOCBOOK]["class"]);

        $inPackageSynopsis = $this->cchunk["packagesynopsis"] ?? false;

        if ($this->cchunk["classsynopsis"]['legacy']) {
            if ($open) {
                // Think this just needs to be set on open and it will persist
                // Will remove comment after review
                if (
                    isset($attrs[Reader::XMLNS_DOCBOOK]["class"]) &&
                    $attrs[Reader::XMLNS_DOCBOOK]["class"] == "interface"
                ) {
                    $this->cchunk["classsynopsis"]["interface"] = true;
                }

                if ($inPackageSynopsis) {
                    return '';
                }
                return '<div class="'.$name.'">';
            }

            /* Just always force the ending } to close the class as an opening { should always be present
            if ($this->cchunk["classsynopsis"]["close"] === true) {
                $this->cchunk["classsynopsis"]["close"] = false;
                return "}</div>";
            }
            return "</div>";
            */
            if ($inPackageSynopsis) {
                return '}';
            }
            return "}</div>";
        }

        /* New rendering for more sensible markup:
         * We open a fake classsynopsisinfo div to not break the CSS expectations */
        if ($open) {
            $occurrences = substr_count($props['innerXml'], '</ooclass>')
                + substr_count($props['innerXml'], '</oointerface>')
                + substr_count($props['innerXml'], '</ooexception>');
            $this->cchunk["classsynopsis"]['nb_list'] = $occurrences;
            if ($inPackageSynopsis) {
                return '<div class="classsynopsisinfo">';
            }
            return '<div class="classsynopsis"><div class="classsynopsisinfo">';
        } else {
            if ($inPackageSynopsis) {
                return '}';
            }
            return '}</div>';
        }
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
        } elseif (strpos($value, '-&gt;')) {
            $explode = '-&gt;';
        } else {
            return $value;
        }

        list($class, $method) = explode($explode, $value);
        if ($class !== $this->cchunk["classsynopsis"]["classname"]) {
            return $value;
        }
        return $method;
    }

    public function format_packagesynopsis($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["packagesynopsis"] = true;
            return '<div class="classsynopsis"><div class="classsynopsisinfo">';
        }
        $this->cchunk["packagesynopsis"] = false;
        return '</div>';
    }

    public function format_packagesynopsis_package($open, $name, $attrs, $props) {
        if ($open) {
            return '<span class="modifier">namespace</span> <strong class="package">';
        }
        return '</strong>;</div>';
    }

    public function format_enumsynopsis($open, $name, $attrs, $props) {
        $inPackageSynopsis = $this->cchunk["packagesynopsis"] ?? false;
        if ($open) {
            if ($inPackageSynopsis) {
                return '<div class="classsynopsisinfo">';
            }
            return '<div class="classsynopsis"><div class="classsynopsisinfo">';
        } else {
            if ($inPackageSynopsis) {
                return '}';
            }
            return '}</div>';
        }
    }
    public function format_enumsynopsis_enumname($open, $name, $attrs, $props): string {
        if ($open) {
            /** Actual enum name in bold */
            return '<span class="modifier">enum</span> <strong class="classname">';
            //return '<span class="modifier">enum</span> <strong class="enumname">';
        }
        //return "</strong><br/>{<br/>";
        return "</strong><br/>{</div>";
    }
    public function format_enumitem($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="fieldsynopsis">';
        }
        return '</div>';
    }
    public function format_enumidentifier($open, $name, $attrs, $props) {
        if ($open) {
            return '    <span class="modifier">case</span>  <span class="classname">';
        }
        return '</span>';
    }
    public function format_enumvalue($open, $name, $attrs, $props) {
        if ($open) {
            return ' = ';
        }
        return '';
    }
    public function format_enumitemdescription($open, $name, $attrs, $props) {
        if ($open) {
            return '; //';
        }
        return "<br><br>";
    }

    public function format_emphasis($open, $name, $attrs)
    {
        $name = "em";

        if (isset($attrs[Reader::XMLNS_DOCBOOK]['role'])) {
            $role = $attrs[Reader::XMLNS_DOCBOOK]['role'];
            if ( $role == "strong" || $role == "bold" ) {
                $name = "strong";
            }
        }

        if ($open) {
            return "<{$name}>";
        } else {
            return "</{$name}>";
        }
    }

    public function format_fieldsynopsis($open, $name, $attrs) {
        $this->cchunk["fieldsynopsis"] = $this->dchunk["fieldsynopsis"];
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]["role"]);
            }
            return '<div class="'.$name.'">';
        }
        if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
            $this->popRole();
        }
        return ";</div>\n";
    }

    public function format_fieldsynopsis_modifier_text($value, $tag) {
        if ($this->getRole() === "attribute") {
            $attribute = trim(strtolower($value), "#[]\\");
            $href = Format::getFilename("class.$attribute");
            if ($href) {
                return '<a href="' . $href . $this->getExt() . '">' .$value. '</a> ';
            }
            return false;
        }
        $this->cchunk["fieldsynopsis"]["modifier"] = trim($value);
        return $this->TEXT($value);
    }

    public function format_modifier($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]["role"]);
                return '<span class="' . $this->getRole() . '">';
            }
            return '<span class="modifier">';
        }
        if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
            if ($attrs[Reader::XMLNS_DOCBOOK]["role"] === "attribute") {
                return '</span><br>';
            }
            $this->popRole();
        }
        return '</span>';
    }

    public function format_methodparam_modifier($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]["role"]);
                return '<span class="' . $this->getRole() . '">';
            }
            return '<span class="modifier">';
        }
        if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
            $this->popRole();
        }
        return '</span>';
    }

    public function format_modifier_text($value, $tag) {
        if ($this->getRole() === "attribute") {
            $attribute = trim(strtolower($value), "#[]\\");
            $href = Format::getFilename("class.$attribute");
            if ($href) {
                return '<a href="' . $href . $this->getExt() . '">' .$value. '</a> ';
            }
        }
        return false;
    }

    public function format_methodsynopsis($open, $name, $attrs, $props) {
        if ($open) {

            $this->params = array(
                "count" => 0,
                "opt" => false,
                "init" => false,
                "content" => "",
                "ellipsis" => '',
                "paramCount" => substr_count($props["innerXml"], "<methodparam")
            );

            $id = (isset($attrs[Reader::XMLNS_XML]["id"]) ? ' id="'.$attrs[Reader::XMLNS_XML]["id"].'"' : '');
            return '<div class="'.$name.' dc-description"'.$id.'>';
        }

        $content = "";
        if ($this->params["paramCount"] > 3) {
            $content .= "<br>";
        }

        $content .= ")";

        $content .= "</div>\n";

        return $content;
    }

    public function format_methodparam_parameter($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return '';
        }
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                return ' <code class="parameter reference">&' . $this->params["ellipsis"] . '$';
            }
            return ' <code class="parameter">' . $this->params["ellipsis"] . '$';
        }
        return "</code>";
    }

    public function format_initializer($open, $name, $attrs) {
        if ($open) {
            $this->params["init"] = true;
            return '<span class="'.$name.'"> = ';
        }
        return '</span>';
    }
    public function format_parameter($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return '';
        }
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                return '<code class="parameter reference">&';
            }
            return '<code class="parameter">';
        }
        return "</code>";
    }

    public function format_void($open, $name, $attrs, $props) {
        if (isset($props['sibling']) && $props['sibling'] == 'methodname') {
            return '(';
        } else {
            return '<span class="type"><a href="language.types.void' . $this->getExt() . '" class="type void">void</a></span>';
        }
    }

    public function format_methodparam($open, $name, $attrs) {
        if ($open) {
            $content = '';
                if ($this->params["count"] === 0) {
                    $content .= "(";
                    if ($this->params["paramCount"] > 3) {
                        $content .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["choice"]) && $attrs[Reader::XMLNS_DOCBOOK]["choice"] == "opt") {
                    $this->params["opt"] = true;
                } else {
                    $this->params["opt"] = false;
                }
                if ($this->params["count"]) {
                    $content .= ",";
                    if ($this->params["paramCount"] > 3) {
                        $content .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;";
                    } else {
                        $content .= " ";
                    }
                }
                $content .= '<span class="methodparam">';
                ++$this->params["count"];
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["rep"]) && $attrs[Reader::XMLNS_DOCBOOK]["rep"] == "repeat") {
                    $this->params["ellipsis"] = '...';
                } else {
                    $this->params["ellipsis"] = '';
                }
                return $content;
        }
        if ($this->params["opt"] && !$this->params["init"]) {
            return '<span class="initializer"> = ?</span></span>';
        }
        $this->params["init"] = false;
        return "</span>";
    }

    public function format_methodname($open, $name, $attr) {
        if ($open) {
            return ' <span class="' .$name. '">';
        }
        return '</span>';
    }

    public function format_varname($open, $name, $attrs) {
        if ($open) {
            return '<var class="'.$name.'">$';
        }
        return "</var>";
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
            return '<table>';
        }
        return '</table>';
    }
    public function format_callout($open, $name, $attrs) {
        if ($open) {
            return '<tr><td><a href="#'.$attrs[Reader::XMLNS_DOCBOOK]["arearefs"].'">' .str_repeat("*", ++$this->cchunk["callouts"]). '</a></td><td>';
        }
        return "</td></tr>\n";
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
            return '<table class="'.$name.'">';
        }
        return '</tbody></table>';
    }
    public function format_segtitle_text($value, $tag) {
        $html = '';
        if (!$this->cchunk["segmentedlist"]["segtitleopened"]) {
            $html .= '<thead><tr>';
        }
        $html .= '<th>'.$this->TEXT($value).'</th>';
        $this->cchunk["segmentedlist"]["segtitleopened"] = true;

        // Don't close the row; we'll have to do that in the first seglistitem.
        return $html;
    }
    public function format_seglistitem($open, $name, $attrs) {
        if ($open) {
            $html = '';

            if (!$this->cchunk["segmentedlist"]["segtitleclosed"]) {
                $html .= '</tr></thead><tbody>';
                $this->cchunk["segmentedlist"]["segtitleclosed"] = true;
            }

            $html .= '<tr class="'.$name.'">';
            return $html;
        }
        return '</tr>';
    }
    public function format_seg($open, $name, $attrs) {
        if ($open) {
            return '<td class="seg">';
        }
        return '</td>';
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
    public function format_variablelist($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]["role"]);
            }
            $classStr = $headerStr = $idStr = '';
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $idStr = ' id="' . $attrs[Reader::XMLNS_XML]["id"] . '"';
            }
            if ($this->getRole() === 'constant_list') {
                $tagName = 'table';
                $classStr = ' class="doctable table"';
                $headerStr = "\n" . $this->indent($props["depth"] + 1) . "<tr>\n"
                    . $this->indent($props["depth"] + 2) . "<th>"
                    . $this->autogen('Constants', $props['lang']) . "</th>\n"
                    . $this->indent($props["depth"] + 2) . "<th>"
                    . $this->autogen('Description', $props['lang']) . "</th>\n"
                    . $this->indent($props["depth"] + 1) . "</tr>";
            } else {
                $tagName = 'dl';
            }
            return '<' . $tagName . $idStr . $classStr . '>' . $headerStr;
        }
        $tagName = ($this->getRole() === 'constant_list') ? 'table' : 'dl';
        $this->popRole();
        return "</" . $tagName . ">";
    }
    public function format_varlistentry($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $this->cchunk['varlistentry']['id'] = $attrs[Reader::XMLNS_XML]["id"];
            } else {
                unset($this->cchunk['varlistentry']['id']);
            }
            return ($this->getRole() === 'constant_list') ? '<tr>' : '';
        }
        return ($this->getRole() === 'constant_list') ? '</tr>' : '';
    }
    public function format_varlistentry_term($open, $name, $attrs, $props) {
        $tagName = ($this->getRole() === 'constant_list') ? 'td' : 'dt';
        if ($open) {
            if (isset($this->cchunk['varlistentry']['id'])) {
                $id = $this->cchunk['varlistentry']['id'];
                unset($this->cchunk['varlistentry']['id']);
                return '<' . $tagName . ' id="' . $id . '">';
            } else {
                return "<" . $tagName . ">";
            }
        }
        return "</" . $tagName . ">";
    }
    public function format_varlistentry_listitem($open, $name, $attrs) {
        $tagName = ($this->getRole() === 'constant_list') ? 'td' : 'dd';
        if ($open) {
            return "<" . $tagName . ">";
        }
        return "</" . $tagName . ">";
    }
    public function format_term($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] == $name) {
                return '<br /><span class="' .$name. '">';
            }
            return '<span class="' .$name. '">';
        }
        return "</span>";
    }
    public function format_trademark($open, $name, $attrs, $props) {
        if ($open) {
            return '<span class=' .$name. '">';
        }
        return '®</span>';
    }
    public function format_userinput($open, $name, $attrs) {
        if ($open) {
            return '<strong class="' .$name. '"><code>';
        }
        return "</code></strong>";
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
        return "</code>";
    }
    public function format_example_content($open, $name, $attrs) {
        if ($open) {
            return '<div class="example-contents"><p>';
        }
        return "</p></div>";
    }
    public function format_programlisting($open, $name, $attrs) {
        $hasAnnotations = array_key_exists('annotations', $attrs[Reader::XMLNS_DOCBOOK]);
        if ($open) {
            $this->pushRole($attrs[Reader::XMLNS_DOCBOOK]["role"] ?? null);
            if ($hasAnnotations) {
                $this->pushAnnotations($attrs[Reader::XMLNS_DOCBOOK]["annotations"]);
            }
            return '<div class="example-contents">';
        }
        if ($hasAnnotations) {
            $this->popAnnotations();
        }
        $this->popRole();
        return "</div>\n";
    }
    public function format_programlisting_text($value, $tag) {
        return $this->CDATA($value);
    }
    public function format_screen($open, $name, $attrs) {
        if ($open) {
            if ($this->getRole() !== "examples"
                && $this->getRole() !== "description"
                && $this->getRole() !== "notes"
                && $this->getRole() !== "returnvalues"
                && $this->getRole() !== "parameters") {
                $this->pushRole('');
            }
            return '<div class="example-contents ' .$name. '">';
        }
        if ($this->getRole() !== "examples"
            && $this->getRole() !== "description"
            && $this->getRole() !== "notes"
            && $this->getRole() !== "returnvalues"
            && $this->getRole() !== "parameters") {
            $this->popRole();
        }
        return '</div>';
    }
    public function format_constant($open, $name, $attrs, $props)
    {
        if ($open) {
            if (str_contains($props["innerXml"], '<replaceable')) {
                $this->pushRole("constant_group");
                $this->cchunk["constant"] = $props["innerXml"];
            }
            return "<strong><code>";
        }

        if ($this->getRole() === "constant_group") {
            $this->popRole();

            $value = str_replace(
                ["<replaceable xmlns=\"http://docbook.org/ns/docbook\">", "</replaceable>"],
                ["<span class=\"replaceable\">", "</span>"],
                strip_tags($this->cchunk["constant"], "<replaceable>")
            );

            $link = $this->createReplaceableConstantLink(strip_tags($this->cchunk["constant"], "<replaceable>"));
            $this->cchunk["constant"] = "";

            if ($link === "") {
                return $value . '</code></strong>';
            }

            return '<a href="' . $link . '">' . $value . '</a></code></strong>';
        }
        return "</code></strong>";
    }

    /**
     * Creates a link to the first constant in the index
     * that matches the pattern of a constant containing a <replaceable> tag
     * or returns an empty string if no match was found.
     *
     * This works only with one set of <replaceable> tags in a constant
     * e.g. CURLE_<replaceable>*</replaceable> or DOM_<replaceable>*</replaceable>_NODE
     */
    private function createReplaceableConstantLink(string $constant): string {
        $pattern = "/" . preg_replace(
            "/<replaceable.*<\/replaceable>/",
            ".*",
            str_replace(
                ".",
                "\.",
                $this->convertConstantNameToId($constant)
            )
        ) ."/";

        $matchingConstantId = "";
        foreach ($this->indexes as $index) {
            if (preg_match($pattern, $index["docbook_id"])) {
                $matchingConstantId = $index["docbook_id"];
                break;
            }
        }

        return $matchingConstantId === "" ? "" : $this->createLink($matchingConstantId);
    }

    private function convertConstantNameToId(string $constantName): string {
        $tempLinkValue = str_replace(
            array("\\", "_"),
            array("-", "-"),
            trim($this->normalizeFQN($constantName), "_")
        );

        if (str_contains($constantName, '::')) {
            // class constant
            list($extensionAndClass, $constant) = explode("::", $tempLinkValue);
            $normalizedLinkFormat = $extensionAndClass . ".constants." . trim($constant, "-");
        } else {
            $normalizedLinkFormat = 'constant.' . $tempLinkValue;
        }

        return $normalizedLinkFormat;
    }

    public function format_constant_text($value, $tag) {
        if ($this->getRole() === "constant_group") {
            return "";
        }

        $normalizedLinkFormat = $this->convertConstantNameToId($value);

        $link = $this->createLink($normalizedLinkFormat);

        if ($link === null) {
            return $value;
        }

        return '<a href="' . $link . '">' . $value . '</a>';
    }
    public function format_replaceable($open, $name, $attrs, $props) {
        if ($this->getRole() === "constant_group") {
            return "";
        }
        return false;
    }

    public function format_property_text($value, $tag) {
        if (! str_contains($value, '::')) {
            return $value;
        }

        $tempLinkValue = str_replace(
            ["\\", "_", "$"],
            ["-", "-", ""],
            trim($this->normalizeFQN($value), "_")
        );

        list($extensionAndClass, $property) = explode("::", $tempLinkValue);
        $normalizedLinkFormat = $extensionAndClass . ".props." . trim($property, "-");

        $link = $this->createLink($normalizedLinkFormat);

        if ($link === null || $link === "") {
            return $value;
        }

        return '<a href="' . $link . '">' . $value . '</a>';
    }

    protected function normalizeFQN(string $fqn): string {
        return \ltrim(\strtolower($fqn), "\\");
    }

    public function admonition_title($title, $lang)
    {
        return '<strong class="' .(strtolower($title)). '">' .($this->autogen($title, $lang)). '</strong>';
    }
    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="'. $name. '">' .$this->admonition_title($name, $props["lang"]);
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
            return '<blockquote class="note"><p>'.$this->admonition_title("note", $props["lang"]). ': ';
        }
        return "</p></blockquote>";
    }
    public function format_note_title($open, $name, $attrs)
    {
        if ($open) {
            return '<strong>';
        }
        return '</strong><br />';
    }
    public function format_example($open, $name, $attrs, $props) {
        if ($open) {
            ++$this->exampleCounter;
            if (isset($props["id"])) {
                return '<div class="' . $name . '" id="' . $props["id"] . '">';
            }
            return '<div class="' . $name . '" id="' . $this->getGeneratedExampleId($this->exampleCounter - 1) . '">';
        }
        return '</div>';
    }
    public function format_example_title($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            return "<p><strong>" . ($this->autogen('example', $props['lang'])
                . (isset($this->cchunk["examples"]) ? ++$this->cchunk["examples"] : "")) . " ";
        }
        return "</strong></p>";
    }
    public function format_table_title($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            return "<caption><strong>";
        }
        return "</strong></caption>";
    }
    public function format_variablelist_title($open, $name, $attrs, $props) {
        if ($open) {
            return ($this->getRole() === 'constant_list') ? "<caption><strong>" : "<strong>";
        }
        return ($this->getRole() === 'constant_list') ? "</strong></caption>" : "</strong>";
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
    public function format_imagedata($open, $name, $attrs) {
        $file    = $attrs[Reader::XMLNS_DOCBOOK]["fileref"];
        if ($newpath = $this->mediamanager->handleFile($file)) {
            $curfile = $this->mediamanager->findFile($file);
            $width   = isset($attrs[Reader::XMLNS_DOCBOOK]["width"]) ? 'width="' . $attrs[Reader::XMLNS_DOCBOOK]["width"] . '"' : '';
            $height  = isset($attrs[Reader::XMLNS_DOCBOOK]["depth"]) ? 'height="' . $attrs[Reader::XMLNS_DOCBOOK]["depth"] . '"' : '';
            $alt     = 'alt="' . ($this->cchunk["mediaobject"]["alt"] !== false ? $this->cchunk["mediaobject"]["alt"] : basename($file)) . '"';

            // Generate height and width when none are supplied.
            if ($curfile && '' === $width . $height) {
                list(,,,$dimensions,,,,) = getimagesize($curfile);
            } else {
                $dimensions = $width . ' ' . $height;
            }

            // Generate warnings when only 1 dimension supplied or alt is not supplied.
            if (!$width xor !$height) {
                $this->outputHandler->v('Missing %s attribute for %s', (!$width ? 'width' : 'height'), $file, VERBOSE_MISSING_ATTRIBUTES);
            }
            if (false === $this->cchunk["mediaobject"]["alt"]) {
                $this->outputHandler->v('Missing alt attribute for %s', $file, VERBOSE_MISSING_ATTRIBUTES);
            }

            return '<img src="' . $newpath . '" ' . $alt . ' ' . $dimensions . ' />';
        } else {
            return '';
        }
    }

    public function format_table($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["table"] = true;
            // Initialize an empty tgroup in case we never process such element
            Format::tgroup(array());
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $idstr = ' id="' . $attrs[Reader::XMLNS_XML]["id"] . '"';
            }
            return '<table' . $idstr . ' class="doctable ' .$name. '">';
        }
        $this->cchunk["table"] = false;
        $str = "";
        if (isset($this->cchunk["tablefootnotes"]) && $this->cchunk["tablefootnotes"]) {
            $opts = array(Reader::XMLNS_DOCBOOK => array());

            $str =  $this->format_tbody(true, "footnote", $opts);
            $str .= $this->format_row(true, "footnote", $opts);
            $str .= $this->format_entry(true, "footnote", $opts, $props+array("colspan" => $this->getColCount()));

            foreach ($this->cchunk["tablefootnotes"] as $k => $noteid) {
                $str .= '<div class="footnote">';
                $str .= '<a name="fnid' .$noteid. '" href="#fn' .$noteid .'"><sup>[' .($k + 1). ']</sup></a>' .$this->cchunk["footnote"][$k]["str"];
                unset($this->cchunk["footnote"][$k]);
                $str .= "</div>\n";

            }
            $str .= $this->format_entry(false, "footnote", $opts, $props);
            $str .= $this->format_row(false, "footnote", $opts);
            $str .= $this->format_tbody(false, "footnote", $opts);

            $this->cchunk["tablefootnotes"] = $this->dchunk["tablefootnotes"];
        }
        return "$str</table>\n";
    }
    public function format_tgroup($open, $name, $attrs) {
        if ($open) {
            Format::tgroup($attrs[Reader::XMLNS_DOCBOOK]);
            return '';
        }
        return '';
    }

    private static function parse_table_entry_attributes($attrs)
    {
        $style  = array();
        $retval = '';
        if (!empty($attrs['align'])) {
            if ('char' != $attrs['align']) {
                $style[] = 'text-align: ' . $attrs['align'];
            } elseif (isset($attrs['char'])) {
                // There's no analogue in CSS, but as this stuff isn't supported
                // in any browser, it is unlikely to appear in DocBook anyway
                $retval .= ' align="char" char="'
                           . htmlspecialchars($attrs["char"], ENT_QUOTES) . '"';
                if (isset($attrs['charoff'])) {
                    $retval .= ' charoff="'
                               . htmlspecialchars($attrs["charoff"], ENT_QUOTES) . '"';
                }
            }
        }
        if (isset($attrs["valign"])) {
            $style[] = 'vertical-align: ' . $attrs["valign"];
        }
        if (isset($attrs["colwidth"])) {
            if (preg_match('/^\\d+\\*$/', $attrs['colwidth'])) {
                // relative_length measure has no analogue in CSS and is
                // unsupported in browsers, leave as is
                $retval .= ' width="' . $attrs['colwidth'] . '"';
            } else {
                // probably fixed width, use inline styles
                $style[] = 'width: ' . $attrs['colwidth'];
            }
        }
        return $retval . (empty($style) ? '' : ' style="' . implode('; ', $style) . ';"');
    }

    public function format_colspec($open, $name, $attrs)
    {
        if ($open) {
            $str = self::parse_table_entry_attributes(Format::colspec($attrs[Reader::XMLNS_DOCBOOK]));
            return '<col' . $str . ' />';
        }
        /* noop */
    }

    public function format_th($open, $name, $attrs)
    {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['valign'])) {
                return '<' . $name . ' style="vertical-align: '
                       . $attrs[Reader::XMLNS_DOCBOOK]['valign'] . ';">';
            } else {
                return '<' . $name . '>';
            }
        }
        return "</$name>\n";
    }

    public function format_tbody($open, $name, $attrs)
    {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['valign'])) {
                return '<tbody class="' . $name . '" style="vertical-align: '
                       . $attrs[Reader::XMLNS_DOCBOOK]['valign'] . ';">';
            } else {
                return '<tbody class="' . $name . '">';
            }
        }
        return "</tbody>";
    }

    public function format_row($open, $name, $attrs)
    {
        if ($open) {
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]['id'])) {
                $idstr = ' id="'. $attrs[Reader::XMLNS_XML]['id']. '"';
            }
            Format::initRow();
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['valign'])) {
                return '<tr' . $idstr . ' style="vertical-align: '
                       . $attrs[Reader::XMLNS_DOCBOOK]['valign'] . ';">';
            } else {
                return '<tr' . $idstr . '>';
            }
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
            $dbattrs = (array)Format::getColspec($attrs[Reader::XMLNS_DOCBOOK]);

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
            return $retval. '<td' . $sColspan . $sRowspan . $moreattrs. '>';
        }
        return "</td>";
    }
    public function format_qandaset($open, $name, $attrs, $props) {
        if ($open) {
            $xml = "<qandaset>" . $props["innerXml"] . "</qandaset>";
            $doc = new \DOMDocument;
            $doc->loadXml($xml);

            $xp = new \DOMXPath($doc);
            $xp->registerNamespace("db", Reader::XMLNS_DOCBOOK);

            $questions = $xp->query("//db:qandaentry/db:question");

            $retval = '<div class="qandaset"><ol class="qandaset_questions">';
            foreach($questions as $node) {
                $id = $xp->evaluate("ancestor::db:qandaentry", $node)->item(0)->getAttributeNs(Reader::XMLNS_XML, "id");

                /* FIXME: No ID? How can we create an anchor for it then? */
                if (!$id) {
                    $id = uniqid("phd");
                }

                $retval .= '<li><a href="#'.$id.'">'.htmlentities($node->textContent, ENT_QUOTES, "UTF-8").'</a></li>';
            }
            $retval .= "</ol></div>";
            return $retval;
        }
    }
    public function format_question($open, $name, $attrs, $props) {
        if ($open) {
            return '<dt><strong>';
        }
        return '</strong></dt>';
    }
    public function format_phd_question($open, $name, $attrs, $props) {
        if ($open) {
            $href = $this->createLink($attrs[Reader::XMLNS_XML]["id"]);
            return '<li><a href="' .$href. '">';
        }
        return '</a></li>';
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

    public function format_bold_paragraph($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            return "<p><strong>";
        }
        return "</strong></p>";
    }

   /**
    * Functions from the old XHTMLPhDFormat
    */
    public function format_legalnotice_chunk($open, $name, $attrs) {
        if ($open) {
            return '<div id="legalnotice">';
        }
        return "</div>\n";
    }

    public function format_div($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="' . $name . '">';
        }
        return '</div>';
    }

    public function format_screen_text($value, $tag) {
        return nl2br($this->TEXT($value));
    }

    /**
    * Renders  a <tag class=""> tag.
    *
    * @return string HTML code
    */
    public function format_tag($open, $name, $attrs, $props) {
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

        $class = 'starttag';
        if (isset($attrs['class'])) {
            $class = $attrs['class'];
        }

        if (!isset($arFixes[$class])) {
            trigger_error('Unknown tag class "' . $class . '"', E_USER_WARNING);
            $class = 'starttag';
        }
        if (!$open) {
            return $arFixes[$class][1] . '</code>';
        }

        return '<code>' . $arFixes[$class][0];
    }

    public function format_dl($open, $name, $attrs, $props) {
        if ($open) {
            return '<dl class="' . $name . '">';
        }
        return '</dl>';
    }

    public function format_itemizedlist($open, $name, $attrs, $props) {
        if ($open) {
            return '<ul class="' . $name . '">';
        }
        return '</ul>';
    }

    public function format_simplelist($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["simplelist"]["type"] = $attrs[Reader::XMLNS_DOCBOOK]["type"] ?? "";
            $this->cchunk["simplelist"]["columns"] = $attrs[Reader::XMLNS_DOCBOOK]["columns"] ?? 1;

            if ($this->cchunk["simplelist"]["columns"] < 1) {
                $this->cchunk["simplelist"]["columns"] = 1;
            }

            if ($this->cchunk["simplelist"]["type"] === "inline") {
                return '<span class="' . $name . '">';
            }

            if ($this->cchunk["simplelist"]["type"] === "vert"
                || $this->cchunk["simplelist"]["type"] === "horiz") {
                return '<table class="' . $name . '">' . "\n" . $this->indent($props["depth"] + 1) . "<tbody>\n";
            }

            return '<ul class="' . $name . '">';
        }

        $list = match ($this->cchunk["simplelist"]["type"]) {
            "inline" => $this->simplelist_format_inline(),
            "horiz"  => $this->simplelist_format_horizontal($props["depth"])
                        . $this->indent($props["depth"] + 1) . "</tbody>\n"
                        . $this->indent($props["depth"]) . "</table>",
            "vert"   => $this->simplelist_format_vertical($props["depth"])
                        . $this->indent($props["depth"] + 1) . "</tbody>\n"
                        . $this->indent($props["depth"]) . "</table>",
            default  => "</ul>",
        };

        $this->cchunk["simplelist"] = $this->dchunk["simplelist"];

        return $list;
    }

    private function indent($depth): string {
        return $depth > 0 ? str_repeat(' ', $depth) : '';
    }

    private function simplelist_format_inline() {
        return implode(", ", $this->cchunk["simplelist"]["members"]) . '</span>';
    }

    private function simplelist_format_horizontal($depth) {
        return $this->chunkReduceTable(
            $this->processTabular(
                $this->cchunk["simplelist"]["members"],
                $this->cchunk["simplelist"]["columns"],
                $depth),
            $this->cchunk["simplelist"]["columns"],
            $depth
        );
    }

    /** Return formatted rows */
    private function chunkReduceTable(array $members, int $cols, int $depth): string
    {
        $trPadding = $this->indent($depth + 2);
        return array_reduce(
                array_chunk(
                    $members,
                    $cols,
                ),
                fn (string $carry, array $entry) => $carry . $trPadding . "<tr>\n" . implode('', $entry) . $trPadding . "</tr>\n",
                ''
            );
    }

    /** Pads $members so that number of members = columns x rows */
    private function processTabular(array $members, int $cols, int $depth): array
    {
        $tdPadding = $this->indent($depth + 3);
        return array_map(
            fn (string $member) => $tdPadding . "<td>$member</td>\n",
            /** The padding is done by getting the additive modular inverse which is
             * ``-\count($members) % $cols`` but because PHP gives us the mod in negative we need to
             * add $cols back to get the positive
             */
            [...$members, ...array_fill(0, (-\count($members) % $cols) + $cols, '')]
        );
    }

    private function simplelist_format_vertical($depth) {
        $members = $this->processTabular(
            $this->cchunk["simplelist"]["members"],
            $this->cchunk["simplelist"]["columns"],
            $depth
        );
        // Sort elements so that we get each correct element for the rows to display vertically
        uksort(
            $members,
            fn (int $l, int $r) => $l % $this->cchunk["simplelist"]["columns"] <=> $r % $this->cchunk["simplelist"]["columns"]
        );
        return $this->chunkReduceTable($members, $this->cchunk["simplelist"]["columns"], $depth);
    }

    public function format_member($open, $name, $attrs, $props) {
        if ($this->cchunk["simplelist"]["type"] === "inline"
            || $this->cchunk["simplelist"]["type"] === "vert"
            || $this->cchunk["simplelist"]["type"] === "horiz") {
            $this->appendToBuffer = $open;
            if (! $open) {
                $this->cchunk["simplelist"]["members"][] = $this->buffer;
            }
            $this->buffer = '';
            return '';
        }
        if ($open) {
            return '<li>';
        }
        return '</li>';
    }

    public function format_member_text($value, $tag) {
        return $value;
    }

    public function format_orderedlist($open, $name, $attrs, $props) {
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
            return '<ol type="' .$numeration. '">';
        }
        return '</ol>';
    }

    /* Support for key inputs is coded like junk */
    public function format_keycap($open, $name, $attrs, $props) {
        if ($open) {
            $content = '';
            if ($props['sibling']) {
                $content = '+';
            }
            return $content . '<kbd class="' . $name . '">';
        }
        return '</kbd>';
    }

    public function format_keycombo($open, $name, $attrs, $props) {

        if (isset($attrs[Reader::XMLNS_DOCBOOK]["action"])) {
            if ($attrs[Reader::XMLNS_DOCBOOK]["action"] !== "simul") {
                trigger_error(vsprintf('No support for keycombo action = %s', [$attrs[Reader::XMLNS_DOCBOOK]["action"]]), E_USER_WARNING);
            }
        }
        if ($open) {
            return '<kbd class="' . $name . '">';
        }
        return '</kbd>';
    }

    public function format_whitespace($whitespace, $elementStack, $currentDepth) {
        /* The following if is to skip unnecessary whitespaces in the parameter list */
        if (
            in_array($elementStack[$currentDepth - 1], ['methodsynopsis', 'constructorsynopsis', 'destructorsynopsis'], true)
            && (in_array($elementStack[$currentDepth] ?? "", ["methodname", "methodparam", "type", "void"], true)
            || count($elementStack) === $currentDepth)
        ) {
            return false;
        }

        /* The following if is to skip whitespace before closing semicolon after property/class constant */
        if ($elementStack[$currentDepth - 1] === "fieldsynopsis" && (in_array($elementStack[$currentDepth], ["varname", "initializer"], true))) {
            return false;
        }

        /*
        TODO: add trim() in type_text handling method and remove the below
              as it doesn't work due to XMLReader including all whitespace
              inside the tag in the text
              hence no separate significant whitespace here
          */
        /* The following if is to skip whitespace inside type elements */
        if ($elementStack[$currentDepth - 1] === "type") {
            return false;
        }

        if (
            $elementStack[$currentDepth - 1] === "simplelist"
            && ($this->cchunk["simplelist"]["type"] === "inline"
                || $this->cchunk["simplelist"]["type"] === "vert"
                || $this->cchunk["simplelist"]["type"] === "horiz")
            ) {
            return false;
        }

        /* The following if is to skip unnecessary whitespaces in the implements list */
        if (
            ($elementStack[$currentDepth - 1] === 'classsynopsisinfo' && $elementStack[$currentDepth] === 'oointerface') ||
            ($elementStack[$currentDepth - 1] === 'oointerface' && $elementStack[$currentDepth] === 'interfacename')
        ) {
            return false;
        }

        return $whitespace;
    }

    public function format_caption($open, $name, $attrs, $props) {
        return $open ? '<div class="caption">' : '</div>';
    }

    public function getGeneratedExampleID($index)
    {
        $originalId = parent::getGeneratedExampleID($index);
        if (! $this->exampleCounterIsPerPage) {
            return $originalId;
        }
        if (preg_match('/^example\-[0-9]+$/', $originalId)) {
            $this->perPageExampleCounter++;
            $this->perPageExampleIds[$originalId] = 'example-' . $this->perPageExampleCounter;
            return $this->perPageExampleIds[$originalId];
        }
        return $originalId;
    }

    public function onNewPage(): void
    {
        $this->perPageExampleCounter = 0;
    }
}
