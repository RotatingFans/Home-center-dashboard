<?php

include_once 'system.php';
include_once 'db.class.php';
require (__DIR__.'/../conf/config.php');


$action = Get('value');
$nodeID = Get('nodeID');

if ($nodeID == '')
    $nodeID = 1;

if ($action != '') {

    switch ($action) {
        case 'nodeName':
            echo json_encode(getNodeName(), JSON_NUMERIC_CHECK);

            break;
        case 'nodes':


            echo getNodes();
            break;
        case 'datapoint':
            echo json_encode(getNextDataPoint($nodeID), JSON_NUMERIC_CHECK);
            break;

        case 'average':
            echo getAverage($nodeID);
            break;

        case 'data':
            //$nodes = getNodes();
            $data = GetData(array(20, 19, 1));
            break;

        case 'latest':
            $ausgabe = array();
            $nodes = getNodes();
            foreach ($nodes as $node) {
                array_push($ausgabe, getNextDataPoint($node['node']));
            }

            echo json_encode($ausgabe, JSON_NUMERIC_CHECK);
            break;

        case 'motion':
            $limit = Get('limit') != '' ? Get('limit') : 3;
            echo json_encode(getMotion($nodeID, $limit), JSON_NUMERIC_CHECK);
            break;

        case 'lightsensors':
            $limit = Get('limit') != '' ? Get('limit') : 1;
            echo json_encode(getLightData($nodeID, $limit), JSON_NUMERIC_CHECK);
            break;
            
        case 'app':
          $ausgabe = array();
          array_push($ausgabe, getNextDataPoint(20));
          array_push($ausgabe, getNextDataPoint(19));
          array_push($ausgabe, getNextDataPoint(18));
          array_push($ausgabe, getNextDataPoint(1));
          array_push($ausgabe, getNextDataPoint(6));
          array_push($ausgabe, getNextDataPoint(8));
          array_push($ausgabe, getNextDataPoint(7));
          array_push($ausgabe, getMotion(2, 3));
          array_push($ausgabe, getMotion(3, 3));
          array_push($ausgabe, getMotion(7, 3));
          array_push($ausgabe, getLightData(5, 1));
          echo json_encode($ausgabe, JSON_NUMERIC_CHECK);  
    }
}
function getNodeName($node) {

    $name = 'None';
    $db = new db("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $options);
    $nodes = $db->select("nodes", "nodeID=" . $node, "", "");
            $name = $data[0]['Name'];

    return $name;
}
function getNodes() {

    $db = new db("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $options);
    $nodes = $db->select("nodes", "", "", "DISTINCT(nodeID) as node");
    return $nodes;
}

function GetData($nodes) {

    $db = new db("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $options);

    $daten = array();

    foreach ($nodes as $node) {
        $daten[$node] = array();

        $bind = array(
            ":nodeID" => $node
        );
        $datalist = $db->select("sensor_log", "nodeID = :nodeID", $bind);

        foreach ($datalist as $row) {
            $unix_date = new DateTime($row['time'], new DateTimezone('UTC'));
            array_push($daten[$node], array($unix_date->getTimestamp() * 1000, $row['time'], $row['temp'], $row['humidity']), getNodeName($node));
        }
    }
    $ausgabe = array();
    foreach ($daten as $node => $data) {
        array_push($ausgabe, getData4Chart($data, 2));
        $humidity = getData4Chart($data, 3);
        if (!empty($humidity)) {
            array_push($ausgabe, $humidity);
        }
    }
    echo json_encode($ausgabe, JSON_NUMERIC_CHECK);
}

function getData4Chart($daten, $index) {
    $data = array();
    foreach ($daten as $key => $value) {
        if ($value[$index] != null) {
            array_push($data, array($value[0], $value[$index]));
        }
    }
    return $data;
}

function getNextDataPoint($nodeID) {


    $db = new db("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $options);

    $bind = array(
        ":nodeID" => $nodeID
    );
    $datalist = $db->select("sensor_log", "nodeID = :nodeID ORDER BY time DESC LIMIT 1", $bind);

    $unix_date = new DateTime($datalist[0]['time'], new DateTimezone('UTC'));
    return array($unix_date->getTimestamp() * 1000, $datalist[0]['time'], $datalist[0]['temp'], $datalist[0]['humidity'], $nodeID, getNodeName($nodeID));
}

function getLatestDataPoints($nodeID) {

    $db = new db("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $options);

    $bind = array(
        ":nodeID" => $nodeID
    );
    $datalist = $db->select("sensor_log", "nodeID = :nodeID ORDER BY time DESC LIMIT 1", $bind);

    $unix_date = new DateTime($datalist[0]['time'], new DateTimezone('UTC'));
    return array($unix_date->getTimestamp() * 1000, $datalist[0]['time'], $datalist[0]['temp'], $datalist[0]['humidity'], $datalist[0]['vcc']);
}

function getAverage($nodeID) {


    $db = new db("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $options);

    $bind = array(
        ":nodeID" => $nodeID
    );
    $averagelist = $db->select("sensor_log", "nodeID = :nodeID", $bind, "TIMESTAMPDIFF(HOUR, min(time), max(time)) as diff, avg(temp) as  avg_temp, avg(humidity) as avg_humidity");

    return json_encode(array($averagelist[0]['diff'], round($averagelist[0]['avg_temp'], 1), round($averagelist[0]['avg_humidity'], 1)), JSON_NUMERIC_CHECK);
}

function getMotion($nodeID, $limit) {

    $db = new db("mysql:host=127.0.0.1;port=3306;dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $options);

    $bind = array(
        ":nodeID" => $nodeID
    );
    $motionlist = $db->select("sensor_motion_log", "nodeID = :nodeID ORDER BY time DESC LIMIT $limit", $bind, "vcc, time, motion,  TIMEDIFF(NOW(), time) as diff");
    $list = array();
    foreach ($motionlist as $motion) {
        array_push($list, array('time' => $motion['time'], 'vcc' => $motion['vcc'], 'diff' => $motion['diff'], 'motion' => $motion['motion'], $nodeID));
    }
    return $list;
}

function getLightData($nodeID, $limit) {


    $db = new db("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD, $options);

    $bind = array(
        ":nodeID" => $nodeID
    );
    $lightlist = $db->select("sensor_ldr_log", "nodeID = :nodeID ORDER BY time DESC LIMIT $limit", $bind, "vcc, time, ldr1, ldr2,  TIMEDIFF(NOW(), time) as diff");

    $list = array();
    foreach ($lightlist as $light) {
        $herd_an = 0;
        $backofen_an = 0;
        if ($light['ldr1'] > 500) {
            $herd_an = 1;
        }
        if ($light['ldr2'] > 500) {
            $backofen_an = 1;
        }
        array_push($list, array('time' => $light['time'], 'vcc' => $light['vcc'], 'diff' => $light['diff'], 'herd' => $herd_an, 'backofen' => $backofen_an, 'nodeID' => $nodeID));
    }
    return $list;
}

?>
