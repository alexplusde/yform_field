<?php

$addon = rex_addon::get('yform');

// In YForm 5 bereits auf 200 gestellt, daher kann man das hier relativ gefahrlos patchen.

if (version_compare($addon->getVersion(), '5.0.0', '<')) {
    $file = rex_path::addon('yform', 'lib/manager/manager.php');
    $content = rex_file::get($file);
    if (!empty($content) && !str_contains($content, 'rex_list::factory($sql, rowsPerPage: 200, defaultSort: [')) {
        $content = preg_replace(
            '/rex_list::factory\(\$sql,\s*defaultSort:/',
            'rex_list::factory($sql, rowsPerPage: 200, defaultSort:',
            $content,
            1,
        );
        rex_file::put($file, $content);
    }
}
