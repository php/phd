<?php
namespace phpdotnet\phd;

class Package_PHP_ChunkedXHTML extends Package_PHP_Web {
    private $nav = "";

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-Chunked-XHTML");
        $this->setExt(Config::ext() === null ? ".html" : Config::ext());

        Config::setCss(array(
            'http://www.php.net/styles/theme-base.css',
            'http://www.php.net/styles/theme-medium.css',
        ));
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function header($id) {
        $title = Format::getLongDescription($id);
        static $cssLinks = null;
        if ($cssLinks === null) {
            $cssLinks = $this->createCSSLinks();
        }
        $header = <<<HEADER
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>$title</title>
{$cssLinks}
 </head>
 <body class="docs">
HEADER;
        $next = $prev = $up = array("href" => null, "desc" => null);
        $nextLink = $prevLink = $upLink = '';
        if ($prevId = Format::getPrevious($id)) {
            $prev = array(
                "href" => $this->getFilename($prevId) . $this->getExt(),
                "desc" => $this->getShortDescription($prevId),
            );
            $prevLink = "<li style=\"float: left;\"><a href=\"{$prev["href"]}\">« {$prev["desc"]}</a></li>";
        }
        if ($nextId = Format::getNext($id)) {
            $next = array(
                "href" => $this->getFilename($nextId) . $this->getExt(),
                "desc" => $this->getShortDescription($nextId),
            );
            $nextLink = "<li style=\"float: right;\"><a href=\"{$next["href"]}\">{$next["desc"]} »</a></li>";
        }
        if ($parentId = Format::getParent($id)) {
            $up = array(
                "href" => $this->getFilename($parentId) . $this->getExt(),
                "desc" => $this->getShortDescription($parentId),
            );
            if ($up['href'] != 'index.html') {
                $upLink = "<li><a href=\"{$up["href"]}\">{$up["desc"]}</a></li>";
            }
        }

        $nav = <<<NAV
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner clearfix">
    <ul class="nav" style="width: 100%">
      {$prevLink}
      {$nextLink}
    </ul>
  </div>
</div>
<div id="breadcrumbs" class="clearfix">
  <ul class="breadcrumbs-container">
    <li><a href="index.html">PHP Manual</a></li>
    {$upLink}
    <li>{$title}</li>
  </ul>
</div>
<div id="layout">
  <div id="layout-content">
NAV;
        $header .= $nav;
        return $header;
    }

    public function footer($id)
    {
        return '</div></div></body></html>';
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

