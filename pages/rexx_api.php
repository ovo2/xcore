<?php

$rexxContent = file_get_contents($this->getPath('lib/rexx.php'));
$content = '<h2>' . $this->i18n('rexx_class') . '</h2>';
$content .= '<code><pre>' . highlight_string($rexxContent, true)  . '</pre></code>';

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('rexx_api'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');

