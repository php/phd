<?php
namespace phpdotnet\phd;

abstract class Format_Factory
{
    public function createXhtmlFormat() {
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }
    public function createBigXhtmlFormat() {
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }
    public function createPHPFormat() {
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }

    public static final function createFactory()
    {
        global $ROOT;
        $package = Config::package();
        $classname = "{$package}Factory";
                
        require_once $ROOT . "/packages/$package/$classname.class.php";

        $factory = new $classname();
        if (!($factory instanceof Format_Factory)) {
            throw new Exception("All Factories must inherit PhDFormatFactory");
        }
        return $factory;
    }
}

?>
