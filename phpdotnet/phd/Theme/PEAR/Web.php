<?php
namespace phpdotnet\phd;

/**
* PEAR theme for the many php files used for pearweb
*
* @package PhD
* @version CVS: $Id$
*/
class Theme_PEAR_Web extends Theme_PEAR_Base
{
    /**
    * Array of file resources
    *
    * @var array
    */
    protected $streams = array();

    protected $writeit = false;

    public $outputdir = '';

    /**
    * URL prefix for all API doc link generated with <phd:pearapi>.
    * On pearweb, the manual is at "/manual/$lang/". This means that
    *  we can use a relative URI here to make the links work on mirrors, too.
    *
    * @var string
    */
    public $phd_pearapi_urlprefix = '../../package/';


    /**
     * Constructor
     *
     * @param array  $IDs     Array of IDs to build
     * @param string $ext     Filename extension to use
     * @param bool   $chunked Whether to chunk the output into individual files
     */
    public function __construct($IDs, $ext = "php", $chunked = true)
    {
        parent::__construct($IDs, $ext, $chunked);
        $this->outputdir = Config::output_dir() . $this->ext . DIRECTORY_SEPARATOR;
        if (!file_exists($this->outputdir) || is_file($this->outputdir)) {
            mkdir($this->outputdir) or die("Can't create the cache directory");
        } else {
            if (file_exists($this->outputdir . "index.php")) {
                unlink($this->outputdir . "index.php");
            }
        }
    }

    /**
     * Write an individual chunk of the manual
     *
     * @param string   $id     ID of the chunk
     * @param resource $stream Stream containing the contents of the chunk
     *
     * @return void
     */
    public function writeChunk($id, $stream)
    {
        rewind($stream);

        // Create random filename when the chunk doesn't have an ID
        if ($id === null) {
            $filename    = tempnam($this->outputdir, 'phd');
            $newfilename = $this->outputdir . basename($filename, '.tmp') . '.' . $this->ext;
            if (rename($filename, $newfilename)) {
                $filename = basename($filename, '.tmp');
            } else {
                throw new Exception("Cannot rename $filename to $newfilename");
            }
            trigger_error("Chunk without an ID found, TOC will NOT work. Wrote content to $newfilename.", E_USER_WARNING);
        } else {
            $filename = $id;
        }
        $filename .= '.' . $this->ext;

        $contents = $this->cleanHtml(stream_get_contents($stream));

        file_put_contents($this->outputdir . $filename, $this->header($id));
        file_put_contents($this->outputdir . $filename, $contents, FILE_APPEND);
        file_put_contents($this->outputdir . $filename, $this->footer($id), FILE_APPEND);

        v("Wrote %s", $this->outputdir . $filename, VERBOSE_CHUNK_WRITING);
    }

    /**
     * Append data to the streams.
     *
     * @param string                                       $data    Data to write
     * @param Reader_Legacy::CLOSE_CHUNK|Reader_Legacy::OPEN_CHUNK $isChunk constant
     *
     * @return int|false
     */
    public function appendData($data, $isChunk)
    {
        switch($isChunk) {
        case Reader_Legacy::CLOSE_CHUNK:
            $id = $this->CURRENT_ID;

            $stream = array_pop($this->streams);
            $retval = fwrite($stream, $data);
            $this->writeChunk($id, $stream);
            fclose($stream);

            $this->CURRENT_ID = null;
            return $retval;
            break;

        case Reader_Legacy::OPEN_CHUNK:
            $this->streams[] = fopen("php://temp/maxmemory", "r+");

        default:
            $stream = end($this->streams);
            $retval = fwrite($stream, $data);
            return $retval;
        }
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

        $parent = Helper::getParent($id);

        if (!$parent || $parent == "ROOT")
            return '<?php
sendManualHeaders("UTF-8","en");
setupNavigation(array(
  "home" => array("index.php", "PEAR Manual"),
  "prev" => array("#", ""),
  "next" => array("#", ""),
  "up"   => array("#", ""),
  "toc"  => array(
    array("#", ""))));
manualHeader("index.php", "PEAR Manual");
?>
';

        // Fetch the siblings information
        $toc = array();
        $siblings = Helper::getChildren($parent);
        foreach ($siblings as $sibling => $array) {
            $toc[] = array($sibling.$ext, empty($array["sdesc"]) ? $array["ldesc"] : $array["sdesc"]);
        }

        // Build the PEAR navigation table
        $nav = array(
            'home' => array('index' . $ext, 'PEAR Manual'),
            'prev' => $this->createPrev($id, $parent, $siblings),
            'next' => $this->createNext($id, $parent, $siblings),
            'up'   => array($this->getFilename($parent).$ext, Helper::getDescription($parent, true)),
            'toc'  => $toc
        );
        return "<?php \n" .
            "sendManualHeaders(\"UTF-8\", \"{$this->lang}\");\n" .
            "setupNavigation(" . var_export($nav, true) . ");\n" .
            'manualHeader("'
                . $this->getFilename($id).$ext . '", '
                . var_export(Helper::getDescription($id, true), true)
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
        $parent = Helper::getParent($id);

        return '<?php manualFooter("'
            . $this->getFilename($id) . $ext . '", '
            . var_export(Helper::getDescription($id, true), true)
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

        return array(Helper::getFilename($parent).$ext, Helper::getDescription($parent, false));
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
            $grandpa = Helper::getParent($parent);
            if (!$grandpa || $grandpa == "ROOT") {
                // There is no next relative
                break;
            }

            $siblings = Helper::getChildren($grandpa);
            $id       = $parent;
            $parent   = $grandpa;
        } while (true);
        return $next;
    }

    /**
     * Destructor - if guide.php exists, this must be the index file copy it over.
     */
    public function __destruct()
    {
        if (file_exists($this->outputdir . "guide.php") && !file_exists($this->outputdir . "index.php")) {
            copy($this->outputdir . "guide.php", $this->outputdir . "index.php");
        }
    }

    /**
     * use for formatting a Q&A set - is this even used?
     *
     * @param bool         $open  if a chunk is open we should append to
     * @param unknown_type $name  The name
     * @param unknown_type $attrs attributes
     *
     * @return string
     */
    public function format_qandaset($open, $name, $attrs)
    {
        if ($open) {
            $this->cchunk["qandaentry"] = array();
            $this->appendData(null, Reader_Legacy::OPEN_CHUNK);
            return '';
        }

        $stream = array_pop($this->streams);
        rewind($stream);
        return parent::qandaset($stream);
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

