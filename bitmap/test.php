<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/6 11:08
 */

require "../../../vendor/autoload.php";


$redis = new \Predis\Client();

$cacheKey = "sign";

$redis->setbit($cacheKey,7,1);
$redis->setbit($cacheKey,8,1);
$redis->setbit($cacheKey,10,1);

$value = $redis->get($cacheKey);
//print_r($value);
//echo PHP_EOL;

$bitmap = unpack('C*',$value);
print_r($bitmap);
echo PHP_EOL;
//
//$count = 0;
//
//foreach ($bitmap as $key => $num){
//    echo "num --- ".$num;
//    echo PHP_EOL;
//
//    for ($i=0;$i<8;$i++){
//        if(( $num>>$i &1) == 1){
//            $count ++;
//        }
//    }
//}
//
//echo "count ----".$count;
//









