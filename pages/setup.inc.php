<?php
$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$chapter = rex_request('chapter', 'string');
$func = rex_request('func', 'string');

$myroot  = $REX['INCLUDE_PATH'] . '/addons/' . $page;

$htaccessRoot = $REX['FRONTEND_PATH'] . '/.htaccess';
$backupPathRoot = $REX['INCLUDE_PATH'] . '/addons/rexseo42/backup/';

if ($func == "do_copy") {
	// first backup files
	$htaccessBackupFile = '_htaccess_' . date('Ymd_His');
	$doCopy = true;
	$htaccessFileExists = false;
	$copySuccessful = false;

	if (file_exists($htaccessRoot)) {
		$htaccessFileExists = true;

		if (copy($htaccessRoot, $backupPathRoot . $htaccessBackupFile)) {
			$doCopy = true;
		} else {
			rex_warning($I18N->msg('rexseo42_setup_file_backup_failed', $htaccessRoot));
			$doCopy = false;
		} 
	}

	// then copy if backup was successful
	if ($doCopy) {
		$sourceFile = $REX['INCLUDE_PATH'] . '/addons/rexseo42/install/_htaccess';

		if (copy($sourceFile, $htaccessRoot)) {
			$copySuccessful = true;
			$msg = $I18N->msg('rexseo42_setup_file_copy_successful');
	
			if ($htaccessFileExists) {
				$msg .= ' ' . $I18N->msg('rexseo42_setup_backup_successful');
			}

			echo rex_info($msg);
		} else {
			echo rex_warning($I18N->msg('rexseo42_setup_file_copy_failed'));	
		}
	} else {
		echo rex_warning($I18N->msg('rexseo42_setup_backup_failed'));
	}

	if ($copySuccessful && rex_request('www_redirect', 'int') == 1) {
		// this is for non-ww to www redirect
		$wwwRedirect1 = '#RewriteCond %{HTTP_HOST} !^www\. [NC]';
		$wwwRedirect2 = '#RewriteRule (.*) http://www.%{HTTP_HOST}/$1 [R=301,L]';
	
		$content = rex_get_file_contents($htaccessRoot);
		$content = str_replace($wwwRedirect1, ltrim($wwwRedirect1, '#'), $content);
		$content = str_replace($wwwRedirect2, ltrim($wwwRedirect2, '#'), $content);

		if (rex_put_file_contents($htaccessRoot, $content) > 0) {
			echo rex_info($I18N->msg('rexseo42_setup_www_redirect_patch_ok'));
		} else {
			echo rex_warning($I18N->msg('rexseo42_setup_www_redirect_patch_failed'));
		}
	}
} elseif ($func == "apply_settings") {
	$server = str_replace("\\'", "'", rex_post('server', 'string'));
	$servername  = str_replace("\\'", "'", rex_post('servername', 'string'));
	$modRewrite = rex_request('mod_rewrite', 'int');

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
		echo rex_info($I18N->msg('rexseo42_setup_settings_saved'));

		$REX['MOD_REWRITE'] = $modRewrite;
		$REX['SERVER'] = stripslashes($server);
		$REX['SERVERNAME'] = stripslashes($servername);
	} else {
		echo rex_warning($I18N->msg('rexseo42_setup_settings_save_failed'));
	}
}
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('rexseo42_setup_step1'); ?></h2>
	<div class="rex-area-content">
		<p><?php echo $I18N->msg('rexseo42_setup_step1_msg1'); ?></p>
		<form action="index.php" method="post">
			<p class="no-bottom-margin" id="codeline">
				<code>/redaxo/include/addons/rexseo42/install/_htaccess</code> <?php echo $I18N->msg('rexseo42_setup_to'); ?> <code>/.htaccess</code>
			</p>

			<p class="rex-form-checkbox rex-form-label-right"> 
				<input type="checkbox" value="1" id="www_redirect" name="www_redirect" />
				<label for="www_redirect"><?php echo $I18N->msg('rexseo42_setup_www_redirect_checkbox'); ?></label>
			</p>

			<input type="hidden" name="page" value="rexseo42" />
			<input type="hidden" name="subpage" value="setup" />
			<input type="hidden" name="func" value="do_copy" />
			<div class="rex-form-row">
				<p class="button"><input type="submit" class="rex-form-submit" name="sendit" id="copy-file-submit" value="<?php echo $I18N->msg('rexseo42_setup_step1_button'); ?>" /></p>
			</div>
		</form>
	</div>
</div>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('rexseo42_setup_step2'); ?></h2>
	<div class="rex-area-content">
		<p class="info-msg"><?php echo $I18N->msg('rexseo42_setup_step2_msg1'); ?></p>
		<form action="index.php" method="post" id="settings-form">
			<p class="rex-form-col-a first-textfield">
				<label for="servername"><?php echo $I18N->msg('rexseo42_setup_website_name'); ?></label>
				<input name="servername" id="servername" type="text" class="rex-form-text" value="<?php echo htmlspecialchars($REX['SERVERNAME']); ?>" />
			</p>

			<p class="rex-form-col-a">
				<label for="server"><?php echo $I18N->msg('rexseo42_setup_website_url'); ?></label>
				<input name="server" id="server" type="text" class="rex-form-text" value="<?php echo htmlspecialchars($REX['SERVER']); ?>" />
			</p>

			<p class="rex-form-col-a rex-form-checkbox ">
				<label for="mod_rewrite"><?php echo $I18N->msg('rexseo42_setup_activate_mod_rewrite'); ?></label>
				<input type="checkbox" checked="checked" value="1" id="mod_rewrite" name="mod_rewrite" />
			</p>

			<input type="hidden" name="page" value="rexseo42" />
			<input type="hidden" name="subpage" value="setup" />
			<input type="hidden" name="func" value="apply_settings" />
			<div class="rex-form-row">
				<p class="button"><input type="submit" class="rex-form-submit" name="sendit" value="<?php echo $I18N->msg('rexseo42_setup_step2_button'); ?>" /></p>
			</div>
		</form>
	</div>
</div>

<?php
$codeExample = '<head>
	<title><?php echo rexseo42::getTitle(); ?></title>
	<meta name="description" content="<?php echo rexseo42::getDescription(); ?>" />
	<meta name="keywords" content="<?php echo rexseo42::getKeywords(); ?>" />
	<meta name="robots" content="<?php echo rexseo42::getRobotRules();?>" />
	<link rel="canonical" href="<?php echo rexseo42::getCanonicalUrl(); ?>" />
</head>';


$codeExampleSubdir = '<head>
	<base href="<?php echo rexseo42::getBaseUrl(); ?>" />
	<title><?php echo rexseo42::getTitle(); ?></title>
	<meta name="description" content="<?php echo rexseo42::getDescription(); ?>" />
	<meta name="keywords" content="<?php echo rexseo42::getKeywords(); ?>" />
	<meta name="robots" content="<?php echo rexseo42::getRobotRules();?>" />
	<link rel="canonical" href="<?php echo rexseo42::getCanonicalUrl(); ?>" />
</head>';
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('rexseo42_setup_step3'); ?></h2>
	<div class="rex-area-content">
		<p class="info-msg"><?php echo $I18N->msg('rexseo42_setup_step3_msg1'); ?></p>
		<div id="code-example"><?php rex_highlight_string($codeExample); ?></div>
		<div id="code-example-subdir"><?php rex_highlight_string($codeExampleSubdir); ?></div>
		<p class="info-msg no-bottom-margin"><?php echo $I18N->msg('rexseo42_setup_codeexamples'); ?></p>
	</div>
</div>

<style type="text/css">
#code-example-subdir,
#code-example {
	display: none;
}

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

#rex-page-rexseo42 #www_redirect {
    margin-top: 8px;
}
</style>

<script type="text/javascript">
jQuery(document).ready( function() {
	jQuery('#settings-form').submit(function() {
		var pat = /^https?:\/\//i;
		var serverString = jQuery('#server').val();
		var slashPosAfterDomain = serverString.indexOf("/", 8); // https:// = 8

		if (pat.test(serverString) && slashPosAfterDomain !== -1 && (serverString.charAt(serverString.length - 1) == '/')) {
			return true;
		}

		alert('<?php echo $I18N->msg('rexseo42_setup_url_alert'); ?>');

		return false;
	});

	jQuery('#mod_rewrite').click(function () {
		var thisCheck = jQuery(this);
		
		if (!thisCheck.is(':checked')) 	{
			alert("<?php echo $I18N->msg('rexseo42_setup_mod_rewrite_alert', $REX['ADDON']['name']['rexseo42']); ?>");
		}
	});

	<?php if (file_exists($htaccessRoot)) { ?>
	jQuery('#copy-file-submit').click(function(e) {
		if (!confirm("<?php echo $I18N->msg('rexseo42_setup_htaccess_alert'); ?>")) {
			e.preventDefault();
		}
	});
	<?php } ?>

	jQuery('#server').keyup(function() {
		updateCodeExample();
	});

	updateCodeExample();
});

function updateCodeExample() {
	var pat = /^https?:\/\//i;
	var hasSubdir = false;
	var url = jQuery('#server').val();
	var slashPosAfterDomain = url.indexOf("/", 8); // https:// = 8

	if (pat.test(url) && slashPosAfterDomain !== -1) {
		var subdir = url.substr(slashPosAfterDomain + 1);
		if (subdir !== '') {
			hasSubdir = true;
		}
	}

	if (hasSubdir) {
		jQuery('#code-example').hide();
		jQuery('#code-example-subdir').show();
	} else {
		jQuery('#code-example-subdir').hide();
		jQuery('#code-example').show();
	}
}
</script>

