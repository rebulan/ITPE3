<?php
header("Content-Type:application/json");
require "../php/connect.php";
		
		$str = "SELECT * from agri_daily where isdeleted = 0 and status = 1";
		
		//echo $str;
		$result = mysqli_query($con,$str);
		$rows = array();
		$print = array();
		
		
		while($record = mysqli_fetch_assoc($result)) {
			
			$rows['AgriDailyID'] = $record['agri_daily_id'];
			$rows['DateIssue'] = $record['date_issue'];
			$rows['ValidityDate'] = $record['validity_date'];
			$rows['Title'] = $record['title'];
			$rows['Content'] = $record['content'];
			array_push($print,$rows);
		}
		print json_encode($print);
		
?>