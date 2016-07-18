<?php
include_once('lib/sensors.php');

?>
	<script src="https://code.highcharts.com/stock/highstock.js"></script>

	<script>
		$(document).ready(function() {

			fillPage();
			// createMotionTable(); // createPostcaseTable(); // createLightSensorTable();

			setInterval(fillPage, 30000);
			//			setInterval(createMotionTable, 30000);
			//			setInterval(createPostcaseTable, 30000);
			//			setInterval(createLightSensorTable, 60000);

			var graph_is_visible = false;
			var chart;
			var options = {
				chart: {
					zoomType: 'x',
					renderTo: 'graph_container'
						/*,events: {
						load: function() {
						// set up the updating of the chart each minute
						var series = this.series[0];
						var series2 =  this.series[1];
						var series3 = this.series[2];
						setInterval(function() {
						$.getJSON('lib/sensors.php?value=latest', function(data) {
						// DHT22
						series.addPoint([data[0][0],data[0][2]], true, true);
						series2.addPoint([data[0][0],data[0][3]], true, true);
						
						// Temp
						series3.addPoint([data[1][0],data[1][2]], true, true);
						 
						})
						}, 60000);
						
						}
						}*/
				},
				yAxis: [{ // Primary yAxis
					labels: {

						formatter: function() {
							var val = this.value;
							var valFloat = parseFloat(val);
							return (valFloat * 1.8 + 32).toFixed(1) + '°F / ' + val.toFixed(1) + '°C';
						},
						style: {
							color: '#AA4643'
						}
					},
					title: {
						text: 'Temperature',
						style: {
							color: '#AA4643'
						}
					}
				}, { // Secondary yAxis
					title: {
						text: 'Humidity',
						style: {
							color: '#4572A7'
						}
					},
					labels: {
						format: '{value} %',
						style: {
							color: '#4572A7'
						}
					},
					opposite: false
				}],
				xAxis: {
					type: 'datetime'
				},
				rangeSelector: {
					inputEnabled: false,
					buttons: [{
						type: 'minute',
						count: 10,
						text: '10m'
					}, {
						type: 'minute',
						count: 30,
						text: '30m'
					}, {
						type: 'minute',
						count: 60,
						text: '1h'
					}, {
						type: 'day',
						count: 1,
						text: '1d'
					}, {
						type: 'week',
						count: 1,
						text: '1w'
					}, {
						type: 'all',
						text: 'all'
					}]
				},
				legend: {
					enabled: true
				},
				series: [{
					name: 'Living Room Temp',
					data: [],
					color: '#AA4643',
					tooltip: {
						valueDecimals: 1,
						valueSuffix: '°C'
					}
				}, {
					name: 'Living Room Humidity',
					data: [],
					yAxis: 1,
					color: '#4572A7',
					tooltip: {
						valueDecimals: 1,
						valueSuffix: '%'
					}
				}, {
					name: 'Bedroom Temp',
					data: [],
					color: '#F44643',
					tooltip: {
						valueDecimals: 1,
						valueSuffix: '°C'
					}
				}, {
					name: 'Bedroom Humidity',
					data: [],
					yAxis: 1,
					color: '#2B92A7',
					tooltip: {
						valueDecimals: 1,
						valueSuffix: '%'
					}
				}, {
					name: 'Garage',
					data: [],
					tooltip: {
						valueDecimals: 1,
						valueSuffix: '°C'
					}
				}]
			};

			$('.button_showgraph').click(function() {
				if (graph_is_visible) {
					$('#graph_container').hide();
					$(this).text('Show');
					graph_is_visible = false;

				} else {
					$('#graph_container').show();
					graph_is_visible = true;
					$(this).text('Hide');

					chart.showLoading();
					$.ajax({
						url: 'lib/sensors.php?value=data',
						dataType: 'json',
						success: function(data) {
							for (var i = 0; i < data.length; i++) {
								chart.series[i].setData(data[i]);
							}
							chart.hideLoading();
						}
					});
				}
			});
			$('#graph_container').show();
			graph_is_visible = true;
			$('.button_showgraph').text('hide');

			chart = new Highcharts.StockChart(options);
			chart.showLoading();
			$.ajax({
				url: 'lib/sensors.php?value=data',
				dataType: 'json',
				success: function(data) {
					for (var i = 0; i < data.length; i++) {
						chart.series[i].setData(data[i]);
					}
					chart.hideLoading();
				}
			});


		});

		function fillPage() {
			$.getJSON('lib/sensors.php?value=latest', function(data) {
				//$("#tempsReal".html(
				$nodeHtml = '<div class="row" style="margin-left:0px;" id="tempsReal">';
				jQuery.each(data, function(i, val) {
					if (i > 4) {
						$nodeHtml = $nodeHtml +
							'</div><div class="row" style="margin-left:0px;" id="tempsReal">';
					}
					$time = Highcharts.dateFormat('%H:%M:%S', val[0]);
					$heading = val[4];
					$tempC = val[2];
					$tempF = val[2] * 1.8 + 32;
					$humidity = val[3];
					if ($humidity !== null) {
						$humidityCode = '<div class="row"><div class="col-xs-6 col-sm-5 col-md-4" style="text-align: right">Humidity:</div><div class="col-xs-6  col-sm-7 col-md-8"><span id="lastcheck_humidity_20"></span>' + $humidity +
							' %</div></div>';
					} else {
						$humidityCode = ''
					}
					$nodeHtml = $nodeHtml +
						'<div class="col-sm-3"><div class="row" style="margin-right:10px"><div class="panel panel-primary"><div class="panel-heading">' + $heading +
						'</div><div class="panel-body"><div class="row"><div class="col-xs-6 col-sm-5 col-md-4" style="text-align: right">Temp:</div><div class="col-xs-6  col-sm-7 col-md-8"><span id="lastcheck_temp_20"></span>' + $tempF +
						' °F</div></div><div class="row"><div class="col-xs-6 col-sm-5 col-md-4" style="text-align: right">Temp:</div><div class="col-xs-6  col-sm-7 col-md-8"><span id="lastcheck_temp_20"></span>' + $tempC +
						' °C</div></div>'
					'</div></div></div>';


				});
				$nodeHtml = $nodeHtml +
					'</div>';
				$('#sensorLast').html($nodeHtml);

			});


		}

		function createMotionTable() {
			$.getJSON('lib/sensors.php?value=motion&nodeID=2&limit=3', function(data) {
				var table = $(".motion tbody");
				table.html("");
				jQuery.each(data, function(i, val) {
					table.append("<tr><td>" + val['time'] + "</td><td>" + val['diff'] + "</td><td>" + val['vcc'] + "</td></tr>");
				});
			});
		}

		function createPostcaseTable() {
			$.getJSON('lib/sensors.php?value=motion&nodeID=3&limit=3', function(data) {
				var table = $(".postcase tbody");
				table.html("");
				jQuery.each(data, function(i, val) {
					var state = val['motion'] == 0 ? "geschlossen" : "offen";
					table.append("<tr><td>" + val['time'] + "</td><td>" + state + "</td><td>" + val['diff'] + "</td><td>" + val['vcc'] + "</td></tr>");
				});
			});
		}

		function createLightSensorTable() {
			$.getJSON('lib/sensors.php?value=lightsensors&nodeID=5&limit=1', function(data) {
				var table = $(".herd_backofen tbody");
				table.html("");
				jQuery.each(data, function(i, val) {
					var stateHerd = val['herd'] == 0 ? "AUS" : "AN";
					var stateBackofen = val['backofen'] == 0 ? "AUS" : "AN";
					table.append("<tr><td>" + val['time'] + "</td><td>" + stateHerd + "</td><td>" + stateBackofen + "</td><td>" + val['diff'] + "</td><td>" + val['vcc'] + "</td></tr>");
				});
			});
		}

		function getSensorData() {
			$.getJSON('lib/sensors.php?value=latest', function(data) {
				// Wohnzimmer
				$("#lastcheck_time_20").html(Highcharts.dateFormat('%H:%M:%S', data[0][0]));
				$("#lastcheck_temp_20").html(data[0][2]);
				$("#lastcheck_humidity_20").html(data[0][3]);
				$("#lastcheck_vcc_20").html(data[0][4]);
				if (!is_null(data[0][2])) {
					$("#tempsReal").html('<div class="col-sm-3"><div class="row" style="margin-right:10px"><div class="panel panel-primary"><div class="panel-heading">Wohnzimmer <span class="pull-right" style="font-size:small"><span id="lastcheck_time_20"></span> (<span id="lastcheck_vcc_20"></span> V)</span></div><div class="panel-body"><div class="row"><div class="col-xs-6 col-sm-5 col-md-4" style="text-align: right">  Temp:</div><div class="col-xs-6  col-sm-7 col-md-8"> <span id="lastcheck_temp_20"></span> °C</div></div><div class="row"><div class="col-xs-6 col-sm-5 col-md-4" style="text-align: right">  Humidity:</div><div class="col-xs-6  col-sm-7 col-md-8"> <span id="lastcheck_humidity_20"></span> %</div></div> </div>   </div></div></div>')
				}
				//Aussen
				$("#lastcheck_time_1").html(Highcharts.dateFormat('%H:%M:%S', data[1][0]));
				$("#lastcheck_temp_1").html(data[1][2]);
				$("#lastcheck_vcc_1").html(data[1][4]);

				// Bad
				$("#lastcheck_time_19").html(Highcharts.dateFormat('%H:%M:%S', data[2][0]));
				$("#lastcheck_temp_19").html(data[2][2]);
				$("#lastcheck_humidity_19").html(data[2][3]);
				$("#lastcheck_vcc_19").html(data[2][4]);

				//Sensor 6
				$("#lastcheck_time_6").html(Highcharts.dateFormat('%H:%M:%S', data[3][0]));
				$("#lastcheck_temp_6").html(data[3][2]);
				$("#lastcheck_vcc_6").html(data[3][4]);
			});
		}

	</script>
	<div id="sensorLast">

	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-2">
					Graph
				</div>
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-10">
					<div class="pull-right" style="margin-right:10px;">
						<div class="button_showgraph button">&#9776</div>
					</div>
				</div>
			</div>
		</div>

		<div class="panel-body">
			<div id="graph_container">

			</div>
		</div>
	</div>
