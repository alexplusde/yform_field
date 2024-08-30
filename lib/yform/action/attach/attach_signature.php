<?php

class rex_yform_action_attach_signature extends rex_yform_action_abstract
{
    public function executeAction(): void
    {
        $fieldName = $this->getElement(2) ?: '';
        if ('' == $fieldName) {
            return;
        }
        $fileName = $this->getElement(3) ?: $fieldName . '.png';

        $signatureValue = $this->params['value_pool']['email'][$fieldName];
        $base64String = str_replace('data:image/png;base64,', '', $signatureValue);
        $imageData = base64_decode(str_replace(' ', '+', $base64String));

        try {
            rex_file::put(rex_path::cache($fileName), $imageData);
            if (file_exists(rex_path::cache($fileName))) {
                $this->params['value_pool']['email_attachments'][] = [0 => $fileName, 1 => rex_path::cache($fileName)];
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function getDescription(): string
    {
        return 'action|signature_attach|fieldname|[filename]';
    }
}
