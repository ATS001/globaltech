<?php 

global $db;

$sql = "SELECT * FROM devisd";

if($db->Query($sql))
{
    $arr = $db->GetHTML();
    echo $arr;
}else{
	log_cron('test log', 'test');
}

