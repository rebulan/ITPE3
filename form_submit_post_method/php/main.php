<?php
	
	if(isset($_POST['fname']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		}
		
		echo "HELLO ".$fname." ".$lname;
	}		
?>