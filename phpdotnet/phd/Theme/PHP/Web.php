<?php
/*  $Id$ */

class phpweb extends phpdotnet implements PhDTheme {
    protected $streams = array();
    protected $writeit = false;


    public function writeChunk($id, $stream) {
        rewind($stream);
        file_put_contents($this->ext."/$id.".$this->ext, $this->header($id));
        file_put_contents($this->ext."/$id.".$this->ext, $stream, FILE_APPEND);
        file_put_contents($this->ext."/$id.".$this->ext, $this->footer($id), FILE_APPEND);
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
        $parent = PhDHelper::getParent($id);
        foreach(PhDHelper::getContainer($parent) as $k => $v) {
            if ($k == "parent") { continue; }
            $toc .= sprintf("array('%s.php', '%s'),\n", $k, addslashes(PhDHelper::getDescription($k, false)));
        }

        /* Yes. This is scary. I know. */
        return '<?php
include_once $_SERVER[\'DOCUMENT_ROOT\'] . \'/include/shared-manual.inc\';
manual_setup(
    array(
        "home" => array("index.php", "PHP Manual"),
        "head" => array("UTF-8", "en"),
        "this" => array("'.$id.'.php", "'.addslashes(PhDHelper::getDescription($id)).'"),
        "prev" => array("function.previous.php", "prevous"),
        "next" => array("function.next.php", "next"),
        "up"   => array("'.$this->getFilename($parent).'.'.$this->ext.'", "'.addslashes(PhDHelper::getDescription($parent, true)). '"),
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

