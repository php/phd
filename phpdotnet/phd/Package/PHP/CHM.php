<?php
namespace phpdotnet\phd;
/*  $Id$ */

class Package_PHP_CHM extends Package_PHP_ChunkedXHTML
{
    const DEFAULT_FONT = "Arial,10,0";
    const DEFAULT_TITLE = "PHP Manual";

    // Array to manual code -> HTML Help Code conversion
    // Code list: http://www.helpware.net/htmlhelp/hh_info.htm
    // Charset list: http://www.microsoft.com/globaldev/nlsweb/default.asp
    // Language code: http://www.unicode.org/unicode/onlinedat/languages.html
    // MIME preferred charset list: http://www.iana.org/assignments/character-sets
    // Font list: http://www.microsoft.com/office/ork/xp/three/inte03.htm
    private $LANGUAGES = array(
        "hk"    => array(
            "langcode" => "0xc04 Hong Kong Cantonese",
            "preferred_charset" => "CP950",
            "mime_charset_name" => "Big5",
            "preferred_font" => "MingLiu,10,0"
        ),
        "tw"    => array(
            "langcode" => "0x404 Traditional Chinese",
            "preferred_charset" => "CP950",
            "mime_charset_name" => "Big5",
            "preferred_font" => "MingLiu,10,0"
        ),
        "cs"    => array(
            "langcode" => "0x405 Czech",
            "preferred_charset" => "Windows-1250",
            "mime_charset_name" => "Windows-1250",
            "preferred_font" => self::DEFAULT_FONT,
        ),
        "da"    => array(
            "langcode" => "0x406 Danish",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT,
            "title" => "PHP Manualen"
        ),
        "de"    => array(
            "langcode" => "0x407 German (Germany)",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT,
            "title" => "PHP Handbuch",
        ),
        "el"    => array(
            "langcode" => "0x408 Greek",
            "preferred_charset" => "Windows-1253",
            "mime_charset_name" => "Windows-1253",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "en"    => array(
            "langcode" => "0x809 English (United Kingdom)",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT,
            "title" => "PHP Manual",
        ),
        "es"    => array(
            "langcode" => "0xc0a Spanish (International Sort)",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "fr"    => array(
            "langcode" => "0x40c French (France)",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT,
            "title" => "Manuel PHP"
        ),
        "fi"    => array(
            "langcode" => "0x40b Finnish",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "he"    => array(
            "langcode" => "0x40d Hebrew",
            "preferred_charset" => "Windows-1255",
            "mime_charset_name" => "Windows-1255",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "hu"    => array(
            "langcode" => "0x40e Hungarian",
            "preferred_charset" => "Windows-1250",
            "mime_charset_name" => "Windows-1250",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "it"    => array(
            "langcode" => "0x410 Italian (Italy)",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT,
            "title" => "Manuale PHP",
        ),
        "ja"    => array(
            "langcode" => "0x411 Japanese",
            "preferred_charset" => "CP932",
            "mime_charset_name" => "csWindows31J",
            "preferred_font" => "MS PGothic,10,0"
        ),
        "kr"    => array(
            "langcode" => "0x412 Korean",
            "preferred_charset" => "CP949",
            "mime_charset_name" => "EUC-KR",
            "preferred_font" => "Gulim,10,0"
        ),
        "nl"    => array(
            "langcode" => "0x413 Dutch (Netherlands)",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "no"    => array(
            "langcode" => "0x414 Norwegian (Bokmal)",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "pl"    => array(
            "langcode" => "0x415 Polish",
            "preferred_charset" => "Windows-1250",
            "mime_charset_name" => "Windows-1250",
            "preferred_font" => self::DEFAULT_FONT,
            "title" => "Podręcznik PHP",
        ),
        "pt_BR" => array(
            "langcode" => "0x416 Portuguese (Brazil)",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT,
            "title" => "Manual do PHP",
        ),
        "ro"    => array(
            "langcode" => "0x418 Romanian",
            "preferred_charset" => "Windows-1250",
            "mime_charset_name" => "Windows-1250",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "ru"    => array(
            "langcode" => "0x419 Russian",
            "preferred_charset" => "Windows-1251",
            "mime_charset_name" => "Windows-1251",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "sk"    => array(
            "langcode" => "0x41b Slovak",
            "preferred_charset" => "Windows-1250",
            "mime_charset_name" => "Windows-1250",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "sl"    => array(
            "langcode" => "0x424 Slovenian",
            "preferred_charset" => "Windows-1250",
            "mime_charset_name" => "Windows-1250",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "sv"    => array(
            "langcode" => "0x41d Swedish",
            "preferred_charset" => "Windows-1252",
            "mime_charset_name" => "Windows-1252",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "tr"    => array(
            "langcode" => "0x41f Turkish",
            "preferred_charset" => "Windows-1254",
            "mime_charset_name" => "Windows-1254",
            "preferred_font" => self::DEFAULT_FONT
        ),
        "zh"    => array(
            "langcode" => "0x804 Simplified Chinese",
            "preferred_charset" => "CP936",
            "mime_charset_name" => "gb2312",
            "preferred_font" => "simsun,10,0"
        )
    );

    // HTML Help Workshop project file
    protected $hhpStream;
    // CHM Table of contents
    protected $hhcStream;
    protected $currentTocDepth = 0;
    protected $lastContent = null;
    protected $toc;
    // CHM Index Map
    protected $hhkStream;
    // Project files Output directory
    protected $chmdir;

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-CHM");        
    }

    public function __destruct() {
        self::footerChm();

        fclose($this->hhpStream);
        fclose($this->hhcStream);
        fclose($this->hhkStream);

        parent::__destruct();
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
            parent::update($event, $val);
            break;

        case Render::STANDALONE:
            parent::update($event, $val);
            break;

        case Render::INIT:
            $this->loadVersionAcronymInfo();
            $this->chmdir = Config::output_dir() . strtolower($this->getFormatName()) . DIRECTORY_SEPARATOR;
            if(!file_exists($this->chmdir) || is_file($this->chmdir)) {
                mkdir($this->chmdir) or die("Can't create the CHM project directory");
            }
            $this->outputdir = Config::output_dir() . strtolower($this->getFormatName()) . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR;
            if(!file_exists($this->outputdir) || is_file($this->outputdir)) {
                mkdir($this->outputdir) or die("Can't create the cache directory");
            }
            $lang = Config::language();
            $this->hhpStream = fopen($this->chmdir . "php_manual_{$lang}.hhp", "w");
            $this->hhcStream = fopen($this->chmdir . "php_manual_{$lang}.hhc", "w");
            $this->hhkStream = fopen($this->chmdir . "php_manual_{$lang}.hhk", "w");

            file_put_contents($this->outputdir . "style.css", $this->fetchStylesheet() . PHP_EOL . 'body { padding : 3px;}');

            self::headerChm();
            break;
        case Render::VERBOSE:
            parent::update($event, $val);
            break;
        }
    }

    protected function appendChm($name, $ref, $hasChild) {
        if ($this->flags & Render::OPEN) {
            $charset = $this->LANGUAGES[Config::language()]["preferred_charset"];
            $name = htmlspecialchars(iconv('UTF-8', $charset, $name), ENT_QUOTES);

            $this->currentTocDepth++;
            fwrite($this->hhpStream, "{$ref}\n");
            fwrite($this->hhcStream, "{$this->offset(1)}<LI><OBJECT type=\"text/sitemap\">\n" .
                "{$this->offset(3)}<param name=\"Name\" value=\"" . $name . "\">\n" .
                "{$this->offset(3)}<param name=\"Local\" value=\"{$ref}\">\n" .
                "{$this->offset(2)}</OBJECT>\n");
            if ($hasChild) fwrite($this->hhcStream, "{$this->offset(2)}<ul>\n");
            fwrite($this->hhkStream,
                "      <LI><OBJECT type=\"text/sitemap\">\n" .
                "        <param name=\"Local\" value=\"{$ref}\">\n" .
                "        <param name=\"Name\" value=\"{$name}\">\n" .
                "      </OBJECT>\n");
        } elseif ($this->flags & Render::CLOSE) {
            if ($hasChild) {
                fwrite($this->hhcStream, "{$this->offset(2)}</ul>\n");
            }
            $this->currentTocDepth--;
        }
    }

    protected function headerChm() {
        $lang = Config::language();
        fwrite($this->hhpStream, '[OPTIONS]
Binary TOC=Yes
Compatibility=1.1 or later
Compiled file=php_manual_' . $lang . '.chm
Contents file=php_manual_' . $lang . '.hhc
Default Font=' . ($this->LANGUAGES[$lang]["preferred_font"] ? $this->LANGUAGES[$lang]["preferred_font"] : self::DEFAULT_FONT). '
Default topic=res' . DIRECTORY_SEPARATOR . 'index.html
Default Window=doc
Display compile progress=Yes
Enhanced decompilation=Yes
Full-text search=Yes
Index file=php_manual_' . $lang . '.hhk
Language=' . $this->LANGUAGES[$lang]["langcode"] . '
Title=' . (isset($this->LANGUAGES[$lang]["title"]) ? $this->LANGUAGES[$lang]["title"] : self::DEFAULT_TITLE) . '

[WINDOWS]
doc="' . (isset($this->LANGUAGES[$lang]["title"]) ? $this->LANGUAGES[$lang]["title"] : self::DEFAULT_TITLE) . '","php_manual_' . $lang . '.hhc","php_manual_' . $lang . '.hhk","res/index.html","res/index.html",,,,,0x33520,,0x70386e,,,,,,,,0

[FILES]
res' . DIRECTORY_SEPARATOR . 'style.css
');
        fwrite($this->hhcStream, '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
  <head>
    <meta name="generator" content="PhD">
    <!-- Sitemap 1.0 -->
  </head>
  <body>
    <object type="text/site properties">
      <param name="Window Styles" value="0x800227">
    </object>
    <ul>
');
        fwrite($this->hhkStream, '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
  <head>
    <meta name="generator" content="PhD">
    <!-- Sitemap 1.0 -->
  </head>
  <body>
    <object type="text/site properties">
      <param name="Window Styles" value="0x800227">
    </object>
    <ul>
');
    }

    protected function footerChm() {
        fwrite($this->hhcStream, "    </ul>\n" .
            "  </body>\n" .
            "</html>\n");
        fwrite($this->hhkStream, "    </ul>\n" .
            "  </body>\n" .
            "</html>\n");
    }

    public function appendData($data) {
        if ($this->lastContent) {
            $this->appendChm($this->lastContent["name"], $this->lastContent["reference"], $this->lastContent["hasChild"]);
        }
        $this->lastContent = null;
        return parent::appendData($data);
    }

    public function format_chunk($open, $name, $attrs, $props) {
        $this->collectContent($attrs);
        return parent::format_chunk($open, $name, $attrs, $props);
    }

    public function format_container_chunk($open, $name, $attrs, $props) {
        $this->collectContent($attrs);
        return parent::format_container_chunk($open, $name, $attrs, $props);
    }

    public function format_root_chunk($open, $name, $attrs) {
        $this->collectContent($attrs);
        return parent::format_root_chunk($open, $name, $attrs);
    }

    public function format_varlistentry($open, $name, $attrs) {
        $this->collectContent($attrs);
        $charset = $this->LANGUAGES[Config::language()]["preferred_charset"];
        $content = htmlspecialchars(iconv('UTF-8', $charset, $this->lastContent["name"]), ENT_QUOTES);

	if ($content) {
            fwrite($this->hhkStream,
                "      <LI><OBJECT type=\"text/sitemap\">\n" .
                "        <param name=\"Local\" value=\"{$this->lastContent["reference"]}\">\n" .
                "        <param name=\"Name\" value=\"{$content}\">\n" .
                "      </OBJECT>\n");
        }
        return false;
    }

    public function header($id) {
        $header = parent::header($id);
        // Add CSS link to <head>
				$pattern = '/(.*)(\r|\n|\r\n|\n\r)(.*)<\/head>/';
				$replacement = '$1  <link media="all" rel="stylesheet" type="text/css" href="style.css"/>$2$3</head>';

				$header = preg_replace($pattern, $replacement, $header);
        return $header;
    }

    private function collectContent($attrs) {
        if (isset($attrs[Reader::XMLNS_XML]["id"])) {
            $id = $attrs[Reader::XMLNS_XML]["id"];
            $this->lastContent = array(
                "name" => Format::getShortDescription($id),
                "reference" => "res" . DIRECTORY_SEPARATOR .
                    (Format::getFilename($id) ? Format::getFilename($id) : $id) . "." . $this->ext,
                "hasChild" => (count(Format::getChildrens($id)) > 0)
            );
        }
    }

    private function fetchStylesheet() {
        $stylesheet = file_get_contents("http://www.php.net/styles/site.css");
        if ($stylesheet) return $stylesheet;
        else {
            v("Stylesheet not fetched. Uses default rendering style.", E_USER_WARNING);
            return "";
        }
    }

    private function offset($offset) {
        $spaces = "";
        for ($i = 0; $i < $offset + 2 * $this->currentTocDepth; $i++)
            $spaces .= "  ";
        return $spaces;
    }

    public function format_link($open, $name, $attrs, $props) {
        $link = parent::format_link($open, $name, $attrs, $props);
        // Add title attribute so address can be seen in CHM files.
        $search = '`<a href="([^"]++)" class="link external">`';
        $replacement = '<a href="\1" class="link external" title="Link : \1">';
        $link = preg_replace($search, $replacement, $link);
        return $link;
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

