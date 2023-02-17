<?php
header("Content-Type:application/json");
define('SERVERNAME', 'localhost');
define('USERNAME','root');
define('PASSWORD','');
define('DATABASE','resto');
$con=mysqli_connect(SERVERNAME,USERNAME,PASSWORD,DATABASE);

		$result = mysqli_query($con,"SELECT * FROM pos_lup_item ");
	

		while($row = mysqli_fetch_assoc($result))
		{
			response($row['item_description'],$row['item_price1']);
		}
		
		mysqli_close($con);
	


function response($item,$price){
	
	$response['ITEM DESCRIPTION'] = $item;
	$response['PRICE'] = $price;
	$json_response = json_encode($response);
	

	echo $json_response;

}
?>