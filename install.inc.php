<?php
/**
 * RexSEO - URLRewriter Addon
 *
 * @link https://github.com/gn2netwerk/rexseo
 *
 * @author dh[at]gn2-netwerk[dot]de Dave Holloway
 * @author code[at]rexdev[dot]de jdlx
 *
 * Based on url_rewrite Addon by
 * @author markus.staab[at]redaxo[dot]de Markus Staab
 *
 * @package redaxo 4.3.x/4.4.x
 * @version 1.5.0
 */

// INSTALL SETTINGS
////////////////////////////////////////////////////////////////////////////////
$myself            = 'rexseo_lite';
$myroot            = $REX['INCLUDE_PATH'].'/addons/'.$myself;

$minimum_REX       = '4.5.0';
$minimum_PHP       = 5;
$disable_addons    = array('url_rewrite');

$error             = array();

// CHECK REDAXO VERSION
////////////////////////////////////////////////////////////////////////////////
if(version_compare($REX['VERSION'].'.'.$REX['SUBVERSION'].'.'.$REX['MINORVERSION'], $minimum_REX, '<'))
{
  $error[] = 'Dieses Addon ben&ouml;tigt Redaxo Version '.$minimum_REX.' oder h&ouml;her.';
}


// CHECK PHP VERSION
////////////////////////////////////////////////////////////////////////////////
if(version_compare(PHP_VERSION, $minimum_PHP, '<'))
{
  $error[] = 'Dieses Addon ben&ouml;tigt mind. PHP '.$minimum_PHP.'!';
}

// CHECK ADDONS TO DISABLE
////////////////////////////////////////////////////////////////////////////////
foreach($disable_addons as $a)
{
  if (OOAddon::isInstalled($a) || OOAddon::isAvailable($a))
  {
    $error[] = 'Addon "'.$a.'" muß erst deinstalliert werden.  <span style="float:right;">[ <a href="index.php?page=addon&addonname='.$a.'&uninstall=1">'.$a.' de-installieren</a> ]</span>';
  }
}


if(count($error)==0)
{
  // SETUP DB FIELDS
  //////////////////////////////////////////////////////////////////////////////
  $sql = new rex_sql();
  //$sql->debugsql = true;
  $sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'article` ADD `seo_title` TEXT, ADD `seo_description` TEXT, ADD `seo_keywords` TEXT, ADD `seo_url` TEXT, ADD `seo_noindex` VARCHAR(1), ADD `seo_ignore_prefix` VARCHAR(1)');

  // delete cache
  rex_generateAll();

  // done!
  $REX['ADDON']['install'][$myself] = 1;
}
else
{
  $REX['ADDON']['installmsg'][$myself] = '<br />'.implode($error,'<br />');
  $REX['ADDON']['install'][$myself] = 0;
}

?>
