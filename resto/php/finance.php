<?php
include('connect.php');
include("general.php");

if(isset($_REQUEST['rebateui']))
{
	?>
	<h2>CUSTOMER REBATE MANAGEMENT</h2>
	<div id = "rebui">
	<div class="box">
			<div class="box-body">
				<form id = "cmsearchform" method = "POST">
					<div class = "row">
						<div class="col-md-3">
							 <div class="form-group">								
								<label for="age">SEARCH CUSTOMER:</label>
								<input type="text" id="ref" name="rebref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
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
																						url :  'php/finance.php',
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
if(isset($_POST['rebref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_no = '$rebref'"));
	
	if(!empty($check))
	{?>
		<script>
			$.post( 
																	'php/finance.php',
																	 { rebateui2:'<?php echo $check["customer_id"];?>' },
																	 function(data) {
																		$('#rebui').html(data);
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
if(!empty($_REQUEST['rebateui2']))
{
	$id = $_REQUEST['rebateui2'];
	
	$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(rebate) as total from ledger_rebate where customer_id = $id and isdeleted = 0"));
	
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
										<div class="col-md-4">
											<div class="form-group">
												<label for="service_description_edit">Enter Points: &nbsp;</label>
												<input type = "hidden" name = "rcus" value = "<?php echo $id;?>">
												<input type="text" class = "form-control" id = "rpoint" name = "rpoint" data-validation="required"
												data-validation-error-msg="Enter Points">
											
											</div>
										</div>
										<div class="col-md-5" style = "padding-top:25px;">
											<button class = "btn btn-success btn-flat btn-sm">SAVE</button>
											<button class = "btn btn-danger btn-flat btn-sm" id = "close">CLOSE</button>
										</div>
										<div class="col-md-3" id = "totalrebateui">
											<h3>TOTAL REBATE: <?php echo number_format($bal['total'],2);?></h3>
										</div>
										

									  </div>
									
							</form>
							<script>
											$("#close").click(
												function(e)
												{
													e.preventDefault();
													$.post( 
																	'php/finance.php',
																	 { rebateui:1 },
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
																			url :  'php/finance.php',
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
					<?php rebate_ledger('',$id,'','','',0);?>
				</div>
		</div>
				
	<?php
}
if(isset($_POST['rcus']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
		$user = get_user_id($_SESSION['c_craft']);
		$agent = get_agent($user);
		$branch = get_branch($user);
		
		insert_rebate($branch,$rcus,0,0,$rpoint);
			$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(rebate) as total from ledger_rebate where customer_id = $rcus and isdeleted = 0"));
			
		?>
			<script>
				$("#totalrebateui").html('<h3>TOTAL REBATE: <?php echo number_format($bal['total'],2);?></h3>');
				notify("New Points Added","#colalert");
			</script>
		<?php
		rebate_ledger('',$rcus,'','','',0);
		
	
		
		
}
if(isset($_POST['rebdel']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	$del = mysqli_query($con,"Update ledger_rebate set isdeleted = 1 where rebate_id = $rebdel");
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_rebate where rebate_id = $rebdel"));
	
			
			$user = get_user_id($_SESSION['c_craft']);
			$agent = get_agent($user);
			$branch = get_branch($user);
	
			
			
	if(!$del)
	{
		
		?>
			<script>
				notify("Error Deleting Ledger Transaction , contact the system administrator");
			</script>
		<?php
	}
	
	rebate_ledger('',$row['customer_id'],'','','',0);
	$bal = mysqli_fetch_assoc(mysqli_query($con,"Select SUM(rebate) as total from ledger_rebate where customer_id = $row[customer_id] and isdeleted = 0"));
	?>
	<script>
		$("#totalrebateui").html('<h2>Total REBATE: <?php echo number_format($bal['total'],2);?></h2>');
	</script>
	<?php
	
}
if(isset($_REQUEST['salesui']))
{
	$level = $_REQUEST['salesui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
	<h2>SALES MONITORING</H2>
	<div class="box">
		<div class="box-body">
				<form id = "pfilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>Date From:</label>
												<input type = "date" class = "form-control" name = "sdfrom" id = "sdfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
											</div>		
										</div>
										<div class="col-md-4">
											<div class = "form-group">
												<label>Enter Date To:</label>
												<input type = "date" class = "form-control" name = "sdto" id = "sdto" data-validation="date" 
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
															
															<select  name = "sbranch" id = "sbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "sbranch" name = "sbranch">
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
																		
																		//alert($("#sfapp").val());
																			$.post( 
																				'php/finance.php',
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
																				 $("#smonitorui").html(loading);
																						$.ajax({
																							url :  'php/finance.php',
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
if(isset($_POST['sdto']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div class="box box">
		<div class="box-body">
			<?php smonitor('',$sbranch,$sdfrom,$sdto,0);?>
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
if(!empty($_REQUEST['paymentui']))
{
	$level = $_REQUEST['paymentui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	?>
	<h2>PAYMENT MONITORING</H2>
	<div class="box">
		<div class="box-body">
				<form id = "pfilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>Date From:</label>
												<input type = "date" class = "form-control" name = "pdfrom" id = "pdfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
											</div>		
										</div>
										<div class="col-md-4">
											<div class = "form-group">
												<label>Enter Date To:</label>
												<input type = "date" class = "form-control" name = "pdto" id = "pdto" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="age">COLLECTION FOR:</label>
											
												<select name = "ptranfrom" id = "ptranfrom" class="form-control" data-validation="required"
																					data-validation-error-msg="Select TRANSACTION FROM">
													<option value = "all" hidden "Selected">ALL</option>					
													<option value = "1">CURRENT</option>
													<option value = "0">PREVIOUS</option>
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
														
														<select  name = "pbranch" id = "pbranch" class="form-control" data-validation="required"
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
											<input type = "hidden" value = "<?php echo $branch;?>" name = "pbranch" id = "pbranch">
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
																				'php/finance.php',
																				{
																					pppbranch:$("#pbranch").val(),
																					pppdto:$("#pdto").val(),
																					pppdfrom:$("#pdfrom").val(),
																					ppptranfrom:$("#ptranfrom").val()
																					
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
																							url :  'php/finance.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#pmonitorui").html(data);
																								
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
		<div id = "pmonitorui">
				
		</div>
	<?php
}
if(isset($_POST['pdto']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div class="box box">
		<div class="box-body">
			<?php 
			pmonitor($pbranch,$pdfrom,$pdto,0,$ptranfrom);
			//pmonitor($pbranch,$pdfrom,$pdto,0);?>
		</div>
	</div>
	<?php
}

if(!empty($_REQUEST['ppdto'])||!empty($_REQUEST['pscus']))
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
if(isset($_REQUEST['freportui']))
{
	$level = $_REQUEST['freportui']
	?>
		<div class="box" style = "margin-top:10px;">
			<div class="box-header with-border">
				<h3 class="box-title">FINANCIAL REPORT</h3>
			</div>
			<div class = "box-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
					  <li class="active"><a href="#tab_1" ID = "receive" data-toggle="tab">RECEIVABLES</a></li>
					  <li><a href="#tab_1" ID = "fin" data-toggle="tab">CASH ON HAND REPORT</a></li>
					</ul>
					<script>
						$('#tab_1').html(loading);	
						$.post( 
																		'php/finance.php',
																		{
																			recrui:<?php echo $level;?>
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
						$("#fin").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/finance.php',
																		{
																			finreportui:<?php echo $level;?>
																		},
																		function(data) {
																			$('#tab_1').html(data);		
																		});
							}
						);
						
						
						$("#receive").click(
							function(e)
							{
								e.preventDefault();
								$('#tab_1').html(loading);	
								$.post( 
																		'php/finance.php',
																		{
																			recrui:<?php echo $level;?>
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

if(isset($_REQUEST['collectionui']))
{
	$level = $_REQUEST['collectionui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
	<h2>COLLECTION REPORT</H2>
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
										<div class="col-md-3" style = "display:none;">
											<div class="form-group">
												<label for="age">COLLECTION FOR:</label>
											
												<select name = "ctranfrom" id = "ctranfrom" class="form-control" data-validation="required"
																					data-validation-error-msg="Select TRANSACTION FROM">
													<option value = "all" "Selected">ALL</option>					
													<option value = "1">CURRENT</option>
													<option value = "0">PREVIOUS</option>
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
																				'php/finance.php',
																				{
																					pcbranch:$("#cbranch").val(),
																					pcdto:$("#cdto").val(),
																					pcdfrom:$("#cdfrom").val(),
																					pctranfrom:$("#ctranfrom").val()
																					
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
																							url :  'php/finance.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#cmonitorui").html(data);
																								
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
		<div id = "cmonitorui">
				
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
			<?php collection_report($cbranch,$cdfrom,$cdto,0,$ctranfrom);?>
		</div>
	</div>
	<?php
}

if(isset($_REQUEST['pcdto']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$bheader = "ALL BRANCH";
		$theader = "ALL COLLECTIONS";
		if($psbranch != 'all')
		{
			$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $pcbranch"));
			$bheader = $br['branch_description']." Branch";
		}
		if($pctranfrom != 'all')
		{
			if($pctranfrom == 1)
			{
				$theader = "CURRENT COLLECTIONS";
			}
			else
			{
				$theader = "PREVIOUS COLLECTIONS";
			}
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">COLLECTION REPORT</h4>
			<h4 style = "text-align:center"><?php echo $bheader;?></h4>
			<h4 style = "text-align:center"><?php echo $theader;?></h4>
			<h4 style = "text-align:center"><?php echo $pcdfrom." to ".$pcdto;?></h4>
			
			<?php
				collection_report($pcbranch,$pcdfrom,$pcdto,1,$pctranfrom);
				
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
if(isset($_REQUEST['recrui']))
{
	$level = $_REQUEST['recrui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
	<h2>RECEIVABLE REPORT</H2>
	<div class="box">
		<div class="box-body">
				<form id = "recfilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>Date From:</label>
												<input type = "date" class = "form-control" name = "recdfrom" id = "recdfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
											</div>		
										</div>
										<div class="col-md-4">
											<div class = "form-group">
												<label>Enter Date To:</label>
												<input type = "date" class = "form-control" name = "recdto" id = "recdto" data-validation="date" 
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
															
															<select  name = "recbranch" id = "recbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "recbranch" name = "recbranch">
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
																		form:'#recfilterform',
																		validateOnBlur : false,
																		errorMessagePosition : 'top',
																		modules : 'security',
																		onSuccess : function($form) {
																		
																		//alert($("#sfapp").val());
																			$.post( 
																				'php/finance.php',
																				{
																					precbranch:$("#recbranch").val(),
																					precdto:$("#recdto").val(),
																					precdfrom:$("#recdfrom").val()
																					
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
																		form:'#recfilterform',
																		validateOnBlur : false,
																		errorMessagePosition : 'top',
																		modules : 'security',
																		onSuccess : function($form) {
																		
																			 var formData = $('#recfilterform').serializeArray();
																				 //var formData = new FormData($('#regform')[0]);
																				 $("#recmonitorui").html(loading);
																						$.ajax({
																							url :  'php/finance.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#recmonitorui").html(data);
																								
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
		<div id = "recmonitorui">
				
		</div>
	<?php
}
if(isset($_POST['recdto']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
	?>
	<div class="box box">
		<div class="box-body">
			<?php receivable($recbranch,$recdfrom,$recdto,0);?>
		</div>
	</div>
	<?php
}

if(isset($_REQUEST['precdto']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$bheader = "ALL BRANCH";
		
		if($psbranch != 'all')
		{
			$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $precbranch"));
			$bheader = $br['branch_description']." Branch";
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h3 style = "text-align:center">RECEIVABLES</h3>
			<h4 style = "text-align:center"><?php echo $bheader;?></h4>
			<h4 style = "text-align:center"><?php echo $precdfrom." to ".$precdto;?></h4>
			
			<?php
				receivable($precbranch,$precdfrom,$precdto,1);
				
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
if(isset($_REQUEST['expenseui']))
{
	
	?>
	
		<h2>EXPENSES & CASH OUTS</h2>
	
		<div class="box">
			
			<div class="box-body">
					<form method = "POST" id = "newexform" enctype="multipart/form-data">
						<div class = "row">
							<div class="col-md-3" STYLE = "display:none;">
								<div class = "form-group">
									<label>CUSTOMER TYPE GROUP:</label>
									<?PHP
										$tgquery = mysqli_query($con,"Select * from lup_customer_type_group
										where isdeleted = 0");
									?>
									<select name = "extypegroup" id = "extypegroup" class="form-control" data-validation="required"
													data-validation-error-msg="Select CUSTOMER TYPE GROUP:">
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
									<label>EXPENSE/CASH OUT</label>
									<select name = "extype" id = "extype" class="form-control" data-validation="required"
													data-validation-error-msg="Select EXPENSE/CASH OUT">
													<option  hidden "selected"></option>
													<option  value = "0">EXPENSE</option>
													<option  value = "1">CASH OUT</option>
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
												'php/finance.php',
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
							<div class="col-md-2">
								<label>(PROOF)SCANNED IMAGE:</label>
								<div class="input-group">
									<input type="file" class="form-control" name = "exproof" id = "exproof">
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
																var formData = $('#newexform')[0];
																$.ajax({
																						url: 'php/finance.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#expenseui2").html(data);
																							
																						},
																						error: function() 
																						{
																							alert('Sending failed');
																						} 	        
																				   });
																				   
																 /*var formData = $('#newexform').serializeArray();
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
																			});*/

															  return false; // Will stop the submission of the form
															},
														});
					</script>					
						
					
				
			</div>
		</div>
		<div class="box">
			
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
								<div class = "form-group" STYLE = "display:none;">
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
								<div class = "form-group" STYLE = "display:none;">
									<label>CUSTOMER TYPE GROUP:</label>
									<?PHP
										$tgquery = mysqli_query($con,"Select * from lup_customer_type_group
										where isdeleted = 0");
									?>
									<select name = "pextypegroup" id = "pextypegroup" class="form-control" data-validation="required"
													data-validation-error-msg="Select EXPENSE/OTHER DEPOSIT">
													<option  hidden "selected"></option>
													<option value = "BOTH">ALL</option>
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
																	'php/finance.php',
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
																				url :  'php/finance.php',
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
		<div class="box">
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
												'php/finance.php',
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
												'php/finance.php',
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

if(isset($_POST['examount']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	
	$check = mysqli_num_rows(mysqli_query($con,"Select * from order_expense where expense_description = '$exdes'
	and STR_TO_DATE(expense_date,'%Y-%m-%d') = STR_TO_DATE('$exdate','%Y-%m-%d')"));
	
	$user = get_user_id($_SESSION['c_craft']);
	$check = 0;
	if($check == 0)
	{
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
			$found = mysqli_num_rows(mysqli_query($con, "Select * from order_expense where result = '$result'"));
			if($found == 0)
				break;
			else
				$i = 0;
		}
		
			$user = get_user_id($_SESSION['c_craft']);
			$agent = get_agent($user);
			$branch = get_branch($user);
								
		$save = mysqli_query($con,"insert into order_expense set
		result = '$result',
		expense_description = '$exdes',
		branch_id = $branch,
		is_other_deposit = '$extype',
		customer_type_group_id = 0,
		expense_amount = $examount,
		expense_date = '$exdate',
		added_by = $user");
		
		$name = $_FILES['exproof']['name'];
		$type = $_FILES['exproof']['type'];
		$size = $_FILES['exproof']['size'];
	
		$res = mysqli_fetch_assoc(mysqli_query($con,"Select expense_id from order_expense where result = '$result'"));
		
		$sss = $res['expense_id']."_".$_FILES['exproof']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					if (!file_exists('../images/expense/')) {
						mkdir('../images/expense/', 0777, true);
					}
					$sourcePath = $_FILES['exproof']['tmp_name'];
					$targetPath = "../images/expense/".basename($res['expense_id']."_".$_FILES['exproof']['name']);

						if(is_uploaded_file($_FILES['exproof']['tmp_name'])) 
						{
							echo "
								<script>
									$('#upload_status').html('Uploading Image, Please Wait');
								</script>
							";
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									mysqli_query($con, "Update order_expense set proof  = '$sss' where 
									expense_id = $res[expense_id]");
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
			
		if($save)
		{
			?>
			<script>
				$('#newexform').trigger("reset");
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
	$user = get_user_id($_SESSION['c_craft']);
	
	mysqli_query($con,"update order_expense set
	expense_amount = $examountsave,
	modified_by = $user
	where expense_id = $examountid");
	
	echo number_format(total_expense($examountdfrom,$examountdto,$examountgroup),2);
}

if(isset($_REQUEST['deleteexpense']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$user = get_user_id($_SESSION['c_craft']);
	
	mysqli_query($con,"update order_expense set
	isdeleted = 1,
	deleted_by = $user
	where expense_id = $deleteexpense");
	
	expense($deleteexpensedfrom,$deleteexpensedto,$deleteexpensetype,0,$deleteexpensegroup);
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
		
		$header = "EXPENSES & CASH OUTS";
	
		
		
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center"><?php echo $header;?></h4>
			<h4 style = "text-align:center"><?php echo $psedfrom." to ".$psedto;?></h4>
			
			<?php
				expense($psedfrom,$psedto,$_REQUEST['pextypee'],1,$_REQUEST['pextypegroup']);
				
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
													'php/finance.php',
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
if(isset($_REQUEST['viewproof']))
{
	$id = $_REQUEST['viewproof'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select proof from order_expense where expense_id = $id"));
	?>
	<div class="box">
			<div class="box-body">
				<form id = 'changeproofform' method = "POST" enctype="multipart/form-data">
					<div class = "row">
						<div class="col-md-4">
									<label>(PROOF)SCANNED IMAGE:</label>
									<div class="input-group">
									<input type = "hidden" name = "cexid" value = "<?php echo $id;?>">
										<input type="file" class="form-control" name = "cexproof" id = "cexproof" data-validation="required"
														data-validation-error-msg="(PROOF)SCANNED IMAGE is required">
									</div>
						</div>
						<div class="col-md-2" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">CHANGE</button>
									</div>	
						</div>
							
					</div>
				</form>
				<script>
					
												$.validate({
															form:'#changeproofform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																var formData = $('#changeproofform')[0];
																$.ajax({
																						url: 'php/finance.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#proofui").html(data);
																							
																					
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
			</div>
	</div>
	<div class="box">
			<div class="box-body" id = "proofui">
				<img src = "images/expense/<?php echo $row['proof'];?>" width  = "100%">
			</div>
	</div>
	<?php
}
if(isset($_POST['cexid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$name = $_FILES['cexproof']['name'];
		$type = $_FILES['cexproof']['type'];
		$size = $_FILES['cexproof']['size'];
	
		$res = mysqli_fetch_assoc(mysqli_query($con,"Select expense_id from order_expense where expense_id = $cexid"));
		
		$sss = $res['expense_id']."_".$_FILES['cexproof']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					if (!file_exists('../images/expense/')) {
						mkdir('../images/expense/', 0777, true);
					}
					$sourcePath = $_FILES['cexproof']['tmp_name'];
					$targetPath = "../images/expense/".basename($res['expense_id']."_".$_FILES['cexproof']['name']);

						if(is_uploaded_file($_FILES['cexproof']['tmp_name'])) 
						{
							echo "
								<script>
									$('#upload_status').html('Uploading Image, Please Wait');
								</script>
							";
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									mysqli_query($con, "Update order_expense set proof  = '$sss' where 
									expense_id = $res[expense_id]");
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
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select proof from order_expense where expense_id = $cexid"));
		?>
		<img src = "images/expense/<?php echo $row['proof'];?>" width  = "100%">
		<?php
			
}
if(isset($_REQUEST['pppdto']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$bheader = "ALL BRANCH";
		$theader = "ALL PAYMENTS";
		if($pppbranch != 'all')
		{
			$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $pppbranch"));
			$bheader = $br['branch_description']." Branch";
		}
		if($ppptranfrom != 'all')
		{
			if($ppptranfrom == 1)
			{
				$theader = "CURRENT PAYMENTS";
			}
			else
			{
				$theader = "PREVIOUS PAYMENTS";
			}
		}
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">PAYMENT MONITORING REPORT</h4>
			<h4 style = "text-align:center"><?php echo $bheader;?></h4>
			<h4 style = "text-align:center"><?php echo $theader;?></h4>
			<h4 style = "text-align:center"><?php echo $pppdfrom." to ".$pppdto;?></h4>
			
			<?php
				pmonitor($pppbranch,$pppdfrom,$pppdto,1,$ppptranfrom);
				
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
if(!empty($_REQUEST['dpmid']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		//$uamount = $spmamount *-1;
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_sales_income where sales_income_id = $dpmid"));	
		
		mysqli_query($con,"Update ledger_sales_income set isdeleted = 1 where sales_income_id = $dpmid");
		
		if($row['remarks_payment']!= 0)
		{
				mysqli_query($con,"Update ledger_receivable set isdeleted = 1 where remarks_payment = $row[remarks_payment]");	
				
				$squery = mysqli_query($con,"Select * from ledger_sales_income where pos_sales_id = $row[pos_sales_id] and settlement_type_id != 0 and isdeleted = 0");
			
					$stotal = 0;
					while($srow = mysqli_fetch_assoc($squery))
					{
													$rebatecheck = mysqli_num_rows(mysqli_query($con,"Select * from settings_settlement_mapping where settings_settlement_mapping.settlement_type_id = $srow[settlement_type_id] and settings_settlement_mapping.with_rebate = 1"));
													
													
													//echo $rebatecheck;
													$iamount = 0;
													if($rebatecheck != 0)
													{
														$iamount = $srow['amount']*-1; 
														$stotal = $stotal + $iamount;
													}
					}
			
				$rebate = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_rebate"));
				$reb = 0;
				$reba = 0;
				$tsales = 0;
			
				$reb = $rebate['rebate_point'];
				$reba = $rebate['rebate_amount'];
				$tsales = $stotal;
				$ramount = ($tsales/$reba*$reb);
				
				mysqli_query($con,"Update ledger_rebate set 
				total_sales = $tsales,
				rebate = $ramount,
				rate_sales = $reba,
				rate_points = $reb
				where pos_sales_id = $row[pos_sales_id]");
			
			$spent = mysqli_num_rows(mysqli_query($con,"Select * from settings_settlement_mapping where 
			settings_settlement_mapping.settlement_type_id = $row[settlement_type_id]
			and settings_settlement_mapping.charge_to_customer = 1"));
			
			if($spent != 0)
			{
				mysqli_query($con,"Update credit_line_transaction set isdeleted = 1 where source_id = $row[pos_sales_id]");
			}
		}
		pmonitor($dpmbranch,$dpmdfrom,$dpmdto,0,$dpmfrom);
}

if(!empty($_REQUEST['drpmid']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		//$uamount = $spmamount *-1;
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where receivable_no = '$drpmid'"));	
		
		mysqli_query($con,"Update ledger_receivable set isdeleted = 1 where receivable_no = '$drpmid'");
		
		
			//$rec = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where transaction_apply_to = '$row[transaction_apply_to]' and transaction_amount > 0"));
			
			//$spent = mysqli_num_rows(mysqli_query($con,"Select * from settings_settlement_mapping where 
			//settings_settlement_mapping.settlement_type_id = $row[settlement_type_id]
			//and settings_settlement_mapping.charge_to_customer = 1"));
			
			//if($spent != 0)
			//{
				mysqli_query($con,"Update credit_line_transaction set isdeleted = 1 where transaction_remarks = '$row[transaction_source_number]'");
			//}
		
		pmonitor($drpmbranch,$drpmdfrom,$drpmdto,0,$drpmfrom);
}


if(!empty($_REQUEST['spmid']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$uamount = $spmamount *-1;
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_sales_income where sales_income_id = $spmid"));		
		mysqli_query($con,"Update ledger_sales_income set settlement_type_id = $spmsettle, amount = $uamount where sales_income_id = $spmid");
		
		if($row['remarks_payment']!= 0)
		{
			//$ccheck = mysqli_num_rows(mysqli_query($con,"Select * from settings_settlement_mapping where settings_settlement_mapping.settlement_type_id = $row[settlement_type_id] and settings_settlement_mapping.charge_to_customer = 1"));
			//$camount = $spmamount;
			
			//if($ccheck == 0)
			//{
				//$camount = $spmamount*-1;
			//}
			//mysqli_query($con,"Update ledger_receivable set settlement_type_id = $spmsettle, transaction_amount = $camount where remarks_payment = $row[remarks_payment]");
			
			$squery = mysqli_query($con,"Select * from ledger_sales_income where pos_sales_id = $row[pos_sales_id] and settlement_type_id != 0 and isdeleted = 0");
			
			$stotal = 0;
			while($srow = mysqli_fetch_assoc($squery))
			{
											$rebatecheck = mysqli_num_rows(mysqli_query($con,"Select * from settings_settlement_mapping where settings_settlement_mapping.settlement_type_id = $srow[settlement_type_id] and settings_settlement_mapping.with_rebate = 1"));
											
											
											//echo $rebatecheck;
											$iamount = 0;
											if($rebatecheck != 0)
											{
												$iamount = $srow['amount']*-1; 
												$stotal = $stotal + $iamount;
											}
			}
			
				$rebate = mysqli_fetch_assoc(mysqli_query($con,"Select * from settings_rebate"));
				$reb = 0;
				$reba = 0;
				$tsales = 0;
			
				$reb = $rebate['rebate_point'];
				$reba = $rebate['rebate_amount'];
				$tsales = $stotal;
				$ramount = ($tsales/$reba*$reb);
				
				mysqli_query($con,"Update ledger_rebate set 
				total_sales = $tsales,
				rebate = $ramount,
				rate_sales = $reba,
				rate_points = $reb
				where remarks_sales = $row[remarks_payment]");
			
				//mysqli_query($con,"Update credit_line_transaction set transaction_amount = $spmamount where source_id = $row[remarks_payment]");
			
		}
		pmonitor($spmbranch,$spmdfrom,$spmdto,0,$spmfrom);
}

if(!empty($_REQUEST['srpmid']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		
		$uamount = $srpmamount *-1;
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from ledger_receivable where receivable_no = '$srpmid'"));		
		mysqli_query($con,"Update ledger_receivable set settlement_type_id = $srpmsettle, transaction_amount = $uamount where receivable_no = $srpmid");


		mysqli_query($con,"Update credit_line_transaction set transaction_amount = $srpmamount where transaction_remarks = $row[transaction_source_number]");
		pmonitor($srpmbranch,$srpmdfrom,$srpmdto,0,$srpmfrom);
}


if(!empty($_REQUEST['finreportui']))
{
	$level = $_REQUEST['finreportui'];
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
	
	?>
	<h2>CASH ON HAND REPORT</H2>
	<div class="box">
		<div class="box-body">
				<form id = "pfilterform" method = "POST">
					
									<div class = "row">	
										<div class="col-md-4">
											<div class = "form-group">
												<label>Date From:</label>
												<input type = "date" class = "form-control" name = "frdfrom" id = "frdfrom" data-validation="date" 
												value = "<?php echo date('Y-m-d');?>"
												data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
											</div>		
										</div>
										<div class="col-md-4">
											<div class = "form-group">
												<label>Enter Date To:</label>
												<input type = "date" class = "form-control" name = "frdto" id = "frdto" data-validation="date" 
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
															
															<select  name = "frbranch" id = "frbranch" class="form-control" data-validation="required"
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
												<input type = "hidden" value = "<?php echo $branch;?>" id = "frbranch" name = "frbranch">
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
																				'php/finance.php',
																				{
																					pfrbranch:$("#frbranch").val(),
																					pfrdto:$("#frdto").val(),
																					pfrdfrom:$("#frdfrom").val()
																					
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
																							url :  'php/finance.php',
																							type : 'post',
																							datatype : 'json',
																							data : formData,
											
																							success : function(data) {
																								$("#freportui2").html(data);
																								
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
		<div id = "freportui2">
				
		</div>
	<?php
}
if(isset($_POST['frdto']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$total = 0;
	?>
	<div class="box box">
		<div class="box-body">
			<?php 
			echo "<h4 style = 'font-weight:bold;'>SALES SUMMARY</H4>";
			sales_summary($frbranch,$frdfrom,$frdto,0);
			//echo "<br>";
			echo "<h4 style = 'font-weight:bold;'>EXPENSES & CASH OUTS</H4>";
			expense_summary($frdfrom,$frdto,'BOTH',$frbranch);
			echo "<br>";
			
			$total_sales = total_sales($frdfrom,$frdto,$frbranch);
			$total_prev = total_prev($frdfrom,$frdto,$frbranch);
			$total_expense = total_expense_report($frbranch,$frdfrom,$frdto);
			$total_cash_out = total_cashout_report($frbranch,$frdfrom,$frdto);
			$total_rec = total_receivable_report($frbranch,$frdfrom,$frdto);
			
			$total = ($total_sales+$total_prev)-($total_expense+$total_cash_out+$total_rec);
			echo "<h3 style = 'font-weight:bold;'>SUMMARY</H3>";
			?>
			<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<tr>
									<th style = "text-align:right;">PREVIOUS COLLECTION:</th>
									<th style = "text-align:right;"><?php echo number_format($total_prev,2);?></th>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL SALES:</th>
									<th style = "text-align:right;"><?php echo number_format($total_sales,2);?></th>
								</TR>
								

								<tr>
									<th style = "text-align:right;">LESS:</th>
									<td style = "text-align:right;"></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL EXPENSES:</th>
									<td style = "text-align:right;"><?php echo number_format($total_expense,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL CASH OUT:</th>
									<td style = "text-align:right;"><?php echo number_format($total_cash_out,2);?></td>
								</TR>								
								<tr>
									<th style = "text-align:right;">RECEIVABLES:</th>
									<td style = "text-align:right;"><?php echo number_format($total_rec,2);?></td>
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

if(isset($_REQUEST['pfrdto']))
{
		foreach($_REQUEST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		}
		$bheader = "ALL BRANCH";
		
		if($psbranch != 'all')
		{
			$br = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_branch where branch_id = $pfrbranch"));
			$bheader = $br['branch_description']." Branch";
		}
		
	?>
	<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CASH ON HAND REPORT</h4>
			<h4 style = "text-align:center"><?php echo $bheader;?></h4>
			<h4 style = "text-align:center"><?php echo $pfrdfrom." to ".$pfrdto;?></h4>
			
			<?php 
			echo "<h4 style = 'font-weight:bold;'>SALES SUMMARY</H4>";
			sales_summary($pfrbranch,$pfrdfrom,$pfrdto,0);
			//echo "<br>";
			echo "<h4 style = 'font-weight:bold;'>EXPENSES & CASH OUTS</H4>";
			expense_summary($pfrdfrom,$pfrdto,'BOTH',$pfrbranch);
			echo "<br>";
			
			$total_sales = total_sales($pfrdfrom,$pfrdto,$pfrbranch);
			$total_prev = total_prev($pfrdfrom,$pfrdto,$pfrbranch);
			$total_expense = total_expense_report($pfrbranch,$pfrdfrom,$pfrdto);
			$total_cash_out = total_cashout_report($pfrbranch,$pfrdfrom,$pfrdto);
			$total_rec = total_receivable_report($pfrbranch,$pfrdfrom,$pfrdto);
			
			$total = ($total_sales+$total_prev)-($total_expense+$total_cash_out+$total_rec);
			echo "<h3 style = 'font-weight:bold;'>SUMMARY</H3>";
			?>
			<table class = "table table-bordered table-hover table-sm" id = "pmtable2">
								<tr>
									<th style = "text-align:right;">PREVIOUS COLLECTION:</th>
									<th style = "text-align:right;"><?php echo number_format($total_prev,2);?></th>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL SALES:</th>
									<th style = "text-align:right;"><?php echo number_format($total_sales,2);?></th>
								</TR>
								

								<tr>
									<th style = "text-align:right;">LESS:</th>
									<td style = "text-align:right;"></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL EXPENSES:</th>
									<td style = "text-align:right;"><?php echo number_format($total_expense,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">TOTAL CASH OUT:</th>
									<td style = "text-align:right;"><?php echo number_format($total_cash_out,2);?></td>
								</TR>								
								<tr>
									<th style = "text-align:right;">RECEIVABLES:</th>
									<td style = "text-align:right;"><?php echo number_format($total_rec,2);?></td>
								</TR>
								<tr>
									<th style = "text-align:right;">CASH ON HAND: </th>
									<th style = "text-align:right;"><?php echo number_format($total,2);?></th>
								</TR>
				</table>
			<?php	
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
