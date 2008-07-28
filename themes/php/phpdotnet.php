<?php
/*  $Id$ */

class PhDPHPFormat extends PhDXHTMLFormat {
    protected $myelementmap = array(
        'function'              => 'format_suppressed_tags',
        'BOGUStitle'                 => array(
            /* DEFAULT */          false,
            'article'           => 'format_container_chunk_title',
            'appendix'          => 'format_container_chunk_title',
            'chapter'           => 'format_container_chunk_title',
            'part'              => 'format_container_chunk_title',
            'info'              => array(
                /* DEFAULT */      false,
                'article'       => 'format_container_chunk_title',
                'appendix'      => 'format_container_chunk_title',
                'chapter'       => 'format_container_chunk_title',
                'part'          => 'format_container_chunk_title',
            ),
        ),

        'titleabbrev'           => 'format_suppressed_tags',
        'type'                  => array(
            /* DEFAULT */          'format_suppressed_tags',
            'classsynopsisinfo' => false,
            'fieldsynopsis'     => false,
            'methodparam'       => false,
            'methodsynopsis'    => false,
        ),


        'BOGUSarticle'               => 'format_container_chunk',
        'BOGUSappendix'              => 'format_container_chunk',
        'BOGUSbibliography'          => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'BOGUSbook'                  => 'format_root_chunk',
        'BOGUSchapter'               => 'format_container_chunk',
        'BOGUScolophon'              => 'format_chunk',
        'BOGUSglossary'              => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'BOGUSindex'                 => array(
            /* DEFAULT */          false,
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'BOGUSlegalnotice'           => 'format_chunk',
        'BOGUSpart'                  => 'format_container_chunk',
        'BOGUSpreface'               => 'format_chunk',
        'BOGUSrefentry'              => 'format_chunk',
        'BOGUSreference'             => 'format_container_chunk',
        'BOGUSsect1'                 => 'format_chunk',
        'BOGUSsect2'                 => 'format_chunk',
        'BOGUSsect3'                 => 'format_chunk',
        'BOGUSsect4'                 => 'format_chunk',
        'BOGUSsect5'                 => 'format_chunk',
        'BOGUSsection'               => 'format_chunk',
        'BOGUSset'                   => 'format_root_chunk',
        'BOGUSsetindex'              => 'format_chunk',
        'BOGUSphpdoc:exception'      => 'format_exception_chunk',
    );
    protected $mytextmap =        array(

        'titleabbrev'           => 'format_suppressed_tags',
    );
    protected $lang = "en";

    protected $CURRENT_ID = "";

    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array(
        "fieldsynopsis"                => array(
            "modifier"                          => "public",
        ),
        "container_chunk"              => null,
    );

}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

