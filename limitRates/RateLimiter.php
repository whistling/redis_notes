<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/7 9:57
 */

namespace Module\redisNotes\limitRates;

use Predis\Client;

class RateLimiter
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function isActionAllowed($userid, $actionKey, $period, $maxCount,&$timeLeft=null)
    {
        $key = "hits:$userid:$actionKey";
        list ($msec, $sec) = explode(' ', microtime());
        $now = (int)(($sec + $msec) * 100000);

        $count = $this->client->zcard($key);
        if ($count >= $maxCount) {
//            $this->client->z
//            $timeleft = ;
            return false;
        }

        $response = $this->client->pipeline(function ($pipe) use ($key, $now, $period, $maxCount) {
            $pipe->zadd($key, $now, (string)$now);
            $pipe->zremrangebyscore($key, 0, $now - $period * 100000);
            $pipe->zcard($key);
            $pipe->expire($key, $period + 1);
        });

        if ($response[0] == 0) {
            echo "插入失败 +1";
        }

        return $response[2] < $maxCount;
    }


}