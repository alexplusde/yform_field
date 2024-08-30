<?php

class rex_yform_value_be_hidden extends rex_yform_value_hidden
{
    public function getDescription(): string
    {
        return 'be_hidden|fieldname|value||[no_db]' . "\n" . 'hidden|fieldname|key|REQUEST/GET/POST/SESSION|[no_db]';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'be_hidden',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name')],
                'key' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_be_hidden_key')],
                'type' => ['type' => 'choice', 'label' => rex_i18n::msg('yform_values_be_hidden_type'), 'choices' => ['' => '', 'REQUEST', 'GET', 'POST', 'SESSION'], 'default' => ''],
                'no_db' => ['type' => 'no_db', 'label' => rex_i18n::msg('yform_values_defaults_table'),  'default' => 0],
            ],
            'description' => 'Versteckte Eingabe',
            'db_type' => ['varchar(191)', 'text',  'int', 'set', 'datetime', 'date'],
        ];
    }
}
