<?php

include_once 'system.php';


$action = Get('action');


switch ($action) {

    /* ------------------- Schedule ------------------- */

    case 'power':
        sendPilight("Verstaerker", "on");
        sleep (1);
        shell_exec('irsend SEND_ONCE auna KEY_POWER');
        break;

    case 'volup':
        shell_exec('irsend SEND_ONCE auna KEY_VOLUMEUP');       
        break;

    case 'voldown':
        shell_exec('irsend SEND_ONCE auna KEY_VOLUMEDOWN');
        break;
        
    case 'volmute':
        shell_exec('irsend SEND_ONCE auna KEY_MUTE');
        break;
        
    case 'select':
        shell_exec('irsend SEND_ONCE auna KEY_SELECT');
        break;
}


?>
