<?php
namespace phpdotnet\phd;

class TestRender {
    protected $format;

    public function __construct(
        string $formatclass,
        array $opts,
        ?array $extra = [],
        ?array $indices = []
    ) {
        foreach ($opts as $k => $v) {
            $method = "set_$k";
            Config::$method($v);
        }
        if (count($extra) != 0) {
            Config::init($extra);
        }
        $classname = __NAMESPACE__ . "\\" . $formatclass;
        $this->format = new $classname();

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

    public function run() {
        $reader = new Reader();
        $render = new Render();
        if (Index::requireIndexing()) {
           $format = new Index;
           $render->attach($format);
           $reader->open(Config::xml_file());
           $render->execute($reader);
           $render->detach($format);
        }
        $render->attach($this->format);
        $reader->open(Config::xml_file());
        $render->execute($reader);
    }
}


