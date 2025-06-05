<?php

$addon = rex_addon::get('yform');

if (version_compare($addon->getVersion(), '5.0.0', '<')) {
    $patched_uuid_file = __DIR__ . '/yform/uuid.php';
    $target_uuid_file = rex_path::addon('yform', 'lib/yform/value/uuid.php');
    if (file_exists($patched_uuid_file) && !file_exists($target_uuid_file)) {
        // Copy the patched uuid.php file to the target location
        rex_file::copy($patched_uuid_file, $target_uuid_file);
    }
}
