<?php
include('connect.php');
include("general.php");
	
if(isset($_REQUEST['transactionui']))
{
	?>
		<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">ORDER TRANSACTION</a></li>
              <li style = "display:none;"><a href="#tab_2" data-toggle="tab">STOCK MONITORING</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
					<div class="box box-warning">
						<div class="box-body">
							<div class="row">
										<div class="col-md-3">
										  
											<label for="lname">Search Name/Account Number:</label>
											<input type="text" id="key" name="key" class="form-control">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">PROCEED</button>
											<button class = "btn btn-primary btn-flat" id = "newcustomer">NEW CUSTOMER</button>
										  </div>
										</div>
									
							</div>
							<div id = "newtransactionalert"></div>
						</div>
					</div>
					<div id = "newtransactionui"></div>
              </div>
							<script>
									$("#newcustomer").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/sales.php',
																{
																	newcustomer:1
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									
									$("#key").keyup(
											function()
											{
													var s = $("#key").val();
														if(s != "")
														{
															$.post( 
															'../php/sales.php',
															 { eq: s },
															 function(data) {
																$('#search_result').html(data);
															 });
														}
														else
														{
															$('#search_result').html("");
														}
											});
											
									$("#searchproceed").click(
											function()
											{
													var account = $("#key").val();
												
													
													if($.trim(account) != '')
													{
															$('#newtransactionui').html(loading);
														$.post( 
															'../php/sales.php',
															 { newtransactionui: account },
															 function(data) {
																$('#newtransactionui').html(data);
																
															 });
													}
													else
													{
														norify("Enter Key to Search","#newtransactionalert");
													}
												
											});
											
								</script>
								
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                The European languages are members of the same family. Their separate existence is a myth.
                For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                in their grammar, their pronunciation and their most common words. Everyone realizes why a
                new common language would be desirable: one could refuse to pay expensive translators. To
                achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                words. If several languages coalesce, the grammar of the resulting language is more simple
                and regular than that of the individual languages.
              </div>
              
            </div>
           
          </div>
        
	<?php
}

if(isset($_REQUEST['newtransactionui']))
{
	$id = $_REQUEST['newtransactionui'];
	
	$agent = "";
	
	if(isset($_REQUEST['newtransactionagent']))
	{
		$agent = $_REQUEST['newtransactionagent'];
	}
	global $con;
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_no = '$id'"));
	
	if(!empty($row))
	{
		$ocheck = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction where customer_id = $row[customer_id]
		and isdeleted = 0 and STR_TO_DATE(order_transaction_date,'%Y-%m-%d') = STR_TO_DATE(NOW(),'%Y-%m-%d')"));
		
		if($agent != "")
		{
				?>
					<div class="box">
						<div class="box-body" id = "customerinfoui">
							<?php agent_info($agent);?>
						</div>
						
					</div>
				<?php
		}
		?>
			
			<div class="box">
						<div class="box-body" id = "customerinfoui">
							<?php customer_info($row['customer_id']);?>
							
						</div>
						<div style = "float:right;"><button class = "btn btn-danger btn-flat btn-xs" id = "cedit">EDIT</button></div>
			</div>
			<script>
												$("#cedit").click(
													function()
													{
															$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/main.php',
																{
																	modcustomer:<?php echo $row['customer_id'];?>,
																	issave:1,
																	cont:'customerinfoui'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
													}
												);
											</script>
			<div class="box">
						<div class="box-body" id = "newitemui">
							<form id = "itemtypeform">
									 <div class="form-group">
										<div class="form-row" >
											<div class="col-md-3">
												<div class="form-group">
												
													<label>Select Packaging To Add:</label>
													<input type = "hidden" name = "cpcustomer" value = "<?php echo $row['customer_id'];?>">
													<Select class = "form-control" name = "package" data-validation="required"
													data-validation-error-msg="Select Packaging">
														<option value = "" hidden "Selected"> </option>
														<option value = 1>Retail</option>
														<option value = 2>Package</option>
													</select>
												
												</div>
											</div>
											<div class="col-md-3" style = "padding-top:25px;">
												<button class = "btn btn-success btn-flat" id = "add_item">PROCEED</button>					
											</div>

									  </div>
									</div>
							</form>
							
							<script>
										$.validate({
														form:'#itemtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#itemtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  '../php/sales.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#newitemui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
						</div>
			</div>
			<div class="box">
						<div class="box-body" id = "cartui">
							<?php show_cart($row['customer_id']);?>
						</div>
			</div>
			
			<div class="box">
						<div class="box-body" id = "pmethodui">
							<form id = "paymentmethodform">
									 <div class="form-group">
										<div class="form-row" >
											<div class="col-md-3">
												<div class="form-group">
													
													<label>Select Payment Method:</label>
													<input type = "hidden" name = "paycustomer" value = "<?php echo $row['customer_id'];?>">
													<input type = "hidden" name = "already" value = "<?php echo $ocheck;?>">
													<input type = "hidden" name = "agentid" value = "<?php echo $agent;?>">
													<Select class = "form-control" name = "pmethod" data-validation="required"
													data-validation-error-msg="Select Payment Method">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_payment_method where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['payment_method_id'];?>"><?php echo $prow['payment_method_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
											</div>
											<div class="col-md-3" style = "padding-top:25px;">
												<button class = "btn btn-success btn-flat" id = "add_item">PROCEED</button>					
											</div>

									  </div>
									</div>
							</form>
							
							<script>
										$.validate({
														form:'#paymentmethodform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#paymentmethodform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  '../php/sales.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
																			success : function(data) {
																				$("#pmethodui").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
							
						</div>
			</div>
			<div id = "pmalert"></div>
			
		<?php
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#newtransactionalert");
			</script>
		<?php
	}
}
if(isset($_POST['package']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	if($package == 1)
	{
		?>
							<form id = "newitemform" method = "post">
									 <div class="form-group">
										<div class="form-row" >
											<div class="col-md-3">
												<label>Browse Product:</label>

													<div class="input-group">
													  <div class="input-group-addon bg-primary">
														<i class="fa fa-search"></i>
													  </div>
													   <input type = "hidden" id = "cpcustomerid" name = "cpcustomerid" value = "<?php echo $cpcustomer;?>">
													  <input type = "hidden" id = "cproducthidden" name = "cproducthidden">
													  <input type="text" class="form-control" name = "cproduct" id = "cproduct" readonly data-validation="required"
													data-validation-error-msg="Browse Product" placeholder = "Click here to browse">
													</div>
											</div>
											<div class="col-md-3" id = "sales_unitui">
												<div class = "form-group">
													<label>UNIT:</label>
													<select name = "sunit" id = "sunit" class="form-control" data-validation="required"
																	data-validation-error-msg="Select UNIT">
																	<option  hidden "Selected"></option>
																
													</select>
												</div>		
											</div>
							
											<div class="col-md-2">
												<label>QTY:</label>

													<div class="input-group">
													  
													  <input type="text" class="form-control" name = "cpqty" data-validation="number"
													data-validation-error-msg="Enter Quantity"
													data-validation-allowing="range[1;10000000]">
													</div>
											</div>
											<div class="col-md-2">
												<label>PRICE:</label>
													<div class="input-group">
													  <input type="text" class="form-control" name = "cpprice" id = "cpprice" data-validation="required"
													data-validation-error-msg="Enter Price">
													</div>
											</div>
											
											<div class="col-md-3" style = "padding-top:25px;">
												<button class = "btn btn-success btn-flat" id = "add_item">ADD</button>	
												<button class = "btn btn-danger btn-flat" id = "cancel">CANCEL</button>													
											</div>

									  </div>
										
									</div>
							</form>
							<div id = "cpalert"></div>
							<script>
									$("#cproduct").click(
														function(e)
														{
															e.preventDefault();
															
															var s = $('#cproduct').attr('id');
															var hidden = $('#cproducthidden').attr('id');
															var price = $('#cpprice').attr('id');
															
															$("#modal2").modal("show");
															$("#modalbody2").css("min-width","60%");
															$('#modalui2').html(loading);	
															$.post( 
																'../php/main.php',
																{
																	productui:1,
																	productfield:s,
																	producthidden:hidden,
																	productprice:price
																},
																function(data) {
																	$('#modalui2').html(data);		
																});
														}
													);

													$("#cancel").click(
														function(e)
														{
															e.preventDefault();
															
															$.post( 
																				'../php/sales.php',
																				{
																					newitemuirefresh:'<?php echo $cpcustomer;?>'
																				},
																				function(data) {
																					$('#newitemui').html(data);	
																					
																				});
																				
														}
													);
										$.validate({
														form:'#newitemform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newitemform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  '../php/sales.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#cartui").html(data);
																				
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
			<form id = "newpackform" method = "post">
									 <div class="form-group">
										<div class="form-row" >
											<div class="col-md-3">
												<label>Browse Package:</label>

													<div class="input-group">
													  <div class="input-group-addon bg-primary">
														<i class="fa fa-search"></i>
													  </div>
													   <input type = "hidden" id = "cpackcustomerid" name = "cpackcustomerid" value = "<?php echo $cpcustomer;?>">
													  <input type = "hidden" id = "cpackhidden" name = "cpackhidden">
													  <input type="text" class="form-control" name = "cpack" id = "cpack" readonly data-validation="required"
													data-validation-error-msg="Browse Product" placeholder = "Click here to browse">
													</div>
											</div>
											
											<div class="col-md-5" style = "padding-top:25px;">
												<button class = "btn btn-success btn-flat" id = "add_item">ADD</button>	
												<button class = "btn btn-danger btn-flat" id = "cancel">CANCEL</button>													
											</div>

									  </div>
										
									</div>
							</form>
							<div id = "cpalert"></div>
							<script>
									$("#cpack").click(
														function(e)
														{
															e.preventDefault();
															e.preventDefault();
															
															var s = $('#cpack').attr('id');
															var hidden = $('#cpackhidden').attr('id');
														
															$("#modal2").modal("show");
															$("#modalbody2").css("max-width","60%");
															$('#modalui2').html(loading);	
															$.post( 
																'../php/main.php',
																{
																	packageui:1,
																	packagefield:s,
																	packagehidden:hidden
																	
																},
																function(data) {
																	$('#modalui2').html(data);		
																});
														}
													);
													
									$("#cancel").click(
														function(e)
														{
															e.preventDefault();
															
															$.post( 
																			'../php/sales.php',
																				{
																					newitemuirefresh:"<?php echo $cpcustomer;?>"
																				},
																				function(data) {
																					$('#newitemui').html(data);		
																				});
																				
														}
													);
										$.validate({
														form:'#newpackform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#newpackform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																
																$("#modal").modal("show");
																$("#modalbody").css("max-width","60%");
																$('#modalui').html(loading);
																 $("#modalui").html(loading);
																 
																		$.ajax({
																			url :  '../php/sales.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#modalui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
		<?php
	}
}
if(isset($_POST['cproduct']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
		//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$isshippingfee = mysqli_fetch_assoc(mysqli_query($con,"Select isshipping from lup_product where product_id = $cproducthidden"));
	//global $con;
	$total = $cpprice * $cpqty;
	$save = mysqli_query($con,"insert into order_transaction_detail set
	product_id = $cproducthidden,
	unit_id = $sunit,
	customer_id = $cpcustomerid,
	product_description = '$cproduct',
	product_price = $cpprice,
	order_quantity = $cpqty, 
	isshipping = $isshippingfee[isshipping],
	total_order_amount = $total");
	
	if($save)
	{
		?>
			<script>
				notify("Product Successfully Added to cart","#cpalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("Error Adding Product, Please Contact the System Administrator","#cpalert");
			</script>
		<?php
	}
	show_cart($cpcustomerid);
}

if(isset($_POST['cpack']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
		//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	global $con;
	$query = mysqli_query($con,"Select * from settings_package where package_id = $cpackhidden and isdeleted = 0");
	?>
	<div class="box">
		<div class="box-body">
			<form method = "post" id = "packageform">
		<input type = "hidden" name = "cpack2" value = "<?php echo $cpackhidden;?>">
		<input type = "hidden" name = "cpack2customerid" value = "<?php echo $cpackcustomerid ;?>">
		
		<table class = "table table-striped table-hover table-sm table-responsive" id = "jomastertable">
								<thead>
									<th>#</th>
									<th>ITEM DESCRIPTION</th>
									<th>PACKAGE</th>
									<th>ITEM CODE</th>
									<th>UNIT</th>
									<th>Qty</th>
									<th>PRICE/UNIT</th>
								
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$pro = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_product where product_id = $row[product_id]"));
					$unit = mysqli_fetch_assoc(mysqli_query($con,"Select unit_description from inv_lup_unit where unit_id = $row[unit_id]"));
				?>
					<input type = "hidden" name = "cpack2proid<?php echo $ctr;?>" value = "<?php echo $pro['product_id']; ;?>">
					<input type = "hidden" name = "cpack2unitid<?php echo $ctr;?>" value = "<?php echo $row['unit_id']; ;?>">
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $cpack;?></td>
						<td><?php echo $pro['product_description'];?></td>
						<td><?php echo $pro['product_code'];?></td>
						<td><?php echo $unit['unit_description'];?></td>
						<td><?php echo $row['qty'];?></td>
						<td><input type="text" class="form-control" name = "pprice<?php echo $ctr;?>" data-validation="required"
													data-validation-error-msg="ENTER PRICE" value = "<?php echo $pro['price1'];?>"></td>
						
					</tr>
				
				<?php
				$ctr++;
			}
			?>
		</table>
				<input type = "hidden" name = "cpack2count" value = "<?php echo $ctr;?>">
				<div class="col-md-6" style = "padding-top:25px;">
					<button class = "btn btn-success btn-flat" id = "savee">ADD</button>	
					<button class = "btn btn-danger btn-flat" id = "cancell">CANCEL</button>													
				</div>
											
	</form>
		<script>
					$("#savee").click(
						function()
						{
								$.validate({
														form:'#packageform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#packageform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  '../php/sales.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#cartui").html(data);
																				//alert('OK');
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
						}
					);
				
			$("#cancell").click(
				function(e)
				{
					e.preventDefault();
					$("#modal").modal('hide');
				}
			);
		
		
			
			
		</script>
		</div>
	</div>
	<?php
}

if(isset($_POST['cpack2']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
		//echo "The value of ".$key." is ". $val." <br>";
	} 
	$ctr = 1;
	
	//$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_package where package_id = $cpack2 and isdeleted = 0"));
	
	while($ctr <= $cpack2count-1)
	{
		$pprice = $_POST['pprice'.$ctr];
		$ppro = $_POST['cpack2proid'.$ctr];
		$unitid = $_POST['cpack2unitid'.$ctr];
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_package where package_id = $cpack2 and product_id = $ppro"));
		?>
			<script>
				//alert('<?php echo $ppro;?>');
			</script>
		
		<?php
	/*while($row = mysqli_fetch_assoc($query))
	{*/
		$price = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_product where product_id = $ppro"));
	
		$total = $pprice * $row['qty'];
		$isshippingfee = mysqli_fetch_assoc(mysqli_query($con,"Select isshipping from lup_product where product_id = $ppro"));
		
		$save = mysqli_query($con,"insert into order_transaction_detail set
		product_id = $ppro,
		unit_id = $unitid,
		package_id = $cpack2,
		customer_id = $cpack2customerid,
		product_description = '$price[product_description]',
		product_price = $pprice,
		order_quantity = $row[qty], 
		isshipping = $isshippingfee[isshipping],
		total_order_amount = $total");
		
		$ctr++;
	}
	show_cart($cpack2customerid);
	?>
		<script>
			$("#modal").modal('hide');
		</script>
	<?php
}
if(isset($_REQUEST['newitemuirefresh']))
{
	$id = $_REQUEST['newitemuirefresh'];
	?>
		<form id = "itemtypeform" method = "post">
									 <div class="form-group">
										<div class="form-row" >
											<div class="col-md-3">
												<div class="form-group">
												
													<label>Select Packaging To Add:</label>
													<input type = "hidden" name = "cpcustomer" value = "<?php echo $id;?>">
													<Select class = "form-control" name = "package" data-validation="required"
													data-validation-error-msg="Select Packaging">
														<option value = "" hidden "Selected"> </option>
														<option value = 1>Retail</option>
														<option value = 2>Package</option>
													</select>
												
												</div>
											</div>
											<div class="col-md-3" style = "padding-top:25px;">
												<button class = "btn btn-success btn-flat" id = "add_item">PROCEED</button>					
											</div>

									  </div>
									</div>
							</form>
							
							<script>
										$.validate({
														form:'#itemtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#itemtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  '../php/sales.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#newitemui").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
	<?php
}

if(isset($_POST['pmethod']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_payment_method where payment_method_id = $pmethod"));
	
	
		?>
							<form id = "bankform" method = "post" enctype="multipart/form-data">
									  <input type = "hidden" id = "pcustomerid" name = "pcustomerid" value = "<?php echo $paycustomer;?>">
									   <input type = "hidden" id = "pmethodid" name = "pmethodid" value = "<?php echo $pmethod;?>">
									    <input type = "hidden" id = "pagentid" name = "pagentid" value = "<?php echo $agentid;?>">
										<div class="row" >
										<?php
										
										
										if($row['rem'] == 1)
										{
											?>
											<div class="col-md-3">
												<label>Browse Remittance Center:</label>
													<div class="input-group">
													  <div class="input-group-addon bg-primary">
														<i class="fa fa-search"></i>
													  </div>
													  
													  <input type = "hidden" id = "premhidden" name = "premhidden">
													  <input type="text" class="form-control" name = "prem" id = "prem" required placeholder = "Click here to browse">
													</div>
											</div>
											<script>
													$("#prem").click(
														function(e)
														{
															e.preventDefault();
															
															var s = $('#prem').attr('id');
															var hidden = $('#premhidden').attr('id');
															
															$("#modal").modal("show");
															$("#modalbody").css("max-width","60%");
															
															$.post( 
																'../php/main.php',
																{
																	remui:1,
																	remfield:s,
																	remhidden:hidden
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
													);
											</script>
											<?php	
										}
										
										if($row['courier'] == 1)
										{
											?>
											<div class="col-md-3">
												<label>Browse Courier:</label>

													<div class="input-group">
													  <div class="input-group-addon bg-primary">
														<i class="fa fa-search"></i>
													  </div>
													   
													  <input type = "hidden" id = "pcourierhidden" name = "pcourierhidden">
													  <input type="text" class="form-control" name = "pcourier" id = "pcourier" readonly data-validation="required"
													data-validation-error-msg="Browse Courier" placeholder = "Click here to browse">
													</div>
											</div>
											<script>
												$("#pcourier").click(
														function(e)
														{
															e.preventDefault();
															
															var s = $('#pcourier').attr('id');
															var hidden = $('#pcourierhidden').attr('id');
															
															$("#modal2").modal("show");
															$("#modalbody2").css("max-width","60%");
															$('#modalui2').html(loading);
															
															$.post( 
																'../php/main.php',
																{
																	courierui:1,
																	courierfield:s,
																	courierhidden:hidden
																	
																},
																function(data) {
																	$('#modalui2').html(data);		
																});
														}
													);
													
												
							
											</script>
											
											<?php	
										}
										if($row['upload'] == 1)
										{
											if($row['bank'] == 1)
											{
											?>
												<div class="col-md-3">
												<label>Browse Bank:</label>
													<div class="input-group">
													  <div class="input-group-addon bg-primary">
														<i class="fa fa-search"></i>
													  </div>
													 
													  <input type = "hidden" id = "psbankhidden" name = "psbankhidden">
													  <input type="text" class="form-control" name = "psbank" id = "psbank" required>
													</div>
											</div>
											<script>
												$("#psbank").change(
														function()
														{
															alert("Click the field to browse remittance center");
															$("#psbank").val('');
														}
													);
									
													
													
												$("#psbank").click(
														function(e)
														{
															e.preventDefault();
															
															var s = $('#psbank').attr('id');
															var hidden = $('#psbankhidden').attr('id');
															
															$("#modal").modal("show");
															$("#modalbody").css("max-width","60%");
															
															$.post( 
																'../php/main.php',
																{
																	bankui:1,
																	bankfield:s,
																	bankhidden:hidden
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
													);
											</script>
											<?php	
											}
											?>
											<div class="col-md-2">
												<label>REFERENCE NO:</label>

													<div class="input-group">
													  
													  <input type="text" class="form-control" name = "psref" required>
													</div>
											</div>
											<div class="col-md-2">
												<label>SCANNED IMAGE:</label>
													<div class="input-group">
													  <input type="file" class="form-control" name = "pscan" id = "pscan" required>
													</div>
											</div>
											<?php	
										}
										else
										{
											if($row['bank'] == 1)
											{
											?>
												<div class="col-md-3">
												<label>Browse Bank:</label>
													<div class="input-group">
													  <div class="input-group-addon bg-primary">
														<i class="fa fa-search"></i>
													  </div>
													 
													  <input type = "hidden" id = "pbankhidden" name = "pbankhidden">
													  <input type="text" class="form-control" name = "pbank" id = "pbank" required>
													</div>
											</div>
											<script>
												$("#pbank").change(
														function()
														{
															alert("Click the field to browse remittance center");
															$("#pbank").val('');
														}
													);
												$("#pbank").click(
														function(e)
														{
															e.preventDefault();
															
															var s = $('#pbank').attr('id');
															var hidden = $('#pbankhidden').attr('id');
															
															$("#modal").modal("show");
															$("#modalbody").css("max-width","60%");
															
															$.post( 
																'../php/main.php',
																{
																	bankui:1,
																	bankfield:s,
																	bankhidden:hidden
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
													);
											</script>
											
											<?php	
											}
											?>
												<div class="col-md-2">
												<label>REFERENCE NO:</label>

													<div class="input-group">
													  
													  <input type="text" class="form-control" name = "pref" required>
													</div>
											</div>
											<?php
										}
										
										if($row['sender'] == 1)
										{
											?>
											<div class="col-md-2">
												<label>SENDER:</label>
													<div class="input-group">
													  <input type="text" class="form-control" name = "psender" required>
													</div>
											</div>
											<?php	
										}
										
										
										?>
											
											<div class="col-md-2">
												<label>REMARKS:</label>
													<div class="input-group">
													<textarea class="form-control" name = "premarks" required></textarea>
													</div>
											</div>
											
											
									</div>
									<div class = "row">									
											
											<div class="col-md-5">
												<button class = "btn btn-success btn-flat btn-sm" id = "SAVE">FINALIZE TRANSACTION</button>	

												<button class = "btn btn-warning btn-flat btn-sm" id = "change">CHANGE PAYMENT METHOD</button>	

												<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>														
											</div>

									  </div>
										
									
							</form>
							<div id = "pmalerta"></div>
							<script>
									
									
									
									$("#change").click(
										function(e)
										{
											e.preventDefault();
											
											$.post( 
																'../php/sales.php',
																{
																	selectpmethod:1,
																	selectpmethodalready:'<?php echo $already;?>'
																	
																},
																function(data) {
																	$('#pmethodui').html(data);		
																});
										}
									);
									
									
										
									
							
																$("#bankform").on('submit',(function(e) {
																	e.preventDefault();
																		
																		<?php
																		if($already != 0)
																		{
																			?>
																				var r = confirm("Customer is already have a transaction record today? Do you want to continue?");
																				
																				if(r == true)
																				{
																					$.ajax({
																						url: '../php/sales.php',
																						type: "POST",
																						data:  new FormData(this),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#pmethodui").html(data);
																							
																						},
																						error: function() 
																						{
																							alert('Sending failed');
																						} 	        
																				   });
																				}
																			<?php
																		}
																		else
																		{
																			?>
																				$.ajax({
																				url: '../php/sales.php',
																				type: "POST",
																				data:  new FormData(this),
																				contentType: false,
																				cache: false,
																				processData:false,
																				success: function(data)
																				{
																					$("#pmethodui").html(data);
																					
																				},
																				error: function() 
																				{
																					alert('Sending failed');
																				} 	        
																		   });
																			<?php
																		}
																		?>
																			
																	   
																	}));	
										$("#cancel").click(
										function(e)
										{
											e.preventDefault();
											location.reload();
										}
									);
									
							</script>
							
		<?php
	
	
}
if(isset($_FILES['pscan']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//echo $bankscan;
	
	if(is_array($_FILES)) 
	{
		$name = $_FILES['pscan']['name'];
		$type = $_FILES['pscan']['type'];
		$size = $_FILES['pscan']['size'];
		
		$bank = 0;
		
		$rem = 0;
		
		if(isset($_POST['prem']))
			$rem = $premhidden;
		
		if(isset($_POST['psbank']))
			$bank = $psbankhidden;
		
		echo $rem." aaa ".$bank;
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
			$found = mysqli_num_rows(mysqli_query($con, "Select * from order_transaction where result = '$result'"));
			if($found == 0)
				break;
			else
				$i = 0;
		}
		$total = 0;
		$total = mysqli_fetch_assoc(mysqli_query($con, "Select SUM(total_order_amount) as total from order_transaction_detail where
		customer_id = $pcustomerid and order_transaction_id = 0 and isdeleted = 0"));
		
		if($total['total'] != "")
		{
			if(empty($pagentid))
				$user = get_user_id($_SESSION['ccraft']);
			else
				$user = $pagentid;
			
			$team_id = 0;
			$team = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $user"));
			
			if(!empty($team))
				$team_id = $team['sales_team_id'];
				
			mysqli_query($con, "insert into order_transaction set
			result = '$result',
			order_transaction_date = NOW(),
			customer_id = $pcustomerid,
			total_amount = $total[total],
			remarks_order = '$premarks',
			customer_shipping_address_id = 0,
			sales_agent_id = $user,
			team_id = $team_id,
			payment_method_id = $pmethodid,
			bank_id = $bank,
			remittance_center_id = $rem,
			reference_payment = '$psref',
			courier_id = '$pcourierhidden',
			datetime_created = NOW(),
			status_remittance  = 1
			");
			
			
					$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from order_transaction where result = '$result'"));
			
					if(empty($row))
					{
						$total_enrolled = 1;
					}
					else
					{
						$total_enrolled = $row['order_transaction_id'];
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
					
				$orderno = $count.$row['order_transaction_id'];
				
				mysqli_query($con, "Update order_transaction set order_transaction_no = '$orderno' where order_transaction_id = $row[order_transaction_id]");
					
			
			$sss = $orderno."_".$_FILES['pscan']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					$sourcePath = $_FILES['pscan']['tmp_name'];
					$targetPath = "../images/bank/".basename($orderno."_".$_FILES['pscan']['name']);

						if(is_uploaded_file($_FILES['pscan']['tmp_name'])) 
						{
							echo "
								<script>
									$('#upload_status').html('Uploading Image, Please Wait');
								</script>
							";
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									mysqli_query($con, "Update order_transaction set reference_payment_image  = '$sss' where 
									order_transaction_id = $row[order_transaction_id]");
									
									mysqli_query($con,"Update order_transaction_detail set 
									order_transaction_id = $row[order_transaction_id],
									order_transaction_no = '$orderno', 
									order_transaction_date = '$row[order_transaction_date]'
									where customer_id = $pcustomerid and order_transaction_id = 0");
									
									mysqli_query($con, "update order_transaction set result = '' where result = '$result'")
									?>
										<script>
											alert('Transaction completed');
											location.reload();
										</script>
									<?php
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
		}
		else
		{
			?>
				<script>
					alert("No Items in the Cart");
				</script>
			<?php
		}
	}
	
	
}
if(isset($_POST['coref']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//echo $bankscan;
	
	
	
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
			$found = mysqli_num_rows(mysqli_query($con, "Select * from order_transaction where result = '$result'"));
			if($found == 0)
				break;
			else
				$i = 0;
		}
		$total = 0;
		$total = mysqli_fetch_assoc(mysqli_query($con, "Select SUM(total_order_amount) as total from order_transaction_detail where
		customer_id = $cocustomerid and order_transaction_id = 0 and isdeleted = 0"));
		
		if($total['total'] != "")
		{
			if(empty($pagentid))
				$user = get_user_id($_SESSION['ccraft']);
			else
				$user = $pagentid;
			
			$team_id = 0;
			$team = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $user"));
			
			if(!empty($team))
				$team_id = $team['sales_team_id'];
				
			mysqli_query($con, "insert into order_transaction set
			result = '$result',
			order_transaction_date = NOW(),
			customer_id = $cocustomerid,
			total_amount = $total[total],
			customer_shipping_address_id = 0,
			sales_agent_id = $user,
			team_id = $team_id,
			courier_id = $cocourierhidden,
			reference_careof = '$coref',
			remarks_order = '$coremarks',
			datetime_created = NOW(),
			status_remittance  = 1
			");
			
			
			$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from order_transaction where result = '$result'"));
			
					if(empty($row))
					{
						$total_enrolled = 1;
					}
					else
					{
						$total_enrolled = $row['order_transaction_id'];
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
					
				$orderno = $count.$row['order_transaction_id'];
				
				mysqli_query($con, "Update order_transaction set order_transaction_no = '$orderno' where order_transaction_id = $row[order_transaction_id]");
					
			
			//$sss = $orderno."_".$_FILES['bankscan']['name'];
			
									
									
									mysqli_query($con,"Update order_transaction_detail set 
									order_transaction_id = $row[order_transaction_id],
									order_transaction_no = '$orderno', 
									order_transaction_date = '$row[order_transaction_date]'
									where customer_id = $refcustomerid and order_transaction_id = 0");
									
									mysqli_query($con, "update order_transaction result = '' where result = '$result'")
									?>
										<script>
											//alert('Transaction completed');
											//location.reload();
										</script>
									<?php
								
							
						
					
				
			
		}
		else
		{
			?>
				<script>
					alert("No Items in the Cart");
				</script>
			<?php
		}
	
	
	
}

if(isset($_POST['pref']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//echo $bankscan;
	$bank = 0;
	$rem = 0;
	if(isset($_POST['prem']))
			$rem = $premhidden;
		
		if(isset($_POST['pbank']))
			$bank = $pbankhidden;
			
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
			$found = mysqli_num_rows(mysqli_query($con, "Select * from order_transaction where result = '$result'"));
			if($found == 0)
				break;
			else
				$i = 0;
		}
		$total = 0;
		$total = mysqli_fetch_assoc(mysqli_query($con, "Select SUM(total_order_amount) as total from order_transaction_detail where
		customer_id = $pcustomerid and order_transaction_id = 0 and isdeleted = 0"));
		
		if($total['total'] != "")
		{
			if(empty($pagentid))
				$user = get_user_id($_SESSION['ccraft']);
			else
				$user = $pagentid;
			
			$team_id = 0;
			$team = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $user"));
			
			if(!empty($team))
				$team_id = $team['sales_team_id'];
				
			mysqli_query($con, "insert into order_transaction set
			result = '$result',
			order_transaction_date = NOW(),
			customer_id = $pcustomerid,
			total_amount = $total[total],
			remarks_order = '$premarks',
			customer_shipping_address_id = 0,
			sales_agent_id = $user,
			team_id = $team_id,
			payment_method_id = $pmethodid,
			bank_id = $bank,
			remittance_center_id = $rem,
			reference_payment = '$pref',
			courier_id = '$pcourierhidden',
			datetime_created = NOW(),
			status_remittance  = 1
			");
			
			
			$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from order_transaction where result = '$result'"));
			
					if(empty($row))
					{
						$total_enrolled = 1;
					}
					else
					{
						$total_enrolled = $row['order_transaction_id'];
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
					
				$orderno = $count.$row['order_transaction_id'];
				
				mysqli_query($con, "Update order_transaction set order_transaction_no = '$orderno', result = '' where order_transaction_id = $row[order_transaction_id]");
					
			
			//$sss = $orderno."_".$_FILES['bankscan']['name'];
			
									
									
									mysqli_query($con,"Update order_transaction_detail set 
									order_transaction_id = $row[order_transaction_id],
									order_transaction_no = '$orderno', 
									order_transaction_date = '$row[order_transaction_date]'
									where customer_id = $pcustomerid and order_transaction_id = 0");
									
									//mysqli_query($con, "update order_transaction result = '' where result = '$result'")
									?>
										<script>
											alert('Transaction completed');
											location.reload();
										</script>
									<?php
								
							
						
					
				
			
		}
		else
		{
			?>
				<script>
					alert("No Items in the Cart");
				</script>
			<?php
		}
	
	
	
}

if(isset($_FILES['remscan']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//echo $bankscan;
	
	if(is_array($_FILES)) 
	{
		$name = $_FILES['remscan']['name'];
		$type = $_FILES['remscan']['type'];
		$size = $_FILES['remscan']['size'];
		
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
			$found = mysqli_num_rows(mysqli_query($con, "Select * from order_transaction where result = '$result'"));
			if($found == 0)
				break;
			else
				$i = 0;
		}
		
		$total = mysqli_fetch_assoc(mysqli_query($con, "Select SUM(total_order_amount) as total from order_transaction_detail where
		customer_id = $remcustomerid and order_transaction_id = 0 and isdeleted = 0")); 
		if($total['total'] != "")
		{
		
			if(empty($pagentid))
				$user = get_user_id($_SESSION['ccraft']);
			else
				$user = $pagentid;
			
			$team = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $user"));
			mysqli_query($con, "insert into order_transaction set
			result = '$result',
			order_transaction_date = NOW(),
			customer_id = $remcustomerid,
			total_amount = $total[total],
			remarks_order = '$remremarks',
			sales_agent_id = $user,
			customer_shipping_address_id = 0,
			team_id = $team[sales_team_id],
			remittance_center_id = $remhidden,
			reference_no_remittance_center = '$remref',
			courier_id = $rcourierhidden,
			datetime_created = NOW(),
			status_remittance = 1			
			");
			
			$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from order_transaction where result = '$result'"));
			
					if(empty($row))
					{
						$total_enrolled = 1;
					}
					else
					{
						$total_enrolled = $row['order_transaction_id'];
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
					
				$orderno = $count.$row['order_transaction_id'];
				
				mysqli_query($con, "Update order_transaction set order_transaction_no = '$orderno' where order_transaction_id = $row[order_transaction_id]");
					
			
			$sss = $orderno."_".$_FILES['remscan']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					$sourcePath = $_FILES['remscan']['tmp_name'];
					$targetPath = "../images/remit/".basename($orderno."_".$_FILES['remscan']['name']);

						if(is_uploaded_file($_FILES['remscan']['tmp_name'])) 
						{
							echo "
								<script>
									$('#upload_status').html('Uploading Image, Please Wait');
								</script>
							";
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									mysqli_query($con, "Update order_transaction set reference_image_bank  = '$sss' where 
									order_transaction_id = $row[order_transaction_id]");
									
									mysqli_query($con,"Update order_transaction_detail set 
									order_transaction_id = $row[order_transaction_id],
									order_transaction_no = '$orderno', 
									order_transaction_date = '$row[order_transaction_date]'
									where customer_id = $remcustomerid and order_transaction_id = 0");
									
									mysqli_query($con, "update order_transaction result = '' where result = '$result'")
									?>
										<script>
											alert('Transaction completed');
											location.reload();
										</script>
									<?php
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
		}
		else
		{
			?>
				<script>
					alert("No Items in the Cart");
				</script>
			<?php
		}
	}
	
}
if(isset($_REQUEST['selectpmethod']))
{
?>
	<form id = "paymentmethodform">
									 <div class="form-group">
										<div class="form-row" >
											<div class="col-md-3">
												<div class="form-group">
												
													<label>Select Payment Method:</label>
													<input type = "hidden" name = "paycustomer" value = "<?php echo $row['customer_id'];?>">
													<input type = "hidden" name = "already" value = "<?php echo $_REQUEST['selectpmethodalready'];?>">
													<Select class = "form-control" name = "pmethod" data-validation="required"
													data-validation-error-msg="Select Payment Method">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_payment_method where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['payment_method_id'];?>"><?php echo $prow['payment_method_name'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
											</div>
											<div class="col-md-3" style = "padding-top:25px;">
												<button class = "btn btn-success btn-flat" id = "add_item">PROCEED</button>					
											</div>

									  </div>
									</div>
							</form>
							
							<script>
										$.validate({
														form:'#paymentmethodform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#paymentmethodform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  '../php/sales.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
																			success : function(data) {
																				$("#pmethodui").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
													
							</script>
<?php
}
if(isset($_REQUEST['newcustomer']))
{
	?>
		<div class="box">
						<div class="box-body" STYLE = "padding:10px;" id = "profileui">
							<form id = "profile" method = "post">	
							<div class  ="row">
								<div class="col-md-4">
									 <div class="form-group">
											<input type = "hidden" name = "issave" value = "<?php echo $_REQUEST['newcustomer'];?>">
											 <label for="age">Reference No:</label>
												<input type="text" id="customer_type" name="customer_type" class="form-control">
											
									</div>
								</div>
								<div class="col-md-4">
									 <div class="form-group">
											
											 <label for="age">Customer Type:</label>
												<select id="ctype" name = "ctype" class="form-control" data-validation="required"
													data-validation-error-msg="Select Customer Type">
												<option value = "" hidden "Selected"></option>
												<?php
												$cquery = mysqli_query($con,"Select * from lup_customer_type where isdeleted = 0");
												while($crow = mysqli_fetch_assoc($cquery))
												{
												?>
												<option value = "<?php echo $crow['customer_type_id'];?>"><?php echo $crow['customer_type_name'];?></option>
												<?php
												}
												?>
											</select>
											
									</div>
								</div>
								
							</div>	
							<div class  ="row">
								<div class="form-group">
										  <label for="age">FULLNAME</label>
								</DIV>
							</div>
							<div class  ="row">
								<div class="col-md-4"><input name="fname" class = "form-control form-flat" placeholder="Firstname" value="" autocomplete="off" data-validation="required"
													data-validation-error-msg="FIRST NAME Field is Required"/></div>
								<div class="col-md-4"><input name="mname" class = "form-control form-flat" placeholder="Middlename" value="" autocomplete="off" data-validation="required"
													data-validation-error-msg="LAST NAME Field is Required"/></div>
								<div class="col-md-4"><input name="lname" class = "form-control form-flat" placeholder="Lastname" value="" autocomplete="off"/></div>
							</div>
							<div class  ="row">
								<div class="col-md-4">
									<div class="form-group">
											<label for="lname">FACEBOOK NAME</label>
											<input type="text" id="fbname" name="fbname" class="form-control" data-validation="required"
													data-validation-error-msg="FACEBOOK NAME Field is Required">
											
									</div>
								</div>
								<div class="col-md-4">
										  <div class="form-group">
										  <label for="age">SEX</label>
											<select id="sex" name = "sex" class="form-control" data-validation="required"
													data-validation-error-msg="Select Sex">
												<option value = "" hidden "Selected"></option>
												<option value = "MALE">MALE</option>
												<option value = "FEMALE">FEMALE</option>
											</select>
											
										  </div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
											<label for="lname">BIRTHDATE</label>
											<input type="date" name="bdate" class="form-control" data-validation="required"
													data-validation-error-msg="BIRTHDATE Field is Required">
											
									</div>
								</div>
								
							</div>
							
							
							<div class  ="row">
								<div class="col-md-4">
									<div class="form-group">
											<label for="lname">CONTACT NO:</label>
											<input type="text" id="lname" name="contact" class="form-control" data-validation="required"
													data-validation-error-msg="CONTACT NO Field is Required">
											
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
											<label for="lname">2ND CONTACT NO:</label>
											<input type="text" id="lname" name="contact2" class="form-control" data-validation="required"
													data-validation-error-msg="2ND CONTACT NO: Field is Required">
											
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
											<label for="lname">EMAIL ADDRESS:</label>
											<input type="text" id="email" name="email" class="form-control" data-validation="email"
													data-validation-error-msg="Enter Valid Email Address">
											
									</div>
								</div>
								
							</div>
							<div class  ="row">
								<div class="form-group">
										  <label for="age">HOME ADDRESS</label>
								</DIV>
							</div>
							<div class="row">
								<div class="col-md-4">
										  <div class="form-group">
										  <label for="age">REGION</label>
											<select name = "region" id = "region" class="form-control" data-validation="required"
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
												$("#region").change(
													function()
													{
														var id = $("#region").val();
														
														$.post( 
																'../php/sales.php',
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
								<div class="col-md-4">
										  <div class="form-group" id = "provinceui"> 
										  <label for="age">PROVINCE</label>
											<select id="province" name = "province" class="form-control" data-validation="required"
													data-validation-error-msg="Select PROVINCE">
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group" id = "citymunui">
										  <label for="age">CITY/MUNICIPALITY</label>
											<select id="citymun" name = "citymun" class="form-control" data-validation="required"
													data-validation-error-msg="Select CITY/MINICIPALITY">
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group" id = "brgyui">
										  <label for="age">BARANGAY</label>
											<select id="brgy" name = "brgy" class="form-control" data-validation="required"
													data-validation-error-msg="Select BARANGAY">
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-7">
									<div class="form-group">
											<label for="lname">STREET & NO:</label>
											<input type="text" name="street" id="street" class="form-control" data-validation="required"
											data-validation-error-msg="STREET & NO Field is Required">
											
									</div>
								</div>
								
							</div>
							<div class  ="row">
								<div class="form-group">
										  <label for="age">SHIPPING ADDRESS</label>
										  <button class = "btn btn-warning btn-flat btn-xs" id = "same">SAME AS HOME ADDRESS</button>
								</DIV>
							</div>
								<script>
									$("#same").click(
										function(e)
										{
											e.preventDefault();
											
											var reg = $("#region").val();
											var province = $("#province").val();
											var tprovince = $("#province option:selected").text();
											var citymun = $("#citymun").val();
											var tcitymun = $("#citymun option:selected").text();
											var brgy = $("#brgy").val();
											var tbrgy = $("#brgy option:selected").text();
											var street = $("#street").val();
											
											$("#sregion").val(reg);
											$('#sprovince').append('<option value = "'+ province +'" hidden Selected>'+ tprovince +'</option>');
											$('#scitymun').append('<option value = "'+ citymun +'" hidden Selected>'+ tcitymun +'</option>');
											$('#sbrgy').append('<option value = "'+ brgy +'" hidden Selected>'+ tbrgy +'</option>');
											
											
											//$("#sregion").val(reg);
											//$("#sprovince").val(province);
											//$("#scitymun").val(citymun);
											//$("#sbrgy").val(brgy);
											$("#sstreet").val(street);
											
											
										}
									);
								</script>
							<div class  ="row">
								<div class="col-md-4">
										  <div class="form-group">
										  <label for="age">REGION</label>
											<select name = "sregion" id = "sregion" class="form-control" data-validation="required"
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
												$("#sregion").change(
													function()
													{
														var id = $("#sregion").val();
														
														$.post( 
																'../php/sales.php',
																{
																	sprovinceui:id
																},
																function(data) {
																	$('#sprovinceui').html(data);
																	
																});
													}
												);
											</script>
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group" id = "sprovinceui"> 
										  <label for="age">PROVINCE</label>
											<select id="sprovince" name = "sprovince" class="form-control" data-validation="required"
													data-validation-error-msg="Select PROVINCE">
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group" id = "scitymunui">
										  <label for="age">CITY/MUNICIPALITY</label>
											<select id="scitymun" name = "scitymun" class="form-control" data-validation="required"
													data-validation-error-msg="Select CITY/MINICIPALITY">
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group" id = "sbrgyui">
										  <label for="age">BARANGAY</label>
											<select id="sbrgy" name = "sbrgy" class="form-control" data-validation="required"
													data-validation-error-msg="Select BARANGAY">
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-7">
									<div class="form-group">
											<label for="lname">STREET & NO:</label>
											<input type="text" name="sstreet" id="sstreet" class="form-control" data-validation="required"
													data-validation-error-msg="STREET & NO Field is Required">
											
									</div>
								</div>
								
							</div>
							<div class  ="row">
								<div class="col-md-4">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "SAVE">SAVE & PROCEED</button>
										  </div>
								</div>
							</div>
							
						</form>
						<script>
							$.validate({
															form:'#profile',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#profile').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 
																			$.ajax({
																				url :  '../php/sales.php',
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
	<?php
}

if(isset($_REQUEST['provinceui']))
{
	$id = $_REQUEST['provinceui'];
	?>
		<label for="age">PROVINCE</label>
											<select name = "province" id = "province" class="form-control" data-validation="required"
													data-validation-error-msg="Select PROVINCE">
												<option value = "" hidden "Selected"></option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_province where isdeleted = 0
												and region_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['province_id'];?>"><?php echo $rrow['province_name'];?></option>
												<?php
												
												}
												?>
											</select>
											<script>
												$("#province").change(
													function()
													{
														var id = $("#province").val();
														
														$.post( 
																'../php/sales.php',
																{
																	citymunui:id
																},
																function(data) {
																	$('#citymunui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['citymunui']))
{
	$id = $_REQUEST['citymunui'];
	?>
		<label for="age">CITY/MUNICIPALITY</label>
											<select name = "citymun" id = "citymun" class="form-control" data-validation="required"
													data-validation-error-msg="Select CITY/MUNICIPALITY">
												<option value = "" hidden "Selected"></option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_city_town where isdeleted = 0
												and province_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['city_town_id'];?>"><?php echo $rrow['city_town_name'];?></option>
												<?php
												
												}
												?>
											</select>
											<script>
												$("#citymun").change(
													function()
													{
														var id = $("#citymun").val();
														
														$.post( 
																'../php/sales.php',
																{
																	brgyui:id
																},
																function(data) {
																	$('#brgyui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['brgyui']))
{
	$id = $_REQUEST['brgyui'];
	?>
		<label for="age">BARANGAY</label>
											<select name = "brgy" id = "brgy" class="form-control" data-validation="required"
													data-validation-error-msg="Select BARANGAY">
												<option value = "" hidden "Selected"></option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_barangay where isdeleted = 0
												and city_town_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['barangay_id'];?>"><?php echo $rrow['barangay_name'];?></option>
												<?php
												
												}
												?>
											</select>
											
	<?php
}
if(isset($_REQUEST['sprovinceui']))
{
	$id = $_REQUEST['sprovinceui'];
	?>
		<label for="age">PROVINCE</label>
											<select name = "sprovince" id = "sprovince" class="form-control" data-validation="required"
													data-validation-error-msg="Select PROVINCE">
												<option value = "" hidden "Selected"></option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_province where isdeleted = 0
												and region_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['province_id'];?>"><?php echo $rrow['province_name'];?></option>
												<?php
												
												}
												?>
											</select>
											<script>
												$("#sprovince").change(
													function()
													{
														var id = $("#sprovince").val();
														
														$.post( 
																'../php/sales.php',
																{
																	scitymunui:id
																},
																function(data) {
																	$('#scitymunui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['scitymunui']))
{
	$id = $_REQUEST['scitymunui'];
	?>
		<label for="age">CITY/MUNICIPALITY</label>
											<select name = "scitymun" id = "scitymun" class="form-control" data-validation="required"
													data-validation-error-msg="Select CITY/MUNICIPALITY">
												<option value = "" hidden "Selected"></option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_city_town where isdeleted = 0
												and province_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['city_town_id'];?>"><?php echo $rrow['city_town_name'];?></option>
												<?php
												
												}
												?>
											</select>
											<script>
												$("#scitymun").change(
													function()
													{
														var id = $("#scitymun").val();
														
														$.post( 
																'../php/sales.php',
																{
																	sbrgyui:id
																},
																function(data) {
																	$('#sbrgyui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['sbrgyui']))
{
	$id = $_REQUEST['sbrgyui'];
	?>
		<label for="age">BARANGAY</label>
											<select name = "sbrgy" id = "sbrgy" class="form-control" data-validation="required"
													data-validation-error-msg="Select BARANGAY">
												<option value = "" hidden "Selected"></option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_barangay where isdeleted = 0
												and city_town_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['barangay_id'];?>"><?php echo $rrow['barangay_name'];?></option>
												<?php
												
												}
												?>
											</select>
											
	<?php
}
if(isset($_POST['lname']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
	$validCharNumber = strlen($validCharacters);
	 
	$length = 7;
	$result = "";
	$found = 1;
	
	while($found == 1)
	{
		for ($i = 0; $i < $length; $i++) {
			$index = mt_rand(0, $validCharNumber-1);
			$result .= $validCharacters[$index];
		}
		$found = mysqli_num_rows(mysqli_query($con,"Select * from customer_profile where result = '$result'"));
		if($found == 0)
			break;
		else
			$i = 0;
	}
	$check = mysqli_num_rows(mysqli_query($con,"Select * from customer_profile where lastname = '$lname' and firstname = '$fname' and mname = '$mname'"));
	
	if($check == 0)
	{
		mysqli_query($con,"insert into customer_profile set 
		reference_no = '$customer_type',
		customer_type = $ctype,
		result = '$result',
		firstname = '$fname',
		lastname = '$lname',
		middlename = '$mname',
		gender  = '$sex',
		birthdate = '$bdate',
		contact_no1 = '$contact',
		contact_no2 = '$contact2',
		email_address = '$email',
		social_media1  = '$fbname',
		created_by_fullname = '$_SESSION[ccraft]'
		");
		
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where result = '$result'"));
		
					if($row['customer_id'] == 0)
					{
						$total_enrolled = 1;
					}
					else
					{
						$total_enrolled = $row['customer_id'];
					}
					
					$count = "";
					if($total_enrolled < 10)
					{
						$count = "0000";
					}
					else if($total_enrolled >= 10 && $total_enrolled < 100)
					{
						$count = "000";
					}
					else if($total_enrolled >= 100 && $total_enrolled < 1000)
					{
						$count = "00";
					}
		$cno = $count.$row['customer_id'];
		
		mysqli_query($con,"update customer_profile set customer_no = '$cno' where customer_id = $row[customer_id]");
		
		mysqli_query($con,"insert into customer_address set 
		customer_id = $row[customer_id],
		street_name = '$street',
		barangay_id = $brgy,
		city_town_id = $citymun,
		province_id = $province,
		region_id = $region
		");
		mysqli_query($con,"insert into customer_shipping_address set 
		customer_id = $row[customer_id],
		street_name = '$sstreet',
		barangay_id = $sbrgy,
		city_town_id = $scitymun,
		province_id = $sprovince,
		region_id = $sregion
		");
		mysqli_query($con,"update customer_profile set result = '' where customer_id = $row[customer_id]");
		if($issave == 1)
		{
		?>
			<script>
				
				$.post( 
																'../php/sales.php',
																 { newtransactionui: '<?php echo $cno;?>' 
																 },
																 function(data) {
																	$('#newtransactionui').html(data);
																	
																	$("#modal").modal('hide');
																	$("#modalui").html('');
																 });
			</script>
		<?php
		}
		else
		{
			?>
				<script>
					$.post( 
																'../php/main.php',
																 { profilerefresh:1 
																 },
																 function(data) {
																	$('#profileui2').html(data);
																	$("#modal").modal("hide");
																 });
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				alert("Customers Full Name already exists in Customer Profile");
			</script>
		<?php
	}
	
}

if(isset($_REQUEST['smonitoringui']))
{
	?>
		<form id = "sfilterform">
		<div class="box box-warning">
			<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "sfdfrom" id = "sfdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "sfdto"  id = "sfdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4">
								
								 <div class="form-group">
										  <label for="age">APPROVAL STATUS:</label>
											
											<select id="sfapp" name = "sfapp" class="form-control" data-validation="required"
													data-validation-error-msg="Select APPROVAL STATUS">
													<option "Selected">ALL</option>
													<option value = "none">NO STATUS</option>
													<option value = "1">APPROVED</option>
													<option value = "2">DENIED</option>
											</select>										
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
										  <label for="age">SHIPPING STATUS</label>
											
											<select id="sfstatus" name = "sfstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select SHIPPING STATUS">
													<option  "Selected">ALL</option>
													<option value = "1">PENDING</option>
													<option value = "2">IN-PROCESS</option>
													<option value = "3">SHIPPED</option>
													<option value = "4">ORDER RECIEVED</option>
											</select>
											
								</div>
							</div>
							<div class="col-md-4">
								
								 <div class="form-group">
										  <label for="age">REMITTANCE STATUS:</label>
											
											<select id="sfremit" name = "sfremit" class="form-control" data-validation="required"
													data-validation-error-msg="Select REMITTANCE STATUS">
													<option "Selected">ALL</option>
													<option value = "1">REMITTED</option>
													<option value = "2">RECEIVED</option>
													<option value = "3">DENIED</option>
											</select>										
								</div>
							</div>
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">FILTER</button>
									<?php
									if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
									{
									?>
										<button class = "btn btn-warning btn-flat" id = "print">PRINT RESULT</button>
									<?php
									}
									?>
									</div>	
							</div>
						</div>
						
						
					
					
					
					<script>
							$("#print").click(
								function()
								{
									$.validate({
															form:'#sfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															//alert($("#sfapp").val());
																$.post( 
																	'../php/main.php',
																	{
																		psfdfrom:$("#sfdfrom").val(),
																		psfdto:$("#sfdto").val(),
																		psfstatus:$("#sfstatus").val(),
																		psfremit:$('#sfremit').val(),
																		psfapp:$("#sfapp").val()
																		
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
															form:'#sfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#sfilterform').serializeArray();
																 	$("#smonitoringui2").html(loading);
																	
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/main.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#smonitoringui2").html(data);
																					
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
			<div class="box-header with-border" id = "sheadui" style = "display:none;">
				<h3 class="box-title">CURRENT DATE TRANSACTIONS</h3>
			</div>
			<div class="box-body" id = "smonitoringui2">
				<?php smonitoring("","","ALL","ALL","ALL");?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['dmonitoringui']))
{
	
	?>
		<form id = "dfilterform">
		<div class="box box-warning">
			
			<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "dfdfrom" id = "dfdfrom" data-validation="date" 
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  
									value = "<?php echo date('Y-m-d');?>"
									data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "dfdto" id = "dfdto" data-validation="date" 
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
										  <label for="age">SHIPPING STATUS</label>
											
											<select name = "dfstatus" id = "dfstatus" class="form-control" data-validation="required"
													data-validation-error-msg="Select SHIPPING STATUS">
													<option  "Selected">ALL</option>
													<option value = "1">PENDING</option>
													<option value = "2">IN-PROCESS</option>
													<option value = "3">SHIPPED</option>
													<option value = "4">ORDER RECIEVED</option>
											</select>
											
								</div>
							</div>
							<div class="col-md-4">
								<?php
								$cquery = mysqli_query($con,"Select * from lup_courier where isdeleted = 0");
								?>
								 <div class="form-group">
										  <label for="age">COURIER</label>
											
											<select name = "sfcourier" id = "sfcourier" class="form-control" data-validation="required"
													data-validation-error-msg="Select SHIPPING STATUS">
													<option "Selected">ALL</option>
												<?php
												while($crow = mysqli_fetch_assoc($cquery))
												{
												?>												
													<option value = "<?php echo $crow['courier_id'];?>"><?php echo $crow['courier_name'];?></option>
													
												<?php
												}
												?>
											</select>
											
								</div>
								</div>
							</div>
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" >FILTER</button>
										<button class = "btn btn-warning btn-flat" id = "print">PRINT RESULT</button>
									</div>	
							</div>
						</div>
						
						
					
					
					
					<script>
							$("#print").click(
								function(e)
								{
									e.preventDefault();
									
									
														$.post( 
															'../php/main.php',
															 { 
																sdfdfrom:$("#dfdfrom").val(),
																sdfdto:$("#dfdto").val(),
																sdfshipping:$('#dfstatus').val(),
																sdfcourier:$("#sfcourier").val()
															 },
															 function(data) {
																$('#click').html(data);
																
															 });
								}
							);
										$.validate({
															form:'#dfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#dfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/main.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#dmonitoringui2").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
														
						</script>
						
			</div>
		</div>
		</form>
		<div class="box box-warning">
			<div class="box-header with-border" style = "display:none;">
				<h3 class="box-title" id = "dheadui">CURRENT DATE DELIVERIES</h3>
			</div>
			<div class="box-body" id = "dmonitoringui2">
				<?php dmonitoring("","",'ALL','ALL');?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['cartdel']))
{
	$id = $_REQUEST['cartdel'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from order_transaction_detail where order_transaction_detail_id = $id"));
	
	mysqli_query($con,"Update order_transaction_detail set isdeleted = 1 where order_transaction_detail_id = $id");
	
	show_cart($row['customer_id']);
}

if(isset($_REQUEST['cartdelall']))
{
	$id = $_REQUEST['cartdelall'];
	
	
	mysqli_query($con,"Update order_transaction_detail set isdeleted = 1 where customer_id = $id
	and order_transaction_id = 0 and isdeleted = 0");
	
	show_cart($id);
}
if(isset($_REQUEST['qtycompid']))
{
	$qtycompid = $_REQUEST['qtycompid'];
	$qtycomp= $_REQUEST['qtycomp'];
	$qtycount= $_REQUEST['qtycount'];
	
	//echo $qtycompid;
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from order_transaction_detail where order_transaction_detail_id = $qtycompid"));
	
	$total = $qtycomp * $row['product_price'];
	
	mysqli_query($con,"update order_transaction_detail set order_quantity = $qtycomp,
	total_order_amount = $total where order_transaction_detail_id = $qtycompid");
	
	echo number_format($total,2);
	
	$tqty = mysqli_fetch_assoc(mysqli_query($con,"Select sum(order_quantity) as tqty from order_transaction_detail where customer_id = $row[customer_id]
	and order_transaction_id = 0 and isdeleted = 0"));
	
	$tgrand = mysqli_fetch_assoc(mysqli_query($con,"Select sum(total_order_amount) as tqty from order_transaction_detail where customer_id = $row[customer_id]
	and order_transaction_id = 0 and isdeleted = 0"));
	
	//echo $tqty['tqty']." AAAA";
	?>
		<script>
			//alert('ok');
			$("#totalqtyui").html('<?php echo number_format($tqty['tqty'],2);?>');
			$("#gtotalui").html('<?php echo number_format($tgrand['tqty'],2);?>');
		</script>
	<?php
}

if(isset($_REQUEST['pricecompid']))
{
	$pricecompid = $_REQUEST['pricecompid'];
	$pricecomp= $_REQUEST['pricecomp'];
	
	//echo $qtycompid;
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from order_transaction_detail where order_transaction_detail_id = $pricecompid"));
	
	$total = $pricecomp * $row['order_quantity'];
	?>
		<script>
			//alert('<?php echo $total;?>');
		</script>
	<?php
	mysqli_query($con,"update order_transaction_detail set product_price = $pricecomp,
	total_order_amount = $total where order_transaction_detail_id = $pricecompid");
	
	echo number_format($total,2);
}
if(isset($_REQUEST['salereportui']))
{
	$user = get_user_id($_SESSION['ccraft']);
	
	$query = mysqli_query($con,"Select se_menu_report.* from se_menu_report, se_report_access where se_report_access.user_id = $user
	and se_report_access.menu_report_id = se_menu_report.menu_report_id and se_report_access.isdeleted = 0
	and se_menu_report.menu_report_module = 'SALES'");
	
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
			<div class="tab-pane" id="tab_3">
					<form id = "cmfilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">CUSTOMER MASTERLIST</h3>
						</div>
						
						<div class="box-body">
					
						<div class="row">
								<div class="col-md-4">
										  <div class="form-group">
										  <label for="age">REGION</label>
											<select name = "cmregion" id = "cmregion" class="form-control" data-validation="required"
													data-validation-error-msg="Select REGION">
												<option value = "" hidden "Selected"></option>
												<option value = "0">ALL</option>
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
												$("#cmregion").change(
													function()
													{
														var id = $("#cmregion").val();
														
														$.post( 
																'../php/sales.php',
																{
																	cmprovinceui:id
																},
																function(data) {
																	$('#provinceui').html(data);
																	
																});
													}
												);
											</script>
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group" id = "provinceui"> 
										  <label for="age">PROVINCE</label>
											<select id="cmprovince" name = "cmprovince" class="form-control">
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group" id = "citymunui">
										  <label for="age">CITY/MUNICIPALITY</label>
											<select id="cmcitymun" name = "cmcitymun" class="form-control" >
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group" id = "brgyui">
										  <label for="age">BARANGAY</label>
											<select id="cmbrgy" name = "cmbrgy" class="form-control">
												<option value = "" hidden "Selected"></option>
												
											</select>
											
										  </div>
								</div>
								<div class="col-md-4">
										  <div class="form-group">
										  <label for="age">CUSTOMER TYPE</label>
											<select name = "cmtype" id = "cmtype" class="form-control">
												<option value = "" hidden "Selected"></option>
												<option value = "0">ALL</option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_customer_type where isdeleted = 0");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['customer_type_id'];?>"><?php echo $rrow['customer_type_name'];?></option>
												<?php
												
												}
												?>
											</select>
										  </div>
								</div>
								
								<div class="col-md-4">
										  <div class="form-group">
										  <label for="age">INACTIVE/ACTIVE</label>
											<select name = "cmactive" id = "cmactive" class="form-control" >
												<option value = "" hidden "Selected"></option>
												<option value = "0">ALL</option>
												<option value = 1>ACTIVE</option>
												<option value = 2>INACTIVE</option>
											</select>
										  </div>
								</div>
								
								
								<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat btn-sm" id = "filtercm">FILTER</button>
									<button class = "btn btn-warning btn-flat btn-sm" id = "printcm">PRINT RESULT</button>
									<button class = "btn btn-danger btn-flat btn-sm" id = "viewall">VIEW ALL</button>	
									<button class = "btn btn-primary btn-flat btn-sm" id = "printall">PRINT ALL</button>			
									
								</div>
								</div>
								
							</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#viewall").click(
								function(e)
								{
									e.preventDefault();
									
									$('#cmasterlist').html(loading);	
									
									$.post( 
																	'../php/sales.php',
																	{
																		viewallcmaster:1
																	
																	},
																	function(data) {
																		$('#cmasterlist').html(data);	
																		
																	});
									
								}
							);
							
							$("#printall").click(
								function(e)
								{
									e.preventDefault();
									
									$.post( 
																	'../php/sales.php',
																	{
																		printallcmaster:1
																	
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
									
								}
							);
							$("#printcm").click(
								function()
								{
									$.validate({
															form:'#cmfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/sales.php',
																	{
																		pcmregion:$("#cmregion").val(),
																		pcmprovince:$("#cmprovince").val(),
																		pcmcitytown:$("#cmcitymun").val(),
																		pcmbrgy:$("#cmbrgy").val(),
																		pcmtype:$("#cmtype").val()
																	
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filtercm").click(
								function()
								{
										$.validate({
															form:'#cmfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#cmfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#cmasterlist").html(loading);
																			$.ajax({
																				url :  '../php/sales.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#cmasterlist").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "cmasterlist"></div>
              </div>
			  
              <div class="tab-pane" id="tab_1">
					<form id = "steamfilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">SALES ORDER BY TEAM</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "stdfrom" id = "stdfrom" data-validation="date"
									value = "<?php echo date('Y-m-d');?>"									
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "stdto"  id = "stdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filter">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printst">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printst").click(
								function()
								{
									$.validate({
															form:'#steamfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/main.php',
																	{
																		pstdfrom:$("#stdfrom").val(),
																		pstdto:$("#stdto").val()
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
															form:'#steamfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#steamfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/sales.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#salebyteamui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "salebyteamui"></div>
              </div>		
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
               <form id = "sitemfilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">SALES BY ITEM</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "sidfrom" id = "sidfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "sidto"  id = "sidto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filtersi">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printsi">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printsi").click(
								function()
								{
									$.validate({
															form:'#sitemfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'../php/main.php',
																	{
																		psidfrom:$("#sidfrom").val(),
																		psidto:$("#sidto").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filtersi").click(
								function()
								{
										$.validate({
															form:'#sitemfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#sitemfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  '../php/sales.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#salebyitemui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "salebyitemui"></div>
              </div>
              
            </div>
           
          </div>
        
	<?php
}
if(isset($_POST['stdfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php salebyteam($stdfrom,$stdto);?>
			</div>
		</div>
	<?php
}
if(isset($_POST['sidfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php salebyitem($sidfrom,$sidto,0);?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['saledelete']))
{
	foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 

	mysqli_query($con,"Update order_transaction set isdeleted = 1 where order_transaction_id = $saledelete");
	
	smonitoring($sdeldfrom, $sdeldto, $sdelstatus, $sdelrem, $sdelapp);
}
if(isset($_REQUEST['cmprovinceui']))
{
	$id = $_REQUEST['cmprovinceui'];
	?>
		<label for="age">PROVINCE</label>
											<select name = "cmprovince" id = "cmprovince" class="form-control" >
												<option value = "" hidden "Selected"></option>
												<option value = "0">ALL</option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_province where isdeleted = 0
												and region_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['province_id'];?>"><?php echo $rrow['province_name'];?></option>
												<?php
												
												}
												?>
											</select>
											<script>
												$("#cmprovince").change(
													function()
													{
														var id = $("#cmprovince").val();
														
														$.post( 
																'../php/sales.php',
																{
																	cmcitymunui:id
																},
																function(data) {
																	$('#citymunui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['cmcitymunui']))
{
	$id = $_REQUEST['cmcitymunui'];
	?>
		<label for="age">CITY/MUNICIPALITY</label>
											<select name = "cmcitymun" id = "cmcitymun" class="form-control">
												<option value = "" hidden "Selected"></option>
												<option value = "0">ALL</option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_city_town where isdeleted = 0
												and province_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['city_town_id'];?>"><?php echo $rrow['city_town_name'];?></option>
												<?php
												
												}
												?>
											</select>
											<script>
												$("#cmcitymun").change(
													function()
													{
														var id = $("#cmcitymun").val();
														
														$.post( 
																'../php/sales.php',
																{
																	cmbrgyui:id
																},
																function(data) {
																	$('#brgyui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['cmbrgyui']))
{
	$id = $_REQUEST['cmbrgyui'];
	?>
		<label for="age">BARANGAY</label>
											<select name = "cmbrgy" id = "cmbrgy" class="form-control">
												<option value = "" hidden "Selected"></option>
												<option value = "0">ALL</option>
												<?php
												$rquery = mysqli_query($con,"Select * from lup_barangay where isdeleted = 0
												and city_town_id = $id");
												while($rrow = mysqli_fetch_assoc($rquery))
												{
												?>
												<option value = "<?php echo $rrow['barangay_id'];?>"><?php echo $rrow['barangay_name'];?></option>
												<?php
												
												}
												?>
											</select>
											
	<?php
}

if(isset($_POST['cmregion']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php customer_masterlist($cmregion,$cmprovince,$cmcitymun,$cmbrgy,0,$cmtype,$cmactive);?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['pcmregion']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	
	$header = "";
	if($pcmregion != "")
	{
		$region = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_region where region_id = $pcmregion"));
		$pro = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_province where province_id = $pcmprovince"));
		$city = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_city_town where region_id = $pcmcitytown"));
		$brgy = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_barangay where region_id = $pcmbrgy"));
		
		$header = $region['region_name']." ".$pro['province_name']." ".$city['city_town_name']." ".$brgy['barangay_name'];
		
	}
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CUSTOMER MASTERLIST</h4>
			<h4 style = "text-align:center"><?php echo $header;?></h4>
			
			<?php
				 customer_masterlist($pcmregion,$pcmprovince,$pcmcitytown,$pcmbrgy,1,$pcmtype);
				
				$user = get_user_id($_SESSION['ccraft']);
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

if(isset($_POST['viewallcmaster']))
{
	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php customer_masterlist('','','','',0,"0");?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['printallcmaster']))
{
	$header = "";
	
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CUSTOMER MASTERLIST</h4>
			<h4 style = "text-align:center"><?php echo $header;?></h4>
			
			<?php
				customer_masterlist('','','','',1,"0");
				
				$user = get_user_id($_SESSION['ccraft']);
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
if(isset($_REQUEST['sales_unit']))
{
	$id = $_REQUEST['sales_unit'];
	?>
		<div class = "form-group">
													<label>UNIT:</label>
													<?PHP
													$pquery = mysqli_query($con,"Select DISTINCT(unit_id) from inv_transaction where 
													item_id = $id");
													
													?>
													<select name = "sunit" id = "sunit" class="form-control" data-validation="required"
																	data-validation-error-msg="Select UNIT"
																	>
																	<option  hidden "Selected"></option>
																<?php
																	while($prow = mysqli_fetch_assoc($pquery))
																	{
																		$unit = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $prow[unit_id]"));
																		
																?>
																	<option value = "<?php echo $unit['unit_id'];?>"><?php echo $unit['unit_description'];?></option>
																<?php
																	}
																?>
													</select>
		</div>		
	<?php
}
if(isset($_REQUEST['salesencodingui']))
{
	?>
		<div class="box box-warning">
						<div class="box-body">
							<div class="row">
										<div class="col-md-3">
										  
											<label for="lname">Search Name/Account Number:</label>
											<input type="text" id="key" name="key" class="form-control">
											<div id = "search_result"></div>
										 
										</div>
										<div class="col-md-3">
												<div class="form-group">
													<label>SALES AGENT:</label>
												
													<Select class = "form-control" id = "csagent" data-validation="required"
													data-validation-error-msg="Select SALES TEAM">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from se_user");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['user_id'];?>"><?php echo $prow['fullname'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										
										<div class="col-md-5" style = "padding-top:25px;">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "searchproceed">PROCEED</button>
											<button class = "btn btn-primary btn-flat" id = "newcustomer">NEW CUSTOMER</button>
										  </div>
										</div>
									
							</div>
							<div id = "newtransactionalert"></div>
						</div>
					</div>
					<div id = "newtransactionui"></div>
					
					<script>
									$("#newcustomer").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/sales.php',
																{
																	newcustomer:2
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									
									$("#key").keyup(
											function()
											{
													var s = $("#key").val();
														if(s != "")
														{
															$.post( 
															'../php/sales.php',
															 { eq: s },
															 function(data) {
																$('#search_result').html(data);
															 });
														}
														else
														{
															$('#search_result').html("");
														}
											});
											
									$("#searchproceed").click(
											function()
											{
													var account = $("#key").val();
													var agent = $("#csagent").val();
													
													
													if($.trim(account) != '' && agent != '')
													{
															$('#newtransactionui').html(loading);
														$.post( 
															'../php/sales.php',
															 { newtransactionui: account,
															newtransactionagent:agent
															 },
															 function(data) {
																$('#newtransactionui').html(data);
																
															 });
													}
													else
													{
														notify("Enter Key to Search and enter sales agent","#newtransactionalert");
													}
												
											});
											
								</script>
								
	<?php
}
?>
