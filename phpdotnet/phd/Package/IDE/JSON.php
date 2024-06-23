<?php
namespace phpdotnet\phd;

class Package_IDE_JSON extends Package_IDE_Base {

    public function __construct(Config $config) {
        parent::__construct($config);
        $this->registerFormatName('IDE-JSON');
        $this->setExt(Config::ext() === null ? ".json" : Config::ext());
    }

    public function parseFunction() {
        return json_encode($this->function);
    }

}


