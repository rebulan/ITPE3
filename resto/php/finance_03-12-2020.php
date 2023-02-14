<?php
include('connect.php');
include("general.php");

if(isset($_REQUEST['pmonitoringui']))
{
	?>
		<form id = "pfilterform" method = "POST">
		<div class="box box-warning">
			<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "pfdfrom" id = "pfdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "pfdto" id = "pfdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
						</div>
						
						
					
				
			</div>
		</div>
		<div class="box box-warning">
			<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								
								 <div class="form-group">
										  <label for="age">REMITTANCE STATUS:</label>
											
											<select id="sfremit" name = "sfremit" class="form-control" data-validation="required"
													data-validation-error-msg="Select REMITTANCE STATUS">
													<option "Selected">ALL</option>
													<option value = "1">REMITTED</option>
													<option value = "2">RECEIVED</option>
											</select>										
								</div>
							</div>
							<div class="col-md-4">
								
								 <div class="form-group">
										  <label for="age">APPROVAL STATUS:</label>
											
											<select id="pfapp" name = "pfapp" class="form-control" data-validation="required"
													data-validation-error-msg="Select APPROVAL STATUS">
													<option "Selected">ALL</option>
													<option value = "none">NO STATUS</option>
													<option value = "1">APPROVED</option>
													<option value = "2">DENIED</option>
											</select>										
								</div>
							</div>
							
							<div class="col-md-4">
								<?php
								$cquery = mysqli_query($con,"Select * from lup_payment_method where isdeleted = 0");
								?>
								 <div class="form-group">
										  <label for="age">PAYMENT METHOD:</label>
											
											<select  name = "pfmethod" id = "pfmethod" class="form-control" data-validation="required"
													data-validation-error-msg="Select PAYMENT METHOD">
													<option "Selected">ALL</option>
												
												<?php
												while($crow = mysqli_fetch_assoc($cquery))
												{
												?>												
													<option value = "<?php echo $crow['payment_method_id'];?>"><?php echo $crow['payment_method_name'];?></option>
													
												<?php
												}
												?>
											</select>
											
								</div>
							</div>
							
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">FILTER</button>
										<button class = "btn btn-warning btn-flat" id = "print">PRINT RESULT</button>
									</div>	
							</div>
						</div>
						
						
					
					
					
					<script>
							$("#print").click(
								function()
								{
									$.validate({
															form:'#pfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															//alert($("#sfapp").val());
																$.post( 
																	'../php/main.php',
																	{
																		ppfdfrom:$("#pfdfrom").val(),
																		ppfdto:$("#pfdto").val(),
																		ppfstatus:$("#sfremit").val(),
																		ppfapp:$('#pfapp').val(),
																		ppfmethod:$("#pfmethod").val()
																		
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
															form:'#pfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#pfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/main.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#pmonitoringui2").html(data);
																					
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
		</form>
		<div class="box box-warning">
			<div class="box-body" id = "pmonitoringui2">
				<?php pmonitoring("", "", "ALL","ALL","ALL");?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['preceive']))
{
	$id = $_REQUEST['preceive'];
	$preceivedfrom= $_REQUEST['preceivedfrom'];
	$preceivedto= $_REQUEST['preceivedto'];
	$preceivestatus= $_REQUEST['preceivestatus'];
	$preceiveapp= $_REQUEST['preceiveapp'];
	$preceivemethod= $_REQUEST['preceivemethod'];
										
	mysqli_query($con,"Update order_transaction set status_remittance = 2,datetime_remittance_updated = NOW() where order_transaction_id = $id");
	pmonitoring($preceivedfrom,$preceivedto,$preceivestatus,$preceiveapp,$preceivemethod);
	
}

if(isset($_REQUEST['apppreceive']))
{
	$id = $_REQUEST['apppreceive'];
	$apppreceivedfrom= $_REQUEST['apppreceivedfrom'];
	$apppreceivedto= $_REQUEST['apppreceivedto'];
	$apppreceiveapp= $_REQUEST['apppreceiveapp'];
	$apppreceivestatus= $_REQUEST['apppreceivestatus'];
	$apppreceivemethod= $_REQUEST['apppreceivemethod'];
	
	$query = mysqli_query($con,"Select * from order_transaction_detail where order_transaction_id = $id and isdeleted = 0");
	$saletran = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where issales = 1 and visible = 1"));
	
	$transact = 0;
	while($row = mysqli_fetch_assoc($query))
	{
		$transact = $row['order_quantity'];
		
		inventory_transaction($row['product_id'],$row['unit_id'],$saletran['transaction_type_id'],$transact,"Transaction from Sales",$row['product_price']);
	}
												
	mysqli_query($con,"Update order_transaction set status_collection = 1,status_shipping  = 1,datetime_collection_updated = NOW() where order_transaction_id = $id");

	
	pmonitoring($apppreceivedfrom,$apppreceivedto,$apppreceivestatus,$apppreceiveapp,$apppreceivemethod);
	
}


if(isset($_REQUEST['pdeny']))
{
	$id = $_REQUEST['pdeny'];
	$pdenydfrom= $_REQUEST['pdenydfrom'];
	$pdenydto= $_REQUEST['pdenydto'];
	$pdenyapp= $_REQUEST['pdenyapp'];
	$pdenystatus= $_REQUEST['pdenystatus'];
	$pdenybank= $_REQUEST['pdenybank'];
	$pdenyrem= $_REQUEST['pdenyrem'];
												
	mysqli_query($con,"Update order_transaction set status_collection = 2 where order_transaction_id = $id");
	
	pmonitoring($pdenydfrom,$pdenydto,$pdenystatus,$pdenyapp,$pdenybank,$pdenyrem);
	
	
}
if(isset($_REQUEST['orderdetails']))
{
	global $con;
	$id = $_REQUEST['orderdetails'];
	$aedit = $_REQUEST['edit'];
	
	$pay = 0;
	
	if(isset($_REQUEST['pay']))
		$pay = $_REQUEST['pay'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from order_transaction where order_transaction_id = $id"));
	?>
		<?php
		if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
		{
		?>
			<button class = "btn btn-success btn-flat btn-xs" id = "printd">PRINT</button>
			<script>
				$("#printd").click(
					function()
					{
														$.post( 
																		'../php/main.php',
																		{
																			printodetails:'<?php echo $id;?>'
																			
																		},
																		function(data) {
																			$('#click').html(data);		
																		});
					}
				);
			</script>
		<?php
		}
		?>
		<div class="box">
						<div class="box-body">
							<?php customer_info($row['customer_id']);?>
						</div>
		</div>
		<div class="box">
			<div class="box-body">
							<?php order_info($row['order_transaction_id']);?>
			</div>
		</div>
		<div class="box">
			<div class="box-body">
				<form method = "POST">
					<input type = "hidden" name = "ship_order_transaction_id"  id = "show_scanned_id" value = "<?php echo $row['order_transaction_id'];?>">
					<button class = "btn btn-primary btn-flat btn-xs" id = "show">SHOW SCANNED SHIPPING DETAILS</button>
					<?php
					if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
					{
						if($aedit == 1)
						{
					?>
						<button class = "btn btn-success btn-flat btn-xs" id = "printship">PRINT SHIPPING DETAILS</button>							
					<?php
						}
					}
					?>
					<?php
					if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
					{
						if($pay == 1)
						{
					?>
						<button class = "btn btn-warning btn-flat btn-xs" id = "pdetails">SHOW SCANNED PAYMENT DETAILS</button>	
						<script>
							$("#pdetails").click(
								function(e)
								{
									e.preventDefault();
									
									$("#modal2").modal("show");
									$("#modalbody2").css("max-width","70%");
									$('#modalui2').html(loading);					
																	$.post( 
																		'../php/main.php',
																		{
																			showspdetails:$('#show_scanned_id').val()
																			
																		},
																		function(data) {
																			$('#modalui2').html(data);		
																		});
								}
							);
						</script>
					<?php
						}
					}
					?>
					
				</form>
				<script>
					$("#show").click(
						function(e)
						{
							e.preventDefault();
							
							$("#modal2").modal("show");
							$("#modalbody2").css("max-width","70%");
							$('#modalui2').html(loading);					
															$.post( 
																'../php/main.php',
																{
																	showscanned:$('#show_scanned_id').val(),
																	allowupload:1
																},
																function(data) {
																	$('#modalui2').html(data);		
																});
						}
					);
					
					
					
	
					$("#printship").click(
						function(e)
						{
							e.preventDefault();
						
															$.post( 
																'../php/main.php',
																{
																	printship:$('#show_scanned_id').val()
																
																},
																function(data) {
																	$('#click').html(data);																	
																});
						}
					);
				</script>
				
			</div>
		</div>
		<div class="box">
			<div class="box-body">
							<?php shipping_info($row['order_transaction_id'],$aedit);?>
			</div>
		</div>
		
		
		<div class="box">
			<div class="box-body" id = "saleordercartui">
							<?php order_cart($row['order_transaction_id']);?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['saleorderdetails']))
{
	global $con;
	$id = $_REQUEST['saleorderdetails'];
	$aedit = $_REQUEST['edit'];
	$pay = 0;
	$count = $_REQUEST['salecount'];
	
	if(isset($_REQUEST['pay']))
		$pay = $_REQUEST['pay'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from order_transaction where order_transaction_id = $id"));
	?>
		<?php
		if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
		{
		?>
			<button class = "btn btn-success btn-flat btn-xs" id = "printd">PRINT</button>
			<script>
				$("#printd").click(
					function()
					{
														$.post( 
																		'../php/main.php',
																		{
																			printodetails:'<?php echo $id;?>'
																			
																		},
																		function(data) {
																			$('#click').html(data);		
																		});
					}
				);
			</script>
		<?php
		}
		?>
		<div class="box">
						<div class="box-body">
							<?php customer_info($row['customer_id']);?>
						</div>
		</div>
		<div class="box">
			<div class="box-body">
							<?php order_info($row['order_transaction_id']);?>
			</div>
		</div>
		<div class="box">
			<div class="box-body">
				<form method = "POST">
					<input type = "hidden" name = "ship_order_transaction_id"  id = "show_scanned_id" value = "<?php echo $row['order_transaction_id'];?>">
					<button class = "btn btn-primary btn-flat btn-xs" id = "show">SHOW SCANNED SHIPPING DETAILS</button>
					<?php
					if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
					{
						if($aedit == 1)
						{
					?>
						<button class = "btn btn-success btn-flat btn-xs" id = "printship">PRINT SHIPPING DETAILS</button>							
					<?php
						}
					}
					?>
					<?php
					if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
					{
						if($pay == 1)
						{
					?>
						<button class = "btn btn-warning btn-flat btn-xs" id = "pdetails">SHOW SCANNED PAYMENT DETAILS</button>	
						<script>
							$("#pdetails").click(
								function(e)
								{
									e.preventDefault();
									
									$("#modal2").modal("show");
									$("#modalbody2").css("max-width","70%");
									$('#modalui2').html(loading);					
																	$.post( 
																		'../php/main.php',
																		{
																			showspdetails:$('#show_scanned_id').val()
																			
																		},
																		function(data) {
																			$('#modalui2').html(data);		
																		});
								}
							);
						</script>
					<?php
						}
					}
					?>
					
				</form>
				<script>
					$("#show").click(
						function(e)
						{
							e.preventDefault();
							
							$("#modal2").modal("show");
							$("#modalbody2").css("max-width","70%");
							$('#modalui2').html(loading);					
															$.post( 
																'../php/main.php',
																{
																	showscanned:$('#show_scanned_id').val(),
																	allowupload:0
																},
																function(data) {
																	$('#modalui2').html(data);		
																});
						}
					);
					
					
					
	
					$("#printship").click(
						function(e)
						{
							e.preventDefault();
						
															$.post( 
																'../php/main.php',
																{
																	printship:$('#show_scanned_id').val()
																
																},
																function(data) {
																	$('#click').html(data);																	
																});
						}
					);
				</script>
				
			</div>
		</div>
		<div class="box">
			<div class="box-body">
							<?php shipping_info($row['order_transaction_id'],$aedit);?>
			</div>
		</div>
		<div class="box">
			<div class="box-body" id = "saleorderui">
							<?php sale_order_cart($row['order_transaction_id'],$count);?>
			</div>
		</div>
	<?php
}


if(isset($_REQUEST['adminreportui']))
{
	$user = get_user_id($_SESSION['useraccount']);
	
	$query = mysqli_query($con,"Select se_menu_report.* from se_menu_report, se_report_access where se_report_access.user_id = $user
	and se_report_access.menu_report_id = se_menu_report.menu_report_id and se_report_access.isdeleted = 0
	and se_menu_report.menu_report_module = 'FINANCE'");
	?>
		<div class="nav-tabs-custom">
           <ul class="nav nav-tabs">
              <?php
			  while($row = mysqli_fetch_assoc($query))
			  {
				  ?>
					<li><a href="<?php echo $row['menu_report_tab'];?>" data-toggle="tab"><?php echo $row['menu_report_description'];?></a></li>
				  <?php
			  }
			  ?>
            </ul>
            <div class="tab-content">
			<div class="tab-pane" id="tab_6">
					<form id = "sstfilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">SALES REPORT(DETAILED)</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "sstdfrom" id = "sstdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									
									<input type = "date" class = "form-control" name = "sstdto"  id = "sstdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>CUSTOMER TYPE GROUP:</label>
									<?PHP
										$tgquery = mysqli_query($con,"Select * from lup_customer_type_group
										where isdeleted = 0");
									?>
									<select name = "ssextypegroup" id = "ssextypegroup" class="form-control" data-validation="required"
													data-validation-error-msg="Select CUSTOMER TYPE GROUP">
													<option  hidden "selected"></option>
													<option>BOTH</option>
												<?php
												while($tgrow = mysqli_fetch_assoc($tgquery))
												{
												?>												
													<option  value = "<?php echo $tgrow['customer_type_group_id'];?>"><?php echo $tgrow['customer_type_group_name'];?></option>
												<?php
												}
												?>												
									</select>
								</div>		
							</div>
							

							
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filtersst">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printsst">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printsst").click(
								function()
								{
									$.validate({
															form:'#sstfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																//alert($("#ssextypegroup").val());
																
																$.post( 
																	'../php/finance.php',
																	{
																		psstdfrom:$("#sstdfrom").val(),
																		psstdto:$("#sstdto").val(),
																		pssextypegroup:$("#ssextypegroup").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filtersst").click(
								function()
								{
										$.validate({
															form:'#sstfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#sstfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#sstreportui").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#sstreportui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "sstreportui"></div>
            </div>
			<div class="tab-pane" id="tab_5">
					<form id = "cccofilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">UNCLAIMED CARE OF/REFERRAL</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "cccodfrom" id = "cccodfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									
									<input type = "date" class = "form-control" name = "cccodto"  id = "cccodto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filterccco">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printccco">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printccco").click(
								function()
								{
									$.validate({
															form:'#cccofilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/finance.php',
																	{
																		pcccodfrom:$("#cccodfrom").val(),
																		pcccodto:$("#cccodto").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filterccco").click(
								function()
								{
										$.validate({
															form:'#cccofilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#cccofilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#cccoreportui").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#cccoreportui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "cccoreportui"></div>
              </div>
			  
			<div class="tab-pane" id="tab_4">
					<form id = "ccofilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">CLAIMED CARE OF/REFERRAL</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "ccodfrom" id = "ccodfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									
									<input type = "date" class = "form-control" name = "ccodto"  id = "ccodto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filtercco">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printcco">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printcco").click(
								function()
								{
									$.validate({
															form:'#ccofilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/finance.php',
																	{
																		pccodfrom:$("#crdfrom").val(),
																		pccodto:$("#crdto").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filtercco").click(
								function()
								{
										$.validate({
															form:'#ccofilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#ccofilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#ccoreportui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "ccoreportui"></div>
              </div>
			  
              <div class="tab-pane" id="tab_1">
					<form id = "crfilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">CLAIMED DEPOSITS</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "crdfrom" id = "crdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									
									<input type = "date" class = "form-control" name = "crdto"  id = "crdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filtercr">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printcr">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printcr").click(
								function()
								{
									$.validate({
															form:'#crfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/finance.php',
																	{
																		pcrdfrom:$("#crdfrom").val(),
																		pcrdto:$("#crdto").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filtercr").click(
								function()
								{
										$.validate({
															form:'#crfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#crfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#collectionreportui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "collectionreportui"></div>
              </div>		
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
               <form id = "ucrfilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">UNCLAIMED DEPOSITS</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "ucrdfrom" id = "ucrdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									
									<input type = "date" class = "form-control" name = "ucrdto"  id = "ucrdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filterucr">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printucr">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printucr").click(
								function()
								{
									$.validate({
															form:'#ucrfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/finance.php',
																	{
																		pucrdfrom:$("#crdfrom").val(),
																		pucrdto:$("#crdto").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filterucr").click(
								function()
								{
										$.validate({
															form:'#ucrfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#ucrfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#unclaimedreportui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "unclaimedreportui"></div>
              </div>
			  
			   <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
               <form id = "sefilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">SHIPPING EXPENSE</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "sedfrom" id = "sedfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									
									<input type = "date" class = "form-control" name = "sedto"  id = "sedto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filterse">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printse">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printse").click(
								function()
								{
									$.validate({
															form:'#sefilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/finance.php',
																	{
																		psedfrom:$("#sedfrom").val(),
																		psedto:$("#sedto").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filterse").click(
								function()
								{
										$.validate({
															form:'#sefilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#sefilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#seui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "seui"></div>
              </div>
			  
              
            </div>
           
          </div>
        
	<?php
}
if(isset($_POST['sedfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php shippingexpense($sedfrom,$sedto,0);?>
			</div>
		</div>
	<?php
}

if(isset($_POST['crdfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php collectionreport_bank($crdfrom,$crdto,0);?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['psedfrom']))
{
		$psedfrom = $_REQUEST['psedfrom'];
		$psedto = $_REQUEST['psedto'];
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">SHIPPING EXPENSE</h4>
			<h4 style = "text-align:center"><?php echo $psedfrom." to ".$psedto;?></h4>
			
			<?php
				shippingexpense($psedfrom,$psedto,1);
				
				$user = get_user_id($_SESSION['useraccount']);
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
		</script>
		
	<?php
}

if(isset($_REQUEST['pcrdfrom']))
{
		$pcrdfrom = $_REQUEST['pcrdfrom'];
		$pcrdto = $_REQUEST['pcrdto'];
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CLAIMED DEPOSITS</h4>
			<h4 style = "text-align:center"><?php echo $pcrdfrom." to ".$pcrdto;?></h4>
			
			<?php
				collectionreport_bank($pcrdfrom,$pcrdto,1);
				
				$user = get_user_id($_SESSION['useraccount']);
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
		</script>
		
	<?php
}
if(isset($_POST['ucrdfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php unclaimedeposit($ucrdfrom,$ucrdto,0);?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['pucrdfrom']))
{
		$pucrdfrom = $_REQUEST['pucrdfrom'];
		$pucrdto = $_REQUEST['pucrdto'];
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">UNCLAIMED DEPOSITS</h4>
			<h4 style = "text-align:center"><?php echo $pucrdfrom." to ".$pucrdto;?></h4>
			
			<?php
				unclaimedeposit($pucrdfrom,$pucrdto,1);
				
				$user = get_user_id($_SESSION['useraccount']);
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
		</script>
		
	<?php
}
if(isset($_POST['ccodfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php claimed_care_of($ccodfrom,$ccodto,0);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['pccodfrom']))
{
		$pccodfrom = $_REQUEST['pccodfrom'];
		$pccodto = $_REQUEST['pccodto'];
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CLAIMED CARE OF/REFERRAL</h4>
			<h4 style = "text-align:center"><?php echo $pccodfrom." to ".$pccodto;?></h4>
			
			<?php
				 claimed_care_of($pccodfrom,$pccodto,1);
				
				$user = get_user_id($_SESSION['useraccount']);
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
		</script>
		
	<?php
}
if(isset($_POST['cccodfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php unclaimed_care_of($cccodfrom,$cccodto,0);?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['pcccodfrom']))
{
		$pcccodfrom = $_REQUEST['pcccodfrom'];
		$pcccodto = $_REQUEST['pcccodto'];
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">UNCLAIMED CARE OF/REFERRAL</h4>
			<h4 style = "text-align:center"><?php echo $pcccodfrom." to ".$pcccodto;?></h4>
			
			<?php
				 unclaimed_care_of($pcccodfrom,$pcccodto,1);
				
				$user = get_user_id($_SESSION['useraccount']);
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
		</script>
		
	<?php
}
if(isset($_POST['sstdfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php salesummaryteam($sstdfrom,$sstdto,0,$ssextypegroup);
				echo "<br>";
				daily_collection($sstdfrom,$sstdto,0,$ssextypegroup);
				echo "<br>";
				previous_claimed($sstdfrom,$sstdto,0,$ssextypegroup);
				echo "<br>";
				prev_collection($sstdfrom,$sstdto,0,$ssextypegroup);
				echo "<br>";
				bank_deposits($sstdfrom,$sstdto,0,$ssextypegroup);
				echo "<br>";
				expense_summary($sstdfrom,$sstdto,$ssextypegroup);
				echo "<br>";
				otherdeposit_summary($sstdfrom,$sstdto,$ssextypegroup);
				echo "<br>";
				productinoutsummary($sstdfrom,$sstdto,1);
				
				
				//$dep = total_daily_collection($sstdfrom,$sstdto);
				$dep = total_sales($sstdfrom,$sstdto,$ssextypegroup);
				//$ship = total_shippingexpense($sstdfrom,$sstdto);
				$prevu = total_previous_claimed($sstdfrom,$sstdto,$ssextypegroup);
				$prev = prev_claim($sstdfrom,$sstdto,$ssextypegroup);
				$exp = total_expense($sstdfrom,$sstdto,$ssextypegroup);
				$deposit = total_deposit($sstdfrom,$sstdto,$ssextypegroup);
				$otherdeposit = total_otherdeposit($sstdfrom,$sstdto,$ssextypegroup);
				$total = ($dep+$prev)-($prevu+$exp+$deposit+$otherdeposit);
				
				echo "<br>";
				?>
				<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<tr>
									<th style = "text-align:right;">TOTAL SALES:</th>
									<td style = "text-align:right;"><?php echo number_format($dep,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">PREVIOUS CLAIM:</th>
									<td style = "text-align:right;"><?php echo number_format($prev,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">LESS:</th>
									<td style = "text-align:right;"></td>
								</TR>
								<tr>
									<th style = "text-align:right;">UNCLAIM:</th>
									<td style = "text-align:right;"><?php echo number_format($prevu,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL EXPENSES:</th>
									<td style = "text-align:right;"><?php echo number_format($exp,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL OTHER DEPOSITS:</th>
									<td style = "text-align:right;"><?php echo number_format($otherdeposit,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL DEPOSITS:</th>
									<td style = "text-align:right;"><?php echo number_format($deposit,2);?></td>
								</TR>
								
								<tr>
									<th style = "text-align:right;">CASH ON HAND: </th>
									<th style = "text-align:right;"><?php echo number_format($total,2);?></th>
								</TR>
				</table>
				
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['psstdfrom']))
{
		$psstdfrom = $_REQUEST['psstdfrom'];
		$psstdto = $_REQUEST['psstdto'];
		$typegroup = $_REQUEST['pssextypegroup'];
	?>
		<div id = "printt">
			
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">SALES REPORT(DETAILED)</h4>
			<h4 style = "text-align:center"><?php echo $psstdfrom." to ".$psstdto;?></h4>
			
			<?php
					salesummaryteam($psstdfrom,$psstdto,1,$typegroup);
						echo "<br>";
					daily_collection($psstdfrom,$psstdto,1,$typegroup);
					echo "<br>";
					previous_claimed($psstdfrom,$psstdto,1,$typegroup);
					echo "<br>";
				prev_collection($psstdfrom,$psstdto,0,$typegroup);
				echo "<br>";
				bank_deposits($psstdfrom,$psstdto,0,$typegroup);
				echo "<br>";
				expense_summary($psstdfrom,$psstdto,$typegroup);
				echo "<br>";
				otherdeposit_summary($psstdfrom,$psstdto,$typegroup);
				echo "<br>";
				productinoutsummary($psstdfrom,$psstdto,1,$typegroup);
				
				//$dep = total_daily_collection($sstdfrom,$sstdto);
				$dep = total_sales($psstdfrom,$psstdto,$typegroup);
				//$ship = total_shippingexpense($sstdfrom,$sstdto);
				$prevu = total_previous_claimed($psstdfrom,$psstdto,$typegroup);
				$prev = prev_claim($psstdfrom,$psstdto,$typegroup);
				$exp = total_expense($psstdfrom,$psstdto,$typegroup);
				$deposit = total_deposit($psstdfrom,$psstdto,$typegroup);
				$otherdeposit = total_otherdeposit($psstdfrom,$psstdto,$typegroup);
				$total = ($dep+$prev)-($prevu+$exp+$deposit+$otherdeposit);
				
				echo "<br>";
				?>
				<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<tr>
									<th style = "text-align:right;">TOTAL SALES:</th>
									<td style = "text-align:right;"><?php echo number_format($dep,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">PREVIOUS CLAIM:</th>
									<td style = "text-align:right;"><?php echo number_format($prev,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">LESS:</th>
									<td style = "text-align:right;"></td>
								</TR>
								<tr>
									<th style = "text-align:right;">UNCLAIM:</th>
									<td style = "text-align:right;"><?php echo number_format($prevu,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL EXPENSES:</th>
									<td style = "text-align:right;"><?php echo number_format($exp,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL OTHER DEPOSITS:</th>
									<td style = "text-align:right;"><?php echo number_format($otherdeposit,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL DEPOSITS:</th>
									<td style = "text-align:right;"><?php echo number_format($deposit,2);?></td>
								</TR>
								
								<tr>
									<th style = "text-align:right;">CASH ON HAND: </th>
									<th style = "text-align:right;"><?php echo number_format($total,2);?></th>
								</TR>
				</table>
			<?php	
				$user = get_user_id($_SESSION['useraccount']);
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
		</script>
		
	<?php
}
if(isset($_REQUEST['expenseui']))
{
	
	?>
		
		<div class="box box-warning">
			
			<div class="box-body">
					<form method = "POST" id = "newexform">
						<div class = "row">
							<div class="col-md-3">
								<div class = "form-group">
									<label>CUSTOMER TYPE GROUP:</label>
									<?PHP
										$tgquery = mysqli_query($con,"Select * from lup_customer_type_group
										where isdeleted = 0");
									?>
									<select name = "extypegroup" id = "extypegroup" class="form-control" data-validation="required"
													data-validation-error-msg="Select EXPENSE/OTHER DEPOSIT">
													<option  hidden "selected"></option>
												<?php
												while($tgrow = mysqli_fetch_assoc($tgquery))
												{
												?>												
													<option  value = "<?php echo $tgrow['customer_type_group_id'];?>"><?php echo $tgrow['customer_type_group_name'];?></option>
												<?php
												}
												?>												
									</select>
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>EXPENSE/OTHER DEPOSIT</label>
									<select name = "extype" id = "extype" class="form-control" data-validation="required"
													data-validation-error-msg="Select EXPENSE/OTHER DEPOSIT">
													<option  hidden "selected"></option>
													<option  value = "0">EXPENSE</option>
													<option  value = "1">OTHER DEPOSIT</option>
									</select>
								</div>		
							</div>
								<script>
								$("#extype").change(
									function()
									{
										var exptype = $("#extype").val();
										
										if(exptype != '')
										{
											$.post( 
												'../php/finance.php',
												{					
													extypeui:exptype
												},
												function(data) {
													$('#exdesui').html(data);
												});
										}
									}
								);
							</script>
							<div id = "exdesui">
																
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>AMOUNT:</label>
									 <input type="text" class="form-control" name = "examount" id = "examount" data-validation="number"
													data-validation-error-msg="Enter Amount"
													data-validation-allowing="float">
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>EXPENSE DATE:</label>
									<input type = "date" class = "form-control" name = "exdate" id = "exdate" data-validation="date" 
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Expense Date">
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
															form:'#newexform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#newexform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#expenseui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#expenseui2").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
					</script>					
						
					
				
			</div>
		</div>
		<div class="box box-warning">
			
			<div class="box-body">
				<form id = "exfilterform">
					
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "exdfrom" id = "exdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									
									<input type = "date" class = "form-control" name = "exdto"  id = "exdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>EXPENSE/OTHER DEPOSIT</label>
									<select name = "extypee" id = "extypee" class="form-control" data-validation="required"
													data-validation-error-msg="Select EXPENSE/OTHER DEPOSIT">
													<option  hidden "selected"></option>
													<option  value = "0">EXPENSE</option>
													<option  value = "1">OTHER DEPOSIT</option>
									</select>
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>CUSTOMER TYPE GROUP:</label>
									<?PHP
										$tgquery = mysqli_query($con,"Select * from lup_customer_type_group
										where isdeleted = 0");
									?>
									<select name = "pextypegroup" id = "pextypegroup" class="form-control" data-validation="required"
													data-validation-error-msg="Select EXPENSE/OTHER DEPOSIT">
													<option  hidden "selected"></option>
													<option>BOTH</option>
												<?php
												while($tgrow = mysqli_fetch_assoc($tgquery))
												{
												?>												
													<option  value = "<?php echo $tgrow['customer_type_group_id'];?>"><?php echo $tgrow['customer_type_group_name'];?></option>
												<?php
												}
												?>												
									</select>
								</div>		
							</div>
							
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filterex">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printex">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
				
				</form>
				<script>
							$("#printex").click(
								function()
								{
									$.validate({
															form:'#exfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/finance.php',
																	{
																		pexdfrom:$("#exdfrom").val(),
																		pexdto:$("#exdto").val(),
																		pextypee:$("#extypee").val(),
																		pextypegroup:$("#pextypegroup").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filterex").click(
								function()
								{
										$.validate({
															form:'#exfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#exfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#expenseui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#expenseui2").html(data);
																					
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
		<script>
			
		</script>
		<div id = "exp_alert"></div>
		<div class="box box-warning">
			<div class="box-body" id = "expenseui2">
				<?PHP expense('','','',0,'');?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['changeexdes']))
{
	$id = $_REQUEST['changeexdes'];
	
	$des = "EXPENSE DESCRIPTION";
	
	if($id == 1)
		$des = "OTHER DEPOSIT DESCRIPTION";
	?>
	<div class="col-md-3">
		<div class = "form-group">
			<label><?php echo $des;?>:</label>
			<input type="text" class="form-control" name = "exdes" id = "exdes" data-validation="required"
														data-validation-error-msg="Enter <?php echo $des;?>">
										<button class = "btn btn-success btn-flat btn-xs" id = "cancel">CANCEL</button>
		</div>
	</div>
		<script>
				$("#cancel").click(
									function(e)
									{
										e.preventDefault();
											$.post( 
												'../php/finance.php',
												{					
													extypeui:<?php echo $id;?>
												},
												function(data) {
													$('#exdesui').html(data);
												});
										
									}
								);
		</script>
	<?php
}
if(isset($_REQUEST['cancelexdes']))
{
	?>
								<div class = "form-group">
									<label>EXPENSE DESCRIPTION:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select DISTINCT(expense_description) from order_expense where isdeleted = 0");
									?>
									<select name = "exdes" id = "exdes" class="form-control" data-validation="required"
													data-validation-error-msg="Select EXPENSE DESCRIPTION:">
													<option  hidden "selected"></option>
													<option  value = "add_new">NEW..</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option ><?php echo $prow['expense_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>
							<script>
								$("#exdes").change(
									function()
									{
										var expdes = $("#exdes").val();
										
										if(expdes == 'add_new')
										{
											$.post( 
												'../php/finance.php',
												{					
													changeexdes:1
												},
												function(data) {
													$('#exdesui').html(data);		
												});
										}
									}
								);
							</script>								
	<?php
}
if(isset($_POST['exdes']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$check = mysqli_num_rows(mysqli_query($con,"Select * from order_expense where expense_description = '$exdes'
	and STR_TO_DATE(expense_date,'%Y-%m-%d') = STR_TO_DATE('$exdate','%Y-%m-%d')"));
	
	$user = get_user_id($_SESSION['useraccount']);
	$check = 0;
	if($check == 0)
	{
		$save = mysqli_query($con,"insert into order_expense set
		expense_description = '$exdes',
		is_other_deposit = '$extype',
		customer_type_group_id = $extypegroup,
		expense_amount = $examount,
		expense_date = '$exdate',
		added_by = $user");
		
		if($save)
		{
			?>
			<script>
				notify("Entry Successfully Saved","#exp_alert");
			</script>
			<?php
		}
		else
		{
			?>
			<script>
				notify("Error Adding new Entry, please contact the system administrator","#exp_alert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("Expense is already added on expense date","#exp_alert");
			</script>
		<?php
	}
	expense('','','',0,'');
}
if(isset($_REQUEST['examountsave']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$user = get_user_id($_SESSION['useraccount']);
	
	mysqli_query($con,"update order_expense set
	expense_amount = $examountsave,
	modified_by = $user
	where expense_id = $examountid");
	
	echo number_format(total_expense($examountdfrom,$examountdto),2);
}

if(isset($_REQUEST['deleteexpense']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$user = get_user_id($_SESSION['useraccount']);
	
	mysqli_query($con,"update order_expense set
	isdeleted = 1,
	deleted_by = $user
	where expense_id = $deleteexpense");
	
	expense($deleteexpensedfrom,$deleteexpensedto,0);
}

if(isset($_POST['exdfrom']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	expense($exdfrom,$exdto,$extypee,0,$pextypegroup);
}
if(isset($_REQUEST['pexdfrom']))
{
		$psedfrom = $_REQUEST['pexdfrom'];
		$psedto = $_REQUEST['pexdto'];
		
		$type = $_REQUEST['pextypee'];
		
		$header = "";
		if($type == 1)
			$header = "OTHER DEPOSITS";
		else
			$header = "EXPENSES";
		
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center"><?php echo $header;?></h4>
			<h4 style = "text-align:center"><?php echo $psedfrom." to ".$psedto;?></h4>
			
			<?php
				expense($psedfrom,$psedto,$_REQUEST['pextypee'],1,$_REQUEST['pextypegroup']);
				
				$user = get_user_id($_SESSION['useraccount']);
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
		</script>
		
	<?php
}

if(isset($_REQUEST['cdepositui']))
{
	
	?>
		
		<div class="box box-warning">
			
			<div class="box-body">
					<form method = "POST" id = "cdform">
						<div class = "row">	
							<div class="col-md-3">
												<label>Browse Care Of Transaction:</label>

													<div class="input-group">
													  <div class="input-group-addon bg-primary">
														<i class="fa fa-search"></i>
													  </div>
													 
													  <input type = "hidden" id = "cdtranhidden" name = "cdtranhidden">
													  <input type="text" class="form-control" name = "cdtran" id = "cdtran" readonly data-validation="required"
													data-validation-error-msg="Browse Care Of Transaction" placeholder = "Click here to browse">
													</div>
							</div>
									<script>
											$("#cdtran").click(
														function(e)
														{
															e.preventDefault();
															
															var s = $('#cdtran').attr('id');
															var hidden = $('#cdtranhidden').attr('id');
														
															
															$("#modal").modal("show");
															$("#modalbody").css("min-width","60%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/main.php',
																{
																	cdtranui:1,
																	cdtranfield:s,
																	cdtranhidden:hidden
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
													);
									</script>
											
							<div class="col-md-3" id = "cdbankui">
								<div class = "form-group">
									<label>BANK/REMITTANCE CENTER</label>
									
									<select name = "cdbank" id = "cdbank" class="form-control" data-validation="required"
													data-validation-error-msg="Select BANK/REMITTANCE CENTER">
													<option  hidden "selected"></option>
													<option  value = "1">BANK</option>
													<option  value = "2">REMITTANCE CENTER</option>
									</select>
								</div>		
							</div>
							<script>
								$("#cdbank").change(
									function()
									{
										var expdes = $("#cdbank").val();
										
										if(expdes == '1')
										{
											$.post( 
												'../php/finance.php',
												{					
													cdbankui2:1
												},
												function(data) {
													$('#cdbankui').html(data);		
												});
										}
										else if(expdes == '2')
										{
											$.post( 
												'../php/finance.php',
												{					
													cdbankui3:1
												},
												function(data) {
													$('#cdbankui').html(data);		
												});
										}
									}
								);
							</script>
							
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" >SAVE</button>
									
									</div>	
							</div>
						</div>
					</form>
					<script>
												$.validate({
															form:'#cdform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#cdform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#expenseui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
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
		</div>
		<div class="box box-warning">
			
			<div class="box-body">
				<form id = "">
					
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "exdfrom" id = "exdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									
									<input type = "date" class = "form-control" name = "exdto"  id = "exdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filterex">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printex">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
				
				</form>
				<script>
							$("#printex").click(
								function()
								{
									$.validate({
															form:'#cdform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/finance.php',
																	{
																		pexdfrom:$("#exdfrom").val(),
																		pexdto:$("#exdto").val()
																	},
																	function(data) {
																		$('#cdlistui').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filterex").click(
								function()
								{
										$.validate({
															form:'#cdform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#cdform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#cdlistui").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#cdlistui").html(data);
																					
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
		<script>
			
		</script>
		<div id = "exp_alert"></div>
		<div class="box box-warning">
			<div class="box-body" id = "cdlistui">
				<?PHP careof_deposit('','',0);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['cdbankui2']))
{
	?>
		<div class = "form-group">
									<label>BANKS</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_bank where isdeleted = 0");
									?>
									<select name = "cdbank" id = "cdbank" class="form-control" data-validation="required"
													data-validation-error-msg="Select Bank">
													<option  hidden "selected"></option>
													
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['bank_id'];?>"><?php echo $prow['bank_name'];?></option>
												<?php
													}
												?>
									</select>
									<button class = "btn btn-danger btn-xs" id = "cancel">CANCEL</button>
									<script>
										$("#cancel").click(
											function(e)
											{
												e.preventDefault();
												
												$.post( 
												'../php/finance.php',
												{					
													resetcdbank:1
												},
												function(data) {
													$('#cdbankui').html(data);		
												});
											}
											
										);
									</script>
								</div>
	<?php
}

if(isset($_REQUEST['cdbankui3']))
{
	?>
								<div class = "form-group">
									<label>REMITTANCE CENTER</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_remittance_center where isdeleted = 0");
									?>
									<select name = "cdrem" id = "cdrem" class="form-control" data-validation="required"
													data-validation-error-msg="Select REMITTANCE CENTER">
													<option  hidden "selected"></option>
													
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['remittance_center_id'];?>"><?php echo $prow['remittance_center_name'];?></option>
												<?php
													}
												?>
									</select>
									<button class = "btn btn-danger btn-xs" id = "cancel">CANCEL</button>
									<script>
										$("#cancel").click(
											function(e)
											{
												e.preventDefault();
												
												$.post( 
												'../php/finance.php',
												{					
													resetcdbank:1
												},
												function(data) {
													$('#cdbankui').html(data);		
												});
											}
											
										);
									</script>
								</div>
	<?php
}
if(isset($_REQUEST['resetcdbank']))
{
		?>
		
								<div class = "form-group">
									<label>BANK/REMITTANCE CENTER</label>
									
									<select name = "cdbank" id = "cdbank" class="form-control" data-validation="required"
													data-validation-error-msg="Select BANK/REMITTANCE CENTER">
													<option  hidden "selected"></option>
													<option  value = "1">BANK</option>
													<option  value = "2">REMITTANCE CENTER</option>
									</select>
								</div>		
				
							<script>
								$("#cdbank").change(
									function()
									{
										var expdes = $("#cdbank").val();
										
										if(expdes == '1')
										{
											$.post( 
												'../php/finance.php',
												{					
													cdbankui2:1
												},
												function(data) {
													$('#cdbankui').html(data);		
												});
										}
										else if(expdes == '2')
										{
											$.post( 
												'../php/finance.php',
												{					
													cdbankui3:1
												},
												function(data) {
													$('#cdbankui').html(data);		
												});
										}
									}
								);
							</script>
		<?php
}
if(isset($_POST['cdtran']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
		//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['useraccount']);
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select total_amount from order_transaction where order_transaction_id = $cdtranhidden"));
	
	$bank = 0;
	
	if(isset($_POST['cdbank']))
	{
		$bank = $cdbank;
	}
	
	$rem = 0;
	
	if(isset($_POST['cdrem']))
	{
		$rem = $cdrem;
	}
	
	mysqli_query($con,"insert into deposit_careof set
	transaction_id = $cdtranhidden,
	amount = $row[total_amount],
	bank_id = $bank,
	remittance_center_id = $rem,
	created_modified = $user");
	
	?>
		<script>
				$('#cdepositui').html(loading);	
				
					$.post( 
								'../php/finance.php',
								{
									cdepositui:1
								},
								function(data) {
									$('#cdepositui').html(data);	
									//alert('OK');
								});
		</script>
	<?php
}
if(isset($_REQUEST['extypeui']))
{
	$id = $_REQUEST['extypeui'];
	
	$des = "EXPENSE DESCRIPTION";
	if($id == 1)
		$des = "OTHER DEPOSIT DESCRIPTION";
	?>
	<div class="col-md-3">
							<div class = "form-group">
									<label><?PHP echo $des;?>:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select DISTINCT(expense_description) from order_expense where isdeleted = 0
									and is_other_deposit = $id");
									?>
									<select name = "exdes" id = "exdes" class="form-control" data-validation="required"
													data-validation-error-msg="Select <?php echo $des;?>:">
													<option  hidden "selected"></option>
													<option  value = "add_new">NEW..</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option ><?php echo $prow['expense_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>
								<script>
									$("#exdes").change(
										function()
										{
											var expdes = $("#exdes").val();
											
											if(expdes == 'add_new')
											{
												$.post( 
													'../php/finance.php',
													{					
														changeexdes:<?php echo $id;?>
													},
													function(data) {
														$('#exdesui').html(data);		
													});
											}
										}
									);
								</script>														
	</div>
		
	<?php
}

if(isset($_REQUEST['deletecareof']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$user = get_user_id($_SESSION['useraccount']);
	
	mysqli_query($con,"update deposit_careof set
	isdeleted = 1
	where deposit_careof_id = $deletecareof");
	
	careof_deposit($deletecareofdfrom,$deletecareofdto,0);
}
if(isset($_REQUEST['pdelui']))
{
	$id = $_REQUEST['pdelui'];
	$allow = $_REQUEST['edit'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from order_transaction where order_transaction_id = $id"));
	
	?>
								<div class="box box-default">
											<div class="box-body" id = "shipimageui">
												<?php
												if($allow == 1)
												{
													if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
													{
												?>
													<form class = "form-inline" id = "uploadform"  method="post" enctype="multipart/form-data">
														<div class = "form-group" style = "width:580px;">
															<input type = "hidden" class = "form-control" id = "pdid" name = "pdid" value = "<?php echo $id;?>">
															<input type = "hidden" class = "form-control" id = "pdshipallow" name = "pdshipallow" value = "<?php echo $allow;?>">
															<input type = "file" class = "form-control" id = "pdcimg" name = "pdcimg" style = "width:160px;">
															<button class = "btn btn-success btn-flat btn-xs" id = "upload">Change</button>
															
														</div>
													</form>
												<?php
													}
												}
												?>
												<img src = "../images/proof_of_delivery/<?php echo $row['proof_delivery'];?>" width = "100%">
												
												<script>

													$("#uploadform").on('submit',(function(e) {
																	e.preventDefault();
																			
																			$.ajax({
																				url: "../php/finance.php",
																				type: "POST",
																				data:  new FormData(this),
																				contentType: false,
																				cache: false,
																				processData:false,
																				success: function(data)
																				{
																					$("#shipimageui").html(data);
																					
																				},
																				error: function() 
																				{
																					alert('Sending failed');
																				} 	        
																		   });
																	
																		   
																		   
																	}));
												</script>
											</div>	
										</div>
	<?php
}

if(isset($_FILES['pdcimg']))
{
	
	if(is_array($_FILES)) 
	{
		$name = $_FILES['pdcimg']['name'];
		$type = $_FILES['pdcimg']['type'];
		$size = $_FILES['pdcimg']['size'];
		
		$id = $_POST['pdid'];
		$allow = $_POST['pdshipallow'];
		
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from order_transaction where order_transaction_id = $id"));
		
		$stname = $row['order_transaction_no'];
		
		$sss = "PROOF_".$stname."-".$_FILES['pdcimg']['name'];
		
		if(!file_exists('../images/proof_of_delivery/'))
		{
				//$dir1 = $_SERVER['DOCUMENT_ROOT'] . '/alphabet/images/'.$sy['school_year'].'-'.($sy['school_year']+1)."_ID".'/';
				$dir1 = '../images/proof_of_delivery/';
				mkdir($dir1, 0777,true);
		}
		
		if($type == "image/jpeg" || $type == "image/png")
		{
			if($size <= 1200000)
			{
				$sourcePath = $_FILES['pdcimg']['tmp_name'];
				$targetPath = "../images/proof_of_delivery/".basename($sss);
				
				
					if(is_uploaded_file($_FILES['pdcimg']['tmp_name'])) 
					{
						echo "
							<script>
								$('#upload_status').html('Uploading Image, Please Wait');
							</script>
						";
								if($row['image'] != '')
								{
									unlink("../images/proof_of_delivery/".$row['proof_delivery']);
								}
								
							if(move_uploaded_file($sourcePath,$targetPath)) {
								mysqli_query($con, "update order_transaction set 
								proof_delivery = '$sss'
								where 
							order_transaction_id  = $id
								");
								
								?>
									<script>
										
										$.post( 
																'../php/finance.php',
																{
																	pdelui:<?php echo $id;?>,
																	edit:1
																},
																function(data) {
																	$('#modalui').html(data);		
																});
											 
									</script>
								<?php
								
								echo "
										<script>
											alert('New Image Uploaded');
										</script>
									";
								
							}
						
					}
				
			}
			else
			{
				echo "
					<script>
						alert('The Image File is too Large, The Allowed File Size is 600kb');
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
	
}
if(isset($_REQUEST['paymentdetailsui']))
{
	$id = $_REQUEST['paymentdetailsui'];
	?>
		<div class="box box-default">
				<div class="box-body" id = "addpaymentui">
					<form class = "form" method = "POST" id = "dtypeform">
						<div class = "row">	
							<div class="col-md-7">
								
								 <div class="form-group">
											<label for="age">Select REMITTANCE CENTER/BANK:</label>
											<input type = "hidden" name = "dtypetransactionid" value = "<?php echo $id;?>">
											<select id="sfremit" name = "dtype" class="form-control" data-validation="required"
													data-validation-error-msg="Select REMITTANCE STATUS">
													<option hidden "selected"></option>
													<option value = "1">BANK</option>
													<option value = "2">REMITTANCE CENTER</option>
											</select>										
								</div>
							</div>
							<div class="col-md-4">
								<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">ADD NEW TRANSACTION</button>
									</div>	
								</div>
							</div>
							
						</div>
					</form>
						<script>
												$.validate({
															form:'#dtypeform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#dtypeform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#addpaymentui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
						</script>
				</div>
		</div>
		<div class="box box-default">
				<div class="box-body" id = "paymentdetailsui">
					<?php transaction_payment($id);?>
				</div>
		</div>
		
	<?php
}
if(isset($_POST['dtype']))
{
	$type = $_POST['dtype'];
	$tran = $_POST['dtypetransactionid'];
	if($type == 1)
	{
		?>
			<form class = "form" method = "POST" id = "paymentbankform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>AMOUNT:</label>
									<input type = "hidden" name = "pbtrasanctionid" value = "<?php echo $tran;?>">
									 <input type="text" class="form-control" name = "pbamount" id = "pbamount" data-validation="number"
													data-validation-error-msg="Enter Amount"
													data-validation-allowing="float">
								</div>		
							</div>
							<div class="col-md-3">
								
								 <div class="form-group">
											<label for="age">BANK:</label>
											<select name = "pbbank" class="form-control" data-validation="required"
													data-validation-error-msg="Select Bank">
												<option hidden "selected"></option>
											<?php
												$bquery = mysqli_query($con,"Select * from lup_bank where isdeleted = 0");
												while($brow = mysqli_fetch_assoc($bquery))
												{
													?>
														<option value = "<?php echo $brow['bank_id'];?>"><?php echo $brow['bank_name'];?></option>
													<?php
												}
											?>
											</select>										
								</div>
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Payment Date:</label>
									<input type = "date" class = "form-control" name = "pbdate" id = "pbdate" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Payment Date">
								</div>		
							</div>
							
							<div class="col-md-7">
								<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">ADD</button>
										<button class = "btn btn-danger btn-flat" id = "bcancel">CANCEL</button>
									</div>	
								</div>
							</div>
							
						</div>
			</form>
						<script>
								$("#bcancel").click(
									function(e)
									{
										e.preventDefault();
										$.post( 
																	'../php/finance.php',
																	{
																		resetdeposittype:'<?php echo $tran;?>'
																		
																	},
																	function(data) {
																		$('#addpaymentui').html(data);	
																		
																	});
									}
								);
												$.validate({
															form:'#paymentbankform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#paymentbankform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#paymentdetailsui").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#paymentdetailsui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
						</script>
		<?php
	}
	else
	{
		?>
			<form class = "form" method = "POST" id = "paymentbankform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>AMOUNT:</label>
									<input type = "hidden" name = "pbtrasanctionid" value = "<?php echo $tran;?>">
									 <input type="text" class="form-control" name = "pbamount" id = "pbamount" data-validation="number"
													data-validation-error-msg="Enter Amount"
													data-validation-allowing="float">
								</div>		
							</div>
							<div class="col-md-3">
								
								 <div class="form-group">
											<label for="age">REMITTANCE CENTER:</label>
											<select name = "pbrem" class="form-control" data-validation="required"
													data-validation-error-msg="Select REMITTANCE CENTER">
												<option hidden "selected"></option>
											<?php
												$bquery = mysqli_query($con,"Select * from lup_remittance_center where isdeleted = 0");
												while($brow = mysqli_fetch_assoc($bquery))
												{
													?>
														<option value = "<?php echo $brow['remittance_center_id'];?>"><?php echo $brow['remittance_center_name'];?></option>
													<?php
												}
											?>
											</select>										
								</div>
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Payment Date:</label>
									<input type = "date" class = "form-control" name = "pbdate" id = "pbdate" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Payment Date">
								</div>		
							</div>
							
							<div class="col-md-7">
								<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">ADD</button>
										<button class = "btn btn-danger btn-flat" id = "pbcancel">CANCEL</button>
									</div>	
								</div>
							</div>
							
						</div>
			</form>
						<script>
								$("#pbcancel").click(
									function(e)
									{
										e.preventDefault();
										$.post( 
																	'../php/finance.php',
																	{
																		resetdeposittype:'<?php echo $tran;?>'
																		
																	},
																	function(data) {
																		$('#addpaymentui').html(data);	
																		
																	});
									}
								);
												$.validate({
															form:'#paymentbankform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#paymentbankform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#paymentdetailsui").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#paymentdetailsui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
						</script>
		<?php
	}
}

if(isset($_POST['pbamount']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
			//echo "The value of ".$key." is ". $val." <br>";
		} 
	$user = get_user_id($_SESSION['useraccount']);
	
	if(isset($_POST['pbbank']))
	{
		mysqli_query($con,"insert into order_payment set
		transaction_id = $pbtrasanctionid,
		amount = $pbamount,
		bank_id = $pbbank,
		date_added = '$pbdate',
		added_by = $user
		");
	}
	else
	{
		mysqli_query($con,"insert into order_payment set
		transaction_id = $pbtrasanctionid,
		amount = $pbamount,
		remittance_center_id = $pbrem,
		date_added = '$pbdate',
		added_by = $user
		");
	}
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select sum(amount) as total from order_payment where transaction_id = $pbtrasanctionid and isdeleted = 0"));
	$row =mysqli_fetch_assoc(mysqli_query($con,"Select total_amount as total from order_transaction where order_transaction_id = $pbtrasanctionid"));
	
	if($total['total'] >= $row['total'])
	{
		change_payment_status($pbtrasanctionid,2);
	}
	
	transaction_payment($pbtrasanctionid);
	
}
if(isset($_REQUEST['resetdeposittype']))
{
	$id = $_REQUEST['resetdeposittype'];
	?>
		<form class = "form" method = "POST" id = "dtypeform">
						<div class = "row">	
							<div class="col-md-7">
								
								 <div class="form-group">
											<label for="age">Select REMITTANCE CENTER/BANK:</label>
											<input type = "hidden" name = "dtypetransactionid" value = "<?php echo $id;?>">
											<select id="sfremit" name = "dtype" class="form-control" data-validation="required"
													data-validation-error-msg="Select REMITTANCE STATUS">
													<option hidden "selected"></option>
													<option value = "1">BANK</option>
													<option value = "2">REMITTANCE CENTER</option>
											</select>										
								</div>
							</div>
							<div class="col-md-4">
								<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">ADD NEW TRANSACTION</button>
									</div>	
								</div>
							</div>
							
						</div>
					</form>
						<script>
												$.validate({
															form:'#dtypeform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#dtypeform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#addpaymentui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
						</script>
	<?php
}
if(isset($_REQUEST['deletedpayment']))
{
	$id = $_REQUEST['deletedpayment'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select transaction_id from order_payment where order_payment_id = $id"));
	mysqli_query($con,"Update order_payment set isdeleted = 1 where order_payment_id = $id");
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select sum(amount) as total from order_payment where transaction_id = $row[transaction_id] and isdeleted = 0"));
	$row2 = mysqli_fetch_assoc(mysqli_query($con,"Select total_amount as total from order_transaction where order_transaction_id = $row[transaction_id]"));
	
	if($total['total'] >= $row2['total'])
	{
		change_payment_status($row['transaction_id'],2);
	}
	else
	{
		change_payment_status($row['transaction_id'],1);
	}
	
	transaction_payment($row['transaction_id']);
	
}
if(isset($_REQUEST['editdpayment']))
{
	$id = $_POST['editdpayment'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from order_payment where order_payment_id = $id"));
	$date_addedc = date_create($row['date_added']);
	$date_added = date_format($date_addedc,"Y-m-d");
	
	echo $date_added;
	if($row['bank_id'] != 0)
	{
		?>
			<form class = "form" method = "POST" id = "paymentbankformedit">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>AMOUNT:</label>
									<input type = "hidden" name = "editdpaymentid" value = "<?php echo $id;?>">
									 <input type="text" class="form-control" name = "pbamountedit" id = "pbamountedit" data-validation="number"
													data-validation-error-msg="Enter Amount"
													data-validation-allowing="float" value = "<?php echo $row['amount'];?>">
								</div>		
							</div>
							<div class="col-md-3">
								
								 <div class="form-group">
											<label for="age">BANK:</label>
											<select name = "pbbankedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select Bank">
											<?php
												$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_bank where bank_id = $row[bank_id]"));
											?>
												<option hidden "selected" value = "<?php echo $bank['bank_id'];?>"><?php echo $bank['bank_name'];?></option>
											<?php
												$bquery = mysqli_query($con,"Select * from lup_bank where isdeleted = 0");
												while($brow = mysqli_fetch_assoc($bquery))
												{
													?>
														<option value = "<?php echo $brow['bank_id'];?>"><?php echo $brow['bank_name'];?></option>
													<?php
												}
											?>
											</select>										
								</div>
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Payment Date:</label>
									<input type = "date" class = "form-control" name = "pbdateedit" id = "pbdateedit" data-validation="date"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Payment Date"
									value = "<?php echo $date_added;?>">
								</div>		
							</div>
							
							<div class="col-md-7">
								<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat btn-sm" id = "filter">UPDATE</button>
										<button class = "btn btn-danger btn-flat btn-sm" id = "bcancel">CANCEL</button>
									</div>	
								</div>
							</div>
							
						</div>
			</form>
						<script>
								$("#bcancel").click(
									function(e)
									{
										e.preventDefault();
										$.post( 
																	'../php/finance.php',
																	{
																		resetdeposittype:'<?php echo $row["transaction_id"];?>'
																		
																	},
																	function(data) {
																		$('#addpaymentui').html(data);	
																		
																	});
									}
								);
												$.validate({
															form:'#paymentbankformedit',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#paymentbankformedit').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#paymentdetailsui").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#paymentdetailsui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
						</script>
		<?php
	}
	else
	{
		?>
			<form class = "form" method = "POST" id = "paymentbankformedit">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>AMOUNT:</label>
									<input type = "hidden" name = "editdpaymentid" value = "<?php echo $id;?>">
									 <input type="text" class="form-control" name = "pbamountedit" id = "pbamountedit" data-validation="number"
													data-validation-error-msg="Enter Amount"
													data-validation-allowing="float" value = "<?php echo $row['amount'];?>">
								</div>		
							</div>
							<div class="col-md-3">
								
								 <div class="form-group">
											<label for="age">REMITTANCE CENTER:</label>
											<select name = "pbremedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select REMITTANCE CENTER">
												<?php
												$rem = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_remittance_center where remittance_center_id = $row[remittance_center_id]"));
												?>
											
												<option hidden "selected" value = "<?php echo $rem['remittance_center_id'];?>"><?php echo $rem['remittance_center_name'];?></option>
											<?php
												$bquery = mysqli_query($con,"Select * from lup_remittance_center where isdeleted = 0");
												while($brow = mysqli_fetch_assoc($bquery))
												{
													?>
														<option value = "<?php echo $brow['remittance_center_id'];?>"><?php echo $brow['remittance_center_name'];?></option>
													<?php
												}
											?>
											</select>										
								</div>
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Payment Date:</label>
									<input type = "date" class = "form-control" name = "pbdateedit" id = "pbdateedit" data-validation="date" 
							
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Payment Date"
									value = "<?php echo $date_added;?>">
								</div>		
							</div>
							
							<div class="col-md-7">
								<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat btn-sm" id = "filter">UPDATE</button>
										<button class = "btn btn-danger btn-flat btn-sm" id = "pbcancel">CANCEL</button>
									</div>	
								</div>
							</div>
							
						</div>
			</form>
						<script>
								$("#pbcancel").click(
									function(e)
									{
										e.preventDefault();
										$.post( 
																	'../php/finance.php',
																	{
																		resetdeposittype:'<?php echo $row["transaction_id"];?>'
																		
																	},
																	function(data) {
																		$('#addpaymentui').html(data);	
																		
																	});
									}
								);
												$.validate({
															form:'#paymentbankformedit',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#paymentbankformedit').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#paymentdetailsui").html(loading);
																			$.ajax({
																				url :  '../php/finance.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#paymentdetailsui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
						</script>
		<?php
	}
}
if(isset($_POST['pbamountedit']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
			//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select transaction_id from order_payment where order_payment_id = $editdpaymentid"));
	
	$user = get_user_id($_SESSION['useraccount']);
	
	if(isset($_POST['pbbankedit']))
	{
		$save = mysqli_query($con,"Update order_payment set
		amount = $pbamountedit,
		bank_id = $pbbankedit,
		date_added = '$pbdateedit'
		where 
		order_payment_id = $editdpaymentid
		");
		
		if(!$save)
			echo "error updating";
	}
	else
	{
		mysqli_query($con,"Update order_payment set
		amount = $pbamountedit,
		remittance_center_id = $pbrem,
		date_added = '$pbdateedit'
		where 
		order_payment_id = $editdpaymentid
		");
	}
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select sum(amount) as total from order_payment where transaction_id = $row[transaction_id] and isdeleted = 0"));
	$row2 = mysqli_fetch_assoc(mysqli_query($con,"Select total_amount as total from order_transaction where order_transaction_id = $row[transaction_id]"));
	
	if($total['total'] >= $row2['total'])
	{
		change_payment_status($row['transaction_id'],2);
	}
	else
	{
		change_payment_status($row['transaction_id'],1);
	}
	
	transaction_payment($row['transaction_id']);
}

?>
