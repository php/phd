<?php
class PhDIndex extends PhDFormat {
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

    'legalnotice'           => 'format_legalnotice_chunk',
    'part'                  => 'format_container_chunk',
    'phpdoc:exception'      => 'format_container_chunk',
    'preface'               => 'format_chunk',
    'refentry'              => 'format_chunk',
    'reference'             => 'format_container_chunk',
    'sect1'                 => 'format_section_chunk',
    'section'               => array(
        /* DEFAULT */          false,
        'sect1'                => 'format_section_chunk',
        'preface'              => 'format_section_chunk',
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
    'set'                   => 'format_container_chunk',
    'setindex'              => 'format_chunk',



            'title'                 => 'format_ldesc',
            'refpurpose'            => 'format_ldesc',
            'refname'               => 'format_refname',
            'titleabbrev'           => 'format_sdesc',
    );
    private $mytextmap = array(
    );
    private $chunks    = array();
    private $previousId = "";

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
    }
    public function TEXT($value) {
    }
    public function CDATA($value) {
    }
    public function createLink($for, &$desc = null, $type = PhDFormat::SDESC) {
    }
    public function appendData($data) {
    }
    // Require indexing if --index=true, if no indexing has been successfully done before
    // or if xml input file has changed since the last indexing
    final static public function requireIndexing() {
        if (!PhDConfig::index() && file_exists(PhDConfig::output_dir() . "index.sqlite")) {
            $db = new SQLite3(PhDConfig::output_dir() . 'index.sqlite');
            $indexingCount = $db->query('SELECT COUNT(time) FROM indexing')->fetchArray(SQLITE3_NUM);
            if ($indexingCount[0] > 0) {
                $indexing = $db->query('SELECT time FROM indexing')->fetchArray(SQLITE3_ASSOC);
                $xmlLastModification = filemtime(PhDConfig::xml_file());
                if ($indexing["time"] > $xmlLastModification) {
                    return false;
                }
            }
        }
        return true;
    }
    public function update($event, $value = null) {
        switch($event) {
            case PhDRender::CHUNK:
                $this->flags = $value;
                break;

            case PhDRender::STANDALONE:
                if ($value) {
                    $this->registerElementMap(static::getDefaultElementMap());
                    $this->registerTextMap(static::getDefaultTextMap());
                }
                break;
            case PhDRender::INIT:
                if ($value) {
                    if (file_exists(PhDConfig::output_dir() . "index.sqlite")) {
                        $db = new SQLite3(PhDConfig::output_dir() . 'index.sqlite');
                        $db->exec('DELETE FROM ids');
                        $db->exec('DELETE FROM indexing');
                    } else {
                        $db = new SQLite3(PhDConfig::output_dir() . 'index.sqlite');
                        $create = <<<SQL
CREATE TABLE ids (
    docbook_id TEXT PRIMARY KEY,
    filename TEXT,
    parent_id TEXT,
    sdesc TEXT,
    ldesc TEXT,
    element TEXT,
    previous TEXT,
    next TEXT
);
CREATE TABLE indexing (
    time INTEGER PRIMARY KEY
);
SQL;
                        $db->exec('PRAGMA default_synchronous=OFF');
                        $db->exec('PRAGMA count_changes=OFF');
                        $db->exec('PRAGMA cache_size=100000');
                        $db->exec($create);
                    }
                    $this->db = $db;

                    $this->chunks = array();
                } else {
                    print_r($this->chunks);
                }
                break;
            case PhDRender::FINALIZE:
                $this->db->exec("INSERT INTO indexing (time) VALUES ('" . time() . "')");
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
        /*if ($open) {
            if(isset($attrs[PhDReader::XMLNS_XML]["id"])) {
                $id = $attrs[PhDReader::XMLNS_XML]["id"];
                $this->storeInfo($name, $id, $this->currentchunk);
            }
            return false;
        }

        if(isset($attrs[PhDReader::XMLNS_XML]["id"])) {
            $this->appendID();
        }*/
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
                    "previous" => $this->previousId
        );
        // Append "next" to the previously inserted row
        if ($isChunk) {
            $this->commitAfter .= sprintf(
                "UPDATE ids SET next = '%s' WHERE docbook_id = '%s';\n",
                $this->db->escapeString($id),
                $this->db->escapeString($this->previousId)
            );
            //echo "resquete UPDATE ids SET next = '".$this->db->escapeString($id)."' WHERE docbook_id = '".$this->db->escapeString($this->previousId)."';\n";
            $this->previousId = $id;
        }
    }
    public function appendID($isChunk = true) {
        static $rand = 0;

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
        $this->commit .= sprintf(
            "INSERT INTO ids (docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '');\n",
            $this->db->escapeString($lastChunkId),
            $this->db->escapeString($lastChunk["filename"]),
            $this->db->escapeString($this->currentchunk),
            $this->db->escapeString($sdesc),
            $this->db->escapeString($lastChunk["ldesc"]),
            $this->db->escapeString($lastChunk["element"]),
            $this->db->escapeString($lastChunk["previous"])
        );
        if ($array === true && !empty($a["sdesc"])) {
            foreach($lastChunk["sdesc"] as $sdesc) {
                ++$rand;
                $this->commit .= sprintf(
                    "INSERT INTO ids (docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next) VALUES('%s', '%s', '', '%s', '%s', '%s', '%s', '');\n",
                    "phdgen-" . $rand,
                    $this->db->escapeString($lastChunk["filename"]),
                    $this->db->escapeString($sdesc),
                    $this->db->escapeString($lastChunk["ldesc"]),
                    $this->db->escapeString($lastChunk["element"]),
                    $this->db->escapeString($lastChunk["previous"])
                );
            }
        }
    }

    public function format_section_chunk($open, $name, $attrs, $props) {
        static $sectionChunks = array();
        if ($open) {
            $sectionChunks[] = $props["sibling"];
            if ($props["sibling"] === $name) {
                return $this->format_chunk($open, $name, $attrs, $props);
            }
            if(isset($attrs[PhDReader::XMLNS_XML]["id"])) {
                $id = $attrs[PhDReader::XMLNS_XML]["id"];
                return $this->storeInfo($name, $id, $this->currentchunk, false);
            }
            return $this->UNDEF($open, $name, $attrs, $props);
        }
        $x = array_pop($sectionChunks);
        if ($x == $name) {
            return $this->format_chunk($open, $name, $attrs, $props);
        }
        $sectionChunks[] = $x;
        if(isset($attrs[PhDReader::XMLNS_XML]["id"])) {
            $id = $attrs[PhDReader::XMLNS_XML]["id"];
            return $this->appendID(false);
        }
        return $this->UNDEF($open, $name, $attrs, $props);
    }
    public function format_container_chunk($open, $name, $attrs, $props) {
        return $this->format_chunk($open, $name, $attrs, $props);
    }
    public function format_chunk($open, $name, $attrs, $props) {
        if ($open) {
            if(isset($attrs[PhDReader::XMLNS_XML]["id"])) {
                $id = $attrs[PhDReader::XMLNS_XML]["id"];
            } else {
                $id = uniqid("phd");
            }
            $this->chunks[] = $id;
            $this->currentchunk = $id;
            $this->storeInfo($name, $id, $id);

            $this->notify(PhDRender::CHUNK, PhDRender::OPEN);

            return false;
        }
        array_pop($this->chunks);
        $this->currentchunk = end($this->chunks);

        $this->notify(PhDRender::CHUNK, PhDRender::CLOSE);

        $this->appendID();
        return false;
    }
    public function format_legalnotice_chunk($open, $name, $attrs, $props) {
        return $this->format_chunk($open, $name, $attrs, $props);
    }
    public function format_ldesc($open, $name, $attrs, $props) {
        if ($open) {
            if (empty($this->nfo[$this->currentid]["ldesc"])) {
                            /* FIXME: How can I mark that node with "reparse" flag? */
                $s = $this->getReader()->readInnerXml();
                $this->nfo[$this->currentid]["ldesc"] = $s;
            }
        }
    }
    public function format_sdesc($open, $name, $attrs, $props) {
        if ($open) {
            if (empty($this->nfo[$this->currentid]["sdesc"])) {
                            /* FIXME: How can I mark that node with "reparse" flag? */
                $s = $this->getReader()->readInnerXml();
                $this->nfo[$this->currentid]["sdesc"] = $s;
            }

        }
    }
    public function format_refname($open, $name, $attrs, $props) {
        if ($open) {
            $s = $this->getReader()->readInnerXml();
            $s = str_replace(array("_", "::", "->"), array("-", "-", "-"), $s);
            $this->nfo[$this->currentid]["sdesc"][] = strtolower($s);
        }
    }
    public function commit() {
        if (isset($this->commit) && $this->commit) {
            var_dump($this->db->exec('BEGIN TRANSACTION; '.$this->commit.' COMMIT'));
            var_dump($this->db->exec('BEGIN TRANSACTION; '.$this->commitAfter.' COMMIT'));
            $this->commit = $this->commitAfter = null;
        }
    }
}

