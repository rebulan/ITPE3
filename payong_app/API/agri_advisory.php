<?php
header("Content-Type:application/json");
require "../php/connect.php";
	
	if(!empty($_GET['AgriInfoID']))
	{
			$id = $_GET['AgriInfoID'];
			$str = "SELECT * from agri_advisory where agri_info_id = $id and status = 1 and isdeleted = 0"; 
	
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			
			$rows['AgriInfoID'] = $record['agri_info_id'];
			$rows['Title'] = $record['title'];
			$rows['Content'] = $record['description'];
			array_push($print,$rows);
		}
		print json_encode($print);
		
	}

		

	
		
?>