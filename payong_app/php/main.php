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
				<form id = "newuserform" method = "POST">
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
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	$title = "";
	$status = 0;
	$dfrom = "";
	$dto = "";
	$adv = "";
	$adid = "";
	$issue = 0;
	if(isset($_REQUEST['editadvid']))
	{
		$level = $_REQUEST['editadvlevel'];
		$adid =$_REQUEST['editadvid'];
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_advisory where announcement_id = $editadvid"));
		$title = $row['title'];
		$status = $row['status'];
		$adv = $row['description'];
		$issue = $row['agri_info_id'];
	}
	else{
		$level = $_REQUEST['newadv'];
	}
	?>
		<div class="box">
			<div class="box-body">
				<form id = "newadvform" method = "POST">
					<div class="row">
						<div class="col-md-6">					  
							<label>TITLE</label>
							<input type = "hidden" name = "adid" value = '<?php echo $adid;?>'>
							<input type="text" name="adtitle" class="form-control" data-validation="required" data-validation-error-msg="Enter TITLE" value = "<?php echo $title;?>">			 
						</div>
					</div>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-4">
								<?php
								$defid = "";
								$def = "";
								$lrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_info where agri_info_id = $issue"));
								if(!empty($lrow))
								{
									$defid = $lrow['agri_info_id'];
									$def = $lrow['date_from']." to ".$lrow['date_to'];
								}
								?>
							<div class="form-group">
								<label>DATE ISSUE:</label>			
								<Select class = "form-control" name = "adissue" id = "adissue" data-validation="required"
													data-validation-error-msg="Select DATE ISSUE">
									<option value = "<?php echo $defid;?>" hidden "Selected"><?php echo $def;?></option>
													<?php
									$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
									while($prow = mysqli_fetch_assoc($pmquery))
									{
									?>
										<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
									<?php
									}
									?>
								</select>			
							</div>
							
						</div>
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
					<script>
					tinymce. remove();
					
					tinymce.init({
							selector:'#advisory',
							plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap emoticons',
							toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
							 menubar: 'file edit view insert format tools table help'
							});</script>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-8">
							<div class="form-group">
								<label>ADVISORY:</label>			
								<textarea name = "advisory" id = "advisory" cols = "70" rows = "10" class = "form-control" data-validation="required" data-validation-error-msg="Enter Announcement"><?php echo $adv;?></textarea>
							</div>
						</DIV>
					</div>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-5">
							<button class = "btn btn-success btn-flat" id = "adsave"><i class="fa fa-save"></i> SAVE</button>
						<?php
						if($adid != "")
						{
							?>
								<button class = "btn btn-primary btn-flat" id = "cancel"> ADVISORY LIST</button>
								<script>
										$("#cancel").click(
											function()
											{
											
												$('#announceui').html(loading);	
													$.post( 
														'php/main.php',
														{
															browseadv:1,
															advstatus:'<?php echo $editadvstatus;?>',
															advissue:'<?php echo $editadvissue;?>'
														},
														function(data) {
															$('#announceui').html(data);		
													});
											}
										);
								</script>
							<?php
						}
						?>
							
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
		${$key} = mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$status = 0;
	if(isset($_POST['adstatus']))
		$status = 1;

	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$check = mysqli_num_rows(mysqli_query($con, "Select * from agri_advisory where
	title = '$adtitle' and isdeleted = 0"));
	$check = 0;
	if($check == 0)
	{
		if(!empty($adid))
		{
			$save = update('agri_advisory',
			['title'=>$adtitle,
			'description'=>$advisory,
			'status'=>$status,
			'agri_info_id'=>$adissue],"announcement_id=$adid");
		
			if($save)
			{
			?>
				<script>
					notify("<i class='fa fa-info'></i> Advisory Updated","#alert");
				</script>
			<?php
			}
			else
			{
			?>
				<script>
					notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Advisory, Contact the System Administrator", "#alert");
				</script>
			<?php
			}

		}
		else{
			$save = insert('agri_advisory',
			['title'=>$adtitle,
			'description'=>$advisory,
			'status'=>$status,
			'agri_info_id'=>$adissue,
			'added_by'=>$user,
			'isdeleted'=>0]);
		
			if($save)
			{
			?>
				<script>
					notify("<i class='fa fa-info'></i> New Advisory Added","#alert");
				</script>
			<?php
			}
			else
			{
			?>
				<script>
					notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Advisory, Contact the System Administrator", "#alert");
				</script>
			<?php
			}
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Advisory Title Already Exist","#classalert");
			</script>
		<?php
	}
		
}
if(isset($_REQUEST['browseadv']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['browseadv'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
		<div class="box">
			<div class="box-body">
				<form id = "browseadvform" method = "POST">
					<div class = "row">	
						
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "adbstatus" id = "adbstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '2' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DATE ISSUE:</label>			
									<Select class = "form-control" name = "adbissue" id = "adbissue" data-validation="required"
														data-validation-error-msg="Select Status">
										<option value = "all" "Selected">ALL</option>
														<?php
										$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
										while($prow = mysqli_fetch_assoc($pmquery))
										{
										?>
											<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
										<?php
										}
										?>
									</select>			
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
		<div id = "advlist">
			<?php
				if(isset($advstatus))
				{
					?>
						<div class="box" style = "margin-top:10px;">
							<div class="box-body">
								<?php advisory($advstatus,$advissue,0);;?>	
							</div>
						</div>
					<?php
					
				}
			?>
		</div>
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
if(isset($_POST['adbstatus']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
		
	<div class="box" style = "margin-top:10px;">
		<div class="box-body">
			<?php advisory($adbstatus,$adbissue,0);?>	
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
				<form id = "newlocationform" method = "POST">
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
				<form id = "browselocform" method = "POST">
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
				<form id = "newlocationform" method = "POST">
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
		<h1><i class="fa fa-location"></i> 10-DAYS WEATHER OUTLOOK</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-warning btn-flat btn-block" id = "upload"><i class="fa fa-upload"></i> UPLOAD DATA FILE </button>
					</div>
					<div class="col-lg-2 col-xs-6" style = "display:none;">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus" ></i> NEW DAILY WEATHER </button>
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
						<form id = "newweatherform" method = "POST">
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
if(!empty($_POST['batchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_advisory',['isdeleted'=>1],"announcement_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php advisory($batchstatus,$batchissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_POST['batchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_advisory',['status'=>1],"announcement_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php advisory($batchstatus,$batchissue,0);?>
			</div>
		</div>
	<?php
	
}
if(!empty($_POST['batchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_advisory',['status'=>2],"announcement_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php advisory($batchstatus,$batchissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_REQUEST['agridateui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//save_daily('../images/day1.dbf');
	$level = $_REQUEST['agridateui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<h2>AGRI WEATHER DATE ISSUE MANAGEMENT</h2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newissueform">
							<div class="row">
								<div class="col-md-4">					  
									 <div class="form-group">
										<label for="lname">DATE ISSUE FROM:</label>
										<input type="date" id = "idatefrom" name = "idatefrom" class="form-control" placeholder="DATE"  data-validation="required" data-validation-error-msg="Enter Date">
									  </div>			 
								</div>
								
								<div class="col-md-4">					  
									 <div class="form-group">
										<label for="lname">DATE ISSUE FROM:</label>
										<input type="date" id = "idateto" name = "idateto" class="form-control" placeholder="DATE"  data-validation="required" data-validation-error-msg="Enter Date">
									  </div>			 
								</div>
								<div class="col-md-4">
		
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "istatus" id = "istatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "" hidden "Selected"></option>
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
										form:'#newissueform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
										var formData = $('#newissueform').serializeArray();									 
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
		<div id = "alert"></div>
		<div class="box">
			<div class="box-body" id = "issuelist">
				<?php issue($level,0);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['idatefrom']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	$save = insert('agri_info',['date_from' => $idatefrom,'date_to' =>$idateto, 'status'=>$istatus,'added_by'=>$user]);
	if($save)
	{
	?>
		<script>
			notify("<i class='fa fa-info'></i> New Agri Weather Issue Added","#alert");
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Agri Weather Issue, Contact the System Administrator", "#alert");
		</script>
	<?php
	}	
}
if(isset($_POST['editissueid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 

	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	
	$save = update('agri_info',['date_from'=>$editissuedatefrom,'date_to'=>$editissuedateto,'status'=>$editissuestatus],"agri_info_id=$editissueid");
	if($save)
	{
	?>
		<script>
			alert("Agri Weather Issue Updated");
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			alert("Error Updating Agri Weather Issue, Contact the System Administrator");
		</script>
	<?php
	}	
}
if(isset($_REQUEST['deleteissueid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$del = update('agri_info',['isdeleted'=>1],"agri_info_id=$deleteissueid");
	?>
		<script>
			$("#controlui<?php echo $deleteissuecount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(!empty($_POST['issuebatchdelete']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_info',['isdeleted'=>1],"agri_info_id=$id");
		}
		issue($ilevel,0);
}
if(!empty($_POST['issuebatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}

		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_info',['status'=>1],"agri_info_id=$id");
		}
	
	issue($ilevel,0);
	
}
if(!empty($_POST['issuebatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_info',['status'=>2],"agri_info_id=$id");
		}
	issue($ilevel,0);
}
if(isset($_REQUEST['agriforecastui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['agriforecastui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-bullhorn"></i> AGRI 10 DAYS WEATHER FORECAST</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW FORECAST</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-primary btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE FORECAST</button>
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
								newagforecast:1
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
								browseafg:1
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
if(!empty($_REQUEST['newagforecast']) || !empty($_REQUEST['editafgid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	$title = "";
	$status = 0;
	$dfrom = "";
	$dto = "";
	$adv = "";
	$adid = "";
	$issue = 0;
	
	if(isset($_REQUEST['editafgid']))
	{
		$level = $_REQUEST['editafgid'];
		$adid =$_REQUEST['editafgid'];
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_forecast where agri_forecast_id = $editafgid"));
		$title = $row['title'];
		$status = $row['status'];
		$adv = $row['description'];
		$issue = $row['agri_info_id'];
	}
	else{
		$level = $_REQUEST['newagforecast'];
	}
	
	?>
		<div class="box">
			<div class="box-body">
				<form id = "newadvform">
					<div class="row">
						<div class="col-md-6">					  
							<label>TITLE</label>
							<input type = "hidden" name = "adid" value = '<?php echo $adid;?>'>
							<input type="text" name="agftitle" class="form-control" data-validation="required" data-validation-error-msg="Enter TITLE" value = "<?php echo $title;?>">			 
						</div>
					</div>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-4">
								<?php
								$defid = "";
								$def = "";
								$lrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_info where agri_info_id = $issue"));
								if(!empty($lrow))
								{
									$defid = $lrow['agri_info_id'];
									$def = $lrow['date_from']." to ".$lrow['date_to'];
								}
								?>
							<div class="form-group">
								<label>DATE ISSUE:</label>			
								<Select class = "form-control" name = "adissue" id = "adissue" data-validation="required"
													data-validation-error-msg="Select Status">
									<option value = "<?php echo $defid;?>" hidden "Selected"><?php echo $def;?></option>
													<?php
									$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
									while($prow = mysqli_fetch_assoc($pmquery))
									{
									?>
										<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
									<?php
									}
									?>
								</select>			
							</div>
							
						</div>
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
					<script>
					tinymce. remove();
					
					tinymce.init({
							selector:'#advisory',
							plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap emoticons',
							toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
							 menubar: 'file edit view insert format tools table help'
							});</script>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-8">
							<div class="form-group">
								<label>FORECAST:</label>			
								<textarea name = "advisory" id = "advisory" cols = "70" rows = "10" class = "form-control" data-validation="required" data-validation-error-msg="Enter Announcement"><?php echo $adv;?></textarea>
							</div>
						</DIV>
					</div>
					<div class = "row" style = "margin-top:10px;">
						<div class="col-md-5">
							<button class = "btn btn-success btn-flat" id = "adsave"><i class="fa fa-save"></i> SAVE</button>
						<?php
						if($adid != "")
						{
							?>
								<button class = "btn btn-primary btn-flat" id = "cancel"> FORECAST LIST</button>
								<script>
										$("#cancel").click(
											function(e)
											{
												e.preventDefault();
												
												$('#announceui').html(loading);	
													$.post( 
														'php/main.php',
														{
															browseafg:1,
															agfstatus:'<?php echo $editafgstatus;?>',
															agfissue:'<?php echo $editafgissue;?>'
														},
														function(data) {
															$('#announceui').html(data);
															
													});
											}
										);
								</script>
							<?php
						}
						?>
							
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
if(isset($_POST['agftitle']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$status = 0;
	if(isset($_POST['adstatus']))
		$status = 1;

	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$check = mysqli_num_rows(mysqli_query($con, "Select * from agri_advisory where
	title = '$agftitle' and isdeleted = 0"));
	$check = 0;
	if($check == 0)
	{
		if(!empty($adid))
		{
			$save = update('agri_forecast',
			['title'=>$agftitle,
			'description'=>$advisory,
			'status'=>$status,
			'agri_info_id'=>$adissue],"agri_forecast_id=$adid");
		
			if($save)
			{
			?>
				<script>
					notify("<i class='fa fa-info'></i> Advisory Updated","#alert");
				</script>
			<?php
			}
			else
			{
			?>
				<script>
					notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Advisory, Contact the System Administrator", "#alert");
				</script>
			<?php
			}

		}
		else{
			$save = insert('agri_forecast',
			['title'=>$agftitle,
			'description'=>$advisory,
			'status'=>$status,
			'agri_info_id'=>$adissue,
			'added_by'=>$user,
			'isdeleted'=>0]);
		
			if($save)
			{
			?>
				<script>
					notify("<i class='fa fa-info'></i> New Forecast Added","#alert");
				</script>
			<?php
			}
			else
			{
			?>
				<script>
					notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Forecast, Contact the System Administrator", "#alert");
				</script>
			<?php
			}
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Forecast Title Already Exist","#classalert");
			</script>
		<?php
	}	
}
if(isset($_REQUEST['browseafg']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['browseafg'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
		<div class="box">
			<div class="box-body">
				<form id = "browseafgform" method = "POST">
					<div class = "row">	
						
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "afgstatus" id = "afgstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '2' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DATE ISSUE:</label>			
									<Select class = "form-control" name = "afgissue" id = "afgissue" data-validation="required"
														data-validation-error-msg="Select Status">
										<option value = "all" "Selected">ALL</option>
														<?php
										$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
										while($prow = mysqli_fetch_assoc($pmquery))
										{
										?>
											<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
										<?php
										}
										?>
									</select>			
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
		<div id = "forecastlist">
			<?php
				if(isset($agfstatus))
				{
					?>
						<div class="box" style = "margin-top:10px;">
							<div class="box-body">
								<?php agri_forecast($agfstatus,$agfissue,0);?>	
							</div>
						</div>
					<?php
				}
			?>
		</div>
		<script>
		$("#adbrowse").click(
			function()
			{
				$.validate({
				form:'#browseafgform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browseafgform').serializeArray();
				$("#forecastlist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#forecastlist").html(data);														
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
if(isset($_POST['afgstatus']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
	<div class="box" style = "margin-top:10px;">
		<div class="box-body">
			<?php agri_forecast($afgstatus,$afgissue,0);?>	
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['afgdelete']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$del = update('agri_forecast',['isdeleted'=>1],"agri_forecast_id=$afgdelete");
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Forecast deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Forecast Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $afgdeletecount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(!empty($_POST['afgbatchdelete']))
{
	
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_forecast',['isdeleted'=>1],"agri_forecast_id=$id");
		}
	
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_forecast($afgbatchstatus,$afgbatchissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_POST['afgbatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_forecast',['status'=>1],"agri_forecast_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_forecast($afgbatchstatus,$afgbatchissue,0);?>
			</div>
		</div>
	<?php
	
}
if(!empty($_POST['afgbatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_forecast',['status'=>2],"agri_forecast_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_forecast($afgbatchstatus,$afgbatchissue,0);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['prognosisui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['prognosisui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-location"></i> PROGNOSIS</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW PROGNOSIS</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-primary btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE PROGNOSIS</button>
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
								newprognosis:1
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
								browseprognosis:1
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
if(!empty($_REQUEST['newprognosis']) || !empty($_REQUEST['editprogid']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	$title = "";
	$status = 0;
	$issue= 0;
	$prog_id = 0;
	$content = "";
	$rainfmin = "";
	$rainfmax = "";
	$raindmin = "";
	$raindmax = "";
	$tempmin = "";
	$tempmax= "";
	$soil = 0;
	$region = 0;
	if(isset($_REQUEST['editprogid']))
	{
		$level = $_REQUEST['editproglevel'];
		$adid =$_REQUEST['editprogid'];
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_prognosis where prognosis_id = $editprogid"));
		$title = $row['title'];
		$status = $row['status'];
		$content = $row['content'];
		$issue = $row['agri_info_id'];
		$prog_id = $editprogid;
		$rainfmin = $row['rainf_min'];
		$rainfmax = $row['rainf_max'];
		$raindmin = $row['raind_min'];
		$raindmax = $row['raind_max'];
		$tempmin = $row['temp_min'];
		$tempmax= $row['temp_max'];
		$soil = $row['soil_condition_id'];
		$region = $row['region_id'];
		?>
		<script>
				$('#soillocationui').html(loading);
				$.post( 
					'php/main.php',
					{
						progprovincelist:'<?php echo $region;?>',
						progval:'<?php echo $row['soil_condition_province'];?>'
					},
					function(data) {
						$('#soillocationui').html(data);
					});
															
											
		</script>
		<?php
	}
	else{
		$level = $_REQUEST['newprognosis'];
	}
	?>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newprognosisform" method = "POST">
							<div class="row">
								<div class="col-md-3">		
										 <div class="form-group">
												<label>LOCATION:</label>
											<?PHP
											$rrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_regions where region_id = $region"));
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											<input type = "hidden" name = "editprognosis_id" value = "<?php echo $prog_id;?>">
											<select name = "plocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '<?php echo $rrow['region_id'];?>' hidden "Selected"><?php echo $rrow['description'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																progprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#soillocationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
								<div class="col-md-4">
									<?php
									$arow = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_info where agri_info_id = $issue"));
									$iss = "";
									
									if(!empty($arow))
										$iss = $arow['date_from']." to ".$arow['date_to'];
									?>
									<div class="form-group">
										<label>DATE ISSUE:</label>			
										<Select class = "form-control" name = "pissue" id = "pissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "<?php echo $arow['agri_info_id'];?>" hidden "Selected"><?php echo $iss;?></option>
															<?php
											
											$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									<?php
									$srow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $status"));
									?>
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "pstatus" id = "pstatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "<?php echo $srow['status_id'];?>" hidden "Selected"><?php echo $srow['status'];?></option>
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
						
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MIN RAIN FALL:</label>
										<input type="number" name="pminrainfall" class = "form-control" data-validation="required" data-validation-error-msg="Enter MIN RAIN FALL" value = "<?php echo $rainfmin;?>">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MAX RAIN FALL:</label>
										<input type="number" name="pmaxrainfall" class = "form-control" data-validation="required" data-validation-error-msg="Enter MAX RAIN FALL" value = "<?php echo $rainfmax;?>">				 
									</div>
								</div>
								
								<div class="col-md-3">	
									<div class="form-group">
									<label for="lname">MIN RAINY DAYS</label>
									<input type="number" name="pminraind" class="form-control" data-validation="required" data-validation-error-msg="Enter MIN RAINY DAYS" value = "<?php echo $raindmin;?>">
									</div>
								</div>
								<div class="col-md-3">	
									<div class="form-group">
									<label for="lname">MAX RAINY DAYS</label>
									<input type="number" name="pmaxraind" class="form-control" data-validation="required" data-validation-error-msg="Enter MAX RAINY DAYS" value = "<?php echo $rainfmax;?>">
									</div>
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<?php
									$scrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_soil_wetness where soil_wetness_id = $soil"));
									?>
									
												<label>SOIL CONDITION:</label>
											<?PHP
											$pquery = mysqli_query($con,"select * from lup_soil_wetness where isdeleted = 0");
											?>
											<select name = "psoil" id = "psoil" class="form-control"  data-validation="required" data-validation-error-msg="Select SOIL CONDITION">
															<option value = '<?php echo $scrow['soil_wetness_id'];?>' hidden "Selected"><?php echo $scrow['description'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['soil_wetness_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											
										</div>		
								</div>
							
								<div class="col-md-3">
									<div class="form-group">
										<label for="lname">MIN TEMPERATURE:</label>
										<input type="number" name="pmintemp" class="form-control" data-validation="required" data-validation-error-msg="Enter MIN TEMPERATURE" value = "<?php echo $tempmin;?>">
									</div>			 
								</div>
								<div class="col-md-3">					  
									<div class="form-group">
										<label for="lname">MAX TEMPERATURE:</label>
										<input type="number" name="pmaxtemp" class="form-control" data-validation="required" data-validation-error-msg="Enter MAX TEMPERATURE" value = "<?php echo $tempmax;?>">
									</div>
								</div>
								
							</div>
							<div id = "soillocationui"></div>
							<div class = "row" style = "margin-top:10px;">
									<div class="col-md-3">					  
										<div class="form-group">
											<label for="lname">TITLE:</label>
											<input type="text" name="ptitle" class="form-control" data-validation="required" data-validation-error-msg="Enter TITLE" value = "<?php echo $title;?>">
										</div>
									</div>
							</div>
								<script>
								tinymce. remove();
					
								tinymce.init({
										selector:'#psum',
										plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap emoticons',
										toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
										 menubar: 'file edit view insert format tools table help'
										});</script>
										
								<div class = "row" style = "margin-top:10px;">
									<div class="col-md-8">
										<div class="form-group">
											<label>SUMMARY:</label>			
											<textarea name = "psum" id = "psum" cols = "70" rows = "10" class = "form-control" data-validation="required" data-validation-error-msg="Enter SUMMARY"><?php echo $content;?></textarea>
										</div>
									</DIV>
								</div>
								<div class = "row">
									<div class="col-md-3" style = "padding-top:25px;">
										<button class = "btn btn-success btn-flat" id = "wsave"><i class="fa fa-save" ></i> SAVE</button>
										<?php
										if(isset($_REQUEST['editprogid']))
										{
											?>
												<button class = "btn btn-danger btn-flat" id = "cancel"><i class="fa fa-arrow-left"></i> BACK</button>
												<script>
														$("#cancel").click(
															function(e)
															{
																e.preventDefault();
																$('#contentui').html(loading);	
																	$.post( 
																		'php/main.php',
																		{
																			browseprognosis:1,
																			progstatus:'<?php echo $editprogstatus;?>',
																			progissue:'<?php echo $editprogissue;?>',
																			proglocation:'<?php echo $editproglocation;?>'
																		},
																		function(data) {
																			$('#contentui').html(data);		
																	});
															}
														);
												</script>
											<?php
										}
										?>
									</div>

								</div>
				
						</form>
							<script>
								$("#wsave").click(
									function()
									{
										$.validate({
										form:'#newprognosisform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#newprognosisform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $('#newprognosisform').serializeArray();									 
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
												alert("Select at least 1 Soil Condition Location")
											}
								
										
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
if(isset($_POST['plocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
	$provval = trim($provval,",");
	
	if($editprognosis_id != 0)
	{
		$save = update('agri_prognosis',['region_id'=>$plocation,
		'title'=>$ptitle,'content'=>$psum,
		'rainf_min'=>$pminrainfall,
		'rainf_max'=>$pmaxrainfall,
		'raind_min'=>$pminraind,
		'raind_max'=>$pmaxraind,
		'temp_min'=>$pmintemp,
		'temp_max'=>$pmaxtemp,
		'soil_condition'=>'',
		'soil_condition_id'=>$psoil,
		'soil_condition_province'=>$provval,
		'status'=>$pstatus],"prognosis_id = $editprognosis_id");
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Prognosis Updated","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Prognosis, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
	}
	else{
		$save = insert('agri_prognosis',['region_id'=>$plocation,
		'title'=>$ptitle,'content'=>$psum,
		'rainf_min'=>$pminrainfall,
		'rainf_max'=>$pmaxrainfall,
		'raind_min'=>$pminraind,
		'raind_max'=>$pmaxraind,
		'temp_min'=>$pmintemp,
		'temp_max'=>$pmaxtemp,
		'soil_condition'=>'',
		'soil_condition_id'=>$psoil,
		'soil_condition_province'=>$provval,
		'status'=>$pstatus,
		'agri_info_id'=>$pissue,
		'added_by'=>$user,
		'isdeleted'=>0]);
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Prognosis Added","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Prognosis, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
	
	}
	
}
if(isset($_REQUEST['browseprognosis']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['browseprognosis'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
		<div class="box">
			<div class="box-body">
				<form id = "browseprogform" method = "POST">
					<div class = "row">	
							<div class="col-md-3">		
										 <div class="form-group">
												<label>LOCATION:</label>
											<?PHP
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											<select name = "ppblocation" id = "pblocation" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = 'all' hidden "Selected">ALL</option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											
										</div>		
								</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "pbstatus" id = "pbstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '2' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DATE ISSUE:</label>			
									<Select class = "form-control" name = "pbissue" id = "pbissue" data-validation="required"
														data-validation-error-msg="Select Status">
										<option value = "all" "Selected">ALL</option>
														<?php
										$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
										while($prow = mysqli_fetch_assoc($pmquery))
										{
										?>
											<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
										<?php
										}
										?>
									</select>			
								</div>
							
							</div>	
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "pbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "pprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "proglist" style = "overflow:auto;">
			<?php
			if(!empty($progissue))
			{
				?>
				<div class="box">
					<div class="box-body">
						<?php echo prognosis($progstatus,$progissue,$proglocation,0);?>
					</div>
				</div>
				<?php
				
			}
			?>
		</div>
		<script>
		$("#pbrowse").click(
			function()
			{
				$.validate({
				form:'#browseprogform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browseprogform').serializeArray();
				$("#proglist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#proglist").html(data);														
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
if(!empty($_POST['ppblocation']))
{
		foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	?>
		<div class="box">
			<div class="box-body">
				<?php prognosis($pbstatus,$pbissue,$ppblocation,0);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['deleteprog']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('agri_prognosis',['isdeleted'=>1],"prognosis_id = $deleteprog");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Prognosis deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Prognosis Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $deleteprogcount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(isset($_REQUEST['progprovincelist']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$prquery = mysqli_query($con,"Select * from lup_provinces where region_id = $progprovincelist and isdeleted = 0");
	?>
		<H4>SOIL CONDITION LOCATION</H4>
		<div class="box">
			<div class="box-body">
				<div class = "row">
				<?php
					if(!empty($_REQUEST['progval']))
					{
						$parray = explode(',',$progval);
						$cc = count($parray);
						
						while($row = mysqli_fetch_assoc($prquery))
						{
							$c = 0;
							$checked = '';
							while($c<=$cc-1)
							{
								if($row['province_id'] == $parray[$c])
								{
									$checked = 'checked';
									break;
								}
								$c++;
							}
							?>
								<div class="col-md-2">
										<div class="form-group">
											<label>
											<input type="checkbox" name="progprovince[<?php echo $row['province_id'];?>]" <?php echo $checked;?>>
											<?php echo $row['description'];?></label>
										</div>			 
								</div>	
							<?php
						}
					}
					else
					{
						while($row = mysqli_fetch_assoc($prquery))
						{
							?>
								<div class="col-md-2">
										<div class="form-group">
											<label>
											<input type="checkbox" name="progprovince[<?php echo $row['province_id'];?>]">
											<?php echo $row['description'];?></label>
										</div>			 
								</div>	
							<?php
						}
					}
				?>
				</div>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['dailyagriprovincelist']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$prquery = mysqli_query($con,"Select * from lup_provinces where region_id = $dailyagriprovincelist and isdeleted = 0");
	?>
		<H4>PROVINCES</H4>
		<div class="box">
			<div class="box-body">
				<div class = "row">
				<?php
					if(!empty($_REQUEST['progval']))
					{
						$parray = explode(',',$progval);
						$cc = count($parray);
						
						while($row = mysqli_fetch_assoc($prquery))
						{
							$c = 0;
							$checked = '';
							while($c<=$cc-1)
							{
								if($row['province_id'] == $parray[$c])
								{
									$checked = 'checked';
									break;
								}
								$c++;
							}
							?>
								<div class="col-md-2">
										<div class="form-group">
											<label>
											<input type="checkbox" name="progprovince[<?php echo $row['province_id'];?>]" <?php echo $checked;?>>
											<?php echo $row['description'];?></label>
										</div>			 
								</div>	
							<?php
						}
					}
					else
					{
						while($row = mysqli_fetch_assoc($prquery))
						{
							?>
								<div class="col-md-2">
										<div class="form-group">
											<label>
											<input type="checkbox" name="progprovince[<?php echo $row['province_id'];?>]">
											<?php echo $row['description'];?></label>
										</div>			 
								</div>	
							<?php
						}
					}
				?>
				</div>
			</div>
		</div>
	<?php
}

if(!empty($_POST['progbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_prognosis',['isdeleted'=>1],"prognosis_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php prognosis($progstatus,$progissue,$proglocation,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_POST['progbatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_prognosis',['status'=>1],"prognosis_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
			<?php prognosis($progstatus,$progissue,$proglocation,0);?>
			</div>
		</div>
	<?php
	
}
if(!empty($_POST['progbatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_prognosis',['status'=>2],"prognosis_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php prognosis($progstatus,$progissue,$proglocation,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_REQUEST['agridailydateui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//save_daily('../images/day1.dbf');
	$level = $_REQUEST['agridailydateui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<h2>DAILY FARM WEATHER DATE ISSUE MANAGEMENT</h2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newadissueform">
							<div class="row">
								<div class="col-md-4">					  
									 <div class="form-group">
										<label for="lname">DATE ISSUE:</label>
										<input type = "hidden" value = "<?php echo $level;?>" name = 'adilevel'>
										<input type="datetime-local" id = "adidateissue" name = "adidateissue" class="form-control" placeholder="DATE"  data-validation="required" data-validation-error-msg="Enter DATE ISSUE">
									  </div>			 
								</div>
								
								<div class="col-md-4">					  
									 <div class="form-group">
										<label for="lname">VALIDITY DATE:</label>
										<input type="datetime-local" id = "adivalidity" name = "adivalidity" class="form-control" placeholder="DATE"  data-validation="required" data-validation-error-msg="Enter VALIDITY DATE">
									  </div>			 
								</div>
								<div class="col-md-4">
		
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "adistatus" id = "adistatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "" hidden "Selected"></option>
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
										form:'#newadissueform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
										var formData = $('#newadissueform').serializeArray();									 
											$.ajax({
											url :  'php/main.php',
											type : 'post',
											datatype : 'json',
											data : formData,		
											success : function(data) {
												$("#adissuelist").html(data);														
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
		<div id = "alert"></div>
		<div class="box">
			<div class="box-body" id = "adissuelist">
				<?php agridailyissue($level,0);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['adidateissue']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	$save = insert('agri_daily',['date_issue' => $adidateissue,'validity_date' =>$adivalidity, 'status'=>$adistatus,'added_by'=>$user]);
	if($save)
	{
	?>
		<script>
			notify("<i class='fa fa-info'></i> New Daily Farm Weather Issue Date Added","#alert");
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Daily Farm Weather Issue Date , Contact the System Administrator", "#alert");
		</script>
	<?php
	}	
	agridailyissue($adilevel,0);
}
if(isset($_POST['editadissueid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 

	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	
	$save = update('agri_daily',['date_issue'=>$editadidateissue,'validity_date'=>$editadivalidity,'status'=>$editadistatus],"agri_daily_id=$editadissueid");
	if($save)
	{
	?>
		<script>
			alert("Daily Farm Weather Issue Updated");
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			alert("Error Updating Daily Farm Weather Issue, Contact the System Administrator");
		</script>
	<?php
	}	
}
if(isset($_REQUEST['deleteadissueid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$del = update('agri_daily',['isdeleted'=>1],"agri_daily_id=$deleteadissueid");
	?>
		<script>
			$("#controlui<?php echo $deleteadissuecount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(!empty($_POST['adissuebatchdelete']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily',['isdeleted'=>1],"agri_daily_id=$id");
		}
		agridailyissue($ilevel,0);
}
if(!empty($_POST['adissuebatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}

		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily',['status'=>1],"agri_daily_id=$id");
		}
	
	agridailyissue($ilevel,0);
	
}
if(!empty($_POST['adissuebatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily',['status'=>2],"agri_daily_id=$id");
		}
	agridailyissue($ilevel,0);
}
if(isset($_REQUEST['synopsisui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$level = $_REQUEST['synopsisui'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	?>
	<section class="content-header">
		<h1><i class="fa fa-location"></i> DAILY FARM WEATHER SYNOPSIS</H1>
	</section>
	<section class = "content">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-success btn-flat btn-block" id = "new"><i class="fa fa-plus"></i> NEW SYNOPSIS</button>
					</div>
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-primary btn-flat btn-block" id = "browse"><i class="fa fa-search"></i> BROWSE SYNOPSIS</button>
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
								newsynopsis:1
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
								browsesynopsis:1
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
if(!empty($_REQUEST['newsynopsis']) || !empty($_REQUEST['editsysid']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	$title = "";
	$status = 0;
	$issue= 0;
	$sys_id = 0;
	$content = "";

	if(isset($_REQUEST['editsysid']))
	{
		$level = $_REQUEST['editsyslevel'];
		$adid =$_REQUEST['editsysid'];
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily_synopsis where synopsis_id = $editsysid"));
		$title = $row['title'];
		$status = $row['status'];
		$content = $row['synopsis'];
		$issue = $row['agri_daily_id'];
		$sys_id = $editsysid;
		?>
		<?php
	}
	else{
		$level = $_REQUEST['newsynopsis'];
	}
	?>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newsynopsisform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<?php
									$arow = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $issue"));
									$iss = "";
									
									if(!empty($arow))
										$iss = $arow['date_issue'];
									?>
									<div class="form-group">
										<label>DATE ISSUE:</label>	
										<input type = "hidden" name = "editsys_id" value = "<?php echo $sys_id;?>">
										<Select class = "form-control" name = "sysissue" id = "sysissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "<?php echo $arow['agri_info_id'];?>" hidden "Selected"><?php echo $iss;?></option>
															<?php
											
											$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									<?php
									$srow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $status"));
									?>
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "sysstatus" id = "sysstatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "<?php echo $srow['status_id'];?>" hidden "Selected"><?php echo $srow['status'];?></option>
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
									<div class="col-md-3">					  
										<div class="form-group">
											<label for="lname">TITLE:</label>
											<input type="text" name="systitle" class="form-control" data-validation="required" data-validation-error-msg="Enter TITLE" value = "<?php echo $title;?>">
										</div>
									</div>
							</div>
							
								<script>
								tinymce. remove();
					
								tinymce.init({
										selector:'#syssum',
										plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap emoticons',
										toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
										 menubar: 'file edit view insert format tools table help'
										});</script>
										
								<div class = "row" style = "margin-top:10px;">
									<div class="col-md-8">
										<div class="form-group">
											<label>SYNOPSIS:</label>			
											<textarea name = "syssum" id = "syssum" cols = "70" rows = "10" class = "form-control" data-validation="required" data-validation-error-msg="Enter SUMMARY"><?php echo $content;?></textarea>
										</div>
									</DIV>
								</div>
								<div class = "row">
									<div class="col-md-3" style = "padding-top:25px;">
										<button class = "btn btn-success btn-flat" id = "wsave"><i class="fa fa-save" ></i> SAVE</button>
										<?php
										if(isset($_REQUEST['editsysid']))
										{
											?>
												<button class = "btn btn-danger btn-flat" id = "cancel"><i class="fa fa-arrow-left"></i> BACK</button>
												<script>
														$("#cancel").click(
															function(e)
															{
																e.preventDefault();
																$('#contentui').html(loading);	
																	$.post( 
																		'php/main.php',
																		{
																			browsesynopsis:1,
																			sypstatus:'<?php echo $editsysstatus;?>',
																			sypissue:'<?php echo $editsysissue;?>'
																		},
																		function(data) {
																			$('#contentui').html(data);		
																	});
															}
														);
												</script>
											<?php
										}
										?>
									</div>

								</div>
				
						</form>
							<script>
								$("#wsave").click(
									function()
									{
										$.validate({
										form:'#newsynopsisform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {

												var formData = $('#newsynopsisform').serializeArray();									 
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
if(isset($_POST['sysissue']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
	if($editsys_id != 0)
	{
		$save = update('agri_daily_synopsis',[
		'title'=>$systitle,'synopsis'=>$syssum,
		'agri_daily_id'=>$sysissue,
		'status'=>$sysstatus],"synopsis_id = $editsys_id");
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Synopsis Updated","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Synopsis, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
	}
	else{
		$save = insert('agri_daily_synopsis',[
		'title'=>$systitle,'synopsis'=>$syssum,
		'status'=>$sysstatus,
		'agri_daily_id'=>$sysissue,
		'added_by'=>$user,
		'isdeleted'=>0]);
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Synopsis Added","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Synopsis, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
	}
	
}
if(isset($_REQUEST['browsesynopsis']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$level = $_REQUEST['browsesynopsis'];
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	?>
		<div class="box">
			<div class="box-body">
				<form id = "browsesysform" method = "POST">
					<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "bsysstatus" id = "bsysstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '2' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DATE ISSUE:</label>			
									<Select class = "form-control" name = "bsysissue" id = "bsysissue" data-validation="required"
														data-validation-error-msg="Select Status">
										<option value = "all" "Selected">ALL</option>
														<?php
										$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
										while($prow = mysqli_fetch_assoc($pmquery))
										{
										?>
											<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
										<?php
										}
										?>
									</select>			
								</div>
							
							</div>	
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "pbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "pprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "synopsislist" style = "overflow:auto;">
			<?php
			if(!empty($sypissue))
			{
				?>
				<div class="box">
					<div class="box-body">
						<?php echo synopsis($sypstatus,$sypissue,0);?>
					</div>
				</div>
				<?php
				
			}
			?>
		</div>
		<script>
		$("#pbrowse").click(
			function()
			{
				$.validate({
				form:'#browsesysform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browsesysform').serializeArray();
				$("#synopsislist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#synopsislist").html(data);														
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
if(!empty($_POST['bsysissue']))
{
		foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	?>
		<div class="box">
			<div class="box-body">
				<?php synopsis($bsysstatus,$bsysissue,0);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['deletesys']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('agri_daily_synopsis',['isdeleted'=>1],"synopsis_id = $deletesys");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Synopsis deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Synopsis Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $deletesyscount;?>").html('RECORD DELETED!');
		</script>
	<?php
}

if(!empty($_POST['sypbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_synopsis',['isdeleted'=>1],"synopsis_id = $id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php synopsis($sypstatus,$sypissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_POST['sypbatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_synopsis',['status'=>1],"synopsis_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
			<?php synopsis($sypstatus,$sypissue,0);?>
			</div>
		</div>
	<?php
	
}
if(!empty($_POST['sypbatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_synopsis',['status'=>2],"synopsis_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
			<?php synopsis($sypstatus,$sypissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_REQUEST['agrihumidityui']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$level = $agrihumidityui
	
	?>
	
		<h2>DAILY FARM WEATHER HUMIDITY</H2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newagrihumidityform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>DATE ISSUE:</label>			
										<Select class = "form-control" name = "huissue" id = "huissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "" hidden "Selected"></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "hustatus" id = "hustatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "" hidden "Selected"></option>
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
						
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MIN HUMIDITY:</label>
										<input type="number" name="humin" class = "form-control" data-validation="required" data-validation-error-msg="Enter MIN RAIN FALL" value = "<?php echo $rainfmin;?>">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MAX HUMIDITY:</label>
										<input type="number" name="humax" class = "form-control" data-validation="required" data-validation-error-msg="Enter MAX RAIN FALL" value = "<?php echo $rainfmax;?>">				 
									</div>
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>REGIONS:</label>
											<?PHP
											
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											
											<select name = "hulocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select REGIONS">
															<option value = '' hidden "Selected"></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#locationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
							</DIV>
								<div id = "locationui"></div>
								<div class = "row">
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
										form:'#newagrihumidityform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#newagrihumidityform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $('#newagrihumidityform').serializeArray();									 
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
												alert("Select at least 1 Province")
											}
								
										
											return false; // Will stop the submission of the form
											},
										});
									}
								);											
							</script>
				  
				
			</div>		
			
		</div>
		<div class="box">
			<div class="box-body">
				<form id = "browsehumidform" method = "POST">
					<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "bhumstatus" id = "bhumstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '2' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DATE ISSUE:</label>			
									<Select class = "form-control" name = "bhumissue" id = "bhumissue" data-validation="required"
														data-validation-error-msg="Select Status">
										<option value = "all" "Selected">ALL</option>
														<?php
										$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
										while($prow = mysqli_fetch_assoc($pmquery))
										{
										?>
											<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
										<?php
										}
										?>
									</select>			
								</div>
							
							</div>	
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "pbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "pprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "agrihumlist" style = "overflow:auto;">
		</div>
		<script>
		$("#pbrowse").click(
			function()
			{
				$.validate({
				form:'#browsehumidform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browsehumidform').serializeArray();
				$("#agrihumlist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#agrihumlist").html(data);														
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
if(isset($_POST['hulocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
		$provval = trim($provval,",");
	

	
		$save = insert('agri_daily_humidity',[
		'humidity_min'=>$humin,
		'humidity_max'=>$humax,
		'provinces'=>$provval,
		'status'=>$hustatus,
		'agri_daily_id'=>$huissue,
		'added_by'=>$user,
		'isdeleted'=>0]);
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Agri Daily Humidity Added","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Agri Daily Humidity, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
}
if(!empty($_REQUEST['bhumissue']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
		<div class="box">
			<div class="box-body">
			<?php  agri_daily_humidity($bhumstatus,$bhumissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_REQUEST['editahumid']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$level = $editahumid;
	$row = mysqli_fetch_assoc(mysqli_query($con,"select * from agri_daily_humidity where agri_daily_humidity_id = $editahumid"));
	?>
		<script>
												$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val(),
																progval:'<?php echo $row['provinces'];?>'
															},
															function(data) {
																$('#locationui').html(data);
															});
		</script>
		
		<h2>EDIT HUMIDITY INFORMATION</H2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "updateagrihumidityform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>DATE ISSUE:</label>	
										<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
										?>
										<input type = "hidden" value = "<?php echo $editahumid;?>" name = "ehuid">
										<input type = "hidden" value = "<?php echo $editahumctr;?>" name = "ehuctr">
										<Select class = "form-control" name = "ehuissue" id = "ehuissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "<?php echo $irow['agri_daily_id'];?>" hidden "Selected"><?php echo $irow['date_issue'];?></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
										?>
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "ehustatus" id = "ehustatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "<?php echo $irow['status_id'];?>" hidden "Selected"><?php echo $irow['status'];?></option>
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
						
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MIN HUMIDITY:</label>
										<input type="number" name="ehumin" class = "form-control" data-validation="required" data-validation-error-msg="Enter MIN RAIN FALL" value = "<?php echo $row['humidity_min'];?>">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MAX HUMIDITY:</label>
										<input type="number" name="ehumax" class = "form-control" data-validation="required" data-validation-error-msg="Enter MAX RAIN FALL" value = "<?php echo $row['humidity_max'];?>">				 
									</div>
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>REGIONS:</label>
											<?PHP
											$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_regions where region_id = $row[region_id]"));
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											
											<select name = "ehulocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select REGIONS">
															<option value = '<?php echo $irow['region_id'];?>' hidden "Selected"><?php echo $irow['description'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#locationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
							</DIV>
								<div id = "locationui"></div>
								<div class = "row">
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
										form:'#updateagrihumidityform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#updateagrihumidityform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $('#updateagrihumidityform').serializeArray();									 
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
												alert("Select at least 1 Province")
											}
								
										
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
if(isset($_POST['ehulocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
		$provval = trim($provval,",");

		$save = update('agri_daily_humidity',[
		'humidity_min'=>$ehumin,
		'humidity_max'=>$ehumax,
		'provinces'=>$provval,
		'status'=>$ehustatus,
		'region_id'=>$ehulocation,
		'agri_daily_id'=>$ehuissue],"agri_daily_humidity_id = $ehuid");
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Agri Daily Humidity Updated","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Agri Daily Humidity, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily_humidity where agri_daily_humidity_id = $ehuid"));
		$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
		$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
							$pr = "";
							$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($row[provinces])");

							while($crow = mysqli_fetch_assoc($cquery))
							{
								$pr = $pr." ".$crow['Provinces'];
							}		
		?>
			<script>
				$("#hissue<?php echo $ehuctr;?>").html('<?php echo $aginfo['date_issue'];?>');
				$("#hstatus<?php echo $ehuctr;?>").html('<?php echo $statuss['status'];?>');
				$("#hmin<?php echo $ehuctr;?>").html('<?php echo $row['humidity_min'];?>');
				$("#hmax<?php echo $ehuctr;?>").html('<?php echo $row['humidity_max'];?>');
				$("#hloc<?php echo $ehuctr;?>").html('<?php echo $pr;?>');
			</script>
		<?php
}
if(isset($_REQUEST['deleteahum']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('agri_daily_humidity',['isdeleted'=>1],"agri_daily_humidity_id=$deleteahum");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Humidity Information deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Humidity Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $deleteahumcount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(!empty($_POST['humbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_humidity',['isdeleted'=>1],"agri_daily_humidity_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_humidity($humstatus,$humissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_POST['humbatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_humidity',['status'=>1],"agri_daily_humidity_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_humidity($humstatus,$humissue,0);?>
			</div>
		</div>
	<?php
	
}
if(!empty($_POST['humbatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_humidity',['status'=>2],"agri_daily_humidity_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_humidity($humstatus,$humissue,0);?>
			</div>
		</div>
	<?php

}
if(!empty($_REQUEST['agrileafui']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$level = $agrileafui
	
	?>
	
		<h2>DAILY FARM WEATHER LEAF WETNESS</H2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newagrileafform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>DATE ISSUE:</label>			
										<Select class = "form-control" name = "leafissue" id = "leafissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "" hidden "Selected"></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "leafstatus" id = "leafstatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "" hidden "Selected"></option>
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
						
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MIN LEAF WETNESS:</label>
										<input type="number" name="leafmin" class = "form-control" data-validation="required" data-validation-error-msg="Enter MIN RAIN FALL" value = "<?php echo $rainfmin;?>">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MAX LEAF WETNESS:</label>
										<input type="number" name="leafmax" class = "form-control" data-validation="required" data-validation-error-msg="Enter MAX RAIN FALL" value = "<?php echo $rainfmax;?>">				 
									</div>
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>REGIONS:</label>
											<?PHP
											
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											
											<select name = "leaflocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select REGIONS">
															<option value = '' hidden "Selected"></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#locationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
							</DIV>
								<div id = "locationui"></div>
								<div class = "row">
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
										form:'#newagrileafform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#newagrileafform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $('#newagrileafform').serializeArray();									 
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
												alert("Select at least 1 Province")
											}
								
										
											return false; // Will stop the submission of the form
											},
										});
									}
								);											
							</script>
				  
				
			</div>		
			
		</div>
		<div class="box">
			<div class="box-body">
				<form id = "browseleafform" method = "POST">
					<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "bleafstatus" id = "bleafstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '2' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DATE ISSUE:</label>			
									<Select class = "form-control" name = "bleafissue" id = "bleafissue" data-validation="required"
														data-validation-error-msg="Select Status">
										<option value = "all" "Selected">ALL</option>
														<?php
										$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
										while($prow = mysqli_fetch_assoc($pmquery))
										{
										?>
											<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
										<?php
										}
										?>
									</select>			
								</div>
							
							</div>	
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "pbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "pprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "agrileaflist" style = "overflow:auto;">
		</div>
		<script>
		$("#pbrowse").click(
			function()
			{
				$.validate({
				form:'#browseleafform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browseleafform').serializeArray();
				$("#agrileaflist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#agrileaflist").html(data);														
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
if(isset($_POST['leaflocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
		$provval = trim($provval,",");
	

	
		$save = insert('agri_daily_leaf',[
		'leaf_min'=>$leafmin,
		'leaf_max'=>$leafmax,
		'provinces'=>$provval,
		'region_id'=>$leaflocation,
		'status'=>$leafstatus,
		'agri_daily_id'=>$leafissue,
		'added_by'=>$user,
		'isdeleted'=>0]);
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Agri Leaf Wetness Information Added","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Agri Daily Leaf Wetness Information, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
}
if(!empty($_REQUEST['bleafstatus']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
		<div class="box">
			<div class="box-body">
			<?php  agri_daily_leaf($bleafstatus,$bleafissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_REQUEST['editleafid']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$level = $editleafid;
	$row = mysqli_fetch_assoc(mysqli_query($con,"select * from agri_daily_leaf where agri_daily_leaf_id = $editleafid"));
	?>
		<script>
												$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:'<?php echo $row['region_id'];?>',
																progval:'<?php echo $row['provinces'];?>'
															},
															function(data) {
																$('#locationui').html(data);
															});
		</script>
		
		<h2>EDIT LEAF WETNESS INFORMATION</H2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "updateagrileafform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>DATE ISSUE:</label>	
										<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
										?>
										<input type = "hidden" value = "<?php echo $editleafid;?>" name = "eleafid">
										<input type = "hidden" value = "<?php echo $editleafctr;?>" name = "eleafctr">
										<Select class = "form-control" name = "eleafissue" id = "eleafissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "<?php echo $irow['agri_daily_id'];?>" hidden "Selected"><?php echo $irow['date_issue'];?></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
										?>
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "eleafstatus" id = "ehustatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "<?php echo $irow['status_id'];?>" hidden "Selected"><?php echo $irow['status'];?></option>
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
						
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MIN LEAF WETNESS:</label>
										<input type="number" name="eleafmin" class = "form-control" data-validation="required" data-validation-error-msg="Enter MIN RAIN FALL" value = "<?php echo $row['humidity_min'];?>">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">MAX LEAF WETNESS:</label>
										<input type="number" name="eleafmax" class = "form-control" data-validation="required" data-validation-error-msg="Enter MAX RAIN FALL" value = "<?php echo $row['humidity_max'];?>">				 
									</div>
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>REGIONS:</label>
											<?PHP
											$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_regions where region_id = $row[region_id]"));
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											
											<select name = "eleaflocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select REGIONS">
															<option value = '<?php echo $irow['region_id'];?>' hidden "Selected"><?php echo $irow['description'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#locationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
							</DIV>
								<div id = "locationui"></div>
								<div class = "row">
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
										form:'#updateagrileafform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#updateagrileafform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $('#updateagrileafform').serializeArray();									 
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
												alert("Select at least 1 Province")
											}
								
										
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
if(isset($_POST['eleaflocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
		$provval = trim($provval,",");

		$save = update('agri_daily_leaf',[
		'leaf_min'=>$eleafmin,
		'leaf_max'=>$eleafmax,
		'provinces'=>$provval,
		'region_id'=>$eleaflocation,
		'status'=>$eleafstatus,
		'region_id'=>$eleaflocation,
		'agri_daily_id'=>$eleafissue],"agri_daily_leaf_id = $eleafid");
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Agri Daily Leaf Wetness Information Updated","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Agri Daily Leaf Wetness Information, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily_leaf where agri_daily_leaf_id = $eleafid"));
		$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
		$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
							$pr = "";
							$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($row[provinces])");

							while($crow = mysqli_fetch_assoc($cquery))
							{
								$pr = $pr." ".$crow['Provinces'];
							}		
		?>
			<script>
				$("#leafissue<?php echo $eleafctr;?>").html('<?php echo $aginfo['date_issue'];?>');
				$("#leafstatus<?php echo $eleafctr;?>").html('<?php echo $statuss['status'];?>');
				$("#leafmin<?php echo $eleafctr;?>").html('<?php echo $row['leaf_min'];?>');
				$("#leafmax<?php echo $eleafctr;?>").html('<?php echo $row['leaf_max'];?>');
				$("#leafloc<?php echo $eleafctr;?>").html('<?php echo $pr;?>');
			</script>
		<?php
}
if(isset($_REQUEST['deleteleaf']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('agri_daily_leaf',['isdeleted'=>1],"agri_daily_leaf_id=$deleteleaf");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Leaf Wetness Information deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Leaf Wetness Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $deleteleafcount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(!empty($_POST['leafbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_leaf',['isdeleted'=>1],"agri_daily_leaf_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_leaf($leafstatus,$leafissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_POST['leafbatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_leaf',['status'=>1],"agri_daily_leaf_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_leaf($leafstatus,$leafissue,0);?>
			</div>
		</div>
	<?php
	
}
if(!empty($_POST['leafbatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_leaf',['status'=>2],"agri_daily_leaf_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_leaf($leafstatus,$leafissue,0);?>
			</div>
		</div>
	<?php

}
if(!empty($_REQUEST['agrisoilui']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$level = $agrisoilui
	
	?>
	
		<h2>DAILY FARM WEATHER SOIL CONDITION</H2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newagrisoilform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>DATE ISSUE:</label>			
										<Select class = "form-control" name = "soilissue" id = "soilissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "" hidden "Selected"></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "soilstatus" id = "soilstatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "" hidden "Selected"></option>
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
						
								<div class="col-md-4">
									
									<div class="form-group">
										<label>SOIL CONDITION:</label>			
										<Select class = "form-control" name = "soilcon" id = "soilcon" data-validation="required"
															data-validation-error-msg="Select SOIL CONDITION">
											<option value = "" hidden "Selected"></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from lup_soil_wetness where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['soil_wetness_id'];?>"><?php echo $prow['description'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
									
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>REGIONS:</label>
											<?PHP
											
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											
											<select name = "soillocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select REGIONS">
															<option value = '' hidden "Selected"></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#locationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
							</DIV>
								<div id = "locationui"></div>
								<div class = "row">
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
										form:'#newagrisoilform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#newagrisoilform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $('#newagrisoilform').serializeArray();									 
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
												alert("Select at least 1 Province")
											}
								
										
											return false; // Will stop the submission of the form
											},
										});
									}
								);											
							</script>
				  
				
			</div>		
			
		</div>
		<div class="box">
			<div class="box-body">
				<form id = "browsesoilform" method = "POST">
					<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "bsoilstatus" id = "bsoilstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '2' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DATE ISSUE:</label>			
									<Select class = "form-control" name = "bsoilissue" id = "bsoilissue" data-validation="required"
														data-validation-error-msg="Select Status">
										<option value = "all" "Selected">ALL</option>
														<?php
										$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
										while($prow = mysqli_fetch_assoc($pmquery))
										{
										?>
											<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
										<?php
										}
										?>
									</select>			
								</div>
							
							</div>	
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "pbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "pprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "agrisoillist" style = "overflow:auto;">
		</div>
		<script>
		$("#pbrowse").click(
			function()
			{
				$.validate({
				form:'#browsesoilform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browsesoilform').serializeArray();
				$("#agrisoillist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#agrisoillist").html(data);														
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
if(isset($_POST['soillocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
		$provval = trim($provval,",");
	

	
		$save = insert('agri_daily_soil_condition',[
		'soil_condition'=>$soilcon,
		'provinces'=>$provval,
		'region_id'=>$soillocation,
		'status'=>$soilstatus,
		'agri_daily_id'=>$soilissue,
		'added_by'=>$user,
		'isdeleted'=>0]);
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Agri Soil Condition Information Added","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Agri Daily Soil Condition Information, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
}
if(!empty($_REQUEST['bsoilstatus']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
		<div class="box">
			<div class="box-body">
			<?php  agri_daily_soil($bsoilstatus,$bsoilissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_REQUEST['editsoilid']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$level = $editsoilid;
	$row = mysqli_fetch_assoc(mysqli_query($con,"select * from agri_daily_soil_condition where agri_daily_soil_id = $editsoilid"));
	?>
		<script>
												$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:'<?php echo $row['region_id'];?>',
																progval:'<?php echo $row['provinces'];?>'
															},
															function(data) {
																$('#locationui').html(data);
															});
		</script>
		
		<h2>EDIT SOIL CONDITION INFORMATION</H2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "updateagrisoilform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>DATE ISSUE:</label>	
										<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
										?>
										<input type = "hidden" value = "<?php echo $editsoilid;?>" name = "esoilid">
										<input type = "hidden" value = "<?php echo $editsoilctr;?>" name = "esoilctr">
										<Select class = "form-control" name = "esoilissue" id = "esoilissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "<?php echo $irow['agri_daily_id'];?>" hidden "Selected"><?php echo $irow['date_issue'];?></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
										?>
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "esoilstatus" id = "esoilstatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "<?php echo $irow['status_id'];?>" hidden "Selected"><?php echo $irow['status'];?></option>
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
						
								<div class="col-md-4">
									<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_soil_wetness where soil_wetness_id = $row[soil_condition]"));
										?>
									<div class="form-group">
										<label>SOIL CONDITION:</label>			
										<Select class = "form-control" name = "esoilcon" id = "esoilcon" data-validation="required"
															data-validation-error-msg="Select SOIL CONDITION">
											<option value = "<?php echo $irow['soil_wetness_id'];?>" hidden "Selected"><?php echo $irow['description'];?></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from lup_soil_wetness where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['soil_wetness_id'];?>"><?php echo $prow['description'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
									
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>REGIONS:</label>
											<?PHP
											$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_regions where region_id = $row[region_id]"));
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											
											<select name = "esoillocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select REGIONS">
															<option value = '<?php echo $irow['region_id'];?>' hidden "Selected"><?php echo $irow['description'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#locationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
							</DIV>
								<div id = "locationui"></div>
								<div class = "row">
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
										form:'#updateagrisoilform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#updateagrisoilform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $('#updateagrisoilform').serializeArray();									 
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
												alert("Select at least 1 Province")
											}
								
										
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
if(isset($_POST['esoillocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
		$provval = trim($provval,",");

		$save = update('agri_daily_soil_condition',[
		'soil_condition'=>$esoilcon,
		'provinces'=>$provval,
		'region_id'=>$esoillocation,
		'status'=>$esoilstatus,
		'region_id'=>$esoillocation,
		'agri_daily_id'=>$esoilissue],"agri_daily_soil_id = $esoilid");
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Agri Daily Soil Condition Information Updated","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Agri Daily Soil Condition  Information, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily_soil_condition where agri_daily_soil_id = $esoilid"));
		$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
		$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
		$sw = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_soil_wetness where soil_wetness_id = $row[soil_condition]"));
							$pr = "";
							$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($row[provinces])");

							while($crow = mysqli_fetch_assoc($cquery))
							{
								$pr = $pr." ".$crow['Provinces'];
							}		
		?>
			<script>
				$("#soilissue<?php echo $esoilctr;?>").html('<?php echo $aginfo['date_issue'];?>');
				$("#soilstatus<?php echo $esoilctr;?>").html('<?php echo $statuss['status'];?>');
				$("#soilmin<?php echo $esoilctr;?>").html('<?php echo $sw['description'];?>');
				$("#soilloc<?php echo $esoilctr;?>").html('<?php echo $pr;?>');
			</script>
		<?php
}
if(isset($_REQUEST['deletesoil']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('agri_daily_soil_condition',['isdeleted'=>1],"agri_daily_soil_id=$deletesoil");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Soil Condition Information deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Soil Condition Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $deletesoilcount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(!empty($_POST['soilbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_soil_condition',['isdeleted'=>1],"agri_daily_soil_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_soil($soilstatus,$soilissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_POST['soilbatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_soil_condition',['status'=>1],"agri_daily_soil_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_soil($soilstatus,$soilissue,0);?>
			</div>
		</div>
	<?php
	
}
if(!empty($_POST['soilbatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_soil_condition',['status'=>2],"agri_daily_soil_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_soil($soilstatus,$soilissue,0);?>
			</div>
		</div>
	<?php

}
if(!empty($_REQUEST['agritempui']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$level = $agritempui
	
	?>
	
		<h2>DAILY FARM WEATHER LOW/HIGH LAND TEMPERATURE</H2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "newagritempform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>DATE ISSUE:</label>			
										<Select class = "form-control" name = "tempissue" id = "tempissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "" hidden "Selected"></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "tempstatus" id = "tempstatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "" hidden "Selected"></option>
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
						
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">LOW LAND MIN TEMPERATURE:</label>
										<input type="number" name="lltempmin" class = "form-control" data-validation="required" data-validation-error-msg="Enter LOW LAND MIN TEMPERATURE">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">LOW LAND MAX TEMPERATURE:</label>
										<input type="number" name="lltempmax" class = "form-control" data-validation="required" data-validation-error-msg="Enter LOW LAND MAX TEMPERATURE">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">HIGH LAND MIN TEMPERATURE:</label>
										<input type="number" name="hltempmin" class = "form-control" data-validation="required" data-validation-error-msg="Enter HIGH LAND MIN TEMPERATURE">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">HIGH LAND MAX TEMPERATURE:</label>
										<input type="number" name="hltempmax" class = "form-control" data-validation="required" data-validation-error-msg="Enter HIGH LAND MAX TEMPERATURE">				 
									</div>
								</div>
								
								<div class="col-md-3">		
										 <div class="form-group">
												<label>REGIONS:</label>
											<?PHP
											
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											
											<select name = "templocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select REGIONS">
															<option value = '' hidden "Selected"></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#locationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
							</DIV>
								<div id = "locationui"></div>
								<div class = "row">
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
										form:'#newagritempform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#newagritempform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $(newagritempform).serializeArray();									 
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
												alert("Select at least 1 Province")
											}
								
										
											return false; // Will stop the submission of the form
											},
										});
									}
								);											
							</script>
				  
				
			</div>		
			
		</div>
		<div class="box">
			<div class="box-body">
				<form id = "browsetempform" method = "POST">
					<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>STATUS:</label>
									<select name = "btempstatus" id = "btempstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
													<option value = '1' "Selected">PUBLISHED</option>
													<option value = '2' "Selected">UNPUBLISHED</option>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>DATE ISSUE:</label>			
									<Select class = "form-control" name = "btempissue" id = "btempissue" data-validation="required"
														data-validation-error-msg="Select Status">
										<option value = "all" "Selected">ALL</option>
														<?php
										$pmquery = mysqli_query($con,"Select * from agri_info where isdeleted = 0");
										while($prow = mysqli_fetch_assoc($pmquery))
										{
										?>
											<option value = "<?php echo $prow['agri_info_id'];?>"><?php echo $prow['date_from']." to ".$prow['date_to'];?></option>		
										<?php
										}
										?>
									</select>			
								</div>
							
							</div>	
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "pbrowse">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "pprint">PRINT</button>
									</div>	
							</div>
					</div>
				</form>
			</div>		
			
		</div>
		<div id = "agritemplist" style = "overflow:auto;">
		</div>
		<script>
		$("#pbrowse").click(
			function()
			{
				$.validate({
				form:'#browsetempform',
				validateOnBlur : false,
				errorMessagePosition : 'top',
				modules : 'security',
				onSuccess : function($form) {
				var formData = $('#browsetempform').serializeArray();
				$("#agrileaflist").html(loading);												 
					$.ajax({
					url :  'php/main.php',
					type : 'post',
					datatype : 'json',
					data : formData,		
					success : function(data) {
						$("#agritemplist").html(data);														
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
if(isset($_POST['templocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
		$provval = trim($provval,",");
	

	
		$save = insert('agri_daily_temp',[
		'lowland_temp_min'=>$lltempmin,
		'lowland_temp_max'=>$lltempmax,
		'highland_temp_min'=>$hltempmin,
		'highland_temp_max'=>$hltempmax,
		'provinces'=>$provval,
		'region_id'=>$templocation,
		'status'=>$tempstatus,
		'agri_daily_id'=>$tempissue,
		'added_by'=>$user,
		'isdeleted'=>0]);
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Agri Temperature Information Added","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Agri Daily Temperature Information, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
}
if(!empty($_REQUEST['btempstatus']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
		<div class="box">
			<div class="box-body">
			<?php  agri_daily_temp($btempstatus,$btempissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_REQUEST['edittempid']))
{
	foreach($_POST as $key=>$val) {
		${$key} =  mysqli_real_escape_string($con,trim($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$level = $edittempid;
	$row = mysqli_fetch_assoc(mysqli_query($con,"select * from agri_daily_temp where agri_daily_temp_id = $edittempid"));
	?>
		<script>
												$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:'<?php echo $row['region_id'];?>',
																progval:'<?php echo $row['provinces'];?>'
															},
															function(data) {
																$('#locationui').html(data);
															});
		</script>
		
		<h2>EDIT FARM WEATHER TEMPERATURE INFORMATION</H2>
		<div class="box">
			<div class="box-body">
				<div id = "alert"></div>
						<form id = "updateagritempform" method = "POST">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>DATE ISSUE:</label>	
										<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
										?>
										<input type = "hidden" value = "<?php echo $edittempid;?>" name = "etempid">
										<input type = "hidden" value = "<?php echo $edittempctr;?>" name = "etempctr">
										<Select class = "form-control" name = "etempissue" id = "etempissue" data-validation="required"
															data-validation-error-msg="Select Date Issue">
											<option value = "<?php echo $irow['agri_daily_id'];?>" hidden "Selected"><?php echo $irow['date_issue'];?></option>
											<?php
											$pmquery = mysqli_query($con,"Select * from agri_daily where isdeleted = 0");
											while($prow = mysqli_fetch_assoc($pmquery))
											{
											?>
												<option value = "<?php echo $prow['agri_daily_id'];?>"><?php echo $prow['date_issue'];?></option>		
											<?php
											}
											?>
										</select>			
									</div>
							
								</div>
								<div class="col-md-4">
									<?php
										$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
										?>
									<div class="form-group">
										<label>STATUS:</label>			
										<Select class = "form-control" name = "etempstatus" id = "etempstatus" data-validation="required"
															data-validation-error-msg="Select Status">
											<option value = "<?php echo $irow['status_id'];?>" hidden "Selected"><?php echo $irow['status'];?></option>
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
						
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">LOW LAND MIN TEMPERATURE:</label>
										<input type="number" name="elltempmin" class = "form-control" data-validation="required" data-validation-error-msg="Enter LOW LAND MIN TEMPERATURE" value = "<?php echo $row['lowland_temp_min'];?>">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">LOW LAND MAX TEMPERATURE:</label>
										<input type="number" name="elltempmax" class = "form-control" data-validation="required" data-validation-error-msg="Enter LOW LAND MAX TEMPERATURE" value = "<?php echo $row['lowland_temp_max'];?>">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">HIGH LAND MIN TEMPERATURE:</label>
										<input type="number" name="ehltempmin" class = "form-control" data-validation="required" data-validation-error-msg="Enter HIGH LAND MIN TEMPERATURE" value = "<?php echo $row['highland_temp_min'];?>">				 
									</div>
								</div>
								<div class="col-md-3">	
									 <div class="form-group">
										<label for="lname">HIGH LAND MAX TEMPERATURE:</label>
										<input type="number" name="ehltempmax" class = "form-control" data-validation="required" data-validation-error-msg="Enter HIGH LAND MAX TEMPERATURE" value = "<?php echo $row['highland_temp_max'];?>">				 
									</div>
								</div>
								<div class="col-md-3">		
										 <div class="form-group">
												<label>REGIONS:</label>
											<?PHP
											$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_regions where region_id = $row[region_id]"));
											$pquery = mysqli_query($con,"Select * from lup_regions where isdeleted = 0");
											?>
											
											<select name = "etemplocation" id = "plocation" class="form-control"  data-validation="required" data-validation-error-msg="Select REGIONS">
															<option value = '<?php echo $irow['region_id'];?>' hidden "Selected"><?php echo $irow['description'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['region_id'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											<script>
												$("#plocation").change(
													function(e)
													{
														e.preventDefault();	
														$.post( 
															'php/main.php',
															{
																dailyagriprovincelist:$("#plocation").val()
															},
															function(data) {
																$('#locationui').html(data);
															});
															
													}
												);
											</script>
										</div>		
								</div>
							</DIV>
								<div id = "locationui"></div>
								<div class = "row">
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
										form:'#updateagritempform',
										validateOnBlur : false,
										errorMessagePosition : 'top',
										modules : 'security',
										onSuccess : function($form) {
											
											var check = $('#updateagritempform').find('input[type=checkbox]:checked').length;
							
											if(check != 0)
											{
												var formData = $('#updateagritempform').serializeArray();									 
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
												alert("Select at least 1 Province")
											}
								
										
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
if(isset($_POST['eleaflocation']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,$val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	
		$provval = "";
		$provs = $_POST['progprovince'];
		foreach ($provs as $id => $val) {
			if(!empty($id))
				$provval = $provval.",".$id;
		}
		$provval = trim($provval,",");

		$save = update('agri_daily_leaf',[
		'lowland_temp_min'=>$elltempmin,
		'lowland_temp_max'=>$elltempmax,
		'highland_temp_min'=>$ehltempmin,
		'highland_temp_max'=>$ehltempmax,
		'provinces'=>$provval,
		'region_id'=>$etemplocation,
		'status'=>$etempstatus,
		'region_id'=>$etemplocation,
		'agri_daily_id'=>$etempissue],"agri_daily_temp_id = $etempid");
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Agri Daily Temperature Information Updated","#alert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Agri Daily Temperature Information, Contact the System Administrator", "#alert");
			</script>
		<?php
		}
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily_leaf where agri_daily_leaf_id = $eleafid"));
		$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
		$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
							$pr = "";
							$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($row[provinces])");

							while($crow = mysqli_fetch_assoc($cquery))
							{
								$pr = $pr." ".$crow['Provinces'];
							}		
		?>
			<script>
				$("#tempissue<?php echo $eleafctr;?>").html('<?php echo $aginfo['date_issue'];?>');
				$("#tempstatus<?php echo $eleafctr;?>").html('<?php echo $statuss['status'];?>');
				$("#lltempmin<?php echo $eleafctr;?>").html('<?php echo $row['lowland_temp_min'];?>');
				$("#lltempmax<?php echo $eleafctr;?>").html('<?php echo $row['lowland_temp_max'];?>');
				$("#hltempmin<?php echo $eleafctr;?>").html('<?php echo $row['highland_temp_min'];?>');
				$("#hltempmax<?php echo $eleafctr;?>").html('<?php echo $row['highland_temp_max'];?>');
				$("#temploc<?php echo $eleafctr;?>").html('<?php echo $pr;?>');
			</script>
		<?php
}
if(isset($_REQUEST['deletetemp']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('agri_daily_temp',['isdeleted'=>1],"agri_daily_temp_id=$deletetemp");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Temperature Information deleted","#alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Temperature Information, contact the system administrator","#alert");
			</script>
		<?php
	}
	?>
		<script>
			$("#controlui<?php echo $deletetempcount;?>").html('RECORD DELETED!');
		</script>
	<?php
}
if(!empty($_POST['tempbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_temp',['isdeleted'=>1],"agri_daily_temp_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_temp($tempstatus,$tempissue,0);?>
			</div>
		</div>
	<?php
}
if(!empty($_POST['leafbatchpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
	if(isset($_POST['select']))
	{
		
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_temp',['status'=>1],"agri_daily_temp_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_temp($tempstatus,$tempissue,0);?>
			</div>
		</div>
	<?php
	
}
if(!empty($_POST['leafbatchunpub']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	if(isset($_POST['select']))
	{
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('agri_daily_temp',['status'=>2],"agri_daily_temp_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php agri_daily_temp($tempstatus,$tempissue,0);?>
			</div>
		</div>
	<?php

}
?>
