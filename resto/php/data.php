<?php
define('SERVERNAME', 'localhost');
define('USERNAME','root');
define('PASSWORD','');
define('DATABASE','resto');
$con=mysqli_connect(SERVERNAME,USERNAME,PASSWORD,DATABASE);
if(!$con){
	die('ERROR : ' . mysqli_connect_error($con));
}

function get_price($name)
{
	global $con;
	
	$query = mysqli_query($con, Select * from pos_lup_item where item_description like '%$name%');
	
	while($row = mysqli_fetch_assoc($query))
	{

			return $row['item_description'];
	}
}
?>