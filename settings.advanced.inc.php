<?php

// if true you get full urls like wordpress has :) rexseo42::getUrlStart() and co. needs to be used consequently for all extra urls (like mediapool urls, etc.)
$REX['ADDON']['rexseo42']['settings']['full_urls'] = false;

// url start piece for all urls spit out rex_getUrl(), rexseo42::getUrlStart() and co. should to be used for all extra urls | can be "/" (recommended, no base tag needed) or "./" (base tag needed!)
$REX['ADDON']['rexseo42']['settings']['url_start'] = '/';

// url start piece for redaxo subdir installations | can be "./" (recommended, base tag needed!)
$REX['ADDON']['rexseo42']['settings']['url_start_subdir'] = './';

// if false seo database fields won't be dropped if rexseo42 will be uninstalled. perhaps someday interesting when updateing rexseo42...
$REX['ADDON']['rexseo42']['settings']['drop_dbfields_on_uninstall'] = true; 

// if true rexseo42::getImageManagerUrl() and rexseo42::getImageTag() will produce seo friendly urls
$REX['ADDON']['rexseo42']['settings']['seo_friendly_image_manager_urls'] = true;

// default title delimeter (including whitespace chars) for seperating name of website and page title
$REX['ADDON']['rexseo42']['settings']['title_delimeter'] = ' | ';

// if true seopage will be only visible at start article of website. also the frontend links will all point to start article and sitemap.xml will show only one url
$REX['ADDON']['rexseo42']['settings']['one_page_mode'] = false;  

// only set to true if you want't to have urls wth special chars like in chinese language etc.
$REX['ADDON']['rexseo42']['settings']['urlencode'] = false; 

// 0 = don't allow article_id urls, show 404 error article | 1 = allow and 301 redirect to non-article_id urls | 2 = just allow both (not recommended!)
$REX['ADDON']['rexseo42']['settings']['allow_articleid'] = 0;

// character to replace whitespaces with in urls
$REX['ADDON']['rexseo42']['settings']['url_whitespace_replace']  = '-'; 

// default follow flag for robots meta tag, can be empty
$REX['ADDON']['rexseo42']['settings']['robots_follow_flag'] = 'follow';

// default archive flag for robots meta tag, can be empty
$REX['ADDON']['rexseo42']['settings']['robots_archive_flag'] = 'noarchive';

// if true pages with similar urls will be accepted (not recommended!)
$REX['ADDON']['rexseo42']['settings']['levenshtein'] = false;

// if true parameters will be rewritten to ++/param1/value1/param2/value2 (not recommended!)
$REX['ADDON']['rexseo42']['settings']['rewrite_params']  = false;

// only for rewrite_params settings: start param rewrite with this string
$REX['ADDON']['rexseo42']['settings']['params_starter']  = '++';




