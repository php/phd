<?php
namespace phpdotnet\phd;
/* $Id$ */

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
    'legalnotice'           => 'format_legalnotice_chunk',
    'part'                  => 'format_container_chunk',
    'phpdoc:exception'      => 'format_container_chunk',
    'phpdoc:exceptionref'   => 'format_container_chunk',
    'phpdoc:classref'       => 'format_container_chunk',
    'phpdoc:varentry'       => 'format_varentry_chunk',
    'preface'               => 'format_chunk',
    'refentry'              => 'format_chunk',
    'reference'             => 'format_container_chunk',
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
    'title'                 => 'format_ldesc',
    'refpurpose'            => 'format_ldesc',
    'refname'               => 'format_sdesc',
    'titleabbrev'           => 'format_sdesc',
    );

    private $mytextmap = array(
    );
    private $pihandlers = array(
        'dbhtml'            => 'PI_DBHTMLHandler',
    );

    private $chunks    = array();
    private $isChunk   = array();
    private $previousId = "";

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
    }
    public function TEXT($value) {
    }
    public function CDATA($value) {
    }
    public function createLink($for, &$desc = null, $type = Format::SDESC) {
    }
    public function appendData($data) {
    }

    /**
     * Checks if indexing is needed.
     *
     * This is determined the following way:
     * 0. If no index file exists, indexing is required.
     * 1. If the config option --no-index is supplied, nothing is indexed
     * 2. If the config option --force-index is supplied, indexing is required
     * 3. If no option is given, the file modification time of the index and
     *    the manual docbook file are compared. If the index is older than
     *    the docbook file, indexing will be done.
     *
     * @return boolean True if indexing is required.
     */
    final static public function requireIndexing()
    {
        $indexfile = Config::output_dir() . 'index.sqlite';
        if (!file_exists($indexfile)) {
            return true;
        }

        if (Config::no_index()) {
            return false;
        }

        if (Config::force_index()) {
            return true;
        }

        $db            = new \SQLite3($indexfile);
        $indexingCount = $db->query('SELECT COUNT(time) FROM indexing')
            ->fetchArray(SQLITE3_NUM);
        if ($indexingCount[0] == 0) {
            return true;
        }

        $indexing = $db->query('SELECT time FROM indexing')
            ->fetchArray(SQLITE3_ASSOC);
        $xmlLastModification = filemtime(Config::xml_file());
        if ($indexing['time'] > $xmlLastModification) {
            return false;
        }
        return true;
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
                    if (file_exists(Config::output_dir() . "index.sqlite")) {
                        $db = new \SQLite3(Config::output_dir() . 'index.sqlite');
                        $db->exec('DELETE FROM ids');
                        $db->exec('DELETE FROM indexing');
                    } else {
                        $db = new \SQLite3(Config::output_dir() . 'index.sqlite');
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
            case Render::FINALIZE:
                $retval = $this->db->exec("BEGIN TRANSACTION; INSERT INTO indexing (time) VALUES ('" . time() . "'); COMMIT");
                $this->commit();
                if ($this->db->lastErrorCode()) {
                    trigger_error($this->db->lastErrorMsg(), E_USER_WARNING);
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
            $this->commitAfter .= sprintf(
                "UPDATE ids SET next = '%s' WHERE docbook_id = '%s';\n",
                $this->db->escapeString($id),
                $this->db->escapeString($this->previousId)
            );
            $this->previousId = $id;
        }
    }
    public function appendID() {
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
            "INSERT INTO ids (docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next, chunk) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '', %d);\n",
            $this->db->escapeString($lastChunkId),
            $this->db->escapeString($lastChunk["filename"]),
            $this->db->escapeString($this->currentchunk),
            $this->db->escapeString($sdesc),
            $this->db->escapeString($lastChunk["ldesc"]),
            $this->db->escapeString($lastChunk["element"]),
            $this->db->escapeString($lastChunk["previous"]),
            $this->db->escapeString($lastChunk["chunk"])
        );
        if ($array === true) {
            foreach($lastChunk["sdesc"] as $sdesc) {
                $this->commit .= sprintf(
                    "INSERT INTO ids (docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next, chunk) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '', 0);\n",
                    $this->db->escapeString($lastChunkId),
                    $this->db->escapeString($lastChunk["filename"]),
                    $this->db->escapeString($this->currentchunk),
                    $this->db->escapeString($sdesc),
                    $this->db->escapeString($lastChunk["ldesc"]),
                    $this->db->escapeString($lastChunk["element"]),
                    $this->db->escapeString($lastChunk["previous"])
                );
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
        return $this->format_chunk($open, $name, $attrs, $props);
    }
    public function format_varentry_chunk($open, $name, $attrs, $props) {
        return $this->format_chunk($open, $name, $attrs, $props);
    }
    public function format_chunk($open, $name, $attrs, $props) {
        $this->processFilename();
        if ($open) {
            if(isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
            } else {
                $this->isChunk[] = false;
                return false;
            }
            $this->isChunk[] = isset($attrs[Reader::XMLNS_PHD]['chunk'])
                    ? $attrs[Reader::XMLNS_PHD]['chunk'] == "true" : true;

            if (end($this->isChunk)) {
                $this->chunks[] = $id;
                $this->currentchunk = $id;
                $this->storeInfo($name, $id, $id);
            }
            return false;
        }
        if (array_pop($this->isChunk)) { 
            $lastchunk = array_pop($this->chunks);
            $this->currentchunk = end($this->chunks);
            $this->appendID();
        }
        return false;
    }
    public function format_legalnotice_chunk($open, $name, $attrs, $props) {
        return $this->format_chunk($open, $name, $attrs, $props);
    }
    public function format_ldesc($open, $name, $attrs, $props) {
        if ($open) {
            if (empty($this->nfo[$this->currentid]["ldesc"])) {
                /* FIXME: How can I mark that node with "reparse" flag? */
                $s = htmlentities(trim(ReaderKeeper::getReader()->readContent()), ENT_COMPAT, "UTF-8");
                $this->nfo[$this->currentid]["ldesc"] = $s;
            }
        }
    }
    public function format_sdesc($open, $name, $attrs, $props) {
        if ($open) {
            $s = htmlentities(trim(ReaderKeeper::getReader()->readContent()), ENT_COMPAT, "UTF-8");
            if (empty($this->nfo[$this->currentid]["sdesc"])) {
                /* FIXME: How can I mark that node with "reparse" flag? */
                $this->nfo[$this->currentid]["sdesc"] = $s;
            } else {
                if (!is_array($this->nfo[$this->currentid]["sdesc"])) {
                    $this->nfo[$this->currentid]["sdesc"] = (array)$this->nfo[$this->currentid]["sdesc"];
                }
                //In the beginning of the array to stay compatible with 0.4 
                array_unshift($this->nfo[$this->currentid]["sdesc"], $s);
            }
        }
    }
    
    public function commit() {
        if (isset($this->commit) && $this->commit) {
            $this->db->exec('BEGIN TRANSACTION; '.$this->commit.' COMMIT');
            $this->db->exec('BEGIN TRANSACTION; '.$this->commitAfter.' COMMIT');
            $this->commit = $this->commitAfter = null;
        }
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
}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

