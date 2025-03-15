<?php

class rex_yform_value_number_lat extends rex_yform_value_number
{
    /**
     * Nachdem ggf. auf das Feld gesetzte individuelle Validierungen durchgeführt
     * wurden, erfolgt hier noch final die impliziete Validierung auf den Gültigkeits-
     * bereich der Koordinate (-90.0° ... 90.0°).
     *
     * Überprüfungen z.B. auf
     * - leere Felder
     * - nicht numerische String-Eingaben
     * - NULL
     * müssen als individuelle Validierungen durchgeführt werden bzw. sind
     * irgendwie in der Parent-Klasse realisiert.
     *
     * Wenn es bereits eine Fehlermeldung aus inidividuellen Validierungen gibt
     * entfällt die Aktion. Gleiches gilt für Werte, die nicht numerisch sind
     * (NULL, string).
     */
    public function postValidateAction(): void
    {
        parent::postValidateAction();

        if (isset($this->params['warning'][$this->getId()])) {
            return;
        }

        $value = $this->getValue() ?? '';
        if ('' === trim($value)) {
            return;
        }

        $value = is_numeric($value) ? (float) $value : 999;
        if ($value < -90.0 || $value > 90.0) {
            $this->params['warning'][$this->getId()] = $this->params['error_class'];
            $this->params['warning_messages'][$this->getId()] = rex_i18n::msg('yform_values_numberlat_range_error', $this->getLabel());
        }
    }

    public function getDescription(): string
    {
        return 'number_lat|name|label|precision|scale|defaultwert|[no_db]|[unit]|[notice]|[attributes]';
    }

    /**
     * @return array<string,mixed>
     */
    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'number_lat',
            'values' => [
                'name' => ['type' => 'name',    'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_label')],
                'precision' => ['type' => 'integer', 'label' => rex_i18n::msg('yform_values_number_precision'), 'default' => '10'],
                'scale' => ['type' => 'integer', 'label' => rex_i18n::msg('yform_values_number_scale'), 'default' => '8'],
                'default' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_number_default')],
                'no_db' => ['type' => 'no_db',   'label' => rex_i18n::msg('yform_values_defaults_table'),  'default' => 0],
                'widget' => ['type' => 'choice', 'label' => rex_i18n::msg('yform_values_defaults_widgets'), 'choices' => ['input:text' => 'input:text', 'input:number' => 'input:number'], 'default' => 'input:text'],
                'unit' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_unit')],
                'notice' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_notice')],
                'attributes' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_defaults_attributes'), 'notice' => rex_i18n::msg('yform_values_defaults_attributes_notice')],
            ],
            'validates' => [
                ['type' => ['name' => 'precision', 'type' => 'integer', 'message' => rex_i18n::msg('yform_values_number_error_precision', '1', '65'), 'not_required' => false]],
                ['type' => ['name' => 'scale', 'type' => 'integer', 'message' => rex_i18n::msg('yform_values_number_error_scale', '0', '30'), 'not_required' => false]],
                ['compare' => ['name' => 'scale', 'name2' => 'precision', 'message' => rex_i18n::msg('yform_values_number_error_compare'), 'compare_type' => '>']],
                ['intfromto' => ['name' => 'precision', 'from' => '1', 'to' => '65', 'message' => rex_i18n::msg('yform_values_number_error_precision', '1', '65')]],
                ['intfromto' => ['name' => 'scale', 'from' => '0', 'to' => '30', 'message' => rex_i18n::msg('yform_values_number_error_scale', '0', '30')]],
            ],
            'description' => rex_i18n::msg('yform_values_numberlat_description'),
            'db_type' => ['DECIMAL({precision},{scale})'],
            'hooks' => [
                'preCreate' => static function (rex_yform_manager_field $field, $db_type) {
                    $db_type = str_replace('{precision}', (string) ($field->getElement('precision') ?? 10), $db_type);
                    $db_type = str_replace('{scale}', (string) ($field->getElement('scale') ?? 8), $db_type);
                    return $db_type;
                },
            ],
            'db_null' => true,
        ];
    }
}
