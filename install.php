<?php

use ScssPhp\ScssPhp\Formatter\Expanded;

/** @var rex_addon $this */

/**
 * Erstellt eine CSS-Datei basierend auf den Backend-Styles aus dem Addon be_style (falls aktiv).
 * rex_scss_compiler ist verfügbar wenn be_style installiert ist.
 */
if (class_exists('rex_scss_compiler')) {
    $compiler = new rex_scss_compiler();

    if (rex::isDebugMode() || false === $this->getProperty('compress_assets', true)) {
        // Klartext-Ausgabe falls man für Tests "lesbares" CSS erzeugen möchte
        $compiler->setFormatter(Expanded::class);
    }

    $compiler->setRootDir(__DIR__ . '/scss');
    $compiler->setScssFile([
        rex_path::plugin('be_style', 'redaxo', 'scss/_variables.scss'),
        rex_path::plugin('be_style', 'redaxo', 'scss/_variables-dark.scss'),
        rex_path::addon('be_style', 'vendor/font-awesome/scss/_variables.scss'),
        __DIR__ . '/scss/be.scss',
    ]);

    $compiler->setCssFile(__DIR__ . '/assets/be.min.css');
    $compiler->compile();
}

if (null == rex_config::get('yform_field', 'choice_status_secret')) {
    rex_config::set('yform_field', 'choice_status_secret', bin2hex(random_bytes(16)));
}

include __DIR__ . '/install/yform_choice_patch.php';
include __DIR__ . '/install/yform_field_list_patch.php';
