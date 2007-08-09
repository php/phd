<?php
/*  $Id$ */

$r = new PhDReader($OPTIONS["xml_root"]."/.manual.xml");
$FILENAMES = $IDs = $IDMap = array();
$CURRENT_FILENAME = $LAST_CHUNK = "";
$PARENTS = array(-1 => "");
$lastid = 0;

/* someone really needs to fix this messed up logic */
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
        $FILENAMES[] = $id;
        $CURRENT_FILENAME = $id;
        $PARENTS[$r->depth] = $id;

        $IDMap[$id] = array("parent" => $PARENTS[$r->depth-1]);
        
        break;

    case PhDReader::CLOSE_CHUNK:
        $LAST_CHUNK = array_pop($FILENAMES);
        $CURRENT_FILENAME = end($FILENAMES);

        $IDMap[$CURRENT_FILENAME][$id] =& $IDMap[$LAST_CHUNK];
        continue 2;
    }

    if ($r->nodeType != XMLReader::ELEMENT) {
        continue;
    }
 
    $IDs[$id] = array("filename" => $CURRENT_FILENAME, "sdesc" => null, "ldesc" => null);
    $lastid = $id;
}
#print_r($IDs);
#var_dump($IDs["funcref"]);
/*
foreach($IDMap[$IDMap["function.strpos"]["parent"]] as $id => $junk) {
    if ($id == "parent") {
        continue;
    }
    printf("%s (%s): %s\n", $id, $IDs[$id]["sdesc"], $IDs[$id]["ldesc"]);
}
*/
/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

