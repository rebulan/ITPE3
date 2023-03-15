<?php
header("Content-Type:application/json");
require "../php/connect.php";
	if(!empty($_GET['AgriInfoID']))
	{
		$region = "";
		if(!empty($_GET['RegionID']))
		$region = mysqli_real_escape_string($con,$_GET['RegionID']);
	
		$str = "SELECT * from agri_prognosis,lup_regions where agri_prognosis.status = 1 and agri_prognosis.isdeleted = 0
		and  lup_regions.region_id = agri_prognosis.region_id and agri_prognosis.agri_info_id = $_GET[AgriInfoID]"; 
		
		if(!empty($region))
			$str = $str." and agri_prognosis.region_id = $region";
		
		
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			
			$rows['AgriInfoID'] = $record['agri_info_id'];
			$rows['Title'] = $record['title'];
			$rows['Content'] = $record['content'];
			$rows['RainFallMin'] = $record['rainf_min'];
			$rows['RainFallMax'] = $record['rainf_max'];
			$rows['RainyDaysMin'] = $record['raind_min'];
			$rows['RainyDaysMax'] = $record['raind_max'];
			$rows['TempMin'] = $record['temp_min'];
			$rows['TempMax'] = $record['temp_max'];
			$rows['SoilCondition'] = $record['soil_condition'];
			$cquery = mysqli_query($con,"Select lup_provinces.description as Province from agri_prognosis_location,lup_provinces where agri_prognosis_location.prognosis_id = $record[prognosis_id] and agri_prognosis_location.isdeleted = 0
			and lup_provinces.province_id = agri_prognosis_location.province_id");
			$ctr = 1;
			$coor = array();

			while($crow = mysqli_fetch_assoc($cquery))
			{
				array_push($coor,$crow);
			}
			$rows['SoilConditionLocation'] = $coor;
			array_push($print,$rows);
		}
		
		print json_encode($print);	
		
	}


		
?>