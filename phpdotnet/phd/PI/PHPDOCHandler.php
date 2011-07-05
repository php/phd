<?php
namespace phpdotnet\phd;
/* $Id: PHPDOCHandler.php 293260 2010-01-08 10:41:19Z rquadling $ */

class PI_PHPDOCHandler extends PIHandler {

    public function __construct($format) {
        parent::__construct($format);
    }

    public function parse($target, $data) {
        $pattern = "/(?<attr>[\w]+[\w\-\.]*)[\s]*=[\s]*\"(?<value>[^\"]*)\"/";

        preg_match($pattern, $data, $matches);
        switch($matches["attr"]) {
            case "print-version-for":
                // FIXME: Figureout a way to detect the current language (for unknownversion)
                return $this->format->autogenVersionInfo($matches["value"], "en");
            default:
                trigger_error("Don't know how to handle {$matches["attr"]}", E_USER_WARNING);
                break;
        }
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/


