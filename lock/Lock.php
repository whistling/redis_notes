<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/5 10:22
 */

namespace Module\redisNotes\lock;


use Illuminate\Support\Carbon;
use Predis\Client;

class Lock
{
    protected $client;

    protected $prefix = "account_";

    public function __construct()
    {
        $this->client = new Client();
    }

    protected function getKey($user_id)
    {
        return $this->prefix . $user_id;
    }

    protected function _lock($key, $ttl)
    {
        return $this->client->set($key, true, 'ex', $ttl, 'nx') != null;
    }

    /**
     * 在指定时间内去多次尝试抢占锁
     *
     * @param $user_id
     * @param $seconds
     * @param null $callback
     * @return bool|mixed
     * @throws \Exception
     */
    public function acquire($user_id,$seconds, $callback = null)
    {
        $starting = $this->currentTime();

        while (!$this->lock($user_id)) {
            usleep(250 * 1000);

            if ($this->currentTime() - $seconds >= $starting) {
                throw new \Exception('超时');
            }
        }

        if (is_callable($callback) ) {
            return tap($callback(), function ()use ($user_id) {
                $this->release($user_id);
            });
        }

        return true;
    }

    public function release($user_id)
    {
        $key = $this->getKey($user_id);
        return $this->_unlock($key);
    }


    /**
     * 一次性抢占锁
     *
     * @param $user_id
     * @param int $ttl
     * @return bool
     */
    public function lock($user_id, $ttl = 20)
    {
        $key = $this->getKey($user_id);
        return $this->_lock($key, $ttl);
    }


    protected function _unlock($key)
    {
        return $this->client->del([$key]);
    }


    protected function currentTime()
    {
        return Carbon::now()->getTimestamp();
    }


}