<?php /** @noinspection PhpLanguageLevelInspection */
/** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
foreach(scandir(__DIR__ . "/src") as $file) {
    if(!str_ends_with($file, ".php")) continue;
    $tokens = PhpToken::tokenize(file_get_contents(__DIR__ . "/src/" . $file));
    $last = null;
    $hasClass = false;
    foreach ($tokens as $token) {
        if($token->is(T_CLASS) && !$last?->is(T_DOUBLE_COLON)) {
            if($hasClass) {
                echo "$file\n";
            } else {
                $hasClass= true;
            }
        }
        $last = $token;
    }
//    echo "$file\n";
}