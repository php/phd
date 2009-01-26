<?php

/**
* Source code highlighting class for phd.
*/
class PhDHighlighter
{
    /**
    * Create a new highlighter instance for the given format.
    *
    * We use a factory so you can return different objects/classes
    * per format.
    *
    * @param string $format Format to highlight (pdf, html, man, ...)
    *
    * @return PhDHighlighter Highlighter object
    */
    public static function factory($format)
    {
        return new self();
    }//public static function factory(..)



    /**
    * Highlight a given piece of source code.
    * Dead simple version that only works for xhtml+php. Returns text as
    *  it was in all other cases.
    *
    * @param string $text   Text to highlight
    * @param string $role   Source code role to use (php, xml, html, ...)
    * @param string $format Format to highlight (pdf, html, man, ...)
    *
    * @return string Highlighted code
    */
    public function highlight($text, $role, $format)
    {
        if ($format == 'xhtml') {
            if ($role == 'php') {
                return highlight_string($text, 1);
            } else {
                return '<pre>'
                    . htmlspecialchars($text, ENT_QUOTES, 'UTF-8')
                    . '</pre>';
            }
        }

        return $text;
    }//public function highlight(..)

}

?>