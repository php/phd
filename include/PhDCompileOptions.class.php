<?php
/* $Id$ */

require 'PhDCommonOptions.class.php';

class PhDCompileOptionsParser extends PhDCommonOptionsParser
{
    public function getOptionList()
    {
        return array_merge(parent::getOptionList(), array(
            'output:'   => 'o:',        // Output directory or file
            'source:'   => 's:',        // Input phpdoc checkout
            'force:'    => 'f:',        // Force DOM save on validation fail
            'partial:'  => 'p:',        // Root ID
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
  -s <directory>
  --source <directory>       The source documentation checkout (default: .)
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
