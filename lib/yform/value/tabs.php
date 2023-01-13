<?php
/**
 * Tabs in YForm-Formularen.
 *
 * Es müssen mindestens drei Tab-Values derselben Gruppe (group_by) im Formular sein:
 *  erster Tab      -> beginnt einen Tab und baut das Tab-Menü über alle auf
 *  innerer Tab     -> jeder innere Tab schließt den vorhergehenden ab und öffnet den eigenen Container
 *  letzter Tab     -> ohne eigenen Eintrag im Tab-Menü, schließt den vorhergehenden Container und die Gruppe
 *
 * nur ein Tab-Value im Formular:   es fehlt der schließende Tab. Technisch nicht machbar.
 * nur zwei Tab-Values im Formular: es gibt eh nur einen Tab im Menü. Das ist sinnlos.
 *
 * Nutzt in rex_yform_field die Felder ...
 *  - group-by: Clusterung als Tab-Gruppe
 *  - default:  Beim Anzeigen ausgewählter Default-Tab (1= Der erste / 2 = der ausgewählte)
 */

class rex_yform_value_tabs extends rex_yform_value_abstract
{
    /**
     * Variablen zur Ablage der Tab-Menü-Struktur
     * @var self[] $tabset
     */
    public array $tabset = [];
    public int $sequence;
    public bool $selected = false;
    public string $hasErrorField = '';

    public string $fragment = 'value.tabs.tpl.php';

    /**
     * sucht die Elemente der eigenen Tabgruppe (group_by) zusammen und markiert
     * alle Elemnte konsolidiert.
     * Wird nur beim ersten Element der Tabgruppe ausgeführt für alle.
     */
    protected function collectTabElements(): void
    {
        // nur ausführen, wenn noch nicht durch ein anderes Tab derselben Gruppe erledigt
        if (0 < count($this->tabset)) {
            return;
        }

        // Alle Tab-Elemente derselben Gruppe ermitteln
        /** @var \rex_yform_value_abstract[] $tabElements  */
        $tabElements = $this->params['values'];
        /** @var self[] $tabElements  */
        $tabElements = array_filter($tabElements, function ($v) {
            return is_a($v, self::class) && $v->getElement('group_by') === $this->getElement('group_by');
        });

        // Zu wenig Elemente: dann wird das nix, ignorieren
        if (3 > count($tabElements)) {
            return;
        }

        // Der letzte Tab (nur Platzhalter für den Abschluss der Tabgruppe) ist nie aktiv.
        $tabElements[array_key_last($tabElements)]->setElement('default', '1');

        // In den Tabs die steuernden Informationen eintragen
        $i = 0;
        $active = -1;
        foreach ($tabElements as $id => $tab) {
            $tab->tabset = $tabElements;
            $tab->sequence = $i++;
            $tab->selected = false;
            if (-1 === $active && '2' === $tab->getElement('default')) {
                $active = $id;
            };
        }
        $active = -1 === $active ? array_key_first($tabElements) : $active;
        $tabElements[$active]->selected = true;
        // Das letzte Element erhält die Nummer PHP_INT_MAX;
        $tabElements[array_key_last($tabElements)]->sequence = PHP_INT_MAX;

        // Gibt es Fehlermeldungen in einem Tab-Bereich? Dann den Tab markieren
        $tabKeys = array_keys($tabElements);
        foreach ($this->params['warning'] as $needle => $errorClass) {
            $fields = array_filter($tabKeys, static function ($v) use ($needle) {
                return $v < $needle;
            });
            $errorTab = end($fields);
            if (false !== $errorTab) {
                $tabElements[$errorTab]->hasErrorField = $errorClass;
            }
        }
    }

    /**
     * Zu diesem Zeitpunkt sind alle Felder existent.
     *
     * @return void
     */
    public function enterObject()
    {
        if (!$this->needsOutput()) {
            return;
        }
        $this->collectTabElements();
        $output = '';

        // Wenn erstes Tab der Gruppe: Menü aufbauen, Tab-Container öffnen
        if (0 === $this->sequence) {
            $output .= $this->parse($this->fragment, ['option' => 'open_tabset', 'tabset' => $this->tabset]);
            $startFeld = true;
        }

        // Wenn nicht Startfeld: vorhergehende Tab-Gruppe schließen
        if (0 < $this->sequence) {
            $output .= $this->parse($this->fragment, ['option' => 'close_tab']);
        }

        // Wenn nicht letzter Eintrag: tab-Gruppe öffnen; der letzte dient ja nur dem Abschluß
        if (PHP_INT_MAX > $this->sequence) {
            $output .= $this->parse($this->fragment, ['option' => 'open_tab']);
        }

        // Wenn letzter Eintrag: Tab-Container schließen
        if (PHP_INT_MAX === $this->sequence) {
            $output .= $this->parse($this->fragment, ['option' => 'close_tabset']);
        }

        $this->params['form_output'][$this->getId()] = $output;
    }

    public function getDescription(): string
    {
        return 'tabs|name|label|aktiv[1,2]|[tabgroup]';
    }

    /**
     * @return array<string,mixed>
     */
    public function getDefinitions(): array
    {
        return [
            'type' => 'value',
            'name' => 'tabs',
            'values' => [
                'name' => [
                    'type' => 'name',
                    'label' => rex_i18n::msg('yform_values_defaults_name'),
                ],
                'label' => [
                    'type' => 'text',
                    'label' => rex_i18n::msg('yform_values_defaults_label'),
                ],
                'default' => [
                    'type' => 'choice',
                    'label' => rex_i18n::msg('yform_values_tabs_active_label'),
                    'choices' => rex_i18n::rawMsg('yform_values_tabs_active_options'),
                    'expanded' => '1',
                    'default' => '1',
                    'notice' => rex_i18n::msg('yform_values_tabs_active_notice'),
                ],
                'group_by' => [
                    'type' => 'text',
                    'label' => rex_i18n::msg('yform_values_tabs_cluster_label'),
                    'notice' => rex_i18n::msg('yform_values_tabs_cluster'),
                ],
            ],
            'description' => rex_i18n::msg('yform_values_tabs_description'),
            'dbtype' => 'none',
            'is_searchable' => false,
            'is_hiddeninlist' => true,
        ];
    }
}
