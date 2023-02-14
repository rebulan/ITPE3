<?php
include('connect.php');
include("general.php");

if(isset($_POST['newprofileui'])||isset($_REQUEST['newprofileui']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$dess = "";
	if(isset($_REQUEST['des']))
	{
		$dess = $_REQUEST['des'];
	}
	?>
			  <h2>
				NEW CUSTOMER PROFILE
			  </h2>
		<div id = "profileui">
						<form id = "profile" method = "post">	
								<div class="alert alert-success alert-dismissible">
									
									<h4><i class="icon fa fa-info"></i> INFORMATION!</h4>
									ALL fields with asterisk are required!
								</div>
							<div class="box">
								<div class="box-body">
									<div class  ="row">
								
										<div class="col-md-4" style = "display:none;">
											 <div class="form-group">
													 <label for="age">Reference No:</label>
														<input type = "hidden" name = "des" value = "<?php echo $dess;?>">
														<input type = "hidden" name = "frompos" value = "<?php echo $frompos;?>">
														<input type="hidden" id="customer_type" name="customer_type" class="form-control" value = "">
											</div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">* Customer Type:</label>
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
										<div id = "referralui">
										</div>
									</div>
								</div>
							</div>
							
							<div class="box">
								<div class="box-body">
									<?php
									if($newprofileui == 1)
									{
									?>
									<div class  ="row">
										<div class="col-md-4">
											<div class="form-group">
													  <label for="age">* FULLNAME: </label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4"><input name="fname" class = "form-control form-flat" placeholder="Firstname" value="" autocomplete="off" data-validation="required"
															data-validation-error-msg="FIRST NAME Field is Required"/></div>
										<div class="col-md-4"><input name="mname" class = "form-control form-flat" placeholder="Middlename" value="" autocomplete="off" data-validation="required"
															data-validation-error-msg="LAST NAME Field is Required"/></div>
										<div class="col-md-4"><input name="lname" class = "form-control form-flat" placeholder="Lastname" value="" autocomplete="off"/></div>
									</div><br>
									<?php
									}
									?>
									<div class="row">
										<?php
										if($newprofileui == 2)
										{
										?>
											<div class="col-md-4">
												<div class="form-group">
														<label for="lname">BUSINESS NAME</label>
														<input type="text" id="businessname" name="businessname" class="form-control" data-validation="required"
																data-validation-error-msg="BUSINESS NAME Field is Required">
														
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
														<label for="lname">CONTACT PERSON</label>
														<input type="text" id="contact_person" name="contact_person" class="form-control" data-validation="required"
																data-validation-error-msg="CONTACT PERSON Field is Required">
														
												</div>
											</div>
										<?php
										}
										?>
										<div class="col-md-7">
											<div class="form-group">
													<label for="lname">* ADDRESS</label>
													<input type="text" name="haddress" class="form-control" data-validation="required"
															data-validation-error-msg="ADDRESS Field is Required">
													
											</div>
										</div>
										<div class="col-md-4">
												  <div class="form-group">
												  <label for="age">* SEX</label>
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
													<input type="date" name="bdate" class="form-control">
													
											</div>
										</div>
										
								
									</div>
								</div>
							</div>
							<div class="box">
								<div class="box-body">
									<div class ="row">
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">* 1ST CONTACT NO:</label>
													<input type="text" id="contact" name="contact" class="form-control" data-validation="required"
															data-validation-error-msg="CONTACT NO Field is Required">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">2ND CONTACT NO:</label>
													<input type="text" id="contact2" name="contact2" class="form-control">
													
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">3RD CONTACT NO:</label>
													<input type="text" id="lname" name="contact3" class="form-control" >
													
											</div>
										</div>
								
										
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">1ST SOCIAL MEDIA:</label>
													<input type="text" id="social1" name="social1" class="form-control" >
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">2ND SOCIAL MEDIA:</label>
													<input type="text" id="social2" name="social2" class="form-control" >
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">3RD SOCIAL MEDIA:</label>
													<input type="text" id="social3" name="social3" class="form-control">
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">EMAIL ADDRESS:</label>
													<input type="text" id="email" name="email" class="form-control">
													
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="box" style = "display:none;">
								<div class="box-body">
									<div class  ="row">
											<div class="col-md-4">
												<div class="form-group">
														  <label for="age">HOME ADDRESS</label>
												</DIV>
											</div>
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
																			'php/customer.php',
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
								</div>
							</div>
							
							<div class="box" style = "display:none;">
								<div class="box-body">
									<div class  ="row">
										<div class="col-md-4">
											<div class="form-group">
													  <label for="age">SHIPPING ADDRESS</label>
													  <button class = "btn btn-warning btn-flat btn-xs" id = "same">SAME AS HOME ADDRESS</button>
											</DIV>
										</div>
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
																		'php/customer.php',
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
								</div>
							</div>
							
							<div class  ="row">
								<div class="col-md-4">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "save">SAVE</button>
											<?php
											if(!isset($_REQUEST['frompos']))
											{
											?>
												<button class = "btn btn-danger btn-flat" id = "cancel">CANCEL</button>
											<?php
											}
											?>
										  </div>
								</div>
							</div>
							
						</form>
						<script>
							$("#cancel").click(
								function(e)
								{
									e.preventDefault();
									$.post( 
																'php/customer.php',
																{
																	singlecm:1
																},
																function(data) {
																	$('#maincontent').html(data);		
																});

								}
							);
							
							/*$("#save").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);*/
							
							$.validate({
															form:'#profile',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																
																 var formData = $('#profile').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 
																			$.ajax({
																				url :  'php/customer.php',
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
																'php/customer.php',
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
																'php/customer.php',
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
																'php/customer.php',
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
																'php/customer.php',
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
if(isset($_POST['lname']) || isset($_POST['businessname']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
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
	
	$lname = "";
	$fname = "";
	$mname = "";
	if(isset($_POST['lname']))
	{
		$lname = trim(strtoupper($_POST['lname']));
		$fname = trim(strtoupper($_POST['fname']));
		$mname = trim(strtoupper($_POST['mname']));
		$check = mysqli_num_rows(mysqli_query($con,"Select * from customer_profile where lastname = '$lname' and firstname = '$fname' and middlename = '$mname' and lastname != '' and middlename != '' and lastname != ''
		and isdeleted = 0"));
	
	}
	
	$businessname = "";
	$contact_person = "";
	
	if(isset($_POST['businessname']))
	{
		$businessname =  trim(strtoupper($_POST['businessname']));
		$contact_person = trim(strtoupper($_POST['contact_person']));
		$check = mysqli_num_rows(mysqli_query($con,"Select * from customer_profile where business_name = '$businessname' and isdeleted = 0"));
	
	}
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$branch = get_branch($user);
								
	//$check = mysqli_num_rows(mysqli_query($con,"Select * from customer_profile where ((lastname = '$lname' and firstname = '$fname' and middlename = '$mname')
	//or (business_name = '$businessname')) and lastname != '' and middlename != '' and lastname != ''"));
	//echo $check;
	if($check == 0)
	{
		mysqli_query($con,"insert into customer_profile set 
		reference_no = '$customer_type',
		branch_id = $branch,
		customer_type_id = $ctype,
		result = '$result',
		firstname = '$fname',
		lastname = '$lname',
		middlename = '$mname',
		business_name = '$businessname',
		contact_person = '$contact_person',
		gender  = '$sex',
		birthdate = '$bdate',
		home_address = '$haddress',
		contact_no1 = '$contact',
		contact_no2 = '$contact2',
		contact_no3 = '$contact3',
		email_address = '$email',
		social_media1  = '$social1',
		social_media2  = '$social2',
		social_media3  = '$social3',
		created_by_fullname = '$_SESSION[c_craft]',
		datetime_created = NOW()
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
		
		if(!empty($_POST['des']))
		{
			?>
			<script>
				$("#modal2").modal("hide");
				$("#ref").val("<?php echo $cno;?>");
				$("#clickval").val(1);
			</script>
	
			<?php
		}
		else
		{
			if(isset($_POST['frompos']))
			{
				?>
					<script>
						$.post( 
							'php/pos.php',
							{ posui2:'<?php echo $row["customer_id"];?>' },
							function(data) {
								$('#posui').html(data);
								$("#modal2").modal('hide');
							});
					</script>
				<?php
			}
			else{
		?>
			<script>
				$('#maincontent').html(loading);
				$.post( 
																'php/customer.php',
																 { 
																	regsuccess:1
																 },
																 function(data) {
																	$('#maincontent').html(data);
																 });
			</script>
	
		<?php
			}
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
if(isset($_REQUEST['regsuccess']))
{
	?>
		<h2>Customer Profile has been Created</h2>
		<button class = "btn btn-success" id = "ok">OK</button>
		<script>
			$("#ok").click(
				function()
				{
					$.post( 
																'php/customer.php',
																 { 
																	cmanagementui:1
																 },
																 function(data) {
																	$('#maincontent').html(data);
																 });
				}
			);
		</script>
	<?php
}
if(isset($_REQUEST['referralui']))
{
	?>
		<div class="col-md-4">
			 <div class="form-group">								
				<label for="age">Referral by:</label>
				<input type="text" id="ref" name="ref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
				data-validation="required" data-validation-error-msg="Referral by field is required">
				<input type="hidden" id="clickval">
				<div id = "search_result"></div>												
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
											
		</script>
	<?php
}
if(isset($_REQUEST['cmanagementui']))
{
	?>
	<H2>CUSTOMER PROFILES</h2>
	<div class="box">
		<div class="box-body" id = "cmfilterui">
			<form id = "cmfilterform">
				<div class  ="row">
								<div class="col-md-4">
									 <div class="form-group">
											 <label for="age">Search:</label>
												<select id="searchcm" name = "searchcm" class="form-control" data-validation="required"
													data-validation-error-msg="Select Search Mode">
												<option value = "" hidden "Selected"></option>
												<option value = "1">INDIVIDUAL CUSTOMER</option>
												<option value = "2">MULTIPLE CUSTOMER</option>
											</select>
											
									</div>
								</div>
								<div class="col-md-5" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">OK</button>
										<button class = "btn btn-warning btn-flat" id = "new">NEW CUSTOMER PROFILE</button>
									</div>	
								</div>
							
								
				</div>	
			</form>
			<script>
				$("#new").click(
											function(e)
											{
												e.preventDefault();
													
													$.post( 
															'php/customer.php',
															 { newprofiletype:1 },
															 function(data) {
																$('#maincontent').html(data);
															 });
															 
															/*$.post( 
															'php/customer.php',
															 { newprofileui:1 },
															 function(data) {
																$('#maincontent').html(data);
															 });*/
														
											});
				$.validate({
															form:'#cmfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																	var cm = $("#searchcm").val();
																	
																	if(cm == 1)
																	{
																		$('#cmfilterui').html(loading);
																		$.post( 
																		'php/customer.php',
																		 { singlecm: 1 },
																		 function(data) {
																			$('#cmfilterui').html(data);
																		 });
																	}
																	else
																	{
																		$('#cmfilterui').html(loading);
																		$.post( 
																			'php/customer.php',
																			 { multicm: 1 },
																			 function(data) {
																				$('#cmfilterui').html(data);
																			 });
																	}
															  return false; // Will stop the submission of the form
															},
														});
			</script>
		</div>
	
	</div>
		<div id = "calert"></div>
		<div id = "customerlist"></div>
	<?php
}
if(isset($_REQUEST['newprofiletype']))
{
	?>
	<H2>CUSTOMER PROFILES</h2>
	<div class="box">
		<div class="box-body" id = "cmfilterui">
			<form id = "cmfilterform">
				<div class  ="row">
								<div class="col-md-4">
									 <div class="form-group">
											 <label for="age">Select Custome Profile Type:</label>
												<select id="newprofileui" name = "newprofileui" class="form-control" data-validation="required"
													data-validation-error-msg="Select Select Custome Profile Type">
												<option value = "" hidden "Selected"></option>
												<option value = "1">INDIVIDUAL</option>
												<option value = "2">ESTABLISHMENT</option>
											</select>
											
									</div>
								</div>
								<div class="col-md-5" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">OK</button>
										<button class = "btn btn-danger btn-flat" id = "cancel">CANCEL</button>
									</div>	
								</div>
							
								
				</div>	
			</form>
			<script>
				$("#cancel").click(
							function(e)
							{
								e.preventDefault();
								
									$.post( 
																	'php/customer.php',
																	{
																		cmanagementui:1
																	},
																	function(data) {
																		$('#maincontent').html(data);	
																		
																	});
							}
						);
						
				$.validate({
															form:'#cmfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																	 var formData = $('#cmfilterform').serializeArray();
																 	
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#maincontent").html(loading);
																			$.ajax({
																				url :  'php/customer.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#maincontent").html(data);
																					
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

if(isset($_REQUEST['singlecm']))
{
	?>
		<H2>CUSTOMER PROFILES</h2>
		<div class="box">
			<div class="box-body">
				<form id = "cmsearchform" method = "POST">
					<div class = "row">
						<div class="col-md-4">
							 <div class="form-group">								
								<label for="age">SEARCH:</label>
								<input type="text" id="ref" name="cmref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
								data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
								<input type="hidden" id="clickval">
								<div id = "search_result"></div>												
							</div>
						</div>
						<div class="col-md-5" style = "padding-top:25px;">
													<div class = "form-group">
														<button class = "btn btn-success btn-flat btn-sm" id = "go">GO</button>
														
														<button class = "btn btn-warning btn-flat btn-sm" id = "new">NEW CUSTOMER PROFILE</button>
													</div>	
						</div>
					</div>
				</form>
			</div>
		</div>
		<div id = "customerlist"></div>
	
		<script>
				$("#new").click(
											function(e)
											{
												e.preventDefault();
													
													$.post( 
															'php/customer.php',
															 { newprofileui:1 },
															 function(data) {
																$('#maincontent').html(data);
															 });
														
											});
											
											$.validate({
															form:'#cmsearchform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#cmsearchform').serializeArray();
																 	
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#customerlist").html(loading);
																			$.ajax({
																				url :  'php/customer.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#customerlist").html(data);
																					
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
	<?php
}
if(isset($_POST['cmref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_num_rows(mysqli_query($con,"Select * from customer_profile where customer_no = '$cmref'"));
	
	if($check != 0)
	{?>
		<div class="box">
			<div class="box-body">
				<?php customer_masterlist("","","","","","","","",$cmref);?>
			</div>
		</div>
		<?php
		
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#calert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['multicm']))
{
	?>
		<form id = "cmfilterform" method = "POST">
					<div class="box">
				
						
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
																'php/customer.php',
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
								
								<div class="col-md-4" style = "display:none;">
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
								<div class="col-md-4" style = "display:none;">
											 <div class="form-group">
													
													 <label for="age">Membership Type:</label>
														<select id = "cmmtype" name = "cmmtype" class="form-control" data-validation="required"
															data-validation-error-msg="Select Membership Type">
														<option  hidden "Selected"></option>
														<option value = "00">ALL</option>
														<option value = "1">NON-MEMBER</option>
														<option value = "0">MEMBER</option>
													</select>
													
											</div>
										</div>
								
								<div class="col-md-7" style = "padding-top:25px;">
								
								 <div class="form-group">
									<button class = "btn btn-success btn-flat btn-sm" id = "filtercm">FILTER</button>			
									<button class = "btn btn-warning btn-flat btn-sm" id = "new">NEW CUSTOMER PROFILE</button>
									<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>	
								</div>
								</div>
								
							</div>
						
						
					
				
					</div>
				</div>
				
				</form>
				<script>
						$("#new").click(
							function(e)
							{
								e.preventDefault();
								
										$.post( 
															'php/customer.php',
															 { newprofiletype:1 },
															 function(data) {
																$('#maincontent').html(data);
															 });
							}
						);
						$("#cancel").click(
							function(e)
							{
								e.preventDefault();
								
									$.post( 
																	'php/customer.php',
																	{
																		cmanagementui:1
																	},
																	function(data) {
																		$('#maincontent').html(data);	
																		
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
																$("#customerlist").html(loading);
																$('#click').html("");	
																 var formData = $('#cmfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#cmasterlist").html(loading);
																			$.ajax({
																				url :  'php/customer.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#customerlist").html(data);
																					
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
																'php/customer.php',
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
																'php/customer.php',
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
		<div class="box">
			<div class="box-body">
				<?php customer_masterlist($cmregion,$cmprovince,$cmcitymun,$cmbrgy,0,$cmtype,"","","");?>
			</div>
		</div>
	<?php
}
if(isset($_POST['viewallcmaster']))
{
	
	?>
		<div class="box box-warning">
			<div class="box-body">
				<?php customer_masterlist("","","","",0,"","","","");?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['printallcmaster']))
{
	$header = "";
	
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CUSTOMER MASTERLIST</h4>
			<h4 style = "text-align:center"><?php echo $header;?></h4>
			
			<?php
				customer_masterlist("","","","",1,"","","","");
				
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
if(isset($_REQUEST['viewprofile']))
{
	$id = $_REQUEST['viewprofile'];
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_id = $id"));
	?>
		 
			  <h2>
				CUSTOMER PROFILE
			  </h2>
		<div id = "profileui">
						<form id = "profile" method = "post">	
							<div class="box">
								<div class="box-body">
									<div class  ="row">
								
										<div class="col-md-4" style = "display:none;">
											 <div class="form-group">
													 <label for="age">Reference No:</label>
														<input type = "hidden" name = "profileeditid" value = "<?php echo $id;?>">
														<input type="hidden" id="customer_type" name="customer_typeedit" class="form-control"
														value = "<?php echo $row['reference_no'];?>">
													
											</div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">* Customer Type:</label>
													<select id="ctype" name = "ctypeedit" class="form-control" data-validation="required"
															data-validation-error-msg="Select Customer Type">
														<?php
														$current = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $row[customer_type_id]"));
														?>
														<option value = "<?php echo $current['customer_type_id'];?>" hidden "Selected"><?php echo $current['customer_type_name'];?></option>
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
										
										<script>
											/*$("#mtype").change(
												function()
												{
													if($("#mtype").val() == "0")
													{
														$.post( 
																			'php/customer.php',
																			{
																				referralui:1
																			},
																			function(data) {
																				$('#referralui').html(data);
																			});
													}
												}
											);*/
										</script>
										<div id = "referralui">
										</div>
									</div>
								</div>
							</div>
							
							<div class="box">
								<div class="box-body">
								<?PHP
								if($row['lastname'] != "")
								{
								?>
									<div class  ="row">
										<div class="col-md-4">
											<div class="form-group">
													  <label for="age">* FULLNAME: </label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4"><input name="fnameedit" class = "form-control form-flat" placeholder="Firstname"  autocomplete="off" data-validation="required"
															data-validation-error-msg="FIRST NAME Field is Required" value = "<?php echo $row['firstname'];?>" autocomplete="off"/></div>
										<div class="col-md-4"><input name="mnameedit" class = "form-control form-flat" placeholder="Middlename" autocomplete="off" data-validation="required"
															data-validation-error-msg="LAST NAME Field is Required" value = "<?php echo $row['lastname'];?>" autocomplete="off"/></div>
										<div class="col-md-4"><input name="lnameedit" class = "form-control form-flat" placeholder="Lastname" autocomplete="off" value = "<?php echo $row['middlename'];?>"/></div>
									</div><br>
								<?php
								}
								?>
									<div class="row">
									<?PHP
									if($row['business_name'] != "")
									{
									?>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">BUSINESS NAME</label>
													<input type="text" id="businessname" name="businessnameedit" class="form-control" data-validation="required"
															data-validation-error-msg="BUSINESS NAME Field is Required" value = "<?php echo $row['business_name'];?>">
													
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">CONTACT PERSON</label>
													<input type="text" id="contact_person" name="contact_personedit" class="form-control" data-validation="required"
															data-validation-error-msg="CONTACT PERSON Field is Required" value = "<?php echo $row['contact_person'];?>">
													
											</div>
										</div>
									<?php
									}
									?>
										<div class="col-md-4">
												  <div class="form-group">
												  <label for="age">* SEX</label>
												<select id="sex" name = "sexedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select Sex">
													<option value = "<?php echo $row['gender'];?>" hidden "Selected"><?php echo $row['gender'];?></option>
													<option value = "MALE">MALE</option>
													<option value = "FEMALE">FEMALE</option>
												</select>
													
												  </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">BIRTHDATE</label>
													<input type="date" name="bdateedit" class="form-control" data-validation="required"
															data-validation-error-msg="BIRTHDATE Field is Required"
															value = "<?php echo $row['birthdate'];?>">
													
											</div>
										</div>
										<div class="col-md-7">
											<div class="form-group">
													<label for="lname">* HOME ADDRESS</label>
													<input type="text" name="haddressedit" class="form-control" data-validation="required"
															data-validation-error-msg="HOME ADDRESS Field is Required"
															value = "<?php echo $row['home_address'];?>">
													
											</div>
										</div>
										
								
									</div>
								</div>
							</div>
							<div class="box">
								<div class="box-body">
									<div class  ="row">
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">* 1ST CONTACT NO:</label>
													<input type="text" id="contact" name="contactedit" class="form-control"
															value = "<?php echo $row['contact_no1'];?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">2ND CONTACT NO:</label>
													<input type="text" id="contact2" name="contact2edit" class="form-control"
															value = "<?php echo $row['contact_no2'];?>">
													
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">3RD CONTACT NO:</label>
													<input type="text" id="lname" name="contact3edit" class="form-control"
															value = "<?php echo $row['contact_no3'];?>">
													
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">1ST SOCIAL MEDIA:</label>
													<input type="text" id="social1" name="social1edit" class="form-control" value = "<?php echo $row['social_media1'];?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">2ND SOCIAL MEDIA:</label>
													<input type="text" id="social2" name="social2edit" class="form-control"
															value = "<?php echo $row['social_media2'];?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">3RD SOCIAL MEDIA:</label>
													<input type="text" id="social3" name="social3edit" class="form-control"
															value = "<?php echo $row['social_media3'];?>">
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
													<label for="lname">EMAIL ADDRESS:</label>
													<input type="text" id="email" name="emailedit" class="form-control" 
															value = "<?php echo $row['email_address'];?>">
													
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="box" style = "display:none;">
								<div class="box-body">
									<div class  ="row">
											<div class="col-md-4">
												<div class="form-group">
														  <label for="age">HOME ADDRESS</label>
												</DIV>
											</div>
									</div>
									<div class="row">
											<div class="col-md-4">
														<?php
														$rreg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_address, lup_region where
														lup_region.region_id = customer_address.region_id
														and customer_address.customer_id = $row[customer_id]"));
														?>
												
													  <div class="form-group">
													  <label for="age">REGION</label>
														<select name = "regionedit" id = "region" class="form-control" data-validation="required"
																data-validation-error-msg="Select REGION">
															
															<option value = "<?php echo $rreg['region_id'];?>" hidden "Selected"><?php echo $rreg['region_name'];?></option>
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
																			'php/customer.php',
																			{
																				provinceeditui:id,
																				provinceeditcusid:'<?php echo $row['customer_id'];?>'
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
															<select id="provinceedit" name = "provinceedit" class="form-control" data-validation="required"
																	data-validation-error-msg="Select PROVINCE">
																<?php
																$preg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_address, lup_province where
																lup_province.province_id = customer_address.province_id
																and customer_address.customer_id = $row[customer_id]"));
																?>
																<option value = "<?php echo $preg['province_id'];?>" hidden "Selected"><?php echo $preg['province_name'];?></option>
																
																
															</select>
														
													  </div>
											</div>
											<div class="col-md-4">
													  <div class="form-group" id = "citymunui">
													  <label for="age">CITY/MUNICIPALITY</label>
														<select id="citymunedit" name = "citymunedit" class="form-control" data-validation="required"
															data-validation-error-msg="Select CITY/MINICIPALITY">
															<?php
															$creg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_address, lup_city_town where
															lup_city_town.city_town_id = customer_address.city_town_id
															and customer_address.customer_id = $row[customer_id]"));
															?>
															<option value = "<?php echo $creg['city_town_id'];?>" hidden "Selected"><?php echo $creg['city_town_name'];?></option>
															
															
														</select>
														
													  </div>
											</div>
											<div class="col-md-4">
													  <div class="form-group" id = "brgyui">
													  <label for="age">BARANGAY</label>
														<select id = "brgyedit" name = "brgyedit" class="form-control" data-validation="required"
																data-validation-error-msg="Select BARANGAY">
															<?php
															$breg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_address, lup_barangay where
															lup_barangay.barangay_id = customer_address.barangay_id
															and customer_address.customer_id = $row[customer_id]"));
															?>
															<option value = "<?php echo $breg['barangay_id'];?>" hidden "Selected"><?php echo $breg['barangay_name'];?></option>
															
															
														</select>
														
													  </div>
											</div>
											<?php
												$breg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_address where
												customer_address.customer_id = $row[customer_id]"));
												?>
												
												<div class="col-md-7">
													<div class="form-group">
															<label for="lname">STREET & NO:</label>
															<input type="text" id = "streetedit" name="streetedit" class="form-control" data-validation="required"
																	data-validation-error-msg="STREET & NO Field is Required" value = "<?php echo $breg['street_name'];?>">
															
													</div>
												</div>
											
									</div>
								</div>
							</div>
							
							<div class="box" style = "display:none;">
								<div class="box-body">
									<div class  ="row">
										<div class="col-md-4">
											<div class="form-group">
													  <label for="age">SHIPPING ADDRESS</label>
													  <button class = "btn btn-warning btn-flat btn-xs" id = "same">SAME AS HOME ADDRESS</button>
											</DIV>
										</div>
									</div>
										<script>
											$("#same").click(
												function(e)
												{
													e.preventDefault();
													
													var reg = $("#region").val();
													var province = $("#provinceedit").val();
													var tprovince = $("#provinceedit option:selected").text();
													var citymun = $("#citymunedit").val();
													var tcitymun = $("#citymunedit option:selected").text();
													var brgy = $("#brgyedit").val();
													var tbrgy = $("#brgyedit option:selected").text();
													var street = $("#streetedit").val();
													
													$("#sregionedit").val(reg);
													$('#sprovinceedit').append('<option value = "'+ province +'" hidden Selected>'+ tprovince +'</option>');
													$('#scitymunedit').append('<option value = "'+ citymun +'" hidden Selected>'+ tcitymun +'</option>');
													$('#sbrgyedit').append('<option value = "'+ brgy +'" hidden Selected>'+ tbrgy +'</option>');
													
													$("#sstreet").val(street);
													
													
												}
											);
										</script>
									<div class  ="row">
										<div class="col-md-4">
												  <div class="form-group">
												  <label for="age">REGION</label>
													<select name = "sregionedit" id = "sregionedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select REGION">
														<?php
														$preg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_shipping_address, lup_region where
														lup_region.region_id = customer_shipping_address.region_id
														and customer_shipping_address.customer_id = $row[customer_id]"));
														?>
														<option value = "<?php echo $preg['region_id'];?>" hidden "Selected"><?php echo $preg['region_name'];?></option>
														
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
														$("#sregionedit").change(
															function()
															{
																var id = $("#sregionedit").val();
																
																$.post( 
																		'php/customer.php',
																		{
																			sprovinceeditui:id
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
													<select id="sprovinceedit" name = "sprovinceedit" class="form-control" data-validation="required"
															data-validation-error-msg="Select PROVINCE">
														
														<?php
														$preg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_shipping_address, lup_province where
														lup_province.province_id = customer_shipping_address.province_id
														and customer_shipping_address.customer_id = $row[customer_id]"));
														?>
														<option value = "<?php echo $preg['province_id'];?>" hidden "Selected"><?php echo $preg['province_name'];?></option>
														
													</select>
													
												  </div>
										</div>
										<div class="col-md-4">
												  <div class="form-group" id = "scitymunui">
												  <label for="age">CITY/MUNICIPALITY</label>
														<select id="scitymun" name = "scitymunedit" class="form-control" data-validation="required"
																data-validation-error-msg="Select CITY/MINICIPALITY">
																<?php
															$creg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_shipping_address, lup_city_town where
															lup_city_town.city_town_id = customer_shipping_address.city_town_id
															and customer_shipping_address.customer_id = $row[customer_id]"));
															?>
															<option value = "<?php echo $creg['city_town_id'];?>" hidden "Selected"><?php echo $creg['city_town_name'];?></option>
															
															
														</select>
													
												  </div>
										</div>
										<div class="col-md-4">
												  <div class="form-group" id = "sbrgyui">
												  <label for="age">BARANGAY</label>
													<select id="sbrgy" name = "sbrgyedit" class="form-control" data-validation="required"
															data-validation-error-msg="Select BARANGAY">
														<?php
														$breg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_shipping_address, lup_barangay where
														lup_barangay.barangay_id = customer_shipping_address.barangay_id
														and customer_shipping_address.customer_id = $row[customer_id]"));
														?>
														<option value = "<?php echo $breg['barangay_id'];?>" hidden "Selected"><?php echo $breg['barangay_name'];?></option>
														
														
													</select>
													
												  </div>
										</div>
										<?php
										$breg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_shipping_address where
														customer_shipping_address.customer_id = $row[customer_id]"));
										?>
										<div class="col-md-7">
											<div class="form-group">
													<label for="lname">STREET & NO:</label>
													<input type="text" name="sstreetedit" id="sstreet" class="form-control" data-validation="required"
															data-validation-error-msg="STREET & NO Field is Required" value = "<?php echo $breg['street_name'];?>">
													
											</div>
										</div>
										
									</div>
								</div>
							</div>
							
							<div class  ="row">
								<div class="col-md-4">
										  <div class="form-group">
											<button class = "btn btn-success btn-flat" id = "save">SAVE</button>
											<button class = "btn btn-danger btn-flat" id = "cancel">CANCEL</button>
										  </div>
								</div>
							</div>
							
						</form>
						<script>
							$("#cancel").click(
								function(e)
								{
									e.preventDefault();
									$("#modal").modal("hide");

								}
							);
							
							/*$("#save").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);*/
							
													$.validate({
															form:'#profile',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																
																 var formData = $('#profile').serializeArray();
																	 
																			$.ajax({
																				url :  'php/customer.php',
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
	<?php
}
if(isset($_REQUEST['provinceeditui']))
{
	$id = $_REQUEST['provinceeditui'];
	$cusid = $_REQUEST['provinceeditcusid'];
	?>
		<label for="age">PROVINCE</label>
											<select name = "provinceedit" id = "provinceedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select PROVINCE">
													<?php
												$preg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_address, lup_province where
												lup_province.province_id = customer_address.province_id
												and customer_address.customer_id = $cus_id]"));
												?>
												<option value = "<?php echo $preg['province_id'];?>" hidden "Selected"><?php echo $preg['province_name'];?></option>
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
												$("#provinceedit").change(
													function()
													{
														var id = $("#provinceedit").val();
														
														$.post( 
																'php/customer.php',
																{
																	citymuneditui:id,
																	citymuneditcusid:<?php echo $cusid;?>
																},
																function(data) {
																	$('#citymunui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['citymuneditui']))
{
	$id = $_REQUEST['citymuneditui'];
	$cus_id = $_REQUEST['citymuneditcusid'];
	?>
		<label for="age">CITY/MUNICIPALITY</label>
											<select name = "citymunedit" id = "citymunedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select CITY/MUNICIPALITY">
												<?php
												$creg = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_address, lup_city_town where
												lup_city_town.city_town_id = customer_address.city_town_id
												and customer_address.customer_id = $cus_id]"));
												?>
												<option value = "<?php echo $creg['city_town_id'];?>" hidden "Selected"><?php echo $creg['city_town_name'];?></option>
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
												$("#citymunedit").change(
													function()
													{
														var id = $("#citymunedit").val();
														
														$.post( 
																'php/customer.php',
																{
																	editbrgyui:id
																},
																function(data) {
																	$('#brgyui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['editbrgyui']))
{
	$id = $_REQUEST['editbrgyui'];
	?>
		<label for="age">BARANGAY</label>
											<select name = "brgyedit" id = "brgyedit" class="form-control" data-validation="required"
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
if(isset($_REQUEST['sprovinceeditui']))
{
	$id = $_REQUEST['sprovinceeditui'];
	
	?>
		<label for="age">PROVINCE</label>
											<select name = "sprovinceedit" id = "sprovinceedit" class="form-control" data-validation="required"
													data-validation-error-msg="Select PROVINCE">
													<?php
												
												?>
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
												$("#sprovinceedit").change(
													function()
													{
														var id = $("#sprovinceedit").val();
														
														$.post( 
																'php/customer.php',
																{
																	scitymuneditui:id
																	
																},
																function(data) {
																	$('#scitymunui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['scitymuneditui']))
{
	$id = $_REQUEST['scitymuneditui'];

	?>
		<label for="age">CITY/MUNICIPALITY</label>
											<select name = "scitymunedit" id = "scitymunedit" class="form-control" data-validation="required"
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
												$("#scitymunedit").change(
													function()
													{
														var id = $("#scitymunedit").val();
														
														$.post( 
																'php/customer.php',
																{
																	sbrgyeditui:id
																},
																function(data) {
																	$('#sbrgyui').html(data);		
																});
													}
												);
											</script>
	<?php
}
if(isset($_REQUEST['sbrgyeditui']))
{
	$id = $_REQUEST['sbrgyeditui'];
	?>
		<label for="age">BARANGAY</label>
											<select name = "sbrgyedit" id = "sbrgyedit" class="form-control" data-validation="required"
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
if(isset($_POST['profileeditid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
		//echo "The value of ".$key." is ". $val." <br>";
	}
	
	mysqli_query($con,"update customer_profile set 
	reference_no = '$customer_typeedit',
	customer_type_id = $ctypeedit,
	firstname = '$fnameedit',
	lastname = '$lnameedit',
	middlename = '$mnameedit',
	business_name = '$businessnameedit',
	contact_person = '$contact_personedit',
	gender  = '$sexedit',
	birthdate = '$bdateedit',
	home_address = '$haddressedit',
	contact_no1 = '$contactedit',
	contact_no2 = '$contact2edit',
	contact_no3 = '$contact3edit',
	email_address = '$emailedit',
	social_media1  = '$social1edit',
	social_media2  = '$social2edit',
	social_media3  = '$social3edit',
	edited_by_fullname = '$_SESSION[c_craft]',
	datetime_edited = NOW()
	where customer_id = $profileeditid
	");
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_id = $profileeditid"));
	
	mysqli_query($con,"update customer_address set 
	street_name = '$streetedit',
	barangay_id = $brgyedit,
	city_town_id = $citymunedit,
	province_id = $provinceedit,
	region_id = $regionedit
	where customer_id = $row[customer_id]
	");
	mysqli_query($con,"update customer_shipping_address set 
	street_name = '$sstreetedit',
	barangay_id = $sbrgyedit,
	city_town_id = $scitymunedit,
	province_id = $sprovinceedit,
	region_id = $sregionedit
	where customer_id = $row[customer_id]
	");
	$issave = 0;
	if($issave == 1)
	{
			?>
			<script>
				$("#modal").modal("hide");
				alert('Customer Profile Updated');
			</script>
			<?php
		customer_info($row['customer_id']);
	}
	else
	{
		?>
			<script>
				$("#modal").modal("hide");
				alert('Customer Profile Updated');
				
				/*$.post( 
															'../php/main.php',
															 { profilerefresh:1 
															 },
															 function(data) {
																$('#profileui2').html(data);
																//
															 });*/
			</script>
		<?php
	}
}
if(isset($_REQUEST['regui']))
{
	?>
	<H2>REGISTRATIONS</h2>
	<div class="box">
		<div class="box-body" id = "cmfilterui">
			<form id = "cmfilterform">
				<div class  ="row">
								<div class="col-md-4">
									 <div class="form-group">
											 <label for="age">Search:</label>
												<select id="searchcm" name = "searchcm" class="form-control" data-validation="required"
													data-validation-error-msg="Select Search Mode">
												<option value = "" hidden "Selected"></option>
												<option value = "1">INDIVIDUAL REGISTRATION</option>
												<option value = "2">MULTIPLE REGISTRATIONS</option>
											</select>
											
									</div>
								</div>
								<div class="col-md-5" style = "padding-top:25px;">
									<div class = "form-group">
										<button class = "btn btn-success btn-flat" id = "filter">OK</button>
										<button class = "btn btn-warning btn-flat" id = "new">NEW REGISTRATION</button>
									</div>	
								</div>
							
								
				</div>	
			</form>
			<script>
				$("#new").click(
											function(e)
											{
												e.preventDefault();
													
															$.post( 
															'php/customer.php',
															 { searchreg:1 },
															 function(data) {
																$('#maincontent').html(data);
															 });
														
											});
				$.validate({
															form:'#cmfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																	var cm = $("#searchcm").val();
																	
																	if(cm == 1)
																	{
																		$('#cmfilterui').html(loading);
																		$.post( 
																		'php/customer.php',
																		 { singlereg: 1 },
																		 function(data) {
																			$('#cmfilterui').html(data);
																		 });
																	}
																	else
																	{
																		$('#cmfilterui').html(loading);
																		$.post( 
																			'php/customer.php',
																			 { multireg: 1 },
																			 function(data) {
																				$('#cmfilterui').html(data);
																			 });
																	}
															  return false; // Will stop the submission of the form
															},
														});
			</script>
		</div>
	
	</div>
		<div id = "calert"></div>
		<div id = "registrations"></div>
	<?php
}
if(isset($_REQUEST['singlereg']))
{
	?>
	<form id = "regsearchform" method = "POST">
		<div class = "row">
			<div class="col-md-4">
				 <div class="form-group">								
					<label for="age">SEARCH:</label>
					<input type="text" id="ref" name="regref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
					data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
					<input type="hidden" id="clickval">
					<div id = "search_result"></div>												
				</div>
			</div>
			<div class="col-md-5" style = "padding-top:25px;">
										<div class = "form-group">
											<button class = "btn btn-success btn-flat btn-sm" id = "go">GO</button>
											<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
											<button class = "btn btn-warning btn-flat btn-sm" id = "new">NEW REGISTRATION</button>
										</div>	
			</div>
		</div>
	</form>
		<script>
				$("#new").click(
											function(e)
											{
												e.preventDefault();
													
															$.post( 
															'php/customer.php',
															 { searchreg:1 },
															 function(data) {
																$('#maincontent').html(data);
															 });
														
											});
											
			$("#cancel").click(
					function(e)
					{
						e.preventDefault();
						$('#maincontent').html(loading);
																		$.post( 
																		'php/customer.php',
																		 { regui:1 },
																		 function(data) {
																			$('#maincontent').html(data);
																		 });
					}
				);
											$.validate({
															form:'#regsearchform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#regsearchform').serializeArray();
																 	$("#registrations").html(loading);
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#customerlist").html(loading);
																			$.ajax({
																				url :  'php/customer.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#registrations").html(data);
																					
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
	<?php
}
if(isset($_POST['regref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_num_rows(mysqli_query($con,"Select * from customer_profile,registration where customer_profile.customer_no = '$regref'
	and registration.customer_id = customer_profile.customer_id"));
	
	if($check != 0)
	{?>
		<div class="box">
			<div class="box-body">
				<?php registration($regref,"","","","",0,"all");?>
			</div>
		</div>
		<?php
		
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#calert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['multireg']))
{
	?>
	<form id = "regsearchform" method = "POST">
		<div class = "row">
				<div class="col-md-4">
											 <div class="form-group">
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">Registration Status:</label>
														<select id="rstatus" name = "rstatus" class="form-control" data-validation="required"
															data-validation-error-msg="Select Registration Status">
														<option value = "" hidden "Selected"></option>
														<?php
														$cquery = mysqli_query($con,"Select * from lup_registration_status where isdeleted = 0");
														while($crow = mysqli_fetch_assoc($cquery))
														{
														?>
														<option value = "<?php echo $crow['registration_status_id'];?>"><?php echo $crow['registration_status_description'];?></option>
														<?php
														}
														?>
													</select>	
					</div>
				</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">VALIDITY:</label>
								
									<select id="rvalidity" name = "rvalidity" class="form-control" data-validation="required"
																		data-validation-error-msg="Select VALIDITY">
										<option value = "all" hidden "Selected">ALL</option>
										<option value = "all">ALL</option>										
										<option value = "1">DATE RANGE</option>
										<option value = "0">LIFETIME</option>
									</select>	
								</div>
							</div>
							
							<div class="col-md-4">
								<div class = "form-group">
									<label>Valid From:</label>
									<input type = "date" class = "form-control" name = "rdfrom" id = "rdfrom">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Valid Until:</label>
									<input type = "date" class = "form-control" name = "rdto"  id = "rdto">
								</div>
							</div>
							
			<div class="col-md-5" style = "padding-top:25px;">
										<div class = "form-group">
											<button class = "btn btn-success btn-flat btn-sm" id = "go">Filter</button>
											<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
											<button class = "btn btn-warning btn-flat btn-sm" id = "new">NEW REGISTRATION</button>
										</div>	
			</div>
		</div>
	</form>
		<script>
				$("#new").click(
											function(e)
											{
												e.preventDefault();
													
															$.post( 
															'php/customer.php',
															 { searchreg:1 },
															 function(data) {
																$('#maincontent').html(data);
															 });
														
											});
											
			$("#cancel").click(
					function(e)
					{
						e.preventDefault();
						$('#maincontent').html(loading);
																		$.post( 
																		'php/customer.php',
																		 { regui:1 },
																		 function(data) {
																			$('#maincontent').html(data);
																		 });
					}
				);
											$.validate({
															form:'#regsearchform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#regsearchform').serializeArray();
																 	$("#registrations").html(loading);
																	 //var formData = new FormData($('#regform')[0]);
																	 $("#customerlist").html(loading);
																			$.ajax({
																				url :  'php/customer.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#registrations").html(data);
																					
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
	<?php
}
if(isset($_POST['rstatus']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	?>
		<div class="box">
			<div class="box-body">
				<?php registration("","",$rstatus,$rdfrom,$rdto,0,$rvalidity);?>
			</div>
		</div>
	<?php
}

if(isset($_REQUEST['cregref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_no = '$cregref'"));
	
	if(!empty($check))
	{
		$regcheck = mysqli_fetch_assoc(mysqli_query($con,"Select * from registration where customer_id = '$check[customer_id]'"));
		if($regcheck == 0)
		{
		?>
			<script>
				$.post( 
															'php/customer.php',
															 { newregui:<?php echo $check['customer_id'];?> },
															 function(data) {
																$('#maincontent').html(data);
															 });
			</script>
			
		<?php
		}
		else
		{
			?>
			<script>
				notify("Registration Already Exist","#calert");
			</script>
		<?php
		}
	}
	else
	{
		?>
			<script>
				notify("No Search Result","#calert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['searchreg']))
{
	?>
	<div class="box">
			<div class="box-body">
				<form id = "regsearchform" method = "POST">
					<div class = "row">
						<div class="col-md-4">
							 <div class="form-group">								
								<label for="age">SEARCH:</label>
								<input type="text" id="ref" name="cregref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
								data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
								<input type="hidden" id="clickval">
								<div id = "search_result"></div>												
							</div>
						</div>
						<div class="col-md-5" style = "padding-top:25px;">
													<div class = "form-group">
														<button class = "btn btn-success btn-flat btn-sm" id = "go">GO</button>
														<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
														
													</div>	
						</div>
					</div>
				</form>
			</div>
	</div>
	<div id = "calert"></div>
		<script>
			$("#cancel").click(
					function(e)
					{
						e.preventDefault();
						$('#maincontent').html(loading);
																		$.post( 
																		'php/customer.php',
																		 { regui:1 },
																		 function(data) {
																			$('#maincontent').html(data);
																		 });
					}
				);
											$.validate({
															form:'#regsearchform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#regsearchform').serializeArray();
																 	
																	 //var formData = new FormData($('#regform')[0]);
																	
																			$.ajax({
																				url :  'php/customer.php',
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
	<?php
}
if(isset($_REQUEST['newregui']))
{
	$id = $_REQUEST['newregui'];
	?>
	<h2>REGISTRATION FORM</h2>
	<div class="box">
			<div class="box-body">
				<?php customer_info($id);?>
			</div>
	</div>
	<div class="box">
			<div class="box-body">
				<form id = "regsearchform" method = "POST">
					<div class = "row">
							<div class="col-md-4">
								<div class="form-group">
									<input type = "hidden" value = "<?php echo $id;?>" name = "rrcid">
									<label for="age">Registration Status:</label>
									<select id="rrstatus" name = "rrstatus" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Registration Status">
																	<option value = "" hidden "Selected"></option>
																	<?php
																	$cquery = mysqli_query($con,"Select * from lup_registration_status where isdeleted = 0");
																	while($crow = mysqli_fetch_assoc($cquery))
																	{
																	?>
																	<option value = "<?php echo $crow['registration_status_id'];?>"><?php echo $crow['registration_status_description'];?></option>
																	<?php
																	}
																	?>
									</select>	
								</div>
							</div>
							<div class="col-md-4">
									
									<div class="form-group">
										<label>CUSTOMER PHOTO:</label>
										<input type="file" class="form-control" name = "rrimg" id = "rrimg" onchange="readURL(this);" required>
									</div>
							</div>
							<div class="col-md-4">
									
									<div class="form-group">
										<label>PROOF OF IDENTIFICATION:</label>
										<input type="file" class="form-control" name = "rrproof" id = "rrproof"  onchange="read_URL(this);" required>
									</div>
							</div>
						</div>
							
							
						<div class = "row">
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">Membership Card:</label>
									<select id="rrcard" name = "rrcard" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Card Type">
																	<option value = "" hidden "Selected"></option>
																	<?php
																	$cquery = mysqli_query($con,"Select * from card_profile, lup_card_type where card_profile.occupied = 0 and card_profile.isdeleted = 0
																	and card_profile.card_type_id = lup_card_type.card_type_id");
																	while($crow = mysqli_fetch_assoc($cquery))
																	{
																	?>
																		<option value = "<?php echo $crow['card_profile_id'];?>"><?php echo $crow['card_number']."-".$crow['card_type_description'];?></option>
																	<?php
																	}
																	?>
									</select>	
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="age">CUSTOMER PHOTO PREVIEW</label>
									<img id="cphoto" src="#" alt="preview" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">PROOF OF IDENTIFICATION PREVIEW</label>
									<img id="piphoto" src="#" alt="preview" />
								</div>
							</div>
							
							</DIV>
							<div class = "row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">VALIDITY:</label>
								
									<select id="validity" name = "validity" class="form-control" data-validation="required"
																		data-validation-error-msg="Select VALIDITY">
										<option value = "" hidden "Selected"></option>					
										<option value = "1">DATE RANGE</option>
										<option value = "0">LIFETIME</option>
									</select>	
								</div>
							</div>
							<div id = "drangeui">
							
							</div>
							<script>
								$("#validity").change(
									function()
									{
										var r = $("#validity").val();
										
										if(r == 1)
										{
														$.post( 
																'php/customer.php',
																{
																	regdrangeui:1
																	
																},
																function(data) {
																	$('#drangeui').html(data);		
																});
																
											/*$("#rrdfromedit").attr("data-validation","date");
											$("#rrdfromedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdfromedit").attr("data-validation-error-msg","Enter valid Valid From");
											
											$("#rrdtoedit").attr("data-validation","date");
											$("#rrdtoedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdtoedit").attr("data-validation-error-msg","Enter valid Valid Until");*/
										}
										else
										{
											
												$("#drangeui").html("");
												
											/*$("#rrdfromedit").attr("data-validation","");
											$("#rrdfromedit").attr("data-validation-format","");
											$("#rrdfromedit").attr("data-validation-error-msg","");
											
											$("#rrdtoedit").attr("data-validation","");
											$("#rrdtoedit").attr("data-validation-format","");
											$("#rrdtoedit").attr("data-validation-error-msg","");*/
											
										}
									}
								);
							</script>
							
							<div class="col-md-4">
								 <div class="form-group">								
									<label for="age">Referral by:</label>
									<input type="text" id="ref" name="rrref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
									data-validation="required" data-validation-error-msg="Referral by field is required">
									<input type="hidden" id="clickval">
									<div id = "search_result"></div>
									<a href = "" id = "newp" class = "dbtn btn-success btn-sm btn-xs btn-flat">NEW PROFILE</a>									
									<script>
										$("#newp").click(
											function(e)
											{
												e.preventDefault();
													$("#modal2").modal("show");
													$("#modalbody2").css("min-width","75%");
													$.post( 
																					'php/customer.php',
																					{
																						newprofileui:1,
																						des:1
																					},
																					function(data) {
																						$('#modalui2').html(data);	
																						
																					});
											}
										);
									</script>
								</div>
							</div>
						
							<div class="col-md-3">
								<div class = "form-group">
									<label>Registration Date:</label>
									<input type = "date" class = "form-control" name = "rrdate"  id = "rrdate" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Registration Date">
								</div>
							</div>
							
							<script>
									function readURL(input) {
										if (input.files && input.files[0]) {
											var reader = new FileReader();

											reader.onload = function (e) {
												$('#cphoto')
													.attr('src', e.target.result)
													.width(200);
													
											};

											reader.readAsDataURL(input.files[0]);
										}
									}
									
									function read_URL(inputt) {
										if (inputt.files && inputt.files[0]) {
											var readerr = new FileReader();

											readerr.onload = function (e) {
												$('#piphoto')
													.attr('src', e.target.result)
													.width(200);
													
											};

											readerr.readAsDataURL(inputt.files[0]);
										}
									}
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
							<div class="col-md-4">
												<div class="form-group">
													<label>Credit Line:</label>
												
													<Select class = "form-control" name = "rrcline" data-validation="required"
													data-validation-error-msg="Select Credit Line">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_credit_line_limit where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['credit_line_limit_id'];?>"><?php echo $prow['credit_line_limit_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
							</div>
							
							<div class="col-md-3" style = "padding-top:25px;">
														<div class = "form-group">
															<button class = "btn btn-success btn-flat btn-sm" id = "go">REGISTER</button>
															<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
														</div>	
							</div>							
					</div>
				</form>
				<script>
							$("#cancel").click(
								function(e)
								{
									e.preventDefault();
									$.post( 
																'php/customer.php',
																{
																	regui:1
																},
																function(data) {
																	$('#maincontent').html(data);		
																});

								}
							);
							
							/*$("#save").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);*/
							$("#go").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);
							$.validate({
															form:'#regsearchform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																var formData = $('#regsearchform')[0];
																$.ajax({
																						url: 'php/customer.php',
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
						
		</div>
	</div>
	<?php
}
if(isset($_FILES['rrimg']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	//echo $bankscan;
	
	if(is_array($_FILES)) 
	{
		$name = $_FILES['rrimg']['name'];
		$type = $_FILES['rrimg']['type'];
		$size = $_FILES['rrimg']['size'];
		
		$pname = $_FILES['rrproof']['name'];
		$ptype = $_FILES['rrproof']['type'];
		$psize = $_FILES['rrproof']['size'];
			
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['rrimg']['name'];
			
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
			
			$psss = $result."_".$_FILES['rrproof']['name'];
			
			if(($type == "image/jpeg" || $type == "image/png") && ($ptype == "image/jpeg" || $ptype == "image/png"))
			{
				if($size <= 6000000 && $psize <= 6000000)
				{
					if (!file_exists('../images/ID/')) {
						mkdir('../images/ID/', 0777, true);
					}
					
					if (!file_exists('../images/proof/')) {
						mkdir('../images/proof/', 0777, true);
					}
					
					
					$sourcePath = $_FILES['rrimg']['tmp_name'];
					$targetPath = "../images/ID/".basename($sss);
					
					$psourcePath = $_FILES['rrproof']['tmp_name'];
					$ptargetPath = "../images/proof/".basename($psss);
					

						if(is_uploaded_file($_FILES['rrimg']['tmp_name']) && is_uploaded_file($_FILES['rrproof']['tmp_name'])) 
						{
							if(move_uploaded_file($sourcePath,$targetPath) && move_uploaded_file($psourcePath,$ptargetPath)) {
							
								$prof = mysqli_fetch_assoc(mysqli_query($con,"Select customer_no from customer_profile where customer_id = $rrcid"));
								$card = mysqli_fetch_assoc(mysqli_query($con,"Select card_type_description from lup_card_type where card_type_id = $rrcard"));
								
								$user = get_user_id($_SESSION['c_craft']);
								$agent = get_agent($user);
								
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
									$found = mysqli_num_rows(mysqli_query($con, "Select * from card_profile where result = '$result'"));
									if($found == 0)
										break;
									else
										$i = 0;
								}
								
								if($validity == 0)
								{
									$rrdfrom = "";
									$rrdto = "";
								}
								
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
									$found = mysqli_num_rows(mysqli_query($con, "Select * from registration where result = '$result'"));
									if($found == 0)
										break;
									else
										$i = 0;
								}
								$user = get_user_id($_SESSION['c_craft']);
								$agent = get_agent($user);
								$branch = get_branch($user);
	
								$ref = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from customer_profile where customer_no ='$rrref'"));
								
								mysqli_query($con,"insert into registration set
								branch_id = $branch,
								result = '$result',
								registration_date = '$rrdate',
								customer_id = '$rrcid',
								referral_id = '$ref[customer_id]',
								card_profile_id = $rrcard,
								registration_status_id = $rrstatus,
								valid_from = '$rrdfrom',
								valid_to = '$rrdto',
								with_validity = $validity,
								photo_of_applicant = '$sss',
								photo_of_identification = '$psss',
								posted_by_fullname = '$agent',
								datetime_posted = NOW(),
								isdeleted = 0
								");
								
								mysqli_query($con,"Update card_profile set occupied = 1 where card_profile_id = $rrcard");
								$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from registration where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['registration_id'];
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
									
								$idno = $row['registration_id'].$count;
								
								mysqli_query($con,"Update registration set registration_no = '$idno',result = '' where registration_id = $row[registration_id]");
								
								$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
								$validCharNumber = strlen($validCharacters);
								 
								$length = 10;
								$result = "";
							
									for ($i = 0; $i < $length; $i++) {
										$index = mt_rand(0, $validCharNumber-1);
										$result .= $validCharacters[$index];
									}
								$limit = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_credit_line_limit where credit_line_limit_id = $rrcline "));	
								
								$save = mysqli_query($con,"Insert into credit_line_allocation set 
								result = '$result',
								credit_line_allocation_no = '',
								allocation_date = NOW(),
								registration_id = $row[registration_id],
								customer_id = $row[customer_id],
								credit_line_limit_id = $rrcline ,
								credit_line_limit_amount = '$limit[credit_line_limit_amount]',
								valid_from = '',
								valid_to = '',
								remarks = 'ALLOCATION',
								user_id = $user,
								isdeleted = 0
								");
		
		

		
								$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from  credit_line_allocation where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['credit_line_allocation_id'];
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
									
									$idno = $row['credit_line_allocation_id'].$count;
									mysqli_query($con,"Update credit_line_allocation set credit_line_allocation_no = '$idno',result = '' where credit_line_allocation_id = $row[credit_line_allocation_id]");
									
									$idno = $idno.date('Ymd');
									insert_cline($row['customer_id'],0,$idno,$limit['credit_line_limit_amount'],$row['credit_line_allocation_id'],$idno,'ALLOCATE','ALLOCATE',$agent);
							?>
									<script>
										$.post( 
																'php/customer.php',
																{
																	rregsuccess:1
																},
																function(data) {
																	$('#maincontent').html(data);
																	
																});
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
	
	
}
if(isset($_REQUEST['rregsuccess']))
{
	?>
		<div class="box">
			<div class="box-body">
				<h2>Registration successfully Added</h2>
				<button class = "btn btn-success btn-flat" id = "home">OK</button>
				<button class = "btn btn-danger btn-flat" id = "new">NEW REGISTRATION</button>
			</div>
		</div>
		<script>
			$("#home").click(
				function()
				{
					$.post( 
																'php/customer.php',
																{
																	regui:1
																},
																function(data) {
																	$('#maincontent').html(data);
																	
																});
				}
			);
			
			$("#new").click(
				function()
				{
						$.post( 
															'php/customer.php',
															 { searchreg:1 },
															 function(data) {
																$('#maincontent').html(data);
															 });
				}
			);
			
										
									</script>
	<?php
}
if(isset($_REQUEST['regedit']))
{
	$id = $_REQUEST['regedit'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from registration where registration_id = $id"));
	
	if($row['with_validity'] == 0)
	{
		?>
			<script>
				$("#cdfromui").html("");
				$("#cdtoui").html("");
			</script>
		<?php
	}
	?>
	<h2>UPDATE CUSTOMER REGISTRATION</h2>
	<div class="box">
			<div class="box-body">
				<?php customer_info($row['customer_id']);?>
			</div>
	</div>
	<div class="box">
			<div class="box-body">
				<form id = "regeditform" method = "POST">
					<div class = "row">
							<div class="col-md-4">
								<div class="form-group">
									<input type = "hidden" value = "<?php echo $id;?>" name = "regeditid">
									<label for="age">Registration Status:</label>
									<select id="rrstatus" name = "rrstatusedit" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Registration Status">
																	<?php
																	$scrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_registration_status where registration_status_id = $row[registration_status_id]"));
																	?>
																	<option value = "<?php echo $scrow['registration_status_id'];?>" "Selected"><?php echo $scrow['registration_status_description'];?></option>
																	<?php
																	$cquery = mysqli_query($con,"Select * from lup_registration_status where isdeleted = 0");
																	while($crow = mysqli_fetch_assoc($cquery))
																	{
																	?>
																	<option value = "<?php echo $crow['registration_status_id'];?>"><?php echo $crow['registration_status_description'];?></option>
																	<?php
																	}
																	?>
									</select>	
								</div>
							</div>
			
						
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">VALIDITY:</label>
								
									<select id="rrvalidedit" name = "rrvalidedit" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Card Type">
										<?php
										if($row['with_validity'] == 1)
										{
											?>
											<option value = "1" hidden "Selected">DATE RANGE</option>		
											<?php
										}
										else
										{
											?>
											<option value = "0" hidden "Selected">LIFETIME</option>		
											<?php
										}
										?>										
														
										<option value = "1">DATE RANGE</option>
										<option value = "0">LIFETIME</option>
									</select>	
								</div>
							</div>
							<script>
								$("#rrvalidedit").change(
									function()
									{
										var r = $("#rrvalidedit").val();
										
										if(r == 1)
										{
														$.post( 
																'php/customer.php',
																{
																	rdrangeui:1
																	
																},
																function(data) {
																	$('#drangeui').html(data);		
																});
																
											/*$("#rrdfromedit").attr("data-validation","date");
											$("#rrdfromedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdfromedit").attr("data-validation-error-msg","Enter valid Valid From");
											
											$("#rrdtoedit").attr("data-validation","date");
											$("#rrdtoedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdtoedit").attr("data-validation-error-msg","Enter valid Valid Until");*/
										}
										else
										{
											
												$("#cdfromui").html("");
												$("#cdtoui").html("");
											/*$("#rrdfromedit").attr("data-validation","");
											$("#rrdfromedit").attr("data-validation-format","");
											$("#rrdfromedit").attr("data-validation-error-msg","");
											
											$("#rrdtoedit").attr("data-validation","");
											$("#rrdtoedit").attr("data-validation-format","");
											$("#rrdtoedit").attr("data-validation-error-msg","");*/
											
										}
									}
								);
							</script>
							<div id = "drangeui">
								<div class="col-md-3" id = "cdfromui">
									<div class = "form-group">
										<label>Valid From:</label>
										<input type = "date" class = "form-control" name = "rrdfromedit" id = "rrdfrom" data-validation="date" 
										value = "<?php echo $row['valid_from'];?>"
										data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
									</div>		
								</div>
								<div class="col-md-3" id = "cdtoui">
									<div class = "form-group">
										<label>Valid Until:</label>
										<input type = "date" class = "form-control" name = "rrdtoedit"  id = "rrdto" data-validation="date" 
										value = "<?php echo $row['valid_to'];?>"
										data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
									</div>
								</div>
							</div>
							<?php
								$eref = mysqli_fetch_assoc(mysqli_query($con,"Select customer_no from customer_profile where customer_id = $row[referral_id]"));
							?>
							<div class="col-md-4">
								 <div class="form-group">								
									<label for="age">Referral by:</label>
									<input type="text" id="ref" name="rrrefedit" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
									data-validation="required" data-validation-error-msg="Referral by field is required"
									value = "<?php echo $eref['customer_no'];?>">
									<input type="hidden" id="clickval" value = 1>
									<div id = "search_result"></div>												
								</div>
							</div>
							
							<div class="col-md-3">
								<div class = "form-group">
									<label>Registration Date:</label>
									<input type = "date" class = "form-control" name = "rrdateedit"  id = "rrdateedit" data-validation="date" 
									value = "<?php echo $row['registration_date'];?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Registration Date">
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
																
							</script>
							<div class="col-md-3" style = "padding-top:25px;">
														<div class = "form-group">
															<button class = "btn btn-success btn-flat btn-sm" id = "go">UPDATE</button>
															<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
														</div>	
							</div>							
					</div>
				</form>
				<script>
							$("#cancel").click(
								function(e)
								{
									e.preventDefault();
									$("#modal").modal("hide");
									$("#modalui").html("");

								}
							);
							
							/*$("#save").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);*/
							$("#go").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);
							$.validate({
															form:'#regeditform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																var formData = $('#regeditform')[0];
																$.ajax({
																						url: 'php/customer.php',
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
						
		</div>
	</div>
	<?php
}
if(isset($_POST['regeditid']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	$refedit = mysqli_fetch_assoc(mysqli_query($con,"Select customer_id from customer_profile where customer_no ='$rrrefedit'"));
	
	if($rrvalidedit == 0)
	{
		$rrdfromedit = "";
		$rrdtoedit = "";
	}
	
	mysqli_query($con,"Update registration set
	registration_date = '$rrdateedit',
	referral_id = '$refedit[customer_id]',
	registration_status_id = $rrstatusedit,
	valid_from = '$rrdfromedit',
	valid_to = '$rrdtoedit',
	with_validity = '$rrvalidedit',
	edited_by_fullname = '$agent',
	datetime_edited = NOW()
	where registration_id = $regeditid");

		?>
			<script>
				alert("Registration Successfully Updated");
				$("#modal").modal("hide");
			</script>
		<?php
	
}
if(isset($_REQUEST['cardprofui']))
{
	$id = $_REQUEST['cardprofui'];
	
	$rrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from registration where registration_id = $id"));
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from card_profile where card_profile_id = $rrow[card_profile_id]"));
	
	if($row['with_validity'] == 0)
	{
		?>
			<script>
				$("#drangeui").html("");
			</script>
		<?php
	}
	
	?>
	<h2>CARD PROFILE</h2>
	<div class="box">
			<div class="box-body">
				<?php customer_info($rrow['customer_id']);?>
			</div>
	</div>
	<div class="box">
			<div class="box-body">
				<?php card_info($rrow['card_profile_id']);?>
			</div>
	</div>
	
	<div class="box">
			<div class="box-body">
				<form id = "regeditform" method = "POST">
			
						<div class = "row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">CARD VALIDITY:</label>
									<input type = "hidden" value = "<?php echo $rrow['card_profile_id'];?>" name = "cardeditid">
									<select id="cvalidedit" name = "cvalidedit" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Card Type">
										<?php
										if($row['with_validity'] == 1)
										{
											?>
											<option value = "1" hidden "Selected">DATE RANGE</option>		
											<?php
										}
										else
										{
											?>
											<option value = "0" hidden "Selected">LIFETIME</option>		
											<?php
										}
										?>										
														
										<option value = "1">DATE RANGE</option>
										<option value = "0">LIFETIME</option>
									</select>	
								</div>
							</div>
							<script>
								$("#cvalidedit").change(
									function()
									{
										var r = $("#cvalidedit").val();
										
										if(r == 1)
										{
														$.post( 
																'php/customer.php',
																{
																	cdrangeui:1
																	
																},
																function(data) {
																	$('#drangeui').html(data);		
																});
																
											/*$("#rrdfromedit").attr("data-validation","date");
											$("#rrdfromedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdfromedit").attr("data-validation-error-msg","Enter valid Valid From");
											
											$("#rrdtoedit").attr("data-validation","date");
											$("#rrdtoedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdtoedit").attr("data-validation-error-msg","Enter valid Valid Until");*/
										}
										else
										{
											
												$("#drangeui").html("");
											/*$("#rrdfromedit").attr("data-validation","");
											$("#rrdfromedit").attr("data-validation-format","");
											$("#rrdfromedit").attr("data-validation-error-msg","");
											
											$("#rrdtoedit").attr("data-validation","");
											$("#rrdtoedit").attr("data-validation-format","");
											$("#rrdtoedit").attr("data-validation-error-msg","");*/
											
										}
									}
								);
							</script>
							<div id = "drangeui">
								<div class="col-md-3" id = "cdfromui">
									<div class = "form-group">
										<label>Valid From:</label>
										<input type = "date" class = "form-control" name = "cdfromedit" id = "cdfromedit" 
										value = "<?php echo $row['valid_from'];?>" placeholder = "yyyy-mm-dd">
									</div>		
								</div>
								<div class="col-md-3" id = "cdtoui">
									<div class = "form-group">
										<label>Valid Until:</label>
										<input type = "date" class = "form-control" name = "cdtoedit"  id = "cdtoedit"  
										value = "<?php echo $row['valid_to'];?>" placeholder = "yyyy-mm-dd">
									</div>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">Card Type:</label>
									<select id="rrcard" name = "rrcardedit" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Card Type">
																	<?php
																	//$scrow = mysqli_fetch_assoc(mysqli_query($con,"Select card_type_id from card_profile where card_profile_history_id = $row[card_profile_id]"));
																	$card = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_card_type where card_type_id = $row[card_type_id]"));
																	
																	?>
																	<option value = "<?php echo $card['card_type_id'];?>" "Selected"><?php echo $card['card_type_description'];?></option>
																	<?php
																	$cquery = mysqli_query($con,"Select * from lup_card_type where isdeleted = 0");
																	while($crow = mysqli_fetch_assoc($cquery))
																	{
																	?>
																	<option value = "<?php echo $crow['card_type_id'];?>"><?php echo $crow['card_type_description'];?></option>
																	<?php
																	}
																	?>
									</select>	
								</div>
							</div>
							
							<div class="col-md-3" style = "padding-top:25px;">
														<div class = "form-group">
															<button class = "btn btn-success btn-flat btn-sm" id = "go">UPDATE</button>
															<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
														</div>	
							</div>							
					</div>
				</form>
				<script>
							$("#cancel").click(
								function(e)
								{
									e.preventDefault();
									$("#modal").modal("hide");
									$("#modalui").html("");

								}
							);
							
							/*$("#save").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);*/
							
							$.validate({
															form:'#regeditform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																var formData = $('#regeditform')[0];
																$.ajax({
																						url: 'php/customer.php',
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
						
		</div>
	</div>
	<?php
}
if(isset($_POST['cardeditid']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 
	
	if($cvalidedit == 0)
	{
		$cdfromedit = "";
		$cdtoedit = "";
	}
	mysqli_query($con,"update card_profile set
		card_type_id = $rrcardedit,
		valid_from = '$cdfromedit',
		valid_to = '$cdtoedit',
		with_validity = $cvalidedit
		where
		card_profile_history_id = $cardeditid");
	
		?>
			<script>
				alert("Card Profile Updated");
				$("#modal").modal("hide");
			</script>
		<?php
		
}

if(isset($_REQUEST['proofui']))
{
	$id = $_REQUEST['proofui'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select photo_of_identification from registration where registration_id = $id"));
	?>
	<div class="box">
			<div class="box-body">
				<form id = 'changeproofform' method = "POST" enctype="multipart/form-data">
					<div class = "row">
						<div class="col-md-4">
									<label>(PHOTO OF IDENTIFICATION)SCANNED IMAGE:</label>
									<div class="input-group">
									<input type = "hidden" name = "cexid" value = "<?php echo $id;?>">
										<input type="file" class="form-control" name = "cexproof" id = "cexproof" data-validation="required"
														data-validation-error-msg="(PHOTO OF IDENTIFICATION)SCANNED IMAGE is required">
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
																						url: 'php/customer.php',
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
				<img src = "images/proof/<?php echo $row['photo_of_identification'];?>" width  = "100%">
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
	
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['cexproof']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					if (!file_exists('images/proof/')) {
						mkdir('images/proof/', 0777, true);
					}
					$sourcePath = $_FILES['cexproof']['tmp_name'];
					$targetPath = "../images/proof/".basename($sss);

						if(is_uploaded_file($_FILES['cexproof']['tmp_name'])) 
						{
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									
									mysqli_query($con, "Update registration set photo_of_identification  = '$sss' where 
									registration_id = $cexid");
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
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select photo_of_identification from registration where registration_id = $cexid"));
		?>
		<img src = "images/proof/<?php echo $row['photo_of_identification'];?>" width  = "100%">
		<?php
			
}

if(isset($_REQUEST['photoui']))
{
	$id = $_REQUEST['photoui'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select photo_of_applicant from registration where registration_id = $id"));
	?>
	<div class="box">
			<div class="box-body">
				<form id = 'changeproofform' method = "POST" enctype="multipart/form-data">
					<div class = "row">
						<div class="col-md-4">
									<label>(PHOTO OF APPLICANT)SCANNED IMAGE:</label>
									<div class="input-group">
									<input type = "hidden" name = "photoeditid" value = "<?php echo $id;?>">
										<input type="file" class="form-control" name = "photoedit" id = "photoedit" data-validation="required"
														data-validation-error-msg="(PHOTO OF APPLICANT)SCANNED IMAGE is required">
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
																						url: 'php/customer.php',
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
				<img src = "images/ID/<?php echo $row['photo_of_applicant'];?>" width  = "100%">
			</div>
	</div>
	<?php
}

if(isset($_POST['photoeditid']))
{
	foreach($_POST as $key=>$val) {
		${$key} = strtoupper($val);
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
		$name = $_FILES['photoedit']['name'];
		$type = $_FILES['photoedit']['type'];
		$size = $_FILES['photoedit']['size'];
	
			$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
			
		
			$sss = $result."_".$_FILES['photoedit']['name'];
			
			if($type == "image/jpeg" || $type == "image/png")
			{
				if($size <= 6000000)
				{
					if (!file_exists('images/ID/')) {
						mkdir('images/ID/', 0777, true);
					}
					$sourcePath = $_FILES['photoedit']['tmp_name'];
					$targetPath = "../images/ID/".basename($sss);

						if(is_uploaded_file($_FILES['photoedit']['tmp_name'])) 
						{
							
								if(move_uploaded_file($sourcePath,$targetPath)) {
									
									
									mysqli_query($con, "Update registration set photo_of_applicant  = '$sss' where 
									registration_id = $photoeditid");
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
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select photo_of_applicant from registration where registration_id = $photoeditid"));
		?>
		<img src = "images/ID/<?php echo $row['photo_of_applicant'];?>" width  = "100%">
		<?php
			
}
if(isset($_REQUEST['regdelete']))
{
	foreach($_REQUEST as $key=>$val) {
		${$key} = trim($val);
	//echo "The value of ".$key." is ". $val." <br>";
	}
	$user = get_user_id($_SESSION['c_craft']);
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from registration where registration_id = $regdelete and isdeleted = 1"));
	
	if($check == 0)
	{
		mysqli_query($con,"update registration set
		isdeleted = 1
		where registration_id = $regdelete");
		
		?>
			<script>
				alert("Registration has been deleted");
			</script>
		<?php
		
	}
	else
	{
		?>
			<script>
				alert("Registration is already deleted");
			</script>
		<?php
	}
	expense($deleteexpensedfrom,$deleteexpensedto,0);
}
if(isset($_REQUEST['rdrangeui']))
{
	?>
		<div class="col-md-3" id = "cdfromui">
									<div class = "form-group">
										<label>Valid From:</label>
										<input type = "date" class = "form-control" name = "rrdfromedit" id = "rrdfrom" data-validation="date" 
										value = "<?php echo $row['valid_from'];?>"
										data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
									</div>		
								</div>
								<div class="col-md-3" id = "cdtoui">
									<div class = "form-group">
										<label>Valid Until:</label>
										<input type = "date" class = "form-control" name = "rrdtoedit"  id = "rrdto" data-validation="date" 
										value = "<?php echo $row['valid_to'];?>"
										data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
									</div>
								</div>
	<?php
}

if(isset($_REQUEST['cdrangeui']))
{
	?>
		<div class="col-md-3" id = "cdfromui">
									<div class = "form-group">
										<label>Valid From:</label>
										<input type = "date" class = "form-control" name = "cdfromedit" id = "rrdfrom" data-validation="date" 
										value = "<?php echo $row['valid_from'];?>"
										data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
									</div>		
								</div>
								<div class="col-md-3" id = "cdtoui">
									<div class = "form-group">
										<label>Valid Until:</label>
										<input type = "date" class = "form-control" name = "cdtoedit"  id = "rrdto" data-validation="date" 
										value = "<?php echo $row['valid_to'];?>"
										data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
									</div>
								</div>
	<?php
}
if(isset($_REQUEST['regdrangeui']))
{
	?>
		<div class="col-md-3">
								<div class = "form-group">
									<label>Valid From:</label>
									<input type = "date" class = "form-control" name = "rrdfrom" id = "rrdfrom" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date From">
								</div>		
							</div>
							<div class="col-md-3">
								<div class = "form-group">
									<label>Valid Until:</label>
									<input type = "date" class = "form-control" name = "rrdto"  id = "rrdto" data-validation="date" 
									value = "<?php echo date('Y-m-d');?>"
									data-validation-format="yyyy-mm-dd" placeholder = "yyyy-mm-dd"  data-validation-error-msg="Enter valid Date to">
								</div>
							</div>
	<?php
}

if(isset($_REQUEST['creportui']))
{
	?>
		<H2>CUSTOMER REPORTS GENERATION</h2>
		<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" ID = "clist" data-toggle="tab">CUSTOMER LIST</a></li>
              <li><a href="#tab_1" data-toggle="tab" id = "reglist">REGISTRATION LIST</a></li>
			  <li><a href="#tab_1" data-toggle="tab" id = "referrals">REFERRAL LIST</a></li>
            </ul>
			<script>
				$('#tab_1').html(loading);	
				$.post( 
																'php/customer.php',
																{
																	clistreport:1
																},
																function(data) {
																	$('#tab_1').html(data);		
																});
																
				$("#clist").click(
					function(e)
					{
						e.preventDefault();
						$('#tab_1').html(loading);	
						$.post( 
																'php/customer.php',
																{
																	clistreport:1
																},
																function(data) {
																	$('#tab_1').html(data);		
																});
					}
				);
				
				$("#reglist").click(
					function(e)
					{
						e.preventDefault();
						$('#tab_1').html(loading);		
						$.post( 
																'php/customer.php',
																{
																	reglistui:1
																},
																function(data) {
																	$('#tab_1').html(data);		
																});
					}
				);
				
				$("#referrals").click(
					function(e)
					{
						e.preventDefault();
						$('#tab_1').html(loading);		
						$.post( 
																'php/customer.php',
																{
																	referralreportui:1
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
        
	<?php
}
if(isset($_REQUEST['clistreport']))
{
	?>
	
			<form id = "cmfilterform" method = "POST">
					
					
						<div class="row">
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
								
								<div class="col-md-4" style = "display:none;">
											 <div class="form-group">
													
													 <label for="age">Membership Type:</label>
														<select id = "cmmtype" name = "cmmtype" class="form-control" data-validation="required"
															data-validation-error-msg="Select Membership Type">
														<option  hidden "Selected"></option>
														<option value = "00">ALL</option>
														<option value = "1">NON-MEMBER</option>
														<option value = "0">MEMBER</option>
													</select>
													
											</div>
								</div>
								
								<div class="col-md-4" style = "padding-top:25px;">
								
								 <div class="form-group">
									
									<button class = "btn btn-warning btn-flat" id = "printcm">PRINT RESULT</button>
							
									</div>
								</div>
								
							</div>
						
						
					
				
				
				
				</form>
				<script>
						
							$("#printall").click(
								function(e)
								{
									e.preventDefault();
									
									$.post( 
																	'php/customer.php',
																	{
																		printallcmaster:1
																	},
																	function(data) {
																		$('#click').html(data);	
																		
																	});
									
								}
							);
							
							
							
										$.validate({
															form:'#cmfilterform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																
																$('#click').html("");	
																 var formData = $('#cmfilterform').serializeArray();
																	 //var formData = new FormData($('#regform')[0]);
																	
																			$.ajax({
																				url :  'php/customer.php',
																				type : 'post',
																				datatype : 'json',
																				data : formData,
								
																				success : function(data) {
																					$("#click").html(data);
																					//alert("OK");
																				}
																			});

															  return false; // Will stop the submission of the form
															},
														});
								
							
						
						</script>
		
	<?php
}
if(isset($_POST['cmtype']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	
	
	$header = "";
	if($cmtype != "0")
	{
		$cus = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_customer_type where customer_type_id = $cmtype"));
		
		$header = $cus['customer_type_name']." Customer List";
		
	}
	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "images/logo.png" width = 100></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">CUSTOMER MASTERLIST</h4>
			<h4 style = "text-align:center"><?php echo $header;?></h4>
			
			<?php
				 customer_masterlist('','','','',1,$cmtype,"","","");
				
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
if(isset($_REQUEST['reglistui']))
{
	?>
	<form id = "regsearchform" method = "POST">
		<div class = "row">
				<div class="col-md-4">
											 <div class="form-group">
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">Registration Status:</label>
														<select id="prstatus" name = "prstatus" class="form-control" data-validation="required"
															data-validation-error-msg="Select Registration Status">
														<option value = "all" hidden "Selected">ALL</option>
														<option value = "all">ALL</option>
														<?php
														$cquery = mysqli_query($con,"Select * from lup_registration_status where isdeleted = 0");
														while($crow = mysqli_fetch_assoc($cquery))
														{
														?>
														<option><?php echo $crow['registration_status_description'];?></option>
														<?php
														}
														?>
													</select>	
					</div>
				</div>
				
				<div class="col-md-4">
											 <div class="form-group">
													<input type = "hidden" value = "0" name = "mtype">
													 <label for="age">Membership Card:</label>
														<select id="prcard" name = "prcard" class="form-control" >
														<option value = "all" hidden "Selected">ALL</option>
															<option value = "all">ALL</option>
														<?php
														$cquery = mysqli_query($con,"Select * from lup_card_type where isdeleted = 0");
														while($crow = mysqli_fetch_assoc($cquery))
														{
														?>
														<option><?php echo $crow['card_type_description'];?></option>
														<?php
														}
														?>
													</select>	
					</div>
				</div>
				
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">VALIDITY:</label>
								
									<select id="prvalidity" name = "prvalidity" class="form-control" data-validation="required"
																		data-validation-error-msg="Select VALIDITY">
										<option value = "all" hidden "Selected">ALL</option>
										<option value = "all">ALL</option>										
										<option value = "1">DATE RANGE</option>
										<option value = "0">LIFETIME</option>
									</select>	
								</div>
							</div>
							
							<div class="col-md-4">
								<div class = "form-group">
									<label>Valid From:</label>
									<input type = "date" class = "form-control" name = "prdfrom" id = "prdfrom">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Valid Until:</label>
									<input type = "date" class = "form-control" name = "prdto"  id = "prdto">
								</div>
							</div>
							
							
							<div class="col-md-4">
								<div class = "form-group">
									<label>Registration Date From:</label>
									<input type = "date" class = "form-control" name = "pregdfrom" id = "pregdfrom">
								</div>		
							</div>
							<div class="col-md-4">
								<div class = "form-group">
									<label>Registration Date to:</label>
									<input type = "date" class = "form-control" name = "pregdto"  id = "pregdto">
								</div>
							</div>
							
							<div class="col-md-5" style = "padding-top:25px;">
														<div class = "form-group">
															<button class = "btn btn-success btn-flat btn-sm" id = "go">PRINT RESULT</button>
														
														</div>	
							</div>
		</div>
	</form>
		<script>
			
											$.validate({
															form:'#regsearchform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#regsearchform').serializeArray();
																			$.ajax({
																				url :  'php/customer.php',
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
	<?php
}
if(isset($_POST['prstatus']))
{
		foreach($_POST as $key=>$val) {
			${$key} = $val;
		//echo "The value of ".$key." is ". $val." <br>";
		} 	

	?>
		<div id = "printt">
			<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
			<h3 style = "text-align:center"><?php echo get_company();?></h3>
			<h4 style = "text-align:center">REGISTRATION MASTERLIST</h4>
			
			<table class = "table table-bordered table-hover table-sm">
				<?php
				if($prstatus != "")
				{
					?>
					<td>STATUS: <?PHP echo $prstatus;?></td>
					<?php
				}
				if($prcard != "")
				{
					?>
					<td>CARD TYPE: <?PHP echo $prcard;?></td>
					<?php
				}
				if($prvalidity != "all")
				{
					?>
					<td>VALIDITY: <?PHP
						IF($prvalidity == 1)
							echo "WITH VALIDITY PERIOD";
						else 
							echo "LIFETIME";
					?></td>
					<?php
				}
				else
				{
					?>
					<td>VALIDITY:ALL</td>
					<?php
				}
				if($prdfrom != "")
				{
					?>
					<td>VALIDITY DATE: <?PHP
						echo $prdfrom." - ".$prdto;
					?></td>
					<?php
				}
				if($pregdfrom != "")
				{
					?>
					<td>REGISTRATION DATE RANGE : <?PHP
						echo $pregdfrom." - ".$pregdto;
					?></td>
					<?php
				}
				
				?>
			</table>
			<?php
				 registration_view($prstatus,$prdfrom,$prdto,1,$prvalidity,$pregdfrom,$pregdto,$prcard);
				
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
if(isset($_REQUEST['referralreportui']))
{
	?>
	<H3>REFERRAL REPORT</H3>
	<form id = "regsearchform" method = "POST">
		<div class = "row">
			<div class="col-md-4">
				 <div class="form-group">								
					<label for="age">SEARCH:</label>
					<input type="text" id="ref" name="refreportref" class="form-control" placeholder = "Enter last name/customer number" autocomplete="off"
					data-validation="required" data-validation-error-msg="ENTER KEY TO SEARCH">
					<input type="hidden" id="clickval">
					<div id = "search_result"></div>												
				</div>
			</div>
			
			<div class="col-md-5" style = "padding-top:25px;">
										<div class = "form-group">
											<button class = "btn btn-success btn-flat btn-sm" id = "go">PRINT</button>
										</div>	
			</div>
		</div>
	</form>
	<div id = "calert"></div>
		<script>
			
								
											$.validate({
															form:'#regsearchform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
															
																 var formData = $('#regsearchform').serializeArray();
																 
																			$.ajax({
																				url :  'php/customer.php',
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
	<?php
}

if(isset($_POST['refreportref']))
{
	foreach($_POST as $key=>$val) {
			${$key} = $val;
		echo "The value of ".$key." is ". $val." <br>";
		} 
	
	$check = mysqli_num_rows(mysqli_query($con,"Select * from customer_profile where customer_profile.customer_no = '$refreportref'"));
	
	if($check != 0)
	{
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from customer_profile where customer_profile.customer_no = '$refreportref'"));
	?>
		
				<div id = "printt">
					<p style = "text-align:center;?>"><img src = "../images/logo.png" width = 150></p>
					<h3 style = "text-align:center"><?php echo get_company();?></h3>
					<h4 style = "text-align:center">REFERRALS</h4>
					<h4 style = "text-align:center"><?php echo $row['customer_no']."-".$row['firstname']." ".$row['middlename']." ".$row['lastname'];?></h4>
				
					<?php
						 referrals($row['customer_id']);
						
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
	else
	{
		?>
			<script>
				notify("No Search Result","#invalert");
			</script>
		<?php
	}
}
if(isset($_REQUEST['viewref']))
{
	?>
		<div class="box">
			<div class="box-body">
				<?php customer_info($_REQUEST['viewref']);?>
			</div>
		</div>
		
		<h3>REFERRAL LIST</H3>
		<div class="box">
			<div class="box-body">
				<?php referrals($_REQUEST['viewref']);?>
			</div>
		</div>
	<?php
}
if(isset($_REQUEST['rclimitui']))
{
	$id = $_REQUEST['rclimitui'];
	
	$rrow = mysqli_fetch_assoc(mysqli_query($con,"Select * from registration where registration_id = $id"));
	
	?>
	<script>
				$("#drangeui").html("");
	</script>
	<h2>CREDIT LIMIT ALLOCATION</h2>
	<div class="box">
			<div class="box-body">
				<?php customer_info($rrow['customer_id']);?>
			</div>
	</div>
	<div class="box">
			<div class="box-body">
				<form id = "regeditform" method = "POST">
			
						<div class = "row">
							<div class="col-md-4">
												<div class="form-group">
													<label>Credit Line:</label>
												
													<Select class = "form-control" name = "clcline" data-validation="required"
													data-validation-error-msg="Select Department">
														<option value = "" hidden "Selected"> </option>
													<?php
													$pmquery = mysqli_query($con,"Select * from lup_credit_line_limit where isdeleted = 0");
													while($prow = mysqli_fetch_assoc($pmquery))
													{
													?>
														<option value = "<?php echo $prow['credit_line_limit_id'];?>"><?php echo $prow['credit_line_limit_description'];?></option>
													
													<?php
													}
													?>
													</select>
												
												</div>
							</div>
										
							<div class="col-md-3">
								<div class="form-group">
									<label for="age">VALIDITY:</label>
									<input type = "hidden" value = "<?php echo $id;?>" name = "clreg">
									<select id="clvalidity" name = "clvalidity" class="form-control" data-validation="required"
																		data-validation-error-msg="Select Validity">
										
										<option value = "0" hidden "Selected"></option>		
												
										<option value = "1">DATE RANGE</option>
										<option value = "0">LIFETIME</option>
									</select>	
								</div>
							</div>
							<script>
								$("#clvalidity").change(
									function()
									{
										var r = $("#clvalidity").val();
										
										if(r == 1)
										{
														$.post( 
																'php/customer.php',
																{
																	cdrangeui:1
																	
																},
																function(data) {
																	$('#drangeui').html(data);		
																});
																
											/*$("#rrdfromedit").attr("data-validation","date");
											$("#rrdfromedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdfromedit").attr("data-validation-error-msg","Enter valid Valid From");
											
											$("#rrdtoedit").attr("data-validation","date");
											$("#rrdtoedit").attr("data-validation-format","yyyy-mm-dd");
											$("#rrdtoedit").attr("data-validation-error-msg","Enter valid Valid Until");*/
										}
										else
										{
											
												$("#drangeui").html("");
											/*$("#rrdfromedit").attr("data-validation","");
											$("#rrdfromedit").attr("data-validation-format","");
											$("#rrdfromedit").attr("data-validation-error-msg","");
											
											$("#rrdtoedit").attr("data-validation","");
											$("#rrdtoedit").attr("data-validation-format","");
											$("#rrdtoedit").attr("data-validation-error-msg","");*/
											
										}
									}
								);
							</script>
							<div id = "drangeui">
								<div class="col-md-3" id = "cdfromui">
									<div class = "form-group">
										<label>Valid From:</label>
										<input type = "date" class = "form-control" name = "cdfromedit" id = "cdfromedit" 
										value = "<?php echo $row['valid_from'];?>" placeholder = "yyyy-mm-dd">
									</div>		
								</div>
								<div class="col-md-3" id = "cdtoui">
									<div class = "form-group">
										<label>Valid Until:</label>
										<input type = "date" class = "form-control" name = "cdtoedit"  id = "cdtoedit"  
										value = "<?php echo $row['valid_to'];?>" placeholder = "yyyy-mm-dd">
									</div>
								</div>
							</div>
							

							<div class="col-md-3" style = "padding-top:25px;">
														<div class = "form-group">
															<button class = "btn btn-success btn-flat btn-sm" id = "go">SAVE</button>
															<button class = "btn btn-danger btn-flat btn-sm" id = "cancel">CANCEL</button>
														</div>	
							</div>							
					</div>
				</form>
				<script>
							$("#cancel").click(
								function(e)
								{
									e.preventDefault();
									$("#modal").modal("hide");
									$("#modalui").html("");

								}
							);
							
							/*$("#save").click(
								function()
								{
								
									var check = $("#clickval").val();
									if(check == "")
									{
										$("#ref").val("");
									}

								}
							);*/
							
							$.validate({
															form:'#regeditform',
															validateOnBlur : false,
															errorMessagePosition : 'top',
															modules : 'security',
															onSuccess : function($form) {
																var formData = $('#regeditform')[0];
																$.ajax({
																						url: 'php/customer.php',
																						type: "POST",
																						data:  new FormData(formData),
																						contentType: false,
																						cache: false,
																						processData:false,
																						success: function(data)
																						{
																							$("#climitlist").html(data);
																					
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
	<div id = "clalert"></div>
	<div class="box">
			<div class="box-body" id = "climitlist">
				<?php creditlmit($id,0); ?>
			</div>
	</div>
	<?php
}
if(isset($_POST['clreg']))
{
	foreach($_POST as $key=>$val) {
		${$key} = trim(strtoupper($val));
	//echo "The value of ".$key." is ". $val." <br>";
	} 
	
	if($clvalidity  == 0)
	{
		$cdfromedit = "";
		$cdtoedit = "";
	}
	
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from registration where registration_id = $clreg"));
	$limit = mysqli_fetch_assoc(mysqli_query($con,"Select * from lup_credit_line_limit where credit_line_limit_id = $clcline "));
	
	$user = get_user_id($_SESSION['c_craft']);
	$agent = get_agent($user);
	
		$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuvwxyz0123456789";
			$validCharNumber = strlen($validCharacters);
			 
			$length = 10;
			$result = "";
		
				for ($i = 0; $i < $length; $i++) {
					$index = mt_rand(0, $validCharNumber-1);
					$result .= $validCharacters[$index];
				}
				
		$save = mysqli_query($con,"Insert into credit_line_allocation set 
		result = '$result',
		credit_line_allocation_no = '',
		allocation_date = NOW(),
		registration_id = $clreg,
		customer_id = $row[customer_id],
		credit_line_limit_id = $clcline ,
		credit_line_limit_amount = '$limit[credit_line_limit_amount]',
		valid_from = '$cdfromedit',
		valid_to = '$cdtoedit',
		remarks = 'ALLOCATION',
		user_id = $user,
		isdeleted = 0
		");
		
		
	
		if($save)
		{
		?>
			<script>
				notify("<i class='fa fa-info'></i> New Credit Limit Allocation Added","#clalert");
			</script>
		<?php
		}
		else
		{
		?>
			<script>
				notify("<i class='fa fa-exclamation-triangle'></i> Error Saving New Credit Limit Allocation, Contact the System Administrator", "#clalert");
			</script>
		<?php
		}
		
		$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from  credit_line_allocation where result = '$result'"));
								
								if(empty($row))
								{
									$total_enrolled = 1;
								}
								else
								{
									$total_enrolled = $row['credit_line_allocation_id'];
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
									
			$idno = $row['credit_line_allocation_id'].$count;
			mysqli_query($con,"Update credit_line_allocation set credit_line_allocation_no = '$idno',result = '' where credit_line_allocation_id = $row[credit_line_allocation_id]");
			
			$idno = $idno.date('Ymd');
			insert_cline($row['customer_id'],0,$idno,$limit['credit_line_limit_amount'],$row['credit_line_allocation_id'],$idno,'ALLOCATE','ALLOCATE',$agent);
			
	creditlmit($clreg,0);
}

?>
