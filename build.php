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

(@include $ROOT."/config.php")
    && isset($OPTIONS)
    && is_array($OPTIONS)
    && isset($OPTIONS["output_format"], $OPTIONS["output_theme"])
    && is_array($OPTIONS["output_theme"])
    or die("Invalid configuration/file not found.\nThis should never happen, did you edit config.php yourself?\n");

require $ROOT. "/include/PhDReader.class.php";
require $ROOT. "/include/PhDPartialReader.class.php";
require $ROOT. "/include/PhDHelper.class.php";
require $ROOT. "/include/PhDFormat.class.php";
require $ROOT. "/include/PhDTheme.class.php";

/* {{{ Build the .ser file names to allow multiple sources for PHD. */
$OPTIONS['index_location'] = $OPTIONS['xml_root'] . DIRECTORY_SEPARATOR . '.index_' . basename($OPTIONS['xml_file'], '.xml') . '.ser';
$OPTIONS['refnames_location'] = $OPTIONS['xml_root'] . DIRECTORY_SEPARATOR . '.refnames_' . basename($OPTIONS['xml_file'], '.xml') . '.ser';
/* }}} */

/* {{{ Index the DocBook file or load from .ser cache files
       NOTE: There are two cache files (where * is the basename of the XML source file):
        - index_*.ser     (containing the ID infos)
        - refnames_*.ser  (containing <refname> => File ID) */
if (!$OPTIONS["index"] && (file_exists($OPTIONS['index_location']) && file_exists($OPTIONS['refnames_location']))) {
    /* FIXME: Load from sqlite db? */
    if ($OPTIONS["verbose"] & VERBOSE_INDEXING) {
        v("Unserializing cached index files...\n");
    }
    $IDs = unserialize(file_get_contents($OPTIONS['index_location']));
    $REFS = unserialize(file_get_contents($OPTIONS['refnames_location']));
    if ($OPTIONS["verbose"] & VERBOSE_INDEXING) {
        v("Unserialization done\n");
    }
} else {
    if ($OPTIONS["verbose"] & VERBOSE_INDEXING) {
        v("Indexing...\n");
    }
    /* This file will "return" an $IDs & $REFS array */
    require $ROOT. "/mktoc.php";

    file_put_contents($OPTIONS['index_location'], $ids = serialize($IDs));
    file_put_contents($OPTIONS['refnames_location'], $refs = serialize($REFS));

    $IDs2 = unserialize($ids);
    $REFS2 = unserialize($refs);
    if ($IDs !== $IDs2 || $REFS !== $REFS2) {
        v("WARNING: Serialized representation does not match");
    }

    if ($OPTIONS["verbose"] & VERBOSE_INDEXING) {
        v("Indexing done\n");
    }
}
/* }}} */

foreach($OPTIONS["output_format"] as $output_format) {
    if ($OPTIONS["verbose"] & VERBOSE_FORMAT_RENDERING) {
        v("Starting %s rendering\n", $output_format);
    }
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
    foreach($OPTIONS["output_theme"][$output_format] as $theme => $array) {
        is_dir($ROOT. "/themes/$theme") or die("Can't find the '$theme' theme");
        if ($OPTIONS["verbose"] & VERBOSE_THEME_RENDERING) {
            v("Using the %s theme (%s)\n", $theme, join(", ", $array));
        }
        
        foreach($array as $themename) {
            $themename = basename($themename);
            require_once $ROOT. "/themes/$theme/$themename.php";
            switch($theme) {
                // FIXME: This is stupid and definetly does not belong in here.
                case "php":
                    $themes[$themename] = new $themename(array($IDs, $REFS),
                        array(
                            "version" => $OPTIONS["version_info"],
                            "acronym" => $OPTIONS["acronyms_file"],
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
    if (!empty($OPTIONS["render_ids"]) || !empty($OPTIONS["skip_ids"])) {
        $idlist = $OPTIONS["render_ids"]+$OPTIONS["skip_ids"];
        if ($OPTIONS["verbose"] & VERBOSE_RENDER_STYLE) {
            v("Running partial build\n");
        }
        if (!is_array($idlist)) {
            $idlist = array($idlist => 1);
        }
        foreach($idlist as $id => $notused) {
            if (!isset($IDs[$id])) {
                v("Unknown ID %s, bailing\n", $id);
                exit(1);
            }
        }

        $reader = new PhDPartialReader($OPTIONS);
    } else {
        if ($OPTIONS["verbose"] & VERBOSE_RENDER_STYLE) {
            v("Running full build\n");
        }
        $reader = new PhDReader($OPTIONS);
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
    if ($OPTIONS["verbose"] & VERBOSE_FORMAT_RENDERING) {
        v("Finished rendering\n");
    }

} // foreach($OPTIONS["output_thtemes"])

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

