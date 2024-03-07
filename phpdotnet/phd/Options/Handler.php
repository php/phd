<?php
namespace phpdotnet\phd;

class Options_Handler implements Options_Interface
{
    /**
     * @return array<string, string>
     */
    public function optionList(): array
    {
        return [
            'format:'      => 'f:',        // The format to render (xhtml, pdf...)
            'noindex'      => 'I',         // Do not re-index
            'forceindex'   => 'r',         // Force re-indexing under all circumstances
            'notoc'        => 't',         // Do not re-create TOC
            'docbook:'     => 'd:',        // The Docbook XML file to render from (.manual.xml)
            'output:'      => 'o:',        // The output directory
            'outputfilename:' => 'F:',     // The output filename (only useful for bightml/bigpdf)
            'partial:'     => 'p:',        // The ID to render (optionally ignoring its children)
            'skip:'        => 's:',        // The ID to skip (optionally skipping its children too)
            'verbose:'     => 'v::',       // Adjust the verbosity level
            'list'         => 'l',         // List supported packages/formats
            'lang::'       => 'L:',        // Language hint (used by the CHM)
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
            'packagedir'   => 'k:',        // Include path for external packages
        ];
    }

    public function option_M(string $k, mixed $v): void
    {
        $this->option_memoryindex($k, $v);
    }

    public function option_memoryindex(string $k, mixed $v): void
    {
        Config::set_memoryindex(true);
    }

    public function option_f(string $k, mixed $v): void
    {
        if ($k === "f") {
            $this->option_format($k, $v);
            return;
        }
        $this->option_outputfilename($k, $v);
    }

    public function option_format(string $k, mixed $v): void
    {
        $formats = [];
        foreach((array)$v as $i => $val) {
            if (!in_array($val, $formats)) {
                $formats[] = $val;
            }
        }
        Config::set_output_format($formats);
    }

    public function option_e(string $k, mixed $v): void
    {
        $this->option_ext($k, $v);
    }

    public function option_ext(string $k, mixed $v): void
    {
        $bool = self::boolval($v);
        if ($bool === false) {
            // `--ext=false` means no extension will be used
            $v = "";
            Config::setExt($v);
        } elseif ($bool === null) {
            // `--ext=true` means use the default extension,
            // `--ext=".foo"` means use ".foo" as the extension
            Config::setExt($v);
        }
    }

    public function option_g(string $k, mixed $v): void
    {
        $this->option_highlighter($k, $v);
    }

    public function option_highlighter(string $k, mixed $v): void
    {
        Config::setHighlighter($v);
    }

    public function option_i(string $k, mixed $v): void
    {
        $this->option_noindex($k, 'true');
    }

    public function option_noindex(string $k, mixed $v): void
    {
        Config::set_no_index(true);
    }

    public function option_r(string $k, mixed $v): void
    {
        $this->option_forceindex($k, 'true');
    }

    public function option_forceindex(string $k, mixed $v): void
    {
        Config::set_force_index(true);
    }

    public function option_t(string $k, mixed $v): void
    {
        $this->option_notoc($k, 'true');
    }

    public function option_notoc(string $k, mixed $v): void
    {
        Config::set_no_toc(true);
    }

    public function option_d(string $k, mixed $v): void
    {
        $this->option_docbook($k, $v);
    }

    public function option_docbook(string $k, mixed $v): void
    {
        if (is_array($v)) {
            trigger_error("Can only parse one file at a time", E_USER_ERROR);
        }
        if (!file_exists($v) || is_dir($v) || !is_readable($v)) {
            trigger_error(sprintf("'%s' is not a readable docbook file", $v), E_USER_ERROR);
        }
        Config::set_xml_root(dirname($v));
        Config::set_xml_file($v);
    }

    public function option_o(string $k, mixed $v): void
    {
        $this->option_output($k, $v);
    }

    public function option_output(string $k, mixed $v): void
    {
        if (is_array($v)) {
            trigger_error("Only a single output location can be supplied", E_USER_ERROR);
        }
        if (!is_dir($v)) {
            mkdir($v, 0777, true);
        }
        if (!is_dir($v) || !is_readable($v)) {
            trigger_error(sprintf("'%s' is not a valid directory", $v), E_USER_ERROR);
        }
        $v = (substr($v, strlen($v) - strlen(DIRECTORY_SEPARATOR)) === DIRECTORY_SEPARATOR) ? $v : ($v . DIRECTORY_SEPARATOR);
        Config::set_output_dir($v);
    }

    public function option_outputfilename(string $k, mixed $v): void
    {
        if (is_array($v)) {
            trigger_error("Only a single output location can be supplied", E_USER_ERROR);
        }
        $file = basename($v);

        Config::set_output_filename($file);
    }

    public function option_p(string $k, mixed $v): void
    {
        if ($k === "P") {
            $this->option_package($k, $v);
            return;
        }
        $this->option_partial($k, $v);
    }

    public function option_partial(string $k, mixed $v): void
    {
        $render_ids = Config::render_ids();
        foreach((array)$v as $i => $val) {
            $recursive = true;
            if (strpos($val, "=") !== false) {
                list($val, $recursive) = explode("=", $val);

                if (!is_numeric($recursive) && defined($recursive)) {
                    $recursive = constant($recursive);
                }
                $recursive = (bool) $recursive;
            }
            $render_ids[$val] = $recursive;
        }
        Config::set_render_ids($render_ids);
    }

    public function option_package(string $k, mixed $v): void
    {
        foreach((array)$v as $package) {
            if (!in_array($package, Config::getSupportedPackages())) {
                $supported = implode(', ', Config::getSupportedPackages());
                trigger_error("Invalid Package (Tried: '$package' Supported: '$supported')", E_USER_ERROR);
            }
        }
        Config::set_package($v);
    }

    public function option_q(string $k, mixed $v): void
    {
        $this->option_quit($k, $v);
    }

    public function option_quit(string $k, mixed $v): void
    {
        Config::set_quit(true);
    }

    public function option_s(string $k, mixed $v): void
    {
        if ($k === "S") {
            $this->option_saveconfig($k, $v);
            return;
        }
        $this->option_skip($k, $v);
    }

    public function option_skip(string $k, mixed $v): void
    {
        $skip_ids = Config::skip_ids();
        foreach((array)$v as $i => $val) {
            $recursive = true;
            if (strpos($val, "=") !== false) {
                list($val, $recursive) = explode("=", $val);

                if (!is_numeric($recursive) && defined($recursive)) {
                    $recursive = constant($recursive);
                }
                $recursive = (bool) $recursive;
            }
            $skip_ids[$val] = $recursive;
        }
        Config::set_skip_ids($skip_ids);
    }

    public function option_saveconfig(string $k, mixed $v): void
    {
        if (is_array($v)) {
            trigger_error(sprintf("You cannot pass %s more than once", $k), E_USER_ERROR);
        }

        // No arguments passed, default to 'true'
        if (is_bool($v)) {
            $v = "true";
        }

        $val = self::boolval($v);
        if (is_bool($val)) {
            Config::set_saveconfig($v);
        } else {
            trigger_error("yes/no || on/off || true/false || 1/0 expected", E_USER_ERROR);
        }
    }

    public function option_v(string $k, mixed $v): void
    {
        if ($k[0] === 'V') {
            $this->option_version($k, $v);
            return;
        }
        $this->option_verbose($k, $v);
    }

    public function option_verbose(string $k, mixed $v): void
    {
        static $verbose = 0;

        foreach((array)$v as $i => $val) {
            foreach(explode("|", $val) as $const) {
                if (defined($const)) {
                    $verbose |= (int)constant($const);
                } elseif (is_numeric($const)) {
                    $verbose |= (int)$const;
                } elseif (empty($const)) {
                    $verbose = max($verbose, 1);
                    $verbose <<= 1;
                } else {
                    trigger_error("Unknown option passed to --$k, '$const'", E_USER_ERROR);
                }
            }
        }
        Config::set_verbose($verbose);
        error_reporting($GLOBALS['olderrrep'] | $verbose);
    }

    public function option_l(string $k, mixed $v): void
    {
        if ($k === "L") {
            $this->option_lang($k, $v);
            return;
        }
        $this->option_list($k, $v);
    }

    public function option_list(string $k, mixed $v): never
    {
        $packageList = Config::getSupportedPackages();

        echo "Supported packages:\n";
        foreach ($packageList as $package) {
            $formats = Format_Factory::createFactory($package)->getOutputFormats();
            echo "\t" . $package . "\n\t\t" . implode("\n\t\t", $formats) . "\n";
        }

        exit(0);
    }

    public function option_lang(string $k, mixed $v): void
    {
        Config::set_language($v);
    }

    public function option_c(string $k, mixed $v): void
    {
        if ($k === "C") {
            $this->option_css($k, $v);
            return;
        }
        $this->option_color($k, $v);
    }

    public function option_color(string $k, mixed $v): void
    {
        if (is_array($v)) {
            trigger_error(sprintf("You cannot pass %s more than once", $k), E_USER_ERROR);
        }
        $val = self::boolval($v);
        if (is_bool($val)) {
            Config::setColor_output($val);
        } else {
            trigger_error("yes/no || on/off || true/false || 1/0 expected", E_USER_ERROR);
        }
    }

    public function option_css(string $k, mixed $v): void
    {
        $styles = [];
        foreach((array)$v as $key => $val) {
            if (!in_array($val, $styles)) {
                $styles[] = $val;
            }
        }
        Config::set_css($styles);
    }

    public function option_k(string $k, mixed $v): void
    {
        $this->option_packagedir($k, $v);
    }

    public function option_packagedir(string $k, mixed $v): void
    {
        $packages = Config::package_dirs();
        foreach((array)$v as $key => $val) {
            if ($path = realpath($val)) {
                if (!in_array($path, $packages)) {
                    $packages[] = $path;
                }
            } else {
                v('Invalid path: %s', $val, E_USER_WARNING);
            }
        }
        Config::set_package_dirs($packages);
    }

    public function option_x(string $k, mixed $v): void
    {
        $this->option_xinclude($k, true);
    }

    public function option_xinclude(string $k, mixed $v): void
    {
        Config::set_process_xincludes(true);
    }

    /**
     * Prints out the current PhD and PHP version.
     * Exits directly.
     *
     * @return never
     */
    public function option_version(string $k, mixed $v): never
    {
        $color  = Config::phd_info_color();
        $output = Config::phd_info_output();
        fprintf($output, "%s\n", term_color('PhD Version: ' . Config::VERSION, $color));

        $packageList = Config::getSupportedPackages();
        foreach ($packageList as $package) {
            $version = Format_Factory::createFactory($package)->getPackageVersion();
            fprintf($output, "\t%s: %s\n", term_color($package, $color), term_color($version, $color));
        }
        fprintf($output, "%s\n", term_color('PHP Version: ' . phpversion(), $color));
        fprintf($output, "%s\n", term_color(Config::copyright(), $color));
        exit(0);
    }

    public function option_h(string $k, mixed $v): never
    {
        $this->option_help($k, $v);
    }

    public function option_help(string $k, mixed $v): never
    {
        echo "PhD version: " .Config::VERSION;
        echo "\n" . Config::copyright() . "\n
  -v
  --verbose <int>            Adjusts the verbosity level
  -f <formatname>
  --format <formatname>      The build format to use
  -P <packagename>
  --package <packagename>    The package to use
  -I
  --noindex                  Do not index before rendering but load from cache
                             (default: false)
  -M
  --memoryindex              Do not save indexing into a file, store it in memory.
                             (default: false)
  -r
  --forceindex               Force re-indexing under all circumstances
                             (default: false)
  -t
  --notoc                    Do not rewrite TOC before rendering but load from
                             cache (default: false)
  -d <filename>
  --docbook <filename>       The Docbook file to render from
  -x
  --xinclude                 Process XML Inclusions (XInclude)
                             (default: false)
  -p <id[=bool]>
  --partial <id[=bool]>      The ID to render, optionally skipping its children
                             chunks (default to true; render children)
  -s <id[=bool]>
  --skip <id[=bool]>         The ID to skip, optionally skipping its children
                             chunks (default to true; skip children)
  -l
  --list                     Print out the supported packages and formats
  -o <directory>
  --output <directory>       The output directory (default: .)
  -F filename
  --outputfilename filename  Filename to use when writing standalone formats
                             (default: <packagename>-<formatname>.<formatext>)
  -L <language>
  --lang <language>          The language of the source file (used by the CHM
                             theme). (default: en)
  -c <bool>
  --color <bool>             Enable color output when output is to a terminal
                             (default: " . (Config::color_output() ? 'true' : 'false') . ")
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
  --saveconfig <bool>        Save the generated config (default: false).

  -Q
  --quit                     Don't run the build. Use with --saveconfig to
                             just save the config.
  -k
  --packagedir               Use an external package directory.


Most options can be passed multiple times for greater effect.
";
        exit(0);
    }

    /**
     * Makes a string into a boolean (i.e. on/off, yes/no, ..)
     *
     * Returns boolean true/false on success, null on failure
     *
     * @param mixed $val
     * @return ?bool
     */
    public static function boolval(mixed $val): ?bool
    {
        if (!is_string($val)) {
            return null;
        }

        switch ($val) {
            case "on":
            case "yes":
            case "true":
            case "1":
                return true;
            break;

            case "off":
            case "no":
            case "false":
            case "0":
                return false;
            break;

            default:
                return null;
        }
    }
}
