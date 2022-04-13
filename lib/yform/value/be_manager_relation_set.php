<?php

class rex_yform_value_be_manager_relation_set extends rex_yform_value_be_manager_relation
{
    public function getDefinitions(): array
    {
        $definition = parent::getDefinitions();
        $defintion['db_type'] = ['text', 'varchar(191)', 'int', 'set'];
        return $definition;
    }
}
