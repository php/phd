<?php
namespace phpdotnet\phd;

class Package_IDE_JSON extends Package_IDE_Base {

    public function __construct(
        Config $config, 
        OutputHandler $outputHandler
    ) {
        parent::__construct($config, $outputHandler);
        $this->registerFormatName('IDE-JSON');
        $this->setExt($this->config->ext() === null ? ".json" : $this->config->ext());
    }

    public function parseFunction() {
        return json_encode($this->function);
    }

}
