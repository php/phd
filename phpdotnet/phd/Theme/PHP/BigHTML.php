<?php
/*  $Id$ */

class bightml extends phpdotnet implements PhDTheme {
    public function __construct(array $IDs, $filename, $ext = "html") {
        parent::__construct($IDs, $filename, $ext, false);
        $this->stream = fopen("bightml.html", "w");
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
        fwrite($this->stream, "<html><body>");
    }
    public function footer() {
        fwrite($this->stream, "</body></html>");
    }

}


/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

