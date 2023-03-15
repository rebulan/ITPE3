<?php
header("Content-Type:application/json");
require "../php/connect.php";
		
		$str = "SELECT * from agri_info where isdeleted = 0 and status = 1";
		
		//echo $str;
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			
			$rows['AgriInfoID'] = $record['agri_info_id'];
			$rows['DateFrom'] = $record['date_from'];
			$rows['DateTo'] = $record['date_to'];
			array_push($print,$rows);
		}
		
		print json_encode($print);
		
?>