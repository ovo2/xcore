<?php
class rexseo42 {
	static $titleDelimiter = "|";
	static $robotsArchiveFlag = "noarchive";
	static $robotsFollowFlag = "follow";

	static function setTitleDelimiter($delimiter) {
		self::$titleDelimiter = $delimiter;
	}

	static function getTitleDelimiter() {
		return self::$titleDelimiter;
	}

	static function getTitle($titleDelimiter = "") {
		global $REX;
		$title = "";
		$fullTitle = "";

		if ($titleDelimiter != "") {
			self::$titleDelimiter = $titleDelimiter;
		}

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
				$fullTitle = self::getPrefix() . ' ' . self::$titleDelimiter . ' ' . $title;
			} else {
				// all other articles will show title first
				$fullTitle = $title . ' ' . self::$titleDelimiter . ' ' . self::getPrefix();
			}
			
			return htmlspecialchars($fullTitle);
		 }
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
	
	static function getPrefix() {
		global $REX;
		
		return $REX['SERVERNAME'];
	}

	static function getCompletePrefixLength() {
		return strlen(self::getPrefix()) + strlen(self::$titleDelimiter) + 2 /* spaces */;
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
		
		if (self::$robotsFollowFlag != '') {
			$robots .= ", " . self::$robotsFollowFlag;
		}

		if (self::$robotsArchiveFlag != '') {
			$robots .= ", " . self::$robotsArchiveFlag;
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
	
	static function getCountryCode() {
		global $REX;

		return $REX['CUR_CLANG'][$REX['CUR_CLANG']];
	}
	
	static function getBaseUrl() {
		global $REX;
	
		$baseUrl = $REX['SERVER'];

		return $baseUrl;
	}

	static function getCanonicalUrl() {
		global $REX;
		
		return rtrim(self::getBaseUrl(), '/') . rex_getUrl($REX['ARTICLE_ID']);
	}

	static function getImageTag($file) {
		$media = OOMedia::getMediaByFileName($file);

		return '<img src="/files/' . $file . '" width="' . $media->getWidth() . '" height="' . $media->getHeight() . '" alt="' . $media->getTitle() . '" />';
	}
}
