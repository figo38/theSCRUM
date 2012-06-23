<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$projectId = $_GET['id'];
	$peopleList = $_GET['peoplelist'];
	$listType = $_GET['listtype'];

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