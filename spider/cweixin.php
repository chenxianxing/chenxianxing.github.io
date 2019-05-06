<?php
require './vendor/autoload.php';
use phpspider\core\db;
use phpspider\core\phpspider;
use phpspider\core\requests;
include dirname(__FILE__) . '/phpQuery/phpQuery.php'; //引入phpquery
/* Do NOT delete this comment */
/* 不要删除这段注释 */
$configs = array(
    'name'                => '微信公众号',
    'log_show'            => false,
    'domains'             => array(
        'mp.weixin.qq.com',
    ),
    'tasknum'             => 1,
    'scan_urls'           => array(
        "https://mp.weixin.qq.com/cgi-bin/appmsg?token=1115044465&lang=zh_CN&f=json&ajax=1&random=0.6968065300931354&action=list_ex&begin=0&count=5&query=&fakeid=MzIzMzA3NDg0Ng%3D%3D&type=9",
    ),
    'content_url_regexes' => array(
        "https://mp.weixin.qq.com/cgi-bin/appmsg\?token=1115044465&lang=zh_CN&f=json&ajax=1&random=0.6968065300931354&action=list_ex&begin=\d+&count=5&query=&fakeid=MzIzMzA3NDg0Ng%3D%3D&type=9",
    ),
    'list_url_regexes'    => array(
        "",
    ),
    'fields'              => array(
        array(
            'name'          => "author", //post
            'selector_type' => 'css',
            'selector'      => 'html',
            'required'      => true,
        ),
    ),
    'max_try'             => 1,
    'export'              => array(
        'type'  => 'db',
        'table' => 'post', // 如果数据表没有数据新增请检查表结构和字段名是否匹配
    ),
    'db_config'           => array(
        'host' => 'localhost',
        'port' => 3306,
        'user' => 'cxx',
        'pass' => '1995418418',
        'name' => 'weixin',
    ),
);
$spider = new phpspider($configs);

$spider->on_start = function ($spider) {
    $cookies = "noticeLoginFlag=1; pgv_pvid=5704802392; RK=4bxE03xYVD; ptcz=c8bf0a4c8cf2fcc03f44e58ebb7fdcd835ba04452dca21a553ad712b0a9ecea1; ua_id=jkPJ8VoR8iiv4rKeAAAAAGheDt309sA3d-jlU4hB1DI=; mm_lang=zh_CN; pgv_pvi=4601354240; noticeLoginFlag=1; eas_sid=d1r5k5A4v7r8B0g098y4l924J5; tvfe_boss_uuid=f250d71f424db375; pac_uid=1_410097923; o_cookie=410097923; mobileUV=1_16a3da86b45_415d1; ied_qq=o0410097923; ptui_loginuin=melloi@bilibili.com; pgv_si=s1745980416; ticket_id=gh_05c640b85362; cert=i3bG19dXelfDSrHGcRTPANCD1mhMKf_B; rewardsn=; wxtokenkey=777; pgv_info=ssid=s4971965654; uuid=ec472b7243a4255d1bbed69b6addec04; bizuin=3862121697; ticket=3d26f24cfffc7d63758f3b90059ef7b099191190; data_bizuin=3862121697; data_ticket=N7b0P1lUhOeq5mrOxgH36evZwiLukc9RKHrdnAd5EpUCkNusIEJODrm71xz7xc8t; slave_sid=bzFObTlPZVIyckpUdHEweEJSWEhIb1hyZUppdnRtcldSelVNbDlHQkdOTGdwSFh5aXA2UWZCeDlRTkRJRHVYQWxDbUtSeGRibERaVmswalNCSEo0N0xvNDRpV0ZLb2FKWjBWX1ZUM3NGb0xxZHVSU3NIVWdhYUVYdUowNVA2OTI2bGpzekt2NmY2OHVsM1ZD; slave_user=gh_05c640b85362; xid=6009da5594d28259ac950f86e3a18772; openid2ticket_o4Uwz5hhIGVUJhBHJLT00YZzjmRY=YwQMkJ1j3yqrOPp3ZOrxhxc6FfvtnHVacTobR2Lrqd8=";
    requests::set_cookies($cookies, 'mp.weixin.qq.com');
    requests::set_header('User-Agent', 'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36');
    // 生成列表页URL入队列
    for ($i = 1; $i <= 43; $i++) {
        $url = "https://mp.weixin.qq.com/cgi-bin/appmsg?token=1115044465&lang=zh_CN&f=json&ajax=1&random=0.6968065300931354&action=list_ex&begin=" . ($i * 5) . "&count=5&query=&fakeid=MzIzMzA3NDg0Ng%3D%3D&type=9";
        $spider->add_url($url);
    }

};

$spider->on_fetch_url = function ($url, $phpspider) {
//当前页处理
    return $url;
};
$spider->on_list_page = function ($page, $content, $phpspider) {
    return $page;
};
$spider->on_content_page = function ($page, $content, $phpspider) {
    return $page;
};

$spider->on_extract_page = function ($page, $data) {

    //var_dump($page['raw']);
    $randnum = rand(1, 30);
    sleep(60 + $randnum);
    $content  = $page['raw'];
    $jsondata = json_decode($page['raw'], true);
    $ary      = $jsondata['app_msg_list'];
    for ($i = 0; $i < count($ary); $i++) {

        $data['url']         = $ary[$i]['link'];
        $data['title']       = $ary[$i]['title'];
        $data['update_time'] = date("Y-m-d H:i", $ary[$i]['update_time']);

        $sql = "Select Count(*) As `count` From `post` Where `url`='{$data['url']}'";
        $row = db::get_one($sql);
        if (!$row['count']) {
            db::insert("post", $data);
        }
    }

    //print_r($data);
    //插入字段
    //$sqlfl="select ID from wp_posts where post_type='post' order by ID desc limit 1";

    return false;
    //return $data;
};

$spider->start();
