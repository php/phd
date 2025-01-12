<?php
/**
 * Source code highlighting class for phd.
 *
 * PHP version 5.3+
 *
 * @category PhD
 * @package  PhD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
namespace phpdotnet\phd;

/**
 * Source code highlighting class for phd.
 *
 * @category PhD
 * @package  PhD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
class Highlighter
{
    /**
    * Create a new highlighter instance for the given format.
    *
    * We use a factory so you can return different objects/classes
    * per format.
    *
    * @param string $format Output format (pdf, xhtml, troff, ...)
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
    * @param string $format Output format (pdf, xhtml, troff, ...)
    *
    * @return string Highlighted code
    */
    public function highlight($text, $role, $format)
    {
        if ($format == 'troff') {
            return "\n.PP\n.nf\n"
                . str_replace("\\", "\\\\", trim($text))
                . "\n.fi";
        } else if ($format != 'xhtml') {
            return $text;
        }

        if ($role == 'php') {
            try {
                $highlight = highlight_string($text, true);
                if (PHP_VERSION_ID >= 80300) {
                    return $highlight;
                } else {
                    return strtr($highlight, [
                        '&nbsp;' => ' ',
                        "\n" => '',
                    ]);
                }
            } catch (\ParseException $e) {
                trigger_error(vsprintf("Parse error while highlighting PHP code: %s\nText: %s", [(string) $e, $text]), E_USER_WARNING);

                return '<pre class="'. $role . 'code">'
                    . htmlspecialchars($text, ENT_QUOTES, 'UTF-8')
                    . "</pre>\n";
            }
        } else {
            return '<pre class="'. ($role ? $role . 'code' : 'programlisting') .'">'
                . htmlspecialchars($text, ENT_QUOTES, 'UTF-8')
                . "</pre>\n";
        }

        return $retval;
    }//public function highlight(..)

}


