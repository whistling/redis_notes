<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/4 9:16
 */

require "../../../vendor/autoload.php";

use Predis\Client;

$client = new Client();

$delay = new \Module\redisNotes\delayQueue\RedisDelayingQueue();

for ($i = 0; $i < 10; $i++) {
    $delay->delay('message delay ' . $i, 20);
}

$delay->loop();


/**
 * zrange
 */
//$client->zadd('books', 10.0, 'java');
//$client->zadd('books', 15, 'go');
//$client->zadd('books', 12, 'php');
//
//
//$nn = $client->zrem('books', 'php');
//var_dump($nn);
//$mm = $client->zrangebyscore('books', 0, 15, [
//    'limit' => array(1, 2), // [0]: offset  [1]: count
//]);
////$mm = $client->zrevrangebyscore('books',20,0,['withscores'=>true]);
//
//var_dump($mm);