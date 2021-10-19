<!DOCTYPE html>
<html>
<head>
<script src="js/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$("#form1").submit(//jquery event of html form for submitting of form data into page request
		function(e)
		{
			e.preventDefault()
			 alert('OK');
			 
			 var formData = $('#form1').serializeArray(); // the data encoded in form 1
																																					
				$.ajax({
					url :'php/main.php', //the sending of data in address in this URL
					type : 'post', //method of sending data into page request
					datatype : 'json', //JSON is a format for storing and transporting data.
					data : formData, //the data that will be sent
							
					success : function(data) {
						$("#content").html(data); //the returned data will display in #content selector 
					}
				}
				);
		});
});
</script>
</head>
<body>
<div>
	
	<form id = "form1" method = "POST">
		<label>FIRST NAME:</label>
		<input type = "text" name = "fname" id = "fname">
		<label>LAST NAME:</label>
		<input type = "text" name = "lname" id = "lname">
		<button>SUBMIT</button>
	</form>
	<div id = "content">
		
	</div>
</div>
</body>
</html>