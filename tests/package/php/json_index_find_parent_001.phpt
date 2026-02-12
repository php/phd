--TEST--
JsonIndex::findParentBookOrSet() traversal and edge cases
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$indexes = [
    'set.mysqlinfo' => [
        'docbook_id' => 'set.mysqlinfo',
        'filename'   => 'set.mysqlinfo',
        'parent_id'  => '',
        'sdesc'      => 'MySQL',
        'ldesc'      => 'MySQL Drivers and Plugins',
        'element'    => 'set',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    'book.datetime' => [
        'docbook_id' => 'book.datetime',
        'filename'   => 'book.datetime',
        'parent_id'  => 'set.mysqlinfo',
        'sdesc'      => 'Date/Time',
        'ldesc'      => 'Date and Time Related Extensions',
        'element'    => 'book',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    'chapter.datetime' => [
        'docbook_id' => 'chapter.datetime',
        'filename'   => 'chapter.datetime',
        'parent_id'  => 'book.datetime',
        'sdesc'      => 'Installing',
        'ldesc'      => 'Installation chapter',
        'element'    => 'chapter',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    'ref.datetime' => [
        'docbook_id' => 'ref.datetime',
        'filename'   => 'ref.datetime',
        'parent_id'  => 'chapter.datetime',
        'sdesc'      => 'Date/Time',
        'ldesc'      => 'Date/Time reference',
        'element'    => 'reference',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
];

$jsonIndex = new JsonIndex($indexes, []);

// Direct parent is a book
echo "--- Direct parent is book ---\n";
$result = $jsonIndex->findParentBookOrSet('chapter.datetime');
echo $result['docbook_id'] . "\n";
echo $result['element'] . "\n";

// Grandparent is book (traverses intermediate chapter)
echo "--- Grandparent is book ---\n";
$result = $jsonIndex->findParentBookOrSet('ref.datetime');
echo $result['docbook_id'] . "\n";
echo $result['element'] . "\n";

// ID itself is a set → returns that set
echo "--- ID is a set ---\n";
$result = $jsonIndex->findParentBookOrSet('set.mysqlinfo');
echo $result['docbook_id'] . "\n";
echo $result['element'] . "\n";

// ID itself is a book → returns that book
echo "--- ID is a book ---\n";
$result = $jsonIndex->findParentBookOrSet('book.datetime');
echo $result['docbook_id'] . "\n";
echo $result['element'] . "\n";

// Nonexistent ID → returns null
echo "--- Nonexistent ID ---\n";
$result = $jsonIndex->findParentBookOrSet('nonexistent');
var_dump($result);
?>
--EXPECT--
--- Direct parent is book ---
book.datetime
book
--- Grandparent is book ---
book.datetime
book
--- ID is a set ---
set.mysqlinfo
set
--- ID is a book ---
book.datetime
book
--- Nonexistent ID ---
NULL
