<?php if ($REX['ADDON']['seo42']['settings']['pagerank_checker']) { ?>
<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('seo42_pr_tool'); ?></h2>
	<div class="rex-area-content">
		<div class="tool-icon"></div>
		<p><?php echo $I18N->msg('seo42_pr_tool_msg') . ' ' . seo42::getServerWithSubdir() ?>.</p>
		<p class="pr"><?php echo $I18N->msg('seo42_pr_tool_pagerank'); ?>: <span id="pagerank">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
	</div>
</div>
<?php } ?>

<?php
require($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.seo42_tool.inc.php');
require($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.seo42_tool_manager.inc.php');

$toolManager = new seo42_tool_manager();

$tool = new seo42_tool($I18N->msg('seo42_tool1'), $I18N->msg('seo42_tool1_desc', seo42::getServerWithSubDir()), 'http://www.google.com/search?q=site:' . seo42::getServerWithSubDir());
$toolManager->addTool($tool);

$tool = new seo42_tool($I18N->msg('seo42_tool3'), $I18N->msg('seo42_tool3_desc'), 'http://www.google.com/webmasters/tools/');
$toolManager->addTool($tool);

$tool = new seo42_tool($I18N->msg('seo42_tool2'), $I18N->msg('seo42_tool2_desc'), 'http://www.google.com/webmasters/tools/submit-url');
$toolManager->addTool($tool);

if (!$REX['ADDON']['seo42']['settings']['pagerank_checker']) {
	$tool = new seo42_tool($I18N->msg('seo42_tool4'), $I18N->msg('seo42_tool4_desc'), 'http://www.gaijin.at/olsgprank.php');
	$toolManager->addTool($tool);
}

$tool = new seo42_tool($I18N->msg('seo42_tool6'), $I18N->msg('seo42_tool6_desc'), 'http://www.seitwert.de/#quick');
$toolManager->addTool($tool);

$tool = new seo42_tool($I18N->msg('seo42_tool8'), $I18N->msg('seo42_tool8_desc'), 'http://www.seomofo.com/snippet-optimizer.html');
$toolManager->addTool($tool);

$toolManager->printToolList($I18N->msg('seo42_tools_caption'));
?>

<style type="text/css">
table.rex-table th {
	font-size: 1.2em;
}

table.rex-table td {
	padding: 11px 5px;
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

div.rex-area-content p.pr {
	line-height: 24px;
	position: relative;
}

#pagerank {
	position: absolute;
	top: 0;
	margin-left: 5px;
	font-size: 17px;
	background: transparent url("../files/addons/seo42/loading.gif") no-repeat left 3px;
}

#pagerank.success,
#pagerank.failure {
	background: transparent;
}

#pagerank.success {
	font-weight: bold;
}

#pagerank.failure {
	font-style: italic;
	font-size: 100%;
}

.rex-hl2 {
	font-size: 1.2em;
}
</style>

<?php if ($REX['ADDON']['seo42']['settings']['pagerank_checker']) { ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	// ajax call for pagerank checker
	$.ajax({
		url: window.location.pathname + '?function=getpagerank&url=<?php echo seo42::getServerWithSubdir(); ?>',
		type : 'POST',
		success : function (result) {
			if (result === '') {
				$('#pagerank').addClass('failure');
				$('#pagerank').html('<?php echo $I18N->msg('seo42_pr_tool_failure'); ?>');
			} else {
				$('#pagerank').addClass('success');
				$('#pagerank').html(result);
			}
		},
		error : function () {
			$('#pagerank').addClass('failure');
			$('#pagerank').html('<?php echo $I18N->msg('seo42_pr_tool_failure'); ?>');
		}
	});
});
</script>
<?php } ?>

