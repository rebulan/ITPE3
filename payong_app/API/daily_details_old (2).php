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
			$id = $_GET['DailyDetailsID'];

		$str = "SELECT 
		forecast_daily_details.daily_details_id as DailyDetailsID,
		forecast_daily_details.forecast_date as ForecastDate,
		forecast_daily_details.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription,
		forecast_daily_details.daily_forecast_rainfall as RainFall,
		lup_rainfall_legends.color as RainFallColorCode,
		forecast_daily_details.daily_forecast_rainfall_percentage as RainFallPercentage,
		lup_rainpercentage_legends.color as RainFallPercentageColorCode,
		forecast_daily_details.daily_forecast_low_temp as LowTemp,
		forecast_daily_details.daily_forecast_lowtemp_hex as LowTempColorCode,
		forecast_daily_details.daily_forecast_high_temp as HighTemp,
		forecast_daily_details.daily_forecast_hightemp_hex as HighTempColorCode
		FROM forecast_daily_details, lup_rainfall_legends, lup_locations, lup_rainpercentage_legends, lup_provinces WHERE forecast_daily_details.isdeleted = 0
		and forecast_daily_details.location_id = lup_locations.location_id 
		and forecast_daily_details.daily_forecast_rainfall_id = lup_rainfall_legends.rainfall_legend_id
		and forecast_daily_details.daily_forecast_rain_percent_id = lup_rainpercentage_legends.rain_percentage_legend_id
		and lup_locations.province_id = lup_provinces.province_id";
		
		if(!empty($id))
			$str = $str." and forecast_daily_details.daily_details_id = '$id'";
		
		if(!empty($location))
			$str = $str." and CONCAT(lup_locations.location_description,',',lup_provinces.description) = '$location'";
		
		if(!empty($fdate))
		{
			if(!empty($limit))
			{
			
				$datee = date_create($fdate);
				$dtoadd = date_add($datee,date_interval_create_from_date_string("$limit days"));
				$dto = date_format($dtoadd,"Y-m-d");
				
				$str = $str." and (STR_TO_DATE(forecast_daily_details.forecast_date,'%Y-%m-%d') >= STR_TO_DATE('$fdate','%Y-%m-%d') and
				STR_TO_DATE(forecast_daily_details.forecast_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
			
			}
			else
			{
				$str = $str." and forecast_daily_details.forecast_date = '$fdate'";
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
			$rows['LowTemp'] = $record['LowTemp'];
			$rows['LowTempColorCode'] = $record['LowTempColorCode'];
			$rows['HighTemp'] = $record['HighTemp'];
			$rows['HighTempColorCode'] = $record['HighTempColorCode'];
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