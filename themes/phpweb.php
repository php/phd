<?php
/*  $Id$ */

class phpweb extends PHPPhDFormat {
    protected $streams = array();
    protected $writeit = false;
    protected $CURRENT_ID = "";
    private   $versions = array();

    public function __construct(PhDReader $reader, array $IDs, array $IDMap, $filename, $ext = "php") {
        parent::__construct($reader, $IDs, $IDMap, $ext);
        $r = new XMLReader;
        if (!$r->open($filename)) {
            throw new Exception;
        }
        $versions = array();
        while($r->read()) {
            if (
                $r->moveToAttribute("name")
                && ($funcname = str_replace(
                    array("::", "->", "__", "_", '$'),
                    array("-",  "-",  "-",  "-", ""),
                    $r->value))
                && $r->moveToAttribute("from")
                && ($from = $r->value)
            ) {
                $versions[strtolower($funcname)] = $from;
                $r->moveToElement();
            }
        }
        $r->close();
        $this->versions = $versions;
    }
    public function versionInfo($funcname) {
        $funcname = str_replace(
                array("::", "->", "__", "_", '$'),
                array("-",  "-",  "-",  "-", ""),
                strtolower($funcname));
        return isset($this->versions[$funcname]) ? $this->versions[$funcname] : "No version information available, might be only in CVS";
    }
    public function format_refnamediv($open, $root) {
        while ($child = PhDFormat::getNextChild($root)) {
            $name = $child["name"];
            switch($name) {
            case "refname":
                $refname = $this->readContent($name);
                break;
            case "refpurpose":
                $refpurpose = $this->readContent($name);
                break;
            }
        }
        $ver = $this->versionInfo($refname);
        
        return sprintf('<div class="refnamediv"><h1 class="refname">%s</h1><p class="verinfo">(%s)</p><p class="refpurpose">%1$s — %s</p></div>', $refname, $ver, $refpurpose);
    }
    public function writeChunk($id, $stream) {
        rewind($stream);
        file_put_contents("cache/$id.php", $this->header($id));
        file_put_contents("cache/$id.php", $stream, FILE_APPEND);
        file_put_contents("cache/$id.php", $this->footer($id), FILE_APPEND);
    }
    public function appendData($data, $isChunk) {
        switch($isChunk) {
        case PhDReader::CLOSE_CHUNK:
            $id = $this->CURRENT_ID;
            $stream = array_pop($this->streams);
            $retval = fwrite($stream, $data);
            $this->writeChunk($id, $stream);
            fclose($stream);
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
        $toc = "";
        $parent = PhDFormat::getParent($id);
        foreach(PhDFormat::getContainer($parent) as $k => $v) {
            if ($k == "parent") { continue; }
            $toc .= sprintf("array('%s.php', '%s'),\n", $k, addslashes(PhDFormat::getDescription($k, false)));
        }

        /* Yes. This is scary. I know. */
        return '<?php
include_once $_SERVER[\'DOCUMENT_ROOT\'] . \'/include/shared-manual.inc\';
manual_setup(
    array(
        "home" => array("index.php", "PHP Manual"),
        "head" => array("UTF-8", "en"),
        "this" => array("'.$id.'.php", "'.addslashes(PhDFormat::getDescription($id)).'"),
        "prev" => array("function.previous.php", "prevous"),
        "next" => array("function.next.php", "next"),
        "up"   => array("'.$this->getFilename($parent).'.'.$this->ext.'", "'.addslashes(PhDFormat::getDescription($parent, true)). '"),
        "toc"  => array('.$toc.'),
    )
);

manual_header();
?>
';
    }
    public function footer($id) {
        return "<?php manual_footer(); ?>";
    }
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

