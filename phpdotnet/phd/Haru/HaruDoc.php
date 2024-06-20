<?php

namespace phpdotnet\phd;

class Haru_HaruDoc {
    const COMP_ALL = 0x0F;
    const PAGE_MODE_USE_OUTLINE = 1;

    private $h = null;
    private $ffi = null;

    public function __construct() {
        try {
            $this->ffi = \FFI::load(__DIR__.'/hpdf.h');
        } catch(\FFI\Exception $e) {
            die($e->getFile() . ":" . $e->getLine() . ": " . $e->getMessage() . ". Is libharu installed?\n");
        }
        $this->h = $this->ffi->HPDF_New(null, null);
        if(is_null($this->h)) {
            throw new Haru_HaruException('Cannot create HaruDoc handle');
        }
    }

    public function setCompressionMode($mode) {
        $status = $this->ffi->HPDF_SetCompressionMode($this->h, $mode);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function addPageLabel($first_page, $style, $first_num, $string_prefix = null) {
        $status = $this->ffi->HPDF_AddPageLabel($this->h, $first_page, $style, $first_num, $string_prefix);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function setPageMode($mode) {
        $status = $this->ffi->HPDF_SetPageMode($this->h, $mode);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function setPagesConfiguration($page_per_pages) {
        $status = $this->ffi->HPDF_SetPagesConfiguration($this->h, $page_per_pages);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function getFont($font_name, $encoding_name) {
        $font_ref = $this->ffi->HPDF_GetFont($this->h, $font_name, $encoding_name);
        if(is_null($font_ref)) {
            throw new Haru_HaruException('Cannot create HaruFont handle');
        }
        $font = new Haru_HaruFont($font_ref);
        return $font;
    }

    public function save($file_name) {
        $status = $this->ffi->HPDF_SaveToFile($this->h, $file_name);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    // Note that the order of params differ from the underlying function
    public function createOutline($title, $parent = null, $encoder = null) {
        if($parent){
            $parent = $parent->h;
        }
        $outline_ref = $this->ffi->HPDF_CreateOutline($this->h, $parent, $title, $encoder);
        if(is_null($outline_ref)) {
            throw new Haru_HaruException('Cannot create HaruOutline handle');
        }
        $outline = new Haru_HaruOutline($outline_ref);
        return $outline;
    }

    public function addPage() {
        $page_ref = $this->ffi->HPDF_AddPage($this->h);
        if(is_null($page_ref)) {
            throw new Haru_HaruException('Cannot create HaruPage handle');
        }
        $page = new Haru_HaruPage($page_ref);
        return $page;
    }

    // TODO: this should return a new Haru_HaruImage instance
    public function loadPNG($filename) {
        $image_ref = $this->ffi->HPDF_LoadPngImageFromFile($this->h, $filename);
        return $image_ref;
    }

    // TODO: this should return a new Haru_HaruImage instance
    public function loadJPEG() {
        $image_ref = $this->ffi->HPDF_LoadJpegImageFromFile($this->h, $filename);
        return $image_ref;
    }
}
