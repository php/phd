<?php
require_once $ROOT . '/themes/pear/pearweb.php';

/**
* PEAR theme for the normal-html-in-many-files version
*
* @package PhD
* @version CVS: $Id$
*/
class pearchunkedhtml extends pearweb
{
    private $nav = "";

    /**
    * URL prefix for all API doc link generated with <phd:pearapi>
    *
    * @var string
    */
    public $phd_pearapi_urlprefix = 'http://pear.php.net/package/';



    public function __construct(array $IDs, $ext = "html")
    {
        parent::__construct($IDs, $ext, true);
        $this->outputdir = PhDConfig::output_dir() . $this->ext . DIRECTORY_SEPARATOR;
        if (!file_exists($this->outputdir) || is_file($this->outputdir)) {
            mkdir($this->outputdir) or die("Can't create the cache directory");
        } else if (file_exists($this->outputdir . 'index.html')) {
            unlink($this->outputdir . 'index.html'); // preserve back-compat
        }
    }



    /**
    * Generates the header HTML for the given ID.
    * Full doctype, html head, begin of body tag and top navigation.
    *
    * @param string $id Page ID
    *
    * @return string Header HTML
    */
    public function header($id)
    {
        $title  = htmlspecialchars(PhDHelper::getDescription($id));
        $parent = PhDHelper::getParent($id);
        $this->next = $this->prev = $this->up = array(null, null);
        $strNext = $strPrev = '';

        if ($parent) {
            if ($parent !== 'ROOT') {
                $siblings = PhDHelper::getChildren($parent);
            } else {
                $siblings = array($id => PhDHelper::getSelf($id));
            }
            $this->prev = parent::createPrev($id, $parent, $siblings);
            if ($this->prev[0] === 'index') {
                $this->prev = array(null, null);
            }
            $this->next = parent::createNext($id, $parent, $siblings);
            if ($this->next[0] === 'index') {
                $this->next = array(null, null);
            }
            if ($parent !== 'index' && $parent !== 'ROOT') {
                $this->up = array(
                    $parent.".html",
                    htmlspecialchars(PhDHelper::getDescription($parent, false))
                );
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
        if ($this->up[0]) {
            $header .= '  <link rel="up" href="'.$this->up[0].'" title="'.$this->up[1].'" />
';
        }
        if ($this->prev[0]) {
            $strPrev = '<a href="' . $this->prev[0] . '" title="' . $this->prev[1] . '">Prev</a>';
            $header .= '  <link rel="prev" href="' . $this->prev[0] . '" title="' . $this->prev[1] . '" />
';
        }
        if ($this->next[0]) {
            $strNext = '<a href="' . $this->next[0] . '" title="' . $this->next[1] . '">Next</a>';
            $header .= '  <link rel="next" href="' . $this->next[0] . '" title="' . $this->next[1] . '" />
';
        }

        $header .= ' </head>
 <body>
';

        $nav = <<<NAV
<div class="navheader">
 <table width="100%" summary="Navigation header">
  <tr><th colspan="3" align="center">{$title}</th></tr>
  <tr>
   <td width="40%" align="left">{$strPrev}</td>
   <td width="20%"></td>
   <td width="40%" align="right">{$strNext}</td>
  </tr>
 </table>
 <hr/>
</div>

NAV;
        $header .= $nav . "<div id=\"body\">\n";
        return $header;
    }



    /**
    * Create the footer HTML for the given page id and return it.
    *
    * @param string $id Page ID
    *
    * @return string Footer HTML
    */
    public function footer($id)
    {
        $strPrev = $strNext = $strUp = '';
        if ($this->up[0]) {
            $strUp = '<a accesskey="h" href="' . $this->up[0] . '">' . $this->up[1] . '</a>';
        }
        if ($this->prev[0]) {
            $strPrev = '<a accesskey="p" href="' . $this->prev[0] . '">Prev</a>';
        }
        if ($this->next[0]) {
            $strNext = '<a accesskey="n" href="' . $this->next[0] . '">Next</a>';
        }
        $strHome = $id !== 'index' ? '<a accesskey="h" href="index.html">PEAR Manual</a>' : '';

        $nav = <<<NAV
<div class="navfooter">
 <hr />
 <table width="100%" summary="Navigation footer">
  <tr>
   <td width="40%" align="left">{$strPrev}</td>
   <td width="20%" align="center">{$strUp}</td>
   <td width="40%" align="right">{$strNext}</td>
  </tr>
  <tr>
   <td width="40%" align="left" valign="top">{$this->prev[1]}</td>
   <td width="20%" align="center">{$strHome}</td>
   <td width="40%" align="right" valign="top">{$this->next[1]}</td>
  </tr>
 </table>
</div>

NAV;
        return "</div>\n$nav</body></html>\n";
    }



    public function __destruct()
    {
        if (file_exists($this->outputdir . "guide.html")
            && !file_exists($this->outputdir . 'index.html')
        ) {
            copy($this->outputdir . "guide.html", $this->outputdir . "index.html");
        }
    }

}
