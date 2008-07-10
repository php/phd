<?php
/* $Id$ */

require 'PhDCommonOptions.class.php';

class PhDSetupOptionsParser extends PhDCommonOptionsParser
{
    public function getOptionList()
    {
        return array_merge(parent::getOptionList(), array(
//          'output:'   => 'o:',        // Output directory or file
        ));
    }
    
    protected function getTitleText()
    {
        return "PhD Setup";
    }
    
    protected function getHelpText()
    {
        return '';/*<<<'ENDBLOB'
  -o <file/directory>
  --output <file/directory>  The output directory or file (default: ./.manual.xml)
ENDBLOB;*/
    }
}

$optParser = new PhDSetupOptionsParser;
$optParser->getopt();

?>
