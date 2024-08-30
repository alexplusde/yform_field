<?php

class rex_yform_value_be_user_select extends rex_yform_value_abstract
{
    public static function user(): array
    {
        $users = [];
        $user_sql = rex_sql::factory()->getArray('SELECT id, `name` FROM rex_user ORDER BY `name`');
        foreach ($user_sql as $user) {
            $users[$user['id']] = $user['name'];
        }
        return $users;
    }

    public function enterObject(): void
    {
        $options = self::user();
        $multiple = true;

        $values = $this->getValue();
        if (!is_array($values)) {
            $values = explode(',', $values);
        }

        $real_values = [];
        foreach ($values as $value) {
            if (isset($options[$value])) {
                $real_values[] = $value;
            }
        }

        $this->setValue($real_values);

        // ---------- rex_yform_set
        if (isset($this->params['rex_yform_set'][$this->getName()]) && !is_array($this->params['rex_yform_set'][$this->getName()])) {
            $value = $this->params['rex_yform_set'][$this->getName()];
            $values = [];
            if (array_key_exists($value, $options)) {
                $values[] = (string) $value;
            }
            $this->setValue($values);
            $this->setElement('disabled', true);
        }

        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.select.tpl.php', compact('options', 'multiple'));
        }

        $this->setValue(implode(',', $this->getValue()));

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        $this->params['value_pool']['email'][$this->getName() . '_NAME'] = isset($options[$this->getValue()]) ? $options[$this->getValue()] : null;

        if ($this->saveInDB()) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }
    }

    public function getDescription(): string
    {
        return 'be_user_select|name|label|attributes|';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'be_user_select',
            'values' => [
                'name' => ['type' => 'name',   'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_label')],
                'attributes' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_attributes'), 'notice' => rex_i18n::msg('yform_values_defaults_attributes_notice')],
                'notice' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_notice')],
            ],
            'description' => rex_i18n::msg('yform_values_be_user_select_description'),
            'db_type' => ['text', 'varchar(191)', 'int'],
        ];
    }

    public static function getListValue(array $params): string
    {
        $return = [];

        $new_select = new self();
        $values = self::user();

        foreach (explode(',', $params['value']) as $k) {
            if (isset($values[$k])) {
                $return[] = rex_i18n::translate($values[$k]);
            }
        }

        return implode('<br />', $return);
    }

    public static function getSearchField(array $params): void
    {
        $options = self::user();
        $options['(empty)'] = '(empty)';
        $options['!(empty)'] = '!(empty)';

        $new_select = new self();
        $options = self::user();

        $params['searchForm']->setValueField(
            'select',
            [
                'name' => $params['field']->getName(),
                'label' => $params['field']->getLabel(),
                'options' => $options,
                'multiple' => 1,
                'size' => 5,
                'notice' => rex_i18n::msg('yform_search_defaults_select_notice'),
            ],
        );
    }

    public static function getSearchFilter(array $params): string
    {
        $sql = rex_sql::factory();

        $field = $params['field']->getName();

        $self = new self();
        $values = $self->getArrayFromString($params['value']);

        $multiple = true;

        $where = [];
        foreach ($values as $value) {
            switch ($value) {
                case '(empty)':
                    $where[] = ' ' . $sql->escapeIdentifier($field) . ' = ""';
                    break;
                case '!(empty)':
                    $where[] = ' ' . $sql->escapeIdentifier($field) . ' != ""';
                    break;
                default:
                    if ($multiple) {
                        $where[] = ' ( FIND_IN_SET( ' . $sql->escape($value) . ', ' . $sql->escapeIdentifier($field) . ') )';
                    } else {
                        $where[] = ' ( ' . $sql->escape($value) . ' = ' . $sql->escapeIdentifier($field) . ' )';
                    }

                    break;
            }
        }

        if (count($where) > 0) {
            return ' ( ' . implode(' or ', $where) . ' )';
        }
        return '';
    }
}
