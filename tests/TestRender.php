<?php
namespace phpdotnet\phd;

class TestRender extends Render {
    protected ?Format $format = null;

    protected Config $config;

    protected ?Index $index = null;

    public function __construct(
        ?Format $format = null,
        Config $config,
        ?array $indices = [],
        ?Index $index = null,
    ) {
        if ($format !== null) {
            $this->format = $format;

            if ($indices) {
                foreach ($indices as $index) {
                    $this->format->SQLiteIndex(
                        null, // $context,
                        null, // $index,
                        $index["docbook_id"] ?? "", // $id,
                        $index["filename"] ?? "", // $filename,
                        $index["parent_id"] ?? "", // $parent,
                        $index["sdesc"] ?? "", // $sdesc,
                        $index["ldesc"] ?? "", // $ldesc,
                        $index["element"] ?? "", // $element,
                        $index["previous"] ?? "", // $previous,
                        $index["next"] ?? "", // $next,
                        $index["chunk"] ?? 0, // $chunk
                    );
                }
            }
        }

        $this->config = $config;

        if ($index !== null) {
            $this->index = $index;
        }
    }

    public function run() {
        $reader = new Reader();

        if ($this->index !== null && $this->index::requireIndexing()) {
           $this->attach($this->index);
           $reader->open($this->config::xml_file());
           $this->execute($reader);
           $this->detach($this->index);
        }

        if ($this->format !== null) {
            $this->attach($this->format);
            $reader->open($this->config::xml_file());
            $this->execute($reader);
        }
    }
}


