<?php
/*  $Id$ */

require_once $ROOT . '/themes/php/phpweb.php';
class chunkedhtml extends phpweb {
    private $nav = "";

    public function __construct(array $IDs, $filename, $ext = "html") {
        phpdotnet::__construct($IDs, $filename, $ext, true);
	$this->outputdir = $GLOBALS['OPTIONS']['output_dir'] . $this->ext . DIRECTORY_SEPARATOR;
        if(!file_exists($this->outputdir) || is_file($this->outputdir)) mkdir($this->outputdir) or die("Can't create the cache directory");
        elseif (file_exists($this->outputdir . 'index.html')) unlink($this->outputdir . 'index.html'); // preserve back-compat
    }
    public function header($id) {
        $title = PhDHelper::getDescription($id, true);
        $header = <<< HEADER
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>$title</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
 </head>
 <body>
HEADER;
        $parent = PhDHelper::getParent($id);
        $next = $prev = $up = array(null, null);
        if ($parent && $parent != "ROOT") {
            $siblings = PhDHelper::getChildren($parent);
            $prev = phpweb::createPrev($id, $parent, $siblings);
            $next = phpweb::createNext($id, $parent, $siblings);
            $up = array($parent.".html", PhDHelper::getDescription($parent, false));
        }

        $nav = <<< NAV
<div style="text-align: center;">
 <div class="prev" style="float: left;"><a href="{$prev[0]}">{$prev[1]}</a></div>
 <div class="next" style="float: right;"><a href="{$next[0]}">{$next[1]}</a></div>
 <div class="up"><a href="{$up[0]}">{$up[1]}</a></div>
 <div class="home"><a href="index.html">PHP Manual</a></div>
</div>
NAV;
        $header .= $nav . "<hr />";
        $this->nav = $nav;
        return $header;
    }
    public function footer($id) {
        $nav = $this->nav;
        $this->nav = "";
        return "<hr />$nav</body></html>\n";
    }
    public function __destruct() {
        if (file_exists($this->outputdir . "manual.html") && !file_exists($this->outputdir . 'index.html')) {
            copy($this->outputdir . "manual.html", $this->outputdir . "index.html");
        }
    }
}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

