<?php
namespace phpdotnet\phd;

class Options_Parser
{

    private Options_Interface $defaultHandler;
    private array $packageHandlers = [];

    /**
     * @param array<?Options_Interface> $packageHandlers
     */
    public function __construct(Options_Interface $defaultHandler, ?array $packageHandlers = []) {
        $this->defaultHandler = $defaultHandler;
        $this->validatePackageHandlers($packageHandlers);
        $this->packageHandlers = $packageHandlers;
    }

    /**
     * @param array<?Options_Interface> $packageHandlers
     *
     * @return array<?Options_Interface>
     */
    private function validatePackageHandlers(array $packageHandlers): void {
        foreach ($packageHandlers as $handler) {
            if (!($handler instanceof Options_Interface)) {
                trigger_error("All package handlers must implement Options_Interface", E_USER_ERROR);
                break;
            }
        }
    }

    public function handlerForOption($option) {
        if (method_exists($this->defaultHandler, "option_{$option}")) {
            return array($this->defaultHandler, "option_{$option}");
        }

        $opt = explode('-', $option);
        $package = strtolower($opt[0]);

        if (isset($this->packageHandlers[$package])) {
            if (method_exists($this->packageHandlers[$package], "option_{$opt[1]}")) {
                return array($this->packageHandlers[$package], "option_{$opt[1]}");
            }
        }
        return NULL;
    }

    public function getLongOptions() {
        $defaultOptions = array_keys($this->defaultHandler->optionList());
        $packageOptions = array();
        foreach ($this->packageHandlers as $package => $handler) {
            foreach ($handler->optionList() as $opt) {
                $packageOptions[] = $package . '-' . $opt;
            }
        }
        return array_merge($defaultOptions, $packageOptions);
    }

    public function getShortOptions() {
        return implode('', array_values($this->defaultHandler->optionList()));
    }

    /**
     * Checks if all options passed are valid.
     *
     * Fix Bug #54217 - Warn about nonexisting parameters
     */
    private function checkOptions() {
        $argv = $_SERVER['argv'];
        $argc = $_SERVER['argc'];

        $short = str_split(str_replace(':', '', $this->getShortOptions()));
        $long = array();
        foreach ($this->getLongOptions() as $opt) {
            $long[] = str_replace(':', '', $opt);
        }

        for ($i=1; $i < $argc; $i++) {
            $checkArgv = explode('=', $argv[$i]);
            if (substr($checkArgv[0], 0, 2) == '--') {
                if (!in_array(substr($checkArgv[0], 2), $long)) {
                    trigger_error('Invalid long option ' . $argv[$i], E_USER_ERROR);
                }
            } elseif (substr($checkArgv[0], 0, 1) == '-') {
                if (!in_array(substr($checkArgv[0], 1), $short)) {
                    trigger_error('Invalid short option ' . $argv[$i], E_USER_ERROR);
                }
           }
        }
    }

    public function getopt() {
        //validate options
        $this->checkOptions();

        $args = getopt($this->getShortOptions(), $this->getLongOptions());
        if ($args === false) {
            trigger_error("Something happend with getopt(), please report a bug", E_USER_ERROR);
        }

        foreach ($args as $k => $v) {
            $handler = $this->handlerForOption($k);
            if (is_callable($handler)) {
                call_user_func($handler, $k, $v);
            } else {
                var_dump($k, $v);
                trigger_error("Hmh, something weird has happend, I don't know this option", E_USER_ERROR);
            }
        }
    }

}


