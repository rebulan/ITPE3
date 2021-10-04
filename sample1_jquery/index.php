<!DOCTYPE html>
<html>
<head>
<script src="js/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#btn1").click(function(){
		$("#maincontent").html("<h1>BUTTON 1 IS CLICKED!</h1>");
  });

  $("#btn2").click(function(){
		$("#maincontent").html("<h1>BUTTON 2 IS CLICKED!</h1>");
  });
  
  $("#btn3").click(function(){
		var s = `
			<h1>What is Lorem Ipsum?</h1>
			
			<p>
				<i>Lorem Ipsum</i> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
			</p>
		`;
		$("#maincontent").html(s);
  });
  
});
</script>
</head>
<body>

<button id="btn1">BUTTON 1</button>
<button id="btn2">BUTTON 2</button>
<button id="btn3">FORMATTED PARAGRAPH</button>

<div id = "maincontent">

</div>
</body>
</html>