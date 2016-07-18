<script type="text/javascript" src="../conf/conf.js"></script>
<script>
	$(document).ready(function() {
		fillWeather()
		setInterval(fillWeather, minutesToMilli(15))
	});

	function minutesToMilli(minutes) {
		return minutes * 60 * 1000
	}

	function fillWeather() {
		var dayData, hourlyData, currentData
		$.when(
			$.getJSON("http://api.wunderground.com/api/" + weatherSettings.apiKey + "/forecast10day/q/" + weatherSettings.location + ".json", function() {})
			.done(function(data) {
				dayData = data

			}),
			$.getJSON("http://api.wunderground.com/api/" + weatherSettings.apiKey + "/hourly/q/" + weatherSettings.location + ".json", function() {})
			.done(function(data) {
				hourlyData = data

			}),
			$.getJSON("http://api.wunderground.com/api/" + weatherSettings.apiKey + "/conditions/q/" + weatherSettings.location + ".json", function() {})
			.done(function(data) {
				currentData = data

			})

		).then(function() {
			var weatherHtml = '<div id="days" class="row"><div class="current_conditions col-xs-6 col-md-2"><div class="row"><h3>' + currentData.current_observation.temp_f + '°F</h3><img class="img-responsive" src="' + currentData.current_observation.icon_url + '"></div><div class="row"><div id="condition">' + currentData.current_observation.weather + '</div><div id="humidity">' + currentData.current_observation.relative_humidity + ' relative humidity</div></div></div>';

			$.each(dayData.forecast.simpleforecast.forecastday, function(i, items) {
				weatherHtml = weatherHtml + '<div class="col-xs-6 col-sm-3 col-md-1 day"><div class="row Date">' + items.date.weekday_short + ' ' + items.date.month + '/' + items.date.day + '</div><div class="row weather-img"><img src="' + items.icon_url + '"> </div><div class="row weather-data"><div id="tempHigh">' + items.high.fahrenheit + '°F</div><div id="tempLow">' + items.low.fahrenheit + '°F</div></div></div>';



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
						weatherHtml = weatherHtml + '<div class="col-xs-6 col-sm-3 col-md-1 hour"><div class="row time">' + hour + ' ' + items.FCTTIME.ampm + '</div><div class="row weather-img"><img src="' + items.icon_url + '"> </div><div class="row weather-data"><h5>' + items.temp.english + '°F</h5><div id="feelsLikeText">Feels like: ' + items.feelslike.english + '°F</div></div></div>';
						hourCount++;
					}
				}

			)
			weatherHtml = weatherHtml + '<div class="row radar"><img class="img-responsive" src="http://api.wunderground.com/api/' + weatherSettings.apiKey + '/animatedradar/animatedsatellite/q/' + weatherSettings.location + '.gif?rad.rainsnow=1&sat.smooth=1&rad.smooth=1&noclutter=1&sat.radius=250&sat.timelabel=1&sat.timelabel.y=290&sat.timelabel.x=800&num=8&delay=50&interval=30&sat.width=1200&height=600&sat.key=sat_ir4&sat.borders=1"></div>'
			$('#Weather').html(weatherHtml);
		});
	}

</script>
<div id="Weather">
</div>
