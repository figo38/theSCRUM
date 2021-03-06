<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$storyId = $_REQUEST['id'];
	
	$S = new Story($storyId, true);
	$nbTasks = $S->hasTasks();
	
	$msg = '';
	$msg2 = '';
	if ($S->isEpic()) {
		$msg = 'permanently delete this epic and its belonging user stories';
		if ($nbTasks > 0) {
			$msg2 = $nbTasks . ' tasks</strong> attached to this epic or its belonging user stories.';
		}
	} else {
		$msg = 'permanently delete this user story';
		if ($nbTasks > 0) {
			$msg2 = $nbTasks . ' tasks</strong> attached to this user story.';
		}		
	}	
?>
<form id="productBacklog_deleteStory_<?php echo $storyId?>" name="productBacklog_deleteStory_<?php echo $storyId?>" method="post" action="#">

<p>This will <strong><?php echo $msg?></strong> and cannot be undone.</p>

<?php if (strlen($msg2) > 0) { ?>
<p>This will also permanently delete the <strong><?php echo $msg2?></p>
<?php } ?>

<p>
<button type="button" id="productBacklog_deleteStory_delete_<?php echo $storyId?>" class="delete">DELETE</button>
<button type="button" id="productBacklog_deleteStory_cancel_<?php echo $storyId?>">CANCEL</button>
</p>

</form>