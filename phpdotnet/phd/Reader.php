<?php
namespace phpdotnet\phd;

class Reader extends \XMLReader
{
    public const XMLNS_XML = "http://www.w3.org/XML/1998/namespace";
    public const XMLNS_XLINK = "http://www.w3.org/1999/xlink";
    public const XMLNS_PHD = "http://www.php.net/ns/phd";
    public const XMLNS_DOCBOOK = "http://docbook.org/ns/docbook";
    
    protected OutputHandler $outputHandler;

    public function __construct(OutputHandler $outputHandler) {
        $this->outputHandler = $outputHandler;
    }

    /* Get the content of a named node, or the current node. */
    public function readContent(?string $node = null): string {
        $retval = "";

        if($this->isEmptyElement) {
            return $retval;
        }

        if (!$node) {
            // We need libxml2.6.20 to be able to read the textual content of the node without skipping over the markup too
            if (\LIBXML_VERSION >= 20620) {
                return self::readString();
            }
            $this->outputHandler->v("You are using libxml2 v%d, but v20620 or newer is preferred", \LIBXML_VERSION, VERBOSE_OLD_LIBXML);

            $node = $this->name;
        }

        while (self::readNode($node)) {
            $retval .= $this->value;
        }
        return $retval;
    }

    private function readNode(string $nodeName): bool {
        return self::read() && !($this->nodeType === self::END_ELEMENT && $this->name === $nodeName);
    }
}
