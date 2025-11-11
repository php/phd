<?php
namespace phpdotnet\phd;

class IndexRepository
{
    private array $indexes  = [];
    private array $children = [];
    private array $refs     = [];
    private array $vars     = [];
    private array $classes  = [];
    private array $examples = [];

    public function __construct(
        private \SQLite3 $db
    ) {}

    public function init(): void {
        $create = <<<SQL
CREATE TABLE ids (
docbook_id TEXT,
filename TEXT,
parent_id TEXT,
sdesc TEXT,
ldesc TEXT,
element TEXT,
previous TEXT,
next TEXT,
chunk INTEGER
);
CREATE TABLE changelogs (
membership TEXT, -- How the extension in distributed (pecl, core, bundled with/out external dependencies..)
docbook_id TEXT,
parent_id TEXT,
version TEXT,
description TEXT
);
CREATE TABLE indexing (
time INTEGER PRIMARY KEY
);
SQL;
        $this->db->exec('DROP TABLE IF EXISTS ids');
        $this->db->exec('DROP TABLE IF EXISTS indexing');
        $this->db->exec('DROP TABLE IF EXISTS changelogs');
        $this->db->exec('PRAGMA default_synchronous=OFF');
        $this->db->exec('PRAGMA count_changes=OFF');
        $this->db->exec('PRAGMA cache_size=100000');
        $this->db->exec($create);
    }

    public function saveIndexingTime(int $time): void {
        $this->db->exec("BEGIN TRANSACTION; INSERT INTO indexing (time) VALUES ('" . $time . "'); COMMIT");
    }

    public function commit(
        array $commitList,
        array $postReplacementIndexes,
        array $postReplacementValues,
        array $changelog,
    ): bool {
        if (!$commitList) {
            return false;
        }

        foreach ($postReplacementValues as $key => $postReplacementValue) {
            $postReplacementValues[$key] = $this->db::escapeString($postReplacementValue);
        }

        foreach ($commitList as $key => $commit) {
            $commitList[$key] = sprintf(
                "INSERT INTO ids (docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next, chunk) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d);\n",
                $this->db::escapeString($commit["docbook_id"]),
                $this->db::escapeString($commit["filename"]),
                $this->db::escapeString($commit["parent_id"]),
                $this->db::escapeString($commit["sdesc"]),
                $this->db::escapeString($commit["ldesc"]),
                $this->db::escapeString($commit["element"]),
                $this->db::escapeString($commit["previous"]),
                $this->db::escapeString($commit["next"]),
                $this->db::escapeString($commit["chunk"])
            );
        }

        $search = $this->db::escapeString("POST-REPLACEMENT");
        $none = $this->db::escapeString("");

        foreach($postReplacementIndexes as $a) {
            if (isset($postReplacementValues[$a["docbook_id"]])) {
                $replacement = $postReplacementValues[$a["docbook_id"]];
                $commitList[$a["idx"]] = str_replace($search, $replacement, $commitList[$a["idx"]]);
            } else {
                // If there are still post replacement, then they don't have
                // any 'next' page
                $commitList[$a["idx"]] = str_replace($search, $none, $commitList[$a["idx"]]);
            }
        }

        $this->db->exec('BEGIN TRANSACTION; '.implode("", $commitList).' COMMIT');
        $this->saveChangelogs($changelog);
        return true;
    }

    private function saveChangelogs(array $changelog): void {
        $log = "";
        foreach($changelog as $id => $arr) {
            foreach($arr as $entry) {
                foreach(preg_split('/,\s+/', $entry[2]) as $version) {
                    $log .= sprintf(
                        "INSERT INTO changelogs (membership, docbook_id, parent_id, version, description) VALUES('%s', '%s', '%s', '%s', '%s');\n",
                        $this->db::escapeString($entry[0] ?? ''),
                        $this->db::escapeString($id),
                        $this->db::escapeString($entry[1]),
                        $this->db::escapeString($version),
                        $this->db::escapeString($entry[3])
                    );
                }
            }
        }
        $this->db->exec('BEGIN TRANSACTION; ' . $log. ' COMMIT');
    }

    public function lastErrorCode(): int {
        return $this->db->lastErrorCode();
    }

    public function lastErrorMsg(): string {
        return $this->db->lastErrorMsg();
    }

    public function getIndexingTimeCount(): int {
        $queryResult = $this->db->query('SELECT COUNT(time) FROM indexing');
        if ($queryResult === false) {
            return 0;
        }
        $indexingCount = $queryResult->fetchArray(\SQLITE3_NUM);
        return $indexingCount[0];
    }

    public function getIndexingTime(): int {
        $indexing = $this->db->query('SELECT time FROM indexing')
            ->fetchArray(\SQLITE3_ASSOC);
        return $indexing['time'];
    }

    public function getIndexes(): array {
        $this->indexes = [];
        $this->db->createAggregate("indexes", [$this, "SQLiteIndex"], [$this, "SQLiteFinal"], 9);
        $this->db->query('SELECT indexes(docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next, chunk) FROM ids');
        return $this->indexes;
    }

    protected function SQLiteIndex($context, $index, $id, $filename, $parent, $sdesc, $ldesc, $element, $previous, $next, $chunk): void {
        $this->indexes[$id] = [
            "docbook_id" => $id,
            "filename"   => $filename,
            "parent_id"  => $parent,
            "sdesc"      => $sdesc,
            "ldesc"      => $ldesc,
            "element"    => $element,
            "previous"   => $previous,
            "next"       => $next,
            "chunk"      => $chunk,
        ];
    }

    public function getIndexesWithDuplicates(): array
    {
        $results =  $this->db->query('SELECT docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next, chunk FROM ids');
        $indexes = [];
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $indexes[] = $row;
        }
        return $indexes;
    }

    private static function SQLiteFinal($context): mixed {
        return $context;
    }

    public function getChildren(): array {
        $this->children = [];
        $this->db->createAggregate("children", [$this, "SQLiteChildren"], [$this, "SQLiteFinal"], 2);
        $this->db->query('SELECT children(docbook_id, parent_id) FROM ids WHERE chunk != 0');
        return $this->children;
    }

    private function SQLiteChildren($context, $index, $id, $parent): void {
        if (!isset($this->children[$parent])
            || !is_array($this->children[$parent])
        ) {
            $this->children[$parent] = [];
        }
        $this->children[$parent][] = $id;
    }

    public function getRefNames(): array {
        $this->refs = [];
        $this->db->createAggregate("refname", [$this, "SQLiteRefname"], [$this, "SQLiteFinal"], 2);
        $this->db->query('SELECT refname(docbook_id, sdesc) FROM ids WHERE element=\'refentry\'');
        return $this->refs;
    }


    private function SQLiteRefname($context, $index, $id, $sdesc): void {
        $ref = strtolower($sdesc);
        $this->refs[$ref] = $id;
    }

    public function getVarNames(): array {
        $this->vars = [];
        $this->db->createAggregate("varname", [$this, "SQLiteVarname"], [$this, "SQLiteFinal"], 2);
        $this->db->query('SELECT varname(docbook_id, sdesc) FROM ids WHERE element=\'phpdoc:varentry\'');
        return $this->vars;
    }

    private function SQLiteVarname($context, $index, $id, $sdesc): void {
        $this->vars[$sdesc] = $id;
    }

    public function getClassNames(): array {
        $this->classes = [];
        $this->db->createAggregate("classname", [$this, "SQLiteClassname"], [$this, "SQLiteFinal"], 2);
        $this->db->query('SELECT classname(docbook_id, sdesc) FROM ids WHERE element=\'phpdoc:exceptionref\' OR element=\'phpdoc:classref\'');
        return $this->classes;
    }

    private function SQLiteClassname($context, $index, $id, $sdesc): void {
        $this->classes[strtolower($sdesc)] = $id;
    }

    public function getExamples(): array {
        $this->examples = [];
        $this->db->createAggregate("example", [$this, "SQLiteExample"], [$this, "SQLiteFinal"], 1);
        $this->db->query('SELECT example(docbook_id) FROM ids WHERE element=\'example\'');
        return $this->examples;
    }

    private function SQLiteExample($context, $index, $id): void {
        $this->examples[] = $id;
    }

    public function getRootIndex(): array|false {
        return $this->db->querySingle('SELECT * FROM ids WHERE parent_id=""', true);
    }

    public function getChangelogsForChildrenOf($bookids): array {
        $ids = [];
        foreach((array)$bookids as $bookid) {
            $ids[] = "'" . $this->db::escapeString($bookid) . "'";
        }
        $results = $this->db->query("SELECT * FROM changelogs WHERE parent_id IN (" . join(", ", $ids) . ")");
        return $this->_returnChangelog($results);
    }

    public function getChangelogsForMembershipOf($memberships): array {
        $ids = [];
        foreach((array)$memberships as $membership) {
            $ids[] = "'" . $this->db::escapeString($membership) . "'";
        }
        $results = $this->db->query("SELECT * FROM changelogs WHERE membership IN (" . join(", ", $ids) . ")");
        return $this->_returnChangelog($results);
    }

    private function _returnChangelog($results): array {
        if (!$results) {
            return [];
        }

        $changelogs = [];
        while ($row = $results->fetchArray()) {
            $changelogs[] = $row;
        }
        return $changelogs;
    }

    public function getParents(array $renderIds): array {
        $parents = [];
        foreach($renderIds as $p => $v) {
            do {
                $id = $this->db::escapeString($p);
                $row = $this->db->query("SELECT parent_id FROM ids WHERE docbook_id = '$id'")->fetchArray(\SQLITE3_ASSOC);
                if ($row["parent_id"]) {
                    $parents[] = $p = $row["parent_id"];
                    continue;
                }
                break;
            } while(1);
        }
        return $parents;
    }
}
