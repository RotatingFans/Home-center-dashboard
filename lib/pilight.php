<?php

include_once 'system.php';

$action = Get('action');

switch ($action) {

    /* ------------------- Socket ------------------- */
    case 'setsocket':
        $socket = Get('socket');
        $status = Get('status');
        if ($status != '') {
            $state = $status == 1 ? 'on' : 'off';
            if ($socket != '') {
                sendPilight($socket, $state);
            } else {
                $config = GetConfig();
                $sockets = getSockets($config);
                foreach ($sockets as $socket) {
                    sendPilight($socket, $state);
                }
            }
        }
        break;

    case 'display':
        $status = Get('status');
        switch ($status) {
            case "off":
                exec("gpio mode 1 out", $out, $result);
                shell_exec("sudo /usr/local/sbin/display.sh off");
                break;
            case "on":
                exec("gpio mode 1 out", $out, $result);
                shell_exec("sudo /usr/local/sbin/display.sh on");
                break;
            case "dimm":
                $value = Get('value');
                if ($value != '') {
                    exec("gpio mode 1 pwm", $out, $result);
                    exec("gpio pwm 1 " . $value, $out, $result);
                }
                break;
        }
        break;

    case 'motor':
        $status = Get('status');
        switch ($status) {
            case 'getlaststatus':
                $file = "/usr/local/sbin/motor.txt";
                echo file_get_contents($file);
                break;

            case 'off':
                exec("sudo python /usr/local/sbin/motor.py dimm 0", $out, $result);
                break;
            case 'on':
                exec("sudo python /usr/local/sbin/motor.py dimm 1024", $out, $result);
                break;

            case 'dimm':
                $value = Get('value');
                if ($value != '') {
                    exec("sudo python /usr/local/sbin/motor.py dimm " . $value, $out, $result);
                }
                break;
        }
        break;

    case 'dimmer':
        $status = Get('status');
        switch ($status) {
            case 'off':
            case 'on':
            case 'up':
            case 'down':
                sendPilightDimmer($status);
                break;
            case 'dimm':
                $value = Get('value');
                if ($value != '') {
                    sendPilightDimmer($status, $value);
                }
                break;
        }
        break;
}
?>
