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
    public function createHowToFormat() {
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }
    public function createManpageFormat() {
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }

    public static final function createFactory()
    {
        $package = Config::package();
        $classname = __NAMESPACE__ . "\\Package_" . $package . "_Factory";

        $factory = new $classname();
        if (!($factory instanceof Format_Factory)) {
            throw new \Exception("All Factories must inherit Format_Factory");
        }
        return $factory;
    }
}

?>
