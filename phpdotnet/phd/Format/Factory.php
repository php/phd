<?php
namespace phpdotnet\phd;

abstract class Format_Factory {
    private $formats     = array();
    private $packageName = "";
    private $optionsHandler = null;
    private $pversion = "unknown";

    final public function getPackageVersion() {
        return $this->pversion;
    }
    final public function setPackageVersion($version) {
        $this->pversion = $version;
    }
    final public function getOutputFormats() {
        return array_keys($this->formats);
    }

    final public function registerOutputFormats($formats) {
        $this->formats = $formats;
    }

    final public function getOptionsHandler() {
        return $this->optionsHandler;
    }

    final public function registerOptionsHandler(Options_Interface $optionsHandler) {
        $this->optionsHandler = $optionsHandler;
    }

    final protected function setPackageName($name) {
        if (!is_string($name)) {
            throw new \Exception("Package names must be strings..");
        }
        $this->packageName = $name;
    }
    final public function getPackageName() {
        return $this->packageName;
    }

    final public function createFormat($format, ...$formatParams) {
        if (isset($this->formats[$format]) && $this->formats[$format]) {
            $classname = __NAMESPACE__ . "\\" . $this->formats[$format];

            $obj = new $classname(...$formatParams);
            if (!($obj instanceof Format)) {
                throw new \Exception("All Formats must inherit Format");
             }
            return $obj;
        }
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }

    final public static function createFactory($package) {
        static $factories = array();

        if (!is_string($package)) {
            throw new \Exception("Package name must be string..");
        }

        if (!isset($factories[$package])) {
            $classname = __NAMESPACE__ . "\\Package_" . $package . "_Factory";

            $factory = new $classname();
            if (!($factory instanceof Format_Factory)) {
                throw new \Exception("All Factories must inherit Format_Factory");
            }
            $factories[$package] = $factory;
        }
        return $factories[$package];
    }

    final public function __toString() {
        return $this->getPackageName();
    }
}


