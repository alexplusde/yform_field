<?php

class rex_yform_action_to_session extends rex_yform_action_abstract
{
    public function executeAction(): void
    {
        $label = $this->getElement(2);
        $fields = array_filter(explode(',', $this->getElement(3)));
        $values = [];

        if (!empty($label) && $fields && isset($this->params['value_pool']['email'])) {
            foreach ($fields as $field) {
                if (!isset($this->params['value_pool']['email'][$field])) {
                    continue;
                }
                $values[$field] = $this->params['value_pool']['email'][$field];
            }
        } elseif (!empty($label) && isset($this->params['value_pool']['email'])) {
            $values = $this->params['value_pool']['email'];
        }
        rex_set_session($label, $values);
    }

    public function getDescription(): string
    {
        return 'action|to_session|session_variable_name|opt:field1,field2';
    }
}
