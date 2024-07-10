<?php
namespace phpdotnet\phd;

class Package_PHP_HowTo extends Package_PHP_Web {
    private $nav = "";

    public function __construct(Config $config) {
        parent::__construct($config);
        $this->registerFormatName("PHP-HowTo");
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
        case Render::STANDALONE:
        case Render::VERBOSE:
            parent::update($event, $val);
            break;
        case Render::INIT:
            $this->setOutputDir($this->config->output_dir() . strtolower($this->getFormatName()) . '/');
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
            break;
        }
    }

    public function header($id) {
        $parent = Format::getParent($id);
        $next = $prev = $up = array(null, null);
        if ($parent && $parent != "ROOT") {
            $siblings = Format::getChildren($parent);
            if ($nextId = Format::getNext($id)) {
                $next = array(
                    Format::getFilename($nextId) . $this->getExt(),
                    Format::getShortDescription($nextId),
                );
            }
            if ($prevId = Format::getPrevious($id)) {
                $prev = array(
                    Format::getFilename($prevId) . $this->getExt(),
                    Format::getShortDescription($prevId),
                );
            }
            $up = array($parent . $this->getExt(), Format::getShortDescription($parent));
        }

        $this->nav = <<<NAV
<div style="text-align: center;">
 <div class="prev" style="text-align: left; float: left;"><a href="{$prev[0]}">{$prev[1]}</a></div>
 <div class="next" style="text-align: right; float: right;"><a href="{$next[0]}">{$next[1]}</a></div>
 <div class="up"><a href="{$up[0]}">{$up[1]}</a></div>
</div>
NAV;

        // Do not put empty navigation container on the main page
        if (empty($prev[0]) && empty($next[0]) && empty($up[0])) {
            $nav = '';
            $this->nav = '';
        } else {
            $nav = $this->nav . "<hr>\n";
        }

        return "<?php require '../../../include/init.inc.php'; site_header();?>\n$nav";
    }

    public function footer($id) {
        // Do not put empty navigation container on the main page
        if (empty($this->nav)) {
            $nav = '';
        } else {
            $nav = "<hr>\n" . $this->nav . "<br>\n";
        }

        return "$nav<?php site_footer(); ?>\n";
    }
}


