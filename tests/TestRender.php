<?php
namespace phpdotnet\phd;

class TestRender {
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
        $render = new Render();

        if ($this->index !== null && $this->index::requireIndexing()) {
           $render->attach($this->index);
           $reader->open($this->config::xml_file());
           $render->execute($reader);
           $render->detach($this->index);
        }

        if ($this->format !== null) {
            $render->attach($this->format);
            $reader->open($this->config::xml_file());
            $render->execute($reader);
        }
    }
}


