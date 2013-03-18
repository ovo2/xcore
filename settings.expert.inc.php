<?php

// if false seo database fields won't be dropped if rexseo42 will be uninstalled. perhaps someday interesting when updateing rexseo42...
$REX['ADDON']['rexseo42']['settings']['drop_dbfields_on_uninstall'] = true; 

// character to replace whitespaces with in urls
$REX['ADDON']['rexseo42']['settings']['url_whitespace_replace']  = '-'; 

// if true seo page will be only visible in start article of website. also the frontend links will all point to start article and in sitemap.xml will show up only one url
$REX['ADDON']['rexseo42']['settings']['one_page_mode'] = false;  

// only set to true if you want't to have urls wth special chars like in chinese langauge etc.
$REX['ADDON']['rexseo42']['settings']['urlencode'] = false; 

// 0 = don't allow article_id urls | 1 = allow and 301 redirect to "real" urls | 2 = just allow (not recommended!)
$REX['ADDON']['rexseo42']['settings']['allow_articleid'] = 0;

// default title delimeter (including whitespace chars) for seperating name of website and page title
$REX['ADDON']['rexseo42']['settings']['title_delimeter'] = ' | ';

// default follow flag for robots meta tag, can be empty
$REX['ADDON']['rexseo42']['settings']['robots_follow_flag'] = 'follow';

// default archive flag for robots meta tag, can be empty
$REX['ADDON']['rexseo42']['settings']['robots_archive_flag'] = 'noarchive';

// don't change this! otherwise the seo god won't be happy ;)
$REX['ADDON']['rexseo42']['settings']['levenshtein'] = false;
$REX['ADDON']['rexseo42']['settings']['rewrite_params']  = false;
$REX['ADDON']['rexseo42']['settings']['params_starter']  = '++';




