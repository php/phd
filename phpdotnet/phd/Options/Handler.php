<?php
namespace phpdotnet\phd;

class Options_Handler implements Options_Interface
{
    public function __construct(
        private Config $config,
        private Format_Factory $formatFactory,
        private OutputHandler $outputHandler
    ) {}

    /**
     * @return array<string, string>
     */
    public function optionList(): array
    {
        return [
            'format:'      => 'f:',        // The format to render (xhtml, troff...)
            'noindex'      => 'I',         // Do not re-index
            'forceindex'   => 'r',         // Force re-indexing under all circumstances
            'notoc'        => 't',         // Do not re-create TOC
            'docbook:'     => 'd:',        // The Docbook XML file to render from (.manual.xml)
            'output:'      => 'o:',        // The output directory
            'outputfilename:' => 'F:',     // The output filename (only useful for bightml)
            'partial:'     => 'p:',        // The ID to render (optionally ignoring its children)
            'skip:'        => 's:',        // The ID to skip (optionally skipping its children too)
            'verbose::'    => 'v::',       // Adjust the verbosity level
            'list'         => 'l',         // List supported packages/formats
            'lang:'        => 'L:',        // Language hint (used by the CHM)
            'color:'       => 'c:',        // Use color output if possible
            'highlighter:' => 'g:',        // Class used as source code highlighter
            'version'      => 'V',         // Print out version information
            'help'         => 'h',         // Print out help
            'package:'     => 'P:',        // The package of formats
            'css:'         => 'C:',        // External CSS
            'xinclude'     => 'x',         // Automatically process xinclude directives
            'ext:'         => 'e:',        // The file-format extension to use, including the dot
            'saveconfig::' => 'S::',       // Save the generated config ?
            'quit'         => 'Q',         // Do not run the render. Use with -S to just save the config.
            'memoryindex'  => 'M',         // Use sqlite in memory rather then file
            'packagedir:'  => 'k:',        // Include path for external packages
        ];
    }

    /**
     * @return array<string, true>
     */
    public function option_M(string $k, mixed $v): array
    {
        return $this->option_memoryindex($k, $v);
    }

    /**
     * @return array<string, true>
     */
    public function option_memoryindex(string $k, mixed $v): array
    {
        return ['memoryIndex' => true];
    }

    /**
     * @return array<string, string|array<string>>
     */
    public function option_f(string $k, mixed $v): array
    {
        if ($k === "f") {
            return $this->option_format($k, $v);
        }
        return $this->option_outputfilename($k, $v);
    }

    /**
     * @return array<string, array<string>>
     */
    public function option_format(string $k, mixed $v): array
    {
        $formats = [];
        foreach((array)$v as $val) {
            if (!in_array($val, $formats)) {
                $formats[] = $val;
            }
        }
        return ['outputFormat' => $formats];
    }

    /**
     * @return array<string, mixed>
     */
    public function option_e(string $k, mixed $v): array
    {
        return $this->option_ext($k, $v);
    }

    /**
     * @return array<string, mixed>
     */
    public function option_ext(string $k, mixed $v): array
    {
        // `--ext=false`: no extension will be used
        // `--ext=true`: use the default extension
        // `--ext=".foo"`: use the ".foo" extension
        return match (self::boolval($v)) {
            false => ['ext' => ''],
            true => ['ext' => null],
            null => ['ext' => $v]
        };
    }

    /**
     * @return array<string, string>
     */
    public function option_g(string $k, mixed $v): array
    {
        return $this->option_highlighter($k, $v);
    }

    /**
     * @return array<string, string>
     */
    public function option_highlighter(string $k, mixed $v): array
    {
        return ['highlighter' => $v];
    }

    /**
     * @return array<string, true>
     */
    public function option_i(string $k, mixed $v): array
    {
        return $this->option_noindex($k, $v);
    }

    /**
     * @return array<string, true>
     */
    public function option_noindex(string $k, mixed $v): array
    {
        return ['noIndex' => true];
    }

    /**
     * @return array<string, true>
     */
    public function option_r(string $k, mixed $v): array
    {
        return $this->option_forceindex($k, $v);
    }

    /**
     * @return array<string, true>
     */
    public function option_forceindex(string $k, mixed $v): array
    {
        return ['forceIndex' => true];
    }

    /**
     * @return array<string, true>
     */
    public function option_t(string $k, mixed $v): array
    {
        return $this->option_notoc($k, $v);
    }

    /**
     * @return array<string, true>
     */
    public function option_notoc(string $k, mixed $v): array
    {
        return ['noToc' => true];
    }

    /**
     * @return array<string, string>
     */
    public function option_d(string $k, mixed $v): array
    {
        return $this->option_docbook($k, $v);
    }

    /**
     * @return array<string, string>
     */
    public function option_docbook(string $k, mixed $v): array
    {
        if (is_array($v)) {
            throw new \Error('Can only parse one file at a time');
        }
        if (!file_exists($v) || is_dir($v) || !is_readable($v)) {
            throw new \Error(sprintf("'%s' is not a readable docbook file", $v));
        }
        return [
            'xmlRoot' => dirname($v),
            'xmlFile' => $v,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function option_o(string $k, mixed $v): array
    {
        return $this->option_output($k, $v);
    }

    /**
     * @return array<string, string>
     */
    public function option_output(string $k, mixed $v): array
    {
        if (is_array($v)) {
            throw new \Error('Only a single output location can be supplied');
        }
        if (!file_exists($v)) {
            $this->outputHandler->v("Creating output directory..", VERBOSE_MESSAGES);
            if (!mkdir($v, 0777, true)) {
                throw new \Error(vsprintf("Can't create output directory : %s", [$v]));
            }
            $this->outputHandler->v("Output directory created", VERBOSE_MESSAGES);
        } elseif (!is_dir($v)) {
            throw new \Error('Output directory is a file?');
        }
        if (!is_dir($v) || !is_readable($v)) {
            throw new \Error(sprintf("'%s' is not a valid directory", $v));
        }
        $v = (substr($v, strlen($v) - strlen(DIRECTORY_SEPARATOR)) === DIRECTORY_SEPARATOR) ? $v : ($v . DIRECTORY_SEPARATOR);

        return ['outputDir' => $v];
    }

    /**
     * @return array<string, string>
     */
    public function option_outputfilename(string $k, mixed $v): array
    {
        if (is_array($v)) {
            throw new \Error('Only a single output location can be supplied');
        }
        $file = basename($v);

        return ['outputFilename' => $file];
    }

    /**
     * @return array<string, array<string>>
     */
    public function option_p(string $k, mixed $v): array
    {
        if ($k === "P") {
            return $this->option_package($k, $v);
        }
        return $this->option_partial($k, $v);
    }

    /**
     * @return array<string, array<string>>
     */
    public function option_partial(string $k, mixed $v): array
    {
        $renderIds = $this->config->renderIds;
        foreach((array)$v as $val) {
            $recursive = true;
            if (strpos($val, "=") !== false) {
                list($val, $recursive) = explode("=", $val);

                if (!is_numeric($recursive) && defined($recursive)) {
                    $recursive = constant($recursive);
                }
                $recursive = (bool) $recursive;
            }
            $renderIds[$val] = $recursive;
        }
        return ['renderIds' => $renderIds];
    }

    /**
     * @return array<string, array<string>>
     */
    public function option_package(string $k, mixed $v): array
    {
        foreach((array)$v as $package) {
            if (!in_array($package, $this->config->getSupportedPackages())) {
                $supported = implode(', ', $this->config->getSupportedPackages());
                throw new \Error("Invalid Package (Tried: '$package' Supported: '$supported')");
            }
        }
        return ['package' => (array) $v];
    }

    /**
     * @return array<string, true>
     */
    public function option_q(string $k, mixed $v): array
    {
        return $this->option_quit($k, $v);
    }

    /**
     * @return array<string, true>
     */
    public function option_quit(string $k, mixed $v): array
    {
        return ['quit' => true];
    }

    /**
     * @return array<string, bool|array<string>>
     */
    public function option_s(string $k, mixed $v): array
    {
        if ($k === "S") {
            return $this->option_saveconfig($k, $v);
        }
        return $this->option_skip($k, $v);
    }

    /**
     * @return array<string, array<string>>
     */
    public function option_skip(string $k, mixed $v): array
    {
        $skipIds = $this->config->skipIds;
        foreach((array)$v as $val) {
            $recursive = true;
            if (strpos($val, "=") !== false) {
                list($val, $recursive) = explode("=", $val);

                if (!is_numeric($recursive) && defined($recursive)) {
                    $recursive = constant($recursive);
                }
                $recursive = (bool) $recursive;
            }
            $skipIds[$val] = $recursive;
        }
        return ['skipIds' => $skipIds];
    }

    /**
     * @return array<string, bool>
     */
    public function option_saveconfig(string $k, mixed $v): array
    {
        if (is_array($v)) {
            throw new \Error(sprintf('You cannot pass %s more than once', $k));
        }

        $val = is_bool($v) ? true : self::boolval($v);

        if (!is_bool($val)) {
            throw new \Error('yes/no || on/off || true/false || 1/0 expected');
        }

        return ['saveConfig' => $val];
    }

    /**
     * @return array<string, int>
     */
    public function option_v(string $k, mixed $v): array
    {
        if ($k[0] === 'V') {
            return $this->option_version($k, $v);
        }
        return $this->option_verbose($k, $v);
    }

    /**
     * @return array<string, int>
     */
    public function option_verbose(string $k, mixed $v): array
    {
        static $verbose = 0;

        foreach((array)$v as $val) {
            foreach(explode("|", $val) as $const) {
                if (defined($const)) {
                    $verbose |= (int)constant($const);
                } elseif (is_numeric($const)) {
                    $verbose |= (int)$const;
                } elseif (empty($const)) {
                    $verbose = max($verbose, 1);
                    $verbose <<= 1;
                } else {
                    throw new \Error("Unknown option passed to --$k, '$const'");
                }
            }
        }
        error_reporting($GLOBALS['olderrrep'] | $verbose);
        return ['verbose' => $verbose];
    }

    /**
     * @return array<string, string>
     */
    public function option_l(string $k, mixed $v): array
    {
        if ($k === "L") {
            return $this->option_lang($k, $v);
        }
        return $this->option_list($k, $v);
    }

    public function option_list(string $k, mixed $v): never
    {
        $packageList = $this->config->getSupportedPackages();

        echo "Supported packages:\n";
        foreach ($packageList as $package) {
            $formats = $this->formatFactory::createFactory($package)->getOutputFormats();
            echo "\t" . $package . "\n\t\t" . implode("\n\t\t", $formats) . "\n";
        }

        exit(0);
    }

    /**
     * @return array<string, string>
     */
    public function option_lang(string $k, mixed $v): array
    {
        return ['language' => $v];
    }

    /**
     * @return array<string, bool|array<string>>
     */
    public function option_c(string $k, mixed $v): array
    {
        if ($k === "C") {
            return $this->option_css($k, $v);
        }
        return $this->option_color($k, $v);
    }

    /**
     * @return array<string, bool>
     */
    public function option_color(string $k, mixed $v): array
    {
        if (is_array($v)) {
            throw new \Error(sprintf('You cannot pass %s more than once', $k));
        }
        $val = self::boolval($v);
        if (!is_bool($val)) {
            throw new \Error('yes/no || on/off || true/false || 1/0 expected');
        }
        return ['colorOutput' => $val];
    }

    /**
     * @return array<string, array<string>>
     */
    public function option_css(string $k, mixed $v): array
    {
        $styles = [];
        foreach((array)$v as $val) {
            if (!in_array($val, $styles)) {
                $styles[] = $val;
            }
        }
        return ['css' => $styles];
    }

    /**
     * @return array<string, array<string>>
     */
    public function option_k(string $k, mixed $v): array
    {
        return $this->option_packagedir($k, $v);
    }

    /**
     * @return array<string, array<string>>
     */
    public function option_packagedir(string $k, mixed $v): array
    {
        $packages = $this->config->packageDirs;
        foreach((array)$v as $val) {
            if ($path = realpath($val)) {
                if (!in_array($path, $packages)) {
                    $packages[] = $path;
                }
            } else {
                trigger_error(vsprintf('Invalid path: %s', [$val]), E_USER_WARNING);
            }
        }
        return ['packageDirs' => $packages];
    }

    /**
     * @return array<string, true>
     */
    public function option_x(string $k, mixed $v): array
    {
        return $this->option_xinclude($k, true);
    }

    /**
     * @return array<string, true>
     */
    public function option_xinclude(string $k, mixed $v): array
    {
        return ['processXincludes' => true];
    }

    /**
     * Prints out the current PhD and PHP version.
     * Exits directly.
     *
     * @return never
     */
    public function option_version(string $k, mixed $v): never
    {
        $this->outputHandler->printPhdInfo('PhD Version: ' . $this->config::VERSION);
        $packageList = $this->config->getSupportedPackages();
        foreach ($packageList as $package) {
            $version = $this->formatFactory::createFactory($package)->getPackageVersion();
            $this->outputHandler->printPhdInfo("\t$package: $version");
        }
        $this->outputHandler->printPhdInfo('PHP Version: ' . phpversion());
        $this->outputHandler->printPhdInfo($this->config->copyright);
        exit(0);
    }

    public function option_h(string $k, mixed $v): never
    {
        $this->option_help($k, $v);
    }

    public function option_help(string $k, mixed $v): never
    {
        echo "PhD version: " .$this->config::VERSION;
        echo "\n" . $this->config->copyright . "\n
  -v
  --verbose <int>            Adjusts the verbosity level
  -f <formatname>
  --format <formatname>      The build format to use
  -P <packagename>
  --package <packagename>    The package to use
  -I
  --noindex                  Do not index before rendering but load from cache
                             (default: " . ($this->config->noIndex ? 'true' : 'false') . ")
  -M
  --memoryindex              Do not save indexing into a file, store it in memory.
                             (default: " . ($this->config->memoryIndex ? 'true' : 'false') . ")
  -r
  --forceindex               Force re-indexing under all circumstances
                             (default: " . ($this->config->forceIndex ? 'true' : 'false') . ")
  -t
  --notoc                    Do not rewrite TOC before rendering but load from
                             cache (default: " . ($this->config->noToc ? 'true' : 'false') . ")
  -d <filename>
  --docbook <filename>       The Docbook file to render from
  -x
  --xinclude                 Process XML Inclusions (XInclude)
                             (default: " . ($this->config->processXincludes ? 'true' : 'false') . ")
  -p <id[=bool]>
  --partial <id[=bool]>      The ID to render, optionally skipping its children
                             chunks (default to true; render children)
  -s <id[=bool]>
  --skip <id[=bool]>         The ID to skip, optionally skipping its children
                             chunks (default to true; skip children)
  -l
  --list                     Print out the supported packages and formats
  -o <directory>
  --output <directory>       The output directory (default: " . $this->config->outputDir . ")
  -F filename
  --outputfilename filename  Filename to use when writing standalone formats
                             (default: <packagename>-<formatname>.<formatext>)
  -L <language>
  --lang <language>          The language of the source file (used by the CHM
                             theme). (default: " . $this->config->language . ")
  -c <bool>
  --color <bool>             Enable color output when output is to a terminal
                             (default: " . ($this->config->getColorOutput() ? 'true' : 'false') . ")
  -C <filename>
  --css <filename>           Link for an external CSS file.
  -g <classname>
  --highlighter <classname>  Use custom source code highlighting php class
  -V
  --version                  Print the PhD version information
  -h
  --help                     This help
  -e <extension>
  --ext <extension>          The alternative filename extension to use,
                             including the dot. Use 'false' for no extension.
  -S <bool>
  --saveconfig <bool>        Save the generated config (default: " . ($this->config->saveConfig ? 'true' : 'false') . ").

  -Q
  --quit                     Don't run the build. Use with --saveconfig to
                             just save the config (default: " . ($this->config->quit ? 'true' : 'false') . ").
  -k
  --packagedir               Use an external package directory.


Most options can be passed multiple times for greater effect.
";
        exit(0);
    }

    /**
     * Makes one of the following strings into a boolean:
     * "on", "off", "yes", "no", "false", "true", "0", "1"
     *
     * Returns boolean true/false on success, null on failure
     */
    private static function boolval(mixed $val): ?bool
    {
        if (!is_string($val)) {
            return null;
        }

        return match ($val) {
            "on", "yes", "true", "1" => true,
            "off", "no", "false", "0" => false,
            default => null
        };
    }
}
