<?php
include('connect.php');
include("general.php");

if(isset($_REQUEST['stockmui']))
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
			<div class="box-body" id = "stockmui2">
				<?php dmonitoring("","",'ALL','ALL');?>
			</div>
		</div>
	<?php
}

?>
