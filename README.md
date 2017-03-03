X-Core für REDAXO 5
===================

Nachfolger von SEO42 :)

Features
--------

* Sauber eingestelltes Caching sowie Komprimierung für Resourcen wie Bildern, Fonts, CSS und JS Dateien (.htaccess)
* Verschiedene URL-Endungen einstellbar (z.B. Endung `.html` oder `/`)
* Automatische Titel-Generierung. Mitgeliefertes Titel-Schema aus [Google-PDF](http://www.google.de/webmasters/docs/einfuehrung-in-suchmaschinenoptimierung.pdf) entnommen.
* Suchmaschinenfreundliche Media Manager Urls durch Verwendung der verfügbaren PHP-Methoden
* Force Download Funktionalität inkl. suchmaschinenfreundlicher URLs und Canonical Header (z.B. für PDF Downloads)
* LangPresets: Spezielle sprachabhängige sowie sprachunabhängige Sonderzeichen-Umschreibungen einstellbar. Aktuell werden 14 Sprachen out-of-the-box unterstützt
* Smart Redirects: Automatische Umleitungen für falsch eingegebene Urls z.B. von Url-Endung `/` nach `.html`
* Offline 404 Modus: Offline Artikel sind für nicht eingeloggte Benutzer nicht mehr erreichbar (404 Seite)
* Automatisches setzen der Locale in PHP um z.B. Monatsnamen/Datumsangaben in der richtigen Sprache zu erhalten
* Sync der Developer Files in das Project AddOn
* Automatisches senden von Header wie `X-UA-Compatible` oder einen `Cache-Control` Header für Server die Media Manager Bilder sonst nicht korrekt cachen
* Bei gleichem Startartikel wie Fehlerartikel wird ein 404 gesendet (was REDAXO sonst nicht tut)
* rexx API: Umfangreiche API a la seo42 Klasse (diesmal) inkl. Dokumentation
* Mitgeliefertes Boilerplate (Template) welches die rexx API benutzt
* Resourcen Handling: Kombinieren von mehreren CSS / JS Dateien zu einer. Senden eines Versionsstrings (Cache-Buster) usw.
* Integrierter LESS sowie SCSS Compiler
* X-Core Extra Styles, Backend ohne grün sowie REDAXO Logo Flicker Fixer
* Klasse `rexx_markdown`: modifizierte Parsedown Klasse die Syntax Highlighting von Codeblöcken in MD Dateien unterstützt
* Klasse `rexx_simple_html_dom`: Wrapper für Simple Html Dom zum einfachen manipulieren des HTML Doms per PHP
* JS Tools wie der Panel Toggler und Persistent Tabs für die Speicherung der Boostrap Tabposition (über die CSS Klasse `rexx-persistent-tabs` einstellbar)
* Zur Website Link in der Metanavigation

Dokumentation
-------------

* [rexx API](docs/rexx_api.md)
* [Simple Html Dom](http://simplehtmldom.sourceforge.net/)

Hinweise
--------

* Getestet mit REDAXO 5.2, 5.3
* AddOn-Ordner lautet: `xcore`
* Abhängigkeiten: yRewrite
* 500 Serverfehler: Wenn Sie einen 500 Serverfehler bekommen entfernen Sie die Zeile `Options -Indexes` aus der `.htaccess` Datei im root Verzeichnis.

Changelog
---------

siehe `CHANGELOG.md` des AddOns

Lizenz
------

MIT-Lizenz, siehe `LICENSE.md` des AddOns

Credits
-------

* X-Core nutzt Code aus dem Theme Addon von Daniel Weitenauer
* PHP-Markdown-Documentation-Generator
* Logo ist Public Domain
* More to come...
