<?php

rex_extension::register('PACKAGES_INCLUDED', function (rex_extension_point $ep) {
    rex_yform::addTemplatePath($this->getPath('ytemplates'));
});

if (rex::isBackend()) {
    rex_view::addCssFile($this->getAssetsUrl('be.min.css'));
}
