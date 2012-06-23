<?php
	/**
	  * Add a new task to a story
	  * @param sprintid Sprint ID
	  * @param id Story ID
	  * @param task Task
	  */

	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$sprintId = $_REQUEST['sprintid'];
	$storyId = $_REQUEST['id'];
	$task = $_REQUEST['task'];

	// Register task
	$S = new Sprint($sprintId, true);
	$taskId = $S->addTask($storyId, $task);
	$projectId = $S->getProjectId(); // Used to verify rights when displaying task.
	$UNIT = $S->getUnit(); // Used to display task.
	
	// Render task
	$D = new TaskDisplay($taskId);
	$D->setDisplayNone(true);
	$D->render(TaskDisplay::$SprintPlanningView);
?>