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
        if ($this->index && $this->config->requiresIndexing()) {
            if (!file_exists($this->config->outputDir)) {
                mkdir($this->config->outputDir, 0755);
            }
            $this->attach($this->index);
            $this->reader::open($this->config->xmlFile);
            $this->execute($this->reader);
            $this->detach($this->index);
        }

        if ($this->format !== null) {
            $this->attach($this->format);
        }

        if (count($this) > 0) {
            $this->reader::open($this->config->xmlFile);
            $this->execute($this->reader);
        }
    }

    public function getIndex(): ?Index {
        return $this->index;
    }
}
