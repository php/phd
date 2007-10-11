<?php
/*  $Id$ */
$r = new PhDReader($OPTIONS);
$FILENAMES = array();
$CURRENT_FILENAME = $LAST_CHUNK = "";

#FIXME: This is a workaround for the <legalnotice> element in the PHP manual
$PARENTS = array(-1 => "ROOT", 1 => "manual", 2 => "manual");
$lastid = 0;

while($r->read()) {
    if (!($id = $r->getID())) {
        $name = $r->name;
        if (in_array($name, array("refname", "titleabbrev")) && empty($IDs[$lastid]["sdesc"])) {
            switch($name) {
            case "refname":
                $IDs[$lastid]["sdesc"] = trim($r->readContent($name));
                continue 2;
            case "titleabbrev":
                $IDs[$lastid]["sdesc"] = trim($r->readContent($name));
                continue 2;
            }
        } else if (in_array($name, array("title", "refpurpose")) && empty($IDs[$lastid]["ldesc"])) {
            switch($name) {
            case "title":
                $IDs[$lastid]["ldesc"] = trim($r->readContent($name));
                continue 2;
            case "refpurpose":
                $IDs[$lastid]["ldesc"] = trim($r->readContent($name));
                continue 2;
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
        "parent"   => $r->isChunk ? $PARENTS[$r->depth-1] : end($FILENAMES),
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

