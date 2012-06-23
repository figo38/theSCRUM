<?php
	$allReleases = Release::getAllReleases();
	$UNIT = $S->getUnit();
	
	if (!$S->isClosed() && $S->getEndDate() != NULL) {
		$dt2 = explode('-', $S->getEndDate());
		$d2 = mktime(19,0,0,$dt2[1], $dt2[2], $dt2[0]);		
?>

<div style="padding-top:5px; padding-bottom:5px;">
The sprint will end <?php echo date('M d, Y', $d2) ?> (<?php echo HelpersDate::timeago($d2);?>).
<?php echo $daysDiff;?>
</div>
<?php
	} else echo '<br/>';
?>

<table>
<thead>
	<th>Story</th>
	<th>To do</th>
	<th>In progress</th>
	<th>Done
<div style="float:right; top:2px; right:2px">
<?php echo img('icon_big.png', 'Big', 'whiteboardlarge')?>
<?php echo img('icon_medium.png', 'Medium', 'whiteboardmedium')?>
<?php echo img('icon_small.png', 'Small', 'whiteboardsmall')?>
</div>
	</th>
</thead>
<?php
	if ($stories) {
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
		<div  class="taskplaceholder" id="todolist-<?php echo $D->getId()?>">
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
		<div class="taskplaceholder" id="inprogresslist-<?php echo $D->getId()?>">
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
		<div class="taskplaceholder" id="donelist-<?php echo $D->getId()?>">
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
	}
?>
</table>

<script type="text/javascript">
<!--
<?php
	if (!$S->isClosed()) {
		if ($flagHasRight) {
?>
new PBInPlaceEditor('sprint-goal-<?php echo $S->getId();?>', { rows:3 });
<?php
		}
		if ($flagHasRight || $USERAUTH->isTeamMemberOf($projectId)) {
?>
new SprintBacklog();
<?php 
		} 
	} 
?>
new ResizingPostIts();
-->
</script>