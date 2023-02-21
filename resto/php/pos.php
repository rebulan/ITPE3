<?php
include('connect.php');
include("general.php");

if(isset($_REQUEST['cposui']))
{
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	$inv = mysqli_num_rows(mysqli_query($con,"Select * from lup_invoice_number where isdeleted = 0 and branch_id = $branch and pos_sales_id = 0"));
	if($inv != 0)
	{
	?>
	<h2>POS</h2>
	<div id = "posui">
	<div class="box">
			<div class="box-body">
				<form id = "cmsearchform" method = "POST">
					<div class = "row">
						<div class="col-md-3">
							 <div class="form-group">								
								<label for="age">SEARCH CUSTOMER:</label>
								<input type="text" id="ref" name="posref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
								data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
								<input type="hidden" id="clickval">
								<div id = "search_result"></div>												
							</div>
						</div>
						
						<div class="col-md-5" style = "padding-top:25px;">
								<div class = "form-group">
									<button class = "btn btn-warning btn-flat" id = "go">GO</button>
									<button class = "btn btn-primary btn-flat" id = "new">NEW CUSTOMER</button>
								</div>	
						</div>
					</div>
					<div id = "posalert"></div>
				</form>
				<script>
						//window.location.href = 'or.php?id=2';
										$("#new").click(
												function(e)
													{
																e.preventDefault();
																$("#modal2").modal("show");
																$("#modalbody2").css("min-width","75%");
																$("#modalui2").html("loading");
																	$.post( 
																	'php/customer.php',
																	 { 	newprofileui:1,
																		frompos:1
																	},
																	 function(data) {
																		$('#modalui2').html(data);
																	 });
																
													});
													
						$("#go").click(
							function()
							{
													$.validate({
																	form:'#cmsearchform',
																	validateOnBlur : false,
																	errorMessagePosition : 'top',
																	modules : 'security',
																	onSuccess : function($form) {
																	
																		 var formData = $('#cmsearchform').serializeArray();
																			
																			 //var formData = new FormData($('#regform')[0]);
																	
																					$.ajax({
																						url :  'php/pos.php',
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
						
													
											$("#ref").keyup(
													function()
													{
															$("#clickval").val("");
															var s = $("#ref").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
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
											
				</script>
			</div>
	</div>
	</div>
	<?php
	}
	else
	{
		?>
			<h2>No Invoice Number Available</h2>
		<?php
	}
}
if(isset($_POST['posref'])||isset($_REQUEST['posref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
		
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_no = '$posref'"));
	
	if(!empty($check))
	{?>
		<script>
												$.post( 
																	'php/pos.php',
																	 { 
																		posui2:'<?php echo $check["customer_id"];?>',
																		iscus:1

																		},
																		
																	 function(data) {
																		$('#posui').html(data);
																	 });
		</script>
		<?php
		
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#posalert");
			</script>
		<?php
	}
}

if(!empty($_REQUEST['cclassui']))
{
	pos_category($_REQUEST['cclassui']);
}

if(!empty($_REQUEST['positems']))
{
	//echo "AAAA";
	pos_items($_REQUEST['positems_sales_id'],'',$_REQUEST['positems'],'','');
}
if(!empty($_REQUEST['add_item_search']))
{
	pos_items($_SESSION['order'],'','','',$_REQUEST['add_item_search']);
}
if(!empty($_REQUEST['itemqnty']))
{
	$id = $_REQUEST['itemqnty'];
	$tr = $_SESSION['tran'];
	
	$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_profile.* from pos_sales, customer_profile where 
	pos_sales.pos_sales_id = $tr and pos_sales.customer_id = customer_profile.customer_id"));
	
	$cgroup = mysqli_fetch_assoc(mysqli_query($con,"Select lup_customer_type_group.pricing from lup_customer_type,lup_customer_type_group where 
	lup_customer_type.customer_type_id = $cus[customer_type_id] and lup_customer_type.customer_type_group = lup_customer_type_group.customer_type_group_id"));
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $id"));
	?>
	<script>
			$("#modal2").on('shown.bs.modal', function () {
				$('input:text:visible:first', this).focus();
			});  
	</script>
	<div class="box">
		<div class = "box-body">
			<form id = "posform">
				<div class="form-row" >
					<div class="col-md-8">
						<div class="form-group">
							<label for="service_description_edit">QUANTITY: &nbsp;</label>
							<input type = "hidden" name = "add_item_id" value = "<?php echo $id;?>">
							<input type="text" class = "form-control" id = "pos_qnty" name = "pos_qnty" data-validation="number"
							data-validation-error-msg="Enter Quantity" autofocus>
													
						</div>
					</div>
					<?php
					if($row['open_price'] == 1)
					{
					?>
					<div class="col-md-8">
						<div class="form-group">
							<label for="service_description_edit">PRICE: &nbsp;</label>
							
							<input type="text" class = "form-control" id = "pos_price" name = "pos_price" data-validation="number"
							data-validation-error-msg="Enter PRICE">
													
						</div>
					</div>
					<?php
					}
					else
					{
						$pr = 0;
						if($cgroup['pricing'] == 1)
							$pr = $row['item_price1'];
						elseif($cgroup['pricing'] == 2)
							$pr = $row['item_price2'];
						elseif($cgroup['pricing'] == 3)
							$pr = $row['item_price3'];
						?>
							<input type = "hidden" name = "pos_price" value = "<?php echo $pr;?>">
						<?php
					}
					?>
					<div class="col-md-8">
						<div class = "form-group">
													<label>UNIT:</label>
													<?PHP
													$pquery = mysqli_query($con,"Select DISTINCT(unit_id) from inv_transaction where 
													item_id = $id");
													
													?>
													<select name = "pos_unit" id = "pos_unit" class="form-control" data-validation="required"
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
					</div>
					
					<div class="col-md-3" style = "padding-top:25px;">
						<button class = "btn btn-success btn-flat">OK</button>							
					</div>
										
				</div>
			</form>
			<script>
								
									$.validate({
														form:'#posform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															$("#positemlist").html(loading);
															 var formData = $('#posform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/pos.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#positemlist").html(data);
																				
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
if(!empty($_POST['add_item_id']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$i = mysqli_fetch_assoc(mysqli_query($con, "Select * from pos_lup_item where item_code = '$add_item_id' and isdeleted = 0"));
	
	$frombr = 0;
	
	if(isset($_REQUEST['frombarcode']))
		$frombr = 1;
	if(!empty($i))
	{
		?>
			<script>
									$("#search_result").html('');
									$("#barcode").val('');
									$("#barcode").blur();
									$("#modal2").modal("show");
									$("#modalbody2").css("width","30%");
									$('#modalui2').html(loading);	
															$.post( 
																		'php/pos.php',
																		{
																			posaddqtyui:'<?php echo $i['item_id'];?>',
																			posaddsalesid:'<?php echo $add_sales_id;?>',
																			frombr:'<?php echo $frombr;?>'
																		},
																		function(data) {
																			$('#modalui2').html(data);	
																			
																		});
									 
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				alert("Item not found");
			</script>
		<?php
	}
}
if(!empty($_POST['posaddqtyui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$i = mysqli_fetch_assoc(mysqli_query($con, "Select * from pos_lup_item where item_id = '$posaddqtyui'"));
	?>
		
		<h2>ORDER - <?PHP ECHO $i['item_description'];?></h2>
		
		<?PHP
			$pquery = mysqli_query($con,"Select * from lup_variations where item_id = $posaddqtyui and isdeleted = 0");
			$vcount = mysqli_num_rows($pquery);	

			$ch = "";
			if($vcount == 1)
				$ch = "checked";
		if($vcount >  0)
		{
		?>
		<form id = "addorderform" method="POST">										
		<h4>VARIATIONS</h4>
		<div class="box box-warning">
			<div class="box-body">
			
				<div class="form-row" >
						
											<div class = "form-group">
											
												<table class = "table table-bordered table-hover table-sm">
												<?php
												$ctr = 1;
													while($irow = mysqli_fetch_assoc($pquery))
													{
												?>
													<tr>
														<td><label><input type="radio" <?php echo $ch;?> id = "pos_var" name = "pos_var" data-validation="required"
							data-validation-error-msg="Select Variation" value = "<?php echo $irow['variation_id'];?>"> <?php echo $irow['description'];?></label></td>
														<TD><?php echo number_format($irow['unit_price'],2);?></td>
													</tr>
												<?php
													}
												?>
												</table>
										
											</div>		
					
				</div>
			</div>
		</div>
		<?php
			$item_query = mysqli_query($con,"Select * from pos_lup_item where addon_id = $i[classification_id] and isdeleted = 0");
			$item_count = mysqli_num_rows($item_query);
		if($item_count > 0)
		{
		?>
				
		<h4>ADD ONS</h4>
		<div class="box box-warning">
			<div class="box-body">
				
				<div class="form-row" >
						<table class = "table table-bordered table-hover table-sm">
						<?php
						$ctr = 1;
							while($irow = mysqli_fetch_assoc($item_query))
							{
						?>
							<tr>
								<td><label><input type="checkbox" id = "pos_addon<?php echo $ctr;?>" name = "pos_addon<?php echo $ctr;?>" value = "<?php echo $irow['item_id'];?>"> <?php echo $irow['item_description'];?></label></td>
								<TD><?php echo number_format($irow['item_price1'],2);?></td>
							</tr>
						<?php
								$ctr++;
							}
						?>
						</table>
						<input type = "hidden" name = "addon_count" value = "<?php echo $ctr;?>">
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<h4>QUANTITY</h4>
		<div class="box box-warning">
			<div class="box-body">
			
	
						
						<div class="form-group">
							<input type = "hidden" name = "add_item_id2" value = "<?php echo $posaddqtyui;?>">
							<input type = "hidden" name = "add_sales_id2" value = "<?php echo $posaddsalesid;?>">
							<input type = "hidden" name = "add_frombr" value = "<?php echo $frombr;?>">
							<input type="number" class = "form-control" id = "pos_qnty2" name = "pos_qnty2" data-validation="number"
							data-validation-error-msg="Enter Quantity" autocomplete = "off" value = "1">				
						</div>
						
					

				
				<script>
					$('#modal4').on('shown.bs.modal', function () {
						$('#pos_qnty2').focus();
					})

					/*$("#pos_qnty2").keyup(
						function(e)
						{
							var k = e.which;
							
							if(k == 13)
							{
												$.post( 
																'php/pos.php',
																{
																	add_item_id2:<?php echo $posaddqtyui;?>,
																	pos_qnty2:$("#pos_qnty2").val(),
																	add_frombr:<?php echo $frombr;?>
																	
																},
																function(data) {
																	$('#click').html(data);
																	$("#modal4").modal('hide');
																	$("#barcode").focus();
																});
							}
						}
					);*/
			
											
							
				</script>
			</div>
		</div>
		<button class = "btn btn-success btn-flat btn-block">ADD</button>
		</form>
			<script>
				$.validate({
														form:'#addorderform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
																		var formData = $('#addorderform')[0];
																		
																			$.ajax({
																						url: 'php/pos.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#click").html(data);
																					
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
	<?php
		}
		else{
			?>
				<h4>NO PRICE DETAILS</H4>
			<?php
		}
}
if(!empty($_REQUEST['add_item_id2']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$i = mysqli_fetch_assoc(mysqli_query($con, "Select * from pos_lup_item where item_id = '$add_item_id2' and isdeleted = 0"));
	if(!empty($i))
	{
		$it = mysqli_fetch_assoc(mysqli_query($con,"Select DISTINCT(inv_transaction.transaction_no) as t,SUM(inv_transaction.quantity) as tot, inv_transaction.unit_cost,inv_transaction.markup from inv_transaction, inv_delivery_details where inv_transaction.item_id = $i[item_id] and inv_transaction.isdeleted = 0
		and inv_transaction.delivery_id = inv_delivery_details.delivery_id
		group by transaction_no having tot > 0 order by inv_delivery_details.delivery_date"));
		if($i['inventory_item'] == 0)
		{
			$it = "1";
		}
		if(!empty($it))
		{
			$var = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_variations where variation_id = $pos_var"));
			//$price = $it['unit_cost']+($it['unit_cost']*($it['markup']/100));
			$price = $var['unit_price'];
			//$price = ceil($price);
			$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $_SESSION[tran]"));
			$class = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $i[classification_id]"));
			$dep = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_department where department_id = $i[department_id]"));
			$cat = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_category where category_id = $i[category_id]"));
			
			$sub = $price*$pos_qnty2;
			
			$user = get_user_id($_SESSION['c_craft']);
			$icheck = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales_detail where pos_sales_id = $_SESSION[tran] and item_id = $add_item_id2 and isdeleted = 0"));
			$icheck = "";
		
			if(empty($icheck))
			{
				$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
				$validCharNumber = strlen($validCharacters);
			 
				$length = 10;
				$result2 = "";
		
				for ($ii = 0; $ii < $length; $ii++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result2 .= $validCharacters[$index];
				}
				
				$save = mysqli_query($con,"insert into pos_sales_detail set
				sales_invoice_number = '',
				pos_sales_id = '$add_sales_id2',
				branch_id = '$row[branch_id]',
				unit_id = $i[unit_id],
				item_id = '$i[item_id]',
				item_code = '$i[item_code]',
				item_description = '$i[item_description]',
				item_short_description = '$i[item_short_description]',
				category_description = '$cat[category_description]',
				department_description = '$dep[department_description]',
				classification_description = '$class[classification_description]',
				item_cost = '$i[item_cost1]',
				item_price = $price,
				quantity = $pos_qnty2,
				grand_total = '$sub',
				order_time = NOW(),
				remarks = 'POS_SALES_DETAIL',
				pos_key = '$result2',
				created_by = '$user',
				isdeleted = 0
				");
				
				$c = 1;
				
				$posd = mysqli_fetch_assoc(mysqli_query($con,"Select pos_sales_detail_id from pos_sales_detail where pos_key = '$result2'"));
				mysqli_query($con,"Update pos_sales_detail set pos_key = '' where pos_key = '$result2'");
				
				while($c <= $addon_count)
				{
					if(isset($_POST['pos_addon'.$c]))
					{
						$item_id = $_POST['pos_addon'.$c];
						$i = mysqli_fetch_assoc(mysqli_query($con, "Select * from pos_lup_item where item_id = '$item_id'"));
						$sub = $i['item_price1']*$pos_qnty2;
						
						mysqli_query($con,"insert into pos_sales_detail set
						sales_invoice_number = '',
						pos_sales_id = '$_SESSION[tran]',
						branch_id = '$row[branch_id]',
						unit_id = $i[unit_id],
						addon_id = $posd[pos_sales_detail_id],
						item_id = '$i[item_id]',
						item_code = '$i[item_code]',
						item_description = '$i[item_description]',
						item_short_description = '$i[item_short_description]',
						category_description = '',
						department_description = '',
						classification_description = '',
						item_cost = '',
						item_price = $i[item_price1],
						quantity = $pos_qnty2,
						grand_total = '$sub',
						order_time = NOW(),
						remarks = 'POS_SALES_DETAIL',
						created_by = '$user',
						isdeleted = 0
						");
					}
					$c++;
				}
				if($save)
				{
					?>
					<script>
						$("#modal2").modal('hide');
					</script>
					<?php
				}
				else
				{
					?>
					<script>
						alert("Error Adding Item");
					</script>
					<?php
				}
			}
			else
			{
				$save = mysqli_query($con,"Update pos_sales_detail set
				item_cost = '$i[item_cost1]',
				item_price = '$i[item_price1]',
				quantity = '$pos_qnty',
				grand_total = '$sub',
				order_time = NOW(),
				created_by = '$user'
				where pos_sales_detail_id = $icheck[pos_sales_detail_id]
				");
				
				if($save)
				{
					?>
					<script>
						$("#modal2").modal('hide');
					</script>
					<?php
				}
				else
				{
					?>
					<script>
						alert("Error Adding Item");
					</script>
					<?php
				}
			}
				
				?>
				<script>
											$("#opos_otype").focus();
											
											$.post( 
																		 'php/pos.php',
																		 {
																			 totalquantity:1
																		},
																		 function(data) {
																			$('#totalqntyui').html(data);
																		
																		 });
											$.post( 
																		 'php/pos.php',
																		 {
																			 subtotal:1
																		},
																		 function(data) {
																			$('#totalui').html(data);
																		
																		 });
					
											$.post( 
																		 'php/pos.php',
																		 {
																			 ototalquantity:1
																		},
																		 function(data) {
																			$('#ototalqntyui').html(data);
																		
																		 });
											$.post( 
																		 'php/pos.php',
																		 {
																			 osubtotal:1
																		},
																		 function(data) {
																			$('#ototalui').html(data);
																		
																		 });
																		 
				</script>
			<?php
		}
		else
		{
			?>
				<script>
					alert("Stock Record Not Found");
					$("#search_result").html('');
				</script>
				<?php
		}
		
	}
	else{
		?>
				<script>
					alert("Item Not Found");
				</script>
				<?php
	}
	
				if($add_frombr == 1)
				{
					?>
						<script>
							$('#positemlist').html(loading);
							 $.post( 
																		 'php/pos.php',
																		 {
																			 retitemlist:1
																		},
																		 function(data) {
																			$('#positemlist').html(data);
																			$("#itemtitle").html('ITEMS LIST');
																			$("#item-toggle").html('');
																		 });
						</script>
					<?php
				}
	
}
if(!empty($_REQUEST['positemdel']))
{
	$id = $_REQUEST['positemdel'];
	$from = $_REQUEST['positemdelfrom'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select pos_sales_id from pos_sales_detail where pos_sales_detail_id = $id"));
	$del = mysqli_query($con,"Update pos_sales_detail set isdeleted = 1 where pos_sales_detail_id = $id");
	
	if(!$del)
	{
		?>
			<script>
				alert("Error deleting Item, Please Contact System Administrator");
			</script>
		<?php
	}
	?>
	<script>
			 $.post( 
																 'php/pos.php',
																 {
																	 totalquantity:1
																},
																 function(data) {
																	$('#totalqntyui').html(data);
																
																 });
			 $.post( 
																 'php/pos.php',
																 {
																	 subtotal:1
																},
																 function(data) {
																	$('#totalui').html(data);
																
																 });
		</script>
	<?php
	pos_item_list($row['pos_sales_id'],0,$from);
	
}
if(!empty($_REQUEST['posdiscountdel']))
{
	$id = $_REQUEST['posdiscountdel'];
	
	$del = mysqli_query($con,"Update pos_sales_detail set isdeleted = 1 where pos_sales_detail_id = $id");
	update_change($_SESSION['tran']);
	
	if(!$del)
	{
		?>
			<script>
				alert("Error deleting discount, Please Contact System Administrator");
			</script>
		<?php
	}
	?>
	<script>
															$.post( 
																 'php/pos.php',
																 {
																	 subtotal:1
																},
																 function(data) {
																	$('#totalui').html(data);
																
																 });
															
															$.post( 
																'php/pos.php',
																{
																	changeuii:1
																	
																},
																function(data) {
																	$('#changeui').html(data);
																	
																});
																
		</script>
	<?php
	discountlist($_SESSION['tran'],0);
	
}

if(!empty($_REQUEST['col_pay_delete']))
{
	$id = $_REQUEST['col_pay_delete'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_sales_income where sales_income_id = $id"));
	
	$del = mysqli_query($con,"Update ledger_sales_income set isdeleted = 1, datetime_deleted = NOW(), deleted_by_fullname = '$_SESSION[c_craft]' where sales_income_id = $id");
	
	if(!$del)
	{
		?>
			<script>
				alert("Error deleting Payment, Please Contact System Administrator");
			</script>
		<?php
	}
	collection_settlement($row['pos_sales_id'],0);
}

if(!empty($_REQUEST['positemedit']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$price = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales_detail
		where pos_sales_detail_id = $positemedit"));
	
/*$i = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $price[item_id]"));
	$p = 0;
	if($i['item_price2'] != 0)
	{
		if($positemeditval >= $i['item_price2'])
			$p = $i['item_price3'];
		else
			$p = $i['item_price1'];
	}
	else
	{
		$p = $i['item_price'];
	}
	*/
	$newsub = 0;
	$newsub = $positemeditval * $price['item_price'];
	
	$del = mysqli_query($con,"Update pos_sales_detail set quantity = $positemeditval, grand_total = $newsub where pos_sales_detail_id = $positemedit ");
	
	if(!$del)
	{
		/*$newval = mysqli_fetch_assoc(mysqli_query($con,"Select (quantity * item_price) as newval from pos_sales_detail
		where pos_sales_detail_id = $positemedit and isdeleted = 0"));
		$totsub = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(sub_total) as total from pos_sales_detail
		where pos_sales_detail_id = $positemedit and isdeleted = 0"));*/
		
		?>
			<script>
				alert("Error Updating Item, Please Contact System Administrator");
			</script>
		<?php
	}
	?>
		<script>
			 $.post( 
																 'php/pos.php',
																 {
																	 totalquantity:1
																},
																 function(data) {
																	$('#totalqntyui').html(data);
																
																 });
			 $.post( 
																 'php/pos.php',
																 {
																	 subtotal:1
																},
																 function(data) {
																	$('#totalui').html(data);
																
																 });
			 $.post( 
																 'php/pos.php',
																 {
																	 ototalquantity:1
																},
																 function(data) {
																	$('#ototalqntyui').html(data);
																
																 });
			 $.post( 
																 'php/pos.php',
																 {
																	 osubtotal:1
																},
																 function(data) {
																	$('#ototalui').html(data);
																
																 });
																 
		</script>
	<?php
	pos_item_list($price['pos_sales_id'],0,$positemeditfrom);
}
if(!empty($_REQUEST['posidisedit']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$price = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales_detail
		where pos_sales_detail_id = $posidisedit"));
	$newsub = 0;
	$titemdis = 0;
	if($posidisper  == 1)
	{
		$newsub = ($price['item_price']*$price['quantity'])-($price['grand_total']*($posidiseditval/100));
		$titemdis = $price['grand_total']*($posidiseditval/100);
	}
	else{
		$newsub = ($price['item_price']*$price['quantity'])-$posidiseditval;
		$titemdis = $posidiseditval;
	}
	
	$del = mysqli_query($con,"Update pos_sales_detail set total_item_discount = $titemdis, item_discount = $posidiseditval, ispercent = $posidisper , grand_total = $newsub where pos_sales_detail_id = $posidisedit ");
	
	update_change($_SESSION['tran']);
	
	if(!$del)
	{
		?>
			<script>
				alert("Error Updating Item, Please Contact System Administrator");
			</script>
		<?php
	}
	?>
		<script>
															$.post( 
																 'php/pos.php',
																 {
																	 totalquantity:1
																},
																 function(data) {
																	$('#totalqntyui').html(data);
																
																 });
															$.post( 
																 'php/pos.php',
																 {
																	 subtotal:1
																},
																 function(data) {
																	$('#totalui').html(data);
																
																 });
															$.post( 
																'php/pos.php',
																{
																	changeuii:1
																	
																},
																function(data) {
																	$('#changeui').html(data);
																	
																});
		</script>
	<?php
	pos_item_list($_SESSION['tran'],0);
}

if(!empty($_REQUEST['pospriceedit']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$price = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales_detail
		where pos_sales_detail_id = $pospriceedit"));
	
	$newsub = 0;
	$newsub = $pospriceeditval * $price['quantity'];
	
	$del = mysqli_query($con,"Update pos_sales_detail set item_price = $pospriceeditval, grand_total = $newsub where pos_sales_detail_id = $pospriceedit ");
	
	if(!$del)
	{
		
		?>
			<script>
				alert("Error Updating Item, Please Contact System Administrator");
			</script>
		<?php
	}
	?>
		<script>
			 $.post( 
																 'php/pos.php',
																 {
																	 totalquantity:1
																},
																 function(data) {
																	$('#totalqntyui').html(data);
																
																 });
			 $.post( 
																 'php/pos.php',
																 {
																	 subtotal:1
																},
																 function(data) {
																	$('#totalui').html(data);
																
																 });
		</script>
	<?php
	pos_item_list($_SESSION['tran'],0);
}
if(!empty($_REQUEST['itemclassui']))
{
	pos_items($_REQUEST['itemclassui'],'','','');
}

if(!empty($_REQUEST['itemdepui']))
{
	pos_items("","",$_REQUEST['itemdepui'],"");
}
if(!empty($_REQUEST['totalquantity']))
{
	$id = $_SESSION['tran'];
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as total from pos_sales_detail where 
	pos_sales_id = $id and isdeleted = 0"));
	IF(!empty($total))
	{
		?>
		<h2>TOTAL QUANTITY: <?php echo number_format($total['total'],2);?></h2>
		<?php
	}
	else
	{
		?>
		<h2>TOTAL QUANTITY: 0.00</h2>
		<?php

	}
}
if(!empty($_REQUEST['subtotal']))
{
	$id = $_SESSION['tran'];
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where 
	pos_sales_id = $id and isdeleted = 0"));
	IF(!empty($total))
	{
		?>
	<h3>TOTAL QUANTITY: <?php echo number_format($total['total'],2);?></h3>
		<?php
	}
	else
	{
		?>
		<h3>TOTAL QUANTITY: 0.00</h3>
		<?php

	}
}
if(!empty($_REQUEST['ototalquantity']))
{
	$id = $_SESSION['order'];
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as total from pos_sales_detail where 
	pos_sales_id = $id and isdeleted = 0"));
	IF(!empty($total))
	{
		?>
		TOTAL QUANTITY: <?php echo number_format($total['total'],2);?>
		<?php
	}
	else
	{
		?>
		TOTAL QUANTITY: 0.00
		<?php

	}
}
if(!empty($_REQUEST['osubtotal']))
{
	$id = $_SESSION['order'];
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where 
	pos_sales_id = $id and isdeleted = 0"));
	IF(!empty($total))
	{
		?>
	TOTAL QUANTITY: <?php echo number_format($total['total'],2);?>
		<?php
	}
	else
	{
		?>
		TOTAL QUANTITY: 0.00
		<?php

	}
}

if(!empty($_REQUEST['fin']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$id = $_SESSION['tran'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $id"));
		if($fiscus == 0)
		{
			$check = 0;
		}
		else
		{
			$check = 1;
		}
	
	$string = "Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where
	lup_settlement_type.settlement_type_id  = settings_settlement_mapping.settlement_type_id and lup_settlement_type.visible = 1";
	if($check != 0)
	{
		$string = $string." and (settings_settlement_mapping.charge_to_customer = 0 or settings_settlement_mapping.charge_to_customer = 1)";
	}
	else
	{
		$string = $string." and (settings_settlement_mapping.charge_to_customer = 0)";
	}
	$def = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where isdefault = 1 and isdeleted = 0"));
	//echo $string;
	//$total = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales_detail where 
	//pos_sales_id = $id and isdeleted = 0"));
	$total = 1;
	if($total != 0)
	{
		?>
			<div class="box" style = "margin-top:-5px;">
						
						<div class="box-body" id = "settletui">
							<form id = "finform" method = "POST">
							
								
										<div class = "row">
											<div class="col-md-4">
													<div class="form-group">
														<label>DATE:</label>
													
														<input type = "date" class = "form-control" name = "pos_date" data-validation="required"
														data-validation-error-msg="Enter Date" value = "<?php echo date('Y-m-d');?>" readonly>
															
													
													</div>
											</div>
											
											<div class="col-md-3">
													<div class="form-group">
														<label>Order Type:</label>
													
														
														<Select class = "form-control" name = "pos_otype" data-validation="required"
															data-validation-error-msg="Select Department">
																<option value = "" hidden "Selected"> </option>
															<?php
															$pmquery = mysqli_query($con,"Select * from pos_lup_order_type where isdeleted = 0");
															while($prow = mysqli_fetch_assoc($pmquery))
															{
															?>
																<option value = "<?php echo $prow['order_type_id'];?>"><?php echo $prow['order_type_description'];?></option>
															
															<?php
															}
															?>
														</select>
													</div>
											</div>
											<div class="col-md-4" style = "display:none;">
													<div class="form-group">
														<label>Remarks:</label>
													
														<input type = "text" class = "form-control" name = "pos_rem">
															
													
													</div>
											</div>
											
											
											<div class="col-md-3">
													<div class="form-group" style = "padding:25px;">
														<button class = "btn btn-primary btn-flat btn-sm" id = "final">SAVE TRANSACTION</button>
													</div>
											</div>
										</div>
									
							
								
							</form>
							<script>
								$("#final").click(
									function()
									{
										
										
											$.validate({
														form:'#finform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															var r = confirm("Confirm Action");
										
															if(r == true)
															{
															
															 var formData = $('#finform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/pos.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#click").html(data);
																			}
																		});
															}

														  return false; // Will stop the submission of the form
														},
													});
										
									}
								);
											
							</script>
							
						</div>
				</div>
				<div class="box" style = "margin-top:-15px;">
						<div class="box-body" >
							<form id = "possettleform">
								<div class = "row">
											<div class="col-md-4">
													<div class="form-group">
														<label>Settlement Type:</label>
														
														<Select class = "form-control" name = "pos_settle" data-validation="required"
														data-validation-error-msg="Select Settlement Type">
															<option value = "<?php echo $def['settlement_type_id'];?>" hidden "Selected"><?php echo $def['settlement_description'];?></option>
														<?php
														$pmquery = mysqli_query($con,$string);
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
											<div class="col-md-3">
													<div class="form-group" style = "padding:25px;">
														<button class = "btn btn-success btn-flat" id = "select">ADD</button>
													
														
													</div>
													
											</div>
											<div class="col-md-2>
													
													<div class="form-group" style = "padding:25px;">
														
														<button class = "btn btn-warning btn-flat" id = "dis">PAYMENT DISCOUNT</button>
														
													</div>
											</div>
								</div>
							</form>
							<script>
							$("#dis").click(
								function(e)
								{
									e.preventDefault();
									$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'php/pos.php',
																{
																	discountui:1
																},
																function(data) {
																	$('#modalui').html(data);		
																});
																
								}
							);
							$("#select").click(
								function(e)
								{
								
										$.validate({
														form:'#possettleform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#possettlementui").html(loading);
															
															 var formData = $('#possettleform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/pos.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#possettlementui").html(data);
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
				
			
							
							
						
				
				<div class="box" style = "margin-top:-15px;">
					<div class="box-body" id = "possettlementui" style = "overflow:auto;min-height:350px;">
						<?PHP pos_settlement_list($_SESSION['tran'],0);?>
					</div>
				</div>
		<?php
	}
	else
	{
		?>
		<h2>No Transaction Listed</h2>
		<?php
	}
}
if(isset($_REQUEST['discountui']))
{
	?>
		<div class="box" style = "margin-top:-15px;">
						<div class="box-body" >
							<form id = "discountform">
								<div class = "row">
											
											 <div class="col-md-5">
												<div class="form-group" id = "disdesui">
													<label for="">Discount Description: </label>
													<select class = "form-control" id = "dis_des" name = "dis_des">
														<option hidden "selected" value = "none">&nbsp;</option>
														<option value = "NEW">NEW..</option>
														<?php
															$mmquery = mysqli_query($con, "Select DISTINCT(item_description) as s from pos_sales_detail where
															discount != 0 and isdeleted = 0 and sales_invoice_number !=''");
															while($mmrow = mysqli_fetch_assoc($mmquery))
															{
														?>
																<option><?php echo $mmrow['s'];?></option>
														<?php
															}
														?>
													</select>
												</div>
											</div>
											<script>
													$("#dis_des").change(
													function()
													{
															
															var s = $("#dis_des").val();
																if(s == "NEW")
																{
																	$.post( 
																	'php/pos.php',
																	 { newdisdes:1 },
																	 function(data) {
																		$('#disdesui').html(data);
																	 });
																}
															
													});
													
													/*$("#dis_des").keyup(
													function()
													{
															
															var s = $("#dis_des").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
																	 { discountq: s },
																	 function(data) {
																		$('#search_result').html(data);
																	 });
																}
																else
																{
																	$('#search_result').html("");
																}
													});*/
													
											</script>
											<div class="col-md-3" >
													<div class="form-group">
														<label>Amount:</label>
														<input type = "text" class = "form-control" name = "dis_amount" 
														data-validation="number" data-validation-allowing="float"
														data-validation-error-msg="Enter Discount Amount">
													</div>
											</div>
											<div class="col-md-2" style = "padding-top:25px;">
												<div class="form-group">
													<label>
													<input type="checkbox" id = "dis_percent" name = "dis_percent" 
													value = "1">
													%</label>
												</div>
											</div>
											<div class="col-md-2">
													<div class="form-group" style = "padding:25px;">
														<button class = "btn btn-success btn-flat" id = "disadd">ADD</button>
													</div>
											</div>
								</div>
							</form>
							<script>
											$.validate({
														form:'#discountform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#discountlistui").html(loading);
															
															 var formData = $('#discountform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/pos.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#discountlistui").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
							
						</div>
				</div>
			<div id = "discountalert"></div>
			<div class="box" style = "margin-top:-15px;">
						<div class="box-body" id = "discountlistui">
							<?PHP echo discountlist($_SESSION['tran'],0);?>
						</div>
			</div>
	<?php
}
if(isset($_REQUEST['newdisdes']))
{
	?>
		<label>New Discount Description:</label>
														<input type = "text" class = "form-control" id = "dis_des" name = "dis_des" 
														data-validation="required"
														data-validation-error-msg="Enter Discount Description">
		<button class = "btn btn-danger btn-xs" id = "canceldes">CANCEL</BUTTON>
		<script>
							$("#dis_des").focus();
										$("#canceldes").click(
													function(e)
													{
														e.preventDefault();
															
														
																	$.post( 
																	'php/pos.php',
																	 { selectdes:1 },
																	 function(data) {
																		$('#disdesui').html(data);
																	 });
																
															
													});	
		</script>
	<?php
}
if(isset($_REQUEST['selectdes']))
{
	?>
		<label for="">Discount Description: </label>
													<select class = "form-control" id = "dis_des" name = "dis_des">
														<option hidden "selected" value = "none">&nbsp;</option>
														<option value = "NEW">NEW..</option>
														<?php
															$mmquery = mysqli_query($con, "Select DISTINCT(item_description) as s from pos_sales_detail where
															discount != 0 and isdeleted = 0 and sales_invoice_number !=''");
															while($mmrow = mysqli_fetch_assoc($mmquery))
															{
														?>
																<option><?php echo $mmrow['s'];?></option>
														<?php
															}
														?>
													</select>	
		<script>
			$("#dis_des").change(
													function()
													{
															
															var s = $("#dis_des").val();
																if(s == "NEW")
																{
																	$.post( 
																	'php/pos.php',
																	 { newdisdes:1 },
																	 function(data) {
																		$('#disdesui').html(data);
																	 });
																}
															
													});
		</script>
	<?php
}

if(isset($_POST['dis_des']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where pos_sales_id = $_SESSION[tran]
	and discount = 0 and isdeleted = 0"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	$amount = 0;
	$percent = 0;
	if(isset($_POST['dis_percent']))
	{
		$amount = $total['total']*($dis_amount/100);
		$percent = 1;
	}
	else{
		$amount = $dis_amount;
	}

	
			$save = mysqli_query($con,"insert into pos_sales_detail set
				sales_invoice_number = '',
				pos_sales_id = '$_SESSION[tran]',
				branch_id = '$branch',
				unit_id = 0,
				item_id = '0',
				discount = $dis_amount,
				ispercent = $percent,
				item_code = '',
				item_description = '$dis_des',
				item_short_description = '$dis_des',
				category_description = 0,
				department_description = 0,
				classification_description = 0,
				item_cost = 0,
				item_price = 0,
				quantity = 0,
				grand_total = $amount*-1,
				order_time = NOW(),
				remarks = 'POS_SALES_DETAIL',
				created_by = '$user',
				isdeleted = 0
				");
		
		update_change($_SESSION['tran']);
		
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> Discount  Added","#discountalert");
														$.post( 
																 'php/pos.php',
																 {
																	 subtotal:1
																},
																 function(data) {
																	$('#totalui').html(data);
																
																 });
														$.post( 
																'php/pos.php',
																{
																	changeuii:1
																	
																},
																function(data) {
																	$('#changeui').html(data);
																	
																});
																
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Adding Discount, Contact the System Administrator", "#discountalert");
			</script>
		<?php
		}
	discountlist($_SESSION['tran'],0);
}
if(isset($_POST['pos_settle']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $pos_settle"));
	
	$ocheck = 0;
	
	if(!empty($_POST['colrefno']))
	{
		$ocheck = mysqli_num_rows(mysqli_query($con,"Select * from ledger_receivable where transaction_source_number = '$colrefno'"));
	}
	
	if($ocheck == 0)
	{
	?>
	<script>
		$("#possetamount").focus();
	</script>
							<form id = "possettletypeform">
								<div class = "row">
												<div class="col-md-5">
													<div class = "form-group">
														<label>AMOUNT:</label>
														<input type = "hidden" name = "possetid" value = "<?php echo $pos_settle;?>">
														<?php
														if(isset($_POST['add_payment_id']))
														{
															?>
															<input type = "hidden" name = "posaddpaymentid" value = "<?php echo $add_payment_id;?>">
															<input type = "hidden" name = "posaddpaymentposid" value = "<?php echo $add_payment_pos_id;?>">
															<input type = "hidden" name = "posaddpaymentctr" value = "<?php echo $add_payment_ctr;?>">
															<input type = "hidden" name = "posaddpaymentsettle" value = "<?php echo $add_payment_settle_amount;?>">
															<input type = "hidden" name = "posaddpaymentremarks" value = "<?php echo $add_payment_remarks;?>">
															
															<input type = "hidden" name = "posorno" value = "<?php echo $colrefno;?>">
															<?php
														}
														?>
														 <input type="text" class="form-control" name = "possetamount" id = "possetamount" data-validation="number"
																		data-validation-error-msg="Enter Amount"
																		data-validation-allowing="float">
													</div>		
												</div>
												<?php
												if($row['with_reference_no1'] == 1)
												{
												?>
												<div class="col-md-3">
													<div class = "form-group">
														<label>REFERENCE NO1:</label>
														 <input type="text" class="form-control" name = "possetref1" id = "possetref1" data-validation="required"
																		data-validation-error-msg="Enter REFERENCE NO1"
																		>
													</div>		
												</div>
												<?php
												}
												if($row['with_reference_no2'] == 1)
												{
												?>
												<div class="col-md-3">
													<div class = "form-group">
														<label>REFERENCE NO2:</label>
														 <input type="text" class="form-control" name = "possetref2" id = "possetref2" data-validation="required"
																		data-validation-error-msg="Enter REFERENCE NO2"
																		>
													</div>		
												</div>
												<?php
												}
												if($row['with_reference_description1'] == 1)
												{
												?>
												<div class="col-md-3">
													<div class = "form-group">
														<label>REFERENCE DESCRIPTION1:</label>
														 <input type="text" class="form-control" name = "possetrefdes1" id = "possetrefdes1" data-validation="required"
																		data-validation-error-msg="Enter REFERENCE DESCRIPTION1"
																		>
													</div>		
												</div>
												<?php
												}
												if($row['with_reference_description2'] == 1)
												{
												?>
												<div class="col-md-3">
													<div class = "form-group">
														<label>REFERENCE DESCRIPTION 2:</label>
														 <input type="text" class="form-control" name = "possetrefdes2" id = "possetrefdes2" data-validation="required"
																		data-validation-error-msg="Enter REFERENCE DESCRIPTION 2"
																		>
													</div>		
												</div>
												<?php
												}
												if($row['with_proof_of_payment1'] == 1)
												{
												?>
												<div class="col-md-3">
													<div class = "form-group">
														<label>PROOF OF PAYMENT 1:</label>
														 <input type="file" class="form-control" name = "possetproof1" id = "possetproof1" data-validation="required"
																		data-validation-error-msg="Enter PROOF OF PAYMENT 1"
																		>
													</div>		
												</div>
												<?php
												}
												if($row['with_proof_of_payment2'] == 1)
												{
												?>
												
												<div class="col-md-3">
													<div class = "form-group">
														<label>PROOF OF PAYMENT 2:</label>
														 <input type="file" class="form-control" name = "possetproof2" id = "possetproof2" data-validation="required"
																		data-validation-error-msg="Enter PROOF OF PAYMENT 2"
																		>
													</div>		
												</div>
												<?php
												}
												?>
											<div class="col-md-7">
													<div class="form-group" style = "padding:25px;">
														<button class = "btn btn-success btn-flat" id = "savee">SAVE</button>
														<button class = "btn btn-primary btn-flat" id = "cancell">CANCEL</button>
													</div>
											</div>
								</div>
							</form>
							<?php
							$dest = "";
							if(isset($_POST['add_payment_id']))
							{
								$dest = "click";
								?>
									<script>
										$("#cancell").click(
											function(e)
											{
												e.preventDefault();
												
												$("#modalui").html(loading);
															$.post( 
																'php/pos.php',
																{
																	addpaymentui:'<?php echo $add_payment_pos_id;?>',
																	addpaymentsettle:'',
																	addpaymentctr:'0',
																	addpaymentsettle2:'0',
																	addpaymentremarks:'FROM_COLLECTION'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
											}
										);
									</SCRIPT>
								<?PHP
							}
							else
							{
								$dest = "possettlementui";
								?>
									<script>
										$("#cancell").click(
											function(e)
											{
												e.preventDefault();
												$('#possettlementui').html(loading);
												$.post( 
																		'php/pos.php',
																		{
																			cancelposset:1
																		},
																		function(data) {
																			$('#possettlementui').html(data);		
																		});
											}
										);
									</SCRIPT>
								<?PHP
							}
							?>
							<script>
							
								$("#savee").click(
									function()
									{
										
										
										$.validate({
														form:'#possettletypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
		
																		var formData = $('#possettletypeform')[0];
																		$("#<?php echo $dest;?>").html(loading);
																			$.ajax({
																						url: 'php/pos.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#<?php echo $dest;?>").html(data);
																							$("#final").focus();
																						},
																						error: function() 
																						{
																							alert('Sending failed');
																						} 	        
																				   });

														  return false; // Will stop the submission of the form
														},
													});
									}
								);		
							</script>
	<?php
	}
	else
	{
		?>
		<h2>OR number already used</h2>
		<button class = "btn btn-success btn-flat btn-sm" id = "back">BACK</button>
		<script>
			$("#back").click(
				function()
				{
					$.post( 
																	'php/pos.php',
																	 { addpaymentui:'<?php echo $_POST["add_payment_id"];?>' },
																	 function(data) {
																		$('#modalui').html(data);
																		
																	 });
				}
			);
		</script>
		<?php
	}
}
if(!empty($_POST['possetamount']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$tran = 0;
	$or = 0;
	$resibo = "";
	$ledid = 0;
	if(isset($_POST['posaddpaymentposid']))
	{
		//$ledid = $_POST['posaddpaymentid'];
		//$led = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where receivable_id = $ledid"));
		
		$inv = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id ='$posaddpaymentposid'"));
		
		if(!empty($inv))
			$tran = $inv['pos_sales_id'];
		
		$resibo = $inv['sales_invoice_number'];
		$or = $_POST['posorno'];
			
	}
	else
	{
		$tran = $_SESSION['tran'];
	}
	
	//echo $tran." aaaaa";
	$sale = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $tran"));
	$grand = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where pos_sales_id = $tran and isdeleted = 0"));
	$total_settle = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount) as total from pos_sales_settlement where pos_sales_id = $tran and isdeleted = 0"));
	if(is_array($_FILES)) 
	{
		if(!empty($_FILES['possetproof1']) && empty($_FILES['possetproof2']))
		{
			$name = $_FILES['possetproof1']['name'];
			$type = $_FILES['possetproof1']['type'];
			$size = $_FILES['possetproof1']['size'];
		
		
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['possetproof1']['name'];
			

			
			if(($type == "image/jpeg" || $type == "image/png"))
			{
				if($size <= 6000000)
				{
					if (!file_exists('../images/proof_of_payment/')) {
						mkdir('../images/proof_of_payment/', 0777, true);
					}
					
					$sourcePath = $_FILES['possetproof1']['tmp_name'];
					$targetPath = "../images/proof_of_payment/".basename($sss);

						if(is_uploaded_file($_FILES['possetproof1']['tmp_name'])) 
						{
							if(move_uploaded_file($sourcePath,$targetPath)) 
							{
							
								$ref1 = "";
								if(isset($_POST['possetref1']))
									$ref1 = $_POST['possetref1'];
								
								$ref2 = "";
								if(isset($_POST['possetref2']))
									$ref2 = $_POST['possetref2'];
								
								$refdes1 = "";
								if(isset($_POST['possetrefdes1']))
									$refdes1 = $_POST['possetrefdes1'];
								
								$refdes2 = "";
								if(isset($_POST['possetrefdes2']))
									$refdes2 = $_POST['possetrefdes2'];
								
								$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $possetid"));
								
								$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
								$validCharNumber = strlen($validCharacters);
								 
								$length = 10;
								$result = "";
							
									for ($i = 0; $i < $length; $i++) {
										$index = mt_rand(0, $validCharNumber-1);
										$index = mt_rand(0, $validCharNumber-1);
										$result .= $validCharacters[$index];
									}
								$user = get_user_id($_SESSION['c_craft']);
								$agent = get_agent($user);
								$branch = get_branch($user);
									
								if(empty($posaddpaymentid))
								{
									$rem = '';
									if(isset($_REQUEST['posaddpaymentremarks']))
										$rem = $_REQUEST['posaddpaymentremarks'];
									
									if(!isset($_POST['posaddpaymentposid']))
									{
										$change_a = ($total_settle['total']+$possetamount)-$grand['total'];
										
										$save = mysqli_query($con,"insert into pos_sales_settlement set
										result = '$result',
										pos_sales_id = $tran,
										sales_invoice_number = '$resibo',
										transaction_date = NOW(),
										settlement_type_id = '$possetid',
										settlement_type_code = '$row[settlement_code]',
										settlement_type_description = '$row[settlement_description]',
										settlement_amount = '$possetamount',
										change_amount = $change_a,
										reference_no1 = '$ref1',
										reference_no2 = '$ref2',
										with_reference_description1 = '$refdes1',
										with_reference_description2 = '$refdes2',
										proof_of_payment1 = '$sss',
										proof_of_payment2 = '',
										remarks = '$rem',
										isdeleted = 0
										");
									}
									else{
										$save = "";
									}
									
								
								
									$settle = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales_settlement where result = '$result'"));
									mysqli_query($con,"Select * from pos_sales_settlement set result = '' where pos_sales_settlement_id = $settle[pos_sales_settlement_id]");
									
									$camount = $possetamount;
									$sales_remarks = 0;
									if(!empty($_POST['posaddpaymentid']))
									{
										$clinecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and
										lup_settlement_type.settlement_type_id = $possetid"));
										$camount = $possetamount * -1;
									}
									else
									{
										$clinecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.charge_to_customer = 1)
										and lup_settlement_type.settlement_type_id = $possetid"));
										$sales_remarks = $settle['pos_sales_settlement_id'];
									}
									
									$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$tran"));
									
									if($clinecheck != 0)
									{
										
										insert_creditline($branch,$cus['customer_id'],$possetid,$camount,$sale['sales_invoice_number'],$or,'',$sss,$tran,$settle['pos_sales_settlement_id'],$agent,'','');
										//insert_creditline($branch,$cus['customer_id'],$possetid,$possetamount,$sale['sales_invoice_number'],$or,"",'',$tran,"",$agent);
									}
									
									
									$incomecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.charge_to_customer = 0)
									and lup_settlement_type.settlement_type_id = $possetid"));
									
									$user = get_user_id($_SESSION['c_craft']);
									$agent = get_agent($user);
									$branch = get_branch($user);
									$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$tran"));
									
									if($incomecheck != 0)
									{
										$verified = 0;
										
										$ver = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where 
										lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.with_verification = 0)
										and lup_settlement_type.settlement_type_id = $possetid"));
										
										if($ver != 0)
										{
											$verified = 1;
										}
										
										$amount = $possetamount-$change_a;
										insert_salesincome($branch,$cus['customer_id'],$_SESSION['tran'],$possetid,$amount,$ref1,$or,1,$verified,'',$sss,$sales_remarks,$settle['pos_sales_settlement_id'],$agent);
									}
								}
								else
								{
										$verified = 0;
										
										$ver = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where 
										lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.with_verification = 0)
										and lup_settlement_type.settlement_type_id = $possetid"));
										
										if($ver != 0)
										{
											$verified = 1;
										}
									//insert_creditline($branch,$cus['customer_id'],$possetid,$possetamount,$sale['sales_invoice_number'],$or,'',$sss,$tran,$settle['pos_sales_settlement_id'],$agent,'','');
									
									$amount = $possetamount * -1;
									insert_creditline($branch,$sale['customer_id'],$possetid,$amount,$sale['sales_invoice_number'],$or,'','','','',$agent,'','');
									
									$lrec = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where transaction_apply_to = '$sale[sales_invoice_number]' 
									and transaction_source = '$or' and transaction_amount = $amount"));
									
									//insert_salesincome($branch,$led['customer_id'],0,$possetid,$amount,'','',1,$verified,'','',$lrec['receivable_id'],0,$agent);
								}
								
								
								
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
		elseif(empty($_FILES['possetproof1']) && !empty($_FILES['possetproof2']))
		{
			$name = $_FILES['possetproof2']['name'];
			$type = $_FILES['possetproof2']['type'];
			$size = $_FILES['possetproof2']['size'];
		
		
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['possetproof2']['name'];
			

			
			if(($type == "image/jpeg" || $type == "image/png"))
			{
				if($size <= 6000000)
				{
					if (!file_exists('../images/proof_of_payment/')) {
						mkdir('../images/proof_of_payment/', 0777, true);
					}
					
					$sourcePath = $_FILES['possetproof2']['tmp_name'];
					$targetPath = "../images/proof_of_payment/".basename($sss);

						if(is_uploaded_file($_FILES['possetproof2']['tmp_name'])) 
						{
							if(move_uploaded_file($sourcePath,$targetPath)) 
							{
							
								$ref1 = "";
								if(isset($_POST['possetref1']))
									$ref1 = $_POST['possetref1'];
								
								$ref2 = "";
								if(isset($_POST['possetref2']))
									$ref2 = $_POST['possetref2'];
								
								$refdes1 = "";
								if(isset($_POST['possetrefdes1']))
									$refdes1 = $_POST['possetrefdes1'];
								
								$refdes2 = "";
								if(isset($_POST['possetrefdes2']))
									$refdes2 = $_POST['possetrefdes2'];
								
								$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $possetid"));
								
								$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
								$validCharNumber = strlen($validCharacters);
								 
								$length = 10;
								$result = "";
							
									for ($i = 0; $i < $length; $i++) {
										$index = mt_rand(0, $validCharNumber-1);
										$result .= $validCharacters[$index];
									}
								
								$user = get_user_id($_SESSION['c_craft']);
								$agent = get_agent($user);
								$branch = get_branch($user);
									
								if(empty($posaddpaymentid))
								{
									$rem = '';
									
									if(isset($_REQUEST['posaddpaymentremarks']))
										$rem = $_REQUEST['posaddpaymentremarks'];
									if(!isset($_POST['posaddpaymentposid']))
									{
										$change_a = ($total_settle['total']+$possetamount)-$grand['total'];
										$save = mysqli_query($con,"insert into pos_sales_settlement set
										result = '$result',
										pos_sales_id = $tran,
										sales_invoice_number = '$resibo',
										transaction_date = NOW(),
										settlement_type_id = '$possetid',
										settlement_type_code = '$row[settlement_code]',
										settlement_type_description = '$row[settlement_description]',
										settlement_amount = '$possetamount',
										change_amount = $change_a,
										reference_no1 = '$ref1',
										reference_no2 = '$ref2',
										with_reference_description1 = '$refdes1',
										with_reference_description2 = '$refdes2',
										proof_of_payment1 = '$sss',
										proof_of_payment2 = '',
										remarks = '$rem',
										isdeleted = 0
										");
									}
									else{
										$save = "";
									}
									
								
									$settle = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales_settlement where result = '$result'"));
									mysqli_query($con,"Select * from pos_sales_settlement set result = '' where pos_sales_settlement_id = $settle[pos_sales_settlement_id]");
									$camount = $possetamount;
									$sales_remarks = 0;
									if(isset($_POST['posaddpaymentid']))
									{
										$clinecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and
										lup_settlement_type.settlement_type_id = $possetid"));
										$camount = $possetamount * -1;
									}
									else
									{
										$clinecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.charge_to_customer = 1)
										and lup_settlement_type.settlement_type_id = $possetid"));
										$sales_remarks = $settle['pos_sales_settlement_id'];
									}
									
									$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$tran"));
									
									if($clinecheck != 0)
									{
										insert_creditline($branch,$cus['customer_id'],$possetid,$camount,$sale['sales_invoice_number'],$or,'',$sss,$tran,$settle['pos_sales_settlement_id'],$agent,'','');
										//insert_creditline($branch,$cus['customer_id'],$possetid,$possetamount,$sale['sales_invoice_number'],$or,"",'',$tran,"",$agent);
									}
								
										$incomecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.charge_to_customer = 0)
									and lup_settlement_type.settlement_type_id = $possetid"));
									
									$user = get_user_id($_SESSION['c_craft']);
									$agent = get_agent($user);
									$branch = get_branch($user);
									$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$tran"));
									
									
									
									if($incomecheck != 0)
									{
										$verified = 0;
										
										$ver = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where 
										lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.with_verification = 0)
										and lup_settlement_type.settlement_type_id = $possetid"));
										
										if($ver != 0)
										{
											$verified = 1;
										}
										
										$amount = $possetamount-$change_a;
										insert_salesincome($branch,$cus['customer_id'],$_SESSION['tran'],$possetid,$amount,$ref1,$or,1,$verified,'',$sss,$sales_remarks,$settle['pos_sales_settlement_id'],$agent);
									}
									
								
								}
								else
								{
									$verified = 0;
										
										$ver = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where 
										lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.with_verification = 0)
										and lup_settlement_type.settlement_type_id = $possetid"));
										
										if($ver != 0)
										{
											$verified = 1;
										}
									//insert_creditline($branch,$cus['customer_id'],$possetid,$possetamount,$sale['sales_invoice_number'],$or,'',$sss,$tran,$settle['pos_sales_settlement_id'],$agent,'','');
									
									$amount = $possetamount * -1;
									insert_creditline($branch,$sale['customer_id'],$possetid,$amount,$sale['sales_invoice_number'],$or,'','','','',$agent,'','');
									
									$lrec = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where transaction_apply_to = '$sale[sales_invoice_number]' 
									and transaction_source = '$or' and transaction_amount = $amount"));
									
									//insert_salesincome($branch,$led['customer_id'],0,$possetid,$amount,'','',1,$verified,'','',$lrec['receivable_id'],0,$agent);
								}
								
								
								
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
		elseif(!empty($_FILES['possetproof1']) && !empty($_FILES['possetproof2']))
		{
			$name = $_FILES['possetproof1']['name'];
			$type = $_FILES['possetproof1']['type'];
			$size = $_FILES['possetproof1']['size'];
			
			$name2 = $_FILES['possetproof2']['name'];
			$type2 = $_FILES['possetproof2']['type'];
			$size2 = $_FILES['possetproof2']['size'];
		
		
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['possetproof1']['name'];
			
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss2 = $result."_".$_FILES['possetproof2']['name'];
			
			

			
			if(($type == "image/jpeg" || $type == "image/png") && ($type2 == "image/jpeg" || $type2 == "image/png"))
			{
				if($size <= 6000000 && $size2 <= 6000000)
				{
					if (!file_exists('../images/proof_of_payment/')) {
						mkdir('../images/proof_of_payment/', 0777, true);
					}
					
					$sourcePath = $_FILES['possetproof1']['tmp_name'];
					$targetPath = "../images/proof_of_payment/".basename($sss);
					$sourcePath2 = $_FILES['possetproof2']['tmp_name'];
					$targetPath2 = "../images/proof_of_payment/".basename($sss2);

						if(is_uploaded_file($_FILES['possetproof1']['tmp_name']) && is_uploaded_file($_FILES['possetproof2']['tmp_name'])) 
						{
							if(move_uploaded_file($sourcePath,$targetPath) && move_uploaded_file($sourcePath2,$targetPath2)) 
							{
							
								$ref1 = "";
								if(isset($_POST['possetref1']))
									$ref1 = $_POST['possetref1'];
								
								$ref2 = "";
								if(isset($_POST['possetref2']))
									$ref2 = $_POST['possetref2'];
								
								$refdes1 = "";
								if(isset($_POST['possetrefdes1']))
									$refdes1 = $_POST['possetrefdes1'];
								
								$refdes2 = "";
								if(isset($_POST['possetrefdes2']))
									$refdes2 = $_POST['possetrefdes2'];
								
								$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $possetid"));
								
								$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
								$validCharNumber = strlen($validCharacters);
								 
								$length = 10;
								$result = "";
							
									for ($i = 0; $i < $length; $i++) {
										$index = mt_rand(0, $validCharNumber-1);
										$result .= $validCharacters[$index];
									}
								$user = get_user_id($_SESSION['c_craft']);
								$agent = get_agent($user);
								$branch = get_branch($user);
									
								if(empty($posaddpaymentid))
								{
									$rem = '';
									
									if($posaddpaymentremarks != '')
										$rem = $posaddpaymentremarks;
									
									if(!isset($_POST['posaddpaymentposid']))
									{
										$change_a = ($total_settle['total']+$possetamount)-$grand['total'];
										$save = mysqli_query($con,"insert into pos_sales_settlement set
										result = '$result',
										pos_sales_id = $tran,
										sales_invoice_number = '$resibo',
										transaction_date = NOW(),
										settlement_type_id = '$possetid',
										settlement_type_code = '$row[settlement_code]',
										settlement_type_description = '$row[settlement_description]',
										settlement_amount = '$possetamount',
										change_amount = $change_a,
										reference_no1 = '$ref1',
										reference_no2 = '$ref2',
										with_reference_description1 = '$refdes1',
										with_reference_description2 = '$refdes2',
										proof_of_payment1 = '$sss',
										proof_of_payment2 = '',
										remarks = '$rem',
										isdeleted = 0
									");
									}
									else{
										$save = "";
									}
									
									
								
								$settle = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales_settlement where result = '$result'"));
								mysqli_query($con,"update pos_sales_settlement set result = '' where pos_sales_settlement_id = $settle[pos_sales_settlement_id]");
									$camount = $possetamount;
									$sales_remarks = 0;
									if(isset($_POST['posaddpaymentid']))
									{
										$clinecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and
										lup_settlement_type.settlement_type_id = $possetid"));
										$camount = $possetamount * -1;
									}
									else
									{
										$clinecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.charge_to_customer = 1)
										and lup_settlement_type.settlement_type_id = $possetid"));
										$sales_remarks = $settle['pos_sales_settlement_id'];
									}
									
									$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$tran"));
									
									if($clinecheck != 0)
									{
										insert_creditline($branch,$cus['customer_id'],$possetid,$camount,$sale['sales_invoice_number'],$or,'',$sss,$tran,$settle['pos_sales_settlement_id'],$agent,'','');
										//insert_creditline($branch,$cus['customer_id'],$possetid,$possetamount,$sale['sales_invoice_number'],$or,"",'',$tran,"",$agent);
									}
									$incomecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.charge_to_customer = 0)
									and lup_settlement_type.settlement_type_id = $possetid"));
									
									$user = get_user_id($_SESSION['c_craft']);
									$agent = get_agent($user);
									$branch = get_branch($user);
									$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$tran"));
									
									
									
									if($incomecheck != 0)
									{
										$verified = 0;
										
										$ver = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where 
										lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.with_verification = 0)
										and lup_settlement_type.settlement_type_id = $possetid"));
										
										if($ver != 0)
										{
											$verified = 1;
										}
									
										
										$amount = $possetamount-$change_a;
										insert_salesincome($branch,$cus['customer_id'],$_SESSION['tran'],$possetid,$amount,$ref1,$or,1,$verified,'',$sss,$sales_remarks,$settle['pos_sales_settlement_id'],$agent);
									}
									
									
								}
								else
								{
									$verified = 0;
										
										$ver = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where 
										lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.with_verification = 0)
										and lup_settlement_type.settlement_type_id = $possetid"));
										
										if($ver != 0)
										{
											$verified = 1;
										}
									//insert_creditline($branch,$cus['customer_id'],$possetid,$possetamount,$sale['sales_invoice_number'],$or,'',$sss,$tran,$settle['pos_sales_settlement_id'],$agent,'','');
									
									$amount = $possetamount * -1;
									insert_creditline($branch,$sale['customer_id'],$possetid,$amount,$sale['sales_invoice_number'],$or,'','','','',$agent,'','');
									
									$lrec = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where transaction_apply_to = '$sale[sales_invoice_number]' 
									and transaction_source = '$or' and transaction_amount = $amount"));
									
									insert_salesincome($branch,$led['customer_id'],0,$possetid,$amount,'','',1,$verified,'','',$lrec['receivable_id'],0,$agent);
								}
								
								
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
									$ref1 = "";
									if(isset($_POST['possetref1']))
										$ref1 = $_POST['possetref1'];
									
									$ref2 = "";
									if(isset($_POST['possetref2']))
										$ref2 = $_POST['possetref2'];
									
									$refdes1 = "";
									if(isset($_POST['possetrefdes1']))
										$refdes1 = $_POST['possetrefdes1'];
									
									$refdes2 = "";
									if(isset($_POST['possetrefdes2']))
										$refdes2 = $_POST['possetrefdes2'];
									
									$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $possetid"));
									
									$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
									$validCharNumber = strlen($validCharacters);
								 
									$length = 10;
									$result = "";
							
									for ($i = 0; $i < $length; $i++) {
										$index = mt_rand(0, $validCharNumber-1);
										$result .= $validCharacters[$index];
									}
									
									$user = get_user_id($_SESSION['c_craft']);
									$agent = get_agent($user);
									$branch = get_branch($user);
								
							
									
								if(empty($posaddpaymentid))
								{
									$rem = '';
									
									if(isset($_REQUEST['posaddpaymentremarks']))
										$rem = $_REQUEST['posaddpaymentremarks'];
									
									if(!isset($_POST['posaddpaymentposid']))
									{
										$change_a = ($total_settle['total']+$possetamount)-$grand['total'];
										$save = mysqli_query($con,"insert into pos_sales_settlement set
										result = '$result',
										pos_sales_id = $tran,
										sales_invoice_number = '$resibo',
										transaction_date = NOW(),
										settlement_type_id = '$possetid',
										settlement_type_code = '$row[settlement_code]',
										settlement_type_description = '$row[settlement_description]',
										settlement_amount = '$possetamount',
										change_amount = $change_a,
										reference_no1 = '$ref1',
										reference_no2 = '$ref2',
										with_reference_description1 = '$refdes1',
										with_reference_description2 = '$refdes2',
										proof_of_payment1 = '',
										proof_of_payment2 = '',
										remarks = '$rem',
										isdeleted = 0
										");
										
										
									
									}
									else{
										$save = "";
									}
									
									
								
									$settle = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales_settlement where result = '$result'"));
									
									mysqli_query($con,"update pos_sales_settlement set result = '' where pos_sales_settlement_id = $settle[pos_sales_settlement_id]");
									$camount = $possetamount;
									$sales_remarks = 0;
									
									if(!empty($_POST['posaddpaymentid']))
									{
										$clinecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and
										lup_settlement_type.settlement_type_id = $possetid"));
										$camount = $possetamount * -1;
										//echo $possetamount;
									}
									else
									{
										$clinecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.charge_to_customer = 1)
										and lup_settlement_type.settlement_type_id = $possetid"));
										$sales_remarks = $settle['pos_sales_settlement_id'];
									}
									$user = get_user_id($_SESSION['c_craft']);
									$agent = get_agent($user);
									$branch = get_branch($user);
									$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$tran"));
									
									//$clinecheck = 0;
									if($clinecheck != 0)
									{
										insert_creditline($branch,$cus['customer_id'],$possetid,$camount,$sale['sales_invoice_number'],$or,'','',$tran,$settle['pos_sales_settlement_id'],$agent,'','');
										//insert_creditline($branch,$cus['customer_id'],$possetid,$possetamount,$sale['sales_invoice_number'],$or,"",'',$tran,"",$agent);
									}
								
									
										$incomecheck = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.charge_to_customer = 0)
										and lup_settlement_type.settlement_type_id = $possetid"));
									
									
									$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$tran"));
									
									
									//$incomecheck = 1;
									//echo $incomecheck." aaa";
									if($incomecheck != 0)
									{
										$verified = 0;
										
										$ver = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where 
										lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.with_verification = 1)
										and lup_settlement_type.settlement_type_id = $possetid"));
										
										if($ver != 0)
										{
											$verified = 1;
										}
										
										$amount = $possetamount-$change_a;
							
										insert_salesincome($branch,$cus['customer_id'],$tran,$possetid,$amount,$ref1,$or,1,$verified,'','',$sales_remarks,$settle['pos_sales_settlement_id'],$agent);
									}
									
									
								}
								else
								{
									
										$verified = 0;
										
										$ver = mysqli_num_rows(mysqli_query($con,"Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where 
										lup_settlement_type.settlement_type_id = settings_settlement_mapping.settlement_type_id and (settings_settlement_mapping.with_verification = 0)
										and lup_settlement_type.settlement_type_id = $possetid"));
										
										if($ver != 0)
										{
											$verified = 1;
										}
									//insert_creditline($branch,$cus['customer_id'],$possetid,$possetamount,$sale['sales_invoice_number'],$or,'',$sss,$tran,$settle['pos_sales_settlement_id'],$agent,'','');
									
									$amount = ($possetamount-$change_a) * -1;
									insert_creditline($branch,$sale['customer_id'],$possetid,$amount,$sale['sales_invoice_number'],$or,'','','','',$agent,'','');
									
									$lrec = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where transaction_apply_to = '$sale[sales_invoice_number]' 
									and transaction_source = '$or' and transaction_amount = $amount"));
									
									//insert_salesincome($branch,$led['customer_id'],0,$possetid,$amount,'','',1,$verified,'','',$lrec['receivable_id'],0,$agent);
									//insert_salesincome($branch,$led['customer_id'],0,$possetid,$amount,'','',1,$verified,'','',$sales_remarks,$settle['pos_sales_settlement_id'],$agent);
									insert_salesincome($branch,$sale['customer_id'],$sale['pos_sales_id'],$possetid,$amount*-1,'',$or,1,$verified,'','','',0,$agent);
								}
									
									
		}
	}
							
	$map = mysqli_fetch_assoc(mysqli_query($con,"Select settlement_type_id from settings_settlement_mapping where charge_to_customer = 1"));
	
	if(isset($_POST['posaddpaymentposid']))
	{
		if(empty($posaddpaymentid))
		{
			
			
			$col = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(amount)as total from ledger_sales_income where pos_sales_id = $sale[pos_sales_id] and 
			isdeleted = 0 and amount < 0"));
			
			$cr = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount)as total from pos_sales_settlement where pos_sales_id = $sale[pos_sales_id] and 
			isdeleted = 0 and settlement_type_id = $map[settlement_type_id]"));
			
		?>
			<script>
			
				$("#ccolui<?php echo $posaddpaymentctr;?>").html('<?php echo number_format($col['total']*-1,2);?>');
				$("#cbalui<?php echo $posaddpaymentctr;?>").html('<?php echo number_format(($sale['total_sales']-$cr['total'])-($col['total']*-1),2);?>');
				
										//$("#modal").modal('hide');
										$.post( 
																'php/pos.php',
																{
																	ledgerrefresh:"<?php echo $sale['sales_invoice_number'];?>",
																	ledgerrefreshcus:"<?php echo $sale['customer_id'];?>"
																	
																},
																function(data) {
																	$('#cusledger').html(data);
																	
																});
										$("#modalui").html(loading);
															$.post( 
																'php/pos.php',
																{
																	addpaymentui:'<?php echo $tran;?>',
																	addpaymentsettle:'',
																	addpaymentctr:'0',
																	addpaymentsettle2:'0',
																	addpaymentremarks:'FROM_COLLECTION'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
																
																
			</script>
		<?php
		}
		else
		{
			$app = mysqli_fetch_assoc(mysqli_query($con,"Select * from credit_line_transaction
			GROUP BY transaction_apply_to
			HAVING SUM(transaction_amount)>0"));
			
			insert_cline($sale['customer_id'],0,$app['transaction_apply_to'],$amount*-1,$possetid,$sale['sales_invoice_number'],'COLLECTION',$or,$user);
			
			//$app = mysqli_fetch_assoc(mysqli_query($con,"Select * from credit_line_transaction
			//GROUP BY transaction_apply_to
			//HAVING SUM(transaction_amount)>0"));
			
			//echo $app['transaction_apply_to']."aaaaa";
			//insert_cline($cus,$tran_type,$apply_to,$amount,$source_id, $source_no,$source_remarks,$tran_remarks,$user);
			//insert_cline($inv['customer_id'],0,$app['transaction_apply_to'],$settle['settlement_amount'],$settle['pos_sales_settlement_id'],$inv['sales_invoice_number'],'COLLECTION','ALLOCATION',$user);
			$col = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount)as total from ledger_receivable where transaction_apply_to = '$sale[sales_invoice_number]' and 
									isdeleted = 0 and transaction_amount < 0"));
									
			//echo "AAAA";
			
			
			?>
			<script>
								$("#crcolui<?php echo $posaddpaymentctr;?>").html('<?php echo number_format($col['total']*-1,2);?>');
								$("#crbalui<?php echo $posaddpaymentctr;?>").html('<?php echo number_format($posaddpaymentsettle-($col['total']*-1),2);?>');
								
								$("#modal").modal('hide');
																
			</script>	
			<?php
		}
	}
	else
	{
		$ttender = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount) as total from pos_sales_settlement
													where pos_sales_id = $_SESSION[tran] and isdeleted = 0"));
		?>
		<script>
			$("#tenderui").html("<h4><?php echo number_format($ttender['total'],2);?>");
		</script>
		<?php
	
		pos_settlement_list($tran,0);		
	}
	
	?>
		<SCRIPT>
		
										
												
												$.post( 
																'php/pos.php',
																{
																	renderuii:1
																	
																},
																function(data) {
																	$('#renderui').html(data);
																
																});
												$.post( 
																'php/pos.php',
																{
																	changeuii:1
																	
																},
																function(data) {
																	$('#changeui').html(data);
																
																});
																
													
												/*$.post( 
																		'php/pos.php',
																		{
																			refresh_collection_settlement:'<?php echo $tran;?>'
																		},
																		function(data) {
																			$('#collection_settlement_list').html(data);		
																		});*/
		</script>
	<?PHP
}
if(!empty($_REQUEST['refresh_collection_settlement']))
{
	$id = $_REQUEST['refresh_collection_settlement'];
	collection_settlement($id);
}

if(!empty($_REQUEST['cancelposset']))
{
	pos_settlement_list($_SESSION['tran'],0);
}
if(!empty($_POST['pos_otype']))
{
	$otype = $_POST['pos_otype'];
	$rem = $_POST['pos_rem'];
	$pos_date = $_POST['pos_date'];
	
	$id = $_SESSION['tran'];
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
											
	$set = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount) as total from pos_sales_settlement where pos_sales_id = $id and isdeleted = 0"));
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where 
			pos_sales_id = $id and isdeleted = 0"));
	
			$spent = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(pos_sales_settlement.settlement_amount) as total from pos_sales_settlement, settings_settlement_mapping where 
			pos_sales_settlement.pos_sales_id = $id and pos_sales_settlement.settlement_type_id = settings_settlement_mapping.settlement_type_id
			and settings_settlement_mapping.charge_to_customer = 1"));	
			
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $id"));
	
	$totalcline = mysqli_fetch_assoc(mysqli_query($con,"Select sum(transaction_amount) as total from credit_line_transaction where customer_id = $row[customer_id] and isdeleted = 0"));
	
	$istran = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales_detail where 
	pos_sales_id = $id and isdeleted = 0"));
	
	if(!empty($istran))
	{
		if($total['total'] <= $set['total'])
		//if($total['total'] != 0)
		{
							
				$invoice = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_invoice_number where pos_sales_id = $_SESSION[tran]"));				
				
				
				$tot = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as total from pos_sales_detail where 
				pos_sales_id = $id and isdeleted = 0"));
				
				
				
				$card = mysqli_fetch_assoc(mysqli_query($con,"Select card_profile_id from registration where customer_id = $row[customer_id]"));
				
				mysqli_query($con,"Update pos_sales set
				order_type_id = $otype,
				sales_datetime = NOW(),
				sales_invoice_number = '$invoice[invoice_number]',
				total_sales = $total[total],
				total_quantity = $tot[total],
				card_id = $c,
				remarks = '$rem'
				where pos_sales_id = $id
				");
				
				mysqli_query($con,"Update pos_sales_detail set
				sales_invoice_number = '$invoice[invoice_number]'
				where pos_sales_id = $id
				");
				
				//insert_salesincome($branch,$row['customer_id'],$id,0,$total['total'],$invoice['invoice_number'],'',0,0,'','',0,0,$user);
				
				$app = mysqli_fetch_assoc(mysqli_query($con,"Select * from credit_line_transaction
				GROUP BY transaction_apply_to
				HAVING SUM(transaction_amount)>0"));
				
				//echo $app['transaction_apply_to']."aaaaa";
				//insert_cline($cus,$tran_type,$apply_to,$amount,$source_id, $source_no,$source_remarks,$tran_remarks,$user);
				
				$spent = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(pos_sales_settlement.settlement_amount) as total from pos_sales_settlement, settings_settlement_mapping where 
				pos_sales_settlement.pos_sales_id = $id and pos_sales_settlement.settlement_type_id = settings_settlement_mapping.settlement_type_id
				and settings_settlement_mapping.charge_to_customer = 1"));
				
				$spenttotal = 0;
				if($spent['total'] != 0)
				{
					$spenttotal = $spent['total']*-1;
					insert_cline($row['customer_id'],0,$app['transaction_apply_to'],$spenttotal,$id,$invoice['invoice_number'],'POS','SPENT',$user);
					mysqli_query($con,"Update credit_line_transaction set transaction_date = '$pos_date' where source_no = '$invoice[invoice_number]'");
				}
				
				mysqli_query($con,"Update ledger_receivable set transaction_date = '$pos_date', transaction_apply_to = '$invoice[invoice_number]', transaction_source_number = '$invoice[invoice_number]' where remarks_sales = $id");
				
				$squery = mysqli_query($con,"Select * from pos_sales_settlement where pos_sales_id = $id and isdeleted = 0");
				mysqli_query($con,"update pos_sales_settlement set sales_invoice_number = '$invoice[invoice_number]', transaction_date = '$pos_date' where pos_sales_id = $id and isdeleted = 0");
				
				$stotal = 0;
				$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from pos_sales where pos_sales_id =$id"));
				while($srow = mysqli_fetch_assoc($squery))
				{
												$rebatecheck = mysqli_num_rows(mysqli_query($con,"Select * from settings_settlement_mapping where settings_settlement_mapping.settlement_type_id = $srow[settlement_type_id] and settings_settlement_mapping.with_rebate = 1"));
												
												
												//echo $rebatecheck;
												if($rebatecheck != 0)
												{
													$stotal = $stotal + $srow['settlement_amount'];
													
													
												}
				}
				$user = get_user_id($_SESSION['c_craft']);
				$agent = get_agent($user);
				$branch = get_branch($user);
				
				insert_rebate($branch,$cus['customer_id'],$_SESSION['tran'],$stotal,$id);
				mysqli_query($con,"Update ledger_rebate set rebate_date = '$pos_date' where pos_sales_id = '$_SESSION[tran]'");
				
				$reg = mysqli_fetch_assoc(mysqli_query($con,"Select * from registration where customer_id = $cus[customer_id]"));
				
				if($reg['referral_id'] != $cus['customer_id'])
				{
					insert_ref_rebate($branch,$reg['referral_id'],$_SESSION['tran'],$stotal,$id);
					mysqli_query($con,"Update ledger_rebate set rebate_date = '$pos_date' where pos_sales_id = '$_SESSION[tran]'");
					
				}
				
				//SAVING INVENTORY TRANSACTION 
				$sdquery = mysqli_query($con,"Select * from pos_sales_detail where pos_sales_id = $id and isdeleted = 0");
				$saletran = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where issales = 1 and visible = 1"));
				while($sdrow = mysqli_fetch_assoc($sdquery))
				{
					inventory_transaction($sdrow['item_id'],$sdrow['unit_id'],0,$saletran['transaction_type_id'],$sdrow['quantity'],'TRANSACTION FROM SALES',$sdrow['item_cost'],$sdrow['branch_id'],'','',$sdrow['pos_sales_detail_id'],0,0);				
					//inventory_transaction($iproduct,$iunit,$isupplier,$itransaction,$iquantity,$iremarks,$icost,$branch,$expire,$delivery,$posdetail_id,$markup)
					
					$it = mysqli_fetch_assoc(mysqli_query($con,"Select inv_delivery_details.delivery_id, inv_delivery_details.delivery_date, SUM(inv_transaction.quantity) as tot, inv_transaction.unit_cost,inv_transaction.markup,inv_transaction.transaction_no, inv_transaction.expiration_date from inv_transaction, inv_delivery_details where inv_transaction.item_id = $sdrow[item_id] and inv_transaction.isdeleted = 0
					and inv_delivery_details.delivery_id = inv_transaction.delivery_id
					group by inv_transaction.transaction_no having tot > 0 order by inv_delivery_details.delivery_date"));
		
					mysqli_query($con,"Update inv_transaction set markup = $it[markup], transaction_no = '$it[transaction_no]',delivery_id = '$it[delivery_id]', expiration_date = '$it[expiration_date]', transaction_date = NOW() where reference_id1 = '$sdrow[pos_sales_detail_id]'");
				}
				
				mysqli_query($con,"Update ledger_sales_income set sales_income_date = '$pos_date' where pos_sales_id = '$_SESSION[tran]'");
				
				$pset = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_receipt_print where iscurrent = 1"));
				
				if($pset['enable'] == 1)
				{
					?>
						<script>
							$.post( 
													'php/pos.php',
													{
														printinvoice2:<?php echo $id;?>
														
													},
													function(data) {
														$('#click').html(data);		
														$("#modal").modal('hide');
													});
						</script>
					<?php
				}
				
				$_SESSION['tran'] = '';
				?>
					<script>
						alert("Transaction Completed.");
						$("#modal").modal('hide');
						$('#maincontent').html(loading);
						//window.location.href = 'or.php?id=<?php echo $id;?>';
				<?php
					if($row['customer_id'] == 0)
					{
						?>
											$.post( 
													'php/pos.php',
													{
														posui:1
														
													},
													function(data) {
														$('#maincontent').html(data);		
													});
						<?php
					}
					else{
						?>
											$.post( 
													'php/pos.php',
													{
														cposui:1
														
													},
													function(data) {
														$('#maincontent').html(data);		
													});
						<?php
					}
				?>
													
						
													
					</script>
				<?php
			
					
		
		}
		else
		{
			?>
			<script>
				alert("Insufficient Settlement Amount");
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script>
			alert("No Transaction Listed");
		</script>
		<?php
		
	}
	
}
if(isset($_REQUEST['printinvoice']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
				
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $printinvoice"));
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">SALES INVOICE</h4>
			
			<h4 style = "text-align:right;color:red;font-weight:bold;">#<?php echo $row['sales_invoice_number'];?></h4>
			
			<div class="box" style = "margin-top:10px;display:none;" >
				<div class="box-header with-border">
					<h3 class="box-title">CUSTOMER INFORMATION</h3>
				</div>
				<div class = "box-body">
					<?php invoice_customer_info($row['customer_id']);?>
				</div>
			</div>
			<div class="box" style = "margin-top:10px;">
				<div class="box-header with-border">
					<h3 class="box-title">ORDER DETAILS</h3>
				</div>
				<div class = "box-body">
					<?php pos_item_list($row['pos_sales_id'],1);?>
				</div>
			</div>
			<div class="box" style = "margin-top:10px;">
				<div class="box-header with-border">
					<h3 class="box-title">PAYMENT DISCOUNT</h3>
				</div>
				<div class = "box-body">
					<?php discountlist($row['pos_sales_id'],1);?>
				</div>
			</div>
			<div class="box" style = "margin-top:10px;">
				<div class="box-header with-border">
					<h3 class="box-title">SETTLEMENT DETAILS</h3>
				</div>
				<div class = "box-body">
					<?php pos_settlement_list($row['pos_sales_id'],1);?>
				</div>
			</div>
			<div class="box" style = "margin-top:10px;display:none;">
				<div class="box-header with-border">
					<h3 class="box-title">ORDER TYPE DETAILS</h3>
				</div>
				<div class = "box-body">
					<?php order_type_details($row['pos_sales_id'],1);?>
				</div>
			</div>
			
			<table class = "table table-bordered">
				<tr>
					<td>
						Printed by:<br><br>
						<p style = "border-bottom:1px solid #000;text-align:center;width:200px;font-weight:bold;"><?php echo $agent;?></p>
					</td>
					<td>
						Received by:<br><br>
						<p style = "border-bottom:1px solid #000;text-align:center;width:200px;font-weight:bold;">&nbsp;</p>
					</td>
					
				</tr>
			</table>
			
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
if(isset($_REQUEST['colui']))
{
	?>
	<h2>COLLECTION</h2>
	<div id = "colui">
	<div class="box">
			<div class="box-body">
				<form id = "cmsearchform" method = "POST">
					<div class = "row">
						<div class="col-md-3">
							 <div class="form-group">								
								<label for="age">SEARCH CUSTOMER:</label>
								<input type="text" id="ref" name="colref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
								data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
								<input type="hidden" id="clickval">
								<div id = "search_result"></div>												
							</div>
						</div>
						
						<div class="col-md-5" style = "padding-top:25px;">
													<div class = "form-group">
														<button class = "btn btn-success btn-flat" id = "go">GO</button>
														
													</div>	
						</div>
					</div>
					<div id = "posalert"></div>
				</form>
				<script>
						
													$.validate({
																	form:'#cmsearchform',
																	validateOnBlur : false,
																	errorMessagePosition : 'top',
																	modules : 'security',
																	onSuccess : function($form) {
																	
																		 var formData = $('#cmsearchform').serializeArray();
																			
																			 //var formData = new FormData($('#regform')[0]);
																	
																					$.ajax({
																						url :  'php/pos.php',
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
											$("#ref").keyup(
													function()
													{
															$("#clickval").val("");
															var s = $("#ref").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
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
											
				</script>
			</div>
	</div>
	</div>
	<?php
}
if(isset($_POST['colref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_no = '$colref'"));
	
	if(!empty($check))
	{?>
		<script>
			$.post( 
																	'php/pos.php',
																	 { colui2:'<?php echo $check["customer_id"];?>' },
																	 function(data) {
																		$('#colui').html(data);
																	 });
		</script>
		<?php
		
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#posalert");
			</script>
		<?php
	}
}
if(!empty($_REQUEST['colui2']))
{
	$id = $_REQUEST['colui2'];
	
	$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable where customer_id = $id and isdeleted = 0
	and transaction_apply_to != ''"));
	
	?>
		<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">CUSTOMER INFORMATION</h3>
				</div>
				<div class="box-body">
					<?php pos_customer_info($id);?>
				</div>
		</div>
		<div class="box" style = "margin-top:10px;">
			  <div class = "box-body" id = "ledtranui">
				<form id = "newcardtypeform">
									
									
									  <div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">SEARCH INVOICE NO: &nbsp;</label>
												<input type = "hidden" name = "sinvoicecus" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "sinvoice" name = "sinvoice" data-validation="required"
												data-validation-error-msg="Enter Invoice Number">
											
											</div>
										</div>
										
									
									
										
										<div class="col-md-5" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat btn-sm">GO</button>
											<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
											<button class = "btn btn-warning btn-flat btn-sm" id = "bal">SUMMARY</button>	
											<button class = "btn btn-primary btn-flat btn-sm" id = "det">DETAIL</button>
											<button class = "btn btn-success btn-flat btn-sm" id = "add">ADD BALANCE</button>											
										 
										</div>
										<div class="col-md-3" id = "totalbalanceui">
											<h2>BALANCE: <?php echo number_format($bal['total'],2);?></h2>
										</div>
										

									  </div>
									
							</form>
							<script>
								$("#add").click(
									function(e)
									{
										e.preventDefault();
										$("#modal").modal("show");
										$("#modalbody").css("min-width","50%");
										$("#modalui").html(loading);
															
										$.post( 
																		'php/pos.php',
																		{
																			ledadd:<?php echo $id;?>
																		},
																		function(data) {
																			$('#modalui').html(data);		
																		});
									}
								);
								$("#det").click(
									function(e)
									{
										e.preventDefault();
										
										$.post( 
																		'php/pos.php',
																		{
																			leddet:<?php echo $id;?>
																		},
																		function(data) {
																			$('#cusledger').html(data);		
																		});
									}
								);
								$("#bal").click(
									function(e)
									{
										e.preventDefault();
										
																$.post( 
																		'php/pos.php',
																		{
																			ledsum:<?php echo $id;?>
																		},
																		function(data) {
																			$('#cusledger').html(data);		
																		});
									}
								);
								$("#cancel").click(
									function(e)
									{
										e.preventDefault();
										
										$.post( 
																		'php/pos.php',
																		{
																			colui:1
																		},
																		function(data) {
																			$('#maincontent').html(data);		
																		});
									}
								);
											$.validate({
														form:'#newcardtypeform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															$("#cusledger").html(loading);
															
															 var formData = $('#newcardtypeform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/pos.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#cusledger").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>			
			  </div>
		</div>
		<div id = "colalert"></div>
		<div class="box">
				<div class="box-body" id = "cusledger">
					<?php ledger('',$id,'','','',0,0);?>
				</div>
		</div>
				
	<?php
}
if(!empty($_REQUEST['addpaymentui']))
{
	$id = $_REQUEST['addpaymentui'];
	$settle = $_REQUEST['addpaymentsettle'];
	$settle2 = $_REQUEST['addpaymentsettle2'];
	$addctr = $_REQUEST['addpaymentctr'];
	$remarks = $_REQUEST['addpaymentremarks'];
	
	$string = "Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where
	lup_settlement_type.settlement_type_id  = settings_settlement_mapping.settlement_type_id  and lup_settlement_type.visible = 1";
	$check = 0;
	
	if($check != 0)
	{
		$string = $string." and (settings_settlement_mapping.charge_to_customer = 0 or settings_settlement_mapping.charge_to_customer = 1)";
	}
	else
	{
		$string = $string." and (settings_settlement_mapping.charge_to_customer = 0)";
	}
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $id"));
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">ADD PAYMENT TO INVOICE # <?php echo $row['sales_invoice_number'];?></h3>
				</div>
			  <div class = "box-body" id = "collection_addpaymentui">
				<form id = "possettleform">
								<div class = "row">
											<div class="col-md-3">
													<div class="form-group">
														<label>Settlement Type:</label>
														<input type = "hidden" name = "add_payment_id" value = "<?php echo $settle;?>">
														<input type = "hidden" name = "add_payment_settle_amount" value = "<?php echo $settle2;?>">
														<input type = "hidden" name = "add_payment_pos_id" value = "<?php echo $id;?>">
														<input type = "hidden" name = "add_payment_ctr" value = "<?php echo $addctr;?>">
														<input type = "hidden" name = "add_payment_remarks" value = "<?php echo $remarks;?>">
														
														<Select class = "form-control" name = "pos_settle" data-validation="required"
														data-validation-error-msg="Select Settlement Type">
															<option value = "" hidden "Selected"> </option>
														<?php
														$pmquery = mysqli_query($con,$string);
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
											<div class="col-md-5">
												<div class="form-group">
													<label for="service_description_edit">Payment Reference No: &nbsp;</label>
													<input type="text" class = "form-control" id = "colrefno" name = "colrefno" data-validation="required"
													data-validation-error-msg="Reference No Field is Required">
												
												</div>
											</div>
											
											<div class="col-md-3">
													<div class="form-group" style = "padding:25px;">
														<button class = "btn btn-success btn-flat" id = "select">OK</button>
													</div>
											</div>
								</div>
							</form>
							<script>
											$.validate({
														form:'#possettleform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
															
															
															
															 var formData = $('#possettleform').serializeArray();
															 $("#collection_addpaymentui").html(loading);
																 //var formData = new FormData($('#regform')[0]);
																 
																		$.ajax({
																			url :  'php/pos.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#collection_addpaymentui").html(data);
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
							</script>
							
			  </div>
		</div>
		<div class="box" style = "margin-top:10px;">
			  <div class = "box-body" id = "collection_settlement_list">
				<?php collection_settlement($id);?>
			  </div>
		</div>
		
							
	<?php
}
if(isset($_REQUEST['ledgerrefresh']))
{
	ledger('',$_REQUEST['ledgerrefreshcus'],'','','',0,0);
	
	$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable where customer_id = $_REQUEST[ledgerrefreshcus] and
	transaction_apply_to != '' and isdeleted = 0"));
	
	?>
	<script>
		$("#totalbalanceui").html('<h2>BALANCE: <?php echo number_format($bal['total'],2);?></h2>');
	</script>
	<?php
}

if(isset($_POST['sinvoice']))
{
	ledger($_POST['sinvoice'],$_POST['sinvoicecus'],'','','',0,0);
	$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable where customer_id = $_POST[sinvoicecus] and
	transaction_apply_to = $_POST[sinvoice] and isdeleted = 0"));
	
	?>
	<script>
		$("#totalbalanceui").html('<h2>BALANCE: <?php echo number_format($bal['total'],2);?></h2>');
	</script>
	<?php
}

if(isset($_REQUEST['ledsum']))
{
	ledger_summary('',$_REQUEST['ledsum'],'','','',0,0);
	
	$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable where customer_id = $_REQUEST[ledsum] and
	isdeleted = 0 and transaction_apply_to != ''"));
	
	?>
	<script>
		$("#totalbalanceui").html('<h2>BALANCE: <?php echo number_format($bal['total'],2);?></h2>');
	</script>
	<?php
}
if(isset($_REQUEST['leddet']))
{
	ledger('',$_REQUEST['leddet'],'','','',0,0);
	$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable where customer_id = $_REQUEST[leddet] and
	isdeleted = 0 and transaction_apply_to != ''"));
	
	?>
	<script>
		$("#totalbalanceui").html('<h2>BALANCE: <?php echo number_format($bal['total'],2);?></h2>');
	</script>
	<?php
	
}
if(isset($_POST['leddel']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$user = get_user_id($_SESSION['c_craft']);
								$agent = get_agent($user);
								$branch = get_branch($user);
								
	$del = mysqli_query($con,"Update ledger_receivable set isdeleted = 1 where receivable_id = $leddel");
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where receivable_id = $leddel"));
	
	mysqli_query($con,"Update pos_sales_settlement set isdeleted = 1 where pos_sales_settlement_id = $row[remarks_payment]");
	mysqli_query($con,"Update ledger_sales_income set isdeleted = 1, datetime_deleted = NOW(), deleted_by_fullname = '$agent' where remarks_payment = $row[remarks_payment]");
	
			$user = get_user_id($_SESSION['c_craft']);
			$agent = get_agent($user);
			$branch = get_branch($user);
	
			$app = mysqli_fetch_assoc(mysqli_query($con,"Select * from credit_line_transaction
			GROUP BY transaction_apply_to
			HAVING SUM(transaction_amount)>0"));
		
			$spenttotal = 0;
			if($row['transaction_amount'] > 0)
				$spenttotal = $row['transaction_amount']*-1;
			else
				$spenttotal = $row['transaction_amount'];
				
			insert_cline($row['customer_id'],0,$app['transaction_apply_to'],$spenttotal,$row['remarks_payment'],$row['transaction_apply_to'],'COLLECTION','SPENT',$user);
			
			
	if(!$del)
	{
		
		?>
			<script>
				notify("Error Deleting Ledger Transaction , contact the system administrator");
			</script>
		<?php
	}
	
	ledger($ledinv,$ledcus,'','','',0,0);
	$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable where (customer_id = $ledcus || transaction_apply_to = '$ledinv') and
	isdeleted = 0 and transaction_apply_to != ''"));
	
	?>
	<script>
		$("#totalbalanceui").html('<h2>BALANCE: <?php echo number_format($bal['total'],2);?></h2>');
	</script>
	<?php
	
}
if(isset($_REQUEST['invoicedetails']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
				
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $invoicedetails"));
	?>
	
			<h4 style = "text-align:right;color:red;font-weight:bold;">#<?php echo $row['sales_invoice_number'];?></h4>
			
			<div class="box" style = "margin-top:10px; display:none;">
				<div class="box-header with-border">
					<h3 class="box-title">CUSTOMER INFORMATION</h3>
				</div>
				<div class = "box-body">
					<?php invoice_customer_info($row['customer_id']);?>
				</div>
			</div>
			
			
			<div class="box" style = "margin-top:10px;">
				<div class="box-header with-border">
					<h3 class="box-title">ORDER DETAILS</h3>
				</div>
				<div class = "box-body">
					<?php pos_item_list($row['pos_sales_id'],1);?>
				</div>
			</div>
			<div class="box" style = "margin-top:10px;">
				<div class="box-header with-border">
					<h3 class="box-title">PAYMENT DISCOUNT</h3>
				</div>
				<div class = "box-body">
					<?php discountlist($row['pos_sales_id'],1);?>
				</div>
			</div>
			
			<div class="box" style = "margin-top:10px;">
				<div class="box-header with-border">
					<h3 class="box-title">SETTLEMENT DETAILS</h3>
				</div>
				<div class = "box-body">
					<?php pos_settlement_list($row['pos_sales_id'],1);?>
				</div>
			</div>
			<div class="box" style = "margin-top:10px;display:none;">
				<div class="box-header with-border">
					<h3 class="box-title">ORDER TYPE DETAILS</h3>
				</div>
				<div class = "box-body">
					<?php order_type_details($row['pos_sales_id'],1);?>
				</div>
			</div>
			
			
		
		
		
	<?php
}
if(isset($_REQUEST['possetdel']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales_settlement where pos_sales_settlement_id = $possetdel"));
	$del = mysqli_query($con,"Update ledger_receivable set isdeleted = 1 where remarks_payment = $possetdel");
	mysqli_query($con,"Update pos_sales_settlement set isdeleted = 1 where pos_sales_settlement_id = $possetdel");
	$ttender = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount) as total from pos_sales_settlement
													where pos_sales_id = $_SESSION[tran] and isdeleted = 0"));
	?>
		<script>
			$("#tenderui").html("<h4><?php echo number_format($ttender['total'],2);?>");
												$.post( 
												'php/pos.php',
																{
																	renderuii:1
																	
																},
																function(data) {
																	$('#renderui').html(data);
																
																});
												$.post( 
																'php/pos.php',
																{
																	changeuii:1
																	
																},
																function(data) {
																	$('#changeui').html(data);
																
																});
																
		</script>
	<?php
	
	if(!$del)
	{
		
		?>
			<script>
				alert("Error Deleting Ledger Transaction , contact the system administrator");
				
			</script>
		<?php
	}
	pos_settlement_list($row['pos_sales_id'],0);
}
if(!empty($_REQUEST['possetedit']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	
	$del = mysqli_query($con,"Update pos_sales_settlement set settlement_amount = $posseteditval where pos_sales_settlement_id = $possetedit");
	$grand = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where pos_sales_id = $_SESSION[tran] and isdeleted = 0"));
	$total_settle = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount) as total from pos_sales_settlement where pos_sales_id = $_SESSION[tran] and isdeleted = 0"));
	$change_a = ($total_settle['total'])-$grand['total'];
	$del = mysqli_query($con,"Update pos_sales_settlement set change_amount = $change_a where pos_sales_settlement_id = $possetedit");
	//mysqli_query($con,"Update ledger_receivable set transaction_amount = $posseteditval where remarks_payment = $possetedit");
	
	$ttender = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount) as total from pos_sales_settlement
													where pos_sales_id = $_SESSION[tran] and isdeleted = 0"));
	?>
		<script>
			$("#tenderui").html("<h4><?php echo number_format($ttender['total'],2);?>");
			$.post( 
												'php/pos.php',
																{
																	renderuii:1
																	
																},
																function(data) {
																	$('#renderui').html(data);
																
																});
												$.post( 
																'php/pos.php',
																{
																	changeuii:1
																	
																},
																function(data) {
																	$('#changeui').html(data);
																
																});
																
		</script>
	<?php
	
	if(!$del)
	{
		/*$newval = mysqli_fetch_assoc(mysqli_query($con,"Select (quantity * item_price) as newval from pos_sales_detail
		where pos_sales_detail_id = $positemedit and isdeleted = 0"));
		$totsub = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(sub_total) as total from pos_sales_detail
		where pos_sales_detail_id = $positemedit and isdeleted = 0"));*/
		
		?>
			<script>
				alert("Error Updating Item, Please Contact System Administrator");
			</script>
		<?php
	}
	
	pos_settlement_list($_SESSION['tran'],0);
}
if(!empty($_REQUEST['ledadd']))
{
	$id = $_REQUEST['ledadd'];
	$check = mysqli_num_rows(mysqli_query($con, "Select * from registration where customer_id = $id"));
	
	$string = "Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where
	lup_settlement_type.settlement_type_id  = settings_settlement_mapping.settlement_type_id ";
	if($check != 0)
	{
		$string = $string." and (settings_settlement_mapping.charge_to_customer = 0 or settings_settlement_mapping.charge_to_customer = 1)";
	}
	else
	{
		$string = $string." and (settings_settlement_mapping.charge_to_customer = 0)";
	}
	?>
	<div class="box">
		<div class="box-body">
			<form id = "transactionform" method = "POST">
				<div class="form-row" >
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">TRANSACTION APPLY TO:</label>
												<input type = "hidden" value = "<?php echo $id;?>" name = "tledid">
												<input type="text" class = "form-control" id = "tapply" name = "tapply" data-validation="required"
												data-validation-error-msg="Enter TRANSACTION APPLY TO">
											
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">TRANSACTION SOURCE:</label>
												<input type="text" class = "form-control" id = "tsource" name = "tsource" data-validation="required"
												data-validation-error-msg="Enter TRANSACTION SOURCE">
											
											</div>
										</div>
										<div class="col-md-3" id = "cdfromui">
											<div class = "form-group">
												<label>Date:</label>
												<input type = "date" class = "form-control" name = "tdue" id = "tdue">
											</div>		
										</div>
										<?php
										$ccrow = mysqli_fetch_assoc(mysqli_query($con,"Select settlement_type_id from settings_settlement_mapping where charge_to_customer = 1"));
										?>
										<input type = "hidden" name = "tset" id = "tset" value = "<?php echo $ccrow['settlement_type_id'];?>">
										
										<div class="col-md-3">
											<div class="form-group">
												<label for="service_description_edit">AMOUNT:</label>
												<input type="text" class = "form-control" id = "tamount" name = "tamount" data-validation="number"
												data-validation-allowing="float"
												data-validation-error-msg="Enter Amount">
											
											</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
												<div class = "form-group">
													<button class = "btn btn-success btn-flat" id = "filter">SAVE</button>
												</div>	
										</div>
										
				</div>
			</div>
		</div>
	</div>
	<script>
																$.validate({
																		form:'#transactionform',
																		validateOnBlur : false,
																		errorMessagePosition : 'top',
																		modules : 'security',
																		onSuccess : function($form) {
																		
																			 var formData = $('#transactionform').serializeArray();
																				 //var formData = new FormData($('#regform')[0]);
																				 $("#cusledger").html(loading);
																						$.ajax({
																							url :  'php/pos.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#cusledger").html(data);
																								
																							}
																						});

																		  return false; // Will stop the submission of the form
																		},
																	});
	</script>
	<?php
}
if(!empty($_POST['tapply']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
								
	//insert_creditline($branch,$tledid,$possetid,$possetamount,$sale['sales_invoice_number'],$or,'',$sss,$tran,$settle['pos_sales_settlement_id'],$agent);
	//insert_creditline($branch,$cus,$set_id,$amount,$apply_to,$source_number,$source,$proof,$remarks_sales,$remarks_payment,$user,$tdate,$tdue)
	insert_creditline($branch,$tledid,$tset,$tamount,$tapply,$tsource,'','','','',$agent,$tdue,'');
	
	?>
		<script>
			alert("New Ledger Transaction Added");
		</script>
	<?php
	ledger('',$tledid,'','','',0,0);
	
}
if(isset($_REQUEST['sreportui']))
{
	$level = $_REQUEST['sreportui'];
	?>
		<div class="box" style = "margin-top:10px;">
			<div class="box-header with-border">
				<h3 class="box-title">SALES REPORTS</h3>
			</div>
			<div class = "box-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
					  <li class="active"><a href="#tab_1" id = "sale" data-toggle="tab" style = "display:none;">SALES TRANSACTION</a></li>
					  <li><a href="#tab_1" ID = "cline" data-toggle="tab" style = "display:none;">CREDIT LINE TRANSACTION</a></li>
					  <li><a href="#tab_1" ID = "rebate" data-toggle="tab" style = "display:none;">REBATE TRANSACTION</a></li>
					  <li><a href="#tab_1" ID = "collection" data-toggle="tab">COLLECTION REPORT</a></li>
					  <li><a href="#tab_1" ID = "salesd" data-toggle="tab">SALES DETAILED REPORT</a></li>
					  <li><a href="#tab_1" ID = "salessm" data-toggle="tab">SALES SUMMARY REPORT</a></li>
					</ul>
					<script>
						$("#salessm").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/pos.php',
																		{
																			salessmui:<?php echo $level;?>
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#salesd").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/pos.php',
																		{
																			salesdui:<?php echo $level;?>
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$("#collection").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/finance.php',
																		{
																			collectionui:<?php echo $level;?>
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						$('#tab_1').html(loading);	
						
																$.post( 
																		'php/pos.php',
																		{
																			saletui:1
																		},
																		function(data) {
																			$('#tab_1').html(data);	
																			
																		});
						$("#sale").click(
							function(e)
							{
								e.preventDefault();
								
								$('#tab_1').html(loading);	
								
																$.post( 
																		'php/pos.php',
																		{
																			saletui:1
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
																		'php/pos.php',
																		{
																			rebatetui:1
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
																		'php/pos.php',
																		{
																			clinetui:1
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
if(!empty($_REQUEST['saletui']))
{
	$level = $_REQUEST['saletui'];
	?>
	<h2>CUSTOMER SALES TRANSACTIONS</h2>
	<div class="box">
			<div class="box-body">
				<form id = "cmsearchform" method = "POST">
					<div class = "row">
						<div class="col-md-3">
							 <div class="form-group">								
								<label for="age">SEARCH CUSTOMER:</label>
								<input type = "hidden" value = "<?php echo $level;?>" name = "saletlevel">
								<input type="text" id="ref" name="saletref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
								data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
								<input type="hidden" id="clickval">
								<div id = "search_result"></div>												
							</div>
						</div>
						
						<div class="col-md-5" style = "padding-top:25px;">
													<div class = "form-group">
														<button class = "btn btn-success btn-flat" id = "go">GO</button>
														
													</div>	
						</div>
					</div>
					<div id = "posalert"></div>
				</form>
				<script>
						
													$.validate({
																	form:'#cmsearchform',
																	validateOnBlur : false,
																	errorMessagePosition : 'top',
																	modules : 'security',
																	onSuccess : function($form) {
																	
																		 var formData = $('#cmsearchform').serializeArray();
																			
																			 //var formData = new FormData($('#regform')[0]);
																	
																					$.ajax({
																						url :  'php/pos.php',
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
											$("#ref").keyup(
													function()
													{
															$("#clickval").val("");
															var s = $("#ref").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
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
											
				</script>
			</div>
	</div>
	<div id = "saletui"></div>
	<?php
}
if(isset($_POST['saletref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_no = '$saletref'"));
	
	if(!empty($check))
	{?>
		<script>
			$.post( 
																	'php/pos.php',
																	 { saletui2:'<?php echo $check["customer_id"];?>',
																		saletui2level:'<?php echo $saletlevel;?>'
																	 },
																	 function(data) {
																		$('#saletui').html(data);
																	 });
		</script>
		<?php
		
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#posalert");
			</script>
		<?php
	}
}
if(!empty($_REQUEST['saletui2']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	$print = 0;
	if($saletui2level == 1)
		$print = 1;
	?>
	<div class="box box">
		<div class="box-body">
			<button class = "btn btn-warning btn-flat" id = "saleprint">PRINT RESULT</button>
		</div>
	</div>
	<script>
		$("#saleprint").click(
			function()
			{
				$.post( 
																				'php/pos.php',
																				{
																					pscus:<?php echo $saletui2;?>
																					
																				},
																				function(data) {
																					$('#click').html(data);	
																					
																				});
			}
		);
	</script>
	<div class="box box">
		<div class="box-body">
			<?php smonitor($saletui2,'','','',$print);?>
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['pscus']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$bheader = "ALL BRANCH";
		
		if($psbranch != 'all')
		{
			$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $psbranch"));
			$bheader = $br['branch_description']." Branch";
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CUSTOMER SALES REPORT</h4>

			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">CUSTOMER INFORMATION</h3>
				</div>
				<div class="box-body">
					<?php pos_customer_info($pscus);?>
				</div>
			</div>
			<?php
				smonitor($pscus,'','','',1);
				
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
if(!empty($_REQUEST['rebatetui']))
{
	$level = $_REQUEST['rebatetui'];
	?>
	<h2>CUSTOMER REBATE TRANSACTIONS</h2>
	<div class="box">
			<div class="box-body">
				<form id = "cmsearchform" method = "POST">
					<div class = "row">
						<div class="col-md-3">
							 <div class="form-group">								
								<label for="age">SEARCH CUSTOMER:</label>
								<input type = "hidden" value = "<?php echo $level;?>" name = "rebatetlevel">
								<input type="text" id="ref" name="rebatetref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
								data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
								<input type="hidden" id="clickval">
								<div id = "search_result"></div>												
							</div>
						</div>
						
						<div class="col-md-5" style = "padding-top:25px;">
													<div class = "form-group">
														<button class = "btn btn-success btn-flat" id = "go">GO</button>
														
													</div>	
						</div>
					</div>
					<div id = "posalert"></div>
				</form>
				<script>
						
													$.validate({
																	form:'#cmsearchform',
																	validateOnBlur : false,
																	errorMessagePosition : 'top',
																	modules : 'security',
																	onSuccess : function($form) {
																	
																		 var formData = $('#cmsearchform').serializeArray();
																			
																			 //var formData = new FormData($('#regform')[0]);
																	
																					$.ajax({
																						url :  'php/pos.php',
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
											$("#ref").keyup(
													function()
													{
															$("#clickval").val("");
															var s = $("#ref").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
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
											
				</script>
			</div>
	</div>
	<div id = "rebatetui"></div>
	<?php
}
if(isset($_POST['rebatetref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_no = '$rebatetref'"));
	
	if(!empty($check))
	{?>
		<script>
			$.post( 
																	'php/pos.php',
																	 { rebatetui2:'<?php echo $check["customer_id"];?>',
																		rebatetui2level:'<?php echo $rebatetlevel;?>'
																	 },
																	 function(data) {
																		$('#rebatetui').html(data);
																	 });
		</script>
		<?php
		
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#posalert");
			</script>
		<?php
	}
}
if(!empty($_REQUEST['rebatetui2']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	$print = 0;
	if($rebatetui2level == 1)
		$print = 1;
	?>
	<div class="box box">
		<div class="box-body">
			<button class = "btn btn-warning btn-flat" id = "saleprint">PRINT RESULT</button>
		</div>
	</div>
	<script>
		$("#saleprint").click(
			function()
			{
				$.post( 
																				'php/pos.php',
																				{
																					prcus:<?php echo $rebatetui2;?>
																					
																				},
																				function(data) {
																					$('#click').html(data);	
																					
																				});
			}
		);
	</script>
	<div class="box box">
		<div class="box-body">
			<?php rebate_ledger('',$rebatetui2,'','','',$print);?>
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['prcus']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CUSTOMER REBATE TRANSACTIONS</h4>

			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">CUSTOMER INFORMATION</h3>
				</div>
				<div class="box-body">
					<?php pos_customer_info($prcus);?>
				</div>
			</div>
			<?php
				rebate_ledger('',$prcus,'','','',1);
				
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
if(!empty($_REQUEST['clinetui']))
{
	$level = $_REQUEST['clinetui'];
	?>
	<h2>CUSTOMER CREDIT LINE TRANSACTIONS</h2>
	<div class="box">
			<div class="box-body">
				<form id = "cmsearchform" method = "POST">
					<div class = "row">
						<div class="col-md-3">
							 <div class="form-group">								
								<label for="age">SEARCH CUSTOMER:</label>
								<input type = "hidden" value = "<?php echo $level;?>" name = "clinetlevel">
								<input type="text" id="ref" name="clinetref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
								data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
								<input type="hidden" id="clickval">
								<div id = "search_result"></div>												
							</div>
						</div>
						
						<div class="col-md-5" style = "padding-top:25px;">
													<div class = "form-group">
														<button class = "btn btn-success btn-flat" id = "go">GO</button>
														
													</div>	
						</div>
					</div>
					<div id = "posalert"></div>
				</form>
				<script>
						
													$.validate({
																	form:'#cmsearchform',
																	validateOnBlur : false,
																	errorMessagePosition : 'top',
																	modules : 'security',
																	onSuccess : function($form) {
																	
																		 var formData = $('#cmsearchform').serializeArray();
																			
																			 //var formData = new FormData($('#regform')[0]);
																	
																					$.ajax({
																						url :  'php/pos.php',
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
											$("#ref").keyup(
													function()
													{
															$("#clickval").val("");
															var s = $("#ref").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
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
											
				</script>
			</div>
	</div>
	<div id = "clinetui"></div>
	<?php
}
if(isset($_POST['clinetref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_no = '$clinetref'"));
	
	if(!empty($check))
	{?>
		<script>
			$.post( 
																	'php/pos.php',
																	 { clinetui2:'<?php echo $check["customer_id"];?>',
																		clinetui2level:'<?php echo $clinetlevel;?>'
																	 },
																	 function(data) {
																		$('#clinetui').html(data);
																	 });
		</script>
		<?php
		
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#posalert");
			</script>
		<?php
	}
}
if(!empty($_REQUEST['clinetui2']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	$print = 0;
	if($clinetui2level == 1)
		$print = 1;
	?>
	<div class="box box">
		<div class="box-body">
			<button class = "btn btn-warning btn-flat" id = "saleprint">PRINT RESULT</button>
		</div>
	</div>
	<script>
		$("#saleprint").click(
			function()
			{
				$.post( 
																				'php/pos.php',
																				{
																					pccus:<?php echo $clinetui2;?>
																					
																				},
																				function(data) {
																					$('#click').html(data);	
																					
																				});
			}
		);
	</script>
	<div class="box box">
		<div class="box-body">
			<?php cline_ledger($clinetui2,'','','',$print);?>
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['pccus']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CUSTOMER CREDIT LINE TRANSACTIONS</h4>

			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">CUSTOMER INFORMATION</h3>
				</div>
				<div class="box-body">
					<?php pos_customer_info($pccus);?>
				</div>
			</div>
			<?php
				cline_ledger($pccus,'','','',1);
				
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
if(isset($_REQUEST['deleteinvoice']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
								
	//$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $deleteinvoice"));
	$del = mysqli_query($con,"Update pos_sales set isdeleted = 1, voided_datetime = NOW(), voided_by_fullname = $user where pos_sales_id = $deleteinvoice");
	mysqli_query($con,"Update credit_line_transaction set isdeleted = 1 where source_id = $deleteinvoice and source_remarks = 'POS'");
	mysqli_query($con,"Update ledger_receivable set isdeleted = 1 where remarks_sales = $deleteinvoice");
	mysqli_query($con,"Update ledger_sales_income set isdeleted = 1,datetime_deleted = NOW(), deleted_by_fullname = '$agent' where pos_sales_id = $deleteinvoice");
	mysqli_query($con,"Update ledger_rebate set isdeleted = 1 where pos_sales_id = $deleteinvoice");
	mysqli_query($con,"Update pos_sales_settlement set isdeleted = 1 where pos_sales_id = $deleteinvoice");
	mysqli_query($con,"Update pos_sales_detail set isdeleted = 1 where pos_sales_id = $deleteinvoice");
	if(!$del)
	{
		
		?>
			<script>
				alert("Error Deleting Sales Transaction , contact the system administrator");
			</script>
		<?php
	}
	?>
		<div class="box box">
			<div class="box-body">
				<?php smonitor($deleteinvoicecus,$deleteinvoicebranch,$deleteinvoicedfrom,$deleteinvoicedto,0);?>
			</div>
		</div>
	<?php
	
}
if(isset($_REQUEST['salesdui']))
{
	$level = $_REQUEST['salesdui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
	<h2>SALES DATAILED REPORT</H2>
	<div class="box">
		<div class="box-body">
				<form id = "pfilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>Date From:</label>
												<input type = "date" class = "form-control" name = "sddfrom" id = "sddfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
											</div>		
										</div>
										<div class="col-md-4">
											<div class = "form-group">
												<label>Enter Date To:</label>
												<input type = "date" class = "form-control" name = "sddto" id = "sddto" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
											</div>
										</div>
										
										<?php
										if($level == 1)
										{
											?>
												<div class="col-md-4">
												<?php
												$cquery = mysqli_query($con,"Select * from lup_branch where isdeleted = 0");
												?>
													<div class="form-group">
														  <label for="age">BRANCH:</label>
															
															<select  name = "sdbranch" id = "sdbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "sdbranch" name = "sdbranch">
											<?php
										}
										?>
										
										<div class="col-md-3" style = "padding-top:25px;">
												<div class = "form-group">
													<button class = "btn btn-success btn-flat" id = "filter">FILTER</button>
													<button class = "btn btn-warning btn-flat" id = "print">PRINT RESULT</button>
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
																	
																			$.post( 
																				'php/pos.php',
																				{
																					psdbranch:$("#sdbranch").val(),
																					psddto:$("#sddto").val(),
																					psddfrom:$("#sddfrom").val(),
																					psdtranfrom:$("#sdtranfrom").val()
																					
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
																				 $("#smonitorui").html(loading);
																						$.ajax({
																							url :  'php/pos.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#salesdui").html(data);
																								
																							}
																						});

																		  return false; // Will stop the submission of the form
																		},
																	});
											}
										);
									
									</script>
									
				
					</form>	
			</div>
		</div>
	</div>
		<div id = "salesdui">
				
		</div>
	<?php
}
if(isset($_POST['sddto']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div class="box box">
		<div class="box-body">
			<?php sales_detail($sdbranch,$sddfrom,$sddto,0);?>
		</div>
	</div>
	<?php
}

if(isset($_REQUEST['psddto']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$bheader = "ALL BRANCH";
		
		if($psdbranch != 'all')
		{
			$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $psdbranch"));
			$bheader = $br['branch_description']." Branch";
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">SALES DETAILED REPORT</h4>
			<h4 style = "text-align:center"><?php echo $bheader;?></h4>
			<h4 style = "text-align:center"><?php echo $psddfrom." to ".$psddto;?></h4>
			
			<?php
				sales_detail($psdbranch,$psddfrom,$psddto,1);
				
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

if(isset($_REQUEST['salessmui']))
{
	$level = $_REQUEST['salessmui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
	<h2>SALES SUMMARY REPORT</H2>
	<div class="box">
		<div class="box-body">
				<form id = "pfilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>Date From:</label>
												<input type = "date" class = "form-control" name = "smdfrom" id = "smdfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
											</div>		
										</div>
										<div class="col-md-4">
											<div class = "form-group">
												<label>Enter Date To:</label>
												<input type = "date" class = "form-control" name = "smdto" id = "smdto" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
											</div>
										</div>
										
										<?php
										if($level == 1)
										{
											?>
												<div class="col-md-4">
												<?php
												$cquery = mysqli_query($con,"Select * from lup_branch where isdeleted = 0");
												?>
													<div class="form-group">
														  <label for="age">BRANCH:</label>
															
															<select  name = "smbranch" id = "smbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "smbranch" name = "smbranch">
											<?php
										}
										?>
										
										<div class="col-md-3" style = "padding-top:25px;">
												<div class = "form-group">
													<button class = "btn btn-success btn-flat" id = "filter">FILTER</button>
													<button class = "btn btn-warning btn-flat" id = "print">PRINT RESULT</button>
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
																	
																			$.post( 
																				'php/pos.php',
																				{
																					psmbranch:$("#smbranch").val(),
																					psmdto:$("#smdto").val(),
																					psmdfrom:$("#smdfrom").val(),
																					psmtranfrom:$("#smtranfrom").val()
																					
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
																				 $("#smonitorui").html(loading);
																						$.ajax({
																							url :  'php/pos.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#salesdui").html(data);
																								
																							}
																						});

																		  return false; // Will stop the submission of the form
																		},
																	});
											}
										);
									
									</script>
									
				
					</form>	
			</div>
		</div>
	</div>
		<div id = "salesdui">
				
		</div>
	<?php
}
if(isset($_POST['smdto']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div class="box box">
		<div class="box-body">
			<?php sales_summary($smbranch,$smdfrom,$smdto,0);?>
		</div>
	</div>
	<?php
}

if(isset($_REQUEST['psmdto']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$bheader = "ALL BRANCH";
		
		if($psmbranch != 'all')
		{
			$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $psmbranch"));
			$bheader = $br['branch_description']." Branch";
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">SALES SUMMARY REPORT</h4>
			<h4 style = "text-align:center"><?php echo $bheader;?></h4>
			<h4 style = "text-align:center"><?php echo $psmdfrom." to ".$psmdto;?></h4>
			
			<?php
				sales_summary($psmbranch,$psmdfrom,$psmdto,1);
				
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
if(isset($_REQUEST['collectionui']))
{
	$level = $_REQUEST['collectionui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
	<h2>CREDIT COLLECTION MONITORING</H2>
	<div class="box">
		<div class="box-body">
				<form id = "pfilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>Date From:</label>
												<input type = "date" class = "form-control" name = "cdfrom" id = "cdfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
											</div>		
										</div>
										<div class="col-md-4">
											<div class = "form-group">
												<label>Enter Date To:</label>
												<input type = "date" class = "form-control" name = "cdto" id = "cdto" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
											</div>
										</div>
									
										<?php
										if($level == 1)
										{
											?>
												<div class="col-md-4">
												<?php
												$cquery = mysqli_query($con,"Select * from lup_branch where isdeleted = 0");
												?>
													<div class="form-group">
														  <label for="age">BRANCH:</label>
															
															<select  name = "cbranch" id = "cbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "cbranch" name = "cbranch">
											<?php
										}
										?>
										
										<div class="col-md-4" style = "display:none;">
												
													<div class="form-group">
														  <label for="age">PAID/UPDAID:</label>
															
															<select  name = "cpaid" id = "cpaid" class="form-control" data-validation="required"
																	data-validation-error-msg="Select PAID/UPDAID">
																	<option "Selected" value = "all">ALL</option>
																	<option "Selected" value = "paid">PAID</option>
																	<option "Selected" value = "unpaid">UNPAID</option>
															</select>
															
													</div>
												</div>
										<div class="col-md-3" style = "padding-top:25px;">
												<div class = "form-group">
													<button class = "btn btn-success btn-flat" id = "filter">REFRESH</button>
													<button class = "btn btn-warning btn-flat" id = "print">PRINT RESULT</button>
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
																				'php/pos.php',
																				{
																					pcbranch:$("#sbranch").val(),
																					pcdto:$("#sdto").val(),
																					pcdfrom:$("#sdfrom").val()
																					
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
																				 $("#smonitorui").html(loading);
																						$.ajax({
																							url :  'php/pos.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#smonitorui").html(data);
																								
																							}
																						});

																		  return false; // Will stop the submission of the form
																		},
																	});
											}
										);
									
									</script>
									
				
					</form>	
			</div>
		</div>
	</div>
	<div class="box">
		<div class="box-body">
				<form id = "pifilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>ENTER INVOICE NUMBER/CUSTOMER NO/LAST NAME:</label>
												<input type="text" id="ref" name="fcinvoice" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
												data-validation="required" data-validation-error-msg="INVOICE NUMBER/CUSTOMER NO/LAST NAME">
												<input type="hidden" id="clickval">
												<div id = "search_result"></div>
											</div>		
										</div>
										
									
										<?php
										if($level == 1)
										{
											?>
												<div class="col-md-4">
												<?php
												$cquery = mysqli_query($con,"Select * from lup_branch where isdeleted = 0");
												?>
													<div class="form-group">
														  <label for="age">BRANCH:</label>
															
															<select  name = "fcbranch" id = "fcbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "fcbranch" name = "fcbranch">
											<?php
										}
										?>
										
									
										<div class="col-md-3" style = "padding-top:25px;">
												<div class = "form-group">
													<button class = "btn btn-success btn-flat" id = "pfilter">SEARCH</button>
													
												</div>	
										</div>
									
									
									
								
								
								
								<script>
										$("#ref").keyup(
													function()
													{
															$("#clickval").val("");
															var s = $("#ref").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
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
										
										$("#pfilter").click(
											function()
											{
													$.validate({
																		form:'#pifilterform',
																		validateOnBlur : false,
																		errorMessagePosition : 'top',
																		modules : 'security',
																		onSuccess : function($form) {
																		
																			 var formData = $('#pifilterform').serializeArray();
																				 //var formData = new FormData($('#regform')[0]);
																				 $("#smonitorui").html(loading);
																						$.ajax({
																							url :  'php/pos.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#smonitorui").html(data);
																								
																							}
																						});

																		  return false; // Will stop the submission of the form
																		},
																	});
											}
										);
									
									</script>
									
				
					</form>	
			</div>
		</div>
	</div>
	
		<div id = "smonitorui">
				
		</div>
	<?php
}
if(isset($_POST['fcinvoice']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div class="box box">
		<div class="box-body">
			<?php collection($fcinvoice,$fcbranch,'','',0,0);?>
		</div>
	</div>
	<?php
}
if(isset($_POST['cdto']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div class="box box">
		<div class="box-body">
			<?php collection('',$cbranch,$cdfrom,$cdto,$cpaid,0);?>
		</div>
	</div>
	<?php
}

if(isset($_REQUEST['psdto']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$bheader = "ALL BRANCH";
		
		if($psbranch != 'all')
		{
			$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $psbranch"));
			$bheader = $br['branch_description']." Branch";
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">SALES REPORT</h4>
			<h4 style = "text-align:center"><?php echo $bheader;?></h4>
			<h4 style = "text-align:center"><?php echo $psdfrom." to ".$psdto;?></h4>
			
			<?php
				smonitor('',$psbranch,$psdfrom,$psdto,1);
				
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
if(isset($_REQUEST['printinvoice2']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
				
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $printinvoice2"));
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch_name($user)." Branch";
	?>
		<div id = "printt">
			
			<h3><?php echo get_company();?></h3>
			<h5><?php echo $branch;?></h5>
			<br>
			<h4>SALES INVOICE</h4>
			<h4 style = "font-weight:bold;">#<?php echo $row['sales_invoice_number'];?></h4>
			<br>
			<?php //invoice_customer_info2($row['customer_id']);?>
			<br>
			<?php pos_item_list2($row['pos_sales_id'],1);?>
			<?php pos_settlement_list2($row['pos_sales_id'],1);?>
			
			
			
			<table class = "table table-condense">
				<tr>
					<td>
						Printed by:<br><br>
						<p style = "border-bottom:1px solid #000;text-align:center;width:200px;font-weight:bold;"><?php echo $agent;?></p>
					</td>
				</tr>
			</table>
			
		</div>
		<script>
			$('#printt').printThis(
				{
														base: "true",
														importCSS: true,
														importStyle: true
				}
			);
			window.onafterprint = window.close; 
		</script>
		
	<?php
}
if(isset($_REQUEST['posui']))
{
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	$inv = mysqli_num_rows(mysqli_query($con,"Select * from lup_invoice_number where isdeleted = 0 and pos_sales_id = 0"));
	if($inv != 0)
	{
	?>
				<form id = "cmsearchform" method = "POST">
					
								<input type="hidden" id="posui2" name="posui2" value = "cus">
								<input type="hidden" id="clickval">
								<div id = "search_result"></div>												
				
				</form>
				<script>
											
																	
																		 var formData = $('#cmsearchform').serializeArray();
																			
																			 //var formData = new FormData($('#regform')[0]);
																		$("#maincontent").html(loading);
																					$.ajax({
																						url :  'php/pos.php',
																						type : 'post',
																						datatype : 'json',
																						data : formData,
										
																						success : function(data) {
																							$("#maincontent").html(data);
																							
																						}
																					});

																	 
				</script>

	<?php
	}
	else
	{
		?>
			<h2>No Invoice Number Available</h2>
		<?php
	}
}
if(isset($_REQUEST['posui2'])||isset($_REQUEST['pos_orderui']))
{
	if(isset($_REQUEST['posui2']))
		$id = $_REQUEST['posui2'];
	
	if(isset($_SESSION['prevtran']))
		$_SESSION['tran'] = $_SESSION['prevtran'];
	
	if(isset($_REQUEST['takeorderui']))
	{
		$user = $_REQUEST['takeorderui'];
		$trow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where sales_invoice_number = '' and created_by_fullname = $user and isdeleted = 0"));
		if(!empty($trow))
		{
			$check = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales, se_user where pos_sales.pos_sales_id = $_SESSION[tran]
			and pos_sales.created_by_fullname = se_user.user_id and se_user.istable = 0"));
			if($check > 0)
			{
				$_SESSION['prevtran'] = $_SESSION['tran'];
			}
			else
			{
				$_SESSION['prevtran'] = "";
			}
		
			$_SESSION['tran'] = $trow['pos_sales_id'];
		}
		else
		{
			$_SESSION['tran'] = '';
		}
	}
	else
	{
		$user = get_user_id($_SESSION['c_craft']);
	}
	
	$cid = 0;
	$cfullname = 'cus';
	if(!empty($_REQUEST['pos_orderui']))
	{
		$check = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales, se_user where pos_sales.pos_sales_id = $_SESSION[tran]
		and pos_sales.created_by_fullname = se_user.user_id and se_user.istable = 0"));
			if($check > 0)
			{
				$_SESSION['prevtran'] = $_SESSION['tran'];
			}
			else
			{
				$_SESSION['prevtran'] = "";
			}
			
		$_SESSION['tran'] = $_REQUEST['pos_orderui']; 
	}
	if(!empty($_REQUEST['posorderui']))
	{
		$cfullname = get_customer_fullname($id);
		$cid = $id;
	}
	
	$trn = 0;
	if(!empty($_SESSION['tran']))
	{
		$trn = $_SESSION['tran'];
	}
	$branch = get_branch($user);
	$agent = get_agent($user);
	
	?>
		<script>
			$.post( 
																'php/pos.php',
																{
																	renderuii:1
																	
																},
																function(data) {
																	$('#renderui').html(data);
																
																});
												$.post( 
																'php/pos.php',
																{
																	changeuii:1
																	
																},
																function(data) {
																	$('#changeui').html(data);
																
																});
		</script>
	<?php
	if(empty($_SESSION['tran']))
	{
		$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
		mysqli_query($con,"insert into pos_sales set
		branch_id = '$branch',
		sales_invoice_number = '',
		customer_id = $cid,
		customer_fullname = '$cfullname',
		result = '$result',
		created_by_fullname = '$user',
		remarks = '',
		isdeleted = 0
		");
		
		$_SESSION['tran'] = '';
		$sales_id = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where result = '$result'"));
		$_SESSION['prev'] = $sales_id['pos_sales_id'];
		$_SESSION['tran'] = $sales_id['pos_sales_id'];
	}
	if($cid == 0)
	{
		mysqli_query($con,"Update pos_sales set customer_id = 0, customer_fullname = '$cfullname' where pos_sales_id = $_SESSION[tran]");
	}
	else{
		mysqli_query($con,"Update pos_sales set customer_id = $cid, customer_fullname = '$cfullname' where pos_sales_id = $_SESSION[tran]");
	}
	
	$sales_id = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $_SESSION[tran]"));
	
	if(empty($_SESSION['prev']))
		$_SESSION['prev'] = 0;
	
	$pcheck = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $_SESSION[prev]
	and sales_invoice_number = ''"));
	
	if($pcheck != 0)
	{
		mysqli_query($con,"Update lup_invoice_number set pos_sales_id = 0 where pos_sales_id = $_SESSION[prev]");
	}
	
	$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_invoice_number where isdeleted = 0 and branch_id = $branch
	and pos_sales_id =0"));
	
	mysqli_query($con,"Update lup_invoice_number set pos_sales_id = $_SESSION[tran] where invoice_number_id = $irow[invoice_number_id]");
	
	$totalq = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as total from pos_sales_detail where 
	pos_sales_id = $_SESSION[tran] and isdeleted = 0"));
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where 
	pos_sales_id = $_SESSION[tran] and isdeleted = 0"));
	//echo $_SESSION['tran']." _AA";
	if(!empty($_REQUEST['iscus']))
	{
		?>
			<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">CUSTOMER INFORMATION</h3>
						</div>
						<div class="box-body">
							<?php pos_customer_info($id);?>
						</div>
					</div>
		<?php
	}
	?>
					<div class="nav-tabs-custom" style = "display:none;">
						<ul class="nav nav-tabs">
						  <li class="active"><a href="#tab_1" data-toggle="tab">CATEGORY</a></li>
						</ul>
						<div class="tab-content">
						  <div class="tab-pane active" id="tab_1">
							
							<?php
							$btn = array('btn btn-primary btn-flat','btn btn-secondary btn-flat','btn btn-success btn-flat','btn btn-info btn-flat','btn btn-warning btn-flat','btn btn-danger btn-flat');
					
	
							
							$cquery = mysqli_query($con,"Select * from pos_lup_category where isdeleted = 0 and visible = 1");
							$ctr = 0;
							$c = 1;
							while($crow = mysqli_fetch_assoc($cquery))
							{
								?>
									<input type = "hidden" id = "itemclassval<?php echo $c;?>" value = "<?php echo $crow['category_id'];?>">
									<button class = "<?php echo $btn[$ctr];?>" id = "itemclass<?php echo $crow['category_id'];?>"><?php echo $crow['category_description'];?></button>	
									<script>
										$("#itemclass<?php echo $crow['category_id'];?>").click(
											function()
											{
												//alert("aaaaa");
												var i = $("#itemclassval<?php echo $c;?>").val();
												$('#itemlistui').html(loading);
												 $.post( 
																 'php/pos.php',
																 {
																	 positems:i
																},
																 function(data) {
																	$('#itemlistui').html(data);
																
																 });
											}
										);
									</script>
								<?php
								$ctr++;
								$c++;
								if($ctr >= 6)
								{
									$ctr = 0;
								}
							}
							?>
							
						  </div>  				  
						</div>
					</div>
	<div class = "row" style = "margin-top:-15px;text-align:right;height:50px;display:none;">
			<div class="col-md-4" style = "text-align:left;">
			
			</div>
			<div class="col-md-2" style = "text-align:right;">
			<h4>TOTAL QUANTITY</h4>
			</div>
			<div class="col-md-2" style = "text-align:right;">
			<h4>TOTAL SALES</h4>
			</div>
	</div>
		
	<div class="callout callout-warning" style = "margin-top:-10px;text-align:right;height:80px;">
        <div class = "row" style = "margin-top:-15px;">
			<div class="col-md-4" style = "text-align:left;padding-top:20px;">
				<div class = "form-group">
					<button class = "btn btn-primary btn-flat btn-sm" id = "fin">BROWSE</button>	
					<?php
					if(empty($_REQUEST['pos_orderui']))
					{
						?>
							<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">RESET</button>
						<?php
					}
					?>
						
				</div>
			</div>
			<div class="col-md-3" id = "totalqntyui" style = "text-align:right;">
			<h3>TOTAL QUANTITY: <?php echo number_format($totalq['total'],2);?></h3>
			</div>
			<div class="col-md-5" id = "totalui" style = "text-align:right;">
			<h3>TOTAL SALES: <?php echo number_format($total['total'],2);?></h3>
			</div>
		</div>
    </div>
		<script>
				$("#barcode").focus();
				$("#fin").click(
							function()
							{
												$('#positemlist').html(loading);
												$.post( 
																 'php/pos.php',
																 {
																	 poscategoryui:'<?php echo $_SESSION['tran'];?>'
																	 
																},
																 function(data) {
																	$('#positemlist').html(data);
																	$("#itemtitle").html('BROWSE ITEMS');
																 });
												$.post( 
																 'php/pos.php',
																 {
																	 
																	 itemtoggleui:1
																},
																 function(data) {
																	$('#item-toggle').html(data);
																	
																 });
																 
							}
						);
						
			$("#cancel").click(
				function()
				{
												$.post( 
																 'php/pos.php',
																 {
																	 cleartran:1
																},
																 function(data) {
																	$('#click').html(data);
																
																 });
																 
					$('#maincontent').html(loading);
					
					<?php
						if($cid == 0)
						{
							?>
								$.post( 
																 'php/pos.php',
																 {
																	 posui:1
																},
																 function(data) {
																	$('#maincontent').html(data);
																	
																
																 });
							<?php
						}
						else{
							?>
								$.post( 
																 'php/pos.php',
																 {
																	 cposui:1
																},
																 function(data) {
																	$('#maincontent').html(data);
																	
																
																 });
							<?php
						}
					?>
													
				}
			);
		</script>
	<div class = "row" style = "margin-top:-12px;">
		<div class="col-lg-4">
			
				<div id = "settleui">
					
				</div>
				<?php
					if(!empty($_REQUEST['pos_orderui']))
					{
						?>
							<div class = "box">
								<div class = "box-body" style = "text-align:center;">
									<?php
										$ct = mysqli_fetch_assoc(mysqli_query($con,"Select fullname from se_user where user_id = $sales_id[created_by_fullname]"));
										echo "<h2>".$ct['fullname']."</h2>";
									?>
								</div>
							</div>
						<?php
					}
				?>
				
			
		</div>
		
			
		<div class="col-lg-8">
			
			<div class="box" style = "margin-top:-5px;">
			
				<div class="box-body" id = "itemlistui">
					<label>SEARCH ITEMS</label>
					<input type = "text" class = "form-control" id = "barcode"  style = "margin:auto;
					background-color:#fff;border-radius:5px;font-size:20px;font-weight:bold;" autocomplete="off">
					<div id = "search_result"></div>
					<div id = "qntyui"></div>						
					<?php //pos_category('');?>
					<script>
											/*$("#barcode").keyup(
													function(e)
													{
															$("#clickval").val("");
															var s = $("#barcode").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
																	 { itemq: s },
																	 function(data) {
																		$('#search_result').html(data);
																	 });
																}
																else
																{
																	$('#search_result').html("");
																}
													});*/
						
						$("#barcode").keyup(
							function(e)
							{
									var key = e.which;
									
									if(key == 13)
									{
										if($("#barcode").val() != '')
										{
											//$("#barcode").val('');
											
													$.post( 
																 'php/pos.php',
																 {
																	 add_item_id:$("#barcode").val(),
																	 add_sales_id:'<?php echo $_SESSION['tran'];?>',
																	 frombarcode:1
																},
																 function(data) {
																	$('#click').html(data);
																	
																 });
										}
									}
									else if(key != 13 && key != 40)
									{
											var s = $("#barcode").val();
																	if(s != "")
																	{
																		$.post( 
																		'php/main.php',
																		 { itemq: s },
																		 function(data) {
																			$('#search_result').html(data);
																		 });
																	}
																	else
																	{
																		$('#search_result').html("");
																	}
									}
									
								
							}
						);
						$('#settleui').html(loading);
						
							$.post( 
												'php/pos.php',
												{
													fin:1,
													fiscus:0
													
												},
												function(data) {
													$('#settleui').html(data);		
												});
					</script>
				</div>
			</div>	
			<div class="box" style = "margin-top:-15px;">
				<div class="box-header with-border">
					<h3 class="box-title" id = "itemtitle">ITEM LIST</h3>
					  <div class="box-tools pull-right" id = "item-toggle">
						
					  </div>
					</div
				</div>
				<div class="box-body">
					<div class="box" style = "margin-top:-12px;overflow:auto;height:500px;">
						<div class="box-body" id = "positemlist">
							<?php pos_item_list($_SESSION['tran'],0,0);?>
						</div>
					</div>
				</div>
			</div>
		
					<div class = "row">
						<div class="col-lg-5">
							<div class="callout callout-success" style = "height:80px;">
								<H5>AMOUNT RENDER:</H5><h2 style = "float:right;margin-top:-15px" id = "renderui">0.00</h2>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="callout callout-warning" style = "height:80px;">
								<H5>CHANGE:</H5><h2 style = "float:right;margin-top:-15px" id = "changeui">0.00</h2>
							</div>
						</div>
					</div>
			
		</div>
	</div>		
	<?php
}
if(!empty($_REQUEST['poscategoryui']))
{
	pos_category($_REQUEST['poscategoryui'],'');
}
if(!empty($_REQUEST['itemtoggleui']))
{
	?>
	<button type="button" class="btn btn-primary btn-xs btn-flat" id = "iback">BACK</button>
	<button type="button" class="btn btn-success btn-xs btn-flat" id = "ilist">ITEM LIST</button>
	<script>
		$("#iback").hide();
		$("#iback").click(
			function()
			{
				$.post( 
												'php/pos.php',
												{
													poscategoryui:'<?php echo $_SESSION['tran'];?>'
												},
												function(data) {
													$('#positemlist').html(data);
													$("#iback").hide();
												});
			}
		);
		$("#ilist").click(
			function()
			{
										$.post( 
												'php/pos.php',
												{
													retitemlist:1
												},
												function(data) {
													$('#positemlist').html(data);
													$("#itemtitle").html('ITEMS LIST');
													$("#item-toggle").html('');
												});
			}
				
		);
	</script>
	<?php
}
if(!empty($_REQUEST['oitemtoggleui']))
{
	?>
	<button type="button" class="btn btn-primary btn-xs btn-flat" id = "iback">BACK</button>
	<button type="button" class="btn btn-success btn-xs btn-flat" id = "ilist">ITEM LIST</button>
	<script>
		$("#iback").hide();
		$("#iback").click(
			function()
			{
										$.post( 
												'php/pos.php',
												{
													poscategoryui:'<?php echo $_SESSION['order'];?>'
												},
												function(data) {
													$('#positemlist').html(data);
													$("#iback").hide();
												});
			}
		);
		$("#ilist").click(
			function()
			{
										$.post( 
												'php/pos.php',
												{
													oretitemlist:1
												},
												function(data) {
													$('#positemlist').html(data);
													$("#itemtitle").html('ITEMS LIST');
													$("#item-toggle").html('');
												});
			}
				
		);
	</script>
	<?php
}
if(!empty($_REQUEST['oretitemlist']))
{
	pos_item_list($_SESSION['order'],0,1);
}

if(!empty($_REQUEST['retitemlist']))
{
	pos_item_list($_SESSION['tran'],0);
}

if(!empty($_REQUEST['cleartran']))
{
	mysqli_query($con,"Update pos_sales_detail set isdeleted = 1 where pos_sales_id = $_SESSION[tran]");
	mysqli_query($con,"Update pos_sales_settlement set isdeleted = 1 where pos_sales_id = $_SESSION[tran]");
}
if(!empty($_REQUEST['clearorder']))
{
	mysqli_query($con,"Update pos_sales_detail set isdeleted = 1 where pos_sales_id = $_SESSION[order]");
	mysqli_query($con,"Update pos_sales_settlement set isdeleted = 1 where pos_sales_id = $_SESSION[order]");
}
if(!empty($_REQUEST['renderuii']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount) as total from pos_sales_settlement where isdeleted = 0 and pos_sales_id = $_SESSION[tran]"));
	
	echo number_format($total['total'],2);
}
if(!empty($_REQUEST['changeuii']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select change_amount as total from pos_sales_settlement where isdeleted = 0 and pos_sales_id = $_SESSION[tran] order by pos_sales_settlement_id DESC"));
	echo number_format($total['total'],2);
}
if(isset($_REQUEST['prepui']))
{
	$level = $_REQUEST['prepui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	?>
	<h2>ORDER PREPARATIONS</H2>
	<div class="box">
		<div class="box-body">
				<form id = "pfilterform" method = "POST">
									<div class = "row">	
										<div class="col-md-3">
												<div class = "form-group">
													<button class = "btn btn-primary btn-flat" id = "refresh">REFRESH</button>
													<button class = "btn btn-success btn-flat" id = "done">SHOW DONE</button>
													<button class = "btn btn-warning btn-flat" id = "print">PRINT RESULT</button>
													<input type = "hidden" id = "doneval" value = "0">
												</div>	
										</div>
								<script>
										$("#done").click(
											function(e)
											{
												e.preventDefault();
												
												$("#doneval").val("1");
															$('#smonitorui').html(loading);	
																		$.post( 
																				'php/pos.php',
																				{
																					showdone:1
																				},
																				function(data) {
																					$('#smonitorui').html(data);	
																					
																				});
											}
										);
										
										$("#refresh").click(
											function(e)
											{
												e.preventDefault();
													$("#doneval").val("0");
															$('#smonitorui').html(loading);	
																		$.post( 
																				'php/pos.php',
																				{
																					refreshprep:1
																				},
																				function(data) {
																					$('#smonitorui').html(data);	
																					
																				});
											}
										);
										
										$("#print").click(
											function()
											{
												$.validate({
																		form:'#pfilterform',
																		validateOnBlur : false,
																		errorMessagePosition : 'top',
																		modules : 'security',
																		onSuccess : function($form) {
																			$.post( 
																				'php/pos.php',
																				{
																					pdoneval:$("#doneval").val()
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
									
				
					</form>	
			</div>
		</div>
	</div>
		<div id = "markalert"></div>
		<div class="box">
			<div class="box-body" id = "smonitorui">
				<?php prep(0,0);?>	
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['doneprep']))
{
	$id = $_REQUEST['doneprep'];
	
	$del = mysqli_query($con,"Update pos_sales_detail set done = 1, done_date = NOW() where pos_sales_detail_id = $id");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Marked as done","#markalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Marking Order, contact the system administrator","#markalert");
			</script>
		<?php
	}
	
	prep(0,0);
}
if(isset($_REQUEST['refreshprep']))
{	
	prep(0,0);
}
if(isset($_REQUEST['showdone']))
{	
	prep(0,1);
}
if(isset($_REQUEST['pdoneval']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$sheader = "DONE LIST";
		
		if($pdoneval == 0)
			$sheader = "PENDING LIST";
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">ORDER PREPARATION LIST</h4>
			<h4 style = "text-align:center"><?php echo $sheader;?></h4>
			<?php
				prep(1,$pdoneval);
				
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
if(isset($_REQUEST['orderui']))
{
	$level = $_REQUEST['orderui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	?>
	<h2>ORDERS</H2>
	<div class="box">
		<div id = "markalert"></div>
		<div class="box">
			<div class="box-body" id = "smonitorui">
				<?php orders(0,0);?>	
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['takeorderui']))
{
	$level = $_REQUEST['takeorderui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	?>
	<h2>ACTIVE ORDERS</H2>
	<button class = "btn btn-primary btn-flat" id = "new_order">NEW ORDER</BUTTON>
		<div id = "markalert"></div>
		<div class="box">
			<div class="box-body">
				<?php takeorders(0,0);?>	
			</div>
		</div>
		<script>
			$("#new_order").click(
				function()
				{
												$('#maincontent').html(loading);
												$.post( 
																'php/pos.php',
																{
																	torderui:1
																	
																},
																function(data) {
																	$('#maincontent').html(data);
																
																});
				}
			);
		</script>
	<?php
}

if(isset($_REQUEST['torderui']) || isset($_REQUEST['currentorderui']) )
{
	if(isset($_REQUEST['torderui']))
	{
		if(!empty($_SESSION['oprev']))
			$_SESSION['order'] = $_SESSION['oprev'];
		else
			$_SESSION['order'] = "";
	}
	
	if(isset($_REQUEST['currentorderui']))
	{
		$check = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $_SESSION[order] and order_count != 0 and isdeleted = 0"));
		if($check == 0)
		{
			$_SESSION['oprev'] = $_SESSION['order'];
		}
		$_SESSION['order'] = $_REQUEST['currentorderui'];
	}
	
	$cid = 0;
	
	$trn = 0;
	if(!empty($_SESSION['order']))
	{
		$trn = $_SESSION['order'];
	}
	$user = get_user_id($_SESSION['c_craft']);
	$branch = get_branch($user);
	$agent = get_agent($user);
	
	?>
		<script>
												$.post( 
																'php/pos.php',
																{
																	renderuii:1
																	
																},
																function(data) {
																	$('#renderui').html(data);
																
																});
												$.post( 
																'php/pos.php',
																{
																	changeuii:1
																	
																},
																function(data) {
																	$('#changeui').html(data);
																
																});
		</script>
	<?php
	if(empty($_SESSION['order']))
	{
		$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
		mysqli_query($con,"insert into pos_sales set
		branch_id = '$branch',
		sales_invoice_number = '',
		customer_id = $cid,
		customer_fullname = '',
		result = '$result',
		created_by_fullname = '$user',
		remarks = '',
		isdeleted = 0
		");
		
		$_SESSION['order'] = '';
		$sales_id = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where result = '$result'"));
		//$_SESSION['oprev'] = $sales_id['pos_sales_id'];
		$_SESSION['order'] = $sales_id['pos_sales_id'];
	}
	//if($cid == 0)
	//{
		//mysqli_query($con,"Update pos_sales set customer_id = 0, customer_fullname = '$cus' where pos_sales_id = $_SESSION[order]");
	//}
	//else{
		//mysqli_query($con,"Update pos_sales set customer_id = $cid, customer_fullname = '$cfullname' where pos_sales_id = $_SESSION[order]");
	//}
	
	$sales_id = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $_SESSION[order]"));
	
	//if(!isset($_SESSION['oprev']))
		//$_SESSION['oprev'] = 0;
	
	//$pcheck = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $_SESSION[oprev]
	//and sales_invoice_number = ''"));
	
	//if($pcheck != 0)
	//{
		//mysqli_query($con,"Update lup_invoice_number set pos_sales_id = 0 where pos_sales_id = $_SESSION[oprev]");
	//}
	
	//$irow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_invoice_number where isdeleted = 0 and branch_id = $branch
	//and pos_sales_id =0"));
	
	//	mysqli_query($con,"Update lup_invoice_number set pos_sales_id = $_SESSION[order] where invoice_number_id = $irow[invoice_number_id]");
	
	$totalq = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as total from pos_sales_detail where 
	pos_sales_id = $_SESSION[order] and isdeleted = 0"));
	
	$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where 
	pos_sales_id = $_SESSION[order] and isdeleted = 0"));
	?>	
	<div class="callout callout-warning" style = "margin-top:-10px;">
        <div class = "row">
			<form id = "orderform">
											<div class="col-md-3" style = "display:none;">
													<div class="form-group">
														
														<input type = "hidden" name = "opos_date" value = "<?php echo date('Y-m-d');?>">
															
													
													</div>
											</div>
											
											<div class="col-md-2">
												<?php
													if(!isset($_REQUEST['currentorderui']))
													{
												?>
													<div class="form-group">
														<Select class = "form-control" name = "opos_otype" id = "opos_otype" data-validation="required"
															data-validation-error-msg="Select Order Type">
																<option value = "" hidden "Selected">SELECT ORDER TYPE</option>
															<?php
															$pmquery = mysqli_query($con,"Select * from pos_lup_order_type where isdeleted = 0");
															while($prow = mysqli_fetch_assoc($pmquery))
															{
															?>
																<option value = "<?php echo $prow['order_type_id'];?>"><?php echo $prow['order_type_description'];?></option>
															
															<?php
															}
															?>
														</select>
													</div>
													<?php
													}
													else{
														?>
															<h3>ORDER NO <?php echo $sales_id['order_count'];?></h3>
														<?php
													}
													?>
											</div>
										
											<div class="col-md-4">
													<div class="form-group">
													<?php
													if(!isset($_REQUEST['currentorderui']))
													{
													?>
														<button class = "btn btn-success btn-flat btn-sm" id = "final">SAVE ORDERS</button>
													<?php
													}
													else
													{
														?>
														<button class = "btn btn-success btn-flat btn-sm" id = "update">UPDATE ORDERS</button>
														<?php
													}
													?>
														
														<button class = "btn btn-primary btn-flat btn-sm" id = "new">NEW ORDER</button>
														<button class = "btn btn-warning btn-flat btn-sm" id = "active">ACTIVE ORDERS</button>
													<?php
													if(!isset($_REQUEST['currentorderui']))
													{
													?>
														<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">RESET</button>
													<?php
													}
													?>
													</div>
											</div>
											<div class="col-md-3" id = "ototalqntyui" STYLE = "font-weight:bold;font-size:15px;">
												TOTAL QUANTITY: <?php echo number_format($totalq['total'],2);?>
											</div>
											<div class="col-md-3" id = "ototalui" STYLE = "font-weight:bold;font-size:15px;">
												TOTAL SALES: <?php echo number_format($total['total'],2);?>
											</div>
				</form>
		</div>
    </div>
		<script>
			$("#update").click(
				function(e)
				{
					e.preventDefault();
						var r = confirm("Confirm Action");
						
						if(r == true)
						{
																 
														$.post( 
																 'php/pos.php',
																 {
																	 updateorderui:1
																},
																 function(data) {
																	$('#click').html(data);
																	
																 });
					
						}
													
				}
				);
				
			$("#new").click(
				function(e)
				{
					e.preventDefault();
						var r = confirm("Confirm Action");
						
						if(r == true)
						{
																 
										$('#maincontent').html(loading);
					
	
														$.post( 
																 'php/pos.php',
																 {
																	 torderui:1
																},
																 function(data) {
																	$('#maincontent').html(data);
																	
																 });
					
						}
													
				}
				);
				$("#active").click(
				function(e)
				{
					e.preventDefault();
						
																 
										$('#maincontent').html(loading);
					
	
														$.post( 
																 'php/pos.php',
																 {
																	 takeorderui:1
																},
																 function(data) {
																	$('#maincontent').html(data);
																	
																 });
					
						
													
				}
				);
				
				$("#final").click(
					function()
					{
						$.validate({
														form:'#orderform',
														validateOnBlur : false,
														errorMessagePosition : 'top',
														modules : 'security',
														onSuccess : function($form) {
														
															 var formData = $('#orderform').serializeArray();
																 //var formData = new FormData($('#regform')[0]);
																 $("#positemlist").html(loading);
																 
																		$.ajax({
																			url :  'php/pos.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#positemlist").html(data);
																				
																			}
																		});

														  return false; // Will stop the submission of the form
														},
													});
					}
				);									
				$("#barcode").focus();
				
						
			$("#cancel").click(
				function(e)
				{
					e.preventDefault();
						var r = confirm("Confirm Reset");
						
						if(r == true)
						{
												$.post( 
																 'php/pos.php',
																 {
																	 clearorder:1
																},
																 function(data) {
																	$('#click').html(data);
					
																 });
																 
										$('#maincontent').html(loading);
					
	
														$.post( 
																 'php/pos.php',
																 {
																	 torderui:1
																},
																 function(data) {
																	$('#maincontent').html(data);
																	
																 });
					
						}
													
				}
			);
		</script>
			
			<div class="box" style = "margin-top:-5px;">
			
				<div class="box-body" id = "itemlistui">
					<div class="row">
						<div class="col-md-6">
							<DIV class = "form-group">
							<input type = "text" class = "form-control" id = "barcode"  placeholder = "SEARCH ITEMS" style = "margin:auto;
							background-color:#fff;border-radius:5px;font-size:20px;font-weight:bold;" autocomplete="off">
							<div id = "search_result"></div>
							<div id = "qntyui"></div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<button class = "btn btn-success btn-flat" id = "osearch">SEARCH</button>
								<button class = "btn btn-primary btn-flat" id = "fin">BROWSE</button>	
							</div>
						</div>
					</div>
										
					<?php //pos_category('');?>
					<script>
						$("#fin").click(
							function(e)
							{
								e.preventDefault();
												$('#positemlist').html(loading);
												$.post( 
																 'php/pos.php',
																 {
																	 poscategoryui:'<?php echo $_SESSION['order'];?>',
																	 
																},
																 function(data) {
																	$('#positemlist').html(data);
																	$("#itemtitle").html('BROWSE ITEMS');
																 });
												$.post( 
																 'php/pos.php',
																 {
																	 
																	 oitemtoggleui:1
																},
																 function(data) {
																	$('#item-toggle').html(data);
																	
																 });
																 
							}
						);
						$("#barcode").keyup(
							function(e)
							{
									var key = e.which;
									
									if(key == 13)
									{
										if($("#barcode").val() != '')
										{
											//$("#barcode").val('');
											$('#positemlist').html(loading);
													$.post( 
																 'php/pos.php',
																 {
																	 add_item_search:$("#barcode").val()
																},
																 function(data) {
																	$('#positemlist').html(data);
																	
																 });
												$.post( 
																 'php/pos.php',
																 {
																	 
																	 oitemtoggleui:1
																},
																 function(data) {
																	$('#item-toggle').html(data);
																	
																 });
																 
										}
									}
							}
						);
											/*$("#barcode").keyup(
													function(e)
													{
															$("#clickval").val("");
															var s = $("#barcode").val();
																if(s != "")
																{
																	$.post( 
																	'php/main.php',
																	 { itemq: s },
																	 function(data) {
																		$('#search_result').html(data);
																	 });
																}
																else
																{
																	$('#search_result').html("");
																}
													});*/
						
						/*$("#barcode").keyup(
							function(e)
							{
									var key = e.which;
									
									if(key == 13)
									{
										if($("#barcode").val() != '')
										{
											//$("#barcode").val('');
											
													$.post( 
																 'php/pos.php',
																 {
																	 add_item_id:$("#barcode").val(),
																	 add_sales_id:'<?php echo $_SESSION['order'];?>',
																	 frombarcode:1
																},
																 function(data) {
																	$('#click').html(data);
																	
																 });
										}
									}
									else if(key != 13 && key != 40)
									{
											var s = $("#barcode").val();
																	if(s != "")
																	{
																		$.post( 
																		'php/main.php',
																		 { itemq: s },
																		 function(data) {
																			$('#search_result').html(data);
																		 });
																	}
																	else
																	{
																		$('#search_result').html("");
																	}
									}
									
								
							}
						);*/
					</script>	
			</div>	
			<div class="box" style = "margin-top:-15px;">
				<div class="box-header with-border">
					<h3 class="box-title" id = "itemtitle">ITEM LIST</h3>
					  <div class="box-tools pull-right" id = "item-toggle">
						
					  </div>
					</div
				</div>
				<div class="box-body">
					<div class="box" style = "margin-top:-12px;height:500px;">
						<div class="box-body" id = "positemlist">
							<?php pos_item_list($_SESSION['order'],0,1);?>
						</div>
					</div>
				</div>
			</div>			
	<?php
}
if(isset($_POST['opos_otype']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	$istran = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales_detail where 
	pos_sales_id = $_SESSION[order] and isdeleted = 0"));
	
	if(!empty($istran))
	{
		$tcount = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales where order_count != 0 and isdeleted = 0 and STR_TO_DATE(created_modified,'%Y-%m-%d') = '$opos_date'"));
		mysqli_query($con,"update pos_sales set order_count = $tcount+1,order_type_id = $opos_otype where pos_sales_id = $_SESSION[order]");
		mysqli_query($con,"Update pos_sales_detail set finalize = 1 where pos_sales_id = $_SESSION[order] and isdeleted = 0 and finalize = 0");
		$ocount = mysqli_fetch_assoc(mysqli_query($con,"Select order_count from pos_sales where pos_sales_id = $_SESSION[order]"));
		$_SESSION['order'] = '';
		$_SESSION['oprev'] = '';
?>
						<script>
							$("#maincontent").html('<h2>Order no. <?php echo $ocount['order_count'];?> ha been created. <button class = "btn btn-success" id = "ook">OK</button>');		
							
							$("#ook").click(
								function()
								{
																$.post( 
																		'php/pos.php',
																		 { torderui:1 },
																		 function(data) {
																			$('#maincontent').html(data);
																		 });
								}
							);
						</script>
		<?php
		$_SESSION['order'] = '';
	}
	else{
		?>
			<script>
				alert("No Transaction Listed");
			</script>
		<?php
	}
}
if(isset($_REQUEST['updateorderui']))
{
	mysqli_query($con,"Update pos_sales_detail set finalize = 1 where pos_sales_id = $_SESSION[order] and isdeleted = 0 and finalize = 0");
	
	?>
		<script>
			alert("Order Successfully Updated");
			$('#maincontent').html(loading);
			$.post( 
																		'php/pos.php',
																		 { currentorderui:1 },
																		 function(data) {
																			$('#maincontent').html(data);
																		 });
		</script>
	<?php
}
?>
