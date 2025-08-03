# 🧩 Zusätzliche Feldtypen, Validierungen und Aktionen für REDAXO 5 YForm 4

Das Addon `yform_field` ergänzt YForm um weitere Feldtypen, Validierungen und Aktionen.

## Features

### Feldtypen

| Feldtyp                | Beschreibung                                                                                     |
|------------------------|--------------------------------------------------------------------------------------------------|
| `be_hidden`            | Hidden-Feld im Backend verfügbar machen                                                          |
| `be_manager_relation_set` | `be_manager_relation` mit Datenbankfeldtyp `SET`                                              |
| `be_media_preview`     | Bildvorschau in der YForm-Datentabelle                                                           |
| `be_user_select`       | Zuweisung von REDAXO-Benutzern                                                                   |
| `choice_html`          | Erlaubt HTML innerhalb des Labels von `choice`                                                   |
| `choice_status`        | Ein `choice`-Feld, das einen Status-Wechsler in der Übersicht anzeigt                            |
| `custom_link`          | Stellt ein Link-Widget für mehrere Link-Typen bereit                                             |
| `datestamp_offset`     | Ein `datestamp`-Feld, mit einem Offset in die Zukunft                                            |
| `datetime_local`       | HTML5-Eingabefeld für Datum und Uhrzeit                                                          |
| `domain`               | Auswahlfeld mit System-Domain und YRewrite-Domains                                               |
| `form_url`             | Erfasst die URL, von der das Formular abgeschickt wurde                                          |
| `number_lat`           | Eingabefeld für einen Breitengrad (-90°...90°)                                                   |
| `number_lng`           | Eingabefeld für einen Längengrad (-180°...180°)                                                  |
| `openai_prompt`        | Erweitert den Inhalt eines Feldes mithilfe der OpenAI API                                        |
| `openai_spellcheck`    | Automatische Rechtschreib- und Grammatikprüfung mithilfe der OpenAI API                          |
| `privacy_policy`       | Checkbox mit Link für Anwendungsfälle wie AGB und Datenschutzerklärung                           |
| `radio`                | Das YForm 3 `radio`-Feld                                                                         |
| `radio_sql`            | Das YForm 3  `radio`-Feld mit SQL-Abfrage                                                        |
| `select`               | Das YForm 3  `select`-Feld                                                                       |
| `select_sql`           | Das YForm 3  `select`-Feld mit SQL-Abfrage                                                       |
| `seo_title`            | Ein `seo_title`-Feld, das Text aus Feldern mit eigenem Text kombiniert - perfekt fürs URL-Addon  |
| `showvalue_extended`   | Ein erweitertes `showvalue`-Feld                                                                 |
| `submit_once`          | Ein `submit`-Feld, das einen Doppelklick verhindert                                              |
| `tabs`                 | Gruppiert Formular-Felder in Tabs                                                                |
| `thumbnailws`          | Ein `thumbnailws`-Feld, das ein Vorschaubild von einer URL via thumbnail.ws-API generiert        |

### Validierungen

| Validierung            | Beschreibung                                     |
|------------------------|--------------------------------------------------|
| `extension_point`      | Extension Point nach erfolgreicher Validierung auslösen |
| `pwned`                | Passwörter gegen "Have I Been Pwned"-API prüfen  |

### Aktionen

| Aktion                | Beschreibung                                         |
|------------------------|-----------------------------------------------------|
| `attach`               | Anhänge an E-Mails hängen                           |
| `attach_signature`     | Signatur aus Signatur-Feldtyp als Anhang hinzufügen |
| `conversion_push`      | Conversion-Tracking für Google Ads                  |
| `history_push`         | URL und Titel in den Browserverlauf einfügen        |
| `to_session`           | Formularwerte in die Session speichern              |

### Patches

`yform_field` repariert Bugs und Funktionen in YForm, wenn Felder oder Add-ons mit `yform_field` darauf basieren. Mithilfe von Patches werden Probleme damit auch vor Releases von YForm gelöst.

#### Patches für YForm < 5

* Erhöhung der Darstellung von Feldern im Table Manager von 30 auf 200
* Klonen von Datensätzen mit UUID-Feldern

## Installation

* Im REDAXO-Backend unter `Installer` abrufen und
* anschließend unter `Hauptmenü` > `AddOns` installieren.

Die gewünschten Feldtypen, Validierungen und Actions stehen automatisch bereit.

## Feldtypen

### `datetime_local` HTML5-Eingabefeld

Stellt ein Eingabefeld für Datum + Uhrzeit zur Verfügung

![image](https://user-images.githubusercontent.com/3855487/209684368-44a136e7-5f75-4d72-b867-3d47eebf796e.png)

### `domain` Auswahlfeld

Stellt ein Select-Feld vom Typ `multiple` zur Verfügung, in dem als Auswahl die System-Domain (bzw. "alle") zur Verfügung steht, oder bei installiertem YRewrite auch alle passenden Domains.

![image](https://user-images.githubusercontent.com/3855487/209684633-7d1c388c-83f2-4363-90a6-fc5665580f1d.png)

### `be_media_preivew` mit Bildvorschau

Erzeugt in der YForm Datentabelle eine Vorschau des aktuell gewählten Bilds

Erlaubtes HTMLps://user-images.githubusercontent.com/3855487/209685003-546ac381-ad23-4d4e-a16d-79fcb855ba3f.png)

### be_manager_relation_set SET als Datenbankfeldtyp

Exakt dasselbe Feld wie `be_manager_relation` nur mit der zusätzlichen Auswahlmöglichkeit des Datenbankfeldtyps `SET`, verwendbar in allen `1:n`-Beziehungen, die direkt im Feldwert gespeichert werden.

> **Tipp:** Ändere in der Datenbanktabelle `yform_field` die Felddefinition deines bestehenden `be_manager_relation`-Felds zu `be_manager_relation_set` und lösche den REDAXO-Cache, statt das Feld zu löschen und neu anzulegen.

### `be_user_select` - REDAXO-Benutzer zuordnen

Ähnlich zu `be_user` mit dem Unterschied, den Backend-Benutzer zuweisen zu können, bspw. für zusätzliche Rechtevergabe oder Verantwortlichkeiten.

### `choice_html` HTML innerhalb des durch Choice erzeugten Labels erlauben

Erlaubt HTML in der Ausgabe des Labels von `choice`, was auch gemäß HTML5 möglich ist, um bspw. ein Bild anstelle oder zusätzlich zur Auswahl zu stellen.

> **Tipp:** Ändere in der Datenbanktabelle `yform_field` die Felddefinition deines bestehenden `choice`-Felds zu `choice_html` und lösche den REDAXO-Cache, statt das Feld zu löschen und neu anzulegen.

### `form_url` - Erfahre, von wo das Formular abgeschickt wurde

Nützlich für statistische Zwecke, wenn ein Formular seitenübergreifend eingebunden wurde und man wissen möchte, von wo es ausgefüllt wurde.

### `privacy_policy` - AGB und Tracking-Einverständnis abfragen

Stellt auf Basis einer regulären Checkbox weitere Eingabe-Informationen zur Verfügung, um bspw. auf AGB oder Datenschutzerklärung hinzuweisen, wie in diesem Beispiel:

![image](https://user-images.githubusercontent.com/3855487/209686556-46de60ad-985f-4c7b-a223-83a1cadee164.png)

Pipe-Schreibweise: `name|label|no_db|attributes|notice|output_values|text|linktext|article_id`

### `tabs` - Formular-Felder in Tabs gruppieren

Ähnlich wie bei Fieldsets können Formulare über Tab-Sets optisch strukturiert werden. Dazu wird das Tab-Value am Anfang einer Feldgruppe eingefügt. Nach der letzten Gruppe muss ein abschließendes Tab-Value gesetzt werden.

Im Formular sind mehrere Tab-Sets möglich, die dann aber eindeutig benannt sein müssen und sich nicht überlappen dürfen.

Es müssen mindestens drei Tab-Values (derselben Gruppe) im Formular sein:

* erster Tab: beginnt einen Tab und baut das Tab-Menü über alle Tabs des Tab-Sets auf.
* innerer Tab: jeder innere Tab schließt den vorhergehenden ab und öffnet den eigenen Container
* letzter Tab: ohne eigenen Eintrag im Tab-Menü, schließt den vorhergehenden Container und die Gruppe

Wenn in einem Tab ein Feld mit Fehlermeldung steckt, wird der Tab optisch markiert
und aktiviert.

Wurde das Formular mit "Übernehmen" gespeichert, wird der zuletzt aktive Tab bei der
Wiederanzeige aktiv gesetzt. Ausnahme: in einem anderen Tab ist ein Feld mit Fehlermeldung.

Ein Formular kann mehrere Tab-Sets enthalten, allerdings nicht geschachtelt. In dem Fall müssen alle zu einem Tab-Set gehörenden Tab-Value denselben Gruppennamen bekommen.

### Actions

#### `attach` - Anhänge an E-Mails hängen

Die Aktion `attach` muss vor der Aktion für den E-Mail-Versand notiert werden - logisch, sonst wird erst die Mail versendet und dann der Anhang beigefügt.

Szenario für Bewerberformulare: Durch geschickte Kombination und Reihenfolge lässt sich zunächst eine Bestätigungs-Mail an eine*n Bewerber*in ohne Anhang versenden, anschließend wird die Action eingetragen und zum Schluss eine weitere Mail-Aktion an das Unternehmen - diese ist dann mit Anhang.

#### `conversion_push` - Conversion-Tracking

Die Aktion `conversion_push` sendet ein Conversion-Tracking-Event an Google Analytics (gtag.js), wenn die Seite mit dem Formular aufgerufen wird.

##### Voraussetzung

Der Google Tag Manager ist initialisiert, bspw. über einen Consent-Manager. Und dieser erstellt einen eigenen EventListener `gtagLoaded`, z. B. auf diese Art und Weise:

```javascript
script = document.createElement('script');
script.src = 'https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX';
script.async = 'async';

window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-XXXXXXXXXX');

// Fügen Sie einen EventListener für das load-Ereignis hinzu
script.addEventListener('load', function() {
    // Erstellen Sie ein neues Event
    var event = new Event('gtagLoaded');

    // Lösen Sie das Event aus
    window.dispatchEvent(event);
});

// Fügen Sie das Skript-Tag zum Dokument hinzu
document.head.appendChild(script);
```

##### Pipe-Schreibweise

```text
// action|conversion_push|google_ads|event:conversion|send_to:AW-XXXXXXXXX/XXXXXXXXXXXXXXXXXXXX|value:1|currency:EUR
```

z.B.: `action|conversion_push|google_ads|conversion|AW-XXXXXXXXX/XXXXXXXXXXXXXXXXXXXX|999|EUR`

##### Standalone-Implementierung

```php
// Conversion nur zählen, wenn kein REDAXO-Benutzer eingeloggt ist
if(rex_backend_login::createUser() == null) {
    echo conversion_push::google_ads('conversion', "AW-XXXXXXXXX/XXXXXXXXXXXXXXXXXXXX", 999, 'EUR');
}
```

### Validierungen

#### `extension_point` - Extension Points nach Validierung auslösen

Die Validierung `extension_point` ermöglicht es, nach erfolgreicher Formular-Validierung Extension Points auszulösen. Dies ist nützlich, um zusätzliche Funktionen wie Dokumentenerstellung, ERP-Integration oder E-Mail-Benachrichtigungen in bestehende YForm-Formulare zu integrieren.

##### Pipe-Schreibweise

```text
validate|extension_point|name|ep_name|label
```

- `name`: Optionaler Name für die Validierung
- `ep_name`: Name des Extension Points (Standard: `YFORM_VALIDATE_EP`)
- `label`: Optionales Label

##### Verwendung im Extension Point Handler

```php
rex_extension::register('MY_CUSTOM_EP', function (rex_extension_point $ep) {
    $form_object = $ep->getParam('form_object');
    $label = $ep->getParam('label');
    $name = $ep->getParam('name');
    $params = $ep->getParam('params');
    
    // Hier können zusätzliche Aktionen durchgeführt werden
    // z.B. Dokumentenerstellung, API-Aufrufe, etc.
});
```

## Einstellungen

Es sind keine weiteren Einstellungen vorhanden.

## Tipps und Tricks

### Weitere Tipps und Tricks

Siehe auch: <https://github.com/alexplusde/yform_field/issues>

## Lizenz

MIT Lizenz, siehe [LICENSE.md](https://github.com/alexplusde/speed_up/blob/master/LICENSE.md)  

## Autoren

**Alexander Walther**  
<https://www.alexplus.de>
<https://github.com/alexplusde>

**Projekt-Lead**  
[Alexander Walther](https://github.com/alxndr-w)

## Credits
