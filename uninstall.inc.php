<?php
if ($REX["ADDON"]["rexseo42"]["settings"]['drop_dbfields_on_uninstall']) {
	$sql = new rex_sql();
	//$sql->debugsql = true;
	$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'article` DROP `seo_title`, DROP `seo_description`, DROP `seo_keywords`, DROP `seo_url`, DROP `seo_noindex`, DROP `seo_ignore_prefix`');
} else {
	echo rex_info('Die Datenbankfelder wurden NICHT entfernt.');
}

rex_generateAll();

$REX['ADDON']['install']['rexseo42'] = 0;
?>
