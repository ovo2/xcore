<?php

$rexxContent = file_get_contents($this->getPath('boot.php'));
$content = '<h2>' . $this->i18n('features_boot') . '</h2>';
$content .= '<code><pre>' . highlight_string($rexxContent, true)  . '</pre></code>';

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('features'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');

