<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>WeckerPy</title>
	<meta name="description" content="Control your Wecker" />
	<meta name="keywords" lang="en" content="" />
	<meta name="author" content="Tobias Haegenlaeuer">
	<meta name="date" content="2013-11-14" />
	<meta name="robots" content="noindex,nofollow">
	<meta http-equiv="content-language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">


	<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
	<!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/flat-ui.mod.css" />
	<link rel="stylesheet" type="text/css" href="css/no-more-table.css" />
	<link rel="stylesheet" type="text/css" href="css/main.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" href="css/colpick.css" />
	<link rel="stylesheet" type="text/css" href="css/flickity.css" />
	<link rel="stylesheet" type="text/css" href="css/weather.css" />



	<script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/flatui-checkbox.js" type="text/javascript"></script>
	<script src="js/fastclick.js" type="text/javascript"></script>

	<script>
		$(function() {
			FastClick.attach(document.body);
		});

	</script>
	<script src="js/jquery-ui.min.js" type="text/javascript"></script>
	<script src="js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
	<script src="js/colpick.js" type="text/javascript"></script>

</head>

<?php
   if(!isset($_GET["action"])){
      $_GET["action"] = "alarmlist";
   }
?>

	<body>
		<div class="container">

			<nav class="navbar navbar-inverse" role="navigation">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand visible-xs" href="#">Navigation</a>
				</div>

				<div class="navbar-collapse collapse navbar-collapse-02" id="navbar">
					<ul class="nav navbar-nav">
						<!--
                  <li active <?php if($_GET["action"] == "alarmlist"){ echo 'class="active"'; }?>>
                    <a href="./index.php?action=alarmlist">Alarms</a>
                  </li> 
                  <li <?php if($_GET["action"] == "powerlist"){ echo 'class="active"';} ?>>
                    <a href="./index.php?action=powerlist">Power</a>
                  </li>
-->
						<li <?php if($_GET[ "action"]=="sensors" ){ echo 'class="active"';} ?>>
							<a href="#slide3">Sensors</a>
						</li>
						<li <?php if($_GET[ "action"]=="weather" ){ echo 'class="active"';} ?>>
							<a href="#slide1">Weather</a>
						</li>
						<li <?php if($_GET[ "action"]=="control" ){ echo 'class="active"';} ?>>
							<a href="#slide2">Status</a>
						</li>

						<!--		
                  <li <?php if($_GET["action"] == "music"){ echo 'class="active"';} ?>>
                    <a href="./index.php?action=music">Music</a>
                  </li>
                  <li <?php if($_GET["action"] == "smartswitch"){ echo 'class="active"';} ?>>
                    <a href="./index.php?action=smartswitch">SmartSwitch</a>
                  </li>
                   <li <?php if($_GET["action"] == "alarmsettings"){ echo 'class="active"';} ?>>
                    <a href="./index.php?action=alarmsettings">Alarm Settings</a>
                  </li>
                    
                  
                   <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Links <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li <?php if($_GET["action"] == "powerpilist"){ echo 'class="active"';} ?>>
                        <a href="./index.php?action=powerpilist">PowerPi</a>
                      <li <?php if($_GET["action"] == "powerpisettings"){ echo 'class="active"';} ?>>
                        <a href="./index.php?action=powerpisettings">PowerPi Settings</a>
                      </li>
                      <li class="divider"></li>
                      <li><a href="/owncloud">owncloud</a></li>
                      <li class="divider"></li>
                      <li><a href="/pcc">Control Center</a></li>
                    </ul>
                  </li>
-->
					</ul>
				</div>
			</nav>
		</div>

		<div class="main-gallery container-fluid">
			<div class="gallery-cell">
				<div id="weather-cell" class="container">

					<?php include('inc/weather.display.inc.php'); ?>


				</div>
			</div>
			<div class="gallery-cell">
				<?php include('inc/dashboard.display.inc.php'); ?>
			</div>
			<div class="gallery-cell">
				<?php include('inc/status.inc.php'); ?>
			</div>
			<div class="gallery-cell">
				<?php include('inc/sensors.display.inc.php');?>
			</div>



			<!--
//    switch($_GET["action"]){ //// case "alarmlist": //// if(file_exists("/var/run/wecker.pid")){ //// include('inc/alarm.list.inc.php'); //// }else{ //// echo "
//<div class=\ "container-fluid\">
//	<h1>Daemon not running!</h1></div>"; //// } //// break; //// case "alarmsettings": //// if($_GET['a'] == 'add'){ //// include('inc/alarm.add.inc.php'); //// }else if($_GET['a'] == 'detail'){ //// include('inc/alarm.detail.inc.php'); //// }else if($_GET['a'] == 'edit'){ //// include('inc/alarm.edit.inc.php'); //// }else{ //// include('inc/alarm.setting.inc.php'); //// } //// break; //// case "powerlist": //// include('inc/power.list.inc.php'); //// break; //// case "powerpilist": //// include('inc/powerpi.list.inc.php'); //// break; // case "control": // include('inc/status.inc.php'); // break; //// case "powerpisettings": //// include('inc/powerpi.setting.inc.php'); //// break; // case "sensors": // include('inc/sensors.display.inc.php'); // break;
//        case "music":
//          include('inc/music.inc.php');
//          break;
//      case "smartswitch":
//          include('inc/smartswitch.inc.php');
//          break;
    //} 
    ?>
-->

		</div>
		<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js "></script>

		<script type="application/javascript">
			$(window).load(function() {
				$('.main-gallery').flickity({
					// options
					cellAlign: 'center',
					wrapAround: true,
					imagesLoaded: true,
					initialIndex: 0
				});
				jQuery(document).ready(function($) {
					$('#navbar a').on('click', function(event) {
						event.preventDefault();
						var indexElement = $(this).attr('href');
						indexElement = indexElement.replace('#slide', '');
						$('.main-gallery').flickity('select', parseInt(indexElement) - 1);
					});
				});
				//your code here
			});

		</script>
	</body>

</html>
