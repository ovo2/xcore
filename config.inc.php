<?php

// register addon
$REX['ADDON']['rxid']['seo42'] = '0';
$REX['ADDON']['name']['seo42'] = 'SEO42';
$REX['ADDON']['version']['seo42'] = '3.5.0 DEV';
$REX['ADDON']['author']['seo42'] = 'Markus Staab, Wolfgang Huttegger, Dave Holloway, Jan Kristinus, jdlx, RexDude';
$REX['ADDON']['supportpage']['seo42'] = 'forum.redaxo.de';
$REX['ADDON']['perm']['seo42'] = 'seo42[]';

// permissions
$REX['PERM'][] = 'seo42[]';
$REX['PERM'][] = 'seo42[tools_only]';
$REX['PERM'][] = 'seo42[redirects_only]';
$REX['EXTPERM'][] = 'seo42[seo_default]';
$REX['EXTPERM'][] = 'seo42[seo_extended]';
$REX['EXTPERM'][] = 'seo42[url_default]';

// consts
define('SEO42_SETTINGS_FILE', $REX['INCLUDE_PATH'] . '/data/addons/seo42/settings.inc.php');
define('REXSEO_PATHLIST', $REX['GENERATED_PATH'] . '/files/rexseo_pathlist.php'); // uses new rex var introduced in REDAXO 4.5

// includes
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.res42.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.nav42.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.seo42.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.seo42_utils.inc.php');

// default settings
$REX['ADDON']['seo42']['settings']['url_ending'] = '.html';
$REX['ADDON']['seo42']['settings']['hide_langslug'] = 0;
$REX['ADDON']['seo42']['settings']['homeurl'] = 2;
$REX['ADDON']['seo42']['settings']['homelang'] = 0;
$REX['ADDON']['seo42']['settings']['auto_redirects'] = 0;
$REX['ADDON']['seo42']['settings']['css_dir'] = "/resources/css/";
$REX['ADDON']['seo42']['settings']['js_dir'] = "/resources/js/";
$REX['ADDON']['seo42']['settings']['images_dir'] = "/resources/images/";
$REX['ADDON']['seo42']['settings']['send_header_x_ua_compatible'] = "1";
$REX['ADDON']['seo42']['settings']['rewriter'] = true; // if true url rewriter for pretty, seo friendly urls will be active (recommended!).
$REX['ADDON']['seo42']['settings']['seopage'] = true; // if true seo page will be shown for articles. for non admins user right seo_default has to be given too.
$REX['ADDON']['seo42']['settings']['urlpage'] = true; // if true url page will be shown for articles. for non admins user right url_default has to be given too.
$REX['ADDON']['seo42']['settings']['title_preview'] = true; // hides the title preview in seopage if false. only necessary if a different title schema is used and therefore title preview is unwanted
$REX['ADDON']['seo42']['settings']['no_prefix_checkbox'] = false; // hides the no prefix/suffix checkbox in seopage if false. only necessary if a different title schema is used and therefore no prefix/suffix checkbox is needed
$REX['ADDON']['seo42']['settings']['custom_canonical_url'] = false; // if true user can change canonical url via seo page. please use this only if you exactly know what you are doing or know that your redaxo users and admins exactly know what they are doing ;)
$REX['ADDON']['seo42']['settings']['noindex_checkbox'] = false; // if true a noindex checkbox in seopage will be shown so that user will be able to set noindex robots flag for his articles
$REX['ADDON']['seo42']['settings']['pagerank_checker'] = true; // ATTENTION: only set to true if your website is live and domain of website should be indexed by google! if true page rank checker tool will be shown in tools section.
$REX['ADDON']['seo42']['settings']['all_url_types'] = true; // if true alls available url types will be shown in select box in url page. set to false to hide url types that need to be treated in navigation code
$REX['ADDON']['seo42']['settings']['full_urls'] = false; // if true you get full urls like in wordpress :) seo42::getUrlStart() and co. needs to be used consequently for all extra urls (like urls to media files, etc.) | url_start option will be ignored by this
$REX['ADDON']['seo42']['settings']['smart_redirects'] = true; // if true smart redirects like domain.de/foo/bar/ -> domain.de/foo/bar.html etc. will be enabled. default is false at it is still a experimental feature
$REX['ADDON']['seo42']['settings']['redirects_allow_regex'] = false; // if true you can use * for the old URL and {number} for the new URL -> Old URL: /DE-*/ New URL: /{1}/
$REX['ADDON']['seo42']['settings']['remove_root_cats_for_categories'] = ''; // array with category ids. all articles in this categoryies will get urltype "remove root cat". but only for articles added after setting was made.
$REX['ADDON']['seo42']['settings']['no_url_for_categories'] = ''; // array with category ids. all articles in this categories will get urltype "no url". but only for articles added after setting was made!
$REX['ADDON']['seo42']['settings']['title_delimiter'] = '-'; // default title delimiter (including whitespace chars) for seperating name of website and page title
$REX['ADDON']['seo42']['settings']['include_query_params'] = true; // if true query params will be added to canonical url and rel alternate tags, but only if certain params not in ignore_query_params array
$REX['ADDON']['seo42']['settings']['ignore_query_params'] = ''; // array with query params that indicate a canonical url. possible notation: foo, foo=bar
$REX['ADDON']['seo42']['settings']['url_start'] = '/'; // url start piece for all urls returned from rex_getUrl(), seo42::getUrlStart() and co.
$REX['ADDON']['seo42']['settings']['url_start_subdir'] = './'; // for redaxo subdir installations: url start piece for all urls returned from rex_getUrl(), seo42::getUrlStart() and co.
$REX['ADDON']['seo42']['settings']['seo_friendly_image_manager_urls'] = true; // if true seo42::getImageManagerFile() and seo42::getImageTag() will produce seo friendly urls
$REX['ADDON']['seo42']['settings']['one_page_mode'] = false; // if true seopage will be only visible at start article of website. also the frontend links will all point to start article and sitemap.xml will show only one url
$REX['ADDON']['seo42']['settings']['ignore_root_cats'] = false; // if true root categories will be completly ignored and not be visible in generated urls (experimental)
$REX['ADDON']['seo42']['settings']['url_whitespace_replace']  = '-'; // character to replace whitespaces with in urls
$REX['ADDON']['seo42']['settings']['robots_txt_auto_disallow'] = false; // if noindex flag is set for article you can choose also to automatically add a disallow statement to robots.txt. this is not recommended because then the url will normally end up in the index. noindex meta tag only is the better choice
$REX['ADDON']['seo42']['settings']['robots_follow_flag'] = 'follow'; // default follow flag for robots meta tag, can be empty
$REX['ADDON']['seo42']['settings']['robots_archive_flag'] = 'noarchive'; // default archive flag for robots meta tag, can be empty
$REX['ADDON']['seo42']['settings']['static_sitemap_priority'] = true; // if true website startarticle will have 1.0, all other articles will have 0.8 priority. if false priority gets calculated by category level.
$REX['ADDON']['seo42']['settings']['force_download_for_filetypes'] = ''; // you can force download of certain filetypes. put file in files dir, add filetype to array e.g. 'pdf' and link to file like this: /download/foo.pdf or use seo42::getDownloadFile($file)
$REX['ADDON']['seo42']['settings']['fix_image_manager_cache_control_header'] = false; // cache control header for image manager files is normally set through .htaccess, but on some servers (1und1) it won't be set correctly so we do it manually
$REX['ADDON']['seo42']['settings']['drop_dbfields_on_uninstall'] = true; // if false seo database fields won't be dropped if seo42 will be uninstalled. perhaps someday interesting when updateing seo42...
$REX['ADDON']['seo42']['settings']['debug_article_id']  = $REX['START_ARTICLE_ID']; // used to control which article should be used for debug output in help section, default is $REX['START_ARTICLE_ID']
$REX['ADDON']['seo42']['settings']['allowed_domains'] = ''; // allowed domains for page rank checker
$REX['ADDON']['seo42']['settings']['global_special_chars'] = '';
$REX['ADDON']['seo42']['settings']['global_special_chars_rewrite'] = '';
$REX['ADDON']['seo42']['settings']['urlencode_lowercase'] = false;
$REX['ADDON']['seo42']['settings']['urlencode_whitespace_replace']  = '_';
$REX['ADDON']['seo42']['settings']['lang'][0]['code'] = 'de';
$REX['ADDON']['seo42']['settings']['lang'][0]['original_name'] = 'deutsch';
$REX['ADDON']['seo42']['settings']['lang'][0]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][0]['special_chars'] = 'Ä|ä|Ö|ö|Ü|ü|ß|&';
$REX['ADDON']['seo42']['settings']['lang'][0]['special_chars_rewrite'] = 'Ae|ae|Oe|oe|Ue|ue|ss|und';

// overwrite default settings with user settings
if (file_exists(SEO42_SETTINGS_FILE)) {
	require_once(SEO42_SETTINGS_FILE);
}

// convert to real arrays
$REX['ADDON']['seo42']['settings']['remove_root_cats_for_categories'] = explode(',', $REX['ADDON']['seo42']['settings']['remove_root_cats_for_categories']);
$REX['ADDON']['seo42']['settings']['no_url_for_categories'] = explode(',', $REX['ADDON']['seo42']['settings']['no_url_for_categories']);
$REX['ADDON']['seo42']['settings']['ignore_query_params'] = explode(',', $REX['ADDON']['seo42']['settings']['ignore_query_params']);
$REX['ADDON']['seo42']['settings']['force_download_for_filetypes'] = explode(',', $REX['ADDON']['seo42']['settings']['force_download_for_filetypes']);

// robots settings (can be different when website manager is installed)
seo42_utils::includeRobotsSettings();

// fix for iis webserver: set request uri manually if not available
seo42_utils::requestUriFix();

// do redirect for frontend if necessary
if (!$REX['REDAXO']) {
	seo42_utils::redirect();
}

// init
if (!$REX['SETUP']) {
	// auto mod rewrite, but not for redaxo system page
	if ($REX['REDAXO'] && rex_request('page') == 'specials') {
		// don't touch mod rewrite var
	} else {
		if ($REX['ADDON']['seo42']['settings']['rewriter']) {
			$REX['MOD_REWRITE'] = true;
		} else {
			$REX['MOD_REWRITE'] = false;
		}
	}

	// init seo42
	rex_register_extension('ADDONS_INCLUDED', 'seo42_utils::init', '', REX_EXTENSION_EARLY);

	// send additional headers if necessary
	rex_register_extension('OUTPUT_FILTER_CACHE', 'seo42_utils::sendHeaders');
}

if ($REX['REDAXO']) {
	// append lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/seo42/lang/');

	// handels ajax request for google pagerank checker in tools section
	if ($REX['ADDON']['seo42']['settings']['pagerank_checker'] && isset($REX['USER']) && rex_request('function') == 'getpagerank') {
		require($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.google_pagerank_checker.inc.php');
		echo GooglePageRankChecker::getRank(rex_request('url'));
		exit;
	}

	// subpages
	if (isset($REX['USER']) && !$REX['USER']->isAdmin() && ($REX['USER']->hasPerm('seo42[tools_only]') || $REX['USER']->hasPerm('seo42[redirects_only]'))) {
		// add subpages for non admin users
		if ($REX['USER']->hasPerm('seo42[tools_only]')) {
			// add tools page only
			$REX['ADDON']['seo42']['SUBPAGES'][] = array('tools', $I18N->msg('seo42_tools'));
		}

		if ($REX['USER']->hasPerm('seo42[redirects_only]')) {
			// add redirects page only
			$REX['ADDON']['seo42']['SUBPAGES'][] = array('redirects', $I18N->msg('seo42_redirects'));
		}

		$REX['ADDON']['seo42']['SUBPAGES'][0][0] = '';
	} else {
		// add subpages
		$REX['ADDON']['seo42']['SUBPAGES'] = array(
			array('', $I18N->msg('seo42_start')),
			array('tools', $I18N->msg('seo42_tools')),
			array('redirects', $I18N->msg('seo42_redirects'))
		);

		// plugins (will be autoloaded incl. language file)
		$plugins = OOPlugin::getAvailablePlugins('seo42');

		for ($i = 0; $i < count($plugins); $i++) {
            if (file_exists($REX['INCLUDE_PATH'] . '/addons/seo42/plugins/' . $plugins[$i] . '/pages/' . $plugins[$i]) ) {
				$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/seo42/plugins/' . $plugins[$i] . '/lang/'); // make msg for subpage available at this point 
				array_push($REX['ADDON']['seo42']['SUBPAGES'], array($plugins[$i], $I18N->msg('seo42_' . $plugins[$i])));
            }
		}

		// rest of sub pages
		array_push($REX['ADDON']['seo42']['SUBPAGES'], 
			array('settings', $I18N->msg('seo42_settings')),
			array('setup', $I18N->msg('seo42_setup')),
			array('help', $I18N->msg('seo42_help'))
		);
	}

	// add css/js files to page header
	if (rex_request('page') == 'seo42') {
		rex_register_extension('PAGE_HEADER', 'seo42_utils::appendToPageHeader');
	}

	// check if seopage/urlpage needs to be enabled
	if (!$REX['ADDON']['seo42']['settings']['one_page_mode'] || ($REX['ADDON']['seo42']['settings']['one_page_mode'] && $REX['ARTICLE_ID'] == $REX['START_ARTICLE_ID'])) {
		if (isset($REX['USER']) && ($REX['USER']->isAdmin())) {
			// admins get everything :)

			if (!$REX['ADDON']['seo42']['settings']['one_page_mode']) { // url page not needed when in one page mode
				seo42_utils::enableURLPage(); // injection order is important
			}

			seo42_utils::enableSEOPage();
		} else {
			if (isset($REX['USER']) && $REX['USER']->hasPerm('seo42[url_default]')) {
				seo42_utils::enableURLPage();
			}

			if (isset($REX['USER']) && ($REX['USER']->hasPerm('seo42[seo_default]') || $REX['USER']->hasPerm('seo42[seo_extended]') || $REX['USER']->hasPerm('editContentOnly[]'))) {
				seo42_utils::enableSEOPage();
			}
		}
	}

	// fix article preview link as othewise not url types will show correct preview url
	rex_register_extension('PAGE_CONTENT_MENU', 'seo42_utils::fixArticlePreviewLink');

	// check for missing db fields after db import
	if (!$REX['SETUP']) {
		rex_register_extension('A1_AFTER_DB_IMPORT', 'seo42_utils::afterDBImport');
	}

	// if clang is added/deleted show message to the user that he should check his lang settings
	rex_register_extension('CLANG_ADDED', 'seo42_utils::showMsgAfterClangModified');
	rex_register_extension('CLANG_DELETED', 'seo42_utils::showMsgAfterClangModified');
	rex_register_extension('CLANG_UPDATED', 'seo42_utils::showMsgAfterClangModified');

	// don't clone seo data when new clang is added
	rex_register_extension('CLANG_ADDED', 'seo42_utils::emptySEODataAfterClangAdded');

	// inform user when article hat different url type
	if (rex_request('page') == 'content' && rex_request('mode') == 'edit' && rex_request('function') == '') {
		rex_register_extension('PAGE_CONTENT_OUTPUT', 'seo42_utils::showUrlTypeMsg');
	}

	// handle remove_root_cats_for_categories option
	if (count($REX['ADDON']['seo42']['settings']['remove_root_cats_for_categories']) > 0) {
		rex_register_extension('ART_ADDED', 'seo42_utils::addRemoveRootCatUrlType');
		rex_register_extension('CAT_ADDED', 'seo42_utils::addRemoveRootCatUrlType');
	}

	// handle no_url_for_categories option
	if (count($REX['ADDON']['seo42']['settings']['no_url_for_categories']) > 0) {
		rex_register_extension('ART_ADDED', 'seo42_utils::addNoUrlType');
		rex_register_extension('CAT_ADDED', 'seo42_utils::addNoUrlType');
	}
} else {
	// init res42 class
	rex_register_extension('ADDONS_INCLUDED', 'res42::init');

	// send additional headers for article if necessary
	rex_register_extension('OUTPUT_FILTER_CACHE', 'seo42_utils::sendHeadersForArticleOnly');

	// fix headers for image manager images if necessary
	if ($REX['ADDON']['seo42']['settings']['fix_image_manager_cache_control_header'] && (isset($_GET['rex_img_type']))) {
		header('Cache-Control: max-age=604800'); // 1 week
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 604800));
	}
}

