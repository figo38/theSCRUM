<?php
	/**
	  * Display the panel to define the working days in the current sprint
	  * @param $S Sprint object
	  * @param $configurationUrl Beginning of URL to lead to the configuration page
	  */
	if (isset($_POST['copyFromPreviousSprint'])) {
		if ($_POST['copyFromPreviousSprint'] == '1') {
			$S->copyTasksFromPrevious();
			HelpersControl::infoMsg('Tasks from previous sprints have been copied into this sprint. You can now <a href="#" id="buildSprintBacklog">start building your sprint backlog &raquo;</a>', 'bSprintBacklogMsg');			
		} else {
			$S->updateCopiedFromPrevious(1);
			HelpersControl::infoMsg('Tasks from previous sprints have been ignored. You can now <a href="#" id="buildSprintBacklog">start building your sprint backlog &raquo;</a>', 'bSprintBacklogMsg');
			
		}
	} elseif ($S->getSprintNumber() == 1) {
		// First sprint for the project, so no task to copy
		$S->updateCopiedFromPrevious(1);
		HelpersControl::infoMsg('As this is the first sprint of this project, you can directly <a href="#" id="buildSprintBacklog">start building your sprint backlog &raquo;</a>', 'bSprintBacklogMsg');
	} elseif ($S->hasTasksCopiedFromPrevious()) {
		// Tasks have already been copied, so let's skip it.
		HelpersControl::infoMsg('Tasks from previous sprint have already been copied into this sprint. You can directly <a href="#" id="buildSprintBacklog">start building your sprint backlog &raquo;</a>', 'bSprintBacklogMsg');
	} else {
		HelpersControl::warningMsg('You have to decide if you want to copy tasks from previous sprint before starting <a href="#" id="buildSprintBacklog2">building your sprint backlog &raquo;</a>', 'bSprintBacklogWarnMsg', true);		
		
		$unfinishedTasks = $S->getUnfinishedTasksFromPrevious();
		if (!$unfinishedTasks) {
			HelpersControl::infoMsg('There is no task to copy from the previous sprint. You can directly <a href="#" id="buildSprintBacklog">start building your sprint backlog &raquo;</a>', 'bSprintBacklogMsg');
		} else {
			HelpersControl::infoMsg('Decide if you want to copy tasks from previous sprint. Then <a href="#" id="buildSprintBacklog">build your sprint backlog &raquo;</a>', 'bSprintBacklogMsg');			
?>

<form method="post" action="#" id="copyTasksForm">
<input type="hidden" name="copyFromPreviousSprint" id="copyFromPreviousSprint" value=""/>
</form>

<p style="font-size:14px;">Do you want to add those unfinished tasks from the previous sprint to this sprint?
        <button id="unfinishedtasks-yes" class="action">Yes, please</button>
        <button id="unfinishedtasks-no" class="delete">No, I'm fine</button>    
</p>

<br/>

<table>
<thead>
<tr>
	<th>#</th>
	<th>Prio</th>
	<th>Task</th>
	<th>Owner</th>
	<th>Estim.</th>
	<th>Curr. estim.</th>
	<th>Worked</th>
	<th>New estim</th>
</tr>
</thead>
<tbody>
<?php		
			foreach ($unfinishedTasks as $k => $task) {
?>
<tr class="taskrow">
	<td class="tid"><strong>TASK</strong></td>
	<td><?php if ($task['prio']) echo $task['prio']; else echo '0';?></td>
	<td><?php echo $task['title'];?></td>
	<td><strong>Own.:</strong></td>
	<td><strong>Estim.:</strong> <?php echo $task['tsk_estim'] . $S->getUnit();?></td>
	<td><strong>Curr. estim.:</strong> <?php echo $task['tsk_reestim'] . $S->getUnit();?></td>	
	<td><strong>Worked:</strong> <?php echo $task['tsk_worked'] . $S->getUnit();?></td>
	<td style="background-color:#fff"><?php echo $task['estim'] . $S->getUnit()?></td>
</tr>
<?php
			}
?>
</tbody>
</table>
<?php			
		}
	}
?>

<script type="text/javascript">
<!--
new CopyTasks(<?php echo $S->getId();?>, "<?php echo $projectUrl . '/sprintbacklog/' . $sprintNumber . '/';?>");
-->
</script>