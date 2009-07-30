<?php
namespace phpdotnet\phd;
/* $Id$ */

class PI_DBHTMLHandler extends PIHandler {
    private $attrs = array(
        "background-color"              => "",
        "bgcolor"                       => "",
        "cellpadding"                   => "",
        "cellspacing"                   => "",
        "class"                         => "",
        "dir"                           => "",
        "filename"                      => "",
        "funcsynopsis-style"            => "",
        "img.src.path"                  => "",
        "label-width"                   => "",
        "linenumbering.everyNth"        => "",
        "linenumbering.separator"       => "",
        "linenumbering.width"           => "",
        "list-presentation"             => "",
        "list-width"                    => "",
        "row-height"                    => "",
        "start"                         => "",
        "stop-chunking"                 => "",
        "table-summary"                 => "",
        "table-width"                   => "",
        "term-presentation"             => "",
        "term-separator"                => "",
        "term-width"                    => "",
        "toc"                           => "",
    );

    public function __construct($format) {
        parent::__construct($format);
    }

    public function parse($target, $data) {
        $pattern = "/(?<attr>[\w]+[\w\-\.]*)[\s]*=[\s]*\"(?<value>[^\"]*)\"/";
        preg_match_all($pattern, $data, $matches);
        for ($i = 0; $i < count($matches["attr"]); $i++) {
            $attr = trim($matches["attr"][$i]);
            $value = trim($matches["value"][$i]);
            $this->setAttribute($attr, $value); 
        }
        //Hack for stop-chunking
        if ($data == "stop-chunking") {
            $this->setAttribute("stop-chunking", true);
        }
    }

    public function setAttribute($attr, $value) {
        if (isset($this->attrs[$attr])) {
            $this->attrs[$attr] = $value;
        }
    }

    public function getAttribute($attr) {
        return isset($this->attrs[$attr]) ? $this->attrs[$attr] : false;
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

