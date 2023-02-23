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
function takeorders($print,$prep)
{
	global $con;
	$string = "Select * from  pos_sales,se_user where pos_sales.isdeleted = 0 and pos_sales.sales_invoice_number = ''
	and se_user.user_id = pos_sales.created_by_fullname and pos_sales.order_count != 0 order by pos_sales.order_count";
	
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "takeordertable">
								<thead>
								<?PHP
								IF($print == 0)
								{
								?>
									<th></th>
								<?php
								}
								?>
									
									<th>ORDER NO.</th>
									<th>ORDER TYPE</th>
									<th>TOTAL QUANTITY</th>
									<th>TOTAL PRICE</th>
									
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$otype = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_order_type where order_type_id = $row[order_type_id]"));
				$pdetail = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as QTY, SUM(grand_total) as price from pos_sales_detail where pos_sales_id = $row[pos_sales_id] and isdeleted = 0
				and finalize = 1"));
				if($pdetail['QTY'] > 0)
				{
				?>
				<tr>
					<?PHP
								IF($print == 0)
								{
								?>
									<td>
									<?php
									if($prep == 0)
									{
										?>
										<a href = "" id = "open<?php echo $ctr;?>" class = "btn btn-success btn-xs btn-flat btn-block">OPEN</a>	
										<script>
											$("#open<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$.post( 
															'php/pos.php',
															{
																currentorderui:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#maincontent').html(data);		
															});
													
													
												}
											);
											
											
											</script>	
										<?php
									}
									else
									{
										echo "<b>DONE</b>";
									}
									?>
							
									</td>
								<?php
								}
								?>
					
					<th><?php echo $row['order_count'];?></th>
					<th><?php echo $otype['order_type_description'];?></th>
					<td><?php echo $pdetail['QTY'];?></td>
					<td><?php echo $pdetail['price'];?></td
				</tr>
				<?php
				$ctr++;
				}
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
						
					$('#takeordertable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function tables($level)
{
		global $con;
		$user = get_user_id($_SESSION['c_craft']);
		$agent = get_agent($user);
		$branch = get_branch($user);
	
		if($level == 1)
			$query = mysqli_query($con,"Select * from se_user where istable = 1 and isdeleted = 0 order by agent_number");
		else
			$query = mysqli_query($con,"Select * from se_user where istable = 1 and branch_id = $branch and isdeleted = 0 order by agent_number");	
	?>
		<table class = "table table-striped table-hover table-sm" id = "ttable">
								<thead>
									<th>#</th>
									<th>TABLE NAME</th>
									<th>BRANCH</th>
									<th>ACTION</TH>
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{

				$branch = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['fullname'];?></td>
						<td><?php echo $branch['branch_description'];?></td>
						<td>
							<button class = "btn btn-primary btn-flat btn-xs" id = "reset<?php echo $ctr;?>">DELETE</button>	
							<button class = "btn btn-warning btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>	
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
																	edit_tableid:'<?php echo $row['user_id'];?>',
																	edit_tablelevel:'<?php echo $level;?>'
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
														
															
															var r = confirm("Confirm DELETE");
															
															if(r == true)
															{
															$.post( 
																'php/main.php',
																{
																	tabledelete:'<?php echo $row['user_id'];?>'
																},
																function(data) {
																	$('#userlistui').html(data);		
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
						
					$('#ttable').DataTable({
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
function order_tables($branch,$level)
{
		global $con;
		$user = get_user_id($_SESSION['c_craft']);
		$agent = get_agent($user);
	
		if($level == 1)
			$query = mysqli_query($con,"Select * from se_user where istable = 1 and isdeleted = 0 order by agent_number");
		else
			$query = mysqli_query($con,"Select * from se_user where istable = 1 and branch_id = $branch and isdeleted = 0 order by agent_number");	
	?>
		<table class = "table table-striped table-hover table-sm" id = "ttable">
								<thead>
									<th>#</th>
									<th>TABLE NAME</th>
									<th>BRANCH</th>
									<th>ACTION</TH>
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{

				$branch = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['fullname'];?></td>
						<td><?php echo $branch['branch_description'];?></td>
						<td>
							<button class = "btn btn-primary btn-flat btn-xs btn-block" id = "order<?php echo $ctr;?>">ORDERS</button>	
							
						</td>
					</tr>
					<script>
													
						$("#order<?php echo $ctr;?>").click(
														function(e)
														{
															e.preventDefault();
															$.post( 
																'php/pos.php',
																{
																	takeorderui:'<?php echo $row['user_id'];?>'
																	
																},
																function(data) {
																	$('#maincontent').html(data);		
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
						
					$('#ttable').DataTable({
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
function orders($print,$prep)
{
	global $con;
	$string = "Select * from  pos_sales,se_user where pos_sales.isdeleted = 0 and pos_sales.sales_invoice_number = ''
	and se_user.user_id = pos_sales.created_by_fullname and pos_sales.order_count != 0";
	
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "ordertable">
								<thead>
								<?PHP
								IF($print == 0)
								{
								?>
									<th></th>
								<?php
								}
								?>
									
									<th>ORDER NO</th>
									<th>TOTAL QUANTITY</th>
									<th>TOTAL PRICE</th>
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
			
				$pdetail = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as QTY, SUM(grand_total) as price from pos_sales_detail where pos_sales_id = $row[pos_sales_id] and isdeleted = 0
				and finalize = 1"));
				if($pdetail['QTY'] > 0)
				{
				?>
				<tr>
					<?PHP
								IF($print == 0)
								{
								?>
									<td>
									<?php
									if($prep == 0)
									{
										?>
										<a href = "" id = "open<?php echo $ctr;?>" class = "btn btn-success btn-xs btn-flat">OPEN</a>
										<a style = "display:none;" href = "" id = "prep<?php echo $ctr;?>" class = "btn btn-primary btn-xs btn-flat">PREPARATIONS</a>	
										<script>
											$("#open<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$.post( 
															'php/pos.php',
															{
																pos_orderui:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#maincontent').html(data);		
															});
													
													
												}
											);
											
											
											</script>	
										<?php
									}
									else
									{
										echo "<b>DONE</b>";
									}
									?>
							
									</td>
								<?php
								}
								?>
					
					<td><?php echo $row['order_count'];?></td>
					<td><?php echo $pdetail['QTY'];?></td>
					<td><?php echo $pdetail['price'];?></td
				</tr>
				<?php
				$ctr++;
				}
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
						
					$('#ordertable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function add_ons($item_id,$level,$print)
{
	global $con;
	
	$string = "Select * from pos_lup_item where isdeleted = 0 and addon_id = $item_id";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>DESCRIPTION</th>
									<th>PRICE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
								<td>
								<input type="text" ID="aeitemdes<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['item_description'];?>">
							</td>
							<td>
								<input type="text" ID="aeitemprice<?php echo $ctr;?>" class="form-control" value = "<?php echo $row['item_price1'];?>">
							</td>
							
							<td id = "actionui<?php echo $ctr;?>">
								<?php
								if($level == 1)
								{
								?>
									<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
									<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
							<?php
								}
								?>
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
											e.preventDefault();
																				
											$.post( 
												'php/main.php',
												{
													addonedit:<?php echo $row['item_id'];?>,
													addoneditdes:$("#aeitemdes<?php echo $ctr;?>").val(),
													addoneditprice:$("#aeitemprice<?php echo $ctr;?>").val(),
												},
												function(data) {
													$('#click').html(data);
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											
											$.post( 
												'php/main.php',
												{
													addondel:<?php echo $row['item_id'];?>,
													addoncount:'<?php echo $ctr;?>'
												},
												function(data) {
													$('#click').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{	
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function variations($product,$dfrom,$dto,$des,$dis)
{
	global $con;
	$string = "Select * from lup_variations,pos_lup_item where lup_variations.isdeleted = 0
	and lup_variations.item_id = pos_lup_item.item_id";
	
	if(!empty($product) && $product != "ALL")
		$string = $string." and lup_variations.item_id = '$product'";
	
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
									
									<th>PRODUCT</th>
									<th>DESCRIPTION</th>
									<th>PRICE</th>
								</thead>
		<?php
			$ctr = 1;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<?php
						if($dis == 1)
						{
						?>
							<td id = "invactions<?php echo $ctr;?>">
							
							
							<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>	
							<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>">EDIT</button>
							<?php
							
							?></td>
						<?php
						}
						?>
						
					
						<td><?php echo $row['item_description'];?></td>
						<td><?php echo $row['description'];?></td>
						<td><?php echo number_format($row['unit_price'],2);?></td>
						
					</tr>
					<script>
						$("#delete<?php echo $ctr;?>").click(
										function()
										{
										
															var r = confirm("confirm delete");

															if(r == true)
															{
																$.post( 
																	'php/main.php',
																	{
																	
																		itransactiondelete:'<?php echo $row["variation_id"];?>',
																		ivardeletecount:'<?php echo $ctr;?>'
																	},
																	function(data) {
																		$('#click').html(data);		
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
																'php/main.php',
																{
																	edititransaction:'<?php echo $row["variation_id"];?>',
																	
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

function prep($print,$prep)
{
	global $con;
	$string = "Select * from  pos_sales_detail,pos_sales,se_user where pos_sales_detail.isdeleted = 0 and pos_sales_detail.done = $prep
	and se_user.user_id = pos_sales_detail.created_by and pos_sales_detail.addon_id = 0 and pos_sales_detail.discount = 0
	and pos_sales.pos_sales_id = pos_sales_detail.pos_sales_id
	and pos_sales.order_count != 0";
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								<?PHP
								IF($print == 0)
								{
								?>
									<th></th>
								<?php
								}
								?>
									<th>ORDER COUNT</th>
								
									<th>DATE AND TIME</th>
									<th>QUANTITY</th>
									<th>PRODUCT</th>
									<th>ADD ONS</th>
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				//$u = mysqli_fetch_assoc(mysqli_query($con,"Select lup_size_unit.*,lup_size.unit_price from lup_size, lup_size_unit where lup_size.size_id = $row[unit_id]
				//and lup_size.unit_id = lup_size_unit.unit_id"));
				
				?>
				<tr>
					<?PHP
								IF($print == 0)
								{
								?>
									<td>
									<?php
									if($prep == 0)
									{
										?>
										<a href = "" id = "don<?php echo $ctr;?>" class = "btn btn-success btn-xs btn-flat">SERVED</a>
										
										<script>
											$("#don<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													var r = confirm("Mark as Served?");
													
													if(r == true)
													{
														$.post( 
															'php/pos.php',
															{
																doneprep:<?php echo $row['pos_sales_detail_id'];?>
															},
															function(data) {
																$('#smonitorui').html(data);		
															});
													}
													
												}
											);
											
											
											</script>	
										<?php
									}
									else
									{
										echo "<b>SERVED</b> - $row[done_date]";
									}
									?>
							
									</td>
								<?php
								}
								?>
					<td><?php echo $row['order_count'];?></td>
					<td><?php echo $row['created_modified'];?></td>
					<td><?php echo $row['quantity'];?></td>
					<td><?php echo $row['item_description'];?></td>
					
					<td>
						<?php
						$add_query = mysqli_query($con,"Select * from pos_sales_detail where addon_id = $row[pos_sales_detail_id]");
						
						while($add = mysqli_fetch_assoc($add_query))
						{

							echo "[".$add['quantity']."-".$add['item_description']."] ";
						}
						?>
					</td>
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
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function update_change($tran)
{
	global $con;
	$grand = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(grand_total) as total from pos_sales_detail where pos_sales_id = $tran and isdeleted = 0"));
	$total_settle = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount) as total from pos_sales_settlement where pos_sales_id = $tran and isdeleted = 0"));
	$change_a = $total_settle['total']-$grand['total'];
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from pos_sales_settlement where pos_sales_id = $tran and isdeleted = 0 order by pos_sales_settlement_id DESC"));
	mysqli_query($con, "Update pos_sales_settlement set change_amount = $change_a where pos_sales_settlement_id = $row[pos_sales_settlement_id]");
}
function deliverydetails($invoice,$supplier,$dfrom,$dto,$print)
{
	
	global $con;
	
	$string = "Select * from inv_delivery_details where isdeleted = 0";
	
	if(!empty($invoice))
	{
		$string = $string." and delivery_invoice_number = '$invoice' ";
	}
	
	if(!empty($supplier) && $supplier != 'all')
	{
		$string = $string." and supplier_id = $supplier";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(date_added,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(date_added,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	$string = $string." order by delivery_date desc"; 
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								
								<th>#</th>
									<th>DELIVERY INVOICE NO</th>
									<th>DELIVERY DATE</th>
									<th>SUPPLIER</th>
									<th>PRICE</th>
									<th>PAYMENT TERM</th>
									<th>STOCKS</th>
									<?PHP
									IF($print == 0)
									{
									?>
										<th></th>
									<?php
									}
									?>
								</thead>
			<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$sup = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_supplier where supplier_id = $row[supplier_id]"));
				$st = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as q from inv_transaction where delivery_id = $row[delivery_id] and isdeleted = 0"));
				?>
					<TR>
					
						<td><?php echo $ctr;?></td>
						<td id = "dinvoiceui<?php echo $ctr;?>"><?php echo $row['delivery_invoice_number'];?></td>
						<td id = "ddateui<?php echo $ctr;?>"><?php echo $row['delivery_date'];?></td>
						<td id = "dsupui<?php echo $ctr;?>"><?php echo $sup['supplier_description'];?></td>
						<td id = "damountui<?php echo $ctr;?>"><?php echo number_format($row['amount'],2);?></td>
						<td id = "dptermui<?php echo $ctr;?>"><?php
							if($row['payment_terms'] == 1)
								echo "CASH";
							else if ($row['payment_terms'] == 2)
								echo "INSTALLMENT";
						?></td>
						<td><?php echo number_format($st['q']);?></td>
							<?PHP
								IF($print == 0)
								{
								?>
									<th ID = "controlui<?php echo $ctr;?>">
										<a href = "" id = "edit<?php echo $ctr;?>" class = "btn btn-warning btn-xs btn-flat">EDIIT</a>
										<a href = "" id = "del<?php echo $ctr;?>" class = "btn btn-danger btn-xs btn-flat">DELETE</a>
									</th>
									<script>
										$("#edit<?php echo $ctr;?>").click(
											function(e)
											{
												e.preventDefault();
													$("#modal").modal("show");
													$("#modalbody").css("min-width","70%");
													$.post( 
														'php/inventory.php',
														{
															deleditid:<?php echo $row['delivery_id'];?>,
															deleditcount:'<?php echo $ctr;?>'
															
														},
														function(data) {
															$('#modalui').html(data);		
														});
												
											}
										);
										$("#del<?php echo $ctr;?>").click(
											function(e)
											{
												e.preventDefault();
												var r = confirm("Confirm Delete");
												
												if(r == true)
												{
													$.post( 
														'php/inventory.php',
														{
															remdelid:<?php echo $row['delivery_id'];?>,
															remdelcount:'<?php echo $ctr;?>'
															
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
								?>
								
					</tr>
					
				<?PHp
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
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function print_rec_settings()
{
	global $con;
	
	$string = "Select * from settings_receipt_print where iscurrent = 1";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th></th>
									<th>#</th>
									<th>ENABLE</th>
									<th>DATE</th>
							
									
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td>
								<?php 
								if($row['enable'] == 0)
								{	
								?>
									<a href = "" id = "en<?php echo $ctr;?>" class = "btn btn-success btn-flat">ENABLE</a>
								<?PHP
								}
								else
								{
									?>
									<a href = "" id = "dis<?php echo $ctr;?>" class = "btn btn-danger btn-flat">DISABLE</a>
									<?php
								}									
								?>
								
								
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php if($row['enable'] == 1)
									echo "TRUE";
								else
									echo "FALSE";
								?></td>
							<td><?php echo $row['date_added'];?></td>
				

							<script>
								
								
								$("#en<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Action")
										
										if(r == true)
										{
											$("#recui").html(loading);
											$.post( 
												'php/main.php',
												{
													enrec:1
													
												},
												function(data) {
													$('#recui').html(data);		
												});
										}
									}
								);
								
								$("#dis<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Action")
										
										if(r == true)
										{
											$("#recui").html(loading);
											$.post( 
												'php/main.php',
												{
													disrec:1
													
												},
												function(data) {
													$('#recui').html(data);		
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

function collection_settlement($pos_sales)
{
	global $con;
	$string = "Select * from ledger_sales_income, lup_settlement_type where ledger_sales_income.pos_sales_id = $pos_sales and ledger_sales_income.isdeleted = 0
	and lup_settlement_type.settlement_type_id = ledger_sales_income.settlement_type_id";
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									
									<th>#</th>
									<th></th>
									<th>SETTLEMENT</th>
									<th>AMOUNT</th>
									<th>REFERENCE NO</th>
									<th>DATE</th>
								</thead>
			<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><button class = "btn btn-danger btn-flat btn-xs" id = "del<?php echo $ctr;?>">DELETE</td>
						<TD><?php echo $row['settlement_description'];?></td>
						<TD><?php echo number_format($row['amount'],2);?></td>
						<TD><?php echo $row['payment_reference_no'];?></td>
						<TD><?php echo $row['sales_income_date'];?></td>
					</tr>
					<script>
						$("#del<?php echo $ctr;?>").click(
							function()
							{
								var r = confirm("Confirm Delete");
								
								if(r == true)
								{

														$.post( 
															'php/pos.php',
															{
																col_pay_delete:<?php echo $row['sales_income_id'];?>
															},
															function(data) {
																$('#collection_settlement_list').html(data);		
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
}
function collection($cus,$branch,$dfrom,$dto,$paid,$print)
{
	
	global $con;
	
	$string = "Select pos_sales.* from pos_sales,customer_profile where pos_sales.sales_invoice_number != '' and pos_sales.customer_id != 0
	and pos_sales.isdeleted = 0";
	
	if(!empty($cus))
	{
		$string = $string." and (pos_sales.sales_invoice_number = '$cus' || customer_profile.customer_no = '$cus')";
	}
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and pos_sales.branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	$map = mysqli_fetch_assoc(mysqli_query($con,"Select settlement_type_id from settings_settlement_mapping where charge_to_customer = 1"));
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								<?PHP
								IF($print == 0)
								{
								?>
									<th></th>
								<?php
								}
								?>
								<th>#</th>
									<th>SALES INVOICE NO</th>
									<th>DATE</th>
									<th>BRANCH</th>
									<th>CUSTOMER</th>
									<th>CREATED BY</th>
									<th>SETTLEMENT</th>
									<th>TOTAL QTY</th>
									<th>TOTAL</th>
									<th>TOTAL COLLECTION</th>
									<th>BALANCE</th>
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			$tcol = 0;
			$tbal = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$tq = $tq+ $row['total_quantity'];
				$total = $total + $row['total_sales'];
				$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				
				$col2 = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(amount)as total from ledger_sales_income where pos_sales_id = $row[pos_sales_id] and 
						isdeleted = 0 and amount > 0"));
				
				$col = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(amount)as total from ledger_sales_income where pos_sales_id = $row[pos_sales_id] and 
						isdeleted = 0"));
						
				$cr = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(settlement_amount)as total, SUM(change_amount)as ch from pos_sales_settlement where pos_sales_id = $row[pos_sales_id] and 
				isdeleted = 0 and settlement_type_id = $map[settlement_type_id]"));
				
				$bal = ($col2['total'])-($cr['total']-$cr['ch']);
				
				
				$ccheck = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales_settlement where pos_sales_id = $row[pos_sales_id] and isdeleted = 0 and settlement_type_id <> $map[settlement_type_id]"));
				$bal2 = 0;
				if($bal2 > 0)
				{
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<?PHP
								IF($print == 0)
								{
								?>
									<td>
									<a href = "" id = "det<?php echo $ctr;?>" class = "btn btn-success btn-xs btn-flat">DETAILS</a>
									<a href = "" id = "pay<?php echo $ctr;?>" class = "btn btn-primary btn-xs btn-flat">PAYMENT</a>
									
											<script>
											$("#pay<?php echo $ctr;?>").click(
											function(e)
											{
												e.preventDefault();
												$("#modal").modal("show");
												$("#modalbody").css("min-width","50%");
															
															//$("#streetform").html(loading);
															$("#modalui").html(loading);
															$.post( 
																'php/pos.php',
																{
																	addpaymentui:'<?php echo $row['pos_sales_id'];?>',
																	addpaymentsettle:'',
																	addpaymentctr:'<?php echo $ctr;?>',
																	addpaymentsettle2:'0',
																	addpaymentremarks:'FROM_COLLECTION'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
											}
											);
										
											
											
											$("#det<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$("#modal2").modal("show");
														$("#modalbody2").css("min-width","65%");
														
														//$("#streetform").html(loading);
														$("#modalui2").html(loading);
														
														$.post( 
															'php/pos.php',
															{
																invoicedetails:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#modalui2').html(data);		
															});
													
												}
											);
											</script>	
									</td>
								<?php
								}
						
								?>
					
					<td><?php echo $row['sales_invoice_number'];?></td>
					<td><?php echo $row['sales_datetime'];?></td>
					<td><?php echo $br['branch_description'];?></td>
					<td><?php echo $row['customer_fullname'];?></td>
					<td><?php echo $row['created_by_fullname'];?></td>
					<td>
						<?php $set_query = mysqli_query($con,"Select * from pos_sales_settlement, lup_settlement_type where 
						pos_sales_settlement.pos_sales_id = $row[pos_sales_id] and pos_sales_settlement.settlement_type_id = lup_settlement_type.settlement_type_id
						and pos_sales_settlement.isdeleted = 0");
						while($set = mysqli_fetch_assoc($set_query))
						{
							echo $set['settlement_description'].", ";
						}
						?>
					</td>
					<td><?php echo $row['total_quantity'];?></td>
					<td><?php echo number_format($row['total_sales']-$cr['total'],2);?></td>
					<td id = "ccolui<?php echo $ctr;?>"><?php
						
						
						$tcol = $tcol + ($col['total']);
						$tbal = $tbal + (($row['total_sales']-$cr['total'])-($col['total']));
						
						echo number_format($col['total'],2);
						?></td>
					<td id = "cbalui<?php echo $ctr;?>"><?php echo number_format(($row['total_sales']-($cr['total']))-($col['total']-$col['ch']),2);?></td>
				</tr>
				<?php
					$ctr++;
				}
				$ccheck = mysqli_num_rows(mysqli_query($con,"Select * from pos_sales_settlement where pos_sales_id = $row[pos_sales_id] and isdeleted = 0 and settlement_type_id = $map[settlement_type_id]"));
				if($ccheck != 0)
				{
					$cquery = mysqli_query($con,"Select pos_sales.*,pos_sales_settlement.settlement_amount, pos_sales_settlement.settlement_type_id from pos_sales_settlement, pos_sales where pos_sales.pos_sales_id = $row[pos_sales_id] and
					pos_sales.pos_sales_id = pos_sales_settlement.pos_sales_id and pos_sales_settlement.isdeleted = 0 and pos_sales_settlement.settlement_type_id = $map[settlement_type_id]");
					
					while($crow = mysqli_fetch_assoc($cquery))
					{
							//$ctr++;
							//$tq = $tq+ $crow['total_quantity'];
							//$total = $total + $crow['total_sales'];
							$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $crow[branch_id]"));
				
						?>
							<tr>
								<td><?php echo $ctr;?></td>
								<?PHP
											IF($print == 0)
											{
											?>
												<td>
												<a href = "" id = "det<?php echo $ctr;?>" class = "btn btn-success btn-xs btn-flat">DETAILS</a>
												<a href = "" id = "pay<?php echo $ctr;?>" class = "btn btn-primary btn-xs btn-flat">PAYMENT</a>
												
														<script>
														$("#pay<?php echo $ctr;?>").click(
														function(e)
														{
															e.preventDefault();
															$("#modal").modal("show");
															$("#modalbody").css("min-width","50%");
																		
																		//$("#streetform").html(loading);
																		$("#modalui").html(loading);
																		$.post( 
																			'php/pos.php',
																			{
																				addpaymentui:'<?php echo $crow['pos_sales_id'];?>',
																				addpaymentsettle:'a',
																				addpaymentctr:'<?php echo $ctr;?>',
																				addpaymentsettle2:'<?php echo $crow['settlement_amount'];?>',
																				addpaymentremarks:'FROM_COLLECTION'
																				
																			},
																			function(data) {
																				$('#modalui').html(data);		
																			});
														}
														);
													
														
														
														$("#det<?php echo $ctr;?>").click(
															function(e)
															{
																e.preventDefault();
																
																	$("#modal2").modal("show");
																	$("#modalbody2").css("min-width","65%");
																	
																	//$("#streetform").html(loading);
																	$("#modalui2").html(loading);
																	
																	$.post( 
																		'php/pos.php',
																		{
																			invoicedetails:<?php echo $row['pos_sales_id'];?>
																		},
																		function(data) {
																			$('#modalui2').html(data);		
																		});
																
															}
														);
														</script>	
												</td>
											<?php
											}
											?>
								
								<td><?php echo $crow['sales_invoice_number'];?></td>
								<td><?php echo $crow['sales_datetime'];?></td>
								<td><?php echo $br['branch_description'];?></td>
								<td><?php echo $crow['customer_fullname'];?></td>
								<td><?php echo $crow['created_by_fullname'];?></td>
								<td>CREDIT LINE</td>
								<td><?php echo $row['total_quantity'];?></td>
								<td><?php echo $crow['total_sales'];?></td>
								<td id = "crcolui<?php echo $ctr;?>"><?php
									$map = mysqli_fetch_assoc(mysqli_query($con,"Select settlement_type_id from settings_settlement_mapping where charge_to_customer = 1"));
									
									$col = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(amount)as total from ledger_sales_income where pos_sales_id = '$crow[pos_sales_id]' and 
									isdeleted = 0"));
									
									$tcol = $tcol + ($col['total']);
									$tbal = $tbal + ($crow['total_sales']-($col['total']));
						
									echo number_format($col['total'],2);
									?></td>
								<td id = "crbalui<?php echo $ctr;?>"><?php echo number_format($crow['total_sales']-($col['total']),2);?></td>
							</tr>
						<?php
						$ctr++;
					}
				}
				
				
			}
			
			?>
	</table>
	
	<table class = "table table-bordered table-hover table-xs">
		<tr>
			<th>TOTAL TRANSACTIONS: <?php echo number_format($ctr-1,2);?> </th>
			<th>TOTAL QUANTITY: <?php echo number_format($tq,2);?></th>
			<th>TOTAL AMOUNT: <?php echo number_format($total,2);?></th>
			<th>TOTAL COLLECTION: <?php echo number_format($tcol,2);?></th>
			<th>TOTAL BALANCE: <?php echo number_format($tbal,2);?></th>
		</tr>
	</table>
	<?php
		
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function order_status($pos,$print)
{
	
	global $con;
	
	$string = "Select * from delivery_status where pos_sales_id = $pos and isdeleted = 0";
	
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
									<?PHP
									if($print == 0)
									{
									?>
									<th></th>
									<?php
									}
									?>
									<th>#</th>
									<th>ORDER STATUS</th>
									<th>SMS</th>					
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<?PHP
								IF($print == 0)
								{
								?>
									<td>
										<a href = "" id = "current<?php echo $ctr;?>" class = "btn btn-success btn-xs btn-flat">SET AS CURRENT</a>
										<a href = "" id = "del<?php echo $ctr;?>" class = "btn btn-warning btn-xs btn-flat">DELETE</a>
											<script>	
											$("#print<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$.post( 
															'php/pos.php',
															{
																printinvoice:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#click').html(data);		
															});
													
												}
											);
											
											$("#det<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$("#modal2").modal("show");
														$("#modalbody2").css("min-width","65%");
														
														//$("#streetform").html(loading);
														$("#modalui2").html(loading);
														
														$.post( 
															'php/pos.php',
															{
																invoicedetails:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#modalui2').html(data);		
															});
													
												}
											);
											
											</script>	
									</td>
								<?php
								}
								?>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['order_status'];?></td>
					<td><?php echo $row['sms'];?></td>	
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
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function dmonitor($branch,$dfrom,$dto,$print,$otype)
{
	
	global $con;
	
	$string = "Select * from pos_sales where sales_invoice_number != ''";

	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
		
	}
	
	if(!empty($otype) && $otype != 'all')
	{
		$string = $string." and order_type_id = $otype";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								<?PHP
								IF($print == 0)
								{
								?>
									<th></th>
								<?php
								}
								?>
								<th>#</th>
									<th>SALES INVOICE NO</th>
									<th>DATE</th>
									<th>BRANCH</th>
									<th>CUSTOMER</th>
									<th>QUANTITY</th>
									<th>ORDER TYPE</th>
									<th>CURRENT STATUS</th>
								
									
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				$type = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_order_type where order_type_id = $row[order_type_id]"));
				?>
				<tr>
					<?PHP
								IF($print == 0)
								{
								?>
									<td>
									<a href = "" id = "det<?php echo $ctr;?>" class = "btn btn-success btn-xs btn-flat">DETAILS</a>
									<a href = "" id = "stat<?php echo $ctr;?>" class = "btn btn-warning btn-xs btn-flat">UPDATE STATUS</a>
									<a href = "" id = "print<?php echo $ctr;?>" class = "btn btn-primary btn-xs btn-flat">PRINT</a>
									
									
											<script>	
											$("#print<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$.post( 
															'php/pos.php',
															{
																printinvoice:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#click').html(data);		
															});
													
												}
											);
											
											$("#det<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$("#modal2").modal("show");
														$("#modalbody2").css("min-width","65%");
														
														//$("#streetform").html(loading);
														$("#modalui2").html(loading);
														
														$.post( 
															'php/pos.php',
															{
																invoicedetails:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#modalui2').html(data);		
															});
													
												}
											);
											
											$("#stat<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$("#modal2").modal("show");
														$("#modalbody2").css("min-width","75%");
														
														//$("#streetform").html(loading);
														$("#modalui2").html(loading);
														
														$.post( 
															'php/inventory.php',
															{
																updatestatusui:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#modalui2').html(data);		
															});
													
												}
											);
											
											</script>	
									</td>
								<?php
								}
								?>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['sales_invoice_number'];?></td>
					<td><?php echo $row['sales_datetime'];?></td>
					<td><?php echo $br['branch_description'];?></td>
					<td><?php echo $row['customer_fullname'];?></td>
					<td><?php echo $row['total_quantity'];?></td>
					<td><?php echo $type['order_type_description'];?></td>
					<td>
					<p id = "statusui<?php echo $ctr;?>">
					<?php
						if($row['order_status_id'] == 0)
							echo "PENDING";
						else
							echo $row['order_status'];
					?>
					</p>
					</td>
					
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
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function total_non_cash_collection($branch,$dfrom,$dto,$print,$from)
{
	global $con;
	
	$string = "Select SUM(total_cash_amount) as total from view_non_cash_collected_summary_report where branch_description != ''";
	
	if($from != "all")
	{
		if($from == 1)
		{
			$string = $string." and collection_for = 'CURRENT'";
		}
		else
		{
			$string = $string." and collection_for = 'PREVIOUS'";
		}
	}
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(collection_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(collection_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
			<?php
			$ctr = 1;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				
				$amount = $row['total'];
				$total = $total + $amount;
				?>
				<tr>
					<th style = "text-align:right;">TOTAL NON CASH COLLECTION(CURRENT+PREVIOUS):</th>
					<th style = "text-align:right;"><?php echo number_format($amount,2);?></th>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
	</table>
	
	<?php
		$print = 1;
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function total_non_cash($branch,$dfrom,$dto)
{
	global $con;
	
	$string = "Select SUM(total_cash_amount) as total from view_non_cash_collected_summary_report where branch_description != ''";
	
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(collection_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(collection_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	
	return $row['total'];
}

function non_cash($branch,$dfrom,$dto,$print,$from)
{
	global $con;
	
	$string = "Select * from view_non_cash_collected_summary_report where branch_description != ''";
	
	if($from != "all")
	{
		if($from == 1)
		{
			$string = $string." and collection_for = 'CURRENT'";
		}
		else
		{
			$string = $string." and collection_for = 'PREVIOUS'";
		}
	}
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(collection_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(collection_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								
								<th>#</th>
									<th>SETTLEMENT TYPE</th>
									<th STYLE = "text-align:right;">AMOUNT</th>
									
								</thead>
			<?php
			$ctr = 1;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				
				$amount = $row['total_cash_amount'];
				$total = $total + $amount;
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['settlement_description'];?></td>
					<td style = "text-align:right;?>"><?php echo number_format($amount,2);?></td>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
	</table>
	
	<table class = "table table-bordered table-hover table-xs">
		<tr>
			<th style = "text-align:right;">GRAND TOTAL:</th>
			<th style = "text-align:right;"><?php echo number_format($total,2);?></th>
		</tr>
	</table>
	<?php
		$print = 1;
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function order_status_list($id,$print)
{
	global $con;
	
	$string = "Select * from pos_order_type_status where order_type_id = $id and isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>DESCRIPTION</th>
									<th>SMS TEMPLATE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$sms = mysqli_fetch_assoc(mysqli_query($con,"Select * from sms_lup_message_template where message_template_id = $row[sms_template_id]"));
					//$dep = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_department where department_id = $row[department_id]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php 
							if($print == 1)
							{
								echo $row['status_description'];
							}
							else
							{
								?>
								<input type = "text" class = "form-control" name = "stdes<?php echo $ctr;?>" id = "stdes<?php echo $ctr;?>" value = "<?php echo $row['status_description'];?>">
								<?php
							}
							?></td>
							<td><?php 
							if($print == 1)
							{
								echo $sms['message_template_description'];
							}
							else
							{
								?>
								<select  name = "stsms<?php echo $ctr;?>" id = "stsms<?php echo $ctr;?>" class="form-control" data-validation="required"
																	data-validation-error-msg="Select Branch">
																	
																	
																	<option value = "<?php echo $sms['message_template_id'];?>" hidden "Selected"><?php echo $sms['message_template_description'];?></option>
																
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
								<?php
							}
							?></td>
							
							<td>
								
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">UPDATE</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
											
											$.post( 
												'php/main.php',
												{
													sstedit:<?php echo $row['pos_order_type_status_id'];?>,
													sstdes:$("#stdes<?php echo $ctr;?>").val(),
													sstsms:$("#stsms<?php echo $ctr;?>").val()
													
												},
												function(data) {
													$('#click').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#order_status_list').html(loading);
											$.post( 
												'php/main.php',
												{
													sstdel:<?php echo $row['pos_order_type_status_id'];?>
												},
												function(data) {
													$('#order_status_list').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function inventory_transaction($iproduct,$iunit,$isupplier,$itransaction,$iquantity,$iremarks,$icost,$branch,$expire,$delivery,$posdetail_id,$markup,$del)
{
		//echo $posdetail_id."aaaa";
			global $con;
			$tran = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_transaction_type where transaction_type_id = $itransaction"));
			
			if($tran['inventory'] == 'OUT')
			{
				$iquantity = $iquantity * -1;
			}
			if(empty($iremarks))
			{
				$iremarks = $tran['transaction_type_description'];
			}
			
			//$pro = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_product where product_id = $iproduct"));
			
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
				$found = mysqli_num_rows(mysqli_query($con, "Select * from inv_transaction where transaction_no = '$result'"));
				if($found == 0)
					break;
				else
					$i = 0;
			}
			$user = get_user_id($_SESSION['c_craft']);
			
			$save = mysqli_query($con,"insert into inv_transaction set
			transaction_no = '$result',
			location_id = $branch,
			delivery_id = $del,
			transaction_date = NOW(),
			expiration_date = '$expire',
			transaction_type_id = $itransaction,
			item_id = $iproduct,
			unit_id = $iunit,
			unit_cost = $icost,
			markup = $markup,
			quantity = $iquantity,
			remarks = '$iremarks',
			created_by = '$user',
			reference_id1 = $posdetail_id,
			created_by_datetime = NOW(),
			created_modified = NOW()");
			
					$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from inv_transaction where transaction_no = '$result'"));
			
					if(empty($row))
					{
						$total_enrolled = 1;
					}
					else
					{
						$total_enrolled = $row['transaction_id'];
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
					
				$tranno = $count.$row['transaction_id'];
				
				mysqli_query($con,"Update inv_transaction set transaction_no = '$tranno' where transaction_id = $row[transaction_id]");
				
				
		if($save)
			return 0;
		else
			return 1;
}

function total_prev($dfrom,$dto,$branch)
{
	global $con;
	
	$string = "Select SUM(ledger_sales_income.amount) as total from ledger_sales_income, pos_sales where ledger_sales_income.isdeleted = 0
	and pos_sales.isdeleted = 0 and pos_sales.sales_invoice_number != ''";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and ledger_sales_income.branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(ledger_sales_income.sales_income_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d'))";
	}
	//echo $string;
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	
	$string2 = "Select SUM(expense_amount) as total from order_expense where isdeleted = 0 and
	(STR_TO_DATE(expense_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d'))";
	
	$erow =  mysqli_fetch_assoc(mysqli_query($con,$string2));
	
	return $row['total']-$erow['total'];
}

function total_current($dfrom,$dto,$branch)
{
	global $con;
	
	$string = "Select SUM(total_cash_amount) as total from view_cash_collected_summary_report where collection_for = 'CURRENT'";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(collection_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(collection_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	
	return $row['total'];
}

function total_sales($dfrom,$dto,$branch)
{
	global $con;
	
	$string = "Select SUM(item_price*total_quantity) as total from view_consolidated_sales_detail_report where branch_description != ''";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(sales_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(sales_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	return $row['total'];
}
function expense_summary($dfrom,$dto,$group,$branch)
{
	//echo $group." aaaa";
	global $con;
	$string = "Select DISTINCT(expense_description) as des, is_other_deposit from order_expense where isdeleted = 0";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if(!empty($dfrom))
	{
		$string = $string." and (STR_TO_DATE(expense_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(expense_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$string = $string." and customer_type_group_id = $group";
			$part = " and customer_type_group_id = $group";
		}
	}
	
	//echo $part;
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>EXPENSE DESCRIPTION</th>
									<th>ENTRY TYPE</th>
									<th style = "text-align:right;">AMOUNT</th>
								</thead>
				<?php
					$ctr = 1;
					$total = 0;
					while($row = mysqli_fetch_assoc($query))
					{
						?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['des'];?></td>
							<td><?phP
								if($row['is_other_deposit'] == 1)
									echo "CASH OUT";
								else
									echo "EXPENSE";
							?></td>
							<td style = "text-align:right;">
							<?php 
								$exp = mysqli_fetch_assoc(mysqli_query($con,"Select sum(expense_amount) as total from order_expense where
								(STR_TO_DATE(expense_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(expense_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and expense_description ='$row[des]' and isdeleted = 0
								".$part."
								"));
								echo number_format($exp['total'],2);
								$total = $total + $exp['total'];
							?></td>
						
						</tr>
							
						<?php
						$ctr++;
					}
				?>
		</table>
		<table class = "table table-bordered table-hover table-sm">
								<tr>
									
									<th style = "text-align:right;">TOTAL:</th>
									<th style = "text-align:right;" id = "totalexpenseui"><?php echo number_format($total,2);?></th>
								</tr>
		</table>						
									
	<?php
}
function total_receivable_report($branch,$dfrom,$dto)
{
	global $con;
	
	$string = "Select SUM(transaction_amount) as total from ledger_receivable where isdeleted = 0";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	
	return $row['total'];
}

function total_cashout_report($branch,$dfrom,$dto)
{
	global $con;
	
	$string = "Select SUM(expense_amount) as total from order_expense where isdeleted = 0
	and is_other_deposit = 1";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(expense_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(expense_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	
	return $row['total'];
}

function total_expense_report($branch,$dfrom,$dto)
{
	global $con;
	
	$string = "Select SUM(expense_amount) as total from order_expense where isdeleted = 0
	and is_other_deposit = 0";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(expense_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(expense_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	
	return $row['total'];
}

function sales_summary($branch,$dfrom,$dto,$print)
{
	global $con;
	
	/*$string = "Select SUM(total_sales) as tsales, SUM(total_quantity) as tquan, branch_id from pos_sales where isdeleted = 0 and sales_invoice_number != ''";
	$string2= "Select * from pos_sales where isdeleted = 0 and sales_invoice_number != ''";
	$string3= "Select SUM(pos_sales_detail.item_cost*pos_sales_detail.quantity) as tcost from pos_sales_detail, pos_sales where pos_sales.isdeleted = 0 and pos_sales_detail.isdeleted = 0
	and pos_sales_detail.pos_sales_id = pos_sales.pos_sales_id and pos_sales.sales_invoice_number != ''";
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
		$string2 = $string2." and branch_id = $branch";
		$string3 = $string3." and pos_sales.branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
		$string2 = $string2." and (STR_TO_DATE(sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
		$string3 = $string3." and (STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string2;
	$query = mysqli_query($con,$string);
	$count = mysqli_num_rows(mysqli_query($con,$string2));
	$tcost = mysqli_fetch_assoc(mysqli_query($con,$string3));*/
	
	$string = "Select * from lup_branch where isdeleted = 0";
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	$query = mysqli_query($con,$string);
	
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
									<th>#</th>
									<th>BRANCH</th>
									<th style = "text-align:right;">TOTAL TRANSACTION</th>
									<th style = "text-align:right;">TOTAL QUANTITY</th>
									
									<th style = "text-align:right;">TOTAL AMOUNT</th>
								</thead>
			<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$string = "Select SUM(total_sales) as tsales, SUM(total_quantity) as tquan, branch_id from pos_sales where isdeleted = 0 and sales_invoice_number != '' and branch_id = $row[branch_id]";
				$string2= "Select * from pos_sales where isdeleted = 0 and sales_invoice_number != '' and branch_id = $row[branch_id]";
				$string3= "Select SUM(pos_sales_detail.item_cost*pos_sales_detail.quantity) as tcost from pos_sales_detail, pos_sales where pos_sales.isdeleted = 0 and pos_sales_detail.isdeleted = 0
				and pos_sales_detail.pos_sales_id = pos_sales.pos_sales_id and pos_sales.sales_invoice_number != ''";
				
				if($dfrom != "" && $dto != "")
				{
					$string = $string." and (STR_TO_DATE(sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
					$string2 = $string2." and (STR_TO_DATE(sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
						STR_TO_DATE(sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
					$string3 = $string3." and (STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
						STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
				}
				$rw = mysqli_fetch_assoc(mysqli_query($con,$string));
				$count = mysqli_num_rows(mysqli_query($con,$string2));
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['branch_description'];?></td>
					<td style = "text-align:right;"><?php echo number_format($count,2);?></td>
					<td style = "text-align:right;"><?php echo number_format($rw['tquan'],2);?></td>
					
					<td style = "text-align:right;"><?php echo number_format($rw['tsales'],2);?></td>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
	</table>
	<?php
		$print = 1;
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function sales_detail($branch,$dfrom,$dto,$class,$print)
{
	global $con;
	
	$string = "Select DISTINCT(pos_sales_detail.item_id) as item_id, pos_sales_detail.item_description,pos_lup_item.*,
	pos_sales.branch_id from pos_sales_detail,pos_sales,pos_lup_item where 
	pos_sales.sales_invoice_number != '' and pos_sales_detail.isdeleted = 0
	and pos_sales_detail.pos_sales_id = pos_sales.pos_sales_id
	and pos_sales.isdeleted = 0
	and pos_sales_detail.discount = 0
	and pos_sales_detail.item_id = pos_lup_item.item_id";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and pos_sales.branch_id = $branch";
	}
	if(!empty($class) && $class != 'all')
	{
		$string = $string." and pos_lup_item.classification_id = $class";
	}
	
	if(!empty($cat) && $cat != 'all')
	{
		$string = $string." and pos_lup_item.category_id = $cat";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								
								<th>#</th>
									<th>BRANCH</th>
									<th>ITEM DESCRIPTION</th>
									<th style = "text-align:right;">QUANTITY</th>
									<th style = "text-align:right;">TOTAL</th>
								</thead>
			<?php
			$ctr = 1;
			$total = 0;
			$gtotal = 0;
			$totalq = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$tstring = "Select SUM(pos_sales_detail.quantity) as q, SUM(pos_sales_detail.grand_total) as g from pos_sales_detail, pos_sales
				where pos_sales_detail.item_id = $row[item_id] 
				and pos_sales.pos_sales_id = pos_sales_detail.pos_sales_id
				and pos_sales.sales_invoice_number != ''
				and pos_sales.isdeleted = 0
				and pos_sales_detail.isdeleted = 0 ";
				if($dfrom != "" && $dto != "")
				{
					$tstring = $tstring." and (STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
						STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
				}
				$trow = mysqli_fetch_assoc(mysqli_query($con,$tstring));
				
				//$total = 0;
				//$total = $row['item_price']*$row['total_quantity'];
				$totalq = $totalq+$trow['q'];
				$gtotal = $gtotal + $trow['g'];
				$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $br['branch_description'];?></td>
					<td><?php echo $row['item_description'];?></td>
					<td style = "text-align:right;"><?php echo number_format($trow['q'],2);?></td>
					<td style = "text-align:right;"><?php echo number_format($trow['g'],2);?></td>
				</tr>
				<?php
				$ctr++;
			}
			$string = "Select DISTINCT(pos_sales_detail.item_description) as dis,
			pos_sales.branch_id from pos_sales_detail,pos_sales where 
			pos_sales.sales_invoice_number != '' and pos_sales_detail.isdeleted = 0
			and pos_sales_detail.pos_sales_id = pos_sales.pos_sales_id
			and pos_sales.isdeleted = 0
			and pos_sales_detail.discount != 0";
			
			if(!empty($branch) && $branch != 'all')
			{
				$string = $string." and pos_sales.branch_id = $branch";
			}
			
			if($dfrom != "" && $dto != "")
			{
				$string = $string." and (STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
			}
			//echo $string;
			$query = mysqli_query($con,$string);
			while($row = mysqli_fetch_assoc($query))
			{
				$tstring = "Select SUM(pos_sales_detail.quantity) as q, SUM(pos_sales_detail.grand_total) as g from pos_sales_detail, pos_sales
				where pos_sales_detail.item_description = '$row[dis]' 
				and pos_sales.pos_sales_id = pos_sales_detail.pos_sales_id
				and pos_sales.sales_invoice_number != ''
				and pos_sales.isdeleted = 0
				and pos_sales_detail.isdeleted = 0 ";
				
				if($dfrom != "" && $dto != "")
				{
					$tstring = $tstring." and (STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
						STR_TO_DATE(pos_sales.sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
				}
				$trow = mysqli_fetch_assoc(mysqli_query($con,$tstring));
				
				//$total = 0;
				//$total = $row['item_price']*$row['total_quantity'];
				$totalq = $totalq+$trow['q'];
				$gtotal = $gtotal + $trow['g'];
				$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $br['branch_description'];?></td>
					<td><?php echo $row['dis'];?></td>
					<td style = "text-align:right;"><?php echo number_format($trow['q'],2);?></td>
					<td style = "text-align:right;"><?php echo number_format($trow['g'],2);?></td>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
			<tr>
				<th style = "text-align:right;"></th>
				<th style = "text-align:right;"></th>
				<th style = "text-align:right;"></th>
				<th style = "text-align:right;"><?php echo number_format($totalq,2);?></th>
				<th style = "text-align:right;"><?php echo number_format($gtotal,2);?></th>
			</tr>
	</table>
	<?php
		$print = 1;
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function total_collection($branch,$dfrom,$dto,$print,$from)
{
	global $con;
	
	$string = "Select SUM(total_cash_amount) as total from view_cash_collected_summary_report where branch_description != ''";
	
	if($from != "all")
	{
		if($from == 1)
		{
			$string = $string." and collection_for = 'CURRENT'";
		}
		else
		{
			$string = $string." and collection_for = 'PREVIOUS'";
		}
	}
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(collection_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(collection_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
			<?php
			$ctr = 1;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				
				$amount = $row['total'];
				$total = $total + $amount;
				?>
				<tr>
					<th style = "text-align:right;">TOTAL CASH COLLECTION(CURRENT+PREVIOUS):</th>
					<th style = "text-align:right;"><?php echo number_format($amount,2);?></th>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
	</table>
	
	<?php
		$print = 1;
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function payment_detail($branch,$dfrom,$dto,$print,$from)
{
	global $con;
	
	$string = "Select * from view_cash_collected_summary_report where branch_description != ''";
	
	if($from != "all")
	{
		if($from == 1)
		{
			$string = $string." and collection_for = 'CURRENT'";
		}
		else
		{
			$string = $string." and collection_for = 'PREVIOUS'";
		}
	}
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(collection_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(collection_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								
								<th>#</th>
									<th>SETTLEMENT TYPE</th>
									<th STYLE = "text-align:right;">AMOUNT</th>
									
								</thead>
			<?php
			$ctr = 1;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				
				$amount = $row['total_cash_amount'];
				$total = $total + $amount;
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['settlement_description'];?></td>
					<td style = "text-align:right;?>"><?php echo number_format($amount,2);?></td>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
	</table>
	
	<table class = "table table-bordered table-hover table-xs">
		<tr>
			<th style = "text-align:right;">GRAND TOTAL:</th>
			<th style = "text-align:right;"><?php echo number_format($total,2);?></th>
		</tr>
	</table>
	<?php
		$print = 1;
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}
function settlement_summary($branch,$dfrom,$dto,$print,$from)
{
	global $con;
	
	$string = "Select * from view_payment_summary_report where branch_description != ''";
	
	if($from != "all")
	{
		if($from == 1)
		{
			$string = $string." and collection_for = 'CURRENT'";
		}
		else
		{
			$string = $string." and collection_for = 'PREVIOUS'";
		}
	}
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(payment_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(payment_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								
								<th>#</th>
									<th>SETTLEMENT TYPE</th>
									<th STYLE = "text-align:right;">AMOUNT</th>
									
								</thead>
			<?php
			$ctr = 1;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				
				$amount = $row['payment_amount'];
				$total = $total + $amount;
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['settlement_description'];?></td>
					<td style = "text-align:right;?>"><?php echo number_format($amount,2);?></td>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
	</table>
	
	<table class = "table table-bordered table-hover table-xs">
		<tr>
			<th style = "text-align:right;">GRAND TOTAL:</th>
			<th style = "text-align:right;"><?php echo number_format($total,2);?></th>
		</tr>
	</table>
	<?php
		$print = 1;
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}
function pmonitor($branch,$dfrom,$dto,$print,$from)
{
	global $con;
	
	$string = "Select * from view_payment_detail_report where branch_description != ''";
	
	if($from != "all")
	{
		if($from == 1)
		{
			$string = $string." and collection_for = 'CURRENT'";
		}
		else
		{
			$string = $string." and collection_for = 'PREVIOUS'";
		}
	}
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(payment_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(payment_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								
									<th>#</th>
									<th>COLLECTION FOR</th>
									<th>SALES INVOICE NO</th>
									<th>DATE</th>
									<th>BRANCH</th>
									<th>CUSTOMER NO</th>
									<th>FULL NAME</th>
									<th>SETTLEMENT TYPE</th>
									<th>AMOUNT</th>
									<?php
										if($print == 0)
										{
										?>
											<th></th>
										<?php
										}
										?>
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				
				$amount = $row['payment_amount'];
				$total = $total + $amount;
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['collection_for'];?></td>
					<td><?php echo $row['payment_for'];?></td>
					<td><?php echo $row['payment_date'];?></td>
					<td><?php echo $row['branch_description'];?></td>
					<td><?php echo $row['customer_no'];?></td>
					<td><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></td>
					<td>
					<?php 
					if($print == 1)
					{
						echo $row['settlement_description'];
					}
					else
					{
						$string = "Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where
						lup_settlement_type.settlement_type_id  = settings_settlement_mapping.settlement_type_id ";
						?>
						<div class="form-group">
														<Select class = "form-control" id = "pmsettle<?php echo $ctr;?>" data-validation="required"
														data-validation-error-msg="Select Settlement Type">
														<?php
														$sel = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[settlement_type_id]"));
														?>
															<option value = "<?php echo $sel['settlement_type_id'];?>" hidden "Selected"><?php echo $sel['settlement_description'];?></option>
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
						<?php
					}
					?></td>
					
					<td style = "text-align:right;?>"><?php 
					if($print == 1)
					{
					echo number_format($amount,2);
					}
					else
					{
						?>
						<input type = "hidden" value = "<?php echo $row['sales_income_id'];?>" id = "pmincome_id">
						<input id = "pmamount<?php echo $ctr;?>"type = "text" value = "<?php echo $amount;?>" class = "form-control" style = "width:80px;">
						<?php
					}
					?></td>
						<?php
								if($print == 0)
								{
								?>
									<td>
										<button class = "btn btn-success btn-flat btn-xs" id = "update<?php echo $ctr;?>">UPDATE</button>
										<button class = "btn btn-danger btn-flat btn-xs" id = "del<?php echo $ctr;?>">DELETE</button>
									</td>
								<?php
								}
								?>
						<script>
							$("#del<?php echo $ctr;?>").click(
														function(e)
														{
															var r = confirm("Confirm Delete");
															
															if(r == true)
															{
																$.post( 
																		'php/finance.php',
																		{
																			dpmid:<?php echo $row['sales_income_id'];?>,
																			dpmsettle:$("#pmsettle<?php echo $ctr;?>").val(),
																			dpmamount:$("#pmamount<?php echo $ctr;?>").val(),
																			dpmbranch:'<?php echo $branch;?>',
																			dpmdfrom:'<?php echo $dfrom;?>',
																			dpmdto:'<?php echo $dto;?>',
																			dpmfrom:'<?php echo $from;?>'

																		},
																		function(data) {
																			$('#pmonitorui').html(data);	
																		});
															}
														}
													);
													
							$("#update<?php echo $ctr;?>").click(
														function(e)
														{
															var r = confirm("Confirm Update");
															
															if(r == true)
															{
																$.post( 
																		'php/finance.php',
																		{
																			spmid:<?php echo $row['sales_income_id'];?>,
																			spmsettle:$("#pmsettle<?php echo $ctr;?>").val(),
																			spmamount:$("#pmamount<?php echo $ctr;?>").val(),
																			spmbranch:'<?php echo $branch;?>',
																			spmdfrom:'<?php echo $dfrom;?>',
																			spmdto:'<?php echo $dto;?>',
																			spmfrom:'<?php echo $from;?>'

																		},
																		function(data) {
																			$('#pmonitorui').html(data);	
																		});
															}
														}
													);
						</script>						
				</tr>
				<?php
				$ctr++;
			}
			$string = "Select * from view_collection_detail_report where branch_description != ''";
	
			if($from != "all")
			{
				if($from == 1)
				{
					$string = $string." and collection_for = 'CURRENT'";
				}
				else
				{
					$string = $string." and collection_for = 'PREVIOUS'";
				}
			}
			
			if(!empty($branch) && $branch != 'all')
			{
				$string = $string." and branch_id = $branch";
			}
			
			if($dfrom != "" && $dto != "")
			{
				$string = $string." and (STR_TO_DATE(transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
			}
			//echo $string;
			$query = mysqli_query($con,$string);
			while($row = mysqli_fetch_assoc($query))
			{
				
				$amount = $row['collection_amount'];
				$total = $total + $amount;
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['collection_for'];?></td>
					<td><?php echo $row['payment_for'];?></td>
					<td><?php echo $row['transaction_date'];?></td>
					<td><?php echo $row['branch_description'];?></td>
					<td><?php echo $row['customer_no'];?></td>
					<td><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></td>
					<td>
					<?php 
					if($print == 1)
					{
						echo $row['settlement_description'];
					}
					else
					{
						$string = "Select lup_settlement_type.* from lup_settlement_type, settings_settlement_mapping where
						lup_settlement_type.settlement_type_id  = settings_settlement_mapping.settlement_type_id ";
						?>
						<div class="form-group">
														<Select class = "form-control" id = "pmsettle<?php echo $ctr;?>" data-validation="required"
														data-validation-error-msg="Select Settlement Type">
														<?php
														$sel = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[settlement_type_id]"));
														?>
															<option value = "<?php echo $sel['settlement_type_id'];?>" hidden "Selected"><?php echo $sel['settlement_description'];?></option>
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
						<?php
					}
					?></td>
					
					<td style = "text-align:right;?>"><?php 
					if($print == 1)
					{
					echo number_format($amount,2);
					}
					else
					{
						?>
						<input type = "hidden" value = "<?php echo $row['sales_income_id'];?>" id = "pmincome_id">
						<input id = "pmamount<?php echo $ctr;?>"type = "text" value = "<?php echo $amount;?>" class = "form-control" style = "width:80px;">
						<?php
					}
					?></td>
						<?php
								if($print == 0)
								{
								?>
									<td>
										<button class = "btn btn-success btn-flat btn-xs" id = "update<?php echo $ctr;?>">UPDATE</button>
										<button class = "btn btn-danger btn-flat btn-xs" id = "del<?php echo $ctr;?>">DELETE</button>
									</td>
								<?php
								}
								?>
						<script>
							$("#del<?php echo $ctr;?>").click(
														function(e)
														{
															var r = confirm("Confirm Delete");
															
															if(r == true)
															{
																$.post( 
																		'php/finance.php',
																		{
																			drpmid:<?php echo $row['receivable_no'];?>,
																			drpmsettle:$("#pmsettle<?php echo $ctr;?>").val(),
																			drpmamount:$("#pmamount<?php echo $ctr;?>").val(),
																			drpmbranch:'<?php echo $branch;?>',
																			drpmdfrom:'<?php echo $dfrom;?>',
																			drpmdto:'<?php echo $dto;?>',
																			drpmfrom:'<?php echo $from;?>'

																		},
																		function(data) {
																			$('#pmonitorui').html(data);	
																		});
															}
														}
													);
													
							$("#update<?php echo $ctr;?>").click(
														function(e)
														{
															var r = confirm("Confirm Update");
															
															if(r == true)
															{
																$.post( 
																		'php/finance.php',
																		{
																			srpmid:<?php echo $row['receivable_no'];?>,
																			srpmsettle:$("#pmsettle<?php echo $ctr;?>").val(),
																			srpmamount:$("#pmamount<?php echo $ctr;?>").val(),
																			srpmbranch:'<?php echo $branch;?>',
																			srpmdfrom:'<?php echo $dfrom;?>',
																			srpmdto:'<?php echo $dto;?>',
																			srpmfrom:'<?php echo $from;?>'

																		},
																		function(data) {
																			$('#pmonitorui').html(data);	
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
	
	<table class = "table table-bordered table-hover table-xs">
		<tr>
			<th style = "text-align:right;">TOTAL PAYMENT:</th>
			<th style = "text-align:right;"><?php echo number_format($total,2);?></th>
		</tr>
	</table>
	<?php
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function order_type_details($pos)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select pos_sales.remarks, pos_lup_order_type.* from pos_sales, pos_lup_order_type
	where pos_sales.order_type_id = pos_lup_order_type.order_type_id and pos_sales.pos_sales_id = $pos"));
	
	?>
			<table class = "table table-bordered table-sm">
								<tr>
									<td>ORDER TYPE: <b><?php echo $row['order_type_description'];?></b></td>
								
									<td>REMARKS: <b><?php
									//$ctype = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type_id]"));
									echo $row['remarks'];
									?></b></td>
									
								</tr>	
			</table>
	<?php
}
function cline_ledger($cus,$branch,$dfrom,$dto,$print)
{
	global $con;
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$string = "Select * from credit_line_transaction where isdeleted = 0";
	if(!empty($cus))
	{
		$string = $string." and customer_id = $cus";
	}
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									
									<th>#</th>
									<th>TRANSACTION NO</th>
									<th>TRANSACTION DATE</th>
									<th>TRANSACTION TYPE</th>
									<th style = "text-align:right;">TRANSACTION AMOUNT</th>									
								</thead>
				<?php
				$ctr = 1;
				$total = 0;
				while($row = mysqli_fetch_assoc($query))
				{
					//$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $branch"));
					?>
						<tr>
						
									
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['transaction_no'];?></td>
							<td><?php echo $row['transaction_date'];?></td>
							<td><?php echo $row['transaction_remarks'];?></td>
							<td style = "text-align:right;"><?php echo $row['transaction_amount'];?></td>
							
						</tr>
					<?php
						$total = $total + $row['transaction_amount'];
					$ctr++;
				}
				?>
		</table>
		<table class = "table table-bordered table-hover table-sm">
			<tr>
				<th style = "text-align:right;">TOTAL:</th>
				<th style = "text-align:right;"><?php echo number_format($total,2);?></th>

			</tr>
		</TABLE>
		<?php
		$print = 0;
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function total_expense($dfrom,$dto,$group)
{
	global $con;
	$string = "Select sum(expense_amount) as total from order_expense where isdeleted = 0
	and is_other_deposit = 0";
	
	if(!empty($dfrom))
	{
		$string = $string." and (STR_TO_DATE(expense_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(expense_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$string = $string." and customer_type_group_id = $group";
			//$part = " and customer_type_group_id = $group";
		}
	}
	
	$row = mysqli_fetch_assoc(mysqli_query($con,$string));
	
	return $row['total'];
	
}

function expense($dfrom,$dto,$type,$print,$group)
{
	global $con;
	$string = "Select * from order_expense where isdeleted = 0";
	
	$type;
	if(!empty($dfrom))
	{
		$string = $string." and (STR_TO_DATE(expense_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(expense_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	if(!empty($type) || $type == "0")
	{
		$string = $string." and is_other_deposit = $type";
	}
	
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$string = $string." and customer_type_group_id = $group";
		}
	}
	
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-xs" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>DESCRIPTION</th>
									<th>BRANCH</th>
									<th>ENTRY TYPE</th>
									
									<th>DATE</th>
									<th style = "text-align:right;">AMOUNT</th>
									<?php
									if($print == 0)
									{
									?>
									<th></th>
									<?php
									}
									?>
								</thead>
				<?php
					$ctr = 1;
					$total = 0;
					while($row = mysqli_fetch_assoc($query))
					{
						$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
						?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['expense_description'];?></td>
							<td><?php echo $br['branch_description'];?></td>
							<td>
								<?php 
								if($row['is_other_deposit'] == 1)
									echo "CASH OUT";
								else
									echo "EXPENSE";
								?>
							</td>
						
							<td><?php echo $row['expense_date'];?></td>
							<td style = "text-align:right;">
							<?php 
								$total = $total + $row['expense_amount'];
								if($print != 0)
								{
									echo number_format($row['expense_amount'],2);
								}
								else
								{
									?>
									<div class = "form-group">
										<input type="text" class="form-control" id = "examount<?php echo $ctr;?>"
										value = "<?php echo number_format($row['expense_amount'],2);?>"
										style = "text-align:right;">
									</div>	
									<?php
								}
							?></td>
							<?php
									if($print == 0)
									{
									?>
									<td><button class = "btn btn-success btn-flat btn-xs" id = "update<?php echo $ctr;?>">UPDATE</button>
									<button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">DELETE</button>
									<button class = "btn btn-warning btn-flat btn-xs" id = "proof<?php echo $ctr;?>">PROOF</button>
									</td>
									<?php
									}
									?>
						</tr>
							<script>
								$("#proof<?php echo $ctr;?>").click(
														function(e)
														{

															$("#modal").modal("show");
															$("#modalbody").css("min-width","60%");
															$('#modalui').html(loading);	
															$.post( 
																		'php/finance.php',
																		{
																			viewproof:<?php echo $row['expense_id'];?>

																		},
																		function(data) {
																			$('#modalui').html(data);	
																			
																		});
														}
													);
													
								$("#update<?php echo $ctr;?>").click(
									function()
									{
										var examount = $("#examount<?php echo $ctr;?>").val();
										var r = confirm("Confirm Update");
										
										if(r == true)
										{
											if(examount > 0)
											{
														$.post( 
																		'php/finance.php',
																		{
																			examountsave:examount,
																			examountid:<?php echo $row['expense_id'];?>,
																			examountdfrom:'<?php echo $dfrom;?>',
																			examountdto:'<?php echo $dto;?>',
																			examountgroup:'<?php echo $group;?>'
																			
																		},
																		function(data) {
																			$('#totalexpenseui').html(data);	
																			
																		});
											}
										}
									}
								);
								
								$("#delete<?php echo $ctr;?>").click(
									function()
									{
										
										var r = confirm("Confirm Update");
										
										if(r == true)
										{

														$.post( 
																		'php/finance.php',
																		{
																			deleteexpense:"<?php echo $row['expense_id'];?>",
																			deleteexpensedfrom:'<?php echo $dfrom;?>',
																			deleteexpensedto:'<?php echo $dto;?>',
																			deleteexpensegroup:'<?php echo $group;?>',
																			deleteexpensetype:'<?php echo $type;?>'
																		},
																		function(data) {
																			$('#expenseui2').html(data);	
																			
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
		<table class = "table table-bordered table-hover table-sm">
								<tr>
									
									<th style = "text-align:right;">GRAND TOTAL:</th>
									<th style = "text-align:right;" id = "totalexpenseui"><?php echo number_format($total,2);?></th>
								</tr>
		</table>						
									
			<?php
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#pmtable2').DataTable({
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
function receivable($branch,$dfrom,$dto,$print)
{
	global $con;
	
	$str = "";
	
	if(!empty($branch) && $branch != 'all')
	{
		$str = $str." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$str = $str." and (transaction_date >= '$dfrom' and transaction_date <= '$dto')";
	}

		$string = "Select SUM(transaction_amount) as total, ledger_receivable.* from ledger_receivable where
		isdeleted = 0 and transaction_apply_to != '' ".$str." 
		GROUP BY receivable_id
		HAVING total > 0";
		
		//echo $string;
		$query = mysqli_query($con,$string);
	
	?>
	<table class = "table table-bordered table-hover table-sm" id = "rectable">
								<thead>
									
									<th>#</th>
									<th>CUSTOMER NO</th>
									<th>FULL NAME</th>
									<th>SALES INVOICE NO</th>
									<th>SETTLMENT TYPE</th>
									<th>DUE DATE</th>
									<th style = "text-align:right;">AMOUNT</th>
								</thead>
			<?PHP
			$ctr = 1;
			$source = "";
			$dis = 1;
			$tot = 0;
			while($row2 = mysqli_fetch_assoc($query))
			{
				$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where transaction_apply_to = $row2[transaction_apply_to]"));
				$set = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[settlement_type_id]"));
				$sid = $row['remarks_sales'];
				$cno = mysqli_fetch_assoc(mysqli_query($con,"Select customer_no from customer_profile where customer_id = $row2[customer_id]"));
				$full = get_customer_fullname($row2['customer_id']);
				$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable
						where transaction_apply_to = $row[transaction_apply_to] and isdeleted = 0"));
				if($total['total'] > 0)
				{		
					$tot = $tot + $total['total'];
				?>
				<tr>
					
					<td><?php echo $ctr?></td>
					<td><?php echo $cno['customer_no'];?></td>
					<td><?php echo $full;?></td>
					<td><?php echo $row2['transaction_apply_to'];?></td>
					<td><?php echo $set['settlement_description'];?></td>
					<td><?php echo $row2['due_date'];?></td>
					<td style = "text-align:right;"><?php
						
						echo number_format($total['total'],2);
					?></td>
					
				</tr>
					<?php
					
				$dis = 0;
				$ctr++;
				}
			}
			?>
	<table class = "table table-bordered table-hover table-sm">
			<tr>

					<th style = "text-align:right;">TOTAL: </th>
					<th style = "text-align:right;"><?php echo number_format($tot,2);?></th>
			</tr>
	</table>
	<?php
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#rectable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
	
}

function collection_report($branch,$dfrom,$dto,$print,$from)
{
	global $con;
	
	$string = "Select ledger_sales_income.*, pos_sales.sales_invoice_number  from ledger_sales_income, pos_sales where ledger_sales_income.isdeleted = 0
	and ledger_sales_income.pos_sales_id = pos_sales.pos_sales_id
	and pos_sales.sales_invoice_number != ''";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and ledger_sales_income.branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(ledger_sales_income.sales_income_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(ledger_sales_income.sales_income_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								
								<th>#</th>
									
									<th>SALES INVOICE NO</th>
									<th>DATE</th>
									<th>BRANCH</th>
									<th>SETTLEMENT TYPE</th>
									<th>AMOUNT</th>
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$branch = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				$set = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[settlement_type_id]"));
				
				
				$total = $total + $row['amount'];
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					
					<td><?php echo $row['sales_invoice_number'];?></td>
					<td><?php echo $row['sales_income_date'];?></td>
					<td><?php echo $branch['branch_description'];?></td>
					<td><?php echo $set['settlement_description'];?></td>
					<td style = "text-align:right;?>"><?php echo number_format($row['amount'],2);?></td>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
	</table>
	
	<table class = "table table-bordered table-hover table-xs">
		<tr>
			<th style = "text-align:right;">TOTAL AMOUNT:</th>
			<th style = "text-align:right;"><?php echo number_format($total,2);?></th>
		</tr>
	</table>
	<?php
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function smonitor($cus,$branch,$dfrom,$dto,$print)
{
	
	global $con;
	
	$string = "Select * from  pos_sales where sales_invoice_number != '' and isdeleted = 0";
	
	if(!empty($branch) && $branch != 'all')
	{
		$string = $string." and branch_id = $branch";
	}
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(sales_datetime,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(sales_datetime,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-xs" id = "banktable">
								<thead>
								<?PHP
								IF($print == 0)
								{
								?>
									<th></th>
								<?php
								}
								?>
								<th>#</th>
									<th>SALES INVOICE NO</th>
									<th>DATE</th>
									<th>BRANCH</th>
									
									<th>CREATED BY</th>
									<th>TOTAL QTY</th>
									<th>TOTAL</th>
									
								</thead>
			<?php
			$ctr = 1;
			$tq = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$tq = $tq+ $row['total_quantity'];
				$total = $total + $row['total_sales'];
				$agent = get_agent($row['created_by_fullname']);
				?>
				<tr>
					<?PHP
								IF($print == 0)
								{
								?>
									<td>
									<a href = "" id = "det<?php echo $ctr;?>" class = "btn btn-success btn-xs btn-flat">DETAILS</a>
									<a href = "" id = "print<?php echo $ctr;?>" class = "btn btn-primary btn-xs btn-flat">PRINT</a>
									<a href = "" id = "del<?php echo $ctr;?>" class = "btn btn-danger btn-xs btn-flat">VOID</a>
									
											<script>
											$("#del<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													var r = confirm("Confirm Delete");
													
													if(r == true)
													{
													
														$.post( 
															'php/pos.php',
															{
																deleteinvoice:<?php echo $row['pos_sales_id'];?>,
																deleteinvoicecus:'<?php echo $cus;?>',
																deleteinvoicebranch:'<?php echo $branch;?>',
																deleteinvoicedfrom:'<?php echo $dfrom;?>',
																deleteinvoicedto:'<?php echo $dto;?>'
																
															},
															function(data) {
																$('#smonitorui').html(data);		
															});
													}
													
												}
											);
											
											$("#print<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$.post( 
															'php/pos.php',
															{
																printinvoice:<?php echo $row['pos_sales_id'];?>
																
															},
															function(data) {
																$('#click').html(data);		
															});
													
												}
											);
											
											$("#det<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
														$("#modal2").modal("show");
														$("#modalbody2").css("min-width","65%");
														
														//$("#streetform").html(loading);
														$("#modalui2").html(loading);
														
														$.post( 
															'php/pos.php',
															{
																invoicedetails:<?php echo $row['pos_sales_id'];?>
															},
															function(data) {
																$('#modalui2').html(data);		
															});
													
												}
											);
											</script>	
									</td>
								<?php
								}
								$br = mysqli_fetch_assoc(mysqli_query($con,"select * from lup_branch where branch_id = $row[branch_id]"));
								?>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['sales_invoice_number'];?></td>
					<td><?php echo $row['sales_datetime'];?></td>
					<td><?php echo $br['branch_description'];?></td>
					<td><?php echo $agent;?></td>
					<td><?php echo $row['total_quantity'];?></td>
					<td><?php echo $row['total_sales'];?></td>
				</tr>
				<?php
				$ctr++;
			}
			
			?>
	</table>
	
	<table class = "table table-bordered table-hover table-xs">
		<tr>
			<th>TOTAL TRANSACTIONS: <?php echo number_format($ctr-1,2);?> </th>
			<th>TOTAL QUANTITY: <?php echo number_format($tq,2);?></th>
			<th>TOTAL AMOUNT: <?php echo number_format($total,2);?></th>
		</tr>
	</table>
	<?php
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}
function rebate_ledger($invoice,$cus,$branch,$dfrom,$dto,$print)
{
	global $con;
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$string = "Select * from ledger_rebate where isdeleted = 0";
	if(!empty($cus))
	{
		$string = $string." and customer_id = $cus";
	}
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<?php
									if($print == 0)
									{
									?>
										<th></th>
									<?php
									}
									?>
									<th>#</th>
									<th>INVOICE NUMBER</th>
									<th>TOTAL SALES</th>
									<th>BRANCH</th>
									<th>SALES W/ REBATE</th>
									<th>RATE SALES</th>
									<th>RATE POINTS</th>
									<th>REBATE</th>
								
								</thead>
				<?php
				$ctr = 1;
				$total = 0;
				while($row = mysqli_fetch_assoc($query))
				{
					$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $branch"));
					?>
						<tr>
							<?php
									if($print == 0)
									{
									?>
										<td>
										<?php
										if($row['sales_invoice_number'] == "")
										{
										?>
											<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">x</a>
											<script>
											$("#bdel<?php echo $ctr;?>").click(
												function(e)
												{
													e.preventDefault();
													
													var r = confirm("Confirm Delete!");
													
													if(r == true)
													{
														$('#cusledger').html(loading);
														$.post( 
															'php/finance.php',
															{
																rebdel:<?php echo $row['rebate_id'];?>
															},
															function(data) {
																$('#cusledger').html(data);		
															});
													}
												}
											);
											</script>	
										<?php
										}
										?>
										</td>
										
									<?php
									}
									?>
							<td><?php echo $ctr;?></td>
							<td><?php 
								if(!empty($row['sales_invoice_number']))
									echo $row['sales_invoice_number'];
								else
									echo "-manually added-";
									
									?></td>
							<td><?php echo $row['total_sales'];?></td>
							<td><?php echo $br['branch_description'];?></td>
							<td><?php echo $row['sales_with_rebate'];?></td>
							<td><?php echo $row['rate_sales'];?></td>
							<td><?php echo $row['rate_points'];?></td>
							<td style = "text-align:right;"><?php echo $row['rebate'];?></td>
						</tr>
					<?php
						$total = $total + $row['rebate'];
					$ctr++;
				}
				?>
		</table>
		<table class = "table table-bordered table-hover table-sm">
			<tr>
				<th style = "text-align:right;">TOTAL:</th>
				<th style = "text-align:right;"><?php echo number_format($total,2);?></th>

			</tr>
		</TABLE>
		<?php
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function invoice($print)
{
	global $con;
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	$string = "Select * from lup_invoice_number where isdeleted = 0 and branch_id = $branch order by invoice_number_id desc";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>INVOICE NUMBER</th>
									<th>BRANCH</th>
									<th>USED</th>
									<th style = "display:none;"></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $branch"));
					//$card = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_card_type where card_type_id = $row[card_type_id]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['invoice_number'];?></td>
							<td><?php echo $br['branch_description'];?></td>
							<td><?php
								if($row['pos_sales_id'] != 0)
									echo "YES";
								else
									echo "NO";
							?></td>
							<td style = "display:none;">
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
							</td>
							
							<script>

								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#invlist').html(loading);
											$.post( 
												'php/main.php',
												{
													invoicedel:<?php echo $row['invoice_number_id'];?>
												},
												function(data) {
													$('#invlist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function insert_cline($cus,$tran_type,$apply_to,$amount,$source_id, $source_no,$source_remarks,$tran_remarks,$user)
{
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
								
	global $con;
	$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
	
				
	mysqli_query($con,"insert into credit_line_transaction set 
	result = '$result',
	transaction_date = NOW(),
	branch_id = $branch,
	customer_id = $cus,
	credit_line_transaction_type_id = $tran_type,
	transaction_apply_to = '$apply_to',
	transaction_amount = '$amount',
	source_id = $source_id,
	source_no = '$source_no',
	source_remarks = '$source_remarks',
	transaction_remarks = '$tran_remarks',
	created_by_fullname = '$user',
	isdeleted = 0");
	
				$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from  credit_line_transaction where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['credit_line_transaction_id'];
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
									
			$idno = $row['credit_line_transaction_id'].$count;
			mysqli_query($con,"Update credit_line_transaction set transaction_no = '$idno',result = '' where credit_line_transaction_id = $row[credit_line_transaction_id]");
}

function creditlmit($reg,$print)
{
	global $con;
	
	$string = "Select * from credit_line_allocation where isdeleted = 0 and registration_id = $reg";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th></th>
									<th>#</th>
									<th>CREDIT LINE CODE</th>
									<th>CREDIT LINE DESCRIPTION</th>
									<th>AMOUNT</th>
									<th>ALLOCATION DATE</th>
									<th>VALIDITY</th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$limit = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_credit_line_limit where credit_line_limit_id = $row[credit_line_limit_id]"));
					?>
						<tr>
							<td>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $limit['credit_line_limit_code'];?></td>
							<td><?php echo $limit['credit_line_limit_description'];?></td>
							<td><?php echo $row['credit_line_limit_amount'];?></td>
							<td><?php echo $row['allocation_date'];?></td>
							<td>
								<?php
								if($row['valid_from'] == '0000-00-00')
								{
									echo "LIFE TIME";
								}
								else
								{
									echo $row['valid_from']." - ".$row['valid_to'];
								}
								?>
							</td>
							
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													catedit:<?php echo $row['category_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#cprofilelist').html(loading);
											$.post( 
												'php/main.php',
												{
													catdel:<?php echo $row['category_id'];?>
												},
												function(data) {
													$('#categorylist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function ledger_summary($invoice,$cus,$branch,$dfrom,$dto,$print,$withbal)
{
	global $con;
	if($withbal == 0)
	{
		$string = "Select DISTINCT(transaction_apply_to) from ledger_receivable where isdeleted = 0 and transaction_apply_to != ''";
		
		if(!empty($cus))
		{
			$string = $string." and customer_id = $cus";
		}
		if(!empty($invoice))
		{
			$string = $string." and transaction_apply_to = $invoice";
		}
		
		$string = $string." order by transaction_apply_to desc, transaction_amount desc";
		
		$query = mysqli_query($con,$string);
	}
	else
	{
		$string = "Select SUM(transaction_amount) as total, ledger_receivable.* from ledger_receivable where
		customer_id = $cus and isdeleted = 0
		GROUP BY receivable_id
		HAVING total > 0";
		
		$query = mysqli_query($con,$string);
	}
	?>
	<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									
									<th>#</th>
									<th>TRANSACTION APPLY TO</th>
									<th>DUE DATE</th>
									<th>AMOUNT</th>
								</thead>
			<?PHP
			$ctr = 1;
			$source = "";
			$dis = 1;
			while($row2 = mysqli_fetch_assoc($query))
			{
				
				
				$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where transaction_apply_to = $row2[transaction_apply_to]"));
				$set = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[settlement_type_id]"));
				$sid = $row['remarks_sales'];
				
					
				?>
				<tr>
					
					<td><?php echo $ctr?></td>
					<td><?php echo $row['transaction_apply_to'];?></td>
					<td><?php echo $row['due_date'];?></td>
					<td><?php
						$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable
						where transaction_apply_to = $row[transaction_apply_to] and isdeleted = 0"));
						echo number_format($total['total'],2);
					?></td>
					
				</tr>
					<?php
					
				$dis = 0;
				$ctr++;
			}
			?>
			
	</table>
	<?php
}

function ledger($invoice,$cus,$branch,$dfrom,$dto,$print,$withbal)
{
	global $con;
	if($withbal == 0)
	{
		$string = "Select * from ledger_receivable where isdeleted = 0 and transaction_apply_to != ''";
		
		if(!empty($cus))
		{
			$string = $string." and customer_id = $cus";
		}
		if(!empty($invoice))
		{
			$string = $string." and transaction_apply_to = $invoice";
		}
		
		$string = $string." order by transaction_apply_to desc, transaction_amount desc";
		
		$query = mysqli_query($con,$string);
	}
	else
	{
		$string = "Select SUM(transaction_amount) as total, ledger_receivable.* from ledger_receivable where
		customer_id = $cus and isdeleted = 0
		GROUP BY receivable_id
		HAVING total > 0";
		
		$query = mysqli_query($con,$string);
	}
	?>
	<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									
									<th>#</th>
									<th></th>
									<th>TRANSACTION SOURCE</th>
									<th>TRANSACTION APPLY TO</th>
									<th>TRANSACTION DATE</th>
									<th>DUE DATE</th>
									<th>SETTLEMENT TYPE</th>
									<th>AMOUNT</th>
								</thead>
			<?PHP
			$ctr = 1;
			$source = "";
			$dis = 1;
			while($row = mysqli_fetch_assoc($query))
			{
					$sid = $row['receivable_id'];
				
				
					$set = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[settlement_type_id]"));
			
				
					if(empty($source))
					{
						$source = $row['transaction_apply_to'];
					}
					if($source != $row['transaction_apply_to'])
					{
						$dis = 1;
						$tot = 0;
						$tot = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable where transaction_apply_to = '$source' and isdeleted = 0"));
						$source = $row['transaction_apply_to'];
						?>
						<tr>
							<td colspan = "7" style = "text-align:right;" >
								
								<b>BALANCE:</b>		
							</td>
							
							<th><?php echo number_format($tot['total'],2);?></th>
						</tr>
						<?php
					}
					
				?>
				<tr>
					
					<td><?php echo $ctr?></td>
					<td>
					<?PHP
					//if($dis != 1)
					//{	
					?>
					
					<button class = "btn btn-danger btn-flat btn-xs" ID = "del<?php echo $ctr;?>">x</button>	
					<script>
						$("#del<?php echo $ctr;?>").click(
							function()
							{
										var r = confirm("Confirm delete");
										
										if(r == true)
										{
											//$("#streetform").html(loading);
											$("#cusledger").html(loading);
											$.post( 
												'php/pos.php',
												{
													leddel:<?php echo $row['receivable_id'];?>,
													ledinv:'<?php echo $invoice;?>',
													ledcus:'<?php echo $cus;?>',
													
												},
												function(data) {
													$('#cusledger').html(data);		
												});
										}
							}
						);
					</script>
					<?php
					//}
					if($dis == 1)
					{	
						?>
							<?php
							if(!empty($sid))
							{
							?>
								<button class = "btn btn-success btn-flat btn-xs" style = "display:none;" ID = "show<?php echo $ctr;?>">DETAILS</button>	
								<button class = "btn btn-primary btn-flat btn-xs" ID = "pay<?php echo $ctr;?>">RECEIVE PAYMENT</button>	
							<?php
							}
							?>
							
								<script>
										$("#show<?php echo $ctr;?>").click(
											function()
											{
												$("#modal").modal("show");
												$("#modalbody").css("min-width","50%");
															
															//$("#streetform").html(loading);
															$("#modalui").html(loading);
															$.post( 
																'php/pos.php',
																{
																	invoicedetails:<?php echo $sid;?>
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
											}
										);
										$("#pay<?php echo $ctr;?>").click(
											function()
											{
												$("#modal").modal("show");
												$("#modalbody").css("min-width","50%");
															
															//$("#streetform").html(loading);
															$("#modalui").html(loading);
															$.post( 
																'php/pos.php',
																{
																	addpaymentui:'<?php echo $sid;?>'
																	
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
					
					</td>
					<td><?php echo $row['transaction_source_number'];?></td>
					<td><?php echo $row['transaction_apply_to'];?></td>
					<td><?php echo $row['transaction_date'];?></td>
					<td><?php echo $row['due_date'];?></td>
					<td><?php echo $set['settlement_description'];?></td>
					<td><?php echo $row['transaction_amount'];?></td>
					
				</tr>
					<?php
					
				$dis = 0;
				$ctr++;
			}
						//$dis = 1;
						$tot = 0;
						$tot = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from ledger_receivable where transaction_apply_to = '$source' and isdeleted = 0"));
						$source = $row['transaction_apply_to'];
						?>
						<tr>
							<td colspan = "7" style = "text-align:right;" >
								
								<b>BALANCE:</b>		
							</td>
							
							<th><?php echo number_format($tot['total'],2);?></th>
						</tr>
			
	</table>
	<?php
}
function invoice_customer_info2($cus)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from customer_profile where customer_id = $cus"));
	?>
			<table class = "table table-condensed table-sm">
								
																	
								<tr>
									<td>Customer no: <b><?php echo $row['customer_no'];?></b></td>
								</tr>
								<tr>
									<td>Full Name: <b><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></b></td>
								</tr>
								<tr>
									<td>Address: <b><?php echo $row['home_address'];?></b></td>
								</tr>								
			</table>
	<?php
}

function invoice_customer_info($cus)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from customer_profile where customer_id = $cus"));
	$add = mysqli_fetch_assoc(mysqli_query($con,"Select customer_address.street_name, lup_barangay.barangay_name, lup_city_town.city_town_name,
	lup_province.province_name, lup_region.region_name from
	lup_barangay, lup_city_town, lup_region, lup_province, customer_address where 
	customer_address.customer_id = $cus and customer_address.barangay_id = lup_barangay.barangay_id
	and customer_address.city_town_id = lup_city_town.city_town_id 
	and customer_address.province_id = lup_province.province_id
	and customer_address.region_id = lup_region.region_id
	"));
	?>
			<table class = "table table-condensed table-sm">
								<tr>
									<td>Customer no: <b><?php echo $row['customer_no'];?></b></td>
								
									<td>Customer Type: <b><?php
									$ctype = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type_id]"));
									echo $ctype['customer_type_name'];
									?></b></td>
									
								</tr>
									<td>Reference No: <b><?php
										
											echo $row['reference_no'];
										
									;?></b></td>
									<td>Customer no: <b><?php echo $row['customer_no'];?></b></td>
									<td>Full Name: <b><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></b></td>
								</tr>
								<tr>
									<td>Address: <b><?php echo $add['street_name']." ".$add['barangay_name']." ".$add['city_town_name'].
									$add['province_name']." ".$add['region_name'];?></b></td>
									<td>CONTACT NO 1: <b><?php echo $row['contact_no1'];?></b></td>
									<td>CONTACT NO 2: <b><?php echo $row['contact_no2'];?></b></td>
								</tr>
								<tr>
									<td>FACEBOOK NAME: <b><?php echo $row['social_media1'];?></b></td>
									<td>E-MAIL ADDRESS: <b><?php echo $row['email_address'];?></b></td>
									<td style = "text-align:right;"></td>
								</tr>
								
			</table>
	<?php
}

function insert_rebate($branch,$cus,$sales_id,$total_sales,$settle)
{
	global $con;
	$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
	
	
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $sales_id"));
	
	$rebate = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_rebate"));
	
	$reb = 0;
	$reba = 0;
	$tsales = 0;
	
	if(!empty($row))
	{
		$reb = $rebate['rebate_point'];
		$reba = $rebate['rebate_amount'];
		$tsales = $total_sales;
		$ramount = ($tsales/$reba*$reb);
	}	
	else
	{
		$ramount = $settle;
	}
	//echo $rebate['rebate_point']." aaa";
	
	
	mysqli_query($con,"insert into ledger_rebate set 
	result = '$result',
	pos_sales_id = $sales_id,
	sales_invoice_number = '$row[sales_invoice_number]',
	total_sales = $row[total_sales],
	sales_with_rebate = $tsales,
	rebate = $ramount,
	rate_sales = $reba,
	rate_points = $reb,
	rebate_date = NOW(),
	branch_id = $branch,
	customer_id = $cus,
	remarks_sales = '$settle',
	isdeleted = 0");
				$row = mysqli_fetch_assoc(mysqli_query($con,"Select rebate_id from  ledger_rebate where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['rebate_id'];
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
									
								$idno = $row['rebate_id'].$count;
			mysqli_query($con,"Update ledger_rebate set rebate_no = '$idno',result = '' where rebate_id = $row[rebate_id]");
}

function insert_ref_rebate($branch,$cus,$sales,$total_sales,$settle)
{
	global $con;
	
	$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
	
	
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_sales where pos_sales_id = $sales"));
	
	$rebate = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_rebate"));
	
	$reb = 0;
	$reba = 0;
	$tsales = 0;
	
	if(!empty($row))
	{
		$reb = $rebate['referral_point'];
		$reba = $rebate['referral_amount'];
		$tsales = $row['total_sales'];
		$ramount = ($row['total_sales']/$reba*$reb);
	}	
	else
	{
		$ramount = $settle;
	}
	//echo $rebate['rebate_point']." aaa";
	
	
	mysqli_query($con,"insert into ledger_rebate set 
	result = '$result',
	pos_sales_id = $sales,
	sales_invoice_number = '$row[sales_invoice_number]',
	total_sales = $tsales,
	rebate = $ramount,
	rate_sales = $reba,
	rate_points = $reb,
	rebate_date = NOW(),
	branch_id = $branch,
	customer_id = $cus,
	remarks_sales = '$settle',
	isdeleted = 0");
				$row = mysqli_fetch_assoc(mysqli_query($con,"Select rebate_id from  ledger_rebate where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['rebate_id'];
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
									
								$idno = $row['rebate_id'].$count;
			mysqli_query($con,"Update ledger_rebate set rebate_no = '$idno',result = '' where rebate_id = $row[rebate_id]");
}

function insert_salesincome($branch,$cus,$sales_id,$set_id,$amount,$sales_reference,$payment_reference,$tran_type,$verified,$vuser,$proof,$sales_remarks,$payment_remarks,$user)
{
	global $con;
	$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
	
				
	mysqli_query($con,"insert into ledger_sales_income set 
	result = '$result',
	branch_id = $branch,
	customer_id = $cus,
	sales_income_date = NOW(),
	pos_sales_id = $sales_id,
	settlement_type_id = $set_id,
	amount = $amount, 
	sales_reference_no = '$sales_reference',
	payment_reference_no = '$payment_reference',
	transaction_type_id = $tran_type,
	verified = $verified,
	verified_datetime = '',
	verified_by_fullname = '',
	proof_of_payment = '$proof',
	remarks_sales = '$sales_remarks',
	remarks_payment = '$payment_remarks',
	isdeleted = 0,
	created_by_fullname = '$user'");
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select sales_income_id from  ledger_sales_income where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['sales_income_id'];
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
									
								$idno = $row['sales_income_id'].$count;
			mysqli_query($con,"Update ledger_sales_income set sales_income_no = '$idno',result = '' where sales_income_id = $row[sales_income_id]");
}

function insert_creditline($branch,$cus,$set_id,$amount,$apply_to,$source_number,$source,$proof,$remarks_sales,$remarks_payment,$user,$tdate,$tdue)
{
	global $con;
	$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
	//$cline = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_credit_line where credit_line_payment_type_id = $set_id and isdeleted = 0"));
	$cline = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_credit_line where isdeleted = 0"));
	$due = "";
	if(!empty($cline))
	{
		$days = $cline['credit_line_days_to_due']." days";
		
		$date= date_create(date('Y-m-d'));
		
		date_add($date,date_interval_create_from_date_string($days));
		$due = date_format($date,"Y-m-d");
	}
	
	if(empty($tdate))
	{
		$tdate = date('Y-m-d');
	}
				
	mysqli_query($con,"insert into ledger_receivable set 
	result = '$result',
	branch_id = $branch,
	customer_id = $cus,
	transaction_date = '$tdate',
	due_date = '$due',
	settlement_type_id = $set_id,
	transaction_amount = $amount,
	transaction_apply_to = '$apply_to',
	transaction_source_number = '$source_number',
	transaction_source = '$source',
	proof_of_payment = '$proof',
	remarks_sales = '$remarks_sales',
	remarks_payment = '$remarks_payment',
	isdeleted = 0,
	created_by_fullname = '$user'");
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select receivable_id from  ledger_receivable where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['receivable_id'];
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
									
								$idno = $row['receivable_id'].$count;
			mysqli_query($con,"Update ledger_receivable set receivable_no = '$idno',result = '' where receivable_id = $row[receivable_id]");
}

function pos_settlement_list($tran,$print)
{
	global $con;
	
	$string = "Select * from pos_sales_settlement where pos_sales_id = $tran and isdeleted = 0 and remarks = ''";
	
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-sm" id = "banktable" style = "font-size:12px;">
								<thead>
									<th>#</th>
									<?php
									if($print == 0)
									{
									?>
									<th></th>
									<?php
									}
									?>
									<th>SETTLEMENT CODE</th>
									<th>SETTLEMENT DESCRIPTION</th>
									<th>AMOUNT</th>
									<th>REFERENCES</th>
									<?php
									if($print == 0)
									{
									?>
									<th>PROOF OF PAYMENT 1</th>
									<th>PROOF OF PAYMENT 2</th>
									<?php
									}
									?>
								</thead>
			<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					<td><?php echo $ctr;?></td>
					<?php
					if($print == 0)
					{
					?>
						<td><button class = "btn btn-danger btn-flat btn-xs" id = "setdel<?php echo $ctr;?>">x</button></td>
					<?php
					}
					?>
					<td><?php echo $row['settlement_type_code'];?></td>
					<td><?php echo $row['settlement_type_description'];?></td>
					<?php
					if($print == 0)
					{
					?>
						<td>
						<input type = "text" id = "posseteditamount<?php echo $ctr;?>" value = "<?php echo $row['settlement_amount'];?>" style = "width:80px;">
						<button class = "btn btn-success btn-flat btn-xs" id = "setedit<?php echo $ctr;?>"><i class="fa fa-check"></i></button>
						<script>
							$("#setedit<?php echo $ctr;?>").click(
							function()
							{
										
											//$("#streetform").html(loading);
											var newqnty = $("#posseteditamount<?php echo $ctr;?>").val();
											$("#possettlementui").html(loading);
											
											//alert(newqnty);
											$.post( 
												'php/pos.php',
												{
													possetedit:<?php echo $row['pos_sales_settlement_id'];?>,
													posseteditval:newqnty
													
													
												},
												function(data) {
													$('#possettlementui').html(data);		
												});
										
							}
						);
						</script>
						</td>
					<?php
					}
					else
					{
						?>
						<td><?php echo $row['settlement_amount'];?></td>
						<?php
					}
					?>
					<td>
						<?php
						if(!empty($row['reference_no1']))
							echo "REFERENCE NO1:".$row['reference_no1']."<br>";
						if(!empty($row['reference_no2']))
							echo "REFERENCE NO2:".$row['reference_no2']."<br>";
						if(!empty($row['with_reference_description1']))
							echo "REFERENCE DESCRIPTION1:".$row['with_reference_description1']."<br>";
						if(!empty($row['with_reference_description2']))
							echo "REFERENCE DESCRIPTION2:".$row['with_reference_description2']."<br>";
						?>
					</td>
					<?php
					if($print == 0)
					{
					?>
						<td>
							<?php
							if(!empty($row['proof_of_payment1']))
							{
								?>
									<img src = "images/proof_of_payment/<?php echo $row['proof_of_payment1'];?>" width = "100" >
								<?php
							}
							?>
						</td>
						<td>
							<?php
							if(!empty($row['proof_of_payment2']))
							{
								?>
									<img src = "images/proof_of_payment/<?php echo $row['proof_of_payment2'];?>" width = "100" >
								<?php
							}
							?>
						</td>
					<?php
					}
					?>
				</td>
				<script>
					$("#setdel<?php echo $ctr;?>").click(
							function()
							{
										var r = confirm("Confirm delete");
										
										if(r == true)
										{
											//$("#streetform").html(loading);
											$("#possettlementui").html(loading);
											$.post( 
												'php/pos.php',
												{
													possetdel:<?php echo $row['pos_sales_settlement_id'];?>
													
												},
												function(data) {
													$('#possettlementui').html(data);		
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
						
					$('#banktable').DataTable({
					  'paging'      : false,
					  'lengthChange': false,
					  'searching'   : false,
					  'ordering'    : false,
					  'info'        : false,
					  'autoWidth'   : false
					});												
				}
			);
		</script>
	<?php
		}
}
function pos_settlement_list2($tran,$print)
{
	global $con;
	
	$string = "Select * from pos_sales_settlement where pos_sales_id = $tran and isdeleted = 0 and remarks = ''";
	
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-condensed table-hover table-sm" style = "font-size:12px;">
								<thead>
									
									<th>SETTLEMENT DESCRIPTION</th>
									<th>AMOUNT</th>
								</thead>
			<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
				<tr>
					
					<td><?php echo $row['settlement_type_description'];?></td>
					<td><?php echo $row['settlement_amount'];?></td>	
				</tr>
				<?php
				$ctr++;
			}
			?>
	</table>
<?php
}
function discountlist($tran,$print)
{
	global $con;
	$string = "Select * from pos_sales_detail where pos_sales_id = $tran and isdeleted = 0 and discount != 0";
	
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-sm" id = "banktable" style = "width:100%;font-size:12px;">
								<thead>
									<?php
									if($print == 0)
									{
									?>
										<th></th>
									<?php
									}
									?>
									<th>#</th>
									<th>DISCOUNT DESCRIPTION</th>
									<th>AMOUNT</th>
									<th>TOTAL DISCOUNT</th>
								</thead>
			<?php
			$ctr = 1;
			$tqnty = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$tqnty = $tqnty + $row['quantity'];
				$total = $total + $row['grand_total'];
				?>
				<tr>
					<?php
									if($print == 0)
									{
									?>
					<td><button class = "btn btn-danger btn-flat btn-xs" id = "del<?php echo $ctr;?>">x</button></td>
					<?php
									}
					?>
					<td><?php echo $ctr;?></td>
					
					<td><?php echo $row['item_description']?></td>
				
								
										<td><?php 
											if($row['ispercent'] == 1) 
												echo $row['discount']."%";
											else
												echo $row['discount'];
											
										?></td>
							
					<td><?php echo number_format($row['grand_total'],2);?></td>
					
				</tr>
					<script>
						$("#del<?php echo $ctr;?>").click(
							function()
							{
										var r = confirm("Confirm delete");
										
										if(r == true)
										{
											//$("#streetform").html(loading);
											$("#discountlistui").html(loading);
											$.post( 
												'php/pos.php',
												{
													posdiscountdel:<?php echo $row['pos_sales_detail_id'];?>
													
												},
												function(data) {
													$('#discountlistui').html(data);		
												});
										}
							}
						);
					</script>
					
				<?php
				$ctr++;
			}
			?>
	</TABLE>
	<?php
	
}

function pos_item_list($tran,$print,$fromorder)
{
	global $con;
	
	$string = "Select * from pos_sales_detail where pos_sales_id = $tran and isdeleted = 0 and discount = 0";
	
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-bordered table-hover table-sm" id = "banktable" style = "width:100%;font-size:12px;">
								<thead>
									<?php
									if($print == 0)
									{
										
									?>
										<th></th>
									<?php
									}
									?>
									<th>#</th>
									
									<th>ITEM DESCRIPTION</th>
									<th>VARIATION</th>
									<th>QUANTITY</th>
									<th>PRICE</th>
									<?php
									if($fromorder != 1)
									{
									?>
									<th>ITEM DISCOUNT</th>
									<?php
									}
									?>
									<th>TOTAL</th>
									
									
								</thead>
			<?php
			$ctr = 1;
			$tqnty = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$tqnty = $tqnty + $row['quantity'];
				$total = $total + $row['grand_total'];
				$i = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_item where item_id = $row[item_id]"));
				$vcount = mysqli_num_rows(mysqli_query($con,"Select * from lup_variations where item_id = $row[item_id]"));
				$v = mysqli_fetch_assoc(mysqli_query($con,"Select description from lup_variations where item_id = $row[item_id]"));
				?>
				<tr>
					<?php
									if($print == 0)
									{
										
									?>
											<td>
											<?php
											if($row['done'] != 1)
											{
											?>
												<button class = "btn btn-danger btn-flat btn-xs" id = "del<?php echo $ctr;?>">x</button>
											<?php
											}
											?>
											</td>
					<?php
										
									}
					?>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['item_description']?></td>
					<td><?php
						if($vcount != 1)
							echo $v['description'];
						else
							echo "-";
					?></td>
					<?php
									if($print == 0 )
									{
										if($row['finalize'] != 1 && $fromorder == 1)
										{
									?>
									<td>
										<input type = "number" id = "iqnty<?php echo $ctr;?>" value = "<?php echo $row['quantity'];?>" style = "width:70px;">
										<button class = "btn btn-success btn-flat btn-xs" id = "iedit<?php echo $ctr;?>"><i class="fa fa-check"></i></button>
									</td>
									<?php
										}
										else{
											?>
											<td><?php echo $row['quantity'];?></td>
											<?php
										}
									}
									else
									{
										?>
										<td><?php echo $row['quantity'];?></td>
										<?php
									}
									if($print == 0 && $i['open_price'] == 1)
									{
									?>
									<td>
										<input type = "number" id = "iprice<?php echo $ctr;?>" value = "<?php echo $row['item_price'];?>" style = "width:70px;">
										<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>" style = "display:none;"><i class="fa fa-check"></i></button>
									</td>
									<?php
									}
									else
									{
										?>
										<td><?php echo $row['item_price'];?></td>
										<?php
									}
									
									
									?>
					<?php
					if($print == 0)
					{
				
						if($fromorder != 1)
						{
					
					?>
					<td>
						<input type = "number" id = "idis<?php echo $ctr;?>" value = "<?php echo $row['item_discount'];?>" style = "width:70px;"
						
						<label><input type = "checkbox" id = "iper<?php echo $ctr;?>" value = "1"
						<?php
						if($row['ispercent'] == 1)
							echo "checked";
						?>><b> % </b></label>
					</td>
					<?php
						}
					}
					else
					{
						?>
							<td><?php echo number_format($row['total_item_discount'],2);?></td>
						<?php
					}
					?>
					<td id = "sub<?php echo $ctr;?>"><?php echo $row['grand_total']?></td>
					
				</tr>
					<script>
						$("#del<?php echo $ctr;?>").click(
							function()
							{
										var r = confirm("Confirm delete");
										
										if(r == true)
										{
											//$("#streetform").html(loading);
											$("#positemlist").html(loading);
											$.post( 
												'php/pos.php',
												{
													positemdel:<?php echo $row['pos_sales_detail_id'];?>,
													positemdelfrom:<?php echo $fromorder;?>
													
												},
												function(data) {
													$('#positemlist').html(data);		
												});
										}
							}
						);
						$("#iqnty<?php echo $ctr;?>").click(function () {
						   $(this).select();
						});
						
						$("#idis<?php echo $ctr;?>").click(function () {
						   $(this).select();
						});
						
						$("#iper<?php echo $ctr;?>").click(
							function()
							{
										if($("#idis").val() != 0)
										{
										
											var newqnty = $("#idis<?php echo $ctr;?>").val();
											
											var iperr;
											if($("#iper<?php echo $ctr;?>").is(':checked'))
												iperr = 1;
											else
												iperr = 0;
											
											//alert(iperr);
											
											//alert(newqnty);
											$("#positemlist").html(loading);
											$.post( 
												'php/pos.php',
												{
													posidisedit:<?php echo $row['pos_sales_detail_id'];?>,
													posidiseditval:newqnty,
													posidiseditcount:'<?php echo $ctr;?>',
													posidisper:iperr
													
												},
												function(data) {
													$('#positemlist').html(data);		
												});
												
										}
								
							}
						);
						$("#idis<?php echo $ctr;?>").keyup(
							function(e)
							{
								var key = e.which;
									
									if(key == 13)
									{
										if($("#idis").val() != 0)
										{
										
											var newqnty = $("#idis<?php echo $ctr;?>").val();
											
											var iperr;
											if($("#iper<?php echo $ctr;?>").is(':checked'))
												iperr = 1;
											else
												iperr = 0;
											
											//alert(iperr);
											
											//alert(newqnty);
											$("#positemlist").html(loading);
											$.post( 
												'php/pos.php',
												{
													posidisedit:<?php echo $row['pos_sales_detail_id'];?>,
													posidiseditval:newqnty,
													posidiseditcount:'<?php echo $ctr;?>',
													posidisper:iperr
													
												},
												function(data) {
													$('#positemlist').html(data);		
												});
												
										}
									}
										
											
										
							}
						);
						
						$("#iqnty<?php echo $ctr;?>").keyup(
							function(e)
							{
								var key = e.which;
									
									if(key == 13)
									{
										if($("#iqnty").val() != '')
										{
											//$("#streetform").html(loading);
											var newqnty = $("#iqnty<?php echo $ctr;?>").val();
											$("#positemlist").html(loading);
											$("#barcode").focus();
											//alert(newqnty);
											$.post( 
												'php/pos.php',
												{
													positemedit:<?php echo $row['pos_sales_detail_id'];?>,
													positemeditval:newqnty,
													positemeditcount:'<?php echo $ctr;?>',
													positemeditfrom:'<?php echo $fromorder;?>'
													
												},
												function(data) {
													$('#positemlist').html(data);		
												});
												
										}
									}
										
											
										
							}
						);
						$("#iedit<?php echo $ctr;?>").click(
							function(e)
							{
								
										if($("#iqnty").val() != '')
										{
											//$("#streetform").html(loading);
											var newqnty = $("#iqnty<?php echo $ctr;?>").val();
											$("#positemlist").html(loading);
											$("#barcode").focus();
											//alert(newqnty);
											$.post( 
												'php/pos.php',
												{
													positemedit:<?php echo $row['pos_sales_detail_id'];?>,
													positemeditval:newqnty,
													positemeditcount:'<?php echo $ctr;?>',
													positemeditfrom:'<?php echo $fromorder;?>'
													
												},
												function(data) {
													$('#positemlist').html(data);		
												});
												
										}		
							}
						);
						$("#iprice<?php echo $ctr;?>").click(function () {
						   $(this).select();
						});
						
						$("#iprice<?php echo $ctr;?>").keyup(
							function(e)
							{
								var key = e.which;
									
									if(key == 13)
									{
										if($("#iqnty").val() != '')
										{
											//$("#streetform").html(loading);
											var newqnty = $("#iprice<?php echo $ctr;?>").val();
											$("#positemlist").html(loading);
											$("#barcode").focus();
											//alert(newqnty);
											$.post( 
												'php/pos.php',
												{
													pospriceedit:<?php echo $row['pos_sales_detail_id'];?>,
													pospriceeditval:newqnty,
													pospriceeditcount:'<?php echo $ctr;?>'
													
												},
												function(data) {
													$('#positemlist').html(data);		
												});
												
										}
									}
										
											
										
							}
						);
						
					</script>
					
				<?php
				$ctr++;
			}
			?>
			<tr>
				<td></td>
				<td></td>
				<th>TOTAL:</th>
				<td></td>
				<th id = "totqnty"><?php echo number_format($tqnty,2);?></th>
				<?php
				if($print == 0)
				{
				?>
				<td></td>
				<?php
				}
				?>

				<th id = "totalsub"><?php echo number_format($total,2);?></th>
				
				
			</tr>
	</TABLE>
	<?php
	
}
function pos_item_list2($tran,$print)
{
	global $con;
	
	$string = "Select * from pos_sales_detail where pos_sales_id = $tran and isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	?>
	<table class = "table table-condensed table-hover table-sm" style = "width:100%;font-size:12px;">
								<thead>
									<?php
									if($print == 0)
									{
									?>
										<th></th>
									<?php
									}
									?>
									<th>#</th>
									
									<th>ITEM DESCRIPTION</th>
									<th>QUANTITY</th>
									<th>PRICE</th>
									<th>TOTAL</th>
								</thead>
			<?php
			$ctr = 1;
			$tqnty = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$tqnty = $tqnty + $row['quantity'];
				$total = $total + $row['grand_total'];
				?>
				<tr>
					<?php
									if($print == 0)
									{
									?>
					<td><button class = "btn btn-danger btn-flat btn-xs" id = "del<?php echo $ctr;?>">x</button></td>
					<?php
									}
					?>
					<td><?php echo $ctr;?></td>
					
					<td><?php echo $row['item_description']?></td>
					<?php
									if($print == 0)
									{
									?>
									<td>
										<input type = "text" id = "iqnty<?php echo $ctr;?>" value = "<?php echo $row['quantity'];?>" style = "width:70px;">
										<button class = "btn btn-success btn-flat btn-xs" id = "edit<?php echo $ctr;?>"><i class="fa fa-check"></i></button>
									</td>
					<?php
									}
									else
									{
										?>
										<td><?php echo $row['quantity'];?></td>
										<?php
									}
									?>
					<td><?php echo $row['item_price']?></td>
					<td id = "sub<?php echo $ctr;?>"><?php echo $row['grand_total']?></td>
				</tr>
					<script>
						$("#del<?php echo $ctr;?>").click(
							function()
							{
										var r = confirm("Confirm delete");
										
										if(r == true)
										{
											//$("#streetform").html(loading);
											$("#positemlist").html(loading);
											$.post( 
												'php/pos.php',
												{
													positemdel:<?php echo $row['pos_sales_detail_id'];?>
													
												},
												function(data) {
													$('#positemlist').html(data);		
												});
										}
							}
						);
						
						$("#edit<?php echo $ctr;?>").click(
							function()
							{
										
											//$("#streetform").html(loading);
											var newqnty = $("#iqnty<?php echo $ctr;?>").val();
											$("#positemlist").html(loading);
											
											//alert(newqnty);
											$.post( 
												'php/pos.php',
												{
													positemedit:<?php echo $row['pos_sales_detail_id'];?>,
													positemeditval:newqnty,
													positemeditcount:'<?php echo $ctr;?>'
													
												},
												function(data) {
													$('#positemlist').html(data);		
												});
										
							}
						);
						
					</script>
					
				<?php
				$ctr++;
			}
			?>
			<tr>
				<td></td>
				
				<th>TOTAL:</th>
				
				<th id = "totqnty"><?php echo number_format($tqnty,2);?></th>
				
				<td></td>
				
				<th id = "totalsub"><?php echo number_format($total,2);?></th>
				
				
			</tr>
	</TABLE>
	<?php
	
}

function pos_items($sales_id,$class,$cat,$dep,$key,$branch)
{
	global $con;
	
	$string = "Select * from pos_lup_item where isdeleted = 0 and visible = 1 and branch_id = $branch";
	
	if(!empty($class))
	{
		$string = $string." and classification_id = $class";
	}
	
	if(!empty($dep))
	{
		$string = $string." and department_id = $dep";
	}
	
	if(!empty($cat))
	{
		$string = $string." and classification_id = $cat";
	}
	
	if(!empty($key))
	{
		$string = $string." and item_description like '%$key%'";
	}
	
	//$icus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_profile.* from pos_sales, customer_profile where 
	//pos_sales.pos_sales_id = $pos_sales_id and pos_sales.customer_id = customer_profile.customer_id"));
	
	//$icgroup = mysqli_fetch_assoc(mysqli_query($con,"Select lup_customer_type_group.pricing from lup_customer_type,lup_customer_type_group where 
	//lup_customer_type.customer_type_id = $icus[customer_type_id] and lup_customer_type.customer_type_group = lup_customer_type_group.customer_type_group_id"));
					
	$query = mysqli_query($con,$string);
	$bg = array('bg-orange','bg-green');
	$count = mysqli_num_rows($query);
	if($count != 0)
	{
	?>
		 <div class="row">
			<?php
			$ctr = 1;
			$c = 0;
			while($row = mysqli_fetch_assoc($query))
			{
					$pr = "";
					if($row['open_price'] == 1)
					{
						$pr = "OPEN PRICE";
					}
					else
					{
						$pr = $row['item_price1'];
						//if($icgroup['pricing'] == 1)
							//$pr = $row['item_price1'];
						//elseif($icgroup['pricing'] == 2)
							//$pr = $row['item_price2'];
						//elseif($icgroup['pricing'] == 3)
							//$pr = $row['item_price3'];
						
					}
					
				?>
					<div class="col-md-4 col-sm-4 col-xs-12" style = "cursor:pointer;" id = "item<?php echo $ctr;?>">
					  <div class="info-box <?php echo $bg[$c];?>">
						<span class="info-box-icon">
						<?php
						if(!empty($row['item_photo']))
						{
						?>
							<img src = "images/items/<?php echo $row['item_photo'];?>" width = "80%" height = "90%">
						<?php
						}
						else
						{
							?>
								<i class="ion ion-ios-gear-outline"></i>
							<?php
						}
						?>
						</span>
						<div class="info-box-content ">
						  <span class="info-box-text"><?php echo $row['item_description'];?></span>
						  <span class="info-box-number" style = "display:none"><?php echo $pr;?></span>
						</div>
						<!-- /.info-box-content -->
					  </div>
			  <!-- /.info-box -->
					</div>
					<script>
						$("#item<?php echo $ctr;?>").click(
							function()
							{
									
															$.post( 
																 'php/pos.php',
																 {
																	 add_item_id:'<?php echo $row['item_code'];?>',
																	 add_sales_id:'<?php echo $sales_id;?>'
																},
																 function(data) {
																	$('#click').html(data);
																	
																 });
																 
								/*$("#modal2").modal("show");
								$("#modalbody2").css("max-width","30%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/pos.php',
												{
													itemqnty:<?php echo $row['item_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});*/
							}
						);
					</script>
				<?php
				$c++;
				$ctr++;
									
				if($c == 2)
					$c = 0;
										
			}
			?>
			
		</div>
	<?php
	}
	else
	{
		echo "<h3>No Search Result.</h3>";
	}
}
function pos_category($sales_id,$class,$branch)
{
	
	global $con;
	$string = "Select * from pos_lup_classification where isdeleted = 0 and visible = 1 and branch_id = $branch";
	
	if(!empty($class))
	{
		$string = $string." and classification_id = $class";
	}
	
	$query = mysqli_query($con,$string);
	
	$count = mysqli_num_rows($query);
	$bg = array('bg-yellow','bg-blue');
	if($count != 0)
	{
	?>
	
							<div class="row">
								<?php
								$ctr = 1;
								$cc = 0;
								while($row = mysqli_fetch_assoc($query))
								{
									$count = 0;
									$count = mysqli_num_rows(mysqli_query($con,"Select * from pos_lup_item where classification_id = $row[classification_id] and isdeleted = 0 and visible = 1"));
									?>
										<input type = "hidden" id = "posccat<?php echo $ctr;?>" value = "<?php echo $row['classification_id'];?>">
										
										<div class="col-lg-4 col-xs-6" id = "nn<?php echo $ctr;?>" style = "cursor:pointer;">
										  <!-- small box -->
										  <div class="small-box <?php echo $bg[$cc];?>">
											<div class="inner">
											  <h3><?php echo $count;?></h3>

											  <p><?php echo $row['classification_description'];?></p>
											</div>
											<div class="icon" style = "display:none;">
											  <i class="ion ion-bag"></i>
											</div>
											<a href="#" class="small-box-footer">Open <i class="fa fa-arrow-circle-right"></i></a>
										  </div>
										</div>
										
										<script>
										$("#nn<?php echo $ctr;?>").click(
											function()
											{
												var item = $('#posccat<?php echo $ctr;?>').val();
												$('#positemlist').html(loading);
									
												 $.post( 
																 'php/pos.php',
																 {
																	 positems:item,
																	 positems_sales_id:'<?php echo $sales_id;?>'
																},
																 function(data) {
																	$('#positemlist').html(data);
																	$("#iback").show();
																 });
											}
										);
									</script>
									
									<?php
									$cc++;
									$ctr++;
									
									if($cc == 2)
										$cc = 0;
								}
								?>
							 </div>
							 
	<?php
	}
	else
	{
		echo "<h3> No Classification Listed";
	}
	
}

function rebatelist($print)
{
	global $con;
	
	$string = "Select * from settings_rebate where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th></th>
									<th>#</th>
									<th>REBATE AMOUNT</th>
									<th>REBATE POINT</th>
									<th>REBATE REFERRAL POINT</th>
									<th>SETTLEMENT TYPE</th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$c = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[rebate_settlement_type_id]"));
			
					?>
						<tr>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
								
						
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['rebate_amount'];?></td>
							<td><?php echo $row['rebate_point'];?></td>
							<td><?php echo $row['referral_point'];?></td>
							<td><?php echo $c['settlement_description'];?></td>
						
							
							<script>
								
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													rbedit:<?php echo $row['settings_rebate_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#rebatelist').html(loading);
											$.post( 
												'php/main.php',
												{
													rbdel:<?php echo $row['settings_rebate_id'];?>
												},
												function(data) {
													$('#rebatelist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function clctypelist($print)
{
	global $con;
	
	$string = "Select * from settings_customer_type where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th></th>
									<th>#</th>
									<th>CUSTOMER TYPE</th>
									<th>WITH CREDIT LINE</th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$c = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type_id]"));
			
					?>
						<tr>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
								
						
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $c['customer_type_name'];?></td>
							<td><?php
								if($row['with_credit_line'] == 1)
									echo "YES";
							?></td>
							
							<script>
								
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													cledit:<?php echo $row['settings_customer_type_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#clctypelist').html(loading);
											$.post( 
												'php/main.php',
												{
													cldel:<?php echo $row['settings_customer_type_id'];?>
												},
												function(data) {
													$('#clctypelist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function headerlist($print)
{
	global $con;
	
	$string = "Select * from settings_pos_receipt_header where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th></th>
									<th>#</th>
									<th>BRANCH</th>
									<th>LINE 1</th>
									<th>LINE 2</th>
									<th>LINE 3</th>
									<th>LINE 4</th>
									<th>LINE 5</th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
			
					?>
						<tr>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
								
						
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $br['branch_description'];?></td>
							<td><?php echo $row['line1'];?></td>
							<td><?php echo $row['line2'];?></td>
							<td><?php echo $row['line3'];?></td>
							<td><?php echo $row['line4'];?></td>
							<td><?php echo $row['line5'];?></td>
							<script>
								
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													headeredit:<?php echo $row['pos_receipt_header_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#itemlist').html(loading);
											$.post( 
												'php/main.php',
												{
													headerdel:<?php echo $row['pos_receipt_header_id'];?>
												},
												function(data) {
													$('#headerlist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function footerlist($print)
{
	global $con;
	
	$string = "Select * from settings_pos_receipt_footer where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th></th>
									<th>#</th>
									<th>BRANCH</th>
									<th>LINE 1</th>
									<th>LINE 2</th>
									<th>LINE 3</th>
									<th>LINE 4</th>
									<th>LINE 5</th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
			
					?>
						<tr>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
								
						
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $br['branch_description'];?></td>
							<td><?php echo $row['line1'];?></td>
							<td><?php echo $row['line2'];?></td>
							<td><?php echo $row['line3'];?></td>
							<td><?php echo $row['line4'];?></td>
							<td><?php echo $row['line5'];?></td>
							<script>
								
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													footeredit:<?php echo $row['pos_receipt_footer_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#itemlist').html(loading);
											$.post( 
												'php/main.php',
												{
													footerdel:<?php echo $row['pos_receipt_footer_id'];?>
												},
												function(data) {
													$('#footerlist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function clinelist($print)
{
	global $con;
	
	$string = "Select * from settings_credit_line where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th></th>
									<th>#</th>
									<th>Credit line days to due</th>
									<th>Credit Line with Penalty</th>
									<th>Credit Line Penalty to apply</th>
									<th>Settlement Type</th>
									
									
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$set = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type where settlement_type_id = $row[credit_line_payment_type_id]"));
			
					?>
						<tr>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
								
						
							</td>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['credit_line_days_to_due'];?></td>
							<td><?php echo $row['credit_line_with_penalty'];?></td>
							<td><?php echo $row['credit_line_penalty_to_apply'];?></td>
							<td><?php echo $set['settlement_description'];?></td>

							<script>
								
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													clineedit:<?php echo $row['settings_credit_line_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#itemlist').html(loading);
											$.post( 
												'php/main.php',
												{
													clinedel:<?php echo $row['settings_credit_line_id'];?>
												},
												function(data) {
													$('#clinelist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function itemlist($key,$cat,$class,$branch,$print)
{
	global $con;
	
	$string = "Select * from pos_lup_item where isdeleted = 0 and addon_id = 0 and branch_id = $branch";
	
	if($key != '')
	{
		$string = $string." and item_description like '%$key%'";
	}
	if($cat != '' && $cat != 'all')
	{
		$string = $string." and category_id = '$cat'";
	}
	if($class != '' && $class != 'all')
	{
		$string = $string." and classification_id = '$class'";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "itemtable">
								<thead>
								<?php
								if($print == 0)
								{
								?>
									<th></th>
								<?php
								}
								?>
									<th>#</th>
									<th>PHOTO</th>
									<th>ITEM NAME</th>
									<th>ITEM SHORT NAME</th>
									<th>UNIT</th>
									<th>QUANTITY</th>
									<th>OPEN PRICE</th>
									<th>CATEGORY</th>
									<th>CLASSIFICATION</th>
									<th>DEPARTMENT</th>
									<th>VISIBLE</th>
								
									
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$dep = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_department where department_id = $row[department_id]"));
					$cat = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_category where category_id = $row[category_id]"));
					$class = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $row[classification_id]"));
					$unit = mysqli_fetch_assoc(mysqli_query($con,"Select * from inv_lup_unit where unit_id = $row[unit_id]"));
					?>
						<tr>
							
							<?php
							
							if($print == 0)
							{
							?><td>
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
								
								
							</td>
							<?php
							}
							?>
						
							
							<td><?php echo $ctr;?></td>
							<td><img src = "images/items/<?php echo $row['item_photo'];?>" width = "50" id = "cphoto<?php echo $ctr;?>" style = "cursor:pointer;"></td>
							<td style = "display:none;"><?php 
							if($print == 1)
							{
								echo $row['item_code'];
							}
							else{
								?>
									<input style type = "text" class = "form-control" ID = "editbarcode<?php echo $ctr;?>" value = "<?php echo $row['item_code'];?>" style = "width:85px;">
									<script>
										$("#editbarcode<?php echo $ctr;?>").keyup(
											function(e)
											{
												var key = e.which;
												
												if(key == 13)
												{
													if($("#editbarcode<?php echo $ctr;?>").val() != '')
													{
													$.post( 
														'php/main.php',
														{
															barcodeeditid:<?php echo $row['item_id'];?>,
															barcodeedit:$("#editbarcode<?php echo $ctr;?>").val()
														},
														function(data) {
															$('#click').html(data);		
														});
													}
												}
												
											}
									);
									</script>
								<?php
							}
							?></td>
							<td><?php echo $row['item_description'];?></td>
							<td><?php echo $row['item_short_description'];?></td>
							<td><?php echo $unit['unit_description'];?></td>
							<td><?php echo $row['quantity'];?></td>
							
							<td><?php 
							
							if($row['open_price'] == 1)
								echo "YES";
							else
								echo "NO";
							?></td>
							<td><?php echo $cat['category_description'];?></td>
							<td><?php echo $class['classification_description'];?></td>
							<td><?php echo $dep['department_description'];?></td>
							<td><?php if($row['visible'] == 1)
													echo "YES";?></td>
							
							
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													itemedit:<?php echo $row['item_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								$("#cphoto<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","75%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													showphoto:<?php echo $row['item_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													itemedit:<?php echo $row['item_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#itemlist').html(loading);
											$.post( 
												'php/main.php',
												{
													itemdel:<?php echo $row['item_id'];?>
												},
												function(data) {
													$('#itemlist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#itemtable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function order_typelist($print)
{
	global $con;
	
	$string = "Select * from pos_lup_order_type where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>ORDER TYPE CODE</th>
									<th>ORDER TYPE NAME</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					//$dep = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_department where department_id = $row[department_id]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['order_type_code'];?></td>
							<td><?php echo $row['order_type_description'];?></td>
							
							<td>
								<a href = "" id = "status<?php echo $ctr;?>" class = "dbtn btn-primary btn-sm btn-xs btn-flat">STATUS</a>
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#status<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("min-width","85%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													order_statusui:<?php echo $row['order_type_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													oredit:<?php echo $row['order_type_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#ordertypelist').html(loading);
											$.post( 
												'php/main.php',
												{
													ordel:<?php echo $row['order_type_id'];?>
												},
												function(data) {
													$('#ordertypelist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}
function menugrouplist($print)
{
	global $con;
	
	$string = "Select * from pos_lup_menu_group where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>MENU GROUP CODE</th>
									<th>MENU GROUP NAME</th>
									
									<th>VISIBLE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					//$dep = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_department where department_id = $row[department_id]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['menu_group_code'];?></td>
							<td><?php echo $row['menu_group_description'];?></td>
							<td><?php
								if($row['visible'] == 1)
								{
									echo "yes";
								}
							?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													mgedit:<?php echo $row['menu_group_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#departmentlist').html(loading);
											$.post( 
												'php/main.php',
												{
													mgdel:<?php echo $row['menu_group_id'];?>
												},
												function(data) {
													$('#menugrouplist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function departmentlist($print)
{
	global $con;
	
	$string = "Select * from pos_lup_department where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>DEPARTMENT CODE</th>
									<th>DEPARTMENT NAME</th>
									
									<th>VISIBLE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					//$dep = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_department where department_id = $row[department_id]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['department_code'];?></td>
							<td><?php echo $row['department_description'];?></td>
							<td><?php
								if($row['visible'] == 1)
								{
									echo "yes";
								}
							?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													depedit:<?php echo $row['department_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#departmentlist').html(loading);
											$.post( 
												'php/main.php',
												{
													depdel:<?php echo $row['department_id'];?>
												},
												function(data) {
													$('#departmentlist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function classificationlist($branch,$print)
{
	global $con;
	
	$string = "Select * from pos_lup_classification where isdeleted = 0 and branch_id = $branch";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>CLASSIFICATION CODE</th>
									<th>CLASSIFICATION NAME</th>
									
									<th>VISIBLE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['classification_code'];?></td>
							<td><?php echo $row['classification_description'];?></td>
							<td><?php
								if($row['visible'] == 1)
								{
									echo "yes";
								}
							?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
								<a href = "" id = "addon<?php echo $ctr;?>" class = "dbtn btn-warning btn-sm btn-xs btn-flat">ADD ONS</a>
								
						
							</td>
							
							<script>
								$("#addon<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("min-width","75%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													addonui:<?php echo $row['classification_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													classedit:<?php echo $row['classification_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#cprofilelist').html(loading);
											$.post( 
												'php/main.php',
												{
													classdel:<?php echo $row['classification_id'];?>
												},
												function(data) {
													$('#classificationlist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function categorylist($print)
{
	global $con;
	
	$string = "Select * from pos_lup_category where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>CATEGORY CODE</th>
									<th>CATEGORY NAME</th>
									<th>CLASSIFICATION</th>
									<th>VISIBLE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$class = mysqli_fetch_assoc(mysqli_query($con,"Select * from pos_lup_classification where classification_id = $row[classification_id]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['category_code'];?></td>
							<td><?php echo $row['category_description'];?></td>
							<td><?php echo $class['classification_description'];?></td>
							<td><?php
								if($row['visible'] == 1)
								{
									echo "yes";
								}
							?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													catedit:<?php echo $row['category_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#cprofilelist').html(loading);
											$.post( 
												'php/main.php',
												{
													catdel:<?php echo $row['category_id'];?>
												},
												function(data) {
													$('#categorylist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}
function units($print)
{
	global $con;
	
	$string = "Select * from inv_lup_unit where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>UNIT CODE</th>
									<th>UNIT NAME</th>
									
									<th>VISIBLE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
				
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['unit_code'];?></td>
							<td><?php echo $row['unit_description'];?></td>
							<td><?php
								if($row['visible'] == 1)
								{
									echo "yes";
								}
							?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													unitedit:<?php echo $row['unit_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#categorylist').html(loading);
											$.post( 
												'php/main.php',
												{
													unitdel:<?php echo $row['unit_id'];?>
												},
												function(data) {
													$('#categorylist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}
function supplierlist($print)
{
	global $con;
	
	$string = "Select * from lup_supplier where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>SUPPLIER CODE</th>
									<th>SUPPLIER NAME</th>
									
									<th>VISIBLE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
				
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['supplier_code'];?></td>
							<td><?php echo $row['supplier_description'];?></td>
							<td><?php
								if($row['visible'] == 1)
								{
									echo "yes";
								}
							?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													supplieredit:<?php echo $row['supplier_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#categorylist').html(loading);
											$.post( 
												'php/main.php',
												{
													supplierdel:<?php echo $row['supplier_id'];?>
												},
												function(data) {
													$('#categorylist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}
function settlelist($print)
{
	global $con;
	
	$string = "Select * from lup_settlement_type where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>SETTLEMENT TYPE CODE</th>
									<th>SETTLEMENT TYPE NAME</th>
									<th>SETTLEMENT TYPE GROUP</th>
									<th>REFERENCE NO 1</th>
									<th>REFERENCE NO 2</th>
									<th>REFERENCE DESCRIPTION 1</th>
									<th>REFERENCE DESCRIPTION 2</th>
									<th>PROOF OF PAYMENT 1</th>
									<th>PROOF OF PAYMENT 2</th>
									<th>CHARGE TO ACCOUNT 1</th>
									<th>CHARGE TO ACCOUNT 2</th>
									<th>VISIBLE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$gr = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_settlement_type_group where settlement_type_group_id = $row[settlement_type_group_id]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['settlement_code'];?></td>
							<td><?php echo $row['settlement_description'];?></td>
							<td><?php echo $gr['settlement_type_group_description'];?></td>
							<td>
								<?php
									if($row['with_reference_no1'] == 1)
										echo "yes";
								?>
							</td>
							<td>
								<?php
									if($row['with_reference_no2'] == 1)
										echo "yes";
								?>
							</td>
							<td>
								<?php
									if($row['with_reference_description1'] == 1)
										echo "yes";
								?>
							</td>
							<td>
								<?php
									if($row['with_reference_description2'] == 1)
										echo "yes";
								?>
							</td>
							<td><?php
									if($row['with_proof_of_payment1'] == 1)
										echo "yes";
								?></td>
							<td><?php
									if($row['with_proof_of_payment2'] == 1)
										echo "yes";
								?></td>
							<td><?php
									if($row['charge_to_account1'] == 1)
										echo "yes";
								?></td>
							<td><?php
									if($row['charge_to_account2'] == 1)
										echo "yes";
								?></td>
							<td><?php
									if($row['visible'] == 1)
										echo "yes";
								?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("min-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													setedit:<?php echo $row['settlement_type_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#settlelist').html(loading);
											$.post( 
												'php/main.php',
												{
													setdel:<?php echo $row['settlement_type_id'];?>
												},
												function(data) {
													$('#settlelist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function setgrouplist($print)
{
	global $con;
	
	$string = "Select * from lup_settlement_type_group where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>SETTLEMENT GROUP TYPE CODE</th>
									<th>SETTLEMENT GROUP TYPE NAME</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['settlement_type_group_code'];?></td>
							<td><?php echo $row['settlement_type_group_description'];?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													sgedit:<?php echo $row['settlement_type_group_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#cprofilelist').html(loading);
											$.post( 
												'php/main.php',
												{
													sgdel:<?php echo $row['settlement_type_group_id'];?>
												},
												function(data) {
													$('#setgrouplist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function regstatuslist($print)
{
	global $con;
	
	$string = "Select * from lup_registration_status where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>REGISTRATION STATUS CODE</th>
									<th>REGISTRATION STATUS NAME</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['registration_status_code'];?></td>
							<td><?php echo $row['registration_status_description'];?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													rsedit:<?php echo $row['registration_status_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#cprofilelist').html(loading);
											$.post( 
												'php/main.php',
												{
													rsdel:<?php echo $row['registration_status_id'];?>
												},
												function(data) {
													$('#rstatuslist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}
function climitlist($print)
{
	global $con;
	
	$string = "Select * from lup_credit_line_limit where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>CREDIT LINE LIMIT CODE</th>
									<th>CREDIT LINE LIMIT NAME</th>
									<th>CREDIT LINE LIMIT AMOUNT</th>
									
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['credit_line_limit_code'];?></td>
							<td><?php echo $row['credit_line_limit_description'];?></td>
							<td><?php echo $row['credit_line_limit_amount'];?></td>
						
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													credit:<?php echo $row['credit_line_limit_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#cprofilelist').html(loading);
											$.post( 
												'php/main.php',
												{
													crdel:<?php echo $row['credit_line_limit_id'];?>
												},
												function(data) {
													$('#climitlist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function cardprofilelist($print)
{
	global $con;
	
	$string = "Select * from card_profile where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>CARD NUMBER</th>
									<th>CARD TYPE</th>
									<th>REGISTERED</th>
									<th>VALIDITY</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$card = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_card_type where card_type_id = $row[card_type_id]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['card_number'];?></td>
							<td><?php echo $card['card_type_description'];?></td>
							<td><?php
								if($row['occupied'] == 1)
									echo "YES";
								else
									echo "NO";
							?></td>
							<td>
								<?php
								if($row['with_validity'] == 0)
								{
									echo "LIFETIME";
								}
								else
								{
									echo $row['valid_from']." to ".$row['valid_to'];
								}
								?>
							</td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													cpedit:<?php echo $row['card_profile_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#cprofilelist').html(loading);
											$.post( 
												'php/main.php',
												{
													cpdel:<?php echo $row['card_profile_id'];?>
												},
												function(data) {
													$('#cprofilelist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function customertypelist($print)
{
	global $con;
	
	$string = "Select * from lup_customer_type where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>CUSTOMER TYPE CODE</th>
									<th>CUSTOMER TYPE NAME</th>
									<th>GROUP</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					$group = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type_group where customer_type_group_id = $row[customer_type_group]"));
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['customer_type_code'];?></td>
							<td><?php echo $row['customer_type_name'];?></td>
							<td><?php echo $group['customer_type_group_name'];?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													ctypeedit:<?php echo $row['customer_type_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#ctypelist').html(loading);
											$.post( 
												'php/main.php',
												{
													ctypedel:<?php echo $row['customer_type_id'];?>
												},
												function(data) {
													$('#ctypelist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
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

function cardtypelist($print)
{
	global $con;
	
	$string = "Select * from lup_card_type where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>CARD TYPE CODE</th>
									<th>CARD TYPE NAME</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['card_type_code'];?></td>
							<td><?php echo $row['card_type_description'];?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													cardtypeedit:<?php echo $row['card_type_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#cardtypelist').html(loading);
											$.post( 
												'php/main.php',
												{
													cardtypedel:<?php echo $row['card_type_id'];?>
												},
												function(data) {
													$('#cardtypelist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
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

function branchlist($print)
{
	global $con;
	
	$string = "Select * from lup_branch where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>PHOTO</th>
									<th>BRANCH CODE</th>
									<th>BRANCH NAME</th>
									<th>CONTAC PERSON 1</th>
									<th>CONTACT PERSON 2</th>
									<th>CONTACT NUMBER 1</th>
									<th>CONTACT NUMBER 2</th>
									<th>ADDRESS</th>
									<th>DATE OPENED</th>
								
									<th>IS ACTIVE</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					
	
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><img id = "bphoto<?php echo $ctr;?>" src = "images/branch_photo/<?php echo $row['branch_photo'];?>" width = "70" style = "cursor:pointer;"></td>
							<td><?php echo $row['branch_code'];?></td>
							<td><?php echo $row['branch_description'];?></td>
							<td><?php echo $row['branch_contact_person1'];?></td>
							<td><?php echo $row['branch_contact_person2'];?></td>
							<td><?php echo $row['branch_contact_number1'];?></td>
							<td><?php echo $row['branch_contact_number2'];?></td>
							<td><?php echo $row['address']?></td>
							<td><?php echo $row['date_open'];?></td>
							
							<td><?php
								if($row['isactive'] == 1)
									echo "YES";
							?></td>
							<td>
						
								<a href = "" id = "bedit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "bdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
								<a href = "" id = "cperson<?php echo $ctr;?>" class = "dbtn btn-warning btn-sm btn-xs btn-flat">CONTACTS</a>
						
							</td>
							
							<script>
								$("#cperson<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("min-width","75%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													cpersonui:<?php echo $row['branch_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bphoto<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("min-width","75%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													branchphotui:<?php echo $row['branch_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bedit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("min-width","75%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'php/main.php',
												{
													branchedit:<?php echo $row['branch_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#bdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#banklist').html(loading);
											$.post( 
												'php/main.php',
												{
													branchdel:<?php echo $row['branch_id'];?>
												},
												function(data) {
													$('#branchlist').html(data);		
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
		if($print == 0)
		{
		?>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#banktable').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}
}

function users($level)
{
		global $con;
		$user = get_user_id($_SESSION['c_craft']);
		$agent = get_agent($user);
		$branch = get_branch($user);
	
		if($level == 1)
			$query = mysqli_query($con,"Select * from se_user where istable = 0 order by agent_number");
		else
			$query = mysqli_query($con,"Select * from se_user where istable = 0 and branch_id = $branch order by agent_number");	
	?>
		<table class = "table table-striped table-hover table-sm" id = "usertable">
								<thead>
									<th>#</th>
									<th>AGENT NUMBER</th>
									<th>FULL NAME</th>
									
									<th>BRANCH</th>
									<th>ACTION</TH>
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$team = mysqli_fetch_assoc(mysqli_query($con, "Select * from lup_sales_team where sales_team_id = $row[sales_team_id]"));
				$branch = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $row[branch_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['agent_number'];?></td>
						<td><?php echo $row['fullname'];?></td>
						<td><?php echo $branch['branch_description'];?></td>
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
						
					$('#usertable').DataTable({
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
function referrals($ref)
{
	global $con;
	$string = "Select * from customer_profile, registration where customer_profile.customer_id = registration.customer_id
	and registration.isdeleted = 0";
	
	
	if($ref != "")
	{
		$string = $string." and registration.referral_id = $ref";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "cmastertablee">
								<thead>
									
									<th>#</th>
									<th>REGISTRATION #</th>
									<th>CUSTOMER #</th>
									<th>FULLNAME</th>
									<th>STATUS</th>
									<th>VALID FROM</th>
									<th>VALID TO</th>
									<th>DATE OF REGISTRATION</th>
								</thead>
		<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$reg_status = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_registration_status where registration_status_id = $row[registration_status_id]"));
					?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['registration_no'];?></td>
						<td><?php echo $row['customer_no'];?></td>
						<td><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></td>
						<td><?php echo $reg_status['registration_status_description'];?></td>
						<td><?php echo $row['valid_from'];?></td>
						<td><?php echo $row['valid_to'];?></td>
						<td><?php echo $row['registration_date'];?></td>
					</tr>
				
				
					<?php

				$ctr++;
				
			}
		?>
		</table>
	<?php
		

}

function registration_view($status,$vfrom,$vto,$print,$valid,$regfrom,$regto,$card)
{
	global $con;
	$string = "Select * from view_registration_list where isdeleted = 0";
	
	if($status != "all")
	{
		$string = $string." and registration_status_description = '$status'";
	}
	if($card != "all")
	{
		$string = $string." and card_name = '$card'";
	}
	if($vfrom != "" && $vto != "")
	{
	
		$string = $string." and (valid_from >= '$vfrom' and
		valid_to <= '$vto')";
	}
	
	if($regfrom != "" && $regto != "")
	{
	
		$string = $string." and (registration_date >= '$vfrom' and
		registration_date <= '$vto')";
	}
	
	
	if($valid != "all")
	{
		$string = $string." and with_validity = $valid";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "cmastertablee">
								<thead>
									
									<th>#</th>
									<?php
									if($print == 0)
									{
										?>
											<th></th>
										<?php
									}
									?>
									<th>REGISTRATION #</th>
									<th>CUSTOMER #</th>
									<th>FULLNAME</th>
									<th>STATUS</th>
									<th>VALID FROM</th>
									<th>VALID TO</th>
									<th>DATE OF REGISTRATION</th>
								</thead>
		<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				//$reg_status = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_registration_status where registration_status_id = $row[registration_status_id]"));
					?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['registration_no'];?></td>
						<td><?php echo $row['customer_no'];?></td>
						<td><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></td>
						<td><?php echo $row['registration_status_description'];?></td>
						<td><?php echo $row['valid_from'];?></td>
						<td><?php echo $row['valid_to'];?></td>
						<td><?php echo $row['registration_date'];?></td>
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
						
					$('#cmastertablee').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}

}


function card_info($id)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from card_profile where card_profile_id = $id"));
	
	?>
			<table class = "table table-condensed table-sm">
								<tr>
									<td>CARD NUMBER: <b><?php echo $row['card_number'];?></b></td>
								
									<td>CARD NAME: <b><?php
								
									echo $row['card_name'];
									?></b></td>	
			</table>
	<?php
}

function customer_info($cus)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from customer_profile where customer_id = $cus"));
	$add = mysqli_fetch_assoc(mysqli_query($con,"Select customer_address.street_name, lup_barangay.barangay_name, lup_city_town.city_town_name,
	lup_province.province_name, lup_region.region_name from
	lup_barangay, lup_city_town, lup_region, lup_province, customer_address where 
	customer_address.customer_id = $cus and customer_address.barangay_id = lup_barangay.barangay_id
	and customer_address.city_town_id = lup_city_town.city_town_id 
	and customer_address.province_id = lup_province.province_id
	and customer_address.region_id = lup_region.region_id
	"));
	?>
			<table class = "table table-condensed table-sm">
								<tr>
									<td>Customer no: <b><?php echo $row['customer_no'];?></b></td>
								
									<td>Customer Type: <b><?php
									$ctype = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type_id]"));
									echo $ctype['customer_type_name'];
									?></b></td>
									<td>Reference No: <b><?php
										
											echo $row['reference_no'];
										
									;?></b></td>
								</tr>
									<?php
									if($row['lastname'] != "")
									{
									?>
									<td>Full Name: <b><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></b></td>
									<td></td>
									<td></td>
									<?php
									}else
									{
										?>
										<td>Business Name: <b><?php echo $row['business_name'];?></b></td>
										<td>Contact Person: <b><?php echo $row['contact_person'];?></b></td>
										<td></td>
										<?php
									}
									?>
								</tr>
								<tr>
									<td>Address: <b><?php echo $add['street_name']." ".$add['barangay_name']." ".$add['city_town_name'].
									$add['province_name']." ".$add['region_name'];?></b></td>
									<td>CONTACT NO 1: <b><?php echo $row['contact_no1'];?></b></td>
									<td>CONTACT NO 2: <b><?php echo $row['contact_no2'];?></b></td>
								</tr>
								<tr>
									<td>FACEBOOK NAME: <b><?php echo $row['social_media1'];?></b></td>
									<td>E-MAIL ADDRESS: <b><?php echo $row['email_address'];?></b></td>
									<td style = "text-align:right;"></td>
								</tr>
								
			</table>
	<?php
}
function pos_customer_info($cus)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from customer_profile where customer_id = $cus"));
	$add = mysqli_fetch_assoc(mysqli_query($con,"Select customer_address.street_name, lup_barangay.barangay_name, lup_city_town.city_town_name,
	lup_province.province_name, lup_region.region_name from
	lup_barangay, lup_city_town, lup_region, lup_province, customer_address where 
	customer_address.customer_id = $cus and customer_address.barangay_id = lup_barangay.barangay_id
	and customer_address.city_town_id = lup_city_town.city_town_id 
	and customer_address.province_id = lup_province.province_id
	and customer_address.region_id = lup_region.region_id
	"));
	
	$limit = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(transaction_amount) as total from credit_line_transaction where isdeleted = 0 and customer_id = $cus
	and transaction_apply_to != ''"));
	
	?>
			<table class = "table table-condensed table-sm">
								<tr>
									<td>Customer no: <b><?php echo $row['customer_no'];?></b></td>
								
									<td>Customer Type: <b><?php
									$ctype = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type_id]"));
									echo $ctype['customer_type_name'];
									?></b></td>
									
								</tr>
								<tr>
									<?php
									if($row['lastname'] != "")
									{
									?>
									<td>Full Name: <b><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></b></td>
								
						
									<?php
									}else
									{
										?>
										<td>Business Name: <b><?php echo $row['business_name'];?></b></td>
										<td>Contact Person: <b><?php echo $row['contact_person'];?></b></td>
					
										<?php
									}
									
									?>
									<td>Available Credit Limit: <b><?php echo number_format($limit['total'],2);?></b></td>
								</tr>				
			</table>
	<?php
}
function registration($custono,$reg,$status,$vfrom,$vto,$print,$valid)
{
	global $con;
	$string = "Select * from customer_profile, registration where customer_profile.customer_id = registration.customer_id
	and registration.isdeleted = 0";
	
	
	if($custono != "")
	{
		$string = $string." and customer_profile.customer_no = '$custono'";
	}
	if($reg != "")
	{
		$string = $string." and registration.registration_no = '$reg'";
	}
	if($status != "")
	{
		$string = $string." and registration_status_id = $status";
	}
	
	if($vfrom != "" && $vto != "")
	{
	
		$string = $string." and (registration.valid_from >= '$vfrom' and
		registration.valid_to <= '$vto')";
	}
	
	if($valid != "all")
	{
		$string = $string." and with_validity = $valid";
	}
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "cmastertablee">
								<thead>
									
									<th>#</th>
									<?php
									if($print == 0)
									{
										?>
											<th></th>
										<?php
									}
									?>
									<th></th>
									<th>REGISTRATION #</th>
									<th>CUSTOMER #</th>
									<th>FULLNAME/BUSINESS NAME</th>
									<th>STATUS</th>
									<th>VALID FROM</th>
									<th>VALID TO</th>
									<th>DATE OF REGISTRATION</th>
								</thead>
		<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$reg_status = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_registration_status where registration_status_id = $row[registration_status_id]"));
					?>
					<tr>
						<td><?php echo $ctr;?></td>
						<?php
									if($print == 0)
									{
										?>
										<td>
												
													
														<div class="form-group">
															<select id="raction<?php echo $ctr;?>" name = "ctype" class="form-control">
																<option value = "" hidden "Selected">Select Action..</option>
																<option value = "1">EDIT</option>
																<option value = "2">DELETE</option>
																<option value = "3">CARD PROFILE</option>
																<option value = "4">PROOF OF IDENTITY</option>
																<option value = "5">PHOTO</option>
																<option value = "6">CREDIT LIMIT</option>
															</select>
															
														</div>
													
													
														<div class="form-group">
														<button class = "btn btn-primary btn-flat btn-xs" id = "go<?php echo $ctr;?>">go</button>
														</div>
													
												
										</td>
										<script>
											$("#go<?php echo $ctr;?>").click(
													function(e)
													{
														e.preventDefault();
														
														var r = $("#raction<?php echo $ctr;?>").val();
														
														if(r == "1")
														{
															$("#modal").modal("show");
															$("#modalbody").css("width","65%");
															
															//$("#streetform").html(loading);
															$("#modalui").html(loading);
															$.post( 
																'php/customer.php',
																{
																	regedit:<?php echo $row['registration_id'];?>
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
														else if(r == "2")
														{
															var r = confirm("Confirm Delete");
															
															if(r == true)
															{
															$.post( 
																'php/customer.php',
																{
																	regdelete:<?php echo $row['registration_id'];?>
																	
																},
																function(data) {
																	$('#click').html(data);		
																});
															}
														}
														else if(r == "3")
														{
															$("#modal").modal("show");
															$("#modalbody").css("width","65%");
															
															//$("#streetform").html(loading);
															$("#modalui").html(loading);
															$.post( 
																'php/customer.php',
																{
																	cardprofui:<?php echo $row['registration_id'];?>
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
														else if(r == "4")
														{
															$("#modal").modal("show");
															$("#modalbody").css("width","65%");
															
															//$("#streetform").html(loading);
															$("#modalui").html(loading);
															$.post( 
																'php/customer.php',
																{
																	proofui:<?php echo $row['registration_id'];?>
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
														
														else if(r == "5")
														{
															$("#modal").modal("show");
															$("#modalbody").css("width","65%");
															
															//$("#streetform").html(loading);
															$("#modalui").html(loading);
															$.post( 
																'php/customer.php',
																{
																	photoui:<?php echo $row['registration_id'];?>
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
														else if(r == "6")
														{
															$("#modal").modal("show");
															$("#modalbody").css("width","65%");
															
															//$("#streetform").html(loading);
															$("#modalui").html(loading);
															$.post( 
																'php/customer.php',
																{
																	rclimitui:<?php echo $row['registration_id'];?>
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
														}
														
														
														
														
													}
												);
										</script>
										<?php
									}
									?>
						<td><?php
							$reg = mysqli_fetch_assoc(mysqli_query($con,"select photo_of_applicant from registration where registration_id = $row[registration_id]"));
						if($reg['photo_of_applicant'] != "")
						{
						?>	
							<img src = "images/ID/<?php echo $reg['photo_of_applicant'];?>" width = "100">
						<?php
						}
						?>
						</td>
						
						<td><?php echo $row['registration_no'];?></td>
						<td><?php echo $row['customer_no'];?></td>
						<td><?php 
						if($row['lastname'] != "")
							echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];
						else
							echo $row['business_name'];
							?></td>
						<td><?php echo $reg_status['registration_status_description'];?></td>
						<td><?php echo $row['valid_from'];?></td>
						<td><?php echo $row['valid_to'];?></td>
						<td><?php echo $row['registration_date'];?></td>
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
						
					$('#cmastertablee').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}

}

function get_company()
{
	$com = "YOLKY TOASTY";
	
	return $com;
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


function get_customer_fullname($id)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_id = $id"));
	
	if($row['lastname'] != '')
		$full = $row['lastname']." ".$row['firstname']." ".$row['middlename'];
	else
		$full = $row['business_name'];
	return $full;
}


function get_user_id($username)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_username = '$username'"));
	
	return $row['user_id'];
}

function customer_masterlist($region,$province,$citymun,$brgy,$print,$type,$inactive,$mem,$custono)
{
	

	global $con;
	$string = "Select * from customer_profile where
	isdeleted = 0 ";
	
	if($mem != "" && $mem != "00")
	{
		$string = $string." and is_non_member = $mem";
	}
	if($custono != "")
	{
		$string = $string." and customer_no = '$custono'";
	}
	$part = "";
	if($inactive == 1)
	{
		$part = " order_transaction_date <= date_sub(now(), interval 2 month)";
	}
	
	if($type != '' && $type != "0")
	{
		$string = $string." and customer_profile.customer_type_id = $type";
	}
	
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "cmastertablee">
								<thead>
									<?php
									if($print == 0)
									{
									?>
									<th></th>
									<?php
									}
									?>
									<th>#</th>
									<th></th>
									<th>CUSTOMER #</th>
									<th>FULL NAME/BUSINESS NAME</th>
									<th>CONTACT #</th>
									<th>HOME ADDRESS</th>
									<th>CUSTOMER TYPE</th>
								</thead>
		<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
					$region = 0;
					$province = 0;
					$city = 0;
					$brgy = 0;
					
					if(!empty($row['region_id']))
						$region = $row['region_id'];
					
					if(!empty($row['province_id']))
						$province = $row['province_id'];
					
					if(!empty($row['city_town_id']))
						$city = $row['city_town_id'];
					
					if(!empty($row['barangay_id']))
						$brgy = $row['barangay_id'];
						
					$add = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_region, lup_province, lup_city_town, lup_barangay
					where lup_region.region_id = $region and lup_province.province_id = $province
					and lup_city_town.city_town_id = $city and lup_barangay.barangay_id = $brgy"));
					
					$ty = mysqli_fetch_assoc(mysqli_query($con,"Select customer_type_name from lup_customer_type where customer_type_id = $row[customer_type_id]"));
					
					$ctype = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type_id]"));
			
					?>
					<tr>
							<?php
									if($print == 0)
									{
									?>
									<td>
										<button class = "btn btn-primary btn-flat btn-xs" id = "ref<?php echo $ctr;?>">REFERRALS</button>
										<button class = "btn btn-success btn-flat btn-xs" id = "profile<?php echo $ctr;?>">PROFILE</button>	
									</td>
									<script>
										$("#ref<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("min-width","75%");
															$('#modalui').html(loading);	
															$.post( 
																'php/customer.php',
																{
																	viewref:<?php echo $row['customer_id'];?>,
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									
										$("#profile<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("min-width","75%");
															$('#modalui').html(loading);	
															$.post( 
																'php/customer.php',
																{
																	viewprofile:<?php echo $row['customer_id'];?>,
																	
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
									
						<td><?php echo $ctr;?></td>
						<td><?php
							$reg = mysqli_fetch_assoc(mysqli_query($con,"select photo_of_applicant from registration where customer_id = $row[customer_id]"));
						if($reg['photo_of_applicant'] != "")
						{
						?>	
							<img src = "images/ID/<?php echo $reg['photo_of_applicant'];?>" width = "100">
						<?php
						}
						?>
						</td>
						<td><?php echo $row['customer_no'];?></td>
						<td><?php 
						if($row['lastname'] != "")
							echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];
						else
							echo $row['business_name'];
						?></td>
						
						
						<td><?php echo $row['contact_no1'];?></td>
						<td><?php echo $row['home_address'];?></td>
						<td><?php echo $ty['customer_type_name'];?></td>
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
						
					$('#cmastertablee').DataTable({
					  'paging'      : true,
					  'lengthChange': true,
					  'searching'   : true,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : true
					});												
				}
			);
		</script>
	<?php
		}

}
if(isset($_REQUEST['eq']))
{
?>
	<script>
	$('.result tr').click(function() {
    var rowId = $(this).data('rowKey');
	$("#ref").val(rowId);
	$("#clickval").val(1);
	$("#search_result").html("");
	});
</script>

<div class="card">
  <div class="card-body">
<?php
	$q = trim($_REQUEST['eq']);
	
	/*$sql = "SELECT * FROM usertb WHERE employee_no like '".$q."%' or lname like '$q%'";
	$result = mysql_query($sql);*/
	
	/*$sql = "SELECT *
			FROM sub_profiletb, sub_subscription
			where sub_profiletb.pro_id = sub_subscription.profile_id and 
			((CONCAT(sub_profiletb.lastname,',',sub_profiletb.firstname,' ',sub_profiletb.middlename) like '$q%') or sub_subscription.account_number like '$q%' or 
			sub_profiletb.company_name like '$q%') limit 0,10";*/
	global $con;
	
	$sql = "Select * from customer_profile where 
	((CONCAT(lastname,' ',firstname,' ',middlename) like '$q%') or customer_no like '$q%' or (CONCAT(firstname,' ',lastname,' ',middlename) like '$q%') or business_name like '$q%')limit 0,30";
	
	//modlog($sql,1);
	
	$result = mysqli_query($con, $sql);

	echo "<div id = 'pro_id'>";
	echo "<table border  = '0', width = '100%' class = 'result'>";
	$ctr = 0;
	  
	while($row = mysqli_fetch_assoc($result)) {
	  
	  $emp_no = $row['customer_no'];
	 
	  echo "<tr data-row-key='".$emp_no."'>";
	 
		if($row['lastname'] != "")
			$fname = $row['lastname']." ".$row['firstname']." ".$row['middlename'];
		else
			$fname = $row['business_name'];
	  echo "<td>";
	  echo $emp_no;
	  echo "</td>";
	  echo "<td>";
	  echo $fname;
	  echo "</td></tr>";
	 $ctr++;
	}

	 echo "</table>";
	  echo "</div>";

?>
	</div>
	</div>
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

function modreportlist($user_id)
{
	global $con;
	$query = mysqli_query($con,"Select se_menu_report.*,se_report_access.report_access_id from se_menu_report, se_report_access where se_report_access.user_id = $user_id
	and se_report_access.menu_report_id = se_menu_report.menu_report_id and se_report_access.isdeleted = 0");
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>MODULE</th>
									<th>REPORT</th>
								</thead>
			<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['menu_report_module'];?></td>
						<td><?php echo $row['menu_report_description'];?></td>
						<td><button class = "btn btn-danger btn-flat btn-xs" id = "repdelete<?php echo $ctr;?>">DELETE</button></td>
					</tr>
					<script>
						$("#repdelete<?php echo $ctr;?>").click(
							function(e)
							{
								e.preventDefault();
								var id = "<?php echo $row['report_access_id'];?>";
								var r = confirm("Confirm Delete");
								
								if(r == true)
								{
									 $.post( 
									 '../php/main.php',
									 {
										 assignreportdel:id
										
									},
									 function(data) {
										$('#modreportlist').html(data);
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
}

?>