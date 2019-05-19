<?php
require './vendor/autoload.php';
use phpspider\core\db;
include dirname(__FILE__) . '/phpQuery/phpQuery.php'; //引入phpquery

/*数据库连接配置*/
$db_config = array(
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'pass' => '',
    'name' => 'softlizhi51',
);
// 数据库配置
db::set_connect('default', $db_config);
// 数据库链接
db::_init();

$sort_ary = array(
    '影音播放' => 5029,
    '系统工具' => 5018,
    '通讯社交' => 5014,
    '手机美化' => 5024,
    '新闻阅读' => 5019,
    '摄影图像' => 5016,
    '考试学习' => 5026,
    '网上购物' => 5017,
    '金融理财' => 5023,
    '生活休闲' => 5020,
    '旅游出行' => 5021,
    '运动健康' => 5028,
    '办公商务' => 5022,
    '育儿亲子' => 5027,
    '休闲益智' => 6001,
    '跑酷竞速' => 6003,
    '扑克棋牌' => 6008,
    '动作冒险' => 6004,
    '飞行射击' => 6002,
    '经营策略' => 6007,
    '网络游戏' => 6009,
    '体育竞技' => 6005,
    '角色扮演' => 6006,
    '游戏辅助' => 5015,
);

foreach ($sort_ary as $key => $value) {
    for ($j = 1; $j < 500; $j++) {
        echo ('正在爬' . $key . '的第：' . $j . '页') . PHP_EOL;
        phpQuery::newDocumentFile("https://www.wandoujia.com/wdjweb/api/category/more?catId=" . $value . "&subCatId=0&page=" . $j . "&ctoken=Xf5XiTo0SQHj8nNToEe_sj25");

        $html = pq("")->html();
        if (mb_strlen($html) < 500) {
            break;
        }
        $html = stripslashes($html);
        $html = preg_replace('/\n/i', "", $html);
        $html = str_replace("'", "", $html);
        preg_match('/<li.*>/i', $html, $matches);

        phpQuery::newDocumentHTML($matches[0]);

        $array = pq('.name');
        foreach ($array as $key) {
            $data = array(
                'name' => pq($key)->text(),
                'url'  => str_replace('%5C%22', '', pq($key)->attr('href')),
            );
            //var_dump($data);
            db::insert('wdj_urls', $data);
        }
    }

}

exit;
//     $address_ary=explode(',', $address1);

//     $logo=$address_ary[5];
//     $address1=$address_ary[3];
//phpQuery::newDocumentFile("https://m.wandoujia.com/wdjweb/api/category/more?catId=5021&subCatId=615&page=3&ctoken=i5L54lQl11NLLylY4a1NIXGL") ;
//phpQuery::newDocumentFile("sites.txt") ;
// //images
// $images_ary=pq(".overview img");
// $new_iamges=[];
// foreach ($images_ary as $key) {
//     $new_iamges[]=pq($key)->attr('src');
// }
// $comma_separated = implode(",", $new_iamges);

//echo json_decode(pq("")->html());

//$ary=explode('"content"',stripslashes($html));
//

//file_get_contents("sites.txt",$matches[0]);
$html = pq("")->html();
$html = stripslashes($html);
$html = str_replace("'", "", $html);
//$html=str_replace("\\", "", $html);
//echo $html;
phpQuery::newDocumentHTML($html);

$array = pq('.name');
foreach ($array as $key) {

    file_put_contents("sites.txt", pq($key)->text() . '==' . str_replace('%5C%22', '', pq($key)->attr('href')), FILE_APPEND);
}
// echo $match;
//echo ($html);
