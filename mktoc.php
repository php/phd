<?php
/*  $Id$ */
$r = new PhDReader($OPTIONS);
$REFS = $FILENAMES = array();
$CURRENT_FILENAME = $LAST_CHUNK = "";

#FIXME: This is a workaround for the <legalnotice> element in the PHP manual
$PARENTS = array(-1 => "ROOT", 1 => "manual", 2 => "manual");
$lastid = 0;

while($r->read()) {
    if (!($id = $r->getID())) {
        $name = $r->name;
        if (empty($IDs[$lastid]["sdesc"])) {
            if ($name == "refname") {
                $IDs[$lastid]["sdesc"] = $refname = trim($r->readContent($name));
                $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $refname));
                $REFS[$ref] = $lastid;
                continue;
            }
            elseif($name == "titleabbrev") {
                $IDs[$lastid]["sdesc"] = trim($r->readContent($name));
                continue;
            }
        } elseif($name == "refname") {
            $refname = trim($r->readContent($name));
            $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $refname));
            $REFS[$ref] = $lastid;
        }
        if (empty($IDs[$lastid]["ldesc"])) {
            if ($name == "title" || $name == "refpurpose") {
                $IDs[$lastid]["ldesc"] = trim($r->readContent($name));
            }
        }
        
        continue;
    }
    switch($r->isChunk) {
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
        "parent"   => $r->isChunk ? $PARENTS[$r->depth-1] : $CURRENT_FILENAME,
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

