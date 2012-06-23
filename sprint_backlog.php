<?php
	/**
	  * Manage the following views:
	  * - sprint management for the project
	  * - sprint backlog
	  * - whiteboard
	  * - burndown chart
	  *
	  * @param $viewtype Decide which page to display (set up by index.php controller)
	  * @param $isConfigured (only if $viewtype=4) Indicates if configuration of current sprint has been done
	  */

	$menu = 2;
	
	switch ($viewtype) {
		case '1':
			$includePortlet = '_portlets/sprint_backlog/view_sprint_backlog.php';
			$JS = array('sprint', 'sprintplanning', 'story', 'sprintbacklog', 'teamallocation', 'retro');	
			$pageTitle = 'Sprint backlog';
			break;
		case '2':
			$includePortlet = '_portlets/sprint_backlog/view_whiteboard.php';
			$JS = array('sprint', 'sprintplanning', 'story', 'sprintbacklog', 'teamallocation', 'burndown', 'retro', 'resizingpostits');
			$pageTitle = 'Whiteboard';
			break;
		case '3':
			$includePortlet = '_portlets/sprint_backlog/view_burndown_chart.php';
			$JS = array('burndown');				
			$pageTitle = 'Burndown chart';
			break;
		case '4':
			// All sprints view
			$includePortlet = '_portlets/sprint_backlog/sprints.php';
			$JS = array('productbacklog', 'sprint');
			$pageTitle = 'Sprint configuration';
			break;
		case '5':
			$includePortlet = '_portlets/sprint_backlog/first_sprint.php';
			$JS = array('productbacklog', 'sprint');
			$pageTitle = 'Sprint configuration';
			$sprintNumber = 1;
			break;
		case '6':
			$includePortlet = '_portlets/sprint_backlog/view_sprint_configuration.php';
			$JS = array('teamallocation', 'dayselection', 'copytasks');			
			$pageTitle = 'Sprint configuration';
			break;			
	}
	include '_portlets/project_header.php';
	
	// Only the product owner and scrum master have right access to the sprint backlog
	$flagHasRight = $USERAUTH->isProductOwnerOf($projectId) || $USERAUTH->isScrumMasterOf($projectId);	
?>

<div id="subsubmenuheader">
<?php if ($viewtype < 4 && isset($S) && (strlen($S->getGoal()) > 0 || $flagHasRight)) { ?>	
	<div id="goals">
		<strong>Sprint goal:</strong>
        <span id="sprint-goal-<?php echo $S->getId();?>"><?php echo $S->getGoal()?></span>
	</div>
<?php } ?>
	<div class="subsubmenu">
		<ul>
			<li<?php if ($viewtype == 4 || $viewtype == 5) echo ' class="selected"';?>>
				<a href="<?php echo $projectUrl?>/sprints/">All sprints</a>
			</li>
<?php if ($viewtype == 6 || (isset($isConfigured) && $isConfigured == 0)) { ?>
			<li<?php if ($viewtype == 6) echo ' class="selected"';?>>
				<a href="<?php echo $projectUrl?>/configuration/<?php echo $sprintNumber?>">Configuration for sprint #<?php echo $sprintNumber?></a>
			</li>
<?php } else { ?>
			<li<?php if ($viewtype == 1) echo ' class="selected"';?>>
				<a href="<?php echo $projectUrl?>/sprintbacklog/<?php echo $sprintNumber?>">The sprint backlog #<?php echo $sprintNumber?></a>
			</li>
			<li<?php if ($viewtype == 2) echo ' class="selected"';?>>
				<a href="<?php echo $projectUrl?>/whiteboard/<?php echo $sprintNumber?>">The whiteboard #<?php echo $sprintNumber?></a>
			</li>
			<li<?php if ($viewtype == 3) echo ' class="selected"';?>>
				<a href="<?php echo $projectUrl?>/burndown/<?php echo $sprintNumber?>">The burndown #<?php echo $sprintNumber?></a>
			</li>
<?php } ?>	
		</ul>
	</div>
</div>

<form method="post" name="dummyForm" action="#">
<input type="hidden" name="sprintBacklog_sprintId" id="sprintBacklog_sprintId" value="<?php echo $sprintId;?>"/>
</form>

<div class="page">

<?php

	if ($viewtype == 4 || $viewtype == 5) {
		include $includePortlet;
	} else { 
		$stories = $P->getAllStories(array('includecompleted' => 1));
		
		if (isset($S)) {	
			$TASKS = $S->getTasks(array('includecompleted' => 1));
		
			if (sizeof($TEAM) > 0) {
				if ($S->isClosed()) {
					include '_portlets/sprint_backlog/closed_sprint.php';
				}
			}

			include $includePortlet;
		}
?>

<select id="teamMemberList">
<option value="">None</option>
<?php if ($TEAM) { foreach ($TEAM as $key => $member) { ?>
<option value="<?php echo $member['login']?>"><?php echo $member['login']?></option>
<?php }} ?>
</select>

<?php if ($S->isClosed() && $flagHasRight) { ?>
<script type="text/javascript">
<!--
new Retro();
-->
</script>
<?php } ?>

<?php
	}
?>