<?php
define('SERVERNAME', 'localhost');
define('USERNAME','root');
define('PASSWORD','');
define('DATABASE','itpe2db');
$con=mysqli_connect(SERVERNAME,USERNAME,PASSWORD,DATABASE);
if(!$con){
	die('ERROR : ' . mysqli_connect_error($con));
}
session_start();
$_SESSION['base_url'] = 'php'; 
date_default_timezone_set('Asia/Manila');
?>