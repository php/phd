<?php
/* $Id$ */

require 'PhDCommonOptions.class.php';

class PhDSetupOptionsParser extends PhDCommonOptionsParser
{
    public function getOptionList()
    {
        return array_merge(parent::getOptionList(), array(
            "format:"   => "f:",        // The format to render (xhtml, pdf...)
            "theme:"    => "t:",        // The theme to render (phpweb, bightml..)
            "list::"    => "l::",       // List supported themes/formats
            "output:"   => "o:",        // Intermediate output directory
        ));
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
        if (!is_dir($v) || !is_readable($v) || !is_writable($v)) {
            trigger_error(sprintf("'%s' is not a valid directory", $v), E_USER_ERROR);
        }
        PhDConfig::set_intermediate_output_dir($v);
    }
    
    public function option_f($k, $v)
    {
        $this->option_format($k, $v);
    }
    public function option_format($k, $v)
    {
        if ($v != "xhtml") {
            trigger_error("Only xhtml is supported at this time", E_USER_ERROR);
        }
    }
    
    public function option_t($k, $v)
    {
        $this->option_theme($k, $v);
    }
    public function option_theme($k, $v)
    {
        /* Remove the default themes */
        $themes = PhDConfig::output_theme();
        $themes['xhtml']['php'] = array();

        foreach((array)$v as $i => $val) {
            switch($val) {
                case "phpweb":
                case "chunkedhtml":
                case "bightml":
                case "chmsource":
                    if (!in_array($val, $themes["xhtml"]["php"])) {
                        $themes["xhtml"]["php"][] = $val;
                    }
                    break;
                default:
                    trigger_error(sprintf("Unknown theme '%s'", $val), E_USER_ERROR);
            }
        }
        PhDConfig::set_output_theme($themes);
    }
    
    public function option_l($k, $v)
    {
        $this->option_list($k, $v);
    }
    public function option_list($k, $v)
    {
        static $formatList = NULL;
        static $themeList = NULL;
        
        if (is_null($formatList)) {
            $formatList = array();
            foreach (glob($GLOBALS['ROOT'] . "/formats/*.php") as $item) {
                $formatList[] = substr(basename($item), 0, -4);
            }
        }
        if (is_null($themeList)) {
            $themeList = array();
            foreach (glob($GLOBALS['ROOT'] . "/themes/*", GLOB_ONLYDIR) as $item) {
                if (!in_array(basename($item), array('CVS', '.', '..'))) {
                    $maintheme = basename($item);
                    $subthemes = array();
                    foreach (glob($item . "/*.php") as $subitem) {
                        $subthemes[] = substr(basename($subitem), 0, -4);
                    }
                    $themeList[$maintheme] = $subthemes;
                }
            }
        }
        
        if ($v === false) {
            $v = array('f', 't');
        }
        
        foreach((array)$v as $val) {
            switch($val) {
                case "f":
                case "format":
                case "formats": {
                    echo "Supported formats:\n";
                    echo "\t" . implode("\n\t", $formatList) . "\n";
                    break;
                }

                case "t":
                case "theme":
                case "themes":
                    echo "Supported themes:\n";
                    foreach ($themeList as $theme => $subthemes) {
                        echo "\t" . $theme . "\n\t\t" . implode("\n\t\t", $subthemes) . "\n";
                    }
                    break;

                default:
                    echo "Unknown list type '$val'\n";
                    break;
            }
        }
        exit(0);
    }
    
    protected function getTitleText()
    {
        return "PhD Setup";
    }
    
    protected function getHelpText()
    {
        return <<<'ENDBLOB'
  -o <directory>
  --output <directory>       The output directory for intermediate build files (default: .)
  -f <formatname>
  --format <formatname>      The output format to use
  -t <themename>
  --theme <themename>        The theme to use
  -l <formats/themes>
  --list <formats/themes>    Print out the supported formats/themes (default: both)
ENDBLOB;
    }
}

$optParser = new PhDSetupOptionsParser;
$optParser->getopt();

?>
