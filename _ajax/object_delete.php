<?php
	include_once '../global.php';
	
	// ID of the HTML element that contains the value to be updated
	// Those IDs are standardized as: "[objectname]-delete-[objectid]"
	// I.E: "delete-sprint-34"
	$fieldId = $_REQUEST["id"];

	// Exploding the fieldID
	$idtab = explode('-', $fieldId);
	$objectname = $idtab[0];
	$objectid = $idtab[2];	
?>
<form id="task-delete-<?=$objectid?>" name="task-delete-<?=$objectid?>" method="post" action="#">
<?php include $objectname . '/delete.php'; ?>

<p>
<button type="button" id="<?=$fieldId?>-go" class="delete">DELETE</button>
<button type="button" id="<?=$fieldId?>-cancel">CANCEL</button>
</p>

</form>