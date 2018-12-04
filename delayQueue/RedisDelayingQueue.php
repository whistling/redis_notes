<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/4 15:14
 */

namespace Module\redisNotes\delayQueue;

use Predis\Client;

class RedisDelayingQueue
{
    protected $client;
    protected $queueKey = 'books';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function delay($msg, $delay)
    {
        $this->client->zadd($this->queueKey, time() + $delay, $msg);
    }

    public function loop()
    {
        while (true) {
            $value = $this->client->zrangebyscore($this->queueKey, 0, time(),
                // [0]: offset / [1]: count
                ['limit' => array(0, 1),]);

            if (empty($value)) {
                sleep(1);
                continue;
            }

            // 并发抢占
            if ($this->client->zrem($this->queueKey, $value[0]) > 0) {
                $this->handleMsg($value[0]);
            };
        }
    }

    public function handleMsg($msg)
    {
        //模拟处理任务时长
        sleep(1);
        echo "this is handle msg ,got -------" . $msg . PHP_EOL;
    }

}