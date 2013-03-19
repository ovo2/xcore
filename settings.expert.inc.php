<?php

// if false seo database fields won't be dropped if rexseo42 will be uninstalled. perhaps someday interesting when updateing rexseo42...
$REX['ADDON']['rexseo42']['settings']['drop_dbfields_on_uninstall'] = true; 

// if true rexseo42::getImageManagerUrl() and rexseo42::getImageTag() will produce seo friendly urls
$REX['ADDON']['rexseo42']['settings']['seo_friendly_image_manager_urls'] = true;

// default title delimeter (including whitespace chars) for seperating name of website and page title
$REX['ADDON']['rexseo42']['settings']['title_delimeter'] = ' | ';

// if true seo page will be only visible in start article of website. also the frontend links will all point to start article and sitemap.xml will show up only one url
$REX['ADDON']['rexseo42']['settings']['one_page_mode'] = false;  

// only set to true if you want't to have urls wth special chars like in chinese language etc.
$REX['ADDON']['rexseo42']['settings']['urlencode'] = false; 

// 0 = don't allow article_id urls | 1 = allow and 301 redirect to non-article_id urls | 2 = just allow both (not recommended!)
$REX['ADDON']['rexseo42']['settings']['allow_articleid'] = 0;

// character to replace whitespaces with in urls
$REX['ADDON']['rexseo42']['settings']['url_whitespace_replace']  = '-'; 

// default follow flag for robots meta tag, can be empty
$REX['ADDON']['rexseo42']['settings']['robots_follow_flag'] = 'follow';

// default archive flag for robots meta tag, can be empty
$REX['ADDON']['rexseo42']['settings']['robots_archive_flag'] = 'noarchive';

// default protocol for base/server url, can also be https:// 
$REX['ADDON']['rexseo42']['settings']['server_protocol'] = 'http://';

// if true pages with similar urls will be accepted (not recommended!)
$REX['ADDON']['rexseo42']['settings']['levenshtein'] = false;

// if true parameters will be rewritten to ++/param1/value1/param2/value2 (not recommended!)
$REX['ADDON']['rexseo42']['settings']['rewrite_params']  = false;

// only for rewrite_params settings: start param rewrite with this string
$REX['ADDON']['rexseo42']['settings']['params_starter']  = '++';




