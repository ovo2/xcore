<?php

class rexseo42_utils {
	static function init($params) {
		global $REX;

		if ($REX['MOD_REWRITE']) {
			// includes
			require_once($REX['INCLUDE_PATH'] . '/addons/rexseo42/classes/class.rexseo_rewrite.inc.php');

			// init globals
			rexseo42::init();

			// init rewriter 
			$rewriter = new RexseoRewrite($REX['ADDON']['rexseo42']['settings']['levenshtein'], $REX['ADDON']['rexseo42']['settings']['rewrite_params']);
			$rewriter->resolve();

			// init current article
			rexseo42::initArticle($REX['ARTICLE_ID']);

			// rewrite ep 
			rex_register_extension('URL_REWRITE', array ($rewriter, 'rewrite'));
		}

		// controller
		include($REX['INCLUDE_PATH'] . '/addons/rexseo42/controller.inc.php');

		// rexseo post init
		rex_register_extension_point('REXSEO_INCLUDED');
	}

	static function afterDBImport($params) {
		global $REX, $I18N;

		$sqlStatement = 'SELECT seo_title, seo_description, seo_keywords, seo_custom_url, seo_canonical_url, seo_noindex, seo_ignore_prefix FROM ' . $REX['TABLE_PREFIX'] . 'article';
		$sql = rex_sql::factory();
		$sql->setQuery($sqlStatement);

		// check for db fields
		if ($sql->getRows() == 0) {
			require($REX['INCLUDE_PATH'] . '/addons/rexseo42/install.inc.php');
			echo rex_info($I18N->msg('rexseo42_dbfields_readded', $REX['ADDON']['name']['rexseo42']));
			echo rex_info($I18N->msg('rexseo42_dbfields_readded_check_setup', $REX['ADDON']['name']['rexseo42']));
		}
	}

	static function showMsgAfterClangModified($params) {
		global $I18N, $REX;

		echo rex_info($I18N->msg('rexseo42_check_lang_msg', $REX['ADDON']['name']['rexseo42']));
	}

	static function addSEOPageToPageContentMenu($params) {
		global $I18N;
			
		$class = "";

		if ($params['mode']  == 'seo') {
			$class = 'class="rex-active"';
		}

		$seoLink = '<a '.$class.' href="index.php?page=content&amp;article_id=' . $params['article_id'] . '&amp;mode=seo&amp;clang=' . $params['clang'] . '&amp;ctype=' . rex_request('ctype') . '">' . $I18N->msg('rexseo42_seopage_linktext') . '</a>';
		array_splice($params['subject'], '-2', '-2', $seoLink);

		return $params['subject'];
	}

	static function addSEOPageToPageContentOutput($params) {
		global $REX, $I18N;

		if ($params['mode']  == 'seo') {
			include($REX['INCLUDE_PATH'] . '/addons/rexseo42/pages/seopage.inc.php');
		}
	}

	static function modifyFrontendLinkInPageContentMenu($params) {
		$lastElement = count($params['subject']) - 1;
		$params['subject'][$lastElement] = preg_replace("/(?<=href=(\"|'))[^\"']+(?=(\"|'))/", '../', $params['subject'][$lastElement]);

		return $params['subject'];
	}

	static function includeWebsiteSpecificConfigFile() {
		global $REX;

		$websiteSpecificConfigFile = self::getWebsiteSpecificConfigFile();

		if (file_exists($websiteSpecificConfigFile)) {
			include_once($websiteSpecificConfigFile);
		} else {
			$REX['ADDON']['rexseo42']['settings']['robots'] = '';
		}
	}

	static function getWebsiteSpecificConfigFile() {
		global $REX;

		return $REX['GENERATED_PATH'] . '/files/rexseo_settings.php';
	}

	static function sanitizeString($string) {
		return trim(preg_replace("/\s\s+/", " ", $string));
	}

	static function sanitizeUrl($url) {
		return preg_replace('@^https?://|/.*|[^\w.-]@', '', $url);
	}
}
