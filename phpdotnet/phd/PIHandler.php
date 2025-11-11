<?php
namespace phpdotnet\phd;

abstract class PIHandler {
    /**
     * @var \phpdotnet\phd\Format
     */
    protected $format;

    public function __construct($format) {
        $this->format = $format;
    }

    abstract public function parse($target, $data);

}


