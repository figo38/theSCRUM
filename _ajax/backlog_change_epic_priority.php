<?php
	include_once '../global.php';
	include_once '../_classes/classloader.php';
	$epicId = $_REQUEST['id'];
	$epicPrio = $_REQUEST['prio'];
	$storyAcceptance = $_REQUEST['acceptance'];
	$storyTypeID = 1; // Child of EPIC is a STORY

	$P = new Project($projectId);
	$storyId = $P->addStory($storyStory, $storyAcceptance, $storyTypeID, $epicId);

	$D = new StoryDisplay(array(
		'id' => $storyId,
		'priority' => 0,
		'estimation' => 0,
		'percentage' => 0,
		'story' => $storyStory,
		'acceptance' => $storyAcceptance,
		'storytype' => $storyTypeID,
		'epicid' => $epicId
	));
	$D->setDisplayNone(true);
	$D->render();
?>