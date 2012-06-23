<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$sprintId = $_GET['sprintid'];
	$storyId = $_GET['id'];
	$task = $_GET['task'];

	$S = new Sprint($sprintId, true);
	$taskId = $S->addTask($storyId, $task);
	$projectId = $S->getProjectId(); // Used to verify rights when displaying task.
	$UNIT = $S->getUnit(); // Used to display task.
	
	$D = new TaskDisplay($taskId);
	$D->setDisplayNone(true);
	$D->render(TaskDisplay::$SprintPlanningView);
?>