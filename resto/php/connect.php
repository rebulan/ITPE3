<?php
define('SERVERNAME', 'localhost');
define('USERNAME','root');
define('PASSWORD','paradigm101');
define('DATABASE','payong');
$con=mysqli_connect(SERVERNAME,USERNAME,PASSWORD,DATABASE);
if(!$con){
	die('ERROR : ' . mysqli_connect_error($con));
}
$enval = "casey_potpot";
session_start();
date_default_timezone_set('Asia/Manila');
?>