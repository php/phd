<?php

require_once $ROOT . '/themes/pear/peartheme.php';
class pearweb extends peartheme {
    protected $streams = array();
    protected $writeit = false;
    protected $outputdir = '';


    public function __construct($IDs, $ext = "php", $chunked = true) {
        parent::__construct($IDs, $ext, $chunked);
        $this->outputdir = PhDConfig::output_dir() . $this->ext . DIRECTORY_SEPARATOR;
        if (!file_exists($this->outputdir) || is_file($this->outputdir)) {
            mkdir($this->outputdir) or die("Can't create the cache directory");
        } else {
            if (file_exists($this->outputdir . "index.php")) {
                unlink($this->outputdir . "index.php");
            }
        }
    }
    public function writeChunk($id, $stream) {
        rewind($stream);

        // Create random filename when the chunk doesn't have an ID
        if ($id === null) {
            $filename = tempnam($this->outputdir, 'phd');
            $newfilename = $this->outputdir . basename($filename, '.tmp') . '.' . $this->ext;
            if(rename($filename, $newfilename)) {
                $filename = basename($filename, '.tmp');
            } else {
                throw new Exception("Cannot rename $filename to $newfilename");
            }
            trigger_error("Chunk without an ID found, TOC will NOT work. Wrote content to $newfilename.", E_USER_WARNING);
        } else {
            $filename = $id;
        }
	$filename .= '.' . $this->ext;

        file_put_contents($this->outputdir . $filename, $this->header($id));
        file_put_contents($this->outputdir . $filename, $stream, FILE_APPEND);
        file_put_contents($this->outputdir . $filename, $this->footer($id), FILE_APPEND);

        v("Wrote %s", $this->outputdir . $filename, VERBOSE_CHUNK_WRITING);
    }
    public function appendData($data, $isChunk) {
        switch($isChunk) {
        case PhDReader::CLOSE_CHUNK:
            $id = $this->CURRENT_ID;

            $stream = array_pop($this->streams);
            $retval = fwrite($stream, $data);
            $this->writeChunk($id, $stream);
            fclose($stream);

            $this->CURRENT_ID = null;
            return $retval;
            break;

        case PhDReader::OPEN_CHUNK:
            $this->streams[] = fopen("php://temp/maxmemory", "r+");

        default:
            $stream = end($this->streams);
            $retval = fwrite($stream, $data);
            return $retval;
        }
    }
    public function header($id) {
        $ext = "." . $this->ext;
        $parent = PhDHelper::getParent($id);

        if (!$parent || $parent == "ROOT")
        	return '<?php
sendManualHeaders("UTF-8","en");
setupNavigation(array(
  "home" => array("index.php", "PEAR Manual"),
  "prev" => array("#", ""),
  "next" => array("#", ""),
  "up"   => array("#", ""),
  "toc"  => array(
    array("#", ""))));
manualHeader("PEAR Manual","index.php");
?>
';

        // Fetch the siblings information
        $toc = array();
        $siblings = PhDHelper::getChildren($parent);
        foreach($siblings as $sibling => $array) {
            $toc[] = array($sibling.$ext, empty($array["sdesc"]) ? $array["ldesc"] : $array["sdesc"]);
        }

        // Build the PEAR navigation table
        $nav = array(
            'home' => array('index' . $ext, 'PEAR Manual'),
            'prev' => $this->createPrev($id, $parent, $siblings),
            'next' => $this->createNext($id, $parent, $siblings),
            'up'   => array($this->getFilename($parent).$ext, PhDHelper::getDescription($parent, true)),
            'toc'  => $toc
        );
		return "<?php \n" .
			"sendManualHeaders(\"UTF-8\", {$this->lang});\n" .
			"setupNavigation(" . var_export($nav, true) . ");\n" .
			'manualHeader("' . $this->getFilename($id).$ext . '", "' . PhDHelper::getDescription($id, true) . '");' . "\n" .
			"?>\n";
    }

    public function footer($id) {
    	$ext = $this->ext . ".";
        $parent = PhDHelper::getParent($id);

        return '<?php manualFooter("' . $this->getFilename($id).$ext . '", "' . PhDHelper::getDescription($id, true) . '"); ?>\n';
    }

    protected function createPrev($id, $parent, $siblings) {
        if (!isset($siblings[$id]) || $parent == 'ROOT') {
            return array(null, null);
        }
        $ext = '.' .$this->ext;

        // Seek to $id
        while (list($tmp,) = each($siblings)) {
            if ($tmp == $id) {
                // Set the internal pointer back to $id
                prev($siblings);
                break;
            }
        }
        $tmp = prev($siblings);
        if ($tmp) {
            while (!empty($tmp["children"])) {
                $tmp = end($tmp["children"]);
            }
            return array($tmp["filename"].$ext, (empty($tmp["sdesc"]) ? $tmp["ldesc"] : $tmp["sdesc"]));
            break;
        }

        return array(PhDHelper::getFilename($parent).$ext, PhDHelper::getDescription($parent, false));
    }
    protected function createNext($id, $parent, $siblings) {
        $ext = '.' .$this->ext;
        $next = array(null, null);
        // {{{ Create the "next" link
        if (!empty($siblings[$id]["children"])) {
            $tmp = reset($siblings[$id]["children"]);
            return array($tmp["filename"].$ext, (empty($tmp["sdesc"]) ? $tmp["ldesc"] : $tmp["sdesc"]));
        }
        do {
            if (!isset($siblings[$id])) {
                break;
            }

            // Seek to $id
            while(list($tmp,) = each($siblings)) {
                if ($tmp == $id) {
                    break;
                }
            }

            $tmp = current($siblings);
            prev($siblings); // Reset the internal pointer to previous pos
            if ($tmp) {
                $next = array($tmp["filename"].$ext, (empty($tmp["sdesc"]) ? $tmp["ldesc"] : $tmp["sdesc"]));
                break;
            }

            // We are the end element in this chapter
            $grandpa = PhDHelper::getParent($parent);
            if (!$grandpa || $grandpa == "ROOT") {
                // There is no next relative
                break;
            }

            $siblings  = PhDHelper::getChildren($grandpa);
            $id = $parent;
            $parent = $grandpa;
        } while(true);
        return $next;
    }
    public function __destruct() {
        if (file_exists($this->outputdir . "guide.php") && !file_exists($this->outputdir . "index.php")) {
            copy($this->outputdir . "guide.php", $this->outputdir . "index.php");
        }
    }

    public function format_qandaset($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["qandaentry"] = array();
            $this->appendData(null, PhDReader::OPEN_CHUNK);
            return '';
        }

        $stream = array_pop($this->streams);
        rewind($stream);
        return parent::qandaset($stream);
    }

}
