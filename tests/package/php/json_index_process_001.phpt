--TEST--
JsonIndex::processJsonIndex() filtering, sdesc/ldesc fallback, and entity decoding
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$indexes = [
    'book.datetime' => [
        'docbook_id' => 'book.datetime',
        'filename'   => 'book.datetime',
        'parent_id'  => '',
        'sdesc'      => 'Date &amp; Time',
        'ldesc'      => 'Date &amp; Time Related Extensions',
        'element'    => 'book',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    'ref.datetime' => [
        'docbook_id' => 'ref.datetime',
        'filename'   => 'ref.datetime',
        'parent_id'  => 'book.datetime',
        'sdesc'      => 'Date/Time',
        'ldesc'      => 'Date/Time &mdash; reference',
        'element'    => 'reference',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    'class.datetime' => [
        'docbook_id' => 'class.datetime',
        'filename'   => 'class.datetime',
        'parent_id'  => 'ref.datetime',
        'sdesc'      => 'DateTime',
        'ldesc'      => 'The DateTime class',
        'element'    => 'phpdoc:classref',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 0,
    ],
    'function.array-map' => [
        'docbook_id' => 'function.array-map',
        'filename'   => 'function.array-map',
        'parent_id'  => 'ref.datetime',
        'sdesc'      => 'array_map',
        'ldesc'      => 'Applies a callback',
        'element'    => 'refentry',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 0,
    ],
    // Non-chunked, non-always-include → should be filtered out
    'some.chapter' => [
        'docbook_id' => 'some.chapter',
        'filename'   => 'some.chapter',
        'parent_id'  => 'book.datetime',
        'sdesc'      => 'Chapter',
        'ldesc'      => 'A chapter',
        'element'    => 'chapter',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 0,
    ],
    // sdesc="" and ldesc!="" → triggers fallback
    'datetime.format' => [
        'docbook_id' => 'datetime.format',
        'filename'   => 'datetime.format',
        'parent_id'  => 'ref.datetime',
        'sdesc'      => '',
        'ldesc'      => 'DateTime::format',
        'element'    => 'refentry',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 0,
    ],
    // Orphan: parent_id not in indexes → ldesc stays unchanged
    'orphan.entry' => [
        'docbook_id' => 'orphan.entry',
        'filename'   => 'orphan.entry',
        'parent_id'  => 'nonexistent',
        'sdesc'      => '',
        'ldesc'      => 'Orphan function',
        'element'    => 'refentry',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 0,
    ],
];

$jsonIndex = new JsonIndex($indexes, []);

[$entries, $descriptions] = $jsonIndex->processJsonIndex();

// Chunked entry is included
echo "--- Chunked book entry ---\n";
echo $entries[0][0] . "\n";  // sdesc
echo $entries[0][1] . "\n";  // filename
echo $entries[0][2] . "\n";  // element

// Non-chunked classref (always-include) is included
echo "--- Always-include classref ---\n";
echo $entries[2][0] . "\n";
echo $entries[2][2] . "\n";

// Non-chunked refentry (always-include) is included
echo "--- Always-include refentry ---\n";
echo $entries[3][0] . "\n";
echo $entries[3][2] . "\n";

// Chapter (non-chunked, not in always-include) is filtered out
echo "--- Count (chapter excluded) ---\n";
echo count($entries) . "\n";

// sdesc/ldesc fallback: sdesc was "", ldesc was "DateTime::format"
// After fallback: sdesc = "DateTime::format", ldesc = parent book ldesc
echo "--- sdesc/ldesc fallback ---\n";
echo $entries[4][0] . "\n";  // sdesc should be "DateTime::format"

// Parent of ref.datetime → book.datetime (ldesc: "Date and Time Related Extensions")
echo $descriptions['datetime.format'] . "\n";

// HTML entity decoding in descriptions
echo "--- Entity decoding ---\n";
echo $descriptions['book.datetime'] . "\n";
echo $descriptions['ref.datetime'] . "\n";

// Orphan entry: parent not found, ldesc stays "Orphan function"
echo "--- Orphan ---\n";
echo $entries[5][0] . "\n";  // sdesc = "Orphan function" (copied from ldesc)
echo $descriptions['orphan.entry'] . "\n";  // ldesc unchanged
?>
--EXPECT--
--- Chunked book entry ---
Date &amp; Time
book.datetime
book
--- Always-include classref ---
DateTime
phpdoc:classref
--- Always-include refentry ---
array_map
refentry
--- Count (chapter excluded) ---
6
--- sdesc/ldesc fallback ---
DateTime::format
Date & Time Related Extensions
--- Entity decoding ---
Date & Time Related Extensions
Date/Time — reference
--- Orphan ---
Orphan function
Orphan function
