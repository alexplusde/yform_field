<?php

class rex_yform_action_attach extends rex_yform_action_abstract
{
    public function executeAction(): void
    {
        $file = array_filter(explode(',', $this->getElement(2))); // specific fields
        $override = (bool) $this->getElement(3) ?? false; // will replace attached files
        $email_attachments = &$this->params['value_pool']['email_attachments']; // attached files

        $attachment = ['filename' => $file[0], 'path' => rex_path::addonData($file[1])];

        // Prevent path traversal
        $realPath = realpath($attachment['path']);
        $allowedPath = realpath(rex_path::addonData(''));

        if ($realPath && str_starts_with($realPath, $allowedPath) && file_exists($realPath)) {
            $attachment['path'] = $realPath;
            if ($override) {
                $email_attachments = [];
            }
            $email_attachments[] = $attachment;
        }
    }

    public function getDescription(): string
    {
        return 'action|attach_file|file(attachment_filename.ext,path/to/file.ext)|opt:replace(0=default/1)';
    }
}
