<?php

class rex_yform_value_domain extends rex_yform_value_abstract
{
    public static function domains(): array
    {
        $domains = [0 => rex::getServer() . ' [alle]'];

        if (rex_addon::get('yrewrite')->isAvailable()) {
            $domains_sql = rex_sql::factory()->getArray('SELECT id, domain FROM rex_yrewrite_domain ORDER BY domain');
            foreach ($domains_sql as $domain) {
                $domains[$domain['id']] = $domain['domain'];
            }
        }
        return $domains;
    }

    public function enterObject(): void
    {
        $options = self::domains();
        $multiple = true;

        $values = $this->getValue();
        if (!is_array($values)) {
            $values = explode(',', $values ?? '');
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
        return 'domain|name|label|attributes|notice';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'domain',
            'values' => [
                'name' => ['type' => 'name',   'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_label')],
                'attributes' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_attributes'), 'notice' => rex_i18n::msg('yform_values_defaults_attributes_notice')],
                'notice' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_notice')],
            ],
            'description' => rex_i18n::msg('yform_values_domain_description'),
            'db_type' => ['text', 'int', 'varchar(191)', 'set'],
        ];
    }

    public static function getListValue(array $params): string
    {
        $return = [];

        $new_select = new self();
        $values = self::domains();

        foreach (explode(',', $params['value'] ?? '') as $k) {
            if (isset($values[$k])) {
                $return[] = rex_i18n::translate($values[$k]);
            }
        }

        return implode('<br />', $return);
    }

    public static function getSearchField(array $params): void
    {
        $options = [];
        $options['(empty)'] = '(empty)';
        $options['!(empty)'] = '!(empty)';
        $options += self::domains();

        $new_select = new self();

        // Kürze jeden Wert in $options um `http://` und `htttps://`
        foreach ($options as $key => $value) {
            $options[$key] = str_replace(['http://', 'https://'], '', $value);
        }

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
}
