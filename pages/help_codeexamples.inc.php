<h1><?php echo $I18N->msg('rexseo42_help_codeexamples'); ?></h1>

<?php
$codeExample1 = '<head>
	<?php echo rexseo42::getHtml(); ?>
</head>';

$codeExample2 = '<?php
echo rexseo42::getImageManagerUrl("image.png", "rex_mediapool_detail"); 
// --> /files/imagestypes/rex_mediapool_detail/image.png

echo rexseo42::getImageTag("image.png");
// --> <img src="/files/image.png" width="200" height="100" alt="' . $I18N->msg('rexseo42_help_codeexamples_ex2_alt') . '" />

echo rexseo42::getImageTag("image.png", "rex_mediapool_detail");
// --> <img src="/files/imagestypes/rex_mediapool_detail/image.png" width="200" height="100" alt="' . $I18N->msg('rexseo42_help_codeexamples_ex2_alt') . '" />;
?>';

$codeExample3 = '<html lang="<?php echo rexseo42::getLangCode(); ?>">';

$codeExample4 = '<?php echo rexseo42::getTitle(" - "); ?>';

$codeExample5 = '<?php
// ' . $I18N->msg('rexseo42_help_codeexamples_ex5_comment1') . '
class rexseo42_ex extends rexseo42
	public static function getTitle($titleDelimeter = "") {
		if ($titleDelimeter == "") {
			// use default title delimeter defined in settings.advanced.inc.php
			$titleDelimeter = self::$defaultTitleDelimeter;
		}

		if (self::$curArticle->getValue("seo_title") == "") {
			// use article name as title
			$title = self::getArticleName();
		} else {
			// use title that user defined
			$title = self::$curArticle->getValue("seo_title");
		}
	
		if (self::$curArticle->getValue("seo_ignore_prefix") == "1") {
			// no prefix, just the title
			$fullTitle = $title;
		} else { 
			if (self::isStartPage()) {
				// the start article shows the website name first
				$fullTitle = self::getWebsiteName() . $titleDelimeter . $title;
			} else {
				// all other articles will show title first
				$fullTitle = $title . $titleDelimeter . self::getWebsiteName();
			}
		 }

		// ' . $I18N->msg('rexseo42_help_codeexamples_ex5_comment2') . '
		return strtolower(htmlspecialchars($fullTitle));
	}
}

// ' . $I18N->msg('rexseo42_help_codeexamples_ex5_comment3') . '
echo rexseo42_ex::getTitel(); // ' . $I18N->msg('rexseo42_help_codeexamples_ex5_comment4') . '
echo rexseo42_ex::getDescription();
echo rexseo42_ex::getKeywords();
?>';

?>

<h2>1) <?php echo $I18N->msg('rexseo42_help_codeexamples_title1'); ?></h2>
<p><?php echo $I18N->msg('rexseo42_help_codeexamples_description1'); ?></p>
<?php rex_highlight_string($codeExample1); ?>

<h2>2) <?php echo $I18N->msg('rexseo42_help_codeexamples_title2'); ?></h2>
<p><?php echo $I18N->msg('rexseo42_help_codeexamples_description2'); ?></p>
<?php rex_highlight_string($codeExample2); ?>

<h2>3) <?php echo $I18N->msg('rexseo42_help_codeexamples_title3'); ?></h2>
<p><?php echo $I18N->msg('rexseo42_help_codeexamples_description3'); ?></p>
<?php rex_highlight_string($codeExample3); ?>

<h2>4) <?php echo $I18N->msg('rexseo42_help_codeexamples_title4'); ?></h2>
<p><?php echo $I18N->msg('rexseo42_help_codeexamples_description4'); ?></p>
<?php rex_highlight_string($codeExample4); ?>

<h2>5) <?php echo $I18N->msg('rexseo42_help_codeexamples_title5'); ?></h2>
<p><?php echo $I18N->msg('rexseo42_help_codeexamples_description5'); ?></p>
<?php rex_highlight_string($codeExample5); ?>

<p><?php echo $I18N->msg('rexseo42_help_codeexamples_thatsallfolks'); ?></p>

<style type="text/css">
.rex-addon-content h2 {
	font-size: 14px;
}
</style>


