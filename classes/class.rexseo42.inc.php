<?php
class rexseo42 {
	protected static $curArticle;
	protected static $startArticleID;
	protected static $defaultTitleDelimeter;
	protected static $robotsFollowFlag;
	protected static $robotsArchiveFlag;
	protected static $serverProtocol;
	protected static $mediaDir;
	protected static $seoFriendlyImageManagerUrls;
	

	public static function init() {
		global $REX;

		self::$curArticle = OOArticle::getArticleById($REX['ARTICLE_ID']);
		self::$startArticleID = $REX['START_ARTICLE_ID'];
		self::$defaultTitleDelimeter = $REX['ADDON']['rexseo42']['settings']['title_delimeter'];
		self::$robotsFollowFlag = $REX['ADDON']['rexseo42']['settings']['robots_follow_flag'];
		self::$robotsArchiveFlag = $REX['ADDON']['rexseo42']['settings']['robots_archive_flag'];
		self::$serverProtocol = $REX['ADDON']['rexseo42']['settings']['server_protocol'];
		self::$mediaDir = $REX['MEDIA_DIR'];
		self::$seoFriendlyImageManagerUrls = $REX['ADDON']['rexseo42']['settings']['seo_friendly_image_manager_urls'];
	}

	public static function getBaseUrl() {
		return self::getServerUrl();
	}

	public static function getTitle($titleDelimeter = '') {
		if ($titleDelimeter == '') {
			// use default title delimeter defined in settings.advanced.inc.php
			$titleDelimeter = self::$defaultTitleDelimeter;
		}

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
			if (self::isStartPage()) {
				// the start article shows the website name first
				$fullTitle = self::getWebsiteName() . $titleDelimeter . $titlePart;
			} else {
				// all other articles will show title first
				$fullTitle = $titlePart . $titleDelimeter . self::getWebsiteName();
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
		return rtrim(self::getBaseUrl(), '/') . rex_getUrl(self::$curArticle->getId());
	}

	public static function getImageTag($imageFile, $imageType = '', $width = 0, $height = 0) {
		$media = OOMedia::getMediaByFileName($imageFile);

		// make sure media object is valid
		if (OOMedia::isValid($media)) {
			$mediaWidth = $media->getWidth();
			$mediaHeight = $media->getHeight();
		} else {
			$mediaWidth = '';
			$mediaHeight = '';
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
			$imgHeight = $width;
		}

		// make url
		if ($imageType == '') {
			$url = '/' . self::$mediaDir . '/' . $imageFile;
		} else {
			$url = self::getImageManagerUrl($imageFile, $imageType);
		}

		return '<img src="' . $url . '" width="' . $imgWidth . '" height="' . $imgHeight . '" alt="' . $media->getTitle() . '" />';
	}

	public static function getImageManagerUrl($imageFile, $imageType) {
		if (self::$seoFriendlyImageManagerUrls) {
			return '/' . self::$mediaDir . '/imagetypes/' . $imageType . '/' . $imageFile;
		} else {
			return '/index.php?rex_img_type=' . $imageType . '&amp;rex_img_file=' . $imageFile;
		}
	}

	public static function getHtml($indent = "\t") {
		$out = '<base href="' . self::getBaseUrl() . '" />';
		$out .= PHP_EOL . $indent . '<title>' . self::getTitle() . '</title>';
		$out .= PHP_EOL . $indent . '<meta name="description" content="' . self::getDescription() . '" />';
		$out .= PHP_EOL . $indent . '<meta name="keywords" content="' . self::getKeywords() . '" />';
		$out .= PHP_EOL . $indent . '<meta name="robots" content="' . self::getRobotRules() . '" />';
		$out .= PHP_EOL . $indent . '<link rel="canonical" href="' . self::getCanonicalUrl() . '" />';
		$out .= PHP_EOL;

		return $out;
	}

	public static function getTitleDelimiter() {
		return self::$defaultTitleDelimeter;
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
		global $REX;

		return self::sanitizeUrl($REX['SERVER']);
	}

	public static function getServerUrl() {
		global $REX;

		return self::$serverProtocol . self::getServer() . '/';
	}

	public static function isStartPage() {
		if (self::$curArticle->getId() == self::$startArticleID) {
			return true;
		} else {
			return false;
		}
	}

	public static function sanitizeUrl($url) {
		return preg_replace('@^https?://|/.*|[^\w.-]@', '', $url);
	}
}
