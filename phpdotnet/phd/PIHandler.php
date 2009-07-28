<?php
namespace phpdotnet\phd;

abstract class PIHandler {
    protected $format;

    public function __construct($format) {
        $this->format = $format;
    }

    public abstract function parse($target, $data);

}

?>
