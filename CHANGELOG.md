SEO42 - Changelog
====================

### Version 2.1.0 - 22. September 2013

* Fixed #59: Es kam eine leere Seite bei Block speichern/übernehmen oder nach Cache löschen tauchte ein PHP-Memory Problem auf.
* Fixed: Die Startseite enthielt einen Lang Slug, auch wenn Option `Starseite` -> `http://domain.de/lang-slug/` mit Option `Lang Slug` -> `Kein Lang Slug für Sprache: xy` aktiv war.
* Fixed: Bei einem 404 Fehler sollte der Fehlerartikel nun in der richtigen Sprache erscheinen
* Wurde bereits vor Installation des Addons eine unvollständige URL unter System eingegeben (z.B. www.redaxo.org), erscheint im Setup direkt eine entsprechende Meldung.
* Klasse `rex_navigation42` (ehemals `rex_navigation_ex`) inkl. Codebeispiele zu SEO42 hinzugefügt. Methode `getMenuByLevel()` in `getNavigationByLevel()` und `getMenuByCategory()` in `getNavigationByCategory()` umbenannt.
* Option `Starseite` standardmäßig auf `http://domain.de/lang-slug/` gesetzt. Greift nur bei mehrsprachigen Websites. Hier wird dann z.B. `/en/home.html` direkt in `/en/` umgeschrieben.
* Neue Optionen `global_special_chars` und `global_special_chars_rewrite` zur `settings.lang.inc.php` hinzugefügt (für die URL-Umschreibung). Damit lassen sich Sonderzeichen definieren die für alle Sprachen gültig sind. Die sprachabhängigen SpecialChars haben eine höhere Priorität bei der Ersetzung wie die sprachunabhängigen, globalen SpecialChars.
* RexSEO EP `REXSEO_SPECIAL_CHARS` entfernt, da nun die SepcialChars über die `settings.lang.inc.php` gesetzt werden.
* Neues Codebeispiel für: Sprachunabhängiger Website-Name im Titel
* Umleitungsvariante "WWW -> Nicht-WWW" hinzugefügt. Über die neuen Option `non_www_to_www` ist es möglich zu steuern welche Art von WWW-Umleitung man im Setup haben möchte.
* Es wird geprüft ob die URL schon existiert bei URL-Typen "Interne URL" sowie "Root-Katagorie entfernen"
* Beim URL-Typ "Interne URL" wird beim Setzen einer neuen URL diese korrekt umgeschrieben falls z.B. Sonderzeichen vorkommen

### Version 2.0.0 - 18. September 2013

* AddOn wurde von REXSEO42 in SEO42 umbenannt. Die Klasse `rexseo42` wurde in `seo42` umbenannt. Ein Update-Anleitung findet sich in der README.md.
* Neue URL-Page zum manipulieren von URLs. Einige Url-Typen greifen erst, wenn bei der Ausgabe der Navigation auf diese reagiert wird. Die Klasse `rex_navigation42` (ehemals `rex_navigation_ex`) unterstützt diese Typen bereits (ab 2.1.0 in SEO42 beigelegt). Zusätzliche URL-Typen sind über die Option `all_url_types` abschaltbar.
* Neues Recht `url_default` hinzugefügt um normalen Benutzer die URL-Page ein bzw. auszuschalten.
* PHP-Methode `setWebsiteName()` hinzugefügt sowie `getTitle()` um Parameter `$websiteName` erweitert. Damit lässt sich z.B. über das String Table Addon einen anderen Website-Namen (der damit dann auch sprachunabhängig sein kann) zwecks Titel-Generierung setzen.
* Titel-Vorschau in der Seopage nach oben verschoben
* Neue Optionen `seopage` und `urlpage` um die beiden Seiten global abzuschalten, wenn nicht gebraucht.
* Plugins werden automatisch in das SEO42-Menü eingebunden, wenn installiert und aktiviert (nur für Entwickler interessant).
* Die NoIndex Checkbox in der SEO-Page wurde standardmäßig abgeschaltet. Über die Option `noindex_checkbox` wieder einzuschalten.
* Die No-Prefix/Suffix Checkbox in der SEO-Page wurde standardmäßig abgeschaltet. Über die Option `no_prefix_checkbox` wieder einzuschalten.
* PageRank Checker zu den Tools hinzugefügt. Lässt sich über die Option `pagerank_checker` ausschalten. Domain-Freischalt-Funktion ist Website Manager kompatibel.
* Auf der Debug Seite wird nun auch die Pathlist ausgegeben.
* Option `title_preview` hinzugefügt um die Titel-Vorschau abzuschalten, falls man sein eigenes Titel-Schema implementiert hat.
* Umbenennungen der Optionen: `userdef_canonical_url` -> `custom_canonical_url`, `hide_no_prefix_checkbox` -> `no_prefix_checkbox`.
* Updatedatum des Artikels wird nun automatisch aktualisiert, wenn Änderungen über die SEO-Page durchgeführt wurden.
* Redirects Plugin wird automatisch installiert und aktiviert sobald SEO42 installiert wird.
* Redirects Plugin hinzugefügt um 301 Weiterleitungen komfortabel über das Backend anlegen zu können. Bitte Urls immer mit einem Splash beginnen, die Ziel Url kann aber auch mit http:// beginnen. Plugin ist Website Manager kompatibel.

### Version 1.2.1 - 21. Mai 2013

* Fixed: Es kam ein PHP-Error wenn kein Artikel vorhanden

### Version 1.2.0 - 18. Mai 2013

* Neues Plugin `url_generate` von tbaddade in die README.md mit aufgenommen
* Neuer FAQ Eintrag wegen möglichen 500 Server Error, siehe auch Hinweise in der README.md
* Neues Recht: `seo42[tools_only]` für Zugriff auf die Tools-Page für Nicht-Admins (`seo42[]` muss mit ausgewählt werden)
* Wenn ein Artikel nicht indiziert werden soll wird zusätzl. noch ein `X-Robots Header` ausgegeben.
* Prefix/Suffix Unterscheidung für die Checkbox in der SEO-Page inkl. Option `hide_no_prefix_checkbox` um die Checkbox zu verstecken, wenn anderes Titelschema benötigt
* Beim WWW-Redirect in der `.htaccess` Datei werden jetzt Sudomains ausgeklammert (wichtig für Website Manager AddOn)
* `ignore_root_cats` Option verbessert
* Fixed #49: Wenn die Url einen Trennstrich hatte, wurde fälschlicherweise ein Unterordner entdeckt
* RewriteRules für Website Manager Addon verbessert

### Version 1.1.42 - 23. April 2013

* Fixed #41: Parameter in der URL (test.html?foo=bar) führten zu einem 404 Fehler
* Neues Feature: man kann nun direkt aus dem Backend heraus sich alle Einstellungen anschauen die gerade aktiv sind
* Verbesserte Debug Info: es werden jetzt auch alle Settings und die .htaccess Datei mit ausgegeben
* REX['MOD_REWRITE'] wird on the fly aktiviert sobald AddOn aktiv (master.inc.php wird dafür nicht angepasst)
* Unterordner Verhalten (hoffentlich) verbessert: `subdir_force_full_urls` hinzugefügt, Base Tag komplett entfernt
* Setup für Unterordner-Installationen angepasst: u.a. Option hinzugefügt um die RewriteBase automatisch zu setzen
* Kleine kosmetische Änderungen sowie Textänderungen
* Einstellungsseite lässt sich wieder speichern

### Version 1.0.42 - 10. April 2013

* Änderungen am Datenbank-Schema: `seo_canonical_url` hinzugefügt, `seo_url` in `seo_custom_url` umbenannt
* Neues Feature: Die Canonical Url kann nun auch per Artikel gesetzt werden. Allerdings muss dies explizit in der `settings.advanced.inc.php` aktiviert werden.
* Neues Feature: Es ist nun mögliche "volle" Urls (also inkl. Domain, wie bei WordPress) über die Option `full_urls` zu erhalten
* Neues Feature: `ignore_root_cats` (experimentell)
* Bei normalen Installationen: Base-Tag kann weggelassen werden.
* "Normale" 404-Seite des Webservers, wenn eine Datei unter `files` oder `redaxo` nicht gefunden wurde
* Neue Debug Seite in der Hilfe
* Neue Hilfe Unterpunkte: Codebeispiele und Links, Faq überarbeitet
* Verzeichnis-Auflistung z.B. für files und `files/addon`s wird per .htaccess Datei unterbunden
* Neues Feature: One Page Mode, für Websites die nur über eine einzige Seite verfügen 
* Neues Feature: SEO Tools - eine Linksammlung zu wichtigen SEO-Tools im Netz
* Neue Permission: `seo42[seo_default]` und `seo42[seo_extended]` um für Nicht-Admins die Sichtbarkeit der SEO-Page zu steuern
* editContentOnly[] wird nun berücksichtigt
* Neues Feature: Checkbox zum setzen der WWW-Umleitung im Setup
* `REX['MEDIA_DIR']` wird genutzt
* Robots noIndex Option:  Bug gefixt der bei Mehrsprachigkeit auftrat
* Lang Codes für die Lang Slugs können nun vorläufig in der `settings.lang.inc.php` definiert werden
* Auto DB Fix nach DB-Import wenn die DB Felder fehlen sollten
* SEO-Page: Title, Description und Keywords abgesichert. Keywords werden z.B. nur kleingeschrieben übernommen.
* SEO-Page: Html und CSS aufgeräumt und verbessert
* seo42 Klasse aufgeräumt und ergänzt: `getHtml()`, `getImageTag()`, `getImageManagerUrl()`, etc.
* Änderungen bis RexSEO 1.5.4 reingenommen
* I18N Support: Alles Strings in die Lang-Datei gepackt
* .htaccess Datei aufgeräumt und vereinfacht, `redaxo/.htaccess` entfernt
* Weitere Einstellungen wurden in die `settings.advanced.inc.php` gepackt
* Code wurde generell vereinfacht und aufgeräumt 
* Lizendatei hinzugefügt
* Installation wird bei bei diesen installierten Addons verweigert: `rexseo`, `url_rewrite`, `yrewrite`
* Bei REDAXO 4.5 Beta Versionen wird die Installation nun nicht mehr verweigert
* Aufruf von ADDONS_INCLUDED auf early gesetzt

### Version 1.0.1 - 17. Feburar 2013

* Changelog eingeführt
* Entfernt: Restore Config/Settings Backup
* Einstellungen aus config.inc.php in settings.inc.php verschoben
* Option in settings.inc.php: bei Deinstallation DB-Felder inkl. Daten erhalten 

### Version 1.0.0 - 09. Feburar 2013

Erstes Release mit folgenden Änderungen/Features gegenüber dem original RexSEO:

* Läuft nur mit REDAXO 4.5+
* Entfernt: Selten gebrauchte Features aus der Benutzeroberfläche
* Entfernt: Hilfe-Icons
* Entfernt: Textile Abhängigkeit 
* Entfernt: Default Beschreibung und Suchbegriffe
* Entfernt: Checkbox "Erweiterte Einstellungen"
* Entfernt: GitHub Anbindung
* Entfernt: JavaScript/jQuery Stuff
* Entfernt: Sitemap Changefreq und Priotity
* Canonical URL wird automatisch pro Domain ermittelt, nicht mehr pro Artikel
* Neue Setup-Routine (Quickstart) die in 3 übersichtliche Schritte aufgeteilt ist
* Backups der .htaccess-Dateien werden in das Backup-Verzeichnis geschoben
* Kommt ohne MetaInfos aus stattdessen extra SEO-Page für jeden Artikel
* SEO-Page: Titel Schema laut Google Empfehlung
* SEO-Page: Titel lässt sich mit der Option "Kein Prefix" komplett selber bestimmen
* SEO-Page: Live Titel Vorschau inkl. Titelkürzung a la Google-Suchergebnis
* SEO-Page: Live Anzeige der Buchstaben/Wörter-Anzahl für Beschreibung/Suchbegriffe
* SEO-Page: Live Vorschau der benutzerdefinierten URL
* SEO-Page: noIndex-Option um Seiten aus dem Suchindex auszuschließen
* Klasse `rexseo_meta` durch statische Klasse `seo42` ersetzt
* sitemap.xml sowie robots.txt erhalten die Headeranweisung "X-Robots-tag: noindex"
* Links zur robots.txt und sitemap.xml in der Einstellungen-Seite
* .htaccess-Datei enthält Rewrite-Regel für suchmaschinenfreundliche Image-Manager-URLs 
* PHP Notices entfernt, Strict-Kompatibel
* REX['GENERATED_PATH'] aus REDAXO 4.5 wird genutzt
* FAQ mit der Antwort auf die Fragen aller Fragen ;)
