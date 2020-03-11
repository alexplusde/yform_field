<?php
/**
 *  This file is part of the REDAXO-AddOn "yform_fields".
 *
 *  @author      FriendsOfREDAXO @ GitHub <https://github.com/FriendsOfREDAXO/focuspoint>
 *  @version     0.1
 *  @copyright   FriendsOfREDAXO <https://friendsofredaxo.github.io/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  ------------------------------------------------------------------------------------------------
 *
 *  Eingebefeld für URLS oder Mailadressen mit Buuton zum Öffnen in einem neuen Fenster.
 *
 *  Basis ist das Feld des Typs "rex_yform_value_text". Es werden keine neuen Felder erzeugt.
 *  in "prepend" wird statt eines Vorlauftextes daas Ergebnis der Auswahl Mail/Link gespeichert.
 *  Analog wird "default" verändert.
 *
 *  Der Feldtyp wird von "text" auf "email" bzw. "url" geändert, um die HTML5-Validierug zu nutzen
 */

class rex_yform_value_for_extern extends rex_yform_value_text
{
    public function parse($template, $params = [])
    {
        extract($params);
        if( $this->getElement('icon') == 2 ) {
            $icon_class = 'envelope';
            $type = 'email';
            $onclick = 'forjs.openmail(\''.$this->getFieldId().'\',\''.rex_i18n::msg('for_extern_value_invalid_mail').'\')';
            $title = rex_i18n::msg('for_extern_value_title_mail');
        } else  {
            $icon_class = 'external-link';
            $type = 'url';
            $onclick = 'forjs.openlink(\''.$this->getFieldId().'\',\''.rex_i18n::msg('for_extern_value_invalid_link').'\')';
            $title = rex_i18n::msg('for_extern_value_title_link');
        }
        $prepend = '<i onclick="'.$onclick.'" class="rex-icon rex-icon-'.$icon_class.'" title="'.$title.'" style="cursor: pointer;"></i>';
        ob_start();
        include $this->params['this']->getTemplatePath($template);
        return ob_get_clean();
    }

    /**
     *  Erzeugt den Auswahl-String für "Mail/Link"
     *
     *  Aufbau:
     *      for_link|feld1|feld2|...
     *
     *  Der Inhalt wird aus dem Namen des feldtyps sowie aus den Namen der Values in der
     *  Feldbeschreibung automatisch gebildet. So werden alle Felder inkl. der aus der Basisklasse
     *  (text) eingebaut. Die Beschreibungen fpr "default" und "type" werden zudem aum die
     *  Auswahlmöglichkeiten erweitert.
     *
     *  @return     string   Auswahloptionen
     */
    public function getOptionsString() {
        return rex_i18n::msg('for_extern_value_option_link').'=1,'.rex_i18n::msg('for_extern_value_option_mail').'=2';
    }

    /**
     *  Erzeugt den Description-String für diesen Feldtyp (Kurzform)
     *
     *  Aufbau:
     *      for_extern|name|label|...
     *
     *  Der Inhalt wird aus dem Namen des feldtyps sowie aus den Namen der Values in der
     *  Feldbeschreibung automatisch gebildet. So werden alle Felder inkl. der aus der Basisklasse
     *  (text) eingebaut. Die Beschreibungen fpr "default" und "type" werden zudem aum die
     *  Auswahlmöglichkeiten erweitert.
     *
     *  @return     string   Short-Description
     */
    public function getDescription()
    {
        $definitions = $this->getDefinitions();
        $description = array_keys( $definitions['values'] );
        array_unshift( $description, $definitions['name'] );
        $info = ' (' . $this->getOptionsString() . ')';
        $item = array_search ( 'default',$description );
        $description[$item] .= $info;
        $item = array_search ( 'prepend',$description );
        $description[$item] .= $info;
        return implode( '|',$description );
    }

    /**
     *  Erzeugt den Description-String für diesen Feldtyp (Kurzform)
     *
     *  Basis sind die Definitionen der Basisklasse (text).
     *  Prepend wird umdefiniert auf ein Auswahlfeld "Mail/Link"
     *  Der Default-Wert wird umdefiniert auf ein Auswahlfeld "Mail/Link"
     *
     *  @return     array   Definitionen für den Feldtyp
     */
    public function getDefinitions()
    {
        $definitions = parent::getDefinitions();
        $definitions['name'] = 'for_extern';
        $definitions['description'] = rex_i18n::msg('for_extern_value_description');
        $definitions['famous'] = false;
        $definitions['values']['default'] = ['label' => $definitions['values']['default']['label'], 'type'=>'choice','choices'=>$this->getOptionsString(), 'expanded'=>1, 'multiple'=>0, 'default'=>1];
        $definitions['values']['prepend'] = ['label' => rex_i18n::msg('for_extern_value_label'), 'type'=>'choice','choices'=>$this->getOptionsString(), 'expanded'=>1, 'multiple'=>0, 'default'=>1];
        return $definitions;
    }
}
