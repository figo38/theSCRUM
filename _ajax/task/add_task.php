<?php
	$storyId = $_REQUEST['id'];
	$sprintId = $_REQUEST['sprintId'];
?>
<form id="sprintBacklog_addTask-<?=$storyId?>" name="sprintBacklog_addTask-<?=$storyId?>" method="post" action="#">

<input type="hidden" name="sprintBacklog_addTask_storyId" id="sprintBacklog_addTask_storyId" value="<?=$storyId?>"/>

<p class="field">
<label for="new_task_<?=$storyId?>">Task:</label>
<textarea style="width:400px; height:100px" id="new_task_<?=$storyId?>"></textarea>
</p>

<p>
<button type="button" id="sprintBacklog_addTask_submit_<?=$storyId?>" class="action">ADD THIS TASK</button>
<button type="button" id="sprintBacklog_addTask_cancel_<?=$storyId?>">CANCEL</button>
</p>

</form>