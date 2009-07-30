<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Format_Factory {
    private $formats = array();

    public final function getOutputFormats() {
        return array_keys($this->formats);
    }

    public final function registerOutputFormats($formats) {
        $this->formats = $formats;
    }

    public final function createFormat($format) { 
        if (isset($this->formats[$format]) && $this->formats[$format]) {
            $classname = __NAMESPACE__ . "\\" . $this->formats[$format];

            $obj = new $classname();
            if (!($obj instanceof Format)) {
                throw new \Exception("All Formats must inherit Format");
             }
            return $obj;
        }
        trigger_error("This format is not supported by this package", E_USER_ERROR);
    }

    public static final function createFactory($package = null) {
        if ($package === null) {
            $package = Config::package();
        }
        $classname = __NAMESPACE__ . "\\Package_" . $package . "_Factory";

        $factory = new $classname();
        if (!($factory instanceof Format_Factory)) {
            throw new \Exception("All Factories must inherit Format_Factory");
        }
        return $factory;
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

