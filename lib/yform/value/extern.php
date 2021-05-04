<?php
/**
*  This file is part of the REDAXO-AddOn "yform_fields".
*
*  @author      FriendsOfREDAXO @ GitHub <https://github.com/FriendsOfREDAXO/focuspoint>
*	            Christoph Böcker
*  @version     0.1
*  @copyright   FriendsOfREDAXO <https://friendsofredaxo.github.io/>
*
*  For the full copyright and license information, please view the LICENSE
*  file that was distributed with this source code.
*
*  ------------------------------------------------------------------------------------------------
*
*  Eingabefeld für URLs oder Mailadressen mit Button zum Öffnen in einem neuen Fenster.
*
*  Basis ist das Feld des Typs "rex_yform_value_text".
*	Es werden keine neuen Spalten in rex_yform_field erzeugt. In "prepend" wird statt eines
*	Vorlauftextes das Ergebnis der Auswahl URL/Mail gespeichert.
*
*  Der type-Tag wird von "text" auf "email" bzw. "url" gesetzt, um die HTML5-Validierug zu nutzen
*
*	Abhängigkeiten:
*		for.js: es werden JS-Funktionen benötigt
*					forjs.openlink()
*					forjs.openmail()
*/

class rex_yform_value_for_extern extends rex_yform_value_text
{

   /**
    *  Erzeugt das Feld-HTML für Formulare
    *
    *  Die eigentliche Arbeit übernimmt das aktuelle Feld-Template der Eltern-Instanz (text)
	*	Der Input-Type wird auf "url" bzw. "email" geändert.
	*	Als "prepend" vor dem Input wird der neue URL/Mail-Button inkl. onclick eingesetzt.
    *
    *  @param      string   Der Template-Name
    *  @param      array    Die Template-Variablen
    *  @return     string   Feld-HTML
    */
   public function parse(string $template, array $params = [])
   {
       if( ( $params['prepend'] ?? 1 ) == 2 ) {
           $params['type'] = 'email';
           $icon_class = 'envelope';
           $onclick = 'forjs.openmail(\''.$this->getFieldId().'\',\''.rex_i18n::msg('for_extern_value_invalid_mail').'\')';
           $title = rex_i18n::msg('for_extern_value_title_mail');
       } else  {
           $params['type'] = 'url';
           $icon_class = 'external-link';
           $onclick = 'forjs.openlink(\''.$this->getFieldId().'\',\''.rex_i18n::msg('for_extern_value_invalid_link').'\')';
           $title = rex_i18n::msg('for_extern_value_title_link');
       }
       $params['prepend'] = '<i onclick="'.$onclick.'" class="rex-icon rex-icon-'.$icon_class.'" title="'.$title.'" style="cursor: pointer;"></i>';
       return parent::parse( $template, $params );
   }

   /**
    *  Erzeugt den Auswahl-String für "Mail/Link"
    *
    *  Aufbau:
    *      URL=1,Mail=2
    *
    *  @return     string   Auswahloptionen
    */
   private function getOptionsString() {
       return rex_i18n::msg('for_extern_value_option_link').'=1,'.rex_i18n::msg('for_extern_value_option_mail').'=2';
   }

   /**
    *  Erzeugt den Description-String für diesen Feldtyp (Kurzform)
    *
    *  @return     string   Short-Description
    */
   public function getDescription()
   {
       return 'for_extern|name|label|[default]|[no_db]|[attributes]|[notice]|'.$this->getOptionsString().'|[append]';
   }

   /**
    *  Feldfefinitionen für das Konfigurations-Formular
    *
    *  Basis sind die Definitionen der Basisklasse (text).
    *  Prepend wird umdefiniert auf ein Auswahlfeld "URL/Mail"
    *
    *  @return     array   Feldfefinitionen
    */
   public function getDefinitions()
   {
       $definitions = parent::getDefinitions();
       $definitions['name'] = 'for_extern';
       $definitions['description'] = rex_i18n::msg('for_extern_value_description');
       $definitions['famous'] = false;
       $definitions['values']['prepend'] = ['label' => rex_i18n::msg('for_extern_value_label'), 'type'=>'choice','choices'=>$this->getOptionsString(), 'expanded'=>1, 'multiple'=>0, 'default'=>1];
       return $definitions;
   }
}
