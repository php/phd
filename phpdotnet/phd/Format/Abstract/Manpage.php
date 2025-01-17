<?php
namespace phpdotnet\phd;

abstract class Format_Abstract_Manpage extends Format {
    public $role = false;

    public function __construct(
        Config $config, 
        OutputHandler $outputHandler,
    ) {
        parent::__construct($config, $outputHandler);
    }

    public function UNDEF($open, $name, $attrs, $props) {
        if ($open) {
            trigger_error("No mapper found for '{$name}'", E_USER_WARNING);
        }
        return "\n.B [NOT PROCESSED] $name [/NOT PROCESSED]";
    }

    public function CDATA($value) {
        return $this->highlight(trim($value), $this->role, 'troff');
    }

    public function TEXT($value) {
        $ret = preg_replace( '/[ \n\t]+/', ' ', $value);

        // Escape \ ' and NUL byte
        $ret = addcslashes($ret, "\\'\0");

        // No newline if current line begins with ',', ';', ':', '.'
        if (in_array($ret[0], array(",", ";", ":", "."))) {
            return $ret;
        }

        return $ret;
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        if ($tag === '') {
            return $tag;
        }

        $isMacro = $tag[0] == ".";

        if ($open) {

            if ($isMacro && strpos($tag, "\n") === false) {
                return "\n" . $tag . "\n";
            }
            return "\n" . $tag;
        }

        return ($isMacro ? "" : "\\fP");
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {
    }

}
