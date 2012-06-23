<?php
	$allReleases = Release::getAllReleases();
	$UNIT = $S->getUnit();
?>

<table>
<thead>
	<th>Story</th>
	<th>To do</th>
	<th>In progress</th>
	<th>Done
<div style="float:right; top:2px; right:2px">
<?=img('icon_big.png', 'Big', 'whiteboardlarge')?>
<?=img('icon_medium.png', 'Medium', 'whiteboardmedium')?>
<?=img('icon_small.png', 'Small', 'whiteboardsmall')?>
</div>
	</th>
</thead>
<?php
	foreach ($stories as $key => $story) { 
		$D = new StoryDisplay($story);
		
		$hasTask = false;
		foreach ($TASKS as $key => $task) {
			if ($task['storyid'] == $D->getId()) {
				$hasTask = true;
			}
		}
		if ($hasTask) {
?>
<tr>
	<td class="taskplaceholder"><?php $D->renderPostIt();?></td>
	<td class="taskplaceholder">
		<div  class="taskplaceholder" id="todolist-<?=$D->getId()?>">
<?php
		foreach ($TASKS as $key => $task) {
			if ($task['storyid'] == $D->getId() && $task['status'] == 0) {
				$TD = new TaskDisplay($task);					
				$TD->renderPostIt();
			}
		}
?>	
		</div>
	</td>
	<td class="taskplaceholder">
		<div class="taskplaceholder" id="inprogresslist-<?=$D->getId()?>">
<?php
		foreach ($TASKS as $key => $task) {
			if ($task['storyid'] == $D->getId() && $task['status'] == 1) {
				$TD = new TaskDisplay($task);					
				$TD->renderPostIt();
			}
		}
?>
		</div>	
	</td>
	<td class="taskplaceholder">
		<div class="taskplaceholder" id="donelist-<?=$D->getId()?>">
<?php
		foreach ($TASKS as $key => $task) {
			if ($task['storyid'] == $D->getId() && $task['status'] == 2) {
				$TD = new TaskDisplay($task);					
				$TD->renderPostIt();
			}
		}
?>		
		</div>		
	</td>
	
</tr>
<?php
		}
	}
?>
</table>

<script type="text/javascript">
<!--
<?php if (!$S->isClosed()) { ?>
<?php if ($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId) || $USERAUTH->isTeamMemberOf($projectId)) { ?>

new SprintBacklog();

<?php } ?>
<?php } ?>
new ResizingWhiteboard();
-->
</script>