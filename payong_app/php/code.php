<?php
include('connect.php');

$sidebar = "";

function accesslevel($mod)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_username = '$_SESSION[forecast]'"));
	
	$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user_access_module where user_id = $row[user_id]
	and module_id = 'all' and isdeleted = 0"));
	
	if(!empty($check))
	{
		$_SESSION['accesslevel'] = $check['access_level'];
	}
	else
	{
		$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user_access_module where user_id = $row[user_id]
		and sub_module_id = $mod and isdeleted = 0"));
		if(!empty($check))
		{
			$_SESSION['accesslevel'] = $check['access_level'];
		}
		else
		{
			$roww = mysqli_fetch_assoc(mysqli_query($con,"Select module_id from se_sub_module where sub_module_id = $mod"));
			
			$check = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user_access_module where user_id = $row[user_id]
			and sub_module_id = 'all' and module_id = $roww[module_id] and isdeleted = 0"));
			if(!empty($check))
			{
				$_SESSION['accesslevel'] = $check['access_level'];
			}
			else
			{
				?>
					<script>
						window.location.href = "../home.php";
					</script>
				<?php
			}
		}
	}
	?>
		<script>
			//alert('<?php echo $_SESSION['accesslevel'];?>');
		</script>
	<?php
}

function mainmenu($ispages)
{
	if($ispages == 1)
		$pages = '';
	else
		$pages = 'modules/';
	
	global $con;
	$user = mysqli_fetch_assoc(mysqli_query($con,"Select user_id from se_user where user_username = '$_SESSION[forecast]'"));
	
	$mod = mysqli_num_rows(mysqli_query($con,"Select *  from se_user_access_module where isdeleted = 0
	and module_id = 'all' and user_id = $user[user_id]"));
			
	if($mod != 0)
	{
	
		$query = mysqli_query($con, "Select DISTINCT(module_id),icon, module_name from se_module where active = 1");
	}
	else
	{
		$query = mysqli_query($con, "Select DISTINCT(se_module.module_id),icon, module_name from se_module,se_user_access_module where se_module.active = 1
		and se_module.module_id = se_user_access_module.module_id and se_user_access_module.isdeleted = 0
		and se_user_access_module.user_id = $user[user_id]");
	}	
	?>
		<ul class="sidebar-menu" data-widget="tree">
        <li class="header" STYLE = "font-size:14px;text-align:center;font-weight:bold;">PAYONG PAGASA - CPANEL</li>
	   <?php
	    $ctr = 1;
	   while($row = mysqli_fetch_assoc($query))
	   {
	   ?>
        <li class="treeview">
          <a href="#">
            <?php echo $row['icon'];?> <span><?php echo $row['module_name'];?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
		  <?php
			$check = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_user_access_module where (module_id = $row[module_id] or module_id = 'all')
			and sub_module_id = 'all' and isdeleted = 0 and user_id = $user[user_id]"));
			
			$level = 0;
			$access = "";
			if(!empty($check))
			{
				$access = "all";
				$level = $check['access_level'];
				$squery = mysqli_query($con,"Select * from se_sub_module where module_id = $row[module_id] and active = 1");
			}
			else
			{
				$squery = mysqli_query($con, "Select se_sub_module.*,se_user_access_module.access_level from se_sub_module,se_user_access_module where se_sub_module.module_id = $row[module_id] and se_sub_module.active = 1
				and se_sub_module.sub_module_id = se_user_access_module.sub_module_id and se_user_access_module.isdeleted = 0");
			}
		  ?>
          <ul class="treeview-menu">
		  <?php
		 
		  while($srow = mysqli_fetch_assoc($squery))
		  {
				if($access != 'all')
				{
					$level = $srow['access_level'];
				}
			  //$d = encrypt($srow['sub_id'],geten());
		  ?>
            <li><a href="#" id = "sss<?php echo $ctr;?>"><i class="fa fa-arrow-circle-right"></i> <?php echo $srow['sub_module_name'];?></a></li>
			<script>
				$("#sss<?php echo $ctr;?>").click(
					function(e)
					{
						e.preventDefault();
						$('#maincontent').html(loading);
						$.post( 
								'php/<?php echo $srow['location'];?>.php',
								{
									<?php echo $srow['keyword'];?>:'<?php echo $level;?>'
								},
								function(data) {
									$('#maincontent').html(data);	
								});
								
					}
				);
			</script>
		 <?php
			$ctr++;
		  }
		  ?>
		  </ul>
        </li>
	<?php
		
	   }
	   ?>
      </ul>
<?php
}
function modheader($module)
{
	if(!empty($module)){

		$row = mysql_fetch_assoc(mysql_query("Select * from menu_modulestb where module_id = $module"));
		if(!empty($row))
		{
			$des = $row['icon']." ".$row['module'];
			echo $des;
		}
		else
		{
			?>
			<script>
				window.location.href = "../home.php";
			</script>
			<?php
		}
	}
	else
	{
		?>
			<script>
				window.location.href = "../home.php";
			</script>
		<?php
	}
}
function company($ispages)
{
	if($ispages == 1)
		$pages = '../';
	else
		$pages = '';
	
	?>
	<script>
		 document.title = "PAYONG PAGASA CONTROL PANEL";
	</script>
	<?php
	//$row = mysql_fetch_assoc(mysql_query("Select * from companytb"));
	
	$des = "<img src = '".$pages."images/logo.png' style = 'width:60px;margin-top:-5px;'>";
	//$des = "LOGO HERE";
	return $des;
}

function altcompany()
{
	
	//$row = mysql_fetch_assoc(mysql_query("Select * from companytb"));
	
	$des = "<b>P</b>PA";
	return $des;
}


function indexcheck()
{
	if(!empty($_SESSION['forecast']))
	{
		echo "
			<script>
				window.location.href = 'home.php';
			</script>
		";
	}
}

function resetcheck()
{
	if(empty($_SESSION['reset']))
	{
		echo "
			<script>
				window.location.href = 'index.php';
			</script>
		";
	}
}

function homeresetcheck()
{
	if(!empty($_SESSION['reset']))
	{
		echo "
			<script>
				//window.location.href = 'reset.php';
			</script>
		";
	}
}

function pagehomecheck()
{
	if(empty($_SESSION['forecast']))
	{
		echo "
			<script>
				window.location.href = '../index.php';
			</script>
		";
	}
}

function homecheck()
{
	if(empty($_SESSION['forecast']))
	{
		echo "
			<script>
				window.location.href = 'index.php';
			</script>
		";
	}
}

function user()
{
	$user = $_SESSION['forecast'];
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from se_user where user_username = '$user'"));
	
	$fullname = $row['fullname'];
	
	?>
		 <i class="fa fa-user"></i>
              <span class="hidden-xs"><?php echo $fullname;?></span>
	<?php
}
function footer($ispages)
{
	if($ispages == 1)
		$pages = '../';
	else
		$pages = '';
	?>
		<div class="pull-right hidden-xs">
			
		</div>
			powered by: METAVERSE LOGO GOES HERE<!-- <img src = "<?php echo $pages;?>images/rac.png" width = '80' >-->
	
			
	<?php
}
?>
