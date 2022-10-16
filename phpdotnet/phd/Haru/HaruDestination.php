<?php

namespace phpdotnet\phd;

class Haru_HaruDestination {
    public $h = null;
    private $ffi = null;

    public function __construct($dest_ref) {
        $this->ffi = \FFI::load(__DIR__.'/hpdf.h');
        $this->h = $dest_ref;
        if(is_null($this->h)) {
            throw new Haru_HaruException('Cannot create HaruDestination handle');
        }
    }

    public function setXYZ($left, $top, $zoom) {
        $status = $this->ffi->HPDF_Destination_SetXYZ($this->h, $left, $top, $zoom);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }
}
