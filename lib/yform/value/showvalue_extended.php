<?php

class rex_yform_value_showvalue_extended extends rex_yform_value_showvalue
{
    public function getDescription(): string
    {
        return 'showvalue_extended|name|label|defaultwert|notice';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'showvalue_extended',
            'values' => [
                'name' => ['type' => 'name',    'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_label')],
                'default' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_text_default')],
                'notice' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_notice')],
            ],
            'description' => rex_i18n::msg('yform_values_showvalue_extended_description'),
            'db_type' => ['text', 'varchar(191)', 'mediumtext', 'longtext'],
        ];
    }
}
