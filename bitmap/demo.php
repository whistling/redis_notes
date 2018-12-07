<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/6 10:05
 */

require "../../../vendor/autoload.php";

/**
 * 一、用户签到
 */

$client = new \Predis\Client();

//$user_id = 12;
//$cache_key = sprintf("sign_%d",$user_id);
//
//$start_date = "2018-12-01";
//$end_date = "2018-12-20";
//
//$today_date = "2018-12-16";
//
//$start_time = strtotime($start_date);
//$today_time = strtotime($today_date);
//
//$offset = floor(($today_time - $start_time)/86400);
//
//echo "今天是第{$offset}天".PHP_EOL;
//// 签到
//// 一年一个用户会占用多少空间呢？大约365/8=45.625个字节，好小，有木有被惊呆？
//$client->setbit($cache_key,$offset,1);
//
//$bit_status = $client->getbit($cache_key,$offset);
//echo 1 == $bit_status ? '今天已经签到啦' : '还没有签到呢';
//echo PHP_EOL;
//
//// start 和 end 参数是字节索引，也就是说指定的位范围必须是 8 的倍数，而不能任意指定。
////这很奇怪，我表示不是很能理解 Antirez 为什么要这样设计。因为这个设计，我们无法直接计算某个月内用户签到了多少天，
////而必须要将这个月所覆盖的字节内容全部取出来 (getrange 可以取出字符串的子串) 然后在内存里进行统计，这个非常繁琐。
//
////错误的计算方式 不是从
//$bit_count = $client->bitcount($cache_key,0,20);
//echo '签到次数'.$bit_count.PHP_EOL;

/**
 * 二、统计活跃用户
 * 使用时间作为cacheKey，然后用户ID为offset，如果当日活跃过就设置为1
 */

$data = [
    '2017-01-10' => array(1,2,3,4,5,6,7,8,9,10),
    '2017-01-11' => array(1,2,3,4,5,6,7,8),
    '2017-01-12' => array(1,2,3,4,5,6),
    '2017-01-13' => array(1,2,3,4),
    '2017-01-14' => array(1,2)
];


foreach ($data as $date => $uids){
    $cacheKey = sprintf("stat_%s",$date);

    foreach ($uids as $uid){
        $client->setbit($cacheKey,$uid,1);
    }
}

/**
对一个或多个保存二进制位的字符串 key 进行位元操作，并将结果保存到 destkey 上。
operation 可以是 AND 、 OR 、 NOT 、 XOR 这四种操作中的任意一种：
    BITOP AND destkey key [key ...] ，对一个或多个 key 求逻辑并，并将结果保存到 destkey 。
    BITOP OR destkey key [key ...] ，对一个或多个 key 求逻辑或，并将结果保存到 destkey 。
    BITOP XOR destkey key [key ...] ，对一个或多个 key 求逻辑异或，并将结果保存到 destkey 。
    BITOP NOT destkey key ，对给定 key 求逻辑非，并将结果保存到 destkey 。
除了 NOT 操作之外，其他操作都可以接受一个或多个 key 作为输入。
 **/
$client->bitop('AND','stat',
    'stat_2017-01-10', 'stat_2017-01-11', 'stat_2017-01-12') . PHP_EOL;

//总活跃用户：6
echo "总活跃用户：" . $client->bitCount('stat') . PHP_EOL;
























