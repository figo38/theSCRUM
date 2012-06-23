<?php
	include '_include/header.php';

	$U = User::getAllUsers();
?>
<h1>User management</h1>

<table>
<thead>
<tr>
	<th>User login</th>
	<th>Administrator</th>
	<th>Last login date</th>
</tr>
</thead>
<tbody>
<?php foreach ($U as $key => $user) { ?>
<tr>
	<td><?=$user['login']?></td>
	<td><input type="checkbox" <?php if ($user['is_admin'] == 1) { ?>checked="checked"<?php } ?>></td>
	<td><?=$user['last_login_date']?></td>
</tr>
<?php } ?>
</tbody>
</table>