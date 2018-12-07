<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/6 17:30
 */

namespace Module\redisNotes\rebloom;


use Predis\Client;

class BloomTest
{
    /**
     * https://krisives.github.io/bloom-calculator/
     *
     * @return string
     */
    public function randomStr(){
       return str_random(64);
    }

    public function randomUsers($num){
        $users = [];
        for ($i=0;$i<$num;$i++){
            $users[$i]=$this->randomStr();
        }
        return $users;
    }


    public function handle($num){

        $users = $this->randomUsers($num);

        $usersTrain = array_slice($users,0,$num/2);

        $usersTest = array_slice($users,$num/2,$num/2);

        $client = new Client();
        $client->del(["ants"]);

        $client->executeRaw(["bf.reserve","ants","0.001",'50000'],$err);

        foreach ($usersTrain as $user){
            $client->executeRaw(["bf.add","ants",$user],$err);
        }

        $fail = 0;
        foreach ($usersTest as $user){
           $res =  $client->executeRaw(["bf.exists","ants",$user],$err);
            if($res == 1){
                $fail++;
            }
        }

        echo "total users $num".PHP_EOL;
        echo "fails---".$fail.PHP_EOL;
        echo  "fail rate---".$fail/($num/2).PHP_EOL;
    }

}