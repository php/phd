<?php
namespace phpdotnet\phd;

class IndexRepository
{
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
            $postReplacementValues[$key] = $this->db->escapeString($postReplacementValue);
        }

        foreach ($commitList as $key => $commit) {
            $commitList[$key] = sprintf(
                "INSERT INTO ids (docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next, chunk) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d);\n",
                $this->db->escapeString($commit["docbook_id"]),
                $this->db->escapeString($commit["filename"]),
                $this->db->escapeString($commit["parent_id"]),
                $this->db->escapeString($commit["sdesc"]),
                $this->db->escapeString($commit["ldesc"]),
                $this->db->escapeString($commit["element"]),
                $this->db->escapeString($commit["previous"]),
                $this->db->escapeString($commit["next"]),
                $this->db->escapeString($commit["chunk"])
            );
        }

        $search = $this->db->escapeString("POST-REPLACEMENT");
        $none = $this->db->escapeString("");

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
                $log .= sprintf(
                    "INSERT INTO changelogs (membership, docbook_id, parent_id, version, description) VALUES('%s', '%s', '%s', '%s', '%s');\n",
                    $this->db->escapeString($entry[0] ?? ''),
                    $this->db->escapeString($id),
                    $this->db->escapeString($entry[1]),
                    $this->db->escapeString($entry[2]),
                    $this->db->escapeString($entry[3])
                );
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
}
