<?php
namespace phpdotnet\phd;

class Config
{
    public const VERSION = '@phd_version@';
    public readonly string $copyright;

    /** @var array<string, string> */
    public array $outputFormat = [];
    public bool $noIndex = false;
    public bool $forceIndex = false;
    public bool $noToc = false;
    public string $xmlRoot = '.';
    public string $xmlFile = './.manual.xml';
    public string $historyFile = './fileModHistory.php';
    public string $langDir = './';
    public string $language = 'en';
    public string $fallbackLanguage = 'en';
    public int $verbose = VERBOSE_DEFAULT;
    public string $dateFormat = 'H:i:s';
    /** @var array<string> */
    public array $renderIds = [];
    /** @var array<string> */
    public array $skipIds = [];
    private bool $colorOutput = true;
    public string $outputDir = './output/';
    public string $outputFilename = '';
    /** @var resource */
    public $phpErrorOutput = \STDERR;
    public string|false $phpErrorColor = '01;31'; // Red
    /** @var resource */
    public $userErrorOutput = \STDERR;
    public string|false $userErrorColor = '01;33'; // Yellow
    /** @var resource */
    public $phdInfoOutput = \STDOUT;
    public string|false $phdInfoColor = '01;32'; // Green
    /** @var resource */
    public $phdWarningOutput = \STDOUT;
    public string|false $phdWarningColor = '01;35'; // Magenta
    public string $highlighter = 'phpdotnet\\phd\\Highlighter';
    /** @var array<string> */
    public array $package =['Generic'];
    /** @var array<string> $css */
    public array $css = [];
    public bool $processXincludes = false;
    public ?string $ext = null;
    /** @var array<string> */
    public array $packageDirs = [__INSTALLDIR__];
    public bool $saveConfig = false;
    public bool $quit = false;
    public ?IndexRepository $indexCache = null;
    public bool $memoryIndex = false;

    public string $phpwebVersionFilename = '';
    public string $phpwebAcronymFilename = '';
    public string $phpwebSourcesFilename = '';
    public string $phpwebHistoryFilename = '';
    
    private const NON_SERIALIZABLE_PROPERTIES = [
        "copyright",
        "indexCache",
        "phpErrorOutput",
        "userErrorOutput",
        "phdInfoOutput",
        "phdWarningOutput",
    ];
    
    public function __construct() {
        $this->copyright = 'Copyright(c) 2007-'  . \date('Y') . ' The PHP Documentation Group';
        
        if('WIN' === \strtoupper(\substr(\PHP_OS, 0, 3))) {
        	$this->colorOutput = false;
        }
    }
    
    /**
     * Sets one or more configuration options from an array
     * 
     * @param array<string, mixed> $configOptions
     */
    public function init(array $configOptions): void {
        foreach ($configOptions as $option => $value) {
            if (! \property_exists($this, $option)) {
                throw new \Exception("Invalid option supplied: $option");
            }
            
            if (\in_array($option, self::NON_SERIALIZABLE_PROPERTIES, true)) {
                continue;
            }
            
            $this->$option = $value;
        }
        
        \error_reporting($GLOBALS['olderrrep'] | $this->verbose);
    }

    /**
     * Returns all serializable configuration options and their values
     * 
     * @return array<string, mixed>
     */
    public function getAllSerializableProperties(): array {
        $object_vars = \get_object_vars($this);
        
        foreach (self::NON_SERIALIZABLE_PROPERTIES as $property) {
            unset($object_vars[$property]);
        }
        
        return $object_vars;
    }

    /**
     * Returns the list of supported formats from the package directories set
     * 
     * @return array<string>
     */
    public function getSupportedPackages(): array {
        $packageList = [];
        foreach($this->packageDirs as $dir) {
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
    public function getColorOutput(): bool {
        return $this->colorOutput;
    }

    /**
     * Enables/disables color output on the terminal
     */
    public function setColorOutput(bool $colorOutput): void {
        // Disable colored output if the terminal doesn't support colors
        if ($colorOutput && function_exists('posix_isatty')) {
            if (!posix_isatty($this->phdInfoOutput)) {
                $this->phdInfoColor = false;
            }
            if (!posix_isatty($this->phdWarningOutput)) {
                $this->phdWarningColor = false;
            }
            if (!posix_isatty($this->phpErrorOutput)) {
                $this->phpErrorColor = false;
            }
            if (!posix_isatty($this->userErrorOutput)) {
                $this->userErrorColor = false;
            }
        }
        $this->colorOutput = $colorOutput;
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
        if (! $this->indexCache) {
            $indexfile = $this->outputDir . 'index.sqlite';
            if (!\file_exists($indexfile)) {
                return true;
            }
        }

        if ($this->noIndex) {
            return false;
        }

        if ($this->forceIndex) {
            return true;
        }

        if ($this->indexCache->getIndexingTimeCount() === 0) {
            return true;
        }

        $xmlLastModification = \filemtime($this->xmlFile);
        if ($this->indexCache->getIndexingTime() > $xmlLastModification) {
            return false;
        }
        return true;
    }
}
