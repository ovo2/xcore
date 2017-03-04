<?php

// includes
require_once(rex_path::addon('xcore', 'functions/functions.php')); // contains out() function which is very useful for debugging purpose

// init main class
rexx::init();

// activate controller
if (rexx::isFrontend()) {
	$controller = new rexx_controller();
	$controller->route();
}

// smart redirects
if (rex_config::get('xcore', 'smart_redirects') == 1 && rexx::isFrontend()) {
	rex_extension::register('PACKAGES_INCLUDED', function() {	
		if (!rexx::isCurrentUrlValid() && isset($_SERVER['REQUEST_URI'])) {
			$trimmedRequestUrl = str_replace('.html', '', trim($_SERVER['REQUEST_URI'], '/'));
			$newUrl = $trimmedRequestUrl . rexx::getUrlEnding();

			if (rexx::isUrlValid($newUrl)) {
				rexx::redirect(rexx::getUrlStart() . $newUrl);
			}
		}
	}, rex_extension::LATE);
}

// set correct locale
rexx::setLocale();

// add x-core customs styles
if (rexx::isBackend()) {
	rex_extension::register('PACKAGES_INCLUDED', function(rex_extension_point $ep) {
		rex_view::addJsFile($this->getAssetsUrl('js/main.js'));
		rex_view::addCssFile($this->getAssetsUrl('css/backend.css'));

		if (rex_config::get('xcore', 'xcore_styles') == 1) {
			rex_view::addCssFile($this->getAssetsUrl('css/xcore.css'));
		}
	}, rex_extension::LATE);


	rex_extension::register('PAGE_BODY_ATTR', function (\rex_extension_point $ep) {
	    $subject = $ep->getSubject();
	
		if (rex_config::get('xcore', 'xcore_styles') == 1 && rex_config::get('be_style/customizer', 'labelcolor') == '#43a047') {
		    $subject['class'][] = 'rexx-customizer-is-green';
		}

		if (rex_config::get('xcore', 'show_meta_frontend_link') == 1) {
		    $subject['class'][] = 'rexx-has-meta-frontend-link';
		}

	    $ep->setSubject($subject);
	});
}

// multiupload: undo deactivate mediapool pages done by multiupload addon
if (rex_config::get('xcore', 'show_multiupload_pages') == 1 && rexx::isBackend()) {
	if (rex_addon::get('multiupload')->isAvailable()) {
		rex_extension::register('PAGES_PREPARED', function() {
			$page = rex_be_controller::getPageObject('mediapool/upload');

			if ($page instanceof rex_be_page) {
				$page->setHidden(false);
				$page->setHasLayout(true);
				$page->setSubPath(rex_path::addon('mediapool', 'pages/upload.php'));
			}

			$page = rex_be_controller::getPageObject('mediapool/sync');

			if ($page instanceof rex_be_page) {
				$page->setHidden(false);
			}
		}, rex_extension::LATE);
	}
}

// yrewrite: add own schema
rex_yrewrite::setScheme(new rexx_yrewrite_scheme());

// logo anti flicker patch
if (rexx::isBackend()) {
	rex_extension::register('OUTPUT_FILTER', function($ep) {
		$subject = $ep->getSubject();

		if (rex::getUser() instanceof rex_user) {
			$newLogo = 'redaxo-logo_logged_in.svg';
		} else {
			$newLogo = 'redaxo-logo_logged_out.svg';
	
		}
	
		$subject = str_replace('../assets/core/redaxo-logo.svg', $this->getAssetsUrl('images/' . $newLogo), $subject);

		// setup msg in addon install page
		if (rex_be_controller::getCurrentPagePart(1) == 'packages') {
			$subject = str_replace(rex_i18n::msg('addon_installed', 'xcore'), rex_i18n::msg('addon_installed', 'xcore') . ' <br/>' . rex_i18n::rawMsg('xcore_addon_installed'), $subject);
		}
		
		return $subject;
	}, rex_extension::LATE);
}

// redirect sync dir of developer to project addon
if (rex_config::get('xcore', 'developer_project_sync') == 1) {
	if (rex_addon::get('developer')->isAvailable()) {
		rex_extension::register('DEVELOPER_MANAGER_START', array('rexx_developer_manager', 'start'), rex_extension::NORMAL, [], true);
	}
}

// send headers
if (rexx::isFrontend()) {
	// this is only for current article
	rex_extension::register('RESPONSE_SHUTDOWN', function() {
		header('X-UA-Compatible: IE=Edge');	// html tag not necessary anymore with this
	});

	// fix headers for media manager images, necessary for some 1und1 servers and others
	if (rex_get('rex_media_file') != '' && rex_get('rex_media_type') != '') {
		header('Cache-Control: max-age=604800'); // 1 week
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 604800));
	}
}

// show offline 404 message for frontend user
if (rex_config::get('xcore', 'offline_404_mode') == 1 && rexx::isFrontend()) {
	rex_extension::register('PACKAGES_INCLUDED', function(rex_extension_point $ep) {
		$article = rexx::getCurrentArticle();

		if (!$article->isOnline() && $article->getId() != rex_article::getNotfoundArticleId()) {
			if (rex_backend_login::createUser()) {
				rex_extension::register('OUTPUT_FILTER', function($ep) {
					$subject = $ep->getSubject();

					$insert = '
						<!-- X-CORE Offline 404 Mode -->
						<style type="text/css">
							html { margin-top: 30px !important; }
							body { position: relative; }
							#rexx-offline-404-frontend-msg { font-family: Arial, sans-serif; font-size: 13px; color: white; background: #4b9ad9; border: 0; position: fixed; left: 0; right: 0; top: 0; padding: 0; text-align: center; z-index: 100; height: 30px; line-height: 30px; box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6) !important; }
							#rexx-logo { background: #4b9ad9 url("/assets/addons/xcore/images/redaxo-logo_logged_in.svg") no-repeat left top; height: 16px; position: absolute; left: 12px; top: 7px; width: 115px; }
						</style>
						<div id="rexx-offline-404-frontend-msg"><strong>' . rex_i18n::msg('xcore_offline_404_frontend_msg1') . '</strong> ' . rex_i18n::msg('xcore_offline_404_frontend_msg2') . '<div id="rexx-logo"></div></div>
						<!-- X-CORE Offline 404 Mode -->' .  PHP_EOL;

					return str_replace('</body>', $insert . '</body>', $subject);
				});
			} else {
				rex_addon::get('structure')->setProperty('article_id', rexx::getNotfoundArticleId());

				rex_extension::register('RESPONSE_SHUTDOWN', function() {
					header("HTTP/1.0 404 Not Found");
				});
			}
		}
	}, rex_extension::LATE);
}

// correct redaxo behaviour and send 404 if sitestartarticle = notfoundarticle
if (rexx::isFrontend()) {
	if (rexx::getSiteStartArticleId() == rexx::getNotfoundArticleId()) {
		rex_extension::register('PACKAGES_INCLUDED', function(rex_extension_point $ep) {
			if (!rexx::isCurrentUrlValid()) {
				rex_extension::register('RESPONSE_SHUTDOWN', function() {
					header("HTTP/1.0 404 Not Found");
				});
			}
		});
	}
}

// add docs to api_docs addon if available
if (rexx::isBackend() && rex_addon::get('api_docs')->isAvailable()) {
	rex_extension::register('API_DOCS', function(rex_extension_point $ep) {
		$subject = $ep->getSubject();

		if (isset($subject['api']['links'])) {
			$subject['api']['links'][] = [
				'title' => rex_i18n::msg('xcore_api_docs_title'),
				'description' => rex_i18n::msg('xcore_api_docs_description'),
				'href' => rex_url::backendPage('xcore/rexx_api'),
				'open_in_new_window' => false
			];
		}

		$ep->setSubject($subject);
	});
}

if (rexx::isBackend() && $this->getConfig('show_meta_frontend_link') == 1) {
	rex_extension::register('META_NAVI', function(rex_extension_point $ep) {
		$subject = $ep->getSubject();

		$subject[] = '<li><a href="' . rex_url::frontend() . '" target="_blank"><i class="rex-icon fa-globe"></i> ' . rex_i18n::msg('xcore_goto_website') . '</a></li>';

		$ep->setSubject($subject);
	});
}

// xcore included ep
rex_extension::registerPoint(new rex_extension_point('XCORE_INCLUDED'));

