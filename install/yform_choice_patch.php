<?php

$path = rex_path::addon('yform', 'lib/yform/value/choice.php');
if (rex_path::addon('yform', 'lib/yform/value/choice.php')) {
    $file = rex_file::get($path);
    if (!$file) {
        return;
    }
    $file = str_replace('private static function createChoiceList($elements)', 'static function createChoiceList($elements)', $file);
    rex_file::put($path, $file);
}
