<?php
/**
 * Created by PhpStorm.
 * User: Ants
 * Date: 2018/12/6 15:11
 */

require "../../../vendor/autoload.php";

use Predis\Client;

$client = new Client();

for($i=1;$i<= 100000;$i++){
    $client->pfadd('codehole','user'.$i);
}

$count = $client->pfcount('codehole');
echo  $count;