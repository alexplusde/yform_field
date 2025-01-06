<?php

class rex_yform_value_openai_prompt extends rex_yform_value_abstract
{
    public function enterObject()
    {
        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.textarea.tpl.php');
        }
    }

    public function postFormAction(): void
    {
        $apiKey = $this->getElement(3);
        $fields = $this->getElement(4);
        $targetField = $this->getElement(5);
        $overwrite = (bool) $this->getElement(6);
        $systemMessage = $this->getElement(7);

        $model = $this->getElement(8) ?? 'gpt-4o-mini';
        $maxTokens = $this->getElement(9) ?? 4000;
        $temperature = $this->getElement(10) ?? 0.5;
        $topP = $this->getElement(11) ?? 1.0;
        $frequencyPenalty = $this->getElement(12) ?? 0.0;
        $presencePenalty = $this->getElement(13) ?? 0.0;

        if (!isset($this->params['value_pool']['sql'][$targetField])) {
            dump('Zielfeld ' . $targetField . ' nicht gefunden');
            return;
        }

        if (!$overwrite && isset($this->params['value_pool']['sql'][$targetField]) && !empty($this->params['value_pool']['sql'][$targetField])) {
            dump('Zielfeld ' . $targetField . ' bereits befüllt');
            return;
        }

        $fieldValues = [];
        foreach (explode(',', $fields) as $field) {
            if (isset($this->params['value_pool']['email'][$field])) {
                $fieldValues[$field] = $this->params['value_pool']['email'][$field];
            }
        }

        $responseText = self::getChatGPTResponse($fieldValues, $apiKey, $systemMessage, $model, $maxTokens, $temperature, $topP, $frequencyPenalty, $presencePenalty);
        $this->params['value_pool']['sql'][$targetField] = $responseText;
        $this->params['value_pool']['email'][$targetField] = $responseText;
    }

    public static function getChatGPTResponse($fieldValues, $apiKey, $systemMessage, $model, $maxTokens, $temperature, $topP, $frequencyPenalty, $presencePenalty)
    {
        $apiUrl = 'https://api.openai.com/v1/chat/completions';

        $userMessage = '';
        foreach ($fieldValues as $value) {
            $userMessage .= "$value\n";
        }

        $data = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemMessage,
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ],
            'max_tokens' => (int) $maxTokens,
            'temperature' => (float) $temperature,
            'top_p' => (float) $topP,
            'frequency_penalty' => (float) $frequencyPenalty,
            'presence_penalty' => (float) $presencePenalty,
        ];

        $content = json_encode($data, JSON_UNESCAPED_UNICODE);

        try {
            $socket = rex_socket::factoryUrl($apiUrl);
            $socket->addHeader('Content-Type', 'application/json; charset=UTF-8');
            $socket->addHeader('Authorization', 'Bearer ' . $apiKey);
            $socket->addHeader('OpenAI-Organization', '');
            $socket->addHeader('OpenAI-Project', '');

            $response = $socket->doPost($content);

            if (!$response->isOk()) {
                dump($content, $socket, $response->getHeader());
                return false;
            }

            $result = $response->getBody();
            $response = json_decode($result, true);

            return $response['choices'][0]['message']['content'];
        } catch (rex_socket_exception $e) {
            dump('Fehler bei der API-Anfrage: ' . $e->getMessage());
            return '';
        }
    }

    public function getDescription(): string
    {
        return 'value|openai_prompt|name|label|api_key|fields|target_field|overwrite|prompt_text|system_message|user_message_template';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'openai_prompt',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name') ?? ''],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label') ?? ''],
                'api_key' => ['type' => 'text', 'label' => 'OpenAI API Key'],
                'fields' => ['type' => 'text', 'label' => 'Datenbank-Tabellenfelder'],
                'target_field' => ['type' => 'text', 'label' => 'Zielfeld'],
                'overwrite' => ['type' => 'checkbox', 'label' => 'Überschreiben, wenn Zielfeld bereits befüllt ist'],
                'system_message' => ['type' => 'textarea', 'label' => 'Systemnachricht'],
                'model' => ['type' => 'text', 'label' => 'Modell'],
                'max_tokens' => ['type' => 'text', 'label' => 'Maximale Tokenanzahl'],
                'temperature' => ['type' => 'text', 'label' => 'Temperatur'],
                'top_p' => ['type' => 'text', 'label' => 'Top P'],
                'frequency_penalty' => ['type' => 'text', 'label' => 'Frequenzstrafe'],
                'presence_penalty' => ['type' => 'text', 'label' => 'Präsenzstrafe'],
            ],
            'description' => 'Erweitert den Inhalt eines Feldes mithilfe der OpenAI API.',
            'db_type' => ['text', 'mediumtext'],
        ];
    }
}
