<?php
namespace phpdotnet\phd;

class Package_IDE_PHP extends Package_IDE_Base {

    public function __construct() {
        $this->registerFormatName('IDE-PHP');
        $this->setExt(Config::ext() === null ? ".php" : Config::ext());
    }

    public function parseFunction() {
        $str = '<?php' . PHP_EOL;
        $str .= 'return ' . var_export($this->function, true) . ';' . PHP_EOL;
        $str .= '?>';
        return $str;
    }

}


