<?php
include('php/code.php');
homeresetcheck();
homecheck();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <?php include('php/css.php');?>
  <?php include('php/js.php');?>
</head>
<body class="hold-transition skin-green-light sidebar-mini">
<div class="wrapper">

  <header class="main-header" style = "background-color:#001ef2;">
    <!-- Logo -->
    <a href="home.php" class="logo" >
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo altcompany();?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo company(0);?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-success-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
     
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <?php user();?>
            </a>
            <ul class="dropdown-menu">
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat" id = "prof">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-default btn-flat" id = "logout">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
 
  <!-- Left side column. contains the logo and sidebar -->
 <aside class="main-sidebar" style = "background-color:<?php echo $sidebar;?>;">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" >
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php mainmenu(0);?>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   
    <section class="content" id = "maincontent">
		
    </section>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <?php footer(0);?>
  </footer>      
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
	
<script>
	
	$("#document").ready(
		function()
		{	
					$.post( 
								'php/main.php',
								{
									resetcheck:1
								},
								function(data) {
									$('#click').html(data);	
								});
		}
	);
	$("#logout").click(
			function(e)
			{
				e.preventDefault();
				var r = confirm("Confirm Logout");
				
				if(r == true)
				{
				
						$.post( 
								'php/main.php',
								{
									logout:1,
									ispages:0
								},
								function(data) {
									$('#click').html(data);	
								});
				}
			}
		);
		
</script>
</body>
</html>
