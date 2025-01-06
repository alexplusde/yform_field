<?php

class rex_yform_value_seo_title extends rex_yform_value_index
{
    public function postFormAction(): void
    {
        if (1 != $this->params['send']) {
            return;
        }

        $value = $this->getValue() ?? '';

        if ('' != $this->getElement('names')) {
            $index_labels = explode(',', $this->getElement('names'));

            $value = '';
            $relations = [];

            foreach ($index_labels as $name) {
                $name = trim($name);

                if ('id' == $name && $this->params['main_id'] > 0) {
                    $value .= $this->params['main_id'];
                }

                if (isset($this->params['value_pool']['sql'][$name])) {
                    $value .= ' ' . $this->params['value_pool']['sql'][$name];
                    continue;
                }
                $value .= trim($name, '"\'');

                $name = explode('.', $name);
                if (count($name) > 1) {
                    $this->addRelation($relations, $name);
                }
            }

            if ($relations) {
                foreach ($this->getRelationValues($relations) as $v) {
                    $value .= ' ' . $v;
                }
            }

            $value .= $this->getElement('salt');

            $fnc = trim($this->getElement('function'));
            if ('' != $fnc) {
                if (1 == $this->getElement('add_this_param')) {
                    $value = call_user_func($fnc, $value, $this) ?? '';
                } else {
                    $value = call_user_func($fnc, $value) ?? '';
                }
            }
        }

        $this->setValue(trim($value));

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->saveInDb()) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }
    }

    public function getDescription(): string
    {
        return 'seo_title|name|label|name1,name2,name3|[no_db]|[func/md5/sha]';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'seo_title',
            'values' => [
                'name' => ['type' => 'name',   'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_label')],
                'names' => ['type' => 'text',  'label' => rex_i18n::msg('yform_values_index_names'), 'notice' => rex_i18n::msg('yform_values_index_names_notice')],
                'no_db' => ['type' => 'no_db',   'label' => rex_i18n::msg('yform_values_defaults_table'),  'default' => 0],
                'function' => ['type' => 'text',  'label' => rex_i18n::msg('yform_values_index_function'), 'notice' => rex_i18n::msg('yform_values_index_function_notice')],
                'add_this_param' => ['type' => 'checkbox',   'label' => rex_i18n::msg('yform_values_index_add_this_param'),  'default' => 0],
                'salt' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_hashvalue_salt')],
            ],
            'description' => rex_i18n::msg('yform_values_index_description'),
            'db_type' => ['mediumtext', 'text', 'varchar(191)'], // text (65kb) mediumtext (16Mb)
            'multi_edit' => false,
        ];
    }
}
