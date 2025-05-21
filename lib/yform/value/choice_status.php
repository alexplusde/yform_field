<?php

class rex_yform_value_choice_status extends rex_yform_value_choice
{
    public function getDescription(): string
    {
        return 'choice_status|name|label|choices|[expanded type: boolean; default: 0, 0,1]|[multiple type: boolean; default: 0, 0,1]|[default]|[group_by]|[preferred_choices]|[placeholder]|[group_attributes]|[attributes]|[choice_attributes]|[notice]|[no_db]';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'choice_status',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label')],
                'choices' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_choices'), 'notice' => rex_i18n::msg('yform_values_choice_choices_notice') . rex_i18n::rawMsg('yform_values_choice_choices_table')],
                'expanded' => ['type' => 'boolean', 'label' => rex_i18n::msg('yform_values_choice_expanded'), 'notice' => rex_i18n::msg('yform_values_choice_expanded_notice')],
                'multiple' => ['type' => 'boolean', 'label' => rex_i18n::msg('yform_values_choice_multiple'), 'notice' => rex_i18n::msg('yform_values_choice_multiple_notice') . rex_i18n::rawMsg('yform_values_choice_expanded_multiple_table')],
                'default' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_default'), 'notice' => rex_i18n::msg('yform_values_choice_default_notice')],
                'group_by' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_group_by'), 'notice' => rex_i18n::msg('yform_values_choice_group_by_notice')],
                'preferred_choices' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_preferred_choices'), 'notice' => rex_i18n::msg('yform_values_choice_preferred_choices_notice')],
                'placeholder' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_placeholder'), 'notice' => rex_i18n::msg('yform_values_choice_placeholder_notice')],
                'group_attributes' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_group_attributes'), 'notice' => rex_i18n::msg('yform_values_choice_group_attributes_notice')],
                'attributes' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_attributes'), 'notice' => rex_i18n::msg('yform_values_choice_attributes_notice')],
                'choice_attributes' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_choice_attributes'), 'notice' => rex_i18n::msg('yform_values_choice_choice_attributes_notice')],
                'notice' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_notice')],
                'no_db' => ['type' => 'no_db', 'label' => rex_i18n::msg('yform_values_defaults_table'), 'default' => 0],
                'choice_label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_choice_label'), 'notice' => rex_i18n::msg('yform_values_choice_choice_label_notice')],
            ],
            'description' => rex_i18n::msg('yform_values_choice_status_description'),
            'db_type' => ['text', 'int', 'tinyint(1)', 'varchar(191)'],
        ];
    }

    public static function select($a)
    {
        $field = $a['field'];
        $status_field = $a['params']['table']->getValueField($field);
        $status_options = rex_yform_value_choice::getListValues([
            'field' => $field,
            'params' => ['field' => $status_field],
        ]);

        $table_name = $a['params']['table']->getTableName();
        $data_id = $a['list']->getValue('id');
        $token = self::getToken($data_id, $table_name);
        $selected_status = $a['value'];

        $fragment = new rex_fragment();
        $fragment->setVar('options', $status_options);
        $fragment->setVar('selected', $selected_status);
        $fragment->setVar('table', $table_name);
        $fragment->setVar('field', $field);
        $fragment->setVar('data_id', $data_id);
        $fragment->setVar('token', $token);

        // if ($selected_status == 0) {
        return $fragment->parse('choice_status_select.php');
        // }
        return $status_options[$selected_status];
    }

    public static function getToken($data_id, $table_name)
    {
        $secret = rex_config::get('yform_field', 'choice_status_secret');
        return hash_hmac('sha256', $data_id . $table_name, $secret);
    }
}
