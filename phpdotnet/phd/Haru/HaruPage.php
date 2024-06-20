<?php

namespace phpdotnet\phd;

class Haru_HaruPage {
    const NUM_STYLE_DECIMAL = 0;
    const FILL = 0;

    private $h = null;
    private $ffi = null;

    public function __construct($page_ref) {
        $this->ffi = \FFI::load(__DIR__.'/hpdf.h');
        $this->h = $page_ref;
        if(is_null($this->h)) {
            throw new Haru_HaruException('Cannot create HaruPage handle');
        }
    }

    public function setTextRenderingMode($mode) {
        $status = $this->ffi->HPDF_Page_SetTextRenderingMode($this->h, $mode);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function setRGBStroke($r, $g, $b) {
        $status = $this->ffi->HPDF_Page_SetRGBStroke($this->h, $r, $g, $b);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function setRGBFill($r, $g, $b) {
        $status = $this->ffi->HPDF_Page_SetRGBFill($this->h, $r, $g, $b);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function setFontAndSize($font, $size) {
        $font_ref = $font->h;
        $status = $this->ffi->HPDF_Page_SetFontAndSize($this->h, $font_ref, $size);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function beginText() {
        $status = $this->ffi->HPDF_Page_BeginText($this->h);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function getCharSpace() {
        $char_space = $this->ffi->HPDF_Page_GetCharSpace($this->h);
        return $char_space;
    }

    public function getWordSpace() {
        $word_space = $this->ffi->HPDF_Page_GetWordSpace($this->h);
        return $word_space;
    }

    public function textOut($x, $y, $text) {
        $status = $this->ffi->HPDF_Page_TextOut($this->h, $x, $y, (string)$text);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function getTextWidth($text) {
        $width = $this->ffi->HPDF_Page_TextWidth($this->h, (string)$text);
        return $width;
    }

    public function endText() {
        $status = $this->ffi->HPDF_Page_EndText($this->h);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function createDestination() {
        $dest_ref = $this->ffi->HPDF_Page_CreateDestination($this->h);
        if(is_null($dest_ref)) {
            throw new Haru_HaruException('Cannot create HaruDestination handle');
        }
        $dest = new Haru_HaruDestination($dest_ref);
        return $dest;
    }

    public function getHeight() {
        $height = $this->ffi->HPDF_Page_GetHeight($this->h);
        return $height;
    }

    public function setLineWidth($width) {
        $status = $this->ffi->HPDF_Page_SetLineWidth($this->h, $width);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function setDash($pattern, $phase) {
        $num_param = 0;
        if($pattern) {
            $num_param = count($pattern);
            $pat_ref_type = \FFI::arrayType($this->ffi->type("HPDF_UINT16"), [$num_param]);
            $pat_ref = $this->ffi->new($pat_ref_type);
            for($i = 0; $i < $num_param; $i++) {
                $pat_ref[$i] = $pattern[$i];
            }
            $pattern = $pat_ref;
        }
        $status = $this->ffi->HPDF_Page_SetDash($this->h, $pattern, $num_param, $phase);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function moveTo($x, $y) {
        $status = $this->ffi->HPDF_Page_MoveTo($this->h, $x, $y);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function lineTo($x, $y) {
        $status = $this->ffi->HPDF_Page_LineTo($this->h, $x, $y);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function stroke($close_path = false) {
        if(!$close_path) {
            $status = $this->ffi->HPDF_Page_Stroke($this->h);
        } else {
            $status = $this->ffi->HPDF_Page_ClosePathStroke($this->h);
        }
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function createURLAnnotation($rectangle, $url) {
        $rect_type = $this->ffi->type("HPDF_Rect");
        $hpdf_rectangle = $this->ffi->new($rect_type);
        $hpdf_rectangle->left = $rectangle[0];
        $hpdf_rectangle->bottom = $rectangle[1];
        $hpdf_rectangle->right = $rectangle[2];
        $hpdf_rectangle->top = $rectangle[3];
        $annotation_ref = $this->ffi->HPDF_Page_CreateURILinkAnnot($this->h, $hpdf_rectangle, $url);
        if(is_null($annotation_ref)) {
            throw new Haru_HaruException('Cannot create HaruAnnotation handle');
        }
        $annotation = new Haru_HaruAnnotation($annotation_ref);
        return $annotation;
    }

    public function rectangle($x, $y, $width, $height) {
        $status = $this->ffi->HPDF_Page_Rectangle($this->h, $x, $y, $width, $height);
        if($status) {
            throw new Haru_HaruException('', $status);
        }
    }

    public function createLinkAnnotation($rectangle, $dest) {
        $rect_type = $this->ffi->type("HPDF_Rect");
        $hpdf_rectangle = $this->ffi->new($rect_type);
        $hpdf_rectangle->left = $rectangle[0];
        $hpdf_rectangle->bottom = $rectangle[1];
        $hpdf_rectangle->right = $rectangle[2];
        $hpdf_rectangle->top = $rectangle[3];
        $annotation_ref = $this->ffi->HPDF_Page_CreateLinkAnnot($this->h, $hpdf_rectangle, $dest->h);
        if(is_null($annotation_ref)) {
            throw new Haru_HaruException('Cannot create HaruAnnotation handle');
        }
        $annotation = new Haru_HaruAnnotation($annotation_ref);
        return $annotation;
    }
}
