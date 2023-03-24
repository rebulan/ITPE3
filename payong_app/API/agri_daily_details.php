<?php
header("Content-Type:application/json");
require "../php/connect.php";
	if(!empty($_GET['AgriDailyID']))
	{
		$str = "SELECT * from agri_daily_humidity where agri_daily_id = $_GET[AgriDailyID]
		and isdeleted = 0 and status = 1"; 
	
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			
			$rows['AgriDailyID'] = $record['agri_daily_id'];
			$rows['MinHumidity'] = $record['humidity_min'];
			$rows['MaxHumidity'] = $record['humidity_max'];
			
			$reg = explode(",",$record['provinces']);
			$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($record[provinces])");
			$ctr = 1;
			$coor = array();

			while($crow = mysqli_fetch_assoc($cquery))
			{
				array_push($coor,$crow);
			}
			$rows['HumidityLocation'] = $coor;
			//array_push($print,$rows);
		}
		
		$str = "SELECT * from agri_daily_leaf where agri_daily_id = $_GET[AgriDailyID]
		and isdeleted = 0 and status = 1"; 
		

		$result = mysqli_query($con,$str);
		//$rows = array();
		
		while($record = mysqli_fetch_assoc($result)) {
			$rows['MinLeafWetness'] = $record['leaf_min'];
			$rows['MaxLeafWetness'] = $record['leaf_max'];
			
			$reg = explode(",",$record['provinces']);
			$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($record[provinces])");
			$ctr = 1;
			$coor2 = array();

			while($crow = mysqli_fetch_assoc($cquery))
			{
				array_push($coor2,$crow);
			}
			$rows['LeafWetnessLocation'] = $coor2;
			
		}
		
		$str = "SELECT * from agri_daily_soil_condition where agri_daily_id = $_GET[AgriDailyID]
		and isdeleted = 0 and status = 1"; 
		

		$result = mysqli_query($con,$str);
		//$rows = array();
		
		while($record = mysqli_fetch_assoc($result)) {
			$rows['SoilCondition'] = $record['soil_condition'];
			
			$reg = explode(",",$record['provinces']);
			$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($record[provinces])");
			$ctr = 1;
			$coor3 = array();

			while($crow = mysqli_fetch_assoc($cquery))
			{
				array_push($coor3,$crow);
			}
			$rows['SoilConditionLocation'] = $coor3;
			
		}
		
		$str = "SELECT * from agri_daily_temp where agri_daily_id = $_GET[AgriDailyID]
		and isdeleted = 0 and status = 1"; 
		

		$result = mysqli_query($con,$str);
		//$rows = array();
		
		while($record = mysqli_fetch_assoc($result)) {
			$rows['LowLandcMinTemp'] = $record['lowland_temp_min'];
			$rows['LowLandcMaxTemp'] = $record['lowland_temp_max'];
			$rows['HighLandMinTemp'] = $record['highland_temp_min'];
			$rows['HighLandMaxTemp'] = $record['highland_temp_max'];
			$reg = explode(",",$record['provinces']);
			$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($record[provinces])");
			$ctr = 1;
			$coor4 = array();
			while($crow = mysqli_fetch_assoc($cquery))
			{
				array_push($coor4,$crow);
			}
			$rows['TempLocation'] = $coor4;
		
		}
		
		$str = "SELECT * from agri_daily_wind where agri_daily_id = $_GET[AgriDailyID]
		and isdeleted = 0 and status = 1"; 
		
		$result = mysqli_query($con,$str);
		//$rows = array();
		
		while($record = mysqli_fetch_assoc($result)) {
			$rows['WindCondition'] = $record['wind_condition'];
			$reg = explode(",",$record['regions']);
			$cquery = mysqli_query($con,"Select description as Regions from lup_regions where region_id IN($record[regions])");
			$ctr = 1;
			$coor6 = array();
			while($crow = mysqli_fetch_assoc($cquery))
			{
				array_push($coor6,$crow);
			}
			$rows['WindContidionLocation'] = $coor6;
			array_push($print,$rows);
		}
		print json_encode($print);	
	}


		
?>