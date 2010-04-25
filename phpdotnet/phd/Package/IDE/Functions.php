<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_IDE_Functions extends Format {
    protected $elementmap = array(
        'caution'               => 'format_notes',
        'entry'                 => 'format_changelog_entry',
        'function'              => 'format_seealso_entry',
        'methodparam'           => 'format_methodparam',
        'methodname'            => 'format_seealso_entry',
        'member'                => 'format_member',
        'note'                  => 'format_notes',
        'refentry'              => 'format_refentry',
        'refname'               => 'name',
        'refnamediv'            => 'format_suppressed_tags',
        'refpurpose'            => 'format_refpurpose',
        'refsect1'              => 'format_refsect1',
        'row'                   => 'format_changelog_row',
        'set'                   => 'format_set',
        'tbody'                 => 'format_changelog_tbody',
        'tip'                   => 'format_notes',
        'warning'               => 'format_notes',
    );
    protected $textmap    = array(
        'entry'                 => 'format_changelog_entry_text',
        'function'              => 'format_seealso_entry_text',
        'initializer'           => 'format_initializer_text',
        'methodname'            => 'format_seealso_entry_text',
        'parameter'             => 'format_parameter_text',
        'refname'               => 'format_refname_text',
        'title'                 => 'format_suppressed_text',
        'type'                  => 'format_type_text',
    );
    protected $cchunk = array();
    protected $dchunk = array(
        "code"                  => false,
        "funcname"              => array(),
        "manualid"              => false,
        "param"                 => array(
            "name"                  => false,
            "type"                  => false,
            "description"           => false,
            "opt"                   => false,
            "initializer"           => false,
        ),
        "example"               => array(
            "title"                 => false,
            "language"              => false,
            "code"                  => false,
            "return"                => false,
        ),
        "methodparam"           => false,
        "refname"               => false,
        "return"                => array(
            "type"                  => false,
        ),
        "changelogentry"        => array(
            "entry"                 => false,
            "version"               => true,
        ),
        "screen"                => false,
        "seealso"               => array(
            "type"                  => false,
            "name"                  => false,
            "description"           => false,
        ),
    );

    protected $chunkFlags;
    protected $chunkOpened = false;
    protected $isFunctionRefSet;
    protected $role = false;
    protected $versions;

    public function __construct() {
        $this->registerFormatName("IDE-Functions");
        $this->setExt(Config::ext() ?: ".xml");
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {}

    public function UNDEF($open, $name, $attrs, $props) {}

    public function TEXT($value) {
        /*TODO We have to render the description of the parameters*/
        if ($this->role == "parameters" || $this->role == "description") {
            return "";
        }

        //Skipping examples
        if ($this->role == "examples") {
            return "";
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function CDATA($value) {
        $content = '<![CDATA['. htmlspecialchars($value, ENT_QUOTES, 'UTF-8') .']]>'; 
        if ($this->role == "examples") {
            if (isset($this->cchunk["code"]) && $this->cchunk["code"]) {
                $this->cchunk["example"]["code"] = $content;
            } elseif (isset($this->cchunk["screen"]) && $this->cchunk["screen"]) {
                $this->cchunk["example"]["return"] = $content;
            }
        }
        return "";
    }
    
    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        return $open ? "<" . $tag . ">" : "</" . $tag . ">\n";
    }

    public function header() {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    } 

    public function appendData($data) {
        if (!$this->isFunctionRefSet) {
            return 0;
        }
        if ($this->chunkFlags == Render::CLOSE) {
            $stream = $this->popFileStream();
            $retval = (trim($data) === "") ? false : fwrite($stream, $data);
            $this->writeChunk($stream);
            fclose($stream);

            $this->chunkFlags = null;
            $this->chunkOpened = false;
            $this->cchunk = array();
            return $retval;
        } elseif ($this->chunkFlags == Render::OPEN) {
            $this->pushFileStream(fopen("php://temp/maxmemory", "r+"));
            $this->chunkFlags = null;
            $this->chunkOpened = true;
        }
        if ($this->chunkOpened) {
            $stream = $this->getFileStream();
            // Remove whitespace nodes
            $retval = ($data != "\n" && trim($data) === "") ? false : fwrite(end($stream), $data);
            return $retval;
        }
        return 0;
    }

    function writeChunk($fp) {
        if (!isset($this->cchunk["funcname"][0])) {
             return;
        }
        $index = 0;
        $filename = $this->getOutputDir() . $this->cchunk["funcname"][$index] . $this->getExt();

        rewind($fp);
        file_put_contents($filename, $this->header());
        file_put_contents($filename, $fp, FILE_APPEND);
        while(isset($this->cchunk["funcname"][++$index])) {
            $filename = $this->getOutputDir() . $this->cchunk["funcname"][$index] . $this->getExt();
            rewind($fp);
            // Replace the default function name by the alternative one
            $content = preg_replace('/'.$this->cchunk["funcname"][0].'/',
                $this->cchunk["funcname"][$index], stream_get_contents($fp), 1);

            file_put_contents($filename, $this->header($index));
            file_put_contents($filename, $content, FILE_APPEND);
        }
    }

    public function CHUNK($value) {
        $this->chunkFlags = $value;
    }

    public function STANDALONE($value) {
        $this->registerElementMap($this->elementmap);
        $this->registerTextMap($this->textmap);
    }

    public function INIT($value) {
        if (file_exists(Config::phpweb_version_filename())) {
            $this->versions = self::generateVersionInfo(Config::phpweb_version_filename());
        } else {
            trigger_error("Can't load the versions file", E_USER_ERROR);
        }
        $this->setOutputDir(Config::output_dir() . strtolower($this->getFormatName()) . '/');
        if (file_exists($this->getOutputDir())) {
            if (!is_dir($this->getOutputDir())) {
                v("Output directory is a file?", E_USER_ERROR);
            }
        } else {
            if (!mkdir($this->getOutputDir())) {
                v("Can't create output directory", E_USER_ERROR);
            }
        }
    }

    public function FINALIZE($value) {
        foreach ($this->getFileStream() as $fp) {
            fclose($fp);
        }
    }

    public function VERBOSE($value) {
        v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
    }

    public function update($event, $value = null) {
        switch($event) {
        case Render::CHUNK:
            $this->CHUNK($value);
            break;
        case Render::STANDALONE:
            $this->STANDALONE($value);
            break;
        case Render::INIT:
            $this->INIT($value);
            break;
        case Render::FINALIZE:
            $this->FINALIZE($value);
            break;
        case Render::VERBOSE:
            $this->VERBOSE($value);
            break;
        }
    }

    public static function generateVersionInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        $r = new \XMLReader;
        if (!$r->open($filename)) {
            throw new \Exception;
        }
        $versions = array();
        while($r->read()) {
            if (
                $r->moveToAttribute("name")
                && ($funcname = str_replace(
                    array("::", "->", "__", "_", '$'),
                    array("-",  "-",  "-",  "-", ""),
                    $r->value))
                && $r->moveToAttribute("from")
                && ($from = $r->value)
            ) {
                $versions[strtolower($funcname)] = $from;
                $r->moveToElement();
            }
        }
        $r->close();
        $info = $versions;
        return $versions;
    }

    public function versionInfo($funcname) {
        $funcname = str_replace(
                array("::", "-&gt;", "->", "__", "_", '$', '()'),
                array("-",  "-",     "-",  "-",  "-", "",  ''),
                strtolower($funcname));
        if(isset($this->versions[$funcname])) {
           return $this->versions[$funcname];
        }
        v("No version info for %s", $funcname, VERBOSE_NOVERSION);
        return false;
    }

    public function format_suppressed_tags($open, $name, $attrs, $props) {
        return "";
    }

    public function format_suppressed_text($value, $tag) {
        return "";
    }

    public function format_set($open, $name, $attrs, $props) {
        if (isset($attrs[Reader::XMLNS_XML]["id"]) && $attrs[Reader::XMLNS_XML]["id"] == "funcref") {
            $this->isFunctionRefSet = $open;
        }
        return false;
    }

    public function format_refentry($open, $name, $attrs, $props) {
        if (!$this->isFunctionRefSet) {
            return false;
        }
        if ($open) {
            $this->notify(Render::CHUNK, Render::OPEN);
            $this->cchunk = $this->dchunk;
            $this->cchunk["manualid"] =  $attrs[Reader::XMLNS_XML]['id'];
            return "<function>\n";
        }
        $this->notify(Render::CHUNK, Render::CLOSE);
        return "</function>";
    }

    public function format_refname_text($value, $tag) {
        $this->cchunk["refname"] = $this->cchunk["funcname"][] = $this->toValidName(trim($value)); 
        return $this->toValidName(trim($value));
    }

    public function format_refpurpose($open, $name, $attrs, $props) {
        if (!$this->isFunctionRefSet) {
            return false;
        }
        if ($open) {
            return "<purpose>";
        }
        $ret = "</purpose>\n";
        $ret .= "<version>".$this->versionInfo($this->cchunk["refname"])."</version>\n";
        $ret .= "<manualid>".$this->cchunk["manualid"]."</manualid>\n";
        $this->cchunk["manualid"] = $this->dchunk["manualid"];
        $this->cchunk["refname"] = $this->dchunk["refname"];
        return $ret;
    }

    public function format_refsect1($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"]) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = false;
            }
        }        
        if ($this->role == "description") {
            return $open ? "\n<params>\n" : "</params>";
        } elseif ($this->role == "parameters") {
            //FIXME return ;
        } elseif ($this->role == "seealso") {
            return $open ? "\n<seealso>\n" : "</seealso>\n";
        } elseif ($this->role == "notes") {
            return $open ? "\n<notes>\n" : "</notes>\n";
        } elseif ($this->role == "examples") {
            return $open ? "\n<examples>\n" : "</examples>\n";
            //return $this->format_examples($open, $name, $attrs, $props);
        } elseif ($this->role == "changelog") {
            return $open ? "\n<changelog>\n" : "</changelog>\n";
        } elseif ($this->role == "errors") {
            return $this->format_errors($open, $name, $attrs, $props);
        } elseif ($this->role == "returnvalues") {
            return $this->format_return($open, $name, $attrs, $props);
        }

        return false;
    }

    public function format_type_text($value, $tag) {
        if (isset($this->cchunk["methodparam"]) && !$this->cchunk["methodparam"]) {
            $this->cchunk["return"]["type"] = $value;
            return "";
        }
        $this->cchunk["param"]["type"] = $value;
        return ""; 
    }

    public function format_methodparam($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["methodparam"] = true;
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["choice"]) && $attrs[Reader::XMLNS_DOCBOOK]["choice"] == "opt") {
                $this->cchunk["param"]["opt"] = "true"; 
            } else {
                $this->cchunk["param"]["opt"] = "false"; 
            }
            return "";
        }
        $content  = "<param>\n";
        $content .= "<name>" . $this->cchunk["param"]["name"] . "</name>\n";
        $content .= "<type>" . $this->cchunk["param"]["type"] . "</type>\n";
        //TODO Render the description of params
        //$content .= "<description>" . $this->["param"]["description"] . "</description>";
        $content .= "<optional>" . $this->cchunk["param"]["opt"] . "</optional>\n";
        if (isset($this->cchunk["param"]["initializer"]) && $this->cchunk["param"]["initializer"]) {
            $content .= "<initializer>" . $this->cchunk["param"]["initializer"] . "</initializer>\n";
        }
        $content .= "</param>\n";

        $this->cchunk["methodparam"] = $this->dchunk["methodparam"];
        $this->cchunk["param"] = $this->dchunk["param"];

        return $content;
    }

    public function format_parameter_text($value, $tag) {
        if ($this->role == "parameters") {
            return "";
        } elseif (isset($this->cchunk["methodparam"]) && $this->cchunk["methodparam"]) {
            $this->cchunk["param"]["name"] = $value;
            return "";
        }
        return "";
    }

    public function format_initializer_text($value, $tag) {
        if (isset($this->cchunk["methodparam"]) && !$this->cchunk["methodparam"]) {
            return "";
        }
        $this->cchunk["param"]["initializer"] = $value;
        return "";
    }

    public function format_return($open, $name, $attrs, $props) {
        if ($open) {
            $content = "\n\n<return>\n";
            $content .= "<type>" . $this->cchunk["return"]["type"] . "</type>\n";
            $content .= "<description>";
            //Read the description
            $reader = ReaderKeeper::getReader();
            do {
                $reader->read();
                //Skipping the title
                if ($reader->nodeType === \XMLReader::ELEMENT && $reader->name != 'title') {
                    $content .= trim(htmlspecialchars($reader->readContent(), ENT_QUOTES, 'UTF-8'));
                }
            } while ($reader->name != 'refsect1');
            $content .= "</description>\n</return>\n";
            $this->cchunk["return"] = $this->dchunk["return"];
            return $content;
        }
    }

    public function format_notes($open, $name, $attrs, $props) {
        if ($this->role != "notes") {
            return '';
        }
        if ($open) {
            $content = "<note>";
            $content .= "\n<type>" . $name . "</type>\n";
            $content .= "<description>";
            //Read the description
            $reader = ReaderKeeper::getReader();
            do {
                $reader->read();
                //Skipping the title
                if ($reader->nodeType === \XMLReader::ELEMENT && $reader->name != 'title') {
                    $content .= trim(htmlspecialchars($reader->readContent(), ENT_QUOTES, 'UTF-8'));
                }
            } while ($reader->name != $name);
            $content .= "</description>\n</note>\n";
            return $content;
        }
    }

    public function format_errors($open, $name, $attrs, $props) {
        if ($open) {
            return "\n\n<errors>\n<description>";
        }
        return "</description>\n</errors>\n";
    }

    public function format_changelog_tbody($open, $name, $attrs, $props) {
        if ($open && $this->role == "changelog") {
            $this->cchunk["changelogentry"] = $this->dchunk["changelogentry"];
            $this->cchunk["changelogentry"]["entry"] = true;
            return false;
        }
        $this->cchunk["changelogentry"]["entry"] = false;
        return "";
    }

    public function format_changelog_row($open, $name, $attrs, $props) {
        if ($this->role == "changelog") { 
            if (isset($this->cchunk["changelogentry"]["entry"]) && $this->cchunk["changelogentry"]["entry"]) {
                return $open ? "\n<entry>" : "</entry>";
            }
        }
        return "";
    }

    public function format_changelog_entry($open, $name, $attrs, $props) {
        if ($this->role == "changelog") {
            if (isset($this->cchunk["changelogentry"]["entry"]) && $this->cchunk["changelogentry"]["entry"]) {
                if (isset($this->cchunk["changelogentry"]["version"]) && $this->cchunk["changelogentry"]["version"]) {
                    if ($open) {
                        return "\n<version>";
                    }
                    $this->cchunk["changelogentry"]["version"] = false;
                    return "</version>\n";
                } else {
                    if ($open) {
                        return "\n<change>";
                    }
                    $this->cchunk["changelogentry"]["version"] = true;
                    return "</change>\n";
                }
            }
        }
        return "";
    }

    public function format_changelog_entry_text($value, $tag) {
         if ($this->role == "changelog") {
            if (isset($this->cchunk["changelogentry"]["entry"]) && !$this->cchunk["changelogentry"]["entry"]) {
                return "";
            }
        }
        /*Skipping examples*/
        if ($this->role == "examples") {
            return "";
        }
        return $value;
    }

    public function format_member($open, $name, $attrs, $props) {
        if ($this->role == "seealso") {
            if ($open) {
                $this->cchunk["seealso"] = $this->dchunk["seealso"];
                return "\n<entry>\n";
            } else {
                $content = "<type>" . $this->cchunk["seealso"]["type"] . "</type>\n";
                $content .= "<name>" . $this->cchunk["seealso"]["name"] . "</name>\n";
                $content .= "</entry>\n";
                return $content;
            }
        }
        return "";
    }

    public function format_seealso_entry($open, $name, $attrs, $props) {
        if ($this->role == "seealso") {
            $this->cchunk["seealso"]["type"] = $name;
        }
        return "";
    }

    public function format_seealso_entry_text($value, $tag) {
        if ($this->role == "seealso") {
            $this->cchunk["seealso"]["name"] = $value;
            return "";
        }
        /*TODO Render the description on params*/
        if ($this->role == "parameters" || $this->role == "description") {
            return ""; 
        }
        /*Skipping examples*/
        if ($this->role == "examples") {
            return "";
        }
        return $value;
    }

    public function format_examples($open, $name, $attrs, $props) {
        if ($open) {
            return "\n\n<examples>";
        }
        $content = "";
        if (isset($this->cchunk["example"]["language"]) && $this->cchunk["example"]["language"]) {
            $content .= "\n<language>" . $this->cchunk["example"]["language"] . "</language>";
        }         
        if (isset($this->cchunk["example"]["code"]) && $this->cchunk["example"]["code"]) {
            $content .= "\n<code>" . $this->cchunk["example"]["code"] . "</code>";
        } 
        if (isset($this->cchunk["example"]["return"]) && $this->cchunk["example"]["return"]) {
            $content .= "\n<return>" . $this->cchunk["example"]["return"] . "</return>";
        }
        $content .= "\n</examples>\n";
        $this->cchunk["example"] = $this->dchunk["example"];

        return $content;
    }

    public function format_programlisting($open, $name, $attrs, $props) {
        if ($this->role == "examples") {
            if ($open) {
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"]) {
                    $this->cchunk["example"]["language"] = $attrs[Reader::XMLNS_DOCBOOK]["role"]; 
                }
            }
            $this->cchunk["code"] = $open;
        }
        return "";
    }

    public function format_screen($open, $name, $attrs, $props) {
        if ($this->role == "examples") {            
            $this->cchunk["screen"] = $open;
        }
        return "";
    }

    public function toValidName($functionName) {
        return str_replace(array("::", "->", "()"), array(".", ".", ""), $functionName);
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

