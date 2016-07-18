<?php
include_once('lib/system.php');

$config = GetConfig();
$sockets = getSockets($config);
$sockets_out = '';
foreach ($sockets as $device) {

    $sockets_out .= "<li>
                      <div class=\"button socket\">
                        <div class=\"button_text\">{$device}</div>
                        <div class=\"button_off button\" socket-name=\"{$device}\">OFF</div>
                        <div class=\"button_on button\" socket-name=\"{$device}\">ON</div> 
                      </div>
                     </li>";
}
$sockets_out = ((count($sockets) > 0) ? "<ul class=\"buttonlist\">{$sockets_out}</ul>" : '');

?>
<script>
    $(document).ready(function() {
        /*
         * control power sockets with pilight 
         */
        $('.socket > .button_on').click(function() {
            var socket = $(this).attr('socket-name');
            if (socket != '' && socket != 'undefined') {
                $.get('lib/pilight.php?action=setsocket&socket=' + socket + '&status=1', function() {
                });
            }
        });

        $('.socket > .button_off').click(function() {
            var socket = $(this).attr('socket-name');
            if (socket != '' && socket != 'undefined') {
                $.get('lib/pilight.php?action=setsocket&socket=' + socket + '&status=0', function() {
                });
            }
        });

        $('.button_on.all').click(function() {
            $.get('lib/pilight.php?action=setsocket&status=1', function(data) {

            });
        });
        $('.button_off.all').click(function() {
            $.get('lib/pilight.php?action=setsocket&status=0', function(data) {

            });
        });


        /* 
         * Get and Set Values for pilight dimmer
         */

        $("#slider_pilight").slider({
            max: <?= $config["devices"]["dimmer"]["dimlevel-maximum"] ?>,
            min: <?= $config["devices"]["dimmer"]["dimlevel-minimum"] ?>,
            value: <?= $config["devices"]["dimmer"]["dimlevel"] ?>,
            slide: function(event, ui) {
                var value = $(this).slider("option", "value");
                $('#slider_pilight_content').html(value);
            },
            change: function(event, ui) {
                var value = $(this).slider("option", "value");
                $('#slider_pilight_content').html(value);
                $("#slider_pilight").slider("option", "disabled", true);
                $.get('./lib/pilight.php?action=dimmer&status=dimm&value=' + value, function() {
                    $("#slider_pilight").slider("option", "disabled", false);
                });

            }
        });

        $('.button_on.dimmer.pilight').click(function() {
            $("#slider_pilight").slider("option", "disabled", true);
            $.get('./lib/pilight.php?action=dimmer&status=on', function() {
                $("#slider_pilight").slider("option", "disabled", false);
            });
        });
        $('.button_off.dimmer.pilight').click(function() {
            $("#slider_pilight").slider("option", "disabled", true);
            $.get('./lib/pilight.php?action=dimmer&status=off', function() {
                $("#slider_pilight").slider("option", "disabled", false);
            });
        });
        $('.button_up.dimmer.pilight').click(function() {
            $("#slider_pilight").slider("option", "disabled", true);
            $.get('./lib/pilight.php?action=dimmer&status=up', function() {
                $("#slider_pilight").slider("option", "disabled", false);
            });
        });
        $('.button_down.dimmer.pilight').click(function() {
            $("#slider_pilight").slider("option", "disabled", true);
            $.get('./lib/pilight.php?action=dimmer&status=down', function() {
                $("#slider_pilight").slider("option", "disabled", false);
            });
        })

        /* 
         * Get and Set Values for stepper motor dimmer
         */
        $("#slider_motor").slider({
            max: 1024,
            min: 0,
            value: 0,
            slide: function(event, ui) {
                var value = $(this).slider("option", "value");
                $('#slider_motor_content').html(value);
            },
            change: function(event, ui) {
                var value = $(this).slider("option", "value");
                $('#slider_motor_content').html(value);
                $("#slider_motor").slider("option", "disabled", true);
                $.get('./lib/pilight.php?action=motor&status=dimm&value=' + value, function() {
                    $("#slider_motor").slider("option", "disabled", false);
                });

            }
        });

        $.ajax({
            url: './lib/pilight.php?action=motor&status=getlaststatus',
            success: function(data) {
                $("#slider_motor").slider("option", "value", data);
                $('#slider_motor_content').html(data);
            }
        });
        $('.button_on.dimmer.motor').click(function() {
            if (!$("#slider_motor").slider("option", "disabled"))
                $("#slider_motor").slider("option", "value", 1024);
        });
        $('.button_off.dimmer.motor').click(function() {
            if (!$("#slider_motor").slider("option", "disabled"))
                $("#slider_motor").slider("option", "value", 0);
        });

        /*
         * Set Weather Display Background Light
         */
        $('.display').click(function() {
            var value = $(this).attr('display-value');
            if (value == 'on') {
                $.get('lib/pilight.php?action=display&status=on');
            } else if (value == 'off') {
                $.get('lib/pilight.php?action=display&status=off');
            } else if (value != '') {
                $.get('lib/pilight.php?action=display&status=dimm&value=' + value);
            }
        });
    });
</script>
<?php
if ($sockets_out != '') {
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-2">
                    Sockets
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-10">
                    <div class="pull-right" style="margin-right:10px;">  
                        <div class="button_off button all">OFF</div>
                        <div class="button_on button all">ON</div> 
                    </div>
                </div>
            </div>

        </div>
        <div class="panel-body">
            <?= $sockets_out ?>

        </div>
    </div>
<?php } ?>   


<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-2">
                Dimmer (Pilight)
            </div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-10">
                <div class="pull-right" style="margin-right:10px;"> 
                    <div class="button_up button dimmer pilight">UP</div>
                    <div class="button_down button dimmer pilight">DOWN</div> 
                    <div class="button_off button dimmer pilight">OFF</div>
                    <div class="button_on button dimmer pilight">ON</div> 
                </div>
            </div>
        </div>  
    </div>
    <div class="panel-body">
        <div class="row" style="padding:10px">
            <div id="slider_pilight_content" class="col-sm-1"><?= $config["devices"]["dimmer"]["dimlevel"] ?></div>
            <div id="slider_pilight" class="col-sm-11" style="margin-top:5px"></div>  
        </div>
    </div>
</div>

<div class="panel panel-default hidden">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-2">
                Dimmer (Stepper Motor)
            </div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-10">
                <div class="pull-right" style="margin-right:10px;">  
                    <div class="button_off button dimmer motor">OFF</div>
                    <div class="button_on button dimmer motor">ON</div> 
                </div>
            </div>
        </div>  
    </div>
    <div class="panel-body">
        <div class="row" style="padding:10px">
            <div id="slider_motor_content" class="col-sm-1"></div>
            <div id="slider_motor" class="col-sm-11" style="margin-top:5px"></div>  
        </div>
    </div>
</div>

<div class="panel panel-default hidden">
    <div class="panel-heading">Weather-Display

    </div>
    <div class="panel-body">
        <div class="row" style="padding:10px">
            <button type="button" class="btn btn-primary display" display-value="off">Off</button>
            <button type="button" class="btn btn-primary display" display-value="on">On</button>
        </div>
    </div>
</div>