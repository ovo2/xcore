<?php

if (!class_exists("Michelf\Markdown")) {
	require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.markdown.inc.php');
}

$mypage = rex_request('page','string');
$subpage = rex_request('subpage', 'string');
$chapter = rex_request('chapter', 'string');
$func = rex_request('func', 'string');

$chapterpages = array (
	'' => array($I18N->msg('seo42_help_chapter_readme'), 'pages/help/readme.inc.php'),
	'codeexamples' => array($I18N->msg('seo42_help_chapter_codeexamples'), 'pages/help/code_examples.inc.php'),
	'faq' => array($I18N->msg('seo42_help_chapter_faq'), 'pages/help/faq.inc.php'),
	'changelog' => array($I18N->msg('seo42_help_chapter_changelog'), 'pages/help/changelog.inc.php'),
	'license' => array('', 'pages/help/license.inc.php'),
	'links' => array($I18N->msg('seo42_help_chapter_links'), 'pages/help/links.inc.php'),
	'debug' => array($I18N->msg('seo42_help_chapter_debug'), 'pages/help/debug.inc.php')
);

// build chapter navigation
$chapternav = '';

foreach ($chapterpages as $chapterparam => $chapterprops) {
	if ($chapterprops[0] != '') {
		if ($chapter != $chapterparam) {
			$chapternav .= ' | <a href="?page=' . $mypage . '&amp;subpage=' . $subpage . '&amp;chapter=' . $chapterparam . '">' . $chapterprops[0] . '</a>';
		} else {
			$chapternav .= ' | <a class="rex-active" href="?page=' . $mypage . '&amp;subpage=' . $subpage . '&amp;chapter=' . $chapterparam . '">' . $chapterprops[0] . '</a>';
		}
	}
}
$chapternav = ltrim($chapternav, " | ");

// build chapter output
$addonroot = $REX['INCLUDE_PATH']. '/addons/'.$mypage.'/';
$source    = $chapterpages[$chapter][1];

// output
echo '
<div class="rex-addon-output" id="subpage-'.$subpage.'">
  <h2 class="rex-hl2" style="font-size:1em">'.$chapternav.'</h2>
  <div class="rex-addon-content">
    <div class= "addon-template">
    ';

include($addonroot . $source);

echo '
    </div>
  </div>
</div>';

?>

<style type="text/css">
div.rex-addon-content p.rex-code {
	margin-bottom: 22px;
}

.addon-template h1 {
	font-size: 18px;
	margin-bottom: 7px;
}

#subpage-help a.rex-active {
    color: #14568A;
}

#subpage-help div.rex-addon-content {
    padding: 10px 12px;
}

#subpage-help div.rex-addon-content ul {
	margin-top: 0;
}
</style>

