<?php
include('php/code.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Log in Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php include('php/css.php');?>
  <?php include('php/js.php');?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page skin-green">
<header class="main-header">
    <!-- Logo -->
    <a href="preparation.php" class="logo" >
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo altcompany();?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo company(0);?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-success-static-top">
     

     
    </nav>
  </header>	
  <h1 style = "width:100%; background-color:#006633;margin:auto;margin-top:30px;text-align:center;padding-top:15px;padding-bottom:15px;color:#fff;">ORDER STATUS</H1><br>
	<div class = "row">
		<div class="col-md-6">
			<div class="box" style = "width:95%;margin-top:10px;margin:auto;">
				   <div class="box-header with-border">
						 <h2 class="box-title">PREPARATIONS</h2>
					</div>
				  <div class="box-body" id = "prepui" style = "font-size:16px;">
				  </div>
			  </div>							  							 
		</div>
		<div class="col-md-6">
			<div class="box" style = "width:95%;margin-top:10px;margin:auto;">
					<div class="box-header with-border">
						 <h2 class="box-title">READY TO SERVE</h2>
					</div>
				  <div class="box-body" id = "readyui" style = "font-size:16px;">
				  </div>
			  </div>
		</div>
	</div>
	
 
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
									setInterval(function()
											{
												
													 $.post( 
													 'php/view.php',
													 {
														
														oprepui:1
													},
													 function(data) {
														$('#prepui').html(data);
														
													 });
													  $.post( 
													 'php/view.php',
													 {
														
														oreadyui:1
													},
													 function(data) {
														$('#readyui').html(data);
														
													 });
													 
												

							
											}, 5000)
											
</script>
</body>
</html>
