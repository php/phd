<?php
namespace phpdotnet\phd;

abstract class Format_Factory {
    private $formats     = array();
    private $packageName = "";
    private $optionsHandler = null;
    private $pversion = "unknown";

    public final function getPackageVersion() {
        return $this->pversion;
    }
    public final function setPackageVersion($version) {
        $this->pversion = $version;
    }
    public final function getOutputFormats() {
        return array_keys($this->formats);
    }

    public final function registerOutputFormats($formats) {
        $this->formats = $formats;
    }

    public final function getOptionsHandler() {
        return $this->optionsHandler;
    }

    public final function registerOptionsHandler(Options_Interface $optionsHandler) {
        $this->optionsHandler = $optionsHandler;
    }

    protected final function setPackageName($name) {
        if (!is_string($name)) {
            throw new \Exception("Package names must be strings..");
        }
        $this->packageName = $name;
    }
    public final function getPackageName() {
        return $this->packageName;
    }

    public final function createFormat($format, ...$formatParams) {
        if (isset($this->formats[$format]) && $this->formats[$format]) {
            $classname = __NAMESPACE__ . "\\" . $this->formats[$format];

            $obj = new $classname(...$formatParams);
            if (!($obj instanceof Format)) {
                throw new \Exception("All Formats must inherit Format");
             }
            return $obj;
        }
        throw new \Error('This format is not supported by this package');
    }

    public static final function createFactory($package) {
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

    public final function __toString() {
        return $this->getPackageName();
    }
}


