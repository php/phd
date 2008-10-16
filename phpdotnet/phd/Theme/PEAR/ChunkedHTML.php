<?php

require_once $ROOT . '/themes/pear/pearweb.php';
class pearchunkedhtml extends pearweb {
    private $nav = "";

    public function __construct(array $IDs, $ext = "html") {
        parent::__construct($IDs, $ext, true);
        $this->outputdir = $GLOBALS['OPTIONS']['output_dir'] . $this->ext . DIRECTORY_SEPARATOR;
        if(!file_exists($this->outputdir) || is_file($this->outputdir)) mkdir($this->outputdir) or die("Can't create the cache directory");
        elseif (file_exists($this->outputdir . 'index.html')) unlink($this->outputdir . 'index.html'); // preserve back-compat
    }
    public function header($id) {
        $title = PhDHelper::getDescription($id, true);
        $parent = PhDHelper::getParent($id);
        $this->next = $this->prev = $this->up = array(null, null);
        if ($parent && $parent != "ROOT") {
            $siblings = PhDHelper::getChildren($parent);
            $this->prev = pearweb::createPrev($id, $parent, $siblings);
            if ($this->prev[0] == 'index') {
                $this->prev = array(null, null);
            }
            $this->next = pearweb::createNext($id, $parent, $siblings);
            if ($this->next[0] == 'index') {
                $this->next = array(null, null);
            }
            if ($parent != 'index') {
                $this->up = array($parent.".html", PhDHelper::getDescription($parent, false));
            }
        }
        $header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
  <title>' . $title . '</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <meta name="generator" content="PhD" />
  <link rel="start" href="index.html" title="PEAR Manual" />
';
    if ($this->up[0])
        $header .= '  <link rel="up" href="'.$this->up[0].'" title="'.$this->up[1].'" />
';
    if ($this->prev[0])
        $header .= '  <link rel="prev" href="'.$this->prev[0].'" title="'.$this->prev[1].'" />
';
    if ($this->next[0])
        $header .= '  <link rel="next" href="'.$this->next[0].'" title="'.$this->next[1].'" />
';
$header .= ' </head>
 <body>
';
        $nav = <<<NAV
<div class="navheader">
 <table width="100%" summary="Navigation header">
  <tr><th colspan="3" align="center">{$title}</th></tr>
  <tr>
   <td width="40%" align="left"><a href="{$this->prev[0]}">Prev</a></td>
   <td width="20%"></td>
   <td width="40%" align="right"><a href="{$this->next[0]}">Next</a></td>
  </tr>
 </table>
 <hr/>
</div>

NAV;
        $header .= $nav . "<div id=\"body\">\n";
        return $header;
    }

    public function footer($id) {
        //FIXME: don't print empty links
        $nav = <<<NAV
<div class="navfooter">
 <hr />
 <table width="100%" summary="Navigation footer">
  <tr>
   <td width="40%" align="left"><a accesskey="p" href="{$this->prev[0]}">Prev</a></td>
   <td width="20%" align="center"><a accesskey="h" href="{$this->up[0]}">{$this->up[1]}</a></td>
   <td width="40%" align="right"><a accesskey="n" href="{$this->next[0]}">Next</a></td>
  </tr>
  <tr>
   <td width="40%" align="left" valign="top">{$this->prev[1]}</td>
   <td width="20%" align="center"><a accesskey="h" href="index.html">PEAR Manual</a></td>
   <td width="40%" align="right" valign="top">{$this->next[1]}</td>
  </tr>
 </table>
</div>

NAV;
        return "</div>\n$nav</body></html>\n";
    }
    public function __destruct() {
        if (file_exists($this->outputdir . "guide.html") && !file_exists($this->outputdir . 'index.html')) {
            copy($this->outputdir . "guide.html", $this->outputdir . "index.html");
        }
    }
}
