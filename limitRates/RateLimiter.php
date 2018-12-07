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

    protected $precision = 100000;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function isActionAllowed($userid, $actionKey, $period, $maxCount,&$timeLeft=null)
    {
        $key = "hits:$userid:$actionKey";
        list ($msec, $sec) = explode(' ', microtime());
        $now = (int)(($sec + $msec) * $this->precision);

        // 次数和剩余时长
        $count = $this->client->zcard($key);
        if ($count >= $maxCount) {
            $arrs = $this->client->zrange($key, 0, -1);
            $timeLeft =   $period - floor(($now -$arrs[0])/ $this->precision) ;
            return false;
        }

        $response = $this->client->pipeline(function ($pipe) use ($key, $now, $period, $maxCount) {
            $pipe->zadd($key, $now, (string)$now);
            $pipe->zremrangebyscore($key, 0, $now - $period *  $this->precision);
            $pipe->zcard($key);
            $pipe->expire($key, $period + 1);
        });

        if ($response[0] == 0) {
            echo "插入失败 +1";
        }

        return $response[2] < $maxCount;
    }


}