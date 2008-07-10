<?php
/* $Id$ */

require 'PhDCommonOptions.class.php';

class PhDRenderOptionsParser extends PhDCommonOptionsParser
{
    public $docbook = false;
    
    public function getOptionList()
    {
        return array_merge(parent::getOptionList(), array(
            "index:"    => "i:",        // Re-index or load from cache
            "docbook:"  => "d:",        // The Docbook XML file to render from (.manual.xml)
            "output:"   => "o:",        // The output directory
            "partial:"  => "p:",        // The ID to render (optionally ignoring its children)
            "skip:"     => "s:",        // The ID to skip (optionally skipping its children too)
        ));
    }
    
    public function option_i($k, $v)
    {
        $this->option_index($k, $v);
    }
    public function option_index($k, $v)
    {
        if (is_array($v)) {
            trigger_error(sprintf("You cannot pass %s more than once", $k), E_USER_ERROR);
        }
        $val = phd_bool($v);
        if (is_bool($val)) {
            PhDConfig::set_index($val);
        } else {
            trigger_error("yes/no || on/off || true/false || 1/0 expected", E_USER_ERROR);
        }
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
        PhDConfig::set_xml_root(dirname($v));
        PhDConfig::set_xml_file($v);
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
        PhDConfig::set_output_dir($v);
    }
    
    public function option_p($k, $v)
    {
        $this->option_partial($k, $v);
    }
    public function option_partial($k, $v)
    {
        $render_ids = PhDConfig::render_ids();
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
        PhDConfig::set_render_ids($render_ids);
    }
    
    public function option_s($k, $v)
    {
        $this->option_skip($k, $v);
    }
    public function option_skip($k, $v)
    {
        $skip_ids = PhDConfig::skip_ids();
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
        PhDConfig::set_skip_ids($skip_ids);
    }
    
    protected function getTitleText()
    {
        return "PhD Renderer";
    }
    
    protected function getHelpText()
    {
        return <<<'ENDBLOB'
  -i <bool>
  --index <bool>             Index before rendering (default) or load from cache (false)
  -d <filename>
  --docbook <filename>       The Docbook file to render from
  -p <id[=bool]>
  --partial <id[=bool]>      The ID to render, optionally skipping its children chunks (default to true; render children)
  -s <id[=bool]>
  --skip <id[=bool]>         The ID to skip, optionally skipping its children chunks (default to true; skip children)
  -o <directory>
  --output <directory>       The output directory for final generated files (default: .)
ENDBLOB;
    }
}

$optParser = new PhDRenderOptionsParser;
$optParser->getopt();

?>
