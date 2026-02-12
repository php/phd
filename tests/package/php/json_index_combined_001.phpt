--TEST--
JsonIndex::processCombinedJsonIndex() type classification and methodName extraction
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$indexes = [
    'book.datetime' => [
        'docbook_id' => 'book.datetime',
        'filename'   => 'book.datetime',
        'parent_id'  => '',
        'sdesc'      => 'Date/Time',
        'ldesc'      => 'Date and Time Related Extensions',
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
        'ldesc'      => 'Date/Time reference',
        'element'    => 'reference',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
];

$indexesWithDuplicates = [
    // refentry → Function, with :: method extraction
    [
        'docbook_id' => 'datetime.format',
        'filename'   => 'datetime.format',
        'parent_id'  => 'ref.datetime',
        'sdesc'      => 'DateTime::format',
        'ldesc'      => 'Returns date formatted',
        'element'    => 'refentry',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    // refentry → Function, no :: → methodName = sdesc
    [
        'docbook_id' => 'function.array-map',
        'filename'   => 'function.array-map',
        'parent_id'  => 'ref.datetime',
        'sdesc'      => 'array_map',
        'ldesc'      => 'Applies a callback',
        'element'    => 'refentry',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    // phpdoc:classref → Class
    [
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
    // phpdoc:exceptionref → Exception
    [
        'docbook_id' => 'class.invalidargumentexception',
        'filename'   => 'class.invalidargumentexception',
        'parent_id'  => 'ref.datetime',
        'sdesc'      => 'InvalidArgumentException',
        'ldesc'      => 'The InvalidArgumentException class',
        'element'    => 'phpdoc:exceptionref',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 0,
    ],
    // phpdoc:varentry → Variable
    [
        'docbook_id' => 'reserved.variables.server',
        'filename'   => 'reserved.variables.server',
        'parent_id'  => 'ref.datetime',
        'sdesc'      => '$_SERVER',
        'ldesc'      => 'Server and execution variables',
        'element'    => 'phpdoc:varentry',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 0,
    ],
    // book → Extension
    [
        'docbook_id' => 'book.datetime',
        'filename'   => 'book.datetime',
        'parent_id'  => '',
        'sdesc'      => 'Date/Time',
        'ldesc'      => 'Date and Time Related Extensions',
        'element'    => 'book',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    // set → Extension
    [
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
    // reference → Extension
    [
        'docbook_id' => 'ref.datetime',
        'filename'   => 'ref.datetime',
        'parent_id'  => 'book.datetime',
        'sdesc'      => 'Date/Time',
        'ldesc'      => 'Date/Time reference',
        'element'    => 'reference',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    // chapter (non-chunked, not in always-include) → filtered out
    [
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
    // section (chunked) → General (default type)
    [
        'docbook_id' => 'intro.datetime',
        'filename'   => 'intro.datetime',
        'parent_id'  => 'book.datetime',
        'sdesc'      => 'Introduction',
        'ldesc'      => 'Intro to Date/Time',
        'element'    => 'section',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
    // sdesc="" and ldesc!="" → triggers fallback
    [
        'docbook_id' => 'datetime.settimezone',
        'filename'   => 'datetime.settimezone',
        'parent_id'  => 'ref.datetime',
        'sdesc'      => '',
        'ldesc'      => 'DateTime::setTimezone',
        'element'    => 'refentry',
        'previous'   => '',
        'next'       => '',
        'chunk'      => 1,
    ],
];

$jsonIndex = new JsonIndex($indexes, $indexesWithDuplicates);

$entries = $jsonIndex->processCombinedJsonIndex();

// Chapter should be filtered out, leaving 10 entries
echo "--- Count (chapter excluded) ---\n";
echo count($entries) . "\n";

// Type classification
echo "--- Type: Function (refentry) ---\n";
echo $entries[0]['type'] . "\n";

echo "--- Type: Class (phpdoc:classref) ---\n";
echo $entries[2]['type'] . "\n";

echo "--- Type: Exception (phpdoc:exceptionref) ---\n";
echo $entries[3]['type'] . "\n";

echo "--- Type: Variable (phpdoc:varentry) ---\n";
echo $entries[4]['type'] . "\n";

echo "--- Type: Extension (book) ---\n";
echo $entries[5]['type'] . "\n";

echo "--- Type: Extension (set) ---\n";
echo $entries[6]['type'] . "\n";

echo "--- Type: Extension (reference) ---\n";
echo $entries[7]['type'] . "\n";

echo "--- Type: General (section, default) ---\n";
echo $entries[8]['type'] . "\n";

// methodName extraction
echo "--- methodName: DateTime::format → format ---\n";
echo $entries[0]['methodName'] . "\n";

echo "--- methodName: array_map → array_map ---\n";
echo $entries[1]['methodName'] . "\n";

// sdesc/ldesc fallback
echo "--- sdesc/ldesc fallback ---\n";
echo $entries[9]['name'] . "\n";
echo $entries[9]['description'] . "\n";
echo $entries[9]['methodName'] . "\n";

// Entry structure
echo "--- Entry structure ---\n";
echo $entries[0]['id'] . "\n";
echo $entries[0]['name'] . "\n";
echo $entries[0]['tag'] . "\n";
?>
--EXPECT--
--- Count (chapter excluded) ---
10
--- Type: Function (refentry) ---
Function
--- Type: Class (phpdoc:classref) ---
Class
--- Type: Exception (phpdoc:exceptionref) ---
Exception
--- Type: Variable (phpdoc:varentry) ---
Variable
--- Type: Extension (book) ---
Extension
--- Type: Extension (set) ---
Extension
--- Type: Extension (reference) ---
Extension
--- Type: General (section, default) ---
General
--- methodName: DateTime::format → format ---
format
--- methodName: array_map → array_map ---
array_map
--- sdesc/ldesc fallback ---
DateTime::setTimezone
Date and Time Related Extensions
setTimezone
--- Entry structure ---
datetime.format
DateTime::format
refentry
