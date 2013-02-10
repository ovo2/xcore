<?php
$articleID = rex_request('article_id');
$clang = rex_request('clang');
$ctype = rex_request('ctype');
$savedURL = rex_request('saved_seo_url');

if (rex_post('saveseo', 'boolean')) {
	$sql = rex_sql::factory();

	$sql->setTable($REX['TABLE_PREFIX'] . "article");
	//$sql->debugsql = 1;
	$sql->setWhere("id=" . $articleID . " AND clang=" . $clang);

	// seo fields
	$sql->setValue('seo_title', rex_post('seo_title'));
	$sql->setValue('seo_description', rex_post('seo_description'));
	$sql->setValue('seo_keywords', rex_post('seo_keywords'));
	$sql->setValue('seo_url', rex_post('seo_url'));

	// ignore prefix
	$ignorePrefix = rex_post('seo_ignore_prefix');

	if (is_array($ignorePrefix)) {
		$sql->setValue('seo_ignore_prefix',  '1');
	} else {
		$sql->setValue('seo_ignore_prefix',  '');
	}

	// no index
	$noIndex = rex_post('seo_noindex');
	
	if (is_array($noIndex)) {
		$sql->setValue('seo_noindex',  '1');
	} else {
		$sql->setValue('seo_noindex',  '');
	}

	// do db update
	if ($sql->update()) {
		// info msg
		echo rex_info('SEO-Daten wurden aktualisiert');

		// delete cached article
		rex_generateArticle($articleID);

		// generated path list if url has changed
		if ($savedURL != rex_post('seo_url')) {
			rexseo_generate_pathlist('');
		}
	} else {
		// err msg
		echo rex_warning($sql->getError());
	}
}

$sql = rex_sql::factory();
//$sql->debugsql = 1;
$seoData = $sql->getArray('SELECT * FROM '. $REX['TABLE_PREFIX'] .'article WHERE id=' . $articleID . ' AND clang=' . $clang);
$seoData = $seoData[0];

echo '
<div class="rex-content-body" id="seo-page">
	<div class="rex-content-body-2">
		<div class="rex-form" id="rex-form-content-metamode">
			<form action="index.php" method="post" enctype="multipart/form-data" id="seo-form" name="seo-form">
				<input type="hidden" name="page" value="content" />
				<input type="hidden" name="article_id" value="' . $articleID . '" />
				<input type="hidden" name="mode" value="seo" />
				<input type="hidden" name="save" value="1" />
				<input type="hidden" name="clang" value="' . $clang . '" />
				<input type="hidden" name="ctype" value="' . $ctype . '" />
				<input type="hidden" name="saved_seo_url" value="' . $seoData['seo_url'] . '" />

				<fieldset class="rex-form-col-1">
					<legend id="seo-default">Allgemein</legend>
					<div class="rex-form-wrapper">
						<div class="rex-form-row"  style="position: relative;">
						<p class="rex-form-text">
							<label for="Titel">Titel</label>
							<input type="text" value="' . $seoData['seo_title'] . '" name="seo_title" id="seo_title" tabindex="30" class="rex-form-text seo-title" />
							<div style="display: inline-block;margin-left: 167px; margin-top: 8px;">
								<span id="title-preview"></span>
							</div>
						<div style="position: absolute;top: 6px; left: 538px;">
							<input id="prefix-check" style="position: relative; top: 3px;" type="checkbox" tabindex="35" value="';

if ($seoData['seo_ignore_prefix'] == '1') {
	echo "1";
	$check = 'checked = "checked"';
} else {
	echo "";
	$check = "";
}

						echo '" name="seo_ignore_prefix[]" class="rex-form-checkbox" ' . $check . '>&nbsp;Kein Prefix</div>
 
						</div>
						<div class="rex-form-row" style="position: relative;"><p class="rex-form-textarea">
							<label for="Beschreibung">Beschreibung</label>
							<textarea name="seo_description" id="seo_description" tabindex="31" rows="2" cols="50" class="rex-form-textarea">' . $seoData['seo_description'] . '</textarea>
							<div style="position: absolute;right:224px;top:69px"><div style="display: inline;" id="description-charcount">0</div>/156 Zeichen
						</div>
					</div>
					<div class="rex-form-row" style="position: relative;">
						<p class="rex-form-textarea">
							<label for="Suchbegriffe">Suchbegriffe</label>
							<textarea name="seo_keywords" id="seo_keywords" tabindex="32" rows="2" cols="50" class="rex-form-textarea">' . $seoData['seo_keywords'] . '</textarea>
							<div style="position: absolute;right:224px;top:69px"><div style="display: inline;" id="keywords-wordcount">0</div>/7 Wörter
						</div>
					</div>
				</fieldset>

				<fieldset class="rex-form-col-1"><legend>URL und Indizierung</legend><div class="rex-form-wrapper">
				<div class="rex-form-row"><p class="rex-form-text" style="margin-bottom: -3px;">
					<label for="custom-url">Benutzerdefinierte URL</label>
					<input type="text" value="' . $seoData['seo_url'] . '" name="seo_url" id="custom-url" tabindex="37" class="rex-form-text">
					</p>

					<div style="display: inline-block;margin-left: 167px; margin-top: 12px;" id="custom-url-preview"></div>
				</div>

				<div class="rex-form-row"><p class="rex-form-col-a rex-form-checkbox">
					<label for="Suchmaschinen_die_Indizierung_nicht_erlauben">Indizierung nicht erlauben</label>
					<input type="checkbox" tabindex="35" id="Suchmaschinen_die_Indizierung_nicht_erlauben" value="';

if ($seoData['seo_noindex'] == '1') {
	echo "1";
	$check = 'checked = "checked"';
} else {
	echo "";
	$check = "";
}

					echo '" name="seo_noindex[]" class="rex-form-checkbox" ' . $check . '>

				</p>
			</div>

			<div class="rex-form-row">
				<p class="rex-form-col-a rex-form-submit">
					<input type="submit" tabindex="39" title="SEO-Daten aktualisieren" value="SEO-Daten aktualisieren" name="saveseo" class="rex-form-submit">
					<br/><br/>
				</p>
			</div>
			<div class="rex-clearer"></div>
		</div>
	</fieldset>
</form>
</div></div></div>';
?>

<style type="text/css">
#seo-page div.rex-form div.rex-form-row {
    padding: 7px 0;
}

#seo-page  #title-preview {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 399px; /* 512px; */
	font-family: arial,sans-serif;
	font-size: normal; /* medium */
	line-height: 16px;
	font-weight: normal;
}

#seo-page #title-preview span {
	font-weight: bold;
}

#seo-page div.rex-form div.rex-form-row label {
	width: 155px;
}

#seo-page div.rex-form div.rex-form-row p input.rex-form-submit {
	margin-left: 165px;
	margin-top: 8px;
}

#seo-page .rex-form-textarea {
	margin-bottom: 12px;
}
</style>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#seo_title').keyup(function() {
		updateTitlePreview();
	});

	jQuery('#prefix-check').change(function() {
		updateTitlePreview();
	});

	jQuery('#seo_description').keyup(function() {
		updateDescriptionCount();
	});

	jQuery('#seo_keywords').keypress(function() {
		updateKeywordsCount();
	});

	jQuery('#custom-url').keyup(function() {
		updateCustomUrlPreview();
	});

	updateTitlePreview();
	updateDescriptionCount();
	updateKeywordsCount();
	updateCustomUrlPreview();

	jQuery('#seo-form').submit(function() {
		var customUrl = jQuery('#custom-url').val();

		if (customUrl === '') {
			return true;
		} else {
			if (customUrl.substring(0, 1) === '/') {
				return true;
			} else {
				alert('Die benutzerdefinierte URL muss mit "/" beginnen.');
				return false;
			}
		}
	});
});

function updateTitlePreview() {
	var titlePrefix = '<?php echo rexseo_lite::getPrefix(); ?>';
	var articleName = '<?php echo rexseo_lite::getArticleName(); ?>';
	var customTitle = jQuery('#seo_title').val();
	var pipeSymbol = '<?php echo rexseo_lite::getTitleDelimiter(); ?>';
	var hasPrefix = !jQuery('#prefix-check').is(':checked');
	var isStartPage = <?php if (rexseo_lite::isStartPage()) { echo 'true'; } else { echo 'false'; } ?>;
	var curTitle = '';
	var curTitlePart = '';

	if (customTitle !== '') {
		curTitlePart = customTitle;
	} else {
		curTitlePart = articleName;
	}

	if (!hasPrefix) {
		curTitle = curTitlePart;
	} else {
		if (isStartPage) {
			curTitle = titlePrefix + ' ' + pipeSymbol + ' ' + curTitlePart;
		} else {
			curTitle = curTitlePart + ' ' + pipeSymbol + ' ' + titlePrefix;
		}
	}

	jQuery('#title-preview').html(curTitle);
}

function updateDescriptionCount() {
	jQuery('#description-charcount').html(jQuery('#seo_description').val().length);
}

function updateCustomUrlPreview() {
	var base = '<?php echo rtrim(rexseo_lite::getBaseUrl(), "/"); ?>';
	var autoUrl = '/' + '<?php echo rex_getUrl($REX["ARTICLE_ID"], $REX["CUR_CLANG"]); ?>';
	var customUrl = jQuery('#custom-url').val();
	var curUrl = '';

	if (customUrl !== '') {
		curUrl = base + customUrl;
	} else {
		curUrl = base + autoUrl;
	}

	jQuery('#custom-url-preview').html(curUrl);
}

function updateKeywordsCount() {
	var curKeywords = jQuery('#seo_keywords').val().replace(' ', '');
	var keywordCount = 0; 

	if (curKeywords !== '') {
		keywordCount = curKeywords.split(',').length;
	}

	jQuery('#keywords-wordcount').html(keywordCount);
}
</script>

