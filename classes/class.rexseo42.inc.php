<?php
class rexseo42 {
	protected static $curArticle;
	protected static $startArticleID;
	protected static $titleDelimeter;
	protected static $robotsFollowFlag;
	protected static $robotsArchiveFlag;
	protected static $serverProtocol;

	public static function init() {
		global $REX;

		self::$curArticle = OOArticle::getArticleById($REX['ARTICLE_ID']);
		self::$startArticleID = $REX['START_ARTICLE_ID'];
		self::$titleDelimeter = $REX['ADDON']['rexseo42']['settings']['title_delimeter'];
		self::$robotsFollowFlag = $REX['ADDON']['rexseo42']['settings']['robots_follow_flag'];
		self::$robotsArchiveFlag = $REX['ADDON']['rexseo42']['settings']['robots_archive_flag'];
		self::$serverProtocol = $REX['ADDON']['rexseo42']['settings']['server_protocol'];
	}

	public static function getBaseUrl() {
		return self::getServerUrl();
	}

	public static function getTitle() {
		if (self::$curArticle->getValue('seo_title') != '') {
			// use userdef title
			$title = self::$curArticle->getValue('seo_title');
		} else {
			// use article name as title
			$title = self::getArticleName();
		}
		
		if (self::$curArticle->getValue('seo_ignore_prefix') == '1') {
			// no prefix, just return the title
			return htmlspecialchars($title);
		} else { 
			if (self::isStartPage()) {
				// the start article shows the servername first
				$fullTitle = self::getWebsiteName() . self::$titleDelimeter . $title;
			} else {
				// all other articles will show title first
				$fullTitle = $title . self::$titleDelimeter . self::getWebsiteName();
			}
			
			return htmlspecialchars($fullTitle);
		 }
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

	public static function getImageTag($file) {
		$media = OOMedia::getMediaByFileName($file);

		return '<img src="/files/' . $file . '" width="' . $media->getWidth() . '" height="' . $media->getHeight() . '" alt="' . $media->getTitle() . '" />';
	}

	public static function getTitleDelimiter() {
		return self::$titleDelimeter;
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

		if (isset($REX['ADDON']['rexseo42']['langcodes'][$clangID])) {
			return $REX['ADDON']['rexseo42']['langcodes'][$clangID];
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
