<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/phpQuery/phpQuery.php';
// use phpspider\core\db;
// use phpspider\core\phpspider;
use phpspider\core\requests;

requests::set_useragent('Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,likeGecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)');
$html = requests::get('https://mobile.baidu.com/api/board?boardid=board_101_0311&pn=16');
//echo $html;
// $data = selector::select($html, "//div[@class='list']");
var_dump(json_decode($html));

exit;
