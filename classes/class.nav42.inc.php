<?php

class nav42 extends rex_navigation {
	static function getNavigationByLevel($levelStart = 0, $levelDepth = 2, $showAll = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $currentClass = 'selected', $firstUlId = '', $firstUlClass = '', $liIdFromMetaField = '', $liClassFromMetaField = '', $linkFromUserFunc = '') {
		global $REX;
		
		$nav = self::factory();
		$path = explode('|', ('0' . $REX['ART'][$REX['ARTICLE_ID']]['path'][$REX['CUR_CLANG']] . $REX['ARTICLE_ID'] . '|'));

		return $nav->get($path[$levelStart], $levelDepth, $showAll, $ignoreOfflines, $hideWebsiteStartArticle, $currentClass, $firstUlId, $firstUlClass, $liIdFromMetaField, $liClassFromMetaField, $linkFromUserFunc);
	}

	static function getNavigationByCategory($categoryId, $levelDepth = 2, $showAll = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $currentClass = 'selected', $firstUlId = '', $firstUlClass = '', $liIdFromMetaField = '', $liClassFromMetaField = '', $linkFromUserFunc = '') {
		$nav = self::factory();

		return $nav->get($categoryId, $levelDepth, $showAll, $ignoreOfflines, $hideWebsiteStartArticle, $currentClass, $firstUlId, $firstUlClass, $liIdFromMetaField, $liClassFromMetaField, $linkFromUserFunc);
	}

	// overwritten method (depends on factory() and get() methods)
	function _getNavigation($categoryId, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $currentClass = 'selected', $firstUlId = '', $firstUlClass = '', $liIdFromMetaField = '', $liClassFromMetaField = '', $linkFromUserFunc = '') { 
		global $REX;

		static $depth = 0;
		
		if ($categoryId < 1) {
			$cats = OOCategory::getRootCategories($ignoreOfflines);
		} else {
			$cats = OOCategory::getChildrenById($categoryId, $ignoreOfflines);
		}

		$return = '';
		$ulIdAttribute = '';
		$ulClassAttribute = '';

		if (count($cats) > 0) {
			if ($depth == 0) {
				// this is first ul
				if ($firstUlId != '') {
					$ulIdAttribute = ' id="' . $firstUlId . '"';
				}

				if ($firstUlClass != '') {
					$ulClassAttribute = ' class="' . $firstUlClass . '"';
				}
			}

			$return .= '<ul' . $ulIdAttribute . $ulClassAttribute . '>';
		}
			
		foreach ($cats as $cat) {
			$cssClasses = '';
			$idAttribute = '';

			// li class from meta infos
			if ($liClassFromMetaField != '' && $cat->getValue($liClassFromMetaField) != '') {
				$cssClasses .= ' ' . $cat->getValue($liClassFromMetaField);
			}

			// li id from meta infos
			if ($liIdFromMetaField != '' && $cat->getValue($liIdFromMetaField) != '') {
				$idAttribute = ' id="' . $cat->getValue($liIdFromMetaField) . '"';
			}

			// selected class
			if ($cat->getId() == $this->current_category_id) {
				// current menuitem
				$cssClasses .= ' ' . $currentClass;
			} elseif (in_array($cat->getId(), $this->path)) {
				// active menuitem in path
				$cssClasses .= ' ' . $currentClass;
			} else {
				// do nothing
			}

			// build class attribute
			if ($cssClasses != '') {
				$classAttribute = ' class="' . trim($cssClasses) . '"';
			} else {
				$classAttribute = '';
			}

			if ($hideWebsiteStartArticle && ($cat->getId() == $REX['START_ARTICLE_ID'])) {
				// do nothing
			} else {
				$depth++;
				$urlType = 0; // default

				$return .= '<li' . $idAttribute . $classAttribute . '>';

				if ($linkFromUserFunc != '') {
					$defaultLink = call_user_func($linkFromUserFunc, $cat, $depth);
				} else {
					$defaultLink = '<a href="' . $cat->getUrl() . '">' . htmlspecialchars($cat->getName()) . '</a>';
				}

				if (!class_exists('seo42')) {
					// normal behaviour
					$return .= $defaultLink;
				} else {
					// only with seo42 2.0.0+
					$urlData = seo42::getCustomUrlData($cat);

					// check if default lang has url clone option (but only if current categoy has no url data set)
					if (count($REX['CLANG']) > 1 && !isset($urlData['url_type'])) {
						$defaultLangCat = OOCategory::getCategoryById($cat->getId(), $REX['START_CLANG_ID']);
						$urlDataDefaultLang = seo42::getCustomUrlData($defaultLangCat);
				
						if (isset($urlDataDefaultLang['url_clone']) && $urlDataDefaultLang['url_clone']) {
							// clone url data from default language to current language
							$urlData = $urlDataDefaultLang;
						}
					}

					if (isset($urlData['url_type'])) {
						switch ($urlData['url_type']) { 
							case 5: // SEO42_URL_TYPE_NONE
								$return .= htmlspecialchars($cat->getName());
								break;
							case 4: // SEO42_URL_TYPE_LANGSWITCH
								$newClangId = $urlData['clang_id'];
								$newArticleId = $REX['ARTICLE_ID'];
								$catNewLang = OOCategory::getCategoryById($newArticleId, $newClangId);

								// if category that should be switched is not online, switch to start article of website
								if (OOCategory::isValid($catNewLang) && !$catNewLang->isOnline()) {
									$newArticleId = $REX['START_ARTICLE_ID'];
								}

								// select li that is current language
								if ($REX['CUR_CLANG'] == $newClangId) {
									$return = substr($return, 0, strlen($return) - strlen('<li>'));
									$return .= '<li class="' . $currentClass . '">';
								}

								$return .= '<a href="' . rex_getUrl($newArticleId, $newClangId) . '">' . htmlspecialchars($cat->getName()) . '</a>';
								break;
							case 8: // SEO42_URL_TYPE_CALL_FUNC
								$return .= call_user_func($urlData['func'], $cat);
								break;
							default:
								$return .= $defaultLink;
								break;
						}
					} else {
						$return .= $defaultLink;
					}
				} 
				
				if (($this->open || $cat->getId() == $this->current_category_id || in_array($cat->getId(), $this->path)) && ($this->depth > $depth || $this->depth < 0)) {
					$return .= $this->_getNavigation($cat->getId(), $ignoreOfflines, $hideWebsiteStartArticle, $currentClass, $firstUlId, $firstUlClass, $liIdFromMetaField, $liClassFromMetaField, $linkFromUserFunc);
				}
				
				$depth--;

				$return .= '</li>';
			}
		}

		if (count($cats) > 0) {
			$return .= '</ul>';
		}

		return $return;
	}
	
	// when overwriting _getNavigation() this needs to be overwritten too at the moment
	static function factory() {
		static $class = null;

		if (!$class) {
			$class = rex_register_extension_point('REX_NAVI_CLASSNAME', 'nav42');
		}
	
		return new $class();
	}

	// when overwriting _getNavigation() this needs to be overwritten too at the moment
	function get($categoryId = 0, $depth = 3, $open = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $currentClass = 'selected', $firstUlId = '', $firstUlClass = '', $liIdFromMetaField = '', $liClassFromMetaField = '', $linkFromUserFunc = '') { 
		if (!$this->_setActivePath()) {
			return false;
		}

		$this->depth = $depth;
		$this->open = $open;
		$this->ignore_offlines = $ignoreOfflines;
		
		return $this->_getNavigation($categoryId, $this->ignore_offlines, $hideWebsiteStartArticle, $currentClass, $firstUlId, $firstUlClass, $liIdFromMetaField, $liClassFromMetaField, $linkFromUserFunc);
	}
}
