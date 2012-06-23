<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$id = $_REQUEST['id'];
	$T = new Task($id, true);
?>

<p class="field">
<strong>Owner:</strong> <span id="task-owner-<?php echo $id?>"><?php echo $T->getOwner()?></span>
</p>

<p class="field">
<strong>Current estimation:</strong> <span id="task-reestim-<?php echo $id?>"><?php echo $T->getReestim()?></span>
</p>

<p class="field">
<strong>Worked:</strong> <span id="task-worked-<?php echo $id?>"><?php echo $T->getWorked()?></span>
</p>