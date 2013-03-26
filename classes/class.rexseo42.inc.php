<?php
class rexseo42 {
	protected static $curArticle;
	protected static $startArticleID;
	protected static $titleDelimiter;
	protected static $robotsFollowFlag;
	protected static $robotsArchiveFlag;
	protected static $mediaDir;
	protected static $mediaAddonDir;
	protected static $seoFriendlyImageManagerUrls;
	protected static $fullUrls;
	protected static $serverUrl;
	protected static $websiteName;
	protected static $server;
	protected static $serverProtocol;
	protected static $serverSubdir;
	protected static $isSubdirInstall;
	protected static $urlStart;
	
	public static function init() {
		// to be called before resolve()
		global $REX;

		// default inits
		self::$startArticleID = $REX['START_ARTICLE_ID'];
		self::$titleDelimiter = $REX['ADDON']['rexseo42']['settings']['title_delimiter'];
		self::$robotsFollowFlag = $REX['ADDON']['rexseo42']['settings']['robots_follow_flag'];
		self::$robotsArchiveFlag = $REX['ADDON']['rexseo42']['settings']['robots_archive_flag'];
		self::$mediaDir = $REX['MEDIA_DIR'];
		self::$mediaAddonDir = $REX['MEDIA_ADDON_DIR'];
		self::$seoFriendlyImageManagerUrls = $REX['ADDON']['rexseo42']['settings']['seo_friendly_image_manager_urls'];
		self::$fullUrls = $REX['ADDON']['rexseo42']['settings']['full_urls'];
		self::$serverUrl = $REX['SERVER'];
		self::$websiteName = $REX['SERVERNAME'];

		// pull apart server url
		$urlParts = self::getUrlParts(self::$serverUrl);

		self::$serverProtocol = $urlParts['protocol'];
		self::$server = $urlParts['site'];
		self::$serverSubdir = trim($urlParts['resource'], '/'); 

		// check for subdir install
		if (self::$serverSubdir == '') {
			self::$isSubdirInstall = false;
		} else {
			self::$isSubdirInstall = true;
		}

		// get url start 
		if (self::$fullUrls) {
			// full worpresslike urls
			self::$urlStart = self::$serverUrl;
		} else {
			if (self::$isSubdirInstall) {
				// url start for subdirs
				self::$urlStart = $REX['ADDON']['rexseo42']['settings']['url_start_subdir'];
			} else {
				// url start for normal redaxo installations
				self::$urlStart = $REX['ADDON']['rexseo42']['settings']['url_start'];
			}
		}
	}

	public static function initArticle($articleId) {
		// to be called after resolve()
		global $REX;

		self::$curArticle = OOArticle::getArticleById($articleId);
	}

	public static function getBaseUrl() {
		return self::$serverUrl;
	}

	public static function getTitle() {
		if (self::$curArticle->getValue('seo_title') == '') {
			// use article name as title
			$titlePart = self::getArticleName();
		} else {
			// use title that user defined
			$titlePart = self::$curArticle->getValue('seo_title');
		}
		
		if (self::$curArticle->getValue('seo_ignore_prefix') == '1') {
			// no prefix, just the title
			$fullTitle = $titlePart;
		} else { 
			if (self::isStartArticle()) {
				// the start article shows the website name first
				$fullTitle = self::getWebsiteName() . self::$titleDelimiter . $titlePart;
			} else {
				// all other articles will show title first
				$fullTitle = $titlePart . self::$titleDelimiter . self::getWebsiteName();
			}
		 }

		return htmlspecialchars($fullTitle);
	}

	public static function getDescription() {
		return htmlspecialchars(self::$curArticle->getValue('seo_description'));
	}

	public static function getKeywords() {	
		return htmlspecialchars(self::$curArticle->getValue('seo_keywords'));
	}

	public static function getRobotRules() {
		if (self::$curArticle->getValue('seo_noindex') == '1') { 
			$robots = "noindex";
		} else {
			$robots = "index";
		}
		
		if (self::$robotsFollowFlag != '') {
			$robots .= ", " . self::$robotsFollowFlag;
		}

		if (self::$robotsArchiveFlag != '') {
			$robots .= ", " . self::$robotsArchiveFlag;
		}

		return $robots;
	}

	public static function getCanonicalUrl() {
		if (self::$fullUrls) {
			return rex_getUrl(self::$curArticle->getId());
		} else {
			return self::getBaseUrl() . ltrim(rex_getUrl(self::$curArticle->getId()), "./");
		}
	}

	public static function getImageTag($imageFile, $imageType = '', $width = 0, $height = 0) {
		$media = OOMedia::getMediaByFileName($imageFile);

		// make sure media object is valid
		if (OOMedia::isValid($media)) {
			$mediaWidth = $media->getWidth();
			$mediaHeight = $media->getHeight();
			$altAttribute = $media->getTitle();
		} else {
			$mediaWidth = '';
			$mediaHeight = '';
			$altAttribute = '';
		}

		// image width
		if ($width == 0) {
			$imgWidth = $mediaWidth;
		} else {
			$imgWidth = $width;
		}

		// image height
		if ($height == 0) {
			$imgHeight = $mediaHeight;
		} else {
			$imgHeight = $height;
		}

		// make url
		if ($imageType == '') {
			$url = self::getMediaFile($imageFile);
		} else {
			$url = self::getImageManagerUrl($imageFile, $imageType);
		}

		return '<img src="' . $url . '" width="' . $imgWidth . '" height="' . $imgHeight . '" alt="' . $altAttribute . '" />';
	}

	public static function getImageManagerUrl($imageFile, $imageType) {
		if (self::$seoFriendlyImageManagerUrls) {
			return self::getMediaDir() . 'imagetypes/' . $imageType . '/' . $imageFile;
		} else {
			return '/index.php?rex_img_type=' . $imageType . '&amp;rex_img_file=' . $imageFile;
		}
	}

	public static function getHtml($indent = "\t") {
		$out = '';

		if (self::$isSubdirInstall && !self::$fullUrls) {
			$out .= '<base href="' . self::getBaseUrl() . '" />' . PHP_EOL;
            $out .= $indent;
		}

		$out .= '<title>' . self::getTitle() . '</title>' . PHP_EOL;
		$out .= $indent . '<meta name="description" content="' . self::getDescription() . '" />' . PHP_EOL;
		$out .= $indent . '<meta name="keywords" content="' . self::getKeywords() . '" />' . PHP_EOL;
		$out .= $indent . '<meta name="robots" content="' . self::getRobotRules() . '" />' . PHP_EOL;
		$out .= $indent . '<link rel="canonical" href="' . self::getCanonicalUrl() . '" />' . PHP_EOL;

		return $out;
	}

	public static function getTitleDelimiter() {
		return self::$titleDelimiter;
	}

	public static function getArticleName() {
		return self::$curArticle->getName();
	}
	
	public static function getWebsiteName() {
		global $REX;
		
		return $REX['SERVERNAME'];
	}
	
	public static function getLangCode($clangID = -1) {
		global $REX;

		if ($clangID == -1) {
			$clangID = $REX['CUR_CLANG'];
		}

		if (isset($REX['ADDON']['rexseo42']['settings']['langcodes'][$clangID])) {
			return $REX['ADDON']['rexseo42']['settings']['langcodes'][$clangID];
		} else {
			return $REX['CLANG'][$clangID];
		}
	}

	public static function getServer() {
		return self::$server;
	}

	public static function getServerUrl() {
		return self::$serverUrl;
	}

	public static function getServerProtocol() {
		return self::$serverProtocol;
	}

	public static function getServerSubdir() {
		return self::$serverSubdir;
	}

	public static function isSubdirInstall() {
		return self::$isSubdirInstall;
	}

	public static function getServerWithSubdir() {
		if (self::$isSubdirInstall) {
			return self::$server . '/' . self::$serverSubdir;
		} else {
			return self::$server;
		}
	}

	public static function getUrlStart() {
		return self::$urlStart;
	}

	public static function setUrlStart($urlStart) {
		self::$urlStart = $urlStart;
	}

	public static function getMediaDir() {
		return self::$urlStart . self::$mediaDir . '/';
	}

	public static function getMediaFile($file) {
		return self::getMediaDir() . $file;
	}

	public static function getMediaAddonDir() {
		global $REX;

		return self::$urlStart . self::$mediaAddonDir . '/';
	}

	public static function isStartArticle() {
		if (self::$curArticle->getId() == self::$startArticleID) {
			return true;
		} else {
			return false;
		}
	}

	protected static function getUrlParts($url) {
		$result = array();
		 
		// Get the protocol, site and resource parts of the URL
		// original url = http://example.com/blog/index?name=foo
		// protocol = http://
		// site = example.com/
		// resource = blog/index?name=foo
		$regex = '#^(.*?//)*([\w\.\d]*)(:(\d+))*(/*)(.*)$#';
		$matches = array();
		preg_match($regex, $url, $matches);
		 
		// Assign the matched parts of url to the result array
		$result['protocol'] = $matches[1];
		$result['port'] = $matches[4];
		$result['site'] = $matches[2];
		$result['resource'] = $matches[6];
		 
		// clean up the site portion by removing the trailing /
		$result['site'] = preg_replace('#/$#', '', $result['site']);
		 
		// clean up the protocol portion by removing the trailing ://
		$result['protocol'] = preg_replace('#://$#', '', $result['protocol']);
		 
		return $result;
	}

	public static function getDebugInfo($articleId = 0) {
		global $I18N;

		if ($articleId != 0) {
			self::initArticle($articleId);			
		}

		if (!OOArticle::isValid(self::$curArticle)) {
			return '';
		}

		$out = '<table id="rexseo42-debug">';

		$out .= self::getDebugInfoRow('rex_getUrl', array(self::$curArticle->getId()));
		$out .= self::getDebugInfoRow('rexseo42::getTitle');
		$out .= self::getDebugInfoRow('rexseo42::getDescription');
		$out .= self::getDebugInfoRow('rexseo42::getKeywords');
		$out .= self::getDebugInfoRow('rexseo42::getRobotRules');
		$out .= self::getDebugInfoRow('rexseo42::getCanonicalUrl');
		$out .= self::getDebugInfoRow('rexseo42::getArticleName');
		$out .= self::getDebugInfoRow('rexseo42::isStartArticle');
		$out .= self::getDebugInfoRow('rexseo42::getWebsiteName');
		$out .= self::getDebugInfoRow('rexseo42::getLangCode', array('0'));
		$out .= self::getDebugInfoRow('rexseo42::getServerProtocol');
		$out .= self::getDebugInfoRow('rexseo42::getBaseUrl');
		$out .= self::getDebugInfoRow('rexseo42::getServerUrl');
		$out .= self::getDebugInfoRow('rexseo42::getServer');
		$out .= self::getDebugInfoRow('rexseo42::getServerWithSubdir');
		$out .= self::getDebugInfoRow('rexseo42::getServerSubdir');
		$out .= self::getDebugInfoRow('rexseo42::isSubdirInstall');
		$out .= self::getDebugInfoRow('rexseo42::getTitleDelimiter');
		$out .= self::getDebugInfoRow('rexseo42::getUrlStart');
		$out .= self::getDebugInfoRow('rexseo42::getMediaDir');
		$out .= self::getDebugInfoRow('rexseo42::getMediaFile', array('image.png'));
		$out .= self::getDebugInfoRow('rexseo42::getMediaAddonDir');
		$out .= self::getDebugInfoRow('rexseo42::getHtml');
		$out .= self::getDebugInfoRow('rexseo42::getImageTag', array('image.png', 'rex_mediapool_detail', '150', '100'));
		$out .= self::getDebugInfoRow('rexseo42::getImageManagerUrl', array('image.png', 'rex_mediapool_detail'));
		$out .= self::getDebugInfoRow('rexseo42::getAnswer');

		$out .= '</table>';

		return $out;
	}

	protected static function getDebugInfoRow($func, $params = array()) {
		$out = '';

		$function = $func . '(';

		for ($i = 0; $i < count($params); $i++) {
			if (!is_numeric($params[$i])) {
				$function .= '"';
			}

			$function .= $params[$i];

			if (!is_numeric($params[$i])) {
				$function .= '"';
			}


			if (isset($params[$i + 1])) {
				$function .= ', ';
			}
		}

		$function .= ')';

		$out .= '<tr>';
		$out .= '<td class="left"><code>' . $function . '</code></td>';
		$out .= '<td class="right">' . htmlspecialchars(call_user_func_array($func, $params)) . '</td>';
		$out .= '</tr>';

		return $out;
	}

	public static function getAnswer() {
		return '42';
	}
}
