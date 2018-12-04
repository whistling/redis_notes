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
