<?php
namespace phpdotnet\phd;

class Package_IDE_PHP extends Package_IDE_Base {

    public function __construct(
        Config $config,
        OutputHandler $outputHandler
    ) {
        parent::__construct($config, $outputHandler);
        $this->registerFormatName('IDE-PHP');
        $this->setExt($this->config->ext === null ? ".php" : $this->config->ext);
    }

    public function parseFunction() {
        $str = '<?php' . PHP_EOL;
        $str .= 'return ' . var_export($this->function, true) . ';' . PHP_EOL;
        $str .= '?>';
        return $str;
    }

}
