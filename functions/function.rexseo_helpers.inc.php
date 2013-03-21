<?php

function rexseo_sanitizeString($string) {
	return trim(preg_replace("/\s\s+/", " ", $string));
}

function rexseo_afterDBImport($params) {
	global $REX, $I18N;

	$sqlStatement = 'SELECT seo_title, seo_description, seo_keywords, seo_url, seo_noindex, seo_ignore_prefix FROM ' . $REX['TABLE_PREFIX'] . 'article';
	$sql = rex_sql::factory();
	$sql->setQuery($sqlStatement);

	// check for db fields
	if ($sql->getRows() == 0) {
		require($REX['INCLUDE_PATH'] . '/addons/rexseo42/install.inc.php');
		echo rex_info($I18N->msg('rexseo42_dbfields_readded', $REX['ADDON']['name']['rexseo42']));
		echo rex_warning($I18N->msg('rexseo42_dbfields_readded_check_template', $REX['ADDON']['name']['rexseo42']));
	}
}

function rexseo_showMsgAfterClangModified($params) {
	global $I18N, $REX;

	echo rex_info($I18N->msg('rexseo42_check_langcodes_msg', $REX['ADDON']['name']['rexseo42']));
}

function rexseo_subdir() {
	global $REX;

	$path_diff = $REX['REDAXO'] ? array('index.php','redaxo'):array('index.php');
	$install_subdir = array_diff_assoc(array_reverse(explode('/',trim($_SERVER['SCRIPT_NAME'],'/'))),$path_diff);
	$rexseo_subdir = count($install_subdir)>0 ? implode('/',array_reverse($install_subdir)).'/' :'';

	return $rexseo_subdir;
}

