SEO42 - The Hitchhiker's Guide to SEO42
=======================================

Diese Hinweise richten sich primär an User die zum ersten mal SEO42 nutzen oder noch Anfänger auf dem Gebiet REDAXO und SEO(42) sind.

- Nach Installation und Aktivierung des AddOns SEO42 sollte als nächstes das SEO42 Setup durchgeführt werden.
- Nach durchführen des Setups sollte unbedingt die Spracheinstellungen kontrolliert und ggf. angepasst werden ja nach eingesetzter Sprachen.
- Ein No Double Content Redirect sollte in den Einstellungen ausgewählt werden nachdem das Setup durchgeführt wurde. Empfohlen: One Domain Only (WWW/Nicht-WWW ist hier bereits inkludiert)
- Hartcodierte Urls wie `index.php?article_id=42` sind nicht mehr zulässig. Alle Urls müssen über `rex_getUrl(42)` ausgegeben werden. Siehe Hilfe > Codebeispiele 1.
- Für ein bestmögliches SEO sollte man sich an das SEO42 Boilerplate im Setup und an die Codebeispiele in der Hilfe (z.b. suchmaschinenfreundliche Image Manager Urls) orientieren. 
- Zum SEO42 Boilerplate: Der eigene Code wird direkt nach `<body>` notiert, die JS Skripts kommen als letztes. Aufruf von `seo42::getLangTags()` kann bei einsprachigen Websites entfernt werden. Und die Einbindung der CSS und JS Dateien sind als Beispiel zu verstehen und müssen individuell angepasst werden ;)
- Standardmäßig werden CSS/JS Dateien aus einem Resourcen-Ordner geholt. Hat man seine CSS/JS Dateien an einem anderen Ort liegen wie standardmäßig eingestellt, so kann man unter Einstellungen > Resourcen die Pfade anpassen (überall `/files/` geht auch). Alternativ kann man auch `seo42::getResourceFile('/meinpfad/foo.css')` nutzen um so die fixen Pfade zu umgehen.
- In der `.htaccess` Datei ist eine Cachingdauer für CSS/JS Dateien von 4 Wochen eingestellt. Bitte reduzieren (z.B. auf 1 Woche) wenn die Dateien nicht wie vorgeschlagen per `seo42::getCSSFile()` / `seo42::getJSFile()` eingebunden werden sollten.
- Der Fehlerartikel unter REDAXO > System sollte nicht gleich dem Startartikel der Website entsprechen. Es sollte aufjedenfall ein eigener Fehlerartikel angelegt werden.
- Einen kurze Einführung zu SEO42 findet man hier: [REDAXO Tutorial: SEO-AddOns - URL-Umschreibung und mehr](http://www.redaxo.org/de/doku/sinnvolle-addons/seo-addons---urls-und-mehr/)
- Für Websites die bei 1und1 gehostet werden: es empfiehlt sich die Einstellung `HTTP Header` > `Image Manager Cache Control Header Fix` zu setzen, da sonst Image Manager Bilder nicht gecachet werden.
- Die Einstellungen sind bereits optimal gesetzt für die meisten Use Cases. Es empfiehlt sich hier nur was zu verstellen wenn man genau weiss was man tut ;)

