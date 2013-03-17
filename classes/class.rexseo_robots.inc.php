<?php
class rexseo_robots
{
  private $host;
  private $robots_txt;


  /**
   * CONSTRUCTOR
   */
  public function rexseo_robots()
  {
    global $REX;
    $this->host = $REX['SERVER'];
    $this->robots_txt = '';
  }


  /**
   * SET HOST
   *
   * @param $host  (string)  http://DOMAIN.TLD
   */
  public function setHost($host)
  {
    $this->host = rtrim($host,'/');
  }


  /**
   * SET CONTENT OF ROBOTS.TXT
   *
   * @return  (string) robots.txt
   */
  public function setContent($content)
  {
	global $REX;
	$out = '';
	$langs = array_keys($REX['CLANG']); // get clang ids

	foreach ($langs as $lang) {
		$query = "SELECT id FROM ".$REX['TABLE_PREFIX']."article WHERE seo_noindex = '1' AND status = 1"; 
		$sql = new rex_sql(); 
		$sql->setQuery($query);
	
		for ($i = 1; $i <= $sql->getRows(); $i++) { 
	  		$out .= "Disallow: " .  rex_getUrl($sql->getValue('id'), $lang) . "\r\n"; 
		  	$sql->next(); 
		}
	}
	
	if ($out != '') {
		$out = "User-agent: *" . "\r\n" . $out . "\r\n";
	}

	if ($out == '' && $content == '') {
		$this->robots_txt = 'User-agent: *' . "\r\n" . 'Disallow:';
	} else {
	    $this->robots_txt = $out . $content;
	}
  }


  /**
   * INSERT SITEMAP LINK INTO ROBOTS.TXT
   *
   * @return  (string) robots.txt
   */
  public function addSitemapLink()
  {
	$this->robots_txt = rtrim($this->robots_txt, "\r\n");
    $this->robots_txt .= "\r\n" . PHP_EOL.'Sitemap: '.$this->host.'sitemap.xml';
  }


  /**
   * RETURN ROBOTS.TXT
   *
   * @return  (string) robots.txt
   */
  public function get()
  {
    return $this->robots_txt;
  }


  /**
   * SEND ROBOTS.TXT
   */
  public function send()
  {
    $robots = self::get();

    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Length: '.strlen($robots));
	header('X-Robots-Tag: noindex, noarchive');
    echo $robots;
    die();
  }

}

