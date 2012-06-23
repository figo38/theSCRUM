<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$projectId = $_REQUEST['id'];

	$P = new Project($projectId, true);
	$projectName = $P->getName();
	$projectUrl = PATH_TO_ROOT . 'project/' . string2url($projectName);
	
	if (strlen($projectId)) {
		$sprintId = $P->addSprint();

		$SD = new SprintDisplay($sprintId);
		$SD->setDisplayNone(true);
		$SD->render();
	}
?>