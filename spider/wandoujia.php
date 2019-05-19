<?php
require './vendor/autoload.php';
use phpspider\core\db;
use phpspider\core\phpspider;
use phpspider\core\requests;
include dirname(__FILE__) . '/phpQuery/phpQuery.php'; //引入phpquery
/* Do NOT delete this comment */
/* 不要删除这段注释 */
$configs = array(
    'name'                => '豌豆荚采集',
    'log_show'            => false,
    'domains'             => array(
        'www.wandoujia.com',
    ),
    'tasknum'             => 5,
    'scan_urls'           => array(
        //'https://www.wandoujia.com/category/game'
        "https://www.wandoujia.com/admin",
    ),
    'content_url_regexes' => array(
        "https://www.wandoujia.com/apps/.*",
    ),
    'list_url_regexes'    => array(
        "",
    ),
    'fields'              => array(
        array(
            'name'          => "post_title",
            'selector_type' => 'css',
            'selector'      => 'title',
            'required'      => true,
        ),

    ),
    'max_try'             => 5,
    'export'              => array(
        'type'  => 'db',
        'table' => 'wp_posts', // 如果数据表没有数据新增请检查表结构和字段名是否匹配
    ),
    'db_config'           => array(
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'pass' => '',
        'name' => 'softlizhi51',
    ),
);
$spider = new phpspider($configs);

$spider->on_start = function ($phpspider) {
    requests::set_useragent("Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,likeGecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)");

    $rsid = db::query("Select * From `wdj_urls` order by id");
    while ($row = db::fetch($rsid)) {

        $phpspider->add_scan_url($row['url']);
    }

};

$spider->on_fetch_url = function ($url, $phpspider) {
//当前页处理
    if (strpos($url, "download") !== false || strpos($url, "binding") !== false || strpos($url, "history") !== false || strpos($url, "comment") !== false) {
        return false;
    }
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

    phpQuery::newDocument($page['raw']);
    $data['post_title']    = pq(".title")->text();
    $data['post_status']   = 'draft';
    $data['post_type']     = 'post';
    $data['post_parent']   = '0';
    $data['comment_count'] = '0';

    //版本
    if (trim(pq(".infos-list dt:eq(2)")->text()) == 'TAG') {
        $data['softver'] = trim(pq(".infos-list dd:eq(3)")->text());
    } else {
        $data['softver'] = trim(pq(".infos-list dd:eq(2)")->text());
    }
//大小
    $data['softsize'] = trim(pq(".infos-list dd:eq(0)")->text());
    //生成随机时间

    $randtime                  = trim(pq(".update-time")->attr('datetime')); //时间
    $data['post_date']         = $randtime;
    $data['post_date_gmt']     = $randtime;
    $data['post_modified']     = $randtime;
    $data['post_modified_gmt'] = $randtime;

    $data['post_content'] = trim(pq(".desc-info .con")->text()); //内容

    //logo
    $data['logo'] = pq(".app-icon img")->attr('src');

    //images
    $images_ary = pq(".overview img");
    $new_iamges = [];
    foreach ($images_ary as $key) {
        $new_iamges[] = pq($key)->attr('src');
    }
    $data['images'] = implode(",", $new_iamges);
    //downurl
    $data['downurl'] = pq(".download-wp a:eq(0)")->attr('href');
    //fromurl
    $data['fromurl'] = $page['url'];
    //sortid
    $html = pq("dd.tag-box a:eq(0)")->attr('href');
    preg_match('/\/(\d+)\?/i', $html, $match);
    $data['sortid'] = $match[1];

    //插入分类
    $sqlfl = "select ID from wp_posts where post_type='post' order by ID desc limit 1";

    $sql = "Select Count(*) As `count` From `wp_posts` Where `post_title`='{$data['post_title']}' and `softver`='{$data['softver']}'  ";
    $row = db::get_one($sql);
    if (!$row['count']) {
        db::insert("wp_posts", $data);
        //$flrow = db::get_one($sqlfl);
        // db::query("Insert Into wp_term_relationships(object_id,term_taxonomy_id) values(" . $flrow["ID"] . ",107)");
    }
    return false;
    //return $data;
};

$spider->start();
