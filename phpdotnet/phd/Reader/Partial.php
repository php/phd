<?php
namespace phpdotnet\phd;

class Reader_Partial extends Reader
{
    protected $partial = array();
    protected $skip    = array();
    protected $parents = array();

    public function __construct(
        OutputHandler $outputHandler,
        array $renderIds,
        ?array $skipIds   = [],
        ?array $parents    = [],
    ) {
        parent::__construct($outputHandler);

        if ($renderIds === []) {
            throw new \Exception("Didn't get any IDs to seek");
        }

        $this->partial = $renderIds;
        $this->skip = $skipIds;
        $this->parents = $parents;
    }

    public function read(): bool {
        static $currently_reading = false;
        static $currently_skipping = false;
        static $arrayPartial = array();
        static $arraySkip = array();

        while($ret = parent::read()) {
            $id = $this->getAttributeNs("id", self::XMLNS_XML) ?? '';
            $currentPartial = end($arrayPartial);
            $currentSkip = end($arraySkip);
            if (isset($this->partial[$id])) {
                if ($currentPartial == $id) {
                    $this->outputHandler->v("%s done", $id, VERBOSE_PARTIAL_READING);

                    unset($this->partial[$id]);
                    $currently_reading = false;
                    array_pop($arrayPartial);
                } else {
                    $this->outputHandler->v("Starting %s...", $id, VERBOSE_PARTIAL_READING);

                    $currently_reading = $id;
                    $arrayPartial[] = $id;
                }
                return $ret;
            } elseif (isset($this->skip[$id])) {
                if ($currentSkip == $id) {
                    $this->outputHandler->v("%s done", $id, VERBOSE_PARTIAL_READING);

                    unset($this->skip[$id]);
                    $currently_skipping = false;
                    array_pop($arraySkip);
                } else {
                    $this->outputHandler->v("Skipping %s...", $id, VERBOSE_PARTIAL_READING);

                    $currently_skipping = $id;
                    $arraySkip[] = $id;
                }
            } elseif ($currently_skipping && $this->skip[$currently_skipping]) {
                if ($currentSkip == $id) {
                    $this->outputHandler->v("Skipping child of %s, %s", $currently_reading, $id, VERBOSE_PARTIAL_CHILD_READING);
                } else {
                    $this->outputHandler->v("%s done", $id, VERBOSE_PARTIAL_CHILD_READING);
                }

            } elseif ($currently_reading && $this->partial[$currently_reading]) {
                if ($currentPartial == $id) {
                    $this->outputHandler->v("Rendering child of %s, %s", $currently_reading, $id, VERBOSE_PARTIAL_CHILD_READING);
                } else {
                    $this->outputHandler->v("%s done", $id, VERBOSE_PARTIAL_CHILD_READING);
                }

                return $ret;
            } elseif (empty($this->partial)) {
                return false;
            } else {
                // If we are used by the indexer then we have no clue about the
                // parents :)
                if ($id && $this->parents) {
                    // If this id isn't one of our ancestors we can jump
                    // completely over it
                    if (!in_array($id, $this->parents)) {
                        parent::next();
                    }
                }
            }
        }
        return $ret;
    }
}
