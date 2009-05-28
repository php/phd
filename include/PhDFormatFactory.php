<?php

abstract class PhDFormatFactory {
    public function createXhtmlFormat() {
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }
    public function createBigXhtmlFormat() {
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }
    public function createPHPFormat() {
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }

    public static final function createFactory() {
        global $ROOT;
        $package = PhDConfig::package();
        $classname = "{$package}Factory";
                
        require_once $ROOT . "/packages/$package/$classname.class.php";

        $factory = new $classname();
        if (!($factory instanceof PhDFormatFactory)) {
            throw new Exception("All Factories must inherit PhDFormatFactory");
        }
        return $factory;
    }
}

?>
