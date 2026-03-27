<?php
namespace phpdotnet\phd;

class JsonIndex
{
    private const array ALWAYS_INCLUDE_ELEMENTS = [
        'refentry',
        'reference',
        'stream_wrapper',
        'phpdoc:classref',
        'phpdoc:exceptionref',
        'phpdoc:varentry',
    ];

    public function __construct(
        private readonly array $indexes,
        private readonly array $indexesWithDuplicates,
    ) {}

    /**
     * Processes the index to extract entries and descriptions. These are
     * used to generate the search index and the descriptions JSON files.
     */
    public function processJsonIndex(): array
    {
        $entries = [];
        $descriptions = [];
        foreach ($this->indexes as $id => $index) {
            if (
                (! $index['chunk'])
                && (! in_array($index['element'], self::ALWAYS_INCLUDE_ELEMENTS, true))
            ) {
                continue;
            }

            if ($index["sdesc"] === "" && $index["ldesc"] !== "") {
                $index["sdesc"] = $index["ldesc"];
                $bookOrSet = $this->findParentBookOrSet($index['parent_id']);
                if ($bookOrSet) {
                    $index["ldesc"] = $this->getLongDescription(
                        $bookOrSet['docbook_id']
                    );
                }
            }

            $entries[] = [
                $index["sdesc"], $index["filename"], $index["element"]
            ];
            $descriptions[$id] = html_entity_decode($index["ldesc"]);
        }
        return [$entries, $descriptions];
    }

    public function processCombinedJsonIndex(): array
    {
        $entries = [];
        foreach ($this->indexesWithDuplicates as $index) {
            if (
                (! $index['chunk'])
                && (! in_array($index['element'], self::ALWAYS_INCLUDE_ELEMENTS, true))
            ) {
                continue;
            }

            if ($index["sdesc"] === "" && $index["ldesc"] !== "") {
                $index["sdesc"] = $index["ldesc"];
                $bookOrSet = $this->findParentBookOrSet($index['parent_id']);
                if ($bookOrSet) {
                    $index["ldesc"] = $this->getLongDescription(
                        $bookOrSet['docbook_id']
                    );
                }
            }

            $nameParts = explode('::', $index['sdesc']);
            $methodName = array_pop($nameParts);

            $type = match ($index['element']) {
                'phpdoc:varentry' => 'Variable',
                'refentry' => 'Function',
                'phpdoc:exceptionref' => 'Exception',
                'phpdoc:classref' => 'Class',
                'set', 'book', 'reference' => 'Extension',
                default => 'General',
            };

            $entries[] = [
                'id' => $index['filename'],
                'name' => $index['sdesc'],
                'description' => html_entity_decode($index['ldesc']),
                'tag' => $index['element'],
                'type' => $type,
                'methodName' => $methodName,
            ];
        }
        return $entries;
    }

    /**
     * Finds the closest parent book or set in the index hierarchy.
     */
    public function findParentBookOrSet(string $id): ?array
    {
        // array_key_exists() to guard against undefined array keys, either for
        // root elements (no parent) or in case the index structure is broken.
        while (array_key_exists($id, $this->indexes)) {
            $parent = $this->indexes[$id];
            $element = $parent['element'];

            if ($element === 'book' || $element === 'set') {
                return $parent;
            }

            $id = $parent['parent_id'];
        }

        return null;
    }

    private function getLongDescription(string $id): string
    {
        if ($this->indexes[$id]["ldesc"]) {
            return $this->indexes[$id]["ldesc"];
        }
        return $this->indexes[$id]["sdesc"];
    }
}
