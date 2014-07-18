<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PHP_ChunkedXHTML extends Package_PHP_Web {
    private $nav = "";

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-Chunked-XHTML");
        $this->setExt(Config::ext() === null ? ".html" : Config::ext());
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
	
	
	/**
	 * Generate css stylesheet for the project
	 * @return string
	 */
	
	protected function generateStylesheet ()
	{
		$stylesheet = $this->loadStylesheets ();
		$stylesheet	= $this->processStylesheetResources ($stylesheet);
		return $stylesheet;
	}
	
	
	/**
	 * Load stylesheets from the config to include in the project stylesheet
	 * @return string
	 */
	
	protected function loadStylesheets ()
	{
		$stylesheet	= '';
		if (Config::css()) {
			foreach(Config::css() as $cssname) {
				$stylesheet .= $this->fetchStylesheet($cssname) . PHP_EOL;
			}
		} else {
			$stylesheet = $this->fetchStylesheet() . PHP_EOL;
		}
		return $stylesheet;
	}
	
	
	/* 
	 * Load and return stylesheet
	 * If the path is relative http://www.php.net/styles/ will be prepended
	 * @return string Stylesheet path
	 */
	
    protected function fetchStylesheet ($name = null) {
		
		// Set to default theme is none is specified
		
		if (is_null ($name))
		{
			$name	= 'theme-base.css';
		}
		
		// Use php URL if path is relative
		
		$url = parse_url ($name);
		
		if (!isset ($url['host']) || !$url['host'])
		{
			$name	= 'http://www.php.net/styles/' . $name;
		}
		
		// Load stylesheet
		
		$stylesheet = file_get_contents ($name);
       
		if ($stylesheet) {
            v('Loaded %s stylesheet.', $name, VERBOSE_MESSAGES);
            return $stylesheet;
        } else {
            v('Stylesheet %s not fetched. Uses default rendering style.', $name, E_USER_WARNING);
            return "";
        }
    }
	
	
	/**
	 * Save stylesheet resources such as images, font files etc locally
	 * @param string $stylesheet Stylesheet data
	 * @return string
	 */
	
	protected function processStylesheetResources ($stylesheet)
	{
		
		// Find referenced content - background images, sprites, etc.
		if (0 !== preg_match_all('`url\((([\'"]|)((?:(?!file:).)*?)\2)\)`', $stylesheet, $stylesheet_urls)) {

			foreach(array_unique($stylesheet_urls[3]) as $stylesheet_url) {

				// Parse the url, getting content from http://www.php.net if there is no scheme and host.
				if (False !== ($parsed_url = parse_url($stylesheet_url))) {

					if (!isset($parsed_url['scheme']) && !isset($parsed_url['host'])) {
						$url_content = file_get_contents('http://www.php.net/' . $stylesheet_url);
					} else {
						// Otherwise content is fully identified.
						$url_content = file_get_contents($stylesheet_url);
					}

					// Make sure the location to save the content is available.
					$content_filename = $this->outputdir . $parsed_url['path'];
					@mkdir(dirname($content_filename), 0777, true);

					// Save the referenced content to the new location.
					file_put_contents($content_filename, $url_content);

					// Force URLS to be relative to the "res" directory, but make them use the unix path separator as they will be processed by HTML.
					$relative_url = trim (substr (realpath ($content_filename), strlen (realpath ($this->outputdir))), DIRECTORY_SEPARATOR);
					$stylesheet = str_replace($stylesheet_url, str_replace(DIRECTORY_SEPARATOR, '/', $relative_url), $stylesheet);

					v('Saved content from css : %s.', $parsed_url['path'], VERBOSE_MESSAGES);
				} else {
					v('Unable to save content from css : %s.', $stylesheet_url, E_USER_WARNING);
				}
			}

		}
		
		return $stylesheet;
		
	}
	
	
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

