<?php
namespace phpdotnet\phd;
/* $Id$ */

class BuildOptionsParser
{
    public $docbook = false;
    public $verbose = 0;

    public function getOptionList()
    {
        return array(
            "format:"      => "f:",        // The format to render (xhtml, pdf...)
            "noindex"      => "I",         // Re-index or load from cache
            "docbook:"     => "d:",        // The Docbook XML file to render from (.manual.xml)
            "output:"      => "o:",        // The output directory
            "partial:"     => "p:",        // The ID to render (optionally ignoring its children)
            "skip:"        => "s:",        // The ID to skip (optionally skipping its children too)
            "verbose:"     => "v",         // Adjust the verbosity level
            "list"         => "l",         // List supported packages/formats
            "lang::"       => "L:",        // Language hint (used by the CHM)
            "color:"       => "c:",        // Use color output if possible
            'highlighter:' => 'g:',        // Class used as source code highlighter
            "version"      => "V",         // Print out version information
            "help"         => "h",         // Print out help
            "package:"     => "P:",        // The package of formats            
        );
    }

    public function option_f($k, $v)
    {
        $this->option_format($k, $v);
    }
    public function option_format($k, $v)
    {
        $formats = array();
        foreach((array)$v as $i => $val) {
            if (!in_array($val, $formats)) {
                $formats[] = $val;
            }
        }
        Config::set_output_format($formats);
    }

    public function option_g($k, $v)
    {
        $this->option_highlighter($k, $v);
    }
    public function option_highlighter($k, $v)
    {
        Config::setHighlighter($v);
    }
   
    public function option_i($k, $v)
    {
        $this->option_noindex($k, 'true');
    }
    public function option_noindex($k, $v)
    {
        Config::set_index(false);
    }

    public function option_d($k, $v)
    {
        $this->option_docbook($k, $v);
    }
    public function option_docbook($k, $v)
    {
        if (is_array($v)) {
            trigger_error("Can only parse one file at a time", E_USER_ERROR);
        }
        if (!file_exists($v) || is_dir($v) || !is_readable($v)) {
            trigger_error(sprintf("'%s' is not a readable docbook file", $v), E_USER_ERROR);
        }
        Config::set_xml_root(dirname($v));
        Config::set_xml_file($v);
        $this->docbook = true;
    }

    public function option_o($k, $v)
    {
        $this->option_output($k, $v);
    }
    public function option_output($k, $v)
    {
        if (is_array($v)) {
            trigger_error("Only a single output location can be supplied", E_USER_ERROR);
        }
        @mkdir($v, 0777, true);
        if (!is_dir($v) || !is_readable($v)) {
            trigger_error(sprintf("'%s' is not a valid directory", $v), E_USER_ERROR);
        }
        $v = (substr($v, strlen($v) - strlen(DIRECTORY_SEPARATOR)) == DIRECTORY_SEPARATOR) ? $v : ($v . DIRECTORY_SEPARATOR);
        Config::set_output_dir($v);
    }

    public function option_p($k, $v)
    {
        if ($k == "P") {
            return $this->option_package($k, $v);
        }
        $this->option_partial($k, $v);
    }
    public function option_partial($k, $v)
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

    public function option_package($k, $v) {
        
        if (in_array($v, Config::getSupportedPackages())) {
            Config::set_package($v);
        } else {
            trigger_error("Invalid Package", E_USER_ERROR);
        }
    }

    public function option_s($k, $v)
    {
        $this->option_skip($k, $v);
    }
    public function option_skip($k, $v)
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

    public function option_v($k, $v)
    {
        if ($k[0] === 'V') {
            $this->option_version($k, $v);
            return;
        }

        if (is_array($v)) {
            foreach($v as $i => $val) {
                $this->verbose |= pow(2, $i);
            }
        } else {
            $this->verbose |= 1;
        }
        Config::set_verbose($this->verbose);
        error_reporting($GLOBALS['olderrrep'] | $this->verbose);
    }

    public function option_verbose($k, $v)
    {
        foreach((array)$v as $i => $val) {
            foreach(explode("|", $val) as $const) {
                if (defined($const)) {
                    $this->verbose |= (int)constant($const);
                } elseif (is_numeric($const)) {
                    $this->verbose |= (int)$const;
                } else {
                    trigger_error("Unknown option passed to --$k, $const", E_USER_ERROR);
                }
            }
        }
        Config::set_verbose($this->verbose);
        error_reporting($GLOBALS['olderrrep'] | $this->verbose);
    }

    public function option_l($k, $v)
    {
        if ($k == "L") {
            return $this->option_lang($k, $v);
        }
        $this->option_list($k, $v);
    }
    public function option_list($k, $v)
    {
        $packageList = Config::getSupportedPackages();
        
        echo "Supported packages:\n";
        foreach ($packageList as $package) {
            $formats = Format_Factory::createFactory($package)->getOutputFormats();
            echo "\t" . $package . "\n\t\t" . implode("\n\t\t", $formats) . "\n";
        }
      
        exit(0);
    }

    public function option_lang($k, $v)
    {
        Config::set_language($v);
    }
    public function option_c($k, $v)
    {
        $this->option_color($k, $v);
    }
    public function option_color($k, $v)
    {
        if (is_array($v)) {
            trigger_error(sprintf("You cannot pass %s more than once", $k), E_USER_ERROR);
        }
        $val = phd_bool($v);
        if (is_bool($val)) {
            if ($val && function_exists('posix_isatty')) {
                Config::set_phd_info_color(posix_isatty(Config::phd_info_output()) ? '01;32' : false);         // Bright (bold) green
                Config::set_user_error_color(posix_isatty(Config::user_error_output()) ? '01;33' : false);     // Bright (bold) yellow
                Config::set_php_error_color(posix_isatty(Config::php_error_output()) ? '01;31' : false);       // Bright (bold) red
            } else {
                Config::set_phd_info_color(false);
                Config::set_user_error_color(false);
                Config::set_php_error_color(false);
            }
        } else {
            trigger_error("yes/no || on/off || true/false || 1/0 expected", E_USER_ERROR);
        }
    }

    public function option_version($k, $v)
    {
        $color = Config::phd_info_color();
        $output = Config::phd_info_output();
        fprintf($output, "%s\n", term_color("PhD Version: " . Config::VERSION, $color));
        fprintf($output, "%s\n", term_color("Copyright(c) 2007-2009 The PHP Documentation Group", $color));
        exit(0);
    }

    public function option_h($k, $v)
    {
        $this->option_help($k, $v);
    }
    public function option_help($k, $v)
    {
        echo "PhD version: " .Config::VERSION;
        echo "\nCopyright (c) 2007-2009 The PHP Documentation Group\n
  -v
  --verbose <int>            Adjusts the verbosity level
  -f <formatname>
  --format <formatname>      The build format to use
  -P <packagename>
  --package <packagename>    The package to use
  -I
  --noindex                  Do not index before rendering but load from cache
                             (default: false)
  -d <filename>
  --docbook <filename>       The Docbook file to render from
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
  -L <language>
  --lang <language>          The language of the source file (used by the CHM
                             theme). (default: en)
  -c <bool>
  --color <bool>             Enable color output when output is to a terminal
                             (default: false)
  -g <classname>
  --highlighter <classname>  Use custom source code highlighting php class
  -V
  --version                  Print the PhD version information
  -h
  --help                     This help

Most options can be passed multiple times for greater affect.
";
        exit(0);
    }

    public function handlerForOption($opt)
    {
        if (method_exists($this, "option_{$opt}")) {
            return array($this, "option_{$opt}");
        } else {
            return NULL;
        }
    }

    public function getopt()
    {
        $opts = $this->getOptionList();
        $args = getopt(implode("", array_values($opts)), array_keys($opts));
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

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

