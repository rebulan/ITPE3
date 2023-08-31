<?php
include('connect.php');
include("general.php");

if(isset($_POST['username']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
			global $con;
			$usercount = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_user where user_username = '$username'"));
			
			if(!empty($usercount))
			{
				//$password = decrypt($encrypt_val,$usercount['password']);
				$pass = md5(sha1($password));
				
				//echo $depass."eto";
				//echo $password;
				if($pass == $usercount['user_password'])
				{
							
							$_SESSION['bvr'] = $usercount['user_username'];
							//modlog("$usercount[employee_no] has logged in to the system",0);
								
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
							toastr.error("Invalid User Name or Password");																			
						</script>
				<?php
				}
					
			}
			else
			{
				?>
				<script>
						toastr.error("Invalid User Name or Password");	
																																										
				</script>
				<?php
			}
	
}
if(isset($_REQUEST['logout']))
{
	
	$isp = $_REQUEST['ispages'];
	
	if($isp == 1)
		$link = 'window.location.href = "../main.php"';
	else
		$link = 'window.location.href = "main.php"';

		$_SESSION['bvr'] = '';
		
		?>
			<script>
				<?php echo $link;?>
			</script>
		<?php
	
}
if(isset($_REQUEST['userui']))
{
	$level = $_REQUEST['userui'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>USER PROFILE</H2>
		<div class="box">
			<div class="box-body">
				<form id = "newuserform">
					<div class="row">
										<div class="col-md-3">
										  
											<label for="lname">USER ID NUMBER:</label>
											<input type = "hidden" name = "ulevel" value = "<?php echo $level;?>">
											<input type="text" name="agent_id" class="form-control" data-validation="required"
													data-validation-error-msg="Enter AGENT ID NUMBER">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-3">
											<label for="lname">FULLNAME:</label>
											<input type="text" name="fullname" class="form-control"data-validation="required"
													data-validation-error-msg="Enter Fullname">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>TEAM:</label>
												
													<Select class = "form-control" name = "salesteam" data-validation="required"
													data-validation-error-msg="Select TEAM">
														<option value = "" hidden "Selected"></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_team where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['team_id'];?>"><?php echo $prow['team_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<?php
										$level = 0;
										if($level == 1)
										{
										?>
										<div class="col-md-3">
												<div class="form-group">
													<label>BRANCH:</label>
												
													<Select class = "form-control" name = "branch" data-validation="required"
													data-validation-error-msg="Select BRANCH">
														<option value = "0" "Selected">NONE</option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_branch where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['branch_id'];?>"><?php echo $prow['branch_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<?php
										}
										else
										{
											?>
												<input type = "hidden" name = "branch" value = "<?php echo $branch;?>"> 
											<?php
										}
										
										?>
										<div class="col-md-3" style = "padding-top:25px;">
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
		$save = mysqli_query($con,"insert into se_user set agent_number = '$agent_id',branch_id = $branch,
		user_username = '$agent_id', user_password = '$pass', fullname = '$fullname',user_reset = 1,team_id = $salesteam");
		
		if($save)
		{
					?>
						<script>
							toastr.success("New User Profile Created","#useralert");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Error Creating Profile, Please Contact the system administrator","#useralert");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				toastr.error("User ID Number Already Exists");
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
				<form id = "edituserform" method = "POST">
					<div class="row">
										<div class="col-md-3">
										  
											<label for="lname">USER ID NUMBER:</label>
											<input type = "hidden" name = "usereditid" value = "<?php echo $id;?>">
											<input type = "hidden" name = "usereditlevel" value = "<?php echo $level;?>">
											<input type="text" name="agent_id_edit" class="form-control" data-validation="required"
													data-validation-error-msg="Enter USER ID NUMBER" value = "<?php echo $row['agent_number'];?>">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-3">
										  
											<label for="lname">FULLNAME:</label>
											<input type="text" name="fullname_edit" class="form-control"data-validation="required"
													data-validation-error-msg="Enter Fullname" value = "<?php echo $row['fullname'];?>">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>TEAM:</label>
													<Select class = "form-control" name = "salesteamedit" data-validation="required"
													data-validation-error-msg="Select TEAM">
													<?php
													$pm = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_team where team_id = $row[team_id]"));
													
														?>
														<option value = "<?php echo $pm['team_id'];?>" hidden "Selected"><?php echo $pm['team_name'];?></option>
														<?php
													$pmquery = mysqli_query($con,"Select * from lup_team where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['team_id'];?>"><?php echo $prow['team_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
											<div class="col-md-3" style = "display:none;">
												<div class="form-group">
													<label>BRANCH:</label>
												
													<Select class = "form-control" name = "branchedit" data-validation="required"
													data-validation-error-msg="Select BRANCH">
													<?php
													$sbranch = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
													if(!empty($sbranch))
													{
														?>
														<option value = "<?php echo $sbranch['branch_id'];?>" hidden "Selected"><?php echo $sbranch['branch'];?></option>
														<option value = "0" hidden "Selected">NONE</option>
														<?php
													}
													else
													{
														?>
														<option value = "0" hidden "Selected">NONE</option>
														<?php
													}
													
													$pmquery = mysqli_query($con,"Select * from lup_branch where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['branch_id'];?>"><?php echo $prow['branch_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
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
		$save = mysqli_query($con,"update se_user set agent_number = '$agent_id_edit',branch_id = $branchedit,
		fullname = '$fullname_edit', team_id = $salesteamedit where user_id = $usereditid");
		
		if($save)
		{
					?>
						<script>
							toastr.success("User Profile Updated");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Error Updating Profile, Please Contact the system administrator");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				toastr.error("USER ID Number Already Exists");
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
							toastr.success("User Profile Reset, The default user name and password is the Agent Number");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Unable to reset User Account, Please Contact the system administrator");
				</script>
			<?php
		}
		
}

if(isset($_REQUEST['assignmod']))
{
	$id = $_REQUEST['assignmod'];
	?>
		<div class="box">
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
											<label>  </label>
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
												<option value = "2">READ WRITE/FULL</option>
												<option value = "3">READ ONLY</option>
											</select>
											
										  </div>
									</div>
									
									<div class="col-md-5">
										<div id = "submoduleselect"></div>
									</div>
									
								</div>
								<div class = "row">
									<div class="col-md-3">
										<div class = "form-group">
											<button class = "btn btn-success" id = "assignmodule">ASSIGN</button>
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
		$resetuser = get_user_id($_SESSION['bvr']);
		$pass = md5(sha1($newpassword));
		mysqli_query($con, "Update se_user set user_username = '$newusername', user_password = '$pass', user_reset = 0 where user_id = $resetuser");
		
		//modlog("$id has changed his username and password",0);
		
		$_SESSION['reset'] = '';
		$_SESSION['bvr'] = $newusername;
		//$_SESSION['employee'] = '';
		?>
		<script>
			
			alert("New User Account Created");
			location.reload();
		</script>
		<?php
}
if(isset($_REQUEST['teamui']))
{
	$level = $_REQUEST['teamui'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>TEAM INFO</H2>
		<div class="box">
			<div class="box-body">
				<form id = "newteamform">
					<div class="row">
										
										<div class="col-md-3">
											<label for="lname">TEAM DESCRIPTION:</label>
											<input type="text" name="teamdes" class="form-control"data-validation="required"
													data-validation-error-msg="Enter TEAM DESCRIPTION">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>TEAM LEADER:</label>
												
													<Select class = "form-control" name = "teamleader" id = "teamleader" data-validation="required"
													data-validation-error-msg="Select TEAM LEADER">
														<option value = "" hidden "Selected"></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from se_user where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['user_id'];?>"><?php echo $prow['fullname'];?></option>
													
													<?php
													}
													?>
													</select>
													<script>
														$("#teamleader").select2();
													</script>
												</div>
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
														form:'#newteamform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newteamform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#teamlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
		<div class="box">
			<div class="box-body" id = "teamlist">
				<?php teams(0);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['teamdes']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_team where team_name = '$teamdes'"));
	
	if($check == 0)
	{
		$save = insert('lup_team',['team_name'=>$teamdes,'team_leader'=>$teamleader,'added_by'=>$user]);
		
		if($save)
		{
					?>
						<script>
							toastr.success("New Team Info Created");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Error Creating Team Info, Please Contact the system administrator");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				toastr.error("Team Description Already Exists");
			</script>
		<?php
	}
	teams(0);
}
if(isset($_POST['editteamid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['forecast']);
	$agent = get_agent($user);
	$ldate = date('Y-m-d h:i:s');
	
	$save = update('lup_team',[
	'team_name'=>$editeamdes,
	'team_leader'=>$editeamleader,
	'last_update'=>$user,
	'date_updated'=>$ldate],
	"team_id=$editteamid");
	
	if($save)
	{
	?>
		<script>
			toastr.success("Team info Updated");
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			toastr.error("Error Updating Team Info, Contact the System Administrator");
		</script>
	<?php
	}	
}
if(isset($_REQUEST['deleteteamid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('lup_team',['isdeleted'=>1],"team_id=$deleteteamid");
	
	if($del)
	{
		?>
			<script>
				toastr.success("Team info deleted" );
				$("#controlui<?php echo $deleteteamcount;?>").html('RECORD DELETED!');
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				toastr.error("Error Deleting Team Info, contact the system administrator" );
			</script>
		<?php
	}
	?>
	<?php
}
if(!empty($_POST['teambatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('lup_team',['isdeleted'=>1],"team_id=$id");
		}
	}
	?>
		<div class="box">
			<div class="box-body">
				<?php teams(0);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['applicationui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$dess = "";
	if(isset($_REQUEST['des']))
	{
		$dess = $_REQUEST['des'];
	}
	?>
			  <h2>
				NEW CLIENT APPLICATION
			  </h2>
		<div id = "profileui">
						<form id = "profile" method = "post">	
								<div class="alert alert-success alert-dismissible">
									
									<h4><i class="icon fa fa-info"></i> INFORMATION!</h4>
									ALL fields with asterisk are required!
								</div>
							<div class="box">
								<div class="box-body">
									<div class  ="row">	
										<div class="col-md-4">
												<div class="form-group">
														<label for="lname">TIN:</label>
														<input type="text" id="ctin" name="ctin" class="form-control" data-validation="required"
																data-validation-error-msg="TIN Field is Required">
														
												</div>
										</div>
										<div class="col-md-3">
											 <div class="form-group">
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">* BIR Office:</label>
														<select id="cbir" name = "cbir" class="form-control" data-validation="required"
															data-validation-error-msg="Select BIR Office">
														<option value = "" hidden "Selected"></option>
														<?php
														$bnquery = mysqli_query($con,"Select * from lup_bir_office where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['office_name'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										
										<div class="col-md-3">
											 <div class="form-group">
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">* TAX Type:</label>
														<select id="ctype" name = "ctax" class="form-control" data-validation="required"
															data-validation-error-msg="Select TAX Type">
														<option value = "" hidden "Selected"></option>
														<option value = "1">VAT</option>
														<option value = "2">OPT</option>
													</select>
													
											</div>
										</div>
										<div class="col-md-3">
											 <div class="form-group">
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">* Business Nature:</label>
														<select id="cbusinessnature" name = "cbusinessnature" class="form-control" data-validation="required"
															data-validation-error-msg="Select Business Nature">
														<option value = "" hidden "Selected"></option>
														<?php
														$bnquery = mysqli_query($con,"Select * from lup_business_nature where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['business_nature'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										
									</div>
								</div>
							</div>
							
							<div class="box">
								<div class="box-body">
									
									<div class="row">
										
											<div class="col-md-4">
												<div class="form-group">
														<label for="lname">BUSINESS NAME</label>
														<input type="text" id="cbusinessname" name="cbusinessname" class="form-control" data-validation="required"
																data-validation-error-msg="BUSINESS NAME Field is Required">
														
												</div>
											</div>
											
										
										<div class="col-md-7">
											<div class="form-group">
													<label for="lname">* BUSINESS ADDRESS</label>
													<input type="text" name="caddress" class="form-control" data-validation="required"
															data-validation-error-msg="ADDRESS Field is Required">
													
											</div>
										</div>
										<div class="col-md-4">
												<div class="form-group">
														<label for="lname">CONTACT PERSON</label>
														<input type="text" id="ccontact_person" name="ccontact_person" class="form-control" data-validation="required"
																data-validation-error-msg="CONTACT PERSON Field is Required">
														
												</div>
											</div>
											
									</div>
							
									<div class ="row">
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">* 1ST CONTACT NO:</label>
													<input type="text" id="ccontact" name="ccontact" class="form-control" data-validation="required"
															data-validation-error-msg="CONTACT NO Field is Required">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">2ND CONTACT NO:</label>
													<input type="text" id="ccontact2" name="ccontact2" class="form-control">
													
											</div>
										</div>
							
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">EMAIL ADDRESS:</label>
													<input type="text" id="cemail" name="cemail" class="form-control">
													
											</div>
										</div>
									</div>
								</div>
							</div>
			
							<div class  ="row">
								<div class="col-md-4">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "save">SAVE</button>
											
										  </div>
								</div>
							</div>
							
						</form>
						<script>
							
							
							/*$("#save").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);*/
							
							$.validate({
															form:'#profile',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																
																 var formData = $('#profile').serializeArray();
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
						</script>
	</div>				
	<?php
}
if(isset($_POST['cbusinessname']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);
		
		$check = mysqli_num_rows(mysqli_query($con,"Select * from client_info where business_name = '$cbusinessname' and isdeleted = 0"));
		
		if($check == 0)
		{
			$save = insert('client_info',[
			'business_name'=>$cbusinessname,
			'TIN'=>$ctin,
			'bir_office'=>$cbir,
			'address'=>$caddress,
			'tax_type'=>$ctax,
			'business_nature'=>$cbusinessnature,
			'contact_person'=>$ccontact_person,
			'contact1'=>$ccontact,
			'contact2'=>$ccontact2,
			'email_address'=>$_REQUEST['cemail'],
			'added_by'=>$user,
			'isdeleted'=>0]);
			
			if($save)
			{
			?>
				<script>
					toastr.success("New Client Profile Added" );
					$("#profile")[0].reset();
				</script>
			<?php
			}
			else
			{
			?>
				<script>
					toastr.error(" Error Saving New Client Profile, Contact the System Administrator", "#alert");
				</script>
			<?php
			}
		}
		else{
			?>
			<script>
				toastr.error("Business Name Already Exist");
			</script>
			<?php
		}
}
if(isset($_REQUEST['cprofileui']))
{
	?>
			  <h2>
				CLIENT PROFILE
			  </h2>
		<div id = "cprofileui">
							<div class="box">
								<div class="box-body">
									<div class  ="row">								
										<div class="col-md-4">
											 <div class="form-group">
													
													 <label for="age">Select Client:</label>
														<select id="selectclient" name = "selectclient" class="form-control" data-validation="required"
															data-validation-error-msg="Select Business Nature">
														<option value = "" hidden "Selected"></option>
														<?php
														$bnquery = mysqli_query($con,"Select * from client_info where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['business_name'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										<script>
											$("#selectclient").select2();
										</script>
										
										<div class="col-md-4" style = "margin-top:25px;s">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "go">PROCEED</button>
										  </div>
								</div>
									</div>
								</div>
							</div>
		</div>
		<script>
							$("#go").click(
								function(e)
								{
									e.preventDefault();
									var s = $("#selectclient").val();
									
									if(s != '')
									{
											$('#cprofileui').html(loading);	
														$.post( 
																'php/main.php',
																{
																	showprofile:s
																},
																function(data) {
																	$('#cprofileui').html(data);		
																});
									}
									else{
										toastr.error('Select Client Profile');
									}

								}
							);
		</script>
<?php
}
if(isset($_REQUEST['showprofile']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from client_info where ID = $showprofile"));
	
	$vat = "";
	if($row['tax_type'] == 1)
	{
		$vat = "VAT";
	}
	
	if($row['tax_type'] == 2)
	{
		$vat = "OPT";
	}
	
	?>
		<div id = "profileui">
						<form id = "profile" method = "post">	
							<div class="box">
								<div class="box-body">
									<div class  ="row">
										<div class="col-md-4">
												<div class="form-group">
														<label for="lname">TIN:</label>
														<input type="text" id="editctin" name="editctin" class="form-control" data-validation="required"
																data-validation-error-msg="TIN Field is Required" value = "<?php echo $row['TIN'];?>">
														
												</div>
										</div>
										<div class="col-md-3">
											 <div class="form-group">
													<?php
														$cubir = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_bir_office where ID = $row[bir_office]"));
													?>
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">* BIR Office:</label>
														<select id="editcbir" name = "editcbir" class="form-control" data-validation="required"
															data-validation-error-msg="Select BIR Office">
														<option value = "<?php echo $cubir['ID'];?>" hidden "Selected"><?php echo $cubir['office_name'];?></option>
														<?php
														$bnquery = mysqli_query($con,"Select * from lup_bir_office where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['office_name'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
													<input type = "hidden" value = "<?php echo $showprofile;?>" name = "editprofileid">
													 <label for="age">* TAX Type:</label>
													 
														<select id="ctype" name = "editctax" class="form-control" data-validation="required"
															data-validation-error-msg="Select TAX Type">
														<option value = "<?php echo $row['tax_type'];?>" hidden "Selected"><?php echo $vat;?></option>
														<option value = "1">VAT</option>
														<option value = "2">OPT</option>
													</select>
													
											</div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
											 <?php
											 $cbus = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_business_nature where ID = $row[business_nature]"));
											 ?>
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">* Business Nature:</label>
														<select id="cbusinessnature" name = "editcbusinessnature" class="form-control" data-validation="required"
															data-validation-error-msg="Select Business Nature">
														<option value = "<?php echo $cbus['ID'];?>" hidden "Selected"><?php echo $cbus['business_nature'];?></option>
														<?php
														$bnquery = mysqli_query($con,"Select * from lup_business_nature where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['business_nature'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										
									</div>
								</div>
							</div>
							
							<div class="box">
								<div class="box-body">
									
									<div class="row">
										
											<div class="col-md-4">
												<div class="form-group">
														<label for="lname">BUSINESS NAME</label>
														<input type="text" id="editcbusinessname" name="editcbusinessname" class="form-control" data-validation="required"
																data-validation-error-msg="BUSINESS NAME Field is Required" value = "<?php echo $row['business_name'];?>">
														
												</div>
											</div>
											
										
										<div class="col-md-7">
											<div class="form-group">
													<label for="lname">* BUSINESS ADDRESS</label>
													<input type="text" name="editcaddress" class="form-control" data-validation="required"
															data-validation-error-msg="ADDRESS Field is Required" value = "<?php echo $row['address'];?>">
													
											</div>
										</div>
										<div class="col-md-4">
												<div class="form-group">
														<label for="lname">CONTACT PERSON</label>
														<input type="text" id="editccontact_person" name="editccontact_person" class="form-control" data-validation="required"
																data-validation-error-msg="CONTACT PERSON Field is Required" value = "<?php echo $row['contact_person'];?>">
														
												</div>
											</div>
											
									</div>
							
									<div class ="row">
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">* 1ST CONTACT NO:</label>
													<input type="text" id="ccontact" name="editccontact" class="form-control" data-validation="required"
															data-validation-error-msg="CONTACT NO Field is Required" value = "<?php echo $row['contact1'];?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">2ND CONTACT NO:</label>
													<input type="text" id="ccontact2" name="editccontact2" class="form-control" value = "<?php echo $row['contact2'];?>">
													
											</div>
										</div>
							
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">EMAIL ADDRESS:</label>
													<input type="text" id="cemail" name="editcemail" class="form-control" value = "<?php echo $row['email_address'];?>">
													
											</div>
										</div>
									</div>
								</div>
							</div>
			
							<div class  ="row">
								<div class="col-md-4">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "save">UPDATE</button>
											<button class = "btn btn-danger btn-flat" id = "cancel">CANCEL</button>
										  </div>
								</div>
							</div>
							
						</form>
						<script>
							
							$("#cancel").click(
								function(e)
								{
									e.preventDefault();
												$('#maincontent').html(loading);	
														$.post( 
																'php/main.php',
																{
																	cprofileui:1
																},
																function(data) {
																	$('#maincontent').html(data);		
																});
									

								}
							);
							$("#save").click(
								function()
								{
								
									$.validate({
															form:'#profile',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																
																 var formData = $('#profile').serializeArray();
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
	</div>				
	<?php
}
if(isset($_POST['editcbusinessname']))
{
	foreach($_POST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);
		
		$check = mysqli_num_rows(mysqli_query($con,"Select * from client_info where business_name = '$editcbusinessname' and ID != $editprofileid and isdeleted = 0"));
		
		if($check == 0)
		{
			$save = update('client_info',[
			'business_name'=>$editcbusinessname,
			'TIN'=>$editctin,
			'bir_office'=>$editcbir,
			'address'=>$editcaddress,
			'tax_type'=>$editctax,
			'business_nature'=>$editcbusinessnature,
			'contact_person'=>$editccontact_person,
			'contact1'=>$editccontact,
			'contact2'=>$editccontact2,
			'email_address'=>$_REQUEST['editcemail']],"ID=$editprofileid");
			
			if($save)
			{
			?>
				<script>
					toastr.success("Client Info Updated" );
					//$("#profile")[0].reset();
				</script>
			<?php
			}
			else
			{
			?>
				<script>
					toastr.error(" Error Updating Client Info, Contact the System Administrator", "#alert");
				</script>
			<?php
			}
		}
		else{
			?>
			<script>
				toastr.error("Business Name Already Exist");
			</script>
			<?php
		}
}
if(isset($_REQUEST['clientass']))
{
	$level = $_REQUEST['clientass'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>CLIENT ASSIGNMENT</H2>
		<div class="box">
			<div class="box-body">
				<form id = "assignfilter" method = "POST">	
					<div class="row">
										<div class="col-md-3">
												<div class="form-group">
													<label>USERS:</label>
												
													<Select class = "form-control" name = "auser" id = "auser" data-validation="required"
													data-validation-error-msg="Select USER">
														<option value = "all" hidden "Selected">ALL</option>
													<?php
													$pmquery = mysqli_query($con,"Select * from se_user, lup_team where se_user.isdeleted = 0 
													and lup_team.team_id = se_user.team_id");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['user_id'];?>"><?php echo $prow['fullname']."-".$prow['team_name'];?></option>
													
													<?php
													}
													?>
													</select>
													<script>
														$("#auser").select2();
													</script>
												</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SHOW ASSIGNMENT</button>
										  </div>
										</div>	
					</div>
				</form>
				<div id = "useralert"></div>
			</div>
		</div>
		<script>
										$.validate({
														form:'#assignfilter',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#assignfilter').serializeArray();
															
																//var formData = new FormData($('#regform')[0]);
																$("#teamlist").html(loading); 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#teamlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
		<div class="box">
			<div class="box-body" id = "teamlist">
				<?php client_assignment('all');?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['auser']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	client_assignment($auser);
}
if(isset($_REQUEST['updateassign']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from client_info where ID = $updateassign"));
	?>
		<h2>ASSIGNMENT HISTORY - <?PHP echo $row['business_name'];?></H2>					
		<div class="box">
			<div class="box-body" id = "uteamlist">
				<?php assign_history($updateassign);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['asclient']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = mysqli_real_escape_string($con,strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);
			$us = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $asuser"));
			$tm = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_team where team_id = $us[team_id]"));
			
			$save = update('client_info',[
			'cassign'=>$asuser,
			'team_id'=>$us['team_id']],"ID=$asclient");
			
			insert('client_assignment',[
			'client_id'=>$asclient,
			'user_id'=>$asuser
			]);
			if($save)
			{
			?>
				<script>
					toastr.success("Client Assignment Updated");
					//$("#assteamform")[0].reset();
					
				</script>
			<?php
			}
			else
			{
			?>
				<script>
					toastr.error(" Error Updating Assignment, Contact the System Administrator", "#alert");
				</script>
			<?php
			}
}
if(isset($_REQUEST['birui']))
{
	$level = $_REQUEST['birui'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>BIR OFFICES</H2>
		<div class="box">
			<div class="box-body">
				<form id = "newbiroffice">
					<div class="row">
										
										<div class="col-md-3">
											<label for="lname">OFFICE DESCRIPTION:</label>
											<input type="text" name="birdes" class="form-control"data-validation="required"
													data-validation-error-msg="Enter OFFICE DESCRIPTION">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SAVE</button>
										
										  </div>
										</div>
									
							</div>
				</form>
			</div>
		</div>
		<script>
										$.validate({
														form:'#newbiroffice',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newbiroffice').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#listui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
		<div class="box">
			<div class="box-body" id = "listui">
				<?php bir(0);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['birdes']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_bir_office where office_name = '$birdes'"));
	
	if($check == 0)
	{
		$save = insert('lup_bir_office',['office_name'=>$birdes,'added_by'=>$user]);
		
		if($save)
		{
					?>
						<script>
							toastr.success("New BIR Office Added");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Error Adding BIR Office, Please Contact the system administrator");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				toastr.error("Office Description Already Exists");
			</script>
		<?php
	}
	bir(0);
}
if(isset($_POST['editbirid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$ldate = date('Y-m-d h:i:s');
	
	$save = update('lup_bir_office',[
	'office_name'=>$editbirdes],
	"ID=$editbirid");
	
	if($save)
	{
	?>
		<script>
			toastr.success("BIR Office Updated");
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			toastr.error("Error Updating BIR Office, Contact the System Administrator");
		</script>
	<?php
	}	
}
if(isset($_REQUEST['deletebirid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('lup_bir_office',['isdeleted'=>1],"ID=$deletebirid");
	
	if($del)
	{
		?>
			<script>
				toastr.success("BIR Office deleted" );
				$("#controlui<?php echo $deletebircount;?>").html('RECORD DELETED!');
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				toastr.error("Error Deleting BIR Office, contact the system administrator" );
			</script>
		<?php
	}
	?>
	<?php
}
if(!empty($_POST['birbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('lup_bir_office',['isdeleted'=>1],"ID=$id");
		}
	}
	bir(0);

}
if(isset($_REQUEST['purchasetypeui']))
{
	$level = $_REQUEST['purchasetypeui'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>PURCHASE TYPE INFO</H2>
		<div class="box">
			<div class="box-body">
				<form id = "newptype">
					<div class="row">
										<div class="col-md-3">
											<label for="lname">PURCHASE TYPE DESCRIPTION:</label>
											<input type="text" name="ptdes" class="form-control"data-validation="required"
													data-validation-error-msg="Enter PURCHASE TYPE  DESCRIPTION">
										</div>
										<div class="col-md-3">
											<label for="lname">PERCENTAGE:</label>
											<input type="number" name="ptpercent" step="0.01" class="form-control"data-validation="required"
													data-validation-error-msg="Enter PERCENTAGE">
										</div>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SAVE</button>
										
										  </div>
										</div>
									
							</div>
				</form>
			</div>
		</div>
		<script>
										$.validate({
														form:'#newptype',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newptype').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#listui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
		<div class="box">
			<div class="box-body" id = "listui">
				<?php purchasetype(0);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['ptdes']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_purchase_type where purchase_type = '$ptdes' and isdeleted = 0"));
	
	if($check == 0)
	{
		$save = insert('lup_purchase_type',['purchase_type'=>$ptdes,'percent'=>$ptpercent,'added_by'=>$user]);
		if($save)
		{
					?>
						<script>
							toastr.success("New Purchase Type Added");
						</script>
					<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Error Adding Purchase Type, Please Contact the system administrator");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				toastr.error("Purchase Type Description Already Exists");
			</script>
		<?php
	}
	purchasetype(0);
}
if(isset($_POST['editptid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$ldate = date('Y-m-d h:i:s');
	
	$save = update('lup_purchase_type',[
	'purchase_type'=>$editptdes,
	'percent'=>$editptpercent,
	'last_update'=>$ldate,
	'update_by'=>$user],
	"ID=$editptid");
	
	if($save)
	{
	?>
		<script>
			toastr.success("Purchase Type Updated");
			$("#lu<?php echo $editptcount;?>").html('<?php echo $ldate;?>');
			$("#upby<?php echo $editptcount;?>").html('<?php echo $agent;?>');
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			toastr.error("Error Updating Purchase Type, Contact the System Administrator");
		</script>
	<?php
	}	
}
if(isset($_REQUEST['deleteptid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('lup_purchase_type',['isdeleted'=>1],"ID=$deleteptid");
	
	if($del)
	{
		?>
			<script>
				toastr.success("Purchase Type deleted" );
				$("#controlui<?php echo $deleteptcount;?>").html('RECORD DELETED!');
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				toastr.error("Error Deleting Purchase Type, contact the system administrator" );
			</script>
		<?php
	}
	?>
	<?php
}
if(!empty($_POST['ptbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('lup_purchase_type',['isdeleted'=>1],"ID=$id");
		}
	}
	purchasetype(0);
}
if(isset($_REQUEST['salestypeui']))
{
	$level = $_REQUEST['salestypeui'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>SALES TYPE INFO</H2>
		<div class="box">
			<div class="box-body">
				<form id = "newstype">
					<div class="row">
										
										<div class="col-md-3">
											<label for="lname">SALES TYPE DESCRIPTION:</label>
											<input type="text" name="stdes"  class="form-control"data-validation="required"
													data-validation-error-msg="Enter SALES TYPE DESCRIPTION">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-3">
											<label for="lname">PERCENTAGE:</label>
											<input type="number" name="stpercent" step="0.01" class="form-control"data-validation="required"
													data-validation-error-msg="Enter PERCENTAGE">
										</div>
										
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SAVE</button>
										
										  </div>
										</div>
									
							</div>
				</form>
			</div>
		</div>
		<script>
										$.validate({
														form:'#newstype',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newstype').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#listui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
		<div class="box">
			<div class="box-body" id = "listui">
				<?php salestype(0);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['stdes']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_sales_type where sales_type = '$stdes' and isdeleted = 0"));
	
	if($check == 0)
	{
		$save = insert('lup_sales_type',['sales_type'=>$stdes,'percent'=>$stpercent,'added_by'=>$user]);
		if($save)
		{
					?>
						<script>
							toastr.success("New Sales Type Added");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Error Adding Sales Type, Please Contact the system administrator");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				toastr.error("Sales Type Description Already Exists");
			</script>
		<?php
	}
	salestype(0);
}
if(isset($_POST['editstid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$ldate = date('Y-m-d h:i:s');
	
	$save = update('lup_sales_type',[
	'sales_type'=>$editstdes,
	'percent'=>$editstpercent,
	'last_update'=>$ldate,
	'update_by'=>$user],
	"ID=$editstid");
	
	if($save)
	{
	?>
		<script>
			toastr.success("Sales Type Updated");
			$("#lu<?php echo $editstcount;?>").html('<?php echo $ldate;?>');
			$("#upby<?php echo $editstcount;?>").html('<?php echo $agent;?>');
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			toastr.error("Error Updating Sales Type, Contact the System Administrator");
		</script>
	<?php
	}	
}
if(isset($_REQUEST['deletestid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('lup_sales_type',['isdeleted'=>1],"ID=$deletestid");
	
	if($del)
	{
		?>
			<script>
				toastr.success("Sales Type deleted" );
				$("#controlui<?php echo $deletestcount;?>").html('RECORD DELETED!');
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				toastr.error("Error Deleting Sales Type, contact the system administrator" );
			</script>
		<?php
	}
	?>
	<?php
}
if(!empty($_POST['stbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('lup_sales_type',['isdeleted'=>1],"ID=$id");
		}
	}
	salestype(0);
}
if(isset($_REQUEST['enui']))
{
	$level = $_REQUEST['enui'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>ENGAGEMENT INFO</H2>
		<div class="box">
			<div class="box-body">
				<form id = "newen">
					<div class="row">
										
										<div class="col-md-3">
											<label for="lname">ENGAGEMENT DESCRIPTION:</label>
											<input type="text" name="endes"  class="form-control"data-validation="required"
													data-validation-error-msg="Enter ENGAGEMENT DESCRIPTION">
										</div>
										
								
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SAVE</button>
										
										  </div>
										</div>
									
							</div>
				</form>
			</div>
		</div>
							<script>
										$.validate({
														form:'#newen',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newen').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
																			success : function(data) {
																				$("#listui").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
		<div class="box">
			<div class="box-body" id = "listui">
				<?php engagement(0);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['endes']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_engagements where engagement = '$endes' and isdeleted = 0"));
	
	if($check == 0)
	{
		$save = insert('lup_engagements',['engagement'=>$endes,'added_by'=>$user]);
		if($save)
		{
					?>
						<script>
							toastr.success("New Engagement Info Added");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Error Adding Engagement Info, Please Contact the system administrator");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				toastr.error("Engagement Info Description Already Exists");
			</script>
		<?php
	}
	engagement(0);
}
if(isset($_POST['editenid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$ldate = date('Y-m-d h:i:s');
	
	$save = update('lup_engagements',[
	'engagement'=>$editendes,
	'last_update'=>$ldate,
	'update_by'=>$user],
	"ID=$editenid");
	
	if($save)
	{
	?>
		<script>
			toastr.success("Engagement Info Updated");
			$("#lu<?php echo $editencount;?>").html('<?php echo $ldate;?>');
			$("#upby<?php echo $editencount;?>").html('<?php echo $agent;?>');
		</script>
	<?php
	}
	else
	{
	?>
		<script>
			toastr.error("Error Updating Engagement Info, Contact the System Administrator");
		</script>
	<?php
	}	
}
if(isset($_REQUEST['deleteenid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('lup_engagements',['isdeleted'=>1],"ID=$deleteenid");
	
	if($del)
	{
		//echo "AAAA";
		?>
			<script>
				toastr.success("Engagement Info deleted" );
				$("#controlui<?php echo $deleteencount;?>").html('RECORD DELETED!');
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				toastr.error("Error Deleting Engagement Info, contact the system administrator" );
			</script>
		<?php
	}
	?>
	<?php
}
if(!empty($_POST['enbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('lup_engagements',['isdeleted'=>1],"ID=$id");
		}
	}
	engagement(0);
}
if(isset($_REQUEST['cenui']))
{
	$level = $_REQUEST['cenui'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>CLIENT ENGAGEMENT</H2>
		<div class="box">
			<div class="box-body">
				<form id = "newen">
					<div class="row">
										
										<div class="col-md-3">
											 <div class="form-group">
													
													 <label for="age">Select Client:</label>
														<select  name = "ceclient" id = "ceclient" class="form-control" data-validation="required"
															data-validation-error-msg="Select Client">
														<option value = "" hidden "Selected"></option>
														<?php
														$bnquery = mysqli_query($con,"Select * from client_info where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['business_name'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										<script>
											$("#ceclient").select2();
										</script>
													
										<div class="col-md-3">
											 <div class="form-group">
													
													 <label for="age">Select Engagement:</label>
														<select name = "ceen" id = "ceen" class="form-control" data-validation="required"
															data-validation-error-msg="Select Engagement">
														<option value = "" hidden "Selected"></option>
														<?php
														$bnquery = mysqli_query($con,"Select * from lup_engagements where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['engagement'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										<script>
											$("#ceen").select2();
										</script>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">SAVE</button>
										
										  </div>
										</div>
									
							</div>
				</form>
			</div>
		</div>
							<script>
										$.validate({
														form:'#newen',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newen').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
																			success : function(data) {
																				$("#listui").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
		<div class="box">
			<div class="box-body">
				<form id = "filteren">
					<div class="row">
										
										<div class="col-md-3">
											 <div class="form-group">
													
													 <label for="age">Client:</label>
														<select  name = "fceclient" id = "fceclient" class="form-control" data-validation="required"
															data-validation-error-msg="Select Client">
														<option value = "all" hidden "Selected">ALL</option>
														<?php
														$bnquery = mysqli_query($con,"Select * from client_info where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['business_name'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										<script>
											$("#ceclient").select2();
										</script>
													
										<div class="col-md-3">
											 <div class="form-group">
													
													 <label for="age">Engagement:</label>
														<select name = "fceen" id = "fceen" class="form-control" data-validation="required"
															data-validation-error-msg="Select Engagement">
														<option value = "all" hidden "Selected">ALL</option>
														<?php
														$bnquery = mysqli_query($con,"Select * from lup_engagements where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['engagement'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										<script>
											$("#ceen").select2();
										</script>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-primary btn-flat" id = "fil">FILTER</button>
											<button class = "btn btn-warning btn-flat" id = "print">PRINT</button>
										
										  </div>
										</div>
							</div>
				</form>
			</div>
		</div>
				
							<script>
								$("#fil").click(
									function()
									{
										$.validate({
														form:'#filteren',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#filteren').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
																			success : function(data) {
																				$("#listui").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
									}
								);
								
								$("#print").click(
									function()
									{
										$.validate({
														form:'#filteren',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
																		$.post( 
																				'php/main.php',
																				{
																					pceclient:$("#fceclient").val(),
																					pceen:$("#fceen").val()
																					
																				},
																				function(data) {
																					$('#click').html(data);	
																				});

														  return false; // Will stop the submission of the form
														},
													});
									}
								);
								
										
													
							</script>					
		<div class="box">
			<div class="box-body" id = "listui">
				<?php cengagement('','',0);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['fceen']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	cengagement($fceclient,$fceen,0);
}
if(isset($_REQUEST['pceclient']))
{
		
	?>
	<div id = "printt">
	<?PHP
	foreach($_REQUEST as $key=>$val) {
			${$key} = trim(strtoupper($val));
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
			<p style = "text-align:center;?>"><img src = "images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php //echo get_company();?></h3>
			<h4 style = "text-align:center">CLIENT ENGAGEMENT REPORT</h4>
			
			<?php
				cengagement($pceclient,$pceen,1);
				
				$user = get_user_id($_SESSION['bvr']);
				$agent = get_agent($user);
				
			?>
			printed by:<br><br>
			<p style = "border-bottom:1px solid #000;text-align:center;width:200px;font-weight:bold;"><?php echo $agent;?></p>
			
			
		</div>
		<script>
			$('#printt').printThis(
				{
														base: "true",
														importCSS: true,
														importStyle: true
				}
			);
			$("#click").html('');
		</script>
	<?php
}

if(isset($_POST['ceen']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from client_engagements where engagement_id = '$ceen' and client_id = $ceclient and isdeleted = 0"));
	
	if($check == 0)
	{
		$save = insert('client_engagements',['engagement_id'=>$ceen,'client_id'=>$ceclient,'added_by'=>$user]);
		if($save)
		{
					?>
						<script>
							toastr.success("New Client Engagement Added");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					toastr.error("Error Adding Client Engagement , Please Contact the system administrator");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				toastr.error("Engagement Info Already Exists on selected client");
			</script>
		<?php
	}
	cengagement('','',0);
}
if(isset($_REQUEST['deletecenid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$del = update('client_engagements',['isdeleted'=>1],"ID=$deletecenid");
	
	if($del)
	{
		//echo "AAAA";
		?>
			<script>
				toastr.success("Client Engagement Deleted" );
				$("#controlui<?php echo $deletecencount;?>").html('RECORD DELETED!');
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				toastr.error("Error Deleting Client Engagement, contact the system administrator" );
			</script>
		<?php
	}
	?>
	<?php
}
if(!empty($_POST['cenbatchdelete']))
{
	if(isset($_POST['select']))
	{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$delete = $_POST['select'];
		foreach ($delete as $id => $val) {
			$del = update('client_engagements',['isdeleted'=>1],"ID=$id");
		}
	}
	cengagement('','',0);
}
if(isset($_REQUEST['clientlistui']))
{
	$level = $_REQUEST['clientlistui'];
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	
	?>
		<h2>CLIENT LIST</H2>
		<div class="box">
			<div class="box-body">
				<form id = "filterclient">
					<div class="row">
										
										<div class="col-md-3">
											 <div class="form-group">
													
													 <label for="age">BIR OFFICE:</label>
														<select  name = "clbir" id = "clbir" class="form-control" data-validation="required"
															data-validation-error-msg="Select BIR OFFICE">
														<option value = "all" "Selected">ALL</option>
														<?php
														$bnquery = mysqli_query($con,"Select * from lup_bir_office where isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
															<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['office_name'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										<script>
											$("#clbir").select2();
										</script>
													
										<div class="col-md-3">
											 <div class="form-group">
													
													 <label for="age">BUSINESS UNIT:</label>
														<select name = "clbunit" id = "clbunit" class="form-control" data-validation="required"
															data-validation-error-msg="Select BUSINESS UNIT">
														<option value = "all" "Selected">ALL</option>
														
													</select>
													
											</div>
										</div>
										<script>
											$("#clbunit").select2();
										</script>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-primary btn-flat" id = "fil">FILTER</button>
											<button class = "btn btn-warning btn-flat" id = "print">PRINT</button>
										
										  </div>
										</div>
									
							</div>
				</form>
			</div>
		</div>
							<script>
										
								$("#fil").click(
									function()
									{
										$.validate({
														form:'#filterclient',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#filterclient').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
																			success : function(data) {
																				$("#listui").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
									}
								);
								
								$("#print").click(
									function()
									{
										$.validate({
														form:'#filterclient',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
																		$.post( 
																				'php/main.php',
																				{
																					pclbir:$("#clbir").val(),
																					pclbunit:$("#clbunit").val()
																					
																				},
																				function(data) {
																					$('#click').html(data);	
																				});

														  return false; // Will stop the submission of the form
														},
													});
									}
								);				
							</script>
		<div id = "listui"> </div>
	<?php
}
if(isset($_POST['clbir']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	?>
	<div class="box">
			<div class="box-body">
				<?php clientlist($clbir,$clbunit,0);?>
			</div>
	</div>
	<?php
	
}
if(isset($_REQUEST['pclbir']))
{	
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	?>
	<div id = "printt">
	<?PHP
	foreach($_REQUEST as $key=>$val) {
			${$key} = trim(strtoupper($val));
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$pbir = "ALL";
		$pbunit = "ALL";
		if($pclbir != 'ALL')
		{
			$brow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_bir_office where ID = $pbir"));
			$pbir = $brow['office_name'];
		}
		if($pclbunit != 'ALL')
		{
			//$string = $string." and engagement_id = $en";
		}
	?>
			<p style = "text-align:center;?>"><img src = "images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php //echo get_company();?></h3>
			<h4 style = "text-align:center">CLIENT LIST REPORT</h4>
			<table class = "table table-bordered table-hover table-sm">
				<tr>
					<td>BIR OFFICE</td>
					<td><?php echo $pbir;?></td>
					<td>BUSINESS UNIT</td>
					<td><?php echo $pbunit;?></td>
				</tr>
			</table>
			<?php
				clientlist($pclbir,$pclbunit,1);
				
				$user = get_user_id($_SESSION['bvr']);
				$agent = get_agent($user);
			?>
			printed by:<br><br>
			<p style = "border-bottom:1px solid #000;text-align:center;width:200px;font-weight:bold;"><?php echo $agent;?></p>
			
			
		</div>
		<script>
			$('#printt').printThis(
				{
														base: "true",
														importCSS: true,
														importStyle: true
				}
			);
			$("#click").html('');
		</script>
	<?php
}
if(isset($_REQUEST['vatui']))
{
	$user = get_user_id($_SESSION['bvr']);
	$agent = get_agent($user);
	?>
			  <h2>
				SALES RECORDING
			  </h2>
		<div id = "srecordui">
							<div class="box">
								<div class="box-body">
									<div class  ="row">								
										<div class="col-md-3">
											 <div class="form-group">
													
													 <label for="age">Select Client:</label>
														<select id="selectclient" name = "selectclient" class="form-control" data-validation="required"
															data-validation-error-msg="Select Client">
														<option value = "" hidden "Selected"></option>
														<?php
														$bnquery = mysqli_query($con,"Select * from client_info where cassign = $user and isdeleted = 0");
														while($bnrow = mysqli_fetch_assoc($bnquery))
														{
														?>
														<option value = "<?php echo $bnrow['ID'];?>"><?php echo $bnrow['business_name'];?></option>
														<?php
														}
														?>
													</select>
													
											</div>
										</div>
										<script>
											$("#selectclient").select2();
										</script>
										
										<div class="col-md-4" style = "margin-top:25px;s">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "go">PROCEED</button>
										  </div>
										</div>
									</div>
								</div>
							</div>
		</div>
		<script>
							$("#go").click(
								function(e)
								{
									e.preventDefault();
									var s = $("#selectclient").val();
									
									if(s != '')
									{
											$('#srecordui').html(loading);	
														$.post( 
																'php/main.php',
																{
																	showsalesui:s
																},
																function(data) {
																	$('#srecordui').html(data);		
																});
									}
									else{
										toastr.error('Select Client');
									}

								}
							);
		</script>
<?php
}
if(isset($_REQUEST['showsalesui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from client_info where ID = $showsalesui"));
	
	?>
	<div class="box">
		<div class="box-body">
			<?php client_info($showsalesui);?>
		</div>
	</div>
	<div class="box">
		<div class="box-body">
		
		</div>
	</div>
<?php
}
?>