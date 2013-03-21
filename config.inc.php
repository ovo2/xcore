<?php
$myself = 'rexseo42';
$myroot = $REX['INCLUDE_PATH'] . '/addons/' . $myself;

$REX['ADDON']['rxid'][$myself] = '0';
$REX['ADDON']['name'][$myself] = 'REXSEO42';
$REX['ADDON']['version'][$myself] = '1.0.42 BETA';
$REX['ADDON']['author'][$myself] = 'Markus Staab, Wolfgang Huttegger, Dave Holloway, Jan Kristinus, jdlx, RexDude';
$REX['ADDON']['supportpage'][$myself] = 'forum.redaxo.de';
$REX['ADDON']['perm'][$myself] = $myself . '[]';

$REX['PERM'][] = 'rexseo42[]';
$REX['EXTPERM'][] = 'rexseo42[seopage]';

// includes
require($myroot . '/functions/function.rexseo_helpers.inc.php');
require($myroot . '/classes/class.rexseo42.inc.php');
require($myroot . '/settings.dyn.inc.php');
require($myroot . '/settings.expert.inc.php');
require($myroot . '/settings.lang.inc.php');

// init
if (!$REX['SETUP']) {
	rex_register_extension('ADDONS_INCLUDED','rexseo_init', '', REX_EXTENSION_EARLY);
}

function rexseo_init($params) {
	global $REX;

	if ($REX['MOD_REWRITE'] !== false) {
		// rewrite
		$levenshtein    = (bool) $REX['ADDON']['rexseo42']['settings']['levenshtein'];
		$rewrite_params = (bool) $REX['ADDON']['rexseo42']['settings']['rewrite_params'];

		require_once $REX['INCLUDE_PATH'].'/addons/rexseo42/classes/class.rexseo_rewrite.inc.php';

		$rewriter = new RexseoRewrite($levenshtein,$rewrite_params);
		$rewriter->resolve();
		
		// init helper class
		rexseo42::init();

		// rewrite ep 
		rex_register_extension('URL_REWRITE', array ($rewriter, 'rewrite'));
	}

	// controller
	include $REX['INCLUDE_PATH'] . '/addons/rexseo42/controller.inc.php';

	// rexseo post init
	rex_register_extension_point('REXSEO_INCLUDED');
}


// seo page
if ($REX['REDAXO']) {
	// add lang file
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
			rex_register_extension('PAGE_CONTENT_MENU', function ($params) {
				global $I18N;
			
				$class = "";

				if ($params['mode']  == 'seo') {
					$class = 'class="rex-active"';
				}

				$seoLink = '<a '.$class.' href="index.php?page=content&amp;article_id=' . $params['article_id'] . '&amp;mode=seo&amp;clang=' . $params['clang'] . '&amp;ctype=' . rex_request('ctype') . '">' . $I18N->msg('rexseo42_seopage_linktext') . '</a>';
				array_splice($params['subject'], '-2', '-2', $seoLink);

				return $params['subject'];
			});

			// include seo page
			rex_register_extension('PAGE_CONTENT_OUTPUT', function ($params) {
				global $REX, $I18N;

				if ($params['mode']  == 'seo') {
					include($REX['INCLUDE_PATH'] . '/addons/rexseo42/pages/seopage.inc.php');
				}
			});
		}
	}

	// for one page mode link to frontend is always "../"
	if ($REX['ADDON']['rexseo42']['settings']['one_page_mode'] && $REX['ARTICLE_ID'] != $REX['START_ARTICLE_ID']) {
		rex_register_extension('PAGE_CONTENT_MENU', function ($params) {
			$lastElement = count($params['subject']) - 1;
			$params['subject'][$lastElement] = preg_replace("/(?<=href=(\"|'))[^\"']+(?=(\"|'))/", '../', $params['subject'][$lastElement]);

			return $params['subject'];
		});
	}

	// check for missing db field after db import
	if (!$REX['SETUP']) {
		rex_register_extension('A1_AFTER_DB_IMPORT', 'rexseo_afterDBImport');
	}

	// if clang is added/deleted show message to the user that he should check his lang settings
	rex_register_extension('CLANG_ADDED', 'rexseo_showMsgAfterClangModified');
	rex_register_extension('CLANG_DELETED', 'rexseo_showMsgAfterClangModified');
}

