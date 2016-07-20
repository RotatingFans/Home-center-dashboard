<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include_once 'system.php';

require (__DIR__.'/../conf/config.php');
$action = GET('action');

if ($action != '') {

    switch ($action) {
        case '10day':
            echo tenDay();
            break;
		case 'hourly':
			//echo json_encode(closeItem($itemId), JSON_NUMERIC_CHECK);
			echo hourly();
			break;
		case 'conditions':
			//echo json_encode(closeItem($itemId), JSON_NUMERIC_CHECK);
			echo conditions();
			break;
		case 'radar';


			$file = "http://api.wunderground.com/api/" . weatherApiKey . "/animatedradar/animatedsatellite/q/" . weatherLocation . ".gif?rad.rainsnow=1&sat.smooth=1&rad.smooth=1&noclutter=1&sat.radius=250&sat.timelabel=1&sat.timelabel.y=290&sat.timelabel.x=800&num=8&delay=50&interval=30&sat.width=1200&height=600&sat.key=sat_ir4&sat.borders=1";
			//$imginfo = getimagesize($file);
			header("Content-type: image/gif");
			readfile($file);
			//echo $file;
		break;

    }
}
	function tenDay() {
		$url = "http://api.wunderground.com/api/" . weatherApiKey . "/forecast10day/q/" . weatherLocation . ".json";
		$response  = file_get_contents($url);
		if ($response === FALSE) { /* Handle error */ }
		return $response;
	}
	function hourly() {
		$url = "http://api.wunderground.com/api/" . weatherApiKey . "/hourly/q/" . weatherLocation . ".json";
		$response  = file_get_contents($url);

		if ($response === FALSE) { /* Handle error */ }
		return $response;
	}
	function conditions() {
		$url = "http://api.wunderground.com/api/" . weatherApiKey . "/conditions/q/" . weatherLocation . ".json";
		$response  = file_get_contents($url);

		if ($response === FALSE) { /* Handle error */ }
		return $response;
	}
	function radar() {

	}

?>
