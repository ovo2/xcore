<?php
$page    = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$chapter = rex_request('chapter', 'string');
$func    = rex_request('func', 'string');
$myroot  = $REX['INCLUDE_PATH'].'/addons/' . $page;
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2">FAQ</h2>
	<div class="rex-area-content">
		<h2><?php echo $I18N->msg('rexseo42_faq01a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq01b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq02a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq02b',  $REX['ADDON']['name']['rexseo42']); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq03a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq03b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq04a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq04b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq05a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq05b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq06a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq06b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq07a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq07b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq08a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq08b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq09a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq09b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq10a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq10b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq11a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq11b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq12a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq12b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq13a',  $REX['ADDON']['name']['rexseo42']); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq13b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq14a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq14b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq15a',  $REX['ADDON']['name']['rexseo42']); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq15b'); ?></p>

		<h2><?php echo $I18N->msg('rexseo42_faq16a'); ?></h2>
		<p><?php echo $I18N->msg('rexseo42_faq16b'); ?></p>
	</div>
</div>

<style type="text/css">
#rex-page-rexseo42 .rex-area-content {
	padding: 12px;
}

#rex-page-rexseo42 .rex-area-content h2 {
	font-size: 13px;
}

#rex-page-rexseo42 .rex-area-content p {
	margin-bottom: 15px;
}
</style>

