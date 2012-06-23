<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$projectId = $_REQUEST['id'];
	$epicId = $_REQUEST['eid'];
	$storyStory = $_REQUEST['story'];
	$storyAcceptance = $_REQUEST['acceptance'];
	$storyType = $_REQUEST['storytype'];

	$storyTypeID = is_numeric($storyType) ? (integer)$storyType : 4;

	$P = new Project($projectId, true);
	$storyId = $P->addStory($storyStory, $storyAcceptance, $storyTypeID, $epicId);
	$projectUnit = $P->getUnit();

	$D = new StoryDisplay($storyId);
	$D->setDisplayNone(true);
	$D->render();
?>