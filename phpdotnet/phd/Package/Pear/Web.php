<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_Pear_Web extends Package_Pear_ChunkedXHTML {
    public function __construct() {
        parent::__construct();
        $this->registerFormatName("Pear-Web");
        $this->setExt("php");
    }

    public function __destruct() {
        parent::close();
    }

    /**
     * Add the header to this file.
     *
     * @param string $id The id of this chunk
     *
     * @return string
     */
    public function header($id)
    {
        $ext = "." . $this->ext;

        $parent = Format::getParent($id);

        if (!$parent || $parent == "ROOT")
            return '<?php
sendManualHeaders("UTF-8","en");
setupNavigation(array(
  "home" => array("index.php", "'.addslashes($this->title).'"),
  "prev" => array("#", ""),
  "next" => array("#", ""),
  "up"   => array("#", ""),
  "toc"  => array(
    array("#", ""))));
manualHeader("index.php", "'.addslashes($this->title).'");
?>
';

        // Fetch the siblings information
        $toc = array();
        $siblings = Format::getChildrens($parent);
        foreach ((array)$siblings as $sibling => $array) {
            $toc[] = array($sibling.$ext, empty($array["sdesc"]) ? $array["ldesc"] : $array["sdesc"]);
        }

        // Build the PEAR navigation table
        $nav = array(
            'home' => array('index' . $ext, $this->title),
            'prev' => $this->createPrev($id, $parent, $siblings),
            'next' => $this->createNext($id, $parent, $siblings),
            'up'   => array($this->getFilename($parent).$ext, Format::getLongDescription($parent)),
            'toc'  => $toc
        );
        return "<?php \n" .
            "sendManualHeaders(\"UTF-8\", \"{$this->lang}\");\n" .
            "setupNavigation(" . var_export($nav, true) . ");\n" .
            'manualHeader("'
                . $this->getFilename($id).$ext . '", '
                . var_export(Format::getLongDescription($id), true)
            . ');' . "\n" .
            "?>\n";
    }

    /**
     * Create the footer for the given page id and return it.
     *
     * In this instance, we return raw php with the pearweb manual footer call.
     *
     * @param string $id Page ID
     *
     * @return string Footer code
     */
    public function footer($id)
    {
        $ext = '.' . $this->ext;
        $parent = Format::getParent($id);

        return '<?php manualFooter("'
            . $this->getFilename($id) . $ext . '", '
            . var_export(Format::getLongDescription($id), true)
            . '); ?>';
    }

    /**
     * Create the previous page link information
     *
     * @param string $id       ID of the page
     * @param string $parent   ID of the parent element
     * @param array  $siblings array of siblings
     *
     * @return array(0=>filename,1=>description)
     */
    protected function createPrev($id, $parent, $siblings)
    {
        if (!isset($siblings[$id]) || $parent == 'ROOT') {
            return array(null, null);
        }
        $ext = '.' .$this->ext;

        // Seek to $id
        while (list($tmp,) = each($siblings)) {
            if ($tmp == $id) {
                // Set the internal pointer back to $id
                if (prev($siblings) === false) {
                    end($siblings);
                }
                break;
            }
        }
        $tmp = prev($siblings);
        if ($tmp) {
            while (!empty($tmp["children"])) {
                $tmp = end($tmp["children"]);
            }
            return array(
                $tmp["filename"].$ext,
                htmlspecialchars(empty($tmp["sdesc"]) ? $tmp["ldesc"] : $tmp["sdesc"])
            );
            break;
        }

        return array(Format::getFilename($parent).$ext, Format::getLongDescription($parent, false));
    }

    /**
     * Create the next page link information
     *
     * @param string $id       ID of the page
     * @param string $parent   ID of the parent element
     * @param array  $siblings array of siblings
     *
     * @return array(0=>filename,1=>description)
     */
    protected function createNext($id, $parent, $siblings)
    {
        $ext = '.' .$this->ext;
        $next = array(null, null);
        // {{{ Create the "next" link
        if (!empty($siblings[$id]["children"])) {
            $tmp = reset($siblings[$id]["children"]);
            return array(
                $tmp["filename"].$ext,
                htmlspecialchars(empty($tmp["sdesc"]) ? $tmp["ldesc"] : $tmp["sdesc"])
            );
        }
        do {
            if (!isset($siblings[$id])) {
                break;
            }

            // Seek to $id
            while (list($tmp,) = each($siblings)) {
                if ($tmp == $id) {
                    break;
                }
            }

            $tmp = current($siblings);
            prev($siblings); // Reset the internal pointer to previous pos
            if ($tmp) {
                $next = array(
                    $tmp["filename"].$ext,
                    htmlspecialchars(empty($tmp["sdesc"]) ? $tmp["ldesc"] : $tmp["sdesc"])
                );
                break;
            }

            // We are the end element in this chapter
            $grandpa = Format::getParent($parent);
            if (!$grandpa || $grandpa == "ROOT") {
                // There is no next relative
                break;
            }

            $siblings = Format::getChildrens($grandpa);
            $id       = $parent;
            $parent   = $grandpa;
        } while (true);
        return $next;
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

