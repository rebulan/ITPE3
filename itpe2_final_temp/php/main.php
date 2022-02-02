<?php
include('connect.php');
//$_SESSION['login'] = "";
?>
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
	var loading = '<div><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>';
</script>

<?php


?>
