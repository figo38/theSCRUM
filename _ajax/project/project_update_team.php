<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$projectId = $_REQUEST['id'];
	$peopleList = $_REQUEST['peoplelist'];
	$listType = $_REQUEST['listtype'];

	$P = new Project($projectId);
	
	switch ($listType) {
		case 'productowners':
			$P->updateProductOwners($peopleList);
			break;
		case 'scrummasters':
			$P->updateScrumMasters($peopleList);
			break;
		case 'team':
			$P->updateTeamMembers($peopleList);
			break;
	}
?>