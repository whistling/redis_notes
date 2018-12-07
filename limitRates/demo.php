<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/7 9:56
 */

require "../../../vendor/autoload.php";


$userid =12;
$actionKey="login";
$period = 60;
$maxCount = 10;
$key = "hits:$userid:$actionKey";
list ($msec,$sec) = explode(' ',microtime());


$limiter = new \Module\redisNotes\limitRates\RateLimiter();
$times = $limiter->isActionAllowed($userid,$actionKey,$period,$maxCount);

for ($i=1;$i<20;$i++){
    $access = $limiter->isActionAllowed($userid,$actionKey,$period,$maxCount,$timeLeft);
    if($access){
        echo  "第{$i}次success".PHP_EOL;
    }else{
        echo  "第{$i}次fail".PHP_EOL."还剩{$timeLeft}秒";
    }
}
