<script type="text/javascript" src="../conf/conf.js"></script>
<script>
	$(document).ready(function() {
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
			var weatherHtml = '<div id="days" class="row"><div class="current_conditions col-xs-12 col-md-2"><div class="row"><h3>' + currentData.current_observation.temp_f + '°F</h3><img src="' + currentData.current_observation.image.url + '"></div><div class="row"><div id="condition">' + currentData.current_observation.weather + '</div><div id="humidity">' + currentData.current_observation.relative_humidity + ' relative humidity</div></div></div>';

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
			$('#Weather').html(weatherHtml);
		});
	})

</script>
<div id="Weather">
</div>
