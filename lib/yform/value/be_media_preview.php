<?php

class rex_yform_value_be_media_preview extends rex_yform_value_be_media
{
    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'be_media_preview',
            'values' => [
                'name' => ['type' => 'name',   'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_label')],
                'preview' => ['type' => 'checkbox',   'label' => rex_i18n::msg('yform_values_be_media_preview')],
                'multiple' => ['type' => 'checkbox',   'label' => rex_i18n::msg('yform_values_be_media_multiple')],
                'category' => ['type' => 'text',   'label' => rex_i18n::msg('yform_values_be_media_category')],
                'types' => ['type' => 'text',   'label' => rex_i18n::msg('yform_values_be_media_types'),   'notice' => rex_i18n::msg('yform_values_be_media_types_notice')],
                'notice' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_notice')],
            ],
            'description' => rex_i18n::msg('yform_values_be_media_preview_description'),
            'formbuilder' => false,
            'db_type' => ['text', 'varchar(191)'],
        ];
    }

    public static function getListValue($params)
    {
        $files = explode(',', $params['subject']);

        $return = [];
        foreach ($files as $file) {
            if (rex_media::get($file)) {
                $return[] = '<img style="width: 40px;" src="' . rex_media_manager::getUrl('rex_media_small', $files[0]) . '">';
            }
        }

        return implode('<br />', $return);
    }
}
