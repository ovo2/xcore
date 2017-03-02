<?php

// init main class
rexx::init();

// add some useful functions like out() 
require_once(rex_path::addon('xcore', 'functions/functions.php'));

// activate controller
if (rexx::isFrontend()) {
	$controller = new rexx_controller();
	$controller->route();
}

// smart redirects
if (rexx::isFrontend()) {
	rex_extension::register('PACKAGES_INCLUDED', function() {	
		if (isset($_SERVER['REQUEST_URI'])) {
			$requestUrl = ltrim($_SERVER['REQUEST_URI'], '/');
			$trimmedRequestUrl = str_replace('.html', '', rtrim($requestUrl, '/'));
			$urlEnding = rex_config::get('xcore', 'url_ending');
			$newUrl = $trimmedRequestUrl . $urlEnding;

			if ($requestUrl != '' && $requestUrl != $newUrl) {
				// check if url really exists
				array_walk_recursive(rex_yrewrite::$paths, function($item, $key) use ($newUrl) {
					if ($item == $newUrl) {
						$urlStart = rex_config::get('xcore', 'url_start');
						rexx::redirect($urlStart . $newUrl);
					}
				});
			
			}
		}
	}, rex_extension::LATE);
}

// add x-core customs styles
if (rexx::isBackend()) {
	rex_extension::register('PACKAGES_INCLUDED', function(rex_extension_point $ep) {
		rex_view::addCssFile($this->getAssetsUrl('css/style.css'));
		rex_view::addJsFile($this->getAssetsUrl('js/main.js'));
	}, rex_extension::LATE);
}

// multiupload: undo deactivate mediapool pages done by multiupload addon
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
		
		return $subject;
	}, rex_extension::LATE);
}

// redirect sync dir of developer to project addon
if (rex_addon::get('developer')->isAvailable()) {
    rex_extension::register('DEVELOPER_MANAGER_START', array('rexx_developer_manager', 'start'), rex_extension::NORMAL, [], true);
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

// xcore included ep
rex_extension::registerPoint(new rex_extension_point('XCORE_INCLUDED'));

