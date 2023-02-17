<?php
header("Content-Type:application/json");
require "../php/connect.php";
	
		$result = mysqli_query($con,"SELECT 
		lup_locations.location_description as LOCATION_DESCRIPTION,
		lup_locations.coordinates as LOCATION_COORDINATES,
		forecast_daily.daily_forecast_rainfall as RAINFALL,
		lup_rainfall_legends.color as RAINFALL_COLOR_CODE,
		forecast_daily.daily_forecast_rainfall_percentage as RAINFALL_PERCENTAGE,
		lup_rainpercentage_legends.color as RAINFALL_PERCENTAGE_COLOR_CODE,
		forecast_daily.daily_forecast_low_temp as LOW_TEMP,
		lup_temperature_legends.color as LOW_TEMP_COLOR_CODE,
		forecast_daily.daily_forecast_high_temp as HIGH_TEMP,
		forecast_daily.daily_forecast_hightemp_id as HIGH_TEMP_ID
		FROM forecast_daily, lup_rainfall_legends, lup_locations, lup_rainpercentage_legends,lup_temperature_legends WHERE forecast_daily.isdeleted = 0
		and forecast_daily.daily_forecast_location_id = lup_locations.location_id 
		and forecast_daily.daily_forecast_rainfall_id = lup_rainfall_legends.rainfall_legend_id
		and forecast_daily.daily_forecast_rain_percent_id = lup_rainpercentage_legends.rain_percentage_legend_id
		and forecast_daily.daily_forecast_lowtemp_id = lup_temperature_legends.temp_legend_id
		and forecast_daily.daily_forecast_hightemp_id = lup_temperature_legends.temp_legend_id");
		
		$rows = array();
		while($record = mysqli_fetch_assoc($result)) {
			array_push($rows, $record);
			$high = mysqli_fetch_assoc(mysqli_query($con,"Select color as HIGH_TEMP_COLOR_CODE from lup_temperature_legends where temp_legend_id = $record[HIGH_TEMP_ID]"));
			array_push($rows, $high);
		}		
		print json_encode($rows);
		
?>