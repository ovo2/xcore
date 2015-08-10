<?php

$debugOut = seo42::getDebugInfo($REX['START_ARTICLE_ID']);

if ($debugOut) {
	echo $debugOut;
} else {
	echo '<strong>' . $I18N->msg('seo42_help_debug_article_wrong') . ' ' . $REX['START_ARTICLE_ID'] . '</strong>';
}

