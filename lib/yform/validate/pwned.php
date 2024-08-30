<?php

class rex_yform_validate_pwned extends rex_yform_validate_abstract
{
    public function enterObject()
    {
        $PasswordObject = $this->getValueObject();

        if (!$this->isObject($PasswordObject)) {
            return;
        }

        if ('' == $PasswordObject->getValue()) {
            return;
        }

        $password = $PasswordObject->getValue();
        $hash = strtoupper(sha1($password));
        $range = substr($hash, 0, 5);
        $suffix = substr($hash, 5);

        $url = "https://api.pwnedpasswords.com/range/$range";
        $rex_socket = rex_socket::factoryUrl($url);
        $rex_socket_response = $rex_socket->doGet();

        if (!$rex_socket_response->isOk()) {
            rex_logger::logError(E_WARNING, 'Failed to connect to pwnedpasswords.com', '', 0);
            return;
        }

        $output = $rex_socket_response->getBody();
        $lines = explode("\n", $output);
        foreach ($lines as $line) {
            [$hashSuffix, $count] = explode(':', $line);
            if (trim($hashSuffix) == $suffix) {
                $PasswordObject->setValue('');
                $this->params['warning'][$PasswordObject->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$PasswordObject->getId()] = $this->getElement('message');
                return;
            }
        }
    }

    public function getDescription(): string
    {
        return 'validate|pwned|passwordfieldname|warning_message';
    }

    public function getDefinitions(): array
    {
        return [
            'type' => 'validate',
            'name' => 'pwned',
            'values' => [
                'name' => ['type' => 'select_name', 'label' => rex_i18n::msg('yform_validate_password_compromised_name')],
                'message' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_password_compromised_message')],
            ],
            'description' => rex_i18n::msg('yform_validate_password_compromised_description'),
        ];
    }
}
