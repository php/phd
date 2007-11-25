<?php
/*  $Id$ */

abstract class PhDTheme extends PhDHelper implements iPhDTheme {
    protected $format;
    public function registerFormat($format) {
        $this->format = $format;
    }
}

interface iPhDTheme {
    public function appendData($data, $isChunk);
}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

