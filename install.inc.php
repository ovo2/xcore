<?php
$myself = 'rexseo42';
$myroot = $REX['INCLUDE_PATH'].'/addons/'.$myself;
$error = array();

// check redaxo version
if (version_compare($REX['VERSION'] . '.' . $REX['SUBVERSION'] . '.' . $REX['MINORVERSION'], '4.4.1', '<=')) {
  $error[] = 'Dieses Addon ben&ouml;tigt Redaxo Version 4.5.0 oder h&ouml;her.';
}

// check php version
if (version_compare(PHP_VERSION, 5, '<')) {
  $error[] = 'Dieses Addon ben&ouml;tigt mind. PHP '.$minimum_PHP.'!';
}

// check for concurrent addons
$disable_addons = array('url_rewrite', 'yrewrite', 'rexseo');

foreach ($disable_addons as $a) {
  if (OOAddon::isInstalled($a) || OOAddon::isAvailable($a)) {
    $error[] = 'Addon "'.$a.'" muß erst deinstalliert werden.  <span style="float:right;">[ <a href="index.php?page=addon&addonname='.$a.'&uninstall=1">'.$a.' de-installieren</a> ]</span>';
  }
}

// setup seo db fields
if (count($error) == 0) {
  $sql = new rex_sql();
  //$sql->debugsql = true;
  $sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'article` ADD `seo_title` TEXT, ADD `seo_description` TEXT, ADD `seo_keywords` TEXT, ADD `seo_url` TEXT, ADD `seo_noindex` VARCHAR(1), ADD `seo_ignore_prefix` VARCHAR(1)');

  // delete cache
  rex_generateAll();

  // done!
  $REX['ADDON']['install'][$myself] = 1;
} else {
  $REX['ADDON']['installmsg'][$myself] = '<br />'.implode($error,'<br />');
  $REX['ADDON']['install'][$myself] = 0;
}

