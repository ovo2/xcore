<?php

$myself = 'rexseo42';
$myroot = $REX['INCLUDE_PATH'] . '/addons/' . $myself;

// register addon
$REX['ADDON']['rxid'][$myself] = '0';
$REX['ADDON']['name'][$myself] = 'REXSEO42';
$REX['ADDON']['version'][$myself] = '1.0.42 BETA';
$REX['ADDON']['author'][$myself] = 'Markus Staab, Wolfgang Huttegger, Dave Holloway, Jan Kristinus, jdlx, RexDude';
$REX['ADDON']['supportpage'][$myself] = 'forum.redaxo.de';
$REX['ADDON']['perm'][$myself] = $myself . '[]';

// permissions
$REX['PERM'][] = 'rexseo42[]';
$REX['EXTPERM'][] = 'rexseo42[seopage]';

// includes
require($myroot . '/classes/class.rexseo42.inc.php');
require($myroot . '/classes/class.rexseo42_utils.inc.php');

require($myroot . '/settings.dyn.inc.php');
require($myroot . '/settings.expert.inc.php');
require($myroot . '/settings.lang.inc.php');

// init
if (!$REX['SETUP']) {
	rex_register_extension('ADDONS_INCLUDED','rexseo42_utils::init', '', REX_EXTENSION_EARLY);
}

if ($REX['REDAXO']) {
	// append lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/rexseo42/lang/');

	// subpages
	$REX['ADDON'][$myself]['SUBPAGES'] = array(
		array('', $I18N->msg('rexseo42_settings')),
		array('tools', $I18N->msg('rexseo42_tools')),
		array('setup', $I18N->msg('rexseo42_setup')),
		array('help', $I18N->msg('rexseo42_help'))
	);

	// check for user permissions
	if (isset($REX['USER']) && ($REX['USER']->isAdmin() || $REX['USER']->hasPerm('rexseo42[seopage]'))) {
		// react on one_page_mode option
		if (!$REX['ADDON']['rexseo42']['settings']['one_page_mode'] || ($REX['ADDON']['rexseo42']['settings']['one_page_mode'] && $REX['ARTICLE_ID'] == $REX['START_ARTICLE_ID'])) {
			// add new menu item
			rex_register_extension('PAGE_CONTENT_MENU', 'rexseo42_utils::addSEOPageToPageContentMenu');

			// include seo page
			rex_register_extension('PAGE_CONTENT_OUTPUT', 'rexseo42_utils::addSEOPageToPageContentOutput');
		}
	}

	// for one page mode link to frontend is always "../"
	if ($REX['ADDON']['rexseo42']['settings']['one_page_mode'] && $REX['ARTICLE_ID'] != $REX['START_ARTICLE_ID']) {
		rex_register_extension('PAGE_CONTENT_MENU', 'rexseo42_utils::modifyFrontendLinkInPageContentMenu');
	}

	// check for missing db field after db import
	if (!$REX['SETUP']) {
		rex_register_extension('A1_AFTER_DB_IMPORT', 'rexseo42_utils::afterDBImport');
	}

	// if clang is added/deleted show message to the user that he should check his lang settings
	rex_register_extension('CLANG_ADDED', 'rexseo42_utils::showMsgAfterClangModified');
	rex_register_extension('CLANG_DELETED', 'rexseo42_utils::showMsgAfterClangModified');
}

