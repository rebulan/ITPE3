<?php
header("Content-Type:application/json");
require "../php/connect.php";
	$fdate = "";
	$limit = "";
		
	if(!empty($_GET['fdate']))
		$fdate = $_GET['fdate'];
	
		if(!empty($_GET['limit']))
			$limit = $_GET['limit'];

		$str = "Select 
		daily_id as ForecastDailyID,
		forecast_date as ForeCastDate,
		tif_file as TiffFile
		from forecast_daily where isdeleted = 0 ";
		
		if(!empty($fdate))
		{
			if(!empty($limit))
			{
				$datee = date_create($fdate);
				$dtoadd = date_add($datee,date_interval_create_from_date_string("$limit days"));
				$dto = date_format($dtoadd,"Y-m-d");
				
				$str = $str." and (STR_TO_DATE(forecast_daily.forecast_date,'%Y-%m-%d') >= STR_TO_DATE('$limit','%Y-%m-%d') and
				STR_TO_DATE(forecast_daily.forecast_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
			}
			else
			{
				$str = $str." and forecast_daily.forecast_date = '$fdate'";
			}
		}
		//echo $str;
		$result = mysqli_query($con,$str);
		
		$rows = array();
		while($record = mysqli_fetch_assoc($result)) {
			
			//$high = mysqli_fetch_assoc(mysqli_query($con,"Select color as HIGH_TEMP_COLOR_CODE from lup_temperature_legends where temp_legend_id = $record[HIGH_TEMP_ID]"));
			array_push($rows, $record);
			
		}		
		print json_encode($rows);
		
?>