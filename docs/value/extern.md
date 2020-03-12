# for_extern: Externe Links und Mailadressen
- [Verwendung](#values-extern-usage)
- [Konfiguration (Table-Manager)](#values-extern-conf-tm)
- [Konfiguration (PHP/Pipe)](#values-extern-conf-pp)
- [Technische Hinweise](#values-extern-th)

<a name="values-extern-usage"></a>
## Verwendung
In diesem Feldtyp können  können URLS und Mailadressen erfasst werden. Die Eingaben werden bereits HTML-seitig über die HTML5-Validierung als `text="url"` bzw. `text="email"` validiert. Über einen Button links der Eingabe kann die URL bzw. die Mail-Adresse in einem neuen Fenster geöffnet werden.

![Formularbeispiel](extern01.png)

Falls das Eingabefeld leer ist oder keine formal gültige Eingabe beinhaltet, wird eine Fehlermeldung eingeblendet.

<a name="values-extern-conf-tm"></a>
## Konfiguration (Table-Manager)
|Element|Verwendung|Beispiel|
|-|-|-|
|name|Feldname in der Tabelle|verteiler|
|label|Label im Formular|Bezeichnung|
|default|Bestimmt den Vorgabewert|_«hier leer»_|
|no_db|Wenn gesetzt wird das Feld nicht gespeichert|_«hier leer»_|
|attributes|Zusätzliche HTML-Attribute für das Eingabefeld|{"multiple":"multiple"}|
|notice|Eingabehinweis|Eine oder mehrere eMail-Adressen, komma-separiert|
|Externer Link=1,Mail=2|Zur Auswahl stehen 1 für "URL" und 2 für "eMail"|2|
|append|Text hinter dem Eingabefeld |_«hier leer»_|

![Feld anlegen im Table-Manager](extern02.png)

<a name="values-extern-conf-pp"></a>
## Konfiguration (PHP/Pipe)
|Typ|Inhalt|
|-|-|
|PHP|`yform->setValueField( 'for_extern', {'«name»', '«label»', '«default»' ,«no_db», '«attributes»', '«notice»', «Externer Link=1,Mail=2», '«append»'} )`|
|&nbsp;|`$yform->setValueField('for_extern', array('mail','Mail-Kontakt','','0','{"multiple":"multiple"}','Eine oder mehrere eMail-Adressen, komma-separiert','2',''));`|
|Pipe|`for_extern\|name\|label\|[default]\|[no_db]\|[attributes]\|[notice]\|Externer Link=1,Mail=2\|[append]`|
|&nbsp;|`for_extern\|verteiler\|Mailverteiler\|\|\|{"multiple":"multiple"}\|Eine oder mehrere eMail-Adressen, komma-separiert\|2\|`|

<a name="values-extern-th"></a>
## Technische Hinweise
**for_extern** ist eine spezialisierte Variante des Feldtyps **text** aus der Klasse `rex_yform_value_text`.
