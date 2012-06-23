<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$id = $_GET['id'];
	$T = new Task($id, true);
?>


<p class="field">
<strong>Owner:</strong> <span id="task-owner-<?=$id?>"><?=$T->getOwner()?></span>
</p>

<p class="field">
<strong>Current estimation:</strong> <span id="task-reestim-<?=$id?>"><?=$T->getReestim()?></span>
</p>

<p class="field">
<strong>Worked:</strong> <span id="task-worked-<?=$id?>"><?=$T->getWorked()?></span>
</p>