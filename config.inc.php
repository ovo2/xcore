<?php
/**
 * RexSEO - URLRewriter Addon
 *
 * @link https://github.com/gn2netwerk/rexseo
 *
 * @author dh[at]gn2-netwerk[dot]de Dave Holloway
 * @author code[at]rexdev[dot]de jdlx
 *
 * Based on url_rewrite Addon by
 * @author markus.staab[at]redaxo[dot]de Markus Staab
 *
 * @package redaxo 4.3.x/4.4.x
 * @version 1.5.0
 */

// ADDON PARAMS
////////////////////////////////////////////////////////////////////////////////
$myself = 'rexseo_lite';
$myroot = $REX['INCLUDE_PATH'].'/addons/'.$myself;

$REX['ADDON'][$myself]['VERSION'] = array
(
'VERSION'      => 1,
'MINORVERSION' => 5,
'SUBVERSION'   => 0,
);

$REX['ADDON']['rxid'][$myself]        = '0';
$REX['ADDON']['name'][$myself]        = 'RexSEO Lite';
$REX['ADDON']['version'][$myself]     = '1.0.0';
$REX['ADDON']['author'][$myself]      = 'Markus Staab, Wolfgang Huttegger, Dave Holloway, Jan Kristinus, jdlx, RexDude ;)';
$REX['ADDON']['supportpage'][$myself] = 'forum.redaxo.de';
$REX['ADDON']['perm'][$myself]        = $myself.'[]';
$REX['PERM'][]                        = $myself.'[]';
$REX['ADDON'][$myself]['SUBPAGES']    = array (
  array ('',          'Einstellungen'),
  array ('setup',      'Setup'),
  array ('help',      'Hilfe')
  );
$REX['ADDON'][$myself]['debug_log']   = 0;
$REX['ADDON'][$myself]['settings']['default_redirect_expire'] = 60;
$REX['PROTOCOL'] = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';


// INCLUDES
////////////////////////////////////////////////////////////////////////////////
require_once($myroot . '/functions/function.rexseo_helpers.inc.php');
require_once($myroot . '/classes/class.rexseo_lite.inc.php');

// USER SETTINGS
////////////////////////////////////////////////////////////////////////////////
// --- DYN
$REX["ADDON"]["rexseo_lite"]["settings"] = array (
  'first_run' => 0,
  'alert_setup' => 0,
  'install_subdir' => '',
  'url_whitespace_replace' => '-',
  'compress_pathlist' => 1,
  'title_schema' => '%B - %S',
  'url_schema' => 'rexseo',
  'url_ending' => '.html',
  'homeurl' => 1,
  'allow_articleid' => 0,
  'levenshtein' => 0,
  'robots' => '',
  'expert_settings' => 1,
  'def_desc' => 
  array (
  ),
  'def_keys' => 
  array (
  ),
  'homelang' => 0,
  'rewrite_params' => 0,
  'hide_langslug' => 0,
  'urlencode' => 0,
  'one_page_mode' => 0,
);
// --- /DYN

$REX["ADDON"]["rexseo_lite"]["settings"]['params_starter']  = '++';

// RUN ON ADDONS INLCUDED
////////////////////////////////////////////////////////////////////////////////
if(!$REX['SETUP']){
  rex_register_extension('ADDONS_INCLUDED','rexseo_init');
}

if(!function_exists('rexseo_init')){
  function rexseo_init($params)
  {
    global $REX;

    if ($REX['MOD_REWRITE'] !== false)
    {
      // REWRITE
      $levenshtein    = (bool) $REX['ADDON']['rexseo_lite']['settings']['levenshtein'];
      $rewrite_params = (bool) $REX['ADDON']['rexseo_lite']['settings']['rewrite_params'];

      require_once $REX['INCLUDE_PATH'].'/addons/rexseo_lite/classes/class.rexseo_rewrite.inc.php';

      $rewriter = new RexseoRewrite($levenshtein,$rewrite_params);
      $rewriter->resolve();

      rex_register_extension('URL_REWRITE', array ($rewriter, 'rewrite'));
    }

    // CONTROLLER
    include $REX['INCLUDE_PATH'].'/addons/rexseo_lite/controller.inc.php';

    // REXSEO POST INIT
    rex_register_extension_point('REXSEO_POST_INIT');

  }
}

// SEOPAGE
////////////////////////////////////////////////////////////////////////////////

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
			include($REX['INCLUDE_PATH'] . '/addons/rexseo_lite/pages/seopage.inc.php');
		}
	});
}

?>
