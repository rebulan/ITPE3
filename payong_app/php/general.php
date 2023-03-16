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
function agri_forecast($status,$issue,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select agri_info.*,agri_forecast.*,se_user.fullname from agri_forecast,se_user,agri_info where agri_forecast.isdeleted = 0
		and se_user.user_id = agri_forecast.added_by
		and agri_info.agri_info_id = agri_forecast.agri_info_id";
		
		if((!empty($status)||$status == 0) && $status != 'all')
			$string = $string." and agri_forecast.status = '$status'";
		
		if(!empty($issue) && $issue != 'all')
		{	
			$string = $string." and agri_forecast.agri_info_id = $issue";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
		if($print == 0)
		{
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
				</div><br>
			<?php
		}
	?>
	
		<table class = "table table-bordered table-hover table-sm" id = "forecasttable">
			<thead>
				<th><input type = "checkbox" name = "selectall" id = "selectall"></th>
				<th>#</th>
				<th>TITLE</th>
				<th>AUTHOR</th>				
				<th>STATUS</TH>
				<th>DATE ADDED</TH>
				<th></th>
			</thead>
			<script>
				$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#forecasttable input').attr('checked', true);
							} else {
								$('#forecasttable input').attr('checked', false);
							}
					}
				);
			</script>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><input type = "checkbox" name = "select[<?php echo $row['agri_forecast_id'];?>]"></td>
					<td><?php echo $ctr;?>
						<input type = "hidden" name = "afgbatchstatus" id = "afgbatchstatus" value = "<?php echo $status;?>">
						<input type = "hidden" name = "afgbatchissue" id = "afgbatchissue" value = "<?php echo $issue;?>">
						
					</td>
					<td><?php echo $row['title'];?></td>
					<td><?php echo $row['fullname'];?></td>
					<td><?php
						if($row['status'] == 1)
							echo "Published";
						else
							echo "Unpublished";
					;?></td>
					<td><?php echo $row['date_added'];?></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">OPEN</button>	
					</td>
				</tr>
					<script>													
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();														
								$.post( 
									'php/main.php',
									{
										editafgid:'<?php echo $row['agri_forecast_id'];?>',
										editafglevel:'1',
										editafgstatus:'<?php echo $status;?>',
										editafgissue:'<?php echo $issue;?>'
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
																		afgdelete:'<?php echo $row["agri_forecast_id"];?>',
																		afgdeletelevel:'1',
																		afgdeletecount:'<?php echo $ctr;?>'
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
						
					var table = $('#forecasttable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : false
					});
					
					$("#bdelete").click(
						function()
						{
							var check = $('#forecasttable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "afgbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#forecastlist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to Delete");
							}
   
							
							 
						}
					);
					
					$("#publish").click(
						function()
						{
							var check = $('#forecasttable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "afgbatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#forecastlist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to published");
							}
							
							
						}
					);
					
					$("#unpublish").click(
						function()
						{
							var check = $('#forecasttable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "afgbatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#forecastlist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to Unpublished");
							}
						}
					);
					
				}
			);
		</script>
	<?php
}

function issue($level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from agri_info where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
		if($print == 0)
		{
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
				</div><br>
				<script>
					
				</script>
			<?php
		}
		?>
		<table class = "table table-bordered table-hover table-sm" id = "agriissue">
			<thead>
				<?php
				if($print == 0)
				{
				?>
				<th><input type = "checkbox" name = "selectall"></th>
				<?php
				}
				?>
				<th>#</th>
				<th>DATE FROM</th>
				<th>DATE TO</th>				
				<th>STATUS</TH>
				<?php
				if($print == 0)
				{
				?>
				<th></th>
				<?php
				}
				?>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				if($print == 1)
				{
				?>
				<tr>
					
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['date_from'];?></td>
					<td><?php echo $row['date_to'];?></td>
					<td><?php
						if($row['status'] == 1)
							echo "Published";
						else
							echo "Unpublished";
					?></td>
				</tr>
					
					
				<?php
				}
				else
				{
					?>
					<tr>
						<input type = "hidden" name = "ilevel" value = "<?php echo $level;?>">
						<td><input type = "checkbox" name = "select[<?php echo $row['agri_info_id'];?>]"></td>
						<td><?php echo $ctr;?></td>
						<td><input type="date" id = "idatefrom<?php echo $ctr;?>" name = "idatefrom<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['date_from'];?>"></td>
						<td><input type="date" id = "idateto<?php echo $ctr;?>" name = "idateto<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['date_to'];?>"></td>
						<td>
											<?PHP
											$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
											$pquery = mysqli_query($con,"select * from lup_status where isdeleted = 0");
											?>
											<select name = "istatus<?php echo $ctr;?>" id = "istatus<?php echo $ctr;?>" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '<?php echo $irow['status_id'];?>' hidden "Selected"><?php echo $irow['status'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['status_id'];?>"><?php echo $prow['status'];?></option>
														<?php
															}
														?>
											</select>
											
							</td>
						<td id = "controlui<?php echo $ctr;?>">
							<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
							<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
						</td>
					</tr>
					<script>						
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
								
								$.post( 
									'php/main.php',
									{
										editissueid:'<?php echo $row['agri_info_id'];?>',
										editissuedatefrom:$("#idatefrom<?php echo $ctr;?>").val(),
										editissuedateto:$("#idateto<?php echo $ctr;?>").val(),
										editissuestatus:$("#istatus<?php echo $ctr;?>").val()
									},
									function(data) {
										$('#click').html(data);	
										
									});
							}
						);
						$("#delete<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								var r = confirm("Confirm delete");
								
								if(r == true)
								{
															
									$.post( 
										'php/main.php',
										{
											deleteissueid:'<?php echo $row['agri_info_id'];?>',
											deleteissuecount:'<?php echo $ctr;?>'
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
				$ctr++;
			}
			?>
		</table>
		
		<script>
			$("#document").ready(
				function()
				{
						
					var table = $('#agriissue').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : false
					});	
					
					$("#bdelete").click(
						function()
						{
							var check = $('#agriissue').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "issuebatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#issuelist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to Delete");
							}
   
							
							 
						}
					);
					
					$("#publish").click(
						function()
						{
							var check = $('#agriissue').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "issuebatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#issuelist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to published");
							}
							
							
						}
					);
					
					$("#unpublish").click(
						function()
						{
							var check = $('#agriissue').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "issuebatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#issuelist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to Unpublished");
							}
						}
					);
				}
			);
		</script>
	<?php
}

function coordinates($location)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from lup_coordinates where location_id = $location and isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "locationtable">
			<thead>
				<th></th>
				<th>#</th>
				<th>COORDINATES</th>
							
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
					<td><?php echo $row['coordinate'];?></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
					</td>
				</tr>
					<script>						
						$("#delete<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								var r = confirm("Confirm delete");
								
								if(r == true)
								{
															
									$.post( 
										'php/main.php',
										{
											coordelete:'<?php echo $row['coordinate_id'];?>',
											coorcount:'<?php echo $ctr;?>'
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

function save_daily($dbf,$udate)
{
	//echo $dbf." aaa";
	global $con;
	 $fdbf = fopen($dbf,'r'); 
	
    $fields = array();

    $buf = fread($fdbf,32);

    $header=unpack( "VRecordCount/vFirstRecord/vRecordLength", substr($buf,4,8));
	
	 $goon = true; 

    $unpackString='';

    while ($goon && !feof($fdbf)) { // read fields:

        $buf = fread($fdbf,32);

        if (substr($buf,0,1)==chr(13)) {$goon=false;} // end of field list

        else {

            $field=unpack( "a11fieldname/A1fieldtype/Voffset/Cfieldlen/Cfielddec", substr($buf,0,18));

           // echo 'Field: '.json_encode($field).'<br/>';

            $unpackString.="A$field[fieldlen]$field[fieldname]/";

            array_push($fields, $field);}}
			
    fseek($fdbf, $header['FirstRecord']+1); // move back to the start of the first record (after the field definitions)
	$f = array();
    for ($i=1; $i<=$header['RecordCount']; $i++) {

        $buf = fread($fdbf,$header['RecordLength']);

        $record=unpack($unpackString,$buf);

        //echo 'record: '.json_encode($record).'<br/>';
		array_push($f, $record);
        //echo $i.$buf.'<br/>';
		} //raw record
	if(isset($f['0']['CITY']))
	{
		for ($i=0; $i<=$header['RecordCount']-1; $i++) {
			//print_r($f[$i]);
			//echo "<br>";
			$str = str_replace(' ','',$f[$i]['CITY']);
			$str = str_replace('(','',$str);
			$str = str_replace(')','',$str);
			$str =  mysqli_real_escape_string($con,strtoupper($str));
			$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where CONCAT(REPLACE(UPPER(lup_locations.location_description), ' ', ''),REPLACE(UPPER(lup_provinces.description), ' ', '')) = '$str' and lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
			$rfval = $f[$i]['RAINFALLTO'];
			$rf = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_rainpercentage_legends where rain_percent_from <= $rfval and rain_percent_to >= $rfval and isdeleted = 0"));
			$ltval = $f[$i]['TEMPMIN'];
			$lt = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where temp_from <= $ltval and temp_to >= $rfval and isdeleted = 0"));
			$htval = $f[$i]['TEMPMAX'];
			$ht = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where temp_from <= $htval and temp_to >= $htval and isdeleted = 0"));
			//$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
			//echo $loc['loc']."<br>";
			//if(!empty($loc))
			//{
				insert('daily_details',['location_id'=>$loc['location_id'],'forecast_date'=>$udate,'location_des'=>$f[$i]['CITY'],'daily_forecast_rainfall_percentage'=>$f[$i]['RAINFALLTO'],'daily_forecast_rain_percent_hex' =>$rf['color'],'rainfall_description'=>$f[$i]['RAINFALLDE'],'cloudcover'=>$f[$i]['CLOUDCOVER'],'humidity'=>$f[$i]['RELHUMIDIT'],'windspeed'=>$f[$i]['WINDSPEED'],'winddirection'=>$f[$i]['WINDDIRECT'],'daily_forecast_low_temp'=>$f[$i]['TEMPMIN'],'daily_forecast_lowtemp_hex'=>$lt['color'],'daily_forecast_high_temp'=>$f[$i]['TEMPMAX'],'daily_forecast_hightemp_hex'=>$ht['color'],'daily_forecast_mean_temp'=>$f[$i]['TEMPMEAN']]);
			//}
		}
		return $i." record/s has been Uploaded";
	}
	else{
		return "Invalid file Format";
	}
	
    fclose($fdbf); 
	
}

function daily_weather($dfrom,$dto,$location,$level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "SELECT 
		daily_details.daily_details_id as DailyDetailsID,
		daily_details.forecast_date as ForecastDate,
		lup_locations.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription,
		daily_details.daily_forecast_rainfall as RainFall,
		daily_details.daily_forecast_rainfall_percentage as RainFallPercentage,
		daily_details.rainfall_description as RainFallDescription,
		daily_details.cloudcover as CloudCover,
		daily_details.humidity as Humidity,
		daily_details.windspeed as WindSpeed,
		daily_details.winddirection as WindDirection,
		daily_details.daily_forecast_low_temp as LowTemp,
		daily_details.daily_forecast_lowtemp_hex as LowTempColorCode,
		daily_details.daily_forecast_high_temp as HighTemp,
		daily_details.daily_forecast_hightemp_hex as HighTempColorCode,
		daily_details.daily_forecast_mean_temp as MeanTemp
		FROM daily_details, lup_locations, lup_provinces WHERE daily_details.isdeleted = 0
		and daily_details.location_id = lup_locations.location_id
		and lup_locations.province_id = lup_provinces.province_id"; 
		
		if(!empty($dfrom))
		{	
			$string = $string." and (STR_TO_DATE(daily_details.forecast_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(daily_details.forecast_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
		
		}
		
		if(!empty($location) && $location != 'all')
		{	
			$string = $string." and lup_locations.location_id = $location";
		
		}
		
		
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "dailyweathertable">
			<thead>
				
				<th>#</th>
				<th>DATE</th>
				<th>LOCATION</th>				
				<th>RAINFALL PERCENTAGE</th>
				<th>RAINFALL DESCRIPTION</th>
				<th>CLOUD COVER</th>
				<th>HUMIDITY</th>
				<th>WIND SPEED</th>
				<th>WIND DIRECTION</th>
				<th>LOW TEMPERATURE</th>
				<th>HIGH TEMPERATURE</th>
				<th>MEAN TEMPERATURE</th>
				<?PHP
				IF($print == 0)
				{
				?>
				<th></th>
				<?php
				}
				?>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				if($print == 1)
				{
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['ForecastDate'];?></td>
					<td><?php echo $row['LocationDescription'];?></td>
					<td><?php echo $row['RainFallPercentage'];?></td>
					<td><?php echo $row['RainFallDescription'];?></td>
					<td><?php echo $row['CloudCover'];?></td>
					<td><?php echo $row['Humidity'];?></td>
					<td><?php echo $row['WindSpeed'];?></td>
					<td><?php echo $row['WindDirection'];?></td>
					<td><?php echo $row['LowTemp'];?></td>
					<td><?php echo $row['HighTemp'];?></td>
					<td><?php echo $row['MeanTemp'];?></td>
					
				</tr>

					
				<?php
				}
				else
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['ForecastDate'];?></td>
							<td><?php echo $row['LocationDescription'];?></td>
							<td><input type="number" ID="wrainp<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['RainFallPercentage'];?>"></td>
							<td>
											<?PHP
											$pquery = mysqli_query($con,"select * from lup_rainfall_des where isdeleted = 0");
											?>
											<select name = "wraindes<?php echo $ctr;?>" id = "wraindes<?php echo $ctr;?>" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '<?php echo $row['RainFallDescription'];?>' hidden "Selected"><?php echo $row['RainFallDescription'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['description'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											
							</td>
							<td>
								<?PHP
											$pquery = mysqli_query($con,"select * from lup_weather_system where isdeleted = 0");
											?>
											<select name = "wcloud<?php echo $ctr;?>" id = "wcloud<?php echo $ctr;?>" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '<?php echo $row['CloudCover'];?>' hidden "Selected"><?php echo $row['CloudCover'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['description'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											
	
							</td>
							<td><input type="number" ID="whumid<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['Humidity'];?>"></td>
							<td><input type="number" ID="wwind<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['WindSpeed'];?>"></td>
							<td>
								<?PHP
											$pquery = mysqli_query($con,"select * from lup_wind_direction where isdeleted = 0");
											?>
											<select name = "wwinddirect<?php echo $ctr;?>" id = "wwinddirect<?php echo $ctr;?>" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '<?php echo $row['WindDirection'];?>' hidden "Selected"><?php echo $row['WindDirection'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['description'];?>"><?php echo $prow['description'];?></option>
														<?php
															}
														?>
											</select>
											
							
							</td>
							<td><input type="number" ID="wlowtemp<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['LowTemp'];?>"></td>
							<td><input type="number" ID="whightemp<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['HighTemp'];?>"></td>
							<td><input type="number" ID="wmeantemp<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['MeanTemp'];?>"></td>
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "wdelete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-primary btn-flat btn-xs" id = "wedit<?php echo $ctr;?>">EDIT</button>	
							</td>
						</tr>
						<script>													
						$("#wedit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();								
								alert('OK');
															
								$.post( 
									'php/main.php',
									{
										editwid:'<?php echo $row['DailyDetailsID'];?>',
										editwrainp:$("#wrainp<?php echo $ctr;?>").val(),
										editwraindes:$("#wraindes<?php echo $ctr;?>").val(),
										editwcloud:$("#wcloud<?php echo $ctr;?>").val(),
										editwhumid:$("#whumid<?php echo $ctr;?>").val(),
										editwwind:$("#wwind<?php echo $ctr;?>").val(),
										editwwinddirect:$("#wwinddirect<?php echo $ctr;?>").val(),
										editwlowtemp:$("#wlowtemp<?php echo $ctr;?>").val(),
										editwhightemp:$("#whightemp<?php echo $ctr;?>").val(),
										editwmeantemp:$("#wmeantemp<?php echo $ctr;?>").val(),
										editwcount:'<?php echo $ctr;?>'
									},
									function(data) {
										$('#click').html(data);		
									});
							}
						);
						$("#wdelete<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								var r = confirm("Confirm delete");
								
								if(r == true)
								{
															
									$.post( 
										'php/main.php',
										{
											deletewid:'<?php echo $row['DailyDetailsID'];?>',
											deletewcount:'<?php echo $ctr;?>'
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
		<table class = "table table-bordered table-hover table-sm" id = "coortable">
			<thead>
				<th></th>
				<th>#</th>
				<th>DESCRIPTION</th>
				<th>PROVINCES</th>				
				<th></th>
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$pro = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_provinces where province_id = $row[province_id]"));
	
				?>
				<tr>
					<td><input type = "checkbox" name = "select<?php echo $ctr;?>"></td>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['location_description'];?></td>
					<td><?php echo $pro['description'];?></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-success btn-flat btn-xs" id = "details<?php echo $ctr;?>">MAP COORDINATES</button>	
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
				</tr>
					<script>						
						$("#details<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
																					
								$("#modal").modal({backdrop: false});
								$("#modalbody").css("min-width","60%");
															
								$.post( 
									'php/main.php',
									{
										showmap:'<?php echo $row['location_id'];?>'
									},
									function(data) {
										$('#modalui').html(data);		
									});
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
						
					$('#coortable').DataTable({
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

function advisory($status,$issue,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select agri_info.*,agri_advisory.*,se_user.fullname from agri_advisory,se_user,agri_info where agri_advisory.isdeleted = 0
		and se_user.user_id = agri_advisory.added_by
		and agri_info.agri_info_id = agri_advisory.agri_info_id";
		
		if((!empty($status)||$status == 0) && $status != 'all')
			$string = $string." and agri_advisory.status = '$status'";
	
		if(!empty($issue) && $issue != 'all')
		{	
			$string = $string." and agri_advisory.agri_info_id = $issue";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
		if($print == 0)
		{
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
				</div><br>
				<script>
					
				</script>
			<?php
		}
	?>
	
		<table class = "table table-bordered table-hover table-sm" id = "advtable">
			<thead>
				<th><input type = "checkbox" name = "selectall" id = "selectall"></th>
				<th>#</th>
				<th>TITLE</th>
				<th>AUTHOR</th>				
				<th>STATUS</TH>
				<th>DATE ISSUE</TH>
				<th>DATE ADDED</TH>
				<th></th>
			</thead>
			<script>
				$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#advtable input').attr('checked', true);
							} else {
								$('#advtable input').attr('checked', false);
							}
					}
				);
			</script>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><input type = "checkbox" name = "select[<?php echo $row['announcement_id'];?>]"></td>
					<td><?php echo $ctr;?>
						<input type = "hidden" name = "batchstatus" id = "batchstatus" value = "<?php echo $status;?>">
						<input type = "hidden" name = "batchissue" id = "batchissue" value = "<?php echo $issue;?>">
						
					</td>
					<td><?php echo $row['title'];?></td>
					<td><?php echo $row['fullname'];?></td>
					<td><?php
						if($row['status'] == 1)
							echo "Published";
						else
							echo "Unpublished";
					;?></td>
					<td><?php echo $row['date_from']." to ".$row['date_to'];?></td>
					<td><?php echo $row['date_added'];?></td>
					<td id = "controlui<?php echo $ctr;?>">
						<button class = "btn btn-danger btn-flat btn-xs" id = "advdelete<?php echo $ctr;?>">DELETE</button>	
						<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">OPEN</button>	
					</td>
				</tr>
					<script>													
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();														
								$.post( 
									'php/main.php',
									{
										editadvid:'<?php echo $row['announcement_id'];?>',
										editadvlevel:'1',
										editadvstatus:'<?php echo $status;?>',
										editadvissue:'<?php echo $issue;?>'
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
						
					var table = $('#advtable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : false
					});
					
					$("#bdelete").click(
						function()
						{
							var check = $('#advtable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "batchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#advlist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to Delete");
							}
   
							
							 
						}
					);
					
					$("#publish").click(
						function()
						{
							var check = $('#advtable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "batchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#advlist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to published");
							}
							
							
						}
					);
					
					$("#unpublish").click(
						function()
						{
							var check = $('#advtable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "batchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#advlist').html(data);															
										}
										});
								}
							}
							else
							{
								alert("Select Item to Unpublished");
							}
						}
					);
					
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