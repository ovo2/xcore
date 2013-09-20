SEO42 AddOn für REDAXO 4.5+
==============================

Ein intergalaktischer Fork des original RexSEO AddOns für REDAXO mit alternativer Benutzerführung und noch weiterer SEO-Goodies...

Features
--------

* Einstellungen-Seite auf das Wesentliche reduziert 
* Zusätzliche Einstellungen vorerst in die `settings.advanced.inc.php` und `settings.lang.inc.php` verlagert
* Neue Setup-Routine
* Kommt ohne MetaInfos aus
* Titel-Schema inspiriert von Google-Empfehlung
* Extra SEO-Page für jeden Artikel inkl. Titel-Vorschau
* Extra URL-Page für jeden Artikel zur Manipulierung der generierten URL
* Suchmaschinenfreundliche Image Manager Urls durch Verwendung der verfügbaren PHP-Methoden
* SEO Tools inkl. PageRank Checker
* Enthält die Antwort auf die eine Frage
* Und noch vieles mehr...

Wichtiger Hinweis vorab
-----------------------

SEO42 kommt mittlerweile ohne einen Base-Tag aus. Dafür ist es aber sehr wichtig, dass alle URLs gleich beginnen, im normalfall mit einem `/`.
Es wird deshalb empfohlen für alle URLs immer die PHP-Methoden aus dem Codebeispiel Nr.2 aus der Hilfe nutzen.

Alle URL-Typen aktivieren
-------------------------

* Einige Url-Typen greifen erst, wenn bei der Ausgabe der Navigation auf diese reagiert wird.
* Die aktualisierte Klasse `rex_navigation_ex` unterstützt diese Typen bereits: https://gist.github.com/RexDude/5354172
* Über die Option `all_url_types` können diese bei Bedarf aber auch deaktiviert werden.

Update von REXSEO42 1.2.x auf SEO42 2.0.0
-----------------------------------------

Ein Update wird nur empfohlen, wenn die neuen Features von der 2er Version benötigt werden.

* In der `settings.advanced.inc.php` von REXSEO42 die Option `drop_dbfields_on_uninstall` auf `false` setzen.
* REXSEO42 deinstallieren und AddOn-Ordner löschen.
* SEO42 installieren.
* In allen Templates den Klassennamen von `rexseo42` nach `seo42` umbenennen.
* AddOn-Einstellungen von Hand nachprüfen und ggf. korrigieren.
* Ggf. Cache löschen.

Weitere Hinweise
----------------

* Läuft nur mit REDAXO 4.5+
* AddOn-Ordner lautet: `seo42`
* AddOn wurde seit Version 2.0.0 von REXSEO42 in SEO42 umbenannt.
* RexSEO Plugins laufen mit SEO42 vorerst nicht.
* Wenn der Webserver einen 500 Server Error meldet die Zeile `Options -Indexes` in der `.htaccess` auskommentieren.
* Geändertes Verhalten für REDAXO Unterordner-Installationen. Bitte FAQ in der Hilfe des AddOns anschauen für weitere Infos.
* Implementiert man sein eigenes Titel-Schema, ist es vielleicht sinnvoll die Optionen `title_preview` und `no_prefix_checkbox` auf `false` zu setzen.
* `$REX["MOD_REWRITE"]` braucht nicht mehr auf `true` gesetzt werden (z.B. über die System-Page von REDAXO). Wenn SEO42 aktiv, wird es automatisch gesetzt.
* Eine hilfreiche Sprach-Sonderzeichen-Tabelle für die `settings.lang.inc.php` findet man hier: http://unicode.e-workers.de/
* Momentan muss man von Hand benötigte Einstellungen vornehmen in den Dateien `settings.advanced.inc.php` und `settings.lang.inc.php`.

Hinweise zum Redirects Plugin
-----------------------------

* Bitte Urls immer mit einem Splash beginnen, die Ziel Url kann aber auch mit http:// beginnen. Plugin ist Website Manager kompatibel.

Verfügbare Plugins
------------------

* `redirects` von RexDude (ist beigelegt, siehe auch Hinweise)
* `url_control` von tbaddade: https://github.com/tbaddade/redaxo_plugin_url_control

Changelog
---------

siehe [CHANGELOG.md](CHANGELOG.md)

Lizenz
------

siehe [LICENSE.md](LICENSE.md)

Credits
-------

* [GN2](https://github.com/gn2netwerk) und [jdlx](https://github.com/jdlx) für das original RexSEO AddOn
* [Markus Staab](https://github.com/staabm) für das zugrundeliegende url_rewrite AddOn
* [Jan Kristinus](http://github.com/dergel) für REDAXO und den neuen EP in REDAXO 4.5
* [Gregor Harlan](https://github.com/gharlan) und [Thomas Blum](https://github.com/tbaddade) für Hilfe, Code und Bugmeldungen :)
* Danke ausserdem an alle die sich mit Ideen und Bugmeldungen eingebracht haben :)
* David Walsh and Jamie Scott für die Google PageRank Checker Klasse

