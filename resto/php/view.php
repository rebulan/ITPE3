<?php
include('connect.php');
include("general.php");

	if(!empty($_REQUEST['trefreshprep']))
	{
		prep(1,0);
	}
	if(!empty($_REQUEST['oprepui']))
	{
		orderstatus(1,1);
	}
	if(!empty($_REQUEST['oreadyui']))
	{
		orderstatus(1,2);
	}
?>