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


		$str = "SELECT 
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription,
		forecast_agri.forecast_agri_date as AgriDate,
		forecast_agri.location_id,
		lup_weather_system.description as Weather_Description,
		lup_weather_system.icon as Weather_Icon,
		forecast_agri.wind as Wind,
		forecast_agri.humidity_from as HumidityLow,
		forecast_agri.humidity_to as HumidityHigh,
		forecast_agri.templow as LowTemp,
		forecast_agri.temphigh as HighTemp,
		forecast_agri.rainfall_from as RainFallFrom,
		forecast_agri.rainfall_to as RainFallTo
		from forecast_agri,lup_locations,lup_weather_system,lup_provinces where forecast_agri.isdeleted = 0
		and forecast_agri.location_id = lup_locations.location_id
		and forecast_agri.weather_id = lup_weather_system.weather_system_id
		and lup_locations.province_id = lup_provinces.province_id";
		
		if(!empty($location))
			$str = $str." and CONCAT(lup_locations.location_description,',',lup_provinces.description) = '$location'";
		
		if(!empty($fdate))
		{
			if(!empty($limit))
			{
			
				$datee = date_create($fdate);
				$dtoadd = date_add($datee,date_interval_create_from_date_string("$limit days"));
				$dto = date_format($dtoadd,"Y-m-d");
				
				$str = $str." and (STR_TO_DATE(forecast_agri.forecast_agri_date,'%Y-%m-%d') >= STR_TO_DATE('$limit','%Y-%m-%d') and
				STR_TO_DATE(forecast_agri.forecast_agri_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
			
			}
			else
			{
				$str = $str." and forecast_agri.forecast_agri_date = '$fdate'";
			}
		}
		//echo $str;
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			
			$rows['LocationDescription'] = $record['LocationDescription'];
			$rows['AgriDate'] = $record['AgriDate'];
			$rows['Weather_Description'] = $record['Weather_Description'];
			$rows['Weather_Icon'] = $record['Weather_Icon'];
			$rows['Wind'] = $record['Wind'];
			$rows['HumidityLow'] = $record['HumidityLow'];
			$rows['LowTemp'] = $record['LowTemp'];
			$rows['HighTemp'] = $record['HighTemp'];
			$rows['RainFallFrom'] = $record['RainFallFrom'];
			$rows['RainFallTo'] = $record['RainFallTo'];
			
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