<?php

require_once $ROOT . '/themes/pear/pearchunkedhtml.php';
class pearchm extends pearchunkedhtml {
    const DEFAULT_FONT = "Arial,10,0";
    const DEFAULT_TITLE = "PEAR Manual";

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
					   "title" => "PEAR Handbuch",
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
					   "title" => "PEAR Manual",
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
					   "title" => "Manuale PEAR",
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
					   "preferred_font" => self::DEFAULT_FONT
				   ),
		"pt_BR" => array(
					   "langcode" => "0x416 Portuguese (Brazil)",
					   "preferred_charset" => "Windows-1252",
					   "mime_charset_name" => "Windows-1252",
					   "preferred_font" => self::DEFAULT_FONT,
					   "title" => "Manual do PEAR",
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

    public function __construct(array $IDs, $ext = "html", $dir = "chm") {
        parent::__construct($IDs, $ext);
        $this->chmdir = PhDConfig::output_dir() . $dir . DIRECTORY_SEPARATOR;
		if(!file_exists($this->chmdir) || is_file($this->chmdir))
			mkdir($this->chmdir) or die("Can't create the CHM project directory");
        $this->outputdir = PhDConfig::output_dir() . $dir . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR;
		if(!file_exists($this->outputdir) || is_file($this->outputdir))
			mkdir($this->outputdir) or die("Can't create the cache directory");

		$lang = PhDConfig::language();
		$this->hhpStream = fopen($this->chmdir . "pear_manual_{$lang}.hhp", "w");
		$this->hhcStream = fopen($this->chmdir . "pear_manual_{$lang}.hhc", "w");
		$this->hhkStream = fopen($this->chmdir . "pear_manual_{$lang}.hhk", "w");

        foreach(array("reset-fonts", "style", "manual") as $name) {
            if (!file_exists($this->outputdir . "$name.css")) {
                file_put_contents($this->outputdir . "$name.css", $this->fetchStylesheet($name));
            }
        }

		self::headerChm();
    }

    public function __destruct() {
        self::footerChm();

        fclose($this->hhpStream);
        fclose($this->hhcStream);
        fclose($this->hhkStream);

        parent::__destruct();
    }

	protected function appendChm($name, $ref, $isChunk, $hasChild) {
		switch ($isChunk) {
			case PhDReader::OPEN_CHUNK :
				$this->currentTocDepth++;
				fwrite($this->hhpStream, "{$ref}\n");
				fwrite($this->hhcStream, "{$this->offset(1)}<li><object type=\"text/sitemap\">\n" .
					"{$this->offset(3)}<param name=\"Name\" value=\"" . htmlentities($name) . "\">\n" .
					"{$this->offset(3)}<param name=\"Local\" value=\"{$ref}\">\n" .
					"{$this->offset(2)}</object>\n");
				if ($hasChild) fwrite($this->hhcStream, "{$this->offset(2)}<ul>\n");
				fwrite($this->hhkStream, "      <li><object type=\"text/sitemap\">\n" .
					"          <param name=\"Local\" value=\"{$ref}\">\n" .
					"          <param name=\"Name\" value=\"" . htmlentities($name) . "\">\n" .
					"        </object>\n    </li>\n");
				break;
			case PhDReader::CLOSE_CHUNK :
				if ($hasChild) {
					fwrite($this->hhcStream, "{$this->offset(2)}</ul>\n");
				}
				$this->currentTocDepth--;
				break;
		}
	}

    protected function headerChm() {
		$lang = PhDConfig::language();
		fwrite($this->hhpStream, '[OPTIONS]
Compatibility=1.1 or later
Compiled file=pear_manual_' . $lang . '.chm
Contents file=pear_manual_' . $lang . '.hhc
Index file=pear_manual_' . $lang . '.hhk
Default Window=doc
Default topic=res\guide.html
Display compile progress=Yes
Full-text search=Yes
Language=' . $this->LANGUAGES[$lang]["langcode"] . '
Title=' . ($this->LANGUAGES[$lang]["title"] ? $this->LANGUAGES[$lang]["title"] : self::DEFAULT_TITLE) . '
Default Font=' . ($this->LANGUAGES[$lang]["preferred_font"] ? $this->LANGUAGES[$lang]["preferred_font"] : self::DEFAULT_FONT). '

[WINDOWS]
doc="' . ($this->LANGUAGES[$lang]["title"] ? $this->LANGUAGES[$lang]["title"] : self::DEFAULT_TITLE) . '","pear_manual_' . $lang . '.hhc","pear_manual_' . $lang . '.hhk","res\guide.html","res\guide.html",,,,,0x23520,,0x386e,,,,,,,,0

[FILES]
res\reset-fonts.css
res\style.css
res\manual.css
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

    public function appendData($data, $isChunk) {
        if ($this->lastContent)
			$this->appendChm($this->lastContent["name"], $this->lastContent["reference"],
				$isChunk, $this->lastContent["hasChild"]);
        $this->lastContent = null;
        return parent::appendData($data, $isChunk);
    }

    public function format_chunk($open, $name, $attrs, $props) {
		$this->collectContent($attrs);
		return parent::format_chunk($open, $name, $attrs, $props);
    }

    public function format_container_chunk($open, $name, $attrs, $props) {
		$this->collectContent($attrs);
		return parent::format_container_chunk($open, $name, $attrs, $props);
    }

    public function format_root_chunk($open, $name, $attrs, $props) {
		$this->collectContent($attrs);
		return parent::format_root_chunk($open, $name, $attrs, $props);
    }

    public function header($id) {
        $header = parent::header($id);
        // Add CSS link to <head>
        $header = ereg_replace('( *)</head>','\\1 <link media="all" rel="stylesheet" type="text/css" href="reset-fonts.css"/>
 <link media="all" rel="stylesheet" type="text/css" href="style.css"/>
 <link media="all" rel="stylesheet" href="manual.css"/>
\\1</head>', $header);
        return $header;
    }



    private function collectContent($attrs) {
		if (isset($attrs[PhDReader::XMLNS_XML]["id"])) {
			$id = $attrs[PhDReader::XMLNS_XML]["id"];
			$this->lastContent = array(
				"name" => PhDHelper::getDescription($id),
				"reference" => "res\\" .
					(PhDHelper::getFilename($id) ? PhDHelper::getFilename($id) : $id) . "." . $this->ext,
				"hasChild" => (count(PhDHelper::getChildren($id)) > 0)
			);
		}
    }

    private function fetchStylesheet($name) {
		$stylesheet = file_get_contents("http://pear.php.net/css/$name.css");
		if ($stylesheet) return $stylesheet;
		else {
			v("Stylesheet $name not fetched. Uses default rendering style.", E_USER_WARNING);
			return "";
		}
    }

    private function offset($offset) {
		$spaces = "";
		for ($i = 0; $i < $offset + 2 * $this->currentTocDepth; $i++)
			$spaces .= "  ";
		return $spaces;
    }

}
