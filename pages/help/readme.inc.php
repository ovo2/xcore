<?php
$file = $REX['INCLUDE_PATH'] . '/addons/seo42/README.md';
$md = file_get_contents($file);
$html = seo42_utils::getHtmlfromMarkdown($md);

$html = str_replace('href="CHANGELOG.md"', 'href="index.php?page=seo42&subpage=help&chapter=changelog"', $html);
$html = str_replace('href="LICENSE.md"', 'href="index.php?page=seo42&subpage=help&chapter=license"', $html);

echo $html;
?>
