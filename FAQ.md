SEO42 - FAQ
===========

Mein Webserver meldet einen 500 Server Error?!
----------------------------------------------

Vermutlich liegt es an der Zeile `Options -Indexes` in der .htaccess Datei. Einfach diese Zeile auskommentieren.


Wie muss die URL der Website im Setup genau angegeben werden?
-------------------------------------------------------------

Die URL der Website muss mit `http://` beginnen und mit einem `/` enden.


Gibts was bestimmtes zu beachten wenn ich REDAXO in einem Unterordner installiert habe?
---------------------------------------------------------------------------------------

Im SEO42 Setup (URL der Website) und in der .htaccess den Unterordner mit angeben: `RewriteBase /unterordner`
Desweiteren sollten Ihre URLs nicht mit / beginnen. Nutzen Sie die PHP-Methoden aus Codebeispiel Nr.2!


Kann ich den Base-Tag auch weglassen?
-------------------------------------

Ja, wenn Sie alle Ihre URLs anhand der PHP-Methoden aus Codebeispiel Nr.2 generieren lassen. Dies gilt insbesondere für REDAXO-Unterordner-Installationen.


Wo legt SEO42 die Backups für die .htaccess Dateien an?
-------------------------------------------------------

Im Backup-Verzeichnis des AddOns: `/seo42/backup/`


SEO42 läuft nicht mehr rund. Was tun?
-------------------------------------

Cache löschen, AddOn reinstallieren und evtl. das Setup neu durchlaufen sind hier zuerstmal deine Freunde ;)


Ich möchte das Pipe-Zeichen im Titel umstellen. Wo kann ich das machen?
-----------------------------------------------------------------------

Der zuständige Parameter heißt `title_delimiter` und ist in der `settings.advanced.inc.php` einstellbar.


Wie kann ich den Titel sonst noch beinflussen?
----------------------------------------------

Auf der SEO-Seite eines Artikels kann man einen individuellen Titel eintragen. Über "Kein Prefix" wird dieser ohne Zusatz angezeigt. Man kann außerdem die Methode `seo42::getTitle()` überschreiben, siehe dazu Codebeispiel 5 in der Hilfe.


Warum wird der Titel für die Startseite "andersherum" ausgegeben wie für die Folgeseiten?
-----------------------------------------------------------------------------------------

Dies wurde im nachfolgenden PDF von Google abgeguckt (siehe nächste Frage).


Wo bekomme ich eigentlich eine gute verständliche Einführung in das Thema "Suchmaschinenoptimierung"?
-----------------------------------------------------------------------------------------------------

Zum Beispiel hier: [http://www.google.de/webmasters/docs/einfuehrung-in-suchmaschinenoptimierung.pdf](http://www.google.de/webmasters/docs/einfuehrung-in-suchmaschinenoptimierung.pdf)


Wo sind eigentlich all die restlichen Einstellungen von RexSEO abgeblieben?
---------------------------------------------------------------------------

Vorläufig wurden diese hier ausgelagert: `settings.advanced.inc.php`


Wie kann ich suchmaschinenfreundliche URLs für Bilder erhalten, die über den Image Manager generiert wurden?
------------------------------------------------------------------------------------------------------------

Zum Beispiel über die Methode `seo42::getImageManagerUrl()`. Siehe dazu Codebeispiel Nr. 3 in der Hilfe.


Ich möchte nicht-www Anfragen auf die www Variante umleiten lassen. Soll doch SEO-technisch gut sein, oder?
-----------------------------------------------------------------------------------------------------------

Yup. In der .htaccess die entsprechenden Zeilen un-auskommentieren bzw. über Setup Schritt 1 automatisch machen lassen.


Meine URLs haben sich geändert. Wie kann ich saubere Weiterleitungen hinbekommen?
---------------------------------------------------------------------------------

Dafür gibt es mittlerweile das mitgelieferte Redirects Plugin. Bitte Urls immer mit einem Splash beginnen, die Ziel Url kann aber auch mit http:// beginnen.


Habe gehört das es SEO-technisch gut wäre wenn Bilder ein Alt-Attribut zugewiesen bekommen?
-------------------------------------------------------------------------------------------

Dafür kann man die Methode `seo42::getImageTag()` nutzen, die dann einen kompletten Image-Tag inkl. Alt-Attribut ausspuckt (siehe Codebeispiel 3). Und im Medienpool wird dieser dann im Titel-Feld gesetzt.


Wenn ich Links mit Anchors nutze, lande ich immer auf der Startseite?
---------------------------------------------------------------------

Dann immer vollständige Links inkl. vorangestelltem Artikel benutzen, z.B. `redaxo://13#anker`.


Ich hab ne Frage die hier net auftaucht. Was tun?
-------------------------------------------------

Schau doch mal ins original RexSEO. Da steht noch mehr Stuff... ;)


Mir gefällt SEO42 nicht! Wie bekomme ich das AddOn wieder entfernt?
-------------------------------------------------------------------

Kein Problem ;) Einfach das Setup von hinten nach vorne durchlaufen und das AddOn deinstallieren.


Wie lautet die Antwort auf die Frage aller Fragen nach dem Leben, dem Universum und dem ganzen Rest?
----------------------------------------------------------------------------------------------------

42


