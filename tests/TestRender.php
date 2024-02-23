<?php
namespace phpdotnet\phd;

class TestRender {
    protected ?Format $format = null;

    protected Config $config;

    public function __construct(
        ?Format $format,
        Config $config,
        ?array $indices = [],
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
    }

    public function run() {
        $reader = new Reader();
        $render = new Render();

        if (Index::requireIndexing()) {
           $format = new Index;
           $render->attach($format);
           $reader->open($this->config::xml_file());
           $render->execute($reader);
           $render->detach($format);
        }

        if ($this->format !== null) {
            $render->attach($this->format);
            $reader->open($this->config::xml_file());
            $render->execute($reader);
        }
    }
}


