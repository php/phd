<?php
namespace phpdotnet\phd;

abstract class Format extends ObjectStorage
{
    /**
     * Represents a short description.
     * Used in createLink()
     *
     * @var    integer
     * @usedby createLink()
     */
    const int SDESC = 1;

    /**
     * Represents a long description.
     * Used in createLink()
     *
     * @var    integer
     * @usedby createLink()
     */
    const int LDESC = 2;

    protected Config $config;
    protected OutputHandler $outputHandler;

    private $elementmap = array();
    private $textmap = array();
    private $formatname = "UNKNOWN";
    protected IndexRepository $indexRepository;

    protected $title;
    protected $fp = array();
    protected $ext;
    protected $outputdir;
    protected $chunked;
    protected $flags = 0;

    /* Processing Instructions Handlers */
    private $pihandlers = array();

    /* Indexing maps */
    protected $indexes = array();
    protected $children = array();
    protected $refs = array();
    protected $vars = array();
    protected $classes = array();
    protected $examples = array();

    private $autogen = array();

    private static $highlighters = array();

    /* See self::parse() */
    protected $appendToBuffer = false;
    protected $buffer = "";

    /* Table handling */
    protected $TABLE = array();

    /**
    * Name of the ID currently being processed
    *
    * @var string
    */
    protected $CURRENT_ID = "";

    public function __construct(Config $config, OutputHandler $outputHandler) {
        $this->config = $config;
        $this->outputHandler = $outputHandler;
        if ($this->config->indexCache) {
            $this->indexRepository = $this->config->indexCache;
            if (!($this instanceof Index)) {
                $this->sortIDs();
            }
        }
    }

    abstract public function transformFromMap($open, $tag, $name, $attrs, $props);
    abstract public function UNDEF($open, $name, $attrs, $props);
    abstract public function TEXT($value);
    abstract public function CDATA($value);

    /**
     * Create link to chunk.
     *
     * @param string  $for   Chunk ID
     * @param string  &$desc Description of link, to be filled if neccessary
     * @param integer $type  Format of description, Format::SDESC or
     *                       Format::LDESC
     *
     * @return string|null|void Relative or absolute URI to access $for
     */
    abstract public function createLink(
        $for, &$desc = null, $type = Format::SDESC
    );

    abstract public function appendData($data);

    /**
     * Called by Format::notify()
     *
     * Possible events:
     * - Render::STANDALONE
     *     Always called with true as value from Render::attach()
     *
     *
     * - Render::INIT
     *     Called from Render::execute() when rendering
     *     is being started. Value is always true
     *
     * - Render::FINALIZE (from Render::execute())
     *     Called from Render::execute() when there is
     *     nothing more to read in the XML file.
     *
     * - Render::VERBOSE
     *     Called if the user specified the --verbose option
     *     as commandline parameter. Called in render.php
     *
     * - Render::CHUNK
     *     Called when a new chunk is opened or closed.
     *     Value is either Render::OPEN or Render::CLOSE
     *
     * @param integer $event Event flag (see Render class)
     * @param mixed   $value Additional value flag. Depends
     *                       on $event type
     *
     * @return void
     */
    abstract public function update($event, $value = null);

    public final function parsePI($target, $data) {
        if (isset($this->pihandlers[$target])) {
            return $this->pihandlers[$target]->parse($target, $data);
        }
    }

    public final function registerPIHandlers($pihandlers) {
        foreach ($pihandlers as $target => $classname) {
            $class = __NAMESPACE__ . "\\" . $classname;
            $this->pihandlers[$target] = new $class($this);
        }
    }

    public function getPIHandler($target) {
        return $this->pihandlers[$target];
    }

    public function sortIDs() {
        $this->indexes = $this->indexRepository->getIndexes();
        $this->children = $this->indexRepository->getChildren();
        $this->refs = $this->indexRepository->getRefNames();
        $this->vars = $this->indexRepository->getVarNames();
        $this->classes = $this->indexRepository->getClassNames();
        $this->examples = $this->indexRepository->getExamples();
    }

    public function SQLiteIndex($context, $index, $id, $filename, $parent, $sdesc, $ldesc, $element, $previous, $next, $chunk) {
        $this->indexes[$id] = array(
            "docbook_id" => $id,
            "filename"   => $filename,
            "parent_id"  => $parent,
            "sdesc"      => $sdesc,
            "ldesc"      => $ldesc,
            "element"    => $element,
            "previous"   => $previous,
            "next"       => $next,
            "chunk"      => $chunk,
        );
    }

    /**
     * Calls update().
     *
     * @param integer $event Event flag. See Render class for constants
     *                       like Render::INIT and Render::CHUNK
     * @param mixed   $val   Value; depends on $event flag
     *
     * @return void
     */
    final public function notify($event, $val = null)
    {
        $this->update($event, $val);
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    /**
     * Set file extension used when chunking and writing
     * out files.
     *
     * @param string $ext File extension without dot
     *
     * @return void
     *
     * @see getExt()
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * Returns file extension without
     * leading dot.
     *
     * @return string File extension.
     *
     * @see setExt()
     */
    public function getExt() {
        return $this->ext;
    }

    public function setOutputDir($outputdir) {
        $this->outputdir = $outputdir;
    }

    public function getOutputDir() {
        return $this->outputdir;
    }

    public function setChunked($chunked) {
        $this->chunked = $chunked;
    }

    public function isChunked() {
        return $this->chunked;
    }

    public function setFileStream($stream) {
        $this->fp = $stream;
    }

    public function getFileStream() {
        return $this->fp;
    }

    public function pushFileStream($stream) {
        $this->fp[] = $stream;
    }

    public function popFileStream() {
        return array_pop($this->fp);
    }

    public function addRefname($id, $ref) {
        $this->refs[$ref] = $id;
    }
    public function addClassname($id, $class) {
        $this->classes[$class] = $id;
    }
    public function addVarname($id, $var) {
        $this->vars[$var] = $id;
    }
    public function getChangelogsForChildrenOf($bookids) {
        return $this->indexRepository->getChangelogsForChildrenOf($bookids);
    }
    public function getChangelogsForMembershipOf($memberships) {
        return $this->indexRepository->getChangelogsForMembershipOf($memberships);
    }
    public function getRefs() {
        return $this->refs;
    }
    public function getExamples() {
        return $this->examples;
    }
    public function getRefnameLink($ref) {
        return isset($this->refs[$ref]) ? $this->refs[$ref] : null;
    }
    public function getClassnameLink($class) {
        return isset($this->classes[$class]) ? $this->classes[$class] : null;
    }
    public function getVarnameLink($var) {
        return isset($this->vars[$var]) ? $this->vars[$var] : null;
    }
    public function getGeneratedExampleID($index) {
        if (array_key_exists($index, $this->examples)) {
            return $this->examples[$index];
        }
        return null;
    }
    final public function registerElementMap(array $map) {
        $this->elementmap = $map;
    }
    final public function registerTextMap(array $map) {
        $this->textmap = $map;
    }
    final public function attach(object $object, mixed $info = array()): void {
        if (!($object instanceof $this) && get_class($object) != get_class($this)) {
            throw new \InvalidArgumentException(get_class($this) . " themes *MUST* _inherit_ " .get_class($this). ", got " . get_class($object));
        }
        $object->notify(Render::STANDALONE, false);
        parent::attach($object, $info);
    }
    final public function getElementMap() {
        return $this->elementmap;
    }
    final public function getTextMap() {
        return $this->textmap;
    }
    final public function registerFormatName($name) {
        $this->formatname = $name;
    }
    public function getFormatName() {
        return $this->formatname;
    }

    /* Buffer where append data instead of the standard stream (see format's appendData()) */
    final public function parse($xml) {
        $reader = new Reader($this->outputHandler);
        $render = new Render();

        $reader->XML("<notatag>" . $xml . "</notatag>");

        $this->appendToBuffer = true;
        $render->attach($this);
        $render->execute($reader);
        $this->appendToBuffer = false;
        $parsed = $this->buffer;
        $this->buffer = "";

        return $parsed;
    }

    final public function autogen($text, $lang = null) {
        if ($lang == NULL) {
            $lang = $this->config->language;
        }
        if (isset($this->autogen[$lang])) {
            if (isset($this->autogen[$lang][$text])) {
                return $this->autogen[$lang][$text];
            }
            if ($lang == $this->config->fallbackLanguage) {
                throw new \InvalidArgumentException("Cannot autogenerate text for '$text'");
            }
            return $this->autogen($text, $this->config->fallbackLanguage);
        }

        $filename = $this->config->langDir . $lang . ".ini";

        if (!file_exists($filename) && strncmp(basename($filename), 'doc-', 4) === 0) {
            $filename = dirname($filename) . DIRECTORY_SEPARATOR . substr(basename($filename), 4);
        }

        $this->autogen[$lang] = parse_ini_file($filename);
        return $this->autogen($text, $lang);
    }

/* {{{ TOC helper functions */

    /**
     * Returns the filename for the given id, without the file extension
     *
     * @param string $id XML Id
     *
     * @return mixed Stringular filename or false if no filename
     *               can be detected.
     */
    final public function getFilename($id)
    {
        return isset($this->indexes[$id]['filename'])
            ? $this->indexes[$id]['filename']
            : false;
    }

    final public function getPrevious($id) {
        return $this->indexes[$id]["previous"];
    }
    final public function getNext($id) {
        return $this->indexes[$id]["next"];
    }
    final public function getParent($id) {
        return $this->indexes[$id]["parent_id"];
    }
    final public function getLongDescription($id, &$isLDesc = null) {
        if ($this->indexes[$id]["ldesc"]) {
            $isLDesc = true;
            return $this->indexes[$id]["ldesc"];
        } else {
            $isLDesc = false;
            return $this->indexes[$id]["sdesc"];
        }
    }
    final public function getShortDescription($id, &$isSDesc = null) {
        if ($this->indexes[$id]["sdesc"]) {
            $isSDesc = true;
            return $this->indexes[$id]["sdesc"];
        } else {
            $isSDesc = false;
            return $this->indexes[$id]["ldesc"];
        }
    }

    /**
     * Returns an array of children IDs of given ID.
     *
     * @param string $id XML ID to retrieve children for.
     *
     * @return array Array of XML IDs
     */
    final public function getChildren($id)
    {
        if (!isset($this->children[$id])
            || !is_array($this->children[$id])
            || count($this->children[$id]) == 0
        ) {
            return [];
        }
        return $this->children[$id];
    }

    /**
     * Tells you if the given ID is to be chunked or not.
     *
     * @param string $id XML ID to get chunk status for
     *
     * @return boolean True if it is to be chunked
     */
    final public function isChunkID($id)
    {
        return isset($this->indexes[$id]['chunk'])
            ? $this->indexes[$id]['chunk']
            : false;
    }

    final public function getRootIndex() {
        static $root = null;
        if ($root == null) {
            $root = $this->indexRepository->getRootIndex();
        }
        return $root;
    }
/* }}} */

/* {{{ Table helper functions */
    public function tgroup($attrs) {
        if (isset($attrs["cols"])) {
            $this->TABLE["cols"] = $attrs["cols"];
            unset($attrs["cols"]);
        }

        $this->TABLE["defaults"] = $attrs;
        $this->TABLE["colspec"] = array();
    }
    public function colspec(array $attrs) {
        $colspec = self::getColSpec($attrs);
        $this->TABLE["colspec"][$colspec["colnum"]] = $colspec;
        return $colspec;
    }
    public function getColspec(array $attrs) {
/* defaults */
        $defaults["colname"] = count($this->TABLE["colspec"])+1;
        $defaults["colnum"]  = count($this->TABLE["colspec"])+1;

        return array_merge($defaults, $this->TABLE["defaults"], $attrs);
    }
    public function getColCount() {
        return $this->TABLE["cols"];
    }
    public function initRow() {
        $this->TABLE["next_colnum"] = 1;
    }
    public function getEntryOffset(array $attrs) {
        $curr = $this->TABLE["next_colnum"];
        foreach($this->TABLE["colspec"] as $spec) {
            if ($spec["colname"] == $attrs["colname"]) {
                $colnum = $spec["colnum"];
                $this->TABLE["next_colnum"] += $colnum-$curr;
                return $colnum-$curr;
            }
        }
        return -1;
    }
    public function colspan(array $attrs) {
        $to = 0;
        $from = 0;
        if (isset($attrs["namest"])) {
            foreach($this->TABLE["colspec"] as $spec) {
                if ($spec["colname"] == $attrs["namest"]) {
                    $from = $spec["colnum"];
                    continue;
                }
                if ($spec["colname"] == $attrs["nameend"]) {
                    $to = $spec["colnum"];
                    continue;
                }
            }
            $colspan = $to-$from+1;
            $this->TABLE["next_colnum"] += $colspan;
            return $colspan;
        }
        $this->TABLE["next_colnum"]++;
        return 1;
    }
    public function rowspan($attrs) {
        if (isset($attrs["morerows"])) {
            return $attrs["morerows"]+1;
        }
        return 1;
    }
/* }}} */

    /**
     * Trim whitespace from a tag value and emit a warning if whitespace was found.
     *
     * @param string $value   The value to trim
     * @param string $tagName The name of the tag for the warning message
     *
     * @return string The trimmed value
     */
    public static function trimValue(string $value, string $tagName): string
    {
        $trimmed = trim($value);
        if ($trimmed !== $value) {
            trigger_error(
                "Whitespace found in <$tagName> tag content, this should be fixed in the XML source",
                E_USER_WARNING,
            );
        }
        return $trimmed;
    }

    /**
    * Highlight (color) the given piece of source code
    *
    * @param string $text   Text to highlight
    * @param string $role   Source code role to use (php, xml, html, ...)
    * @param string $format Format to highlight (xhtml, troff, ...)
    *
    * @return string Highlighted code
    */
    public function highlight($text, $role = 'php', $format = 'xhtml')
    {
        if (!isset(self::$highlighters[$format])) {
            $class = $this->config->highlighter;
            self::$highlighters[$format] = $class::factory($format);
        }

        return self::$highlighters[$format]->highlight(
            $text, $role, $format
        );
    }

    /**
    * Provide a nested list of IDs from the document root to the CURRENT_ID.
    *
    * @param string  $name  The name of the current element.
    * @param mixed[] $props Properties relating to the current element.
    *
    * @return string A nested list of IDs from the root to the CURRENT_ID.
    */
    public function getDebugTree($name, $props)
    {
        /* Build the list of IDs from the CURRENT_ID to the root. */
        $ids = array();
        $id = $this->CURRENT_ID;
        while($id != '')
            {
            $ids[] = '<' . $this->indexes[$id]['element'] . ' id="' . $id . '">';
            $id = $this->indexes[$id]['parent_id'];
            }

        /* Reverse the list so that it goes form the root to the CURRENT_ID. */
        $ids = array_reverse($ids);

        /* Build an indented tree view of the ids. */
        $tree = '';
        $indent = 0;
        array_walk($ids, function($value, $key) use(&$tree, &$indent)
        {
            $tree .= str_repeat('    ', $indent++) . $value . PHP_EOL;
        });

        /* Add the open and closed sibling and the current element. */
        $tree .=
            str_repeat('    ', $indent) . '<' . $props['sibling'] . '>' . PHP_EOL .
            str_repeat('    ', $indent) . '...' . PHP_EOL .
            str_repeat('    ', $indent) . '</' . $props['sibling'] . '>' . PHP_EOL .
            str_repeat('    ', $indent) . '<' . $name . '>' . PHP_EOL;

        return $tree;
    }
}
