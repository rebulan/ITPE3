<?php
header("Content-Type:application/json");
require "../php/connect.php";
	
		$result = mysqli_query($con,"SELECT * from lup_rainfall_legends where isdeleted = 0");
		
		$rows = array();
		while($record = mysqli_fetch_assoc($result)) {
			array_push($rows, $record);
			
		}		
		print json_encode($rows);
		
?>