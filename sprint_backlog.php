<?php
	$JS = array('sprint', 'sprintplanning', 'story', 'sprintbacklog', 'teamallocation', 'burndown', 'retro');		
	$menu = 2;
	$includePortlet = '';
	$pageTitle = '';	
	switch ($viewtype) {
		case '1':
			$includePortlet = '_portlets/sprint_backlog/view_sprint_planning.php';
			$pageTitle = 'Sprint planning';
			break;
		case '2':
			$includePortlet = '_portlets/sprint_backlog/view_sprint_backlog.php';
			$pageTitle = 'Whiteboard';
			break;
		case '3':
			$includePortlet = '_portlets/sprint_backlog/view_burndown_chart.php';
			$pageTitle = 'Burndown chart';
			break;					
	}
	include '_portlets/project_header.php';

	$showCompletedTasks = 1;
	if (isset($_POST['id'])) {
		$showCompletedTasks = isset($_POST['showCompletedTasks']) ? $_POST['showCompletedTasks'] : 0;
	}
		
	$stories = $P->getAllStories(array('includecompleted' => 1));
		
	if (isset($S)) {
		if (isset($_POST['copyFromPreviousSprint']) && $_POST['copyFromPreviousSprint'] == '1') {
			$S->copyTasksFromPrevious();
		}
	
		$TASKS = $S->getTasks(array('includecompleted' => $showCompletedTasks));
		
		if (sizeof($TEAM) > 0) {
?>
<form method="post" id="filtering" action="<?=$projectUrl?>">
<div id="formgoalbox">
	<div id="filteringoptions">
		<div class="filters">
			<h2>Filtering options</h2>
			<input type="hidden" name="id" id="sprintBacklog_projectId" value="<?=$projectId?>"/>
			<input type="hidden" name="sprintid" id="sprintBacklog_sprintId" value="<?=$sprintId?>"/>			
			<input type="hidden" name="sprintnumber" id="sprintBacklog_sprintNumber" value="<?=$sprintNumber?>"/>			
			<input type="checkbox" name="showCompletedTasks" id="showCompletedTasks" value="1" <?php if ($showCompletedTasks == 1) echo 'checked="checked"'; ?>/> <label for="showCompletedTasks" style="margin-right:40px">Show completed tasks</label>
	
			<label for="viewtype">View:</label>
			<select name="viewtype" id="viewtype">
				<option value="1"<?php if ($viewtype==1) echo ' selected="selected"';?>>Sprint planning</option>
				<option value="2"<?php if ($viewtype==2) echo ' selected="selected"';?>>Whiteboard</option>
				<option value="3"<?php if ($viewtype==3) echo ' selected="selected"';?>>Burndown chart</option>                
			</select>
	
			<button type="submit" id="submitform">Refresh</button>
		</div>
	</div>
<?php if (strlen($S->getGoal()) > 0) { ?>	
	<div id="goals"><strong>Sprint goal:</strong> <?=$S->getGoal()?></div>
<?php } ?>
	<div class="clear"></div>
</div>
</form>
<br/>

<?php
			if ($S->isClosed()) {
				include '_portlets/sprint_backlog/closed_sprint.php';
			}
		}

		include $includePortlet;
	} else {
?>
<div class="infoMsg">
	<div class="inner">
		No team has been set up for this project. <a href="./manage_project_team.php?id=<?=$projectId?>">Go and build your team &raquo;</a>
	</div>
</div>
<?php
	}
?>

<select id="teamMemberList">
<?php if ($TEAM) { foreach ($TEAM as $key => $member) { ?>
<option value="<?=$member['login']?>"><?=$member['login']?></option>
<?php }} ?>
</select>

<script type="text/javascript">
<!--
$('submitform').observe('click', function(event) {
	var curr = $('filtering').action;
	var sprintnumber = $F('sprintBacklog_sprintNumber');
	if ($F('viewtype') == 1) { $('filtering').action = curr + '/sprintbacklog/' + sprintnumber;} else
	if ($F('viewtype') == 2) { $('filtering').action = curr + '/whiteboard/' + sprintnumber;} else
	if ($F('viewtype') == 3) { $('filtering').action = curr + '/burndown/' + sprintnumber;}	
});
<?php if ($S->isClosed() && ($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId))) { ?>
new Retro();
<?php } ?>
-->
</script>