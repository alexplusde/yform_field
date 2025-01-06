<?php

class rex_yform_value_openai_spellcheck extends rex_yform_value_abstract
{
    private $systemMessage = 'Bitte überprüfe den folgenden Text auf Rechtschreibung und Grammatikfehler. Korrigiere den Text. Achte darauf, dass das Format des Textes (HTML, Markdown oder reiner Text) beibehalten wird. Wenn der Text in HTML ist, gib korrekt formatiertes HTML zurück. Wenn der Text in Markdown ist, gib korrekt formatiertes Markdown zurück. Wenn der Text weder HTML noch Markdown ist, gib korrekt formatierten Plaintext zurück. Fehler sind auch falsche Zeilenumbrüche mitten im Satz, falsche Sonderzeichen oder Trennzeichen mitten im Wort und falsche Formatierung von Listen. Formatiere Listen in eigenen Absätzen beziehungsweise Listenpunkten. Auf keinen Fall darf der Text inhaltlich verändert werden. Deine Antwort darf nur den Text enthalten, der korrigiert wurde. Wenn du keine Fehler findest, gib den Text dennoch vollständig und unverändert zurück.';
    private $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function enterObject()
    {
        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.textarea.tpl.php');
        }
    }

    public function postFormAction(): void
    {
        $apiKey = $this->getElement(3);
        $field = $this->getElement(4);

        if (0 == $this->getParam('send')) {
            return;
        }

        if (!isset($this->params['value_pool']['sql'][$field])) {
            return;
        }

        $fieldValue = $this->params['value_pool']['sql'][$field];

        $responseText = self::getChatGPTResponse($fieldValue, $apiKey, $this->systemMessage, $this->apiUrl);
        $this->params['value_pool']['sql'][$field] = $responseText;
    }

    public static function getChatGPTResponse($fieldValue, $apiKey, $systemMessage, $apiUrl)
    {
        $userMessage = "Text: \"$fieldValue\"";

        $data = [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
                [
                    'role' => 'system',
                    'content' => $systemMessage,
                ],
            ],
            'max_tokens' => 4444,
            'temperature' => 0.3,
            'top_p' => 0.95,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
        ];

        $content = json_encode($data, JSON_UNESCAPED_UNICODE);

        try {
            $socket = rex_socket::factoryUrl($apiUrl);
            $socket->addHeader('Content-Type', 'application/json; charset=UTF-8');
            $socket->addHeader('Authorization', 'Bearer ' . $apiKey);

            $response = $socket->doPost($content);

            if (!$response->isOk()) {
                dump($response, 'Fehler bei der API-Anfrage.');
            }

            $result = $response->getBody();
            $response = json_decode($result, true);

            if (isset($response['choices'][0]['message']['content'])) {
                return $response['choices'][0]['message']['content'];
            }
            dump('Fehler: Keine gültige Antwort von der API erhalten.', $response);
            return '';
        } catch (rex_socket_exception $e) {
            dump('Fehler bei der API-Anfrage: ' . $e->getMessage());
            return '';
        }
    }

    public function getDescription(): string
    {
        return 'value|openai_spellcheck|name|label|api_key|field';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'openai_spellcheck',
            'values' => [
                'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name') ?? ''],
                'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label') ?? ''],
                'api_key' => ['type' => 'text', 'label' => 'OpenAI API Key'],
                'field' => ['type' => 'text', 'label' => 'Datenbank-Tabellenfeld'],
            ],
            'description' => 'Automatische Rechtschreib- und Grammatikprüfung mithilfe der OpenAI API.',
            'db_type' => ['text', 'mediumtext'],
        ];
    }
}
