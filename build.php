#!@php_bin@
<?php
/*  $Id$ */

/* {{{ Find the $ROOT directory of PhD
       @php_dir@ will be replaced by the pear package manager 
       If @php_dir@ however hasn't been replaced by anything,
       fallback to the dir containing this file */
$ROOT = "@php_dir@/phd";
if ($ROOT == "@php_dir"."@/phd") {
    $ROOT = dirname(__FILE__);
}
/* }}} */

(include $ROOT . "/config.php")
    or die("Configuration file not found.\nRe-run phd-setup.\n");

(include $ROOT . "/include/PhDBuildOptions.class.php")
    && is_array(PhDConfig::output_theme())
    or die("Invalid configuration.\nThis should never happen, did you edit config.php yourself?\nRe-run phd-setup.\n");

/* {{{ BC for PhD 0.0.* (and PHP5.2 on Windows)
       i.e. `phd path/to/.manual.xml */
if (!$optParser->docbook && $argc > 1) {
    $arg = $argv[$argc-1];
    if (is_dir($arg)) {
        PhDConfig::set_xml_root($arg);
        PhDConfig::set_xml_file($arg . DIRECTORY_SEPARATOR . ".manual.xml");
    } elseif (is_file($arg)) {
        PhDConfig::set_xml_root(dirname($arg));
        PhDConfig::set_xml_file($arg);
    }
}
/* }}} */

/* {{{ If no docbook file was passed, ask for it
       This loop should be removed in PhD 0.3.0, and replaced with a fatal errormsg */
while (!is_dir(PhDConfig::xml_root()) || !is_file(PhDConfig::xml_file())) {
    print "I need to know where you keep your '.manual.xml' file (I didn't find it in " . PhDConfig::xml_root() . "): ";
    $root = trim(fgets(STDIN));
    if (is_file($root)) {
        PhDConfig::set_xml_file($root);
        PhDConfig::set_xml_root(dirname($root));
    } else {
        PhDConfig::set_xml_file($root . "/.manual.xml");
        PhDConfig::set_xml_root($root);
    }
}
/* }}} */

/* This needs to be done in *all* cases! */
PhDConfig::set_output_dir(realpath(PhDConfig::output_dir()) . DIRECTORY_SEPARATOR);

// These files really should be moved into phd/
PhDConfig::set_version_info(PhDConfig::xml_root()."/phpbook/phpbook-xsl/version.xml");
PhDConfig::set_acronyms_file(PhDConfig::xml_root()."/entities/acronyms.xml");

require $ROOT. "/include/PhDReader.class.php";
require $ROOT. "/include/PhDPartialReader.class.php";
require $ROOT. "/include/PhDHelper.class.php";
require $ROOT. "/include/PhDFormat.class.php";
require $ROOT. "/include/PhDTheme.class.php";

/* {{{ Build the .ser file names to allow multiple sources for PHD. */
PhDConfig::set_index_location(PhDConfig::xml_root() . DIRECTORY_SEPARATOR . '.index_' . basename(PhDConfig::xml_file(), '.xml') . '.ser');
PhDConfig::set_refnames_location(PhDConfig::xml_root() . DIRECTORY_SEPARATOR . '.refnames_' . basename(PhDConfig::xml_file(), '.xml') . '.ser');
/* }}} */

/* {{{ Index the DocBook file or load from .ser cache files
       NOTE: There are two cache files (where * is the basename of the XML source file):
        - index_*.ser     (containing the ID infos)
        - refnames_*.ser  (containing <refname> => File ID) */
if (!PhDConfig::index() && (file_exists(PhDConfig::index_location()) && file_exists(PhDConfig::refnames_location()))) {
    /* FIXME: Load from sqlite db? */
    v("Unserializing cached index files...", VERBOSE_INDEXING);

    $IDs = unserialize(file_get_contents(PhDConfig::index_location()));
    $REFS = unserialize(file_get_contents(PhDConfig::refnames_location()));

    v("Unserialization done", VERBOSE_INDEXING);
} else {
    v("Indexing...", VERBOSE_INDEXING);

    /* This file will "return" an $IDs & $REFS array */
    require $ROOT. "/mktoc.php";

    file_put_contents(PhDConfig::index_location(), $ids = serialize($IDs));
    file_put_contents(PhDConfig::refnames_location(), $refs = serialize($REFS));

    $IDs2 = unserialize($ids);
    $REFS2 = unserialize($refs);
    if ($IDs !== $IDs2 || $REFS !== $REFS2) {
        trigger_error("Serialized representation does not match", E_USER_WARNING);
    }

    v("Indexing done", VERBOSE_INDEXING);
}
/* }}} */

foreach(PhDConfig::output_format() as $output_format) {
    v("Starting %s rendering", $output_format, VERBOSE_FORMAT_RENDERING);

    switch($output_format) {
    case "xhtml":
        $classname = "XHTMLPhDFormat";
        break;
    }

    // {{{ Initialize the output format and fetch the methodmaps
    require $ROOT. "/formats/$output_format.php";
    $format = new $classname(array($IDs, $REFS));
    $formatmap = $format->getElementMap();
    $formattextmap = $format->getTextMap();
    /* }}} */

    /* {{{ initialize output themes and fetch the methodmaps */
    $themes = $elementmaps = $textmaps = array();
    foreach(val(PhDConfig::output_theme(), $output_format) as $theme => $array) {
        is_dir($ROOT. "/themes/$theme") or die("Can't find the '$theme' theme");
        v("Using the %s theme (%s)", $theme, join(", ", $array), VERBOSE_THEME_RENDERING);
        
        foreach($array as $themename) {
            $themename = basename($themename);
            require_once $ROOT. "/themes/$theme/$themename.php";
            switch($theme) {
                // FIXME: This is stupid and definetly does not belong in here.
                case "php":
                    $themes[$themename] = new $themename(array($IDs, $REFS),
                        array(
                            "version" => PhDConfig::version_info(),
                            "acronym" => PhDConfig::acronyms_file(),
                        )
                    );
                    break;
                default:
                    $themes[$themename] = new $themename(array($IDs, $REFS));
            }
            
            // FIXME: this needs to go away when we add support for
            // things other than xhtml
            $themes[$themename]->registerFormat($format);
            

            // If the theme returns empty callback map there is no need to include it
            $tmp = $themes[$themename]->getElementMap();
            if(!empty($tmp)) {
                $elementmaps[$themename] = $tmp;
            }

            $tmp = $themes[$themename]->getTextMap();
            if (!empty($tmp)) {
                $textmaps[$themename] = $tmp;
            }
        }

    }
    /* }}} */

    /* {{{ Initialize the PhD[Partial]Reader */
    $idlist = PhDConfig::render_ids()+PhDConfig::skip_ids();
    if (!empty($idlist)) {

        v("Running partial build", VERBOSE_RENDER_STYLE);
        if (!is_array($idlist)) {
            $idlist = array($idlist => 1);
        }
        foreach($idlist as $id => $notused) {
            if (!isset($IDs[$id])) {
                trigger_error(sprintf("Unknown ID %s, bailing", $id), E_USER_ERROR);
            }
        }

        $reader = new PhDPartialReader();
    } else {
        v("Running full build", VERBOSE_RENDER_STYLE);

        $reader = new PhDReader();
    }
    /* }}} */

    while($reader->read()) {
        $nodetype = $reader->nodeType;

        switch($nodetype) {
        case XMLReader::ELEMENT:
        case XMLReader::END_ELEMENT: /* {{{ */
            $nodename = $reader->name;
            $open     = $nodetype == XMLReader::ELEMENT;
            $isChunk  = $reader->isChunk;
            $attrs    = $reader->getAttributes();
            $props    = array(
                "empty" => $reader->isEmptyElement,
                "isChunk" => $isChunk,
                "lang"  => $reader->xmlLang,
                "ns"    => $reader->namespaceURI,
                "sibling" => $reader->getPreviousSiblingTagName(),
            );

            $skip = array();
            foreach($elementmaps as $theme => $map) {
                if (isset($map[$nodename])) {
                    $tag = $map[$nodename];
                    if (is_array($tag)) {
                        $tag = $reader->notXPath($tag);
                    }
                    if ($tag) {
                        if (strncmp($tag, "format_", 7)) {
                            $retval = $themes[$theme]->transformFromMap($open, $tag, $nodename, $attrs, $props);
                            if ($retval !== false) {
                                $themes[$theme]->appendData($retval, $isChunk);
                                $skip[] = $theme;
                            }
                            continue;
                        }

                        $funcname = $tag;
                        $retval = $themes[$theme]->{$funcname}($open, $nodename, $attrs, $props);
                        if ($retval !== false) {
                            $themes[$theme]->appendData($retval, $isChunk);
                            $skip[] = $theme;
                        }
                        continue;
                    }
                }
            }

            $funcname = "format_$nodename";
            if (count($skip) < count($themes)) {
                if (isset($formatmap[$nodename])) {
                    $tag = $formatmap[$nodename];
                    if (is_array($tag)) {
                        $tag = $reader->notXPath($tag);
                    }
                    if (strncmp($tag, "format_", 7)) {
                        $retval = $format->transformFromMap($open, $tag, $nodename, $attrs, $props);
                        foreach($themes as $name => $theme) {
                            if (!in_array($name, $skip)) {
                                $theme->appendData($retval, $isChunk);
                            }
                        }
                        break;
                    }
                    $funcname = $tag;
                }
                $retval = $format->{$funcname}($open, $nodename, $attrs, $props);
                foreach($themes as $name => $theme) {
                    if (!in_array($name, $skip)) {
                        $theme->appendData($retval, $isChunk);
                    }
                }
            }
            break;
            /* }}} */

        case XMLReader::TEXT: /* {{{ */
            $skip = array();
            $value = $reader->value;
            $parentname = $reader->getParentTagName();
            foreach($textmaps as $theme => $map) {
                if (isset($map[$parentname])) {
                    $tagname = $map[$parentname];
                    if (is_array($tagname)) {
                        $tagname = $reader->notXPath($tagname, $reader->depth-1);
                    }

                    if ($tagname) {
                        $retval = $themes[$theme]->{$tagname}($value, $parentname);
                        if ($retval !== false) {
                            $skip[] = $theme;
                            $themes[$theme]->appendData($retval, false);
                        }
                    }
                }
            }
            if (count($skip) < count($themes)) {
                $retval = false;
                if (isset($formattextmap[$parentname])) {
                    $tagname = $formattextmap[$parentname];
                    if (is_array($tagname)) {
                        $tagname = $reader->notXPath($tagname, $reader->depth-1);
                    }
                    if ($tagname) {
                        $retval = $format->{$tagname}($value, $parentname);
                    }
                }
                if ($retval === false) {
                    $retval = $format->TEXT($value);
                }
                foreach ($themes as $name => $theme) {
                    if (!in_array($name, $skip)) {
                        $theme->appendData($retval, false);
                    }
                }
            }
            break;
        /* }}} */

        case XMLReader::CDATA: /* {{{ */
            $value = $reader->value;
            $retval = $format->CDATA($value);
            foreach($themes as $name => $theme) {
                $theme->appendData($retval, false);
            }
            break;
        /* }}} */

        case XMLReader::WHITESPACE:
        case XMLReader::SIGNIFICANT_WHITESPACE: /* {{{ */
            $value = $reader->value;
            foreach($themes as $name => $theme) {
                $theme->appendData($value, false);
            }
            break;
        /* }}} */

        case XMLReader::COMMENT:
        case XMLReader::DOC_TYPE:
            /* swallow it */
            continue 2;

        default:
            $nodename = $reader->name;
            trigger_error("Don't know how to handle {$nodename} {$nodetype}", E_USER_ERROR);
            return;
        }
    }

    $reader->close();
    v("Finished rendering", VERBOSE_FORMAT_RENDERING);

} // foreach(PhDConfig::output_themes())

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

