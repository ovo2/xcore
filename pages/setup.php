<div id="rexx-setup">
<?php

$content = '<h2>' . $this->i18n('setup_msg1') . '</h2>';
$content .= '<p>Stellen Sie sicher dass unter yRewrite > Domaineinstellungen und unter System > Einstellungen die selben Werte wie Url, Fehlerartikel etc. gesetzt sind.</p>';

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('setup_step1'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');



$rexxContent = file_get_contents($this->getPath('install/_htaccess'));
$content = '<h2>' . $this->i18n('setup_msg2') . '</h2>';
$content .= '<p>Mitgelieferte <code>/xcore/install/_htaccess</code> kopieren in das root Verzeichnis dieser Website und dort in <code>.htaccess</code> umbenennen.<br />Wenn Sie einen <strong>500 Serverfehler</strong> bekommen entfernen Sie die Zeile <code>Options -Indexes</code>.</p>';
$content .= '<code><pre>' . highlight_string($rexxContent, true)  . '</pre></code>';

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('setup_step2'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');



$rexxContent = file_get_contents($this->getPath('install/template.php'));
$content = '<h2>' . $this->i18n('setup_msg3') . '</h2>';
$content .= '<p>Benutzen Sie die rexx API und passen Sie Ihre Templates an.</p>';
$content .= '<code><pre>' . highlight_string($rexxContent, true)  . '</pre></code>';

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('setup_step3'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
?>
</div>
