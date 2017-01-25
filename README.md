# anushaadabala
Site Crawler
A basic crawler script to fetch and list all hyperlinks under a domain.

Environment:
LAMP/XAMPP Environment is required. Primarily PHP, Apache.

Execution Steps:
- Download the crawler_basic.php file to local or any remote LAMP/XAMPP environment.
- Execute the script by accessing crawler_basic.php from the browser url.

Enhancements, if given more time:
- The getAnchors regular expression to be improvised.
- The repetative listing of links, at page-level and over-all site-level needs to be addressed.
- Custom error handling is to be included.
- Known issue: 
	Site crawling depth, currently set to 2 to exclude third-party crawling needs to be further tuned. 
	Even though the depth is increased, it should only crawl through internal pages and a condition to exclude the hostname and ignore the external urls crawled needs to be further included/improved.
	
	