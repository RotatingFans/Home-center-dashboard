<?php
include_once('../conf/config.php');

include_once 'system.php';

$action = Get('action');


if($action == "setSmartswitch"){
    $data = json_decode(Get('data'));
    
    $conf = new Config();
    
    $string = array();
    foreach($data as $key => $led){
      $led = (array) $led;
      array_push($string, $led["r"].",".$led["g"].",".$led["b"]);
      /*$ch = curl_init("https://api.spark.io/v1/devices/".$conf->SPARK_DEVICE_ID."/ledrgb?access_token=".$conf->SPARK_ACCESS_TOKEN);*/
    } 
    $ch = curl_init("https://api.spark.io/v1/devices/".$conf->SMARTSWITCH_DEVICE_ID."/ledrgball?access_token=".$conf->SPARK_ACCESS_TOKEN);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "args=".implode(',', $string));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    $output = curl_exec($ch); 
    curl_close($ch);
      
    echo $output;
    //}
}else if($action == "setWordClock"){
    $data = json_decode(Get('data'));
        
    $conf = new Config();

    $data = (array) $data[0];
    $ch = curl_init("https://api.spark.io/v1/devices/".$conf->WORDCLOCK_DEVICE_ID."/controlColor?access_token=".$conf->SPARK_ACCESS_TOKEN);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "args=".$data["r"].",".$data["g"].",".$data["b"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    $output = curl_exec($ch); 
    curl_close($ch);
      
    echo $output;
}

?>
