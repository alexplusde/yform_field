<?php

class rex_yform_value_datetime_local extends rex_yform_value_abstract
{
    public const VALUE_DATETIME_DEFAULT_FORMAT = IntlDateFormatter::FULL;
    public const VALUE_DATE_FORMATS = [IntlDateFormatter::NONE => 'ausblenden', IntlDateFormatter::SHORT => 'IntlDateFormatter::SHORT', IntlDateFormatter::MEDIUM => 'IntlDateFormatter::MEDIUM', 'IntlDateFormatter::FULL' => 'IntlDateFormatter::FULL'];
    public const VALUE_TIME_FORMATS = [IntlDateFormatter::NONE => 'ausblenden', IntlDateFormatter::SHORT => 'IntlDateFormatter::SHORT', IntlDateFormatter::MEDIUM => 'IntlDateFormatter::MEDIUM', 'IntlDateFormatter::FULL' => 'IntlDateFormatter::FULL'];

    public function preValidateAction(): void
    {
        $value = $this->getValue();
        if (1 == $this->getElement('current_date') && '' == $this->getValue() && $this->params['main_id'] < 1) {
            $value = date('Y-m-d H:i:s');
        } else {
            $value = str_replace('T', ' ', (string) $value);
        }
        $this->setValue($value);
    }

    public function enterObject()
    {
        $value = (string) $this->getValue();

        $this->setValue(str_replace('T', ' ', (string) $this->getValue()));

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->saveInDb()) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }

        if (!$this->needsOutput() && !$this->isViewable()) {
            return;
        }

        if (!$this->isEditable()) {
            $this->params['form_output'][$this->getId()] = $this->parse(
                ['value.datetime-view.tpl.php', 'value.date-view.tpl.php', 'value.view.tpl.php'],
                ['type' => 'text', 'value' => $this->getValue()],
            );
        } else {
            $dateValue = date_create($this->getValue());

            $this->params['form_output'][$this->getId()] = $this->parse(
                ['value.text.tpl.php'],
                ['type' => 'datetime-local', 'min' => $this->getElement('min'), 'max' => $this->getElement('max'), 'value' => date_format($dateValue, 'Y-m-d\TH:i')],
            );
        }
    }

    public static function date_formatter($format_date = IntlDateFormatter::FULL, $format_time = IntlDateFormatter::SHORT, $lang = 'de')
    {
        return datefmt_create($lang, $format_date, $format_time, null, IntlDateFormatter::GREGORIAN);
    }

    public function getDescription(): string
    {
        return 'datetime|name|label|min|max|[1/Aktuelles Datum voreingestellt]|[no_db]';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'datetime_local',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label')],
                'min' => ['type' => 'text', 'attributes' => '{"type": "date", "value":""}', 'label' => rex_i18n::msg('yform_values_datetime_start')],
                'max' => ['type' => 'text', 'attributes' => '{"type": "date", "value":""}', 'label' => rex_i18n::msg('yform_values_datetime_end')],
                'current_date' => ['type' => 'boolean', 'label' => rex_i18n::msg('yform_values_datetime_current_date')],
                'no_db' => ['type' => 'no_db', 'label' => rex_i18n::msg('yform_values_defaults_table'),  'default' => 0],
                'attributes' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_attributes'), 'notice' => rex_i18n::msg('yform_values_defaults_attributes_notice')],
                'notice' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_notice')],
            ],
            'description' => rex_i18n::msg('yform_values_datetime_local_description'),
            'db_type' => ['datetime'],
            'famous' => true,
        ];
    }

    public static function getListValue($params): string
    {
        if ('0000-00-00 00:00:00' !== $params['value']) {
            return '' . self::date_formatter()->format(strtotime($params['value'])) . '';
        }
        return '---';
    }

    public static function getSearchField($params)
    {
        $format = 'YYYY-MM-DD HH:ii:ss';
        $params['searchForm']->setValueField('text', ['name' => $params['field']->getName(), 'label' => $params['field']->getLabel(), 'notice' => rex_i18n::msg('yform_values_date_search_notice', $format), 'attributes' => '{"data-yform-tools-daterangepicker":"' . $format . '"}']);
    }

    public static function getSearchFilter($params)
    {
        $value = trim($params['value']);
        /** @var rex_yform_manager_query $query */
        $query = $params['query'];
        $field = $query->getTableAlias() . '.' . $params['field']->getName();

        if ('' == $value) {
            return $query;
        }

        $format = 'YYYY-MM-DD HH:ii:ss';
        $format_len = mb_strlen($format);
        $firstchar = mb_substr($value, 0, 1);

        switch ($firstchar) {
            case '>':
            case '<':
            case '=':
                $value = mb_substr($value, 1);
                return $query->where($field, $value, $firstchar);
        }

        if (mb_strlen($value) == $format_len) {
            return $query->where($field, $value);
        }

        $dates = explode(' - ', $value);
        if (2 == count($dates)) {
            $date_from = $dates[0];
            $date_to = $dates[1];

            return $query
                    ->where($field, $date_from, '>=')
                    ->where($field, $date_to, '<=');
        }

        // plain compare
        return $query->where($field, $value);
    }
}
