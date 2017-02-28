<?php

$content = '<h2>' . $this->i18n('config_object') . '</h2>';
$content .= '<p>Bitte momentan noch über <code>rex_config::set("xcore", "the_key", "my_value");</code> einmalig (!) von Hand einstellen. Danach bitte den Cache löschen.</p>';
$content .= '<code><pre>' . print_r($this->getConfig(), true) . '</pre></code>';

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('config'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');

