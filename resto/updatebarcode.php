<?php

define('SERVERNAME', 'localhost');
define('USERNAME','root');
define('PASSWORD','');
define('DATABASE','posdb');
$con=mysqli_connect(SERVERNAME,USERNAME,PASSWORD,DATABASE);
if(!$con){
	die('ERROR : ' . mysqli_connect_error($con));
}

					$query = mysqli_query($con, "Select * from pos_lup_item where isdeleted = 0");
					
					while($row = mysqli_fetch_assoc($query))
					{
					
						$total_enrolled = $row['item_id'];
					
					
					$count = "";
					if($total_enrolled < 10)
					{
						$count = "00000";
					}
					else if($total_enrolled >= 10 && $total_enrolled < 100)
					{
						$count = "0000";
					}
					else if($total_enrolled >= 100 && $total_enrolled < 1000)
					{
						$count = "000";
					}
					else if($total_enrolled >= 1000 && $total_enrolled < 10000)
					{
						$count = "00";
					}
					else if($total_enrolled >= 10000 && $total_enrolled < 100000)
					{
						$count = "0";
					}
					else if($total_enrolled >= 100000 && $total_enrolled < 1000000)
					{
						$count = "";
					}
					
				$tranno = $count.$row['item_id'];
				
				mysqli_query($con, "Update pos_lup_item set item_code = '$tranno' where item_id = $row[item_id]");
					}
			
					
				
?>