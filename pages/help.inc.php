<?php
$mypage  = rex_request('page',    'string');
$subpage = rex_request('subpage', 'string');
$chapter = rex_request('chapter', 'string');
$func    = rex_request('func',    'string');

$chapterpages = array (
	'' => array('FAQ', 'pages/help/faq.inc.php'),
	'codeexamples' => array('Codebeispiele', 'pages/help/code_examples.inc.php'),
	'links' => array('Links', 'pages/help/links.inc.php')
);

// build chapter navigation
$chapternav = '';

foreach ($chapterpages as $chapterparam => $chapterprops) {
  if ($chapter != $chapterparam) {
    $chapternav .= ' | <a href="?page='.$mypage.'&subpage='.$subpage.'&chapter='.$chapterparam.'" class="chapter '.$chapterparam.'">'.$chapterprops[0].'</a>';
  } else {
    $chapternav .= ' | <a class="rex-active" href="?page='.$mypage.'&subpage='.$subpage.'&chapter='.$chapterparam.'" class="chapter '.$chapterparam.'">'.$chapterprops[0].'</a>';
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
</style>

