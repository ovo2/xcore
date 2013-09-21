<h1><?php echo $I18N->msg('seo42_help_codeexamples'); ?></h1>

<?php
$codeExample1 = '<head>
	<?php echo seo42::getHtml(); ?>
</head>';

$codeExample2 = '<?php
echo rex_getUrl(42);
// --> ' . seo42::getUrlStart() . 'questions/the-ultimate-answer.html

echo seo42::getMediaFile("image.png");
// --> ' . seo42::getUrlStart() . 'files/foo.png

echo seo42::getMediaDir();
// --> ' . seo42::getUrlStart() . 'files/

echo seo42::getUrlStart() . "js/jquery.min.js"; 
// --> ' . seo42::getUrlStart() . 'js/jquery.min.js
?>';

$codeExample3 = '<?php
echo seo42::getImageManagerUrl("image.png", "rex_mediapool_detail"); 
// --> /files/imagestypes/rex_mediapool_detail/image.png

echo seo42::getImageTag("image.png");
// --> <img src="/files/image.png" width="300" height="200" alt="' . $I18N->msg('seo42_help_codeexamples_ex3_alt') . '" />

echo seo42::getImageTag("image.png", "rex_mediapool_detail", 150, 100);
// --> <img src="/files/imagestypes/rex_mediapool_detail/image.png" width="150" height="100" alt="' . $I18N->msg('seo42_help_codeexamples_ex3_alt') . '" />;
?>';

$codeExample4 = '<html lang="<?php echo seo42::getLangCode(); ?>">';

$codeExample5 = '<?php echo seo42::getTitle(rex_string_table::getString("website_name")); ?>';

$codeExample6 = '<?php 
// ausgabe des 1. navigationslevels
echo rex_navigation42::getNavigationByLevel(0, 1);

// ausgabe des 2. und 3. navigationslevels
echo rex_navigation42::getNavigationByLevel(1, 3);

// ausgabe der navigation mit startkategorie id = 42, 2 level tief
echo rex_navigation42::getNavigationByCategory(42, 2);
?>';

$codeExample7 = '<?php
// --> ' . strtoupper($I18N->msg('seo42_help_codeexamples_ex7_comment1')) . '
class seo42_ex extends seo42
	public static function getTitle($websiteName = "") {
		if ($websiteName == "") {
			// use default website name if user did not set different one
			$websiteName = self::getWebsiteName();
		}

		if (self::getArticleValue("seo_title") == "") {
			// use article name as title
			$titlePart = self::getArticleName();
		} else {
			// use title that user defined
			$titlePart = self::getArticleValue("seo_title");
		}
		
		if (self::getArticleValue("seo_ignore_prefix") == "1") {
			// no prefix, just the title
			$fullTitle = $titlePart;
		} else { 
			if (self::isStartArticle()) {
				// the start article shows the website name first
				$fullTitle = $websiteName . self::getTitleDelimiter() . $titlePart;
			} else {
				// all other articles will show title first
				$fullTitle = $titlePart . self::getTitleDelimiter() . $websiteName;
			}
		 }

		// --> ' . strtoupper($I18N->msg('seo42_help_codeexamples_ex7_comment2')) . '
		return strtolower(htmlspecialchars($fullTitle));
	}
}

// --> ' . strtoupper($I18N->msg('seo42_help_codeexamples_ex7_comment3')) . '
echo seo42_ex::getTitle(); // ' . $I18N->msg('seo42_help_codeexamples_ex7_comment4') . '
echo seo42_ex::getDescription();
echo seo42_ex::getKeywords();
?>';

?>

<h2>1) <?php echo $I18N->msg('seo42_help_codeexamples_title1'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description1'); ?></p>
<?php rex_highlight_string($codeExample1); ?>

<h2>2) <?php echo $I18N->msg('seo42_help_codeexamples_title2'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description2'); ?></p>
<?php rex_highlight_string($codeExample2); ?>

<h2>3) <?php echo $I18N->msg('seo42_help_codeexamples_title3'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description3'); ?></p>
<?php rex_highlight_string($codeExample3); ?>

<h2>4) <?php echo $I18N->msg('seo42_help_codeexamples_title4'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description4'); ?></p>
<?php rex_highlight_string($codeExample4); ?>

<h2>5) <?php echo $I18N->msg('seo42_help_codeexamples_title5'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description5'); ?></p>
<?php rex_highlight_string($codeExample5); ?>

<h2>6) <?php echo $I18N->msg('seo42_help_codeexamples_title6'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description6'); ?></p>
<?php rex_highlight_string($codeExample6); ?>

<h2>7) <?php echo $I18N->msg('seo42_help_codeexamples_title7'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description7'); ?></p>
<?php rex_highlight_string($codeExample7); ?>

<style type="text/css">
.rex-addon-content h2 {
	font-size: 14px;
}
</style>


