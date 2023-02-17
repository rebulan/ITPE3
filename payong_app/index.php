<?php
include('php/code.php');
indexcheck();
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
<div class="login-box">
  <div class="login-logo">
    <img src="images/logo.png" style="width:120px;">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body" style = "border-radius:10px;">
    <p class="login-box-msg">Sign in to start your session</p>

    <form method="post" id = "loginform">
      <div class="form-group has-feedback">
        <input type="text" id = "username" name = "username" class="form-control" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id = "password" name = "password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
	  <div id = "alert"></div>
      <div class="form-group">
          <button type="submit" id = "login" class="btn btn-primary btn-block btn-flat">Sign In</button>
      </div>
    </form>
	<script>
	
		$("#username").focus();
		$("#login").click(
			function(e)
			{
				e.preventDefault();
				
				var username = $("#username").val();
				var pass = $("#password").val();
				
				if($.trim(username) != '' && $.trim(pass) != '')
				{
							var formData = $('#loginform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#alert").addClass("alert alert-warning");
							$("#alert").html('<div style = "width:100%; height:50px;text-align:center;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> Logging in');
							
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#click").html(data);
							
																			}
																		});
				}
				else
				{
								$("#alert").addClass("alert alert-danger");
																										$("#alert").slideDown();
																										$("#alert").html("<i class='fa fa-exclamation-triangle'></i> Empty User Name or Password");
																										
																										
				}
			}
		);
	</script>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
