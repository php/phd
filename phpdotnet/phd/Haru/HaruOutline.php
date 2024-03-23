<?php

namespace phpdotnet\phd;

class Haru_HaruOutline {
    public $h = null;
    private $ffi = null;

    public function __construct($outline_ref) {
        $this->ffi = \FFI::load(__DIR__.'/hpdf.h');
        $this->h = $outline_ref;
        if(is_null($this->h)) {
            throw new Haru_HaruException('Cannot create HaruOutline handle');
        }
    }

    public function setDestination($dest) {
        $status = $this->ffi->HPDF_Outline_SetDestination($this->h, $dest->h);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function setOpened($opened)
    {
        $status = $this->ffi->HPDF_Outline_SetOpened($this->h, $opened);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }
}
