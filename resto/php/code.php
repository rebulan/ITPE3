<?php
include('connect.php');

$sidebar = "";

function accesslevel($mod)
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con,"Select * from se_user where user_username = '$_SESSION[c_craft]'"));
	
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
function geten()
{
	global $con;
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from encryptval"));
	return $row['en'];	
}
function encrypt($data,$pass)
{
    $salt = substr(md5(mt_rand(), true), 8);
    $key = md5($pass . $salt, true);
    $iv  = md5($key . $pass . $salt, true);
    $ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
    return base64_encode('Salted__' . $salt . $ct);
}
function decrypt($data,$pass)
{
    $data = base64_decode($data);
    $salt = substr($data, 8, 8);
    $ct   = substr($data, 16);
    $key = md5($pass . $salt, true);
    $iv  = md5($key . $pass . $salt, true);
    $pt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ct, MCRYPT_MODE_CBC, $iv);
    return $pt;
}
function mainmenu($ispages)
{
	if($ispages == 1)
		$pages = '';
	else
		$pages = 'modules/';
	
	global $con;
	$user = mysqli_fetch_assoc(mysqli_query($con,"Select user_id from se_user where user_username = '$_SESSION[c_craft]'"));
	
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
        <li class="header" STYLE = "font-size:12px;text-align:center;">POINT OF SALES SYSTEM</li>
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
function sidemenu($module)
{
	$btn = array('btn btn-primary btn-sm','btn btn-secondary btn-sm','btn btn-success btn-sm','btn btn-info btn-sm','btn btn-warning btn-sm','btn btn-danger btn-sm');
	
	$isall = mysql_num_rows(mysql_query("Select * from menu_assignmenttb where (module_id = $module or module_id = 'all') and items = 'all' and employee_no = '$_SESSION[employee]' and deleted = 0"));
	
	if($isall != 0)
	{
		$query = mysql_query("Select * from menu_submoduletb where module_id = $module");
	}
	else
	{
		$query = mysql_query("Select DISTINCT(menu_submoduletb.sub_id) as sub_id from menu_submoduletb,menu_assignmenttb where menu_assignmenttb.module_id = $module
		and menu_assignmenttb.items = menu_submoduletb.sub_id and menu_assignmenttb.deleted = 0 and menu_submoduletb.isdeleted = 0");
	}
	
	$bctr = 0;
	while($row = mysql_fetch_assoc($query))
	{
		$sname = mysql_fetch_assoc(mysql_query("Select name from menu_submoduletb where sub_id = $row[sub_id]"));
		
		$isall = mysql_num_rows(mysql_query("Select * from menu_assignmenttb where (module_id = $module or module_id = 'all') and (items = 'all' or $row[sub_id]) and menu_item = 'all' and employee_no = '$_SESSION[employee]' and deleted = 0"));
		
		if($isall != 0)
		{
			$iquery = mysql_query("Select * from menu_itemtb where submodule = $row[sub_id]");
		}
		else
		{
			$iquery = mysql_query("Select menu_itemtb.* from menu_itemtb,menu_assignmenttb where menu_assignmenttb.items = $row[sub_id]
			and menu_assignmenttb.menu_item = menu_itemtb.menu_item_id and menu_assignmenttb.deleted = 0 and menu_itemtb.isdeleted = 0");
		}			
	?>
	<div class="btn-group" style = "margin-top:-25px;">
			<button type="button" class="<?php echo $btn[$bctr];?> dropdown-toggle" data-toggle="dropdown">
			  <?php echo $sname['name'];?>
			</button>
			<div class="dropdown-menu">
				  <?php
					while($irow = mysql_fetch_assoc($iquery))
					{
					?>		
						<a class="dropdown-item" href="<?php echo $irow['file'];?>?module=<?php echo $module;?>"><?php echo $irow['menu_item_name'];?></a>
					<?php
					}
					?>
			</div>
	</div>	
	<?php
			if($bctr == 5)
				$bctr = 0;
		$bctr++;
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
		 document.title = "RAC POS V.1.0";
	</script>
	<?php
	//$row = mysql_fetch_assoc(mysql_query("Select * from companytb"));
	
	$des = "<img src = '".$pages."images/logo.png' style = 'width:80px;margin-top:-5px;'>";
	return $des;
}

function altcompany()
{
	
	//$row = mysql_fetch_assoc(mysql_query("Select * from companytb"));
	
	$des = "<b>S</b>DS";
	return $des;
}


function indexcheck()
{
	if(!empty($_SESSION['c_craft']))
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
	if(empty($_SESSION['c_craft']))
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
	if(empty($_SESSION['c_craft']))
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
	$user = $_SESSION['c_craft'];
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
   powered by:  <img src = "<?php echo $pages;?>images/rac.png" width = '80' >
	
			
	<?php
}
?>
