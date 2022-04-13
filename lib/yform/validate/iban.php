<?php

class rex_yform_validate_iban extends rex_yform_validate_abstract
{
    public function enterObject()
    {
        $Object = $this->getValueObject();

        if (!$this->isObject($Object)) {
            return;
        }

        if ($Object->getValue() == '') {
            return;
        }

        if ($Object->getValue()) {

            
        $iban    = str_replace(array(
            ' ',
            '-',
            '.',
            ','
        ), '', strtoupper($Object->getValue()));

            $pattern = '#(?P<value>((?=[0-9A-Z]{28}$)AL\d{10}[0-9A-Z]{16}$|^(?=[0-9A-Z]{24}$)AD\d{10}[0-9A-Z]{12}$|^(?=[0-9A-Z]{20}$)AT\d{18}$|^(?=[0-9A-Z]{22}$)BH\d{2}[A-Z]{4}[0-9A-Z]{14}$|^(?=[0-9A-Z]{16}$)BE\d{14}$|^(?=[0-9A-Z]{20}$)BA\d{18}$|^(?=[0-9A-Z]{22}$)BG\d{2}[A-Z]{4}\d{6}[0-9A-Z]{8}$|^(?=[0-9A-Z]{21}$)HR\d{19}$|^(?=[0-9A-Z]{28}$)CY\d{10}[0-9A-Z]{16}$|^(?=[0-9A-Z]{24}$)CZ\d{22}$|^(?=[0-9A-Z]{18}$)DK\d{16}$|^FO\d{16}$|^GL\d{16}$|^(?=[0-9A-Z]{28}$)DO\d{2}[0-9A-Z]{4}\d{20}$|^(?=[0-9A-Z]{20}$)EE\d{18}$|^(?=[0-9A-Z]{18}$)FI\d{16}$|^(?=[0-9A-Z]{27}$)FR\d{12}[0-9A-Z]{11}\d{2}$|^(?=[0-9A-Z]{22}$)GE\d{2}[A-Z]{2}\d{16}$|^(?=[0-9A-Z]{22}$)DE\d{20}$|^(?=[0-9A-Z]{23}$)GI\d{2}[A-Z]{4}[0-9A-Z]{15}$|^(?=[0-9A-Z]{27}$)GR\d{9}[0-9A-Z]{16}$|^(?=[0-9A-Z]{28}$)HU\d{26}$|^(?=[0-9A-Z]{26}$)IS\d{24}$|^(?=[0-9A-Z]{22}$)IE\d{2}[A-Z]{4}\d{14}$|^(?=[0-9A-Z]{23}$)IL\d{21}$|^(?=[0-9A-Z]{27}$)IT\d{2}[A-Z]\d{10}[0-9A-Z]{12}$|^(?=[0-9A-Z]{20}$)[A-Z]{2}\d{5}[0-9A-Z]{13}$|^(?=[0-9A-Z]{30}$)KW\d{2}[A-Z]{4}22!$|^(?=[0-9A-Z]{21}$)LV\d{2}[A-Z]{4}[0-9A-Z]{13}$|^(?=[0-9A-Z]{,28}$)LB\d{6}[0-9A-Z]{20}$|^(?=[0-9A-Z]{21}$)LI\d{7}[0-9A-Z]{12}$|^(?=[0-9A-Z]{20}$)LT\d{18}$|^(?=[0-9A-Z]{20}$)LU\d{5}[0-9A-Z]{13}$|^(?=[0-9A-Z]{19}$)MK\d{5}[0-9A-Z]{10}\d{2}$|^(?=[0-9A-Z]{31}$)MT\d{2}[A-Z]{4}\d{5}[0-9A-Z]{18}$|^(?=[0-9A-Z]{27}$)MR13\d{23}$|^(?=[0-9A-Z]{30}$)MU\d{2}[A-Z]{4}\d{19}[A-Z]{3}$|^(?=[0-9A-Z]{27}$)MC\d{12}[0-9A-Z]{11}\d{2}$|^(?=[0-9A-Z]{22}$)ME\d{20}$|^(?=[0-9A-Z]{18}$)NL\d{2}[A-Z]{4}\d{10}$|^(?=[0-9A-Z]{15}$)NO\d{13}$|^(?=[0-9A-Z]{28}$)PL\d{10}[0-9A-Z]{,16}n$|^(?=[0-9A-Z]{25}$)PT\d{23}$|^(?=[0-9A-Z]{24}$)RO\d{2}[A-Z]{4}[0-9A-Z]{16}$|^(?=[0-9A-Z]{27}$)SM\d{2}[A-Z]\d{10}[0-9A-Z]{12}$|^(?=[0-9A-Z]{,24}$)SA\d{4}[0-9A-Z]{18}$|^(?=[0-9A-Z]{22}$)RS\d{20}$|^(?=[0-9A-Z]{24}$)SK\d{22}$|^(?=[0-9A-Z]{19}$)SI\d{17}$|^(?=[0-9A-Z]{24}$)ES\d{22}$|^(?=[0-9A-Z]{24}$)SE\d{22}$|^(?=[0-9A-Z]{21}$)CH\d{7}[0-9A-Z]{12}$|^(?=[0-9A-Z]{24}$)TN59\d{20}$|^(?=[0-9A-Z]{26}$)TR\d{7}[0-9A-Z]{17}$|^(?=[0-9A-Z]{,23}$)AE\d{21}$|^(?=[0-9A-Z]{22}$)GB\d{2}[A-Z]{4}\d{14}))#';
            // check
            if (preg_match($pattern, $iban, $matches)) {
                return false;
            } else {
                $this->params['warning'][$Object->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$Object->getId()] = $this->getElement('message');
                return true;
            }
        }
    }

    public function getDescription()
    {
        return 'validate|iban|fieldname|warning_message';
    }

    public function getDefinitions()
    {
        return [
            'type' => 'validate',
            'name' => 'iban',
            'values' => [
                'name' => ['type' => 'select_name', 'label' => rex_i18n::msg('yform_validate_iban_name')],
                'message' => ['type' => 'text',        'label' => rex_i18n::msg('yform_validate_iban_message')],
            ],
            'description' => rex_i18n::msg('yform_validate_iban_description'),
        ];
    }

    public function isDeprecated()
    {
        return true;
    }
}
