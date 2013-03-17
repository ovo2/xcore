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
$REX['ADDON']['rexseo42']['settings']['url_whitespace_replace']  = '-'; // character to replace whitespaces with
$REX['ADDON']['rexseo42']['settings']['one_page_mode'] = false;  // if true seo page will be only visible in start article of website. also the frontend links will all point to start article
$REX['ADDON']['rexseo42']['settings']['urlencode'] = false; // only set to true if you have urls in chinese etc.
$REX['ADDON']['rexseo42']['settings']['allow_articleid'] = 0; // 0 = don't allow article_id urls | 1 = allow and 301 redirects to rewritten urls | 2 = just allow (not recommended!)
$REX['ADDON']['rexseo42']['settings']['drop_dbfields_on_uninstall'] = true; // if false seo database fields won't be dropped if rexseo42 will be uninstalled

// don't change this! otherwise the seo god won't be happy ;)
$REX['ADDON']['rexseo42']['settings']['levenshtein'] = false;
$REX['ADDON']['rexseo42']['settings']['rewrite_params']  = false;
$REX['ADDON']['rexseo42']['settings']['params_starter']  = '++';




