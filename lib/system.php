<?php

define('PILIGHT_ADDRESS', '127.0.0.1');
define('PILIGHT_PORT', 5000);

function Get($val) {
    if (isset($_GET[$val]))
        return $_GET[$val];
    else
        return '';
}

function aasort(&$array, $key) {
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}

/**
 * ========================================================================
 *                          Pilight
 * ========================================================================
 */

/**
 * send Pilight remote Socket
 * @param type $socketname
 * @param type $state
 * @return type
 */
function sendPilight($socketname, $state) {

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    $result = socket_connect($socket, PILIGHT_ADDRESS, PILIGHT_PORT);
    var_dump($result);

    $data = "{\"action\":\"identify\"}";
    $out = '';
    socket_write($socket, $data, strlen($data));
    $out .= socket_read($socket, 1024);

    $data2 = "{\"action\":\"control\",\"code\":{\"device\":\"" . str_replace(' ', '', $socketname) . "\",\"state\":\"" . $state . "\"}}";

    socket_write($socket, $data2, strlen($data2));
    $out .= socket_read($socket, 1024);

    socket_close($socket);
    return $out;
}

/**
 * send Pilight remote dimmer
 * @param type $type
 * @param type $value
 * @return type
 */
function sendPilightDimmer($type, $value='') {       #


    if ($type == "up"  || $type == "down"){
       $config = GetConfig();
       $max =  $config["devices"]["dimmer"]["dimlevel-maximum"];
       $min =  $config["devices"]["dimmer"]["dimlevel-minimum"];
       $curr =   $config["devices"]["dimmer"]["dimlevel"];
       if($type == "up"){
          $value = $curr +1 >= $max ? $max : $curr + 1;
       }
       if($type == "down"){
          $value = $curr - 1 <= $min ? $min : $curr - 1;
       }
       var_dump($value);
    }

    $socketname = "dimmer";

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    $result = socket_connect($socket, PILIGHT_ADDRESS, PILIGHT_PORT);
    var_dump($result);

    $data = "{\"action\":\"identify\"}";
    $out = '';
    socket_write($socket, $data, strlen($data));
    $out .= socket_read($socket, 1024);


    if ($type == "dimm" || $type == "up" || $type == "down") {
        $data2 = "{\"action\":\"control\",\"code\":{\"device\":\"" . str_replace(' ', '', $socketname) . "\",\"values\":{\"dimlevel\":" . $value . "}}}";
    } elseif ($type == "on") {
        $data2 = "{\"action\":\"control\",\"code\":{\"device\":\"" . str_replace(' ', '', $socketname) . "\",\"state\": \"on\"}}";
    } elseif ($type == "off") {
        $data2 = "{\"action\":\"control\",\"code\":{\"device\":\"" . str_replace(' ', '', $socketname) . "\",\"state\": \"off\"}}";
    }

    socket_write($socket, $data2, strlen($data2));
    $out .= socket_read($socket, 1024);

    socket_close($socket);
    return $out;
}

/**
 * get Pilight Config
 * @return type
 */
function GetConfig() {
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    $result = socket_connect($socket, PILIGHT_ADDRESS, PILIGHT_PORT);

    $data = "{\"action\":\"identify\"}";
    $out = '';
    socket_write($socket, $data, strlen($data));
    $out .= socket_read($socket, 1024);

    $data2 = "{\"action\":\"request config\"}";

    $config = '';
    socket_write($socket, $data2, strlen($data2));
    //$config = socket_read($socket, 2048);

    do {
        $out = socket_read($socket, 1025);
        $config .= $out;
    } while (strlen($out) >= 1024);


    socket_close($socket);
    $conf = json_decode($config, true);
    return $conf['config'];
}

/**
 * get Sockets from Pilight config
 * @param type $config
 * @return array
 */
function getSockets($config) {
    $sockets = array();
    foreach ($config['gui'] as $device) {
        if (array_key_exists('media', $device)) {
            if (in_array("web", $device["media"])) {
                array_push($sockets, $device['name']);
            }
        }
    }
    return $sockets;
}

?>