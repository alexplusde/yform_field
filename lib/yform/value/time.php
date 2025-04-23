<?php

class rex_yform_value_time extends rex_yform_value_abstract
{
    public function enterObject()
    {
        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse(
                ['value.text.tpl.php'],
                ['type' => 'time', 'min' => $this->getElement('min'), 'max' => $this->getElement('max'), 'value' => date('H:i')],
            );
        }

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->saveInDb('2')) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }
    }

    public function getDescription(): string
    {
        return 'time|name|label|min|max|[1/Aktuelles Datum voreingestellt]|[no_db]';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'time',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label')],
                'min' => ['type' => 'text', 'attributes' => '{"type": "time", "value":"00:00"}', 'label' => rex_i18n::msg('yform_values_time_start')],
                'max' => ['type' => 'text', 'attributes' => '{"type": "time", "value":"00:00"}', 'label' => rex_i18n::msg('yform_values_time_end')],
                'current_date' => ['type' => 'boolean', 'label' => rex_i18n::msg('yform_values_time_current_time')],
                'no_db' => ['type' => 'no_db', 'label' => rex_i18n::msg('yform_values_defaults_table'),  'default' => 0],
                'attributes' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_attributes'), 'notice' => rex_i18n::msg('yform_values_defaults_attributes_notice')],
                'notice' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_notice')],
            ],
            'description' => rex_i18n::msg('yform_values_datetime_local_description'),
            'db_type' => ['varchar(5)'],
            'famous' => false,
        ];
    }
}
