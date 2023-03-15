<?php
header("Content-Type:application/json");
require "../php/connect.php";
	
			$str = "SELECT * from agri_daily_advisory where status = 1 and isdeleted = 0"; 
	
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			$rows['Title'] = $record['title'];
			$rows['Content'] = $record['description'];
			array_push($print,$rows);
		}
		print json_encode($print);
		
	

		

	
		
?>