<?php

rex_extension::register('PACKAGES_INCLUDED', function (rex_extension_point $ep) {
    rex_yform::addTemplatePath($this->getPath('ytemplates'));
});

if (rex::isBackend()) {
    rex_view::addCssFile($this->getAssetsUrl('be.min.css'));
    rex_view::addJsFile(rex_addon::get('yform_field')->getAssetsUrl('choice_status.js'));
}

rex_extension::register('YFORM_DATA_LIST', static function ($ep) {
    $list = $ep->getSubject();

    $table = $ep->getParam('table');

    foreach ($table->getFields() as $field) {
        if ('choice_status' == $field->getTypeName()) {
            $list->setColumnFormat($field->getName(), 'custom', ['rex_yform_value_choice_status', 'select'], ['table' => $ep->getParam('table')]);
        }
    }
});

if (rex::isBackend() && 'yform/email/index' == rex_be_controller::getCurrentPage()) {
    yform_field::email_template_test();
}
