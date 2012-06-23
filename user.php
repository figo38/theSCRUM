<?php
	$pageTitle = 'User management';
	include '_include/header.php';
	$flagHasRight = $USERAUTH->isAdmin();
	
	if (!(isset($sortby) && (strcmp($sortby, 'login') == 0 || strcmp($sortby, 'last_login_date') == 0))) {
		$sortby = 'login';
	}
	
	$U = Dbuser::getAllUsers($sortby);
?>
<h1>User management</h1>

<?php
	if ($flagHasRight) { 
?>
<div id="actionbar">
	<div class="left">
		<button type="button" id="addnewobject"><span class="btngAddObject">Add a new user</span></button>
	</div>
	<div class="right sortby">
    	Sort by: <a href="<?php echo PATH_TO_ROOT;?>user-management/login"<?php if (strcmp($sortby, 'login') == 0) echo ' class="selected"';?>>user login</a> 
        - <a href="<?php echo PATH_TO_ROOT;?>user-management/last_login_date"<?php if (strcmp($sortby, 'last_login_date') == 0) echo ' class="selected"';?>>last login date</a>
	</div>    
</div>
<?php
	}
?>

<table>
<thead>
<tr>
	<th>User login</th>
	<th>Administrator</th>
	<th>Last login date</th>
    <th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php
	if ($U) {
		foreach ($U as $key => $user) { 
			$U = new DbuserDisplay($user);
			$U->render();
		}
	}
?>
</tbody>
</table>

<script>
<!--
//-->
</script>