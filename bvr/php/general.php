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
	
var loading = '<div style = "width:100%; height:100px;text-align:center;padding-top:30px;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i>';

$('#modal').on('hidden.bs.modal', function (e) {
  $("#modalui").html('');
});
$('#modal2').on('hidden.bs.modal', function (e) {
  $("#modalui2").html('');
});
$('#modal3').on('hidden.bs.modal', function (e) {
  $("#modalui3").html('');
});

$('#modal4').on('hidden.bs.modal', function (e) {
  $("#modalui4").html('');
});
</script>

<?php
function modules($personnel)
{
	global $con;
	$query = mysqli_query($con, "Select * from se_user_access_module where user_id = '$personnel' and isdeleted = 0");
	
?>
	<table class="table table-bordered table-sm" id = "userlisttable">
		<thead>
			<th>#</th>
			<th>MODULE</th>
			<th>SUBMODULE</th>
			<th>ACCESS LEVEL</th>
			<th></th>
		</thead>
	<?PHP
	$ctr = 1;
	while($row = mysqli_fetch_assoc($query))
	{
	?>
		<tr>
			<td><?php echo $ctr;?></td>
			<td>
			<?php
				if($row['module_id'] != 'all')
				{
					$mod = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_module where module_id = $row[module_id]"));
					
					echo $mod["module_name"];
				}
				else
				{
					echo "ALL";
				}
			?>
			</td>
				<td>
			<?php
				if($row['sub_module_id'] != 'all')
				{
						$submod = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_sub_module where sub_module_id = $row[sub_module_id]"));
					
						echo $submod["sub_module_name"];
				}
				else
				{
					echo "ALL";
				}
			?>
			</td>
			<td>
				<?php
					if($row['access_level'] == 1)
					{
						echo "FULL ACCESS";
					}
					else if($row['access_level'] == 2)
					{
						echo "READ/WRITE";
					}
					else
					{
						echo "READ ONLY";
					}
				?>
			</td>
					<td><a href = "#" class = "btn btn-danger btn-flat btn-sm" id = "assigndel<?php echo $ctr;?>">DELETE</a></td>
			<script>
				$("#assigndel<?php echo $ctr;?>").click(
					function(e)
					{
						e.preventDefault();
						
						
						var id = '<?php echo $row['user_access_module_id'];?>';
						
						var r = confirm("Confirm Delete");
						
						if(r == true)
						{
							 $.post( 
							 'php/main.php',
							 {
								 assigndel:id
								
							},
							 function(data) {
								$('#modlist').html(data);
							 });
						}
					}
				);
			</script>
		</tr>
	<?php
		$ctr++;
	}
	?>
	</table>
<?php
}
function users($level)
{
		global $con;
		$user = get_user_id($_SESSION['c_craft']);
		$agent = get_agent($user);
		$branch = get_branch($user);
		
		$level == 1;
		if($level == 1)
			$query = mysqli_query($con,"Select * from se_user order by agent_number");
		else
			$query = mysqli_query($con,"Select * from se_user where branch_id = $branch order by agent_number");	
	?>
		<table class = "table table-striped table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>USER ID NUMBER</th>
									<th>FULL NAME</th>
									<th>TEAM</th>
									<th style = "display:none;">BRANCH</th>
									<th>ACTION</TH>
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$team = mysqli_fetch_assoc(mysqli_query($con, "Select * from lup_team where team_id = $row[team_id]"));
				//$branch = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['agent_number'];?></td>
						<td><?php echo $row['fullname'];?></td>
						<td><?php 
							if(!empty($temp))
								echo $team['team_name'];
							else
								echo "NONE";
							?></td>
						<td style = "display:none;"><?php echo $branch['branch_description'];?></td>
						<td>
							<button class = "btn btn-success btn-flat btn-xs" id = "assign<?php echo $ctr;?>">MODULE ASSIGNMENT</button>	
							<button class = "btn btn-danger btn-flat btn-xs" id = "report<?php echo $ctr;?>" style = "display:none;">REPORT ASSISGMENT</button>	
							<button class = "btn btn-primary btn-flat btn-xs" id = "reset<?php echo $ctr;?>">RESET</button>	
							<button class = "btn btn-warning btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
						</td>
					</tr>
					<script>
						$("#report<?php echo $ctr;?>").click(
														function(e)
														{
															e.preventDefault();
														
															
															$("#modal").modal("show");
															$("#modalbody").css("min-width","60%");
															
															$.post( 
																'php/main.php',
																{
																	assignreport:'<?php echo $row['user_id'];?>'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
													);
													
						$("#assign<?php echo $ctr;?>").click(
														function(e)
														{
															e.preventDefault();
														
															
															$("#modal").modal("show");
															$("#modalbody").css("min-width","60%");
															
															$.post( 
																'php/main.php',
																{
																	assignmod:'<?php echo $row['user_id'];?>'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
													);
													
						$("#edit<?php echo $ctr;?>").click(
														function(e)
														{
															e.preventDefault();
														
															
															$("#modal").modal("show");
															$("#modalbody").css("min-width","60%");
															
															$.post( 
																'php/main.php',
																{
																	edit_userid:'<?php echo $row['user_id'];?>',
																	edit_userlevel:'<?php echo $level;?>'
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
function agent_info($id)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_user where user_id = $id"));
	
	?>
			<table class = "table table-condensed table-sm">
								<tr>
									<td>Agent Number: <b><?php echo $row['agent_number'];?></b></td>
									<td>Full Name: <b><?php echo $row['fullname'];?></b></td>
								</tr>
								
			</table>
	<?php
}
function get_user_id($username)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_username = '$username'"));
	
	return $row['user_id'];
}
function get_agent($username)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $username"));
	
	return $row['fullname'];
}
function get_branch($username)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $username"));
	
	return $row['branch_id'];
}
function get_branch_name($username)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $username"));
	
	return $row['branch_desription'];
}
?>