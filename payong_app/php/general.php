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
function daily_mon_rainfall_per($dfrom,$dto,$location,$level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "SELECT 
		daily_mon_normal_rainfall.*,
		lup_locations.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription
		FROM daily_mon_normal_rainfall, lup_locations, lup_provinces WHERE daily_mon_normal_rainfall.isdeleted = 0
		and daily_mon_normal_rainfall.location_id = lup_locations.location_id
		and lup_locations.province_id = lup_provinces.province_id"; 
		
		if(!empty($dfrom))
		{	
			$string = $string." and (STR_TO_DATE(daily_mon_normal_rainfall.daily_mon_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(daily_mon_normal_rainfall.daily_mon_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
		
		}
		
		if(!empty($location) && $location != 'all')
		{	
			$string = $string." and lup_locations.location_id = $location";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "dailymontemptable">
			<thead>
				
				<th>#</th>
				<th>DATE</th>
				<th>LOCATION</th>				
				<th>RAINFALL PERCENTAGE(mm)</th>
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
					<td><?php echo $row['daily_mon_date'];?></td>
					<td><?php echo $row['LocationDescription'];?></td>
					<td><?php echo $row['mean'];?></td>	
				</tr>

					
				<?php
				}
				else
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['daily_mon_date'];?></td>
							<td><?php echo $row['LocationDescription'];?></td>
							<td><input type="number" ID="mean<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['mean'];?>"></td>
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
										editdmonprainid:'<?php echo $row['daily_mon_rainp_id'];?>',
										edidmonprain:$("#mean<?php echo $ctr;?>").val(),
										editdmonpraincount:'<?php echo $ctr;?>'
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
											deletedmonnrainid:'<?php echo $row['daily_mon_rainp_id'];?>',
											deletedmonnraincount:'<?php echo $ctr;?>'
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
						
					$('#dailymontemptable').DataTable({
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

function daily_mon_normal_rainfall($dfrom,$dto,$location,$level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "SELECT 
		daily_mon_normal_rainfall.*,
		lup_locations.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription
		FROM daily_mon_normal_rainfall, lup_locations, lup_provinces WHERE daily_mon_normal_rainfall.isdeleted = 0
		and daily_mon_normal_rainfall.location_id = lup_locations.location_id
		and lup_locations.province_id = lup_provinces.province_id"; 
		
		if(!empty($dfrom))
		{	
			$string = $string." and (STR_TO_DATE(daily_mon_normal_rainfall.daily_mon_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(daily_mon_normal_rainfall.daily_mon_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
		
		}
		
		if(!empty($location) && $location != 'all')
		{	
			$string = $string." and lup_locations.location_id = $location";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "dailymontemptable">
			<thead>
				
				<th>#</th>
				<th>DATE</th>
				<th>LOCATION</th>				
				<th>NORMAL RAINFALL(mm)</th>
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
					<td><?php echo $row['daily_mon_date'];?></td>
					<td><?php echo $row['LocationDescription'];?></td>
					<td><?php echo $row['mean'];?></td>	
				</tr>

					
				<?php
				}
				else
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['daily_mon_date'];?></td>
							<td><?php echo $row['LocationDescription'];?></td>
							<td><input type="number" ID="mean<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['mean'];?>"></td>
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
										editdmonnrainid:'<?php echo $row['daily_mon_nrain_id'];?>',
										edidmonnrain:$("#mean<?php echo $ctr;?>").val(),
										editdmonnnraincount:'<?php echo $ctr;?>'
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
											deletedmonnrainid:'<?php echo $row['daily_mon_nrain_id'];?>',
											deletedmonnraincount:'<?php echo $ctr;?>'
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
						
					$('#dailymontemptable').DataTable({
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

function daily_mon_actual_rainfall($dfrom,$dto,$location,$level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "SELECT 
		daily_mon_actual_rainfall.*,
		lup_locations.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription
		FROM daily_mon_actual_rainfall, lup_locations, lup_provinces WHERE daily_mon_actual_rainfall.isdeleted = 0
		and daily_mon_actual_rainfall.location_id = lup_locations.location_id
		and lup_locations.province_id = lup_provinces.province_id"; 
		
		if(!empty($dfrom))
		{	
			$string = $string." and (STR_TO_DATE(daily_mon_actual_rainfall.daily_mon_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(daily_mon_actual_rainfall.daily_mon_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
		
		}
		
		if(!empty($location) && $location != 'all')
		{	
			$string = $string." and lup_locations.location_id = $location";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "dailymontemptable">
			<thead>
				
				<th>#</th>
				<th>DATE</th>
				<th>LOCATION</th>				
				<th>ACTUAL RAINFALL(mm)</th>
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
					<td><?php echo $row['daily_mon_date'];?></td>
					<td><?php echo $row['LocationDescription'];?></td>
					<td><?php echo $row['mean'];?></td>	
				</tr>

					
				<?php
				}
				else
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['daily_mon_date'];?></td>
							<td><?php echo $row['LocationDescription'];?></td>
							<td><input type="number" ID="mean<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['mean'];?>"></td>
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
										editdmonarainid:'<?php echo $row['daily_mon_arain_id'];?>',
										edidmonarain:$("#mean<?php echo $ctr;?>").val(),
										editdmonnaraincount:'<?php echo $ctr;?>'
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
											deletedmonarainid:'<?php echo $row['daily_mon_arain_id'];?>',
											deletedmonaraincount:'<?php echo $ctr;?>'
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
						
					$('#dailymontemptable').DataTable({
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
function daily_mon_temp_max($dfrom,$dto,$location,$level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "SELECT 
		daily_mon_temp.*,
		lup_locations.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription
		FROM daily_mon_temp, lup_locations, lup_provinces WHERE daily_mon_temp.isdeleted = 0
		and daily_mon_temp.location_id = lup_locations.location_id
		and lup_locations.province_id = lup_provinces.province_id
		and daily_mon_temp.minmax = 'max'"; 
		
		if(!empty($dfrom))
		{	
			$string = $string." and (STR_TO_DATE(daily_mon_temp.daily_mon_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(daily_mon_temp.daily_mon_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
		
		}
		
		if(!empty($location) && $location != 'all')
		{	
			$string = $string." and lup_locations.location_id = $location";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "dailymontemptable">
			<thead>
				
				<th>#</th>
				<th>DATE</th>
				<th>LOCATION</th>				
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
					<td><?php echo $row['daily_mon_date'];?></td>
					<td><?php echo $row['LocationDescription'];?></td>
					<td><?php echo $row['mean'];?></td>	
				</tr>

					
				<?php
				}
				else
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['daily_mon_date'];?></td>
							<td><?php echo $row['LocationDescription'];?></td>
							<td><input type="number" ID="meantemp<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['mean'];?>"></td>
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
										editdmontempid:'<?php echo $row['daily_mon_temp_id'];?>',
										edidmontemp:$("#meantemp<?php echo $ctr;?>").val(),
										editdmpntempcount:'<?php echo $ctr;?>'
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
											deletedmontemp:'<?php echo $row['daily_mon_temp_id'];?>',
											deletedmontempcount:'<?php echo $ctr;?>'
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
						
					$('#dailymontemptable').DataTable({
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

function daily_mon_temp($dfrom,$dto,$location,$level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "SELECT 
		daily_mon_temp.*,
		lup_locations.location_id,
		CONCAT(lup_locations.location_description,',',lup_provinces.description) as LocationDescription
		FROM daily_mon_temp, lup_locations, lup_provinces WHERE daily_mon_temp.isdeleted = 0
		and daily_mon_temp.location_id = lup_locations.location_id
		and lup_locations.province_id = lup_provinces.province_id
		and daily_mon_temp.minmax = 'max'"; 
		
		if(!empty($dfrom))
		{	
			$string = $string." and (STR_TO_DATE(daily_mon_temp.daily_mon_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(daily_mon_temp.daily_mon_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
		
		}
		
		if(!empty($location) && $location != 'all')
		{	
			$string = $string." and lup_locations.location_id = $location";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "dailymontemptable">
			<thead>
				
				<th>#</th>
				<th>DATE</th>
				<th>LOCATION</th>				
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
					<td><?php echo $row['daily_mon_date'];?></td>
					<td><?php echo $row['LocationDescription'];?></td>
					<td><?php echo $row['mean'];?></td>	
				</tr>

					
				<?php
				}
				else
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['daily_mon_date'];?></td>
							<td><?php echo $row['LocationDescription'];?></td>
							<td><input type="number" ID="meantemp<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['mean'];?>"></td>
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
										editdmontempid:'<?php echo $row['daily_mon_temp_id'];?>',
										edidmontemp:$("#meantemp<?php echo $ctr;?>").val(),
										editdmpntempcount:'<?php echo $ctr;?>'
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
											deletedmontemp:'<?php echo $row['daily_mon_temp_id'];?>',
											deletedmontempcount:'<?php echo $ctr;?>'
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
						
					$('#dailymontemptable').DataTable({
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

function save_daily_mon($dbf,$udate,$type)
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
	if(isset($f['0']['Municipali']))
	{
		if($type == 1)
		{
			for ($i=0; $i<=$header['RecordCount']-1; $i++) {
				//print_r($f[$i]);
				//echo "<br>";
				$str = str_replace(' ','',$f[$i]['Municipali']);
				$str = str_replace('(','',$str);
				$str = str_replace(')','',$str);
				$str =  mysqli_real_escape_string($con,strtoupper($str));
				$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where CONCAT(REPLACE(UPPER(lup_locations.location_description), ' ', ''),REPLACE(UPPER(lup_provinces.description), ' ', '')) = '$str' and lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				$rfval = $f[$i]['MEAN'];
				$rf = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_rainfall_legends where rainfall_from <= $rfval and rainfall_to >= $rfval and isdeleted = 0"));
				
				//$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				//echo $loc['loc']."<br>";
				//if(!empty($loc))
				//{
					insert('daily_mon_normal_rainfall',['location_id'=>$loc['location_id'],'daily_mon_date'=>$udate,'mean'=>$f[$i]['MEAN'],'color'=>$rf['color']]);
				//}
			}
			return $i." record/s has been Uploaded";
		}
		
		if($type == 2)
		{
			for ($i=0; $i<=$header['RecordCount']-1; $i++) {
				//print_r($f[$i]);
				//echo "<br>";
				$str = str_replace(' ','',$f[$i]['Municipali']);
				$str = str_replace('(','',$str);
				$str = str_replace(')','',$str);
				$str =  mysqli_real_escape_string($con,strtoupper($str));
				$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where CONCAT(REPLACE(UPPER(lup_locations.location_description), ' ', ''),REPLACE(UPPER(lup_provinces.description), ' ', '')) = '$str' and lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				$rfval = $f[$i]['MEAN'];
				$rf = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_actual_rainfall_legends where arainfall_from <= $rfval and arainfall_to >= $rfval and isdeleted = 0"));
				
				//$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				//echo $loc['loc']."<br>";
				//if(!empty($loc))
				//{
					insert('daily_mon_actual_rainfall',['location_id'=>$loc['location_id'],'daily_mon_date'=>$udate,'mean'=>$f[$i]['MEAN'],'color'=>$rf['color']]);
				//}
			}
			return $i." record/s has been Uploaded";
		}
		
		if($type == 3)
		{
			for ($i=0; $i<=$header['RecordCount']-1; $i++) {
				//print_r($f[$i]);
				//echo "<br>";
				$str = str_replace(' ','',$f[$i]['Municipali']);
				$str = str_replace('(','',$str);
				$str = str_replace(')','',$str);
				$str =  mysqli_real_escape_string($con,strtoupper($str));
				$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where CONCAT(REPLACE(UPPER(lup_locations.location_description), ' ', ''),REPLACE(UPPER(lup_provinces.description), ' ', '')) = '$str' and lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				$rfval = $f[$i]['MEAN'];
				$rf = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_rainpercentage_legends where rain_percent_from <= $rfval and rain_percent_to >= $rfval and isdeleted = 0"));
				
				//$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				//echo $loc['loc']."<br>";
				//if(!empty($loc))
				//{
					insert('daily_mon_rainfall_percentage',['location_id'=>$loc['location_id'],'daily_mon_date'=>$udate,'mean'=>$f[$i]['MEAN'],'color'=>$rf['color']]);
				//}
			}
			return $i." record/s has been Uploaded";
		}
		
		if($type == 4)
		{
			for ($i=0; $i<=$header['RecordCount']-1; $i++) {
				//print_r($f[$i]);
				//echo "<br>";
				$str = str_replace(' ','',$f[$i]['Municipali']);
				$str = str_replace('(','',$str);
				$str = str_replace(')','',$str);
				$str =  mysqli_real_escape_string($con,strtoupper($str));
				$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where CONCAT(REPLACE(UPPER(lup_locations.location_description), ' ', ''),REPLACE(UPPER(lup_provinces.description), ' ', '')) = '$str' and lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				$ltval = $f[$i]['MEAN'];
				$lt = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where temp_from <= $ltval and temp_to >= $ltval and isdeleted = 0"));
				
				//$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				//echo $loc['loc']."<br>";
				//if(!empty($loc))
				//{
					insert('daily_mon_temp',['location_id'=>$loc['location_id'],'daily_mon_date'=>$udate,'mean'=>$f[$i]['MEAN'],'color'=>$lt['color'],'minmax'=>'min']);
				//}
			}
			return $i." record/s has been Uploaded";
		}
		if($type == 5)
		{
			for ($i=0; $i<=$header['RecordCount']-1; $i++) {
				//print_r($f[$i]);
				//echo "<br>";
				$str = str_replace(' ','',$f[$i]['Municipali']);
				$str = str_replace('(','',$str);
				$str = str_replace(')','',$str);
				$str =  mysqli_real_escape_string($con,strtoupper($str));
				$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where CONCAT(REPLACE(UPPER(lup_locations.location_description), ' ', ''),REPLACE(UPPER(lup_provinces.description), ' ', '')) = '$str' and lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				$ltval = $f[$i]['MEAN'];
				$lt = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_temperature_legends where temp_from <= $ltval and temp_to >= $ltval and isdeleted = 0"));
				
				//$loc = mysqli_fetch_assoc(mysqli_query($con,"Select lup_locations.location_id, CONCAT(TRIM(UPPER(lup_locations.location_description)),TRIM(UPPER(lup_provinces.description))) as loc from lup_locations,lup_provinces where lup_locations.province_id = lup_provinces.province_id and lup_locations.isdeleted = 0"));
				//echo $loc['loc']."<br>";
				//if(!empty($loc))
				//{
					insert('daily_mon_temp',['location_id'=>$loc['location_id'],'daily_mon_date'=>$udate,'mean'=>$f[$i]['MEAN'],'color'=>$lt['color'],'minmax'=>'max']);
				//}
			}
			return $i." record/s has been UploadedDDD";
		}
		
	}
	else{
		return "Did not match";
	}
	
    fclose($fdbf); 
	
}

function agri_daily_wind($status,$issue,$print)
{
		
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from agri_daily_wind where isdeleted = 0";
		
		if(!empty($status) && $status != 'all')
		{	
			$string = $string." and status = $status";
		
		}
		
		if(!empty($issue) && $issue != 'all')
		{	
			$string = $string." and agri_daily_id = $issue";
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
		<table class = "table table-bordered table-hover table-sm" id = "windtable">
			<thead>
				<?PHP
				IF($print == 0)
				{
				?>
				<td><input type = "checkbox" id = "selectall"></td>
				<script>
					$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#windtable input').attr('checked', true);
							} else {
								$('#windtable input').attr('checked', false);
							}
					}
					);
				</script>
				<?php
				}
				?>
				<th>#</th>
				<th>DATE ISSUE</th>
				<th>STATUS</th>		
				<th>WIND CONDITION</th>
				<th>REGIONS</th>
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
				$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
				$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
				?>
				<tr>
					<td><input type = "checkbox" name = "select[<?php echo $row['agri_daily_wind_id'];?>]">
					<input type = "hidden" name = "bbwindstatus" value = "<?php echo $status;?>">
					<input type = "hidden" name = "bbwindissue" value = "<?php echo $issue;?>"></td>
					<td><?php echo $ctr;?></td>
					<td id = "windissue<?php echo $ctr;?>"><?php echo $aginfo['date_issue'];?></td>
					<td id = "windstatus<?php echo $ctr;?>"><?php echo $statuss['status'];?></td>
					<td id = "windcon<?php echo $ctr;?>"><?php echo $row['wind_condition'];?></td>
					<td id = "windloc<?php echo $ctr;?>">
						<?php
							$cquery = mysqli_query($con,"Select description as regions from lup_regions where region_id IN($row[regions])");

							while($crow = mysqli_fetch_assoc($cquery))
							{
								echo $crow['regions']." ";
							}
						?>
					</td>
					<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
					<script>													
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								$("#modal").modal("show");
								$("#modalbody").css("min-width","70%");
								
								$.post( 
									'php/main.php',
									{
										editwindid:'<?php echo $row['agri_daily_wind_id'];?>',
										editwindctr:'<?php echo $ctr;?>'
									},
									function(data) {
										$('#modalui').html(data)
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
											deletewind:'<?php echo $row['agri_daily_wind_id'];?>',
											deletewindcount:'<?php echo $ctr;?>'
										},
										function(data) {
											$('#click').html(data);		
										});
								}
							}
						);
					</SCRIPT>
				</tr>

					
				<?php
				
				$ctr++;
			}
			?>
		</table>
		
		<script>
			$("#document").ready(
				function()
				{
						
					var table = $('#windtable').DataTable({
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
							var check = $('#windtable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "windbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agriwindlist').html(data);															
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
							var check = $('#windtable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "windbatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agriwindlist').html(data);															
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
							var check = $('#windtable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "windbatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agriwindlist').html(data);															
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

function agri_daily_soil($status,$issue,$print)
{
		
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from agri_daily_soil_condition where isdeleted = 0";
		
		if(!empty($status) && $status != 'all')
		{	
			$string = $string." and status = $status";
		
		}
		
		if(!empty($issue) && $issue != 'all')
		{	
			$string = $string." and agri_daily_id = $issue";
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
		<table class = "table table-bordered table-hover table-sm" id = "soiltable">
			<thead>
				<?PHP
				IF($print == 0)
				{
				?>
				<td><input type = "checkbox" id = "selectall"></td>
				<script>
					$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#soiltable input').attr('checked', true);
							} else {
								$('#soiltable input').attr('checked', false);
							}
					}
					);
				</script>
				<?php
				}
				?>
				<th>#</th>
				<th>DATE ISSUE</th>
				<th>STATUS</th>		
				<th>SOIL CONDITION</th>
				<th>LOCATION</th>
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
				$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
				$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
				$sw = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_soil_wetness where soil_wetness_id = $row[soil_condition]"));
				?>
				<tr>
					<td><input type = "checkbox" name = "select[<?php echo $row['agri_daily_soil_id'];?>]">
					<input type = "hidden" name = "soilstatus" value = "<?php echo $status;?>">
					<input type = "hidden" name = "soilissue" value = "<?php echo $issue;?>"></td>
					<td><?php echo $ctr;?></td>
					<td id = "soilissue<?php echo $ctr;?>"><?php echo $aginfo['date_issue'];?></td>
					<td id = "soilstatus<?php echo $ctr;?>"><?php echo $statuss['status'];?></td>
					<td id = "soilmin<?php echo $ctr;?>"><?php echo $sw['description'];?></td>
					<td id = "soilloc<?php echo $ctr;?>">
						<?php
							$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($row[provinces])");

							while($crow = mysqli_fetch_assoc($cquery))
							{
								echo $crow['Provinces']." ";
							}
						?>
					</td>
					<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
					<script>													
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								$("#modal").modal("show");
								$("#modalbody").css("min-width","70%");
								
								$.post( 
									'php/main.php',
									{
										editsoilid:'<?php echo $row['agri_daily_soil_id'];?>',
										editsoilctr:'<?php echo $ctr;?>'
									},
									function(data) {
										$('#modalui').html(data)
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
											deletesoil:'<?php echo $row['agri_daily_soil_id'];?>',
											deletesoilcount:'<?php echo $ctr;?>'
										},
										function(data) {
											$('#click').html(data);		
										});
								}
							}
						);
					</SCRIPT>
				</tr>

					
				<?php
				
				$ctr++;
			}
			?>
		</table>
		
		<script>
			$("#document").ready(
				function()
				{
						
					var table = $('#soiltable').DataTable({
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
							var check = $('#soiltable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "soilbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrisoillist').html(data);															
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
							var check = $('#soiltable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "soilbatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrisoillist').html(data);															
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
							var check = $('#soiltable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "soilbatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrisoillist').html(data);															
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

function agri_daily_leaf($status,$issue,$print)
{
		
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from agri_daily_leaf where isdeleted = 0";
		
		if(!empty($status) && $status != 'all')
		{	
			$string = $string." and status = $status";
		
		}
		
		if(!empty($issue) && $issue != 'all')
		{	
			$string = $string." and agri_daily_id = $issue";
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
		<table class = "table table-bordered table-hover table-sm" id = "leaftable">
			<thead>
				<?PHP
				IF($print == 0)
				{
				?>
				<td><input type = "checkbox" id = "selectall"></td>
				<script>
					$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#leaftable input').attr('checked', true);
							} else {
								$('#leaftable input').attr('checked', false);
							}
					}
					);
				</script>
				<?php
				}
				?>
				<th>#</th>
				<th>DATE ISSUE</th>
				<th>STATUS</th>		
				<th>MIN LEAF WETNESS</th>
				<th>MAX LEAF WETNESS</th>
				<th>LOCATION</th>
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
				$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
				$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
				?>
				<tr>
					<td><input type = "checkbox" name = "select[<?php echo $row['agri_daily_leaf_id'];?>]">
					<input type = "hidden" name = "leafstatus" value = "<?php echo $status;?>">
					<input type = "hidden" name = "leafissue" value = "<?php echo $issue;?>"></td>
					<td><?php echo $ctr;?></td>
					<td id = "leafissue<?php echo $ctr;?>"><?php echo $aginfo['date_issue'];?></td>
					<td id = "leafstatus<?php echo $ctr;?>"><?php echo $statuss['status'];?></td>
					<td id = "leafmin<?php echo $ctr;?>"><?php echo $row['leaf_min'];?></td>
					<td id = "leafmax<?php echo $ctr;?>"><?php echo $row['leaf_max'];?></td>
					<td id = "leafloc<?php echo $ctr;?>">
						<?php
							$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($row[provinces])");

							while($crow = mysqli_fetch_assoc($cquery))
							{
								echo $crow['Provinces']." ";
							}
						?>
					</td>
					<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
					<script>													
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								$("#modal").modal("show");
								$("#modalbody").css("min-width","70%");
								
								$.post( 
									'php/main.php',
									{
										editleafid:'<?php echo $row['agri_daily_leaf_id'];?>',
										editleafctr:'<?php echo $ctr;?>'
									},
									function(data) {
										$('#modalui').html(data)
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
											deleteleaf:'<?php echo $row['agri_daily_leaf_id'];?>',
											deleteleafcount:'<?php echo $ctr;?>'
										},
										function(data) {
											$('#click').html(data);		
										});
								}
							}
						);
					</SCRIPT>
				</tr>

					
				<?php
				
				$ctr++;
			}
			?>
		</table>
		
		<script>
			$("#document").ready(
				function()
				{
						
					var table = $('#leaftable').DataTable({
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
							var check = $('#leaftable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "leafbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrileaflist').html(data);															
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
							var check = $('#leaftable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "leafbatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrileaflist').html(data);															
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
							var check = $('#leaftable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "leafbatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrileaflist').html(data);															
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

function agri_daily_humidity($status,$issue,$print)
{
		
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from agri_daily_humidity where isdeleted = 0";
		
		if(!empty($status) && $status != 'all')
		{	
			$string = $string." and status = $status";
		
		}
		
		if(!empty($issue) && $issue != 'all')
		{	
			$string = $string." and agri_daily_id = $issue";
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
		<table class = "table table-bordered table-hover table-sm" id = "humiditytable">
			<thead>
				<?PHP
				IF($print == 0)
				{
				?>
				<td><input type = "checkbox" id = "selectall"></td>
				<script>
					$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#humiditytable input').attr('checked', true);
							} else {
								$('#humiditytable input').attr('checked', false);
							}
					}
					);
				</script>
				<?php
				}
				?>
				<th>#</th>
				<th>DATE ISSUE</th>
				<th>STATUS</th>		
				<th>MIN HUMIDITY</th>
				<th>MAX HUMIDITY</th>
				<th>LOCATION</th>
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
				$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
				$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
				?>
				<tr>
					<td><input type = "checkbox" name = "select[<?php echo $row['agri_daily_humidity_id'];?>]">
					<input type = "hidden" name = "humstatus" value = "<?php echo $status;?>">
					<input type = "hidden" name = "humissue" value = "<?php echo $issue;?>"></td>
					<td><?php echo $ctr;?></td>
					<td id = "hissue<?php echo $ctr;?>"><?php echo $aginfo['date_issue'];?></td>
					<td id = "hstatus<?php echo $ctr;?>"><?php echo $statuss['status'];?></td>
					<td id = "hmin<?php echo $ctr;?>"><?php echo $row['humidity_min'];?></td>
					<td id = "hmax<?php echo $ctr;?>"><?php echo $row['humidity_max'];?></td>
					<td id = "hloc<?php echo $ctr;?>">
						<?php
							$cquery = mysqli_query($con,"Select description as Provinces from lup_provinces where province_id IN($row[provinces])");

							while($crow = mysqli_fetch_assoc($cquery))
							{
								echo $crow['Provinces']." ";
							}
						?>
					</td>
					<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-primary btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
					</td>
					<script>													
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								$("#modal").modal("show");
								$("#modalbody").css("min-width","70%");
								
								$.post( 
									'php/main.php',
									{
										editahumid:'<?php echo $row['agri_daily_humidity_id'];?>',
										editahumctr:'<?php echo $ctr;?>'
									},
									function(data) {
										$('#modalui').html(data)
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
											deleteahum:'<?php echo $row['agri_daily_humidity_id'];?>',
											deleteahumcount:'<?php echo $ctr;?>'
										},
										function(data) {
											$('#click').html(data);		
										});
								}
							}
						);
					</SCRIPT>
				</tr>

					
				<?php
				
				$ctr++;
			}
			?>
		</table>
		
		<script>
			$("#document").ready(
				function()
				{
						
					var table = $('#humiditytable').DataTable({
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
							var check = $('#humiditytable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "humbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrihumlist').html(data);															
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
							var check = $('#humiditytable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "humbatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrihumlist').html(data);															
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
							var check = $('#humiditytable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "humbatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#agrihumlist').html(data);															
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

function synopsis($status,$issue,$print)
{
		
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from agri_daily_synopsis where isdeleted = 0";
		
		if(!empty($status) && $status != 'ALL')
		{	
			$string = $string." status = $status";
		
		}
		
		if(!empty($issue) && $issue != 'ALL')
		{	
			$string = $string." and agri_daily_id = $issue";
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
		<table class = "table table-bordered table-hover table-sm" id = "synopsistable">
			<thead>
				<?PHP
				IF($print == 0)
				{
				?>
				<td><input type = "checkbox" id = "selectall"></td>
				<script>
					$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#synopsistable input').attr('checked', true);
							} else {
								$('#synopsistable input').attr('checked', false);
							}
					}
					);
				</script>
				<?php
				}
				?>
				<th>#</th>
				<th>DATE ISSUE</th>
				<th>TITLE</th>
				<th>STATUS</th>				
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
				$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_daily where agri_daily_id = $row[agri_daily_id]"));
				$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
				if($print == 1)
				{
				?>
				<tr>
					
					<td><?php echo $ctr;?></td>
					<td><?php echo $aginfo['date_issue'];?></td>
					<td><?php echo $row['title'];?></td>
					<td><?php echo $statuss['status'];?></td>
				</tr>

					
				<?php
				}
				else
				{
					?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['synopsis_id'];?>]">
								<input type = "hidden" name = "sypstatus" value = "<?php echo $status;?>">
								<input type = "hidden" name = "sypissue" value = "<?php echo $issue;?>">
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $aginfo['date_issue'];?></td>
							<td><?php echo $row['title'];?></td>
							<td><?php echo $statuss['status'];?></td>
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
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
										editsysid:'<?php echo $row['synopsis_id'];?>',
										editsyslevel:'1',
										editsysstatus:'<?php echo $status;?>',
										editsysissue:'<?php echo $issue;?>'
									},
									function(data) {
										$('#contentui').html(data);
									
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
											deletesys:'<?php echo $row['synopsis_id'];?>',
											deletesyscount:'<?php echo $ctr;?>'
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
						
					var table = $('#synopsistable').DataTable({
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
							var check = $('#synopsistable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "sypbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#synopsislist').html(data);															
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
							var check = $('#synopsistable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "sypbatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#synopsislist').html(data);															
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
							var check = $('#synopsistable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "sypbatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#synopsislist').html(data);															
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

function agridailyissue($level,$print)
{
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from agri_daily where isdeleted = 0"; 
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
				<th><input type = "checkbox" name = "selectall" id = "selectall"></th>
				
				<script>
					$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#agriissue input').attr('checked', true);
							} else {
								$('#agriissue input').attr('checked', false);
							}
					}
					);
				</script>
				
				<?php
				}
				?>
				<th>#</th>
				<th>DATE ISSUE</th>
				<th>VALIDITY DATE</th>				
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
				$sta = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
				if($print == 1)
				{
				?>
				<tr>
					
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['date_issue'];?></td>
					<td><?php echo $row['validity_date'];?></td>
					<td><?php
						echo $sta['status'];
					?></td>
				</tr>
					
					
				<?php
				}
				else
				{
					?>
					<tr>
						<input type = "hidden" name = "ilevel" value = "<?php echo $level;?>">
						<td><input type = "checkbox" name = "select[<?php echo $row['agri_daily_id'];?>]"></td>
						<td><?php echo $ctr;?></td>
						<td><input type="datetime-local" id = "adidateissue<?php echo $ctr;?>" name = "adidateissue<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['date_issue'];?>"></td>
						<td><input type="datetime-local" id = "adivalidity<?php echo $ctr;?>" name = "adivalidity<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['validity_date'];?>"></td>
						<td>
											<?PHP
											$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
											$pquery = mysqli_query($con,"select * from lup_status where isdeleted = 0");
											?>
											<select name = "adistatus<?php echo $ctr;?>" id = "adistatus<?php echo $ctr;?>" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
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
										editadissueid:'<?php echo $row['agri_daily_id'];?>',
										editadidateissue:$("#adidateissue<?php echo $ctr;?>").val(),
										editadivalidity:$("#adivalidity<?php echo $ctr;?>").val(),
										editadistatus:$("#adistatus<?php echo $ctr;?>").val()
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
											deleteadissueid:'<?php echo $row['agri_daily_id'];?>',
											deleteadissuecount:'<?php echo $ctr;?>'
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
										name: "adissuebatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#adissuelist').html(data);															
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
										name: "adissuebatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#adissuelist').html(data);															
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
										name: "adissuebatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#adissuelist').html(data);															
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

function prognosis($status,$issue,$location,$print)
{
		
		global $con;
		$user = get_user_id($_SESSION['forecast']);
		$agent = get_agent($user);

		$string = "Select * from agri_prognosis, lup_regions where agri_prognosis.isdeleted = 0
		and agri_prognosis.region_id = lup_regions.region_id";
		
		if(!empty($status) && $status != 'ALL')
		{	
			$string = $string." and agri_prognosis.status = $status";
		
		}
		
		if(!empty($location) && $location != 'ALL')
		{	
			$string = $string." and lup_regions.region_id = $location";
		
		}
		
		if(!empty($issue) && $issue != 'ALL')
		{	
			$string = $string." and agri_prognosis.agri_info_id = $issue";
		
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
		<table class = "table table-bordered table-hover table-sm" id = "prognosistable">
			<thead>
				<?PHP
				IF($print == 0)
				{
				?>
				<td><input type = "checkbox" id = "selectall"></td>
				<script>
					$("#selectall").click(
					function()
					{
							if ($(this).is(':checked')) {
								$('#prognosistable input').attr('checked', true);
							} else {
								$('#prognosistable input').attr('checked', false);
							}
					}
					);
				</script>
				<?php
				}
				?>
				<th>#</th>
				<th>DATE ISSUE</th>
				<th>REGION</th>
				<th>STATUS</th>				
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
				$aginfo = mysqli_fetch_assoc(mysqli_query($con,"Select * from agri_info where agri_info_id = $row[agri_info_id]"));
				$statuss = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_status where status_id = $row[status]"));
				if($print == 1)
				{
				?>
				<tr>
					
					<td><?php echo $ctr;?></td>
					<td><?php echo $aginfo['date_from']." to ".$aginfo['date_to'];?></td>
					<td><?php echo $row['description'];?></td>
					<td><?php echo $statuss['status'];?></td>
				</tr>

					
				<?php
				}
				else
				{
					?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['prognosis_id'];?>]">
								<input type = "hidden" name = "progstatus" value = "<?php echo $status;?>">
								<input type = "hidden" name = "progissue" value = "<?php echo $issue;?>">
								<input type = "hidden" name = "proglocation" value = "<?php echo $location;?>">
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $aginfo['date_from']." to ".$aginfo['date_to'];?></td>
							<td><?php echo $row['description'];?></td>
							<td><?php echo $statuss['status'];?></td>
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
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
										editprogid:'<?php echo $row['prognosis_id'];?>',
										editproglevel:'1',
										editprogstatus:'<?php echo $status;?>',
										editprogissue:'<?php echo $issue;?>',
										editproglocation:'<?php echo $location;?>'
									},
									function(data) {
										$('#contentui').html(data);
										
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
											deleteprog:'<?php echo $row['prognosis_id'];?>',
											deleteprogcount:'<?php echo $ctr;?>'
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
						
					var table = $('#prognosistable').DataTable({
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
							var check = $('#prognosistable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "progbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#proglist').html(data);															
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
							var check = $('#prognosistable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "progbatchpub",
										value: 'pub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#proglist').html(data);															
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
							var check = $('#prognosistable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									var data = table.$('input').serializeArray();
									data.push({
										name: "progbatchunpub",
										value: 'unpub'
									});
									data = jQuery.param(data);
								
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#proglist').html(data);															
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
				<th><input type = "checkbox" name = "selectall" id = "selectall"></th>
				<script>
					$("#selectall").click(
						function()
						{
								if ($(this).is(':checked')) {
									$('#agriissue input').attr('checked', true);
								} else {
									$('#agriissue input').attr('checked', false);
								}
						}
					);
				</script>
			
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
				$print = 1;
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