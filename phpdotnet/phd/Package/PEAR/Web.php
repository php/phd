<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PEAR_Web extends Package_PEAR_ChunkedXHTML {
    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PEAR-Web");
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
        $siblingIDs = Format::getChildren($parent);
        $siblings = array();
        foreach ($siblingIDs as $sid) {
            $siblings[$sid] = array(
                "filename" => Format::getFilename($sid),
                "parent" => Format::getParent($sid),
                "sdesc" => Format::getShortDescription($sid),
                "ldesc" => Format::getLongDescription($sid),
                "children" => $this->createChildren($sid),
            );
        }
        foreach ((array)$siblings as $sibling => $array) {
            $toc[] = array($sibling.$ext, empty($array["sdesc"]) ? $array["ldesc"] : $array["sdesc"]);
        }

        $prev = $next = array(null, null);
        if ($prevID = Format::getPrevious($id)) {
            $prev = array(
                Format::getFilename($prevID).$ext,
                Format::getLongDescription($prevID),
            );
        }        
        if ($nextID = Format::getNext($id)) {
            $next = array(
                Format::getFilename($nextID).$ext,
                Format::getLongDescription($nextID),
            );
        }
        // Build the PEAR navigation table
        $nav = array(
            'home' => array('index' . $ext, $this->title),
            'prev' => $prev,
            'next' => $next,
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

    protected function createChildren($id) {
        if (!Format::getChildren($id)) {
            return array();
        }
        $children =  array($id => array( 
            "filename" => Format::getFilename($id),
            "parent" => Format::getParent($id),
            "sdesc" => Format::getShortDescription($id),
            "ldesc" => Format::getLongDescription($id),
        ));
        foreach (Format::getChildren($id) as $child) {
            $children["children"] = $this->createChildren($child);
        }
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

