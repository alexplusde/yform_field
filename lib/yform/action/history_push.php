<?php

class rex_yform_action_history_push extends rex_yform_action_abstract
{
    public function executeAction(): void
    {
        $fragment = new rex_fragment();
        $fragment->setVar('nonce', rex_response::getNonce());
        $fragment->setVar('target_url', $this->getElement(2) ?? rex_getUrl(rex_article::getCurrentId(), rex_clang::getCurrentId(), ['page' => rex_request('page')]));
        $fragment->setVar('target_title', $this->getElement(3) ?? rex::getServerName());

        $this->params['output'] .= $fragment->parse('history_push.php');
    }

    public function getDescription(): string
    {
        return 'action|history_push|target_url|target_title';
    }
}
