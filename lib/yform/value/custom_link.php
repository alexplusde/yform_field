<?php

/**
 * Author: Joachim Doerr
 * Date: 2019-02-26
 * Time: 10:27.
 */

class rex_yform_value_custom_url extends rex_yform_value_abstract
{
    public function enterObject()
    {
        static $counter = 0;
        ++$counter;

        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.custom_url.tpl.php', compact('counter'));
        }

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'custom_url',
            'values' => [
                'name' => ['type' => 'name',   'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text',   'label' => rex_i18n::msg('yform_values_defaults_label')],
                'media' => ['type' => 'checkbox',   'label' => rex_i18n::msg('yform_values_custom_url_media')],
                'external' => ['type' => 'checkbox',   'label' => rex_i18n::msg('yform_values_custom_url_external')],
                'mailto' => ['type' => 'checkbox',   'label' => rex_i18n::msg('yform_values_custom_url_mailto')],
                'intern' => ['type' => 'checkbox',   'label' => rex_i18n::msg('yform_values_custom_url_intern')],
                'phone' => ['type' => 'checkbox',   'label' => rex_i18n::msg('yform_values_custom_url_phone')],
                'types' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_custom_url_media_types')],
                'media_category' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_custom_url_media_category')],
                'category' => ['type' => 'be_url',    'label' => rex_i18n::msg('yform_values_custom_url_url_category')],
                'yurl' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_custom_url_yurl')],
                'notice' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_notice')],
            ],
            'description' => rex_i18n::msg('yform_values_custom_url_description'),
            'formbuilder' => false,
            'db_type' => ['text'],
        ];
    }

    public static function getListValue($params)
    {
        if ('' == $params['value']) {
            return '-';
        }
        return rex_var_custom_link::getCustomUrlText($params['value']);
    }
}
