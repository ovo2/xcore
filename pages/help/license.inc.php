<?php
$file = $REX['INCLUDE_PATH'] . '/addons/seo42/LICENSE.md';
$md = file_get_contents($file);
$html = seo42_utils::getHtmlfromMarkdown($md);

$html = str_replace('<h1>SEO42 - ', '<h1>', $html);

echo $html;
?>
