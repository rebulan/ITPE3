<?php
header("Content-Type:application/json");
require "../php/connect.php";
		
		$str = "SELECT 
		agri_info_id as AgriInfoID,
		date_from as DateFrom,
		date_to as DateTo
		from agri_info where isdeleted = 0 and status = 1";
		
		//echo $str;
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			
			$rows['AgriInfoID'] = $record['AgriInfoID'];
			$rows['DateFrom'] = $record['DateFrom'];
			$rows['DateTo'] = $record['DateTo'];
			array_push($print,$rows);
		}
		
		print json_encode($print);
		
?>