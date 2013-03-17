<?php
$page    = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$chapter = rex_request('chapter', 'string');
$func    = rex_request('func', 'string');
$myroot  = $REX['INCLUDE_PATH'].'/addons/' . $page;
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2">FAQ</h2>
	<div class="rex-area-content">
		<h2>Warum brauchts eigentlich noch ein weiteres RexSEO?</h2>
		<p>Ganz einfach: Weil RexSEO zwar sehr gefällt, aber so wie es ist in manchen Punkten (z.B. Setup) nicht den Vorstellungen vom RexDude entspricht und es ja außerdem völlig legitim ist einen Fork nach seinem eigenem Gusto zu machen.</p>

		<h2>Ich habe eine Seite importiert und jetzt hagelt es Fehlermeldungen im HTML-Quellcode!?</h2>
		<p><?php echo $REX['ADDON']['name']['rexseo42']; ?> <a href="index.php?page=addon&addonname=rexseo42&install=1">reinstallieren</a> sollte das Problem beheben.</p>

		<h2>Gibts was bestimmtes zu beachten wenn ich REDAXO in einem Unterverzeichnis installiert habe?</h2>
		<p>Ja. In der .htaccess im Root-Ordner bei RewriteBase das Unterverzeichnis ergänzen: <code>RewriteBase /UNTERVERZEICHNIS</code> sowie darauf achten, dass "Url dieser Website" ohne das Unterverzechnis angegeben wird.</p>

		<h2>Ich mag das Pipe-Zeichen nicht im Titel. Wie kann ich das umstellen?</h2>
		<p>Momentan so: der Methode getTitle das neue Zeichen als Parameter übergeben: <code>rexseo42::getTitle("-");</code></p>

		<h2>Wie kann ich den Titel sonst noch beinflussen?</h2>
		<p>Auf der SEO-Seite eines Artikels kann man einen individuellen Titel eintragen. Über "Kein Prefix anzeigen" wird dieser ohne Zusatz angezeigt. Man kann außerdem die Methode <code>rexseo42::getTitle()</code> für Spezial-Titel-Geschichten überschreiben.</p>

		<h2>Warum wird der Titel für die Startseite "andersherum" ausgegeben wie für die Folgeseiten?</h2>
		<p>Siehe PDF in Antwort zur nächsten Frage.</p>

		<h2>Wo bekomme ich eigentlich eine gute verständliche Einführung in das Thema "Suchmaschinenoptimierung"?</h2>
		<p>Zum Beispiel hier: <a href="http://www.google.de/webmasters/docs/einfuehrung-in-suchmaschinenoptimierung.pdf" target="_blank" class="extern">einfuehrung-in-suchmaschinenoptimierung.pdf</a></p>
		
		<h2>Wie kann ich suchmaschinenfreundlichere URLs für Bilder aus dem Image Manager erhalten?</h2>
		<p>Im HTML anstelle von <code>index.php?rex_img_type=ImgTypeName&rex_img_file=ImageFileName</code> dass hier verwenden:<br /><code>/files/imagetypes/ImgTypeName/ImageFileName</code></p>

		<h2>Ich möchte nicht-www Anfragen auf die www Variante umleiten lassen. Soll doch SEO-technisch gut sein, oder?</h2>
		<p>Yup. In der .htaccess im Root-Ordner die entsprechenden Zeilen un-auskommentieren.</p>

		<h2>Meine URLs haben sich geändert. Wie kann ich saubere Weiterleitungen hinbekommen?</h2>
		<p>In der .htaccess im Root-Ordner an oberster Stelle pro URL eine 301 Weiterleitung nach diesem Schema einrichten:<br /><code>Redirect 301 /alte-seite.html http://www.my-domain.de/neue-seite.html</code></p>

		<h2>Habe gehört das es SEO-technisch gut wäre wenn Bilder ein Alt-Attribut zugewiesen bekommen?</h2>
		<p>Dafür kann man die Methode <code>rexseo42::getImageTag($file)</code> nutzen, die dann einen kompletten Image-Tag inkl. Alt-Attribute ausspuckt. Und im Medienpool wird dieser dann im Titel-Feld gesetzt.</p>

		<h2>Wenn ich Links mit Anchors nutze, lande ich immer auf der Startseite?</h2>
		<p>Immer vollständige Links inkl. vorangestelltem Artikel benutzen, z.B. <code>redaxo://13#anker</code></p>

		<h2>Wo legt <?php echo $REX['ADDON']['name']['rexseo42']; ?> die Backups für die .htaccess Dateien an?</h2>
		<p>Im Backup-Verzeichnis des AddOns: <code>/rexseo42/backup/</code></p>

		<h2>Ich hab ne Frage die hier net auftaucht. Was tun?</h2>
		<p>Schau doch mal ins original RexSEO. Da steht noch mehr Stuff... ;)</p>

		<h2>Mir gefällt <?php echo $REX['ADDON']['name']['rexseo42']; ?> nicht! Wie krieg ich das wieder raus aus meinem REDAXO?</h2>
		<p>Kein Problem ;) Einfach das Setup von hinten nach vorne durchlaufen und das AddOn <a href="index.php?page=addon&addonname=rexseo42&uninstall=1">deinstallieren</a>.</p>

		<h2>Wie lautet die Antwort auf die Frage aller Fragen nach dem Leben, dem Universum und dem ganzen Rest?</h2>
		<p>42</p>

	</div>
</div>

<style type="text/css">
#rex-page-rexseo42 .rex-area-content {
	padding: 12px;
}

#rex-page-rexseo42 .rex-area-content h2 {
	font-size: 13px;
}

#rex-page-rexseo42 .rex-area-content p {
	margin-bottom: 15px;
}
</style>

