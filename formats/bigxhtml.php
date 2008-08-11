<?php
class PhDBigXHTMLFormat extends PhDXHTMLFormat {
    private $simpleName = "Big-XHTML";
    private $myelementmap = array(
        'link'                  => 'format_link',
        'xref'                  => 'format_xref',
        'title'                 => array(
            /* DEFAULT */          false,
            'info'              => array(
                /* DEFAULT */      false,
                'article'       => 'format_container_chunk_top_title',
                'appendix'      => 'format_container_chunk_top_title',
                'book'          => 'format_container_chunk_top_title',
                'chapter'       => 'format_container_chunk_top_title',
                'part'          => 'format_container_chunk_top_title',
                'set'           => 'format_container_chunk_top_title',
            ),
            'article'           => 'format_container_chunk_top_title',
            'appendix'          => 'format_container_chunk_top_title',
            'book'              => 'format_container_chunk_top_title',
            'chapter'           => 'format_container_chunk_top_title',
            'part'              => 'format_container_chunk_top_title',
            'set'               => 'format_container_chunk_top_title',
        ),
        'reference'             => 'format_container_chunk_below',
        'question'              => array(
            /* DEFAULT */          false,
            'questions'         => 'format_phd_question', // From the PhD namespace
        ),

 
    );
    private $mytextmap = array(
    );
    private $bigfp;
    protected $flags;


    public function __construct() {
        parent::__construct();
        parent::registerFormatName($this->simpleName);
    }
    public function appendData($data) {
        if ($this->appendToBuffer) {
            $this->buffer .= $data;
            return;
        }
    	$id = "BIGHTML ID";
        if ($this->flags & PhDRender::CLOSE) {
            fwrite($this->bigfp, $data);

            /* Append footer */
            fwrite($this->bigfp, $this->footer($id));
            $this->flags ^= PhDRender::CLOSE;
        } elseif ($this->flags & PhDRender::OPEN) {
            /* Prepend header */
            fwrite($this->bigfp, $this->header($id));

            fwrite($this->bigfp, $data);
            $this->flags ^= PhDRender::OPEN;
        } else {
            fwrite($this->bigfp, $data);
        }

    }
    public function header($id) {
        return "\n";
    }
    public function footer($id) {
        return "\n<hr />\n";
    }
    public function open() {
        static $i = 0;
        $i++;
        $this->bigfp = fopen(PhDConfig::output_dir() . "bightml$i.html", "w+");
        // FIXME: Use correct lang attribute and insert <title> of the DB file
        fwrite($this->bigfp, 
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>PHP Manual</title>
</head>
<body>
');

    }
    public function close() {
        fwrite($this->bigfp, "</body>\n</html>");
        fclose($this->bigfp);
    }
    public function getDefaultElementMap() {
        return $this->myelementmap;
    }
    public function getDefaultTextMap() {
        return $this->mytextmap;
    }
    public function update($event, $val = null) {
        switch($event) {
        case PhDRender::CHUNK:
            $this->flags = $val;
            break;

        case PhDRender::STANDALONE:
            if ($val) {
                $this->registerElementMap(parent::getDefaultElementMap());
                $this->registerTextMap(parent::getDefaultTextMap());
            } else {
                $this->registerElementMap(static::getDefaultElementMap());
                $this->registerTextMap(static::getDefaultTextMap());
            }
            break;

        case PhDRender::INIT:
            if ($val) {
                if (!is_resource($this->bigfp)) {
                    $this->open();
                }
            } else {
                $this->close();
            }
            break;
        case PhDRender::VERBOSE:
            v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
            break;
        }
    }
    public function createLink($for, &$desc = null, $type = self::SDESC) {
        $retval = '#' . $for;
        if (isset($this->idx[$for])) {
            $result = $this->idx[$for];
            if ($type === self::SDESC) {
                $desc = $result["sdesc"] ?: $result["ldesc"];
            } else {
                $desc = $result["ldesc"] ?: $result["sdesc"];
            }            
        }
        return $retval;
    }

}

/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

