<?php
$myself  = rex_request('page',            'string');
$subpage = rex_request('subpage',         'string');
$func    = rex_request('func',            'string');
$backup  = $REX['INCLUDE_PATH'].'/backup/addons/rexseo42/config.inc.php';
$table   = $REX['TABLE_PREFIX'].'rexseo_redirects';

$CAST = array (
      'page'                       => 'unset',
      'subpage'                    => 'unset',
      'func'                       => 'unset',
      'submit'                     => 'unset',
      'sendit'                     => 'unset',
      'homeurl'                    => 'int',
      'homelang'                   => 'int',
      'allow_articleid'            => 'int',
      'hide_langslug'              => 'int',
	  'one_page_mode'              => 'int',
      );


// UPDATE/SAVE SETTINGS
////////////////////////////////////////////////////////////////////////////////
if ($func == 'update')
{
  // GET ADDON SETTINGS FROM REQUEST
  $myCONF = rexseo_batch_cast($_POST,$CAST);

  // UPDATE REX
  $REX['ADDON'][$myself]['settings'] = $myCONF;

  // SAVE ADDON SETTINGS
  $DYN    = '$REX["ADDON"]["'.$myself.'"]["settings"] = '.stripslashes(var_export($myCONF,true)).';';
  $config = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/settings.inc.php';
  rex_replace_dynamic_contents($config, $DYN);
  //rex_replace_dynamic_contents($backup, $DYN); // RexDude
  echo rex_info('Einstellungen wurden gespeichert.');
  rexseo_generate_pathlist('');
  //echo rex_info('Pathlist wurden aktuallisiert.');
}

// SUBDIR CHANGE NOTIFY
////////////////////////////////////////////////////////////////////////////////
if($REX['ADDON'][$myself]['settings']['install_subdir'] != rexseo_subdir())
{
  echo rex_warning('ACHTUNG: Das aktuelle Installationsverzeichnis von Redaxo scheint sich ge&auml;ndert zu haben.<br />
                   Zum aktualisieren einmal die RexSEO settings speichern.<br />
                   Evtl. notwendige <a href="index.php?page=seo&subpage=help&chapter=&func=alert_setup&highlight='.urlencode('Installation in Unterverzeichnissen:').'">Anpassung der RewriteBase</a> in der .htaccess beachten!');
}


// TOGGLE REDIRECT
////////////////////////////////////////////////////////////////////////////////
if(rex_request('func','string')=='toggle_redirect' && intval(rex_request('id','int'))>0)
{
  $db = new rex_sql;
  $db->setQuery('UPDATE `'.$table.'` SET `status` = IF(status=1, 0, 1) WHERE `id`='.rex_request('id','int').';');
  rexseo_htaccess_update_redirects();
}


// DELETE REDIRECT
////////////////////////////////////////////////////////////////////////////////
if(rex_request('func','string')=='delete_redirect' && intval(rex_request('id','int'))>0)
{
  $db = new rex_sql;
  $db->setQuery('DELETE FROM `'.$table.'` WHERE `id`='.rex_request('id','int').';');
  rexseo_htaccess_update_redirects();
}


// URL_SCHEMA SELECT BOX
////////////////////////////////////////////////////////////////////////////////
$url_schema_select = new rex_select();
$url_schema_select->setSize(1);
$url_schema_select->setName('url_schema');
$url_schema_select->addOption('RexSEO','rexseo');
$url_schema_select->addOption('url_rewrite','url_rewrite');
$url_schema_select->setAttribute('style','width:250px');
$url_schema_select->setSelected($REX['ADDON'][$myself]['settings']['url_schema']);

// URL_ENDING SELECT BOX
////////////////////////////////////////////////////////////////////////////////
$url_ending_select = new rex_select();
$url_ending_select->setSize(1);
$url_ending_select->setName('url_ending');
$url_ending_select->addOption('.html','.html');
$url_ending_select->addOption('/','/');
$url_ending_select->addOption('(ohne)','');
$url_ending_select->setAttribute('style','width:70px;margin-left:20px;');
$url_ending_select->setSelected($REX['ADDON'][$myself]['settings']['url_ending']);


// HOMEURL SELECT BOX
////////////////////////////////////////////////////////////////////////////////
$ooa = OOArticle::getArticleById($REX['START_ARTICLE_ID']);
if($ooa)
{
  $homename = strtolower($ooa->getName());
}
else
{
  $homename = 'Startartikel';
}
unset($ooa);

$homeurl_select = new rex_select();
$homeurl_select->setSize(1);
$homeurl_select->setName('homeurl');
$homeurl_select->addOption($REX['SERVER'].$homename.'.html',0);
$homeurl_select->addOption($REX['SERVER'],1);
$homeurl_select->addOption($REX['SERVER'].'lang-slug/',2);
$homeurl_select->setAttribute('style','width:250px;');
$homeurl_select->setSelected($REX['ADDON'][$myself]['settings']['homeurl']);


// LANGSLUG SELECT BOX
////////////////////////////////////////////////////////////////////////////////
if(count($REX['CLANG']) > 1)
{
  $hide_langslug_select = new rex_select();
  $hide_langslug_select->setSize(1);
  $hide_langslug_select->setName('hide_langslug');
  $hide_langslug_select->addOption('Bei allen Sprachen einfügen',-1);
  foreach($REX['CLANG'] as $id => $str)
  {
    $hide_langslug_select->addOption('Kein lang slug für Sprache: '.$str,$id);
  }
  $hide_langslug_select->setSelected($REX['ADDON'][$myself]['settings']['hide_langslug']);
  $hide_langslug_select = '
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="hide_langslug">Lang slug</label>
                '.$hide_langslug_select->get().'
                </p>
          </div><!-- /rex-form-row -->';
}
else
{
  $hide_langslug_select = '';
}


// HOMELANG SELECT BOX
////////////////////////////////////////////////////////////////////////////////
if(count($REX['CLANG']) > 1)
{
  $homelang_select = new rex_select();
  $homelang_select->setSize(1);
  $homelang_select->setName('homelang');
  foreach($REX['CLANG'] as $id => $str)
  {
    $homelang_select->addOption($str,$id);
  }
  $homelang_select->setSelected($REX['ADDON'][$myself]['settings']['homelang']);
  $homelang_select->setAttribute('style','width:70px;margin-left:20px;');
  $homelang_box = '
              <span style="margin:0 4px 0 4px;display:inline-block;width:100px;text-align:right;">
                Sprache
              </span>
              '.$homelang_select->get().'
              ';
}
else
{
  $homelang_box = '';
}

// ARTICLE_ID SELECT BOX
////////////////////////////////////////////////////////////////////////////////
$allow_articleid_select = new rex_select();
$allow_articleid_select->setSize(1);
$allow_articleid_select->setName('allow_articleid');
$allow_articleid_select->addOption('Nicht zulässig, nur rewrite URLs',0);
$allow_articleid_select->addOption('Zulässig, 301 Weiterleitung auf korrekte URL (ohne Parameter)',1);
//$allow_articleid_select->addOption('Zulässig ohne Weiterleitung'                ,2);
$allow_articleid_select->setSelected($REX['ADDON'][$myself]['settings']['allow_articleid']);

// ONE PAGE MODE CHECKBOX
////////////////////////////////////////////////////////////////////////////////
if($REX['ADDON'][$myself]['settings']['one_page_mode'] == 1)
{
  $onepagemode_checked = 'checked="checked"';
}
else
{
  $onepagemode_checked = '';
}


// FORM
////////////////////////////////////////////////////////////////////////////////
echo '

<div class="rex-addon-output">
  <div class="rex-form">

  <form action="index.php" method="post">
    <input type="hidden" name="page"                   value="rexseo42" />
    <input type="hidden" name="subpage"                value="" />
    <input type="hidden" name="func"                   value="update" />
    <input type="hidden" name="install_subdir"         value="'.rexseo_subdir().'" />
';

echo '
    <div id="expert_block" style="margin:0;padding:0;">

      <fieldset class="rex-form-col-1">
        <legend>URL Rewrite Optionen</legend>
        <div class="rex-form-wrapper">

		  

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="url_schema" class="helptopic">Schema:</label>
                '.$url_schema_select->get().'

              <span style="margin:0 4px 0 4px;display:inline-block;width:100px;text-align:right;" class="helptopic">Endung</span>
                '.$url_ending_select->get().'
            </p>
          </div><!-- /rex-form-row -->

          '.$hide_langslug_select.'

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="homeurl" class="helptopic">Startseite</label>
                '.$homeurl_select->get().'
                '.$homelang_box.'
            </p>
          </div><!-- /rex-form-row -->

		  <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="allow_articleid" class="helptopic">Aufruf via article_id</label>
                '.$allow_articleid_select->get().'
                </p>
          </div>

        </div><!-- /rex-form-wrapper -->
      </fieldset>

      <fieldset class="rex-form-col-1">
        <legend>robots.txt</legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="robots" class="helptopic">Zusätzliche Einträge</label>
              <textarea id="rexseo_robots" name="robots" rows="3">'.stripslashes($REX['ADDON'][$myself]['settings']['robots']).'</textarea>
            </p>
          </div><!-- /rex-form-row -->

		  <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="robots-txt" class="helptopic">Link zur robots.txt</label>
              <span class="rex-form-read" id="robots-txt"><a href="' . rexseo42::getBaseUrl() . 'robots.txt" target="_blank">' . rexseo42::getBaseUrl() . 'robots.txt</a></span>
            </p>
          </div><!-- /rex-form-row -->

        </div><!-- /rex-form-wrapper -->
      </fieldset>


      <fieldset class="rex-form-col-1">
        <legend>sitemap.xml</legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="xml-sitemap" class="helptopic">Link zur sitemap.xml</label>
              <span class="rex-form-read" id="xml-sitemap"><a href="' . rexseo42::getBaseUrl() . 'sitemap.xml" target="_blank">' . rexseo42::getBaseUrl() . 'sitemap.xml</a></span>
            </p>
          </div><!-- /rex-form-row -->

        </div><!-- /rex-form-wrapper -->
      </fieldset>

    </div><!-- /expert / one page mode -->

      <fieldset class="rex-form-col-1">
        <legend>&nbsp;</legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row rex-form-element-v2">

			<!--<p class="rex-form-checkbox"style="display:inline !important;">
              <label for="onepage_settings" style="width:145px !important;">One Page Mode</label>
              <input type="checkbox" '.$onepagemode_checked.' value="1" id="one_page_mode" name="one_page_mode">
            </p>-->

            <p class="rex-form-submit">
              <input style="margin-top: 5px; margin-bottom: 5px;" class="rex-form-submit" type="submit" id="sendit" name="sendit" value="Einstellungen speichern" />
            </p>
          </div><!-- /rex-form-row -->

        </div><!-- /rex-form-wrapper -->
      </fieldset>

  </form>
  </div><!-- /rex-addon-output -->
</div><!-- /rex-form -->

';

unset($allow_articleid_select,$homeurl_select,$url_ending_select,$url_schema_select);


