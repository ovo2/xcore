<h1><?php echo $I18N->msg('seo42_help_debug'); ?></h1>

<?php
$codeExample1 = '<?php echo seo42::getDebugInfo(); ?>';

echo '<p>' . $I18N->msg('seo42_help_debug_desc') . '</p>';

rex_highlight_string($codeExample1);

echo '<p>' . $I18N->msg('seo42_help_debug_output', $REX['ADDON']['seo42']['settings']['debug_article_id']) . '</p>';

$REX['REDAXO'] = false;
$REX['ADDON']['seo42']['settings']['include_query_params'] = false;

$debugOut = seo42::getDebugInfo($REX['ADDON']['seo42']['settings']['debug_article_id']);

$REX['REDAXO'] = true;
$REX['ADDON']['seo42']['settings']['include_query_params'] = true;

if ($debugOut) {
	echo $debugOut;
} else {
	echo '<strong>' . $I18N->msg('seo42_help_debug_article_wrong') . '</strong>';
}
?>


