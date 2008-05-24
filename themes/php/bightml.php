<?php
/*  $Id$ */

require_once $ROOT . '/themes/php/phpdotnet.php';
class bightml extends phpdotnet {
    public function __construct(array $IDs, $filename, $ext = "html") {
        parent::__construct($IDs, $filename, $ext, false);
        $this->stream = fopen($GLOBALS['OPTIONS']['output_dir'] . "bightml.html", "w");
        self::header();
    }
    public function appendData($data, $isChunk) {
        if($isChunk) {
            $data .= "<hr />";
        }
        return fwrite($this->stream, $data);
    }
    public function __destruct() {
        self::footer();
        fclose($this->stream);
    }
    public function header() {
        fwrite($this->stream, '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>PHP Manual</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
 </head>
 <body>');

    }
    public function footer() {
        fwrite($this->stream, "</body></html>");
    }
    public function format_qandaset($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["qandaentry"] = array();
            $this->ostream = $this->stream;
            $this->stream = fopen("php://temp/maxmemory", "r+");
            return '';
        }

        $stream = $this->stream;
        $this->stream = $this->ostream;
        unset($this->ostream);
        rewind($stream);

        return parent::qandaset($stream);
    }
}


/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

