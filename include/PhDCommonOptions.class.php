<?php
/* $Id$ */

abstract class PhDCommonOptionsParser extends PhDOptionParser
{
    public $verbose = 0;
    
    abstract protected function getTitleText();
    abstract protected function getHelpText();
    
    public function getOptionList()
    {
        return array(
            "source:"   => "s:",        // Input data
            "color:"    => "c:",        // Use color output if possible
            "verbose:"  => "v",         // Adjust the verbosity level
            "version"   => "V",         // Print out version information
            "help"      => "h",         // Print out help
        );
    }
    
    public function option_s($k, $v)
    {
        $this->option_source($k, $v);
    }
    public function option_source($k, $v)
    {
        if (is_array($v)) {
            trigger_error("Only a single input location can be supplied", E_USER_ERROR);
        }
        if (!is_dir($v) || !is_readable($v)) {
            trigger_error(sprintf("'%s' is not a valid directory", $v), E_USER_ERROR);
        }
        PhDConfig::set_source_dir($v);
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
        PhDConfig::set_verbose($this->verbose);
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
        PhDConfig::set_verbose($this->verbose);
        error_reporting($GLOBALS['olderrrep'] | $this->verbose);
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
                PhDConfig::set_phd_info_color(posix_isatty(PhDConfig::phd_info_output()) ? '01;32' : false);         // Bright (bold) green
                PhDConfig::set_user_error_color(posix_isatty(PhDConfig::user_error_output()) ? '01;33' : false);     // Bright (bold) yellow
                PhDConfig::set_php_error_color(posix_isatty(PhDConfig::php_error_output()) ? '01;31' : false);       // Bright (bold) red
            } else {
                PhDConfig::set_phd_info_color(false);
                PhDConfig::set_user_error_color(false);
                PhDConfig::set_php_error_color(false);
            }
        } else {
            trigger_error("yes/no || on/off || true/false || 1/0 expected", E_USER_ERROR);
        }
    }
    
    public function option_version($k, $v)
    {
        $color = PhDConfig::phd_info_color();
        $output = PhDConfig::phd_info_output();
        $rev = preg_replace('/\$Re[v](?:ision)?(: ([\d.]+) ?)?\$$/e', "'\\1' == '' ? '??' : '\\2'", $GLOBALS['base_revision']);
        fprintf($output, "%s\n", term_color($this->getTitleText(), $color));
        fprintf($output, "%s\n", term_color("PhD Version: " . PHD_VERSION . " (" . $rev . ")", $color));
        fprintf($output, "%s\n", term_color("Copyright(c) 2008 The PHP Documentation Group", $color));
        exit(0);
    }
    
    public function option_h($k, $v)
    {
        $this->option_help($k, $v);
    }
    public function option_help($k, $v)
    {
        echo $this->getTitleText() . "\n";
        echo "PhD version: " . PHD_VERSION . "\n";
        echo <<<ENDBLOB
Copyright (c) 2008 The PHP Documentation Group

{$this->getHelpText()}  
  -s <directory>
  --source <directory>       The source documentation checkout or build area (default: .)
  -v
  --verbose <int>            Adjusts the verbosity level
  -c <bool>
  --color <bool>             Enable color output when output is to a terminal (default: false)
  -V
  --version                  Print the PhD version information
  -h
  --help                     This help

Most options can be passed multiple times for greater effect.

ENDBLOB;
        exit(0);
    }
}

?>
