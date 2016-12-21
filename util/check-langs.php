<?php
if ($argc != 2 || (isset($argv[1]) && in_array($argv[1], array('--help', '-h')))) {
    echo "Check language files for missing/redundant keys\n\n";
    echo "Usage:               {$argv[0]} <lang>\n";
    echo "Alternative usage:   {$argv[0]} all\n";
    exit;
}

chdir('../phpdotnet/phd/data/langs/');

if ($argv[1] === 'all') {
    $languages = glob('*.ini');
    unset($languages[array_search('en.ini', $languages)]);
} else {
    $languages[] = $argv[1] . '.ini';
}

$englishKeys = array_keys(parse_ini_file('en.ini'));

foreach($languages as $language) {
    check_language($language, $englishKeys);
}

function check_language($path, $englishKeys) {
    $language = substr($path, 0, -4);

    if (!file_exists($path)) {
        exit("Unknown language: $language\n");
    }

    $languageKeys = array_keys(parse_ini_file($path));

    $missing = array_diff($englishKeys, $languageKeys);
    $redundant = array_diff($languageKeys, $englishKeys);

    echo "Checking $language...";

    if (!$missing && !$redundant) {
        echo " OK\n";
    }

    if ($missing) {
        echo "\n\tMissing (" . sizeof($missing) . "):\n";

        foreach ($missing as $text) {
            echo "\t\t$text\n";
        }
    }

    if ($redundant) {
        $placing = $missing ? "\t" : "\n\t";

        $redundant = implode(", ", $redundant);
        echo "{$placing}Redundant: $redundant\n";
    }
}
