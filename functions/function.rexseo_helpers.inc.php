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


  function rexseo_recursive_copy($source, $target, $makedir=TRUE, &$result=array(), $counter=1, $folderPermission='', $filePermission='')
  {
    global $REX;
    $folderPermission = (empty($folderPermission)) ? $REX['DIRPERM'] : $folderPermission;
    $filePermission = (empty($filePermission)) ? $REX['FILEPERM'] : $filePermission;

    // SCAN SOURCE DIR WHILE IGNORING  CERTAIN FILES
    $ignore = array('.DS_Store','.svn','.','..');
    $dirscan = array_diff(scandir($source), $ignore);

    // WALK THROUGH RESULT RECURSIVELY
    foreach($dirscan as $item)
    {

      // DO DIR STUFF
      if (is_dir($source.$item)) /* ITEM IS A DIR */
      {
        if(!is_dir($target.$item) && $makedir=TRUE) /* DIR NONEXISTANT IN TARGET */
        {
          if(mkdir($target.$item)) /* CREATE DIR IN TARGET */
          {
            if(chmod($source.$item,$folderPermission))
            {
            }
            else
            {
              echo rex_warning('Rechte für "'.$target.$item.'" konnten nicht gesetzt werden!');
            }
          }
          else
          {
            echo rex_warning('Das Verzeichnis '.$source.$item.' konnte nicht angelegt werden!');
          }
        }

        // RECURSION
        rexseo_recursive_copy($source.$item.'/', $target.$item.'/', $makedir, $result, $counter);
      }

      // DO FILE STUFF
      elseif (is_file($source.$item)) /* ITEM IS A FILE */
      {
        if (rex_is_writable($target)) /* CHECK WRITE PERMISSION */
        {
          if(is_file($target.$item)) /* FILE EXISTS IN TARGET */
          {
            {
              if(!copy($source.$item,$target.$item))
              {
                $result[$counter]['path'] = $target;
                $result[$counter]['item'] = $item;
                $result[$counter]['copystate'] = 0;
                echo rex_warning('Datei "'.$target.$item.'" konnte nicht geschrieben werden!');
              }
              else
              {
                $result[$counter]['path'] = $target;
                $result[$counter]['item'] = $item;
                if(chmod($target.$item,$filePermission))
                {
                  $result[$counter]['copystate'] = 1;
                  //echo rex_info('Datei "'.$target.$item.'" wurde angelegt.');
                }
                else
                {
                  $result[$counter]['copystate'] = 0;
                  echo rex_warning('Rechte für "'.$target.$item.'" konnten nicht gesetzt werden!');
                }
              }
            }
          }
          else
          {
            if(!copy($source.$item,$target.$item))
            {
              $result[$counter]['path'] = $target;
              $result[$counter]['item'] = $item;
              $result[$counter]['copystate'] = 0;
              echo rex_warning('Datei "'.$target.$item.'" konnte nicht geschrieben werden!');
            }
            else
            {
              $result[$counter]['path'] = $target;
              $result[$counter]['item'] = $item;
              if(chmod($target.$item,$filePermission))
              {
                $result[$counter]['copystate'] = 1;
                //echo rex_info('Datei "'.$target.$item.'" wurde erfolgreich angelegt.');
              }
              else
              {
                $result[$counter]['copystate'] = 0;
                echo rex_warning('Rechte für "'.$target.$item.'" konnten nicht gesetzt werden!');
              }
            }
          }
        }
        else
        {
          echo rex_warning('Keine Schreibrechte für das Verzeichnis "'.$target.'" !');
        }
      }
      $counter++;
    }
    return $result;
  }



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
