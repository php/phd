<?php
namespace phpdotnet\phd;

class TestPHPChunkedXHTML extends Package_PHP_ChunkedXHTML {
    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
            parent::update($event, $val);
            break;
        case Render::STANDALONE:
            parent::update($event, $val);
            break;
        case Render::INIT:
            $this->setOutputDir(Config::output_dir() . strtolower($this->getFormatName()) . '/');
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
            $filename = Config::xml_file();
        }

        echo "Filename: " . basename($filename) . "\n";
        echo "Content:" . $content . "\n";
    }
}
