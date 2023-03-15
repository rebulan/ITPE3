<?php
header("Content-Type:application/json");
require "../php/connect.php";
		
		$region = "";

		if(!empty($_GET['RegionID']))
		$region = mysqli_real_escape_string($con,$_GET['RegionID']);
	
		$str = "SELECT * from lup_regions where isdeleted = 0";
		
		if(!empty($region))
			$str = $str." and region_id= '$region'";
		
		//echo $str;
		
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		while($record = mysqli_fetch_assoc($result)) {
			
			
			$rows['RegionID'] = $record['region_id'];
			$rows['RegionDescription'] = $record['description'];
			array_push($print,$rows);
		}
		
		print json_encode($print);
		
?>