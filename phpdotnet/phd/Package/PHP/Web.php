<?php
namespace phpdotnet\phd;

class Package_PHP_Web extends Package_PHP_XHTML {

    protected $sources = array();

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-Web");
        $this->setTitle("PHP Manual");
        $this->setChunked(true);
        $this->setExt(Config::ext() === null ? ".php" : Config::ext());
    }

    public function close() {
        foreach ($this->getFileStream() as $fp) {
            fclose($fp);
        }
    }

    public function __destruct() {
        $this->close();
    }

    public function appendData($data) {
    	if ($this->appendToBuffer) {
    		$this->buffer .= $data;

    		return;
    	} elseif ($this->flags & Render::CLOSE) {
            $fp = $this->popFileStream();
            fwrite($fp, $data);
            $this->writeChunk($this->CURRENT_CHUNK, $fp);
            fclose($fp);

            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            $fp = fopen("php://temp/maxmemory", "r+");
            fwrite($fp, $data);
            $this->pushFileStream($fp);

            $this->flags ^= Render::OPEN;
        } else {
            $fp = $this->getFileStream();
            fwrite(end($fp), $data);
        }
    }

    public function writeChunk($id, $fp) {
        $filename = $this->getOutputDir() . $id . $this->getExt();

        rewind($fp);
        file_put_contents($filename, $this->header($id));
        file_put_contents($filename, $fp, FILE_APPEND);
        file_put_contents($filename, $this->footer($id), FILE_APPEND);
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::FINALIZE:
            $this->writeJsonIndex();
            break;

        case Render::CHUNK:
            $this->flags = $val;
            break;

        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap(static::getDefaultElementMap());
                $this->registerTextMap(static::getDefaultTextMap());
            }
            break;

        case Render::INIT:
            $this->loadVersionAcronymInfo();
            $this->loadSourcesInfo();
            $this->setOutputDir(Config::output_dir() . strtolower($this->getFormatName()) . '/');
            $this->postConstruct();
            if (file_exists($this->getOutputDir())) {
                if (!is_dir($this->getOutputDir())) {
                    v("Output directory is a file?", E_USER_ERROR);
                }
            } else {
                if (!mkdir($this->getOutputDir(), 0777, true)) {
                    v("Can't create output directory", E_USER_ERROR);
                }
            }
            if ($this->getFormatName() == "PHP-Web") {
                if (!Config::no_toc() && is_dir($this->getOutputDir() . 'toc')) {
                    removeDir($this->getOutputDir() . 'toc');
                }
                if (!file_exists($this->getOutputDir() . "toc") || is_file($this->getOutputDir() . "toc")) {
                    mkdir($this->getOutputDir() . "toc", 0777, true) or die("Can't create the toc directory");
                }
            }
            if (Config::css()) {
                $this->fetchStylesheet();
            }
            break;
        case Render::VERBOSE:
        	v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
        	break;
        }
    }

    public function header($id) {
        static $written_toc = array();
        $ext = $this->getExt();
        $parent = Format::getParent($id);
        $filename = "toc" . DIRECTORY_SEPARATOR . $parent . ".inc";
        $up = array(0 => null, 1 => null);
        $incl = '';

        $next = $prev = array(null, null);
        if ($parent && $parent != "ROOT") {
            $siblings = Format::getChildren($parent);
            if (!isset($written_toc[$filename])) {
                $written_toc[$filename] = true;

                $toc = $toc_deprecated = array();

                foreach($siblings as $sid) {
                    $sibling_short_desc = Format::getShortDescription($sid);

                    $entry = array(
                        Format::getFilename($sid).$ext,
                        $sibling_short_desc,
                    );

                    if ($this->deprecationInfo($sibling_short_desc) !== false) {
                        $toc_deprecated[] = $entry;
                    } else {
                        $toc[] = $entry;
                    }
                }

                $parents = array();
                $p = $parent;
                while (($p = Format::getParent($p)) && $p != "ROOT") {
                    $parents[] = array(
                        Format::getFilename($p).$ext,
                        Format::getShortDescription($p),
                    );
                }

                $content = '<?php
$TOC = ' . var_export($toc, true) . ';
$TOC_DEPRECATED = ' . var_export($toc_deprecated, true) . ';
$PARENTS = ' . var_export($parents, true) . ';';

                file_put_contents($this->getOutputDir() . $filename, $content);

                v("Wrote TOC (%s)", $this->getOutputDir() . $filename, VERBOSE_TOC_WRITING);
            }

            $incl = 'include_once dirname(__FILE__) ."/toc/' .$parent. '.inc";';
            $up = array(Format::getFilename($parent).$ext, Format::getShortDescription($parent));

            if ($prevId = Format::getPrevious($id)) {
                $prev = array(
                    Format::getFilename($prevId).$ext,
                    Format::getShortDescription($prevId),
                );
            }
            if ($nextId = Format::getNext($id)) {
                $next = array(
                    Format::getFilename($nextId).$ext,
                    Format::getShortDescription($nextId),
                );
            }
        }

        $setup = array(
            "home" => array('index'.$ext, $this->getTitle()),
            "head" => array("UTF-8", $this->lang),
            "this" => array($id.$ext, Format::getShortDescription($id)),
            "up"   => $up,
            "prev" => $prev,
            "next" => $next,
            "alternatives" => $this->cchunk["alternatives"],
            "source" => $this->sourceInfo($id),
        );
        if ($this->getChildren($id)) {
            $lang = Config::language();
            $setup["extra_header_links"] = array(
                "rel"   => "alternate",
                "href"  => "/manual/{$lang}/feeds/{$id}.atom",
                "type"  => "application/atom+xml",
            );
        }
        $var = var_export($setup, true);

        return '<?php
include_once $_SERVER[\'DOCUMENT_ROOT\'] . \'/include/shared-manual.inc\';
$TOC = array();
$TOC_DEPRECATED = array();
$PARENTS = array();
'.$incl.'
$setup = '.$var.';
$setup["toc"] = $TOC;
$setup["toc_deprecated"] = $TOC_DEPRECATED;
$setup["parents"] = $PARENTS;
manual_setup($setup);

manual_header();
?>
';
    }

    public function footer($id) {
        return "<?php manual_footer(); ?>";
    }

    protected function writeJsonIndex() {
        v("Writing search indexes..", VERBOSE_FORMAT_RENDERING);
        $ids = array();
        $desc = array();
        foreach($this->indexes as $id => $index) {
            $ids[] = array($index["sdesc"], $index["filename"], $index["element"]);
            $desc[$id] = $index["ldesc"];
        }
        file_put_contents($this->getOutputDir() . "search-index.json", json_encode($ids));
        file_put_contents($this->getOutputDir() . "search-description.json", json_encode($desc));
        v("Index written", VERBOSE_FORMAT_RENDERING);
    }

    public function loadSourcesInfo() {
        $this->sources = self::generateSourcesInfo(Config::phpweb_sources_filename());
    }

    public static function generateSourcesInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        if (!is_file($filename)) {
            v("Can't find sources file (%s), skipping!", $filename, E_USER_NOTICE);
            return array();
        }

        $r = new \XMLReader;
        if (!$r->open($filename)) {
            v("Can't open the sources file (%s)", $filename, E_USER_ERROR);
        }
        $info = array();
        $r->read();
        while($r->read()) {
            if (
                $r->moveToAttribute("id")
                && ($id = $r->value)
                && $r->moveToAttribute("lang")
                && ($lang = $r->value)
                && $r->moveToAttribute("path")
                && ($path = $r->value)
            ) {
                $info[$id] = array("lang" => $lang, "path" => $path);
                $r->moveToElement();
            }
        }
        $r->close();
        return $info;
    }

    public function sourceInfo($id) {
        if (!isset($this->sources[$id])) {
            v("Missing source for: %s", $id, E_USER_WARNING);
        }
        return isset($this->sources[$id]) ? $this->sources[$id] : null;
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
