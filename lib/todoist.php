<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include_once 'system.php';

require (__DIR__.'/../conf/config.php');
$action = Get('action');
$itemID = Get('item');
$Value = Get('value');

if ($action != '') {

    switch ($action) {
        case 'all':
            echo getAllData();
            break;
		case 'closeItem':
			//echo json_encode(closeItem($itemId), JSON_NUMERIC_CHECK);
			echo closeItem($itemID);
			break;
		case 'updateItemName':
			//echo json_encode(closeItem($itemId), JSON_NUMERIC_CHECK);
			echo updateItemName($itemID, $Value);
			break;
		case 'newItem':
			//echo json_encode(closeItem($itemId), JSON_NUMERIC_CHECK);
			echo newItem($itemID, $Value);
			break;
    }
}
	function getAllData() {
		$url = 'https://todoist.com/API/v7/sync';
		$data = array('token' => todoist_Token, 'sync_token' => '*', 'resource_types' => '["all"]');

		foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$ch  = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 3);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		if ($response === FALSE) { /* Handle error */ }
		return $response;
	}
	function closeItem($itemId) {
				$url = 'https://todoist.com/API/v7/sync';
		$uuid = md5(uniqid(rand(), true));
		$commands = array(array('type' => "item_close", "uuid" => $uuid, "args" => array("id" => $itemId)));
		$commandsJSON = json_encode($commands, JSON_NUMERIC_CHECK);
		$data = array('token' => todoist_Token, 'commands' =>  $commandsJSON);

		foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string);

		rtrim($fields_string, '&');
				rtrim($fields_string, '&');

		$ch  = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		if ($response === FALSE) { /* Handle error */ }
		return $response;
	}
	function updateItemName($itemId, $val) {
				$url = 'https://todoist.com/API/v7/sync';
		$uuid = md5(uniqid(rand(), true));
		$commands = array(array('type' => "item_update", "uuid" => $uuid, "args" => array("id" => $itemId, "content" => $val)));
		$commandsJSON = json_encode($commands, JSON_NUMERIC_CHECK);
		$data = array('token' => todoist_Token, 'commands' =>  $commandsJSON);

		foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string);

		rtrim($fields_string, '&');
				rtrim($fields_string, '&');

		$ch  = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		if ($response === FALSE) { /* Handle error */ }
		return $response;
	}
	function newItem($itemId, $val) {
				$url = 'https://todoist.com/API/v7/sync';
		$uuid = md5(uniqid(rand(), true));
				$tempId = md5(uniqid(rand(), true));

		$commands = array(array('type' => "item_add", "uuid" => $uuid,"temp_id" => $tempId, "args" => array("project_id" => $itemId, "content" => $val)));
		$commandsJSON = json_encode($commands, JSON_NUMERIC_CHECK);
		$data = array('token' => todoist_Token, 'commands' =>  $commandsJSON);

		foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string);

		rtrim($fields_string, '&');
				rtrim($fields_string, '&');

		$ch  = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		if ($response === FALSE) { /* Handle error */ }
		return $response;
	}
?>
