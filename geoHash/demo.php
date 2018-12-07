<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/7 14:19
 */

require "../../../vendor/autoload.php";

use Predis\Client;

$client = new Client();

$client->geoadd('company', '116.48105', '39.996794', 'juejin');
$client->geoadd('company', '116.514203', '39.905409', 'ireader');
$client->geoadd('company', '116.489033', '40.007669', 'meituan');
$client->geoadd('company', '116.562108', '39.787602', 'jd');
$client->geoadd('company', '116.334255', '40.027400', 'xiaomi');

//距离  单位 m、km、ml、ft
$distance = $client->geodist('company', 'juejin', 'ireader', 'm');
echo "distance between juejin and iread is {$distance}m" . PHP_EOL;

//hash 值
$hash = $client->geohash('company', ['juejin']);
echo "hash of juejin is {$hash[0]}" . PHP_EOL;

// 附近的公司
$nearby1 = $client->georadius('company', '116.334255','39.905409', 20, 'km'
    , [
        'WITHDIST'=>true,
//        'WITHHASH'=>true,
//        'WITHCOORD'=>true,
        'SORT'=>'asc',
        'COUNT'=>3
    ]);
echo "116.334255  39.905409 附近20Km的公司---" . PHP_EOL;
var_dump($nearby1);


$nearby = $client->georadiusbymember('company', 'ireader', 20, 'km'
    , [
        'WITHDIST'=>true,
//        'WITHHASH'=>true,
//        'WITHCOORD'=>true,
        'SORT'=>'asc',
        'COUNT'=>3
    ]);
echo "ireader附近20Km的公司---" . PHP_EOL;
var_dump($nearby);



