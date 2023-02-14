<?php
include('connect.php');
include("general.php");

function cstockmonitoring($product,$unitt,$cat, $class, $dfrom,$dto,$print,$branch)
{
	global $con;
	$string = "Select * from pos_lup_item where isdeleted = 0";
	
	if($product != "" && $product != "all")
	{
		$string = $string." and item_id = $product";
	}
	
	if($unitt != "" && $unitt != "all")
	{
		$string = $string." and unit_id = $unitt";
	}
	if($cat != "" && $cat != "all")
	{
		$string = $string." and category_id = $cat";
	}
	if($class != "" && $class != "all")
	{
		$string = $string." and classification_id = $class";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	
	<table class = "table table-bordered table-sm" id = "cstockmtable">
								<thead>
									<th>#</th>
									<th>PRODUCT DESCRIPTION</th>
									<th>UNIT</th>
									<th>REMAINING STOCKS</th>
									<th>TOTAL RETURNS</th>
									<th>TOTAL STOCKS</th>
								</thead>
		<?PHP
		$ctr = 1;
		while($row = mysqli_fetch_assoc($query))
		{
					$pstring = "Select DISTINCT(unit_id) from inv_transaction where 
													item_id = $row[item_id] and isdeleted = 0";
													
					if($unitt != "" && $unitt != "all")
					{
						$pstring = $pstring." and unit_id = $unitt";
					}
				
					
					$pquery = mysqli_query($con,$pstring);
					$count = mysqli_num_rows($pquery);
					
					if($count != 0)
					{
						while($prow = mysqli_fetch_assoc($pquery))
						{
							$unit = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $prow[unit_id]"));
				?>
								<tr>
									<td><?php echo $ctr;?></td>
									
											<td><?php echo $row['item_description'];?></td>
											<td><?php echo $unit['unit_description'];?></td>
											<td>
												<?php
												
													$isret = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where isreturn = 1"));
													$stockstring = "Select SUM(quantity) as total from inv_transaction where item_id = $row[item_id]
													and unit_id = $prow[unit_id] and isdeleted = 0 and transaction_type_id != $isret[transaction_type_id]";
													
														if(!empty($dfrom))
														{
															
															$stockstring = $stockstring." and (STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
															STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
														
														}
		
													$stockcount1 = mysqli_fetch_assoc(mysqli_query($con,$stockstring));
													
													echo number_format($stockcount1['total'],2);
												?>
											</td>
											<td>
											<?php
												$stockstring = "Select SUM(quantity) as total from inv_transaction where item_id = $row[item_id]
													and unit_id = $prow[unit_id] and isdeleted = 0 and transaction_type_id = $isret[transaction_type_id]";
													
														if(!empty($dfrom))
														{
															
															$stockstring = $stockstring." and (STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
															STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
														
														}
		
													$stockcount2 = mysqli_fetch_assoc(mysqli_query($con,$stockstring));
													
													echo number_format($stockcount2['total']*-1,2);
											?>
											</td>
											<td><?php echo number_format($stockcount1['total']-($stockcount2['total']*-1),2);?></td>
								</tr>
				<?php
						
						}
					}
					else
					{
						$unit = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $row[unit_id]"));
						?>
							<tr>
									<td><?php echo $ctr;?></td>
									<td><?php echo $row['item_description'];?></td>
									<td><?php echo $unit['unit_description'];?></td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
							</tr>
						<?php
					}
					
			$ctr++;	
		}
		?>
	</table>
	<?php
	if($print == 0)
	{
	?>
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
					var table = $('#cstockmtable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : false,
					   select: {
							style: 'single'
						},
						keys: {
						   keys: [ 13 /* ENTER */, 38 /* UP */, 40 /* DOWN */ ]
						}
					});	

					 $('#cstockmtable').on( 'draw.dt', function () {
							table.row(2).select();
					 
							} );
						
						// Handle event when cell gains focus
						$('#cstockmtable').on('key-focus.dt', function(e, datatable, cell){
							// Select highlighted row
							table.row(cell.index().row).select();
							
						});
						
						// Handle click on table cell
						$('#cstockmtable').on('click', 'tbody td', function(e){
							e.stopPropagation();
							var rowIdx = table.cell(this).index().row;
							
							table.row(rowIdx).select();
						});
				}
			);
		</script>
		
	<?php
	}
}

function stockmonitoring($stockno,$product,$dfrom,$dto,$print,$branch,$cat,$class)
{
	global $con;
	
	$string = "Select DISTINCT(inv_transaction.transaction_no) as tran_no, inv_transaction.unit_id, inv_transaction.item_id, pos_lup_item.item_description, pos_lup_item.category_id, pos_lup_item.classification_id from inv_transaction ,inv_delivery_details, pos_lup_item where inv_transaction.isdeleted = 0
	and inv_transaction.delivery_id = inv_delivery_details.delivery_id 
	and inv_transaction.item_id = pos_lup_item.item_id";
													
	if($branch != 'all')
	{
		$string = $string." and inv_transaction.location_id = $branch";
	}
	
	if($product != '' && $product != 'all')
	{
		$string = $string." and inv_transaction.item_id = '$product'";
	}
	if($class != '' && $class != 'all')
	{
		$string = $string." and pos_lup_item.classification_id = '$class'";
	}
	if($cat != '' && $cat != 'all')
	{
		$string = $string." and pos_lup_item.category_id = '$cat'";
	}
	if($stockno != '')
	{
		$string = $string." and inv_transaction.transaction_no = '$stockno'";
	}
	
	if($dfrom != '' && $dto != '')
	{
		$string = $string." and (STR_TO_DATE(inv_delivery_details.delivery_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
		STR_TO_DATE(inv_delivery_details.delivery_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;			
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-sm" id = "stockmtable">
								<thead>
									<th>#</th>
									<th>STOCK NO</th>
									<th>PRODUCT DESCRIPTION</th>
									<th>CLASSIFICATION</th>
									<th>CATEGORY</th>
									<th>TOTAL STOCKS</th>
									<th>TOTAL SALES</th>
									<th>TOTAL RETURNS</th>
									<th>REMAINING STOCKS</th>
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
						//$unit = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $row[unit_id]"));
						//$item = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $row[item_id]"));
						$cat = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_category where category_id = $row[category_id]"));
						$class = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $row[classification_id]"));
			?>
							<tr>
								<td><?php echo $ctr;?></td>
										<td><?php echo $row['tran_no'];?></td>
										<td><?php echo $row['item_description'];?></td>
										<td><?php echo $class['classification_description'];?></td>
										<td><?php echo $cat['category_description'];?></td>
										<td>
											<?php
												$stockstring = "Select SUM(quantity) as total from inv_transaction where transaction_no = '$row[tran_no]'
												and isdeleted = 0 and quantity > 0 ";
												
												if(!empty($dfrom))
													{
														
														$stockstring = $stockstring." and (STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
														STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
													
													}
	
												$stockcount = mysqli_fetch_assoc(mysqli_query($con,$stockstring));
												
												echo number_format($stockcount['total'],2);
											?>
										</td>
										<td>
											<?php
												$stockstring = "Select SUM(inv_transaction.quantity) as total from inv_transaction, inv_lup_transaction_type where inv_transaction.transaction_no = '$row[tran_no]'
												and inv_transaction.isdeleted = 0
												and inv_transaction.transaction_type_id = inv_lup_transaction_type.transaction_type_id
												and inv_lup_transaction_type.issales = 1";
												//echo $stockstring;
												if(!empty($dfrom))
													{
														
														$stockstring = $stockstring." and (STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
														STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
													
													}
	
												$stockcounttt = mysqli_fetch_assoc(mysqli_query($con,$stockstring));
												
												echo number_format($stockcounttt['total'],2);
											?>
											
										</td>
										
										<td>
											<?php
												$stockstring = "Select SUM(inv_transaction.quantity) as total from inv_transaction, inv_lup_transaction_type where inv_transaction.transaction_no = '$row[tran_no]'
												and inv_transaction.isdeleted = 0
												and inv_transaction.transaction_type_id = inv_lup_transaction_type.transaction_type_id
												and inv_lup_transaction_type.isreturn = 1";
												//echo $stockstring;
												if(!empty($dfrom))
													{
														
														$stockstring = $stockstring." and (STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
														STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
													
													}
	
												$stockcountt = mysqli_fetch_assoc(mysqli_query($con,$stockstring));
												
												echo number_format($stockcountt['total'],2);
											?>
											
										</td>
										<td><?php echo number_format(($stockcount['total'] + $stockcountt['total'] + $stockcounttt['total']),2);?></td>
										<?php
										if($print == 0)
										{
										?>
											<td><button class = "btn btn-primary btn-flat btn-xs" id = "transaction<?php echo $ctr;?>">TRANSACTIONS</button></td>
										<?php
										}
										?>
							</tr>
							<script>
								$("#transaction<?php echo $ctr;?>").click(
									function()
									{
															$("#modal").modal("show");
															$("#modalbody").css("min-width","65%");
															$('#modalui').html(loading);	
															
															$.post( 
																'php/inventory.php',
																{
																	montransactionpro:<?php echo $row['item_id'];?>,
																	montransactionstockno:'<?php echo $row['tran_no'];?>',
																	montransactiondfrom:'<?php echo $dfrom;?>',
																	montransactiondto:'<?php echo $dto;?>',
																	montransactionbranch:'<?php echo $branch;?>'
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
	
	<?php
	if($print == 0)
	{
	?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#stockmtable').DataTable({
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
}
function inventory($tran,$product,$type,$supplier,$dfrom,$dto,$unit,$dis,$branch,$exdfrom,$exdto,$deldfrom,$deldto,$level,$delivery)
{
	global $con;
	$string = "Select inv_transaction.*, pos_lup_item.item_description from inv_transaction,pos_lup_item where inv_transaction.isdeleted = 0
	and inv_transaction.item_id = pos_lup_item.item_id";
	if($delivery != '' && $delivery != 'all')
	{
		$string = $string." and inv_transaction.delivery_id = $delivery";
	}
	
	if($branch != 'all')
	{
		$string = $string." and inv_transaction.location_id = $branch";
	}
	if(!empty($unit)&& $unit != 'all')
	{
		$string = $string." and inv_transaction.unit_id = '$unit'";
	}
	
	if(!empty($supplier) && $supplier != 'all')
	{
		$string = $string." and inv_transaction.supplier_id = '$supplier'";
	}
	
	if(!empty($tran))
	{
		$string = $string." and inv_transaction.transaction_no = '$tran'";
	}
	if(!empty($product) && $product != 'all')
		$string = $string." and inv_transaction.item_id = '$product'";
	
	if(!empty($type)&& $type != 'all')
		$string = $string." and inv_transaction.transaction_type_id = '$type'";
	
	if(!empty($dfrom))
	{	
		$string = $string." and (STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')>= STR_TO_DATE('$dfrom','%Y-%m-%d') and
		STR_TO_DATE(inv_transaction.transaction_date,'%Y-%m-%d')<= STR_TO_DATE('$dto','%Y-%m-%d'))";
	
	}
	if(!empty($exdfrom))
	{	
		$string = $string." and (STR_TO_DATE(inv_transaction.expiration_date,'%Y-%m-%d')>= STR_TO_DATE('$exdfrom','%Y-%m-%d') and
		STR_TO_DATE(inv_transaction.expiration_date,'%Y-%m-%d')<= STR_TO_DATE('$exdto','%Y-%m-%d'))";
	}
	$string = $string." order by transaction_date";
	//echo $string;
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "invtable">
								<thead>
									<th>#</th>
									<?php
									if($dis == 1)
									{
									?>
									<th>ACTIONS</th>
									<?php
									}
									?>
									<th>STOCK NUMBER</th>
									<th>DELIVERY INVOICE NUMBER</th>
									<th>TRANSACTION TYPE</th>
									<th>TRANSACTION DATE</th>
									<th>DELIVERY DATE</th>
									<th>EXPIRATION DATE</th>
									<th>PRODUCT</th>
									<th>SUPPLIER</th>
									<th>UNIT</th>
									<th>QUANTITY</th>
								</thead>
		<?php
			$ctr = 1;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$type = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where transaction_type_id = $row[transaction_type_id]"));
				$total = $total + $row['quantity'];
				$unit = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $row[unit_id]"));
				$idel = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_delivery_details where delivery_id = $row[delivery_id]")); 
				$sup = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_supplier where supplier_id = $idel[supplier_id]"));
				
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<?php
						if($dis == 1)
						{
						?>
							<td id = "invactions">
							<button class = "btn btn-warning btn-flat btn-xs" id = "remarks<?php echo $ctr;?>">REMARKS</button>	
							<?php
							if($row['transaction_type_id'] != 0 && ($type['issales'] != 1 && $type['isreturn'] != 1))
							{
							?>
							<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
							<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>" style = "display:none;">EDIT</button>
							<?php
							}
							?></td>
						<?php
						}
						?>
						
						<td><?php echo $row['transaction_no'];?></td>
						<td><?php echo $idel['delivery_invoice_number'];?></td>
						<td><?php echo $type['transaction_type_description'];?></td>
						<td><?php echo $row['transaction_date'];?></td>
						<td><?php echo $idel['delivery_date'];?></td>
						<td><?php echo $row['expiration_date'];?></td>
						<td><?php echo $row['item_description'];?></td>
						<td><?php echo $sup['supplier_description'];?></td>
						<td><?php echo $unit['unit_description'];?></td>
				
						<td STYLE = "text-align:right;"><?php echo number_format($row['quantity'],2);?></td>
					</tr>
					<script>
						$("#delete<?php echo $ctr;?>").click(
										function()
										{
															var r = confirm("confirm delete");

															if(r == true)
															{
																$.post( 
																	'php/inventory.php',
																	{
																	
																		itransactiondelete:'<?php echo $row["transaction_id"];?>',
																		itransactiondeletelevel:'<?php echo $level;?>'
																	},
																	function(data) {
																		$('#stockmui2').html(data);		
																	});
															}
										}
									);
									
						$("#edit<?php echo $ctr;?>").click(
										function()
										{
											
															$("#modal").modal("show");
															$("#modalbody").css("min-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'php/inventory.php',
																{
																	edititransaction:'<?php echo $row["transaction_id"];?>',
																	editilevel:'<?php echo $level;?>'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
						
						$("#remarks<?php echo $ctr;?>").click(
										function()
										{
											
															$("#modal").modal("show");
															$("#modalbody").css("min-width","50%");
															$('#modalui').html(loading);	
															$.post( 
																'php/inventory.php',
																{
																	transactionremarks:'<?php echo $row["transaction_id"];?>'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									
					</script>
				<?Php
				$ctr++;
			}
		?>
		</table>
		<table class = "table table-bordered table-hover table-sm" id = "invtable">
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th>TOTAL STOCK</th>
									<th><?php echo number_format($total,2);?></th>
								</tr>
		</table>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#invtable').DataTable({
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
if(!empty($_REQUEST['dstockmui']))
{
	$level = $_REQUEST['dstockmui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$br = $branch;
	
	if($level == 1)
		$br = 'all';
	?>
		<h2>DELIVERY DETAILS</h2>
		<div class="box box-warning">
			<div class="box-body">
					<form method = "POST" id = "newstockform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>Delivery INVOICE NUMBER:</label>
									<input type = "text" class = "form-control" name = "dinvoice" data-validation="required"
														data-validation-error-msg="Enter Delivery INVOICE NUMBER">
								</div>		
							</div>
							
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>SUPPLIER:</label>
									
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_supplier where isdeleted = 0 order by supplier_description");
									?>
									<select name = "dsupplier" id = "dsupplier" class="form-control" data-validation="required"
													data-validation-error-msg="Select SUPPLIER">
													<option  hidden "Selected"></option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['supplier_id'];?>"><?php echo $prow['supplier_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
						
							<div class="col-md-3">
								<div class = "form-group">
									<label>Delivery Date:</label>
									<input type = "date" class = "form-control" name = "ddeldate" data-validation="required"
														data-validation-error-msg="Enter Delivery Date">
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>AMOUNT:</label>
									 <input type="text" class="form-control" name = "damount" id = "damount" data-validation="number"
													data-validation-error-msg="Enter Amount"
													data-validation-allowing="float">
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>PAYMENT TERM:</label>
									<select name = "dpterm" id = "dpterm" class="form-control" data-validation="required"
													data-validation-error-msg="Select PAYMENT TERM">
													<option  hidden "Selected"></option>
													<option value = 1>CASH</option>
													<option  value = 2>INSTALLMENT</option>
												
									</select>
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
															
															<select  name = "dbranch" id = "dbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "dbranch" name = "dbranch">
											<?php
										}
										?>
										
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
																
																			$.ajax({
																				url :  'php/inventory.php',
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
			
			<div class="box-body" id = "invfilterui">
						<form method = "POST" id = "dfilterform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>Delivery INVOICE NUMBER: <i>(leave blank if all)</i></label>
									<input type = "text" class = "form-control" name = "dfinvoice" >
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>SUPPLIER:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_supplier where isdeleted = 0 order by supplier_description");
									?>
									<select name = "dfsupplier" id = "dfsupplier" class="form-control" data-validation="required"
													data-validation-error-msg="Select SUPPLIER">
													<option "Selected" value = "all">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['supplier_id'];?>"><?php echo $prow['supplier_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<div class="col-md-4">
							<label>DATE FROM:</label>

							<div class="form-group">
								<input type = "date" class = "form-control" name = "dfdfrom" id = "dfdfrom" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
							</div>
						</div>
						<div class="col-md-4">
							<label>DATE TO:</label>

							<div class="form-group">
								<input type = "date" class = "form-control" name = "dfdto" id = "dfdto"  placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
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
															
															<select  name = "dfbranch" id = "dfbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "dfbranch" name = "dfbranch">
											<?php
										}
										?>
										
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" >FILTER</button>
									
									</div>	
							</div>
						</div>
					</form>
					<script>
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
																	 $("#stockmui2").html(loading);
																			$.ajax({
																				url :  'php/inventory.php',
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
		<script>
					$("#add").click(
												function(e)
														{
															e.preventDefault();
															
															$("#modal").modal("show");
															$("#modalbody").css("min-width","40%");
															$('#modalui').html(loading);	
															$.post( 
																		'php/inventory.php',
																		{
																			addreturnui:1

																		},
																		function(data) {
																			$('#modalui').html(data);	
																			
																		});
														}
													);
													
			$("#go").click(
				function()
						{
									var fil = $("#invfilter").val();

									if(fil == 1)
									{
										$.post( 
										'php/inventory.php',
										{
																	
											invfiltertrannumber:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
									else if(fil == 2)
									{
										$.post( 
										'php/inventory.php',
										{
																	
											invfilterpro:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
									else if(fil == 3)
									{
										$.post( 
										'php/inventory.php',
										{
																	
											invfilterexpire:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
															
						}
			);
		</script>
		<div id = "inv_alert"></div>
			<div id = "stockmui2" style = "overflow:auto;">
				<?PHP //deliverydetails("","","","","");?>
			</div>
	<?php
}
if(isset($_POST['dinvoice']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from inv_delivery_details where delivery_invoice_number = '$dinvoice'"));
	$user = get_user_id($_SESSION['c_craft']);
	if($check == 0)
	{
		$save = mysqli_query($con,"insert into inv_delivery_details set
		delivery_invoice_number = '$dinvoice',
		amount = $damount,
		supplier_id = $dsupplier,
		branch_id = $dbranch,
		delivery_date = '$ddeldate',
		date_added = NOW(),
		added_by = $user,
		payment_terms = $dpterm");
		
		if($save)
		{
					?>
						<script>
							notify("New Delivery Details Created","#inv_alert");
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					notify("Error Creating Delivery Details, Please Contact the system administrator","#inv_alert");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				notify("Delivery Invoice Number Already Exists","#inv_alert");
			</script>
		<?php
	}
}

if(!empty($_REQUEST['stockmui']))
{
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$br = $branch;
	$level = $_REQUEST['stockmui'];
	
	if($level == 1)
		$br = 'all';
	?>
		<h2>STOCK MANAGEMENT</h2>
		<div class="box box-warning">
			
			<div class="box-body">
					<form method = "POST" id = "newstockform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>PRODUCT:</label>
									<input type = "hidden" value = "<?php echo $level;?>" name = "ilevel">
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_item where isdeleted = 0 order by item_description");
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
							<script>
								$("#iproduct").select2();
							</script>
							<div class="col-md-3">
								<div class = "form-group">
									<label>UNIT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_lup_unit where visible = 1");
									?>
									<select name = "iunit" id = "iunit" class="form-control" data-validation="required"
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
								<div class = "form-group">
									<label>SUPPLIER:</label>
									<input type = "hidden" value = "<?php echo $level;?>" name = "ilevel">
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_supplier where isdeleted = 0 order by supplier_description");
									?>
									<select name = "isupplier" id = "isupplier" class="form-control" data-validation="required"
													data-validation-error-msg="Select SUPPLIER">
													<option  hidden "Selected"></option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['supplier_id'];?>"><?php echo $prow['supplier_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>TRANSACTION TYPE:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_lup_transaction_type where visible = 1
									and issales = 0 and isreturn = 0");
									?>
									<select name = "itransaction" id = "itransaction" class="form-control" data-validation="required"
													data-validation-error-msg="Select TRANSACTION TYPE">
													<option  hidden "Selected"></option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['transaction_type_id'];?>"><?php echo $prow['transaction_type_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>DELIVERY INVOICE NUMBER:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_delivery_details where isdeleted = 0 order by delivery_date desc");
									?>
									<select name = "idelinvoice" id = "idelinvoice" class="form-control" data-validation="required"
													data-validation-error-msg="Select DELIVERY INVOICE NUMBER">
													<option  hidden "Selected"></option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['delivery_id'];?>"><?php echo $prow['delivery_invoice_number'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							
							<div class="col-md-3" style = "display:none;">
								<div class = "form-group">
									<label>Delivery Date:</label>
									<input type = "date" class = "form-control" name = "ideldate" data-validation="required"
														data-validation-error-msg="Enter Delivery Date">
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>Expiration Date:</label>
									<input type = "date" class = "form-control" name = "iexdate" data-validation="required"
														data-validation-error-msg="Enter Expiration Date">
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>QUANTITY:</label>
									 <input type="text" class="form-control" name = "iquantity" id = "iquantity" data-validation="number"
													data-validation-error-msg="Enter Quantity"
													data-validation-allowing="float,negative">
								</div>		
							</div>
							<div class="col-md-3" style = "display:none;">
								<div class = "form-group">
									<label>Mark Up:</label>
									 <input type="text" class="form-control" name = "imark" id = "imark" data-validation="number"
													data-validation-error-msg="Enter Mark Up"
													data-validation-allowing="float">
								</div>		
							</div>
							
							<div class="col-md-3" style = "display:none;">
								<div class = "form-group">
									<label>UNIT COST:</label>
									 <input type="text" class="form-control" name = "icost" id = "icost" data-validation="number"
													data-validation-error-msg="Enter Unit Cost"
													data-validation-allowing="float,negative">
								</div>		
							</div>
							<div class="col-md-2">
								<label>REMARKS:</label>
								<div class="input-group">
									<textarea class="form-control" name = "iremarks"></textarea>
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
															
															<select  name = "ibranch" id = "ibranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "ibranch" name = "ibranch">
											<?php
										}
										?>
										
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "save">SAVE</button>
										<button class = "btn btn-primary btn-flat" id = "add">ADD RETURNS</button>
									</div>	
							</div>
						</div>
					</form>
					<script>
							$("#add").click(
												function(e)
														{
															e.preventDefault();
															
															$("#modal").modal("show");
															$("#modalbody").css("min-width","40%");
															$('#modalui').html(loading);	
															$.post( 
																		'php/inventory.php',
																		{
																			addreturnui:1

																		},
																		function(data) {
																			$('#modalui').html(data);	
																			
																		});
														}
													);
													
							$("#save").click(
								function()
								{
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
															
																			$.ajax({
																				url :  'php/inventory.php',
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
												
					</script>					
						
					
				
			</div>
		</div>
		<div class="box box-warning">
			
			<div class="box-body" id = "invfilterui">
						<div class = "row">	
							<div class="col-md-4">
								 <div class="form-group">
										  <label for="age">FILTER BY:</label>
											
											<select name = "invfilter" id = "invfilter" class="form-control" >
													<option value = "1">TRANSACTION NUMBER</option>
													<option value = "3">EXPIRATION DATE</option>
													<option value = "2">OTHER PARAMETERS</option>
											</select>
											
								</div>
							</div>
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "go">OK</button>
										
										
										
									</div>	
							</div>
						</div>
			</div>
		</div>
		<script>
					
													
			$("#go").click(
				function()
						{
									var fil = $("#invfilter").val();

									if(fil == 1)
									{
										$.post( 
										'php/inventory.php',
										{
																	
											invfiltertrannumber:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
									else if(fil == 2)
									{
										$.post( 
										'php/inventory.php',
										{
																	
											invfilterpro:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
									else if(fil == 3)
									{
										$.post( 
										'php/inventory.php',
										{
																	
											invfilterexpire:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
															
						}
			);
		</script>
		<div id = "inv_alert"></div>
	
			<div id = "stockmui2" style = "overflow:auto;">
				<?PHP //inventory("","","","","","","",1,$br,'','','','',$level,$id);?>
			</div>
		
	<?php
}
if(isset($_POST['iproduct']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}

	$save = inventory_transaction($iproduct,$iunit,$isupplier,$itransaction,$iquantity,$iremarks,0,$ibranch,$iexdate,$ideldate,0,0,$idelinvoice);
	
	if($save == 0)
	{
		?>
			<script>
				notify("Inventory Transaction Saved","#inv_alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("Error Saving New Inventory Transacition,Please Contact the System Administrator","#inv_alert");
			</script>
		<?php
	}
	
	//inventory("","","","","","","",1,$ibranch,'','','','',$ilevel);
}
if(isset($_REQUEST['edititransaction']))
{
	$id = $_REQUEST['edititransaction'];
	$ilevel = $_REQUEST['editilevel'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_transaction where transaction_id = $id"));
	?>
		
		<div class="box box-warning">
			
			<div class="box-body">
					<form method = "POST" id = "editstockform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<input type = "hidden" value = "<?php echo $ilevel;?>" name = "inveditlevel">
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
							<script>
								$("#iproductedit").select2();
							</script>
							<div class="col-md-3">
								<div class = "form-group">
									<label>UNIT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_lup_unit where visible = 1");
									$psel = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $row[unit_id]"));
									?>
									<select name = "iunitedit" id = "iunitedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option  value = "<?php echo $psel['unit_id'];?>" hidden "Selected"> <?php echo $psel['unit_description'];?></option>
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
							<div class="col-md-3">
								<div class = "form-group">
									<label>TRANSACTION TYPE:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_lup_transaction_type where visible = 1");
									$psel = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where  transaction_type_id = $row[transaction_type_id]"));
									
									?>
									<select name = "itransactionedit" id = "itransactionedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select TRANSACTION TYPE">
													<option  value = "<?php echo $psel['transaction_type_id'];?>" hidden "Selected"> <?php echo $psel['transaction_type_description'];?></option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['transaction_type_id'];?>"><?php echo $prow['transaction_type_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>QUANTITY:</label>
									 <input type="text" class="form-control" name = "iquantityedit" id = "iquantityedit" data-validation="number"
													data-validation-error-msg="Enter Quantity" value  ="<?php echo $row['quantity'];?>"
													data-validation-allowing="float,negative">
								</div>		
							</div>
								<div class="col-md-3">
								<div class = "form-group">
									<label>UNIT COST:</label>
									 <input type="text" class="form-control" name = "icostedit" id = "icostedit" data-validation="number"
													data-validation-error-msg="Enter Unit Cost" value = "<?php echo $row['unit_cost'];?>"
													data-validation-allowing="float,negative">
								</div>		
							</div>
							<div class="col-md-2">
								<label>REMARKS:</label>
								<div class="input-group">
									<textarea class="form-control" name = "iremarksedit"><?php echo $row['remarks'];?></textarea>
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
																				url :  'php/inventory.php',
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
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$br = $branch;
	
	if($inveditlevel == 1)
		$br = 'all';
		
			$tran = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where transaction_type_id = $itransactionedit"));
			if($tran['inventory'] == 'OUT')
			{
				$iquantity = $iquantity * -1;
			}
			if(empty($iremarksedit))
			{
				$iremarks = $tran['transaction_type_description'];
			}
			
			$user = get_user_id($_SESSION['c_craft']);
			
			$save = mysqli_query($con,"Update inv_transaction set
			transaction_type_id = $itransactionedit,
			item_id = $iproductedit,
			unit_id = $iunitedit,
			unit_cost = $icostedit,
			quantity = $iquantityedit,
			remarks = '$iremarksedit',
			edited_by = '$user',
			edited_by_datetime = NOW(),
			created_modified = NOW()
			where transaction_id = $inveditid");
			
	
	if($save)
	{
		?>
			<script>
				notify("Inventory Transaction Updated","#inveditalert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("Error Updating Transaction,Please Contact the System Administrator","#inveditalert");
			</script>
		<?php
	}
	
	inventory("","","","","","",1,$br,$inveditlevel);
}
if(isset($_REQUEST['itransactiondelete']))
{
	$id = $_REQUEST['itransactiondelete'];
	$level = $_REQUEST['itransactiondeletelevel'];
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$br = $branch;
	
	if($level == 1)
		$br = 'all';
		
	mysqli_query($con,"Update inv_transaction set isdeleted = 1 where transaction_id = $id");
	inventory("","","","","","","",1,$br,$level);
	
}
if(isset($_REQUEST['transactionremarks']))
{
	$id = $_REQUEST['transactionremarks'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select remarks from inv_transaction where transaction_id = $id"));
	?>
		
		<div class="box box-warning">
			<div class="box-body">
				<?php echo $row['remarks'];?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['invfiltertrannumber']))
{
	$level = $_REQUEST['invfiltertrannumber'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	$branch = get_branch($user);
	$br = $branch;
	
	if($level == 1)
	{
		$br = 'all';
	}
	?>
	<form id = "trannoform">
		<div class="row" >
			<div class="col-md-4">
				<label>ENTER TRANSACTION NUMBER:</label>

				<div class="form-group">
					<input type = "hidden" value = "<?php echo $br;?>" name = "ftbranch">
					<input type = "hidden" value = "<?php echo $level;?>" name = "ftlevel">
					<input type="text" class="form-control" name = "ftranno" id = "ftranno" data-validation="required"
													data-validation-error-msg="ENTER TRANSACTION NUMBER">
				</div>
			</div>
			<div class="col-md-4" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "ipro">PROCEED</button>
										<button class = "btn btn-primary btn-flat" id = "iprint" >PRINT</button>
										<button class = "btn btn-warning btn-flat" id = "ifcancel" >CANCEL</button>
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
						$("#ipro").click(
							function()
							{
								$.validate({
															form:'#trannoform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#trannoform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#stockmui2").html(loading);
																			$.ajax({
																				url :  'php/inventory.php',
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
							}
						);
						$("#iprint").click(
							function()
							{
								$.validate({
															form:'#trannoform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																
																$.post( 
																	'php/inventory.php',
																	{
																		pstockno:$("#ftranno").val(),
																		pstockbranch:'<?php echo $br;?>'
																		
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
					
	<?php
}
if(isset($_POST['ftranno']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
	<div class="box">
				<div class="box-body">
					<?php inventory($ftranno,"","","","","","","",$ftbranch,'','','','',$ftlevel,"");?>	
				</div>
			</div>
	<?PHP
	
	//($tran,$product,$type,$supplier,$dfrom,$dto,$unit,$dis,$branch,$expire,$level)
}
if(isset($_REQUEST['pstockno']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">STOCK NO <?PHP ECHO $pstockno;?> TRANSACTIONS</h4>
			<?php
				inventory($pstockno,"","","","","","","",$pstockbranch,'','','','',1,"");
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

if(isset($_REQUEST['invfilterexpire']))
{
	$level = $_REQUEST['invfilterexpire'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	$branch = get_branch($user);
	$br = $branch;
	
	if($level == 1)
	{
		$br = 'all';
	}
	?>
	<form id = "trannoform">
		<div class="row" >
			<div class="col-md-4">
				<label>EXPIRATION DATE FROM:</label>

				<div class="form-group">
					<input type = "hidden" value = "<?php echo $br;?>" name = "ftexbranch">
					<input type = "hidden" value = "<?php echo $level;?>" name = "ftexlevel">
					<input type = "date" class = "form-control" name = "exdfrom" id = "exdfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
				</div>
			</div>
			<div class="col-md-4">
				<label>EXPIRATION DATE TO:</label>

				<div class="form-group">
					<input type = "date" class = "form-control" name = "exdto" id = "exdto" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
				</div>
			</div>
			<div class="col-md-5 style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "ipro">PROCEED</button>
										<button class = "btn btn-primary btn-flat" id = "iprint">PRINT</button>
										<button class = "btn btn-warning btn-flat" id = "ifcancel" >CANCEL</button>
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
					$("#ipro").click(
						function()
						{
							$.validate({
															form:'#trannoform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#trannoform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#stockmui2").html(loading);
																			$.ajax({
																				url :  'php/inventory.php',
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
						}
					);
					$("#iprint").click(
						function()
						{
							$.validate({
															form:'#trannoform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 $.post( 
																	'php/inventory.php',
																	{
																		piexdfrom:$("#exdfrom").val(),
																		piexdto:$("#exdto").val(),
																		piexbr:'<?php echo $br;?>'
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
					
	<?php
}
if(isset($_POST['exdfrom']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
		<div class="box">
				<div class="box-body">
					<?php inventory("","","","","","","","",$ftexbranch,$exdfrom,$exdto,'','',$ftexlevel,"");?>	
				</div>
		</div>
	<?php
	
	//($tran,$product,$type,$supplier,$dfrom,$dto,$unit,$dis,$branch,$expire,$level)
}
if(isset($_REQUEST['piexdfrom']))
{
	?>
		<div id = "printt">
		<?php
			foreach($_POST as $key=>$val) {
				${$key} = $val;
				//echo "The value of ".$key." is ". $val." <br>";
			}
		?>
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">PRODUCT EXPIRATION REPORT</h4>
			<h4 style = "text-align:center"><?PHP echo $piexdfrom;?> - <?PHP echo $piexdto;?></h4>
			<BR>
			<?php
				inventory("","","","","","","","",$piexbr,$piexdfrom,$piexdto,'','',1,"");
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

if(isset($_REQUEST['invfilterpro']))
{
	$level = $_REQUEST['invfilterpro'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
	$branch = get_branch($user);
	$br = $branch;
	
	if($level == 1)
	{
		$br = 'all';
	}
	
	?>
		<form method = "POST" id = "ifilterform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<input type = "hidden" value = "<?php echo $br;?>" name = "ifbranch">
									<input type = "hidden" value = "<?php echo $level;?>" name = "iflevel">
					
									<label>PRODUCT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_item where isdeleted = 0");
									?>
									<select name = "ifproduct" id = "ifproduct" class="form-control">
													<option value = 'all' "Selected">ALL</option>
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
							<script>
								$("#ifproduct").select2();
							</script>
							<div class="col-md-3">
								<div class = "form-group">
									<label>UNIT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_lup_unit where visible = 1");
									?>
									<select name = "ifunit" id = "ifunit" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
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
							<div class="col-md-3">
								<div class = "form-group">
									<label>SUPPLIER:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_supplier where isdeleted = 0");
									?>
									<select name = "ifsupplier" id = "ifsupplier" class="form-control" data-validation="required"
													data-validation-error-msg="Select UNIT">
													<option value = 'all' "Selected">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['supplier_id'];?>"><?php echo $prow['supplier_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>TRANSACTION TYPE:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_lup_transaction_type where visible = 1");
									?>
									<select name = "iftransaction" id = "iftransaction" class="form-control">
													<option value = 'all' "Selected">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['transaction_type_id'];?>"><?php echo $prow['transaction_type_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>DELIVERY INVOICE NUMBER:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_delivery_details where isdeleted = 0 order by delivery_date desc");
									?>
									<select name = "ifdelinvoice" id = "ifdelinvoice" class="form-control" data-validation="required"
													data-validation-error-msg="Select DELIVERY INVOICE NUMBER">
													<option value = 'all' "Selected">ALL</option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['delivery_id'];?>"><?php echo $prow['delivery_invoice_number'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
							
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "ifdfrom" id = "ifdfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "ifdto"  id = "ifdto" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to" >
								</div>
							</div>
							

							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "ipro">FILTER</button>
										<button class = "btn btn-primary btn-flat" id = "iprint">PRINT</button>
										<button class = "btn btn-warning btn-flat" id = "ifcancel">CANCEL</button>
									
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
							
							$("#ipro").click(
								function()
								{
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
																				url :  'php/inventory.php',
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
								}
							);
							$("#iprint").click(
								function()
								{
									$.validate({
															form:'#ifilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 $.post( 
																	'php/inventory.php',
																	{
																		pifpro:$("#ifproduct").val(),
																		pifunit:$("#ifunit").val(),
																		pifsupplier:$("#ifsupplier").val(),
																		piftransaction:$("#iftransaction").val(),
																		pifdelinvoice:$("#ifdelinvoice").val(),
																		pifdfrom:$("#ifdfrom").val(),
																		pifdto:$("#ifdto").val(),
																		pifbr:'<?php echo $br;?>'
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
	<?php
}
if(isset($_POST['ifproduct']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
		<div class="box">
				<div class="box-body">
					<?php inventory("",$ifproduct,$iftransaction,$ifsupplier,$ifdfrom,$ifdto,$ifunit,1,$ifbranch,'','','','',$iflevel,$ifdelinvoice);?>	
				</div>
		</div>
	<?php
}
if(isset($_REQUEST['pifpro']))
{
	?>
		<div id = "printt">
		<?php
			foreach($_POST as $key=>$val) {
				${$key} = $val;
				//echo "The value of ".$key." is ". $val." <br>";
			}
			
			$pro = "ALL";
			$unit = "ALL";
			$supplier = "ALL";
			$tran = "ALL";
			$del = "ALL";
			if($pifpro != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $pifpro"));
				$pro = $prow['item_description'];
			}
			if($pifunit != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $pifunit"));
				$unit = $prow['unit_description'];
			}
			if($pifsupplier != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_supplier where supplier_id = $pifsupplier"));
				$supplier = $prow['supplier_description'];
			}
			if($piftransaction != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where transaction_type_id = $piftransaction"));
				$tran = $prow['transaction_type_description'];
			}
			if($pifdelinvoice != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_delivery_details where delivery_id = $pifdelinvoice"));
				$del = $prow['delivery_invoice_number'];
			}
			
		?>
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">INVENTORY TRANSACTION REPORT</h4>
			<h4 style = "text-align:center"><?php echo $pifdfrom;?> - <?php echo $pifdto;?></h4>
			
			
			<table class = "table table-bordered table-hover table-sm">
				<tr>
					<td>PRODUCT:<?php echo $pro;?></td>
					<td>UNIT:<?php echo $unit;?></td>
					<td>SUPPLIER:<?php echo $supplier;?></td>
					<td>TRANSACTION TYPE:<?php echo $tran;?></td>
					<td>DELIVERY INVOICE NUMBER:<?php echo $del;?></td>
				</tr>
			</table>
			<?php
				inventory("",$pifpro,$piftransaction,$pifsupplier,$pifdfrom,$pifdto,$pifunit,1,$pifbr,'','','','',1,$pifdelinvoice);
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

if(isset($_REQUEST['cancelifilter']))
{
	?>
		<div class = "row">	
							<div class="col-md-4">
								 <div class="form-group">
										  <label for="age">FILTER BY:</label>
											
											<select name = "invfilter" id = "invfilter" class="form-control" >
													<option value = "1">TRANSACTION NUMBER</option>
													<option value = "3">EXPIRATION DATE</option>
													<option value = "2">OTHER PARAMETERS</option>
											</select>
											
								</div>
							</div>
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "go">OK</button>
										
									</div>	
							</div>
		</div>
		<script>
			$("#go").click(
				function()
						{
									var fil = $("#invfilter").val();

									if(fil == 1)
									{
										$('#invfilterui').html(loading);
										$.post( 
										'php/inventory.php',
										{
																	
											invfiltertrannumber:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
									else if(fil == 2)
									{
										$('#invfilterui').html(loading);	
										$.post( 
										'php/inventory.php',
										{
																	
											invfilterpro:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
									else if(fil == 3)
									{
										$('#invfilterui').html(loading);	
										$.post( 
										'php/inventory.php',
										{
																	
											invfilterexpire:1
										},
										function(data) {
											$('#invfilterui').html(data);		
										});
									}
									
															
						}
			);
		</script>
		
	<?php
}
if(isset($_REQUEST['stockmoui']))
{
	$level = $_REQUEST['stockmoui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$br = $branch;
	
	if($level == 1)
		$br = 'all';
	?>
		<h2>STOCK MONITORING</h2>
		<div class="box">
			
			<div class="box-body" id = "invfilterui">
						<form method = "POST" id = "stockfilterform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>STOCK NO:</label>
									<input type = "text" name = "stftockno" id = "stftockno" class="form-control">		
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>PRODUCT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_item where isdeleted = 0 order by item_description");
									?>
									<select name = "stfproduct" id = "stfproduct" class="form-control">
													<option  value = "all" "Selected">ALL</option>
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
							<script>
								$("#stfproduct").select2();
							</script>
								<div class="col-md-3">
								<div class = "form-group">
									<label>CATEGORY:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_category where isdeleted = 0 order by category_description");
									?>
									<select name = "cstfcat" id = "stfcat" class="form-control">
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
									$pquery = mysqli_query($con,"Select * from pos_lup_classification where isdeleted = 0 order by classification_description");
									?>
									<select name = "cstfclass" id = "stfclass" class="form-control">
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
										if($level == 1)
										{
											?>
												<div class="col-md-4">
												<?php
												$cquery = mysqli_query($con,"Select * from lup_branch where isdeleted = 0");
												?>
													<div class="form-group">
														  <label for="age">BRANCH:</label>
															
															<select  name = "stfbranch" id = "stfbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "stfbranch" name = "stfbranch">
											<?php
										}
										?>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "stfdfrom" id = "stfdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "stfdto"  id = "stfdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							

							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">FILTER</button>
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
																	'php/inventory.php',
																	{
																		pstfproduct:$("#stfproduct").val(),
																		pstfclass:$("#stfclass").val(),
																		pstfcat:$("#stfcat").val(),
																		pstfstock:$("#stftockno").val(),
																		pstfdfrom:$("#stfdfrom").val(),
																		pstfdto:$("#stfdto").val(),
																		pstfbranch:$("#stfbranch").val()
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
																	 $("#stockmoui2").html(loading);
																			$.ajax({
																				url :  'php/inventory.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#stockmoui2").html(data);
																					
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
		<div class="box">
			<div class="box-body" id = "stockmoui2" style = "overflow:auto;">
				<?PHP stockmonitoring("","","","",0,$br,'','');?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['montransactionpro']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$level = 0;
	if($montransactionbranch == 'all')
		$level = 1;
	?>
		<div class="box">
			<div class="box-body" id = "stockmoui2" style = "overflow:auto;">
				<?PHP inventory($montransactionstockno,$montransactionpro,"","",$montransactiondfrom,$montransactiondto,'',0,$montransactionbranch,'','','','',$level,'');
				?>
			</div>
		</div>
	<?php
	
}

if(isset($_POST['stfproduct']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	stockmonitoring($stftockno, $stfproduct, $stfdfrom,$stfdto,0,$stfbranch,$cstfcat, $cstfclass);
}
if(isset($_REQUEST['devreportui']))
{
	$user = get_user_id($_SESSION['c_craft']);
	
	$query = mysqli_query($con,"Select se_menu_report.* from se_menu_report, se_report_access where se_report_access.user_id = $user
	and se_report_access.menu_report_id = se_menu_report.menu_report_id and se_report_access.isdeleted = 0
	and se_menu_report.menu_report_module = 'INVENTORY'");
	
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
			
			  
              <div class="tab-pane" id="tab_1">
					<form id = "sreturnfilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">LIST OF RETURNS</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "rldfrom" id = "rldfrom" data-validation="date"
									value = "<?php echo date('Y-m-d');?>"									
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "rldto"  id = "rldto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filter">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printrl">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printrl").click(
								function()
								{
									$.validate({
															form:'#sreturnfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'php/inventory.php',
																	{
																		prldfrom:$("#rldfrom").val(),
																		prldto:$("#rldto").val()
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
															form:'#sreturnfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#sreturnfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#dmonitoringui2").html(loading);
																			$.ajax({
																				url :  'php/inventory.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#returnlistui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "returnlistui"></div>
              </div>		
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
               <form id = "sitemfilterform">
					<div class="box box-warning">
						 <div class="box-header with-border">
						 <h3 class="box-title">SHIPPING STATUS SUMMARY</h3>
						</div>
						
						<div class="box-body">
					
						<div class = "row">	
							<div class="col-md-4">
								<div class = "form-group">
									<label>Date From:</label>
									<input type = "date" class = "form-control" name = "sssdfrom" id = "sssdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Enter Date To:</label>
									<input type = "date" class = "form-control" name = "sssdto"  id = "sssdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
							<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat" id = "filtersss">FILTER</button>
									<button class = "btn btn-warning btn-flat" id = "printsss">PRINT RESULT</button>							
								</div>
							</div>
							
						</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
							$("#printsss").click(
								function()
								{
									$.validate({
															form:'#sitemfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
															
																$.post( 
																	'php/inventory.php',
																	{
																		psssdfrom:$("#sssdfrom").val(),
																		psssdto:$("#sssdto").val()
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
															  return false; // Will stop the submission of the form
															},
														});
								}
							);
							
							$("#filtersss").click(
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
																				url :  'php/inventory.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#ssslistui").html(data);
																					
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								}
							);
						
						</script>
					<div id = "ssslistui"></div>
              </div>
              
            </div>
           
          </div>
        
	<?php
}
if(isset($_POST['rldfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php returnlist($rldfrom,$rldto,0);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['prldfrom']))
{
		$pstdfrom = $_REQUEST['prldfrom'];
		$pstdto = $_REQUEST['prldto'];
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">LIST OF RETURNS</h4>
			<h4 style = "text-align:center"><?php echo $pstdfrom." to ".$pstdto;?></h4>
			
			<?php
				returnlist($pstdfrom,$pstdto,1);
				
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
		</script>
		
	<?php
}
if(isset($_POST['sssdfrom']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php shipping_status_summary($sssdfrom,$sssdto,0);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['psssdfrom']))
{
		$pstdfrom = $_REQUEST['psssdfrom'];
		$pstdto = $_REQUEST['psssdto'];
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">SHIPPING STATUS SUMMARY</h4>
			<h4 style = "text-align:center"><?php echo $pstdfrom." to ".$pstdto;?></h4>
			
			<?php
				shipping_status_summary($pstdfrom,$pstdto,1);
				
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
		</script>
		
	<?php
}
if(isset($_REQUEST['dmonitorui']))
{
	$level = $_REQUEST['dmonitorui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
	<h2>DELIVERY MONITORING</H2>
	<div class="box">
		<div class="box-body">
				<form id = "pfilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>Date From:</label>
												<input type = "date" class = "form-control" name = "ddfrom" id = "ddfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
											</div>		
										</div>
										<div class="col-md-4">
											<div class = "form-group">
												<label>Enter Date To:</label>
												<input type = "date" class = "form-control" name = "ddto" id = "ddto" data-validation="date" 
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
															
															<select  name = "dbranch" id = "dbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "dbranch" name = "dbranch">
											<?php
										}
										?>
										<div class="col-md-3">
													<div class="form-group">
														<label>Order Type:</label>
													
														<Select class = "form-control" name = "dotype" id = "dotype" data-validation="required"
														data-validation-error-msg="Select Order Type">
															<option value = "all" "Selected">ALL</option>
														<?php
														$oquery = mysqli_query($con,"Select * from pos_lup_order_type where isdeleted = 0");
														while($orow = mysqli_fetch_assoc($oquery))
														{
														?>
															<option value = "<?php echo $orow['order_type_id'];?>"><?php echo $orow['order_type_description'];?></option>
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
																				'php/inventory.php',
																				{
																					psbranch:$("#sbranch").val(),
																					psdto:$("#sdto").val(),
																					psdfrom:$("#sdfrom").val()
																					
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
																				 $("#dmonitorui").html(loading);
																						$.ajax({
																							url :  'php/inventory.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#dmonitorui").html(data);
																								
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
		<div id = "dmonitorui">
				
		</div>
	<?php
}
if(isset($_POST['ddto']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div class="box box">
		<div class="box-body">
			<?php dmonitor($dbranch,$ddfrom,$ddto,0,$dotype);?>
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
if(isset($_REQUEST['updatestatusui']))
{
	$id = $_REQUEST['updatestatusui'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $id"));
	
	?>
		<div class="box" style = "margin-top:10px;">
			   <div class="box-header with-border">
						 <h3 class="box-title">UPDATE ORDER STATUS - INVOICE #:<?PHP ECHO $row['sales_invoice_number'];?></h3>
				</div>
			  <div class = "box-body">
					<div class="box">
					<div class = "box-body">
						<form id = "newcardtypeform">
									
									 <div class="form-group">
									  <div class="form-row" >
										<div class="col-md-3">
												<div class="form-group">
													<label>SELECT ORDER STATUS:</label>
													<input type = "hidden" name = "ostatid" value = "<?php echo $id;?>">
													<Select class = "form-control" name = "ostat" id = "ostat" data-validation="required"
													data-validation-error-msg="SELECT ORDER STATUS">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from pos_order_type_status where order_type_id = $row[order_type_id] and isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['pos_order_type_status_id'];?>"><?php echo $prow['status_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
										</div>
										<div class="col-md-3" style = "padding-top:25px;">
										 
											<button class = "btn btn-success btn-flat">ADD</button>							
										 
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
																			url :  'php/inventory.php',
																			type : 'post',
																			datatype : 'json',
																			data : formData,
							
																			success : function(data) {
																				$("#ostatlist").html(data);
																				
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
				
				<div id = "ostatalert"></div>
				<div id = "ostatlist"><?php order_status($id,0);?></div>				
			  </div>
		</div>
	<?php
}
if(isset($_POST['ostatid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_order_type_status where pos_order_type_status_id = $ostat"));
	$sms = mysqli_fetch_assoc(mysqli_query($con,"Select * from sms_lup_message_template where message_template_id = $row[sms_template_id]"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
		$save = mysqli_query($con,"Insert into delivery_status set 
		order_status_id = $ostat,
		order_status = '$row[status_description]',
		sms = '$sms[message_template_description]',
		pos_sales_id = $ostatid
		");
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Order Status Added","#ostatalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Order Status, Contact the System Administrator", "#ostatalert");
			</script>
		<?php
		}
	
	
	order_status($ostatid,0);
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
if(isset($_REQUEST['addreturnui']))
{
	$level = 0;
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
		<h2>ADD RETURNS</h2>
		<div class="box">
			
			<div class="box-body">
					<form method = "POST" id = "returnform">
						<div class = "row">	
							
							
							<div class="col-md-4">
								<div class = "form-group">
									<label>QUANTITY:</label>
									 <input type="text" class="form-control" name = "rquantity" id = "rquantity" data-validation="number"
													data-validation-error-msg="Enter Quantity"
													data-validation-allowing="float,negative">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>STOCK NO:</label>
									 <input type="text" class="form-control" name = "rstock" id = "rstock" data-validation="number"
													data-validation-error-msg="Enter STOCK NO"
													data-validation-allowing="float">
								</div>		
							</div>

							<div class="col-md-4">
								<label>REMARKS:</label>
								<div class="input-group">
									<textarea class="form-control" name = "rremarks"></textarea>
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
															
															<select  name = "ibranch" id = "ibranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "ibranch" name = "ibranch">
											<?php
										}
										?>
										
							<div class="col-md-4" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" >SAVE</button>
									
									</div>	
							</div>
						</div>
					</form>
					<script>
												$.validate({
															form:'#returnform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#returnform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	 $("#stockmui2").html(loading);
																			$.ajax({
																				url :  'php/inventory.php',
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
	<?php
}
if(isset($_POST['rstock']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_transaction where transaction_no = '$rstock'"));
	//$del = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_delivery_details where delivery_id = $row[delivery_id]"));
	$ret = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where isreturn = 1"));
	
		$save = mysqli_query($con,"insert into inv_transaction set
		transaction_no = '$rstock',
		location_id = $ibranch,
		transaction_date = NOW(),
		delivery_id = $row[delivery_id],
		expiration_date = '$row[expiration_date]',
		transaction_type_id = $ret[transaction_type_id],
		item_id = $row[item_id],
		unit_id = $row[unit_id],
		unit_cost = $row[unit_cost],
		markup = $row[markup],
		quantity = ($rquantity*-1),
		remarks = '$rremarks]',
		created_by = '$user',
		reference_id1 = $row[reference_id1],
		created_by_datetime = NOW(),
		created_modified = NOW()");
		
	if($save)
	{
		?>
			<script>
				notify("Inventory Transaction Saved","#inv_alert");
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("Error Saving New Transacition,Please Contact the System Administrator","#inv_alert");
			</script>
		<?php
	}
	
	//inventory("","","","","","","",1,$ibranch,'','','','',0);
}
if(isset($_POST['deleditid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_delivery_details where delivery_id = $deleditid"));
	$date= date_create($row['delivery_date']);
	$deldate = date_format($date,"Y-m-d");
	?>
	<h2>EDIT - DELIVERY DETAILS</h2>
		<div class="box box-warning">
			<div class="box-body">
					<form method = "POST" id = "editdelform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>Delivery INVOICE NUMBER:</label>
									<input type = "hidden" name = "deleditid" value = "<?php echo $deleditid;?>">
									<input type = "hidden" name = "deleditcount" value = "<?php echo $deleditcount;?>">
									<input type = "text" class = "form-control" name = "dinvoiceedit" data-validation="required"
														data-validation-error-msg="Enter Delivery INVOICE NUMBER" value = "<?php echo $row['delivery_invoice_number'];?>">
								</div>		
							</div>
							
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>SUPPLIER:</label>
									
									<?PHP
									$pquery = mysqli_query($con,"Select * from lup_supplier where isdeleted = 0 order by supplier_description");
									$ssup = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_supplier where supplier_id = $row[supplier_id]"));
									?>
									<select name = "dsupplieredit" id = "dsupplieredit" class="form-control" data-validation="required"
													data-validation-error-msg="Select SUPPLIER">
													<option  hidden "Selected" value = "<?php echo $ssup['supplier_id'];?>"><?php echo $ssup['supplier_description'];?></option>
												<?php
													while($prow = mysqli_fetch_assoc($pquery))
													{
												?>
													<option value = "<?php echo $prow['supplier_id'];?>"><?php echo $prow['supplier_description'];?></option>
												<?php
													}
												?>
									</select>
								</div>		
							</div>
						
							<div class="col-md-3">
								<div class = "form-group">
									<label>Delivery Date:</label>
									<input type = "date" class = "form-control" name = "ddeldateedit" data-validation="required"
														data-validation-error-msg="Enter Delivery Date" value = "<?php echo $deldate;?>">
								</div>		
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>AMOUNT:</label>
									 <input type="text" class="form-control" name = "damountedit" id = "damountedit" data-validation="number"
													data-validation-error-msg="Enter Amount"
													data-validation-allowing="float" value = "<?php echo $row['amount'];?>">
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>PAYMENT TERM:</label>
									<?php
										$term = '';
										if($row['payment_terms'] == 1)
										$term = "CASH";
										else if ($row['payment_terms'] == 2)
										$term = "INSTALLMENT";
									?>
									<select name = "dptermedit" id = "dptermedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select PAYMENT TERM">
													<option  hidden "Selected"><?php echo $term;?></option>
													<option value = 1>CASH</option>
													<option  value = 2>INSTALLMENT</option>
												
									</select>
								</div>		
							</div>
									
							<div class="col-md-3" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" >UPDATE</button>
									
									</div>	
							</div>
						</div>
					</form>
					<script>
												$.validate({
															form:'#editdelform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#editdelform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	// var head = $("#dfdfrom").val()+" TO "+$("#dfdto").val()+" DELIVERIES";
																	 
																	 //$("#dheadui").html(head);
																	
																			$.ajax({
																				url :  'php/inventory.php',
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
						
					
				<div id = "deleditalert"></div>
			</div>
		</div>
	<?php	
}
if(isset($_POST['dinvoiceedit']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
		echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$check = mysqli_num_rows(mysqli_query($con, "Select * from inv_delivery_details where delivery_invoice_number = '$dinvoiceedit'
	and delivery_id != $deleditid"));
	
	$user = get_user_id($_SESSION['c_craft']);
	if($check == 0)
	{
		$save = mysqli_query($con,"update inv_delivery_details set
		delivery_invoice_number = '$dinvoiceedit',
		amount = $damountedit,
		supplier_id = $dsupplieredit,
		delivery_date = '$ddeldateedit',
		payment_terms = '$dptermedit'
		where delivery_id = $deleditid");
		
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_delivery_details where delivery_id = $deleditid"));
		$dsup = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_supplier where supplier_id = $row[supplier_id]"));
		
										$term = '';
										if($row['payment_terms'] == 1)
										$term = "CASH";
										else if ($row['payment_terms'] == 2)
										$term = "INSTALLMENT";
		if($save)
		{
					?>
						<script>
							notify("Delivery Details Updated","#deleditalert");
							
							$("#dinvoiceui<?php echo $deleditcount;?>").html('<?php echo $row['delivery_invoice_number'];?>');
							$("#ddateui<?php echo $deleditcount;?>").html('<?php echo $row['delivery_date'];?>');
							$("#dsupui<?php echo $deleditcount;?>").html('<?php echo $dsup['supplier_description'];?>');
							$("#damountui<?php echo $deleditcount;?>").html('<?php echo number_format($row['amount'],2);?>');
							$("#dptermui<?php echo $deleditcount;?>").html('<?php echo $term;?>');
							$("#click").html('');
						</script>
		<?php
		}
		else
		{
			?>
				<script>
					notify("Error Updating Delivery Details, Please Contact the system administrator","#deleditalert");
				</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				notify("Delivery Invoice Number Already Exists","#deleditalert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['remdelid']))
{
	foreach($_POST as $key=>$val) {
	${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$del = mysqli_query($con,"Update inv_delivery_details set isdeleted = 1 where delivery_id = $remdelid");
	
	if($del)
	{
		?>
			<script>
				notify("<i class='fa fa-exclamation-info'></i> Delivery Details deleted","#deleditalert");
				$("#controlui<?php echo $remdelcount;?>").html('DELIVERY DETAILS DELETED');
			</script>
		<?php
	}
	else
	{
		?>
			<script>
				notify("<i class='fas fa-exclamation-triangle'></i> Error Deleting Delivery Details, contact the system administrator","#deleditalert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['dfinvoice']))
{
	foreach($_POST as $key=>$val) {
	${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	?>
	<div class="box box-warning">
		<div class="box-body">
			<?php deliverydetails($dfinvoice,$dfsupplier,$dfdfrom,$dfdto,0);?>
		</div>
	</div>
	<?php
}
if(isset($_REQUEST['cstockmui']))
{
	$level = $_REQUEST['cstockmui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$br = $branch;
	
	if($level == 1)
		$br = 'all';
	?>
		<h2>CURRENT STOCK INQUIRY</h2>
		<div class="box box-warning">
			
			<div class="box-body" id = "invfilterui">
						<form method = "POST" id = "stockfilterform">
						<div class = "row">	
							<div class="col-md-3">
								<div class = "form-group">
									<label>PRODUCT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from pos_lup_item where isdeleted = 0 order by item_description");
									?>
									<select name = "cstfproduct" id = "cstfproduct" class="form-control">
													<option "Selected" value = "all">ALL</option>
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
							<script>
								$("#cstfproduct").select2();
							</script>
							<div class="col-md-3">
								<div class = "form-group">
									<label>UNIT:</label>
									<?PHP
									$pquery = mysqli_query($con,"Select * from inv_lup_unit where visible = 1");
									?>
									<select name = "cstfunit" id = "cstfunit" class="form-control">
													<option "Selected" value = "all">ALL</option>
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
									$pquery = mysqli_query($con,"Select * from pos_lup_classification where isdeleted = 0 order by classification_description");
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
																	'php/inventory.php',
																	{
																		pcstfproduct:$("#cstfproduct").val(),
																		pcstfunit:$("#cstfunit").val(),
																		pcstfcat:$("#cstfcat").val(),
																		pcstfclass:$("#cstfclass").val(),
																		pcstfbranch:$("#cstfbranch").val()
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
																	 $("#stockmoui2").html(loading);
																			$.ajax({
																				url :  'php/inventory.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#stockmoui2").html(data);
																					
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
		
		<div id = "stockmoui2"></div>
		
	<?php
}

if(isset($_POST['cstfproduct']))
{
	foreach($_POST as $key=>$val) {
		${$key} = $val;
	//echo "The value of ".$key." is ". $val." <br>";
	}
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?PHP cstockmonitoring($cstfproduct,$cstfunit,$cstfcat, $cstfclass,'','',0,$cstfbranch);?>
			</div>
		</div>
	<?php
	
}
if(isset($_REQUEST['pcstfproduct']))
{
	?>
		<div id = "printt">
		<?php
			foreach($_POST as $key=>$val) {
				${$key} = $val;
				//echo "The value of ".$key." is ". $val." <br>";
			}
			
			$pro = "ALL";
			$unit = "ALL";
			$cat = "ALL";
			$class = "ALL";
			if($pcstfproduct != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $pcstfproduct"));
				$pro = $prow['item_description'];
			}
			
			if($pcstfunit != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $pcstfunit"));
				$unit = $prow['unit_description'];
			}
			if($pcstfcat != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_category where category_id = $pcstfcat"));
				$cat = $prow['category_description'];
			}
			if($pcstfclass != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $pcstfclass"));
				$class = $prow['classification_description'];
			}
		?>
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CURRENT STOCKS</h4>
			<h4 style = "text-align:center"><?php echo date('Y-m-d');?></h4>
			
			<table class = "table table-bordered table-hover table-sm">
				<tr>
					<td>PRODUCT:<?php echo $pro;?></td>
					<td>UNIT:<?php echo $unit;?></td>
					<td>CATEGORY:<?php echo $cat;?></td>
					<td>CLASSIFICATION:<?php echo $class;?></td>
				</tr>
			</table>
			<?php
				cstockmonitoring($pcstfproduct,$pcstfunit,$pcstfcat, $pcstfclass,'','',1,$pcstfbranch);
				
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
if(isset($_REQUEST['pstfproduct']))
{
	?>
		<div id = "printt">
		<?php
			foreach($_POST as $key=>$val) {
				${$key} = $val;
				//echo "The value of ".$key." is ". $val." <br>";
			}
			
			$pro = "ALL";
			$unit = "ALL";
			$cat = "ALL";
			$class = "ALL";
			if($pstfproduct != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $pstfproduct"));
				$pro = $prow['item_description'];
			}
			
			
			if($pstfcat != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_category where category_id = $pstfcat"));
				$cat = $prow['category_description'];
			}
			if($pstfclass != "all")
			{
				$prow = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $pstfclass"));
				$class = $prow['classification_description'];
			}
		?>
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">STOCK MONITORING REPORT</h4>
			<h4 style = "text-align:center"><?php echo $pstfdfrom." - ".$pstfdto;?></h4>
			
			<table class = "table table-bordered table-hover table-sm">
				<tr>
					<td>PRODUCT:<?php echo $pro;?></td>
					<td>STOCK NO:<?php echo $pstfstock;?></td>
					<td>CATEGORY:<?php echo $cat;?></td>
					<td>CLASSIFICATION:<?php echo $class;?></td>
				</tr>
			</table>
			<?php
				stockmonitoring($pstfstock, $pstfproduct, $pstfdfrom,$pstfdto,1,$pstfbranch,$pstfclass,$pstfcat);
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
?>
