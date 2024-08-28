<?php

class rex_yform_action_attach_signature extends rex_yform_action_abstract
{
    public function executeAction(): void
    {
        $fieldname = $this->getElement(2) ?? '';
        $filenname = $this->getElement(3) ?? 'signature.png';

        if ('' == $fieldname) {
            return;
        }

        $signatureValue = $this->params['value_pool']['email'][$fieldname];
        $base64String = str_replace('data:image/png;base64,', '', $signatureValue);
        $imageData = base64_decode(str_replace(' ', '+', $base64String));

        try {
            rex_file::put(rex_path::cache($filenname), $imageData);
            if (file_exists(rex_path::cache($filenname))) {
                $this->params['value_pool']['email_attachments']['signature_attach'] = [0 => $filenname, 1 => rex_path::cache($filenname)];
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        rex_file::delete(rex_path::cache($filenname));
    }

    public function getDescription(): string
    {
        return 'action|attach_signature|fieldname|[filename]';
    }
}
