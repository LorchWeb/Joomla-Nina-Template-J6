# Nina: Migration zu Joomla 6

## Ziel

Das Joomla51-Template Nina soll nativ unter Joomla 6 funktionieren, ohne dass das Plugin **Behaviour – Backward Compatibility 6** aktiviert sein muss. Funktionsumfang und Optik bleiben unverändert; geändert wird nur, was Joomla 6 erfordert oder was nachweislich defekt war.

Das Releaseprodukt ist ein Template-ZIP, das als **Update eines vorhandenen Nina-Templates (2.0.x für Joomla 4/5)** und als Neuinstallation unter Joomla 6 eingespielt werden kann. Template-Stile, Zuordnungen und Parameter bleiben beim Update erhalten.

## Ausgangslage und Prüfgrundlage

- Basis: Joomla51 Nina 2.0.5 (Joomla 4/5).
- Referenz: Joomla-6.2.0-Quellcode (Core-APIs, Layouts, Sprachdateien) statt Annahmen aus Joomla 3/4.
- PHP-Lint unter PHP 8.3, Laufzeittests unter Joomla 6.2.0 mit PHP 8.4, Plugin **Behaviour – Backward Compatibility 6** deaktiviert, Debug-Modus und Fehlerberichterstattung „Maximum“.
- Statische Suche nach Legacy-Code (globale `J*`-Klassen, `jimport`, `JPATH_PLATFORM`, `DS`, `Joomla\CMS\Filesystem`, `Joomla\CMS\Input`, `getDbo/getUser/getDocument`, `$app->input`, `getCfg`, `add*Declaration`): ohne Treffer.
- Alle `Text::_()`-Schlüssel gegen die Joomla-6-Sprachdateien abgeglichen.

## Blocker unter Joomla 6 (ohne Kompatibilitäts-Plugin)

| Bereich | Problem | Lösung |
|---|---|---|
| Template-Optionen (`elements/`) | `defined('JPATH_PLATFORM') or die` – Konstante existiert nur noch im Compat-Plugin, Felder brachen ab | `\defined('_JEXEC') or die;` |
| `elements/tplhelper.php` | `JUri` | `Joomla\CMS\Uri\Uri` |
| `helper.php` (Preset laden/Import/Export) | `\JSession`, globales `\Factory` (Export schon unter J4/J5 defekt) | `Session`, Datenbank aus dem DI-Container |
| `update.php` | `Joomla\CMS\Filesystem\File/Folder` | `Joomla\Filesystem\…`, `is_dir()/is_file()` |
| `elements/checkbox.php` (unbenutzt) | `behavior.mootools`, `DS`, `=&` | entfernt bzw. ersetzt |

## Veraltete APIs

- `Factory::getDocument()` → im Template `$this`, sonst `Factory::getApplication()->getDocument()`.
- `Factory::getUser()` → `getIdentity()`, `Factory::getDbo()` → `DatabaseInterface` aus dem Container, `$app->input` → `getInput()`, `getCfg()` entfernt.
- `jimport()` entfernt, `JLoader::import()` → `require_once`.
- Rund 60 Aufrufe von `addStyleDeclaration`, `addScriptDeclaration`, `addStyleSheet`, `addScript` (Entfernung in Joomla 7) → `$wa->addInlineStyle()`, `addInlineScript()`, `registerAndUseStyle/Script()`, `useStyle()`.
- `_JEXEC`-Guards in `inc/` und `html/layouts/` ergänzt, `composer.json` Plattform PHP 8.3.

Die eigenen Formularfelder behalten ihre Klassennamen `JFormField…`: `FormHelper::loadClass()` findet sie in Joomla 6.2 über `addfieldpath` weiterhin – das ist eine Namenskonvention, kein Klassen-Alias.

## Behobene Fehler (auch unter Joomla 4/5 vorhanden)

- **Fehlerseite:** `error.php` hatte keine `jdoc:include`-Tags im `<head>`; die per WebAssetManager angeforderten Styles und Font Awesome wurden nie ausgegeben, Plugins konnten keine Skripte einfügen (`onBeforeCompileHead` wird erst durch `jdoc:include type="metas"` ausgelöst). Ergänzt; Seitentitel unverändert. Sprachschlüssel `JERROR_LAYOUT_MIS_TYPED_ADDRESS` → `JERROR_LAYOUT_MISTYPED_ADDRESS`.
- **Masonry-Layout:** lud seine Skripte aus `media/j51_assets/…`, das es nicht gibt – Masonry war ohne Funktion. Jetzt template-eigene Assets `template.imagesloaded` und `template.masonry`.
- **Medienfelder in den Template-Optionen:** Editoren wie JCE entfernen den „Leeren“-Button; das Gruppierungsskript verschiebt die Felder, das Joomla-Medienfeld brach beim Wieder-Einhängen ab („Auswählen“ ohne Funktion). Unsichtbarer Platzhalter-Button in `elements/js/script.js`.
- **Systemmeldungen:** `html/layouts/joomla/system/message.php` stammte aus Joomla 3 (Schließen-Knopf ohne Funktion). Override entfernt – Joomla-6-Layout; `update.php` löscht die alte Datei.
- **Frontend-Beitragsformular:** farbige Status-Auswahl unsichtbar (weiße Schrift auf weißem `select-bg.svg`). CSS-Regel in `css/base/template.css`.

## Installer

Das Installer-Skript löschte bei jedem Update alte Override-Ordner früherer Versionen (u. a. `html/mod_login`, `html/com_contact`, `html/mod_custom`) – samt darin angelegter Kunden-Overrides. Ab 6.2.1 läuft diese Bereinigung nur noch beim Update von Versionen vor 2.0.0 (Vorgängerversion aus `manifest_cache` im `preflight`). Die eigene alte `message.php` wird immer entfernt.

## Sonstiges

- Version 6.2.1 (einheitlich mit Emani und Layla), Autor „Joomla51 / Alpenrand Digital“.
- Details-Reiter des Template-Stils: „Project by Alpenrand Digital“ (Fieldset `basic`, Notizfeld, Sprachschlüssel `TPL_J51_NINA_ALPENRAND_NOTE`).
- Eigenes jQuery 3.6.0 bleibt bewusst erhalten.

## Laufzeittests (Joomla 6.2.0, PHP 8.4, Compat-Plugin aus)

| Bereich | Ergebnis |
|---|---|
| Neuinstallation, Update, Kind-Template | ok |
| Template-Optionen öffnen/speichern, Preset laden/Export/Import | ok |
| Navigation: Menüpunkt, Überschrift, Trenner, Untermenü, aktiver Punkt, externe URL in neuem Fenster | ok |
| Mobilmenü (390 px), Tablet (800 px), Sticky-Header, Nach-oben-Button | ok |
| Masonry, Beitrag, Slideshow, News-Karussell | ok |
| Smart Search: Treffer, leere Suche, Umlaute | ok |
| Module: Eigenes Modul, Anmeldung, Menü, Navigationspfad, Suche | ok |
| Frontend-Bearbeitung inkl. Speichern und Systemmeldung | ok |
| 404-Seite, Offline-Seite, `tmpl=component` | ok |
| Browserkonsole, Netzwerk (keine 404 auf Assets) | ok |
