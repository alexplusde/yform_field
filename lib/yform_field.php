<?php

class yform_field
{
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
