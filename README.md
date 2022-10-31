# Feldtypen-Erweiterung für REDAXO 5 YForm 4

Das Addon `yform_field` ergänzt YForm um weitere Feldtypen, Validierungen und Actions.

## Features

* **E-Mail Attachments** - nur eine Zeile, um Anhänge aus Formularen an E-Mails zu hängen
* **Echtes Datetime-Value** - optimierte HTML5-Ausgabe mit optionaler Einschränkung per min/max-Auswahl
* **`be_media` mit Bildvorschau** - zeigt statt der Dateinamen die gewählten Bilder als Vorschau
* **`be_manager_relation` als SET** - erweitert be_manager_relation um die Möglichkeit, ein Feld als echtes DB-Feld `SET` anzulegen
* **`domain` - SELECT-Auswahl mit der System-Domain und allen YRewrite-Domains (sofern installiert)

## Installation

* Im REDAXO-Backend unter `Installer` abrufen und
* anschließend unter `Hauptmenü` > `AddOns` installieren.

Die gewünschten Feldtypen, Validierungen und Actions stehen automatisch bereit.

## Einstellungen

Es sind keine weiteren Einstellungen vorhanden.

## Tipps und Tricks

### `attach`-Action

Die Aktion `attach` muss vor der Aktion für den E-Mail-Versand notiert werden - logisch, sonst wird erst die Mail versendet und dann der Anhang beigefügt. 

Szenario für Bewerberformulare: Durch geschickte Kombination und Reihenfolge lässt sich zunächst eine Bestätigungs-Mail an eine*n Bewerber*in ohne Anhang versenden, anschließend wird die Action eingetragen und zum Schluss eine weitere Mail-Aktion an das Unternehmen - diese ist dann mit Anhang.

### Weitere Tipps und Tricks 

Siehe auch: https://github.com/alexplusde/yform_field/issues



## Lizenz

MIT Lizenz, siehe [LICENSE.md](https://github.com/alexplusde/speed_up/blob/master/LICENSE.md)  

## Autoren

**Alexander Walther**  
https://www.alexplus.de
https://github.com/alexplusde

**Projekt-Lead**  
[Alexander Walther](https://github.com/alxndr-w)

## Credits

