<?php

class rex_yform_compromised extends rex_yform_validate_abstract
{
    public function enterObject()
    {
        $Object = $this->getValueObject($this->getElement('name'));

        if (!$this->isObject($Object)) {
            return;
        }

        if ('' == $Object->getValue()) {
            return;
        }

        $partitalSHA256 = substr(hash('sha256', $Object->getValue()), 0, 10);

        $url = 'https://api.enzoic.com/v1/passwords';
        $rex_socket = rex_socket::factoryUrl($url);
        $rex_socket->addBasicAuthorization(rex_config::get('yform_field', 'compromised_api_key'), rex_config::get('yform_field', 'compromised_api_secret'));
        $rex_socket_response = $rex_socket->doPost(['partialSHA256' => $partitalSHA256]);

        if (!$rex_socket_response->isOk()) {
            $body = $rex_socket_response->getBody();
            $candidates = json_decode($body, true);
            if (!$candidates) {
                return;
            }

            if (isset($candidates['candidates'])) {
                foreach ($candidates['candidates'] as $candidate) {
                    if ($candidate['sha256'] == hash('sha256', $Object->getValue())) {
                        $Object->setValue('');
                        $this->params['warning'][$Object->getId()] = $this->params['error_class'];
                        $this->params['warning_messages'][$Object->getId()] = $this->getElement('message');
                        return;
                    }
                }
            }
        }
    }

    public function getDescription(): string
    {
        return 'validate|password_compromised|passwordfieldname|warning_message';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'validate',
            'name' => 'password_compromised',
            'values' => [
                'name' => ['type' => 'select_name', 'label' => rex_i18n::msg('yform_validate_password_compromised_name')],
                'message' => ['type' => 'text',        'label' => rex_i18n::msg('yform_validate_password_compromised_message')],
            ],
            'description' => rex_i18n::msg('yform_validate_password_compromised_description'),
        ];
    }
}
