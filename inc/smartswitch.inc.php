<?php
include_once('lib/smartswitch.php');
?>
<script>
    $(document).ready(function() {
    
      var buttonvalues =[];
      buttonvalues[0] = {"r":0, "g":0, "b":0};
      buttonvalues[1] = {"r":0, "g":0, "b":0};
      buttonvalues[2] = {"r":0, "g":0, "b":0};
      buttonvalues[3] = {"r":0, "g":0, "b":0};
      
      var wordclockvalues = [{"r":0, "g":0, "b":0}];
              
    var colorpicker = $('.color-box').colpick({
	     colorScheme:'dark',
	     layout:'rgbhex',
	     color:'#000000',
       submit: false,
	     onChange:function(hsb,hex,rgb,el) {
		        $(el).css('background-color', '#'+hex);
            buttonvalues[$(el).attr('data-led')]  = rgb;
	     }
    });
    colorpicker.css('background-color', '#000000');
    
    $('#wordclock_picker').colpick({
      colorScheme:'dark',
    	flat:true,
    	layout:'rgbhex',
    	submit:false,
      onChange:function(hsb,hex,rgb,el) {
		        $(el).css('background-color', '#'+hex);
            wordclockvalues[0]  = rgb;
	     }
    });
    
    
    $('#sendtoSwitch').click(function(e){
      $.ajax({
           url: 'lib/smartswitch.php?action=setSmartswitch&data=' + JSON.stringify(buttonvalues),
           success: function(data) {
              console.log(data);
           }
      });
    });
    $('#sendtoClock').click(function(e){
      $.ajax({
           url: 'lib/smartswitch.php?action=setWordClock&data=' + JSON.stringify(wordclockvalues),
           success: function(data) {
              console.log(data);
           }
      });
    });

  });
</script>

<style>
.color-box {
	float:left;
	width:80px;
	height:80px;
	margin:5px;
	border: 1px solid white;
  -moz-border-radius: 10px;
  -webkit-border-radius: 10px;
  border-radius: 10px;
}
</style>
 <div class="panel panel-primary">
        <div class="panel-heading">LEDs</div>
        
        <div class="panel-body">
            <div class="row">
              <div class="center-block" style="width:180px;">
              
              <div class="color-box" data-led="3"></div>
              <div class="color-box" data-led="2"></div>
              <div class="clearfix" ></div>
              <div class="color-box" data-led="0"></div>
              <div class="color-box" data-led="1"></div>
              
                <div style="margin-top:100px;">
                  <button id="sendtoSwitch" title="Send to Switch" type="button" class="btn btn-primary center-block"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> SmartSwitch</button>
                </div>
              </div>
            </div>
      </div>
</div>

 <div class="panel panel-primary">
        <div class="panel-heading">WordClock</div>
        
        <div class="panel-body">
            <div class="row">
              <div class="center-block" style="width:180px;">
                <div id="wordclock_picker"></div>
                <div style="margin-top:10px;">
                    <button id="sendtoClock" title="Send to WordClock" type="button" class="btn btn-primary center-block"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> WordClock</button>
                </div>
              </div>
            </div>
      </div>
</div>
