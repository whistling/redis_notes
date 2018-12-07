<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/6 15:22
 */

require "../../../vendor/autoload.php";

/**
 * 布隆过滤器可以理解为一个不怎么精确的 set 结构，当你使用它的 contains 方法判断某个对象是否存在时，它可能会误判。但是布隆过滤器也不是特别不精确，
 * 只要参数设置的合理，它的精确度可以控制的相对足够精确，只会有小小的误判概率。
 *
 * 当布隆过滤器说某个值存在时，这个值可能不存在；当它说不存在时，那就肯定不存在。
 * 打个比方，当它说不认识你时，肯定就不认识； 当它说见过你时，可能根本就没见过面，不过因为你的脸跟它认识的人中某脸比较相似 (某些熟脸的系数组合)，所以误判以前见过你。
 *
 **/


/**
 * 安装
 * 一、扩展
 * 1. git clone  https://github.com/RedisLabsModules/rebloom.git
 * 2. cd rebloom
 * 3. make (生成rebloom.so)
 * 4. redis-server stop
 * 5. redis-server --loadmodule /path/to/rebloom.so
 *
 *
 * 二、docker
 * > docker pull redislabs/rebloom  # 拉取镜像
 * > docker run -p6379:6379 redislabs/rebloom  # 运行容器
 * > redis-cli  # 连接容器中的 redis 服务
 *
 * redis-server redis.conf --loadmodule /usr/rebloom/rebloom.so INITIAL_SIZE 1000000 ERROR_RATE 0.0001
 # 容量100万, 容错率万分之一, 占用空间是4m
 *
 *
 * 因此，想保持错误率低，布隆过滤器的空间使用率需为50%。
 */

$client = new \Predis\Client();


//一、 见过了就是见过了
//for ($i=1;$i<10000;$i++){
//    $client->executeRaw(["bf.add","ants","user".$i],$err);
//    $ret = $client->executeRaw(["bf.exists","ants","user".$i],$err);
//    if($ret == 0){
//        print $i;
//        break;
//    }
//}

//二、没见过 我也可能认为见过了 呜呜。。。
//每次运行清空一下
//$client->del(['ants']);
//for ($i=1;$i<10000;$i++){
//    //增加这一句 减少错误率
//    $client->executeRaw(["bf.reserve","ants","0.001",'5000'],$err);
//    $client->executeRaw(["bf.add","ants","user".$i],$err);
//    $ret = $client->executeRaw(["bf.exists","ants","user".($i+1)],$err);
//    if($ret == 1){
//        print $i;
//        break;
//    }
//}

$test = new \Module\redisNotes\rebloom\BloomTest();
$test->handle(100000);


















