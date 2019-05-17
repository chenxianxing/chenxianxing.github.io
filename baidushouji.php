<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/phpQuery/phpQuery.php';
// use phpspider\core\db;
// use phpspider\core\phpspider;
// use phpspider\core\requests;
/* Do NOT delete this comment */
/* 不要删除这段注释 */

//$url     = 'https://mobile.baidu.com/api/board?boardid=board_101_0311&pn=16';
//$content = get_fcontent($url);
//$body    = phpQuery::newDocumentHTML($content);
$body = phpQuery::newDocumentFile('https://mobile.baidu.com/api/board?boardid=board_101_0311&pn=16');
var_dump(json_decode(pq($body)->html()));

exit;

$url     = 'https://www.baidu.com';
$content = get_data($url);
echo $content;

function get_data($url)
{

    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    //curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);

    //
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,likeGecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)");

    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    return $data;
}
