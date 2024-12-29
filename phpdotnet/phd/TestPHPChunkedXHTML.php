<?php
namespace phpdotnet\phd;

class TestPHPChunkedXHTML extends Package_PHP_ChunkedXHTML {
    public function __construct(
        Config $config,
        OutputHandler $outputHandler
    ) {
        parent::__construct($config, $outputHandler);
    }

    public function update($event, $value = null) {
        switch($event) {
        case Render::CHUNK:
            parent::update($event, $value);
            break;
        case Render::STANDALONE:
            parent::update($event, $value);
            break;
        case Render::INIT:
            $this->setOutputDir($this->config->outputDir . strtolower($this->getFormatName()) . '/');
            break;
        //No verbose
        }
    }

    public function writeChunk($id, $fp) {
        $filename = $this->getOutputDir() . $id . $this->getExt();

        rewind($fp);
        $content = "\n";
        $content .= stream_get_contents($fp);

        if ($id === "") {
            $filename = $this->config->xmlFile;
        }

        echo "Filename: " . basename($filename) . "\n";
        echo "Content:" . $content . "\n";
    }
}
