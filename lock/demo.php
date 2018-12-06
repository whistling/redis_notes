<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/5 9:03
 */

require "../../../vendor/autoload.php";

//在两个终端 同时执行该文件，第一个可以正确执行成功，第二个 因为没有抢到锁 会超时退出

$client = new \Predis\Client();


// 模拟操作 某个用户的账户

buy(12);

/**
 * @param $user_id
 * @return bool|mixed
 * @throws Exception
 */
function buy($user_id){
    $lock = new \Module\redisNotes\lock\Lock();
    $access = $lock->acquire($user_id,1);
    if($access){
        echo  "doing something ".PHP_EOL;
        sleep(2);
    }

    $lock->release($user_id);
    return $access;
}