<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/14 10:31
 */

namespace Module\redisNotes\transaction;


use Predis\Client;
use Predis\Transaction\AbortedMultiExecException;

class Transaction
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }


    public function key_for($user_id)
    {
        return "account_" . $user_id;
    }


    public function doubleCount($user_id)
    {
        $key = $this->key_for($user_id);

        //*** 一、管道
//        $this->client->watch($key);
//        $value = $this->client->get($key);
//        $response = $this->client->pipeline(function ($pipe) use ($key,$value){
////            $pipe->watch($key);
//            $pipe->set($key, 201);
//            $pipe->multi();
//            $pipe->set($key, $value * 2);
//        });
//        var_dump($response);
//        $this->client->exec();

        //*** 二、无管道
        $this->client->transaction();
        $this->client->watch($key);
        $value = $this->client->get($key);
        $this->client->set($key, 201);
        $this->client->multi();
        $this->client->set($key, $value * 2);

        //*** response 为null watch 成功啥也没执行
        $response = $this->client->exec();
        return $this->client->get($key);
    }


    // 包装好的 transaction  cas
//    public function doubleCount($user_id)
//    {
//        $key = $this->key_for($user_id);
//        $options = [
//            'cas' => true,
//            'watch' => $key,
//            'retry' => 1,
//        ];
////        $this->client->watch($key);
////        $value = $this->client->get($key);
////        $value *=2;
////
////        $this->client->ex();
//        try {
//            $this->client->transaction($options, function ($tx) use ($key) {
//                $tx->set($key, '20');
//                $tx->multi();
//                $value = $tx->get($key);
//                $value *= 2;
//                $tx->set($key, $value);
//            });
//        } catch (AbortedMultiExecException $exception) {
//            echo $exception->getMessage() . PHP_EOL;
//        }
//        return $this->client->get($key);
//    }


//    function zpop($key)
//    {
//        $element = null;
//        $options = [
//            'cas' => true,
//            'watch' => $key,
//            'retry' => 3,
//        ];
//        $this->client->transaction($options, function ($tx) use ($key, &$element) {
//            @list($element) = $tx->zrange($key, 0, 0);
//            if (isset($element)) {
//                $tx->multi();   // With CAS, MULTI *must* be explicitly invoked.
//                $tx->zrem($key, $element);
//            }
//        });
//
//        return $element;
//    }


}