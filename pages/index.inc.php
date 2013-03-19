<?php
$myself        = rex_request('page', 'string');
$subpage       = rex_request('subpage', 'string')!='' ? rex_request('subpage', 'string'): 'options';
$chapter       = rex_request('chapter', 'string');
$func          = rex_request('func', 'string');
$section_id    = rex_request('section_id', 'string');
$section_class = rex_request('section_class', 'string');
$highlight     = rex_request('highlight', 'string');
$myroot        = $REX['INCLUDE_PATH'].'/addons/'.$myself;

// includes
require_once $myroot.'/functions/function.rexseo_helpers.inc.php';
require_once $myroot.'/classes/class.rexseo_rewrite.inc.php';

// layout top
require $REX['INCLUDE_PATH'] . '/layout/top.php';

// title
rex_title($REX['ADDON']['name'][$myself] . ' <span class="version">' . $REX['ADDON']['version'][$myself] . '</span>', $REX['ADDON'][$myself]['SUBPAGES']);

// subpages
switch($subpage){
	case'':
		$subpage = 'options';
	case'options':
	case'tools':
	case'setup':
	case'help':
		$local_path = '/addons/'.$myself.'/pages/';
		break;
	default:
		$local_path = '/addons/'.$myself.'/plugins/'.$subpage.'/';
}

require $REX['INCLUDE_PATH'].$local_path.$subpage.'.inc.php';
?>

<style type="text/css">
#rex-title .version {
	font-size:14px; 
	color:silver;
}

#rex-page-rexseo42 a.extern {
	background: transparent url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAcAAAA8CAYAAACq76C9AAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAFFSURBVHjaYtTpO/CfAQcACCAmBjwAIIAY//9HaNTtP4hiCkAAMeGSAAGAAGJCl7hcaM8IYwMEEBMuCRAACCAmXBIgABBAKA5CBwABhNcrAAGEVxIggPBKAgQQXkmAAMIrCRBAeCUBAgivJEAA4ZUECCC8kgABhFcSIIDwSgIEEF5JgADCKwkQQHglAQIIryRAAOGVBAggvJIAAYRXEiCA8EoCBBBeSYAAwisJEEB4JQECiAVbNoABgADCqxMggPDmMoAAwpvLAAIIby4DCCC8uQwggPDmMoAAwpvLAAIIr1cAAgivJEAA4ZUECCC8kgABhFcSIIDwSgIEEF5JgADCKwkQQHglAQIIryRAAOGVBAggvJIAAYRXEiCA8EoCBBBeSYAAwisJEEB4JQECCK8kQADhlQQIILySAAGEVxIggPBKAgQYAARTLlfrU5G2AAAAAElFTkSuQmCC) no-repeat right 3px;
	padding-right: 10px;
}
</style>

<?php
// layout bottom
require $REX['INCLUDE_PATH'] . '/layout/bottom.php';

