<?php

// --- DYN
$REX['ADDON']['rexseo42']['settings']['install_subdir'] = '';
$REX['ADDON']['rexseo42']['settings']['url_schema'] = 'rexseo';
$REX['ADDON']['rexseo42']['settings']['url_ending'] = '.html';
$REX['ADDON']['rexseo42']['settings']['hide_langslug'] = 0;
$REX['ADDON']['rexseo42']['settings']['homeurl'] = 1;
$REX['ADDON']['rexseo42']['settings']['homelang'] = 0;
$REX['ADDON']['rexseo42']['settings']['robots'] = '';
// --- /DYN

// expert settings
$REX['ADDON']['rexseo42']['settings']['url_whitespace_replace']  = '-';
$REX['ADDON']['rexseo42']['settings']['one_page_mode'] = false;
$REX['ADDON']['rexseo42']['settings']['urlencode'] = false; // use this if you have urls in chinese etc.
$REX['ADDON']['rexseo42']['settings']['allow_articleid'] = 0; // 0 = don't allow article_id urls | 1 = allow and 301 redirects to rewritten urls | 2 = just allow (not recommended!)
$REX['ADDON']['rexseo42']['settings']['drop_dbfields_on_uninstall'] = true;

// don't change this, it's bad seo if you do!
$REX['ADDON']['rexseo42']['settings']['levenshtein'] = false;
$REX['ADDON']['rexseo42']['settings']['rewrite_params']  = false;
$REX['ADDON']['rexseo42']['settings']['params_starter']  = '++';




