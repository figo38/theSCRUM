<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	$projectId = $_GET['id'];
	$epicId = $_GET['eid'];
	$storyStory = $_GET['story'];
	$storyAcceptance = $_GET['acceptance'];
	$storyType = $_GET['storytype'];

	$storyTypeID = 1;
	switch ($storyType) {
		case 'STORY':
			$storyTypeID = 1;
			break;
		case 'SPIKE':
			$storyTypeID = 3;
			break;
		default:
			break;
	}

	$P = new Project($projectId, true);
	$storyId = $P->addStory($storyStory, $storyAcceptance, $storyTypeID, $epicId);
	$projectUnit = $P->getUnit();

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