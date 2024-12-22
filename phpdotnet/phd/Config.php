<?php
namespace phpdotnet\phd;

class Config
{
    const VERSION = '@phd_version@';
    public readonly string $copyright;

    /** @var array<string, string> */
    public array $output_format = [];
    public bool $no_index = false;
    public bool $force_index = false;
    public bool $no_toc = false;
    public string $xml_root = '.';
    public string $xml_file = './.manual.xml';
    public string $history_file = './fileModHistory.php';
    public string $lang_dir = './';
    public string $language = 'en';
    public string $fallback_language = 'en';
    public int $verbose = VERBOSE_DEFAULT;
    public string $date_format = 'H:i:s';
    /** @var array<string> */
    public array $render_ids = [];
    /** @var array<string> */
    public array $skip_ids = [];
    private bool $color_output = true;
    public string $output_dir = './output/';
    public string $output_filename = '';
    /** @var resource */
    public $php_error_output = \STDERR;
    public string $php_error_color = '01;31'; // Red
    /** @var resource */
    public $user_error_output = \STDERR;
    public string $user_error_color = '01;33'; // Yellow
    /** @var resource */
    public $phd_info_output = \STDOUT;
    public string $phd_info_color = '01;32'; // Green
    /** @var resource */
    public $phd_warning_output = \STDOUT;
    public string $phd_warning_color = '01;35'; // Magenta
    public string $highlighter = 'phpdotnet\\phd\\Highlighter';
    /** @var array<string> */
    public array $package =['Generic'];
    /** @var array<string> $css */
    public array $css = [];
    public bool $process_xincludes = false;
    public ?string $ext = null;
    /** @var array<string> */
    public array $package_dirs = [__INSTALLDIR__];
    public bool $saveconfig = false;
    public bool $quit = false;
    public ?IndexRepository $indexcache = null;
    public bool $memoryindex = false;

    public string $phpweb_version_filename = '';
    public string $phpweb_acronym_filename = '';
    public string $phpweb_sources_filename = '';
    public string $phpweb_history_filename = '';
    
    public function __construct() {
        $this->copyright = 'Copyright(c) 2007-'  . \date('Y') . ' The PHP Documentation Group';
        
        if('WIN' === \strtoupper(\substr(\PHP_OS, 0, 3))) {
        	$this->color_output = false;
        }
    }
    
    /**
     * Sets one or more configuration options from an array
     * 
     * @param array<string, mixed>
     */
    public function init(array $configOptions): void {
        foreach ($configOptions as $option => $value) {
            if (! \property_exists($this, $option)) {
                throw new \Exception("Invalid option supplied: $option");
            }
            
            $this->$option = $value;
        }
        
        \error_reporting($GLOBALS['olderrrep'] | $this->verbose);
    }

    /**
     * Returns all configuration options and their values
     * 
     * @return array<string, mixed>
     */
    public function getAllFiltered(): array {
        return \get_object_vars($this);
    }

    /**
     * Returns the list of supported formats from the package directories set
     * 
     * @return array<string>
     */
    public function getSupportedPackages(): array {
        $packageList = [];
        foreach($this->package_dirs as $dir) {
            foreach (\glob($dir . "/phpdotnet/phd/Package/*", \GLOB_ONLYDIR) as $item) {
                $baseitem = \basename($item);
                if ($baseitem[0] !== '.') {
                    $packageList[] = $baseitem;
                }
            }
        }
        return $packageList;
    }

    /**
     * Returns whether terminal output supports colors 
     */
    public function getColor_output(): bool {
        return $this->color_output;
    }

    /**
     * Enables/disables color output on the terminal
     */
    public function setColor_output(bool $color_output): void {
        // Disable colored output if the terminal doesn't support colors
        if ($color_output && function_exists('posix_isatty')) {
            if (!posix_isatty($this->phd_info_output)) {
                $this->phd_info_color = false;
            }
            if (!posix_isatty($this->phd_warning_output)) {
                $this->phd_warning_color = false;
            }
            if (!posix_isatty($this->php_error_output)) {
                $this->php_error_color = false;
            }
            if (!posix_isatty($this->user_error_output)) {
                $this->user_error_color = false;
            }
        }
        $this->color_output = $color_output;
    }

    /**
     * Checks if indexing is needed.
     *
     * This is determined the following way:
     * 0. If no index file exists, indexing is required.
     * 1. If the config option --no-index is supplied, nothing is indexed
     * 2. If the config option --force-index is supplied, indexing is required
     * 3. If no option is given, the file modification time of the index and
     *    the manual docbook file are compared. If the index is older than
     *    the docbook file, indexing will be done.
     *
     * @return boolean True if indexing is required.
     */
    public function requiresIndexing(): bool {
        if (! $this->indexcache) {
            $indexfile = $this->output_dir . 'index.sqlite';
            if (!\file_exists($indexfile)) {
                return true;
            }
        }

        if ($this->no_index) {
            return false;
        }

        if ($this->force_index) {
            return true;
        }

        if ($this->indexcache->getIndexingTimeCount() === 0) {
            return true;
        }

        $xmlLastModification = \filemtime($this->xml_file);
        if ($this->indexcache->getIndexingTime() > $xmlLastModification) {
            return false;
        }
        return true;
    }
}
