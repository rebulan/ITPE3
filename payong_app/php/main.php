<?php
include('connect.php');
include("general.php");

if(isset($_POST['username']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		} 	
			global $con;
			$usercount = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_user where user_username = '$username'"));
			
			if(!empty($usercount))
			{
				$pass = md5(sha1($password));
				if($pass == $usercount['user_password'])
				{
							
							$_SESSION['forecast'] = $usercount['user_username'];
						
								
						if($usercount['user_reset'] == 1)
						{
							$_SESSION['reset'] = 1;
						
						}
							?>
								<script>
									window.location.href = 'home.php';
								</script>
							<?php
							
								
				}
				else
				{
					?>
						<script>
							notify("<i class='fa fa-exclamation-triangle'></i> Invalid User Name or Password","#alert");																			
						</script>
				<?php
				}
					
			}
			else
			{
				?>
				<script>
						notify("<i class='fa fa-exclamation-triangle'></i> Invalid User Name or Password","#alert");
																																										
				</script>
				<?php
			}
}
if(isset($_REQUEST['logout']))
{
	$isp = $_REQUEST['ispages'];
	if($isp == 1)
		$link = 'window.location.href = "../index.php"';
	else
		$link = 'window.location.href = "index.php"';
		$_SESSION['forecast'] = '';
		?>
			<script>
				<?php echo $link;?>
			</script>
		<?php
}
if(isset($_REQUEST['userui']))
{
	$level = $_REQUEST['userui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-users"></i> USER PROFILES</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<form id = "newuserform">
					<div class="row">
						<div class="col-md-3">					  
							<label for="lname">USER ID NUMBER:</label>
							<input type = "hidden" name = "ulevel" value = "<?php echo $level;?>">
							<input type="text" name="agent_id" class="form-control" data-validation="required" data-validation-error-msg="Enter AGENT ID NUMBER">
							<div id = "search_result"></div>					 
						</div>
						<div class="col-md-3">
							<label for="lname">FULLNAME:</label>
							<input type="text" name="fullname" class="form-control"data-validation="required" data-validation-error-msg="Enter Fullname">
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
				<?php users($level);?>
			</div>
		</div>
	</section>
	<?php
}
if(isset($_POST['agent_id']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
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
				notify("User ID Number Already Exists","#useralert");
			</script>
		<?php
	}
	users($ulevel);
}
if(isset($_REQUEST['edit_userid']))
{
	$level = $_REQUEST['edit_userlevel'];
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
							<input type = "hidden" name = "usereditlevel" value = "<?php echo $level;?>">
							<input type="text" name="agent_id_edit" class="form-control" data-validation="required" readonly data-validation-error-msg="Enter AGENT ID NUMBER" value = "<?php echo $row['agent_number'];?>">				 
						</div>
						<div class="col-md-5">				  
							<label for="lname">FULLNAME::</label>
							<input type="text" name="fullname_edit" class="form-control"data-validation="required" data-validation-error-msg="Enter Fullname" value = "<?php echo $row['fullname'];?>">
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
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from se_user where agent_number = '$agent_id_edit'
	and user_id != $usereditid"));
	//$pass = md5(sha1($agent_id));
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
	users($usereditlevel);
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

if(isset($_REQUEST['assignmod']))
{
	$id = $_REQUEST['assignmod'];
	?>
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">USER INFORMATION</h3>
			</div>
			<div class="box-body">
				<?php agent_info($id);?>
			</div>
		</div>
		<div class="box">
			<div class="box-body">
				<div class = "form">
								<div class = "row">	
									<div class="col-md-5">
										<div class = "form-group">
											<label for="age">MODULES: </label>
											<select class = "form-control" id = "mainmodule">
												<option hidden "selected" value = "none">&nbsp;</option>
												<option value = "all">ALL</option>
												<?php
													$mmquery = mysqli_query($con, "Select * from se_module where active = 1");
													while($mmrow = mysqli_fetch_assoc($mmquery))
													{
												?>
														<option value = "<?php echo $mmrow['module_id'];?>"><?php echo $mmrow['module_name'];?></option>
												<?php
													}
												?>
											</select>
											<script>
												$("#mainmodule").change(
													function()
													{
														var mm = $("#mainmodule").val();
														
														if(mm != "all")
														{
															if(mm != 'none')
															{
																 $.post( 
																 'php/main.php',
																 {
																	 submoduleui:mm
																},
																 function(data) {
																	$('#submoduleselect').html(data);
																 });
															}
														}
														else
														{
															$('#submoduleselect').html('');
															$('#menuitemselect').html('');
														}
													}
												);
											</script>
										</div>
									</div>
									<div class="col-md-5">
										 <div class="form-group">
											<label for="age">ACCESS LEVEL</label>
											<input type= "hidden" name = "statusid" value = "<?php echo $id;?>">
											<select id="access_level" name = "access_level" class="form-control" >
												<option value = "" hidden "Selected"></option>
												<option value = "1">FULL ACCESS</option>
												<option value = "2">READ ONLY</option>
											</select>
											
										  </div>
									</div>
									
									<div class="col-md-5">
										<div id = "submoduleselect"></div>
									</div>
									
								</div>
								<div class = "row" style = "margin-top:10px;">
									<div class="col-md-5">
										<div class = "form-group">
											<button class = "btn btn-success btn-flat btn-block" id = "assignmodule">ASSIGN</button>
										</div>
									</div>
								</div>
								
								<script>
									$("#assignmodule").click(
										function()
										{
											var mm = $("#mainmodule").val();
											var sub = $("#submodule").val();
											var access = $("#access_level").val();
											var id = '<?php echo $id;?>';
											
											
											if(mm == "none")
											{
												alert("Select Module");
											}
											else if(mm == "all")
											{
												if(access != "")
												{
												 $.post( 
														 'php/main.php',
														 {
															moduleval:mm,
															submoduleval:'all',
															idval:id,
															access:access
														},
														 function(data) {
															$('#modlist').html(data);
														 });
												}
												else
												{
													alert("Select Access Level");
												}
											}
											else
											{
												if(sub == "none")
												{
													alert("Select Submodule");
												}
												else if(sub == "all")
												{
													if(access != "")
													{
													 $.post( 
															 'php/main.php',
														 {
															moduleval:mm,
															submoduleval:sub,
															idval:id,
															access:access
														},
														 function(data) {
															$('#modlist').html(data);
															//alert('OK');
														 });
													}
													else
													{
														alert("Select Access Level");
													}
												}
												else
												{
													if(access != "")
													{
														 $.post( 
															 'php/main.php',
														 {
															moduleval:mm,
															submoduleval:sub,
															idval:id,
															access:access
														},
														 function(data) {
															$('#modlist').html(data);
													
														 });
													}
													else
													{
														alert("Select Access Level");
													}
													
												
												}
											}
										}
									);
								</script>
							</div>
			</div>
		</div>		
		<div class="box">
			<div class="box-body" id = "modlist">
				<?php echo modules($id);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['submoduleui']))
{
	$id = $_REQUEST['submoduleui'];
?>
							<label>Sub Modules:</label>
									<select class = "form-control" id = "submodule">
										<option hidden "selected" value = "none">&nbsp;</option>
										<option value = "all">ALL</option>
										<?php
											$mmquery = mysqli_query($con, "Select * from se_sub_module where module_id = $id");
											while($mmrow = mysqli_fetch_assoc($mmquery))
											{
										?>
												<option value = "<?php echo $mmrow['sub_module_id'];?>"><?php echo $mmrow['sub_module_name'];?></option>
										<?php
											}
										?>
									</select>
									
									<script>
										$("#submodule").change(
											function()
											{
												var mm = $("#submodule").val();
												
												if(mm != "all")
												{
													if(mm != 'none')
													{
														 $.post( 
														 	 'php/main.php',
														 {
															 menuitemui:mm
														},
														 function(data) {
															$('#menuitemselect').html(data);
														 });
													}
												}
												else
												{
													
													$('#menuitemselect').html('');
												}
											}
										);
									</script>
<?php
}
if(isset($_REQUEST['moduleval']))
{
	
	$module = $_REQUEST['moduleval'];
	$submodule = $_REQUEST['submoduleval'];
	//$menuitem = $_REQUEST['menuitemval'];
	$empno = $_REQUEST['idval'];
	$access = $_REQUEST['access'];
	
	$modallcheck = mysqli_num_rows(mysqli_query($con,"Select * from se_user_access_module where module_id = 'all' and user_id = $empno and isdeleted = 0"));
		
	if($modallcheck == 0)
	{
		if($module == 'all')
		{
					
					mysqli_query($con, "Update se_user_access_module set 
							isdeleted = 1
							where user_id = $empno
							");
				
					
					mysqli_query($con,"insert into se_user_access_module set 
							module_id = 'all',
							sub_module_id = 'all',
							user_id = $empno,
							access_level = $access
							");
					
					/*modlog("insert into menu_assignmenttb set 
							module_id = all,
							items = all,
							menu_item = all,
							employee_no = $empno
							",1);
					modlog("Assign All Module to $empno",0);*/
	
					//menuassignment($empno);
		}
		else
		{
			//$modcheck = mysql_num_rows(mysql_query("Select * from menu_assignmenttb where module_id = '$module' and employee_no = '$empno' and deleted = 0"));
			//if($modcheck == 0)
			//{
				if($submodule == "all")
				{
					//echo "AAAAA";
					$save = mysqli_query($con, "Update se_user_access_module set 
							isdeleted = 1
							where user_id = $empno and module_id = '$module'
							");
						
					$mrow = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_module where module_id = $module"));					
					mysqli_query($con, "insert into se_user_access_module set 
							module_id = '$module',
							sub_module_id = 'all',
							user_id = $empno,
							access_level = $access
							");
						/*modlog("insert into menu_assignmenttb set 
							module_id = $module,
							items = all,
							menu_item = all,
							employee_no = '$empno'
							",1);
					modlog("Assign $mrow[module] to $empno",0);*/
					
					
				}
				else
				{
					$submodallcheck = mysqli_num_rows(mysqli_query($con, "Select * from se_user_access_module where sub_module_id = 'all' and user_id = $empno and isdeleted = 0 and module_id = '$module'"));
					if($submodallcheck == 0)
					{
						/*if($menuitem == 'all')
						{
								mysql_query("Update menu_assignmenttb set 
										deleted = 1
										where employee_no = '$empno' and items = '$submodule'
										");
								
								$log = mysql_fetch_assoc(mysql_query("Select * from menu_modulestb,menu_submoduletb where
										menu_modulestb.module_id = $module and menu_submoduletb.sub_id = $submodule"));
										
								
										
								mysql_query("insert into menu_assignmenttb set 
										module_id = '$module',
										items = '$submodule',
										menu_item = 'all',
										employee_no = '$empno'
										");
								
									modlog("insert into menu_assignmenttb set 
										module_id = $module,
										items = $submodule,
										menu_item = all,
										employee_no = $empno
									",1);
									modlog("Assign $log[module]/$log[name] to $empno",0);
										
										
						}
						else
						{	
							$allitemcheck = mysql_num_rows(mysql_query("Select * from menu_assignmenttb where items = '$submodule' 
							and employee_no = '$empno' and deleted = 0 and menu_item = 'all'"));
							
							if($allitemcheck == 0)
							{
								$itemcheck = mysql_num_rows(mysql_query("Select * from menu_assignmenttb where items = '$submodule' 
								and employee_no = '$empno' and deleted = 0 and menu_item = '$menuitem'"));
								if($itemcheck == 0)
								{*/	
									
									//$check = mysql_num_rows(mysql_query("Select * from menu_assignmenttb where employee_no = '$empno'
									//and module_id = '$module' and items = '$submodule' and menu_item = '$menuitem' and deleted = 0"));
									
									//if($check == 0)
									//{
										
										
										
										
										
										mysqli_query($con, "insert into se_user_access_module set 
										module_id = '$module',
										sub_module_id = '$submodule',
										user_id = '$empno',
										access_level = $access
										");
										
										
										
									//}
									//else
									//{
										//echo "
											//<script>
												//alert('Menu Items Already Assigned');
											//</script>
											//";
									//}
									
								/*}
								else
								{
									echo "
									<script>
										alert('Menu Items Already Assigned');
									</script>
									";
								}
							}
							else
							{
								echo "
									<script>
										alert('All Menu Items Already Assigned');
									</script>
								";
							}
						//}*/
					}
					else
					{
							echo "
								<script>
									alert('All submodules Already Assigned');
								</script>
							";
					}
				}
			//}
			/*else
			{
				echo "
				<script>
					alert('Modules Already Assigned');
				</script>
			";
			}*/
		}
	}
	else
	{
		echo "
			<script>
				alert('All modules Already Assigned');
			</script>
		";
	}
	modules($empno);
}
if(isset($_REQUEST['assigndel']))
{
	$id = $_REQUEST['assigndel'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_user_access_module where user_access_module_id = $id"));
	
	mysqli_query($con, "update se_user_access_module set isdeleted = 1 where user_access_module_id = $id");
	modules($row['user_id']);
}
if(isset($_REQUEST['resetcheck']))
{
	if(!empty($_SESSION['reset']))
	{	
		?>
			<script>
									$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'php/main.php',
																{
																	resetuser:1
																},
																function(data) {
																	$('#modalui').html(data);		
																});
			</script>
		<?php
	}
}

if(isset($_REQUEST['resetuser']))
{
	?>
			<div class="box">
						<div class="box-body">
						<form id = "newuserform" method = "POST">
							<div class="form-group">
							  <div class="form-label-group">
								<input type="email" id="newusername" name = "newusername" class="form-control" placeholder="New User Name" autofocus="autofocus">
								
							  </div>
							</div>
							<div class="form-group">
							  <div class="form-label-group">
								<input type="password" id="newpassword" name = "newpassword" class="form-control" placeholder="Password">
								
							  </div>
							</div>
							 <div class="form-group">
							  <div class="form-label-group">
								<input type="password" id="repassword" name = "repassword" class="form-control" placeholder="Retype Password">
								
							  </div>
							</div>
							<div id = "alert"></div>
						 
							<button class="btn btn-success text-white " id = "save"><i class="fa fa-save"></i> SAVE</button>
						 </form>
						</div>
			</div>
			<script>
			$("#save").click(
			function(e)
			{
				e.preventDefault();
				
				var username = $("#newusername").val();
				var pass = $("#newpassword").val();
				var repass = $("#repassword").val();
				
				if($.trim(username) != '' && $.trim(pass) != '' && $.trim(repass) != '')
				{
					if(pass == repass)
					{
							
							var formData = $('#newuserform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#click").html(data);
																				//alert('Submit Successfull');
																			}
																		});
					}
					else
					{
						$("#alert").addClass("alert alert-danger");
																										$("#alert").slideDown();
																										$("#alert").html("<i class='fa fa-exclamation-triangle'></i> Retype Password did not match");
																										
																										window.setTimeout(function() {
																										
																										$("#alert").hide();
																										}, 5000);
					}
				}
				else
				{
								$("#alert").addClass("alert alert-danger");
																										$("#alert").slideDown();
																										$("#alert").html("<i class='fa fa-exclamation-triangle'></i> Empty User Name or Password");
																										
																										window.setTimeout(function() {
																										
																										$("#alert").hide();
																										}, 5000);
				}
			}
		);
	</script>
	<?php
}

if(isset($_POST['newusername']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		}
		//echo 
		$resetuser = get_user_id($_SESSION['forecast']);
		$pass = md5(sha1($newpassword));
		mysqli_query($con, "Update se_user set user_username = '$newusername', user_password = '$pass', user_reset = 0 where user_id = $resetuser");
		
		//modlog("$id has changed his username and password",0);
		
		$_SESSION['reset'] = '';
		$_SESSION['forecast'] = $newusername;
		//$_SESSION['employee'] = '';
		?>
		<script>
			
			alert("New User Account Created");
			location.reload();
		</script>
		<?php
}
if(isset($_REQUEST['announcementui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['announcementui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-bullhorn"></i> ADVISORIES</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW ADVISORY</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-primary btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE ADVISORY</button>
					</div>
				</div>
			</div>
		</div>
	
		<script>
			$("#new").click(
				function()
				{
					$('#announceui').html(loading);	
						$.post( 
							'php/main.php',
							{
								newadv:1
							},
							function(data) {
								$('#announceui').html(data);		
						});
				}
			);
			$("#browse").click(
				function()
				{
				
					$('#announceui').html(loading);	
						$.post( 
							'php/main.php',
							{
								browseadv:1
							},
							function(data) {
								$('#announceui').html(data);		
						});
				}
			);
			
		</script>
		
		
		<div id = "announceui">
		</div>
		
	</section>
	<?php
}

if(!empty($_REQUEST['newadv']) || !empty($_REQUEST['editadvid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['newadv'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	$title = "";
	$status = 0;
	$location = 0;
	$adv = "";
	if(isset($_REQUEST['editadvid']))
	{
		$row = mysqli_fetch_assoc(mysqli_query("Select * from announcements where announcement_id = $editadvid"));
		$title = $row['title'];
		$status = $row['status'];
		$location = $row['location_id'];
		$adv = $row['description'];
	}
	?>
		<div class="box">
			<div class="box-body">
				<form id = "newadvform">
					<div class="row">
						<div class="col-md-6">					  
							<label>TITLE</label>
							<input type="text" name="adtitle" class="form-control" data-validation="required" data-validation-error-msg="Enter TITLE" value = "<?php echo $title;?>">			 
						</div>
					</div>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-4">
							<?php
							$lrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_locations where location_id = $location"));
							?>
							<div class="form-group">
								<label>LOCATION:</label>			
								<Select class = "form-control" name = "adlocation" id = "adlocation" data-validation="required"
													data-validation-error-msg="Select Location" style = "height:75px;">
									<option value = "<?php echo $lrow['location_id'];?>" hidden "Selected"><?php echo $lrow['location_description'];?></option>
													<?php
									$pmquery = mysqli_query($con,"Select * from lup_locations where isdeleted = 0");
									while($prow = mysqli_fetch_assoc($pmquery))
									{
									?>
										<option value = "<?php echo $prow['location_id'];?>"><?php echo $prow['location_description'];?></option>		
									<?php
									}
									?>
								</select>			
							</div>
						</div>
						<script>
								$("#adlocation").select2();
						</script>
						<div class="col-md-4">
				
								<?php
								$lrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $status"));
								?>
							<div class="form-group">
								<label>STATUS:</label>			
								<Select class = "form-control" name = "adstatus" id = "adstatus" data-validation="required"
													data-validation-error-msg="Select Status">
									<option value = "<?php echo $lrow['status_id'];?>" hidden "Selected"><?php echo $lrow['status'];?></option>
													<?php
									$pmquery = mysqli_query($con,"Select * from lup_status where isdeleted = 0");
									while($prow = mysqli_fetch_assoc($pmquery))
									{
									?>
										<option value = "<?php echo $prow['status_id'];?>"><?php echo $prow['status'];?></option>		
									<?php
									}
									?>
								</select>			
							</div>
							
						</div>
					</div>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-8">
							<div class="form-group">
								<label>ADVISORY:</label>			
								<textarea name = "advisory" cols = "70" rows = "10" class = "form-control" data-validation="required" data-validation-error-msg="Enter Announcement"><?php echo $adv;?></textarea>
							</div>
						</DIV>
					</div>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-5">
							<button class = "btn btn-success btn-flat" id = "adsave"><i class="fa fa-save"></i> SAVE</button>
						
							<button class = "btn btn-primary btn-flat" id = "searchproceed" style = "display:none;"><i class="fa fa-eye" ></i> PREVIEW</button>
						</div>
					</div>
				</form>
			</div>		
			<div id = "alert"></div>
		</div>
		<script>
		$("#adsave").click(
			function()
			{
				$.validate({
				form:'#newadvform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#newadvform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#click").html(data);														
					}
					});
					return false; // Will stop the submission of the form
					},
				});
			}
		);
			
													
		</script>

	<?php
}
if(isset($_POST['adtitle']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$status = 0;
	if(isset($_POST['adstatus']))
		$status = 1;

	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$check = mysqli_num_rows(mysqli_query($con, "Select * from announcements where
	title = '$adtitle' and isdeleted = 0"));
	$check = 0;
	if($check == 0)
	{
		$save = insert('announcements',['title'=>$adtitle,'description'=>$advisory,'status'=>$status,'location_id'=>$adlocation,'added_by'=>$user,'isdeleted'=>0]);
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Announcement Added","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Announcement, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Announcement Title Already Exist","#classalert");
			</script>
		<?php
	}
		
}
if(isset($_REQUEST['browseadv']))
{
	$level = $_REQUEST['browseadv'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
		<div class="box">
			<div class="box-body">
				<form id = "browseadvform">
					<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>LOCATION:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_locations where isdeleted = 0");
									?>
									<select name = "adblocation" id = "adblocation" class="form-control">
													<option value = 'all' "Selected">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['location_id'];?>"><?php echo $prow['location_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<script>
								$("#adblocation").select2();
							</script>
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "adbstatus" id = "adbstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '0' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>

							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:<i>(leave blank if all dates)</i></label>
									<input type = "date" class = "form-control" name = "adbdfrom" id = "adbdfrom" placeholder = "yyyy-mm-dd">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:<i>(leave blank if all dates)</i></label>
									<input type = "date" class = "form-control" name = "adbdto"  id = "adbdto" placeholder = "yyyy-mm-dd"  >
								</div>
							</div>
							

							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "adbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "adprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "advlist"></div>
		<script>
		$("#adbrowse").click(
			function()
			{
				$.validate({
				form:'#browseadvform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browseadvform').serializeArray();
				$("#advlist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#advlist").html(data);														
					}
					});
					return false;
					},
				});
			}
		);										
		</script>
	<?php
}
if(isset($_POST['adblocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
	<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-success btn-flat btn-block btn-sm" id = "publish"><i class="fa fa-eye"></i> PUBLISH</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-warning btn-flat btn-block btn-sm" id = "unpublish"><i class="fa fa-eye-slash"></i> UNPUBLISH</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> DELETE </button>
					</div>
				</div>
		
	<div class="box" style = "margin-top:10px;">
		<div class="box-body">
			<?php advisory($adblocation,$adbstatus,$adbdfrom,$adbdto);?>	
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['advdelete']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('announcements',['isdeleted'=>1],"announcement_id=$advdelete");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Advisory deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Advisory Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $advdeletecount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(isset($_REQUEST['locationui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['locationui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-location"></i> PINNED LOCATIONS</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW LOCATION</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-primary btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE LOCATION LIST</button>
					</div>
				</div>
			</div>
		</div>
	
		<script>
			$("#new").click(
				function()
				{
					$('#locationui').html(loading);	
						$.post( 
							'php/main.php',
							{
								newlocation:1
							},
							function(data) {
								$('#locationui').html(data);		
						});
				}
			);
			$("#browse").click(
				function()
				{
				
					$('#locationui').html(loading);	
						$.post( 
							'php/main.php',
							{
								browselocation:1
							},
							function(data) {
								$('#locationui').html(data);		
						});
				}
			);
			
		</script>
		
		
		<div id = "locationui">
		</div>
		
	</section>
	<?php
}
if(!empty($_REQUEST['newlocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['newlocation'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<div class="box">
			<div class="box-body">
				<form id = "newlocationform">
					<div class="row">
						<div class="col-md-4">					  
							<label>DESCRIPTION:</label>
							<input type="text" name="adtitle" class="form-control" data-validation="required" data-validation-error-msg="Enter TITLE">			 
						</div>
						<div class="col-md-4">					  
							<label>COORDINATES:</label>
							<input type="text" name="adtitle" class="form-control" data-validation="required" data-validation-error-msg="Enter TITLE">			 
						</div>
						<div class="col-md-4" style = "padding-top:25px;">
							<button class = "btn btn-success btn-flat" id = "adsave"><i class="fa fa-save" ></i> SAVE</button>
						</div>
					</div>
					<div class="mapouter" style = "margin-top:10px; height:500px;"><div class="gmap_canvas"><iframe width="100%" height="100%" id="gmap_canvas" src="https://maps.google.com/maps?q=philippines&t=&z=6&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://2yu.co">2yu</a><br><style>.mapouter{position:relative;text-align:right;height:100%;width:100%;}</style><a href="https://embedgooglemap.2yu.co/">html embed google map</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:100%;width:100%;}</style></div></div>
				</form>
			</div>		
			<div id = "alert"></div>
		</div>
		<script>
		$("#adsave").click(
			function()
			{
				$.validate({
				form:'#newlocationform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#newlocationform').serializeArray();									 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#click").html(data);														
					}
					});
					return false; // Will stop the submission of the form
					},
				});
			}
		);
			
													
		</script>

	<?php
}
if(isset($_REQUEST['browselocation']))
{
	$level = $_REQUEST['browselocation'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
		<div class="box">
			<div class="box-body">
				<form id = "browselocform">
					<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>PROVINCES:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_provinces where isdeleted = 0");
									?>
									<select name = "blocregion" id = "blocregion" class="form-control">
													<option value = 'all' "Selected">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['province_id'];?>"><?php echo $prow['province_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<script>
								$("#blocregion").select2();
							</script>
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "adbstatus" id = "adbstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '0' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "locbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "locprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "advlist"></div>
		<script>
		$("#locbrowse").click(
			function()
			{
				$.validate({
				form:'#browselocform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browselocform').serializeArray();
				$("#advlist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#advlist").html(data);														
					}
					});
					return false;
					},
				});
			}
		);										
		</script>
	<?php
}
if(isset($_POST['blocregion']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
	<div class="box" style = "margin-top:10px;">
		<div class="box-body">
			<?php locations(1);?>	
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['templegendui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['templegendui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-temperature-half"></i> TEMPERATURE LEGENDS</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-8">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW ENTRY</button>
					</div>
					<div class="col-lg-2 col-xs-8">
						<button class = "btn btn-primary btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE RECORDS</button>
					</div>
				</div>
			</div>
		</div>
	
		<script>
			$("#new").click(
				function()
				{
					$('#locationui').html(loading);	
						$.post( 
							'php/main.php',
							{
								newtemplegend:1
							},
							function(data) {
								$('#templegendui').html(data);		
						});
				}
			);
			$("#browse").click(
				function()
				{
				
					$('#locationui').html(loading);	
						$.post( 
							'php/main.php',
							{
								browsetemplegend:1
							},
							function(data) {
								$('#templegendui').html(data);		
						});
				}
			);
			
		</script>
		
		
		<div id = "templegendui">
		</div>
		
	</section>
	<?php
}
if(!empty($_REQUEST['newtemplegend']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['newtemplegend'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<div class="box">
			<div class="box-body">
				<form id = "newlocationform">
					<div class="row">
						<div class="col-md-3">					  
							<label>COLOR:</label>
							<input type="text" name="tempcolor" id="tempcolor"  class="form-control" data-validation="required" data-validation-error-msg="Enter COLOR">			 
						</div>
						<script>
							$('#tempcolor').spectrum({
							  type: "component"
							});
						</script>
						<div class="col-md-3">					  
							<label>TEMPERATURE FROM:</label>
							<input type="text" name="tempfrom" class="form-control" data-validation="required" data-validation-error-msg="Enter TEMPERATURE FROM">			 
						</div>
						<div class="col-md-3">					  
							<label>TEMPERATURE TO:</label>
							<input type="text" name="tempto" class="form-control" data-validation="required" data-validation-error-msg="Enter TEMPERATURE TO">			 
						</div>
						<div class="col-md-3" style = "padding-top:25px;">
							<button class = "btn btn-success btn-flat" id = "adsave"><i class="fa fa-save" ></i> SAVE</button>
						</div>
					</div>
	
				</form>
			</div>		
			<div id = "alert"></div>
		</div>
		<script>
		$("#adsave").click(
			function()
			{
				$.validate({
				form:'#newlocationform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#newlocationform').serializeArray();									 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#click").html(data);														
					}
					});
					return false; // Will stop the submission of the form
					},
				});
			}
		);
			
													
		</script>

	<?php
}
if(isset($_REQUEST['browsetemplegend2']))
{
	$level = $_REQUEST['browsetemplegend2'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
		<div class="box">
			<div class="box-body">
				<form id = "browselocform">
					<div class = "row">	
							<div class="col-md-3">					  
							<label>TEMPERATURE FROM:</label>
							<input type="text" name="tempfrom" class="form-control" data-validation="required" data-validation-error-msg="Enter TEMPERATURE FROM">			 
						</div>
						<div class="col-md-3">					  
							<label>TEMPERATURE TO:</label>
							<input type="text" name="tempto" class="form-control" data-validation="required" data-validation-error-msg="Enter TEMPERATURE TO">			 
						</div>
						<div class="col-md-3" style = "padding-top:25px;">
							<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "locbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "locprint">PRINT</button>
							</div>	
						</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "advlist"></div>
		<script>
		$("#locbrowse").click(
			function()
			{
				$.validate({
				form:'#browselocform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browselocform').serializeArray();
				$("#advlist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#advlist").html(data);														
					}
					});
					return false;
					},
				});
			}
		);										
		</script>
	<?php
}
if(isset($_POST['browsetemplegend']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
	<div class="box" style = "margin-top:10px;">
		<div class="box-body">
			<?php temperature_legends('','',1);?>	
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['rainfalllegendui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['rainfalllegendui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-cloud-rain"></i> ACTUAL RAINFALL LEGENDS</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-8">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW ENTRY</button>
					</div>
					<div class="col-lg-2 col-xs-8">
						<button class = "btn btn-primary btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE RECORDS</button>
					</div>
				</div>
			</div>
		</div>
	
		<script>
			$("#new").click(
				function()
				{
					$('#locationui').html(loading);	
						$.post( 
							'php/main.php',
							{
								newrainlegend:1
							},
							function(data) {
								$('#templegendui').html(data);		
						});
				}
			);
			$("#browse").click(
				function()
				{
				
					$('#locationui').html(loading);	
						$.post( 
							'php/main.php',
							{
								browserainlegend:1
							},
							function(data) {
								$('#templegendui').html(data);		
						});
				}
			);
			
		</script>
		
		
		<div id = "templegendui">
		</div>
		
	</section>
	<?php
}

if(!empty($_REQUEST['newrainlegend']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['newrainlegend'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<div class="box">
			<div class="box-body">
				<form id = "newlocationform">
					<div class="row">
						<div class="col-md-3">					  
							<label>COLOR:</label>
							<input type="text" name="tempcolor" id="tempcolor"  class="form-control" data-validation="required" data-validation-error-msg="Enter COLOR">			 
						</div>
						<script>
							$('#tempcolor').spectrum({
							  type: "component"
							});
						</script>
						<div class="col-md-3">					  
							<label>RAINFALL FROM(mm):</label>
							<input type="text" name="tempfrom" class="form-control" data-validation="number" data-validation-error-msg="Enter RAINFALL FROM">			 
						</div>
						<div class="col-md-3">					  
							<label>RAINFALL TO(mm):</label>
							<input type="text" name="tempto" class="form-control" data-validation="number" data-validation-error-msg="Enter RAINFALL TO">			 
						</div>
						<div class="col-md-3" style = "padding-top:25px;">
							<button class = "btn btn-success btn-flat" id = "adsave"><i class="fa fa-save" ></i> SAVE</button>
						</div>
					</div>
	
				</form>
			</div>		
			<div id = "alert"></div>
		</div>
		<script>
		$("#adsave").click(
			function()
			{
				$.validate({
				form:'#newlocationform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#newlocationform').serializeArray();									 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#click").html(data);														
					}
					});
					return false; // Will stop the submission of the form
					},
				});
			}
		);
			
													
		</script>

	<?php
}
if(isset($_POST['browserainlegend']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
	<div class="box" style = "margin-top:10px;">
		<div class="box-body">
			<?php rainfall_legends(1);?>	
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['rainfallperlegendui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['rainfallperlegendui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-cloud-rain"></i> RAINFALL PERCENTAGE LEGENDS</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-8">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW ENTRY</button>
					</div>
					<div class="col-lg-2 col-xs-8">
						<button class = "btn btn-warning btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE RECORDS</button>
					</div>
				</div>
			</div>
		</div>
	
		<script>
			$("#new").click(
				function()
				{
					$('#locationui').html(loading);	
						$.post( 
							'php/main.php',
							{
								newrainperlegend:1
							},
							function(data) {
								$('#templegendui').html(data);		
						});
				}
			);
			$("#browse").click(
				function()
				{
				
					$('#locationui').html(loading);	
						$.post( 
							'php/main.php',
							{
								browserainperlegend:1
							},
							function(data) {
								$('#templegendui').html(data);		
						});
				}
			);
			
		</script>
		
		
		<div id = "templegendui">
		</div>
		
	</section>
	<?php
}

if(!empty($_REQUEST['newrainperlegend']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['newrainperlegend'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<div class="box">
			<div class="box-body">
				<form id = "newlocationform">
					<div class="row">
						<div class="col-md-3">					  
							<label>COLOR:</label>
							<input type="text" name="tempcolor" id="tempcolor"  class="form-control" data-validation="required" data-validation-error-msg="Enter COLOR">			 
						</div>
						<script>
							$('#tempcolor').spectrum({
							  type: "component"
							});
						</script>
						<div class="col-md-3">					  
							<label>DESCRIPTION:</label>
							<input type="text" name="tempfrom" class="form-control" data-validation="required" data-validation-error-msg="Enter DESCRIPTION">			 
						</div>
						<div class="col-md-3">					  
							<label>RAINFALL FROM(%):</label>
							<input type="text" name="tempfrom" class="form-control" data-validation="number" data-validation-error-msg="Enter RAINFALL FROM">			 
						</div>
						<div class="col-md-3">					  
							<label>RAINFALL TO(%):</label>
							<input type="text" name="tempto" class="form-control" data-validation="number" data-validation-error-msg="Enter RAINFALL TO">			 
						</div>
						<div class="col-md-3" style = "padding-top:25px;">
							<button class = "btn btn-success btn-flat" id = "adsave"><i class="fa fa-save" ></i>SAVE</button>
						</div>
					</div>
	
				</form>
			</div>		
			<div id = "alert"></div>
		</div>
		<script>
		$("#adsave").click(
			function()
			{
				$.validate({
				form:'#newlocationform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#newlocationform').serializeArray();									 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#click").html(data);														
					}
					});
					return false; // Will stop the submission of the form
					},
				});
			}
		);
			
													
		</script>

	<?php
}
if(isset($_POST['browserainperlegend']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
	<div class="box" style = "margin-top:10px;">
		<div class="box-body">
			<?php rainfallper_legends(1);?>	
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['dweatherui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['dweatherui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-location"></i> 10-DAYS WEATHER FORECAST</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-warning btn-flat btn-block" id = "upload"><i class="fa fa-upload"></i> UPLOAD DATA FILE </button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW DAILY WEATHER </button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-primary btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE</button>
					</div>
				</div>
			</div>
		</div>
	
		<script>
			$("#new").click(
				function()
				{
					$('#contentui').html(loading);	
						$.post( 
							'php/main.php',
							{
								newdailyweather:1
							},
							function(data) {
								$('#contentui').html(data);		
						});
				}
			);
			$("#upload").click(
				function()
				{
					$('#contentui').html(loading);	
						$.post( 
							'php/main.php',
							{
								newupload:1
							},
							function(data) {
								$('#contentui').html(data);		
						});
				}
			);
			
			$("#browse").click(
				function()
				{
				
					$('#contentui').html(loading);	
						$.post( 
							'php/main.php',
							{
								browsedwether:1
							},
							function(data) {
								$('#contentui').html(data);		
						});
				}
			);
			
		</script>
		
		
		<div id = "contentui">
		</div>
		
	</section>
	<?php
}
if(!empty($_REQUEST['newdailyweather']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//save_daily('../images/day1.dbf');
	$level = $_REQUEST['newdailyweather'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<h2>Add New Daily Weather</h2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newweatherform">
							<div class="row">
								<div class="col-md-4">					  
									 <div class="form-group">
										<label for="lname">FORECAST DATE:</label>
										<input type="date" id = "wdate" name = "wdate" class="form-control" placeholder="DATE"  data-validation="required" data-validation-error-msg="Enter Date">
									  </div>			 
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>LOCATION:</label>
											<?PHP
											$pquery = mysqli_query($con,"Select CONCAT(lup_locations.location_description,',',lup_provinces.description) as loc, lup_locations.location_id from lup_locations, lup_provinces where lup_locations.isdeleted = 0
											and lup_locations.province_id = lup_provinces.province_id");
											?>
											<select name = "wlocation" id = "wlocation" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '' hidden "Selected">Select Location</option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['location_id'];?>"><?php echo $prow['loc'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#wlocation").select2();
											</script>
										</div>		
								</div>
								
								<div class="col-md-3" style = "display:none;">	
									 <div class="form-group">
										<label for="lname">RAIN FALL:</label>
										<input type="hidden" name="wrainfall" value = "0">				 
									</div>
								</div>
								<div class="col-md-3">	
									<div class="form-group">
									<label for="lname">RAIN FALL PERCENTAGE</label>
									<input type="number" name="wrainfallpercent" class="form-control" data-validation="required" data-validation-error-msg="Enter RAIN FALL PERCENTAGE">
									</div>
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>RAIN DESCRIPTION:</label>
											<?PHP
											$pquery = mysqli_query($con,"select * from lup_rainfall_des where isdeleted = 0");
											?>
											<select name = "wraindes" id = "wraindes" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '' hidden "Selected">Select RAIN DESCRIPTION</option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['description'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#wraindes").select2();
											</script>
										</div>		
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>CLOUD COVER:</label>
											<?PHP
											$pquery = mysqli_query($con,"select * from lup_weather_system where isdeleted = 0");
											?>
											<select name = "wcloud" id = "wcloud" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '' hidden "Selected">Select Cloud Cover Condition</option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['description'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#wcloud").select2();
											</script>
										</div>		
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="lname">WIND SPEED:</label>
										<input type="number" name="wwind" class="form-control" data-validation="required" data-validation-error-msg="Enter LOW TEMPERATURE">
									</div>			 
								</div>
								
								<div class="col-md-3">		
										 <div class="form-group">
												<label>WIND DIRECTION:</label>
											<?PHP
											$pquery = mysqli_query($con,"select * from lup_wind_direction where isdeleted = 0");
											?>
											<select name = "wwinddirect" id = "wwinddirect " class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '' hidden "Selected">Select WIND DIRECTION</option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['description'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#wwinddirect").select2();
											</script>
										</div>		
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="lname">HUMIDITY:</label>
										<input type="number" name="whumid" class="form-control" data-validation="required" data-validation-error-msg="Enter LOW TEMPERATURE">
									</div>			 
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="lname">LOW TEMPERATURE:</label>
										<input type="number" name="wlowtemp" class="form-control" data-validation="required" data-validation-error-msg="Enter LOW TEMPERATURE">
									</div>			 
								</div>
								<div class="col-md-3">					  
									<div class="form-group">
										<label for="lname">HIGH TEMPERATURE:</label>
										<input type="number" name="whightemp" class="form-control" data-validation="required" data-validation-error-msg="Enter HIGH TEMPERATURE">
									</div>
								</div>
								<div class="col-md-3">					  
									<div class="form-group">
										<label for="lname">MEAN TEMPERATURE:</label>
										<input type="number" name="wmeantemp" class="form-control" data-validation="required" data-validation-error-msg="Enter MEAN TEMPERATURE">
									</div>
								</div>
								
								<div class="col-md-3" style = "padding-top:25px;">
									<button class = "btn btn-success btn-flat" id = "wsave"><i class="fa fa-save" ></i> SAVE</button>
								</div>
							</div>
				
						</form>
							<script>
								$("#wsave").click(
									function()
									{
										$.validate({
										form:'#newweatherform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
										var formData = $('#newweatherform').serializeArray();									 
											$.ajax({
											url :  'php/main.php',
											type : 'post',
											datatype : 'json',
											data : formData,		
											success : function(data) {
												$("#click").html(data);														
											}
											});
											return false; // Will stop the submission of the form
											},
										});
									}
								);											
							</script>
				  
				
			</div>		
			
		</div>
		
	<?php
}
if(isset($_REQUEST['browsedwether']))
{
	$level = $_REQUEST['browsedwether'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
		<div class="box">
			<div class="box-body">
				<form id = "browsedailyform">
					<div class = "row">	
							<div class="col-md-4">					  
								<div class="form-group">
									<label>WEATHER DATE FROM:</label>
									<input type="date" id = "fwdatefrom" name = "fwdatefrom" class="form-control" placeholder="DATE" value = "<?PHP ECHO date("Y-m-d");?>">
								</div>			 
							</div>
							<div class="col-md-4">					  
								<div class="form-group">
									<label>WEATHER DATE FROM:</label>
									<input type="date" id = "fwdateto" name = "fwdateto" class="form-control" placeholder="DATE" value = "<?PHP ECHO date("Y-m-d");?>">
								</div>			 
							</div>
							
							<div class="col-md-3">
								 <div class="form-group">
										<label>LOCATION:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select CONCAT(lup_locations.location_description,',',lup_provinces.description) as loc, lup_locations.location_id from lup_locations, lup_provinces where lup_locations.isdeleted = 0
									and lup_locations.province_id = lup_provinces.province_id");
									?>
									<select name = "fwlocation" id = "fwlocation" class="form-control">
													<option value = 'all' "Selected">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['location_id'];?>"><?php echo $prow['loc'];?></option>
												<?php
													}
												?>
									</select>
									
								</div>		
							</div>
							<script>
								$("#fwlocation").select2();
							</script>
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "dbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "dprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "dailywlist" style = "overflow:auto;"> </div>
		<script>
		$("#dbrowse").click(
			function()
			{
				$.validate({
				form:'#browsedailyform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browsedailyform').serializeArray();
				$("#dailywlist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#dailywlist").html(data);														
					}
					});
					return false;
					},
				});
			}
		);										
		</script>
	<?php
}
if(isset($_POST['wdate']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$rainf = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_rainfall_legends where 
	rainfall_from <= $wrainfall and rainfall_to >= $wrainfall and isdeleted = 0"));
	$rainp = $rain = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_rainpercentage_legends where 
	rain_percent_from <= $wrainfallpercent and rain_percent_to >=$wrainfallpercent and isdeleted = 0"));
	$ltemp = $rain = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where 
	temp_from <= $wlowtemp and temp_to >=$wlowtemp and isdeleted = 0"));
	$htemp = $rain = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where 
	temp_from <= $whightemp and temp_to >=$whightemp and isdeleted = 0"));
	$mtemp = $rain = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where 
	temp_from <= $wmeantemp and temp_to >=$wmeantemp and isdeleted = 0"));

	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	$save = insert('daily_details',['location_id'=>$wlocation,'forecast_date'=>$wdate,'daily_forecast_rainfall'=>$wrainfall,'daily_forecast_rainfall_hex'=>$rainf['color'],'daily_forecast_rainfall_percentage'=>$wrainfallpercent,'daily_forecast_rain_percent_hex'=>$rainp['color'],'rainfall_description'=>$wraindes,'cloudcover'=>$wcloud,'humidity'=>$whumid,'windspeed'=>$wwind,'winddirection'=>$wwinddirect,'daily_forecast_low_temp'=>$wlowtemp,'daily_forecast_lowtemp_hex'=>$ltemp['color'],'daily_forecast_high_temp'=>$whightemp,'daily_forecast_hightemp_hex'=>$htemp['color'],'daily_forecast_mean_temp'=>$wmeantemp,'added_by'=>$user,'isdeleted'=>0,'daily_forecast_mean_temp_hex'=>$mtemp['color']]);
	
	if($save)
	{
	?>
		<script>
			notify("<i class='fa fa-info'></i> New Daily Weather Added","#alert");
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Daily Weather, Contact the System Administrator", "#alert");
		</script>
	<?php
	}	
}
if(isset($_POST['fwdatefrom']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
				<div class="row" style = "display:none;">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-success btn-flat btn-block btn-sm" id = "publish"><i class="fa fa-eye"></i> PUBLISH</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-warning btn-flat btn-block btn-sm" id = "unpublish"><i class="fa fa-eye-slash"></i> UNPUBLISH</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> DELETE </button>
					</div>
				</div>
		
	<div class="box" style = "margin-top:10px;">
		<div class="box-body" style = "overflow:auto;">
			<?php daily_weather($fwdatefrom ,$fwdateto,$fwlocation,1,0)?>	
		</div>
	</div>
	<?php
}
if(!empty($_REQUEST['newupload']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//save_daily('../images/day1.dbf');
	$level = $_REQUEST['newupload'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<h2>Upload Data File</h2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newweatherform">
							<div class="row">
								<div class="col-md-4">					  
									 <div class="form-group">
										<label for="lname">DATE:</label>
										<input type="date" id = "uddate" name = "uddate" class="form-control" placeholder="DATE"  data-validation="required" data-validation-error-msg="Enter Date">
									  </div>			 
								</div>
								
								<div class="col-md-3">					  
									<div class="form-group">
										<label for="lname">Data File:</label>
										<input type="file" name="udfile" class="form-control" data-validation="required" data-validation-error-msg="Browse Data  File">
									</div>
								</div>
								<div class="col-md-3" style = "padding-top:25px;">
									<button class = "btn btn-success btn-flat" id = "wsave"><i class="fa fa-save" ></i> UPLOAD</button>
								</div>
							</div>
				
						</form>
							<script>
								$("#wsave").click(
									function()
									{
										$.validate({
										form:'#newweatherform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
																var formData = $('#newweatherform')[0];
																
																$("#backdrop").modal({backdrop: false});
																$("#backdropui").html("<h2>Uploading... Please Wait..</h2>");
																$.ajax({
																						url: 'php/main.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							
																							$("#click").html(data);
																							//alert("OKKK");
																					
																						},
																						error: function() 
																						{
																							alert('Sending failed');
																						} 	        
																				   });
											return false; // Will stop the submission of the form
											},
										});
									}
								);											
							</script>
				  
				
			</div>		
			
		</div>
		
	<?php
}
if(isset($_POST['uddate']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$name = $_FILES['udfile']['name'];
		$type = $_FILES['udfile']['type'];
		$size = $_FILES['udfile']['size'];
	
			if($type == "application/octet-stream")
			{
				$save = save_daily($_FILES['udfile']['tmp_name'],$uddate);
				
				if($save != '')
				{
					?>
					<script>
						alert("<?php echo $save;?>");
						$("#backdrop").modal('hide');
						$('#contentui').html(loading);	
						$.post( 
							'php/main.php',
							{
								newupload:1
							},
							function(data) {
								$('#contentui').html(data);		
						});
						
					</script>
				<?php
				}
			}
			else
			{
					echo "
						<script>
							alert('Invalid DBF file');
						</script>
					";
			}	
}
if(!empty($_REQUEST['showmap']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['showmap'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_locations, lup_provinces where lup_locations.location_id = $showmap
	and lup_locations.province_id = lup_provinces.province_id"));
	?>
		<h2><?php echo $row['location_description']." ".$row['description'];?></h2>
		<div class="box">
			<div class="box-body">
				<form id = "newlocationform">
					<div class="row">
						<div class="col-md-4">					  
							<label>COORDINATES:</label>
							<input type = "hidden" name = "map_id" value = "<?php echo $showmap;?>">
							<input type="text" name="mcoor" class="form-control" data-validation="required" data-validation-error-msg="Enter TITLE">			 
						</div>
						<div class="col-md-4" style = "padding-top:25px;">
							<button class = "btn btn-success btn-flat" id = "adsave"><i class="fa fa-save" ></i> SAVE</button>
						</div>
					</div>
					
				</form>
			</div>		
			<div id = "alert"></div>
		</div>
		<div class="box">
			<div class="box-body" id = "coorui">
				<?php coordinates($showmap);?>
			</div>
		</div>
		<script>
		$("#adsave").click(
			function()
			{
				$.validate({
				form:'#newlocationform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#newlocationform').serializeArray();									 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#coorui").html(data);														
					}
					});
					return false; // Will stop the submission of the form
					},
				});
			}
		);
			
													
		</script>

	<?php
}
if(isset($_POST['map_id']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$status = 0;
	if(isset($_POST['adstatus']))
		$status = 1;

	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$save = insert('lup_coordinates',['coordinate'=>$mcoor,'location_id'=>$map_id,'added_by'=>$user,'isdeleted'=>0]);
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Coordinates Added","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Coordinates, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
	coordinates($map_id);
		
}
if(isset($_REQUEST['coordelete']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('lup_coordinates',['isdeleted'=>1],"coordinate_id=$coordelete");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Coordinates deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Coordinates Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $coorcount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(isset($_POST['editwid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//$rainf = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_rainfall_legends where 
	//rainfall_from <= $wrainfall and rainfall_to >= $wrainfall and isdeleted = 0"));
	$rainp = $rain = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_rainpercentage_legends where 
	rain_percent_from <= $editwrainp and rain_percent_to >=$editwrainp and isdeleted = 0"));
	$ltemp = $rain = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where 
	temp_from <= $editwlowtemp and temp_to >=$editwlowtemp and isdeleted = 0"));
	$htemp = $rain = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where 
	temp_from <= $editwhightemp and temp_to >=$editwhightemp and isdeleted = 0"));
	$mtemp = $rain = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where 
	temp_from <= $editwmeantemp and temp_to >=$editwmeantemp and isdeleted = 0"));

	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	
	$save = update('daily_details',['daily_forecast_rainfall_percentage'=>$editwrainp,'daily_forecast_rain_percent_hex'=>$rainp['color'],'rainfall_description'=>$editwraindes,'cloudcover'=>$editwcloud,'humidity'=>$editwhumid,'windspeed'=>$editwwind,'winddirection'=>$editwwinddirect,'daily_forecast_low_temp'=>$editwlowtemp,'daily_forecast_lowtemp_hex'=>$ltemp['color'],'daily_forecast_high_temp'=>$editwhightemp,'daily_forecast_hightemp_hex'=>$htemp['color'],'daily_forecast_mean_temp'=>$editwmeantemp,'daily_forecast_mean_temp_hex'=>$mtemp['color']],"daily_details_id=$editwid");
	if($save)
	{
	?>
		<script>
			alert("Daily Weather Updated");
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			alert("Error Updating New Daily Weather, Contact the System Administrator");
		</script>
	<?php
	}	
}
if(isset($_REQUEST['deletewid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('daily_details',['isdeleted'=>1],"daily_details_id=$deletewid");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Daily Weather deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Daily Weathe Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $deletewcount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
?>
