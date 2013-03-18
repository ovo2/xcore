<?php
class rexseo42 {
	static function getTitle() {
		global $REX;

		$title = "";
		$fullTitle = "";

		// userdef title or article name?
		if ($REX['ART'][$REX['ARTICLE_ID']]['seo_title'][$REX['CUR_CLANG']] != "") {
			$title = $REX['ART'][$REX['ARTICLE_ID']]['seo_title'][$REX['CUR_CLANG']];
		} else {
			$title = self::getArticleName();
		}
		
		if ($REX['ART'][$REX['ARTICLE_ID']]['seo_ignore_prefix'][$REX['CUR_CLANG']] == "1") {
			// no prefix, just the title
			return htmlspecialchars($title);
		} else { 
			if (self::isStartPage()) {
				// the start article shows the servername first
				$fullTitle = self::getWebsiteName() . $REX['ADDON']['rexseo42']['settings']['title_delimeter'] . $title;
			} else {
				// all other articles will show title first
				$fullTitle = $title . $REX['ADDON']['rexseo42']['settings']['title_delimeter'] . self::getWebsiteName();
			}
			
			return htmlspecialchars($fullTitle);
		 }
	}

	static function getTitleDelimiter() {
		global $REX;

		return $REX['ADDON']['rexseo42']['settings']['title_delimeter'];
	}

	static function isStartPage() {
		global $REX;

		if ($REX['ARTICLE_ID'] == $REX['START_ARTICLE_ID']) {
			return true;
		} else {
			return false;
		}
	}

	static function getArticleName() {
		global $REX;
		
		return $REX['ART'][$REX['ARTICLE_ID']]['name'][$REX['CUR_CLANG']];
	}
	
	static function getWebsiteName() {
		global $REX;
		
		return $REX['SERVERNAME'];
	}

	static function getCompletePrefixLength() {
		global $REX;

		return strlen(self::getWebsiteName()) + strlen($REX['ADDON']['rexseo42']['settings']['title_delimeter']);
	} 
	
	static function getDescription() {
		global $REX;
		$description = "";
		$description = $REX['ART'][$REX['ARTICLE_ID']]['seo_description'][$REX['CUR_CLANG']];

		return htmlspecialchars($description);
	}
	
	static function getRobotRules() {
		global $REX;
		$robots = "";
		
		if (self::isNoIndex($REX['ART'][$REX['ARTICLE_ID']]['seo_noindex'][$REX['CUR_CLANG']])) { 
			$robots = "noindex";
		} else { 
			$robots = "index";
		}
		
		if ($REX['ADDON']['rexseo42']['settings']['robots_follow_flag'] != '') {
			$robots .= ", " . $REX['ADDON']['rexseo42']['settings']['robots_follow_flag'];
		}

		if ($REX['ADDON']['rexseo42']['settings']['robots_archive_flag'] != '') {
			$robots .= ", " . $REX['ADDON']['rexseo42']['settings']['robots_archive_flag'];
		}

		return $robots;
	}
	
	static function isNoIndex($value) {
		if ($value == '1') { 
			return 1;
		} else {
			return 0;
		}
	}
	
	static function getKeywords() {
		global $REX;
		$keywords = "";
		$keywords = $REX['ART'][$REX['ARTICLE_ID']]['seo_keywords'][$REX['CUR_CLANG']];
		
		return htmlspecialchars($keywords);
	}
	
	static function getLangCode($clangID = -1) {
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

	static function sanitizeUrl($url) {
		return preg_replace('@^https?://|/.*|[^\w.-]@', '', $url);
	}

	static function getServer() {
		global $REX;

		return self::sanitizeUrl($REX['SERVER']);
	}

	static function getServerUrl() {
		global $REX;

		return $REX['ADDON']['rexseo42']['settings']['server_protocol'] . self::getServer() . '/';
	}
	
	static function getBaseUrl() {
		return self::getServerUrl();
	}

	static function getCanonicalUrl() {
		global $REX;
		
		return rtrim(self::getBaseUrl(), '/') . rex_getUrl($REX['ARTICLE_ID']);
	}

	static function getImageTag($file) {
		$media = OOMedia::getMediaByFileName($file);

		return '<img src="/files/' . $file . '" width="' . $media->getWidth() . '" height="' . $media->getHeight() . '" alt="' . $media->getTitle() . '" />';
	}

	static function getHtml($indent = "\t") {
		$out = '<base href="' . self::getBaseUrl() . '" />';
		$out .= PHP_EOL . $indent . '<title>' . self::getTitle() . '</title>';
		$out .= PHP_EOL . $indent . '<meta name="description" content="' . self::getDescription() . '" />';
		$out .= PHP_EOL . $indent . '<meta name="keywords" content="' . self::getKeywords() . '" />';
		$out .= PHP_EOL . $indent . '<meta name="robots" content="' . self::getRobotRules() . '" />';
		$out .= PHP_EOL . $indent . '<link rel="canonical" href="' . self::getCanonicalUrl() . '" />';
		$out .= PHP_EOL;

		return $out;
	}
}
