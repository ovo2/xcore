<?php
$myself = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');

// save settings
if ($func == 'update') {
	$settings = (array) rex_post('settings', 'array', array());
	$msg = seo42_utils::checkDirForFile(SEO42_SETTINGS_FILE);

	if ($msg != '') {
		echo rex_warning($msg);
	} else {
		// type conversion
		foreach ($REX['ADDON']['seo42']['settings'] as $key => $value) {
			if (isset($settings[$key])) {
				$settings[$key] = seo42_utils::convertVarType($value, $settings[$key]);
			}
		}

		// merge
		$REX['ADDON']['seo42']['settings'] = array_merge((array) $REX['ADDON']['seo42']['settings'], $settings);

		// write
		$content = "<?php\n\n";
		
		foreach ((array) $REX['ADDON']['seo42']['settings'] as $key => $value) {
			if (!isset($REX['ADDON']['seo42']['website_settings'][$key])) {
				$content .= "\$REX['ADDON']['seo42']['settings']['$key'] = " . var_export($value, true) . ";\n";
			}
		}

		if (rex_put_file_contents(SEO42_SETTINGS_FILE, $content)) {
			echo rex_info($I18N->msg('seo42_config_ok'));
		} else {
			echo rex_warning($I18N->msg('seo42_config_error'));
		}

		seo42_utils::updateWebsiteSettingsFile($settings);
		seo42_generate_pathlist('');
	}
}

// url ending select box
$url_ending_select = new rex_select();
$url_ending_select->setSize(1);
$url_ending_select->setName('settings[url_ending]');
$url_ending_select->addOption('.html','.html');
$url_ending_select->addOption('/','/');
$url_ending_select->addOption($I18N->msg('seo42_settings_url_ending_without'), '');
$url_ending_select->setAttribute('style','width:70px;');
$url_ending_select->setSelected($REX['ADDON'][$myself]['settings']['url_ending']);

// home url select box
$ooa = OOArticle::getArticleById($REX['START_ARTICLE_ID']);

if ($ooa) {
  $homename = strtolower($ooa->getName());
} else {
  $homename = 'Startartikel';
}

unset($ooa);

$homeurl_select = new rex_select();
$homeurl_select->setSize(1);
$homeurl_select->setName('settings[homeurl]');
$homeurl_select->addOption(seo42::getServerUrl().$homename.'.html',0);
$homeurl_select->addOption(seo42::getServerUrl(),1);
$homeurl_select->addOption(seo42::getServerUrl().'lang-slug/',2);
$homeurl_select->setAttribute('style','width:250px;');
$homeurl_select->setSelected($REX['ADDON'][$myself]['settings']['homeurl']);

// lang slug select box
if (count($REX['CLANG']) > 1) {
  $hide_langslug_select = new rex_select();
  $hide_langslug_select->setSize(1);
  $hide_langslug_select->setName('settings[hide_langslug]');
  $hide_langslug_select->addOption($I18N->msg('seo42_settings_langslug_all'),-1);

  foreach($REX['CLANG'] as $id => $str) {
    $hide_langslug_select->addOption($I18N->msg('seo42_settings_langslug_noslug') . ' '.$str,$id);
  }

  $hide_langslug_select->setSelected($REX['ADDON'][$myself]['settings']['hide_langslug']);
  $hide_langslug_select = '
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="hide_langslug">' . $I18N->msg('seo42_settings_langslug') . '</label>
                '.$hide_langslug_select->get().'
                </p>
          </div><!-- /rex-form-row -->';
} else {
  $hide_langslug_select = '';
}

// home lang select box
if (count($REX['CLANG']) > 1) {
  $homelang_select = new rex_select();
  $homelang_select->setSize(1);
  $homelang_select->setName('[homelang]');

  foreach($REX['CLANG'] as $id => $str) {
    $homelang_select->addOption($str,$id);
  }

  $homelang_select->setSelected($REX['ADDON'][$myself]['settings']['homelang']);
  $homelang_select->setAttribute('style','width:70px;margin-left:20px;');
  $homelang_box = '
              <span style="margin:0 4px 0 4px;display:inline-block;width:100px;text-align:right;">
                ' . $I18N->msg('seo42_settings_language') . '
              </span>
              '.$homelang_select->get().'
              ';
} else {
  $homelang_box = '';
}

$auto_redirects_select = new rex_select();
$auto_redirects_select->setSize(1);
$auto_redirects_select->setName('settings[auto_redirects]');
$auto_redirects_select->addOption($I18N->msg('seo42_settings_auto_redirects_0'), '0');
$auto_redirects_select->addOption($I18N->msg('seo42_settings_auto_redirects_1'), '1');
$auto_redirects_select->addOption($I18N->msg('seo42_settings_auto_redirects_2'), '2');
$auto_redirects_select->addOption($I18N->msg('seo42_settings_auto_redirects_3'), '3');
$auto_redirects_select->setSelected($REX['ADDON'][$myself]['settings']['auto_redirects']);

?>

<div class="rex-addon-output">
  <div class="rex-form">

  <form action="index.php" method="post">
    <input type="hidden" name="page" value="seo42" />
    <input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
    <input type="hidden" name="func" value="update" />

      <fieldset class="rex-form-col-1">
        <legend><?php echo $I18N->msg('seo42_settings_main_section'); ?></legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="url_ending"><?php echo $I18N->msg('seo42_settings_url_ending'); ?></label>
               <?php echo $url_ending_select->get(); ?>
            </p>
          </div>

          <?php echo $hide_langslug_select; ?>

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="homeurl"><?php echo $I18N->msg('seo42_settings_startpage'); ?></label>
                <?php echo $homeurl_select->get(); ?>
                <?php echo $homelang_box; ?>
            </p>
          </div>

		 <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="auto_redirects"><?php echo $I18N->msg('seo42_settings_auto_redirects'); ?></label>
               <?php echo $auto_redirects_select->get(); ?>
            </p>
          </div>
		</div>
       </fieldset>

    <fieldset class="rex-form-col-1">
        <legend><?php echo $I18N->msg('seo42_settings_resource_section'); ?></legend>
        <div class="rex-form-wrapper">

          	<div class="rex-form-row rex-form-element-v1">
				<p class="rex-form-text">
					<label for="css_dir"><?php echo $I18N->msg('seo42_settings_css_dir'); ?></label>
					<input type="text" value="<?php echo $REX['ADDON']['seo42']['settings']['css_dir']; ?>" name="settings[css_dir]" class="rex-form-text" id="css_dir">
				</p>
			</div>

			<div class="rex-form-row rex-form-element-v1">
				<p class="rex-form-text">
					<label for="js_dir"><?php echo $I18N->msg('seo42_settings_js_dir'); ?></label>
					<input type="text" value="<?php echo $REX['ADDON']['seo42']['settings']['js_dir']; ?>" name="settings[js_dir]" class="rex-form-text" id="js_dir">
				</p>
			</div>

			<div class="rex-form-row rex-form-element-v1">
				<p class="rex-form-text">
					<label for="images_dir"><?php echo $I18N->msg('seo42_settings_images_dir'); ?></label>
					<input type="text" value="<?php echo $REX['ADDON']['seo42']['settings']['images_dir']; ?>" name="settings[images_dir]" class="rex-form-text" id="images_dir">
				</p>
			</div>

			<div class="rex-form-row rex-form-element-v1">
				<p class="rex-form-text">
					<label for="icons_dir"><?php echo $I18N->msg('seo42_settings_icons_dir'); ?></label>
					<input type="text" value="<?php echo $REX['ADDON']['seo42']['settings']['icons_dir']; ?>" name="settings[icons_dir]" class="rex-form-text" id="icons_dir">
				</p>
			</div>

		</div>
       </fieldset>

      <fieldset class="rex-form-col-1">
        <legend><?php echo $I18N->msg('seo42_settings_robots_sitemap_section'); ?></legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="robots"><?php echo $I18N->msg('seo42_settings_robots_additional'); ?></label>
              <textarea name="settings[robots]" rows="2"><?php echo stripslashes($REX['ADDON'][$myself]['website_settings']['robots']); ?></textarea>
            </p>
          </div>

		  <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="robots-txt"><?php echo $I18N->msg('seo42_settings_robots_link'); ?></label>
              <span class="rex-form-read" id="robots-txt"><a href="<?php echo seo42::getBaseUrl(); ?>robots.txt" target="_blank"><?php echo seo42::getBaseUrl(); ?>robots.txt</a></span>
            </p>
          </div>

		<div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="xml-sitemap"><?php echo $I18N->msg('seo42_settings_sitemap_link'); ?></label>
              <span class="rex-form-read" id="xml-sitemap"><a href="<?php echo seo42::getBaseUrl(); ?>sitemap.xml" target="_blank"><?php echo seo42::getBaseUrl(); ?>sitemap.xml</a></span>
            </p>
          </div>

        </div>
      </fieldset>

    <fieldset class="rex-form-col-1">
        <legend><?php echo $I18N->msg('seo42_settings_misc_section'); ?></legend>
        <div class="rex-form-wrapper">

			<div class="rex-form-row rex-form-element-v1">
				<p class="rex-form-checkbox">
					<label for="send_header_x_ua_compatible"><?php echo $I18N->msg('seo42_settings_send_header_x_ua_compatible'); ?></label>
					<input type="hidden" name="settings[send_header_x_ua_compatible]" value="0" />
					<input type="checkbox" name="settings[send_header_x_ua_compatible]" id="send_header_x_ua_compatible" value="1" <?php if ($REX['ADDON']['seo42']['settings']['send_header_x_ua_compatible']) { echo 'checked="checked"'; } ?>>
				</p>
			</div>
		</div>
       </fieldset>

	<fieldset class="rex-form-col-1">
      <legend><?php echo $I18N->msg('seo42_settings_advanced_settings_section'); ?></legend>
      <div class="rex-form-wrapper">
		<div class="rex-form-row rex-form-element-v1">
			<p class="rex-form-col-a rex-form-read">
				<label for="show-settings"><?php echo $I18N->msg('seo42_settings_show_all'); ?></label>
				<span class="rex-form-read"><a id="show-settings" href="#"><?php echo $I18N->msg('seo42_settings_show'); ?></a></span>
			</p>
		</div>

		<div id="all-settings" style="display: none;" class="rex-form-row rex-form-element-v1">
			<p class="rex-form-col-a rex-form-read">
				<pre class="rex-code"><?php echo seo42_utils::print_r_pretty($REX['ADDON']['seo42']['settings']); ?></pre>
				<br />
				<pre class="rex-code"><?php echo seo42_utils::print_r_pretty($REX['ADDON']['seo42']['website_settings']); ?></pre>
			</p>
		</div>

        </div>
      </fieldset>

      <fieldset class="rex-form-col-1">
        <legend>&nbsp;</legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row rex-form-element-v2">

            <p class="rex-form-submit">
              <input style="margin-top: 5px; margin-bottom: 5px;" class="rex-form-submit" type="submit" id="sendit" name="sendit" value="<?php echo $I18N->msg('seo42_settings_submit'); ?>" />
            </p>
          </div>

        </div>
      </fieldset>

  </form>
  </div>
</div>

<?php
unset($homeurl_select,$url_ending_select);
?>

<style type="text/css">
#lang_hint {
	width: auto;
}
</style>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#show-settings').toggle( 
			function() {
				$('#all-settings').slideDown(); 
				$('#show-settings').html('<?php echo $I18N->msg('seo42_settings_hide'); ?>');
			}, 
			function() { 
				$('#all-settings').slideUp(); 
				$('#show-settings').html('<?php echo $I18N->msg('seo42_settings_show'); ?>');
			} 
		);
	});
</script>


