<script type="text/javascript" src="../conf/conf.js"></script>
<script>
	$(document).ready(function() {
		fillWeather();
		setInterval(fillWeather, minutesToMilli(15));

	});

	function minutesToMilli(minutes) {
		return minutes * 60 * 1000
	}

	function fillWeather() {
		var dayData, hourlyData, currentData
		$.when(
			$.getJSON("lib/weather.php?action=10day", function() {})
			.done(function(data) {
				dayData = data

			}),
			$.getJSON("lib/weather.php?action=hourly", function() {})
			.done(function(data) {
				hourlyData = data

			}),
			$.getJSON("lib/weather.php?action=conditions", function() {})
			.done(function(data) {
				currentData = data

			})

		).then(function() {
			var weatherHtml = '<div id="days" class="row"><div class="col-xs-12 col-sm-2"><div class="current_conditions center-block"><div class="ConditionText"><h3>' + currentData.current_observation.temp_f + '°F</h3><img class="img-responsive center-block" src="' + currentData.current_observation.icon_url + '"></div><div class="ConditionText"><div id="condition">' + currentData.current_observation.weather + '</div><div id="humidity">' + currentData.current_observation.relative_humidity + ' relative humidity</div></div></div></div>';

			$.each(dayData.forecast.simpleforecast.forecastday, function(i, items) {
				weatherHtml = weatherHtml + '<div class="col-xs-3 col-sm-3 col-sm-1 day"><div class="row Date">' + items.date.weekday_short + ' ' + items.date.month + '/' + items.date.day + '</div><div class="row weather-img"><img src="' + items.icon_url + '"> </div><div class="row weather-data"><div id="tempHigh">' + items.high.fahrenheit + '°F</div><div id="tempLow">' + items.low.fahrenheit + '°F</div></div></div>';



			})
			weatherHtml = weatherHtml + '</div><div class="row hourly">';
			var hourCount = 0;

			$.each(hourlyData.hourly_forecast, function(i, items) {
					var hour = parseInt(items.FCTTIME.hour);
					var d = new Date();
					if (hour > d.getHours() && hourCount < 12) {

						if (items.FCTTIME.ampm == "PM" && hour > 12) {
							hour = hour - 12;
						}
						weatherHtml = weatherHtml + '<div class="col-xs-3 col-sm-3 col-sm-1 hour"><div class="row time">' + hour + ' ' + items.FCTTIME.ampm + '</div><div class="row weather-img"><img src="' + items.icon_url + '"> </div><div class="row weather-data"><div id="tempHigh">' + items.temp.english + '°F</div><div id="feelsLikeText">Feels like: </div><div id="feelsLikeTemp">' + items.feelslike.english + '°F</div></div></div>';
						hourCount++;
					}
				}

			)
			weatherHtml = weatherHtml + '</div><div class="row radar"><img class="img-responsive radar center-block"  src="lib/weather.php?action=radar"></div>'
			$('#Weather').html(weatherHtml);


			window.dispatchEvent(new Event('resize'));


		});
	}

</script>
<div id="Weather">
</div>
