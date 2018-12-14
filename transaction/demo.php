<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/14 10:28
 */

require "../../../vendor/autoload.php";

$client = new \Predis\Client();


$ts = new \Module\redisNotes\transaction\Transaction();

 echo $ts->doubleCount('10').PHP_EOL;






