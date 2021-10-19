<!DOCTYPE html>
<html>
<head>
<script src="js/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$("#page1").click(
		function()
		{
				$.post( 
					'php/main.php',
					{
						page1:1														
					},
				function(data) {
					$('#content').html(data);	
																					
				});
		}
	);
	
	$("#page2").click(
		function()
		{
				$.post( 
					'php/main.php',
					{
						page2:1															
					},
				function(data) {
					$('#content').html(data);	
																					
				});
		}
	);
	
});
</script>
</head>
<body>
<div>
	<p>This single web page will show a partial page with print function located in php/main.php file.</p>
	<button id = "page1">SHOW PAGE 1</button>
	<button id = "page2">SHOW PAGE 2</button>
	<div id = "content">
		
	</div>
</div>
</body>
</html>