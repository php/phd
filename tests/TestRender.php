<?php
namespace phpdotnet\phd;

class TestRender {
    protected $format;

    public function __construct($formatclass, $opts, $extra = array()) {
        foreach ($opts as $k => $v) {
            $method = "set_$k";
            Config::$method($v);
        }
        if (count($extra) != 0) {
            Config::init($extra);
        }
        $classname = __NAMESPACE__ . "\\" . $formatclass;
        $this->format = new $classname();
    }

    public function run() {
        $reader = new Reader();
        $render = new Render();
        if (Index::requireIndexing()) {
           $format = new Index;
           $render->attach($format);
           $reader->open(Config::xml_file());
           $render->execute($reader);
           $render->detach($format);
        }
        $render->attach($this->format);
        $reader->open(Config::xml_file());
        $render->execute($reader);
    }
}


