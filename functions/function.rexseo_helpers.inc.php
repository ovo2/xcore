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
		echo rex_info($I18N->msg('rexseo42_dbfields_readded_check_setup', $REX['ADDON']['name']['rexseo42']));
	}
}

function rexseo_showMsgAfterClangModified($params) {
	global $I18N, $REX;

	echo rex_info($I18N->msg('rexseo42_check_lang_msg', $REX['ADDON']['name']['rexseo42']));
}

