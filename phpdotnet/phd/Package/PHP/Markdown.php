<?php
namespace phpdotnet\phd;

class Package_PHP_Markdown extends Format {

    const OPEN_CHUNK  = 0x01;
    const CLOSE_CHUNK = 0x02;

    protected bool $chunkOpen = false;
    protected array $cchunk = [];
    protected array $pChunk = [];

    private array $dchunk = [
        "funcname"          => [],
        "firstrefname"      => true,
        "chunkid"           => "",
        "role"              => null,
        "methodsynopsis"    => [
            "returntype"    => "",
            "methodname"    => "",
            "params"        => [],
            "firstsynopsis" => true,
            "suppress"      => false,
        ],
        "listdepth"         => 0,
        "listtype"          => [],
        "listcount"         => [],
        "tableheader"       => false,
        "tablecols"         => 0,
        "tablerow"          => [],
        "tablecellcount"    => 0,
        "examplenumber"     => 0,
        "inprogramlisting"  => false,
        "programlistingrole"=> null,
        "classsyn_suppress" => false,
        "classsyn"          => ["modifier" => "", "name" => "", "extends" => "", "implements" => []],
        "_cur_ooclass"      => ["modifier" => "", "name" => ""],
        "_classdecl_written"=> false,
        "_pkg_name"         => "",
        "_pkg_has_class"    => false,
        "_in_enumdesc"      => false,
        "_in_initializer"   => false,
        "_bq_depth"         => 0,
        "_bq_buf"           => "",
        "_bq_label"         => "",
        "_in_entry"         => false,
        "_pending_func"     => null,
    ];

    private array $elementmap = [
        /* Chunk boundaries — refentry/class/var get simple chunk, structural get container chunk */
        'refentry'              => 'format_refentry_chunk',
        'phpdoc:classref'       => 'format_container_chunk',
        'phpdoc:exceptionref'   => 'format_container_chunk',
        'phpdoc:varentry'       => 'format_simple_chunk',
        'set'                   => 'format_root_chunk',
        'book'                  => 'format_container_chunk',
        'part'                  => 'format_container_chunk',
        'chapter'               => 'format_container_chunk',
        'appendix'              => 'format_container_chunk',
        'article'               => 'format_container_chunk',
        'preface'               => 'format_suppressed_tags',
        'reference'             => 'format_container_chunk',
        'setindex'              => 'format_simple_chunk',
        'legalnotice'           => 'format_simple_chunk',

        /* Non-chunk structure */
        'partintro'             => 'format_suppressed_tags',
        'info'                  => 'format_suppressed_tags',
        'titleabbrev'           => 'format_suppressed_tags',
        'pubdate'               => 'format_suppressed_tags',
        'copyright'             => 'format_suppressed_tags',
        'authorgroup'           => 'format_suppressed_tags',
        'author'                => 'format_suppressed_tags',
        'affiliation'           => 'format_suppressed_tags',
        'indexterm'             => 'format_suppressed_tags',
        'primary'               => 'format_suppressed_tags',
        'secondary'             => 'format_suppressed_tags',
        'index'                 => 'format_suppressed_tags',
        'indexdiv'              => 'format_suppressed_tags',
        'indexentry'            => 'format_suppressed_tags',
        'colophon'              => 'format_suppressed_tags',
        'abstract'              => 'format_suppressed_tags',
        'imageobject'           => 'format_suppressed_tags',
        'imagedata'             => 'format_suppressed_tags',
        'figure'                => 'format_suppressed_tags',
        'alt'                   => 'format_suppressed_tags',
        'phd:toc'               => 'format_suppressed_tags',
        'phd:tocentry'          => 'format_suppressed_tags',
        'xi:include'            => 'format_suppressed_tags',

        /* Titles / sections */
        'title'                 => [
            /* DEFAULT */          'format_chunk_title',
            'book'              => 'format_chunk_title',
            'part'              => 'format_chunk_title',
            'chapter'           => 'format_chunk_title',
            'appendix'          => 'format_chunk_title',
            'article'           => 'format_chunk_title',
            'reference'         => 'format_chunk_title',
            'preface'           => 'format_chunk_title',
            'set'               => 'format_suppressed_tags',
            'refsect1'          => 'format_refsect1_title',
            'refsect2'          => 'format_refsect2_title',
            'refsect3'          => 'format_refsect3_title',
            'section'           => 'format_section_title',
            'sect1'             => 'format_section_title',
            'sect2'             => 'format_refsect2_title',
            'sect3'             => 'format_refsect3_title',
            'sect4'             => 'format_refsect3_title',
            'simplesect'        => 'format_refsect2_title',
            'example'           => 'format_example_title',
            'note'              => 'format_admonition_title',
            'caution'           => 'format_admonition_title',
            'warning'           => 'format_admonition_title',
            'tip'               => 'format_admonition_title',
            'important'         => 'format_admonition_title',
            'table'             => 'format_table_title',
            'informaltable'     => 'format_suppressed_tags',
        ],

        /* refentry parts */
        'refnamediv'            => 'format_suppressed_tags',
        'refname'               => 'format_refname',
        'refpurpose'            => 'format_refpurpose',
        'refsynopsisdiv'        => 'format_refsynopsisdiv',
        'refsect1'              => 'format_refsect1',
        'refsect2'              => 'format_suppressed_tags',
        'refsect3'              => 'format_suppressed_tags',
        'refsection'            => 'format_refsect1',
        'section'               => 'format_container_chunk',
        'sect1'                 => 'format_container_chunk',
        'sect2'                 => 'format_container_chunk',
        'sect3'                 => 'format_container_chunk',
        'sect4'                 => 'format_container_chunk',
        'simplesect'            => 'format_suppressed_tags',

        /* Method/function synopsis */
        'methodsynopsis'        => 'format_methodsynopsis',
        'constructorsynopsis'   => 'format_methodsynopsis',
        'destructorsynopsis'    => 'format_methodsynopsis',
        'classsynopsis'         => 'format_classsynopsis',
        'classsynopsisinfo'     => 'format_suppressed_tags',
        'ooclass'               => 'format_ooclass',
        'oointerface'           => 'format_oointerface',
        'ooexception'           => 'format_oointerface',
        'fieldsynopsis'         => 'format_suppressed_tags',
        'modifier'              => [
            /* DEFAULT */          'format_code_inline',
            'classsynopsisinfo' => 'format_suppressed_tags',
            'ooclass'           => 'format_suppressed_tags',
            'oointerface'       => 'format_suppressed_tags',
            'ooexception'       => 'format_suppressed_tags',
            'fieldsynopsis'     => 'format_suppressed_tags',
        ],
        'methodname'            => [
            /* DEFAULT */          'format_methodname_link',
            'methodsynopsis'    => 'format_methodsynopsis_name',
            'constructorsynopsis'=> 'format_methodsynopsis_name',
            'destructorsynopsis'=> 'format_methodsynopsis_name',
        ],
        'type'                  => [
            /* DEFAULT */          'format_type_inline',
            'fieldsynopsis'     => 'format_suppressed_tags',
            'classsynopsisinfo' => 'format_suppressed_tags',
            'methodsynopsis'    => 'format_methodsynopsis_returntype',
            'constructorsynopsis'=> 'format_methodsynopsis_returntype',
            'destructorsynopsis'=> 'format_methodsynopsis_returntype',
            'methodparam'       => 'format_methodparam_type',
            'type'              => [
                /* DEFAULT */      'format_type_inline',
                'methodsynopsis' => 'format_methodsynopsis_returntype',
                'constructorsynopsis'=> 'format_methodsynopsis_returntype',
                'destructorsynopsis'=> 'format_methodsynopsis_returntype',
                'methodparam'    => 'format_methodparam_type',
                'type'           => [
                    /* DEFAULT */  'format_type_inline',
                    'methodsynopsis' => 'format_methodsynopsis_returntype',
                    'constructorsynopsis'=> 'format_methodsynopsis_returntype',
                    'destructorsynopsis'=> 'format_methodsynopsis_returntype',
                    'methodparam' => 'format_methodparam_type',
                ],
            ],
        ],
        'methodparam'           => 'format_methodparam',
        'parameter'             => [
            /* DEFAULT */          'format_parameter_inline',
            'methodparam'       => 'format_methodparam_parameter',
        ],
        'initializer'           => [
            /* DEFAULT */          'format_suppressed_tags',
            'methodparam'       => 'format_methodparam_initializer',
        ],
        'void'                  => 'format_void',
        'funcsynopsis'          => 'format_suppressed_tags',
        'funcsynopsisinfo'      => 'format_suppressed_tags',
        'funcdef'               => 'format_suppressed_tags',
        'funcprototype'         => 'format_suppressed_tags',
        'paramdef'              => 'format_suppressed_tags',

        /* Block elements */
        'para'                  => [
            /* DEFAULT */          'format_para',
            'listitem'          => 'format_para_listitem',
            'entry'             => 'format_suppressed_tags',
            'footnote'          => 'format_suppressed_tags',
        ],
        'simpara'               => 'format_para',
        'formalpara'            => 'format_suppressed_tags',
        'literallayout'         => 'format_verbatim',
        'programlisting'        => 'format_programlisting',
        'screen'                => 'format_verbatim',
        'synopsis'              => 'format_verbatim',
        'informalexample'       => 'format_suppressed_tags',
        'example'               => 'format_example',
        'blockquote'            => 'format_blockquote',
        'sidebar'               => 'format_blockquote',
        'procedure'             => 'format_suppressed_tags',
        'step'                  => 'format_suppressed_tags',

        /* Admonitions */
        'note'                  => 'format_admonition',
        'caution'               => 'format_admonition',
        'warning'               => 'format_admonition',
        'tip'                   => 'format_admonition',
        'important'             => 'format_admonition',

        /* Lists */
        'itemizedlist'          => 'format_itemizedlist',
        'orderedlist'           => 'format_orderedlist',
        'listitem'              => 'format_listitem',
        'variablelist'          => 'format_suppressed_tags',
        'varlistentry'          => 'format_suppressed_tags',
        'term'                  => 'format_term',
        'simplelist'            => 'format_suppressed_tags',
        'member'                => 'format_member',

        /* Tables */
        'table'                 => 'format_table',
        'informaltable'         => 'format_table',
        'tgroup'                => 'format_tgroup',
        'colspec'               => 'format_suppressed_tags',
        'thead'                 => 'format_thead',
        'tbody'                 => 'format_suppressed_tags',
        'tfoot'                 => 'format_suppressed_tags',
        'row'                   => 'format_row',
        'entry'                 => 'format_entry',

        /* Inline elements */
        'function'              => 'format_function_link',
        'classname'             => [
            /* DEFAULT */          'format_classname_link',
            'classsynopsisinfo' => 'format_suppressed_tags',
            'ooclass'           => 'format_suppressed_tags',
            'oointerface'       => 'format_suppressed_tags',
            'ooexception'       => 'format_suppressed_tags',
            'fieldsynopsis'     => 'format_suppressed_tags',
        ],
        'interfacename'         => 'format_classname_link',
        'exceptionname'         => 'format_classname_link',
        'varname'               => [
            /* DEFAULT */          'format_code_inline',
            'fieldsynopsis'     => 'format_suppressed_tags',
        ],
        'constant'              => 'format_code_inline',
        'literal'               => 'format_code_inline',
        'code'                  => 'format_code_inline',
        'command'               => 'format_code_inline',
        'computeroutput'        => 'format_code_inline',
        'userinput'             => 'format_code_inline',
        'envar'                 => 'format_code_inline',
        'filename'              => 'format_code_inline',
        'option'                => 'format_code_inline',
        'property'              => 'format_code_inline',
        'errorcode'             => 'format_code_inline',
        'errortext'             => 'format_code_inline',
        'emphasis'              => [
            /* DEFAULT */          'format_emphasis',
            'entry'             => 'format_suppressed_tags',
        ],
        'replaceable'           => 'format_emphasis',
        'acronym'               => 'format_suppressed_tags',
        'abbrev'                => 'format_suppressed_tags',
        'link'                  => 'format_link',
        'xref'                  => 'format_xref',
        'uri'                   => 'format_uri',
        'email'                 => 'format_suppressed_tags',
        'citetitle'             => 'format_emphasis',
        'citation'              => 'format_suppressed_tags',
        'footnote'              => 'format_suppressed_tags',
        'footnoteref'           => 'format_suppressed_tags',
        'subscript'             => 'format_suppressed_tags',
        'superscript'           => 'format_suppressed_tags',
        'quote'                 => 'format_quote',
        'tag'                   => 'format_code_inline',
        'application'           => 'format_emphasis',
        'systemitem'            => 'format_code_inline',
        'productname'           => 'format_emphasis',
        'phrase'                => 'format_suppressed_tags',
        'trademark'             => 'format_suppressed_tags',
        'optional'              => 'format_optional',

        /* Changelog / segmented lists */
        'segmentedlist'         => 'format_suppressed_tags',
        'seglistitem'           => 'format_suppressed_tags',
        'segtitle'              => 'format_suppressed_tags',
        'seg'                   => 'format_suppressed_tags',

        /* Callouts */
        'calloutlist'           => 'format_suppressed_tags',
        'callout'               => 'format_suppressed_tags',
        'co'                    => 'format_suppressed_tags',

        /* Mediaobject */
        'mediaobject'           => 'format_suppressed_tags',
        'textobject'            => 'format_suppressed_tags',
        'inlinemediaobject'     => 'format_suppressed_tags',

        /* Misc */
        'refentrytitle'         => 'format_suppressed_tags',
        'biblioentry'           => 'format_suppressed_tags',
        'bibliography'          => 'format_suppressed_tags',
        'glossary'              => 'format_suppressed_tags',
        'glossterm'             => 'format_suppressed_tags',
        'glossdef'              => 'format_suppressed_tags',
        'qandaset'              => 'format_suppressed_tags',
        'qandaentry'            => 'format_suppressed_tags',
        'question'              => 'format_suppressed_tags',
        'answer'                => 'format_suppressed_tags',
        'cmdsynopsis'           => 'format_suppressed_tags',
        'arg'                   => 'format_suppressed_tags',
        'group'                 => 'format_suppressed_tags',
        'revision'              => 'format_suppressed_tags',
        'revhistory'            => 'format_suppressed_tags',
        'date'                  => 'format_suppressed_tags',
        'othercredit'           => 'format_suppressed_tags',
        'personname'            => 'format_suppressed_tags',
        'firstname'             => 'format_suppressed_tags',
        'surname'               => 'format_suppressed_tags',
        'honorific'             => 'format_suppressed_tags',
        'othername'             => 'format_suppressed_tags',
        'contrib'               => 'format_suppressed_tags',
        'edition'               => 'format_suppressed_tags',
        'productionset'         => 'format_suppressed_tags',
        'production'            => 'format_suppressed_tags',
        'lhs'                   => 'format_suppressed_tags',
        'rhs'                   => 'format_suppressed_tags',
        'nonterminal'           => 'format_suppressed_tags',
        'annotation'            => 'format_suppressed_tags',
        'bridgehead'            => 'format_suppressed_tags',
        'collab'                => 'format_suppressed_tags',
        'collabname'            => 'format_suppressed_tags',
        'holder'                => 'format_suppressed_tags',
        'year'                  => 'format_suppressed_tags',

        /* Elements encountered in PHP manual not in core DocBook list */
        'caption'               => 'format_caption',
        'citerefentry'          => 'format_citerefentry',
        'keycap'                => 'format_keycap',
        'keycombo'              => 'format_keycombo',
        'manvolnum'             => 'format_manvolnum',
        'package'               => [
            /* DEFAULT */          'format_code_inline',
            'packagesynopsis'   => 'format_pkg_name',
        ],
        'packagesynopsis'       => 'format_packagesynopsis',
        'enumsynopsis'          => 'format_enumsynopsis',
        'enumidentifier'        => 'format_enumidentifier',
        'enumitem'              => 'format_enumitem',
        'enumitemdescription'   => 'format_enumitemdescription',
        'enumname'              => [
            /* DEFAULT */          'format_code_inline',
            'enumsynopsis'      => 'format_enumsynopsis_enumname',
        ],
        'enumvalue'             => 'format_enumvalue',
    ];

    private array $textmap = [
        'refname'               => 'format_refname_text',
        'function'              => 'format_function_link_text',
        'type'                  => [
            /* DEFAULT */          false,
            'fieldsynopsis'     => 'format_suppress_text',
            'classsynopsisinfo' => 'format_suppress_text',
            'methodsynopsis'    => 'format_methodsynopsis_returntype_text',
            'constructorsynopsis'=> 'format_methodsynopsis_returntype_text',
            'destructorsynopsis'=> 'format_methodsynopsis_returntype_text',
            'methodparam'       => 'format_methodparam_type_text',
            'type'              => [
                /* DEFAULT */      false,
                'methodsynopsis' => 'format_methodsynopsis_returntype_text',
                'constructorsynopsis'=> 'format_methodsynopsis_returntype_text',
                'destructorsynopsis'=> 'format_methodsynopsis_returntype_text',
                'methodparam'    => 'format_methodparam_type_text',
                'type'           => [
                    /* DEFAULT */  false,
                    'methodsynopsis' => 'format_methodsynopsis_returntype_text',
                    'constructorsynopsis'=> 'format_methodsynopsis_returntype_text',
                    'destructorsynopsis'=> 'format_methodsynopsis_returntype_text',
                    'methodparam' => 'format_methodparam_type_text',
                ],
            ],
        ],
        'methodname'            => [
            /* DEFAULT */          'format_methodname_link_text',
            'methodsynopsis'    => 'format_methodsynopsis_name_text',
            'constructorsynopsis'=> 'format_methodsynopsis_name_text',
            'destructorsynopsis'=> 'format_methodsynopsis_name_text',
        ],
        'parameter'             => [
            /* DEFAULT */          'format_parameter_inline_text',
            'methodparam'       => 'format_methodparam_name_text',
        ],
        'initializer'           => [
            /* DEFAULT */          'format_suppress_text',
            'methodparam'       => 'format_methodparam_initializer_text',
        ],
        'programlisting'        => 'format_verbatim_text',
        'screen'                => 'format_verbatim_text',
        'literallayout'         => 'format_verbatim_text',
        'synopsis'              => 'format_verbatim_text',
        // Suppress text inside suppressed structural elements
        'title'                 => [
            /* DEFAULT */          false,
            'set'               => 'format_suppress_text',
        ],
        'titleabbrev'           => 'format_suppress_text',
        'pubdate'               => 'format_suppress_text',
        'primary'               => 'format_suppress_text',
        'secondary'             => 'format_suppress_text',
        'varname'               => [
            /* DEFAULT */          false,
            'fieldsynopsis'     => 'format_suppress_text',
        ],
        // Suppress text inside class-synopsis structural elements
        'classsynopsis'         => 'format_suppress_text',
        'classsynopsisinfo'     => 'format_suppress_text',
        'ooclass'               => 'format_suppress_text',
        'oointerface'           => 'format_suppress_text',
        'ooexception'           => 'format_suppress_text',
        'fieldsynopsis'         => 'format_suppress_text',
        'modifier'              => [
            /* DEFAULT */          false,
            'classsynopsisinfo' => 'format_suppress_text',
            'ooclass'           => 'format_ooclass_modifier_text',
            'oointerface'       => 'format_suppress_text',
            'ooexception'       => 'format_suppress_text',
            'fieldsynopsis'     => 'format_suppress_text',
        ],
        'classname'             => [
            /* DEFAULT */          'format_classname_link_text',
            'classsynopsisinfo' => 'format_suppress_text',
            'ooclass'           => 'format_ooclass_classname_text',
            'oointerface'       => 'format_suppress_text',
            'ooexception'       => 'format_suppress_text',
        ],
        'interfacename'         => [
            /* DEFAULT */          'format_classname_link_text',
            'oointerface'       => 'format_oointerface_name_text',
        ],
        'exceptionname'         => [
            /* DEFAULT */          'format_classname_link_text',
            'ooexception'       => 'format_oointerface_name_text',
        ],
        'package'               => [
            /* DEFAULT */          false,
            'packagesynopsis'   => 'format_pkg_name_text',
        ],
        'enumitemdescription'   => 'format_suppress_text',
    ];

    public function __construct(Config $config, OutputHandler $outputHandler) {
        parent::__construct($config, $outputHandler);

        $this->registerFormatName("PHP-Markdown");
        $this->setExt($this->config->ext ?? ".md");
        $this->setChunked(true);
        $this->cchunk = $this->dchunk;
        $this->registerPIHandlers([
            'phpdoc'      => 'PI_MarkdownPHPDOCHandler',
            'dbtimestamp' => 'PI_DBHTMLHandler',
        ]);
    }

    public function __destruct() {
        foreach ($this->getFileStream() as $fp) {
            fclose($fp);
        }
    }

    public function update($event, $value = null): void {
        switch ($event) {
        case Render::CHUNK:
            switch ($value) {
            case self::OPEN_CHUNK:
                if ($this->getFileStream()) {
                    $this->pChunk = $this->cchunk;
                }
                $this->pushFileStream(fopen("php://temp/maxmemory", "r+"));
                $this->cchunk    = $this->dchunk;
                $this->chunkOpen = true;
                break;

            case self::CLOSE_CHUNK:
                $stream = $this->popFileStream();
                $this->writeChunk($stream);
                fclose($stream);
                if ($this->getFileStream()) {
                    $this->cchunk    = $this->pChunk;
                    $this->chunkOpen = true;
                } else {
                    $this->cchunk    = $this->dchunk;
                    $this->chunkOpen = false;
                }
                break;
            }
            break;

        case Render::STANDALONE:
            $this->registerElementMap(static::getDefaultElementMap());
            $this->registerTextMap(static::getDefaultTextMap());
            break;

        case Render::INIT:
            $outputDir = $this->config->outputDir . strtolower($this->toValidName($this->getFormatName())) . '/';
            $this->setOutputDir($outputDir);
            if (!file_exists($outputDir)) {
                if (!mkdir($outputDir, 0777, true)) {
                    throw new \Error("Can't create output directory");
                }
            } elseif (!is_dir($outputDir)) {
                throw new \Error('Output directory is a file?');
            }
            break;

        case Render::VERBOSE:
            $this->outputHandler->v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
            break;
        }
    }

    public function appendData($data): int {
        if (!$this->chunkOpen || $data === null || $data === false || $data === "") {
            return 0;
        }
        // Suppress incidental output from child elements inside methodsynopsis or classsynopsis
        if (!empty($this->cchunk["methodsynopsis"]["suppress"])) {
            return 0;
        }
        if (!empty($this->cchunk["classsyn_suppress"])) {
            return 0;
        }
        if (!empty($this->cchunk["_in_enumdesc"])) {
            return 0;
        }
        // Outside preformatted blocks, drop structural whitespace (XML indentation containing newlines).
        // Preserve plain spaces — they may be meaningful between adjacent inline elements.
        if (empty($this->cchunk["inprogramlisting"]) && trim($data, " \t") === "" && strpos($data, "\n") !== false) {
            return 0;
        }
        // Inside table cells, collapse newlines to spaces to keep GFM table rows single-line
        if (!empty($this->cchunk["_in_entry"])) {
            $data = preg_replace('/\s*\n\s*/', ' ', $data);
            if ($data === "" || $data === " ") {
                return 0;
            }
        }
        // Buffer content inside blockquote/admonition for line-by-line prefix processing
        if (!empty($this->cchunk["_bq_depth"])) {
            $this->cchunk["_bq_buf"] .= $data;
            return strlen($data);
        }
        $streams = $this->getFileStream();
        $stream  = end($streams);
        if (!is_resource($stream)) {
            return 0;
        }
        return fwrite($stream, $data);
    }

    private function cleanMarkdown(string $content): string {
        // Collapse 3+ consecutive blank lines to 2
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        // Trim leading/trailing whitespace from the whole document
        return trim($content) . "\n";
    }

    public function writeChunk($stream): void {
        rewind($stream);
        $content = $this->cleanMarkdown(stream_get_contents($stream));

        $id = $this->cchunk["chunkid"] ?? "";
        if ($id === "" || $content === "\n") {
            return;
        }
        $path = $this->getOutputDir() . $id . $this->getExt();
        file_put_contents($path, $content);
        $this->outputHandler->v("Wrote %s", $path, VERBOSE_CHUNK_WRITING);
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        return $tag;
    }

    public function TEXT($value) {
        // Pure indentation (whitespace containing a newline) → suppress entirely
        if (trim($value) === "" && str_contains($value, "\n")) {
            return "";
        }
        // Collapse inline whitespace; preserve meaningful single spaces
        $normalized = preg_replace('/[ \t]*\n[ \t]*/', ' ', $value);
        $normalized = preg_replace('/[ \t]+/', ' ', $normalized);
        // Accumulate text inside methodparam initializers regardless of child element
        if (!empty($this->cchunk["_in_initializer"])) {
            $idx = count($this->cchunk["methodsynopsis"]["params"]) - 1;
            if ($idx >= 0) {
                $this->cchunk["methodsynopsis"]["params"][$idx]["initializer"] .= trim($normalized);
            }
            return "";
        }
        return $normalized;
    }

    public function UNDEF($open, $name, $attrs, $props) {
        if ($open) {
            trigger_error("No mapper found for '{$name}'", E_USER_WARNING);
        }
    }

    public function CDATA($value) {
        // CDATA is used for preformatted code — preserve as-is
        return $value;
    }

    private function desc(string $id, bool $long = false): string {
        $raw = $long ? ($this->getLongDescription($id) ?: $this->getShortDescription($id))
                     : ($this->getShortDescription($id) ?: $this->getLongDescription($id));
        return html_entity_decode($raw ?: $id, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function linkFor(string $id): string {
        $filename = $this->getFilename($id) ?: $id;
        return $filename . $this->getExt();
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {
        $desc = $this->desc($for);
        return $this->linkFor($for);
    }

    public function autogenVersionInfo($refnames): string {
        return '';
    }

    public function format_suppress_text($value, $tag): string {
        return "";
    }

    public function format_whitespace($whitespace, $elementStack, $currentDepth) {
        // Preserve a single space between inline elements (e.g. <literal>x</literal> <parameter>y</parameter>).
        // Discard multi-character whitespace (structural XML indentation / newlines between block elements).
        if ($whitespace === " ") {
            $this->appendData(" ");
        }
        return false;
    }

    public function toValidName($name): string {
        return str_replace(["::", "->", "()", " ", '$', '/', '\\'], [".", ".", "", "-", "", "", ""], $name);
    }

    public function getDefaultElementMap(): array {
        return $this->elementmap;
    }

    public function getDefaultTextMap(): array {
        return $this->textmap;
    }

    // -------------------------------------------------------------------------
    // Chunk handlers
    // -------------------------------------------------------------------------

    private function isChunkedByAttributes(array $attrs, string $name): bool {
        if (isset($attrs[Reader::XMLNS_PHD]['chunk'])) {
            return $attrs[Reader::XMLNS_PHD]['chunk'] !== 'false';
        }
        if (isset($attrs[Reader::XMLNS_DOCBOOK]['annotations'])) {
            return !str_contains($attrs[Reader::XMLNS_DOCBOOK]['annotations'], 'chunk:false');
        }
        return $name !== 'preface';
    }

    /** refentry, phpdoc:varentry — simple chunk, filename from xml:id */
    public function format_simple_chunk($open, $name, $attrs, $props) {
        $id = $attrs[Reader::XMLNS_XML]["id"] ?? "";
        if ($id === "") {
            return "";
        }
        if ($open) {
            $this->notify(Render::CHUNK, self::OPEN_CHUNK);
            $this->cchunk["chunkid"] = $id;  // set after OPEN_CHUNK resets cchunk
        } else {
            $this->notify(Render::CHUNK, self::CLOSE_CHUNK);
        }
        return false;
    }

    /** refentry — simple chunk, but with refname-based filename fallback */
    public function format_refentry_chunk($open, $name, $attrs, $props) {
        return $this->format_simple_chunk($open, $name, $attrs, $props);
    }

    /** book, chapter, part, reference, appendix, article, phpdoc:classref, section, sect1 — chunked container */
    public function format_container_chunk($open, $name, $attrs, $props) {
        $id = $attrs[Reader::XMLNS_XML]["id"] ?? "";
        // Use index-based chunk flag when available (authoritative for section/sect1)
        if ($id !== "" && !$this->isChunkID($id)) {
            return "";
        }
        if ($id === "" && !$this->isChunkedByAttributes($attrs, $name)) {
            return "";
        }
        if ($id === "") {
            return "";
        }
        if ($open) {
            $this->notify(Render::CHUNK, self::OPEN_CHUNK);
            $this->cchunk["chunkid"] = $id;  // set after OPEN_CHUNK resets cchunk
        } else {
            $this->cchunk["chunkid"] = $id;
            // Emit two-level TOC matching XHTML output
            $children = $this->getChildren($id);
            $toc = "";
            if (count($children)) {
                $toc = "\n";
                foreach ($children as $childId) {
                    $desc  = $this->desc($childId);
                    $ldesc = $this->desc($childId, true);
                    $line  = "- [" . $desc . "](" . $this->linkFor($childId) . ")";
                    if ($ldesc && $ldesc !== $desc) {
                        $line .= " — " . $ldesc;
                    }
                    $toc .= $line . "\n";
                    foreach ($this->getChildren($childId) as $grandId) {
                        $gdesc  = $this->desc($grandId);
                        $gldesc = $this->desc($grandId, true);
                        $gline  = "  - [" . $gdesc . "](" . $this->linkFor($grandId) . ")";
                        if ($gldesc && $gldesc !== $gdesc) {
                            $gline .= " — " . $gldesc;
                        }
                        $toc .= $gline . "\n";
                    }
                }
            }
            if ($toc !== "") {
                $streams = $this->getFileStream();
                $stream  = end($streams);
                if (is_resource($stream)) {
                    fwrite($stream, $toc);
                }
            }
            $this->notify(Render::CHUNK, self::CLOSE_CHUNK);
        }
        return false;
    }

    /** set — root chunk, emits top-level TOC */
    public function format_root_chunk($open, $name, $attrs, $props) {
        return $this->format_container_chunk($open, $name, $attrs, $props);
    }

    public function format_suppressed_tags($open, $name, $attrs, $props) {
        return "";
    }

    // -------------------------------------------------------------------------
    // refentry / name / purpose
    // -------------------------------------------------------------------------

    public function format_refname($open, $name, $attrs, $props) {
        if ($open) {
            return ($this->cchunk["firstrefname"]) ? false : "";
        }
        if ($this->cchunk["firstrefname"]) {
            $this->cchunk["firstrefname"] = false;
            return false;
        }
        return "";
    }

    public function format_refname_text($value, $tag) {
        $this->cchunk["funcname"][] = $this->toValidName(trim($value));
        if ($this->cchunk["firstrefname"]) {
            return "# " . trim($value) . "\n\n";
        }
        return "";
    }

    public function format_refpurpose($open, $name, $attrs, $props) {
        if ($open) {
            return "";
        }
        return "\n\n";
    }

    public function format_refsynopsisdiv($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["methodsynopsis"]["firstsynopsis"])
            && $this->cchunk["methodsynopsis"]["firstsynopsis"]) {
            return "\n## Synopsis\n\n";
        }
        if (!$open) {
            $this->cchunk["methodsynopsis"]["firstsynopsis"] = false;
        }
        return "";
    }

    // -------------------------------------------------------------------------
    // refsect1 sections
    // -------------------------------------------------------------------------

    public function format_refsect1($open, $name, $attrs, $props) {
        if ($open && isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
            $this->cchunk["role"] = $attrs[Reader::XMLNS_DOCBOOK]["role"];
        }
        if (!$open) {
            $this->cchunk["role"] = null;
        }
        return "";
    }

    public function format_chunk_title($open, $name, $attrs, $props) {
        if ($open) {
            return "\n# ";
        }
        return "\n\n";
    }

    public function format_refsect1_title($open, $name, $attrs, $props) {
        if ($open) {
            return "\n## ";
        }
        return "\n\n";
    }

    public function format_refsect2_title($open, $name, $attrs, $props) {
        if ($open) {
            return "\n### ";
        }
        return "\n\n";
    }

    public function format_refsect3_title($open, $name, $attrs, $props) {
        if ($open) {
            return "\n#### ";
        }
        return "\n\n";
    }

    public function format_section_title($open, $name, $attrs, $props) {
        if ($open) {
            return "\n## ";
        }
        return "\n\n";
    }

    public function format_title($open, $name, $attrs, $props) {
        if ($open) {
            return "\n### ";
        }
        return "\n\n";
    }

    // -------------------------------------------------------------------------
    // methodsynopsis
    // -------------------------------------------------------------------------

    public function format_methodsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            // If inside classsynopsis, flush the class declaration before the first method block
            if (!empty($this->cchunk["classsyn_suppress"])) {
                $this->flushClassDeclaration();
            }
            $this->cchunk["methodsynopsis"]["returntype"] = "";
            $this->cchunk["methodsynopsis"]["methodname"] = "";
            $this->cchunk["methodsynopsis"]["params"]     = [];
            $this->cchunk["methodsynopsis"]["suppress"]   = true;
            return "";
        }
        $this->cchunk["methodsynopsis"]["suppress"] = false;

        // Build signature string
        $ms     = $this->cchunk["methodsynopsis"];
        $params = [];
        foreach ($ms["params"] as $p) {
            // <void/> means no parameters; skip it
            if ($p["type"] === "void" && $p["name"] === "") {
                continue;
            }
            $param = "";
            if ($p["optional"]) {
                $param .= "[";
            }
            if ($p["type"]) {
                $param .= $p["type"] . " ";
            }
            if ($p["reference"]) {
                $param .= "&";
            }
            if (!empty($p["variadic"])) {
                $param .= "...";
            }
            if ($p["name"]) {
                $param .= "$" . $p["name"];
            }
            if ($p["initializer"]) {
                $param .= " = " . $p["initializer"];
            }
            if ($p["optional"]) {
                $param .= "]";
            }
            $params[] = rtrim($param);
        }

        $retSuffix = $ms["returntype"] ? ": " . $ms["returntype"] : "";
        $sig = "function " . $ms["methodname"] . "(" . implode(", ", $params) . ")" . $retSuffix;
        $this->cchunk["methodsynopsis"]["firstsynopsis"] = false;

        $block = "```php\n" . $sig . "\n```\n\n";
        // Write directly so classsyn_suppress in appendData doesn't block this
        $streams = $this->getFileStream();
        $stream  = end($streams);
        if (is_resource($stream) && $this->chunkOpen) {
            fwrite($stream, $block);
        }
        return "";
    }

    public function format_methodsynopsis_name($open, $name, $attrs, $props) {
        return "";
    }

    public function format_methodsynopsis_returntype($open, $name, $attrs, $props) {
        return "";
    }

    public function format_methodsynopsis_returntype_text($value, $tag) {
        $existing = $this->cchunk["methodsynopsis"]["returntype"];
        $this->cchunk["methodsynopsis"]["returntype"] = $existing !== "" ? $existing . "|" . trim($value) : trim($value);
        return "";
    }

    public function format_methodsynopsis_name_text($value, $tag) {
        $this->cchunk["methodsynopsis"]["methodname"] = trim($value);
        return "";
    }

    public function format_methodparam($open, $name, $attrs, $props) {
        if ($open) {
            $opt      = isset($attrs[Reader::XMLNS_DOCBOOK]["choice"]) &&
                $attrs[Reader::XMLNS_DOCBOOK]["choice"] === "opt";
            $variadic = isset($attrs[Reader::XMLNS_DOCBOOK]["rep"]) &&
                $attrs[Reader::XMLNS_DOCBOOK]["rep"] === "repeat";
            $this->cchunk["methodsynopsis"]["params"][] = [
                "optional"    => $opt,
                "variadic"    => $variadic,
                "type"        => "",
                "name"        => "",
                "initializer" => "",
                "reference"   => false,
            ];
        }
        return "";
    }

    public function format_methodparam_parameter($open, $name, $attrs, $props) {
        if ($open) {
            $role = $attrs[Reader::XMLNS_DOCBOOK]["role"] ?? "";
            if ($role === "reference") {
                $idx = count($this->cchunk["methodsynopsis"]["params"]) - 1;
                if ($idx >= 0) {
                    $this->cchunk["methodsynopsis"]["params"][$idx]["reference"] = true;
                }
            }
        }
        return "";
    }

    public function format_methodparam_type($open, $name, $attrs, $props) {
        return "";
    }

    public function format_methodparam_type_text($value, $tag) {
        $idx = count($this->cchunk["methodsynopsis"]["params"]) - 1;
        if ($idx >= 0) {
            $existing = $this->cchunk["methodsynopsis"]["params"][$idx]["type"];
            $this->cchunk["methodsynopsis"]["params"][$idx]["type"] =
                $existing !== "" ? $existing . "|" . trim($value) : trim($value);
        }
        return "";
    }

    public function format_methodparam_name_text($value, $tag) {
        $idx = count($this->cchunk["methodsynopsis"]["params"]) - 1;
        if ($idx >= 0) {
            $ref = isset($this->cchunk["methodsynopsis"]["params"][$idx]["reference"])
                && $this->cchunk["methodsynopsis"]["params"][$idx]["reference"];
            if (!$ref && str_starts_with(trim($value), "&")) {
                $this->cchunk["methodsynopsis"]["params"][$idx]["reference"] = true;
                $value = ltrim($value, "&");
            }
            $this->cchunk["methodsynopsis"]["params"][$idx]["name"] = ltrim(trim($value), '$');
        }
        return "";
    }

    public function format_methodparam_initializer($open, $name, $attrs, $props) {
        $this->cchunk["_in_initializer"] = $open;
        return "";
    }

    public function format_methodparam_initializer_text($value, $tag) {
        // Direct text in initializer (no child element) — also accumulated by TEXT() for nested elements
        $idx = count($this->cchunk["methodsynopsis"]["params"]) - 1;
        if ($idx >= 0) {
            $this->cchunk["methodsynopsis"]["params"][$idx]["initializer"] .= trim($value);
        }
        return "";
    }

    private function flushClassDeclaration(): void {
        if ($this->cchunk["_classdecl_written"]) {
            return;
        }
        $cs = $this->cchunk["classsyn"];
        if ($cs["name"] === "") {
            return;
        }
        $this->cchunk["_classdecl_written"] = true;
        $decl = ($cs["modifier"] !== "" ? $cs["modifier"] . " " : "") . "class " . $cs["name"];
        if ($cs["extends"] !== "") {
            $decl .= " extends " . $cs["extends"];
        }
        if (!empty($cs["implements"])) {
            $decl .= " implements " . implode(", ", $cs["implements"]);
        }
        $pkg    = $this->cchunk["_pkg_name"] ?? "";
        $prefix = $pkg !== "" ? "namespace " . $pkg . ";\n" : "";
        $streams = $this->getFileStream();
        $stream  = end($streams);
        if (is_resource($stream) && $this->chunkOpen) {
            fwrite($stream, "\n" . $prefix . "**" . $decl . "**\n\n");
        }
    }

    public function format_classsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["classsyn"]           = ["modifier" => "", "name" => "", "extends" => "", "implements" => []];
            $this->cchunk["classsyn_suppress"]  = true;
            $this->cchunk["_classdecl_written"] = false;
            $this->cchunk["_pkg_has_class"]     = true;
            return "";
        }
        $this->cchunk["classsyn_suppress"] = false;
        $this->flushClassDeclaration();
        return "";
    }

    public function format_ooclass($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["_cur_ooclass"] = ["modifier" => "", "name" => ""];
        } else {
            $modifier  = trim($this->cchunk["_cur_ooclass"]["modifier"]);
            $classname = trim($this->cchunk["_cur_ooclass"]["name"]);
            if ($modifier === "extends" && $classname !== "") {
                $this->cchunk["classsyn"]["extends"] = $classname;
            } elseif ($classname !== "") {
                $this->cchunk["classsyn"]["name"] = $classname;
                $mod = trim(preg_replace('/\bclass\b/', '', $modifier));
                $this->cchunk["classsyn"]["modifier"] = $mod;
            }
        }
        return "";
    }

    public function format_oointerface($open, $name, $attrs, $props) {
        return "";
    }

    public function format_ooclass_modifier_text($value, $tag) {
        $this->cchunk["_cur_ooclass"]["modifier"] .= $value;
        return "";
    }

    public function format_ooclass_classname_text($value, $tag) {
        $this->cchunk["_cur_ooclass"]["name"] .= $value;
        return "";
    }

    public function format_oointerface_name_text($value, $tag) {
        if (trim($value) !== "") {
            $this->cchunk["classsyn"]["implements"][] = trim($value);
        }
        return "";
    }

    public function format_void($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["methodsynopsis"]["params"][] = [
                "optional"    => false,
                "type"        => "void",
                "name"        => "",
                "initializer" => "",
                "reference"   => false,
                "variadic"    => false,
            ];
        }
        return "";
    }

    // -------------------------------------------------------------------------
    // Block elements
    // -------------------------------------------------------------------------

    public function format_para($open, $name, $attrs, $props) {
        if ($open) {
            return "";
        }
        return "\n\n";
    }

    public function format_para_listitem($open, $name, $attrs, $props) {
        return "";
    }

    public function format_programlisting($open, $name, $attrs, $props) {
        if ($open) {
            $role = $attrs[Reader::XMLNS_DOCBOOK]["role"] ?? "php";
            $this->cchunk["programlistingrole"] = $role;
            $this->cchunk["inprogramlisting"]   = true;
            return "\n```" . $role . "\n";
        }
        $this->cchunk["inprogramlisting"] = false;
        return "\n```\n\n";
    }

    public function format_verbatim($open, $name, $attrs, $props) {
        if ($open) {
            return "\n```\n";
        }
        return "\n```\n\n";
    }

    public function format_verbatim_text($value, $tag) {
        return $value;
    }

    public function format_example($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["examplenumber"])) {
            $this->cchunk["examplenumber"]++;
        }
        return "";
    }

    public function format_example_title($open, $name, $attrs, $props) {
        if ($open) {
            $n = $this->cchunk["examplenumber"] ?? 0;
            return "\n**Example " . $n . ": ";
        }
        return "**\n\n";
    }

    private function flushBlockquoteBuffer(string $label = ""): void {
        $content = trim($this->cchunk["_bq_buf"]);
        $this->cchunk["_bq_buf"] = "";
        $lines  = explode("\n", $content);
        $result = "\n";
        if ($label !== "") {
            $result .= "> **" . $label . ":**\n>\n";
        }
        foreach ($lines as $line) {
            $result .= $line === "" ? ">\n" : "> " . $line . "\n";
        }
        $result .= "\n";
        $streams = $this->getFileStream();
        $stream  = end($streams);
        if (is_resource($stream)) {
            fwrite($stream, $result);
        }
    }

    public function format_blockquote($open, $name, $attrs, $props) {
        if ($open) {
            if ($this->cchunk["_bq_depth"] === 0) {
                $this->cchunk["_bq_buf"] = "";
            }
            $this->cchunk["_bq_depth"]++;
            return "";
        }
        $this->cchunk["_bq_depth"]--;
        if ($this->cchunk["_bq_depth"] === 0) {
            $this->flushBlockquoteBuffer();
        }
        return "";
    }

    // -------------------------------------------------------------------------
    // Admonitions
    // -------------------------------------------------------------------------

    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            if ($this->cchunk["_bq_depth"] === 0) {
                $this->cchunk["_bq_buf"]   = "";
                $this->cchunk["_bq_label"] = strtoupper($name);
            }
            $this->cchunk["_bq_depth"]++;
            return "";
        }
        $this->cchunk["_bq_depth"]--;
        if ($this->cchunk["_bq_depth"] === 0) {
            $label = $this->cchunk["_bq_label"];
            $this->cchunk["_bq_label"] = "";
            $this->flushBlockquoteBuffer($label);
        }
        return "";
    }

    public function format_admonition_title($open, $name, $attrs, $props) {
        return "";
    }

    public function format_table_title($open, $name, $attrs, $props) {
        if ($open) {
            return "\n**";
        }
        return "**\n\n";
    }

    // -------------------------------------------------------------------------
    // Lists
    // -------------------------------------------------------------------------

    public function format_itemizedlist($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["listdepth"]++;
            $this->cchunk["listtype"][] = "ul";
        } else {
            $this->cchunk["listdepth"]--;
            array_pop($this->cchunk["listtype"]);
        }
        return "\n";
    }

    public function format_orderedlist($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["listdepth"]++;
            $this->cchunk["listtype"][]  = "ol";
            $this->cchunk["listcount"][] = 0;
        } else {
            $this->cchunk["listdepth"]--;
            array_pop($this->cchunk["listtype"]);
            array_pop($this->cchunk["listcount"]);
        }
        return "\n";
    }

    public function format_listitem($open, $name, $attrs, $props) {
        if (!$open) {
            return "\n";
        }
        $depth  = max(0, $this->cchunk["listdepth"] - 1);
        $indent = str_repeat("  ", $depth);
        $type   = !empty($this->cchunk["listtype"]) ? end($this->cchunk["listtype"]) : "ul";
        if ($type === "ol") {
            $idx = count($this->cchunk["listcount"]) - 1;
            $this->cchunk["listcount"][$idx]++;
            $n = $this->cchunk["listcount"][$idx];
            return $indent . $n . ". ";
        }
        return $indent . "- ";
    }

    public function format_term($open, $name, $attrs, $props) {
        if ($open) {
            return "\n**";
        }
        return "**\n";
    }

    public function format_member($open, $name, $attrs, $props) {
        if ($open) {
            return "- ";
        }
        return "\n";
    }

    // -------------------------------------------------------------------------
    // Tables (GFM pipe tables)
    // -------------------------------------------------------------------------

    public function format_table($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["tableheader"] = false;
            $this->cchunk["tablecols"]   = 0;
        }
        return "\n";
    }

    public function format_tgroup($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["tablecols"] = (int)($attrs[Reader::XMLNS_DOCBOOK]["cols"] ?? 0);
        }
        return "";
    }

    public function format_thead($open, $name, $attrs, $props) {
        $this->cchunk["tableheader"] = $open;
        return "";
    }

    public function format_row($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["tablecellcount"] = 0;
            return "| ";
        }
        // Close row — emit trailing newline; if header row, emit separator
        if ($this->cchunk["tableheader"]) {
            $cols = $this->cchunk["tablecellcount"] ?: ($this->cchunk["tablecols"] ?: 2);
            return "\n| " . implode(" | ", array_fill(0, $cols, "---")) . " |\n";
        }
        return "\n";
    }

    public function format_entry($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["_in_entry"] = true;
            return "";
        }
        $this->cchunk["_in_entry"] = false;
        $this->cchunk["tablecellcount"]++;
        return " | ";
    }

    // -------------------------------------------------------------------------
    // Inline elements
    // -------------------------------------------------------------------------

    public function format_function_link($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["_pending_func"] = "";
            return "";
        }
        $fn = trim($this->cchunk["_pending_func"] ?? "");
        $this->cchunk["_pending_func"] = null;
        if ($fn === "") {
            return "";
        }
        $fn  = rtrim($fn, "()");
        $id  = "function." . str_replace(["_", "::"], ["-", "."], strtolower($fn));
        $url = $this->linkFor($id);
        return "[`" . $fn . "`](" . $url . ")";
    }

    public function format_function_link_text($value, $tag) {
        if (isset($this->cchunk["_pending_func"]) && $this->cchunk["_pending_func"] !== null) {
            $this->cchunk["_pending_func"] .= $value;
        }
        return "";
    }

    public function format_code_inline($open, $name, $attrs, $props) {
        return $open ? "`" : "`";
    }

    public function format_methodname_link($open, $name, $attrs, $props) {
        return "";
    }

    public function format_methodname_link_text($value, $tag) {
        $ref = ltrim(strtolower($value), '\\');
        $filename = $this->getRefnameLink($ref);
        if ($filename !== null) {
            return "[`" . $value . "`](" . $filename . $this->getExt() . ")";
        }
        return "`" . $value . "`";
    }

    public function format_classname_link($open, $name, $attrs, $props) {
        return "";
    }

    public function format_classname_link_text($value, $tag) {
        $ref = ltrim(strtolower($value), '\\');
        $filename = $this->getClassnameLink($ref);
        if ($filename !== null) {
            return "[`" . $value . "`](" . $filename . $this->getExt() . ")";
        }
        return "`" . $value . "`";
    }

    public function format_emphasis($open, $name, $attrs, $props) {
        $role = $attrs[Reader::XMLNS_DOCBOOK]["role"] ?? "";
        $marker = ($role === "strong" || $role === "bold") ? "**" : "*";
        return $marker;
    }

    public function format_type_inline($open, $name, $attrs, $props) {
        return $open ? "`" : "`";
    }

    public function format_parameter_inline($open, $name, $attrs, $props) {
        return $open ? "`\$" : "`";
    }

    public function format_parameter_inline_text($value, $tag) {
        return ltrim($value, '$');
    }

    public function format_optional($open, $name, $attrs, $props) {
        return $open ? "[" : "]";
    }

    public function format_quote($open, $name, $attrs, $props) {
        return $open ? "\"" : "\"";
    }

    public function format_link($open, $name, $attrs, $props) {
        $linkend = $attrs[Reader::XMLNS_DOCBOOK]["linkend"] ?? "";
        $url     = $attrs[Reader::XMLNS_XLINK]["href"] ?? "";
        if ($open) {
            return "[";
        }
        if ($url) {
            return "](" . $url . ")";
        }
        if ($linkend) {
            return "](" . $this->linkFor($linkend) . ")";
        }
        // No target — close the bracket opened on the open pass
        return "]";
    }

    public function format_xref($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            $linkend = $attrs[Reader::XMLNS_DOCBOOK]["linkend"] ?? "";
            $desc    = $this->desc($linkend);
            return "[" . $desc . "](" . $this->linkFor($linkend) . ")";
        }
        return "";
    }

    public function format_uri($open, $name, $attrs, $props) {
        return $open ? "<" : ">";
    }

    // -------------------------------------------------------------------------
    // Keyboard
    // -------------------------------------------------------------------------

    public function format_keycap($open, $name, $attrs, $props) {
        // Sibling keycap inside keycombo: prefix with "+"
        $prefix = ($props["sibling"] ?? false) ? "+" : "";
        return $open ? $prefix . "`" : "`";
    }

    public function format_keycombo($open, $name, $attrs, $props) {
        // keycombo wraps multiple keycap children; backticks on the caps do the work
        return "";
    }

    // -------------------------------------------------------------------------
    // Man page references
    // -------------------------------------------------------------------------

    public function format_citerefentry($open, $name, $attrs, $props) {
        return $open ? "`" : "`";
    }

    public function format_manvolnum($open, $name, $attrs, $props) {
        return $open ? "(" : ")";
    }

    // -------------------------------------------------------------------------
    // Caption
    // -------------------------------------------------------------------------

    public function format_caption($open, $name, $attrs, $props) {
        return $open ? "\n*" : "*\n\n";
    }

    // -------------------------------------------------------------------------
    // Package synopsis  (namespace + extension declarations)
    // -------------------------------------------------------------------------

    public function format_packagesynopsis($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["_pkg_name"]      = "";
            $this->cchunk["_pkg_has_class"] = false;
            return "";
        }
        // If classsynopsis was inside, the class declaration already includes the namespace
        if ($this->cchunk["_pkg_has_class"]) {
            return "";
        }
        // Standalone packagesynopsis (no classsynopsis) — emit namespace declaration
        $pkg = $this->cchunk["_pkg_name"];
        return $pkg !== "" ? "\n```php\nnamespace " . $pkg . ";\n```\n\n" : "";
    }

    public function format_pkg_name($open, $name, $attrs, $props) {
        return "";
    }

    public function format_pkg_name_text($value, $tag) {
        $this->cchunk["_pkg_name"] .= $value;
        return "";
    }

    // -------------------------------------------------------------------------
    // Enum synopsis  (enum Name { case X = v; })
    // -------------------------------------------------------------------------

    public function format_enumsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            return "\n```php\n";
        }
        return "}\n```\n\n";
    }

    public function format_enumsynopsis_enumname($open, $name, $attrs, $props) {
        if ($open) {
            return "enum ";
        }
        return " {\n";
    }

    public function format_enumitem($open, $name, $attrs, $props) {
        if ($open) {
            return "    case ";
        }
        return ";\n";
    }

    public function format_enumidentifier($open, $name, $attrs, $props) {
        // Identifier text is emitted by TEXT() directly; just pass through
        return "";
    }

    public function format_enumvalue($open, $name, $attrs, $props) {
        return $open ? " = " : "";
    }

    public function format_enumitemdescription($open, $name, $attrs, $props) {
        $this->cchunk["_in_enumdesc"] = $open;
        return "";
    }
}
