<?php
namespace phpdotnet\phd;

class Index extends Format
{
    private $myelementmap = array(
    'article'               => 'format_container_chunk',
    'appendix'              => 'format_container_chunk',
    'bibliography'          => array(
        /* DEFAULT */          false,
        'article'           => 'format_chunk',
        'book'              => 'format_chunk',
        'part'              => 'format_chunk',
    ),
    'book'                  => 'format_container_chunk',
    'chapter'               => 'format_container_chunk',
    'colophon'              => 'format_chunk',
    'glossary'              => array(
        /* DEFAULT */          false,
        'article'           => 'format_chunk',
        'book'              => 'format_chunk',
        'part'              => 'format_chunk',
    ),
    'index'                 => array(
        /* DEFAULT */          false,
        'article'           => 'format_chunk',
        'book'              => 'format_chunk',
        'part'              => 'format_chunk',
    ),
    'legalnotice'           => 'format_chunk',
    'part'                  => 'format_container_chunk',
    'phpdoc:exception'      => 'format_container_chunk',
    'phpdoc:exceptionref'   => 'format_container_chunk',
    'phpdoc:classref'       => 'format_container_chunk',
    'phpdoc:varentry'       => 'format_chunk',
    'preface'               => 'format_chunk',
    'refentry'              => 'format_refentry',
    'reference'             => 'format_reference',
    'sect1'                 => 'format_chunk',
    'section'               => array(
        /* DEFAULT */          false,
        'sect1'             => 'format_section_chunk',
        'chapter'           => 'format_section_chunk',
        'appendix'          => 'format_section_chunk',
        'article'           => 'format_section_chunk',
        'part'              => 'format_section_chunk',
        'reference'         => 'format_section_chunk',
        'refentry'          => 'format_section_chunk',
        'index'             => 'format_section_chunk',
        'bibliography'      => 'format_section_chunk',
        'glossary'          => 'format_section_chunk',
        'colopone'          => 'format_section_chunk',
        'book'              => 'format_section_chunk',
        'set'               => 'format_section_chunk',
        'setindex'          => 'format_section_chunk',
        'legalnotice'       => 'format_section_chunk',
    ),
    'set'                   => 'format_container_chunk',
    'setindex'              => 'format_chunk',
    'title'                 => 'format_long_desc',
    'refpurpose'            => 'format_long_desc',
    'refname'               => 'format_short_desc',
    'titleabbrev'           => 'format_short_desc',
    'example'               => 'format_example',
    'refsect1'              => 'format_refsect1',
    'row'                   => array(
        /* DEFAULT */          null,
        'tbody'             => 'format_row',
    ),
    'entry'                 => array(
        /* DEFAULT */          null,
        "row"               => array(
            /* DEFAULT */      null,
            "tbody"         => 'format_entry',
        ),
    ),
    );

    private $mytextmap = array(
    );
    private $pihandlers = array(
        'dbhtml'            => 'PI_DBHTMLHandler',
        'phpdoc'            => 'PI_PHPDOCHandler',
    );

    private bool $inLongDesc = false;
    private bool $inShortDesc = false;
    private string $currentLongDesc = "";
    private string $currentShortDesc = "";
    private $currentchunk;
    private $ids       = array();
    private $currentid;
    private $chunks    = array();
    private $isChunk   = array();
    protected $nfo       = array();
    private $isSectionChunk = array();
    private $previousId = "";
    private $inChangelog = false;
    private $currentChangelog = array();
    private string $currentChangeLogString = "";
    protected $changelog       = array();
    private $currentMembership = null;
    private $commit     = array();
    private $POST_REPLACEMENT_INDEXES = array();
    private $POST_REPLACEMENT_VALUES  = array();
    private int $exampleCounter = 0;

    public function __construct(
        IndexRepository $indexRepository,
        Config $config,
        OutputHandler $outputHandler
    ) {
        $this->indexRepository = $indexRepository;
        parent::__construct($config, $outputHandler);
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
    }
    public function TEXT($value) {
        if ($this->inLongDesc) {
            $this->currentLongDesc .= $value;
        }
        if ($this->inShortDesc) {
            $this->currentShortDesc .= $value;
        }
        if ($this->inChangelog) {
            $this->currentChangeLogString .= $value;
        }
    }
    public function CDATA($value) {
    }
    public function createLink($for, &$desc = null, $type = Format::SDESC) {
    }
    public function appendData($data) {
        if ($this->inLongDesc && is_string($data) && trim($data) === "") {
            $this->currentLongDesc .= $data;
        }
        if ($this->inShortDesc && is_string($data) && trim($data) === "") {
            $this->currentShortDesc .= $data;
        }
        if ($this->inChangelog && is_string($data) && trim($data) === "") {
            $this->currentChangeLogString .= $data;
        }
    }

    public function update($event, $value = null)
    {
        switch($event) {
            case Render::CHUNK:
                $this->flags = $value;
                break;

            case Render::STANDALONE:
                if ($value) {
                    $this->registerElementMap(static::getDefaultElementMap());
                    $this->registerTextMap(static::getDefaultTextMap());
                    $this->registerPIHandlers($this->pihandlers);
                }
                break;
            case Render::INIT:
                if ($value) {
                    $this->indexRepository->init();
                    $this->chunks = array();
                } else {
                    print_r($this->chunks);
                }
                break;
            case Render::FINALIZE:
                $this->indexRepository->saveIndexingTime(time());
                if ($this->indexRepository->commit(
                    $this->commit,
                    $this->POST_REPLACEMENT_INDEXES,
                    $this->POST_REPLACEMENT_VALUES,
                    $this->changelog,
                )) {
                    $this->commit = [];
                }
                if ($this->indexRepository->lastErrorCode()) {
                    trigger_error($this->indexRepository->lastErrorMsg(), E_USER_WARNING);
                }
                break;
        }
    }
    public function getDefaultElementMap() {
        return $this->myelementmap;
    }
    public function getDefaultTextMap() {
        return $this->mytextmap;
    }
    public function UNDEF($open, $name, $attrs, $props) {
        if ($open) {
            if(isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
                $this->storeInfo($name, $id, $this->currentchunk, false);
                if ($props["empty"]) {
                    $this->appendID();
                }
            }
            return false;
        }

        if(isset($attrs[Reader::XMLNS_XML]["id"])) {
            $this->appendID();
        }
        return false;
    }
    protected function storeInfo($elm, $id, $filename, $isChunk = true) {
        $this->ids[] = $id;
        $this->currentid = $id;
        $this->nfo[$id] = array(
                    "parent"   => "",
                    "filename" => $filename,
                    "sdesc"    => "",
                    "ldesc"    => "",
                    "element"  => $elm,
                    "children" => array(),
                    "previous" => $isChunk ? $this->previousId : "",
                    "chunk"    => $isChunk,
        );
        // Append "next" to the previously inserted row
        if ($isChunk) {
            $this->POST_REPLACEMENT_VALUES[$this->previousId] = $id;
            $this->previousId = $id;
        }
    }
    public function appendID() {
        static $idx = -1;
        $lastChunkId = array_pop($this->ids);
        $parentid = end($this->ids);
        $this->currentid = $parentid;

        $lastChunk = $this->nfo[$lastChunkId];
        if (is_array($lastChunk["sdesc"])) {
            $array = true;
            $sdesc = array_shift($lastChunk["sdesc"]);
        } else {
            $array = false;
            $sdesc = $lastChunk["sdesc"];
        }

        $this->commit[++$idx] = [
            "docbook_id" => $lastChunkId,
            "filename" => $lastChunk["filename"],
            "parent_id" => $this->currentchunk,
            "sdesc" => $sdesc,
            "ldesc" => $lastChunk["ldesc"],
            "element" => $lastChunk["element"],
            "previous" => $lastChunk["previous"],
            "next" => ($lastChunk["chunk"] ? "POST-REPLACEMENT" : ""),
            "chunk" => $lastChunk["chunk"],
        ];
        if ($lastChunk["chunk"]) {
            $this->POST_REPLACEMENT_INDEXES[] = array("docbook_id" => $lastChunkId, "idx" => $idx);
        }
        if ($array === true) {
            foreach($lastChunk["sdesc"] as $sdesc) {
                $this->commit[++$idx] = [
                    "docbook_id" => $lastChunkId,
                    "filename" => $lastChunk["filename"],
                    "parent_id" => $this->currentchunk,
                    "sdesc" => $sdesc,
                    "ldesc" => $lastChunk["ldesc"],
                    "element" => $lastChunk["element"],
                    "previous" => $lastChunk["previous"],
                    "next" => ($lastChunk["chunk"] ? "POST-REPLACEMENT" : ""),
                    "chunk" => 0,
                ];
                $this->POST_REPLACEMENT_INDEXES[] = array("docbook_id" => $lastChunkId, "idx" => $idx);
            }
        }

    }
    public function format_section_chunk($open, $name, $attrs, $props) {
        if ($open) {
            if (!isset($attrs[Reader::XMLNS_XML]["id"])) {
                $this->isSectionChunk[] = false;
                return $this->UNDEF($open, $name, $attrs, $props);
            }
            $this->isSectionChunk[] = true;
            return $this->format_chunk($open, $name, $attrs, $props);
        }
        if (array_pop($this->isSectionChunk)) {
            return $this->format_chunk($open, $name, $attrs, $props);
        }
        return $this->UNDEF($open, $name, $attrs, $props);
    }
    public function format_container_chunk($open, $name, $attrs, $props) {
        if ($open) {
            if ($name == "book") {
                $this->currentMembership = null;
            }
            return $this->format_chunk($open, $name, $attrs, $props);
        }
        return $this->format_chunk($open, $name, $attrs, $props);
    }

    public function format_refentry($open, $name, $attrs, $props) {
        /* Note role attribute also has usage with "noversion" to not check version availability */
        /* We overwrite the tag name to continue working with the usual indexing */
        if (isset($attrs[Reader::XMLNS_DOCBOOK]['role'])) {
            return match ($attrs[Reader::XMLNS_DOCBOOK]['role']) {
                'class', 'enum' => $this->format_container_chunk($open, 'phpdoc:classref', $attrs, $props),
                'exception' => $this->format_container_chunk($open, 'phpdoc:exceptionref', $attrs, $props),
                'variable' => $this->format_chunk($open, 'phpdoc:varentry', $attrs, $props),
                'stream_wrapper' => $this->format_chunk($open, 'stream_wrapper', $attrs, $props),
                'stream_context_option' => $this->format_chunk($open, 'stream_context_option', $attrs, $props),
                default => $this->format_chunk($open, $name, $attrs, $props),
            };
        }
        return $this->format_chunk($open, $name, $attrs, $props);
    }

    public function format_chunk($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return false;
        }

        $this->processFilename();
        if ($open) {
            if(isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
            } else {
                $this->isChunk[] = false;
                return false;
            }

            /* Legacy way to mark chunks */
            if (isset($attrs[Reader::XMLNS_PHD]['chunk'])) {
                $this->isChunk[] = $attrs[Reader::XMLNS_PHD]['chunk'] == "true";
            } elseif (isset($attrs[Reader::XMLNS_DOCBOOK]['annotations'])) {
                $this->isChunk[] = !str_contains($attrs[Reader::XMLNS_DOCBOOK]['annotations'], 'chunk:false');
            } else {
                $this->isChunk[] = true;
            }

            if (end($this->isChunk)) {
                $this->chunks[] = $id;
                $this->currentchunk = $id;
                $this->storeInfo($name, $id, $id);
            } else {
                $this->storeInfo($name, $id, $this->currentchunk, false);
                $this->appendID();
            }
            return false;
        }
        if (array_pop($this->isChunk)) {
            array_pop($this->chunks);
            $this->currentchunk = end($this->chunks);
            $this->appendID();
        }
        return false;
    }

    public function format_long_desc($open, $name, $attrs, $props) {
        if ($open) {
            $this->inLongDesc = true;
            $this->currentLongDesc = "";
            return;
        }
        $this->inLongDesc = false;
        if ($this->nfo[$this->currentid]["ldesc"] === "") {
            $this->nfo[$this->currentid]["ldesc"] = htmlentities(trim($this->currentLongDesc), ENT_COMPAT, "UTF-8");
        }
    }
    public function format_short_desc($open, $name, $attrs, $props) {
        if ($open) {
            $this->inShortDesc = true;
            $this->currentShortDesc = "";
            return;
        }
        $this->inShortDesc = false;
        $s = htmlentities(trim($this->currentShortDesc), ENT_COMPAT, "UTF-8");
        if ($this->nfo[$this->currentid]["sdesc"] === "") {
            $this->nfo[$this->currentid]["sdesc"] = $s;
        } else {
            if (!is_array($this->nfo[$this->currentid]["sdesc"])) {
                $this->nfo[$this->currentid]["sdesc"] = (array)$this->nfo[$this->currentid]["sdesc"];
            }
            //In the beginning of the array to stay compatible with 0.4
            array_unshift($this->nfo[$this->currentid]["sdesc"], $s);
        }
    }

    public function format_example($open, $name, $attrs, $props) {
        if ($open) {
            ++$this->exampleCounter;

            if(isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
            }
            else {
                $id = "example-" . $this->exampleCounter;
            }

            $this->storeInfo($name, $id, $this->currentchunk, false);
            return false;
        }

        $this->appendID();
        return false;
    }
    public function format_refsect1($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['role'])) {
                if ($attrs[Reader::XMLNS_DOCBOOK]['role'] == "changelog") {
                    $this->inChangelog = true;
                }
            }
            return;
        }
        $this->inChangelog = false;
    }

    public function format_entry($open, $name, $attrs, $props) {
        if ($open) {
            $this->currentChangeLogString = "";
            return;
        }
        if ($this->inChangelog) {
            $this->currentChangelog[] = htmlentities(trim($this->currentChangeLogString), ENT_COMPAT, "UTF-8");
        }
    }

    public function format_row($open, $name, $attrs, $props) {
        if ($this->inChangelog) {
            if ($open) {
                $parent_id = $this->ids[count($this->ids) - 2];
                $this->currentChangelog = array($this->currentMembership, $parent_id);
            } else {
                $this->changelog[$this->currentid][] = $this->currentChangelog;
            }
        }

        /* Fall back to regular handling so contents get processed */
        return $this->UNDEF($open, $name, $attrs, $props);
    }

    public function processFilename() {
        static $dbhtml = null;
        if ($dbhtml == null) {
            $dbhtml = $this->getPIHandler("dbhtml");
        }
        $filename = $dbhtml->getAttribute("filename");
        if ($filename) {
            $this->nfo[end($this->chunks)]["filename"] = $filename;
            $dbhtml->setAttribute("filename", false);
        }
    }

    public function setMembership($membership) {
        $this->currentMembership = $membership;
    }

    public function format_reference($open, $name, $attrs, $props) {
        if (isset($attrs[Reader::XMLNS_DOCBOOK]['role'])) {
            $name = match ($attrs[Reader::XMLNS_DOCBOOK]['role']) {
                "class", "enum" => "phpdoc:classref",
                "exception" => "phpdoc:exceptionref",
                default => $name,
            };
        }
        return $this->format_container_chunk($open, $name, $attrs, $props);
    }
}
