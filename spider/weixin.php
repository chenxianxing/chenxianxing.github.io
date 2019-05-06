<?php
require './vendor/autoload.php';
use phpspider\core\phpspider;
use phpspider\core\requests;
use phpspider\core\db;
include(dirname(__FILE__) .'/phpQuery/phpQuery.php');//引入phpquery
/* Do NOT delete this comment */
/* 不要删除这段注释 */
$configs = array(
    'name' => '励志故事',
    'log_show' =>false,
    'domains' => array(
        'mp.weixin.qq.com'
    ),
    'tasknum' => 1,
    'scan_urls' => array(
        //'https://www.wandoujia.com/category/game'
        "https://mp.weixin.qq.com/cgi-bin/searchbiz?action=search_biz&token=1503237045&lang=zh_CN&f=json&ajax=1&random=0.10169828192262598&query=励志&begin=0&count=5"
    ),
    'content_url_regexes' => array(
        "https://mp.weixin.qq.com/cgi-bin/searchbiz\?action=search_biz&token=1503237045&lang=zh_CN&f=json&ajax=1&random=0.10169828192262598&query=励志&begin=\d+&count=5"
    ),
    'list_url_regexes' => array(
        ""
    ),
    'fields' => array(
        array(
            'name' => "name",//post
            'selector_type' => 'css',
            'selector' => 'html',
            'required' => true
        ),
    ),
    'max_try' => 1,
    'export' => array(
    'type' => 'db',
    'table' => 'author',  // 如果数据表没有数据新增请检查表结构和字段名是否匹配
    ),
    'db_config' => array(
        'host'  => 'localhost',
        'port'  => 3306,
        'user'  => 'cxx',
        'pass'  => 'cxx1995418418',
        'name'  => 'weixin',
    ),
);
$spider = new phpspider($configs);

$spider->on_start=function($spider){
$cookies = "noticeLoginFlag=1; remember_acct=chenxianxing01%40163.com; pgv_pvi=1876827136; RK=DbxM0XxRGh; tvfe_boss_uuid=69f3506379657609; ue_ts=1544441347; ue_uk=e5cd717965101412caea19b571ff4a83; ue_uid=ed28e8f7b28806ef2a61ea4a3a710662; ue_skey=b4f98b4eb35c40e188deb2dfbf2285da; eas_sid=E1U5d40454x4p1G36418N9A6D8; LW_uid=L1H5h4D4g4W4Z1J304h8Q9u7k1; LW_pid=c05663a53eb9126821a7d6e94c725562; o_cookie=410097923; pac_uid=1_410097923; mobileUV=1_169d414cda4_241b2; sensorsdata2015jssdkcross=%7B%22distinct_id%22%3A%2216a26672047886-08b149bb64536e-5d4e211f-2073600-16a266720484f1%22%2C%22%24device_id%22%3A%2216a26672047886-08b149bb64536e-5d4e211f-2073600-16a266720484f1%22%2C%22props%22%3A%7B%22%24latest_traffic_source_type%22%3A%22%E8%87%AA%E7%84%B6%E6%90%9C%E7%B4%A2%E6%B5%81%E9%87%8F%22%2C%22%24latest_referrer%22%3A%22https%3A%2F%2Fwww.baidu.com%2Flink%22%2C%22%24latest_referrer_host%22%3A%22www.baidu.com%22%2C%22%24latest_search_keyword%22%3A%22%E6%9C%AA%E5%8F%96%E5%88%B0%E5%80%BC%22%7D%7D; pgv_pvid=7492911404; ptcz=3b28a7af5f215ff25e2e1dfe7d8c08e3b9b1bf8bdaec9b814f9f27683151483d; noticeLoginFlag=1; pgv_si=s3425904640; uuid=c58e6d23ef5659793882b3b909c8580c; ticket=15b419489e868e1e7316af8d036a784519ca7231; ticket_id=gh_05c640b85362; cert=c2va5CM_s60yqLtVtqhbfkMzt0DsmEAd; data_bizuin=3862121697; data_ticket=DGoB0ucDPjF9Q/ZVYi6d/gpIee+SXecDcNMsXMRszjmRZf2mWuSMIJ2yZSi1ivUM; ua_id=mPIx2MuUmbuiI4kdAAAAAM5C1AZgPVvxnz-9tcJ6I5o=; xid=5df45403a37e02ba74fb24700c04287b; openid2ticket_o4Uwz5hhIGVUJhBHJLT00YZzjmRY=VIdCA4g0iXlOVlE26UupGerwlvwl3rXf8jfkn656zPE=; mm_lang=zh_CN; rewardsn=; wxtokenkey=777; slave_user=gh_05c640b85362; slave_sid=UHlwQ1BWWDN2RnNIVzBMOFAyZGhheGViYkxHVktUQlZkWXlQeXFVazhZSEdwMHVCczBqOW5HTWxGVmREQWZxNFYxTENha0tXbTl5UzBxUG5ZMjlWRW5GR1hfX3duYTVUNEMyblVpZHNsUUNwczJTa1A2QkpLdFNoQjg5Zkp3R3ZHazRhNDVkR0hjMnJlTXh5; bizuin=3862121697";
    requests::set_cookies($cookies, 'mp.weixin.qq.com');
    // 生成列表页URL入队列
    for ($i = 20; $i <= 79; $i++)
    {
        $url = "https://mp.weixin.qq.com/cgi-bin/searchbiz?action=search_biz&token=1503237045&lang=zh_CN&f=json&ajax=1&random=0.10169828192262598&query=励志&begin=".($i*5)."&count=5";
        $spider->add_url($url);
    }

};

$spider->on_fetch_url = function($url, $phpspider)
{//当前页处理
    
    return $url;
};
$spider->on_list_page = function($page, $content, $phpspider) 
{
   
    return $page;
};
$spider->on_content_page = function($page, $content, $phpspider) 
{
    return $page;
};


$spider->on_extract_page = function($page, $data) 
{
    
    //var_dump($page['raw']);
    sleep(30);
    $content=$page['raw'];
    $jsondata = json_decode($page['raw'], true);
    $ary=$jsondata['list'];
    for ($i=0; $i <count($ary); $i++) {

        $data['url']=$ary[$i]['fakeid'];
        $data['name']=$ary[$i]['nickname'];
        $sql = "Select Count(*) As `count` From `author` Where `url`='{$data['url']}'";
        $row = db::get_one($sql);
        if (!$row['count']) 
         {
             db::insert("author", $data);
         }
    }

    
    //print_r($data);
    //插入字段
    //$sqlfl="select ID from wp_posts where post_type='post' order by ID desc limit 1";
    
    return false;
    //return $data;
};

$spider->start();