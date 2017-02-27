<?php

class rexx extends rex {
	static $titleDelimeter;
	static $urlStart;
	static $cssDir;
	static $jsDir;
	static $imageDir;
	static $favIconsDir;

	const mediaTypesDir = 'mediatypes';
	const defaultRobotsArchiveFlag = 'noarchive';

	public static function init() {	
		self::$titleDelimeter = rex_config::get('xcore', 'title_delimeter');
		self::$urlStart = rex_config::get('xcore', 'url_start');
		self::$cssDir = rex_config::get('xcore', 'css_dir');
		self::$jsDir = rex_config::get('xcore', 'js_dir');
		self::$imageDir = rex_config::get('xcore', 'image_dir');
		self::$favIconsDir = rex_config::get('xcore', 'favicon_dir');

		rexx_resource_includer::init(self::$cssDir, self::$jsDir, self::$imageDir, self::$favIconsDir);
	}

	public static function isFrontend() {
		return !rexx::isBackend();
	}	

	public static function getCSSDir() {
		return self::$cssDir;
	}

	public static function getJSDir() {
		return self::$jsDir;
	}

	public static function getImageDir() {
		return self::$imageDir;
	}

	public static function getFavIconDir() {
		return self::$favIconsDir;
	}

	public static function getLangCode() {
		$curClang = rexx::getCurrentClang();

		return $curClang->getCode();
	}

	public static function getLangCount() {
		return count(rex_clang::getAll());
	}

	public static function getBaseUrl() {
		return rexx::getServer();
	}

	public static function getTitle($websiteName = '') {
		if ($websiteName == '') {
			$websiteName = rexx::getWebsiteName();
		}
		
		if (rexx::isSiteStartArticle()) {
			$fullTitle = $websiteName . ' ' . rexx::getTitleDelimiter() . ' ' . rexx::getTitlePart();
		} else {
			$fullTitle = rexx::getTitlePart() . ' ' . rexx::getTitleDelimiter() . ' ' . $websiteName;
		}

		return htmlspecialchars($fullTitle);
	}

	public static function isSiteStartArticle() {
		if (rexx::getSiteStartArticleId() == rexx::getCurrentArticleId()) {
			return true;
		} else {
			return false;
		}
	}

	public static function getWebsiteName() {
		return rexx::getServerName();
	}

	public static function getTitleDelimiter() {
		return self::$titleDelimeter;
	}

	public static function getTitlePart() {
		$curArticle = rexx::getCurrentArticle();

		if ($curArticle->getValue('yrewrite_title') != '') {
			return $curArticle->getValue('yrewrite_title'); 
		} else {
			return $curArticle->getValue('name');
        }
	}

	public static function getDescription() {
		$curArticle = rexx::getCurrentArticle();
		$description = $curArticle->getValue('yrewrite_description');
		$description = str_replace(["\n","\r"], [' ',''], $description);

		return $description;
	}

	public static function getKeywords() {
		return ''; // nothing there yet
	}

	public static function getRobotRules() {
		$curArticle = rexx::getCurrentArticle();

	     if ($curArticle->getValue('yrewrite_index') == 1 || ($curArticle->getValue('yrewrite_index') == 0 && $curArticle->isOnline())) {
            return 'index, follow, ' . self::defaultRobotsArchiveFlag;
        } else {
            return 'noindex, follow, ' . self::defaultRobotsArchiveFlag;
        }
	}

	public static function getCanonicalUrl() {
		$seo = new rex_yrewrite_seo();

		return $seo->getCanonicalUrl();
	}

	public static function getUrlStart() {
		return self::$urlStart;
	}

	public static function getUrlEnding() {
		return rexx::$urlEnding;
	}

	public static function getLangTags() {
		$seo = new rex_yrewrite_seo();

		return $seo->getHreflangTags() . PHP_EOL;
	}

	public static function getUrl($id = null, $clang = null, array $params = [], $separator = '&amp;') {
		return rex_getUrl($id, $clang, $params, $separator);
	}

	public static function getFullUrl($id = null, $clang = null, array $params = [], $separator = '&amp;') {
		return rexx::getServerUrl() . rexx::getTrimmedUrl($id, $clang, $params, $separator);
	}

	public static function getTrimmedUrl($id = null, $clang = null, array $params = [], $separator = '&amp;') {
		return ltrim(rexx::getUrl($id, $clang, $params, $separator), "./");
	}

	public static function getTrackingCode() {
		return rex_global_settings::getDefaultValue('tracking_code', true);
	}

	public static function getString($field, $clangId = null, $allowEmpty = false) {
		return rex_global_settings::getValue($field, $clangId, $allowEmpty);
	}

	public static function getMediaTypeDescription($mediaType) {
		$query = 'SELECT * FROM '. rexx::getTablePrefix() .'media_manager_type WHERE name LIKE "' . $mediaType . '"';

		$sql = rex_sql::factory();
		$sql->setQuery($query);

		if ($sql->getRows() > 0) {
			return $sql->getValue('description');
		} else {
			return $mediaType;
		}
	}

	public static function getHtmlAttribute($attribute, $value) {
		if ($value != '' || $attribute == 'alt') {
			return ' ' . $attribute . '="' . htmlspecialchars($value) . '"';
		} else {
			return '';
		}
	}
	
	public static function includeTemplate($templateId) {
		$template = new rex_template($templateId);
		
		if ($template instanceof rex_template) {
			include_once($template->getFile());
		}
	}
	
	public static function getArticleContent($articleId, $ctypeId = -1) {
		$article = new rex_article_content($articleId);
		
		return $article->getArticle($ctypeId); 
	}
	
	public static function getCurrentArticleContent($ctypeId = -1) {
		return rexx::getArticleContent(rexx::getCurrentArticleId(), $ctypeId); 
	}
	
	public static function getArticleLink($articleId) {
		return '<a href="' . rexx::getUrl($articleId) . '">' . rexx::getArticleName($articleId) . '</a>';
	}

	public static function getBackendUrl(array $params = [], $escape = true) {
		return rex_url::backendController($params, $escape);
	}

	public static function getArticleName($articleId) {
		$article = rexx::getArticle($articleId);

		if (rexx::isValidArticle($article)) {
			return $article->getName();
		} else {
			return 'Article with ID = ' . $articleId . ' not found.';
		}
	}

	public static function getCurrentArticleName() {
		return rexx::getArticleName(rexx::getCurrentArticleId());
	}

	public static function isValidArticle($article) {
		if ($article instanceof rex_article) {
			return true;
		} else {
			return false;
		}
	}

	public static function getCurrentArticleId() {
		return rex_article::getCurrentId();
	}

	public static function getCurrentArticle() {
		return rex_article::getCurrent();
	}

	public static function getCurrentCategoryId() {
		return rex_category::getCurrentId();
	}

	public static function getCurrentCategory() {
		return rex_category::getCurrent();
	}

	public static function getCurrentParentCategoryId() {
		$parentCategory = rexx::getCurrentParentCategory();

		return $category->getId();
	}

	public static function getCurrentParentCategory() {
		$category = rexx::getCurrentCategory();

		return $category->getParent();
	}

	public static function getArticle($articleId) {
		return rex_article::get($articleId);
	}

	public static function getServerUrl() {
		return rexx::getServer();
	}

	public static function getSiteStartArticleId() {
		return rex_article::getSiteStartArticleId();
	}

	public static function getCurrentClang() {
		return rex_clang::getCurrent();
	}

	public static function getCurrentClangId() {
		return rex_clang::getCurrentId();
	}

	public static function getUrlString($string) {
		$scheme = new rex_xcore_yrewrite_scheme();

		return $scheme->normalize($string, rexx::getCurrentClangId());
	}

	public static function getMedia($filename) {
		return rex_media::get($filename);
	}

	public static function isMediaValid($media) {
		if ($media instanceof rex_media) {
			return true;
		} else {
			return false;
		}
	}

	public static function getMediaDir() {
		return rex_url::media();
	}

	public static function getMediaFile($file) {
		if ($file == '') {
			return '';
		} else {
			return rexx::getMediaDir() . $file;
		}
	}

	public static function getAbsoluteMediaFile($file) {
		return rexx::getAbsolutePath(rexx::getMediaDir()) . $file;
	}

	public static function getAbsolutePath($path) {
		return rex_path::frontend() . trim($path, "/") . '/';
	}

	public static function getAbsoluteFile($file) {
		return rex_path::frontend() . trim($file, "/");
	}

	public static function getFullMediaUrl($file) {
		return rexx::getServerUrl() . rexx::getMediaDirName() . '/' . $file;
	}

	public static function getMediaDirName() {
		return trim(rexx::getMediaDir(), "/");
	}

	public static function getMediaManagerFile($mediaFile, $mediaType, $validHtml = true) {
		$url = '';

		if (rexx::isBackend()) {
			$url = rex_url::backendController() . '?rex_media_type=' . $mediaType . '&rex_media_file=' . $mediaFile;
		} else {
			$url = rexx::getUrlStart() . self::mediaTypesDir . '/' . $mediaType . '/' . $mediaFile;
		}

		if ($validHtml) {
			return htmlspecialchars($url);
		} else {
			return $url;
		}
	}

	public static function getLastUpdateDate($format = 'd.m.Y') {
		$query =  'SELECT updatedate FROM ' . rexx::getTablePrefix() . 'article WHERE updatedate <> 0 ORDER BY updatedate DESC LIMIT 1';

		$sql = rex_sql::factory();
		$sql->setQuery($query);

		return date($format, strtotime($sql->getValue('updatedate')));
	}

	public static function getGlobalJSVars($jsVars = []) {
		$out = '';

		foreach ($jsVars as $jsVar => $jsValue) {
			$globalVar = rex_global_settings::getDefaultValue($jsValue);
			
			if (is_numeric($globalVar)) {
				$out .= 'var ' . $jsVar . ' = ' . $globalVar . '; ';
			} else {
				$out .= 'var ' . $jsVar . ' = "' . $globalVar . '"; ';
			}
		}

		return $out;
	}

	public static function getMediaListAsArray($mediaList) {
		return explode(',', $mediaList);
	}

	public static function redirectToArticle($id, $clang = null, array $params = [], $statusCode = 301) {
		rexx::redirectToUrl(rexx::getFullUrl($id, $clang, $params, '&'), $statusCode);
	}

	public static function redirectToUrl($url, $statusCode = 301) {
	    while (@ob_end_clean());
		header('Location: ' . $url, true, $statusCode);
		die();
	}

	public static function redirect($url, $statusCode = 301) {
		rexx::redirectToUrl($url, $statusCode);
	}

	public static function getCurrentArticleAttribute($attributeValues, $attributeType = 'id') {
		$curArticleId = rexx::getCurrentArticleId();

		if (isset($attributeValues[$curArticleId])) {
			return ' ' . $attributeType . '="' . $attributeValues[$curArticleId] . '"';
		} else {
			return '';
		}		
	}

	public static function isOdd($int) {
		return ($int & 1);
	}

	// converts a form with fieldsets into tabs :)
	public static function getTabbedForm($form) {
		$form = str_replace('"selected', '" selected', $form); // bugfix mform
		$form = str_replace('"checked', '" checked', $form); // bugfix mform
		$html = rexx_simple_html_dom::str_get_html($form);
		$tabs = [];

		if (is_object($html)) {
			$fieldsets = $html->find('fieldset');

			foreach($fieldsets as $fieldset) {
				$legend = $fieldset->find('legend', 0);
				$tabName = $legend->innertext;
				$legend->outertext = '';

				$tabs[] = ['name' => $tabName, 'content' => $fieldset->innertext];
			}
		}

		$tabControl = '';
		$tabIdPrefix = 'tab-';

		$tabControl .= '<div class="mform">';
		$tabControl .= '<ul class="nav nav-tabs" role="tablist">';

		for ($i = 0; $i < count($tabs); $i++) {
			if ($i == 0) {
				$class = 'active';
			} else {
				$class = '';
			}

			$tabControl .= '<li role="presentation" class="' . $class . '"><a href="#' . $tabIdPrefix . $i . '" aria-controls="' . $tabIdPrefix . $i . '" role="tab" data-toggle="tab">' . $tabs[$i]['name'] . '</a></li>';
		}

		$tabControl .= '</ul>';
		$tabControl .= '<div class="tab-content">';

		for ($i = 0; $i < count($tabs); $i++) {
			if ($i == 0) {
				$class = 'active';
			} else {
				$class = '';
			}

			$tabControl .= '<div role="tabpanel" class="tab-pane ' . $class . '" id="' . $tabIdPrefix . $i . '">' . $tabs[$i]['content'] . '</div>';
		}

		$tabControl .= '</div>';
		$tabControl .= '</div>';

		// add js for persistant tabs (tabs keep position after reload )
		$tabControl .= "
			<script type=\"text/javascript\">
				$('a[data-toggle=\"tab\"]').on(\"shown.bs.tab\", function (e) {
					var id = $(e.target).attr(\"href\");
					localStorage.setItem('selectedTab', id)
				});

				var selectedTab = localStorage.getItem('selectedTab');

				if (selectedTab != null) {
					$('a[data-toggle=\"tab\"][href=\"' + selectedTab + '\"]').tab('show');
				}
			</script>";

		return $tabControl;
	}

	public static function getArrayFromRexValue($value) {
		return rex_var::toArray($value);
	}

	public static function getMediaManagerImageWidth($mediaFile, $mediaType) {
		return rex_media_manager::create($mediaType, $mediaFile)->getMedia()->getImageWidth();
    }

	public static function getMediaManagerImageHeight($mediaFile, $mediaType) {
		return rex_media_manager::create($mediaType, $mediaFile)->getMedia()->getImageHeight();
    }

	public static function prettyPrintVar($varTitle, $varValue, $icon = 'fa-info-circle') {
		$out = '';

		if ($icon == '') {
			$iTag = '';
		} else {
			$iTag = '<i class="rex-icon ' . $icon . '"></i> ';
		}

		$out .= '<div class="pretty-var-box">';
		$out .= $iTag . '<strong>' . $varTitle . ':</strong> ' . $varValue;
		$out .= '</div>';

		return $out;
	}

	public static function isArticleSliceValid($slice) {
		return rexx::isSliceValid($slice);
	}

	public static function isSliceValid($slice) {
		if ($slice instanceof rex_article_slice) {
			return true;
		} else {
			return false;
		}
	}

	public static function isArticleValid($article) {
		if ($article instanceof rex_article) {
			return true;
		} else {
			return false;
		}
	}

	public static function isCategoryValid($category) {
		if ($category instanceof rex_category) {
			return true;
		} else {
			return false;
		}
	}

	public static function getStrippedString($string, $removeParagraphs = true) {
		if ($removeParagraphs) {
			$string = str_replace(['<p>', '</p>'], '', $string);
		}

		return $string;
	}

	public static function isArticleOnline($articleId) {
		$article = rexx::getArticle($articleId);

		if (rexx::isArticleValid($article)) {
			return $article->isOnline();
		} else {
			return 'Article with ID = ' . $articleId . ' does not exist!';
		}
	}

	public static function isCurrentArticleOnline() {
		return rexx::isArticleOnline(rexx::getCurrentArticleId());
	}

	// check if previous slice is from same module type
	public static function isFirstSliceOfSameType($sliceId) {
		return rexx::isSliceOfSameType($sliceId, true);
	}
	
	// check if next slice is from same module type
	public static function isLastSliceOfSameType($sliceId) {
		return rexx::isSliceOfSameType($sliceId, false);
	}

	protected static function isSliceOfSameType($sliceId, $previous = true) {
		$curSlice = rex_article_slice::getArticleSliceById($sliceId, rexx::getCurrentClangId());

		if ($previous) {
			$slice = $curSlice->getPreviousSlice();
		} else {
			$slice = $curSlice->getNextSlice();
		}

		if (rexx::isSliceValid($slice)) {
			$sliceStatus = rexx::getSliceStatus($slice->getId());

			if ($sliceStatus == null) {
				$sliceStatus = 1;
			}

			if ($sliceStatus == 1 && $curSlice->getModuleId() == $slice->getModuleId()) {
				return false;
			}
		}

		return true;
	}

	public static function getSliceStatus($sliceId) {
		$sql = rex_sql::factory();
		$sql->setQuery('SELECT status FROM ' . rexx::getTablePrefix() . 'article_slice WHERE id = ' . $sliceId);

		if ($sql->getRows() > 0) {
			return $sql->getValue('status');
		}

		return null;
	}

	public static function getCSSFile($file, $vars = []) {
		return rexx_resource_includer::getCSSFile($file, $vars);
	}

	public static function getJSFile($file) {
		return rexx_resource_includer::getJSFile($file);
	}

	public static function getImageFile($file) {
		return rexx_resource_includer::getImageFile($file);
	}

	public static function getAbsoluteImageFile($file) {
		return rexx_resource_includer::getAbsoluteImageFile($file);
	}

	public static function getFavIconFile($file) {
		return rexx_resource_includer::getFavIconFile($file);
	}

	public static function getResourceFile($fileWithPath) {
		return rexx_resource_includer::getResourceFile($fileWithPath);
	}

	public static function getCombinedCSSFile($combinedFile, $sourceFiles) {
		return rexx_resource_includer::getCombinedCSSFile($combinedFile, $sourceFiles);
	}

	public static function getCombinedJSFile($combinedFile, $sourceFiles) {
		 return rexx_resource_includer::getCombinedJSFile($combinedFile, $sourceFiles);
	}

	public static function getJSCodeFromTemplate($templateId, $simpleMinify = true) {
		return rexx_resource_includer::getJSCodeFromTemplate($templateId, $simpleMinify);
	}

	public static function getJSCodeFromFile($file, $simpleMinify = true) {
		return rexx_resource_includer::getJSCodeFromFile($file, $simpleMinify);
	}

	public static function getGlobalValue($field, $clangId = null) {
		return rex_global_settings::getValue($field, $clangId);
	}

	public static function getDefaultGlobalValue($field) {
		return rex_global_settings::getDefaultValue($field);
	}
}

