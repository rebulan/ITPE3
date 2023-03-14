<?php
header("Content-Type:application/json");
require "../php/connect.php";
		
		$location = "";

		if(!empty($_GET['location']))
		$location = mysqli_real_escape_string($con,$_GET['location']);
	
		$str = "SELECT 
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription,
		lup_locations.location_id
		from lup_locations,lup_provinces where 
		lup_locations.province_id = lup_provinces.province_id";
		
		if(!empty($location))
			$str = $str." and CONCAT(lup_locations.location_description,',',lup_provinces.description) = '$location'";
		
		//echo $str;
		
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		while($record = mysqli_fetch_assoc($result)) {
			
			
			$rows['LocationID'] = $record['location_id'];
			$rows['LocationDescription'] = $record['LocationDescription'];
			
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