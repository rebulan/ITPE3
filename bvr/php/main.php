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
	$user = get_user_id($_SESSION['c_craft']);
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
				<form id = "edituserform">
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
													$pm = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_team where sales_team_id = $row[sales_team_id]"));
													
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
		$save = mysqli_query($con,"update se_user set agent_number = '$agent_id_edit',branch_id = $branchedit,
		fullname = '$fullname_edit', sales_team_id = $salesteamedit where user_id = $usereditid");
		
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
		$resetuser = get_user_id($_SESSION['c_craft']);
		$pass = md5(sha1($newpassword));
		mysqli_query($con, "Update se_user set user_username = '$newusername', user_password = '$pass', user_reset = 0 where user_id = $resetuser");
		
		//modlog("$id has changed his username and password",0);
		
		$_SESSION['reset'] = '';
		$_SESSION['c_craft'] = $newusername;
		//$_SESSION['employee'] = '';
		?>
		<script>
			
			alert("New User Account Created");
			location.reload();
		</script>
		<?php
}

?>