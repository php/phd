<?php
namespace phpdotnet\phd;
/* $Id$ */

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
            case "generate-index-for":
                switch($matches["value"]) {
                    case "function":
                    case "refentry":
                        $tmp = $this->format->getRefs();
                        $ret   = "";
                        $refs = array();
                        $info = array();
                        foreach($tmp as $id) {
                            $filename = $this->format->createLink($id, $desc);
                            $refs[$filename] = $desc;
                            $info[$filename] = array($this->format->getLongDescription($id, $islong), $islong);
                        }

                        natcasesort($refs);

                        // Workaround for 5.3 that doesn't allow func()[index]
                        $current = current($refs);
                        $char = $current[0];

                        $ret = "<ul class='gen-index index-for-{$matches["value"]}'>";
                        $ret .= "<li class='gen-index index-for-{$char}'>$char<ul id='{$matches["value"]}-index-for-{$char}'>\n";
                        foreach($refs as $filename => $data) {
                            if ($data[0] != $char && strtolower($data[0]) != $char) {
                                $char = strtolower($data[0]);
                                $ret .= "</ul></li>\n";
                                $ret .= "<li class='gen-index index-for-{$char}'>$char<ul id='{$matches["value"]}-index-for-{$char}'>\n";
                            }
                            $longdesc = $info[$filename][1] ? " - {$info[$filename][0]}" : "";
                            $ret .= '<li><a href="'.$filename. '" class="index">' .$data. '</a>' . $longdesc . '</li>'."\n";
                        }
                        $ret .= "</ul></li></ul>\n\n";
                        return $ret;
                        break;

                    case "examples":
                        $ret = "<ul class='gen-index index-for-{$matches["value"]}'>";
                        foreach($this->format->getExamples() as $idx => $id) {
                            $link = $this->format->createLink($id, $desc);
                            $ret .= '<li><a href="'.$link.'" class="index">Example#' .$idx. ' - ' .$desc. '</a></li>'."\n";
                        }
                        $ret .= "</ul>";
                        return $ret;
                        break;
                    default:
                        trigger_error("Don't know how to handle {$matches["value"]} for {$matches["attr"]}", E_USER_WARNING);
                        break;
                }
                break;

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


