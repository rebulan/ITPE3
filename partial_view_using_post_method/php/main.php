<?php
	
	if(isset($_REQUEST['page1']))
	{
		?>
		<div id = "printpage">
		<h1>THIS IS PAGE 1</h1>
		<p style = "width:250px; color:#fff; background-color:#000; padding:5px;">
			Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
		</p>
		</div>
		<button id = "print1">PRINT PAGE 1</button>
		<script>
			$("#print1").click(
				function()
				{
						$("#printpage").print();
						alert('OK');
				}
			);
		</script>
		<?PHP
	}

	if(isset($_REQUEST['page2']))
	{
		?>
		<div id = "printpage2">
		<h1>THIS IS PAGE 2</h1>
		<p style = "width:250px;color:#000; background-color:#006633;padding:5px;">
			Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
		</p>
		</div>
		<button id = "print2">PRINT PAGE 2</button>
		<script>
			$("#print2").click(
				function()
				{
						$("#printpage2").print();
				}
			);
		</script>
		<?PHP
	}		
?>