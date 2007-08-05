<?php
/*  $Id$ */

class PHPPhDFormat extends XHTMLPhDFormat {
    protected $CURRENT_ID = "";
    protected $ext;

    public function __construct(PhDReader $reader, array $IDs, array $IDMap, $ext = "php") {
        parent::__construct($reader, $IDs, $IDMap, $ext);
    }
    public function format_function($open, $name) {
        $func = $this->readContent($name);
        $link = str_replace(array("_", "::", "->"), "-", $func);

        if (!substr_compare($this->CURRENT_ID, $link, -strlen($link)) || !($filename = PhDFormat::getFilename("function.$link"))) {
            return sprintf("<b>%s()</b>", $func);
        }

        return sprintf('<a href="%s.%s" class="function">%s()</a>', $filename, $this->ext, $func);
    }
    public function format_container_chunk($open, $name) {
        $this->CURRENT_ID = $id = PhDFormat::getID();
        if ($open) {
            return "<div>";
        }
        $chunks = PhDFormat::getContainer($id);
        $content = "";
        if (count($chunks) > 1) {
            $content = '<ul class="chunklist chunklist_'.$name.'">';
            if ($name == "reference") {
                foreach($chunks as $chunkid => $junk) {
                    if ($chunkid == "parent") { continue; }
                    $content .= sprintf('<li><a href="%s.%s">%s</a> — %s</li>', $chunkid, $this->ext, PhDFormat::getDescription($chunkid, false), PhDFormat::getDescription($chunkid, true));
                }
            } else {
                foreach($chunks as $chunkid => $junk) {
                    if ($chunkid == "parent") { continue; }
                    $content .= sprintf('<li><a href="%s.%s">%s</a></li>', $chunkid, $this->ext, PhDFormat::getDescription($chunkid, true));
                }
            }
            $content .= "</ul>\n";
        }
        $content .= "</div>\n";
        
        return $content;
    }
}
/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

