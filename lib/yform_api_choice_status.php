<?php

class rex_api_choice_status extends rex_api_function
{
    protected $published = true;

    public function execute()
    {
        if (!rex::isBackend() || !rex_backend_login::hasSession()) {
            exit;
        }

        $token = rex_request('token', 'string', '');
        $table = rex_request('table', 'string', '');
        $data_id = rex_request('data_id', 'int', 0);
        $field = rex_request('field', 'string', '');
        $value = rex_request('value', 'string');
        $secret = rex_config::get('yform_field', 'choice_status_secret');

        $expectedToken = hash_hmac('sha256', $data_id . $table, $secret);
        $check = hash_equals($expectedToken, $token);

        rex_response::cleanOutputBuffers();

        if ($data_id && $table && $token && $field && $check) {
            $dataset = rex_yform_manager_dataset::get($data_id, $table);

            if ($dataset) {
                $dataset->setValue($field, $value);
                if (!$dataset->save()) {
                    rex_logger::factory()->log('Error', 'error: API Call: Status not saved');
                    rex_response::setStatus(rex_response::HTTP_BAD_REQUEST);
                } else {
                    rex_response::setStatus(rex_response::HTTP_OK);
                }
            }
        } else {
            rex_logger::factory()->log('Error', 'error: API Parameter not correct: token:' . $token);
            rex_response::setStatus(rex_response::HTTP_BAD_REQUEST);
        }
        exit;
    }
}
