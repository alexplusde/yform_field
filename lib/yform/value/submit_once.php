<?php

class rex_yform_value_submit_once extends rex_yform_value_submit
{
    public function init()
    {
        $this->params['submit_btn_show'] = false;
    }

    public function enterObject()
    {
        $label = $this->getElement('label');

        if ('' == $this->getElement('css_classes')) {
            $this->setElement('css_classes', 'btn-primary');
        }
        $css_classes = $this->getElement('css_classes');

        if ('' == $this->getElement('loading')) {
            $this->setElement('loading', 'loading');
        }
        $loading = $this->getElement('loading');

        if ($this->needsOutput() && $this->isViewable()) {
            if (!$this->isEditable()) {
            } else {
                $this->params['form_output'][$this->getId()] = $this->parse('value.submit_once.tpl.php', compact('label', 'loading', 'css_classes'));
            }
        }
    }

    public function getDescription(): string
    {
        return 'submit_once|name|label|loadingtext|[cssclass]';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'submit_once',
            'values' => [
                'name' => ['type' => 'name',    'label' => rex_i18n::msg('yform_values_defaults_name')],
                'label' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_submit_once_label')],
                'loading' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_submit_once_loading')],
                'css_classes' => ['type' => 'text',    'label' => rex_i18n::msg('yform_values_submit_css_classes'),
                ],
            ],
            'deprecated' => false,
            'description' => rex_i18n::msg('yform_values_submit_once_description'),
            'db_type' => ['none'],
        ];
    }
}
