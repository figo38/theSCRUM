<?php
	$projectname = trim($_REQUEST['new_project']);
	if (strlen($projectname)) {
		$projectId = Scrum::addProject($projectname);
		$D = new ProjectDisplay($projectId);
		$D->setDisplayNone(true);
		$D->render();
	}
?>