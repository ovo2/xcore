<?php

class rex_navigation42 extends rex_navigation {
	static function getNavigationByLevel($levelStart = 0, $levelDepth = 2, $showAll = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $firstUlId = '', $firstUlClass = '', $selectedClass = 'selected') {
		global $REX;
		
		$nav = self::factory();
		$path = explode('|', ('0' . $REX['ART'][$REX['ARTICLE_ID']]['path'][$REX['CUR_CLANG']] . $REX['ARTICLE_ID'] . '|'));

		return $nav->get($path[$levelStart], $levelDepth, $showAll, $ignoreOfflines, $hideWebsiteStartArticle, $firstUlId, $firstUlClass, $selectedClass);
	}

	static function getNavigationByCategory($categoryId, $levelDepth = 2, $showAll = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $firstUlId = '', $firstUlClass = '', $selectedClass = 'selected') {
		$nav = self::factory();

		return $nav->get($categoryId, $levelDepth, $showAll, $ignoreOfflines, $hideWebsiteStartArticle, $firstUlId, $firstUlClass, $selectedClass);
	}

	// overwritten method (depends on factory() and get() methods)
	function _getNavigation($categoryId, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $firstUlId = '', $firstUlClass = '', $selectedClass = 'selected') { 
		global $REX;

		static $depth = 0;
		
		if ($categoryId < 1) {
			$navObj = OOCategory::getRootCategories($ignoreOfflines);
		} else {
			$navObj = OOCategory::getChildrenById($categoryId, $ignoreOfflines);
		}

		$return = '';
		$ulIdAttribute = '';
		$ulClassAttribute = '';

		if (count($navObj) > 0) {
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
			
		foreach ($navObj as $nav) {
			$cssClasses = array();

			// website specific
			//$cssClasses[] = $nav->getValue('cat_css_class');

			if ($nav->getId() == $this->current_category_id) {
				// current menuitem
				$cssClasses[] = $selectedClass;
			} elseif (in_array($nav->getId(), $this->path)) {
				// active menuitem in path
				$cssClasses[] = $selectedClass;
			} else {
				// do nothing
			}

			// build class attribute
			if (count($cssClasses) > 0) {
				$classAttribute = ' class="' . trim(implode(' ', $cssClasses)) . '"';
			} else {
				$classAttribute = '';
			}

			if ($hideWebsiteStartArticle && ($nav->getId() == $REX['START_ARTICLE_ID'])) {
				// do nothing
			} else {
				$depth++;
				$urlType = 0; // default

				$return .= '<li' . $classAttribute . '>';

				$defaultLink = '<a href="' . $nav->getUrl() . '">' . htmlspecialchars($nav->getName()) . '</a>';

				if (!class_exists('seo42')) {
					// normal behaviour
					$return .= $defaultLink;
				} else {
					// only with seo42 2.0.0+
					$urlData = seo42::getCustomUrlData($nav);

					// check if default lang has url clone option (but only if current categoy has no url data set)
					if (count($REX['CLANG']) > 1 && !isset($urlData['url_type'])) {
						$defaultLangCat = OOCategory::getCategoryById($nav->getId(), $REX['START_CLANG_ID']);
						$urlDataDefaultLang = seo42::getCustomUrlData($defaultLangCat);
				
						if (isset($urlDataDefaultLang['url_clone'])) {
							// clone url data from default language to current language
							$urlData = $urlDataDefaultLang;
						}
					}

					if (isset($urlData['url_type'])) {
						switch ($urlData['url_type']) { 
							case 5: // SEO42_URL_TYPE_NONE
								$return .= htmlspecialchars($nav->getName());
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
									$return .= '<li class="' . $selectedClass . '">';
								}

								$return .= '<a href="' . rex_getUrl($newArticleId, $newClangId) . '">' . htmlspecialchars($nav->getName()) . '</a>';
								break;
							case 8: // SEO42_URL_TYPE_CALL_FUNC
								$return .= call_user_func($urlData['func'], $nav);
								break;
							default:
								$return .= $defaultLink;
								break;
						}
					} else {
						$return .= $defaultLink;
					}
				} 
				
				if (($this->open || $nav->getId() == $this->current_category_id || in_array($nav->getId(), $this->path)) && ($this->depth > $depth || $this->depth < 0)) {
					$return .= $this->_getNavigation($nav->getId(), $ignoreOfflines, $hideWebsiteStartArticle, $firstUlId, $firstUlClass, $selectedClass);
				}
				
				$depth--;

				$return .= '</li>';
			}
		}

		if (count($navObj) > 0) {
			$return .= '</ul>';
		}

		return $return;
	}
	
	// when overwriting _getNavigation() this needs to be overwritten too at the moment
	static function factory() {
		static $class = null;

		if (!$class) {
			$class = rex_register_extension_point('REX_NAVI_CLASSNAME', 'rex_navigation42');
		}
	
		return new $class();
	}

	// when overwriting _getNavigation() this needs to be overwritten too at the moment
	function get($categoryId = 0, $depth = 3, $open = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $firstUlId = '', $firstUlClass = '', $selectedClass = 'selected') { 
		if (!$this->_setActivePath()) {
			return false;
		}

		$this->depth = $depth;
		$this->open = $open;
		$this->ignore_offlines = $ignoreOfflines;
		
		return $this->_getNavigation($categoryId, $this->ignore_offlines, $hideWebsiteStartArticle, $firstUlId, $firstUlClass, $selectedClass);
	}
}

