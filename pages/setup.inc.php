<?php
$page    = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$chapter = rex_request('chapter', 'string');
$func    = rex_request('func', 'string');
$myroot  = $REX['INCLUDE_PATH'].'/addons/' . $page;

if ($func == "do_copy") {
	// first backup files
	$htaccessRoot = $REX['FRONTEND_PATH'] . '/.htaccess';
	$backupPathRoot = $REX['INCLUDE_PATH'] . '/addons/rexseo42/backup/';
	$htaccessBackupFile = '_htaccess_' . date('Ymd_His');
	$doCopy = true;
	$fileExists = false;

	if (file_exists($htaccessRoot)) {
		$fileExists = true;

		if (copy($htaccessRoot, $backupPathRoot . $htaccessBackupFile)) {
			$doCopy = true;
		} else {
			rex_warning('Datei "' . $htaccessRoot . '" konnte nicht gesichert werden!');
			$doCopy = false;
		} 
	}

	// then copy if backup was successful
	if ($doCopy) {
		$source = $REX['INCLUDE_PATH'] . '/addons/rexseo42/install/';
		$target = $REX['HTDOCS_PATH'];
		$result = rexseo_recursive_copy($source, $target);

		if ($result[1]['copystate'] == 1) {
			$msg = 'Kopieren der Datei erfolgreich.';
	
			if ($fileExists) {
				$msg .= ' Ein Backup wurde zuvor angelegt.';
			}

			echo rex_info($msg);
		} else {
			echo rex_warning('Beim Kopieren der .htaccess Datei ist ein Fehler aufgetreten. Bitte kopieren Sie die Datei manuell.');	
		}
	} else {
		echo rex_warning('Backup der .htaccess Datei fehlgeschlagen. Bitte kopieren Sie die Datei manuell.');
	}
} elseif ($func == "apply_settings") {
	$server = str_replace("\\'", "'", rex_post('server', 'string'));
	$servername  = str_replace("\\'", "'", rex_post('servername', 'string'));
	$modRewrite = rex_post('mod_rewrite', 'int');

	if ($modRewrite == 1) {
		$modRewriteBool = 'true';
	} else {
		$modRewriteBool = 'false';
	}

	$masterFile = $REX['INCLUDE_PATH'] . '/master.inc.php';
	$content = rex_get_file_contents($masterFile);

	$search = array('\\"', "'", '$');
	$destroy = array('"', "\\'", '\\$');
	$replace = array(
		'search' => array(
			"@(REX\['SERVER'\].?\=.?).*$@m",
			"@(REX\['SERVERNAME'\].?\=.?).*$@m",
			"@(REX\['MOD_REWRITE'\].?\=.?).*$@m"
		),
		'replace' => array(
			"$1'".str_replace($search, $destroy, $server) . "';",
			"$1'".str_replace($search, $destroy, $servername) . "';",
			'$1'.strtolower(str_replace($search, $destroy, $modRewriteBool)) . ';'
		)
	);

	$content = preg_replace($replace['search'], $replace['replace'], $content);

	if (rex_put_file_contents($masterFile, $content) > 0) {
		echo rex_info('Einstellungen wurden übernommen.');

		$REX['MOD_REWRITE'] = $modRewrite;
		$REX['SERVER'] = stripslashes($server);
		$REX['SERVERNAME'] = stripslashes($servername);
	} else {
		echo rex_warning('Speichern der Einstellungen fehlgeschlagen. Bitte nehmen Sie die Einstellungen manuell auf der System-Seite vor.');
	}
}
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2">Schritt 1: Datei kopieren</h2>
	<div class="rex-area-content">
		<p>Diese .htaccess Datei muss in das Hauptverzeichnis von REDAXO kopiert werden:</p>
		<ul class="no-bottom-margin">
			<li><code>/redaxo/include/addons/rexseo42/install/.htaccess</code> nach <code>/.htaccess</code></li>
		</ul>

		<form action="index.php" method="get">
			<input type="hidden" name="page" value="rexseo42" />
			<input type="hidden" name="subpage" value="setup" />
			<input type="hidden" name="func" value="do_copy" />
			<div class="rex-form-row">
				<p class="button"><input type="submit" class="rex-form-submit" name="sendit" value="Dateien kopieren" /></p>
			</div>
		</form>
	</div>
</div>

<div class="rex-addon-output">
	<h2 class="rex-hl2">Schritt 2: Einstellungen vornehmen</h2>
	<div class="rex-area-content">
		<p class="info-msg">Bitte die folgenden <a href="index.php?page=specials">System</a> Einstellungen vornehmen bzw. ergänzen:
		<form action="index.php" method="post" id="settings-form">
			<p class="rex-form-col-a first-textfield">
				<label for="servername">Name der Website</label>
				<input name="servername" id="servername" type="text" class="rex-form-text" value="<?php echo htmlspecialchars($REX['SERVERNAME']); ?>" />
			</p>

			<p class="rex-form-col-a">
				<label for="server">Domain der Website</label>
				<input name="server" id="server" type="text" class="rex-form-text" value="<?php echo htmlspecialchars($REX['SERVER']); ?>" />
			</p>

			<p class="rex-form-col-a rex-form-checkbox ">
				<label for="mod_rewrite">Mod-Rewrite einschalten</label>
				<input type="checkbox" checked="checked" value="1" id="mod_rewrite" name="mod_rewrite" />
			</p>

			<input type="hidden" name="page" value="rexseo42" />
			<input type="hidden" name="subpage" value="setup" />
			<input type="hidden" name="func" value="apply_settings" />
			<div class="rex-form-row">
				<p class="button"><input type="submit" class="rex-form-submit" name="sendit" value="Einstellungen übernehmen" /></p>
			</div>
		</form>
	</div>
</div>

<?php
$codeExample2 = '<head>
	<base href="<?php echo rexseo42::getBaseUrl(); ?>" />
	<title><?php echo rexseo42::getTitle(); ?></title>
	<meta name="description" content="<?php echo rexseo42::getDescription(); ?>" />
	<meta name="keywords" content="<?php echo rexseo42::getKeywords(); ?>" />
	<meta name="robots" content="<?php echo rexseo42::getRobotRules();?>" />
	<link rel="canonical" href="<?php echo rexseo42::getCanonicalUrl(); ?>" />
</head>';
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2">Schritt 3: Template ergänzen</h2>
	<div class="rex-area-content">
		<p class="info-msg">Zuletzt ergänzen Sie den Head-Bereich Ihres Templates wie folgt:</p><?php rex_highlight_string($codeExample2); ?>
	</div>
</div>

<style type="text/css">
#rex-page-rexseo42 .rex-code {
    word-wrap: break-word;
}

#rex-page-rexseo42 .info-msg {
	margin-bottom: 10px;
}

#rex-page-rexseo42 .no-bottom-margin {
	margin-bottom: 0px;
	margin-top: 7px;
}

#rex-page-rexseo42 .button {
	float: right; 
	margin-bottom: 10px; 
	margin-right: 5px;
}

#rex-page-rexseo42 p.rex-form-col-a.first-textfield {
	margin-bottom: 3px;
}

#rex-page-rexseo42 p.rex-form-col-a label {
	width: 160px;
	display: inline-block;
	margin-bottom: 10px;
}

#rex-page-rexseo42 p.rex-form-col-a input.rex-form-text {
	width: 320px;
}

#rex-page-rexseo42 p.rex-form-checkbox input {
	position: relative;
	top: 3px;
}
</style>

<script type="text/javascript">
jQuery(document).ready( function() {
	jQuery('#settings-form').submit(function() {
		var pat = /^https?:\/\//i;
		var serverString = jQuery('#server').val();

		if ((pat.test(serverString)) && (serverString.charAt(serverString.length - 1) == '/')) {
			return true;
		}

		alert('URL dieser Website muss mit "http://" beginnen und mit "/" enden.');
		return false;
	});

	jQuery('#mod_rewrite').click(function () {
		var thisCheck = jQuery(this);
		
		if (!thisCheck.is(':checked')) 	{
			alert("Mod-Rewrite muss eingeschaltet sein!");
		}
	});
});
</script>

