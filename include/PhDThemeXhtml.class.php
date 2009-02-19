<?php
/*  $Id$ */

/**
* Base class for XHTML themes
*/
abstract class PhDThemeXhtml extends PhDTheme
{
    /**
    * If we are a theme generating chunks or not
    *
    * @var boolean
    */
    protected $chunked = true;

    /**
    * Media manager object
    *
    * @var PhDMediaManager
    */
    public $mediamanager = null;

    /**
    * Language
    */
    protected $lang = 'en';



    /**
    * Called after the constructor finished.
    * This is needed since themes set their outputdir and outputfile
    * in the constructor. That file/dir is used for mediamanager.
    * That means we cannot instantiate and complete the manager in our
    * constructor centrally.
    *
    * Each theme needs its own media manager, since the manager contains
    * the output path.
    *
    * @return void
    */
    public function postConstruct()
    {
        $this->mediamanager = new PhDMediaManager(PhDConfig::xml_root());

        if (isset($this->outputdir) && $this->outputdir) {
            $this->mediamanager->output_dir = $this->outputdir;
        } else {
            $this->mediamanager->output_dir        = $this->outputfile . '-data/';
            $this->mediamanager->relative_ref_path = basename($this->mediamanager->output_dir) . '/';
        }
    }//public function postConstruct()



    /**
    * Creates a table of contents for the given id.
    * Also creates nested TOCs if that's wanted ($depth)
    *
    * @param string  $id     ID of section for which to generate TOC
    * @param string  $name   Tag name (for ul class)
    * @param array   $props  Build properties (?? FIXME)
    * @param integer $depth  Depth of TOC
    * @param boolean $header If the header shall be shown ("Table of contents")
    *
    * @return string HTML code for TOC
    */
    public function createToc($id, $name, $props, $depth = 1, $header = true)
    {
        $chunks = PhDHelper::getChildren($id);
        if ($depth == 0 || !count($chunks)) {
            return '';
        }

        $content = '';
        if ($header) {
            $content .= " <strong>" . $this->format->autogen("toc", $props["lang"]) . "</strong>\n";
        }
        $content .= " <ul class=\"chunklist chunklist_$name\">\n";
        foreach ($chunks as $chunkid => $junk) {
            $long  = $this->format->TEXT(PhDHelper::getDescription($chunkid, true));
            $short = $this->format->TEXT(PhDHelper::getDescription($chunkid, false));
            if ($long && $short && $long != $short) {
                $desc = $short . '</a> -- ' . $long;
            } else {
                $desc = ($long ? $long : $short) . '</a>';
            }
            //FIXME
            if ($this->chunked) {
                $content .= "  <li><a href=\"{$chunkid}.{$this->ext}\">" . $desc;
            } else {
                $content .= "  <li><a href=\"#{$chunkid}\">" . $this->format->TEXT(PhDHelper::getDescription($chunkid, false)) . "</a>";
            }
            if ($depth > 1) {
                $content .= $this->createToc($chunkid, $name, $props, $depth - 1, false);
            }

            $content .= "</li>\n";;
        }

        $content .= " </ul>\n";

        return $content;
    }



    /**
    * Handle an image.
    */
    public function format_imagedata($open, $name, $attrs) {
        $file    = $attrs[PhDReader::XMLNS_DOCBOOK]["fileref"];
        $newpath = $this->mediamanager->handleFile($file);

        if ($this->format->cchunk["mediaobject"]["alt"] !== false) {
            return '<img src="' . $newpath . '" alt="' .$this->format->cchunk["mediaobject"]["alt"]. '" />';
        }
        return '<img src="' . $newpath . '" />';
    }



    /**
    * Handle a <phd:toc> tag.
    */
    public function format_phd_toc($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="phd-toc">';
        }
        return $this->createToc(
            $attrs[PhDReader::XMLNS_PHD]['element'],
            'phd-toc',
            $props,
            isset($attrs[PhDReader::XMLNS_PHD]['toc-depth'])
                ? (int)$attrs[PhDReader::XMLNS_PHD]['toc-depth'] : 1,
            false
        ) . "</div>\n";
    }

}


/*
 * vim600: sw=4 ts=4 fdm=syntax syntax=php et
 * vim<600: sw=4 ts=4
 */

?>