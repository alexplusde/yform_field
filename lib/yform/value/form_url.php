<?php

class rex_yform_value_form_url extends rex_yform_value_abstract
{
    public function setValue($value)
    {
        $this->value = rex_getUrl(rex_article::getCurrentId());
    }

    public function enterObject()
    {
        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.hidden.tpl.php', ['fieldName' => $this->getElement(1)]);
        }

        $this->params['value_pool']['email'][$this->getName()] = $this->getValue();
        if ($this->saveInDb('2')) {
            $this->params['value_pool']['sql'][$this->getName()] = $this->getValue();
        }
    }

    public function getDescription(): string
    {
        return 'form_url|fieldname|[no_db]';
    }
}
