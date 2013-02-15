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

$sql = new rex_sql();
//$sql->debugsql = true;
$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'article` DROP `seo_title`, DROP `seo_description`, DROP `seo_keywords`, DROP `seo_url`, DROP `seo_noindex`, DROP `seo_ignore_prefix`');

rex_generateAll();

$REX['ADDON']['install']['rexseo42'] = 0;
?>
