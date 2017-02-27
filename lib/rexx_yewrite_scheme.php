<?php
class rexx_yrewrite_scheme extends rex_yrewrite_scheme {
	public function appendArticle($path, rex_article $art, rex_yrewrite_domain $domain) {
		$urlEnding = rex_config::get('xcore', 'url_ending');

		if ($art->isStartArticle() && $domain->getMountId() != $art->getId()) {
			return $path . $urlEnding;
		}

		return $path . '/' . $this->normalize($art->getName()) . $urlEnding;
	}
}
