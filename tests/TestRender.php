<?php
namespace phpdotnet\phd\Tests;

use phpdotnet\phd\Config;
use phpdotnet\phd\Index;
use phpdotnet\phd\Reader;
use phpdotnet\phd\Render;

class TestRender {
    protected $format;

    public function __construct($formatClass, $opts, $extra = array()) {
        foreach ($opts as $k => $v) {
            $method = "set_$k";
            Config::$method($v);
        }
        if (count($extra) != 0) {
            Config::init($extra);
        }
        $this->format = new $formatClass();
    }

    public function run() {
        $reader = new Reader();
        $render = new Render();
        if (Index::requireIndexing()) {
           $format = $render->attach(new Index);
           $reader->open(Config::xml_file());
           $render->execute($reader);
           $render->detach($format);
        }
        $render->attach($this->format);
        $reader->open(Config::xml_file());
        $render->execute($reader);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
