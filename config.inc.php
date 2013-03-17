<?php
$myself = 'rexseo42';
$myroot = $REX['INCLUDE_PATH'] . '/addons/' . $myself;

$REX['ADDON']['rxid'][$myself] = '0';
$REX['ADDON']['name'][$myself] = 'REXSEO42';
$REX['ADDON']['version'][$myself] = '1.0.1';
$REX['ADDON']['author'][$myself] = 'Markus Staab, Wolfgang Huttegger, Dave Holloway, Jan Kristinus, jdlx, RexDude';
$REX['ADDON']['supportpage'][$myself] = 'forum.redaxo.de';
$REX['ADDON']['perm'][$myself] = $myself . '[]';
$REX['PERM'][] = $myself . '[]';

// subpages
$REX['ADDON'][$myself]['SUBPAGES'] = array(
	array('', 'Einstellungen'),
	array('setup', 'Setup'),
	array('help', 'Hilfe')
);

// includes
require_once($myroot . '/functions/function.rexseo_helpers.inc.php');
require_once($myroot . '/classes/class.rexseo42.inc.php');
require_once($myroot . '/settings.inc.php');

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

		rex_register_extension('URL_REWRITE', array ($rewriter, 'rewrite'));
	}

	// controller
	include $REX['INCLUDE_PATH'] . '/addons/rexseo42/controller.inc.php';

	// rexseo post init
	rex_register_extension_point('REXSEO_POST_INIT');
}


// seo page
if ($REX['REDAXO']) {
	// add new menu item
	rex_register_extension('PAGE_CONTENT_MENU', function ($params) {
		$class = "";

		if ($params['mode']  == 'seo') {
			$class = 'class="rex-active"';
		}

		$seoLink = '<a '.$class.' href="index.php?page=content&amp;article_id=' . $params['article_id'] . '&amp;mode=seo&amp;clang=' . $params['clang'] . '&amp;ctype=' . rex_request('ctype') . '">SEO</a>';
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

