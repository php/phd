<?php
/* $Id$ */

require 'PhDCommonOptions.class.php';

class PhDCompileOptionsParser extends PhDCommonOptionsParser
{
    public function getOptionList()
    {
        return array_merge(parent::getOptionList(), array(
//          "??????:"   => "?:",        // ?????
        ));
    }
    
    protected function getTitleText()
    {
        return "PhD XML Compiler";
    }
    
    protected function getHelpText()
    {
        return <<<'ENDBLOB'
  -o <file/directory>
  --output <file/directory>  The output directory or file (default: ./.manual.xml)
ENDBLOB;
    }
}

$optParser = new PhDCompileOptionsParser;
$optParser->getopt();

?>
