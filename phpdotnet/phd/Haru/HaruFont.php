<?php

namespace phpdotnet\phd;

class Haru_HaruFont {
    public $h = null;
    private $ffi = null;

    public function __construct($font_ref) {
        $this->ffi = \FFI::load(__DIR__.'/hpdf.h');
        $this->h = $font_ref;
        if(is_null($this->h)) {
            throw new Haru_HaruException('Cannot create HaruFont handle');
        }
    }

    public function measureText($text, $width, $font_size, $char_space, $word_space, $word_wrap = 0) {
        $len = strlen($text);
        if(!$len) {
            return 0;
        }
        $str_ref_type = \FFI::arrayType($this->ffi->type("HPDF_BYTE"), [$len]);
        $str_ref = $this->ffi->new($str_ref_type);
        \FFI::memcpy($str_ref, $text, $len);
        $fit_count = $this->ffi->HPDF_Font_MeasureText($this->h, $str_ref, $len, $width, $font_size, $char_space, $word_space, $word_wrap, null);
        return (int)$fit_count;
    }
}
