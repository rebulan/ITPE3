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
function intsales($client,$user,$print)
{
		global $con;
		$user = get_user_id($user);
		$agent = get_agent($user);

		$string = "Select * from int_sales_purchase where isdeleted = 0 and client_id = $client and added_by = $user"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> BATCH DELETE </button>
					</div>
				</div><br>
				
		<table class = "table table-bordered table-hover table-sm" id = "intsalestable">
			<thead>
				<th><input type = "checkbox" id = "selectall">
					<script>
						$("#selectall").click(
						function()
						{
								if ($(this).is(':checked')) {
									$('#intsalestable input').attr('checked', true);
								} else {
									$('#intsalestable input').attr('checked', false);
								}
						}
						);
					</script>
				</th>
				<th>#</th>
				<th>CUSTOMER NAME</th>
				<th>TIN</th>
				<th>ADDRESS</th>	
				<th>GROSS VAT</th>
				<th>NET VAT</th>
				<th>SALES TYPE</th>	
				<th>TRANSACTION DATE</th>				
				<th>ADDED BY</th>
				<th>DATE ADDED</th>
				
				<th></th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$added = get_agent($row['added_by']);
				//$upby= get_agent($row['update_by']);
				$stype = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_sales_type where ID = $row['sales_type']"));
				?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['ID'];?>]"></td>
							<td><?php echo $ctr;?></td>
							
							<td><input type="text" ID="intscus<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['customer_name'];?>"></td>
							<td><input type="text" ID="intstin<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['tin'];?>"></td>
							<td><input type="text" ID="intsaddress<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['address'];?>"></td>
							<td><input type="text" ID="intsgvat<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['gross_vat'];?>"></td>
							<td><input type="text" ID="instnvat<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['net_vat'];?>"></td>
							<td><Select class = "form-control" id = "intssalestype<?php echo $ctr;?>">
															<option value = "<?php echo $stype['ID'];?>" hidden "Selected">"<?php echo $stype['sales_type'];?></option>
														<?php
														$pmquery = mysqli_query($con,"Select * from lup_sales_type where isdeleted = 0");
														while($prow = mysqli_fetch_assoc($pmquery))
														{
														?>
															<option value = "<?php echo $prow['ID'];?>"><?php echo $prow['sales_type'];?></option>
														
														<?php
														}
														?>
														</select>
							</td>
							<td><input type="date" ID="intsdate<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['transaction_date'];?>"></td>
							<td><?php echo $added;?></td>
							<td><?php echo $row['date_added'];?></td>
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>">UPDATE</button>	
							</td>
						</tr>
						<script>								
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								var r = confirm("Confirm Update");
								if(r == true)
								{
									$.post( 
										'php/main.php',
										{
											editintsid:'<?php echo $row['ID'];?>',
											editintscus:$("#intscus<?php echo $ctr;?>").val(),
											editintstin:$("#intstin<?php echo $ctr;?>").val(),
											editintsaddress:$("#intsaddress<?php echo $ctr;?>").val(),
											editintstin:$("#intstin<?php echo $ctr;?>").val(),
											editintsgvat:$("#intsgvat<?php echo $ctr;?>").val(),
											editintnvat:$("#intnvat<?php echo $ctr;?>").val(),
											editintsdate:$("#intsdate<?php echo $ctr;?>").val()
										},
										function(data) {
											$('#click').html(data)
										});
								}
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
											deleteintsid:'<?php echo $row['ID'];?>',
											deleteinyscount:'<?php echo $ctr;?>'
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
		<?php
		if($print == 0)
		{
		?>
			<script>
				$("#document").ready(
				function()
				{
						
					 var table = $('#sttable').DataTable({
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
							var check = $('#sttable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "stbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#listui').html(data);															
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
					
				}
			);
			</script>
		<?php
		}
		?>
	<?php
}

function clientlist($bir,$bunit,$print)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from client_info where isdeleted = 0"; 
		
		if($bir != '' && $bir != 'ALL')
		{
			$string = $string." and bir_office = $bir";
		}
		if($bunit != '' && $bunit != 'ALL')
		{
			//$string = $string." and engagement_id = $en";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "cltable">
			<thead>
				<th>#</th>
				<th>CLIENT DESCRIPTION</th>
				<th>BIR OFFICE</th>
				<th>ADDED BY</th>
				<th>DATE ADDED</th>			
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$added = get_agent($row['added_by']);
				$bir = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_bir_office where ID = $row[bir_office]"));
				?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['business_name'];?></td>
							<td><?php echo $bir['office_name'];?></td>
							<td><?php echo $added;?></td>
							<td><?php echo $row['date_added'];?></td>
							
						</tr>
				
					<?php
				
				$ctr++;
			}
			?>
		</table>
		<?php
		if($print == 0)
		{
		?>
			<script>
				$("#document").ready(
				function()
				{
						
					 var table = $('#cltable').DataTable({
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
	<?php
}

function cengagement($client,$en, $print)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from client_engagements where isdeleted = 0"; 
		if($client != '' && $client != 'ALL')
		{
			$string = $string." and client_id = $client";
		}
		if($en != '' && $en != 'ALL')
		{
			$string = $string." and engagement_id = $en";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> BATCH DELETE </button>
					</div>
				</div><br>
				
		<table class = "table table-bordered table-hover table-sm" id = "centable">
			<thead>
				<th><input type = "checkbox" id = "selectall">
					<script>
						$("#selectall").click(
						function()
						{
								if ($(this).is(':checked')) {
									$('#centable input').attr('checked', true);
								} else {
									$('#centable input').attr('checked', false);
								}
						}
						);
					</script>
				</th>
				<th>#</th>
				<th>CLIENT</th>
				<th>ENGAGEMENT DESCRIPTION</th>
				<th>ADDED BY</th>
				<th>DATE ADDED</th>
				<th></th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$added = get_agent($row['added_by']);
				$cl = mysqli_fetch_assoc(mysqli_query($con,"Select * from client_info where ID = $row[client_id]"));
				$en = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_engagements where ID = $row[engagement_id]"));
				?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['ID'];?>]"></td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $cl['business_name'];?></td>
							<td><?php echo $en['engagement'];?></td>
							<td><?php echo $added;?></td>
							<td><?php echo $row['date_added'];?></td>
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
											deletecenid:'<?php echo $row['ID'];?>',
											deletecencount:'<?php echo $ctr;?>'
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
		<?php
		if($print == 0)
		{
		?>
			<script>
				$("#document").ready(
				function()
				{
						
					 var table = $('#centable').DataTable({
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
							var check = $('#centable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "cenbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#listui').html(data);															
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
					
				}
			);
			</script>
		<?php
		}
		?>
	<?php
}

function engagement($print)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from lup_engagements where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> BATCH DELETE </button>
					</div>
				</div><br>
				
		<table class = "table table-bordered table-hover table-sm" id = "entable">
			<thead>
				<th><input type = "checkbox" id = "selectall">
					<script>
						$("#selectall").click(
						function()
						{
								if ($(this).is(':checked')) {
									$('#entable input').attr('checked', true);
								} else {
									$('#entable input').attr('checked', false);
								}
						}
						);
					</script>
				</th>
				<th>#</th>
				<th>ENGAGEMENT DESCRIPTION</th>
				<th>ADDED BY</th>
				<th>DATE ADDED</th>
				<th>LAST UPDATE</th>	
				<th>UPDATED BY</th>	
				<th></th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$added = get_agent($row['added_by']);
				$upby= get_agent($row['update_by']);
				?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['ID'];?>]"></td>
							<td><?php echo $ctr;?></td>
							<td><input type="text" ID="endes<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['engagement'];?>"></td>
							<td><?php echo $added;?></td>
							<td><?php echo $row['date_added'];?></td>
							<td id = "lu<?php echo $ctr;?>"><?php echo $row['last_update'];?></td>
							<td id = "upby<?php echo $ctr;?>"><?php echo $upby;?></td>
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>">UPDATE</button>	
							</td>
						</tr>
						<script>								
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								var r = confirm("Confirm Update");
								if(r == true)
								{
									$.post( 
										'php/main.php',
										{
											editenid:'<?php echo $row['ID'];?>',
											editendes:$("#endes<?php echo $ctr;?>").val(),
											editenpercent:$("#enpercent<?php echo $ctr;?>").val(),
											editencount:'<?php echo $ctr;?>'
										},
										function(data) {
											$('#click').html(data)
											
										});
								}
									
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
											deleteenid:'<?php echo $row['ID'];?>',
											deleteencount:'<?php echo $ctr;?>'
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
		<?php
		if($print == 0)
		{
		?>
			<script>
				$("#document").ready(
				function()
				{
						
					 var table = $('#entable').DataTable({
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
							var check = $('#entable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "enbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#listui').html(data);															
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
					
				}
			);
			</script>
		<?php
		}
		?>
	<?php
}

function purchasetype($print)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from lup_purchase_type where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> BATCH DELETE </button>
					</div>
				</div><br>
				
		<table class = "table table-bordered table-hover table-sm" id = "pttable">
			<thead>
				<th><input type = "checkbox" id = "selectall">
					<script>
						$("#selectall").click(
						function()
						{
								if ($(this).is(':checked')) {
									$('#pttable input').attr('checked', true);
								} else {
									$('#pttable input').attr('checked', false);
								}
						}
						);
					</script>
				</th>
				<th>#</th>
				<th>PURCHASE TYPE DESCRIPTION</th>
				<th>%</th>
				<th>ADDED BY</th>
				<th>DATE ADDED</th>
				<th>LAST UPDATE</th>	
				<th>UPDATED BY</th>	
				<th></th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$added = get_agent($row['added_by']);
				$upby= get_agent($row['update_by']);
				?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['ID'];?>]"></td>
							<td><?php echo $ctr;?></td>
							<td><input type="text" ID="ptdes<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['purchase_type'];?>"></td>
							<td><input type="number" step = "0.01" ID="ptpercent<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['percent'];?>"></td>
							<td><?php echo $added;?></td>
							<td><?php echo $row['date_added'];?></td>
							<td id = "lu<?php echo $ctr;?>"><?php echo $row['last_update'];?></td>
							<td id = "upby<?php echo $ctr;?>"><?php echo $upby;?></td>
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>">UPDATE</button>	
							</td>
						</tr>
						<script>								
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								var r = confirm("Confirm Update");
								if(r == true)
								{
									$.post( 
										'php/main.php',
										{
											editptid:'<?php echo $row['ID'];?>',
											editptdes:$("#ptdes<?php echo $ctr;?>").val(),
											editptpercent:$("#ptpercent<?php echo $ctr;?>").val(),
											editptcount:'<?php echo $ctr;?>'
										},
										function(data) {
											$('#click').html(data)
											
										});
								}
									
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
											deleteptid:'<?php echo $row['ID'];?>',
											deleteptcount:'<?php echo $ctr;?>'
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
		<?php
		if($print == 0)
		{
		?>
			<script>
				$("#document").ready(
				function()
				{
						
					 var table = $('#pttable').DataTable({
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
							var check = $('#pttable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "ptbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#listui').html(data);															
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
					
				}
			);
			</script>
		<?php
		}
		?>
	<?php
}

function salestype($print)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from lup_sales_type where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> BATCH DELETE </button>
					</div>
				</div><br>
				
		<table class = "table table-bordered table-hover table-sm" id = "sttable">
			<thead>
				<th><input type = "checkbox" id = "selectall">
					<script>
						$("#selectall").click(
						function()
						{
								if ($(this).is(':checked')) {
									$('#sttable input').attr('checked', true);
								} else {
									$('#sttable input').attr('checked', false);
								}
						}
						);
					</script>
				</th>
				<th>#</th>
				<th>SALE TYPE DESCRIPTION</th>
				<th>%</th>
				<th>ADDED BY</th>
				<th>DATE ADDED</th>
				<th>LAST UPDATE</th>	
				<th>UPDATED BY</th>	
				<th></th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$added = get_agent($row['added_by']);
				$upby= get_agent($row['update_by']);
				?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['ID'];?>]"></td>
							<td><?php echo $ctr;?></td>
							
							<td><input type="text" ID="stdes<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['sales_type'];?>"></td>
							<td><input type="text" ID="stpercent<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['percent'];?>"></td>
							<td><?php echo $added;?></td>
							<td><?php echo $row['date_added'];?></td>
							<td id = "lu<?php echo $ctr;?>"><?php echo $row['last_update'];?></td>
							<td id = "upby<?php echo $ctr;?>"><?php echo $upby;?></td>
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>">UPDATE</button>	
							</td>
						</tr>
						<script>								
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								var r = confirm("Confirm Update");
								if(r == true)
								{
									$.post( 
										'php/main.php',
										{
											editstid:'<?php echo $row['ID'];?>',
											editstdes:$("#stdes<?php echo $ctr;?>").val(),
											editstpercent:$("#stpercent<?php echo $ctr;?>").val(),
											editstcount:'<?php echo $ctr;?>'
										},
										function(data) {
											$('#click').html(data)
										});
								}
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
											deletestid:'<?php echo $row['ID'];?>',
											deletestcount:'<?php echo $ctr;?>'
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
		<?php
		if($print == 0)
		{
		?>
			<script>
				$("#document").ready(
				function()
				{
						
					 var table = $('#sttable').DataTable({
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
							var check = $('#sttable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "stbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#listui').html(data);															
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
					
				}
			);
			</script>
		<?php
		}
		?>
	<?php
}

function bir($print)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from lup_bir_office where isdeleted = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> BATCH DELETE </button>
					</div>
				</div><br>
				
		<table class = "table table-bordered table-hover table-sm" id = "birtable">
			<thead>
				<th><input type = "checkbox" id = "selectall">
					<script>
						$("#selectall").click(
						function()
						{
								if ($(this).is(':checked')) {
									$('#teamtable input').attr('checked', true);
								} else {
									$('#teamtable input').attr('checked', false);
								}
						}
						);
					</script>
				</th>
				<th>#</th>
				<th>OFFICE DESCRIPTION</th>
				<th>ADDED BY</th>	
				<th></th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$added = get_agent($row['added_by']);
				?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['ID'];?>]"></td>
							<td><?php echo $ctr;?></td>
							
							<td><input type="text" ID="birdes<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['office_name'];?>"></td>
							<td><?php echo $added;?></td>
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>">UPDATE</button>	
							</td>
						</tr>
						<script>								
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								var r = confirm("Confirm Update");
								if(r == true)
								{
									$.post( 
										'php/main.php',
										{
											editbirid:'<?php echo $row['ID'];?>',
											editbirdes:$("#birdes<?php echo $ctr;?>").val()
										},
										function(data) {
											$('#click').html(data)
											
										});
								}
									
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
											deletebirid:'<?php echo $row['ID'];?>',
											deletebircount:'<?php echo $ctr;?>'
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
		<?php
		if($print == 0)
		{
		?>
			<script>
				$("#document").ready(
				function()
				{
						
					 var table = $('#birtable').DataTable({
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
							var check = $('#birtable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "birbatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#listui').html(data);															
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
					
				}
			);
			</script>
		<?php
		}
		?>
	<?php
}

function assign_history($assign)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from client_assignment where client_id = $assign order by date_added"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "assigntable">
			<thead>
				
				<th>#</th>
				<th>ASSIGNED TO</th>
				<th>DATE ASSIGNED</th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				
				$auser = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $row[user_id]"));
				$team = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_team where team_id = $auser[team_id]"));
				?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $auser['fullname']."-".$team['team_name'];?></td>
							<td><?php echo $row['date_added'];?></td>
						
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
						
					 var table = $('#assigntable').DataTable({
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

function client_assignment($assign)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from client_info where isdeleted = 0"; 
		
		if($assign != '' && $assign != 'all')
		{
			$string = $string." and cassign = $assign";
		}
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "clienttable">
			<thead>
				
				<th>#</th>
				<th>BUSINESS NAME</th>
				<th>ASSIGNED TO</th>
				<th></th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$team = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_team where team_id = $row[team_id]"));
				$auser = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $row[cassign]"));
				?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['business_name'];?></td>
							<td id = "assuserui<?php echo $ctr;?>" >
													<select style = "width:100%;" class = "form-control" name = "asuser<?php echo $ctr;?>" id = "asuser<?php echo $ctr;?>">
														<option value = "<?php echo $auser['user_id'];?>" hidden "Selected"><?php echo $auser['fullname']."-".$team['team_name'];?></option>
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
													</td>
							<td id = "controlui<?php echo $ctr;?>">	
								<button class = "btn btn-success btn-flat btn-xs" id = "update<?php echo $ctr;?>">UPDATE ASSIGNMENT</button>	
								<button class = "btn btn-warning btn-flat btn-xs" id = "history<?php echo $ctr;?>">ASSIGNMENT HISTORY</button>
							</td>
						</tr>
						<script>	

						$("#asuser<?php echo $ctr;?>").select2(
							{
								 width: 'resolve'
							}
						);
						
						$("#history<?php echo $ctr;?>").click(
									function(e)
									{
															e.preventDefault();
															$("#modal").modal("show");
															$('#modalui').html(loading);
																	
																	$.post( 
																				'php/main.php',
																				{
																					updateassign:'<?php echo $row['ID'];?>',
																					updateassignctr:'<?php echo $ctr;?>'
																				},
																				function(data) {
																					$('#modalui').html(data);	
																				
																				});
															
									}
								);
						$("#update<?php echo $ctr;?>").click(
									function(e)
									{
															
												var r = confirm("Confirm Action");

												if(r == true)
												{
																	$.post( 
																				'php/main.php',
																				{
																					asclient:'<?php echo $row['ID'];?>',
																					asuser:$("#asuser<?php echo$ctr;?>").val()
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
						
					 var table = $('#clienttable').DataTable({
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

function teams($print)
{
		global $con;
		$user = get_user_id($_SESSION['bvr']);
		$agent = get_agent($user);

		$string = "Select * from lup_team where isdeleted = 0 and no_team = 0"; 
		//echo $string;
		$query = mysqli_query($con,$string);
	?>
				<div class="row">
					<div class="col-lg-2 col-xs-6">
						<button class = "btn btn-danger btn-flat btn-block btn-sm" id = "bdelete"><i class="fa fa-remove"></i> BATCH DELETE </button>
					</div>
				</div><br>
				
		<table class = "table table-bordered table-hover table-sm" id = "teamtable">
			<thead>
				<th><input type = "checkbox" id = "selectall">
					<script>
						$("#selectall").click(
						function()
						{
								if ($(this).is(':checked')) {
									$('#teamtable input').attr('checked', true);
								} else {
									$('#teamtable input').attr('checked', false);
								}
						}
						);
					</script>
				
				</th>
				<th>#</th>
				<th>TEAM DESCRIPTION</th>
				<th>TEAM LEADER</th>	
				<th></th>				
			</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
						<tr>
							<td><input type = "checkbox" name = "select[<?php echo $row['team_id'];?>]"></td>
							<td><?php echo $ctr;?></td>
							
							<td><input type="text" ID="teamdes<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['team_name'];?>"></td>
							<td>
											<?PHP
											$pquery = mysqli_query($con,"select * from se_user where isdeleted = 0");
											$tlead = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $row[team_leader]"));
											?>
											<select style = "width:100%;"name = "teamleader<?php echo $ctr;?>" id = "teamleader<?php echo $ctr;?>" class="form-control"  data-validation="required" data-validation-error-msg="Select Location">
															<option value = '<?php echo $tlead['user_id'];?>' hidden "Selected"><?php echo $tlead['fullname'];?></option>
														<?php
															while($prow = mysqli_fetch_assoc($pquery))
															{
														?>
															<option value = "<?php echo $prow['user_id'];?>"><?php echo $prow['fullname'];?></option>
														<?php
															}
														?>
											</select>
											
							</td>
	
							<td id = "controlui<?php echo $ctr;?>">
								<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
								<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>">UPDATE</button>	
							</td>
						</tr>
						<script>	
						$("#teamleader<?php echo $ctr;?>").select2(
							{
								 width: 'resolve'
							}
						);								
						$("#edit<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();	
								
								var r = confirm("Confirm Update");
								if(r == true)
								{
									$.post( 
										'php/main.php',
										{
											editteamid:'<?php echo $row['team_id'];?>',
											editeamdes:$("#teamdes<?php echo $ctr;?>").val(),
											editeamleader:$("#teamleader<?php echo $ctr;?>").val()
										},
										function(data) {
											$('#click').html(data)
											
										});
								}
									
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
											deleteteamid:'<?php echo $row['team_id'];?>',
											deleteteamcount:'<?php echo $ctr;?>'
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
		<?php
		if($print == 0)
		{
		?>
			<script>
				$("#document").ready(
				function()
				{
						
					 var table = $('#teamtable').DataTable({
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
							var check = $('#teamtable').find('input[type=checkbox]:checked').length;
							
							if(check != 0)
							{
								var r = confirm("confirm Action");

								if(r == true)
								{
									
									var data = table.$('input').serializeArray();
									data.push({
										name: "teambatchdelete",
										value: 'delete'
									});
									data = jQuery.param(data);
									
									$.ajax({
										url :  'php/main.php',
										type : 'post',
										datatype : 'json',
										data : data,		
										success : function(data) {
											$('#teamlist').html(data);															
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
					
				}
			);
			</script>
		<?php
		}
		?>
	<?php
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
		$user = get_user_id($_SESSION['bvr']);
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
							if(!empty($team))
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
function client_info($id)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from client_info where ID = $id"));
	
	?>
			<table class = "table table-condensed table-sm">
								<tr>
									<td>TIN: <b><?php echo $row['TIN'];?></b></td>
									<td>Business Name: <b><?php echo $row['business_name'];?></b></td>
								</tr>
								
			</table>
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

?>