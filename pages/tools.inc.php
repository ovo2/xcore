<?php
require($REX['INCLUDE_PATH'] . '/addons/rexseo42/classes/class.rexseo42_tool.inc.php');
require($REX['INCLUDE_PATH'] . '/addons/rexseo42/classes/class.rexseo42_tool_manager.inc.php');

$toolManager = new rexseo42_tool_manager();

$tool = new rexseo42_tool($I18N->msg('rexseo42_tool1'), $I18N->msg('rexseo42_tool1_desc', rexseo42::getServer()), 'http://www.google.com/search?q=site:' . rexseo42::getServer());
$toolManager->addTool($tool);

$tool = new rexseo42_tool($I18N->msg('rexseo42_tool3'), $I18N->msg('rexseo42_tool3_desc'), 'http://www.google.com/webmasters/tools/');
$toolManager->addTool($tool);

$tool = new rexseo42_tool($I18N->msg('rexseo42_tool2'), $I18N->msg('rexseo42_tool2_desc'), 'http://www.google.com/webmasters/tools/submit-url');
$toolManager->addTool($tool);

$tool = new rexseo42_tool($I18N->msg('rexseo42_tool4'), $I18N->msg('rexseo42_tool4_desc'), 'http://www.gaijin.at/olsgprank.php');
$toolManager->addTool($tool);

$tool = new rexseo42_tool($I18N->msg('rexseo42_tool6'), $I18N->msg('rexseo42_tool6_desc'), 'http://www.seitwert.de/#quick');
$toolManager->addTool($tool);

$tool = new rexseo42_tool($I18N->msg('rexseo42_tool8'), $I18N->msg('rexseo42_tool8_desc'), 'http://www.seomofo.com/snippet-optimizer.html');
$toolManager->addTool($tool);

$toolManager->printToolList($I18N->msg('rexseo42_tools_caption'));
?>

<style type="text/css">
table.rex-table th {
	font-size: 1.2em;
}

table.rex-table td p {
	margin-top: 6px;
	color: #32353A;
}

table.rex-table td p.url {
	color: grey;
}

.rex-table td a,
.rex-table td span {
	font-weight: bold;
}
</style>

