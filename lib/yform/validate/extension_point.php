<?php

class rex_yform_validate_extension_point extends rex_yform_validate_abstract
{
    public function enterObject()
    {
        // Hole die übergebenen Parameter
        $name = $this->getElement(1); // Name (optional)
        $ep_name = $this->getElement(2); // Extension Point Name
        $label = $this->getElement(3); // Label (optional)

        // Prüfe, ob das Formular valide ist
        if ($this->params['send'] && !$this->params['form_show']) {
            // Extension Point auslösen und Formularobjekt übergeben
            rex_extension::registerPoint($ep_name ?: 'YFORM_VALIDATE_EP', [
                'form_object' => $this->params['form'],
                'label' => $label,
                'name' => $name,
                'params' => $this->params,
            ]);
        }
    }

    public function getDescription(): string
    {
        return 'validate|extension_point|name|ep_name|label';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'validate',
            'name' => 'extension_point',
            'values' => [
                'name' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_extension_point_name')],
                'ep_name' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_extension_point_ep_name')],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_extension_point_label')],
            ],
            'description' => rex_i18n::msg('yform_validate_extension_point_description'),
        ];
    }
}