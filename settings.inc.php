<?php

// --- DYN
$REX["ADDON"]["rexseo42"]["settings"] = array (
  'install_subdir' => '',
  'url_schema' => 'rexseo',
  'url_ending' => '.html',
  'homeurl' => 1,
  'allow_articleid' => 0,
  'robots' => '',
  'homelang' => 0,
  'hide_langslug' => 0,
  'one_page_mode' => 0,
);
// --- /DYN

$REX["ADDON"]["rexseo42"]["settings"]['urlencode'] = 0; // 0 = Zeichenersetzung per lang Datei | 1 = Kodierung per urlencode
$REX["ADDON"]["rexseo42"]["settings"]['compress_pathlist'] = 1;
$REX["ADDON"]["rexseo42"]["settings"]['url_whitespace_replace']  = '-';
$REX["ADDON"]["rexseo42"]["settings"]['drop_dbfields_on_uninstall'] = true; // switch to false to maintain all rexseo42 db fields on uninstall

// don' alter this, it's bad seo!
$REX["ADDON"]["rexseo42"]["settings"]['levenshtein'] = false;
$REX["ADDON"]["rexseo42"]["settings"]['rewrite_params']  = false;
$REX["ADDON"]["rexseo42"]["settings"]['params_starter']  = '++';


