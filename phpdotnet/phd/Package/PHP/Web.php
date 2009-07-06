<?php
namespace phpdotnet\phd;

class Package_PHP_Web extends Package_PHP_XHTML {
    protected $formatname = "PHP-Web";
    protected $title = "PHP Manual";

    public function __construct() {
        parent::__construct();
        parent::registerFormatName($this->formatname);
        $this->chunked = true;
        $this->ext = "php";
    }

    public function close() {
        foreach ($this->fp as $fp) {
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
            $fp = array_pop($this->fp);
            fwrite($fp, $data);
            $this->writeChunk($this->CURRENT_CHUNK, $fp);
            fclose($fp);

            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            $this->fp[] = $fp = fopen("php://temp/maxmemory", "r+");
            fwrite($fp, $data);

            $this->flags ^= Render::OPEN;
        } else {
            $fp = end($this->fp);
            fwrite($fp, $data);
        }
    }

    public function writeChunk($id, $fp) {
        $filename = $this->outputdir . $id . '.' .$this->ext;

        rewind($fp);
        file_put_contents($filename, $this->header($id));
        file_put_contents($filename, $fp, FILE_APPEND);
        file_put_contents($filename, $this->footer($id), FILE_APPEND);
    }

    public function update($event, $val = null) {
        switch($event) {
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
            $this->outputdir = $tmp = Config::output_dir() . strtolower($this->getFormatName()) . '/';
            if (file_exists($tmp)) {
                if (!is_dir($tmp)) {
                    v("Output directory is a file?", E_USER_ERROR);
                }
            } else {
                if (!mkdir($tmp)) {
                    v("Can't create output directory", E_USER_ERROR);
                }
            }
            if ($this->getFormatName() == "PHP-Web") {
                if (!file_exists($this->outputdir . "toc") || is_file($this->outputdir . "toc")) {
                    mkdir($this->outputdir . "toc") or die("Can't create the toc directory");
                }
            }
            break;
        case Render::VERBOSE:
        	v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
        	break;
        }
    }

    public function header($id) {
        $ext = '.' . $this->ext;
        $parent = Format::getParent($id);
        $filename = "toc" . DIRECTORY_SEPARATOR . $parent . ".inc";
        $up = array("href" => null, "desc" => null);
        $incl = '';

        $next = $prev = array(null, null);
        if ($parent && $parent != "ROOT") {
            $siblings = Format::getChildrens($parent);
            if (!file_exists($this->outputdir . $filename)) {
                $toc = array();

                foreach($siblings as $sid) {
                    $toc[] = array(
                        Format::getFilename($sid).$ext, 
                        Format::getShortDescription($sid),
                    );
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
$PARENTS = ' . var_export($parents, true) . ';';

                file_put_contents($this->outputdir . $filename, $content);

                v("Wrote TOC (%s)", $this->outputdir . $filename, VERBOSE_TOC_WRITING);
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
            "home" => array('index'.$ext, $this->title),
            "head" => array("UTF-8", $this->lang),
            "this" => array($id.$ext, Format::getShortDescription($id)),
            "up"   => $up,
            "prev" => $prev,
            "next" => $next,
        );
        $var = var_export($setup, true);

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

}
