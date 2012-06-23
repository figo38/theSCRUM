<form id="addnewobject_form" name="addnewobject_form" method="post" action="#">
<?php
	$objectname = $_REQUEST['objname'];
	include $objectname . '/add.php';
?>
<p>
<button type="button" id="addnewobject_submit" class="action">Add it</button>
<button type="button" id="addnewobject_cancel">CANCEL</button>
</p>
</form>