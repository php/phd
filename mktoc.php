<?php
/**
* Creates the internal table of contents array.
* It is used to look up referenced ids and referenced names.
*
* The TOC is stored in the $ID variable. It is not saved, just generated.
* Generation works by using PhDReader to iterate over each tag, stopping at
* title (or similar) tags and chunk elements.
* Chunk element names are defined in PhDReader::OPEN_CHUNK and
* PhDReader::CLOSE_CHUNK.
* Title tags are title, titleabbrev, refname and refpurpose.
*
* IDs are taken from the xml:id="" attributes of the tags.
*
* It has the following format:
* array(
*     '$id' => array(
*         'sdesc'    => 'Short description (e.g. title)',
*         'ldesc'    => 'Long description (e.g. titleabbrev)',
*         'children' => array('array', 'of', 'children', 'ids'),
*         'parent'   => 'parent_id',
*         'filename' => 'filename for chunk'
*     ),
*     [..more..]
* )
*
* This script also creates the associative $REFS array, using the normalized
* <refname> value as key and its xml:id as value. This is used in
* PhDHelper::getRefnameLink(), and allows easy refname->link resolution.
*
* @package PhD
* @version CVS: $Id$
*/

$r = new PhDReader($OPTIONS);
$VARS = $CLASSES = $REFS = $FILENAMES = array();
$CURRENT_FILENAME = $LAST_CHUNK = "";

#FIXME: This is a workaround for the <legalnotice> element in the PHP manual
$PARENTS = array(-1 => "ROOT", 1 => "manual", 2 => "manual");
$lastid = 0;

while ($r->read()) {
    if (!($id = $r->getID())) {
        $name = $r->name;
        if (empty($IDs[$lastid]["sdesc"])) {
            if ($name == "refname") {
                $IDs[$lastid]["sdesc"] = $refname = trim($r->readContent($name));
                $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $refname));
                $REFS[$ref] = $lastid;
                $VARS[$refname] = $lastid;
                continue;
            }
            else if($name == "titleabbrev") {
                $IDs[$lastid]["sdesc"] = $class = trim($r->readContent($name));
                $elm = $r->getParentTagName();
                if ($elm == "phpdoc:classref" || $elm == "phpdoc:exceptionref") {
                    $CLASSES[strtolower($class)] = $lastid;
                }
                continue;
            }
        } else if ($name == "refname") {
            $refname = trim($r->readContent($name));
            $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $refname));
            $REFS[$ref] = $lastid;
            $VARS[$refname] = $lastid;
        }
        if (empty($IDs[$lastid]["ldesc"])) {
            if ($name == "title" || $name == "refpurpose") {
                $IDs[$lastid]["ldesc"] = trim($r->readContent($name));
            }
        }

        continue;
    }

    switch ($r->isChunk) {
    case PhDReader::OPEN_CHUNK:
        $CURRENT_FILENAME = $FILENAMES[] = $PARENTS[$r->depth] = $id;
        break;

    case PhDReader::CLOSE_CHUNK:
        $LAST_CHUNK = array_pop($FILENAMES);
        $CURRENT_FILENAME = end($FILENAMES);

        $IDs[$CURRENT_FILENAME]["children"][$LAST_CHUNK] = $IDs[$LAST_CHUNK];


        continue 2;
    }

    if ($r->nodeType != XMLReader::ELEMENT) {
        continue;
    }

    $IDs[$id] = array(
        "filename" => $CURRENT_FILENAME,
        "parent"   => $r->isChunk
                        ? (isset($PARENTS[$r->depth-1])
                            ? $PARENTS[$r->depth-1]
                            : $PARENTS[$r->depth-2]
                        )
                        : $CURRENT_FILENAME,
        "sdesc"    => null,
        "ldesc"    => null,
        "children" => array(),
    );

    $lastid = $id;
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
