<?php

class rex_yform_value_datalist extends rex_yform_value_abstract
{
    public function enterObject()
    {
        // Get the choices for the datalist
        $choices = $this->getChoices();

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->saveInDb()) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }

        if (!$this->needsOutput() && !$this->isViewable()) {
            return;
        }

        if (!$this->isEditable()) {
            $this->params['form_output'][$this->getId()] = $this->parse(
                ['value.datalist-view.tpl.php', 'value.view.tpl.php'],
                ['value' => $this->getValue()],
            );
        } else {
            $attributes = [
                'type' => 'text',
                'id' => $this->getFieldId(),
                'name' => $this->getFieldName(),
                'value' => $this->getValue(),
                'list' => $this->getFieldId() . '_datalist',
            ];

            if ($this->getElement('placeholder')) {
                $attributes['placeholder'] = rex_i18n::translate($this->getElement('placeholder'));
            }

            $attributes = $this->getAttributes('attributes', $attributes, [
                'autocomplete', 'disabled', 'pattern', 'readonly', 'required', 'placeholder', 'maxlength',
            ]);

            $this->params['form_output'][$this->getId()] = $this->parse(
                'value.datalist.tpl.php',
                [
                    'attributes' => $attributes,
                    'choices' => $choices,
                    'datalist_id' => $this->getFieldId() . '_datalist',
                ],
            );
        }
    }

    protected function getChoices(): array
    {
        $choicesElement = $this->getElement('choices');
        $choices = [];

        if (is_string($choicesElement) && 'SELECT' == rex_sql::getQueryType($choicesElement)) {
            $sql = rex_sql::factory();
            $sql->setDebug($this->getParam('debug'));
            $result = $sql->getArray($choicesElement);

            foreach ($result as $row) {
                $rowValues = array_values($row);
                $key = $rowValues[0];
                $value = $rowValues[1] ?? $key;
                $choices[$key] = $value;
            }
        } elseif (is_string($choicesElement) && mb_strlen(trim($choicesElement)) > 0 && '{' == mb_substr(trim($choicesElement), 0, 1) && '{{' != mb_substr(trim($choicesElement), 0, 2)) {
            // JSON format
            $json = json_decode($choicesElement, true);
            if (is_array($json)) {
                $choices = $json;
            }
        } elseif (is_callable($choicesElement)) {
            $res = call_user_func($choicesElement);
            if (is_array($res)) {
                $choices = $res;
            } else {
                $json = json_decode($res, true);
                if (is_array($json)) {
                    $choices = $json;
                }
            }
        } else {
            // String array format
            $choices = $this->getArrayFromString($choicesElement);
        }

        return $choices;
    }

    public function getDescription(): string
    {
        return 'datalist|name|label|choices|[default]|[placeholder]|[attributes]|[notice]|[no_db]';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'datalist',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label')],
                'choices' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_choice_choices'), 'notice' => rex_i18n::msg('yform_values_choice_choices_notice') . rex_i18n::rawMsg('yform_values_choice_choices_table')],
                'default' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_default')],
                'placeholder' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_text_placeholder')],
                'attributes' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_attributes'), 'notice' => rex_i18n::msg('yform_values_defaults_attributes_notice')],
                'notice' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_notice')],
                'no_db' => ['type' => 'no_db', 'label' => rex_i18n::msg('yform_values_defaults_table'), 'default' => 0],
            ],
            'description' => rex_i18n::msg('yform_values_datalist_description'),
            'db_type' => ['text', 'varchar(191)', 'varchar(255)'],
            'famous' => true,
        ];
    }
}
