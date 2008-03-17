<?php
/**
  * array addNode ( array &ar1, string $id [, string $parentid] )
  **/
function addNode(array &$a_Nodes, $s_ID, $s_ParentID = 'ROOT') {
    $a_Nodes[$s_ID] = array(
        'ID' => $s_ID,
        'Up' => &$a_Nodes[$s_ParentID],
    );
    $a_Nodes[$s_ParentID]['Dn'][$s_ID] = &$a_Nodes[$s_ID];
    return $a_Nodes[$s_ID];
}

// Quick hack until indexer is in /phd with config and build
$ROOT = dirname(__FILE__) . '/../..';

// Load the config (to get where the XML file is.
(@include $ROOT."/config.php")
    && isset($OPTIONS)
    && is_array($OPTIONS)
    or die("Invalid configuration/file not found.\nThis should never happen, did you edit config.php yourself?\n");


// Define an empty tree.
$a_Nodes = array(
    'ROOT' => array(
        'ID' => 'ROOT',
    ),
);

// Define the movable node.
$a_CurrentNode = $a_Nodes['ROOT'];

/* 

(DONE) I read an XML node.

(DONE) If the nodeType is ELEMENT and the XML node has an ID, then add a new
       node to the tree and use this new node as the current node.

(DONE) If the nodeType is 15 and the stack says that this was an ID'd node,
       then set the currentparent to the currentparent's parent.

(TODO) Log values from specific nodes to the current ID node - like
       title/refname/refpurpose/etc.

(TODO) The ID should be normalised. Pointers please.
*/

$dt_Start = microtime(true);
$o_XML = new XMLReader();
$o_XML->open($OPTIONS['xml_file'], 'UTF-8');
while($o_XML->read()) {
    switch($o_XML->nodeType) {
        case XMLReader::ELEMENT: // We have an element.
            // Does it have an ID?
            if ($o_XML->hasAttributes && $o_XML->moveToAttributeNs('id', 'http://www.w3.org/XML/1998/namespace')) {
                // Log a new node.
                $a_CurrentNode = addNode($a_Nodes, $o_XML->value, $a_CurrentNode['ID']);
            }
            break;

        case XMLReader::END_ELEMENT : // We have finished with the element.
            // Does it have an ID?
            if ($o_XML->hasAttributes && $o_XML->moveToAttributeNs('id', 'http://www.w3.org/XML/1998/namespace')) {
                // Move up the stored node tree.
                $a_CurrentNode = $a_CurrentNode['Up'];
            }
            break;
    }
}
echo 'Read XML and added ', count($a_Nodes), ' IDs to node tree in ', microtime(True) - $dt_Start, PHP_EOL;

$dt_Start = microtime(True);
reset($a_Nodes);
do {
    $a_Prev = prev($a_Nodes);
    if (False === $a_Prev) {
        $a_Prev = reset($a_Nodes);
    }
    $a_Current = next($a_Nodes);
    $a_Next = next($a_Nodes);
    if (False === $a_Next) {
        $a_Next = reset($a_Nodes);
    }
    $a_Nodes[$a_Current['ID']] += array(
        'PR' => &$a_Nodes[$a_Prev['ID']],
        'NX' => &$a_Nodes[$a_Next['ID']],
    );
}
while('ROOT' !== $a_Next['ID']);
echo 'Added next/prev values to node tree in ', microtime(True) - $dt_Start, PHP_EOL;

// Store the index locally as to not pollute anything.
file_put_contents('./index.ser', serialize($a_Nodes));

// Test left and right of function.str-repeat
$s_ID = 'function.str-repeat';
echo "{$a_Nodes[$s_ID]['PR']['ID']} << $s_ID >> {$a_Nodes[$s_ID]['NX']['ID']}", PHP_EOL;