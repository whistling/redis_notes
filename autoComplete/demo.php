<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2019/1/2 15:23
 */


require "../../../vendor/autoload.php";

use Predis\Client;

$client = new Client();
$users = [15155865921,15155865451,18855865921,18855868821,18855869871,15155865934];

// 1.分词存取

$client->pipeline(function ($pipe) use ($users) {
    foreach ($users as $user) {
        $len = mb_strlen($user, 'utf-8');
        for ($i = 0; $i <= $len; $i++) {
            $key = mb_substr($user, 0, $i + 1, 'utf-8');
            if ($i == $len) {
                $key .= '*';
            }
            $pipe->zadd('autocomplet_user_mobile', 0, $key);
        }
    }
});





// 2.检索
$key = '151558659';
$index = $client->zRank('autocomplet_user_mobile', $key);

if ($_res = $client->zRange('autocomplet_user_mobile', $index, -1)) {
    foreach ($_res as $val) {
        if (strpos($val, $key) === 0 && strrev($val)[0] == '*') {
            $arr[] = substr($val, 0, -1);
        }
    }
}

var_dump($arr);