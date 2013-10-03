<?php
$file = $REX['INCLUDE_PATH'] . '/addons/seo42/FAQ.md';
$md = file_get_contents($file);
$html = seo42_utils::getHtmlfromMarkdown($md);

echo $html;
?>

