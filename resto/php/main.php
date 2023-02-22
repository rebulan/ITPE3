<?php
include('connect.php');
include("general.php");

if(!empty($_REQUEST['itemq']))
{
?>
	<script>
	/*$('#result tr').click(function() {
    var rowId = $(this).data('rowKey');
	$("#barcode").val(rowId);
	$("#barcode").focus();
	$("#clickval").val(1);
	$("#search_result").html("");
	});*/
</script>

<div class="card">
  <div class="card-body">
<?php
	$q = trim($_REQUEST['itemq']);
	
	/*$sql = "SELECT * FROM usertb WHERE employee_no like '".$q."%' or lname like '$q%'";
	$result = mysql_query($sql);*/
	
	/*$sql = "SELECT *
			FROM sub_profiletb, sub_subscription
			where sub_profiletb.pro_id = sub_subscription.profile_id and 
			((CONCAT(sub_profiletb.lastname,',',sub_profiletb.firstname,' ',sub_profiletb.middlename) like '$q%') or sub_subscription.account_number like '$q%' or 
			sub_profiletb.company_name like '$q%') limit 0,10";*/
	global $con;
	
	$sql = "Select * from pos_lup_item where 
	(item_description like '%$q%' or item_code like '%$q%') and isdeleted = 0 and addon_id = 0 limit 0,30";

	$result = mysqli_query($con, $sql);
	?>
	<table class = "table table-bordered table-sm" id = "searchtable">
		<thead>
			<th>ITEM CODE</th>
			<th>PRODUCT DESCRIPTION</th>						
		</thead>
	<?PHP
	$ctr = 0;  
	while($row = mysqli_fetch_assoc($result)) {
	  
	  $emp_no = $row['item_code'];
	 ?>
		<tr>
			<td><?php echo $emp_no;?></td>
			<td><?php echo $row['item_description'];?></td>
		</tr>
	 <?php
	 $ctr++;
	}
	?>
	 </table>
					<style>
						table.dataTable th.focus,
						table.dataTable td.focus {
						  outline: none;
						}
					</style>
	<script>
			$(document).ready(
				function()
				{
					
				
					var table = $('#searchtable').DataTable({
					  'paging'      : false,
					  'lengthChange': false,
					  'searching'   : false,
					  'ordering'    : false,
					  'info'        : false,
					  'autoWidth'   : false,
					   select: {
							style: 'single'
						},
						keys: {
						   keys: [ 13 /* ENTER */, 38 /* UP */, 40 /* DOWN */ ]
						}
					});	

					
							table.row(0).select();
							table.cell( ':eq(0)' ).focus();
					 
							
						
						// Handle event when cell gains focus
						$('#searchtable').on('key-focus.dt', function(e, datatable, cell){
							// Select highlighted row
							table.row(cell.index().row).select();
							
						});
						
						// Handle click on table cell
						$('#searchtable').on('click', 'tbody td', function(e){
							e.stopPropagation();
							var rowIdx = table.cell(this).index().row;
							
							table.row(rowIdx).select();
						});  
						 $('#searchtable').on('key.dt', function(e, datatable, key, cell){
								// If ENTER key is pressed
								if(key === 13){
									// Get highlighted row data
									var data = table.row(cell.index().row).data();
								   // alert( table.cell(this).data() );
									// FOR DEMONSTRATION ONLY
									$("#barcode").val(data[0]);
									$("#barcode").focus();
									$("#search_result").html('');
								}
							});						
				}
			);
		</script>
	</div>
	</div>
<?php
}
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
							
							$_SESSION['c_craft'] = $usercount['user_username'];
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
		$link = 'window.location.href = "../main.php"';
	else
		$link = 'window.location.href = "main.php"';

		$_SESSION['c_craft'] = '';
		$_SESSION['tran'] = '';
		$_SESSION['order'] = '';
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
		<H2>USER PROFILES</h2>
		<div class="box">
			<div class="box-body">
				<form id = "newuserform">
					<div class="row">
										<div class="col-md-3">
										  
											<label for="lname">AGENT ID NUMBER:</label>
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
										<?php
										if($level == 1)
										{
										?>
										<div class="col-md-3">
												<div class="form-group">
													<label>BRANCH:</label>
												
													<Select class = "form-control" name = "branch" data-validation="required"
													data-validation-error-msg="Select BRANCH">
														<option value = "" hidden "Selected"> </option>
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
											<input type="text" name="agent_id_edit" class="form-control" data-validation="required"
													data-validation-error-msg="Enter AGENT ID NUMBER" value = "<?php echo $row['agent_number'];?>">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-5">
										  
											<label for="lname">FULLNAME::</label>
											<input type="text" name="fullname_edit" class="form-control"data-validation="required"
													data-validation-error-msg="Enter Fullname" value = "<?php echo $row['fullname'];?>">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>SALES TEAM:</label>
													<Select class = "form-control" name = "salesteamedit" data-validation="required"
													data-validation-error-msg="Select SALES TEAM">
													<?php
													$pm = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_sales_team where sales_team_id = $row[sales_team_id]"));
													?>
													<option value = "<?php echo $pm['sales_team_id'];?>" hidden "Selected"><?php echo $pm['sales_team_name'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_sales_team where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['sales_team_id'];?>"><?php echo $prow['sales_team_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>BRANCH:</label>
												
													<Select class = "form-control" name = "branchedit" data-validation="required"
													data-validation-error-msg="Select BRANCH">
													<?php
													$sbranch = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
													?>
														<option value = "<?php echo $sbranch['branch_id'];?>" hidden "Selected"><?php echo $sbranch['branch'];?></option>
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
												<option value = "2">READ ONLY</option>
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
															alert('ok')
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

if(isset($_REQUEST['assignreport']))
{
	$id = $_REQUEST['assignreport'];
	?>
		<div class="box">
			<div class="box-body">
				<?php agent_info($id);?>
			</div>
		</div>
		<div class="box">
			<div class="box-body">
				<form id = "reportform">
								<div class = "row">	
									<div class="col-md-3">
										<div class = "form-group">
											<label>MODULES </label>
											<input type = "hidden" value = "<?php echo $id;?>" name = "modreportuserid">
											<select class = "form-control" id = "rmodule" name = "rmodule" data-validation="required"
																	data-validation-error-msg="Select Module">
												<option hidden "selected" value = "">&nbsp;</option>
												<option>SALES</option>
												<option>FINANCE</option>
												<option>INVENTORY</option>
											</select>
										</div>
									</div>
									<script>
											$("#rmodule").change(
														function(e)
														{
															e.preventDefault();
															$.post( 
																'../php/main.php',
																{
																	modrep:$("#rmodule").val()
																},
																function(data) {
																	$('#reportui').html(data);		
																});
														}
													);
									</script>
									<div class="col-md-3">
										<div class = "form-group" id = "reportui">
											<label>REPORTS</label>
											<select class = "form-control" id = "modreport" name = "modreport" data-validation="required"
																	data-validation-error-msg="Select Report">
												<option hidden "selected" value = "">&nbsp;</option>
												
											</select>
										</div>
									</div>
									<div class="col-md-2" style = "padding-top:25px;">
										 <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "filtercr">SAVE</button>				
										</div>
									</div>
								</div>
				</form>
				<SCRIPT>
					$.validate({
															form:'#reportform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#reportform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/main.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#modreportlist").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
				</SCRIPT>
			</div>
		</div>
		<div id = "modreportalert"></div>
		<div class="box">
			<div class="box-body" id = "modreportlist">
				<?php modreportlist($id);?>
			</div>
		</div>
	<?php
}	

if(isset($_REQUEST['modrep']))
{
	$id = $_REQUEST['modrep'];
	$query = mysqli_query($con,"Select * from se_menu_report where menu_report_module = '$id' and isdeleted = 0");
	?>
											<label>REPORTS</label>	
											<select class = "form-control" id = "modreport" name = "modreport" data-validation="required"
																	data-validation-error-msg="Select Report">
												<option hidden "selected" value = "none">&nbsp;</option>
												<?php
												while($row = mysqli_fetch_assoc($query))
												{
													?>
														<option value = "<?php echo $row['menu_report_id'];?>"><?php echo $row['menu_report_description'];?></option>
													<?php
												}
												?>
											</select>
	<?php
}	
if(isset($_POST['modreportuserid']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_num_rows(mysqli_query($con,"Select * from se_report_access where user_id = $modreportuserid and menu_report_id = $modreport and isdeleted = 0"));
	
	if($check == 0)
	{
		$save = mysqli_query($con,"insert into se_report_access set
		menu_report_id = $modreport,
		user_id = $modreportuserid");
		
		if($save)
		{
			?>
			<script>
				notify("The Report successfully assigned","#modreportalert");
			</script>
			<?php
		}
		else
		{
			?>
			<script>
				notify("Error Assigning Report, Please contact the system administrator","#modreportalert");
			</script>
			<?php
		}
		
	}
	else
	{
		?>
			<script>
				notify("The Report is already assigned","#modreportalert");
			</script>
		<?php
	}
	
	modreportlist($modreportuserid);
}
if(isset($_REQUEST['assignreportdel']))
{
	$id = $_REQUEST['assignreportdel'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select user_id from se_report_access where report_access_id = $id"));
	
	mysqli_query($con,"Update se_report_access set isdeleted = 1 where report_access_id = $id");
	
	modreportlist($row['user_id']);
}
if(isset($_REQUEST['branchui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">BRANCH MANAGEMENT</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newbranchform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_code" name = "branch_code" data-validation="required"
												data-validation-error-msg="Branch Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_name" name = "branch_name" data-validation="required"
												data-validation-error-msg="Branch Description Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
													<label>Branch PHOTO:</label>
													<div class="input-group">
													
													<input type="file" class="form-control" name = "branch_photo" id = "branch_photo" data-validation="required"
																		data-validation-error-msg=">Branch PHOTO Field is required">
													</div>
										</div>
						
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Contact Person1: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_cperson1" name = "branch_cperson1" data-validation="required"
												data-validation-error-msg=">Branch Contact Person1 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Contact Person2: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_cperson2" name = "branch_cperson2" data-validation="required"
												data-validation-error-msg=">Branch Contact Person2 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3"style = "display:none;">
													<label>Contact Person Photo 1:</label>
													<div class="input-group">
													
													<input type="file" class="form-control" name = "branch_contact_photo1" id = "branch_contact_photo1" data-validation="required"
																		data-validation-error-msg="Contact Person Photo 1 Field is required">
													</div>
										</div>
										<div class="col-md-3"style = "display:none;">
													<label>Contact Person Photo 2:</label>
													<div class="input-group">
													
													<input type="file" class="form-control" name = "branch_contact_photo2" id = "branch_contact_photo2" data-validation="required"
																		data-validation-error-msg="Contact Person Photo 2 Field is required">
													</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Contact Number1: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_cno1" name = "branch_cno1" data-validation="required"
												data-validation-error-msg="Branch Contact Number1 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Contact Number2: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_cno2" name = "branch_cno2" data-validation="required"
												data-validation-error-msg="Branch Contact Number2 Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Date Opened: &nbsp;</label>
												<input type="date" class = "form-control" id = "branch_open" name = "branch_open" data-validation="required"
												data-validation-error-msg="Date Open Field is Required">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="service_description_edit">Date Close: &nbsp;</label>
												<input type="date" class = "form-control" id = "branch_close" name = "branch_close">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Address: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_address" name = "branch_address" data-validation="required"
												data-validation-error-msg="Branch Address Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-4" style = "display:none;">
													  <div class="form-group">
													  <label for="age">REGION</label>
														<select name = "branch_region" id = "branch_region" class="form-control" data-validation="required"
																data-validation-error-msg="Select REGION">
															<option value = "" hidden "Selected"></option>
															<?php
															$rquery = mysqli_query($con,"Select * from lup_region where isdeleted = 0");
															while($rrow = mysqli_fetch_assoc($rquery))
															{
															?>
															<option value = "<?php echo $rrow['region_id'];?>"><?php echo $rrow['region_name'];?></option>
															<?php
															
															}
															?>
														</select>
														<script>
															$("#branch_region").change(
																function()
																{
																	var id = $("#branch_region").val();
																	
																	$.post( 
																			'php/customer.php',
																			{
																				provinceui:id
																			},
																			function(data) {
																				$('#provinceui').html(data);
																			
																			});
																}
															);
														</script>
													  </div>
											</div>
											<div class="col-md-4" style = "display:none;">
													  <div class="form-group" id = "provinceui"> 
													  <label for="age">PROVINCE</label>
														<select id="branch_province" name = "branch_province" class="form-control" data-validation="required"
																data-validation-error-msg="Select PROVINCE">
															<option value = "" hidden "Selected"></option>
															
														</select>
														
													  </div>
											</div>
											<div class="col-md-4" style = "display:none;">
													  <div class="form-group" id = "citymunui">
													  <label for="age">CITY/MUNICIPALITY</label>
														<select id="branch_citymun" name = "branch_citymun" class="form-control" data-validation="required"
																data-validation-error-msg="Select CITY/MINICIPALITY">
															<option value = "" hidden "Selected"></option>
															
														</select>
														
													  </div>
											</div>
											<div class="col-md-4" style = "display:none;">
													  <div class="form-group" id = "brgyui">
													  <label for="age">BARANGAY</label>
														<select id="branch_brgy" name = "branch_brgy" class="form-control" data-validation="required"
																data-validation-error-msg="Select BARANGAY">
															<option value = "" hidden "Selected"></option>
															
														</select>
														
													  </div>
											</div>
											<div class="col-md-5" style = "display:none;">
												<div class="form-group">
														<label for="lname">STREET & NO:</label>
														<input type="text" name="branch_street" id="branch_street" class="form-control" data-validation="required"
														data-validation-error-msg="STREET & NO Field is Required">
														
												</div>
											</div>
											<div class="col-md-2">
											<div class="form-group">
												<label >
												<input type="checkbox" id = "branch_active" name = "branch_active" value = "1">
												Active Branch</label>
											</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#newbranchform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#branchlist").html(loading);
															 
																var formData = $('#newbranchform')[0];
																$.ajax({
																						url: 'php/main.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#branchlist").html(data);
																					
																						},
																						error: function() 
																						{
																							alert('Sending failed');
																						} 	        
																				   });

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "branchalert"></div>
				<div id = "branchlist"><?php branchlist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['branch_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
			$name = $_FILES['branch_photo']['name'];
			$type = $_FILES['branch_photo']['type'];
			$size = $_FILES['branch_photo']['size'];
	
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
			$sss = $result."_".$_FILES['branch_photo']['name'];
			
			
			
			
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if(($size <= 6000000))
				{
					if (!file_exists('../images/branch_photo/')) {
						mkdir('../images/branch_photo/', 0777, true);
					}
					
					
					
									$sourcePath = $_FILES['branch_photo']['tmp_name'];
									$targetPath = "../images/branch_photo/".basename($sss);
					
									$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_branch where
									(branch_description = '$branch_name' or branch_code = '$branch_code') and isdeleted = 0"));
									$user = get_user_id($_SESSION['c_craft']);
									$agent = get_agent($user);
									$check = 0;
									if($check == 0)
									{
										if(is_uploaded_file($_FILES['branch_photo']['tmp_name'])) 
										{
												if(move_uploaded_file($sourcePath,$targetPath)) 
												{
														$active = 0;
														if(isset($_POST['branch_active']))
														{
															$active = $_POST['branch_active'];
															
														}
														$save = mysqli_query($con,"Insert into lup_branch set 
														branch_code = '$branch_code',
														branch_description = '$branch_name',
														branch_photo = '$sss',
														branch_contact_person1 = '$branch_cperson1',
														branch_contact_person2 = '$branch_cperson2',
														contact_person_photo1 = '',
														contact_person_photo2 = '',
														branch_contact_number1 = '$branch_cno1',
														branch_contact_number2 = '$branch_cno2',
														address = '$branch_address',
														
														date_open = '$branch_open',
														date_close = '',
														remarks = 'from_setup',
														isactive = '$active',
														created_modified = NOW()
														");
													
														if($save)
														{
														?>
															<script>
																notify("<i class='fa fa-info'></i> New branch Added","#branchalert");
															</script>
														<?php
														}
														else
														{
														?>
															<script>
																notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Branch, Contact the System Administrator", "#branchalert");
															</script>
														<?php
														}
														branchlist(0);
												}
													
													
													
										}
									}
									else
													{
														?>
															<script>
																notify("<i class='fa fa-exclamation-triangle'></i> Branch Description or Code Already Exist","#branchalert");
															</script>
														<?php
													}
									
										
						
					
				}
				else
				{
					echo "
						<script>
							alert('The Image File is too Large, The Allowed File Size is 6mb');
						</script>
					";
				}
			}
			else
			{
					echo "
						<script>
							alert('Invalid Image Extension.JPEG and PNG Only');
						</script>
					";
			}
}
if(isset($_REQUEST['branchdel']))
{
	$id = $_REQUEST['branchdel'];
	
	$del = mysqli_query($con,"Update lup_branch set isdeleted = 1 where branch_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Branch deleted","#branchalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Branch Information, contact the system administrator","#branchalert");
			</script>
		<?php
	}
	
	branchlist(0);
}
if(isset($_REQUEST['branchedit']))
{
	$id = $_REQUEST['branchedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editbranchform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Code: &nbsp;</label>
												<input type = "hidden" name = "branch_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "branch_code_edit" name = "branch_code_edit" data-validation="required"
												data-validation-error-msg="Branch Code: Field is Required"
												value = "<?php echo $row['branch_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_name_edit" name = "branch_name_edit" data-validation="required"
												data-validation-error-msg="Branch Description Field is Required"
												value = "<?php echo $row['branch_description'];?>"
												>
											
											</div>
										</div>
										
						
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Contact Person1: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_cperson1_edit" name = "branch_cperson1_edit" data-validation="required"
												data-validation-error-msg=">Branch Contact Person1 Field is Required"
												value = "<?php echo $row['branch_contact_person1'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Contact Person2: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_cperson2_edit" name = "branch_cperson2_edit" data-validation="required"
												data-validation-error-msg=">Branch Contact Person2 Field is Required"
												value = "<?php echo $row['branch_contact_person2'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Contact Number1: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_cno1_edit" name = "branch_cno1_edit" data-validation="required"
												data-validation-error-msg="Branch Contact Number1 Field is Required"
												value = "<?php echo $row['branch_contact_number1'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Branch Contact Number2: &nbsp;</label>
												<input type="text" class = "form-control" id = "branch_cno2_edit" name = "branch_cno2_edit" data-validation="required"
												data-validation-error-msg="Branch Contact Number2 Field is Required"
												value = "<?php echo $row['branch_contact_number2'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Date Open: &nbsp;</label>
												<input type="date" class = "form-control" id = "branch_open_edit" name = "branch_open_edit" data-validation="required"
												data-validation-error-msg="Date Open Field is Required"
												value = "<?php echo $row['date_open'];?>">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="service_description_edit">Date Close: &nbsp;</label>
												<input type="date" class = "form-control" id = "branch_close_edit" name = "branch_close_edit"
												value = "<?php echo $row['date_close'];?>">
											
											</div>
										</div>
										
										
										<div class="col-md-4" style = "display:none;">
													  <div class="form-group">
													  <label for="age">REGION</label>
														<select name = "branch_region" id = "branch_region" class="form-control" data-validation="required"
																data-validation-error-msg="Select REGION">
															<?php
															$rrow = mysqli_fetch_assoc( mysqli_query($con,"Select * from lup_region where region_id = $row[region_id]"));
															?>
															<option value = "<?php echo $rrow['region_id'];?>" hidden "Selected"><?php echo $rrow['region_name'];?></option>
															<?php
															$rquery = mysqli_query($con,"Select * from lup_region where isdeleted = 0");
															while($rrow = mysqli_fetch_assoc($rquery))
															{
															?>
															<option value = "<?php echo $rrow['region_id'];?>"><?php echo $rrow['region_name'];?></option>
															<?php
															
															}
															?>
														</select>
														<script>
															$("#branch_region").change(
																function()
																{
																	var id = $("#branch_region").val();
																	
																	$.post( 
																			'php/customer.php',
																			{
																				provinceui:id
																			},
																			function(data) {
																				$('#provinceui').html(data);
																			
																			});
																}
															);
														</script>
													  </div>
											</div>
											<div class="col-md-4" style = "display:none;">
													  <div class="form-group" id = "provinceui"> 
													  <label for="age">PROVINCE</label>
														<select id="province" name = "province" class="form-control" data-validation="required"
																data-validation-error-msg="Select PROVINCE">
															<?php
															$rrow = mysqli_fetch_assoc( mysqli_query($con,"Select * from lup_province where province_id = $row[province_id]"));
															?>
															<option value = "<?php echo $rrow['province_id'];?>" hidden "Selected"><?php echo $rrow['province_name'];?></option>
															
														</select>
														
													  </div>
											</div>
											<div class="col-md-4" style = "display:none;">
													  <div class="form-group" id = "citymunui">
													  <label for="age">CITY/MUNICIPALITY</label>
														<select id="citymun" name = "citymun" class="form-control" data-validation="required"
																data-validation-error-msg="Select CITY/MINICIPALITY">
															<?php
															$rrow = mysqli_fetch_assoc( mysqli_query($con,"Select * from lup_city_town where city_town_id = $row[city_town_id]"));
															?>
															<option value = "<?php echo $rrow['city_town_id'];?>" hidden "Selected"><?php echo $rrow['city_town_name'];?></option>
															
														</select>
														
													  </div>
											</div>
											<div class="col-md-4" style = "display:none;">
													  <div class="form-group" id = "brgyui">
													  <label for="age">BARANGAY</label>
														<select id="brgy" name = "brgy" class="form-control" data-validation="required"
																data-validation-error-msg="Select BARANGAY">
															<?php
															$rrow = mysqli_fetch_assoc( mysqli_query($con,"Select * from lup_barangay where barangay_id = $row[brgy_id]"));
															?>
															<option value = "<?php echo $rrow['barangay_id'];?>" hidden "Selected"><?php echo $rrow['barangay_name'];?></option>
															
														</select>
														
													  </div>
											</div>
											<div class="col-md-5">
												<div class="form-group">
														<label for="lname">ADDRESS:</label>
														<input type="text" name="branch_address_edit" id="branch_address_edit" class="form-control" data-validation="required"
														data-validation-error-msg="ADDRESS Field is Required"
														value = "<?php echo $row['address'];?>">
														
												</div>
											</div>
											<div class="col-md-2">
											<div class="form-group">
												<label >
												<input type="checkbox" id = "branch_active_edit" name = "branch_active_edit" value = "1"
												<?php
												if($row['isactive'] == 1)
													echo "checked";
												?>>
												Active Branch</label>
											</div>
										</div>
								
										
									
							
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editbranchform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editbranchform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#branchlist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#branchlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "brancheditalert"></div>
				
	<?php
}

if(isset($_POST['branch_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_branch where
	(branch_description = '$branch_name_edit' or branch_code = '$branch_code_edit') and isdeleted = 0 and branch_id != $branch_edit_id"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$active = 0;
														if(isset($_POST['branch_active_edit']))
														{
															$active = $_POST['branch_active_edit'];
															
														}
														
	if($check == 0)
	{
		$save = mysqli_query($con,"update lup_branch set 
		branch_code = '$branch_code_edit',
		branch_description = '$branch_name_edit',
		branch_contact_person1 = '$branch_cperson1_edit',
		branch_contact_person2 = '$branch_cperson2_edit',
		branch_contact_number1 = '$branch_cno1_edit',
		branch_contact_number2 = '$branch_cno2_edit',
		address = '$branch_address_edit',
		date_open = '$branch_open_edit',
		isactive = '$active',
		created_modified = NOW()
		where branch_id = $branch_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Branch Updated","#brancheditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Branch, Contact the System Administrator", "#brancheditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Branch Description or Code Already Exist","#brancheditalert");
			</script>
		<?php
	}
	
	branchlist(0);
}
if(isset($_REQUEST['cardtypeui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">CARD TYPE MANAGEMENT</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Card Type Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "cardtype_code" name = "cardtype_code" data-validation="required"
												data-validation-error-msg="Card Type Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Card Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "cardtype_name" name = "cardtype_name" data-validation="required"
												data-validation-error-msg="Card Type Description Field is Required">
											
											</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#branchlist").html(loading);
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#cardtypelist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cardtypealert"></div>
				<div id = "cardtypelist"><?php cardtypelist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['cardtype_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_card_type where
	(card_type_description = '$cardtype_name' or card_type_code = '$cardtype_code') and isdeleted = 0"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into lup_card_type set 
		card_type_code = '$cardtype_code',
		card_type_description = '$cardtype_name',
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Card Type Added","#cardtypealert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Card Type, Contact the System Administrator", "#cardtypealert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Card Type Description or Code Already Exist","#cardtypealert");
			</script>
		<?php
	}
	
	cardtypelist(0);
}
if(isset($_REQUEST['cardtypedel']))
{
	$id = $_REQUEST['cardtypedel'];
	
	$del = mysqli_query($con,"Update lup_card_type set isdeleted = 1 where card_type_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Card Type deleted","#cardtypealert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Card Type Information, contact the system administrator","#cardtypealert");
			</script>
		<?php
	}
	
	cardtypelist(0);
}
if(isset($_REQUEST['cardtypeedit']))
{
	$id = $_REQUEST['cardtypeedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_card_type where card_type_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Card Type Code: &nbsp;</label>
												<input type = "hidden" name = "cardtype_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "cardtype_code_edit" name = "cardtype_code_edit" data-validation="required"
												data-validation-error-msg="Card Type Code: Field is Required"
												value = "<?php echo $row['card_type_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Card Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "cardtype_name_edit" name = "cardtype_name_edit" data-validation="required"
												data-validation-error-msg="Card Type Description Field is Required"
												value = "<?php echo $row['card_type_description'];?>"
												>
											
											</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#cardtypelist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#cardtypelist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cardtypeeditalert"></div>
				
	<?php
}

if(isset($_POST['cardtype_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_card_type where
	(card_type_description = '$cardtype_name_edit' or card_type_code = '$cardtype_code_edit') and isdeleted = 0 and card_type_id != $cardtype_edit_id"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update lup_card_type set 
		card_type_code = '$cardtype_code_edit',
		card_type_description = '$cardtype_name_edit'		
		where card_type_id = $cardtype_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Card Type has been Saved","#cardtypeeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Card type, Contact the System Administrator", "#cardtypeeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Card type Description or Code Already Exist","#cardtypeeditalert");
			</script>
		<?php
	}
	
	cardtypelist(0);
}
if(isset($_REQUEST['customertypeui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">CUSTOMER TYPE MANAGEMENT</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Customer Type Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "ctype_code" name = "ctype_code" data-validation="required"
												data-validation-error-msg="Customer Type Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Customer Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "ctype_name" name = "ctype_name" data-validation="required"
												data-validation-error-msg="Customer Type Description Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>GROUP:</label>
												
													<Select class = "form-control" name = "ctypegroup" data-validation="required"
													data-validation-error-msg="Select Group">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_customer_type_group where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['customer_type_group_id'];?>"><?php echo $prow['customer_type_group_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#ctypelist").html(loading);
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#ctypelist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "ctypealert"></div>
				<div id = "ctypelist"><?php customertypelist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['ctype_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_customer_type where
	(customer_type_name = '$ctype_name' or customer_type_code = '$ctype_code') and isdeleted = 0"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into lup_customer_type set 
		customer_type_code = '$ctype_code',
		customer_type_name = '$ctype_name',
		customer_type_group = $ctypegroup,
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Customer Type Added","#ctypealert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Customer Type, Contact the System Administrator", "#ctypealert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Customer Type Description or Code Already Exist","#ctypealert");
			</script>
		<?php
	}
	
	customertypelist(0);
}
if(isset($_REQUEST['ctypedel']))
{
	$id = $_REQUEST['ctypedel'];
	
	$del = mysqli_query($con,"Update lup_customer_type set isdeleted = 1 where customer_type_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Customer Type deleted","#ctypealert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Customer Type Information, contact the system administrator","#ctypealert");
			</script>
		<?php
	}
	
	customertypelist(0);
}
if(isset($_REQUEST['ctypeedit']))
{
	$id = $_REQUEST['ctypeedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Customer Type Code: &nbsp;</label>
												<input type = "hidden" name = "ctype_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "ctype_code_edit" name = "ctype_code_edit" data-validation="required"
												data-validation-error-msg="Customer Type Code: Field is Required"
												value = "<?php echo $row['customer_type_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Customer Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "ctype_name_edit" name = "ctype_name_edit" data-validation="required"
												data-validation-error-msg="Card Type Description Field is Required"
												value = "<?php echo $row['customer_type_name'];?>"
												>
											
											</div>
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>GROUP:</label>
												
													<Select class = "form-control" name = "ctypegroup_edit" data-validation="required"
													data-validation-error-msg="Select Group">
													<?php
													$grow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type_group where customer_type_group_id = $row[customer_type_group]"));
													?>
														<option value = "<?php echo $grow['customer_type_group_id'];?>" hidden "Selected"><?php echo $grow['customer_type_group_name'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_customer_type_group where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['customer_type_group_id'];?>"><?php echo $prow['customer_type_group_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#cardtypelist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#ctypelist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "ctypeeditalert"></div>
				
	<?php
}

if(isset($_POST['ctype_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_customer_type where
	(customer_type_name = '$ctype_name_edit' or customer_type_code = '$ctype_code_edit') and isdeleted = 0 and customer_type_id != $ctype_edit_id"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update lup_customer_type set 
		customer_type_code = '$ctype_code_edit',
		customer_type_name = '$ctype_name_edit',
		customer_type_group = '$ctypegroup_edit'
		where customer_type_id = $ctype_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Customer Type has been Saved","#ctypeeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Customer type, Contact the System Administrator", "#ctypeeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Customer type Description or Code Already Exist","#ctypeeditalert");
			</script>
		<?php
	}
	
	customertypelist(0);
}
if(isset($_REQUEST['cprofileui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">CARD PROFILE MANAGEMENT</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
												<div class="form-group">
													<label>CARD TYPE:</label>
												
													<Select class = "form-control" name = "pctype" data-validation="required"
													data-validation-error-msg="Select CARD TYPE">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_card_type where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['card_type_id'];?>"><?php echo $prow['card_type_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="age">VALIDITY:</label>
											
												<select id="validity" name = "validity" class="form-control" data-validation="required"
																					data-validation-error-msg="Select VALIDITY">
													<option value = "" hidden "Selected"></option>					
													<option value = "1">DATE RANGE</option>
													<option value = "0">LIFETIME</option>
												</select>	
											</div>
										</div>
										<div id = "drangeui">
										
										</div>
							<script>
								$("#validity").change(
									function()
									{
										var r = $("#validity").val();
										
										if(r == 1)
										{
														$.post( 
																'php/customer.php',
																{
																	regdrangeui:1
																	
																},
																function(data) {
																	$('#drangeui').html(data);		
																});
																
											/*$("#rrdfromedit").attr("data-validation","date");
											$("#rrdfromedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdfromedit").attr("data-validation-error-msg","Enter valid Valid From");
											
											$("#rrdtoedit").attr("data-validation","date");
											$("#rrdtoedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdtoedit").attr("data-validation-error-msg","Enter valid Valid Until");*/
										}
										else
										{
											
												$("#drangeui").html("");
												
											/*$("#rrdfromedit").attr("data-validation","");
											$("#rrdfromedit").attr("data-validation-format","");
											$("#rrdfromedit").attr("data-validation-error-msg","");
											
											$("#rrdtoedit").attr("data-validation","");
											$("#rrdtoedit").attr("data-validation-format","");
											$("#rrdtoedit").attr("data-validation-error-msg","");*/
											
										}
									}
								);
							</script>
							<div class="col-md-4">
								 <div class="form-group">								
									<label for="age">No of Copy:</label>
									<input type="text" id="pcopy" name="pcopy" class="form-control" autocomplete="off"
									data-validation="number" data-validation-error-msg="No of Copy field is required"
									value = "1">
												
								</div>
							</div>
							
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#cprofilelist").html(loading);
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#cprofilelist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cprofilealert"></div>
				<div id = "cprofilelist"><?php cardprofilelist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(!empty($_POST['pctype']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$card = mysqli_fetch_assoc(mysqli_query($con,"Select card_type_description from lup_card_type where card_type_id = $pctype"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	//$pcopy = 0;
	$c = 1;
	while($c<=$pcopy)
	{
								$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
								$validCharNumber = strlen($validCharacters);
								 
								$length = 5;
								$result = "";
								$found = 1;
								
								while($found == 1)
								{
									for ($i = 0; $i < $length; $i++) {
										$index = mt_rand(0, $validCharNumber-1);
										$result .= $validCharacters[$index];
									}
									$found = mysqli_num_rows(mysqli_query($con, "Select * from card_profile where result = '$result'"));
									if($found == 0)
										break;
									else
										$i = 0;
								}
								
								if($validity == 0)
								{
									$rrdfrom = "";
									$rrdto = "";
								}
								
								mysqli_query($con,"insert into card_profile set
									result = '$result',
									card_profile_id = 0,
									card_profile_no = '',
									card_number = '',
									card_name = '$card[card_type_description]',
									card_type_id = $pctype,
									datetime_created = NOW(),
									created_by_fullname = '$agent',
									valid_from = '$rrdfrom',
									valid_to = '$rrdto',
									with_validity = $validity,
									remarks = 'CREATED FROM SYSTEM PANEL',
									isdeleted = 0,
									created_modified = NOW()
								");
								
								$row = mysqli_fetch_assoc(mysqli_query($con,"Select card_profile_id from card_profile where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['card_profile_id'];
								}
								
									$count = "";
									if($total_enrolled < 10)
									{
										$count = "000000000";
									}
									else if($total_enrolled >= 10 && $total_enrolled < 100)
									{
										$count = "00000000";
									}
									else if($total_enrolled >= 100 && $total_enrolled < 1000)
									{
										$count = "0000000";
									}
									else if($total_enrolled >= 1000 && $total_enrolled < 10000)
									{
										$count = "000000";
									}
									else if($total_enrolled >= 10000 && $total_enrolled < 100000)
									{
										$count = "00000";
									}
									else if($total_enrolled >= 100000 && $total_enrolled < 1000000)
									{
										$count = "0000";
									}
									else if($total_enrolled >= 1000000 && $total_enrolled < 10000000)
									{
										$count = "000";
									}
									else if($total_enrolled >= 10000000 && $total_enrolled < 100000000)
									{
										$count = "00";
									}
								else if($total_enrolled >= 100000000 && $total_enrolled < 1000000000)
									{
										$count = "0";
									}
									else
									{
										$count = "";
									}
									
								$idno = $count.$row['card_profile_id'];
								
				
								mysqli_query($con, "Update card_profile set card_number = '$idno',result = '' where card_profile_id = $row[card_profile_id]");	
		$c++;
	}
	
	cardprofilelist(0);
								
}
if(!empty($_REQUEST['cpdel']))
{
	$id = $_REQUEST['cpdel'];
	
	$del = mysqli_query($con,"Update card_profile set isdeleted = 1 where card_profile_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Card Profile deleted","#cprofilealert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Card Profile,contact the system administrator","#cprofilealert");
			</script>
		<?php
	}
	
	cardprofilelist(0);
}
if(isset($_REQUEST['ctypeedit']))
{
	$id = $_REQUEST['ctypeedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Customer Type Code: &nbsp;</label>
												<input type = "hidden" name = "ctype_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "ctype_code_edit" name = "ctype_code_edit" data-validation="required"
												data-validation-error-msg="Customer Type Code: Field is Required"
												value = "<?php echo $row['customer_type_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Customer Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "ctype_name_edit" name = "ctype_name_edit" data-validation="required"
												data-validation-error-msg="Card Type Description Field is Required"
												value = "<?php echo $row['customer_type_name'];?>"
												>
											
											</div>
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>GROUP:</label>
												
													<Select class = "form-control" name = "ctypegroup_edit" data-validation="required"
													data-validation-error-msg="Select Group">
													<?php
													$grow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type_group where customer_type_group_id = $row[customer_type_group]"));
													?>
														<option value = "<?php echo $grow['customer_type_group_id'];?>" hidden "Selected"><?php echo $grow['customer_type_group_name'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_customer_type_group where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['customer_type_group_id'];?>"><?php echo $prow['customer_type_group_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#cardtypelist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#ctypelist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "ctypeeditalert"></div>
				
	<?php
}

if(isset($_POST['ctype_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_customer_type where
	(customer_type_name = '$ctype_name_edit' or customer_type_code = '$ctype_code_edit') and isdeleted = 0 and customer_type_id != $ctype_edit_id"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update lup_customer_type set 
		customer_type_code = '$ctype_code_edit',
		customer_type_name = '$ctype_name_edit',
		customer_type_group = '$ctypegroup_edit'
		where customer_type_id = $ctype_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Customer Type has been Saved","#ctypeeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Customer type, Contact the System Administrator", "#ctypeeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Customer type Description or Code Already Exist","#ctypeeditalert");
			</script>
		<?php
	}
	
	customertypelist(0);
}
if(!empty($_REQUEST['cpedit']))
{
	$id = $_REQUEST['cpedit'];
	
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from card_profile where card_profile_id = $id"));
	
	if($row['with_validity'] == 0)
	{
		?>
			<script>
				$("#drangeui").html("");
			</script>
		<?php
	}
	
	?>
	<div class="box">
			<div class="box-body">
				<?php card_info($row['card_profile_id']);?>
			</div>
	</div>
	<div class="box">
			<div class="box-body">
				<form id = "regeditform" method = "POST">
			
						<div class = "row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">CARD VALIDITY:</label>
									<input type = "hidden" value = "<?php echo $row['card_profile_id'];?>" name = "cardeditid">
									<select id="cvalidedit" name = "cvalidedit" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Card Type">
										<?php
										if($row['with_validity'] == 1)
										{
											?>
											<option value = "1" hidden "Selected">DATE RANGE</option>		
											<?php
										}
										else
										{
											?>
											<option value = "0" hidden "Selected">LIFETIME</option>		
											<?php
										}
										?>										
														
										<option value = "1">DATE RANGE</option>
										<option value = "0">LIFETIME</option>
									</select>	
								</div>
							</div>
							<script>
								$("#cvalidedit").change(
									function()
									{
										var r = $("#cvalidedit").val();
										
										if(r == 1)
										{
														$.post( 
																'php/customer.php',
																{
																	cdrangeui:1
																	
																},
																function(data) {
																	$('#drangeui').html(data);		
																});
																
											/*$("#rrdfromedit").attr("data-validation","date");
											$("#rrdfromedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdfromedit").attr("data-validation-error-msg","Enter valid Valid From");
											
											$("#rrdtoedit").attr("data-validation","date");
											$("#rrdtoedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdtoedit").attr("data-validation-error-msg","Enter valid Valid Until");*/
										}
										else
										{
											
												$("#drangeui").html("");
											/*$("#rrdfromedit").attr("data-validation","");
											$("#rrdfromedit").attr("data-validation-format","");
											$("#rrdfromedit").attr("data-validation-error-msg","");
											
											$("#rrdtoedit").attr("data-validation","");
											$("#rrdtoedit").attr("data-validation-format","");
											$("#rrdtoedit").attr("data-validation-error-msg","");*/
											
										}
									}
								);
							</script>
							<div id = "drangeui">
								<div class="col-md-3" id = "cdfromui">
									<div class = "form-group">
										<label>Valid From:</label>
										<input type = "date" class = "form-control" name = "cdfromedit" id = "cdfromedit" 
										value = "<?php echo $row['valid_from'];?>" placeholder = "yyyy-mm-dd">
									</div>		
								</div>
								<div class="col-md-3" id = "cdtoui">
									<div class = "form-group">
										<label>Valid Until:</label>
										<input type = "date" class = "form-control" name = "cdtoedit"  id = "cdtoedit"  
										value = "<?php echo $row['valid_to'];?>" placeholder = "yyyy-mm-dd">
									</div>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">Card Type:</label>
									<select id="rrcard" name = "rrcardedit" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Card Type">
																	<?php
																	//$scrow = mysqli_fetch_assoc(mysqli_query($con,"Select card_type_id from card_profile where card_profile_history_id = $row[card_profile_id]"));
																	$card = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_card_type where card_type_id = $row[card_type_id]"));
																	
																	?>
																	<option value = "<?php echo $card['card_type_id'];?>" "Selected"><?php echo $card['card_type_description'];?></option>
																	<?php
																	$cquery = mysqli_query($con,"Select * from lup_card_type where isdeleted = 0");
																	while($crow = mysqli_fetch_assoc($cquery))
																	{
																	?>
																	<option value = "<?php echo $crow['card_type_id'];?>"><?php echo $crow['card_type_description'];?></option>
																	<?php
																	}
																	?>
									</select>	
								</div>
							</div>
							
							<div class="col-md-3" style = "padding-top:25px;">
														<div class = "form-group">
															<button class = "btn btn-success btn-flat btn-sm" id = "go">UPDATE</button>
															<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
														</div>	
							</div>							
					</div>
				</form>
				<script>
							$("#cancel").click(
								function(e)
								{
									e.preventDefault();
									$("#modal").modal("hide");
									$("#modalui").html("");

								}
							);
							
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
															form:'#regeditform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																var formData = $('#regeditform')[0];
																$.ajax({
																						url: 'php/main.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#cprofilelist").html(data);
																					
																						},
																						error: function() 
																						{
																							alert('Sending failed');
																						} 	        
																				   });
																				   
																

															  return false; // Will stop the submission of the form
															},
														});			
						</script>
						
		</div>
	</div>
	<?php
}
if(isset($_POST['cardeditid']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	if($cvalidedit == 0)
	{
		$cdfromedit = "";
		$cdtoedit = "";
	}
		mysqli_query($con,"update card_profile set
		card_type_id = $rrcardedit,
		valid_from = '$cdfromedit',
		valid_to = '$cdtoedit',
		with_validity = $cvalidedit
		where
		card_profile_history_id = $cardeditid");
	
		?>
			<script>
				alert("Card Profile Updated");
				$("#modal2").modal("hide");
			</script>
		<?php
		cardprofilelist(0);
}
if(isset($_REQUEST['climitui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">CREDIT LINE LIMIT MANAGEMENT</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Credit Limit Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "cr_code" name = "cr_code" data-validation="required"
												data-validation-error-msg="Credit Limit Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Credit Limit Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "cr_name" name = "cr_name" data-validation="required"
												data-validation-error-msg="Credit LimitDescription Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Credit Limit Amount: &nbsp;</label>
												<input type="text" class = "form-control" id = "cr_amount" name = "cr_amount" data-validation="number"
												data-validation-error-msg="Credit Limit Description Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#ctypelist").html(loading);
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#climitlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cralert"></div>
				<div id = "climitlist"><?php climitlist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['cr_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_credit_line_limit where
	(credit_line_limit_description= '$cr_name' or credit_line_limit_code = '$cr_code') and isdeleted = 0"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into lup_credit_line_limit set 
		credit_line_limit_code = '$cr_code',
		credit_line_limit_description = '$cr_name',
		credit_line_limit_amount = $cr_amount,
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Credit Line Limit Added","#cralert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Credit Line Limit, Contact the System Administrator", "#cralert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Credit Line Limit Description or Code Already Exist","#cralert");
			</script>
		<?php
	}
	
	climitlist(0);
}
if(isset($_REQUEST['crdel']))
{
	$id = $_REQUEST['crdel'];
	
	$del = mysqli_query($con,"Update lup_credit_line_limit set isdeleted = 1 where credit_line_limit_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Credit Line Limit deleted","#cralert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Credit Line Limit Information, contact the system administrator","#cralert");
			</script>
		<?php
	}
	
	climitlist(0);
}
if(isset($_REQUEST['credit']))
{
	$id = $_REQUEST['credit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_credit_line_limit where credit_line_limit_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Credit Line Limit Code: &nbsp;</label>
												<input type = "hidden" name = "cr_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "cr_code_edit" name = "cr_code_edit" data-validation="required"
												data-validation-error-msg="Customer Type Code: Field is Required"
												value = "<?php echo $row['credit_line_limit_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Credit Line Limit Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "cr_name_edit" name = "cr_name_edit" data-validation="required"
												data-validation-error-msg="Card Type Description Field is Required"
												value = "<?php echo $row['credit_line_limit_description'];?>"
												>
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Credit Line Limit Amount: &nbsp;</label>
												<input type="text" class = "form-control" id = "cr_amount_edit" name = "cr_amount_edit" data-validation="required"
												data-validation-error-msg="Card Type Description Field is Required"
												value = "<?php echo $row['credit_line_limit_amount'];?>"
												>
											
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#climitlist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#climitlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "creditalert"></div>
				
	<?php
}

if(isset($_POST['cr_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_credit_line_limit where
	(credit_line_limit_description = '$cr_name_edit' or credit_line_limit_code = '$cr_code_edit') and isdeleted = 0 and credit_line_limit_id != $cr_edit_id"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update lup_credit_line_limit set 
		credit_line_limit_code = '$cr_code_edit',
		credit_line_limit_description = '$cr_name_edit',
		credit_line_limit_amount = '$cr_amount_edit'
		where credit_line_limit_id = $cr_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Credit Line Limit has been Saved","#creditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Credit Line Limit, Contact the System Administrator", "#creditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Credit Line Limit Description or Code Already Exist","#creditalert");
			</script>
		<?php
	}
	
	climitlist(0);
}
if(isset($_REQUEST['regstatusui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">REGISTRATION STATUS MANAGEMENT</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Registration Status Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "rs_code" name = "cr_code" data-validation="required"
												data-validation-error-msg="Registration Status Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Registration Status Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "rs_name" name = "cr_name" data-validation="required"
												data-validation-error-msg="Registration Status Description Field is Required">
											
											</div>
										</div>
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#ctypelist").html(loading);
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#rstatuslist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "rsalert"></div>
				<div id = "rstatuslist"><?php regstatuslist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['rs_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_registration_status where
	(registration_status_description= '$rs_name' or registration_status_code = '$rs_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into lup_registration_status set 
		registration_status_code = '$rs_code',
		registration_status_description = '$rs_name',
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Registration Status Added","#rsalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Registration Status, Contact the System Administrator", "#rsalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Credit Line Limit Description or Code Already Exist","#rsalert");
			</script>
		<?php
	}
	
	regstatuslist(0);
}
if(isset($_REQUEST['rsdel']))
{
	$id = $_REQUEST['rsdel'];
	
	$del = mysqli_query($con,"Update lup_registration_status set isdeleted = 1 where registration_status_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Registration Status deleted","#rsalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Registration Status Information, contact the system administrator","#rsalert");
			</script>
		<?php
	}
	
	regstatuslist(0);
}
if(isset($_REQUEST['rsedit']))
{
	$id = $_REQUEST['rsedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_registration_status where registration_status_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Registration Status Code: &nbsp;</label>
												<input type = "hidden" name = "rs_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "rs_code_edit" name = "rs_code_edit" data-validation="required"
												data-validation-error-msg="Registration Status Code Field is Required"
												value = "<?php echo $row['registration_status_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Registration Status Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "rs_name_edit" name = "rs_name_edit" data-validation="required"
												data-validation-error-msg="Registration Status Description Field is Required"
												value = "<?php echo $row['registration_status_description'];?>"
												>
											
											</div>
										</div>
										
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#rstatuslist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#rstatuslist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "rseditalert"></div>
				
	<?php
}

if(isset($_POST['rs_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_registration_status where
	(registration_status_description = '$rs_name_edit' or registration_status_code = '$rs_code_edit') and isdeleted = 0 and registration_status_id != $rs_edit_id"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update lup_registration_status set 
		registration_status_code = '$cr_code_edit',
		registration_status_description = '$cr_name_edit'
		where registration_status_id = $rs_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Registration Status has been Saved","#rseditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Registration Status, Contact the System Administrator", "#rseditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Registration Status Description or Code Already Exist","#creditalert");
			</script>
		<?php
	}
	
	regstatuslist(0);
}

if(isset($_REQUEST['setgroupui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">SETTLEMENT TYPE GROUP MANAGEMENT</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Settlement Type Group Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "sg_code" name = "sg_code" data-validation="required"
												data-validation-error-msg="Registration Status Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Settlement Type Group Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "sg_name" name = "sg_name" data-validation="required"
												data-validation-error-msg="Registration Status Description Field is Required">
											
											</div>
										</div>
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#setgrouplist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#setgrouplist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "sgalert"></div>
				<div id = "setgrouplist"><?php setgrouplist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['sg_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_settlement_type_group where
	(settlement_type_group_description= '$sg_name' or settlement_type_group_code = '$sg_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into lup_settlement_type_group set 
		settlement_type_group_code = '$sg_code',
		settlement_type_group_description = '$sg_name',
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Settlement Group Type Added","#sgalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Settlement Group Type, Contact the System Administrator", "#sgalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Settlement Group Type Description or Code Already Exist","#sgalert");
			</script>
		<?php
	}
	
	setgrouplist(0);
}
if(isset($_REQUEST['sgdel']))
{
	$id = $_REQUEST['sgdel'];
	
	$del = mysqli_query($con,"Update lup_settlement_type_group set isdeleted = 1 where settlement_type_group_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Settlement Group deleted","#sgalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Settlement Group Information, contact the system administrator","#sgalert");
			</script>
		<?php
	}
	
	setgrouplist(0);
}
if(isset($_REQUEST['sgedit']))
{
	$id = $_REQUEST['sgedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type_group where settlement_type_group_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Settlement Type Group Code: &nbsp;</label>
												<input type = "hidden" name = "sg_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "sg_code_edit" name = "sg_code_edit" data-validation="required"
												data-validation-error-msg="Registration Status Code Field is Required"
												value = "<?php echo $row['settlement_type_group_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Settlement Type Group Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "sg_name_edit" name = "sg_name_edit" data-validation="required"
												data-validation-error-msg="Registration Status Description Field is Required"
												value = "<?php echo $row['settlement_type_group_description'];?>"
												>
											
											</div>
										</div>
										
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#setgrouplist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#setgrouplist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "sgeditalert"></div>
				
	<?php
}

if(isset($_POST['sg_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_settlement_type_group where
	(settlement_type_group_description = '$sg_name_edit' or settlement_type_group_code = '$sg_code_edit') and isdeleted = 0 and settlement_type_group_id != $sg_edit_id"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update lup_settlement_type_group set 
		settlement_type_group_code = '$sg_code_edit',
		settlement_type_group_description = '$sg_name_edit'
		where settlement_type_group_id = $sg_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Settlement Type Group has been Saved","#sgeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Settlement Type Group, Contact the System Administrator", "#sgeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Settlement Type Group Description or Code Already Exist","#sgeditalert");
			</script>
		<?php
	}
	
	setgrouplist(0);
}
if(isset($_REQUEST['settleui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">SETTLEMENT TYPE MANAGEMENT</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Settlement Type Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "set_code" name = "set_code" data-validation="required"
												data-validation-error-msg="Settlement Type Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Settlement Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "set_name" name = "set_name" data-validation="required"
												data-validation-error-msg="Settlement Type Description Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>Settlement Type Group:</label>
												
													<Select class = "form-control" name = "set_group" data-validation="required"
													data-validation-error-msg="Select Settlement Type Group">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_settlement_type_group where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['settlement_type_group_id'];?>"><?php echo $prow['settlement_type_group_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
									</div>
									<div class = "row">
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_ref1" name = "set_ref1" value = "1">
												<label for="service_description_edit">&nbsp;Reference NO. 1</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_ref2" name = "set_ref2" value = "1">
												<label for="service_description_edit">&nbsp;Reference NO. 2</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_refdes1" name = "set_refdes1" value = "1">
												<label for="service_description_edit">&nbsp;Reference Description 1</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_refdes2" name = "set_refdes2" value = "1">
												<label for="service_description_edit">&nbsp;Reference Description 2</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_proof1" name = "set_proof1" value = "1">
												<label for="service_description_edit">&nbsp;Proof of Payment 1</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_proof2" name = "set_proof2" value = "1">
												<label for="service_description_edit">&nbsp;Proof of Payment 2</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_charge1" name = "set_charge1" value = "1">
												<label for="service_description_edit">&nbsp;Charge to Account 1</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_charge2" name = "set_charge2" value = "1">
												<label for="service_description_edit">&nbsp;Charge to Account 2</label>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_visible" name = "set_visible" value = "1">
												<label for="service_description_edit">&nbsp;Visible</label>
											</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#settlelist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#settlelist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "setalert"></div>
				<div id = "settlelist"><?php settlelist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['set_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$set_ref1  = 0;
	$set_ref2 = 0;
	$set_refdes1 = 0;
	$set_refdes2 = 0;
	$set_proof1 = 0;
	$set_proof2 = 0;
	$set_charge1 = 0;
	$set_charge2 = 0;
	$set_visible = 0;
	
	if(isset($_POST['set_ref1']))
		$set_ref1  = $_POST['set_ref1'];
	if(isset($_POST['set_ref2']))
		$set_ref2  = $_POST['set_ref2'];
	if(isset($_POST['set_refdes1']))
		$set_refdes1  = $_POST['set_refdes1'];
	if(isset($_POST['set_refdes2']))
		$set_refdes2  = $_POST['set_refdes2'];
	if(isset($_POST['set_proof1']))
		$set_proof1  = $_POST['set_proof1'];
	if(isset($_POST['set_proof2']))
		$set_proof2  = $_POST['set_proof2'];
	if(isset($_POST['set_charge1']))
		$set_charge1  = $_POST['set_charge1'];
	if(isset($_POST['set_charge2']))
		$set_charge2  = $_POST['set_charge2'];
	if(isset($_POST['set_visible']))
		$set_visible  = $_POST['set_visible'];
		
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_settlement_type where
	(settlement_description= '$set_name' or settlement_code = '$set_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into lup_settlement_type set 
		settlement_code = '$set_code',
		settlement_description = '$set_name',
		settlement_type_group_id = '$set_group',
		with_reference_no1= $set_ref1,
		with_reference_no2 = $set_ref2,
		with_reference_description1 = $set_refdes1,
		with_reference_description2 = $set_refdes2,
		with_proof_of_payment1 = $set_proof1,
		with_proof_of_payment2 = $set_proof2,
		charge_to_account1 = $set_charge1,
		charge_to_account2 = $set_charge2,
		visible = $set_visible,
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Settlement Type Added","#setalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Settlement Type, Contact the System Administrator", "#setalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Settlement Type Description or Code Already Exist","#setalert");
			</script>
		<?php
	}
	
	settlelist(0);
}
if(isset($_REQUEST['setdel']))
{
	$id = $_REQUEST['setdel'];
	
	$del = mysqli_query($con,"Update lup_settlement_type set isdeleted = 1 where settlement_type_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Settlement Type deleted","#setalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Settlement Type Information, contact the system administrator","#setalert");
			</script>
		<?php
	}
	
	settlelist(0);
}
if(isset($_REQUEST['setedit']))
{
	$id = $_REQUEST['setedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editsetform">
									
									 
									  <div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<input type = "hidden" value = "<?php echo $id;?>" name = "set_edit_id">
												<label for="service_description_edit">Settlement Type Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "set_code_edit" name = "set_code_edit" data-validation="required"
												data-validation-error-msg="Settlement Type Code Field is Required"
												value = "<?php echo $row['settlement_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Settlement Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "set_name_edit" name = "set_name_edit" data-validation="required"
												data-validation-error-msg="Settlement Type Description Field is Required"
												value = "<?php echo $row['settlement_description'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>Settlement Type Group:</label>
												
													<Select class = "form-control" name = "set_group_edit" data-validation="required"
													data-validation-error-msg="Select Settlement Type Group">
													<?php
													$grow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type_group where settlement_type_group_id = $row[settlement_type_group_id]"));
													?>
														<option value = "<?php echo $grow['settlement_type_group_id'];?>" hidden "Selected"><?php echo $grow['settlement_type_group_description'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_settlement_type_group where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['settlement_type_group_id'];?>"><?php echo $prow['settlement_type_group_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
									</div>
									<div class = "row">
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_ref1_edit" name = "set_ref1_edit" value = "1"
												<?php
												if($row['with_reference_no1'] == 1)
													echo "checked";
												?>
												>
												<label for="service_description_edit">&nbsp;Reference NO. 1</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_ref2_edit" name = "set_ref2_edit" value = "1"
												<?php
												if($row['with_reference_no2'] == 1)
													echo "checked";
												?>
												>
												<label for="service_description_edit">&nbsp;Reference NO. 2</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_refdes1_edit" name = "set_refdes1_edit" value = "1"
												<?php
												if($row['with_reference_description1'] == 1)
													echo "checked";
												?>>
												<label for="service_description_edit">&nbsp;Reference Description 1</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_refdes2_edit" name = "set_refdes2_edit" value = "1"
												<?php
												if($row['with_reference_no2'] == 1)
													echo "checked";
												?>>
												<label for="service_description_edit">&nbsp;Reference Description 2</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_proof1_edit" name = "set_proof1_edit" value = "1"
												<?php
												if($row['with_proof_of_payment1'] == 1)
													echo "checked";
												?>>
												<label for="service_description_edit">&nbsp;Proof of Payment 1</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_proof2_edit" name = "set_proof2_edit" value = "1"
												<?php
												if($row['with_proof_of_payment2'] == 1)
													echo "checked";
												?>>
												<label for="service_description_edit">&nbsp;Proof of Payment 2</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_charge1_edit" name = "set_charge1_edit" value = "1"
												<?php
												if($row['charge_to_account1'] == 1)
													echo "checked";
												?>>
												<label for="service_description_edit">&nbsp;Charge to Account 1</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_charge2_edit" name = "set_charge2_edit" value = "1"
												<?php
												if($row['charge_to_account2'] == 1)
													echo "checked";
												?>>
												<label for="service_description_edit">&nbsp;Charge to Account 2</label>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<input type="checkbox" id = "set_visible_edit" name = "set_visible_edit" value = "1"
												<?php
												if($row['visible'] == 1)
													echo "checked";
												?>>
												<label for="service_description_edit">&nbsp;Visible</label>
											</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									
							</form>
							<script>
									$.validate({
														form:'#editsetform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editsetform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#settlelist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#settlelist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "seteditalert"></div>
				
	<?php
}
if(isset($_POST['set_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$set_ref1  = 0;
	$set_ref2 = 0;
	$set_refdes1 = 0;
	$set_refdes2 = 0;
	$set_proof1 = 0;
	$set_proof2 = 0;
	$set_charge1 = 0;
	$set_charge2 = 0;
	$set_visible = 0;
	
	if(!empty($_POST['set_ref1_edit']))
		$set_ref1  = $_POST['set_ref1_edit'];
	if(!empty($_POST['set_ref2_edit']))
		$set_ref2  = $_POST['set_ref2_edit'];
	if(!empty($_POST['set_refdes1_edit']))
		$set_refdes1  = $_POST['set_refdes1_edit'];
	if(!empty($_POST['set_refdes2_edit']))
		$set_refdes2  = $_POST['set_refdes2_edit'];
	if(!empty($_POST['set_proof1_edit']))
		$set_proof1  = $_POST['set_proof1_edit'];
	if(!empty($_POST['set_proof2_edit']))
		$set_proof2  = $_POST['set_proof2_edit'];
	if(!empty($_POST['set_charge1_edit']))
		$set_charge1  = $_POST['set_charge1_edit'];
	if(!empty($_POST['set_charge2_edit']))
		$set_charge2  = $_POST['set_charge2_edit'];
	if(!empty($_POST['set_visible_edit']))
		$set_visible  = $_POST['set_visible_edit'];
		
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_settlement_type where
	(settlement_description= '$set_name_edit' or settlement_code = '$set_code_edit') and isdeleted = 0 and settlement_type_id != $set_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Update lup_settlement_type set 
		settlement_code = '$set_code_edit',
		settlement_description = '$set_name_edit',
		settlement_type_group_id = '$set_group_edit',
		with_reference_no1= $set_ref1,
		with_reference_no2 = $set_ref2,
		with_reference_description1 = $set_refdes1,
		with_reference_description2 = $set_refdes2,
		with_proof_of_payment1 = $set_proof1,
		with_proof_of_payment2 = $set_proof2,
		charge_to_account1 = $set_charge1,
		charge_to_account2 = $set_charge2,
		visible = $set_visible
		where settlement_type_id = $set_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Settlement Type has been Updated","#seteditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Settlement Type, Contact the System Administrator", "#seteditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Settlement Type Description or Code Already Exist","#seteditalert");
			</script>
		<?php
	}
	
	settlelist(0);
}

if(isset($_REQUEST['possetui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			<div class="box-header with-border">
				<h3 class="box-title">POS LOOK UPS</h3>
			</div>
			<div class = "box-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab" id = "item">ITEMS</a></li>
					  <li ><a href="#tab_1" ID = "cat" data-toggle="tab">CATEGORIES</a></li>
					  <li><a href="#tab_1" data-toggle="tab" id = "class">CLASSIFICATION</a></li>
					  <li><a href="#tab_1" data-toggle="tab" id = "dep">DEPARTMENT</a></li>
					  <li><a href="#tab_1" data-toggle="tab" id = "var" >VARIATIONS</a></li>
					  <li><a href="#tab_1" data-toggle="tab" id = "unit">UNITS</a></li>
						<li><a href="#tab_1" data-toggle="tab" id = "otype">ORDER TYPE</a></li>
						<li><a href="#tab_1" data-toggle="tab" id = "invoice">INVOICE NUMBER</a></li>
					</ul>
					<script>
						$('#tab_1').html(loading);	
						$.post( 
																		'php/main.php',
																		{
																			itemui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
						$("#var").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			stockmui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						$("#unit").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			unitui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#invoice").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			invoiceui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#cat").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			catui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#class").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			classui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#dep").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			depui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						$("#mgroup").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			mgroupui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#otype").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			otypeui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						$("#item").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			itemui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						
						
						
						
						
					</script>
					
					<div class="tab-content">
					  <div class="tab-pane active" id="tab_1">
						
					  </div>  
				
					</div>
				  </div>
			</div>
		</div>
		
        
	<?php
}

if(isset($_REQUEST['classui']))
{
		$user = get_user_id($_SESSION['c_craft']);
		$agent = get_agent($user);
		$branch = get_branch($user);
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">POS CLASSIFICATION</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Classification Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "class_code" name = "class_code" data-validation="required"
												data-validation-error-msg="Classification Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Classification Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "class_name" name = "class_name" data-validation="required"
												data-validation-error-msg="Classification Description Field is Required">
											
											</div>
										</div>
										<div class="col-md-4" style = "display:none;">
												<div class="form-group">
													<label>Department:</label>
												
													<Select class = "form-control" name = "class_dep" data-validation="required"
													data-validation-error-msg="Select Department">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_department where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['department_id'];?>"><?php echo $prow['department_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "class_visible" name = "class_visible" value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#classificationlist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#classificationlist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "classalert"></div>
				<div id = "classificationlist"><?php classificationlist($branch,0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['class_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$class_visible = 0;
	if(isset($_POST['class_visible']))
		$class_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_classification where
	(classification_description= '$class_name' or classification_code = '$class_code') and isdeleted = 0"));
	
		$user = get_user_id($_SESSION['c_craft']);
				$agent = get_agent($user);
				$branch = get_branch($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into pos_lup_classification set 
		classification_code = '$class_code',
		classification_description = '$class_name',
		branch_id = $branch,
		visible = $class_visible,
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Classification Added","#classalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Classification, Contact the System Administrator", "#classalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Classification Description or Code Already Exist","#classalert");
			</script>
		<?php
	}
	
	classificationlist($branch,0);
}
if(isset($_REQUEST['classdel']))
{
	$id = $_REQUEST['classdel'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from  pos_lup_classification where classification_id = $id"));
	$del = mysqli_query($con,"Update pos_lup_classification set isdeleted = 1 where classification_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Classification deleted","#classalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Classification Information, contact the system administrator","#classalert");
			</script>
		<?php
	}
	
	classificationlist($row['branch_id'],0);
}
if(isset($_REQUEST['classedit']))
{
	$id = $_REQUEST['classedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Classification Code: &nbsp;</label>
												<input type = "hidden" name = "class_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "class_code_edit" name = "class_code_edit" data-validation="required"
												data-validation-error-msg="Classification Code Field is Required"
												value = "<?php echo $row['classification_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Classification Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "class_name_edit" name = "class_name_edit" data-validation="required"
												data-validation-error-msg="Classification Description Field is Required"
												value = "<?php echo $row['classification_description'];?>"
												>
											
											</div>
										</div>
									
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "class_visible_edit" name = "class_visible_edit" 
												<?php
												if($row['visible'] == 1)
													echo "checked";
												?>
												value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#classificationlist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#classificationlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "classeditalert"></div>
				
	<?php
}

if(isset($_POST['class_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $class_edit_id"));

	$class_visible = 0;
	if(isset($_POST['class_visible_edit']))
		$class_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_classification where
	(classification_description= '$class_name_edit' or classification_code = '$class_code_edit') and isdeleted = 0 and classification_id != $class_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update pos_lup_classification set 
		classification_code = '$class_code_edit',
		classification_description = '$class_name_edit',
		visible = $class_visible
		where classification_id = $class_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Classification has been saved","#classeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Classification, Contact the System Administrator", "#classeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Classification Description or Code Already Exist","#classeditalert");
			</script>
		<?php
	}
	
	classificationlist($row['branch_id'],0);
}
if(isset($_REQUEST['catui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">POS CATEGORY</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Category Code Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "cat_code" name = "cat_code" data-validation="required"
												data-validation-error-msg="Category Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Category Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "cat_name" name = "cat_name" data-validation="required"
												data-validation-error-msg="Category Description Field is Required">
											
											</div>
										</div>
										<div class="col-md-4" style = "display:none;">
												<div class="form-group">
													<label>Classification:</label>
												
													<Select class = "form-control" name = "cat_class" data-validation="required"
													data-validation-error-msg="Select Classification">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_classification where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['classification_id'];?>"><?php echo $prow['classification_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "cat_visible" name = "cat_visible" value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#categorylist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#categorylist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "catalert"></div>
				<div id = "categorylist"><?php categorylist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['cat_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$class_visible = 0;
	if(isset($_POST['class_visible']))
		$class_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_category where
	(category_description= '$cat_name' or category_code = '$cat_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into pos_lup_category set 
		category_code = '$cat_code',
		category_description = '$cat_name',
		classification_id = 0,
		visible = $cat_visible,
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Category Added","#catalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Category, Contact the System Administrator", "#catalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Category Description or Code Already Exist","#catalert");
			</script>
		<?php
	}
	
	categorylist(0);
}
if(isset($_REQUEST['catdel']))
{
	$id = $_REQUEST['catdel'];
	
	$del = mysqli_query($con,"Update pos_lup_category set isdeleted = 1 where category_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Category deleted","#catalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Category Information, contact the system administrator","#catalert");
			</script>
		<?php
	}
	
	categorylist(0);
}
if(isset($_REQUEST['catedit']))
{
	$id = $_REQUEST['catedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_category where category_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Category Code: &nbsp;</label>
												<input type = "hidden" name = "cat_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "cat_code_edit" name = "cat_code_edit" data-validation="required"
												data-validation-error-msg="Category Code Field is Required"
												value = "<?php echo $row['category_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Category Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "cat_name_edit" name = "cat_name_edit" data-validation="required"
												data-validation-error-msg="Category Description Field is Required"
												value = "<?php echo $row['category_description'];?>"
												>
											
											</div>
										</div>
										<div class="col-md-4">
												<div class="form-group">
													<label>Classification:</label>
												
													<Select class = "form-control" name = "cat_class_edit" data-validation="required"
													data-validation-error-msg="Select Classification">
													<?php
														$crow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $row[classification_id]"));
													?>													
														<option value = "<?php echo $crow['classification_id'];?>" hidden "Selected"><?php echo $crow['classification_description'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_classification where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['classification_id'];?>"><?php echo $prow['classification_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "cat_visible_edit" name = "cat_visible_edit" 
												<?php
												if($row['visible'] == 1)
													echo "checked";
												?>
												value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#categorylist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#categorylist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cateditalert"></div>
				
	<?php
}

if(isset($_POST['cat_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$cat_visible = 0;
	if(isset($_POST['cat_visible_edit']))
		$cat_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_category where
	(category_description= '$cat_name_edit' or category_code = '$cat_code_edit') and isdeleted = 0 and category_id != $cat_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update pos_lup_category set 
		category_code = '$cat_code_edit',
		category_description = '$cat_name_edit',
		classification_id = $cat_class_edit,
		visible = $cat_visible
		where category_id = $cat_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Category has been saved","#cateditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Category, Contact the System Administrator", "#cateditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Category Description or Code Already Exist","#cateditalert");
			</script>
		<?php
	}
	
	categorylist(0);
}
if(isset($_REQUEST['depui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">POS DEPARTMENTS</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">DEPARTMENT Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "dep_code" name = "dep_code" data-validation="required"
												data-validation-error-msg="DEPARTMENT Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">DEPARTMENT Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "dep_name" name = "dep_name" data-validation="required"
												data-validation-error-msg="DEPARTMENT Description Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "dep_visible" name = "dep_visible" value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#departmentlist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#departmentlist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "depalert"></div>
				<div id = "departmentlist"><?php departmentlist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['dep_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$dep_visible = 0;
	if(isset($_POST['dep_visible']))
		$dep_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_department where
	(department_description= '$dep_name' or department_code = '$dep_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into pos_lup_department set 
		department_code = '$dep_code',
		department_description = '$dep_name',
		visible = $dep_visible,
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Department Added","#depalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Department, Contact the System Administrator", "#depalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Department Description or Code Already Exist","#depalert");
			</script>
		<?php
	}
	
	departmentlist(0);
}
if(isset($_REQUEST['depdel']))
{
	$id = $_REQUEST['depdel'];
	
	$del = mysqli_query($con,"Update pos_lup_department set isdeleted = 1 where department_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Department deleted","#depalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Department Information, contact the system administrator","#depalert");
			</script>
		<?php
	}
	
	departmentlist(0);
}
if(isset($_REQUEST['depedit']))
{
	$id = $_REQUEST['depedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_department where department_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Department Code: &nbsp;</label>
												<input type = "hidden" name = "dep_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "dep_code_edit" name = "dep_code_edit" data-validation="required"
												data-validation-error-msg="Department Code Field is Required"
												value = "<?php echo $row['department_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Department Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "dep_name_edit" name = "dep_name_edit" data-validation="required"
												data-validation-error-msg="Department Description Field is Required"
												value = "<?php echo $row['department_description'];?>"
												>
											
											</div>
										</div>
										
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "dep_visible_edit" name = "dep_visible_edit" 
												<?php
												if($row['visible'] == 1)
													echo "checked";
												?>
												value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#departmentlist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#departmentlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "depeditalert"></div>
				
	<?php
}

if(isset($_POST['dep_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$dep_visible = 0;
	if(isset($_POST['dep_visible_edit']))
		$dep_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_department where
	(department_description= '$dep_name_edit' or department_code = '$dep_code_edit') and isdeleted = 0 and department_id != $dep_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update pos_lup_department set 
		department_code = '$dep_code_edit',
		department_description = '$dep_name_edit',
		visible = $dep_visible
		where department_id = $dep_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Department has been saved","#depeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Department, Contact the System Administrator", "#depeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Department Description or Code Already Exist","#depeditalert");
			</script>
		<?php
	}
	
	departmentlist(0);
}
if(isset($_REQUEST['mgroupui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">POS MENU GROUP</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Menu Group Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "mg_code" name = "mg_code" data-validation="required"
												data-validation-error-msg="Menu Group Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Menu Group Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "mg_name" name = "mg_name" data-validation="required"
												data-validation-error-msg="Menu Group Description Field is Required">
											
											</div>
										</div>
								
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "mg_visible" name = "mg_visible" value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#menugrouplist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#menugrouplist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "mgalert"></div>
				<div id = "menugrouplist"><?php menugrouplist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['mg_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$mg_visible = 0;
	if(isset($_POST['mg_visible']))
		$mg_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_menu_group where
	(menu_group_description= '$mg_name' or menu_group_code = '$mg_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into pos_lup_menu_group set 
		menu_group_code = '$mg_code',
		menu_group_description = '$mg_name',
		visible = $mg_visible,
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Menu Group Added","#mgalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Menu Group, Contact the System Administrator", "#mgalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Menu Group Description or Code Already Exist","#mgalert");
			</script>
		<?php
	}
	
	menugrouplist(0);
}
if(isset($_REQUEST['mgdel']))
{
	$id = $_REQUEST['mgdel'];
	
	$del = mysqli_query($con,"Update pos_lup_menu_group set isdeleted = 1 where menu_group_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Menu Group deleted","#mgalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Menu Group Information, contact the system administrator","#mgalert");
			</script>
		<?php
	}
	
	menugrouplist(0);
}
if(isset($_REQUEST['mgedit']))
{
	$id = $_REQUEST['mgedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_menu_group where menu_group_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Menu Group Code: &nbsp;</label>
												<input type = "hidden" name = "mg_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "mg_code_edit" name = "mg_code_edit" data-validation="required"
												data-validation-error-msg="Menu Group Code Field is Required"
												value = "<?php echo $row['menu_group_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Menu Group Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "mg_name_edit" name = "mg_name_edit" data-validation="required"
												data-validation-error-msg="Menu Group Description Field is Required"
												value = "<?php echo $row['menu_group_description'];?>"
												>
											
											</div>
										</div>
										
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "mg_visible_edit" name = "mg_visible_edit" 
												<?php
												if($row['visible'] == 1)
													echo "checked";
												?>
												value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#menugrouplist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#menugrouplist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "mgeditalert"></div>
				
	<?php
}

if(isset($_POST['mg_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$mg_visible = 0;
	if(isset($_POST['mg_visible_edit']))
		$cat_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_menu_group where
	(menu_group_description= '$mg_name_edit' or menu_group_code = '$mg_code_edit') and isdeleted = 0 and menu_group_id != $mg_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update pos_lup_menu_group set 
		menu_group_code = '$mg_code_edit',
		menu_group_description = '$mg_name_edit',
		visible = $mg_visible
		where menu_group_id = $mg_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Menu Group  has been saved","#mgeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Menu Group , Contact the System Administrator", "#mgeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Menu Group Description or Code Already Exist","#mgeditalert");
			</script>
		<?php
	}
	
	menugrouplist(0);
}
if(isset($_REQUEST['otypeui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">POS ORDER TYPE</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Order Type Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "or_code" name = "or_code" data-validation="required"
												data-validation-error-msg="Order Type Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Order Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "or_name" name = "or_name" data-validation="required"
												data-validation-error-msg="Order Type Description Field is Required">
											
											</div>
										</div>
										
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#ordertypelist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#ordertypelist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "oralert"></div>
				<div id = "ordertypelist"><?php order_typelist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['or_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 

	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_order_type where
	(order_type_description= '$or_name' or order_type_code = '$or_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into pos_lup_order_type set 
		order_type_code = '$or_code',
		order_type_description = '$or_name',
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Order Type Added","#oralert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Order Type, Contact the System Administrator", "#oralert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Order Type Description or Code Already Exist","#oralert");
			</script>
		<?php
	}
	
	order_typelist(0);
}
if(isset($_REQUEST['ordel']))
{
	$id = $_REQUEST['ordel'];
	
	$del = mysqli_query($con,"Update pos_lup_order_type set isdeleted = 1 where order_type_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Order Type deleted","#oralert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Order Type Information, contact the system administrator","#oralert");
			</script>
		<?php
	}
	
	order_typelist(0);
}
if(isset($_REQUEST['oredit']))
{
	$id = $_REQUEST['oredit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_order_type where order_type_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Order Type Code: &nbsp;</label>
												<input type = "hidden" name = "or_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "or_code_edit" name = "or_code_edit" data-validation="required"
												data-validation-error-msg="Order Type Code Field is Required"
												value = "<?php echo $row['order_type_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Order Type Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "or_name_edit" name = "or_name_edit" data-validation="required"
												data-validation-error-msg="Order Type Description Field is Required"
												value = "<?php echo $row['order_type_description'];?>"
												>
											
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#ordertypelist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#ordertypelist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "oreditalert"></div>
				
	<?php
}

if(isset($_POST['or_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_order_type where
	(order_type_description= '$or_name_edit' or order_type_code = '$or_code_edit') and isdeleted = 0 and order_type_id != $or_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update pos_lup_order_type set 
		order_type_code = '$or_code_edit',
		order_type_description = '$or_name_edit'
		where order_type_id = $or_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Order Type has been saved","#oreditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Order Type, Contact the System Administrator", "#oreditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Order Type Description or Code Already Exist","#oreditalert");
			</script>
		<?php
	}
	
	order_typelist(0);
}
if(isset($_REQUEST['itemui']))
{
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">POS PRODUCTS</h3>
				</div>
			  <div class = "box-body">
				<div class="box">
					<div class = "box-body">
						<form id = "newitemform">
									
									 <div class="form-group">
									  <div class="form-row" >	
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Item Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_name" name = "item_name" data-validation="required"
												data-validation-error-msg="Item Description Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Item Short Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_sname" name = "item_sname" data-validation="required"
												data-validation-error-msg="Item Short Description Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Quantity: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_quantity" name = "item_quantity" data-validation="required"
												data-validation-error-msg="Quantity Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class = "form-group">
												<label>Item UNIT:</label>
												<?PHP
												$pquery = mysqli_query($con,"Select * from inv_lup_unit where visible = 1");
												?>
												<select name = "item_unit" id = "item_unit" class="form-control" data-validation="required"
																data-validation-error-msg="Select UNIT"
																>
																<option  hidden "Selected"></option>
															<?php
																while($prow = mysqli_fetch_assoc($pquery))
																{
															?>
																<option value = "<?php echo $prow['unit_id'];?>"><?php echo $prow['unit_description'];?></option>
															<?php
																}
															?>
												</select>
											</div>		
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group" >
												<label for="service_description_edit">Price: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_price1" name = "item_price1" data-validation="number"
												data-validation-allowing="float"  data-validation-error-msg="Price1 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group" >
												<label for="service_description_edit">Whole Sale Quantity: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_price2" name = "item_price2" data-validation="number"
												data-validation-allowing="float"  data-validation-error-msg="IPrice2 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group" >
												<label for="service_description_edit">Whole Sale Price: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_price3" name = "item_price3" data-validation="number"
												data-validation-allowing="float"  data-validation-error-msg="Price3 Field is Required" value = "0">
											
											</div>
										</div>
										
										<div class="col-md-3" style = "display:none;">
											<div class="form-group" >
												<label for="service_description_edit">Cost1: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_cost1" name = "item_cost1" data-validation="number"
												data-validation-allowing="float"  data-validation-error-msg="Cost1 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;" >
											<div class="form-group" >
												<label for="service_description_edit">Cost2: &nbsp;</label>
												<input type="hidden" class = "form-control" id = "item_cost2" name = "item_cost2" data-validation="number"
												data-validation-allowing="float"  data-validation-error-msg="Cost2 Field is Required">
											</div>
										</div>
										<div class="col-md-2" style = "padding-top:25px;">
											<div class="form-group">
												<label>
												<input type="checkbox" id = "item_openprice" name = "item_openprice" 
												value = "1">
												Open Price</label>
											</div>
										</div>
										
										
							
										<div class="col-md-4">
												<div class="form-group">
													<label>Classification:</label>
												
													<Select class = "form-control" name = "item_class" data-validation="required"
													data-validation-error-msg="Select Classification">
																										
														<option value = "" hidden "Selected"</option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_classification where isdeleted = 0 and branch_id = $branch");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['classification_id'];?>"><?php echo $prow['classification_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Department:</label>
												
													<Select class = "form-control" name = "item_dep" data-validation="required"
													data-validation-error-msg="Select Department">
																										
														<option value = "" hidden "Selected"</option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_department where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['department_id'];?>"><?php echo $prow['department_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-4">
												<div class="form-group">
													<label>Category:</label>
												
													<Select class = "form-control" name = "item_cat" data-validation="required"
													data-validation-error-msg="Select Category">
																										
														<option value = "" hidden "Selected"</option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_category where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['category_id'];?>"><?php echo $prow['category_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-2" style = "padding-top:25px;">
											<div class="form-group">
												<label>
												<input type="checkbox" id = "item_visible" name = "item_visible" 
												value = "1">
												VISIBLE</label>
											</div>
										</div>
										<div class="col-md-2" style = "padding-top:25px;display:none;">
											<div class="form-group">
												<label>
												<input type="checkbox" id = "item_inv" name = "item_inv" 
												value = "1">
												Inventory Item</label>
											</div>
										</div>
										
										
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newitemform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#itemlist").html(loading);
															
															
																		var formData = $('#newitemform')[0];
																		
																			$.ajax({
																						url: 'php/main.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#itemlist").html(data);
																					
																						},
																						error: function() 
																						{
																							alert('Sending failed');
																						} 	        
																				   });

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<div class="box">
			
					<div class="box-body" id = "invfilterui">
						<form method = "POST" id = "stockfilterform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>ITEM CODE/DESCRIPTION:</label>
									<input type="hidden" class = "form-control" id = "cstbranch" name = "cstbranch">
									<input type="text" class = "form-control" id = "cstfname" name = "cstfname">
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>CATEGORY:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_category where isdeleted = 0 order by category_description");
									?>
									<select name = "cstfcat" id = "cstfcat" class="form-control">
													<option "Selected" value = "all">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['category_id'];?>"><?php echo $prow['category_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>CLASSIFICATION:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_classification where isdeleted = 0 and branch_id = $branch order by classification_description");
									?>
									<select name = "cstfclass" id = "cstfclass" class="form-control">
													<option "Selected" value = "all">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['classification_id'];?>"><?php echo $prow['classification_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<?php
										$level = 2;
										if($level == 1)
										{
											?>
												<div class="col-md-4">
												<?php
												$cquery = mysqli_query($con,"Select * from lup_branch where isdeleted = 0");
												?>
													<div class="form-group">
														  <label for="age">BRANCH:</label>
															
															<select  name = "cstfbranch" id = "cstfbranch" class="form-control" data-validation="required"
																	data-validation-error-msg="Select Branch">
																	<option "Selected" value = "all">ALL</option>
																
																<?php
																while($crow = mysqli_fetch_assoc($cquery))
																{
																?>												
																	<option value = "<?php echo $crow['branch_id'];?>"><?php echo $crow['branch_description'];?></option>
																	
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "cstfbranch" name = "cstfbranch">
											<?php
										}
										?>
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter" >FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "print">PRINT</button>
									</div>	
							</div>
						</div>
					</form>
					<script>
							$("#print").click(
								function()
								{
									$.validate({
															form:'#stockfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'php/main.php',
																	{
																		pistfname:$("#cstfname").val(),
																		pistfcat:$("#cstfcat").val(),
																		pistfclass:$("#cstfclass").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filter").click(
								function()
								{
									$.validate({
															form:'#stockfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#stockfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#itemlist").html(loading);
																			$.ajax({
																				url :  'php/main.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#itemlist").html(data);
																					
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
			  
			  ?>
				
				<div id = "itemalert"></div>
				<div id = "itemlist" style = "overflow:auto;"></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['cstfcat']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	itemlist($cstfname,$cstfcat,$cstfclass,$cstfbranch,0);
}
if(isset($_POST['item_name']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 

								$item_openprice = 0;
								if(isset($_POST['item_openprice']))
									$item_openprice = 1;
									
								$item_visible = 0;
								if(isset($_POST['item_visible']))
									$item_visible = 1;
									
									
									$item_inv = 0;
								if(isset($_POST['item_inv']))
									$item_inv = 1;
									
								$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_item where
								(item_description= '$item_name') and isdeleted = 0"));
								
								$user = get_user_id($_SESSION['c_craft']);
								$agent = get_agent($user);
								if($check == 0)
								{
									
									$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
									$validCharNumber = strlen($validCharacters);
									 
									$length = 10;
									$result = "";
								
										for ($i = 0; $i < $length; $i++) {
											$index = mt_rand(0, $validCharNumber-1);
											$result .= $validCharacters[$index];
										}
										
									$save = mysqli_query($con,"insert into pos_lup_item set 
									item_code = '$result',
									item_description = '$item_name',
									item_short_description = '$item_sname',
									unit_id = $item_unit,
									quantity = $item_quantity,
									item_photo = '',
									item_price1 = '',
									item_price2 = '',
									item_price3 = '',
									item_cost1 = '',
									item_cost2 = '',
									open_price = '$item_openprice',
									category_id = '$item_cat',
									classification_id = '$item_class',
									department_id = '$item_dep',
									remarks = 'from_lookup',
									visible = '$item_visible ',
									isdeleted = 0,
									inventory_item = '',
									created_by_fullname = '$agent',
									created_datetime = NOW()
									");
									
									if($save)
									{
									?>
										<script>
											notify("<i class='fa fa-info'></i> New item has been saved","#itemalert");
										</script>
									<?php
									}
									else
									{
									?>
										<script>
											notify("<i class='fa fa-exclamation-triangle'></i> Error Updating item, Contact the System Administrator", "#itemalert");
										</script>
									<?php
									}
								}
								else
								{
									?>
										<script>
											notify("<i class='fa fa-exclamation-triangle'></i> Item Description or Code Already Exist","#itemalert");
										</script>
									<?php
								}
}
if(isset($_REQUEST['itemdel']))
{
	$id = $_REQUEST['itemdel'];
	
	$del = mysqli_query($con,"Update pos_lup_item set isdeleted = 1 where item_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Item deleted","#itemalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Item Information, contact the system administrator","#itemalert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['itemedit']))
{
	$id = $_REQUEST['itemedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "edititemform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="service_description_edit">Item Code: &nbsp;</label>
												<input type = "hidden" value = "<?php echo $id;?>" name = "item_edit_id">
												<input type="text" class = "form-control" id = "item_code_edit" name = "item_code_edit" data-validation="required"
												data-validation-error-msg="Item Code Field is Required"
												value = "<?php echo $row['item_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Item Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_name_edit" name = "item_name_edit" data-validation="required"
												data-validation-error-msg="Item Description Field is Required"
												value = "<?php echo $row['item_description'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Item Short Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_sname_edit" name = "item_sname_edit" data-validation="required"
												data-validation-error-msg="Item Short Description Field is Required"
												value = "<?php echo $row['item_short_description'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Quantity: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_quantity_edit" name = "item_quantity_edit" data-validation="required"
												data-validation-error-msg="Quantity Field is Required"
												value = "<?php echo $row['quantity'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class = "form-group">
												<label>Item UNIT:</label>
												<?PHP
												$pquery = mysqli_query($con,"Select * from inv_lup_unit where visible = 1");
												$sunit = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $row[unit_id]"));
												?>
												<select name = "item_unit_edit" id = "item_unit_edit" class="form-control" data-validation="required"
																data-validation-error-msg="Select UNIT"
																>
																<option  value = "<?php echo $sunit['unit_id'];?>"hidden "Selected"><?php echo $sunit['unit_description'];?></option>
															<?php
																while($prow = mysqli_fetch_assoc($pquery))
																{
															?>
																<option value = "<?php echo $prow['unit_id'];?>"><?php echo $prow['unit_description'];?></option>
															<?php
																}
															?>
												</select>
											</div>		
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="service_description_edit">Price: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_price1_edit" name = "item_price1_edit" data-validation="number"
												data-validation-error-msg="Price Field is Required" data-validation-allowing="float" 
												value = "<?php echo $row['item_price1'];?>">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="service_description_edit">Whole Sale Quantity: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_price2_edit" name = "item_price2_edit" data-validation="number"
												data-validation-error-msg="Whole Sale Quantity Field is Required" data-validation-allowing="float" 
												value = "<?php echo $row['item_price2'];?>">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="service_description_edit">Whole Sale Price: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_price3_edit" name = "item_price3_edit" data-validation="number"
												data-validation-error-msg="Whole Sale Price Field is Required" data-validation-allowing="float" 
												value = "<?php echo $row['item_price3'];?>">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="service_description_edit">Cost1: &nbsp;</label>
												<input type="text" class = "form-control" id = "item_cost1_edit" name = "item_cost1_edit" data-validation="number"
												data-validation-error-msg="Cost1 Field is Required" data-validation-allowing="float" 
												value = "<?php echo $row['item_cost1'];?>">
											
											</div>
										</div>
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="service_description_edit">Cost2: &nbsp;</label>
												<input type="hidden" class = "form-control" id = "item_cost2_edit" name = "item_cost2_edit" data-validation="number"
												data-validation-error-msg="Cost2 Field is Required" data-validation-allowing="float" 
												value = "<?php echo $row['item_cost2'];?>">
											
											</div>
										</div>
										<div class="col-md-2" style = "padding-top:25px;">
											<div class="form-group">
												<label>
												<input type="checkbox" id = "item_openprice_edit" name = "item_openprice_edit" 
												<?php
												if($row['open_price'] == 1)
													echo "checked";
												?>
												value = "1">
												Open Price</label>
											</div>
										</div>
									
							
										<div class="col-md-4">
												<div class="form-group">
													<label>Classification:</label>
												
													<Select class = "form-control" name = "item_class_edit" data-validation="required"
													data-validation-error-msg="Select Classification">
													<?php
														$crow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $row[classification_id]"));
													?>													
														<option value = "<?php echo $crow['classification_id'];?>" hidden "Selected"><?php echo $crow['classification_description'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_classification where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['classification_id'];?>"><?php echo $prow['classification_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Department:</label>
												
													<Select class = "form-control" name = "item_dep_edit" data-validation="required"
													data-validation-error-msg="Select Department">
																										
													<?php
														$crow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_department where department_id = $row[department_id]"));
													?>													
														<option value = "<?php echo $crow['department_id'];?>" hidden "Selected"><?php echo $crow['department_description'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_department where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['department_id'];?>"><?php echo $prow['department_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-4">
												<div class="form-group">
													<label>Category:</label>
												
													<Select class = "form-control" name = "item_cat_edit" data-validation="required"
													data-validation-error-msg="Select Category">
																										
													<?php
														$crow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_category where category_id = $row[category_id]"));
													?>													
														<option value = "<?php echo $crow['category_id'];?>" hidden "Selected"><?php echo $crow['category_description'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_lup_category where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['category_id'];?>"><?php echo $prow['category_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-2" style = "padding-top:25px;">
											<div class="form-group">
												<label>
												<input type="checkbox" id = "item_visible_edit" name = "item_visible_edit" 
												<?php
												if($row['visible'] == 1)
													echo "checked";
												?>
												value = "1">
												VISIBLE</label>
											</div>
										</div>
										<div class="col-md-2" style = "padding-top:25px;display:none;">
											<div class="form-group">
												<label>
												<input type="checkbox" id = "item_inv_edit" name = "item_inv_edit" 
												<?php
												if($row['inventory_item'] == 1)
													echo "checked";
												?>
												value = "1">
												Inventory Item</label>
											</div>
										</div>
										
										
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#edititemform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#itemlist").html(loading);
															
															
																		var formData = $('#edititemform')[0];
																		
																			$.ajax({
																						url: 'php/main.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#itemlist").html(data);
																					
																						},
																						error: function() 
																						{
																							alert('Sending failed');
																						} 	        
																				   });

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "itemeditalert"></div>
				
	<?php
}

if(isset($_POST['item_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$item_visible = 0;
	if(isset($_POST['item_visible_edit']))
		$item_visible = 1;
		
	$item_openprice_edit = 0;
	if(isset($_POST['item_openprice_edit']))
		$item_openprice_edit = 1;
	
	$item_inv = 0;
	if(isset($_POST['item_inv_edit']))
		$item_inv = 1;
		
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_item where
	(item_description= '$item_name_edit' or item_code = '$item_code_edit') and isdeleted = 0 and item_id != $item_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
									$save = mysqli_query($con,"Update pos_lup_item set 
									item_code = '$item_code_edit',
									item_description = '$item_name_edit',
									item_short_description = '$item_sname_edit',
									unit_id = $item_unit_edit,
									quantity = $item_quantity_edit,
									item_price1 = '$item_price1_edit',
									item_price2 = '$item_price2_edit',
									item_price3 = '$item_price3_edit',
									item_cost1 = '$item_cost1_edit',
									item_cost2 = '$item_cost2_edit',
									open_price = '$item_openprice_edit',
									category_id = '$item_cat_edit',
									classification_id = '$item_class_edit',
									department_id = '$item_dep_edit',
									visible = '$item_visible',
									inventory_item = '$item_inv',
									edited_by = '$agent',
									edited_datetime = NOW()
									where item_id = $item_edit_id
									");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Item has been saved","#itemeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Item, Contact the System Administrator", "#itemeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Item Description or Code Already Exist","#itemeditalert");
			</script>
		<?php
	}
}
if(isset($_POST['barcodeeditid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_item where
	item_code = '$barcodedit' and isdeleted = 0 and item_id != $barcodeeditid"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
									$save = mysqli_query($con,"Update pos_lup_item set 
									item_code = '$barcodeedit',
									edited_by = '$agent',
									edited_datetime = NOW()
									where item_id = $barcodeeditid
									");
	
		if($save)
		{
		?>
			<script>
				alert("ITEM CODE has been save");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				alert(Error Updating Item Code, Contact the System Administrator");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				alert("Item Code Already Exist");
			</script>
		<?php
	}
}

if(isset($_REQUEST['showphoto']))
{
	$id = $_REQUEST['showphoto'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select item_photo from pos_lup_item where item_id = $id"));
	?>
	<div class="box">
			<div class="box-body">
				<form id = 'changeitemform' method = "POST" enctype="multipart/form-data">
					<div class = "row">
						<div class="col-md-4">
									<label>ITEM PHOTO:</label>
									<div class="input-group">
									<input type = "hidden" name = "ipid" value = "<?php echo $id;?>">
										<input type="file" class="form-control" name = "citemphoto" id = "citemphoto" data-validation="required"
														data-validation-error-msg="ITEM PHOTO Field is required">
									</div>
						</div>
						<div class="col-md-2" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">CHANGE</button>
									</div>	
						</div>
							
					</div>
				</form>
				<script>
					
												$.validate({
															form:'#changeitemform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																var formData = $('#changeitemform')[0];
																$.ajax({
																						url: 'php/main.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#itemphotoui").html(data);
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
						
				</script>
			</div>
	</div>
	<div class="box">
			<div class="box-body" id = "itemphotoui">
				<img src = "images/items/<?php echo $row['item_photo'];?>" width  = "100%">
			</div>
	</div>
	<?php
}
if(isset($_POST['ipid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$name = $_FILES['citemphoto']['name'];
		$type = $_FILES['citemphoto']['type'];
		$size = $_FILES['citemphoto']['size'];
	
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['citemphoto']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					if (!file_exists('images/items/')) {
						mkdir('../images/items/', 0777, true);
					}
					$sourcePath = $_FILES['citemphoto']['tmp_name'];
					$targetPath = "../images/items/".basename($sss);

						if(is_uploaded_file($_FILES['citemphoto']['tmp_name'])) 
						{
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									
									mysqli_query($con,"Update pos_lup_item set item_photo = '$sss' where 
									item_id = $ipid");
								}
							
						}
					
				}
				else
				{
					echo "
						<script>
							alert('The Image File is too Large, The Allowed File Size is 6mb');
						</script>
					";
				}
			}
			else
			{
					echo "
						<script>
							alert('Invalid Image Extension.JPEG and PNG Only');
						</script>
					";
			}
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select item_photo from pos_lup_item where item_id = $ipid"));
		?>
		<img src = "images/items/<?php echo $row['item_photo'];?>" width  = "100%">
		<?php
			
}
if(isset($_REQUEST['branchphotui']))
{
	$id = $_REQUEST['branchphotui'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select branch_photo from lup_branch where branch_id = $id"));
	?>
	<div class="box">
			<div class="box-body">
				<form id = 'changeitemform' method = "POST" enctype="multipart/form-data">
					<div class = "row">
						<div class="col-md-4">
									<label>BRANCH PHOTO:</label>
									<div class="input-group">
									<input type = "hidden" name = "bpid" value = "<?php echo $id;?>">
										<input type="file" class="form-control" name = "cbranchphoto" id = "cbranchphoto" data-validation="required"
														data-validation-error-msg="BRANCH PHOTO Field is required">
									</div>
						</div>
						<div class="col-md-2" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">CHANGE</button>
									</div>	
						</div>
							
					</div>
				</form>
				<script>
					
												$.validate({
															form:'#changeitemform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																var formData = $('#changeitemform')[0];
																$.ajax({
																						url: 'php/main.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#branchphotoui").html(data);
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
						
				</script>
			</div>
	</div>
	<div class="box">
			<div class="box-body" id = "branchphotoui">
				<img src = "images/branch_photo/<?php echo $row['branch_photo'];?>" width  = "100%">
			</div>
	</div>
	<?php
}
if(isset($_POST['bpid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$name = $_FILES['cbranchphoto']['name'];
		$type = $_FILES['cbranchphoto']['type'];
		$size = $_FILES['cbranchphoto']['size'];
	
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['cbranchphoto']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					if (!file_exists('../images/branch_photo/')) {
						mkdir('../images/branch_photo/', 0777, true);
					}
					$sourcePath = $_FILES['cbranchphoto']['tmp_name'];
					$targetPath = "../images/branch_photo/".basename($sss);

						if(is_uploaded_file($_FILES['cbranchphoto']['tmp_name'])) 
						{
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									
									mysqli_query($con,"Update lup_branch set branch_photo = '$sss' where 
									branch_id = $bpid");
								}
							
						}
					
				}
				else
				{
					echo "
						<script>
							alert('The Image File is too Large, The Allowed File Size is 6mb');
						</script>
					";
				}
			}
			else
			{
					echo "
						<script>
							alert('Invalid Image Extension.JPEG and PNG Only');
						</script>
					";
			}
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select branch_photo from lup_branch where branch_id = $bpid"));
		?>
		<img src = "images/branch_photo/<?php echo $row['branch_photo'];?>" width  = "100%">
		<?php
			
}
if(isset($_REQUEST['cpersonui']))
{
	$id = $_REQUEST['cpersonui'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $id"));
	?>
	<div class = "row">
		<div class="col-md-6">
			<div class="box">
					<div class="box-body">
						<form id = 'changeitemform' method = "POST" enctype="multipart/form-data">
							<div class = "row">
								<div class="col-md-6">
											<label>CONTACT PERSON 1 PHOTO:</label>
											<div class="input-group">
											<input type = "hidden" name = "cpid" value = "<?php echo $id;?>">
												<input type="file" class="form-control" name = "cpphoto" id = "cpphoto" data-validation="required"
																data-validation-error-msg="CONTACT PERSON 1 PHOTO Field is required">
											</div>
								</div>
								<div class="col-md-2" style = "padding-top:25px;">
											<div class = "form-group">
												<button class = "btn btn-success btn-flat" id = "filter">CHANGE</button>
											</div>	
								</div>
									
							</div>
						</form>
						<script>
							
														$.validate({
																	form:'#changeitemform',
																	validateOnBlur : false,
																	errorMessagePosition : 'top',
																	modules : 'security',
																	onSuccess : function($form) {
																		var formData = $('#changeitemform')[0];
																		$.ajax({
																								url: 'php/main.php',
																								type: "POST",
																								data:  new FormData(formData),
																								contentType: false,
																								cache: false,
																								processData:false,
																								success: function(data)
																								{
																									$("#ccpphotoui1").html(data);
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
								
						</script>
					</div>
			</div>
			<div class="box">
					<div class="box-body" id = "ccpphotoui1">
						<img src = "images/branch_contact/<?php echo $row['contact_person_photo1'];?>" width  = "100%">
					</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box">
					<div class="box-body">
						<form id = 'changeitemform2' method = "POST" enctype="multipart/form-data">
							<div class = "row">
								<div class="col-md-6">
											<label>CONTACT PERSON 2 PHOTO:</label>
											<div class="input-group">
											<input type = "hidden" name = "cpid2" value = "<?php echo $id;?>">
												<input type="file" class="form-control" name = "cpphoto2" id = "cpphoto2" data-validation="required"
																data-validation-error-msg="CONTACT PERSON 2 PHOTO Field is required">
											</div>
								</div>
								<div class="col-md-2" style = "padding-top:25px;">
											<div class = "form-group">
												<button class = "btn btn-success btn-flat" id = "filter">CHANGE</button>
											</div>	
								</div>
									
							</div>
						</form>
						<script>
							
														$.validate({
																	form:'#changeitemform2',
																	validateOnBlur : false,
																	errorMessagePosition : 'top',
																	modules : 'security',
																	onSuccess : function($form) {
																		var formData = $('#changeitemform2')[0];
																		$.ajax({
																								url: 'php/main.php',
																								type: "POST",
																								data:  new FormData(formData),
																								contentType: false,
																								cache: false,
																								processData:false,
																								success: function(data)
																								{
																									$("#ccpphotoui2").html(data);
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
								
						</script>
					</div>
			</div>
			<div class="box">
					<div class="box-body" id = "ccpphotoui2">
						<img src = "images/branch_contact/<?php echo $row['contact_person_photo2'];?>" width  = "100%">
					</div>
			</div>
		</div>
		
	</div>
	
	<?php
}


if(isset($_POST['cpid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$name = $_FILES['cpphoto']['name'];
		$type = $_FILES['cpphoto']['type'];
		$size = $_FILES['cpphoto']['size'];
	
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['cpphoto']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					if (!file_exists('../images/branch_contact/')) {
						mkdir('../images/branch_contact/', 0777, true);
					}
					$sourcePath = $_FILES['cpphoto']['tmp_name'];
					$targetPath = "../images/branch_contact/".basename($sss);

						if(is_uploaded_file($_FILES['cpphoto']['tmp_name'])) 
						{
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									
									mysqli_query($con,"Update lup_branch set contact_person_photo1 = '$sss' where 
									branch_id = $cpid");
								}
							
						}
					
				}
				else
				{
					echo "
						<script>
							alert('The Image File is too Large, The Allowed File Size is 6mb');
						</script>
					";
				}
			}
			else
			{
					echo "
						<script>
							alert('Invalid Image Extension.JPEG and PNG Only');
						</script>
					";
			}
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select contact_person_photo1 from lup_branch where branch_id = $cpid"));
		?>
		<img src = "images/branch_contact/<?php echo $row['contact_person_photo1'];?>" width  = "100%">
		<?php
			
}
if(isset($_POST['cpid2']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$name = $_FILES['cpphoto2']['name'];
		$type = $_FILES['cpphoto2']['type'];
		$size = $_FILES['cpphoto2']['size'];
	
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['cpphoto2']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					if (!file_exists('../images/branch_contact/')) {
						mkdir('../images/branch_contact/', 0777, true);
					}
					$sourcePath = $_FILES['cpphoto2']['tmp_name'];
					$targetPath = "../images/branch_contact/".basename($sss);

						if(is_uploaded_file($_FILES['cpphoto2']['tmp_name'])) 
						{
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									
									mysqli_query($con,"Update lup_branch set contact_person_photo2 = '$sss' where 
									branch_id = $cpid2");
								}
							
						}
					
				}
				else
				{
					echo "
						<script>
							alert('The Image File is too Large, The Allowed File Size is 6mb');
						</script>
					";
				}
			}
			else
			{
					echo "
						<script>
							alert('Invalid Image Extension.JPEG and PNG Only');
						</script>
					";
			}
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select contact_person_photo2 from lup_branch where branch_id = $cpid2"));
		?>
		<img src = "images/branch_contact/<?php echo $row['contact_person_photo2'];?>" width  = "100%">
		<?php
			
}
if(!empty($_REQUEST['possettingsui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			<div class="box-header with-border">
				<h3 class="box-title">POS SETTINGS</h3>
			</div>
			<div class = "box-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
					  <li class="active"><a href="#tab_1" ID = "cline" data-toggle="tab">CREDIT LINE</a></li>
					  <li><a href="#tab_1" data-toggle="tab" id = "ctype">CUSTOMER TYPE WITH CREDIT LINE</a></li>
					  <li><a href="#tab_1" data-toggle="tab" id = "otype">ORDER TYPE SETTINGS</a></li>
					   <li><a href="#tab_1" data-toggle="tab" id = "rec">PRINT RECEIPT SETTINGS</a></li>
					    <li><a href="#tab_1" data-toggle="tab" id = "rec" style = "display:none;">RECEIPT HEADER</a></li>
						 <li><a href="#tab_1" data-toggle="tab" id = "rebate">REBATE</a></li>
						 <li><a href="#tab_1" data-toggle="tab" id = "smapping">SETTLEMENT MAPPING</a></li>
					</ul>
					<script>
						$('#tab_1').html(loading);	
						$.post( 
																		'php/main.php',
																		{
																			clineui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
																		
						$("#rec").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			recprintui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
																		
						$("#cline").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			clineui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#footer").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			sfooterui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						$("#header").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			sheaderui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#ctype").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			clctypeui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						$("#rebate").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/main.php',
																		{
																			rebateui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						
						
					
						
						
						
						
						
						
					</script>
					
					<div class="tab-content">
					  <div class="tab-pane active" id="tab_1">
						
					  </div>  
				
					</div>
				  </div>
			</div>
		</div>
		
        
	<?php
}
if(isset($_REQUEST['clineui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">CREDIT LINE </h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Credit line days to due: &nbsp;</label>
												<input type="text" class = "form-control" id = "cline_days" name = "cline_days" data-validation="number"
												data-validation-error-msg="Credit line days to due is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Credit Line with Penalty: &nbsp;</label>
												<input type="text" class = "form-control" id = "cline_penalty" name = "cline_penalty" data-validation="number"
												data-validation-allowing="float" data-validation-error-msg="Credit Line with Penalty Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Credit Line Penalty to apply: &nbsp;</label>
												<input type="text" class = "form-control" id = "cline_apply" name = "cline_apply" data-validation="number"
												data-validation-allowing="float" data-validation-error-msg="Credit Line Penalty to apply Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-4">
												<div class="form-group">
													<label>Settlement Type:</label>
												
													<Select class = "form-control" name = "cline_settle" data-validation="required"
													data-validation-error-msg="Select Settlement Type">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_settlement_type where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['settlement_type_id'];?>"><?php echo $prow['settlement_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#classificationlist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#clinelist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "clinealert"></div>
				<div id = "clinelist"><?php clinelist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['cline_days']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$class_visible = 0;
	if(isset($_POST['class_visible']))
		$class_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_credit_line where credit_line_payment_type_id = $cline_settle and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into settings_credit_line set 
		credit_line_days_to_due= '$cline_days',
		credit_line_with_penalty = '$cline_penalty',
		credit_line_penalty_to_apply = '$cline_apply',
		credit_line_payment_type_id = '$cline_settle',
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Credit Line Settings Added","#clinealert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Credit Line Settings, Contact the System Administrator", "#clinealert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Selected Settlement type already have a credit line settings ","#clinealert");
			</script>
		<?php
	}
	
	clinelist(0);
}
if(isset($_REQUEST['clinedel']))
{
	$id = $_REQUEST['clinedel'];
	
	$del = mysqli_query($con,"Update settings_credit_line set isdeleted = 1 where settings_credit_line_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Credit Line deleted","#clinealert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Credit Line Information, contact the system administrator","#clinealert");
			</script>
		<?php
	}
	
	clinelist(0);
}
if(isset($_REQUEST['clineedit']))
{
	$id = $_REQUEST['clineedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_credit_line where settings_credit_line_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Credit line days to due: &nbsp;</label>
												<input type = "hidden" name = "cline_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "cline_days_edit" name = "cline_days_edit" data-validation="number"
												data-validation-error-msg="Credit line days to due is Required"
												value = "<?php echo $row['credit_line_days_to_due'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Credit Line with Penalty: &nbsp;</label>
												<input type="text" class = "form-control" id = "cline_penalty_edit" name = "cline_penalty_edit" data-validation="number"
												data-validation-allowing="float" data-validation-error-msg="Credit Line with Penalty Field is Required"
												value = "<?php echo $row['credit_line_with_penalty'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Credit Line Penalty to apply: &nbsp;</label>
												<input type="text" class = "form-control" id = "cline_apply_edit" name = "cline_apply_edit" data-validation="number"
												data-validation-allowing="float" data-validation-error-msg="Credit Line Penalty to apply Field is Required"
												value = "<?php echo $row['credit_line_penalty_to_apply'];?>">
											
											</div>
										</div>
										
										<div class="col-md-4">
												<div class="form-group">
													<label>Settlement Type:</label>
												
													<Select class = "form-control" name = "cline_settle_edit" data-validation="required"
													data-validation-error-msg="Select Settlement Type">
													<?php
														$srow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[credit_line_payment_type_id]"));
													?>
														<option value = "<?php echo $srow['settlement_type_id'];?>" hidden "Selected"><?php echo $srow['settlement_description'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_settlement_type where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['settlement_type_id'];?>"><?php echo $prow['settlement_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#classificationlist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#clinelist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "clineeditalert"></div>
				
	<?php
}

if(isset($_POST['cline_edit_id']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_credit_line where credit_line_payment_type_id = $cline_settle_edit and settings_credit_line_id != $cline_edit_id"));
	if($check == 0)
	{
		$save = mysqli_query($con,"Update settings_credit_line set 
		credit_line_days_to_due= '$cline_days_edit',
		credit_line_with_penalty = '$cline_penalty_edit',
		credit_line_penalty_to_apply = '$cline_apply_edit',
		credit_line_payment_type_id = '$cline_settle_edit'
		where settings_credit_line_id = $cline_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Credit Line Settings has been saved","#clineeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Credit Line Settings, Contact the System Administrator", "#clineeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Credit Line Settings Description or Code Already Exist","#clineeditalert");
			</script>
		<?php
	}
	
	clinelist(0);
}
if(isset($_REQUEST['sfooterui']))
{
	
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">RECEIPT FOOTER SETTINGS </h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
												<div class="form-group">
													<label>Branch:</label>
												
													<Select class = "form-control" name = "footer_branch" data-validation="required"
													data-validation-error-msg="Select Branch">
														<option value = "" hidden "Selected"> </option>
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
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 1: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line1" name = "footer_line1" data-validation="required"
												data-validation-error-msg="Line 1 Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 2: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line2" name = "footer_line2" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 2 Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 3: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line3" name = "footer_line3" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 3 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 4: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line4" name = "footer_line4" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 4 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 5: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line5" name = "footer_line5" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 5 Field is Required">
											
											</div>
										</div>
										
										
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#footerlist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#footerlist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "footeralert"></div>
				<div id = "footerlist"><?php footerlist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['footer_branch']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_pos_receipt_footer where branch_id = $footer_branch and isdeleted = 0"));
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into settings_pos_receipt_footer set 
		branch_id= '$footer_branch',
		line1 = '$footer_line1',
		line2 = '$footer_line2',
		line3 = '$footer_line3',
		line4 = '$footer_line4',
		line5 = '$footer_line5',
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Branch Receipt Footer Settings Added","#footeralert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Branch Receipt Footer, Contact the System Administrator", "#footeralert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Branch Footer Setting Already Exists ","#footeralert");
			</script>
		<?php
	}
	
	footerlist(0);
}
if(isset($_REQUEST['footerdel']))
{
	$id = $_REQUEST['footerdel'];
	
	$del = mysqli_query($con,"Update settings_pos_receipt_footer set isdeleted = 1 where pos_receipt_footer_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Branch Receipt Footer deleted","#footeralert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Branch Receipt Footer, contact the system administrator","#footeralert");
			</script>
		<?php
	}
	
	footerlist(0);
}
if(isset($_REQUEST['footeredit']))
{
	$id = $_REQUEST['footeredit'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from  settings_pos_receipt_footer where pos_receipt_footer_id = $id"));
	
	?>
		<div class="box" style = "margin-top:10px;">
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
												<div class="form-group">
													<label>Branch:</label>
													<input type = "hidden" value = "<?php echo $id;?>" name = "footer_edit_id">
													<Select class = "form-control" name = "footer_branch_edit" data-validation="required"
													data-validation-error-msg="Select Branch">
														<?php
														$brow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"))
														?>
														<option value = "<?php echo $brow['branch_id'];?>" hidden "Selected"><?php echo $brow['branch_description'];?></option>
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
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 1: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line1_edit" name = "footer_line1_edit" data-validation="required"
												data-validation-error-msg="Line 1 Field is Required"
												value = "<?php echo $row['line1'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 2: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line2_edit" name = "footer_line2_edit" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 2 Field is Required"
												value = "<?php echo $row['line2'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 3: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line3_edit" name = "footer_line3_edit" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 3 Field is Required"
												value = "<?php echo $row['line3'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 4: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line4_edit" name = "footer_line4_edit" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 4 Field is Required"
												value = "<?php echo $row['line4'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 5: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line5_edit" name = "footer_line5_edit" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 5 Field is Required"
												value = "<?php echo $row['line5'];?>">
											
											</div>
										</div>
										
										
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#footerlist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#footerlist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "footereditalert"></div>
						
			  </div>
		</div>
	<?php
}
if(isset($_POST['footer_edit_id']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_pos_receipt_footer where branch_id = $footer_branch_edit and pos_receipt_footer_id != $footer_edit_id "));
	if($check == 0)
	{
		$save = mysqli_query($con,"Update settings_pos_receipt_footer set 
		branch_id= '$footer_branch_edit',
		line1 = '$footer_line1_edit',
		line2 = '$footer_line2_edit',
		line3 = '$footer_line3_edit',
		line4 = '$footer_line4_edit',
		line5 = '$footer_line5_edit'
		where pos_receipt_footer_id = $footer_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Branch Receipt Footer Settings Added","#footereditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Branch Receipt Footer, Contact the System Administrator", "#footereditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Branch Footer Setting Already Exists ","#footereditalert");
			</script>
		<?php
	}
	
	footerlist(0);
}

if(isset($_REQUEST['sheaderui']))
{
	
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">RECEIPT HEADER SETTINGS </h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
												<div class="form-group">
													<label>Branch:</label>
												
													<Select class = "form-control" name = "header_branch" data-validation="required"
													data-validation-error-msg="Select Branch">
														<option value = "" hidden "Selected"> </option>
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
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 1: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line1" name = "footer_line1" data-validation="required"
												data-validation-error-msg="Line 1 Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 2: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line2" name = "footer_line2" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 2 Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 3: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line3" name = "footer_line3" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 3 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 4: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line4" name = "footer_line4" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 4 Field is Required">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 5: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line5" name = "footer_line5" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 5 Field is Required">
											
											</div>
										</div>
										
										
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#headerlist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#headerlist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "footeralert"></div>
				<div id = "headerlist"><?php headerlist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['header_branch']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_pos_receipt_header where branch_id = $header_branch and isdeleted = 0"));
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into settings_pos_receipt_header set 
		branch_id= '$header_branch',
		line1 = '$footer_line1',
		line2 = '$footer_line2',
		line3 = '$footer_line3',
		line4 = '$footer_line4',
		line5 = '$footer_line5',
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Branch Receipt Header Settings Added","#footeralert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Branch Receipt Header, Contact the System Administrator", "#footeralert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Branch Header Setting Already Exists ","#footeralert");
			</script>
		<?php
	}
	
	headerlist(0);
}
if(isset($_REQUEST['headerdel']))
{
	$id = $_REQUEST['headerdel'];
	
	$del = mysqli_query($con,"Update settings_pos_receipt_header set isdeleted = 1 where pos_receipt_header_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Branch Receipt Header deleted","#footeralert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Branch Receipt Header, contact the system administrator","#footeralert");
			</script>
		<?php
	}
	
	headerlist(0);
}
if(isset($_REQUEST['headeredit']))
{
	$id = $_REQUEST['headeredit'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from  settings_pos_receipt_header where pos_receipt_header_id = $id"));
	
	?>
		<div class="box" style = "margin-top:10px;">
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
												<div class="form-group">
													<label>Branch:</label>
													<input type = "hidden" value = "<?php echo $id;?>" name = "header_edit_id">
													<Select class = "form-control" name = "header_branch_edit" data-validation="required"
													data-validation-error-msg="Select Branch">
														<?php
														$brow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"))
														?>
														<option value = "<?php echo $brow['branch_id'];?>" hidden "Selected"><?php echo $brow['branch_description'];?></option>
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
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 1: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line1_edit" name = "footer_line1_edit" data-validation="required"
												data-validation-error-msg="Line 1 Field is Required"
												value = "<?php echo $row['line1'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 2: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line2_edit" name = "footer_line2_edit" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 2 Field is Required"
												value = "<?php echo $row['line2'];?>">
											
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 3: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line3_edit" name = "footer_line3_edit" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 3 Field is Required"
												value = "<?php echo $row['line3'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 4: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line4_edit" name = "footer_line4_edit" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 4 Field is Required"
												value = "<?php echo $row['line4'];?>">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">Line 5: &nbsp;</label>
												<input type="text" class = "form-control" id = "footer_line5_edit" name = "footer_line5_edit" data-validation="required"
												data-validation-allowing="float" data-validation-error-msg="Line 5 Field is Required"
												value = "<?php echo $row['line5'];?>">
											
											</div>
										</div>
										
										
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#headerlist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#headerlist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "footereditalert"></div>
						
			  </div>
		</div>
	<?php
}
if(isset($_POST['header_edit_id']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_pos_receipt_header where branch_id = $header_branch_edit and pos_receipt_header_id != $header_edit_id "));
	if($check == 0)
	{
		$save = mysqli_query($con,"Update settings_pos_receipt_header set 
		branch_id= '$header_branch_edit',
		line1 = '$footer_line1_edit',
		line2 = '$footer_line2_edit',
		line3 = '$footer_line3_edit',
		line4 = '$footer_line4_edit',
		line5 = '$footer_line5_edit'
		where pos_receipt_header_id = $header_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Branch Receipt Header Settings Saved","#footereditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Branch Receipt Header, Contact the System Administrator", "#footereditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Branch Header Setting Already Exists ","#footereditalert");
			</script>
		<?php
	}
	
	headerlist(0);
}
if(isset($_REQUEST['clctypeui']))
{
	
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">CUSTOMER TYPE WITH CREDIT LINE</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
												<div class="form-group">
													<label>Customer Type:</label>
												
													<Select class = "form-control" name = "cl_ctype" data-validation="required"
													data-validation-error-msg="Select Customer Type">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_customer_type where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['customer_type_id'];?>"><?php echo $prow['customer_type_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label >
												<input type="checkbox" id = "cl_with" name = "cl_with" value = "1">
												With Credit Line</label>
											</div>
										</div>
										
										
										
										
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#clctypelist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#clctypelist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "clalert"></div>
				<div id = "clctypelist"><?php clctypelist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['cl_ctype']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$cl_with = 0;
	
	if(isset($_POST['cl_with']))
		$cl_with = $_POST['cl_with'];
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_customer_type where customer_type_id = $cl_ctype and isdeleted = 0"));
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into settings_customer_type set 
		customer_type_id= '$cl_ctype',
		with_credit_line = '$cl_with',
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Customer Type Settings Added","#clalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving Customer Type Settings, Contact the System Administrator", "#clalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Customer Type Settings Already Exists ","#clalert");
			</script>
		<?php
	}
	
	clctypelist(0);
}
if(isset($_REQUEST['cldel']))
{
	$id = $_REQUEST['cldel'];
	
	$del = mysqli_query($con,"Update settings_customer_type set isdeleted = 1 where settings_customer_type_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Branch Receipt Header deleted","#clalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Branch Receipt Header, contact the system administrator","#clalert");
			</script>
		<?php
	}
	
	clctypelist(0);
}
if(isset($_REQUEST['cledit']))
{
	$id = $_REQUEST['cledit'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from  settings_customer_type where settings_customer_type_id = $id"));
	
	?>
		<div class="box" style = "margin-top:10px;">
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
												<div class="form-group">
													<label>Customer Type:<?php echo $row['customer_type_id'];?></label>
													<input type ="hidden" name = "cl_edit_id" value = "<?php echo $id;?>">
													<Select class = "form-control" name = "cl_ctype_edit" data-validation="required"
													data-validation-error-msg="Select Customer Type">
													<?php
														$crow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type_id]"));
													?>													
														<option value = "<?php echo $crow['customer_type_id'];?>" hidden "Selected"><?php echo $crow['customer_type_name'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_customer_type where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['customer_type_id'];?>"><?php echo $prow['customer_type_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label >
												<input type="checkbox" id = "cl_with_edit" name = "cl_with_edit" value = "1"
												<?php
												if($row['with_credit_line'] == 1)
													echo "checked";
												?>
												>
												With Credit Line</label>
											</div>
										</div>
										
										
										
										
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#clctypelist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#clctypelist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cleditalert"></div>
						
			  </div>
		</div>
	<?php
}
if(isset($_POST['cl_edit_id']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$cl_with = 0;
	
	if(isset($_POST['cl_with_edit']))
		$cl_with = $_POST['cl_with_edit'];
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_customer_type where customer_type_id = $cl_ctype_edit and isdeleted = 0 and settings_customer_type_id != $cl_edit_id"));
	if($check == 0)
	{
		$save = mysqli_query($con,"Update settings_customer_type set 
		customer_type_id= '$cl_ctype_edit',
		with_credit_line = '$cl_with'
		where settings_customer_type_id = $cl_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Customer Type Settings has been saved","#cleditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving Customer Type Settings, Contact the System Administrator", "#cleditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Customer Type Settings Already Exists ","#cleditalert");
			</script>
		<?php
	}
	
	clctypelist(0);
}

if(isset($_REQUEST['rebateui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">REBATE SETTINGS </h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">REBATE AMOUNT: &nbsp;</label>
												<input type="text" class = "form-control" id = "rb_amount" name = "rb_amount" data-validation="number"
												data-validation-allowing="float" 
												data-validation-error-msg="Credit line days to due is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">REBATE POINT: &nbsp;</label>
												<input type="text" class = "form-control" id = "rb_point" name = "rb_point" data-validation="number"
												data-validation-allowing="float" data-validation-error-msg="Credit Line with Penalty Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">REFERRAL POINT: &nbsp;</label>
												<input type="text" class = "form-control" id = "rb_ref" name = "rb_ref" data-validation="number"
												data-validation-allowing="float" data-validation-error-msg="Credit Line Penalty to apply Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-4">
												<div class="form-group">
													<label>Settlement Type:</label>
												
													<Select class = "form-control" name = "rb_settle" data-validation="required"
													data-validation-error-msg="Select Settlement Type">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_settlement_type where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['settlement_type_id'];?>"><?php echo $prow['settlement_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#rebatelist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#rebatelist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "rbalert"></div>
				<div id = "rebatelist"><?php rebatelist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_REQUEST['enrec']))
{
	mysqli_query($con,"Update settings_receipt_print set iscurrent = 0");
	mysqli_query($con,"insert into settings_receipt_print set enable = 1, date_added = NOW(), iscurrent = 1");
	print_rec_settings();
}
if(isset($_REQUEST['disrec']))
{
	mysqli_query($con,"Update settings_receipt_print set iscurrent = 0");
	mysqli_query($con,"insert into settings_receipt_print set enable = 0, date_added = NOW(), iscurrent = 1");
	print_rec_settings();
}
if(isset($_REQUEST['recprintui']))
{
	$check = mysqli_num_rows(mysqli_query($con,"Select * from settings_receipt_print"));
	if($check == 0)
	{
		mysqli_query($con,"insert into settings_receipt_print set date_added = NOW(), iscurrent = 1");
	}
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">PRINT RECEIPT SETTINGS </h3>
				</div>
			  <div class = "box-body">
					<div class="box" id = "recui">
					<div class = "box-body">
						<?php echo print_rec_settings();?>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "rbalert"></div>		
			  </div>
		</div>
	<?php
}

if(isset($_POST['rb_amount']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_rebate where rebate_settlement_type_id = $rb_settle and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into settings_rebate set 
		rebate_amount= '$rb_amount',
		rebate_point = '$rb_point',
		referral_point = '$rb_ref',
		rebate_settlement_type_id = '$rb_settle',
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Rebate Settings Added","#rbalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Rebate Settings, Contact the System Administrator", "#rbalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Selected Settlement type already have a Rebate settings ","#rbalert");
			</script>
		<?php
	}
	
	rebatelist(0);
}
if(isset($_REQUEST['rbdel']))
{
	$id = $_REQUEST['rbdel'];
	
	$del = mysqli_query($con,"Update settings_rebate set isdeleted = 1 where settings_rebate_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Rebate Settings deleted","#rbalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Rebate Settings, contact the system administrator","#rbalert");
			</script>
		<?php
	}
	
	rebatelist(0);
}
if(isset($_REQUEST['rbedit']))
{
	$id = $_REQUEST['rbedit'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_rebate where settings_rebate_id = $id"));
	?>
		<div class="box" style = "margin-top:10px;">
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<input type = "hidden" value = "<?php echo $id;?>" name = "rb_edit_id">
												<label for="service_description_edit">REBATE AMOUNT: &nbsp;</label>
												<input type="text" class = "form-control" id = "rb_amount_edit" name = "rb_amount_edit" data-validation="number"
												data-validation-allowing="float" 
												data-validation-error-msg="Credit line days to due is Required"
												value = "<?php echo $row['rebate_amount'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">REBATE POINT: &nbsp;</label>
												<input type="text" class = "form-control" id = "rb_point_edit" name = "rb_point_edit" data-validation="number"
												data-validation-allowing="float" data-validation-error-msg="Credit Line with Penalty Field is Required"
												value = "<?php echo $row['rebate_point'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">REFERRAL POINT: &nbsp;</label>
												<input type="text" class = "form-control" id = "rb_ref_edit" name = "rb_ref_edit" data-validation="number"
												data-validation-allowing="float" data-validation-error-msg="Credit Line Penalty to apply Field is Required"
												value = "<?php echo $row['referral_point'];?>">
											
											</div>
										</div>
										
										<div class="col-md-4">
												<div class="form-group">
													<label>Settlement Type:</label>
												
													<Select class = "form-control" name = "rb_settle_edit" data-validation="required"
													data-validation-error-msg="Select Settlement Type">
													<?php
														$rrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[rebate_settlement_type_id]"));
													?>
														<option value = "<?php echo $rrow['settlement_type_id'];?>" hidden "Selected"><?php echo $rrow['settlement_description'];?></option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_settlement_type where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['settlement_type_id'];?>"><?php echo $prow['settlement_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
								
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#rebatelist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#rebatelist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "rbeditalert"></div>
		
			  </div>
		</div>
	<?php
}
if(isset($_POST['rb_amount_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from settings_rebate where rebate_settlement_type_id = $rb_settle_edit and setting_rebate_id != $rb_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Update settings_rebate set 
		rebate_amount= '$rb_amount_edit',
		rebate_point = '$rb_point_edit',
		referral_point = '$rb_ref_edit',
		rebate_settlement_type_id = '$rb_settle_edit'
		where settings_rebate_id = $rb_edit_id

		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Rebate Settings Saved","#rbeditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Rebate Settings, Contact the System Administrator", "#rbeditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Selected Settlement type already have a Rebate settings ","#rbeditalert");
			</script>
		<?php
	}
	
	rebatelist(0);
}
if(isset($_REQUEST['invoiceui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">SALES INVOICE NUMBER GENERATION</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
				
							<div class="col-md-4">
								 <div class="form-group">								
									<label for="age">No of Copy:</label>
									<input type="text" id="inno" name="inno" class="form-control" autocomplete="off"
									data-validation="number" data-validation-error-msg="No of Copy field is required"
									value = "1">
												
								</div>
							</div>
							
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									
							</form>
							<script>
									$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#invlist").html(loading);
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#invlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "invalert"></div>
				<div id = "invlist"><?php invoice(0);?></div>				
			  </div>
		</div>
	<?php
}
if(!empty($_POST['inno']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
								
	$countt = mysqli_num_rows(mysqli_query($con,"Select * from lup_invoice_number where branch_id = $branch"));
	
	if($countt == 0)
		$countt = 0;
		
	$c = 1;
	while($c<=$inno)
	{				
			$countt++;
									$total_enrolled = $countt;
								
								
									$count = "";
									if($total_enrolled < 10)
									{
										$count = "000000000";
									}
									else if($total_enrolled >= 10 && $total_enrolled < 100)
									{
										$count = "00000000";
									}
									else if($total_enrolled >= 100 && $total_enrolled < 1000)
									{
										$count = "0000000";
									}
									else if($total_enrolled >= 1000 && $total_enrolled < 10000)
									{
										$count = "000000";
									}
									else if($total_enrolled >= 10000 && $total_enrolled < 100000)
									{
										$count = "00000";
									}
									else if($total_enrolled >= 100000 && $total_enrolled < 1000000)
									{
										$count = "0000";
									}
									else if($total_enrolled >= 1000000 && $total_enrolled < 10000000)
									{
										$count = "000";
									}
									else if($total_enrolled >= 10000000 && $total_enrolled < 100000000)
									{
										$count = "00";
									}
								else if($total_enrolled >= 100000000 && $total_enrolled < 1000000000)
									{
										$count = "0";
									}
									else
									{
										$count = "";
									}
									
								$idno = $count.$countt;
								
								mysqli_query($con,"insert into lup_invoice_number set
									invoice_number = '$idno',
									pos_sales_id = 0,
									branch_id = $branch,
									user_id = $user
								");
								
		$c++;
	}
	?>
			<script>
				notify("Invoice List Generated","#invalert");
			</script>
	<?php
	
	invoice(0);
								
}
if(!empty($_REQUEST['cpdel']))
{
	$id = $_REQUEST['cpdel'];
	
	$del = mysqli_query($con,"Update card_profile set isdeleted = 1 where card_profile_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Card Profile deleted","#cprofilealert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Card Profile,contact the system administrator","#cprofilealert");
			</script>
		<?php
	}
	
	cardprofilelist(0);
}
if(isset($_REQUEST['order_statusui']))
{
	$id = $_REQUEST['order_statusui'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_order_type where order_type_id = $id"));
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title"><?php echo $row['order_type_description'];?> ORDER STATUS</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Order STATUS DESCRPTION: &nbsp;</label>
												<input type = "hidden" value = "<?php echo $id;?>" name = "osid">
												<input type="text" class = "form-control" id = "osdesc" name = "osdesc" data-validation="required"
												data-validation-error-msg="Order Type Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">SMS TEMPLATE: &nbsp;</label>
															<select  name = "ossms" id = "ossms" class="form-control" data-validation="required"
																	data-validation-error-msg="Select Branch">
																	<option hidden "Selected" value = ""></option>
																
																<?php
																$cquery = mysqli_query($con,"Select * from sms_lup_message_template where isdeleted = 0");
																while($crow = mysqli_fetch_assoc($cquery))
																{
																?>												
																	<option value = "<?php echo $crow['message_template_id'];?>"><?php echo $crow['message_template_description'];?></option>

																<?php
																}
																?>
															</select>
											
											</div>
										</div>
										
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															
															
															 var formData = $('#newcardtypeform').serializeArray();
															 $("#order_status_list").html(loading);
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#order_status_list").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "osalert"></div>
				<div id = "order_status_list"><?php order_status_list($id,0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['osid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 

	$check = mysqli_num_rows(mysqli_query($con,"Select * from pos_order_type_status where
	status_description= '$osdesc' and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into pos_order_type_status set 
		status_description = '$osdesc',
		sms_template_id = $ossms,
		order_type_id = $osid,
		added_by = '$user'
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Order Status Added","#osalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Order Status, Contact the System Administrator", "#osalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Order Status Description Already Exist","#osalert");
			</script>
		<?php
	}
	
	order_status_list($osid,0);
}

if(isset($_REQUEST['sstedit']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 

	$check = mysqli_num_rows(mysqli_query($con,"Select * from pos_order_type_status where
	status_description= '$sstdes' and isdeleted = 0 and pos_order_type_status_id != $sstedit"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	if($check == 0)
	{
		$save = mysqli_query($con,"update pos_order_type_status set 
		status_description = '$sstdes',
		sms_template_id = $sstsms
		where pos_order_type_status_id = $sstedit
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Order Status updated","#osalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Order Status, Contact the System Administrator", "#osalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Order Status Description Already Exist","#osalert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['sstdel']))
{
	$id = $_REQUEST['sstdel'];
	
	$del = mysqli_query($con,"Update pos_order_type_status set isdeleted = 1 where pos_order_type_status_id = $id");
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_order_type_status where pos_order_type_status_id = $id"));
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Order Status deleted","#osalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Order Status, contact the system administrator","#osalert");
			</script>
		<?php
	}
	
	order_status_list($row['order_type_id'],0);
}
if(isset($_REQUEST['supplierui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">SUPPLIERS</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Supplier Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "supplier_code" name = "supplier_code" data-validation="required"
												data-validation-error-msg="Supplier Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Supplier Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "supplier_name" name = "supplier_name" data-validation="required"
												data-validation-error-msg="Supplier Description Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "supplier_visible" name = "supplier_visible" value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#categorylist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#categorylist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "catalert"></div>
				<div id = "categorylist"><?php supplierlist(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['supplier_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$supplier_visible = 0;
	if(isset($_POST['supplier_visible']))
		$supplier_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_supplier where
	(supplier_description= '$supplier_name' or supplier_code = '$supplier_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into lup_supplier set 
		supplier_code = '$supplier_code',
		supplier_description = '$supplier_name',
		visible = $supplier_visible,
		created_modified = NOW(),
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Supplier Added","#catalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Supplier, Contact the System Administrator", "#catalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Supplier Description or Code Already Exist","#catalert");
			</script>
		<?php
	}
	
	supplierlist(0);
}
if(isset($_REQUEST['supplierdel']))
{
	$id = $_REQUEST['supplierdel'];
	
	$del = mysqli_query($con,"Update lup_supplier set isdeleted = 1 where supplier_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Supplier deleted","#catalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting supplier Information, contact the system administrator","#catalert");
			</script>
		<?php
	}
	
	supplierlist(0);
}
if(isset($_REQUEST['supplieredit']))
{
	$id = $_REQUEST['supplieredit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_supplier where supplier_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Supplier Code: &nbsp;</label>
												<input type = "hidden" name = "supplier_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "supplier_code_edit" name = "supplier_code_edit" data-validation="required"
												data-validation-error-msg="Supplier Code Field is Required"
												value = "<?php echo $row['supplier_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Supplier Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "supplier_name_edit" name = "supplier_name_edit" data-validation="required"
												data-validation-error-msg="Supplier Description Field is Required"
												value = "<?php echo $row['supplier_description'];?>"
												>
											
											</div>
										</div>
										
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "supplier_visible_edit" name = "supplier_visible_edit" 
												<?php
												if($row['visible'] == 1)
													echo "checked";
												?>
												value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#categorylist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#categorylist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cateditalert"></div>
				
	<?php
}

if(isset($_POST['supplier_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$supplier_visible = 0;
	if(isset($_POST['supplier_visible_edit']))
		$supplier_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from lup_supplier where
	(supplier_description= '$supplier_name_edit' or supplier_code = '$supplier_code_edit') and isdeleted = 0 and supplier_id != $supplier_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update lup_supplier set 
		supplier_code = '$supplier_code_edit',
		supplier_description = '$supplier_name_edit',
		visible = $supplier_visible
		where supplier_id = $supplier_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Supplier has been saved","#cateditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Supplier, Contact the System Administrator", "#cateditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Supplier Description or Code Already Exist","#cateditalert");
			</script>
		<?php
	}
	
	supplierlist(0);
}
if(isset($_REQUEST['unitui']))
{
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">UNITS</h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">UNIT Code: &nbsp;</label>
												<input type="text" class = "form-control" id = "unit_code" name = "unit_code" data-validation="required"
												data-validation-error-msg="UNIT Code Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">UNIT Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "unit_name" name = "unit_name" data-validation="required"
												data-validation-error-msg="UNIT Description Field is Required">
											
											</div>
										</div>
										
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "unit_visible" name = "unit_visible" value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
									
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#categorylist").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#categorylist").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "catalert"></div>
				<div id = "categorylist"><?php units(0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['unit_code']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$unit_visible = 0;
	if(isset($_POST['unit_visible']))
		$unit_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from inv_lup_unit where
	(unit_description= '$unit_name' or unit_code = '$unit_code') and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"insert into inv_lup_unit set 
		unit_code = '$unit_code',
		unit_description = '$unit_name',
		visible = $unit_visible,
		isdeleted = 0
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Unit Added","#catalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Unit, Contact the System Administrator", "#catalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Unit Description or Code Already Exist","#catalert");
			</script>
		<?php
	}
	
	Units(0);
}
if(isset($_REQUEST['unitdel']))
{
	$id = $_REQUEST['unitdel'];
	
	$del = mysqli_query($con,"Update inv_lup_unit set isdeleted = 1 where unit_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Unit deleted","#catalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Unit Information, contact the system administrator","#catalert");
			</script>
		<?php
	}
	
	units(0);
}
if(isset($_REQUEST['unitedit']))
{
	$id = $_REQUEST['unitedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $id"));
	?>
					<div class="box">
					<div class = "box-body">
						<form id = "editcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Unit Code: &nbsp;</label>
												<input type = "hidden" name = "unit_edit_id" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "unit_code_edit" name = "unit_code_edit" data-validation="required"
												data-validation-error-msg="Unit Code Field is Required"
												value = "<?php echo $row['unit_code'];?>">
											
											</div>
										</div>
										
										<div class="col-md-5">
											<div class="form-group">
												<label for="service_description_edit">Unit Description: &nbsp;</label>
												<input type="text" class = "form-control" id = "unit_name_edit" name = "unit_name_edit" data-validation="required"
												data-validation-error-msg="Unit Description Field is Required"
												value = "<?php echo $row['unit_description'];?>"
												>
											
											</div>
										</div>
										
										<div class="col-md-4" style = "padding-top:25px;">
											<div class="form-group">
												<input type="checkbox" id = "unit_visible_edit" name = "unit_visible_edit" 
												<?php
												if($row['visible'] == 1)
													echo "checked";
												?>
												value = "1">
												<label for="service_description_edit">&nbsp;VISIBLE</label>
											</div>
										</div>
										
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">SAVE</button>							
										 
										</div>

									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#editcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#editcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#categorylist").html(loading);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#categorylist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cateditalert"></div>
				
	<?php
}

if(isset($_POST['unit_code_edit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$unit_visible = 0;
	if(isset($_POST['unit_visible_edit']))
		$unit_visible = 1;
	$check = mysqli_num_rows(mysqli_query($con, "Select * from inv_lup_unit where
	(unit_description= '$unit_name_edit' or unit_code = '$unit_code_edit') and isdeleted = 0 and unit_id != $unit_edit_id"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	if($check == 0)
	{
		$save = mysqli_query($con,"update inv_lup_unit set 
		unit_code = '$unit_code_edit',
		unit_description = '$unit_name_edit',
		visible = $unit_visible
		where unit_id = $unit_edit_id
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Unit has been saved","#cateditalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Unit, Contact the System Administrator", "#cateditalert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Unit Description or Code Already Exist","#cateditalert");
			</script>
		<?php
	}
	
	units(0);
}

if(isset($_REQUEST['pistfcat']))
{
	?>
		<div id = "printt">
		<?php
			foreach($_POST as $key=>$val) {
				${$key} = $val;
				//echo "The value of ".$key." is ". $val." <br>";
			}
			
			$pro = "";
			$cat = "ALL";
			$class = "ALL";		
			if($pistfcat != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_category where category_id = $pistfcat"));
				$cat = $prow['category_description'];
			}
			if($pistfclass != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $pistfclass"));
				$class = $prow['classification_description'];
			}
		?>
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">ITEM LIST</h4>
			
			
			<table class = "table table-bordered table-hover table-sm">
				<tr>
					<td>CATEGORY:<?php echo $cat;?></td>
					<td>CLASSIFICATION:<?php echo $class;?></td>
				</tr>
			</table>
			<?php
				itemlist($pistfname,$pistfcat,$pistfclass,1);
				$user = get_user_id($_SESSION['c_craft']);
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
if(!empty($_REQUEST['stockmui']))
{
	$level = $_REQUEST['stockmui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	?>
		
		<div class="box box-warning">
			
			<div class="box-body">
					<form method = "POST" id = "newstockform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>PRODUCTrr:</label>
									<input type = "hidden" value = "<?php echo $level;?>" name = "ilevel">
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_item where isdeleted = 0 and branch_id = $branch and addon_id = 0 order by item_description");
									?>
									<select name = "iproduct" id = "iproduct" class="form-control" data-validation="required"
													data-validation-error-msg="Select PRODUCT">
													<option  hidden "Selected"></option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['item_id'];?>"><?php echo $prow['item_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>DESCRIPTION:</label>
									 <input type="text" class="form-control" name = "ides" id = "ides" data-validation="required" data-validation-error-msg="Enter DESCRIPTION">
	
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>PRICE:</label>
									 <input type="text" class="form-control" name = "iprice" id = "iprice" data-validation="number"
													data-validation-error-msg="Enter PRICE"
													data-validation-allowing="float">
								</div>		
							</div>
							
							
							
										
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" >SAVE</button>
									
									</div>	
							</div>
						</div>
					</form>
					<script>
												$.validate({
															form:'#newstockform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#newstockform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#stockmui2").html(loading);
																			$.ajax({
																				url :  'php/main.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#stockmui2").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
					</script>					
						
					
				
			</div>
		</div>
		<div class="box box-warning">
			
			<div class="box-body" id = "invfilterui">
						<form method = "POST" id = "ifilterform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<input type = "hidden" value = "<?php echo $br;?>" name = "ifbranch">
									<input type = "hidden" value = "<?php echo $level;?>" name = "iflevel">
					
									<label>PRODUCT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_item where isdeleted = 0 and branch_id = $branch and addon_id = 0 order by item_description");
									?>
									<select name = "ifproduct" id = "ifproduct" class="form-control">
													<option "Selected">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['item_id'];?>"><?php echo $prow['item_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" >FILTER</button>
							
									
									</div>	
							</div>
						</div>
					</form>
					<script>
							$("#ifcancel").click(
								function(e)
								{
									e.preventDefault();
									$('#invfilterui').html(loading);
									$.post( 
										'php/inventory.php',
										{
																	
											cancelifilter:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									
								}
							);
												$.validate({
															form:'#ifilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#ifilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#stockmui2").html(loading);
																			$.ajax({
																				url :  'php/main.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#stockmui2").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
					</script>
			</div>
		</div>
		<div id = "inv_alert"></div>
		<div id = "stockmui2">
			<?PHP //variations("","","","",1);?>
		</div>
	<?php
}
if(isset($_POST['iproduct']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
		$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 5;
			$result = "";
			$found = 1;
			
			while($found == 1)
			{
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				$found = mysqli_num_rows(mysqli_query($con, "Select * from lup_variations where ref_no = '$result'"));
				if($found == 0)
					break;
				else
					$i = 0;
			}
	$check = mysqli_num_rows(mysqli_query($con,"Select * from lup_variations where item_id = $iproduct
	and description = '$ides' and isdeleted = 0"));
	
	if($check == 0)
	{
		$save = mysqli_query($con,"INSERT INTO `lup_variations`(`ref_no`, `item_id`, `description`, `unit_price`) VALUES ('$result',$iproduct,'$ides',$iprice)");
		if($save)
		{
			?>
				<script>
					notify("Variation Saved","#inv_alert");
				</script>
			<?php
		}
		else
		{
			?>
				<script>
					notify("Error Saving New Variation,Please Contact the System Administrator","#inv_alert");
				</script>
			<?php
		}
	
	
	}
	else
	{
			?>
				<script>
					notify("Variation Description already Exists","#inv_alert");
				</script>
			<?php
	}
}
if(isset($_REQUEST['edititransaction']))
{
	$id = $_REQUEST['edititransaction'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_variations where variation_id = $id"));
	?>
		
		<div class="box box-warning">
			
			<div class="box-body">
					<form method = "POST" id = "editstockform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<input type = "hidden" value = "<?php echo $id;?>" name = "inveditid">
									<label>PRODUCT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_item where isdeleted = 0 order by item_description");
									$psel = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $row[item_id]"));
									?>
									<select name = "iproductedit" id = "iproductedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select PRODUCT">
													<option  value = "<?php echo $psel['item_id'];?>" hidden "Selected"> <?php echo $psel['item_description'];?></option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['item_id'];?>"><?php echo $prow['item_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>DESCRIPTION:</label>
									 <input type="text" class="form-control" name = "idesedit" id = "idesedit" data-validation="required" data-validation-error-msg="Enter DESCRIPTION"
									 value = "<?php echo $row['description'];?>">
	
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>PRICE:</label>
									 <input type="text" class="form-control" name = "ipriceedit" id = "ipriceedit" data-validation="number"
													data-validation-error-msg="Enter Price" value  ="<?php echo $row['unit_price'];?>"
													data-validation-allowing="float,negative">
								</div>		
							</div>
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" >SAVE</button>
									</div>	
							</div>
						</div>
					</form>
					<script>
												$.validate({
															form:'#editstockform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#editstockform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#stockmui2").html(loading);
																			$.ajax({
																				url :  'php/main.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
																				success : function(data) {
																					$("#stockmui2").html(data);
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
					</script>					
						
					
				
			</div>
		</div>
		<div id = "inveditalert"></div>
<?php
}
if(isset($_POST['iproductedit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
			$user = get_user_id($_SESSION['c_craft']);
			
			$save = mysqli_query($con,"Update lup_variations set
			item_id = $iproductedit,
			description = '$idesedit',
			unit_price = $ipriceedit
			where variation_id = $inveditid");
			
	
	if($save)
	{
		?>
			<script>
				notify("Variation Info Updated","#inveditalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("Error Updating Variation Info ,Please Contact the System Administrator","#inveditalert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['itransactiondelete']))
{
	$id = $_REQUEST['itransactiondelete'];
	$count = $_REQUEST['ivardeletecount'];
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	mysqli_query($con,"Update lup_variations set isdeleted = 1 where variation_id = $id");
	
	?>
		<script>
			$("#invactions<?php echo $count;?>").html("VARIATION DELETED");
		</script>
	<?php
}
if(isset($_POST['ifproduct']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
	<div class="box box-warning">
		<div class="box-body">
			<?php
			variations($ifproduct,'','','',1);
			?>
		</div>
	</div>
	<?php
	
}
if(isset($_REQUEST['addonui']))
{
	$id = $_REQUEST['addonui'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $id"));
	?>
		<section class="content-header">
			<h1>
				<?php echo $row['classification_description'];?> Add-Ons
			</h1>
			
		</section>
			 <section class="content">
				<div class="box" style = "margin-top:10px;">
					<div class = "box-body">
					<div class="box">
					<div class = "box-body">
							<form id = "newaddonform">
									 <div class="form-group">
										<div class="form-row" >
											<div class="col-md-3">
												<div class="form-group">
													<label for="service_description_edit">DESCRIPTION: &nbsp;</label>
													<input type="hidden" class = "form-control" id = "addonid" name = "addonid" value = "<?php echo $id;?>">
													<input type="text" class = "form-control" id = "addondes" name = "addondes" data-validation="required"
													data-validation-error-msg="DESCRIPTION Field is Required">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="service_description_edit">PRICE: &nbsp;</label>
													<input type="text" class = "form-control" id = "addonprice" name = "addonprice" data-validation="number"
													data-validation-error-msg="PRICE Field is Required">
												</div>
											</div>
											<div class="col-md-3" style = "padding-top:25px;">
												<button class = "btn btn-success btn-flat">SAVE</button>							
											</div>
									  </div>
									</div>
							</form>
							<script>
									$.validate({
														form:'#newaddonform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#addonlistui").html(loading);
															 var formData = $('#newaddonform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/main.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#addonlistui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
					</div>
				</div>
				<?php
			  
			  ?>
				
				<div id = "cardtypealert"></div>
					
			  </div>
		</div>
		<div class="box" style = "margin-top:10px;">
			  <div class = "box-body" id = "addonlistui">
				<?php add_ons($id,1,0);?>
			  </div>
		</div>
			 </section>
		
	<?php
}
if(isset($_POST['addondes']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_item where
	item_description = '$addondes' and addon_id = $addonid and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	if($check == 0)
	{
		$save = mysqli_query($con,"Insert into pos_lup_item set 
		item_description = '$addondes',
		item_price1 = '$addonprice',
		addon_id = $addonid
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Add ons Added","#cardtypealert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Add ons, Contact the System Administrator", "#cardtypealert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Add ons  Description or Code Already Exist","#cardtypealert");
			</script>
		<?php
	}
	
	add_ons($addonid,1,0);
}

if(isset($_REQUEST['addondel']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$del = mysqli_query($con,"Update pos_lup_item set isdeleted = 1 where item_id = $addondel");
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Add On deleted","#cardtypealert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting  Add On Information, contact the system administrator","#cardtypealert");
			</script>
		<?php
	}
	?>
		<script>
			$("#actionui<?php echo $addoncount;?>").html("DELETED");
		</script>
	<?php
}
if(isset($_POST['addonedit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from pos_lup_item where
	item_description = '$addoneditdes' and item_id != $addonedit and isdeleted = 0"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	if($check == 0)
	{
		$save = mysqli_query($con,"Update pos_lup_item set 
		item_description = '$addoneditdes',
		item_price1 = '$addoneditprice',
		item_id = $addonedit
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Add ons Updated","#cardtypealert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Updating Add ons, Contact the System Administrator", "#cardtypealert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Add ons  Description Exist","#cardtypealert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['tableui']))
{
	$level = $_REQUEST['tableui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>TABLES</H2>
		<div class="box">
			<div class="box-body">
				<form id = "newuserform">
					<div class="row">
										<div class="col-md-3">
											<label for="lname">TABLE DESCRIPTION:</label>
											<input type="text" name="tname" class="form-control"data-validation="required"
													data-validation-error-msg="Enter TABLE DESCRIPTION">
											<div id = "search_result"></div>
										 
										</div>
										<?php
										if($level == 1)
										{
										?>
										<div class="col-md-3">
												<div class="form-group">
													<label>BRANCH:</label>
												
													<Select class = "form-control" name = "branch" data-validation="required"
													data-validation-error-msg="Select BRANCH">
														<option value = "" hidden "Selected"> </option>
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
				<?php tables($level);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['tname']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from se_user where fullname = '$tname' and branch_id = $branch and isdeleted = 0"));
	//$pass = md5(sha1($agent_id));
	if($check == 0)
	{
		$save = mysqli_query($con,"insert into se_user set branch_id = $branch,
		fullname = '$tname',istable = 1");
		
		if($save)
		{
					?>
						<script>
							notify("New Table Added","#useralert");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					notify("Error Creating Table Info, Please Contact the system administrator","#useralert");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				notify("Table Already Exists","#useralert");
			</script>
		<?php
	}
	tables(1);
}
if(isset($_REQUEST['edit_tableid']))
{
	$level = $_REQUEST['edit_tablelevel'];
	$id = $_REQUEST['edit_tableid'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $id"));
	?>
		<div class="box">
			<div class="box-body">
				<form id = "edituserform">
					<div class="row">
										<div class="col-md-5">
										  
											<label for="lname">TABLE DESCRIPTION:</label>
											<input type = "hidden" name = "tableeditid" value = "<?php echo $id;?>">
											<input type="text" name="tablename_edit" class="form-control"data-validation="required"
													data-validation-error-msg="Enter TABLE DESCRIPTION" value = "<?php echo $row['fullname'];?>">
											<div id = "search_result"></div>
										 
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
if(isset($_POST['tableeditid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from se_user where fullname = '$tablename_edit'
	and user_id != $tableeditid"));
	//$pass = md5(sha1($agent_id));
	if($check == 0)
	{
		$save = mysqli_query($con,"update se_user set 
		fullname = '$tablename_edit' where user_id = $tableeditid");
		
		if($save)
		{
					?>
						<script>
							notify("Table Name Updated","#usereditalert");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					notify("Error Updating Table Name, Please Contact the system administrator","#usereditalert");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				notify("Table Name Already Exists","#useralert");
			</script>
		<?php
	}
	tables(1);
}
if(isset($_REQUEST['tabledelete']))
{
	$id = $_REQUEST['tabledelete'];
	mysqli_query($con, "Update se_user set isdeleted = 1 where user_id = $id");
	tables(1);
}

?>
