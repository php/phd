<?php

namespace phpdotnet\phd;

class Haru_HaruAnnotation {
    private $h = null;
    private $ffi = null;

    public function __construct($annotation_ref) {
        $this->ffi = \FFI::load(__DIR__.'/hpdf.h');
        $this->h = $annotation_ref;
        if(is_null($this->h)) {
            throw new Haru_HaruException('Cannot create HaruAnnotation handle');
        }
    }
    public function setBorderStyle($width, $dash_on, $dash_off) {
        $status = $this->ffi->HPDF_LinkAnnot_SetBorderStyle($this->h, $width, $dash_on, $dash_off);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }
}
