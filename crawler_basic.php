<?php 
ini_set('max_execution_time', 0);
class crawler {
    protected $_url;
    protected $_depth;
    protected $_host;
    protected $_alreadylooped = array();
 
    public function __construct($url, $depth = 2) {
        $this->_url = $url;
        $this->_depth = $depth;
		$this->_host = parse_url($url, PHP_URL_HOST);
    }

	//func to loop through all anchors and collect hrefs
    protected function _collectAnchorTags($content, $url, $depth) {

		$getAnchors = strip_tags($content, "<a>");
		preg_match_all("/<a[\s]+[^>]*?href[\s]?=[\s\"\']+"."(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/", $getAnchors, $anchors, PREG_SET_ORDER ); 
        foreach ($anchors as $element) {
            $href = $element[1];
            if (0 !== strpos($href, 'http')) {
                $path = '/' . ltrim($href, '/');
                if (extension_loaded('http')) {
                    $href = http_build_url($url, array('path' => $path));
                } else {
                    $parts = parse_url($url);
                    $href = $parts['scheme'] . '://';
                    if (isset($parts['user']) && isset($parts['pass'])) {
                        $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                    }
                    $href .= $parts['host'];
                    if (isset($parts['port'])) {
                        $href .= ':' . $parts['port'];
                    }
                    $href .= $path;
                }
            }
			
			if (!strpos($href,"#")) {
				$this->crawlPage($href, $depth-1);
				echo $depth. " - " .$href."<br>";
			}
        } 
    }

	//Curl to get the page content 
    protected function _fetchPageContent($url) {
		$handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);
        curl_close($handle);
		return array( $response);
     }

    protected function _displayTitles($url){
        ob_end_flush();
        $count = count($this->_alreadylooped);
		echo "<b>Crawling ".$url."</b><br>";
        ob_start();
        flush();
    }

	//validation to crawl under same domain, no follow to external sites, exclude duplicates.
    protected function _isValid($url, $depth) { 
        if (strpos($url, $this->_host) === false || $depth === 0 || isset($this->_alreadylooped[$url])) {
            return false;
        }
        return true;
    }

	//Page crawler and output display
    public function crawlPage($url, $depth) {
        if (!$this->_isValid($url, $depth)) {
            return;
        }
        $this->_alreadylooped[$url] = true;
        list($content) = $this->_fetchPageContent($url);
         $this->_displayTitles($url);
         $this->_collectAnchorTags($content, $url, $depth);
    }

    public function execute() {
        $this->crawlPage($this->_url, $this->_depth);
    }
	
	public function __destruct() {
		unset($this->_url);
		unset($this->_depth);
		unset($this->_host);
	}
}

$siteURL = 'http://wiprodigital.com/';
$depth = 2;
$mycrawler = new crawler($siteURL, $depth);
$mycrawler->execute();

?>