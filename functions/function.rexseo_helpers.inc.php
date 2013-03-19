<?php

function rexseo_sanitizeString($string) {
	return preg_replace("/\s\s+/", " ", $string);
}

function rexseo_afterDBImport($params) {
	global $REX, $I18N;

	$sqlStatement = 'SELECT seo_title, seo_description, seo_keywords, seo_url, seo_noindex, seo_ignore_prefix FROM ' . $REX['TABLE_PREFIX'] . 'article';
	$sql = rex_sql::factory();
	$sql->setQuery($sqlStatement);

	// check for db fields
	if ($sql->getRows() == 0) {
		require($REX['INCLUDE_PATH'] . '/addons/rexseo42/install.inc.php');
		echo rex_info($I18N->msg('rexseo42_dbfields_readded', $REX['ADDON']['name']['rexseo42']));
	}
}

function rexseo_showMsgAfterClangModified($params) {
	global $I18N, $REX;

	echo rex_info($I18N->msg('rexseo42_check_langcodes_msg', $REX['ADDON']['name']['rexseo42']));
}

////////////////////////////////////////////////////////////////////////////////
// http://php.net/manual/de/function.include.php
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('get_include_contents'))
{
  function get_include_contents($filename) {
    if (is_file($filename)) {
      ob_start();
      include $filename;
      $new_redirectss = ob_get_contents();
      ob_end_clean();
      return $new_redirectss;
    }
    return false;
  }
}

// REDAXO INSTALL ORDNER ERMITTELN
////////////////////////////////////////////////////////////////////////////////
function rexseo_subdir()
{
  global $REX;
  $path_diff = $REX['REDAXO'] ? array('index.php','redaxo'):array('index.php');
  $install_subdir = array_diff_assoc(array_reverse(explode('/',trim($_SERVER['SCRIPT_NAME'],'/'))),$path_diff);
  $rexseo_subdir = count($install_subdir)>0 ? implode('/',array_reverse($install_subdir)).'/' :'';
  return $rexseo_subdir;
}

// PARAMS CAST FUNCTIONS
////////////////////////////////////////////////////////////////////////////////
function rexseo_nl_2_array($str)
{
  $arr = array_filter(preg_split("/\n|\r\n|\r/", $str));
  return is_array($arr) ? $arr : array($arr);
}

function rexseo_array_2_nl($arr)
{
  return count($arr)>0 ? implode(PHP_EOL,$arr) : '';
}

function rexseo_301_2_array($str)
{
  $arr = array();
  $tmp = array_filter(preg_split("/\n|\r\n|\r/", $str));
  foreach($tmp as $k => $v)
  {
    $a = explode(' ',trim($v));
    $arr[trim(ltrim($a[0],'/'))] = array('article_id'=>intval($a[1]),'clang'=>intval($a[2]));
  }
  return $arr;
}

function rexseo_301_2_string($arr)
{
  $str = '';
  foreach($arr as $k => $v)
  {
    $str .= $k.' '.$v['article_id'].' '.$v['clang'].PHP_EOL;
  }
  return $str;
}

function rexseo_batch_cast($request,$conf)
{
  if(is_array($request) && is_array($conf))
  {
    foreach($conf as $key => $cast)
    {
      switch($cast)
      {
        case 'unset':
          unset($request[$key]);
          break;

        case '301_2_array':
          $request[$key] = rexseo_301_2_array($request[$key]);
          break;

        case 'nl_2_array':
          $request[$key] = rexseo_nl_2_array($request[$key]);
          break;

        default:
          $request[$key] = rex_request($key,$cast);
      }
    }
    return $request;
  }
  else
  {
    trigger_error('wrong input type, array expected', E_USER_ERROR);
  }
}

// FIX INTERNAL LAINKAS FOR TINY/TEXTILE
////////////////////////////////////////////////////////////////////////////////
function rexseo_fix_42x_links($params)
{
  global $REX;

  $subdir = $REX['ADDON']['rexseo42']['settings']['install_subdir'];
  if($subdir=='')
  {
    $relpath     = '/redaxo/';
    $replacement = '/';
  }
  else
  {
    $relpath     = '/'.$subdir.'redaxo/';
    $replacement = '/'.$subdir;
  }

  // textile, tiny
  return str_replace(
    array('&quot;:'.$relpath, '"'.$relpath),
    array('&quot;:'.$replacement, '"'.$replacement),
    $params['subject']
  );
}

/**
 * legacy function
 **/
function rexseo_htaccess_update_redirects(){
  if(OOPlugin::isAvailable('rexseo42','redirects_manager')){
    redirects_manager::updateHtaccess();
  }
}
