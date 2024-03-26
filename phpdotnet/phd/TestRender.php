<?php
namespace phpdotnet\phd;

class TestRender extends Render {
    public function __construct(
        protected Reader $reader,
        protected Config $config,
        protected ?Format $format = null,
        protected ?Index $index = null,
    ) {}

    public function run() {
        if ($this->index && requireIndexing($this->config)) {
            if (!file_exists($this->config->output_dir())) {
                mkdir($this->config->output_dir(), 0755);
            }
            $this->attach($this->index);
            $this->reader->open($this->config->xml_file());
            $this->execute($this->reader);
            $this->detach($this->index);
        }

        if ($this->format !== null) {
            $this->attach($this->format);
            $this->reader->open($this->config->xml_file());
            $this->execute($this->reader);
        }
    }

    public function getIndex(): ?Index {
        return $this->index;
    }
}
