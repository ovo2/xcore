<?php
class rexseo_sitemap
{
  private $mode;
  private $db_articles;


  /**
   * GET SITEMAP ARTICLES FROM DB
   *
   * @return (array) sitemap articles
   */
  private function get_db_articles()
  {
    global $REX, $REXSEO_URLS;

	foreach ($REXSEO_URLS as $url)  {
		$article = OOArticle::getArticleById($url['id'], $url['clang']);

		if (OOArticle::isValid($article) && $article->isOnline() && !isset($url['status'])) {
			$db_articles[$url['id']][$url['clang']] = array('loc'        => seo42::getFullUrl($url['id'], $url['clang']),
								                           'lastmod'    => date('Y-m-d\TH:i:s', $article->getValue('updatedate')) . '+00:00',
								                           'changefreq' => self::calc_article_changefreq($article->getValue('updatedate'), ''),
								                           'priority'   => self::calc_article_priority($url['id'], $url['clang'], $article->getValue('path'), ''),
														   'noindex'   => $article->getValue('seo_noindex')
								                           );	
		}
	}

    // EXTENSIONPOINT REXSEO_SITEMAP_ARRAY_CREATED
    $db_articles = rex_register_extension_point('REXSEO_SITEMAP_ARRAY_CREATED',$db_articles);

    // EXTENSIONPOINT REXSEO_SITEMAP_ARRAY_FINAL (READ ONLY)
    rex_register_extension_point('REXSEO_SITEMAP_ARRAY_FINAL',$db_articles);

    $this->db_articles = $db_articles;
  }


  /**
   * CALCULATE ARTICLE PRIORITY
   *
   * @param $article_id           (int)     rex_article.article_id
   * @param $clang                (int)     rex_article.clang
   * @param $path                 (string)  rex_article.path
   * @param $seo_priority  (float)   rex_article.seo_priority
   *
   * @return                      (float)   priority
   */
  private function calc_article_priority($article_id,$clang,$path,$seo_priority='')
  {
    global $REX;

    if($seo_priority!='')
      return $seo_priority;

    if($article_id==$REX['START_ARTICLE_ID'] && $clang==$REX['START_CLANG_ID'])
      return 1.0;

    return pow(0.8,count(explode('|',$path))-1);
  }


  /**
   * CALCULATE ARTICLE CHANGEFREQ
   *
   * @param $updatedate            (int)    rex_article.updatedate
   * @param $seo_changefreq (string) rex_article.seo_changefreq
   *
   * @return                       (string) change frequency  [never|yearly|monthly|weekly|daily|hourly|always]
   */
  private function calc_article_changefreq($updatedate,$seo_changefreq='')
  {
    if($seo_changefreq!='')
      return $seo_changefreq;

    $age = time() - $updatedate;

    switch($age)
    {
      case($age<604800):
        return 'daily';
      case($age<2419200):
        return 'weekly';
      default:
        return 'monthly';
    }
  }


  /**
   * BUILD SINGLE XML LOC FRAGMENT
   *
   * @param $loc        (string) full article url  [including lang]
   * @param $lastmod    (string) article last modified date  [UNIX date]
   * @param $changefreq (string) change frequency  [never|yearly|monthly|weekly|daily|hourly|always]
   * @param $priority   (float)  priority  [maximum: 1.0]
   *
   * @return            (string) xml location fragment
   */
  private function xml_loc_fragment($loc,$lastmod,$changefreq,$priority)
  {
    $xml_loc = "\t" . '<url>'.PHP_EOL.
    "\t\t" . '<loc>'.$loc.'</loc>'.PHP_EOL.
    "\t\t" . '<lastmod>'.$lastmod.'</lastmod>'.PHP_EOL.
    "\t\t" . '<changefreq>'.$changefreq.'</changefreq>'.PHP_EOL.
    "\t\t" . '<priority>'.number_format($priority, 2, ".", "").'</priority>'.PHP_EOL.
    "\t" . '</url>'.PHP_EOL;

    return $xml_loc;
  }


  /**
   * CONSTRUCTOR
   */
  public function rexseo_sitemap()
  {
    global $REX;

    $this->db_articles = array();
    $this->mode         = 'xml';

    self::get_db_articles();
  }


  /**
   * SET MODE
   *
   * @param $mode  (string)  [xml|json]
   */
  public function setMode($mode)
  {
    $this->mode = $mode;
  }


  /**
   * RETURN SITEMAP
   *
   * @return            (string) sitemap [xml|json]
   */
  public function get()
  {

    switch($this->mode)
    {
      case'json':
        return json_encode($this->db_articles);
      break;

      default:
        $xml_sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
		global $REX;

		if ($REX['ADDON']['seo42']['settings']['one_page_mode']) {
			$art = $this->db_articles[$REX['START_ARTICLE_ID']][0];

			if ($art['noindex'] != true) {
			   	$xml_sitemap .= self::xml_loc_fragment($art['loc'],$art['lastmod'],$art['changefreq'],$art['priority']);
			}		
		} else {
			if ($this->db_articles[$REX['START_ARTICLE_ID']][$REX['START_CLANG_ID']]['noindex'] != true) { // RexDude: Do not add Articles to Sitemap if Start Article has NoIndex Flag set.
				foreach($this->db_articles as $id=>$clangs)
				{
				  foreach($clangs as $art)
				  { 
					if ($art['noindex'] != true) {
				    	$xml_sitemap .= self::xml_loc_fragment($art['loc'],$art['lastmod'],$art['changefreq'],$art['priority']);
					}
				  }
				}
			}
		}

        // EXTENSIONPOINT REXSEO_SITEMAP_INJECT
        $inject = rex_register_extension_point('REXSEO_SITEMAP_INJECT');
        if(is_array($inject) && count($inject)>0)
        {
          foreach($inject as $key => $art)
          {
            $xml_sitemap .= self::xml_loc_fragment($art['url'],$art['lastmod'],$art['changefreq'],$art['priority']);
          }
        }

        $xml_sitemap .= '</urlset>';

        return $xml_sitemap;
    }
  }


  /**
   * SEND SITEMAP
   */
  public function send()
  {
    $map = self::get();

    while(ob_get_level()){
      ob_end_clean();
    } 

    switch($this->mode)
    {
      case'json':
        header('Content-Type: application/json');
      break;
      case'xml':
        header('Content-Type: application/xml');
      break;
      default:
    }
    header('Content-Length: '.strlen($map));
	header('X-Robots-Tag: noindex, noarchive');

    echo $map;
    die();
  }


}

