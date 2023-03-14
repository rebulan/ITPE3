<?php
header("Content-Type:application/json");
require "../php/connect.php";
		$daily_id = "";
		$location = "";
		$limit = "";
	
	if(!empty($_GET['location']))
		$location = mysqli_real_escape_string($con,$_GET['location']);
	
	if(!empty($_GET['fdate']))
		$fdate = $_GET['fdate'];
	
	if(!empty($_GET['limit']))
			$limit = $_GET['limit'];
	
	if(!empty($_GET['DailyDetailsID']))
			$daily_id = $_GET['DailyDetailsID'];


		$str = "SELECT 
		daily_details.daily_details_id as DailyDetailsID,
		daily_details.forecast_date as ForecastDate,
		lup_locations.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription,
		daily_details.daily_forecast_rainfall as RainFall,
		daily_details.daily_forecast_rainfall_hex as RainFallColorCode,
		daily_details.daily_forecast_rainfall_percentage as RainFallPercentage,
		daily_details.daily_forecast_rain_percent_hex as RainFallPercentageColorCode,
		daily_details.rainfall_description as RainFallDescription,
		daily_details.cloudcover as CloudCover,
		daily_details.humidity as Humidity,
		daily_details.windspeed as WindSpeed,
		daily_details.winddirection as WindDirection,
		daily_details.daily_forecast_low_temp as LowTemp,
		daily_details.daily_forecast_lowtemp_hex as LowTempColorCode,
		daily_details.daily_forecast_high_temp as HighTemp,
		daily_details.daily_forecast_hightemp_hex as HighTempColorCode,
		daily_details.daily_forecast_mean_temp as MeanTemp
		FROM daily_details, lup_locations, lup_provinces WHERE daily_details.isdeleted = 0
		and daily_details.location_id = lup_locations.location_id
		and lup_locations.province_id = lup_provinces.province_id"; 
		
		if(!empty($location))
			$str = $str." and CONCAT(lup_locations.location_description,',',lup_provinces.description) = '$location'";
		
		if(!empty($daily_id))
			$str = $str." and daily_details.daily_details_id = $daily_id";
		
		if(!empty($fdate))
		{
			if(!empty($limit))
			{
			
				$datee = date_create($fdate);
				$dtoadd = date_add($datee,date_interval_create_from_date_string("$limit days"));
				$dto = date_format($dtoadd,"Y-m-d");
				
				$str = $str." and (STR_TO_DATE(daily_details.forecast_date,'%Y-%m-%d') >= STR_TO_DATE('$fdate','%Y-%m-%d') and
				STR_TO_DATE(daily_details.forecast_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
			
			}
			else
			{
				$str = $str." and daily_details.forecast_date = '$fdate'";
			}
		}
	
		
	
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			
			$rows['DailyDetailsID'] = $record['DailyDetailsID'];
			$rows['ForecastDate'] = $record['ForecastDate'];
			$rows['LocationDescription'] = $record['LocationDescription'];
			$rows['RainFall'] = $record['RainFall'];
			$rows['RainFallColorCode'] = $record['RainFallColorCode'];
			$rows['RainFallPercentage'] = $record['RainFallPercentage'];
			$rows['RainFallPercentageColorCode'] = $record['RainFallPercentageColorCode'];
			$rows['RainFallDescription'] = $record['RainFallDescription'];
			$rows['CloudCover'] = $record['CloudCover'];
			$rows['Humidity'] = $record['Humidity'];
			$rows['WindSpeed'] = $record['WindSpeed'];
			$rows['WindDirection'] = $record['WindDirection'];
			$rows['LowTemp'] = $record['LowTemp'];
			$rows['LowTempColorCode'] = $record['LowTempColorCode'];
			$rows['HighTemp'] = $record['HighTemp'];
			$rows['HighTempColorCode'] = $record['HighTempColorCode'];
			$rows['MeanTemp'] = $record['MeanTemp'];
			$cquery = mysqli_query($con,"Select coordinate from lup_coordinates where location_id = $record[location_id] and isdeleted = 0");
			$ctr = 1;
			$coor = array();

			while($crow = mysqli_fetch_assoc($cquery))
			{
				array_push($coor,$crow);
			}
			$rows['coordinates'] = $coor;
			array_push($print,$rows);
		}
		
		print json_encode($print);

	
		
?>