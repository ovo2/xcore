<h1><?php echo $I18N->msg('rexseo42_help_debug'); ?></h1>

<?php
$codeExample1 = '<?php echo rexseo42::getDebugInfo(); ?>';

echo '<p>' . $I18N->msg('rexseo42_help_debug_desc') . '</p>';

rex_highlight_string($codeExample1);

echo '<p>' . $I18N->msg('rexseo42_help_debug_output', $REX['ADDON']['rexseo42']['settings']['debug_article_id']) . '</p>';

$debugOut = rexseo42::getDebugInfo($REX['ADDON']['rexseo42']['settings']['debug_article_id']);

if ($debugOut) {
	echo $debugOut;
} else {
	echo '<strong>' . $I18N->msg('rexseo42_help_debug_article_wrong') . '</strong>';
}
?>


