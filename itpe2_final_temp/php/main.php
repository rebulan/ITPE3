<?php
include('connect.php');
//$_SESSION['login'] = "";

?>
<script>
	function notify(msg,con)
	{
		$(con).addClass("alert alert-danger");
			$(con).slideDown();
			$(con).html(msg);
																												
			window.setTimeout(function() {																								
				$(con).hide();
			}, 5000);
	}
	var loading = '<div><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>';
</script>
<?php
function menus()
{
		global $con;
		$query = mysqli_query($con,"Select * from menu where isactive = 1 order by module_name");
		
	?>
		<table class = "table table-bordered table-hover table-sm" id = "menutable">
								<thead>
									<th>#</th>
									<th>DESCRIPTION</th>
									<th>KEY WORD</th>
									<th>CODE LOCATION</th>
									<th>ACTION</TH>
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['module_name'];?></td>
						<td><?php echo $row['keyword'];?></td>
						<td><?php echo $row['location'];?></td>
						<td>
						<?php
							if($row['readonly'] == 0)
							{
						?>
							<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
							<button class = "btn btn-warning btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>
						<?php
							}
						?>
						</td>
					</tr>
					<script>					
										
					</script>
					
				<?php
				$ctr++;
			}
			?>
		</table>
		
		<script>
			$("#document").ready(
				function()
				{
						
					$('#menutable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : false
					});												
				}
			);
		</script>
	<?php
	
	


}
function users()
{
		global $con;
		$query = mysqli_query($con,"Select * from se_user order by agent_number");
		
	?>
		<table class = "table table-striped table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>AGENT NUMBER</th>
									<th>FULL NAME</th>
									<th>ACTION</TH>
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['agent_number'];?></td>
						<td><?php echo $row['fullname'];?></td>
						<td>
							<button class = "btn btn-primary btn-flat btn-xs" id = "reset<?php echo $ctr;?>">RESET</button>	
							<button class = "btn btn-warning btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>
							<?php
							if($row['isadmin'] == 0)
							{
							?>
								<button class = "btn btn-danger btn-flat btn-xs" id = "block<?php echo $ctr;?>">BLOCK</button>
							<?php
							}
							?>
						</td>
					</tr>
					<script>					
						$("#edit<?php echo $ctr;?>").click(
														function(e)
														{
															e.preventDefault();
														
															
															$("#modal").modal("show");
															$("#modalbody").css("min-width","60%");
															
															$.post( 
																'php/main.php',
																{
																	edit_userid:'<?php echo $row['user_id'];?>'
																	
																},
																function(data) {
																	$('#modalui').html(data);															
																});
														}
													);
						$("#reset<?php echo $ctr;?>").click(
														function(e)
														{
															e.preventDefault();
														
															
															var r = confirm("Confirm Reset");
															
															if(r == true)
															{
															$.post( 
																'php/main.php',
																{
																	user_reset:'<?php echo $row['user_id'];?>'
																},
																function(data) {
																	$('#click').html(data);		
																});
															}
														}
													);
						
						$("#block<?php echo $ctr;?>").click(
														function(e)
														{
															e.preventDefault();
														
															
															var r = confirm("Confirm Reset");
															
															if(r == true)
															{
															$.post( 
																'php/main.php',
																{
																	user_block:'<?php echo $row['user_id'];?>'
																},
																function(data) {
																	$('#click').html(data);		
																});
															}
														}
													);
													
					</script>
					
				<?php
				$ctr++;
			}
			?>
		</table>
		
		<script>
			$("#document").ready(
				function()
				{
						
					$('#pmtable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : false
					});												
				}
			);
		</script>
	<?php
	
	


}

if(isset($_REQUEST['sessioncheck']))
{
	
	if(!empty($_SESSION['login']))
	{
		?>
			<script>
				$('#useraccountui').html(loading);	
				$('#menuui').html(loading);	
				$.post( 
					'php/main.php',
					{
						useraccountdis:1
					},
					function(data) {
						$('#useraccountui').html(data);	
					});
				
				$.post( 
					'php/main.php',
					{
						menuui:1
					},
					function(data) {
						$('#menuui').html(data);	
					});
					
			</script>
		<?php
	}
	else
	{
	?>
		<div class="login-box">
			<div class="login-box-body">
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
				  <div class="row">
					<div class="col-xs-8">
					  <button type="submit" id = "login" class="btn btn-success btn-block btn-flat">Sign In</button>
					</div>
				  </div>
				</form>
			</div>
		</div>
		<script>
			$("#login").click(
				function(e)
				{
					e.preventDefault();
					
					var username = $("#username").val();
					var pass = $("#password").val();
					
					if($.trim(username) != '' && $.trim(pass) != '')
					{
								var formData = $('#loginform').serializeArray();
																			$.ajax({
																				url :  "php/main.php",
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#click").html(data);
																					alert('Submit Successfull');
																				}
																			});
					}
					else
					{
						alert("</i> Empty User Name or Password");																					
					}
				}
			);
		</script>
	<?php
	}
}
if(isset($_POST['username']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		} 	
			global $con;
			$usercount = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_user where user_username = '$username'"));
			if(!empty($usercount))
			{

				$_SESSION['login'] = $usercount['user_id'];
				?>
					<script>
						location.reload();
					</script>
				<?php	
			}
			else
			{
				?>
				<script>
						alert("<Invalid User Name or Password");																																				
				</script>
				<?php
			}
	
}

if(isset($_REQUEST['useraccountdis']))
{
	$user = $_SESSION['login'];
	global $con;
	$string = "select * from se_user where user_id =$user";
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	$fullname = $row['fullname'];
	?>
				<i class="fa fa-user"></i> 
				<span class="hidden-xs"><?php echo $fullname;?></span>

			  <script>
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
									logout:1
								},
								function(data) {
									$('#click').html(data);	
								});
						}
					}
				);
			  </script>
	<?php
}
if(isset($_REQUEST['logout']))
{
		$_SESSION['login'] = '';
		
		?>
			<script>
				location.reload();
			</script>
		<?php
	
}
if(isset($_REQUEST['menuui']))
{	
	global $con;
	
	$mod = 1;		
	if($mod != 0)
	{
		$query = mysqli_query($con, "Select * from menu where active = 1");
	}
	else
	{
		$query = mysqli_query($con, "Select DISTINCT(se_module.module_id),icon, module_name from se_module,se_user_access_module where se_module.active = 1
		and se_module.module_id = se_user_access_module.module_id and se_user_access_module.isdeleted = 0
		and se_user_access_module.user_id = $user[user_id]");
	}	
	?>
		<ul class="sidebar-menu" data-widget="tree">
        <li class="header" STYLE = "font-size:11px;text-align:center;font-weight:bold;">IT PE 4 FINAL EXAM</li>
	   <?php
	    $ctr = 1;
	   while($row = mysqli_fetch_assoc($query))
	   {
	   ?>
        <li class="treeview">
          <a href="#" id = "sss<?php echo $ctr;?>">
            <?php echo $row['icon'];?> <span><?php echo $row['module_name'];?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right"></i>
            </span>
          </a>
			<script>
				$("#sss<?php echo $ctr;?>").click(
					function(e)
					{
						e.preventDefault();
						
						$('#maincontent').html(loading);
						
						$.post( 
								"<?php echo $_SESSION['base_url'];?>/<?php echo $row['location'];?>",
								{
									<?php echo $row['keyword'];?>:1
								},
								function(data) {
									$('#maincontent').html(data);	
									
								});
								
					}
				);
			</script>
			
        </li>
	<?php
			$ctr++;
	   }
	   ?>
      </ul>
<?php
}
if(isset($_REQUEST['userui']))
{
	?>
		<div class="box-header with-border">
				<h3 class="box-title">USER MANAGER</h3>
		</div>
		<div class="box">
			<div class="box-body">
				<form id = "newuserform">
					<div class="row">
										<div class="col-md-3">
										  
											<label for="lname">AGENT ID NUMBER:</label>
											<input type = "hidden" name = "ulevel" value = "<?php echo $level;?>">
											<input type="text" name="agent_id" class="form-control" data-validation="required"
													data-validation-error-msg="Enter AGENT ID NUMBER">
											
										 
										</div>
										<div class="col-md-3">
											<label for="lname">FULLNAME:</label>
											<input type="text" name="fullname" class="form-control"data-validation="required"
													data-validation-error-msg="Enter Fullname">
											
										 
										</div>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SAVE</button>
										  </div>
										</div>
									
							</div>
				</form>
				<div id = "useralert"></div>
			</div>
		</div>
		<script>
										$.validate({
														form:'#newuserform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newuserform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#userlistui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
		<div class="box">
			<div class="box-body" id = "userlistui">
				<?php users();?>
			</div>
		</div>
	<?php
}
if(isset($_POST['agent_id']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from se_user where agent_number = '$agent_id'"));
	$pass = md5(sha1($agent_id));
	if($check == 0)
	{
		$save = mysqli_query($con,"insert into se_user set agent_number = '$agent_id',
		user_username = '$agent_id', user_password = '$pass', fullname = '$fullname',user_reset = 1");
		if($save)
		{
					?>
						<script>
							notify("New User Profile Created","#useralert");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					notify("Error Creating Profile, Please Contact the system administrator","#useralert");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				notify("Agent Number Already Exists","#useralert");
			</script>
		<?php
	}
	users($ulevel);
}
if(isset($_REQUEST['edit_userid']))
{
	$id = $_REQUEST['edit_userid'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $id"));
	?>
		<div class="box">
			
			<div class="box-body">
				<form id = "edituserform">
					<div class="row">
										<div class="col-md-5">
										  
											<label for="lname">AGENT ID NUMBER:</label>
											<input type = "hidden" name = "usereditid" value = "<?php echo $id;?>">
											<input type="text" name="agent_id_edit" class="form-control" data-validation="required"
													data-validation-error-msg="Enter AGENT ID NUMBER" value = "<?php echo $row['agent_number'];?>">
										</div>
										<div class="col-md-5">
										  
											<label for="lname">FULLNAME::</label>
											<input type="text" name="fullname_edit" class="form-control"data-validation="required"
													data-validation-error-msg="Enter Fullname" value = "<?php echo $row['fullname'];?>">
										</div>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SAVE</button>
										
										  </div>
										</div>
									
							</div>
				</form>
				<div id = "usereditalert"></div>
			</div>
		</div>
		<script>
										$.validate({
														form:'#edituserform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#edituserform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  '<?php echo $_SESSION['base_url'];?>/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#userlistui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
<?php
}
if(isset($_POST['usereditid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from se_user where agent_number = '$agent_id_edit'
	and user_id != $usereditid"));
	if($check == 0)
	{
		$save = mysqli_query($con,"update se_user set agent_number = '$agent_id_edit',
		fullname = '$fullname_edit' where user_id = $usereditid");
		
		if($save)
		{
					?>
						<script>
							notify("User Profile Updated","#usereditalert");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					notify("Error Updating Profile, Please Contact the system administrator","#usereditalert");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				notify("Agent Number Already Exists","#useralert");
			</script>
		<?php
	}
	users();
}
if(isset($_REQUEST['user_reset']))
{
	$id = $_REQUEST['user_reset'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $id"));
	$pass = md5(sha1($row['agent_number']));
	$save = mysqli_query($con,"Update se_user set user_reset = 1, user_username = '$row[agent_number]', user_password = '$pass' where user_id = $id");
	
		if($save)
		{
					?>
						<script>
							notify("User Profile Reset, The default user name and password is the Agent Number","#useralert");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					notify("Unable to reset User Account, Please Contact the system administrator","#useralert");
				</script>
			<?php
		}
		
}
if(isset($_REQUEST['smenuui']))
{
	?>
		<div class="box-header with-border">
				<h3 class="box-title">MENU MANAGEMENT</h3>
		</div>
		<div class="box">
			
			<div class="box-body">
				<form id = "newmenuform">
					<div class="row">
										<div class="col-md-3">
										  
											<label for="lname">DESCRIPTION:</label>
											<input type="text" name="mdes" class="form-control" data-validation="required"
													data-validation-error-msg="Enter DESCRIPTION">
											
										 
										</div>
										<div class="col-md-3">
											<label for="lname">KEY WORD:</label>
											<input type="text" name="mkey" class="form-control"data-validation="required"
													data-validation-error-msg="Enter KEY WORD">
											
										 
										</div>
										<div class="col-md-3">
											<label for="lname">CODE LOCATION:</label>
											<input type="text" name="mloc" class="form-control"data-validation="required"
													data-validation-error-msg="Enter CODE LOCATION">
											
										 
										</div>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SAVE</button>
										  </div>
										</div>
									
							</div>
				</form>
				<div id = "menualert"></div>
			</div>
		</div>
		<script>
										$.validate({
														form:'#newmenuform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newmenuform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#menulistui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
		<div class="box">
			<div class="box-body" id = "menulistui">
				<?php menus();?>
			</div>
		</div>
	<?php
}
?>
