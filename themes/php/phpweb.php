<?php
/*  $Id$ */

require_once $ROOT . '/themes/php/phpdotnet.php';
class phpweb extends phpdotnet {
    protected $streams = array();
    protected $writeit = false;
    protected $outputdir = '';


    public function __construct($IDs, $filename, $ext = "php", $chunked = true) {
        parent::__construct($IDs, $filename, $ext, $chunked);
        $this->outputdir = $GLOBALS['OPTIONS']['output_dir'] . $this->ext . DIRECTORY_SEPARATOR;
        if (!file_exists($this->outputdir) || is_file($this->outputdir)) mkdir($this->outputdir) or die("Can't create the cache directory");
        if (!file_exists($this->outputdir . "toc") || is_file($this->outputdir . "toc")) mkdir($this->outputdir . "toc") or die("Can't create the toc directory");
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
            v("WARNING: Chunk without an ID found, TOC will NOT work. Wrote content to $newfilename.\n");
        } else {
            $filename = $id;
        }
	$filename .= '.' . $this->ext;

        file_put_contents($this->outputdir . $filename, $this->header($id));
        file_put_contents($this->outputdir . $filename, $stream, FILE_APPEND);
        file_put_contents($this->outputdir . $filename, $this->footer($id), FILE_APPEND);

        if ($GLOBALS["OPTIONS"]["verbose"] & VERBOSE_CHUNK_WRITING) {
            v("Wrote %s\n", $this->outputdir . $filename);
        }
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
        $ext = '.' .$this->ext;
        $parent = PhDHelper::getParent($id);
        $filename = "toc" . DIRECTORY_SEPARATOR . $parent . ".inc";
        $up = array(null, null);
        $incl = '';

        $next = $prev = array(null, null);
        if ($parent && $parent != "ROOT") {
            $siblings = PhDHelper::getChildren($parent);
            /* TODO:
             *   Maybe this isn't worth it.. but this, in theory, allows you 
             * to easily add new pages without needing to rebuild the entire 
             * section.
             */
            if (!file_exists($this->outputdir . $filename)) {
                $toc = array();

                foreach($siblings as $sid => $array) {
                    $toc[] = array($sid.$ext, empty($array["sdesc"]) ? $array["ldesc"] : $array["sdesc"]);
                }

                $parents = array();
                $p = $parent;
                while (($p = PhDHelper::getParent($p)) && $p != "ROOT") {
                    $parents[] = array($p.$ext, PhDHelper::getDescription($p, true));
                }

                $content = '<?php
$TOC = ' . var_export($toc, true) . ';
$PARENTS = ' . var_export($parents, true) . ';';

                file_put_contents($this->outputdir . $filename, $content);

                if ($GLOBALS["OPTIONS"]["verbose"] & VERBOSE_TOC_WRITING) {
                    v("Wrote TOC (%s)\n", $this->outputdir . $filename);
                }
            }

            $incl = 'include_once dirname(__FILE__) ."/toc/' .$parent. '.inc";';
            $up = array($this->getFilename($parent).$ext, PhDHelper::getDescription($parent, true));

            $prev = $this->createPrev($id, $parent, $siblings);
            $next = $this->createNext($id, $parent, $siblings);
        }

        $setup = array(
            "home" => array('index'.$ext, "PHP Manual"),
            "head" => array("UTF-8", $this->lang),
            "this" => array($id.$ext, PhDHelper::getDescription($id)),
            "up"   => $up,
            "prev" => $prev,
            "next" => $next,
        );
        $var = var_export($setup, true);

        /* Yes. This is scary. I know. */
        return '<?php
include_once $_SERVER[\'DOCUMENT_ROOT\'] . \'/include/shared-manual.inc\';
$TOC = array();
$PARENTS = array();
'.$incl.'
$setup = '.$var.';
$setup["toc"] = $TOC;
$setup["parents"] = $PARENTS;
manual_setup($setup);

manual_header();
?>
';
    }
    public function footer($id) {
        return "<?php manual_footer(); ?>";
    }
    protected function createPrev($id, $parent, $siblings) {
        $ext = '.' .$this->ext;
        if (!isset($siblings[$id])) {
            return array(null, null);
        }

        // Seek to $id
        while(list($tmp,) = each($siblings)) {
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
        if (file_exists($this->outputdir . "manual.php")) {
            copy($this->outputdir . "manual.php", $this->outputdir . "index.php");
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

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

