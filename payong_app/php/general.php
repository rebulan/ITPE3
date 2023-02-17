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
function daily_weather($dfrom,$dto,$location,$level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "SELECT 
		forecast_daily_details.daily_details_id as DailyDetailsID,
		forecast_daily_details.forecast_date as ForecastDate,
		lup_locations.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription,
		forecast_daily_details.daily_forecast_rainfall as RainFall,
		lup_rainfall_legends.color as RainFallColorCode,
		forecast_daily_details.daily_forecast_rainfall_percentage as RainFallPercentage,
		lup_rainpercentage_legends.color as RainFallPercentageColorCode,
		forecast_daily_details.daily_forecast_low_temp as LowTemp,
		forecast_daily_details.daily_forecast_lowtemp_hex as LowTempColorCode,
		forecast_daily_details.daily_forecast_high_temp as HighTemp,
		forecast_daily_details.daily_forecast_hightemp_hex as HighTempColorCode
		FROM forecast_daily_details, lup_rainfall_legends, lup_locations, lup_rainpercentage_legends, lup_provinces WHERE forecast_daily_details.isdeleted = 0
		and forecast_daily_details.location_id = lup_locations.location_id 
		and forecast_daily_details.daily_forecast_rainfall_id = lup_rainfall_legends.rainfall_legend_id
		and forecast_daily_details.daily_forecast_rain_percent_id = lup_rainpercentage_legends.rain_percentage_legend_id
		and lup_locations.province_id = lup_provinces.province_id"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "dailyweathertable">
			<thead>
				
				<th>#</th>
				<th>DATE</th>
				<th>LOCATION</th>				
				<th>RAINFALL</TH>
				<th>RAINFALL PERCENTAGE</th>
				<th>LOW TEMPERATURE</th>
				<th>HIGH TEMPERATURE</th>
				<th></th>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['ForecastDate'];?></td>
					<td><?php echo $row['LocationDescription'];?></td>
					<td><?php echo $row['RainFall'];?></td>
					<td><?php echo $row['RainFallPercentage'];?></td>
					<td><?php echo $row['LowTemp'];?></td>
					<td><?php echo $row['HighTemp'];?></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
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
										editadvid:'<?php echo $row['announcement_id'];?>',
										editadvlevel:'1'
									},
									function(data) {
										$('#announceui').html(data);		
									});
							}
						);
						$("#advdelete<?php echo $ctr;?>").click(
							function()
							{
												var r = confirm("confirm delete");

														if(r == true)
															{
																$.post( 
																	'php/main.php',
																	{
																		advdelete:'<?php echo $row["announcement_id"];?>',
																		advdeletelevel:'1',
																		advdeletecount:'<?php echo $ctr;?>'
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
						
					$('#dailyweathertable').DataTable({
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

function rainfallper_legends($level)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from lup_rainpercentage_legends where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "rainfall">
			<thead>
				<th></th>
				<th>#</th>
				<th>RAINFALL % FROM</th>
				<th>RAINFALL % TO</th>				
				<th>COLOR</TH>
				<th></th>
				<th></th>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><input type = "checkbox" name = "select<?php echo $ctr;?>"></td>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['rain_percent_from'];?>%</td>
					<td><?php echo $row['rain_percent_to'];?>%</td>
					<td style = "background-color:<?php echo $row['color'];?>;color:#fff;"><?php echo $row['color'];?></td>
					<td></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
				</tr>
					<script>						
						$("#details<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								$("#modal").modal("show");
								$("#modalbody").css("min-width","60%");
															
								$.post( 
									'php/main.php',
									{
										showadvdetails:'<?php echo $row['announcement_id'];?>'
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
										editadvid:'<?php echo $row['announcement_id'];?>',
										editadvlevel:'1'
									},
									function(data) {
										$('#announceui').html(data);		
									});
							}
						);
						$("#advdelete<?php echo $ctr;?>").click(
							function()
							{
												var r = confirm("confirm delete");

														if(r == true)
															{
																$.post( 
																	'php/main.php',
																	{
																		advdelete:'<?php echo $row["announcement_id"];?>',
																		advdeletelevel:'1',
																		advdeletecount:'<?php echo $ctr;?>'
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
						
					$('#rainfall').DataTable({
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

function rainfall_legends($level)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from lup_rainfall_legends where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "rainfall">
			<thead>
				<th></th>
				<th>#</th>
				<th>RAIN FALL FROM</th>
				<th>RAIN FALL TO</th>				
				<th>COLOR</TH>
				<th></th>
				<th></th>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><input type = "checkbox" name = "select<?php echo $ctr;?>"></td>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['rainfall_from'];?>mm</td>
					<td><?php echo $row['rainfall_to'];?>mm</td>
					<td style = "background-color:<?php echo $row['color'];?>;color:#fff;"><?php echo $row['color'];?></td>
					<td></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
				</tr>
					<script>						
						$("#details<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								$("#modal").modal("show");
								$("#modalbody").css("min-width","60%");
															
								$.post( 
									'php/main.php',
									{
										showadvdetails:'<?php echo $row['announcement_id'];?>'
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
										editadvid:'<?php echo $row['announcement_id'];?>',
										editadvlevel:'1'
									},
									function(data) {
										$('#announceui').html(data);		
									});
							}
						);
						$("#advdelete<?php echo $ctr;?>").click(
							function()
							{
												var r = confirm("confirm delete");

														if(r == true)
															{
																$.post( 
																	'php/main.php',
																	{
																		advdelete:'<?php echo $row["announcement_id"];?>',
																		advdeletelevel:'1',
																		advdeletecount:'<?php echo $ctr;?>'
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
						
					$('#rainfall').DataTable({
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

function temperature_legends($temp_from,$temp_to,$level)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from lup_temperature_legends where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "locationtable">
			<thead>
				<th></th>
				<th>#</th>
				<th>TEMPERATURE FROM</th>
				<th>TEMPERATURE TO</th>				
				<th>COLOR</TH>
				<th></th>
				<th></th>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><input type = "checkbox" name = "select<?php echo $ctr;?>"></td>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['temp_from'];?></td>
					<td><?php echo $row['temp_to'];?></td>
					<td style = "background-color:<?php echo $row['color'];?>;color:#fff;"><?php echo $row['color'];?></td>
					<td></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
				</tr>
					<script>						
						$("#details<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								$("#modal").modal("show");
								$("#modalbody").css("min-width","60%");
															
								$.post( 
									'php/main.php',
									{
										showadvdetails:'<?php echo $row['announcement_id'];?>'
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
										editadvid:'<?php echo $row['announcement_id'];?>',
										editadvlevel:'1'
									},
									function(data) {
										$('#announceui').html(data);		
									});
							}
						);
						$("#advdelete<?php echo $ctr;?>").click(
							function()
							{
												var r = confirm("confirm delete");

														if(r == true)
															{
																$.post( 
																	'php/main.php',
																	{
																		advdelete:'<?php echo $row["announcement_id"];?>',
																		advdeletelevel:'1',
																		advdeletecount:'<?php echo $ctr;?>'
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
						
					$('#locationtable').DataTable({
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

function locations($level)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from lup_locations where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "locationtable">
			<thead>
				<th></th>
				<th>#</th>
				<th>DESCRIPTION</th>
				<th>REGION</th>				
				<th>COORDINATES</TH>
				<th></th>
				<th></th>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><input type = "checkbox" name = "select<?php echo $ctr;?>"></td>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['location_description'];?></td>
					<td></td>
					<td><?php echo $row['coordinates'];?></td>
					<td></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-success btn-flat btn-xs" id = "details<?php echo $ctr;?>">VIEW MAP</button>	
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
				</tr>
					<script>						
						$("#details<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								$("#modal").modal("show");
								$("#modalbody").css("min-width","60%");
															
								$.post( 
									'php/main.php',
									{
										showadvdetails:'<?php echo $row['announcement_id'];?>'
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
										editadvid:'<?php echo $row['announcement_id'];?>',
										editadvlevel:'1'
									},
									function(data) {
										$('#announceui').html(data);		
									});
							}
						);
						$("#advdelete<?php echo $ctr;?>").click(
							function()
							{
												var r = confirm("confirm delete");

														if(r == true)
															{
																$.post( 
																	'php/main.php',
																	{
																		advdelete:'<?php echo $row["announcement_id"];?>',
																		advdeletelevel:'1',
																		advdeletecount:'<?php echo $ctr;?>'
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
						
					$('#locationtable').DataTable({
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

function advisory($location,$status,$dfrom,$dto)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select announcements.*,se_user.fullname,lup_locations.location_description from announcements,se_user,lup_locations where announcements.isdeleted = 0
		and se_user.user_id = announcements.added_by
		and lup_locations.location_id = announcements.location_id ";
		
		if(!empty($location) && $location != 'all')
			$string = $string." and announcements.location_id = '$location'";
		
		if((!empty($status)||$status == 0) && $status != 'all')
			$string = $string." and announcements.status = '$status'";
	
		if(!empty($dfrom))
		{	
			$string = $string." and (STR_TO_DATE(announcements.date_added,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(announcements.date_added,'%Y-%m-%d')<= STR_TO_DATE('$dfrom','%Y-%m-%d'))";
		
		}
		if(!empty($dto))
		{	
			$string = $string." and (STR_TO_DATE(announcements.date_added,'%Y-%m-%d')>= STR_TO_DATE('$dto','%Y-%m-%d') and
			STR_TO_DATE(announcements.date_added,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "advtable">
			<thead>
				<th></th>
				<th>#</th>
				<th>TITLE</th>
				<th>AUTHOR</th>				
				<th>STATUS</TH>
				<th>LOCATION</TH>
				<th></th>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><input type = "checkbox" name = "select<?php echo $ctr;?>"></td>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['title'];?></td>
					<td><?php echo $row['fullname'];?></td>
					<td><?php
						if($row['status'] == 1)
							echo "Published";
						else
							echo "Unpublished";
					;?></td>
					<td><?php echo $row['location_description'];?></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-success btn-flat btn-xs" id = "details<?php echo $ctr;?>">DETAILS</button>	
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
				</tr>
					<script>						
						$("#details<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								$("#modal").modal("show");
								$("#modalbody").css("min-width","60%");
															
								$.post( 
									'php/main.php',
									{
										showadvdetails:'<?php echo $row['announcement_id'];?>'
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
										editadvid:'<?php echo $row['announcement_id'];?>',
										editadvlevel:'1'
									},
									function(data) {
										$('#announceui').html(data);		
									});
							}
						);
						$("#advdelete<?php echo $ctr;?>").click(
							function()
							{
												var r = confirm("confirm delete");

														if(r == true)
															{
																$.post( 
																	'php/main.php',
																	{
																		advdelete:'<?php echo $row["announcement_id"];?>',
																		advdeletelevel:'1',
																		advdeletecount:'<?php echo $ctr;?>'
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
						
					$('#advtable').DataTable({
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

function insert($table,$para=array()){
			global $con;
            $table_columns = implode(',',array_keys($para));
            $table_value = implode("','", $para);

            $sql="INSERT INTO $table($table_columns)VALUES('$table_value')";
            $result = mysqli_query($con,$sql);
			return $result;
}
function update($table,$para=array(),$id){
			global $con;
            $args = array();
            foreach ($para as $key => $value) {
                $args[] = "$key = '$value'"; 
            }
            $sql="UPDATE  $table SET " . implode(',', $args);
            $sql .=" WHERE $id";
            $result = mysqli_query($con,$sql);
			return $result;
}
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
function agent_info($id)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_user where user_id = $id"));
	
	?>
	<table class = "table table-condensed table-sm">
		<tr>
			<td>User ID Number: <b><?php echo $row['agent_number'];?></b></td>
			<td>Full Name: <b><?php echo $row['fullname'];?></b></td>
		</tr>							
	</table>
	<?php
}

function users($level)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$query = mysqli_query($con,"Select * from se_user order by agent_number");
		
	?>
		<table class = "table table-striped table-hover table-sm" id = "pmtable">
			<thead>
				<th>#</th>
				<th>USER ID NUMBER</th>
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
						<button class = "btn btn-success btn-flat btn-xs" id = "assign<?php echo $ctr;?>">MODULE ASSIGNMENT</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "reset<?php echo $ctr;?>">RESET</button>	
						<button class = "btn btn-warning btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
				</tr>
					<script>						
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
?>