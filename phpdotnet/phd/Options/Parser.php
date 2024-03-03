<?php
namespace phpdotnet\phd;

class Options_Parser
{

    private Options_Interface $defaultHandler;
    /** @var array<Options_Interface> */
    private array $packageHandlers = [];

    public function __construct(
        Options_Interface $defaultHandler,
        Options_Interface ...$packageHandlers
    ) {
        $this->defaultHandler = $defaultHandler;
        $this->packageHandlers = $packageHandlers;
    }

    /**
     * @return array<Options_Interface, string>
     */
    private function handlerForOption(string $option): array {
        if (method_exists($this->defaultHandler, "option_{$option}")) {
            return [$this->defaultHandler, "option_{$option}"];
        }

        $opt = explode('-', $option);
        $package = strtolower($opt[0]);

        if (isset($this->packageHandlers[$package])) {
            if (method_exists($this->packageHandlers[$package], "option_{$opt[1]}")) {
                return [$this->packageHandlers[$package], "option_{$opt[1]}"];
            }
        }
        return [];
    }

    /**
     * @return array<string>
     */
    private function getLongOptions(): array {
        $defaultOptions = array_keys($this->defaultHandler->optionList());
        $packageOptions = [];
        foreach ($this->packageHandlers as $package => $handler) {
            foreach ($handler->optionList() as $opt) {
                $packageOptions[] = $package . '-' . $opt;
            }
        }
        return array_merge($defaultOptions, $packageOptions);
    }

    private function getShortOptions(): string {
        $defaultOptions = array_values($this->defaultHandler->optionList());
        $packageOptions = [];
        foreach ($this->packageHandlers as $handler) {
            foreach ($handler->optionList() as $opt) {
                $packageOptions[] = $opt;
            }
        }
        $options = array_merge($defaultOptions, $packageOptions);
        return implode('', array_values($options));
    }

    /**
     * Checks if all options passed are valid.
     *
     * Fix Bug #54217 - Warn about nonexisting parameters
     */
    private function validateOptions(): void {
        $argv = $_SERVER['argv'];
        $argc = $_SERVER['argc'];

        $short = str_split(str_replace(':', '', $this->getShortOptions()));
        $long = [];
        foreach ($this->getLongOptions() as $opt) {
            $long[] = str_replace(':', '', $opt);
        }

        for ($i=1; $i < $argc; $i++) {
            $checkArgv = explode('=', $argv[$i]);
            if (substr($checkArgv[0], 0, 2) === '--') {
                if (!in_array(substr($checkArgv[0], 2), $long)) {
                    trigger_error('Invalid long option ' . $argv[$i], E_USER_ERROR);
                }
            } elseif (substr($checkArgv[0], 0, 1) === '-') {
                if (!in_array(substr($checkArgv[0], 1), $short)) {
                    trigger_error('Invalid short option ' . $argv[$i], E_USER_ERROR);
                }
           }
        }
    }

    public function getopt() {
        $this->validateOptions();

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
