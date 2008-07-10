<?php
/* $Id$ */

require 'PhDCommonOptions.class.php';

class PhDCompileOptionsParser extends PhDCommonOptionsParser
{
    public function getOptionList()
    {
        return array_merge(parent::getOptionList(), array(
            "force:"    => "f:",        // Force DOM save on validation fail
            "partial:"  => "p:",        // Root ID
        ));
    }
    
    protected function getTitleText()
    {
        return "PhD XML Compiler";
    }
    
    protected function getHelpText()
    {
        return <<<'ENDBLOB'
  -f <bool>
  --force <bool>             Save (true) invalid XML with errors or discard (default).
  -p <id>
  --partial <id>             The root ID to render (default all).
ENDBLOB;
    }
}

$optParser = new PhDCompileOptionsParser;
$optParser->getopt();

?>
