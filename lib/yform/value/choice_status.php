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
                'choices' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_choices'), 'notice' => rex_i18n::msg('yform_values_choice_choices_notice').rex_i18n::rawMsg('yform_values_choice_choices_table')],
                'expanded' => ['type' => 'boolean', 'label' => rex_i18n::msg('yform_values_choice_expanded'), 'notice' => rex_i18n::msg('yform_values_choice_expanded_notice')],
                'multiple' => ['type' => 'boolean', 'label' => rex_i18n::msg('yform_values_choice_multiple'), 'notice' => rex_i18n::msg('yform_values_choice_multiple_notice').rex_i18n::rawMsg('yform_values_choice_expanded_multiple_table')],
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
            'description' => rex_i18n::msg('yform_values_choice_description'),
            'db_type' => ['text', 'int', 'tinyint(1)', 'varchar(191)'],
            'famous' => true,
        ];
    }

    public static function getListValue($params)
    {
        $listValues = self::getListValues($params);
        $return = [];
        foreach (explode(',', $params['value']) as $value) {
            if (isset($listValues[$value])) {
                $return[] = rex_i18n::translate($listValues[$value]);
            }
        }

        return implode('<br />', $return);
    }

    public static function getListValues($params)
    {
        $fieldName = $params['field'];
        if (!isset(self::$yform_list_values[$fieldName])) {
            $field = $params['params']['field'];

            $choiceList = self::createChoiceList([
                'choice_attributes' => (isset($field['choice_attributes'])) ? $field['choice_attributes'] : '',
                'choice_label' => (isset($field['choice_label'])) ? $field['choice_label'] : '',
                'choices' => (isset($field['choices'])) ? $field['choices'] : [],
                'expanded' => (isset($field['expanded'])) ? $field['expanded'] : '',
                'group_by' => (isset($field['group_by'])) ? $field['group_by'] : '',
                'multiple' => (isset($field['multiple'])) ? $field['multiple'] : false,
                'placeholder' => (isset($field['placeholder'])) ? $field['placeholder'] : '',
                'preferred_choices' => (isset($field['preferred_choices'])) ? $field['preferred_choices'] : [],
            ]);

            $choices = $choiceList->getChoicesByValues();
            foreach ($choices as $value => $label) {
                self::$yform_list_values[$fieldName][$value] = $label;
            }
        }
        return self::$yform_list_values[$fieldName];
    }


    /* Spezifisches */
    public static function yform_data_list_action_button(\rex_extension_point $ep)
    {
        $table_name = $ep->getParam('table')->getTableName();

        if ($table_name == "rex_akkreditieren") {
            $subject = $ep->getSubject();
            $subject[] = '<hr />';
            $subject[] = '<a href="index.php?rex-api-call=status&id=___id___&status=___status___">✅ akzeptieren</a>';
            $subject[] = '<a href="index.php?rex-api-call=status&id=___id___&status=___status___">❌ ablehnen</a>';
            $subject[] = '<hr />';
            return $subject;
        }
    }

    public static function yform_data_list_status($a)
    {
        $status_field = $a['params']['table']->getValueField('status');
        if ($status_field->getTypeName() == "choice") {
            $status_options = \rex_yform_value_choice::getListValues([
                'field'  => 'status',
                'params' => ['field' => $status_field],
            ]);
            
            $table_name  = $a['params']['table']->getTableName();
            $data_id = $a['list']->getValue('id');
            $token = self::yform_data_list_status_token($data_id, $table_name);
            $selected = $a['value'];

            $fragment = new rex_fragment();
            $fragment->setVar("options", $status_options);
            $fragment->setVar("selected", $selected);
            $fragment->setVar("table", $table_name);
            $fragment->setVar("data_id", $data_id);
            $fragment->setVar("token", $token);
            return $fragment->parse('akkreditieren/status_select.php');
        }
    }

    public static function yform_data_list_status_token($data_id, $table_name)
    {
        $secret = rex_config::get('yform_field', 'choice_status_secret');

        return password_hash($secret . $data_id . $table_name, PASSWORD_DEFAULT);
    }
}
