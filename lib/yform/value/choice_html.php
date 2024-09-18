<?php

class rex_yform_value_choice_html extends rex_yform_value_choice
{
    public static $yform_list_values = [];

    public function enterObject()
    {
        $choiceList = self::createChoiceList([
            'choice_attributes' => $this->getElement('choice_attributes'),
            'choice_label' => $this->getElement('choice_label'),
            'choices' => $this->getElement('choices'),
            'expanded' => $this->getElement('expanded'),
            'group_by' => $this->getElement('group_by'),
            'multiple' => $this->getElement('multiple'),
            'placeholder' => $this->getElement('placeholder'),
            'preferred_choices' => $this->getElement('preferred_choices'),
        ]);

        if (null === $this->getValue()) {
            $this->setValue([]);
        } elseif (!is_array($this->getValue())) {
            $this->setValue(explode(',', $this->getValue()));
        }

        $values = $this->getValue();

        if (!$values) {
            if (in_array($this->getElement('default'), $choiceList->getChoices(), true)) {
                $defaultChoices = [$this->getElement('default')];
            } else {
                $defaultChoices = explode(',', $this->getElement('default'));
            }
            if (!$choiceList->isMultiple() && count($defaultChoices) >= 2) {
                throw new InvalidArgumentException('Expecting one default value for ' . $this->getFieldName() . ', but ' . count($defaultChoices) . ' given!');
            }
            $this->setValue($choiceList->getDefaultValues($defaultChoices));
        }

        $proofedValues = $choiceList->getProofedValues($values);

        if ($this->needsOutput() && $this->isViewable()) {
            $groupAttributes = [];
            if (false !== $this->getElement('group_attributes')) {
                $groupAttributes = $this->getAttributes('group_attributes', $groupAttributes);
            }

            $choiceAttributes = [];
            $elementAttributes = [];
            if ($choiceList->isExpanded()) {
                if (false !== $this->getElement('attributes')) {
                    $elementAttributes = $this->getAttributes('attributes', $elementAttributes);
                }

                $choiceAttributes = [
                    'id' => $this->getFieldId(),
                    'name' => $this->getFieldName(),
                    'type' => 'radio',
                ];
                if ($choiceList->isMultiple()) {
                    $choiceAttributes['name'] .= '[]';
                    $choiceAttributes['type'] = 'checkbox';
                }
            } else {
                $elementAttributes['id'] = $this->getFieldId();
                $elementAttributes['name'] = $this->getFieldName();

                if ($choiceList->isMultiple()) {
                    $elementAttributes['name'] .= '[]';
                    $elementAttributes['multiple'] = 'multiple';
                    $elementAttributes['size'] = count($choiceList->getChoices());
                }
                $elementAttributes = $this->getAttributes('attributes', $elementAttributes, ['autocomplete', 'disabled', 'pattern', 'readonly', 'required', 'size']);
            }

            $choiceListView = $choiceList->createView($choiceAttributes);

            $template = $choiceList->isExpanded() ? 'value.choice.check_html.tpl.php' : 'value.choice.select.tpl.php';

            if (!$this->isEditable()) {
                $template = str_replace('choice', 'choice-view', $template);
                $getChoices = static function ($choices, $options) use (&$getChoices) {
                    foreach ($choices as $choice) {
                        if ('rex_yform_choice_group_view' == $choice::class) {
                            /** @var rex_yform_choice_group_view $choice */
                            $options = $getChoices($choice->choices, $options);
                        } else {
                            /* @var rex_yform_choice_view $choice */
                            $options[$choice->getValue()] = $choice->getLabel();
                        }
                    }
                    return $options;
                };
                $options = $getChoices($choiceListView->choices, []);
                $html = $this->parse([$template, 'value.view.tpl.php'], compact('options', 'choiceList', 'choiceListView', 'elementAttributes', 'groupAttributes'));
            } else {
                $html = $this->parse($template, compact('choiceList', 'choiceListView', 'elementAttributes', 'groupAttributes'));
            }

            $html = trim(preg_replace(['/\s{2,}/', '/>\s+/', '/\s+</'], [' ', '>', '<'], $html));
            $this->params['form_output'][$this->getId()] = $html;
        }

        $this->setValue(implode(',', $proofedValues));

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        $this->params['value_pool']['email'][$this->getName() . '_LABELS'] = implode(', ', $choiceList->getSelectedListForEmail($values));
        $this->params['value_pool']['email'][$this->getName() . '_LIST'] = implode("\n", $choiceList->getCompleteListForEmail($values));

        if ($this->saveInDb()) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }
    }

    public function getDescription(): string
    {
        return 'choice_html|name|label|choices|[expanded type: boolean; default: 0, 0,1]|[multiple type: boolean; default: 0, 0,1]|[default]|[group_by]|[preferred_choices]|[placeholder]|[group_attributes]|[attributes]|[choice_attributes]|[notice]|[no_db]';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'choice_html',
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
            'description' => rex_i18n::msg('yform_values_choice_description'),
            'db_type' => ['text', 'int', 'int(10) unsigned', 'tinyint(1)', 'varchar(191)'],
            'famous' => true,
        ];
    }
}
