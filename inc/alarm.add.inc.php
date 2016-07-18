<div class="panel panel-default">
    <div class="panel-heading">Alarm Settings</div>
    <div class="panel-body">
        <?php
        include_once('lib/weckerpy.php');
        ?>
        <script>
            $(document).ready(function() {
                $('#fade_div').hide();

                $('#wecker_fade').click(function(event) {
                    $('#fade_div').toggle();
                });

                $("#addform").submit(function(e) {
                    e.preventDefault();

                    var weekday = $('#wecker_wochentag').val();
                    var hour = $('#wecker_hour option:selected').val();
                    var minute = $('#wecker_minute option:selected').val();
                    var playlist = $('#wecker_playlist option:selected').val();
                    var duration = $('#wecker_duration').val();

                    var onoff = 0;
                    if ($('#wecker_onoff').hasClass('checked')) {
                        onoff = 1
                    }
                    var fade = 0;
                    if ($('#wecker_fade').hasClass('checked')) {
                        fade = 1
                    }

                    var dimm = 0;
                    if ($('#wecker_dimm').hasClass('checked')) {
                        dimm = 1
                    }
                    var power_on = $('#wecker_power_on').val();
                    var power_off = $('#wecker_power_off').val();
                    var end_volume = $('#wecker_end_volume').val();
                    var start_volume = $('#wecker_start_volume').val();
                    var tts_content = $('#wecker_tts').val();
                    var tts = $('<div/>').text(tts_content).html();

                    if (weekday == null) {
                        alert('Please choose a weekday');
                        return;
                    }
                    if (duration == '') {
                        duration = 0;
                    }

                    if (playlist == '') {
                        alert('Please choose a playlist');
                        return;
                    }

                    $.get('./lib/weckerpy.php?action=addwecker&weekday=' + weekday + '&hour=' + hour + '&minute=' + minute + '&playlist=' + playlist + '&fade=' + fade + '&dimm=' + dimm + '&duration=' + duration + '&onoff=' + onoff + '&power-on=' + power_on + '&power-off=' + power_off + '&start-volume=' + start_volume + '&end-volume=' + end_volume + '&tts=' + tts, function(data) {
                        if (data != 'True') {
                            alert(data);
                        }
                        window.location.href = "index.php?action=alarmsettings";
                    });
                    return false;
                });

            });

        </script>
        <form role="form" id="addform">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Weekday</label>                    
                    <select class="form-control" id="wecker_wochentag" class="select-block" multiple>
                        <?php
                        $timestamp = strtotime('last Monday');
                        $days = array();
                        for ($i = 0; $i < 7; $i++) {
                            $days[] = strftime('%A', $timestamp);
                            $timestamp = strtotime('+1 day', $timestamp);
                        }
                        foreach ($days as $d) {
                            echo '<option value="' . $d . '">' . $d . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Time</label>                        
                    <div class="row">
                        <div class="col-xs-6">
                            <select class="form-control" id="wecker_hour" >
                                <?php
                                $max = 23;
                                for ($i = 0; $i <= $max; $i++) {
                                    $format = str_pad($i, 2, 0, STR_PAD_LEFT);
                                    echo "<option value=\"$format\" >$format</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xs-6">
                            <select class="form-control" id="wecker_minute" >
                                <?php
                                $max = 59;
                                for ($i = 0; $i <= $max; $i++) {
                                    $format = str_pad($i, 2, 0, STR_PAD_LEFT);
                                    echo "<option value=\"$format\" >$format</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="wecker_playlist">Playlist</label>
                    <select class="form-control" id="wecker_playlist" class="select-block">
                        <option value="">Choose Playlist</option>
                        <?php
                        $playlists = array('wecker', 'housetime', 'top100');
                        foreach ($playlists as $playlist) {
                            echo "<option value=\"$playlist\">$playlist</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label" for="wecker_duration">Duration <span style="color: #737373; font-size: 12px">Zero equals endless alarm</span></label>
                    <input class="form-control" id="wecker_duration" type="number" size="1" placeholder="Duration" class="nomargb">
                </div>

                <div class="form-group">
                    <label class="control-label" for="wecker_start_volume">Start Volume</label>
                    <input class="form-control" id="wecker_start_volume" type="number" size="1"  value="50" class="nomargb">
                </div>
                <div class="checkbox" id="wecker_fade">
                    <label for="wecker_fadestatus">
                        <input type="checkbox" class="form-control" value="" id="wecker_fadestatus" data-toggle="checkbox">Fade Music In</label>
                </div>
                <div id="fade_div">
                    <div class="form-group">
                        <label class="control-label" for="wecker_end_volume">End Volume</label>
                        <input class="form-control" id="wecker_end_volume" type="number" size="1"  value="80" class="nomargb">
                    </div>
                </div>


            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Power Supplies ON</label>                    
                    <select class="form-control" id="wecker_power_on" class="select-block" multiple>
                        <?php
                        include_once('lib/system.php');
                        $config = GetConfig();
                        $sockets = getSockets($config);
                        foreach ($sockets as $socket) {
                            if ($socket == 'Verstaerker')
                                echo '<option selected value="' . urlencode($socket) . '">' . $socket . '</option>';
                            else
                                echo '<option value="' . urlencode($socket) . '">' . $socket . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Power Supplies OFF</label>                    
                    <select class="form-control" id="wecker_power_off" class="select-block" multiple>
                        <?php
                        include_once('lib/system.php');
                        $config = GetConfig();
                        $sockets = getSockets($config);
                        foreach ($sockets as $socket) {
                            echo '<option value="' . urlencode($socket) . '">' . $socket . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="checkbox" id="wecker_dimm" >
                    <label for="wecker_dimmstatus">
                        <input type="checkbox" class="form-control" value="" id="wecker_dimmstatus" data-toggle="checkbox">Dimmer</label>
                </div>
                <div class="checkbox" id="wecker_onoff" >
                    <label for="wecker_status">
                        <input type="checkbox" class="form-control" value="" id="wecker_status" data-toggle="checkbox">Active</label>
                </div>
                <div class="form-group">
                    <label class="control-label" for="wecker_tts">TTS</label>
                    <textarea class="form-control" id="wecker_tts" rows="3" placeholder="Text to say"></textarea>
                    <p class="help-block">The following Placeholder are possible:
                    <ul class="help-block">
                        <li>{time} for the current Time</li>
                        <li>{temp} for the current temperature</li>
                        <li>{wetter} for the current weather</li>
                        <li>{vorhersage} for the current weather forecast</li>
                    </ul>
                    </p>
                </div>

                <button type="submit" class="btn btn-primary">Create Alarm</button>

            </div>          
        </form>
    </div>
</div>