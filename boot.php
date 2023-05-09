<?php

rex_extension::register('PACKAGES_INCLUDED', function (rex_extension_point $ep) {
    rex_yform::addTemplatePath($this->getPath('ytemplates'));
});

if (rex::isBackend()) {
    rex_view::addCssFile($this->getAssetsUrl('be.min.css'));
}

/*
rex_extension::register('YFORM_DATA_LIST', function ($ep) {
    if ($ep->getParam('table')->getTableName() == "rex_akkreditieren") {
        $list = $ep->getSubject();


        $list->setColumnFormat('status', 'custom', ["akkreditieren", "yform_data_list_status"], ["table" => $ep->getParam('table')]);

        $list->setColumnFormat(
            'name',
            'custom',
            function ($a) {
                $_csrf_key = rex_yform_manager_table::get('rex_akkreditieren')->getCSRFKey();
                $token = rex_csrf_token::factory($_csrf_key)->getUrlParams();

                $params = array();
                $params['table_name'] = 'rex_akkreditieren';
                $params['rex_yform_manager_popup'] = '0';
                $params['_csrf_token'] = $token['_csrf_token'];
                $params['data_id'] = $a['list']->getValue('id');
                $params['function'] = 'edit';

                return '<a href="'.rex_url::backendPage('akkreditieren', $params) .'">'. $a['list']->getValue('name').'</a>';
            }
        );
    }
});

rex_view::addJsFile(rex_addon::get('yform_field')->getAssetsUrl('js/choice_status.js'));
*/
