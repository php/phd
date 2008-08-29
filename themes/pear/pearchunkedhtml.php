<?php

require_once $ROOT . '/themes/pear/pearweb.php';
class pearchunkedhtml extends pearweb {
    private $nav = "";

    public function __construct(array $IDs, $ext = "html") {
        parent::__construct($IDs, $ext, true);
        $this->outputdir = PhDConfig::output_dir() . $this->ext . DIRECTORY_SEPARATOR;
        if(!file_exists($this->outputdir) || is_file($this->outputdir)) mkdir($this->outputdir) or die("Can't create the cache directory");
        elseif (file_exists($this->outputdir . 'index.html')) unlink($this->outputdir . 'index.html'); // preserve back-compat
    }
    public function header($id) {
        $title = PhDHelper::getDescription($id, true);
        $parent = PhDHelper::getParent($id);
        $next = $prev = $up = array(null, null);
        if ($parent && $parent != "ROOT") {
            $siblings = PhDHelper::getChildren($parent);
            $prev = pearweb::createPrev($id, $parent, $siblings);
            $next = pearweb::createNext($id, $parent, $siblings);
            $up = array($parent.".html", PhDHelper::getDescription($parent, false));
        }
        $header = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>' . $title . '</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <meta name="generator" content="PhD" />
  <link rel="start" href="guide.html" title="PEAR Manual" />
';
    if ($up[0])
        $header .= '  <link rel="up" href="'.$up[0].'" title="'.$up[1].'" />
';
    if ($prev[0])
        $header .= '  <link rel="prev" href="'.$prev[0].'" title="'.$prev[1].'" />
';
    if ($next[0])
        $header .= '  <link rel="next" href="'.$next[0].'" title="'.$next[1].'" />
';
$header .= ' </head>
 <body>
';
        
        $nav = <<<NAV
<table style="background-color: #E0E0E0; width: 100%; font-size: 75%; white-space: nowrap; border-collapse:collapse; border-spacing:0pt;" align="center">
 <tbody>
 <tr valign="top">
  <td colspan="2" height="1"><hr style="height: 1px; margin: 0px;"></td>
 </tr>
 <tr valign="top">
  <td align="left" style="padding: 0px 10px;">
   <a href="{$prev[0]}">{$prev[1]}</a>
  </td>
  <td align="right" style="padding: 0px 10px;">
   <a href="{$next[0]}">{$next[1]}</a>
  </td>
 </tr>
 <tr valign="top">
  <td colspan="2" height="1"><hr style="height: 1px; margin: 0px;"></td>
 </tr>
 <tr valign="top">
  <td colspan="2" align="center"><a href="{$up[0]}">{$up[1]}</a></td>
 </tr>
 <tr valign="top">
  <td colspan="2" height="1"><hr style="height: 1px; margin: 0px;"></td>
 </tr>
 <tr valign="top">
  <td colspan="2" align="center"><a href="guide.html">PEAR Manual</a></td>
 </tr>
 <tr valign="top">
  <td colspan="2" height="1"><hr style="height: 1px; margin: 0px;"></td>
 </tr>
 </tbody>
</table>
NAV;
        $header .= $nav . "<hr /><div id=\"doc3\"><div id=\"body\">";
        $this->nav = $nav;
        return $header;
    }
    public function footer($id) {
        $nav = $this->nav;
        $this->nav = "";
        return "<hr /></div></div>$nav</body></html>\n";
    }
    public function __destruct() {
        if (file_exists($this->outputdir . "guide.html") && !file_exists($this->outputdir . 'index.html')) {
            copy($this->outputdir . "guide.html", $this->outputdir . "index.html");
        }
    }
}
