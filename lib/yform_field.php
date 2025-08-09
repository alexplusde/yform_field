<?php

class yform_field
{
    /**
     * Array to store registered datalist output callbacks
     * @var array
     */
    private static $dataListCallbacks = [];
    
    /**
     * Flag to ensure extension is only registered once
     * @var bool
     */
    private static $extensionRegistered = false;

    /**
     * Modifies the output of a specific field in YForm data tables
     * 
     * This method provides an easy way to customize how field values are displayed 
     * in YForm data table lists. It registers a callback that will be called when 
     * the specified field is rendered in the table view.
     * 
     * @param string $table The table name (e.g., 'rex_article', 'my_table')
     * @param string $fieldname The field name to modify (e.g., 'title', 'status')
     * @param callable $callback The callback function to apply. Receives an array with:
     *                          - 'value': current field value
     *                          - 'table': table object
     *                          - 'fieldname': field name
     *                          - 'list': list object
     *                          - 'data_id': record ID
     *                          - 'dataset': dataset object (if available)
     * @return void
     * 
     * @example 
     * // Add edit link to title field
     * yform_field::modifyDatalistOutput('my_table', 'title', function($params) {
     *     return $params['value'] . ' <a href="/edit/' . $params['data_id'] . '">[Edit]</a>';
     * });
     * 
     * @example
     * // Format status field with colored badges
     * yform_field::modifyDatalistOutput('my_table', 'status', function($params) {
     *     $status = $params['value'];
     *     $class = $status == 1 ? 'success' : 'danger';
     *     return '<span class="label label-' . $class . '">' . ($status == 1 ? 'Active' : 'Inactive') . '</span>';
     * });
     */
    public static function modifyDatalistOutput($table, $fieldname, $callback)
    {
        if (!is_string($table) || empty($table)) {
            throw new InvalidArgumentException('Table name must be a non-empty string');
        }
        
        if (!is_string($fieldname) || empty($fieldname)) {
            throw new InvalidArgumentException('Field name must be a non-empty string');
        }
        
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Callback must be callable');
        }

        // Store the callback for later use
        self::$dataListCallbacks[$table][$fieldname] = $callback;
        
        // Register the extension point if not already registered
        if (!self::$extensionRegistered) {
            self::registerDataListExtension();
            self::$extensionRegistered = true;
        }
    }

    /**
     * Register the YFORM_DATA_LIST extension point
     * @return void
     */
    private static function registerDataListExtension()
    {
        rex_extension::register('YFORM_DATA_LIST', static function ($ep) {
            $list = $ep->getSubject();
            $table = $ep->getParam('table');
            
            if (!$table) {
                return;
            }
            
            $tableName = $table->getTableName();
            
            // Check if we have callbacks for this table
            if (!isset(self::$dataListCallbacks[$tableName])) {
                return;
            }
            
            foreach (self::$dataListCallbacks[$tableName] as $fieldname => $callback) {
                // Set custom column format that uses our callback
                $list->setColumnFormat($fieldname, 'custom', [__CLASS__, 'executeCallback'], [
                    'callback' => $callback,
                    'table' => $table,
                    'fieldname' => $fieldname
                ]);
            }
        });
    }

    /**
     * Execute the registered callback for a field
     * 
     * @param array $params Parameters from the list
     * @return string The modified output
     */
    public static function executeCallback($params)
    {
        $callback = $params['params']['callback'];
        $table = $params['params']['table'];
        $fieldname = $params['params']['fieldname'];
        $value = $params['value'];
        $list = $params['list'];
        
        // Prepare callback parameters
        $callbackParams = [
            'value' => $value,
            'table' => $table,
            'fieldname' => $fieldname,
            'list' => $list,
            'data_id' => $list->getValue('id'),
            'dataset' => null
        ];
        
        // Try to get the dataset if possible
        try {
            if ($list->getValue('id')) {
                $callbackParams['dataset'] = rex_yform_manager_dataset::get($list->getValue('id'), $table->getTableName());
            }
        } catch (Exception $e) {
            // If dataset retrieval fails, continue without it
        }
        
        return call_user_func($callback, $callbackParams);
    }

    public static function email_template_test()
    {
        $func = rex_request::request('func', 'string');
        if ('send_yform_email_preview' != $func) {
            return;
        }

        $phpmailer = rex_addon::get('phpmailer');
        $emailTemplate = rex_sql::factory()->getArray('SELECT * FROM rex_yform_email_template WHERE id = :id', [':id' => rex_request::request('id', 'int')]);

        if (!count($emailTemplate) || !$phpmailer->getConfig('test_address', false)) {
            return;
        }

        $etpl = rex_yform_email_template::getTemplate($emailTemplate[0]['name']);
        $etpl['subject'] = htmlspecialchars_decode(sprintf(rex_addon::get('yform_field')->i18n('yform_email_list_send_preview_subject'), $emailTemplate[0]['name']));
        $etpl['mail_to'] = $phpmailer->getConfig('test_address');
        $etpl['mail_to_name'] = 'Test-Empfänger';
        $etpl['mail_from'] = $phpmailer->getConfig('from');
        $etpl['mail_from_name'] = $phpmailer->getConfig('fromname');
        $etpl['mail_reply_to'] = $etpl['mail_reply_to_name'] = '';

        $send = rex_yform_email_template::sendMail($etpl, $emailTemplate[0]['name']);

        if ($send) {
            rex_extension::register('OUTPUT_FILTER', static function (rex_extension_point $ep) use (&$emailTemplate, &$etpl) {
                $suchmuster = '<section class="rex-page-section">';
                $ersetzen = rex_view::success(sprintf(
                    rex_addon::get('yform_field')->i18n('yform_email_list_send_preview_success'),
                    $emailTemplate[0]['name'],
                    $etpl['mail_to'],
                )) . '<section class="rex-page-section">';

                $pos = strpos($ep->getSubject(), $suchmuster);

                if (false !== $pos) {
                    $ep->setSubject(substr_replace($ep->getSubject(), $ersetzen, $pos, strlen($suchmuster)));
                }
            });
        }

        rex_extension::register('REX_LIST_GET', static function (rex_extension_point $ep) {
            $list = $ep->getSubject();
            $list->addColumn('func_send_preview', '<div class="send_yform_email_preview"><i class="rex-icon fa-envelope-o"></i>&nbsp;' . rex_addon::get('yform_field')->i18n('yform_email_list_send_preview') . '</div>', count($list->getColumnNames()));
            $list->setColumnLabel('func_send_preview', '');
            $list->setColumnParams('func_send_preview', ['func' => 'send_yform_email_preview', 'id' => '###id###', 'page' => 'yform/email/index']);
        });
    }
}
