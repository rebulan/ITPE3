<?php
include('php/connect.php');
function get_announcement($name)
{
	global $con;
	$query = mysqli_query($con,"Select * from announcements, lup_locations where announcements.isdeleted = 0
	and lup_locations.location_id = announcements.location_id");
	
	$des[] = "";
	$ctr = 0;
	while($row = mysqli_fetch_assoc($query))
	{
		$des[$ctr] = $row['item_description'];
		$ctr++;
	}
	return $des;
}
?>