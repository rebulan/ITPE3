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

function change_payment_status($tran,$val)
{
	global $con;
	
	mysqli_query($con,"Update order_transaction set status_remittance = $val where order_transaction_id = $tran");
}
function transaction_payment($tran)
{
	global $con;
	$string = "Select * from order_payment where transaction_id = $tran and isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
		?>
			<table class = "table table-bordered table-hover table-sm" id = "paymenttable">
								<thead>
									<th>#</th>
									<th>AMOUNT</th>
									<th>BANK</th>
									<th>REMITTANCE CENTER</th>
									<th>PAYMENT DATE</th>
									<th>
									
									</th>
								</thead>
					<?php
						$ctr = 1;
						while($row = mysqli_fetch_assoc($query))
						{
							?>
								<tr>
									<td><?php echo $ctr;?></td>
									<td><?php echo number_format($row['amount'],2);?></td>
									<td><?php
										$bankd = mysqli_fetch_assoc(mysqli_query($con, "Select bank_name from lup_bank where bank_id = $row[bank_id]"));
										echo $bankd['bank_name'];
									?></td>
									<td><?php
										$remd = mysqli_fetch_assoc(mysqli_query($con, "Select remittance_center_name from lup_remittance_center where remittance_center_id = $row[remittance_center_id]"));
										echo $remd['remittance_center_name'];
									?></td>
									<td><?php echo $row['date_added'];?></td>
									<td>
									<a href = "" id = "edit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
									<a href = "" id = "del<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
									</td>
								</tr>
								<SCRIPT>
									$("#del<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										var r = confirm("Confirm Delete");
										
										if(r == true)
										{
													$.post( 
																	'../php/finance.php',
																	{
																		deletedpayment:'<?php echo $row["order_payment_id"];?>'
																		
																	},
																	function(data) {
																		$('#paymentdetailsui').html(data);
																		
																	});
										}
									}
								);
								
									$("#edit<?php echo $ctr;?>").click(
									function(e)
									{
													e.preventDefault();
										
													$.post( 
																	'../php/finance.php',
																	{
																		editdpayment:'<?php echo $row["order_payment_id"];?>'
																		
																	},
																	function(data) {
																		$('#addpaymentui').html(data);
																		
																	});
										
									}
								);
								
								</SCRIPT>
							<?php
							$ctr++;
						}
					?>
			</table>
			<script>
			$("#document").ready(
				function()
				{
						
					$('#paymenttable').DataTable({
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
function customerproductsale($dfrom,$dto,$customerid)
{
	global $con;
	$date = "";
	if($dfrom != '')
	{
		$date = "and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	$string = "Select * from lup_product where isdeleted = 0";
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>ITEM</th>
									<th>QUANTITY</th>
									<th>TOTAL</th>
									
								</thead>
			<?php
				$ctr = 1;
				$qtotal = 0;
				$gtotal = 0;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['product_description'];?></td>
							<td><?php
								$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product where
								
								order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and order_transaction.customer_id = $customerid
								".$date."
								and lup_product.product_id = $row[product_id]
								"));
								
								echo number_format($qty['qty']);
								$qtotal = $qtotal + $qty['qty'];
							?></td>
							<td><?php
							
							$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity*order_transaction_detail.product_price) as total from order_transaction,order_transaction_detail,lup_product where
								order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction_detail.isdeleted = 0
								and order_transaction.customer_id = $customerid
								".$date."
								and order_transaction_detail.product_id = lup_product.product_id
	
								and lup_product.product_id = $row[product_id]"));
								$gtotal = $gtotal + $total['total'];
								echo number_format($total['total'],2);
							?></td>
						</tr>
					<?php
					$ctr++;
				}
			?>
		</table>
		
			<table class = "table table-bordered table-hover table-sm">
				<tr>
								
									<th>TOTAL QUANTITY: <?php echo number_format($qtotal,2);?></th>
									<th>TOTAL AMOUNT: <?php echo number_format($gtotal,2);?></th>
									
				</tr>
			</table>
		
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

function customer_transaction($dfrom, $dto, $customerid)
{

	global $con;

		$due = date_create($dfrom);
		$datefrom = date_format($due,"Y-m-d");
		
		$dueto = date_create($dto);
		$dateto = date_format($dueto,"Y-m-d");
		
		//echo $status." ".$app." aa";
	$string = "Select * from order_transaction where isdeleted = 0 and status_return = ''
	and status_collection = 1";
	
	if($dfrom != "" && $dto != "")
	{
		$string = $string." and (STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	if($customerid != "")
	{
		$string = $string." and customer_id = $customerid";
	}


	//echo $string;
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-striped table-hover table-xs" id = "cstable">
								<thead>
									<th>#</th>
									<th>TRANSACTION NO</th>
									<th>SALES AGENT</th>
									<th>CUSTOMER NO</th>
									<th>REMITTANCE</th>
									<th>DATE CREATE</th>
									<th>TOTAL QTY</th>
									<th>TOTAL</th>
									<th>PAYMENT STATUS</th>
									<th>APPROVAL</th>
								</thead>
		<?PHP
			$ctr = 1;
			$gtotal = 0;
			$totalqty = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$gtotal = $gtotal + $row['total_amount'];
				$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_no from customer_profile where customer_id = $row[customer_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						
						<td><?php echo $row['order_transaction_no'];?></td>
						<td><?php echo get_agent($row['sales_agent_id']);?></td>
						<td><?php echo $cus['customer_no'];?></td>
						<td><?php
							if(!empty($row['bank_id']))
							{
								$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_bank where bank_id = $row[bank_id]"));
								
								echo $bank['bank_name']."-".$row['reference_payment'];
							}
							else if(!empty($row['remittance_center_id']))
							{
								$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_remittance_center where remittance_center_id = $row[remittance_center_id]"));
								
								echo $bank['remittance_center_name']."-".$row['reference_payment'];
							}
							else
							{
								echo "REFERRAL NO:".$row['reference_payment'];
							}
						?></td>
						<td><?php echo $row['order_transaction_date'];?></td>
							<td><?php
							$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_quantity) as total from order_transaction_detail
							where order_transaction_id = $row[order_transaction_id] and isdeleted = 0 and isshipping = 0"));
							echo number_format($qty['total'],2);
							
							$totalqty = $totalqty + $qty['total'];
							
						?></td>
						<td><?php echo $row['total_amount'];?></td>
						<td><?php if($row['status_remittance'] == 1)
									echo "REMITTED";
									elseif($row['status_remittance'] == 2)
									echo "RECEIVED";
									elseif($row['status_remittance'] == 3)
									echo "DENIED";
							
							echo "<br>".$row['datetime_remittance_updated'];
						?></td>
						
						<td><?php 	if($row['status_collection'] == 1)
									echo "APPROVED";
									else if($row['status_collection'] == 2)
									echo "DENIED";
							echo "<br>".$row['datetime_collection_updated'];		
						?></td>
						
					</tr>
					
				<?php
				$ctr++;
			}
			?>
		</table>
		<table class = "table table-striped table-hover table-sm">
				<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<th>TOTAL QUANTITY: <?php echo number_format($totalqty,2);?></th>
									<th>TOTAL AMOUNT: <?php echo number_format($gtotal,2);?></th>
									<td></td>
									<td></td>
									<td></td>
				</tr>
			</table>
		<script>
			$("#document").ready(
				function()
				{
						
					$('#cstable').DataTable({
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

function careof($field,$hidden)
{
	global $con;
	$string = "Select order_transaction.*,se_user.fullname from order_transaction,lup_payment_method,se_user where 
	order_transaction.isdeleted = 0 and order_transaction.payment_method_id = lup_payment_method.payment_method_id
	and lup_payment_method.is_care_of = 1 and lup_payment_method.isdeleted = 0
	and order_transaction.sales_agent_id = se_user.user_id
	and order_transaction.status_remittance = 2
	and order_transaction.status_collection = 1";
	
	$query = mysqli_query($con,$string);
	
	//modlog("Select * from lup_streettb where isdeleted = 0",1);
	?>
		<table class = "table table-bordered table-sm" id = "producttable">
						<thead>	
							<th></th>
							<th>SALES AGENT</th>
							<th>TRANSACTION NUMBER</th>
							<th>DATE</th>
							<th></th>
						</thead>
		<?PHP
		$ctr = 1;
		while($row = mysqli_fetch_assoc($query))
		{
			$check = mysqli_num_rows(mysqli_query($con,"Select * from deposit_careof where transaction_id = $row[order_transaction_id]
			and isdeleted = 0"));
			
			if($check == 0)
			{
			?>
				<tr>
					<input type = "hidden" id = "fieldrow<?php echo $ctr;?>" value = "<?php echo $row['order_transaction_no'];?>">
					<input type = "hidden" id = "hiddenrow<?php echo $ctr;?>" value = "<?php echo $row['order_transaction_id'];?>">
					
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['fullname'];?></td>
					<td><?php echo $row['order_transaction_no'];?></td>
					<td><?php echo $row['order_transaction_date'];?></td>
					<td>
							<a href = "" id = "selectp<?php echo $ctr;?>" class = "dbtn btn-primary btn-xs btn-flat">SELECT</a>
					</td>
				</tr>
				<script>

												$("#selectp<?php echo $ctr;?>").click(
													function(e)
													{
														e.preventDefault();
														$("#<?php echo $field;?>").val($("#fieldrow<?php echo $ctr;?>").val());
														$("#<?php echo $hidden;?>").val($("#hiddenrow<?php echo $ctr;?>").val());
														$("#modal").modal('hide');
													}
												);
									
				
				</script>
			<?php
			$ctr++;
			}
		}
		?>
		</table>
											<script>
												$("#document").ready(
													function()
													{
															
														$('#producttable').DataTable({
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

function remitlist($print)
{
	global $con;
	
	$string = "Select * from lup_remittance_center where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "remittable">
								<thead>
									<th>#</th>
									<th>REMITTANCE CENTER CODE</th>
									<th>REMITTANCE CENTER NAME</th>
									<th>DIRECT DEPOSIT</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['remittance_center_code'];?></td>
							<td><?php echo $row['remittance_center_name'];?></td>
							<td><?php
								if($row['direct_deposit'] == 1)
									echo "YES";
								else
									echo "NO";
							?></td>
							<td>
						
								<a href = "" id = "redit<?php echo $ctr;?>" class = "dbtn btn-success btn-sm btn-xs btn-flat">EDIT</a>
								<a href = "" id = "rdel<?php echo $ctr;?>" class = "dbtn btn-danger btn-sm btn-xs btn-flat">DELETE</a>
						
							</td>
							
							<script>
								$("#redit<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
											$("#modal2").modal("show");
											$("#modalbody2").css("max-width","65%");
											
											//$("#streetform").html(loading);
											$("#modalui2").html(loading);
											$.post( 
												'../php/main.php',
												{
													remitedit:<?php echo $row['remittance_center_id'];?>
													
												},
												function(data) {
													$('#modalui2').html(data);		
												});
										
									}
								);
								
								$("#rdel<?php echo $ctr;?>").click(
									function(e)
									{
										e.preventDefault();
										
										var r = confirm("Confirm Delete!");
										
										if(r == true)
										{
											$('#banklist').html(loading);
											$.post( 
												'../php/main.php',
												{
													remitdel:<?php echo $row['remittance_center_id'];?>
												},
												function(data) {
													$('#remitlist').html(data);		
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
						
					$('#remittable').DataTable({
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

function banklist($print)
{
	global $con;
	
	$string = "Select * from lup_bank where isdeleted = 0";
	
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "banktable">
								<thead>
									<th>#</th>
									<th>BANK CODE</th>
									<th>BANK NAME</th>
									<th>DIRECT DEPOSIT</th>
									<th></th>
								</thead>
				<?php
				$ctr = 1;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['bank_code'];?></td>
							<td><?php echo $row['bank_name'];?></td>
							<td><?php
								if($row['bank_deposit'] == 1)
									echo "YES";
								else
									echo "NO";
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
												'../php/main.php',
												{
													bankedit:<?php echo $row['bank_id'];?>
													
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
												'../php/main.php',
												{
													bankdel:<?php echo $row['bank_id'];?>
												},
												function(data) {
													$('#banklist').html(data);		
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
function bank_deposits($dfrom,$dto,$print,$group)
{
	global $con;
		$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}
	
	$bquery = mysqli_query($con,"Select DISTINCT(lup_bank.bank_name), lup_bank.bank_id from lup_bank, order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	lup_bank.bank_id = order_transaction.bank_id
	and lup_bank.bank_deposit = 1
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
	");
	
	$rquery = mysqli_query($con,"Select DISTINCT(lup_remittance_center.remittance_center_name), lup_remittance_center.remittance_center_id from lup_remittance_center, order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	lup_remittance_center.remittance_center_id = order_transaction.remittance_center_id
	and lup_remittance_center.direct_deposit = 1
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
	");
	

	

	
	$tquery = mysqli_query($con,"Select * from lup_product where isdeleted = 0 and isshipping = 0");

	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>BANK(DEPOSITS)</th>
									<th style = "text-align:right;">TOTAL TRANSACTIONS</th>
									<?php
									$tctr = 0;
									$bpro[] = "";
									$ptotal[] = 0;
									
									while($trow = mysqli_fetch_assoc($tquery))
									{
										$ptotal[$tctr] = 0;
										$bpro[$tctr] = $trow['product_id'];
										?>
											<th style = "text-align:right;"><?php echo $trow['product_description'];?></th>
										<?php
										$tctr++;
									}
									?>
									<th style = "text-align:right;">TOTAL AMOUNT</th>
								</thead>
		<?PHP
		$ctr = 1;
		$gtotal = 0;
		$gtotaltran = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
					
					$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
					
				$gtotal = $gtotal + $total['total'];
				
				$gtotaltran = $gtotaltran + $totaltran;
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $brow['bank_name'];?></td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr = 0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_remittance = 2
							
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = $brow[bank_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
						$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		while($rrow = mysqli_fetch_assoc($rquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
					
					$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and						order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
					
				$gtotal = $gtotal + $total['total'];
				
				$gtotaltran = $gtotaltran + $totaltran;
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $rrow['remittance_center_name'];?></td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr = 0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_remittance = 2
							
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.remittance_center_id = $rrow[remittance_center_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
						$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		?>
			<tr>
					<td></td>
					<th>GRAND TOTAL:</th>
					<th style = "text-align:right;"><?php echo number_format($gtotaltran,2);?></th>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
						?>
						<th style = "text-align:right;"><?php echo number_format($ptotal[$bctr],2);?></th>
						
						<?php
						$bctr++;
					}
					?>
					
					<th style = "text-align:right;"><?php echo number_format($gtotal,2);?></th>
				</tr>
		</table>
			
		<?php
		return $gtotal;
}

function total_deposit($dfrom,$dto,$group)
{
	global $con;
	
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select sum(order_transaction.total_amount) as total from lup_bank, order_transaction,
customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and		
	lup_bank.bank_id = order_transaction.bank_id
	and lup_bank.bank_deposit = 1
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
	"));
	
	$row2 = mysqli_fetch_assoc(mysqli_query($con,"Select sum(order_transaction.total_amount) as total from lup_remittance_center, order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	lup_remittance_center.remittance_center_id = order_transaction.remittance_center_id
	and lup_remittance_center.direct_deposit = 1
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
	"));
	
	
	return $row['total']+$row2['total'];
}
function otherdeposit_summary($dfrom,$dto,$group)
{
	global $con;
	$string = "Select DISTINCT(expense_description) as des from order_expense where isdeleted = 0
	and is_other_deposit = 1";
	
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
	
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>OTHER DEPOSIT DESCRIPTION</th>
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
							
							<td style = "text-align:right;">
							<?php 
								$exp = mysqli_fetch_assoc(mysqli_query($con,"Select sum(expense_amount) as total from order_expense where
								(STR_TO_DATE(expense_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(expense_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and expense_description ='$row[des]' and isdeleted = 0
								".$part."
								and is_other_deposit = 1;
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
									
									<th style = "text-align:right;">TOTAL OTHER DEPOSIT:</th>
									<th style = "text-align:right;" id = "totalexpenseui"><?php echo number_format($total,2);?></th>
								</tr>
		</table>						
									
	<?php
}

function expense_summary($dfrom,$dto,$group)
{
	//echo $group." aaaa";
	global $con;
	$string = "Select DISTINCT(expense_description) as des from order_expense where isdeleted = 0
	and is_other_deposit = 0";
	
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
							
							<td style = "text-align:right;">
							<?php 
								$exp = mysqli_fetch_assoc(mysqli_query($con,"Select sum(expense_amount) as total from order_expense where
								(STR_TO_DATE(expense_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(expense_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and expense_description ='$row[des]' and isdeleted = 0
								".$part."
								and is_other_deposit = 0;
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
									
									<th style = "text-align:right;">TOTAL EXPENSE:</th>
									<th style = "text-align:right;" id = "totalexpenseui"><?php echo number_format($total,2);?></th>
								</tr>
		</table>						
									
	<?php
}

function prev_collection($dfrom,$dto,$print,$group)
{
	global $con;
	
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}
	
	$bquery = mysqli_query($con,"Select DISTINCT(lup_bank.bank_name), lup_bank.bank_id from lup_bank, order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	lup_bank.bank_id = order_transaction.bank_id
	and (STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
	");

	
	$rquery = mysqli_query($con,"Select DISTINCT(lup_remittance_center.remittance_center_name), lup_remittance_center.remittance_center_id from lup_remittance_center, order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	lup_remittance_center.remittance_center_id = order_transaction.remittance_center_id
	and (STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
	");
	
	$cquery = mysqli_query($con,"Select DISTINCT(sales_agent_id),payment_method_id from order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and
					(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0");
	
	$tquery = mysqli_query($con,"Select * from lup_product where isdeleted = 0 and isshipping = 0");
	
	
	
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>BANK/REMITTANCE (PREVIOUS CLAIM)</th>
									<th style = "text-align:right;">TOTAL TRANSACTIONS</th>
									<?php
									$tctr = 0;
									$bpro[] = "";
									$ptotal[] = 0;
									
									while($trow = mysqli_fetch_assoc($tquery))
									{
										$ptotal[$tctr] = 0;
										$bpro[$tctr] = $trow['product_id'];
										?>
											<th style = "text-align:right;"><?php echo $trow['product_description'];?></th>
										<?php
										$tctr++;
									}
									?>
									<th style = "text-align:right;">TOTAL AMOUNT</th>
								</thead>
		<?PHP
			$ctr = 1;
		$gtotal = 0;
		$gtotaltran = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
					
					$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
					
				$gtotal = $gtotal + $total['total'];
				
				$gtotaltran = $gtotaltran + $totaltran;
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $brow['bank_name'];?></td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr = 0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
						STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
						and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
								and order_transaction.status_remittance = 2
							
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = $brow[bank_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
						$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		while($rrow = mysqli_fetch_assoc($rquery))
		{
			$user = get_user_id($_SESSION['useraccount']);
			$agent = get_agent($user);

			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
			customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
			order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
			customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
			order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
					
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $rrow['remittance_center_name'];?></td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
								and order_transaction.status_collection = 2
							
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.remittance_center_id = $rrow[remittance_center_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		while($crow = mysqli_fetch_assoc($cquery))
		{
			$user = get_user_id($_SESSION['useraccount']);
			$agent = get_agent($user);
			
			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and				
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
					
					
			$method = mysqli_fetch_assoc(mysqli_query($con,"Select payment_method_name from lup_payment_method where payment_method_id = $crow[payment_method_id]"));
			
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $agent." (".$method['payment_method_name'];?>)</td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
							STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
							and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
								and order_transaction.status_collection = 1
								and order_transaction.status_remittance = 2
								
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 
								and order_transaction.sales_agent_id = $crow[sales_agent_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		?>
			<tr>
					<td></td>
					<th>GRAND TOTAL:</th>
					<th style = "text-align:right;"><?php echo number_format($gtotaltran,2);?></th>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
						?>
						<th style = "text-align:right;"><?php echo number_format($ptotal[$bctr],2);?></th>
						
						<?php
						$bctr++;
					}
					?>
					
					<th style = "text-align:right;"><?php echo number_format($gtotal,2);?></th>
				</tr>
		</table>
			
		<?php
		return $gtotal;
}

function total_otherdeposit($dfrom,$dto,$group)
{
	global $con;
	$string = "Select sum(expense_amount) as total from order_expense where isdeleted = 0
	and is_other_deposit = 1";
	
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
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>DESCRIPTION</th>
									<th>ENTRY TYPE</th>
									<th>CUSTOMER TYPE GROUP</th>
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
						?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['expense_description'];?></td>
							<td>
								<?php 
								if($row['is_other_deposit'] == 1)
									echo "OTHER DEPOSIT";
								else
									echo "EXPENSE";
								?>
							</td>
							<td>
								<?php
									$tgroup = mysqli_fetch_assoc(mysqli_query($con,"Select customer_type_group_name from lup_customer_type_group
									where customer_type_group_id = $row[customer_type_group_id]"));
									
									if(!empty($tgroup))
										echo $tgroup['customer_type_group_name'];
									else
										echo "na";
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
									<td><button class = "btn btn-success btn-flat btn-sm" id = "update<?php echo $ctr;?>">UPDATE</button>
									<button class = "btn btn-danger btn-flat btn-sm" id = "delete<?php echo $ctr;?>">DELETE</button>
									</td>
									<?php
									}
									?>
						</tr>
							<script>
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
																		'../php/finance.php',
																		{
																			examountsave:examount,
																			examountid:<?php echo $row['expense_id'];?>,
																			examountdfrom:'<?php echo $dfrom;?>',
																			examountdto:'<?php echo $dto;?>'
																			
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
																		'../php/finance.php',
																		{
																			deleteexpense:"<?php echo $row['expense_id'];?>",
																			deleteexpensedfrom:'<?php echo $dfrom;?>',
																			deleteexpensedto:'<?php echo $dto;?>'
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
function shipping_status_summary($dfrom,$dto,$print)
{
	global $con;
	$total = 0;
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>STATUS</th>
									<th style = "text-align:right;">QUANTITY</th>
								</thead>
								<tr>
									<td>1</td>
									<td>PENDING</td>
									<td style = "text-align:right;"><?php
									
										$status = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction where status_shipping = 1 and isdeleted = 0
										and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))"));
										
										echo $status;
										
										$total = $total + $status;
									?></td>
								</tr>
								<tr>
									<td>2</td>
									<td>IN-PROCESS</td>
									<td style = "text-align:right;"><?php
									
										$status = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction where status_shipping = 2 and isdeleted = 0
										and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))"));
										
										echo $status;
										$total = $total + $status;
									?></td>
								</tr>
								<tr>
									<td>3</td>
									<td>SHIPPED</td>
									<td style = "text-align:right;"><?php
									
										$status = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction where status_shipping = 3 and isdeleted = 0
										and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))"));
										
										echo $status;
										$total = $total + $status;
									?></td>
								</tr>
								<tr>
									<td>4</td>
									<td>ORDER RECEIVED</td>
									<td style = "text-align:right;"><?php
									
										$status = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction where status_shipping = 4 and isdeleted = 0
										and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))"));
										
										echo $status;
										$total = $total + $status;
									?></td>
								</tr>
								<tr>
									<td></td>
									<th>TOTAL SHIPMENT</th>
									<th style = "text-align:right;"><?php
									
										echo $total;
									?></th>
								</tr>
		</TABLE>
	<?php
}
function returnlist($dfrom,$dto,$print)
{
	global $con;
	
	$string = "Select order_transaction_detail.* from order_transaction_detail, order_transaction where 
	order_transaction.isdeleted = 0 and order_transaction.status_return != ''
	and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
	and order_transaction_detail.isdeleted = 0
	and order_transaction_detail.isshipping = 0";
	
	
	if($dfrom != '')
	{
		$string = $string." and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>PRODUCT</th>
									<th>ORDER #</th>
									<th style = "text-align:right;">QUANTITY</th>
								</thead>
				<?php
				$ctr = 1;
				$total = 0;
				while($row = mysqli_fetch_assoc($query))
				{
					$pro = mysqli_fetch_assoc(mysqli_query($con,"Select product_description from lup_product where product_id = $row[product_id]"));
				?>
						<tr>
							<td><?PHP ECHO $ctr;?></td>
							<td><?php echo $pro['product_description'];?></td>
							<td><?php echo $row['order_transaction_no'];?></td>
							<td style = "text-align:right;"><?php echo $row['order_quantity'];?></td>
						</tr>
				<?php
					$total = $total + $row['order_quantity'];
					$ctr++;
				}
				?>
		</table>
		<table class = "table table-bordered table-hover table-sm">
			<tr>
				<th style = "text-align:right;">TOTAL QUANTITY:</th>
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
function total_shippingexpense($dfrom,$dto)
{
	global $con;
	$bquery = mysqli_query($con,"Select * from lup_courier where isdeleted = 0");
	
			$ctr = 1;
			$etotal = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
	
								$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.total_order_amount) as total from order_transaction,order_transaction_detail,lup_product where 
								order_transaction.courier_id = $brow[courier_id] and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.isshipping = 1"));
								
								//echo number_format($qty['total'],2);
								$etotal = $etotal + $qty['total'];
								//$qtotal = $qtotal + $qty['qty'];
	
			$ctr++;
		}	
		return $etotal;
}

function prev_claim($dfrom,$dto,$group)
{
	global $con;
	
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select sum(total_amount) as total from order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and		
	(STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.datetime_remittance_updated,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') < STR_TO_DATE('$dfrom','%Y-%m-%d')
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0"));

	return $row['total'];
}

function total_sales($dfrom,$dto,$group)
{
	global $con;
	
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select sum(total_amount) as total from order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and										
	(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0"));

	return $row['total'];
}
function total_daily_collection($dfrom,$dto)
{
	global $con;
	$bquery = mysqli_query($con,"Select * from lup_bank where isdeleted = 0");
	
	$rquery = mysqli_query($con,"Select * from lup_remittance_center where isdeleted = 0");
	
	$cquery = mysqli_query($con,"Select DISTINCT(sales_agent_id),payment_method_id from order_transaction where order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0");
	
	$tquery = mysqli_query($con,"Select * from lup_product where isdeleted = 0 and isshipping = 0");
	
	?>

									<?php
									$tctr = 0;
									$bpro[] = "";
									$ptotal[] = 0;
									
									while($trow = mysqli_fetch_assoc($tquery))
									{
										$ptotal[$tctr] = 0;
										$bpro[$tctr] = $trow['product_id'];
										$tctr++;
									}
									?>
								
		<?PHP
			$ctr = 1;
		$gtotal = 0;
		$gtotaltran = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
					
					$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction where order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
					
				$gtotal = $gtotal + $total['total'];
				
				$gtotaltran = $gtotaltran + $totaltran;
				$bctr = 0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product where
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								
								and order_transaction.bank_id = $brow[bank_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
								
								$bctr++;
					}
			$ctr++;
		}
		
		while($rrow = mysqli_fetch_assoc($rquery))
		{
			

			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction where order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
					
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
	
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product where
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								
								and order_transaction.remittance_center_id = $rrow[remittance_center_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						
						$bctr++;
					}
			$ctr++;
		}
		
		while($crow = mysqli_fetch_assoc($cquery))
		{
			$user = get_user_id($_SESSION['useraccount']);
			$agent = get_agent($user);
			
			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where 
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction where 
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
					
					
			//$method = mysqli_fetch_assoc(mysqli_query($con,"Select payment_method_name from lup_payment_method where payment_method_id = $crow[payment_method_id]"));
			
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
		
			
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product where
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.status_remittance = 2
							
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 
								and order_transaction.sales_agent_id = $crow[sales_agent_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						
						$bctr++;
					}
					
					
					
			$ctr++;
		}
		return $gtotal;
}

function total_previous_claimed($dfrom,$dto,$group)
{
	global $con;
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}

	$bquery = mysqli_query($con,"Select * from lup_bank where isdeleted = 0");
	
	$rquery = mysqli_query($con,"Select * from lup_remittance_center where isdeleted = 0");
	
	$cquery = mysqli_query($con,"Select DISTINCT(sales_agent_id),payment_method_id from order_transaction where order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0");
	
	$tquery = mysqli_query($con,"Select * from lup_product where isdeleted = 0 and isshipping = 0");
	
	?>

									<?php
									$tctr = 0;
									$bpro[] = "";
									$ptotal[] = 0;
									
									while($trow = mysqli_fetch_assoc($tquery))
									{
										$ptotal[$tctr] = 0;
										$bpro[$tctr] = $trow['product_id'];
										$tctr++;
									}
									?>
								
		<?PHP
			$ctr = 1;
		$gtotal = 0;
		$gtotaltran = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
				
					and order_transaction.isdeleted = 0"));
					
					$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					
					and order_transaction.isdeleted = 0"));
					
				$gtotal = $gtotal + $total['total'];
				
				$gtotaltran = $gtotaltran + $totaltran;
				$bctr = 0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_remittance = 1
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								
								and order_transaction.bank_id = $brow[bank_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
								
								$bctr++;
					}
			$ctr++;
		}
		
		while($rrow = mysqli_fetch_assoc($rquery))
		{
			

			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
			customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
			order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
			customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
			order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					
					and order_transaction.isdeleted = 0"));
					
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
	
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_remittance = 1
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								
								and order_transaction.remittance_center_id = $rrow[remittance_center_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						
						$bctr++;
					}
			$ctr++;
		}
		
		while($crow = mysqli_fetch_assoc($cquery))
		{
			$user = get_user_id($_SESSION['useraccount']);
			$agent = get_agent($user);
			
			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
			customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and				
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					
					and order_transaction.isdeleted = 0"));
					
					
			//$method = mysqli_fetch_assoc(mysqli_query($con,"Select payment_method_name from lup_payment_method where payment_method_id = $crow[payment_method_id]"));
			
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
		
			
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.status_remittance = 1
							
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 
								and order_transaction.sales_agent_id = $crow[sales_agent_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						
						$bctr++;
					}
					
					
					
			$ctr++;
		}
		return $gtotal;
}

function productinoutsummary($dfrom,$dto,$print)
{
	global $con;
	
	
	$string = "Select * from lup_product where isdeleted = 0 and isshipping = 0";
	
	/*$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}*/
	
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>ITEM</th>
									
									<th style = "text-align:right;">IN</th>
									<th style = "text-align:right;">OUT</th>
									<th style = "text-align:right;">END</th>
								</thead>
			<?php
				$ctr = 1;
				$qtotal = 0;
				$gtotal = 0;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['product_description'];?></td>
							<td style = "text-align:right;"><?php
								$in = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as total from inv_transaction where item_id = $row[product_id] and 
								(STR_TO_DATE(transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and isdeleted = 0 and quantity > 0"));
								
								echo number_format($in['total'],2);
								
							?></td>
							<td style = "text-align:right;"><?php
							
							$out = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(quantity) as total from inv_transaction where item_id = $row[product_id] and
								(STR_TO_DATE(transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and isdeleted = 0 and quantity < 0"));
							
							$tout = $out['total']*-1;
								
								echo number_format($tout,2);
							?></td>
							<td style = "text-align:right;"><?php echo number_format($in['total']-$tout,2);?></td>
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

function previous_claimed($dfrom,$dto,$print,$group)
{
	global $con;
	
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}
	
	
	$bquery = mysqli_query($con,"Select DISTINCT(lup_bank.bank_name), lup_bank.bank_id from lup_bank, order_transaction,
customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and		
	lup_bank.bank_id = order_transaction.bank_id
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0
	");

	
	$rquery = mysqli_query($con,"Select DISTINCT(lup_remittance_center.remittance_center_name), lup_remittance_center.remittance_center_id from lup_remittance_center, order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
									
	lup_remittance_center.remittance_center_id = order_transaction.remittance_center_id
	
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0
	");
	
	$cquery = mysqli_query($con,"Select DISTINCT(sales_agent_id),payment_method_id from order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0");
	
	$tquery = mysqli_query($con,"Select * from lup_product where isdeleted = 0 and isshipping = 0");
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>BANK/REMITTANCE (UNCLAIMS)</th>
									<th style = "text-align:right;">TOTAL TRANSACTIONS</th>
									<?php
									$tctr = 0;
									$bpro[] = "";
									$ptotal[] = 0;
									
									while($trow = mysqli_fetch_assoc($tquery))
									{
										$ptotal[$tctr] = 0;
										$bpro[$tctr] = $trow['product_id'];
										?>
											<th style = "text-align:right;"><?php echo $trow['product_description'];?></th>
										<?php
										$tctr++;
									}
									?>
									<th style = "text-align:right;">TOTAL AMOUNT</th>
								</thead>
		<?PHP
			$ctr = 1;
		$gtotal = 0;
		$gtotaltran = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0"));
					
					$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0"));
					
				$gtotal = $gtotal + $total['total'];
				
				$gtotaltran = $gtotaltran + $totaltran;
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $brow['bank_name'];?></td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr = 0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_remittance = 1
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = $brow[bank_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
						$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		while($rrow = mysqli_fetch_assoc($rquery))
		{
			$user = get_user_id($_SESSION['useraccount']);
			$agent = get_agent($user);

			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
			customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
			order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
			customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
			order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0"));
					
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $rrow['remittance_center_name'];?></td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_remittance = 1
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.remittance_center_id = $rrow[remittance_center_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		while($crow = mysqli_fetch_assoc($cquery))
		{
			$user = get_user_id($_SESSION['useraccount']);
			$agent = get_agent($user);
			
			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
									
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
				
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
									
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					
					and order_transaction.isdeleted = 0"));
					
					
			$method = mysqli_fetch_assoc(mysqli_query($con,"Select payment_method_name from lup_payment_method where payment_method_id = $crow[payment_method_id]"));
			
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $agent." (".$method['payment_method_name'];?>)</td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_remittance = 1
								and order_transaction.status_collection = 1
								
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 
								and order_transaction.sales_agent_id = $crow[sales_agent_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		?>
			<tr>
					<td></td>
					<th>GRAND TOTAL:</th>
					<th style = "text-align:right;"><?php echo number_format($gtotaltran,2);?></th>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
						?>
						<th style = "text-align:right;"><?php echo number_format($ptotal[$bctr],2);?></th>
						
						<?php
						$bctr++;
					}
					?>
					
					<th style = "text-align:right;"><?php echo number_format($gtotal,2);?></th>
				</tr>
		</table>
			
		<?php
		return $gtotal;
}

function daily_collection($dfrom,$dto,$print,$group)
{
	global $con;
	
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}
	
	$bquery = mysqli_query($con,"Select DISTINCT(lup_bank.bank_name), lup_bank.bank_id from lup_bank, order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	lup_bank.bank_id = order_transaction.bank_id
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
	");

	
	$rquery = mysqli_query($con,"Select DISTINCT(lup_remittance_center.remittance_center_name), lup_remittance_center.remittance_center_id from lup_remittance_center, order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	lup_remittance_center.remittance_center_id = order_transaction.remittance_center_id
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
	");
	
	//$rquery = mysqli_query($con,"Select * from lup_remittance_center where isdeleted = 0");
	
	$cquery = mysqli_query($con,"Select DISTINCT(sales_agent_id),payment_method_id from order_transaction,
	customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and	
	order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0");
	
	$tquery = mysqli_query($con,"Select * from lup_product where isdeleted = 0 and isshipping = 0");
	

	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>BANK/REMITTANCE (CLAIMED)</th>
									<th style = "text-align:right;">TOTAL TRANSACTIONS</th>
									<?php
									$tctr = 0;
									$bpro[] = "";
									$ptotal[] = 0;
									
									while($trow = mysqli_fetch_assoc($tquery))
									{
										$ptotal[$tctr] = 0;
										$bpro[$tctr] = $trow['product_id'];
										?>
											<th style = "text-align:right;"><?php echo $trow['product_description'];?></th>
										<?php
										$tctr++;
									}
									?>
									<th style = "text-align:right;">TOTAL COLLECTION</th>
									<th style = "text-align:right;">TOTAL PAYMENT</th>
								</thead>
		<?PHP
			$ctr = 1;
		$gtotal = 0;
		$gtotaltran = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0"));
					
					$totalp = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_payment.amount) as total from order_transaction,
					customer_profile, lup_customer_type, order_payment
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0
					and order_transaction.order_transaction_id = order_payment.transaction_id
					and order_payment.isdeleted = 0"));
					
					
					
					$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
					order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0"));
					
				$gtotal = $gtotal + $total['total'];
				
				$gtotaltran = $gtotaltran + $totaltran;
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $brow['bank_name'];?></td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr = 0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product where
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_remittance = 2
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = $brow[bank_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
						$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
					<td style = "text-align:right;"><?php echo number_format($totalp['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		while($rrow = mysqli_fetch_assoc($rquery))
		{
			$user = get_user_id($_SESSION['useraccount']);
			$agent = get_agent($user);

			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
					order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction, 
			customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
			order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
					
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $rrow['remittance_center_name'];?></td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.remittance_center_id = $rrow[remittance_center_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		while($crow = mysqli_fetch_assoc($cquery))
		{
			$user = get_user_id($_SESSION['useraccount']);
			$agent = get_agent($user);
			
			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
				
					and order_transaction.isdeleted = 0"));
			
			$totaltran = mysqli_num_rows(mysqli_query($con,"Select * from order_transaction,
					customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
					order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and sales_agent_id = $crow[sales_agent_id] and 
					payment_method_id = $crow[payment_method_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					
					and order_transaction.isdeleted = 0"));
					
					
			$method = mysqli_fetch_assoc(mysqli_query($con,"Select payment_method_name from lup_payment_method where payment_method_id = $crow[payment_method_id]"));
			
			$gtotal = $gtotal + $total['total'];
			$gtotaltran = $gtotaltran + $totaltran;	
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $agent." (".$method['payment_method_name'];?>)</td>
					<td style = "text-align:right;"><?php echo number_format($totaltran,2);?></td>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
								$pqty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product,
								customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.status_remittance = 2
								
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 
								and order_transaction.sales_agent_id = $crow[sales_agent_id]
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.product_id = $bpro[$bctr]"));
								$ptotal[$bctr] = $ptotal[$bctr] +  $pqty['qty'];
						?>
						<td style = "text-align:right;"><?php echo number_format($pqty['qty'],2);?></td>
						<?php
						$bctr++;
					}
					?>
					
					<td style = "text-align:right;"><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		
		?>
			<tr>
					<td></td>
					<th>GRAND TOTAL:</th>
					<th style = "text-align:right;"><?php echo number_format($gtotaltran,2);?></th>
					<?php
					$bctr=0;
					while($bctr <= $tctr-1)
					{
						?>
						<th style = "text-align:right;"><?php echo number_format($ptotal[$bctr],2);?></th>
						
						<?php
						$bctr++;
					}
					?>
					
					<th style = "text-align:right;"><?php echo number_format($gtotal,2);?></th>
				</tr>
		</table>
			
		<?php
		return $gtotal;
}

function inventory_transaction($iproduct,$iunit,$itransaction,$iquantity,$iremarks,$icost)
{
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
			$user = get_user_id($_SESSION['useraccount']);
			
			$save = mysqli_query($con,"insert into inv_transaction set
			transaction_no = '$result',
			transaction_date = NOW(),
			transaction_type_id = $itransaction,
			item_id = $iproduct,
			unit_id = $iunit,
			unit_cost = $icost,
			quantity = $iquantity,
			remarks = '$iremarks',
			created_by = '$user',
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
				
				mysqli_query($con, "Update inv_transaction set transaction_no = '$tranno' where transaction_id = $row[transaction_id]");
				
				
		if($save)
			return 0;
		else
			return 1;
}
function customer_masterlist($region,$province,$citymun,$brgy,$print,$type,$inactive)
{
	

	global $con;
	$string = "Select * from customer_profile, customer_address where
	customer_profile.customer_id = customer_address.customer_id";
	
	$part = "";
	if($inactive == 1)
	{
		$part = " order_transaction_date <= date_sub(now(), interval 2 month)";
	}
	
	if($type != '' && $type != "0")
	{
		$string = $string." and customer_profile.customer_type = $type";
	}
	
	if($region != '' && $region != "0")
	{
		$string = $string." and customer_address.region_id = $region";
	}
	
	if($province != '' && $province != "0")
	{
		$string = $string." and customer_address.province_id = $province";
	}
	if($citymun != '' && $citymun != "0")
	{
		$string = $string." and customer_address.city_town_id = $citymun";
	}
	if($brgy != '' && $brgy != "0")
	{
		$string = $string." and customer_address.barangay_id = $brgy";
	}
	
	//echo $string;
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "cmastertable">
								<thead>
									<th>#</th>
									<th>CUSTOMER #</th>
									<th>FULLNAME</th>
									<th>FB NAME</th>
									<th>CONTACT #</th>
									<th>HOME ADDRESS</th>
									<th>TYPE</th>
									<th>LAST TRANSACTION</th>
									<th></th>
								</thead>
		<?php
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
					//$check = mysqli_fetch_assoc(mysqli_query($con,"Select order_transaction_date from order_transaction where customer_id = $row[customer_id]
					//and status_collection = 1 and STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= date_sub(now(), interval 2 month)"));
					
					$check = mysqli_fetch_assoc(mysqli_query($con,"Select order_transaction_date from order_transaction where customer_id = $row[customer_id]
					and status_collection = 1 and STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= date_sub(now(), interval 2 month)"));
					
					$add = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_region, lup_province, lup_city_town, lup_barangay
					where lup_region.region_id = $row[region_id] and lup_province.province_id = $row[province_id]
					and lup_city_town.city_town_id = $row[city_town_id] and lup_barangay.barangay_id = $row[barangay_id]"));
					
					$ty = mysqli_fetch_assoc(mysqli_query($con,"Select customer_type_name from lup_customer_type where customer_type_id = $row[customer_type]"));
					
				if($inactive == 1)
				{
					
					
					if(!empty($check))
					{
					?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['customer_no'];?></td>
						<td><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></td>
						<td><?php echo $row['social_media1'];?></td>
						<td><?php echo $row['contact_no1'];?></td>
						<td><?php echo $row['street_name']." ".$add['barangay_name']." ".$add['city_town_name']." ".$add['province_name']." ".$add['region_name'];?></td>
						<td><?php echo $ty['customer_type_name'];?></td>
						<td><?php echo $check['order_transaction_date'];?></td>
						<td>ACTIVE</td>
					</tr>
					<?php
					}
					
				}
				elseif($inactive == 2)
				{
					if(empty($check))
					{
						$check2 = mysqli_fetch_assoc(mysqli_query($con,"Select order_transaction_date from order_transaction where customer_id = $row[customer_id]
						order by order_transaction_date desc"));
					?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['customer_no'];?></td>
						<td><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></td>
						<td><?php echo $row['social_media1'];?></td>
						<td><?php echo $row['contact_no1'];?></td>
						<td><?php echo $row['street_name']." ".$add['barangay_name']." ".$add['city_town_name']." ".$add['province_name']." ".$add['region_name'];?></td>
						<td><?php echo $ty['customer_type_name'];?></td>
						<td><?php echo $check2['order_transaction_date'];?></td>
						<td>INACTIVE</td>
					</tr>
					<?php
					}
				}
				else
				{
					$check2 = mysqli_fetch_assoc(mysqli_query($con,"Select order_transaction_date from order_transaction where customer_id = $row[customer_id]
						order by order_transaction_date desc"));
					?>
						<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['customer_no'];?></td>
						<td><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></td>
						<td><?php echo $row['social_media1'];?></td>
						<td><?php echo $row['contact_no1'];?></td>
						<td><?php echo $row['street_name']." ".$add['barangay_name']." ".$add['city_town_name']." ".$add['province_name']." ".$add['region_name'];?></td>
						<td><?php echo $ty['customer_type_name'];?></td>
						<td><?php echo $check2['order_transaction_date'];?></td>
						<td>
							<?php
								if(!empty($check))
									echo "ACTIVE";
								else
									echo "INACTIVE";
							?>
						</td>
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
		<script>
			$("#document").ready(
				function()
				{
						
					$('#cmastertable').DataTable({
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
function salesummaryteam($dfrom,$dto,$print,$group)
{
	global $con;
	$query = mysqli_query($con,"Select * from lup_product where isdeleted = 0");
	
	//$tquery = mysqli_query($con,"Select * from lup_sales_team where isdeleted = 0");
	
	$tquery =mysqli_query($con,"Select DISTINCT(lup_sales_team.sales_team_name),lup_sales_team.sales_team_id from lup_sales_team, order_transaction  where lup_sales_team.isdeleted = 0
	and lup_sales_team.sales_team_id = order_transaction.team_id
	and (STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
	STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
	and order_transaction.status_collection = 1
	and order_transaction.isdeleted = 0");
	
	$part = "";
	if(!empty($group))
	{
		if($group != "BOTH")
		{
			$part = " and lup_customer_type.customer_type_group = $group";
		}
	}
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>PRODUCT</th>
								<?php
									$tctr = 0;
									$teamid[] = "";
									while($trow = mysqli_fetch_assoc($tquery))
									{
										$teamid[$tctr] = $trow['sales_team_id'];
										?>
											<th colspan = "2"><?php echo $trow['sales_team_name'];?></th>
										<?php
										$tctr++;
									}
								?>
									<th STYLE = "text-align:right;">TOTAL QUANTITY</th>
									<th STYLE = "text-align:right;">TOTAL SALES</th>
			
								</thead>
		<?PHP
			$ctr = 1;
			$gtotalamount = 0;
			$gtotalqty = 0;
			$tqty[] = 0;
			$ttotal[] = 0;
		while($row = mysqli_fetch_assoc($query))
		{
			
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $row['product_description'];?></td>
					<?php
						$cctr = 0;
						$totalqty = 0;
						$totalamount = 0;
						
						while($cctr <= $tctr-1)
						{
							if(!isset($tqty[$cctr]))
							{
								$tqty[$cctr] = 0;
							}
							
							if(!isset($ttotal[$cctr]))
							{
								$ttotal[$cctr] = 0;
							}
							?>
								<td STYLE = "text-align:right;">
								<?PHP
									$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from 
									order_transaction,order_transaction_detail,lup_product, 
									customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
									(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
									STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
									and order_transaction.status_collection = 1
									and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
									and order_transaction.isdeleted = 0
									and order_transaction_detail.isdeleted = 0
									and order_transaction_detail.product_id = $row[product_id]
									and order_transaction_detail.product_id = lup_product.product_id
									and order_transaction.team_id = $teamid[$cctr]"));
							
									echo number_format($qty['qty']);
									$tqty[$cctr] = $tqty[$cctr] + $qty['qty'];
									$totalqty = $totalqty + $qty['qty'];
									
								?>
								</td >
								<td STYLE = "text-align:right;">
								<?php
									$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity*order_transaction_detail.product_price) as total from 
									order_transaction,order_transaction_detail,lup_product,
									customer_profile, lup_customer_type
									where
									order_transaction.customer_id = customer_profile.customer_id
									and customer_profile.customer_type = lup_customer_type.customer_type_id
									".$part."
									and
									(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
									STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
									and order_transaction.status_collection = 1
									and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
									and order_transaction.isdeleted = 0
									and order_transaction_detail.isdeleted = 0
									and lup_product.product_id = $row[product_id]
									and order_transaction_detail.product_id = lup_product.product_id
									and order_transaction.team_id = $teamid[$cctr]
									"));
								
									echo number_format($total['total'],2);
									$ttotal[$cctr] = $ttotal[$cctr] + $total['total'];
									$totalamount = $totalamount + $total['total'];
								?>
								</td>
							<?php
							$cctr++;
						}
						$gtotalqty = $gtotalqty + $totalqty;
						$gtotalamount = $gtotalamount + $totalamount;
					?>
					<th STYLE = "text-align:right;"><?php echo number_format($totalqty,2);?></th>
					<th STYLE = "text-align:right;"><?php echo number_format($totalamount,2);?></th>
				
				</tr>
			<?php
			$ctr++;
		}
		?>
			<tr>
				<td></td>
				<td></td>
			<?php
					$cctr = 0;
						
						while($cctr <= $tctr-1)
						{
			?>
							<th STYLE = "text-align:right;"><?php echo number_format($tqty[$cctr]);?></th>
							<th STYLE = "text-align:right;"><?php echo number_format($ttotal[$cctr],2);?></th>
			<?php
						$cctr++;
						}
			?>
				<th STYLE = "text-align:right;"><?php echo number_format($gtotalqty,2);?></th>
				<th STYLE = "text-align:right;"><?php echo number_format($gtotalamount,2);?></th>
			</tr>
		</table>
		<?php
}

function unclaimed_care_of($dfrom,$dto,$print)
{
	global $con;
	$query = mysqli_query($con,"Select * from order_transaction where order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0");
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>AGENT NAME</th>
									<th>PAYMENT METHOD</th>
									<th>REMARKS</th>
									<th>TOTAL AMOUNT</th>
								</thead>
		<?PHP
			$ctr = 1;
			$gtotal = 0;
		while($row = mysqli_fetch_assoc($query))
		{
			$gtotal = $gtotal+$row['total_amount'];
			$user = mysqli_fetch_assoc(mysqli_query($con,"Select fullname from se_user where user_id = $row[sales_agent_id]"));
			$method = mysqli_fetch_assoc(mysqli_query($con,"Select payment_method_name from lup_payment_method where payment_method_id = $row[payment_method_id]"));
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $user['fullname'];?></td>
					<td><?php echo $method['payment_method_name'];?></td>
					<td><?php echo $row['remarks_order'];?></td>
					<td><?php echo number_format($row['total_amount'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>GRAND TOTAL</td>
				<th><?PHP ECHO number_format($gtotal,2);?></th>
			</tr>
		</table>
		<?php
}
function claimed_care_of($dfrom,$dto,$print)
{
	global $con;
	$query = mysqli_query($con,"Select * from order_transaction where order_transaction.bank_id = 0 and order_transaction.remittance_center_id = 0 and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0");
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>AGENT NAME</th>
									<th>PAYMENT METHOD</th>
									<th>REMARKS</th>
									<th>TOTAL AMOUNT</th>
								</thead>
		<?PHP
			$ctr = 1;
			$gtotal = 0;
		while($row = mysqli_fetch_assoc($query))
		{
			$gtotal = $gtotal+$row['total_amount'];
			$user = mysqli_fetch_assoc(mysqli_query($con,"Select fullname from se_user where user_id = $row[sales_agent_id]"));
			$method = mysqli_fetch_assoc(mysqli_query($con,"Select payment_method_name from lup_payment_method where payment_method_id = $row[payment_method_id]"));
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $user['fullname'];?></td>
					<td><?php echo $method['payment_method_name'];?></td>
					<td><?php echo $row['remarks_order'];?></td>
					<td><?php echo number_format($row['total_amount'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>GRAND TOTAL</td>
				<th><?PHP ECHO number_format($gtotal,2);?></th>
			</tr>
		</table>
		<?php
}
function shippingexpense($dfrom,$dto,$print)
{
	global $con;
	$bquery = mysqli_query($con,"Select * from lup_courier where isdeleted = 0");
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>COURIER NAME</th>
									<th style = "text-align:right;">TOTAL AMOUNT</th>
								</thead>
		<?PHP
			$ctr = 1;
			$etotal = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $brow['courier_name'];?></td>
					<td style = "text-align:right;">
					<?php
								$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.total_order_amount) as total from order_transaction,order_transaction_detail,lup_product where 
								order_transaction.courier_id = $brow[courier_id] and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.isshipping = 1"));
								
								echo number_format($qty['total'],2);
								$etotal = $etotal + $qty['total'];
								//$qtotal = $qtotal + $qty['qty'];
					?>
					</td>
				</tr>
			<?php
			$ctr++;
		}
		?>
			<tr>
				
				<td></td>
				<th>GRAND TOTAL</th>
				<th style = "text-align:right;"><?PHP ECHO number_format($etotal,2);?></th>
			</tr>
		</table>
		<?php
		return $etotal;
}

function unclaimedeposit($dfrom,$dto,$print)
{
	global $con;
	$bquery = mysqli_query($con,"Select * from lup_bank where isdeleted = 0");
	$rquery = mysqli_query($con,"Select * from lup_remittance_center where isdeleted = 0");
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>BANK/REMITTANCE CENTER</th>
									<th>TOTAL AMOUNT</th>
								</thead>
		<?PHP
			$ctr = 1;
		$gtotal = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0"));
				$gtotal = $gtotal + $total['total'];
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $brow['bank_name'];?></td>
					<td><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		while($rrow = mysqli_fetch_assoc($rquery))
		{
			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 1
					and order_transaction.status_collection = 1
					and order_transaction.isdeleted = 0"));
				$gtotal = $gtotal + $total['total'];
				
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $rrow['remittance_center_name'];?></td>
					<td><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		?>
			<tr>
					<td></td>
					<th>GRAND TOTAL:</th>
					<th><?php echo number_format($gtotal,2);?></th>
				</tr>
		</table>
				
		<?php
}
function collectionreport_bank($dfrom,$dto,$print)
{
	global $con;
	$bquery = mysqli_query($con,"Select * from lup_bank where isdeleted = 0");
	$rquery = mysqli_query($con,"Select * from lup_remittance_center where isdeleted = 0");
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>BANK/REMITTANCE CENTER</th>
									<th>TOTAL AMOUNT</th>
								</thead>
		<?PHP
			$ctr = 1;
		$gtotal = 0;
		while($brow = mysqli_fetch_assoc($bquery))
		{
					$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where order_transaction.bank_id = $brow[bank_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0"));
				$gtotal = $gtotal + $total['total'];
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $brow['bank_name'];?></td>
					<td><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		while($rrow = mysqli_fetch_assoc($rquery))
		{
			$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where order_transaction.remittance_center_id = $rrow[remittance_center_id] and
					(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
					STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
					and order_transaction.status_remittance = 2
					and order_transaction.isdeleted = 0"));
				$gtotal = $gtotal + $total['total'];
				
			?>
				<tr>
					<td><?php echo $ctr;?></td>
					<td><?php echo $rrow['remittance_center_name'];?></td>
					<td><?php echo number_format($total['total'],2);?></td>
				</tr>
			<?php
			$ctr++;
		}
		?>
			<tr>
					<td></td>
					<th>GRAND TOTAL:</th>
					<th><?php echo number_format($gtotal,2);?></th>
				</tr>
		</table>
				
		<?php
}
function salebyitem($dfrom,$dto,$print)
{
	global $con;
	$string = "Select * from lup_product where isdeleted = 0";
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<thead>
									<th>#</th>
									<th>ITEM</th>
									<th>QUANTITY</th>
									<th>TOTAL</th>
									
								</thead>
			<?php
				$ctr = 1;
				$qtotal = 0;
				$gtotal = 0;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['product_description'];?></td>
							<td><?php
								$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product where
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
	
								and lup_product.product_id = $row[product_id]"));
								
								echo number_format($qty['qty']);
								$qtotal = $qtotal + $qty['qty'];
							?></td>
							<td><?php
							
							$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity*order_transaction_detail.product_price) as total from order_transaction,order_transaction_detail,lup_product where
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
	
								and lup_product.product_id = $row[product_id]"));
								$gtotal = $gtotal + $total['total'];
								echo number_format($total['total'],2);
							?></td>
						</tr>
					<?php
					$ctr++;
				}
			?>
		</table>
		
			<table class = "table table-bordered table-hover table-sm">
				<tr>
								
									<th>TOTAL QUANTITY: <?php echo number_format($qtotal,2);?></th>
									<th>TOTAL AMOUNT: <?php echo number_format($gtotal,2);?></th>
									
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

function salebyteam_print($dfrom,$dto)
{
	global $con;
	$string = "Select * from lup_sales_team where isdeleted = 0 and no_team = 0";
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>TEAM NAME</th>
									<th>QUANTITY</th>
									<th>TOTAL</th>
									
								</thead>
			<?php
				$ctr = 1;
				$qtotal = 0;
				$gtotal = 0;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['sales_team_name'];?></td>
							<td><?php
								$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product where order_transaction.team_id = $row[sales_team_id] and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
							
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.isshipping = 0"));
								
								echo number_format($qty['qty']);
								$qtotal = $qtotal + $qty['qty'];
							?></td>
							<td><?php
							
							$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where order_transaction.team_id = $row[sales_team_id] and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								
								and order_transaction.isdeleted = 0
								"));
								$gtotal = $gtotal + $total['total'];
								echo number_format($total['total'],2);
							?></td>
						</tr>
					<?php
					$ctr++;
				}
			?>
		</table>
		
			<table class = "table table-bordered table-hover table-sm">
				<tr>
								
									<th>TOTAL QUANTITY: <?php echo number_format($qtotal,2);?></th>
									<th>TOTAL AMOUNT: <?php echo number_format($gtotal,2);?></th>
									
				</tr>
			</table>
			
		
	<?php
}
function salebyteam($dfrom,$dto)
{
	global $con;
	$string = "Select * from lup_sales_team where isdeleted = 0 and no_team = 0";
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>TEAM NAME</th>
									<th>QUANTITY</th>
									<th>TOTAL</th>
									
								</thead>
			<?php
				$ctr = 1;
				$qtotal = 0;
				$gtotal = 0;
				while($row = mysqli_fetch_assoc($query))
				{
					?>
						<tr>
							<td><?php echo $ctr;?></td>
							<td><?php echo $row['sales_team_name'];?></td>
							<td><?php
								$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction_detail.order_quantity) as qty from order_transaction,order_transaction_detail,lup_product where order_transaction.team_id = $row[sales_team_id] and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								and order_transaction.order_transaction_id = order_transaction_detail.order_transaction_id
								and order_transaction.isdeleted = 0
								
								and order_transaction_detail.isdeleted = 0
								and order_transaction_detail.product_id = lup_product.product_id
								and lup_product.isshipping = 0"));
								
								echo number_format($qty['qty']);
								$qtotal = $qtotal + $qty['qty'];
							?></td>
							<td><?php
							
							$total = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_transaction.total_amount) as total from order_transaction where order_transaction.team_id = $row[sales_team_id] and
								(STR_TO_DATE(order_transaction.order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
								STR_TO_DATE(.order_transaction.order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
								and order_transaction.status_collection = 1
								
								and order_transaction.isdeleted = 0
								"));
								$gtotal = $gtotal + $total['total'];
								echo number_format($total['total'],2);
							?></td>
						</tr>
					<?php
					$ctr++;
				}
			?>
		</table>
		
			<table class = "table table-bordered table-hover table-sm">
				<tr>
								
									<th>TOTAL QUANTITY: <?php echo number_format($qtotal,2);?></th>
									<th>TOTAL AMOUNT: <?php echo number_format($gtotal,2);?></th>
									
				</tr>
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
function get_company()
{
	$com = "AZCALIVA CORPORATION";
	
	return $com;
}
function profile()
{
		global $con;
		$query = mysqli_query($con,"Select social_media1,lastname, firstname, middlename, customer_id,customer_no,customer_type,reference_no from customer_profile order by lastname");
	
	?>
		<table class = "table table-striped table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>CUSTOMER NO</th>
									<th>REFERENCE NO</th>
									<th>CUSTOMER TYPE</th>
									<th>FULL NAME</TH>
									<th>FACEBOOK NAME</TH>
									<th>ACTION</TH>
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['customer_no'];?></td>
						<td><?php echo $row['reference_no'];		
						?></td>
						<td><?php
							$ctype = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type]"));
							echo $ctype['customer_type_name'];
						?></td>
						<td><?php echo $row['lastname']." ".$row['firstname']." ".$row['middlename'];?></td>
						<td><?php echo $row['social_media1'];?></td>
						<td>
							<button class = "btn btn-primary btn-flat btn-xs" id = "tran<?php echo $ctr;?>">TRANSACTIONS</button>
							<button class = "btn btn-warning btn-flat btn-xs" id = "pro<?php echo $ctr;?>">PRODUCT SALES SUMMARY</button>
							<button class = "btn btn-success btn-flat btn-xs" id = "modify<?php echo $ctr;?>">VIEW/MODIFY</button>	
						</td>
					</tr>
					<script>
									$("#modify<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/main.php',
																{
																	modcustomer:<?php echo $row['customer_id'];?>,
																	issave:2,
																	cont:'click'
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									$("#tran<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/main.php',
																{
																	trancustomer:<?php echo $row['customer_id'];?>
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									
									$("#pro<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/main.php',
																{
																	procustomer:<?php echo $row['customer_id'];?>
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
							 '../php/main.php',
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

function users()
{
		global $con;
		$query = mysqli_query($con,"Select * from se_user order by agent_number");
	
	?>
		<table class = "table table-striped table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>AGENT NUMBER</th>
									<th>FULL NAME</th>
									<th>TEAM</th>
									<th>ACTION</TH>
								</thead>
		<?PHP
			$ctr = 1;
			while($row = mysqli_fetch_assoc($query))
			{
				$team = mysqli_fetch_assoc(mysqli_query($con, "Select * from lup_sales_team where sales_team_id = $row[sales_team_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['agent_number'];?></td>
						<td><?php echo $row['fullname'];?></td>
						<td><?php echo $team['sales_team_name'];?></td>
					
						<td>
							<button class = "btn btn-success btn-flat btn-xs" id = "assign<?php echo $ctr;?>">MODULE ASSIGNMENT</button>	
							<button class = "btn btn-danger btn-flat btn-xs" id = "report<?php echo $ctr;?>">REPORT ASSISGMENT</button>	
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
																'../php/main.php',
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
																'../php/main.php',
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
															$("#modalbody").css("max-width","60%");
															
															$.post( 
																'../php/main.php',
																{
																	edit_userid:'<?php echo $row['user_id'];?>'
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
																'../php/main.php',
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
function dmonitoring_print($dfrom, $dto, $status, $courier)
{
	//echo $status." ".$courier;
	global $con;
	
		$due = date_create($dfrom);
		$datefrom = date_format($due,"Y-m-d");
		
		$dueto = date_create($dto);
		$dateto = date_format($dueto,"Y-m-d");
		
		
	if($status == 'ALL' && $courier != 'ALL')
	{
		
		if($dfrom == "" && $dto = "")
		{
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d')>= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d')<= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and courier_id = $courier
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0
			order by status_remittance desc");
		}
		else
		{
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and courier_id = $courier
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0
			order by status_remittance desc");

		}
		
	}
	elseif($status != 'ALL' && $courier == 'ALL')
	{
		
		if($dfrom == "" && $dto = "")
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and status_shipping = $status
		and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0
			order by status_remittance desc");
		}
		else
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and status_shipping = $status
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0
			order by status_remittance desc");

		}
	}
	elseif($status != 'ALL' && $courier != 'ALL')
	{
		//echo "AAAAA";
		if($dfrom == "" && $dto = "")
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and status_shipping = $status
			and courier_id = $courier
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0
			order by status_remittance desc");
		}
		else
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and status_shipping = $status
			and courier_id = $courier
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0
			order by status_remittance desc");

		}
	}
	else
	{
		
		if($dfrom == "" && $dto == "")
		{
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1
			and (STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0
			order by status_remittance desc");
		}
		else
		{
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0
			order by status_remittance desc");
		}
	}
		
	
	?>
		<table class = "table table-bordered table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>TRANSACTION NO</th>
									<th>SALES AGENT</th>
									<th>TEAM</th>
									<th>CUSTOMER #/Name</th>
									<th>COURIER</th>
									<th>TRACKING #</th>
									<th>DATE CREATE</th>
									<th>TOTAL QUANTITY</th>
									<th>SHIPPING STATUS</th>
									
								</thead>
		<?PHP
			$ctr = 1;
			$totalqty = 0;
			$gtotal = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$gtotal = $gtotal + $row['total_amount'];
				$cus = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_id = $row[customer_id]"));
				$tdetails = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_sales_team where sales_team_id = $row[team_id]"));
				$cou = mysqli_fetch_assoc(mysqli_query($con,"Select courier_name from lup_courier where courier_id = $row[courier_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						
						<td><?php echo $row['order_transaction_no'];?></td>
						<td><?php echo get_agent($row['sales_agent_id']);?></td>
						<td><?php echo $tdetails['sales_team_name'];?></td>
						<td><?php echo $cus['customer_no']."<br>".$cus['firstname']." ".$cus['middlename']." ".$cus['lastname'];?></td>
						<td><?php echo $cou['courier_name'];?></td>
						<td><?php echo $row['reference_no_courier'];?></td>
						<td><?php echo $row['order_transaction_date'];?></td>
						<td><?php
						$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_quantity) as total from order_transaction_detail
							where order_transaction_id = $row[order_transaction_id] and isdeleted = 0 and isshipping = 0"));
							echo number_format($qty['total'],2);
							
							$totalqty = $totalqty + $qty['total'];
							
						?></td>
						
					
						<td><?php 
								
								
									if($row['status_shipping'] == "1")
										echo "PENDING";
									elseif($row['status_shipping'] == "2")
										echo "PROCESSING";
									elseif($row['status_shipping'] == 3)
										echo "SHIPPED";
									elseif($row['status_shipping'] == 4)
										echo "ORDER RECIEVED";
							
							echo "<br>".$row['datetime_shipping_updated'];
						?></td>
						
					</tr>
					
					
				<?php
				$ctr++;
			}
			?>
		</table>
			<table class = "table table-striped table-hover table-sm">
				<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<th>TOTAL QUANTITY: <?php echo number_format($totalqty,2);?></th>
									<th></th>
									<td></td>
									<td></td>
									<td></td>
				</tr>
			</table>
	<?php
	
	
}

function dmonitoring($dfrom, $dto, $status, $courier)
{
	//echo $status." ".$courier;
	global $con;
	
		$due = date_create($dfrom);
		$datefrom = date_format($due,"Y-m-d");
		
		$dueto = date_create($dto);
		$dateto = date_format($dueto,"Y-m-d");
		
		
	if($status == 'ALL' && $courier != 'ALL')
	{
		
		if($dfrom == "" && $dto = "")
		{
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and courier_id = $courier
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0 and status_return = ''
			order by status_remittance desc");
		}
		else
		{
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and courier_id = $courier
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0 and status_return = ''
			order by status_remittance desc");

		}
		
	}
	elseif($status != 'ALL' && $courier == 'ALL')
	{
		
		if($dfrom == "" && $dto = "")
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and status_shipping = $status
		and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0 and status_return = ''
			order by status_remittance desc");
		}
		else
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and status_shipping = $status
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0 and status_return = ''
			order by status_remittance desc");

		}
	}
	elseif($status != 'ALL' && $courier != 'ALL')
	{
		//echo "AAAAA";
		if($dfrom == "" && $dto = "")
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and status_shipping = $status
			and courier_id = $courier
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0 and status_return = ''
			order by status_remittance desc");
		}
		else
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and status_shipping = $status
			and courier_id = $courier
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0 and status_return = ''
			order by status_remittance desc");

		}
	}
	else
	{
		
		if($dfrom == "" && $dto == "")
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1
			and (STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0 and status_return = ''
			order by status_remittance desc");
		}
		else
		{
			$query = mysqli_query($con,"Select * from order_transaction where status_collection = 1 and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and (payment_method_id != 4 and payment_method_id != 5) and isdeleted = 0 and status_return = ''
			order by status_remittance desc");
		}
	}
		
	
	?>
		<table class = "table table-striped table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>ACTION</TH>
									<th>TRANSACTION NO</th>
									<th>SALES AGENT</th>
									<th>TEAM</th>
									<th>CUSTOMER #/Name</th>
									<th>COURIER</th>
									<th>TRACKING #</th>
									<th>DATE CREATE</th>
									<th>TOTAL QUANTITY</th>
									<th>SHIPPING STATUS</th>
									
								</thead>
		<?PHP
			$ctr = 1;
			$totalqty = 0;
			$gtotal = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$gtotal = $gtotal + $row['total_amount'];
				$cus = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_id = $row[customer_id]"));
				$tdetails = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_sales_team where sales_team_id = $row[team_id]"));
				$cou = mysqli_fetch_assoc(mysqli_query($con,"Select courier_name from lup_courier where courier_id = $row[courier_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td>
						<?php
						if($row['status_shipping'] != 4)
						{
							if($_SESSION['accesslevel'] == 1 || $_SESSION['accesslevel'] == 2)
							{
						?>
								<button class = "btn btn-success btn-flat btn-xs" id = "update<?php echo $ctr;?>">UPDATE STATUS</button>	
						<?php
							}
						}
						else
						{
							?>
								<button class = "btn btn-warning btn-flat btn-xs" id = "ret<?php echo $ctr;?>">RETURN</button>
								<button class = "btn btn-danger btn-flat btn-xs" id = "pdel<?php echo $ctr;?>">PROOF OF DELIVERY</button>									
							<?php
						}
						?>
							<button class = "btn btn-primary btn-flat btn-xs" id = "details<?php echo $ctr;?>">DETAILS</button>	
						</td>
						<td><?php echo $row['order_transaction_no'];?></td>
						<td><?php echo get_agent($row['sales_agent_id']);?></td>
						<td><?php echo $tdetails['sales_team_name'];?></td>
						<td><?php echo $cus['customer_no']."<br>".$cus['firstname']." ".$cus['middlename']." ".$cus['lastname'];?></td>
						<td><?php echo $cou['courier_name'];?></td>
							<td><?php echo $row['reference_no_courier'];?></td>
						<td><?php echo $row['order_transaction_date'];?></td>
						<td><?php
						$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_quantity) as total from order_transaction_detail
							where order_transaction_id = $row[order_transaction_id] and isdeleted = 0 and isshipping = 0"));
							echo number_format($qty['total'],2);
							
							$totalqty = $totalqty + $qty['total'];
							
						?></td>
					
					
						<td><?php 
								
								
									if($row['status_shipping'] == "1")
										echo "PENDING";
									elseif($row['status_shipping'] == "2")
										echo "PROCESSING";
									elseif($row['status_shipping'] == 3)
										echo "SHIPPED";
									elseif($row['status_shipping'] == 4)
										echo "ORDER RECIEVED";
							
							echo "<br>".$row['datetime_shipping_updated'];
						?></td>
						
					</tr>
					<script>
						$("#details<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/finance.php',
																{
																	orderdetails:<?php echo $row['order_transaction_id'];?>,
																	edit:1
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
						
						$("#pdel<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/finance.php',
																{
																	pdelui:<?php echo $row['order_transaction_id'];?>,
																	edit:1
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									
						
						$("#update<?php echo $ctr;?>").click(
										function()
										{
											
															$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/main.php',
																{
																	updatestatus:<?php echo $row['order_transaction_id'];?>,
																	updatestatusdfrom:'<?php echo $dfrom;?>',
																	updatestatusdto:'<?php echo $dto;?>',
																	updatestatusstatus:'<?php echo $status;?>',
																	updatestatuscourier:'<?php echo $courier;?>'
																	
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
						
						$("#ret<?php echo $ctr;?>").click(
										function()
										{
											
															var r = confirm("Confirm Action");
															
															if(r == true)
															{
																$.post( 
																	'../php/main.php',
																	{
																		returnui:<?php echo $row['order_transaction_id'];?>,
																		returnstatusdfrom:'<?php echo $dfrom;?>',
																		returnstatusdto:'<?php echo $dto;?>',
																		returnstatusstatus:'<?php echo $status;?>',
																		returnstatuscourier:'<?php echo $courier;?>'
																		
																	},
																	function(data) {
																		$('#dmonitoringui2').html(data);		
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
			<table class = "table table-striped table-hover table-sm">
				<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<th>TOTAL QUANTITY: <?php echo number_format($totalqty,2);?></th>
									<th></th>
									<td></td>
									<td></td>
									<td></td>
				</tr>
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

function smonitoring($dfrom, $dto, $status, $rem, $app)
{
	//echo $status." ".$rem;
	global $con;
	$user = get_user_id($_SESSION['useraccount']);
	$string = "Select * from order_transaction where ";
	
	if($app == "none")
		$app = "0";
		
	if($dfrom == "" && $dto == "")
	{
		$string = $string." (STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))";
	}
	else
	{
		$string = $string."(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	if($status != "ALL")
	{
		$string = $string." and status_shipping = $status";
	}
	
	if($rem != "ALL")
	{
		$string = $string." and  status_remittance = $rem ";
	}
	
	if($app != "ALL")
	{
		$string = $string."and status_collection = $app";
	}
	
	//echo $string;
		$string = $string." and isdeleted = 0 and status_return = ''";
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-striped table-hover table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									<th>ACTION</TH>
									<th>TRANSACTION NO</th>
									<th>SALES AGENT</th>
									<th>TEAM</th>
									<th>CUSTOMER NO</th>
									<th>TOTAL QTY</th>
								
									<th>DATE CREATE</th>
									<th>PAYMENT STATUS</th>
									<th>APPROVAL</th>
									<th>SHIPPING STATUS</th>
									
								</thead>
		<?PHP
			$ctr = 1;
			$totalqty = 0;
			$gtotal = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$gtotal = $gtotal + $row['total_amount'];
				$cus = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_id = $row[customer_id]"));
				$tdetails = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_sales_team where sales_team_id = $row[team_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td id = "sactionui<?php echo $ctr;?>">
							
							<button class = "btn btn-primary btn-flat btn-xs" id = "details<?php echo $ctr;?>">DETAILS</button>	
						<?php
						if($row['status_collection'] == 0)
						{
						?>
							<button class = "btn btn-warning btn-flat btn-xs" id = "sdelete<?php echo $ctr;?>">X</button>	
						<?php
						}
						?>
						</td>
						<td><?php echo $row['order_transaction_no'];?></td>
						<td><?php echo get_agent($row['sales_agent_id']);?></td>
						<td><?php echo $tdetails['sales_team_name'];?></td>
						<td><?php echo $cus['customer_no']."<br>".$cus['firstname']." ".$cus['middlename']." ".$cus['lastname'];?></td>
						<td id = "sqtyui<?php echo $ctr;?>"><?php
							$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_quantity) as total from order_transaction_detail
							where order_transaction_id = $row[order_transaction_id] and isdeleted = 0 and isshipping = 0"));
							echo number_format($qty['total'],2);
							
							$totalqty = $totalqty + $qty['total'];
							
						?></td>
						
						<td><?php echo $row['order_transaction_date'];?></td>
						
						<td><?php if($row['status_remittance'] == 1)
									echo "REMITTED";
									elseif($row['status_remittance'] == 2)
									echo "RECEIVED";
									elseif($row['status_remittance'] == 3)
									echo "DENIED";
							
							echo "<br>".$row['datetime_remittance_updated'];
						?></td>
						
						<td><?php 	if($row['status_collection'] == 1)
									echo "APPROVED";
									else if($row['status_collection'] == 2)
									echo "DENIED";
							echo "<br>".$row['datetime_collection_updated'];		
						?></td>
						<td><?php 
								
								
									if($row['status_shipping'] == "1")
										echo "PENDING";
									elseif($row['status_shipping'] == "2")
										echo "PROCESSING";
									elseif($row['status_shipping'] == 3)
										echo "SHIPPED";
									elseif($row['status_shipping'] == 4)
										echo "ORDER RECIEVED";
							
							echo "<br>".$row['datetime_shipping_updated'];
						?></td>
						
						
					</tr>
					<script>
						$("#sdelete<?php echo $ctr;?>").click(
										function()
										{
															var r = confirm("confirm delete");

															if(r == true)
															{
																$.post( 
																	'../php/sales.php',
																	{
																	
																		saledelete:<?php echo $row['order_transaction_id'];?>,
																		sdeldfrom:'<?php echo $dfrom;?>',
																		sdeldto:'<?php echo $dto;?>',
																		sdelstatus:'<?php echo $status;?>',
																		sdelrem:'<?php echo $rem;?>',
																		sdelapp:'<?php echo $app;?>'
																	},
																	function(data) {
																		$('#smonitoringui2').html(data);		
																	});
															}
										}
									);
						$("#details<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/finance.php',
																{
																	saleorderdetails:<?php echo $row['order_transaction_id'];?>,
																	edit:0,
																	pay:1,
																	salecount:<?php echo $ctr;?>
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
			<table class = "table table-striped table-hover table-sm">
				<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<th>TOTAL QUANTITY: <?php echo number_format($totalqty,2);?></th>
									<th></th>
									<td></td>
									<td></td>
									<td></td>
				</tr>
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

function smonitoring_print($dfrom, $dto, $status, $rem, $app)
{
	
	global $con;
	
	$user = get_user_id($_SESSION['useraccount']);
	$string = "Select * from order_transaction where ";
	
	
	if($app == "none")
		$app = "0";
	
//echo $app;	
	if($dfrom == "" && $dto == "")
	{
		$string = $string." (STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))";
	}
	else
	{
		$string = $string."(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	if($status != "ALL")
	{
	
		$string = $string." and status_shipping = $status";
	}
	
	if($rem != "ALL")
	{
		$string = $string." and  status_remittance = $rem ";
	}
	
	if($app != "ALL")
	{
		$string = $string."and status_collection = $app";
	}
	
	//echo $string;
	$string = $string." and isdeleted = 0 and status_return = ''";
	$query = mysqli_query($con,$string);
	
	/*if($status == 'ALL' && $rem != 'ALL')
	{
		
		if($dfrom == "" && $dto = "")
		{
			$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and status_remittance = $rem
			order by status_remittance desc");
			
		}
		else
		{
			$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and status_remittance = $rem
			order by status_remittance desc");
			
		}
		
	}
	elseif($status != 'ALL' && $rem == 'ALL')
	{
	
		if($dfrom == "" && $dto = "")
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and status_shipping = $status
			order by status_remittance desc");
		}
		else
		{
			$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and status_shipping = $status
			order by status_remittance desc");

		}
	}
	elseif($status != 'ALL' && $rem != 'ALL')
	{
	
		if($dfrom == "" && $dto = "")
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			and status_shipping = $status
			and status_remittance = $rem
			order by status_remittance desc");
		}
		else
		{
				
			$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			and status_shipping = $status
			and status_remittance = $rem
			order by status_remittance desc");

		}
	}
	else
	{
		
		if($dfrom == "" && $dto == "")
		{
			
			/*$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))
			order by status_remittance desc");
			$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user
			order by status_remittance desc");
			
		}
		else
		{
			
			$query = mysqli_query($con,"Select * from order_transaction where sales_agent_id = $user and
			(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))
			order by status_remittance desc");
		}
	}*/
	
	?>
		<table class = "table table-bordered table-sm" id = "pmtable">
								<thead>
									<th>#</th>
									
									<th>TRANSACTION NO</th>
									<th>SALES AGENT</th>
									<th>TEAM</th>
									<th>CUSTOMER NO</th>
									<th>TOTAL QTY</th>
								
									<th>DATE CREATE</th>
									<th>PAYMENT STATUS</th>
									<th>APPROVAL</th>
									<th>SHIPPING STATUS</th>
									
								</thead>
		<?PHP
			$ctr = 1;
			$totalqty = 0;
			$gtotal = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$gtotal = $gtotal + $row['total_amount'];
				$cus = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_id = $row[customer_id]"));
				$tdetails = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_sales_team where sales_team_id = $row[team_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						
						<td><?php echo $row['order_transaction_no'];?></td>
						<td><?php echo get_agent($row['sales_agent_id']);?></td>
						<td><?php echo $tdetails['sales_team_name'];?></td>
						<td><?php echo $cus['customer_no']."<br>".$cus['firstname']." ".$cus['middlename']." ".$cus['lastname'];?></td>
						<td><?php
							$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_quantity) as total from order_transaction_detail
							where order_transaction_id = $row[order_transaction_id] and isdeleted = 0 and isshipping = 0"));
							echo number_format($qty['total'],2);
							
							$totalqty = $totalqty + $qty['total'];
							
						?></td>
						
						<td><?php echo $row['order_transaction_date'];?></td>
						
						<td><?php if($row['status_remittance'] == 1)
									echo "REMITTED";
									elseif($row['status_remittance'] == 2)
									echo "RECEIVED";
									elseif($row['status_remittance'] == 3)
									echo "DENIED";
							
							echo "<br>".$row['datetime_remittance_updated'];
						?></td>
						
						<td><?php 	if($row['status_collection'] == 1)
									echo "APPROVED";
									else if($row['status_collection'] == 2)
									echo "DENIED";
							echo "<br>".$row['datetime_collection_updated'];		
						?></td>
						<td><?php 
								
								
									if($row['status_shipping'] == "1")
										echo "PENDING";
									elseif($row['status_shipping'] == "2")
										echo "PROCESSING";
									elseif($row['status_shipping'] == 3)
										echo "SHIPPED";
									elseif($row['status_shipping'] == 4)
										echo "ORDER RECIEVED";
							
							echo "<br>".$row['datetime_shipping_updated'];
						?></td>
						
						
					</tr>
				<?php
				$ctr++;
			}
			?>
				
		</table>
			<table class = "table table-bordered table-sm">
				<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<th>TOTAL QUANTITY: <?php echo number_format($totalqty,2);?></th>
									<th></th>
									<td></td>
									<td></td>
									<td></td>
				</tr>
			</table>
	<?php
	
	
}
function pmonitoring_print($dfrom, $dto, $status,$app,$method)
{

	global $con;
	
	/*if($bank == "NONE")
		$bank = "0";
	
	if($rem == "NONE")
		$rem = "0";*/
	
	if($app == "none")
		$app = "0";
		
		
		$due = date_create($dfrom);
		$datefrom = date_format($due,"Y-m-d");
		
		$dueto = date_create($dto);
		$dateto = date_format($dueto,"Y-m-d");
		
		//echo $status." ".$app." aa";
	$string = "Select * from order_transaction where ";
	
	if($dfrom == "" && $dto == "")
	{
		$string = $string." (STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))";
	}
	else
	{
		$string = $string."(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	if($status != "ALL")
	{
		$string = $string." and status_remittance = $status";
	}
	
	if($app != "ALL")
	{
		$string = $string." and status_collection = $app";
	}
	
	if($method != "ALL")
	{
		$string = $string." and payment_method_id = $method";
	}

	//echo $string;
		$string = $string." and isdeleted = 0 and status_return = ''";
	$query = mysqli_query($con,$string);
	?>
		<table class = "table table-bordered table-hover table-xs" id = "pmtable">
								<thead>
									<th>#</th>
									
									<th>TRANSACTION NO</th>
									<th>SALES AGENT</th>
								
									<th>CUSTOMER NO</th>
									<th>REMITTANCE</th>
									<th>DATE CREATE</th>
									<th>TOTAL QTY</th>
									<th>TOTAL</th>
									<th>TOTAL PAYMENT</th>
									<th>APPROVAL</th>
									
								</thead>
		<?PHP
			$ctr = 1;
			$gtotal = 0;
			$totalqty = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$gtotal = $gtotal + $row['total_amount'];
				$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_no from customer_profile where customer_id = $row[customer_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['order_transaction_no'];?></td>
						<td><?php echo get_agent($row['sales_agent_id']);?></td>
						<td><?php echo $cus['customer_no'];?></td>
						<td><?php
							if(!empty($row['bank_id']))
							{
								$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_bank where bank_id = $row[bank_id]"));
								
								echo $bank['bank_name']."-".$row['reference_payment'];
							}
							else if(!empty($row['remittance_center_id']))
							{
								$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_remittance_center where remittance_center_id = $row[remittance_center_id]"));
								
								echo $bank['remittance_center_name']."-".$row['reference_payment'];
							}
							else
							{
								echo "REFERRAL NO:".$row['reference_payment'];
							}
						?></td>
						<td><?php echo $row['order_transaction_date'];?></td>
							<td><?php
							$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_quantity) as total from order_transaction_detail
							where order_transaction_id = $row[order_transaction_id] and isdeleted = 0 and isshipping = 0"));
							echo number_format($qty['total'],2);
							
							$totalqty = $totalqty + $qty['total'];
							
						?></td>
						<td><?php echo $row['total_amount'];?></td>
						<td><?php 
						
							
								
								$totalpay = mysqli_fetch_assoc(mysqli_query($con,"Select sum(amount) as total from order_payment where transaction_id = $row[order_transaction_id] and isdeleted = 0"));
								
								echo number_format($totalpay['total'],2);
								
								/*if($row['status_remittance'] == 1)
									echo "REMITTED";
									elseif($row['status_remittance'] == 2)
									echo "RECEIVED";
									elseif($row['status_remittance'] == 3)
									echo "DENIED";*/
							
							//echo "<br>".$row['datetime_remittance_updated'];
						
							/*if($row['status_remittance'] == 1)
									echo "REMITTED";
									elseif($row['status_remittance'] == 2)
									echo "RECEIVED";
									elseif($row['status_remittance'] == 3)
									echo "DENIED";
							
							echo "<br>".$row['datetime_remittance_updated'];*/
						?></td>
						
						<td><?php 	if($row['status_collection'] == 1)
									echo "APPROVED";
									else if($row['status_collection'] == 2)
									echo "DENIED";
							echo "<br>".$row['datetime_collection_updated'];		
						?></td>
						
					</tr>
					
				<?php
				$ctr++;
			}
			?>
		</table>
		<table class = "table table-bordered table-hover table-sm">
				<tr>
								
									<th>TOTAL QUANTITY: <?php echo number_format($totalqty,2);?></th>
									<th>TOTAL AMOUNT: <?php echo number_format($gtotal,2);?></th>
									
				</tr>
			</table>
		
	<?php
	
	
}

function pmonitoring($dfrom, $dto, $status,$app,$method)
{
	global $con;
	
	if($app == "none")
		$app = "0";
		
		
		$due = date_create($dfrom);
		$datefrom = date_format($due,"Y-m-d");
		
		$dueto = date_create($dto);
		$dateto = date_format($dueto,"Y-m-d");
		
		//echo $status." ".$app." aa";
	$string = "Select * from order_transaction where ";
	
	if($dfrom == "" && $dto == "")
	{
		$string = $string." (STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE(NOW(),'%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE(NOW(),'%Y-%m-%d'))";
	}
	else
	{
		$string = $string."(STR_TO_DATE(order_transaction_date,'%Y-%m-%d') >= STR_TO_DATE('$dfrom','%Y-%m-%d') and
			STR_TO_DATE(order_transaction_date,'%Y-%m-%d') <= STR_TO_DATE('$dto','%Y-%m-%d'))";
	}
	
	if($status != "ALL")
	{
		$string = $string." and status_remittance = $status";
	}
	
	if($app != "ALL")
	{
		$string = $string." and status_collection = $app";
	}
	
	if($method != "ALL")
	{
		$string = $string." and payment_method_id = $method";
	}

	//echo $string;
	$string = $string." and isdeleted = 0 and status_return = ''";
	$query = mysqli_query($con,$string);
	
	?>
		<table class = "table table-striped table-hover table-xs" id = "pmtable">
								<thead>
									<th>#</th>
									<th>ACTION</th>
									<th>TRANSACTION NO</th>
									<th>SALES AGENT</th>
								
									<th>CUSTOMER NO</th>
									<th>REMITTANCE</th>
									<th>DATE CREATE</th>
									<th>TOTAL QTY</th>
									<th>TOTAL</th>
									<th>TOTAL PAYMENT</th>
									<th>APPROVAL</th>
									
								</thead>
		<?PHP
			$ctr = 1;
			$gtotal = 0;
			$totalqty = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				$gtotal = $gtotal + $row['total_amount'];
				$cus = mysqli_fetch_assoc(mysqli_query($con,"Select customer_no from customer_profile where customer_id = $row[customer_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td>
							<button class = "btn btn-success btn-flat btn-xs" id = "details<?php echo $ctr;?>">DETAILS</button>	
							
						<?php
						if($row['status_collection'] == 1)
						{
						?>
							<button class = "btn btn-warning btn-flat btn-xs" id = "receive<?php echo $ctr;?>" style = "display:none;">RECEIVE</button>	
							<button class = "btn btn-primary btn-flat btn-xs" id = "payments<?php echo $ctr;?>">PAYMENTS</button>	
						<?php
						}
						
						if($row['status_collection'] == 0)
						{
						?>
							<button class = "btn btn-primary btn-flat btn-xs" id = "approve<?php echo $ctr;?>">APPROVE</button>	
							<button class = "btn btn-danger btn-flat btn-xs" id = "deny<?php echo $ctr;?>">DENY</button>
							<script>
							$("#deny<?php echo $ctr;?>").click(
							function()
							{
								var r = confirm("Confirm Deny");
								
								if(r == true)
								{
									$.post( 
											'../php/finance.php',
											{
												pdeny:<?php echo $row['order_transaction_id'];?>,
												pdenydfrom:'<?php echo $dfrom;?>',
												pdenydto:'<?php echo $dto;?>',
												pdenyapp:'<?php echo $app;?>',
												pdenystatus:'<?php echo $status;?>',
												pdenybank:'NONE',
												pdenyrem:'NONE'
											},
											function(data) {
												$('#pmonitoringui2').html(data);		
											});
								}
											
							}
						);
							</script>
						<?php
						}
						?>						
						</td>
						<td><?php echo $row['order_transaction_no'];?></td>
						<td><?php echo get_agent($row['sales_agent_id']);?></td>
						<td><?php echo $cus['customer_no'];?></td>
						<td><?php
							if(!empty($row['bank_id']))
							{
								$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_bank where bank_id = $row[bank_id]"));
								
								echo $bank['bank_name']."-".$row['reference_payment'];
							}
							else if(!empty($row['remittance_center_id']))
							{
								$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_remittance_center where remittance_center_id = $row[remittance_center_id]"));
								
								echo $bank['remittance_center_name']."-".$row['reference_payment'];
							}
							else
							{
								echo "REFERRAL NO:".$row['reference_payment'];
							}
						?></td>
						<td><?php echo $row['order_transaction_date'];?></td>
							<td><?php
							$qty = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(order_quantity) as total from order_transaction_detail
							where order_transaction_id = $row[order_transaction_id] and isdeleted = 0 and isshipping = 0"));
							echo number_format($qty['total'],2);
							
							$totalqty = $totalqty + $qty['total'];
							
						?></td>
						<td><?php echo $row['total_amount'];?></td>
						<td><?php 
								
								$totalpay = mysqli_fetch_assoc(mysqli_query($con,"Select sum(amount) as total from order_payment where transaction_id = $row[order_transaction_id] and isdeleted = 0"));
								
								echo number_format($totalpay['total'],2);
								
								/*if($row['status_remittance'] == 1)
									echo "REMITTED";
									elseif($row['status_remittance'] == 2)
									echo "RECEIVED";
									elseif($row['status_remittance'] == 3)
									echo "DENIED";*/
							
							//echo "<br>".$row['datetime_remittance_updated'];
						?></td>
						
						<td><?php 	if($row['status_collection'] == 1)
									echo "APPROVED";
									else if($row['status_collection'] == 2)
									echo "DENIED";
							echo "<br>".$row['datetime_collection_updated'];		
						?></td>
						
					</tr>
					<script>
						$("#payments<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("min-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/finance.php',
																{
																	paymentdetailsui:<?php echo $row['order_transaction_id'];?>
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									
						$("#details<?php echo $ctr;?>").click(
										function()
										{
															$("#modal").modal("show");
															$("#modalbody").css("max-width","65%");
															$('#modalui').html(loading);	
															$.post( 
																'../php/finance.php',
																{
																	orderdetails:<?php echo $row['order_transaction_id'];?>,
																	edit:0,
																	pay:1
																},
																function(data) {
																	$('#modalui').html(data);		
																});
										}
									);
									
						$("#receive<?php echo $ctr;?>").click(
							function()
							{
								var r = confirm("Confirm Received");
								
								if(r == true)
								{
									$.post( 
											'../php/finance.php',
											{
											
												preceive:<?php echo $row['order_transaction_id'];?>,
												preceivedfrom:'<?php echo $dfrom;?>',
												preceivedto:'<?php echo $dto;?>',
												preceiveapp:'<?php echo $app;?>',
												preceivestatus:'<?php echo $status;?>',
												preceivemethod:'<?php echo $method;?>'
											
											},
											function(data) {
												$('#pmonitoringui2').html(data);		
											});
								}
											
							}
						);
						
						$("#approve<?php echo $ctr;?>").click(
							function()
							{
								var r = confirm("Confirm Approval");
								
								if(r == true)
								{
									
									$.post( 
											'../php/finance.php',
											{
												
												apppreceive:<?php echo $row['order_transaction_id'];?>,
												apppreceivedfrom:'<?php echo $dfrom;?>',
												apppreceivedto:'<?php echo $dto;?>',
												apppreceiveapp:'<?php echo $app;?>',
												apppreceivestatus:'<?php echo $status;?>',
												apppreceivemethod:'<?php echo $method;?>'
												
											},
											function(data) {
												$('#pmonitoringui2').html(data);		
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
		<table class = "table table-striped table-hover table-sm">
				<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<th>TOTAL QUANTITY: <?php echo number_format($totalqty,2);?></th>
									<th>TOTAL AMOUNT: <?php echo number_format($gtotal,2);?></th>
									<td></td>
									<td></td>
									<td></td>
				</tr>
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

function show_cart($customer)
{
	global $con;
	$query = mysqli_query($con,"Select * from order_transaction_detail where customer_id = $customer
	and order_transaction_id = 0 and isdeleted = 0");
	?>
		<table class = "table table-striped table-hover table-sm table-responsive" id = "jomastertable">
								<thead>
									<th>#</th>
								
									<th>ITEM DESCRIPTION</th>
									<th>ITEM CODE</th>
									<th>UNIT</th>
									<th>Qty</th>
									<th>PRICE</th>
									<th>SUB TOTAL</th>
									<th><button class = "btn btn-danger btn-flat btn-xs" id = "deleteall">X ALL</button></th>
								</thead>
								<script>
									$("#deleteall").click(
										function(e)
										{
											e.preventDefault();
											
											
											var r = confirm("Confirm Delete");
											
											if(r == true)
											{
											$('#cartui').html(loading);
											
											$.post( 
												 '../php/sales.php',
												 {
													 cartdelall:<?php echo $customer;?>
													
												},
												 function(data) {
													$('#cartui').html(data);
													
												 });
											}
										}
									);
								</script>
		<?PHP
			$ctr = 1;
			$totalqty = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				if($row['isshipping'] == 0)
				{
					$totalqty = $totalqty +  $row['order_quantity'];
				}
				$total = $total +  $row['total_order_amount'];
				$pro = mysqli_fetch_assoc(mysqli_query($con,"Select product_code from lup_product where product_id = $row[product_id]"));
				$unit = mysqli_fetch_assoc(mysqli_query($con,"Select unit_description from inv_lup_unit where unit_id = $row[unit_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['product_description'];?></td>
						<td><?php echo $pro['product_code'];?></td>
						<td><?php echo $unit['unit_description'];?></td>
						<td>
													<div class="input-group">
													  
													  <input type="text" class="form-control" name = "mcpqty" id = "mcpqty<?php echo $ctr;?>" data-validation="number"
													data-validation-error-msg="Enter Quantity"
													data-validation-allowing="range[1;10000000]" style = "width:50px;" value = "<?php echo $row['order_quantity'];?>">
													</div>
						</td>
						<td>
						<div class="input-group">
													  
													  <input type="text" class="form-control" 
													  style = "width:100px;"
													  name = "mcpprice" id = "mcpprice<?php echo $ctr;?>" value = "<?php echo $row['product_price'];?>">
													</div>
						
						</td>
						<td id = "ctotalui<?php echo $ctr;?>"><?php echo $row['total_order_amount'];?></td>
						<td><button class = "btn btn-danger btn-flat btn-xs" id = "delete<?php echo $ctr;?>">x</button></td>
					</tr>
					<script>
					$("#mcpprice<?php echo $ctr;?>").focusout(
						function()
						{
							var id = '<?php echo $row['order_transaction_detail_id'];?>';
							$.post( 
							 '../php/sales.php',
							 {
								 pricecompid:id,
								 pricecomp:$("#mcpprice<?php echo $ctr;?>").val()
							},
							 function(data) {
								$('#ctotalui<?php echo $ctr;?>').html(data);
								
							 });
						}
					);
					
					$("#mcpqty<?php echo $ctr;?>").focusout(
						function()
						{
							var id = '<?php echo $row['order_transaction_detail_id'];?>';
							$.post( 
							 '../php/sales.php',
							 {
								 qtycompid:id,
								 qtycomp:$("#mcpqty<?php echo $ctr;?>").val(),
								 qtycount:'<?php echo $ctr;?>'
							},
							 function(data) {
								$('#ctotalui<?php echo $ctr;?>').html(data);
								
							 });
						}
					);
				$("#delete<?php echo $ctr;?>").click(
					function(e)
					{
						e.preventDefault();
						
						
						var id = '<?php echo $row['order_transaction_detail_id'];?>';
						
						var r = confirm("Confirm Delete");
						
						if(r == true)
						{
						$('#cartui').html(loading);
						
						$.post( 
							 '../php/sales.php',
							 {
								 cartdel:id
								
							},
							 function(data) {
								$('#cartui').html(data);
								
							 });
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
						<th></th>
						<th></th>
						<th id = "totalqtyui"><?php echo number_format($totalqty,2);?></th>
						<td></td>
						<th id = "gtotalui"><?php echo number_format($total,2);?></th>
						<td></td>
					</tr>
		</table>
	<?php
	
	
}

function sale_order_cart($id,$count)
{
	global $con;
	$query = mysqli_query($con,"Select * from order_transaction_detail where order_transaction_id = $id and isdeleted = 0");
	$app = mysqli_fetch_assoc(mysqli_query($con,"Select status_collection from order_transaction where order_transaction_id = $id"));
	?>
		<table class = "table table-bordered table-hover table-sm table-responsive" id = "jomastertable">
								<thead>
									<th>#</th>
									<?php
									if($app['status_collection'] == 0)
									{
									?>
									<th>ACTION</th>
									<?php
									}
									?>
									<th>ITEM DESCRIPTION</th>
									<th>ITEM CODE</th>
									<th>UNIT</th>
									<th>QTY</th>
									<th style = "display:none;">PRICE</th>
									<th style = "display:none;">SUB TOTAL</th>
									
								</thead>
		<?PHP
			$ctr = 1;
			$totalqty = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				if($row['isshipping'] == 0)
				{
					$totalqty = $totalqty +  $row['order_quantity'];
				}
				$total = $total +  $row['total_order_amount'];
				$pro = mysqli_fetch_assoc(mysqli_query($con,"Select product_code from lup_product where product_id = $row[product_id]"));
				$unit = mysqli_fetch_assoc(mysqli_query($con,"Select unit_description from inv_lup_unit where unit_id = $row[unit_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<?php
									if($app['status_collection'] == 0)
									{
									?>
										<td><button class = "btn btn-warning btn-flat btn-xs" id = "oddelete<?php echo $ctr;?>">X</button>
										<script>
												$("#oddelete<?php echo $ctr;?>").click(
													function()
													{
																		var r = confirm("confirm delete");

																		if(r == true)
																		{
																			$.post( 
																				'../php/main.php',
																				{
																				
																					oddelete:<?php echo $id;?>,
																					oddelete_id:'<?php echo $row["order_transaction_detail_id"];?>',
																					odcount:'<?php echo $count;?>'
																				},
																				function(data) {
																					$('#saleorderui').html(data);
																					
																				});
																		}
													}
												);
										</script>
										</td>
									<?php
									}
									?>
						<td><?php echo $row['product_description'];?></td>
						<td><?php echo $pro['product_code'];?></td>
						<td><?php echo $unit['unit_description'];?></td>
						<td><?php echo $row['order_quantity'];?></td>
						
						
						
					</tr>
				<?php
				$ctr++;
			}
			?>
					<tr >
						<td></td>
						<td></td>
						<td></td>
						<th>TOTAL: </th>
						<th id = "totalqtyui"><?php echo number_format($totalqty,2);?></th>
						
						
						
					</tr>
		</table>
		
	<?php
	
	
}

function order_cart($id)
{
	global $con;
	$query = mysqli_query($con,"Select * from order_transaction_detail where order_transaction_id = $id and isdeleted = 0");
	?>
		<table class = "table table-bordered table-hover table-sm table-responsive" id = "jomastertable">
								<thead>
									<th>#</th>
								
									<th>ITEM DESCRIPTION</th>
									<th>ITEM CODE</th>
									<th>UNIT</th>
									<th>Qty</th>
									<th>PRICE</th>
									<th>SUB TOTAL</th>
									
								</thead>
		<?PHP
			$ctr = 1;
			$totalqty = 0;
			$total = 0;
			while($row = mysqli_fetch_assoc($query))
			{
				if($row['isshipping'] == 0)
				{
					$totalqty = $totalqty +  $row['order_quantity'];
				}
				$total = $total +  $row['total_order_amount'];
				$pro = mysqli_fetch_assoc(mysqli_query($con,"Select product_code from lup_product where product_id = $row[product_id]"));
				$unit = mysqli_fetch_assoc(mysqli_query($con,"Select unit_description from inv_lup_unit where unit_id = $row[unit_id]"));
				?>
					<tr>
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['product_description'];?></td>
						<td><?php echo $pro['product_code'];?></td>
						<td><?php echo $unit['unit_description'];?></td>
						<td><?php echo $row['order_quantity'];?></td>
						<td><?php echo $row['product_price'];?></td>
						<td><?php echo $row['total_order_amount'];?></td>
						
					</tr>
				<?php
				$ctr++;
			}
			?>
			<tr>
						<td></td>
						<td></td>
						<th></th>
						<th></th>
						<th id = "totalqtyui"><?php echo number_format($totalqty,2);?></th>
						<td></td>
						<th id = "gtotalui"><?php echo number_format($total,2);?></th>
						<td></td>
					</tr>
		</table>
		
	<?php
	
	
}

/*function modlog($log,$issql)
{
	if($issql == 1)
	{
		$save = mysql_query("insert into log_sql set query = '$log', employee_no = '$_SESSION[employee]', date_added = NOW()");
	
	}
	else
	{
		mysql_query("insert into log_activity set activity = '$log', employee_no = '$_SESSION[employee]', date_added = NOW()");
	}
}*/

$baseurl = "http://localhost/";

function get_agent($username)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_id = $username"));
	
	return $row['fullname'];
}

function get_user_id($username)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_username = '$username'"));
	
	return $row['user_id'];
}
if(isset($_REQUEST['eq']))
{
?>
	<script>
	$('.result tr').click(function() {
    var rowId = $(this).data('rowKey');
	$("#key").val(rowId);
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
	((CONCAT(lastname,' ',firstname,' ',middlename) like '$q%') or customer_no like '$q%' or (CONCAT(firstname,' ',lastname,' ',middlename) like '$q%')) limit 0,30";
	
	//modlog($sql,1);
	
	$result = mysqli_query($con, $sql);

	echo "<div id = 'pro_id'>";
	echo "<table border  = '0', width = '100%' class = 'result'>";
	$ctr = 0;
	  
	while($row = mysqli_fetch_assoc($result)) {
	  
	  $emp_no = $row['customer_no'];
	 
	  echo "<tr data-row-key='".$emp_no."'>";
	 
	  $fname = $row['lastname']." ".$row['firstname']." ".$row['middlename'];
	  
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
function shipping_info($id,$allowedit)
{
	//echo $allowedit;
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from order_transaction where order_transaction_id = $id"));
	$shipid = 0;
	?>
		<script>
			//alert('<?php echo $row['customer_shipping_address_id'];?>');
		</script>
	<?php
	if(!empty($row['customer_shipping_address_id']));
	{
		?>
		<script>
			//alert('<?php echo $row['customer_shipping_address_id'];?>');
		</script>
		<?php
		$shipid = $row['customer_shipping_address_id'];
	}
	$ship = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_shipping_address where customer_shipping_address_id = $shipid"));
	
	$address = "";
	if(!empty($ship))
	{
	$add = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_region, lup_province, lup_city_town, lup_barangay
	where lup_region.region_id = $ship[region_id]
	and lup_province.province_id = $ship[province_id]
	and lup_city_town.city_town_id = $ship[city_town_id]
	and lup_barangay.barangay_id = $ship[barangay_id]"));
	$address = $ship['street_name']." ".$add['barangay_name']." ".$add['city_town_name']." ".$add['province_name']." ".$add['region_name'];
	
	}
	$cou = mysqli_fetch_assoc(mysqli_query($con, "Select * from lup_courier where courier_id = $row[courier_id]"));

	?>
		<div id = "shippingui" STYLE = "width:100%;">
			<table class = "table table-condensed table-sm">
								<tr>
									<td>COURIER: <b><?php echo $cou['courier_name'];?></b></td>
									<td>SHIPPING ADDRESS: <b><?php echo $address;?></b></td>
									<td>
									<?php
									if($allowedit == 1)
									{
										if($row['status_shipping'] != 4)
										{
									?>
											<button class = "btn btn-primary btn-flat btn-sm" id = "edit">EDIT</button></td>
									<?php
										}
									}
									?>
								</tr>
								<tr>
									<td>CONSIGNEE: <b><?php echo $row['ship_to'];?></b></td>
									<td>TRACKING NUMBER: <b><?php echo $row['reference_no_courier'];?></b></td>
								</tr>
						<script>
							$("#edit").click(
								function()
								{
									$.post( 
																'../php/main.php',
																{
																	shipeditui:<?php echo $id;?>
																},
																function(data) {
																	$('#shippingui').html(data);		
																});
								}
							);
						</script>
			</table>
		</div>
	<?php
}

function employee_info($emp)
{
	$row = mysql_fetch_assoc(mysql_query("Select * from usertb where employee_no = '$emp'"));
	$pos = mysql_fetch_assoc(mysql_query("Select * from user_typetb where ID = $row[position]"));
	?>
			<table class = "table table-condensed table-sm">
								<tr>
									<td>Employee Number: <b><?php echo $row['employee_no'];?></b></td>
									<td>Full Name: <b><?php echo $row['lname'].", ".$row['fname']." ".$row['mname'];?></b></td>
								</tr>
								<tr>
									<td>Position: <b><?php echo $pos['description'];?></b></td>
									<td></td>
								</tr>
			</table>
	<?php
}

function order_info($id)
{
	global $con; 
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from order_transaction where order_transaction_id = $id"));
	
	$rem = "";
	$remno = "";
							if(!empty($row['bank_id']))
							{
								$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_bank where bank_id = $row[bank_id]"));
								
								$rem = $bank['bank_name'];
								$remno = $row['reference_payment'];
								//echo $row['bank_id']." ";
							}
							elseif(!empty($row['remittance_center_id']))
							{
								$bank = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_remittance_center where remittance_center_id = $row[remittance_center_id]"));
								
								$rem = $bank['remittance_center_name'];
								$remno = $row['reference_payment'];
							}
							else
							{
								$rem = "na";
								$remno = $row['reference_payment'];
							}
	?>
			<table class = "table table-condensed table-sm">
								<tr>
									<td>ORDER TRANSACTION NUMER: <b><?php echo $row['order_transaction_no'];?></b></td>
									<td>REFERENCE NUMBER:<b><?php echo $remno;?></b></td>
								</tr>
								<tr>
									<td>BANK/REMITTANCE CENTER: <b><?php echo $rem;?></b></td>
									<td>ORDER REMARKS:<b><?php echo $row['remarks_order'];?></b></td>
								</tr>
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
									$ctype = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type]"));
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





function userlist($userfield,$userhidden,$tech,$biller)
{
	if(!empty($tech))
	{
		$type = mysql_fetch_assoc(mysql_query("Select * from user_typetb where istech = 1"));
		$query = mysql_query("Select * from usertb where block = 0 and position = $type[ID]");
		
		modlog("Select * from user_typetb where istech = 1",1);
		modlog("Select * from usertb where block = 0 and position = $type[ID]",1);
	}
	elseif(!empty($biller))
	{
		$type = mysql_fetch_assoc(mysql_query("Select * from user_typetb where isbiller = 1"));
		$query = mysql_query("Select * from usertb where block = 0 and position = $type[ID]");
		
		modlog("Select * from user_typetb where isbiller = 1",1);
		modlog("Select * from usertb where block = 0 and position = $type[ID]",1);
	}
	else
	{
		$query = mysql_query("Select * from usertb where block = 0");
		modlog("Select * from usertb where block = 0",1);
	}
	//echo $userhidden;
	
	?>
		<table class = "table table-bordered table-sm" id = "emptable">
						<thead>	
							<th></th>
							<th>EMPLOYEE NUMBER</th>
							<th>FULLNAME</th>
							<th></th>
						</thead>
			<?php
			$ctr = 1;
			while($row = mysql_fetch_assoc($query))
			{
				?>
					<tr>
						<input style = "display:none;" type = "text" id = "ufieldrow<?php echo $ctr;?>" value = "<?php echo $row['lname']." ".$row['fname']." ".$row['mname'];?>">
						<input  style = "display:none;" type = "text" id = "uhiddenrow<?php echo $ctr;?>" value = "<?php echo $row['employee_no'];?>">
						<td><?php echo $ctr;?></td>
						<td><?php echo $row['employee_no'];?></td>
						<td><?php echo $row['lname']." ".$row['fname']." ".$row['mname'];?></td>
						<td>
						<?php
						if(!empty($userfield))
						{
						?>
							<a href = "" data-dismiss="modal" aria-label="Close" id = "selectuser<?php echo $ctr;?>" class = "btn btn-primary btn-sm">SELECT</a>
						<?php	
						}
						?>
						</td>
					</tr>
					<script>
					$("#selectuser<?php echo $ctr;?>").click(
													function(e)
													{
														e.preventDefault();
														
														//alert($("#ufieldrow<?php echo $ctr?>").val());
														
														$("#<?php echo $userfield;?>").val($("#ufieldrow<?php echo $ctr;?>").val());
														$("#<?php echo $userhidden;?>").val($("#uhiddenrow<?php echo $ctr;?>").val());
														
														
													}
					);
					</script>
				<?php
				$ctr++;
			}
			?>
		<table>
		<script>
											
														$("#emptable").DataTable();
												
										</script>
	<?php
}



?>