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

	public function transformFromMap($open, $tag, $name, $attrs, $props) {
	}
	public function TEXT($value) {
	}
	public function CDATA($value) {
	}
	public function createLink($for, &$desc = null, $desc = PhDFormat::SDESC) {
	}
	public function appendData($data) {
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
				if (file_exists("index.sqlite")) {
					unlink("index.sqlite");
				}
				$db = sqlite_open("index.sqlite");

				sqlite_exec($db, 'PRAGMA default_synchronous=OFF');
				sqlite_exec($db, 'PRAGMA count_changes=OFF');
				sqlite_exec($db, 'PRAGMA cache_size=100000');

				$create = <<<SQL
CREATE TABLE ids (
	docbook_id TEXT PRIMARY KEY,
	filename TEXT,
	parent_id TEXT,
	sdesc TEXT,
	ldesc TEXT,
	element TEXT
);
SQL;
				sqlite_exec($db, 'PRAGMA default_synchronous=OFF');
				sqlite_exec($db, 'PRAGMA count_changes=OFF');
				sqlite_exec($db, 'PRAGMA cache_size=100000');
				sqlite_exec($db, $create);

				$this->db = $db;

				$this->chunks = array();
			} else {
				//print_r($this->chunks);
			}
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
            if(!isset($attrs[PhDReader::XMLNS_XML]["id"])) {
				return false;
			}
			$id = $attrs[PhDReader::XMLNS_XML]["id"];
			$this->storeInfo($name, $id, $this->currentchunk);

			return false;
		}

		if(!isset($attrs[PhDReader::XMLNS_XML]["id"])) {
			return false;
		}

		$this->appendID();
		return false;
	}
	protected function storeInfo($elm, $id, $filename) {
		$this->nfo[$id] = array(
			"parent"   => "",
			"filename" => $filename,
			"sdesc"    => "",
			"ldesc"    => "",
			"element"  => $elm,
			"children" => array(),
		);
		$this->ids[] = $id;
		$this->currentid = $id;
	}
	public function appendID() {
		static $rand = 0;

		$lastchunkid = array_pop($this->ids);
		$parentid = end($this->ids);

		$this->currentid = $parentid;
		$a = $this->nfo[$lastchunkid];
		if (is_array($a["sdesc"])) {
			$array = true;
			$sdesc = array_shift($a["sdesc"]);
		} else {
			$array = false;
			$sdesc = $a["sdesc"];
		}
		$this->commit .= sprintf(
			"INSERT INTO ids (docbook_id, filename, parent_id, sdesc, ldesc, element) VALUES('%s', '%s', '%s', '%s', '%s', '%s');\n",
			sqlite_escape_string($lastchunkid),
			sqlite_escape_string($a["filename"]),
			sqlite_escape_string($this->currentchunk),
			sqlite_escape_string($sdesc),
			sqlite_escape_string($a["ldesc"]),
			sqlite_escape_string($a["element"])
		);
		if ($array === true && !empty($a["sdesc"])) {
			foreach($a["sdesc"] as $sdesc) {
				++$rand;
				$this->commit .= sprintf(
					"INSERT INTO ids (docbook_id, filename, parent_id, sdesc, ldesc, element) VALUES('%s', '%s', '', '%s', '%s', '%s');\n",
					"phdgen-" . $rand,
					sqlite_escape_string($a["filename"]),
					sqlite_escape_string($sdesc),
					sqlite_escape_string($a["ldesc"]),
					sqlite_escape_string($a["element"])
				);
			}
		}
	}
    public function format_section_chunk($open, $name, $attrs, $props) {
        static $a = array();
        if ($open) {
            $a[] = $props["sibling"];
            if ($props["sibling"] === $name) {
                return $this->format_chunk($open, $name, $attrs, $props);
            }
            return $this->UNDEF($open, $name, $attrs, $props);
        }
        $x = array_pop($a);
        if ($x == $name) {
                return $this->format_chunk($open, $name, $attrs, $props);
        }
        $a[] = $x;
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
		var_dump(sqlite_exec($this->db, 'BEGIN TRANSACTION; '.$this->commit.' COMMIT'));
		$this->commit = null;
	}
}

