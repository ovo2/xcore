<?php
global $REX;

// include website specific config file
rexseo42_utils::includeWebsiteSpecificConfigFile();

if (rex_request('rexseo_func')!='')
{
  $path = $REX['INCLUDE_PATH'].'/addons/rexseo';

  switch (rex_request('rexseo_func'))
  {
    case 'sitemap':
      require_once $REX['INCLUDE_PATH'].'/addons/rexseo42/classes/class.rexseo_sitemap.inc.php';
      $map = new rexseo_sitemap;

      switch(rex_request('mode'))
      {
        case'json':
          $map->setMode('json');
          $map->send();
        break;
        default:
          $map->send();
      }

      die();
    break;


  case 'robots':
      require_once $REX['INCLUDE_PATH'].'/addons/rexseo42/classes/class.rexseo_robots.inc.php';

      $robots = new rexseo_robots;
      $robots->setContent($REX['ADDON']['rexseo42']['settings']['robots']);
      $robots->addSitemapLink();
      $robots->send();

      die();
    break;

    default:
    break;
  }
}

