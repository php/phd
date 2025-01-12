<?php
namespace phpdotnet\phd;

class Package_PHP_Manpage extends Package_Generic_Manpage {

    protected $elementmap = array(
        'phpdoc:varentry'               => 'format_chunk',
        'phpdoc:classref'               => 'format_chunk',
        'phpdoc:exception'              => 'format_chunk',
        'phpdoc:exceptionref'           => 'format_chunk',
        'phpdoc:varentry'               => 'format_chunk',
        'fieldsynopsis'                 => array(
            /* DEFAULT */                  'format_methodsynopsis',
            'classsynopsis'         => 'format_class_methodsynopsis',
        ),
        'constructorsynopsis'             => array(
            /* DEFAULT */                  'format_methodsynopsis',
            'classsynopsis'         => 'format_class_methodsynopsis',
        ),
        'methodsynopsis'                => array(
            /* DEFAULT */                  'format_methodsynopsis',
            'classsynopsis'         => 'format_class_methodsynopsis',
        ),
    );

    protected $textmap =        array(
        'titleabbrev'               => array(
            /* DEFAULT */        false,
            'phpdoc:classref'    => 'format_class_title_text',
            'phpdoc:exception'   => 'format_class_title_text',
            'phpdoc:exceptionref'=> 'format_class_title_text',
        ),
    );

    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array();

    public function __construct(
        Config $config,
        OutputHandler $outputHandler
    ) {
        parent::__construct($config, $outputHandler);

        $this->registerFormatName("PHP-Functions");
        $this->setTitle("PHP Manual");
        $this->dchunk = array_merge(parent::getDefaultChunkInfo(), $this->getDefaultChunkInfo());
    }

    public function __destruct() {
        $this->close();
    }

    public function update($event, $value = null) {
        switch($event) {
        case Render::STANDALONE:
            if ($value) {
                $this->registerElementMap(array_merge(parent::getDefaultElementMap(), $this->elementmap));
                $this->registerTextMap(array_merge(parent::getDefaultTextMap(), $this->textmap));
            } else {
                $this->registerElementMap($this->getDefaultElementMap());
                $this->registerTextMap($this->getDefaultTextMap());
            }
            break;
        default:
            parent::update($event, $value);
            break;
        }
    }


    public function header($index) {
        return ".TH " . strtoupper($this->cchunk["funcname"][$index]) . " 3 \"" . $this->date . "\" \"PHP Documentation Group\" \"" . $this->bookName . "\"" . "\n";
    }
    public function format_chunk($open, $name, $attrs, $props) {
        return parent::format_chunk($open, $name, $attrs, $props);
    }
    public function format_class_title_text($value) {
        $this->cchunk["funcname"][] = $this->toValidName(trim($value));
    }

    public function format_class_methodsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.TP 0.2i\n\(bu\n";
        }

        $retval = parent::format_methodsynopsis($open, $name, $attrs, $props);

        if ($name == "fieldsynopsis") {
            return "";
        }
        return $retval;
    }


    public function close() {
        foreach ($this->getFileStream() as $fp) {
            fclose($fp);
        }
    }

}
