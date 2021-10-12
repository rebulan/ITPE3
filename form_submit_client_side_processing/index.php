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
			var fname = $("#fname").val();//storing the value of #fname text input into variable
			var lname = $("#lname").val();//storing the value of #lname text input into variable
			
			document.write('HELLO '+fname+" "+lname);//display value of two text input using concatenatio and document.write
		}
	);
});
</script>
</head>
<body>
<div>
	
	<form id = "form1">
		<label>FIRST NAME:</label>
		<input type = "text" id = "fname">
		<label>LAST NAME:</label>
		<input type = "text" id = "lname">
		<button>SUBMIT</button>
	</form>
</div>
</body>
</html>