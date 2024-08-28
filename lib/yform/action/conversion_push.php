<?php

class conversion_push extends rex_yform_action_abstract
{
    public function postAction(): void
    {
        if ('google_ads' == $this->getElement(2)) {
            $event = $this->getElement(3);
            $send_to = $this->getElement(4);
            $value = $this->getElement(5);
            $currency = $this->getElement(6);

            self::google_ads($event, $send_to, $value, $currency);
        }
    }

    public static function google_ads(string $event = 'conversion', string $send_to = '', int $value = 0, string $currency = 'EUR')
    {
        $project = rex_addon::get('project');

        /* Neues rex_fragment anlegen und Variablen setzen */
        $fragment = new rex_fragment();
        $fragment->setVar('event', $event, false);
        $fragment->setVar('send_to', $send_to, false);
        $fragment->setVar('value', $value, false);
        $fragment->setVar('currency', $currency, false);

        /* Fragment parsen und in den Output schreiben */
        $gajs = $fragment->parse('conversion_push_ga.php');

        $project->setProperty('js', $project->getProperty('js') . $gajs);
        return $gajs;
    }

    public function getDescription(): string
    {
        return 'action|conversion_push|google_ads|event:conversion|send_to:AW-XXXXXXXXX/XXXXXXXXX-XXXXXXXXXX|value:1|currency:EUR';
    }
}
