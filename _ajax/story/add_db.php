<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$projectId = $_POST['id'];
	$storyStory = trim($_POST['story']);
	$storyAcceptance = trim($_POST['acceptance']);
	$storyType = $_POST['storytype'];

	$storyTypeID = 1;
	switch ($storyType) {
		case 'STORY':
			$storyTypeID = 1;
			break;
		case 'EPIC':
			$storyTypeID = 2;
			break;
		case 'SPIKE':
			$storyTypeID = 3;
			break;
	}

	$P = new Project($projectId, true);
	$storyId = $P->addStory($storyStory, $storyAcceptance, $storyTypeID);
	$projectUnit = $P->getUnit();

	$D = new StoryDisplay(array(
		'id' => $storyId,
		'priority' => 0,
		'estimation' => 0,
		'percentage' => 0,
		'story' => $storyStory,
		'acceptance' => $storyAcceptance,
		'storytype' => $storyTypeID
	));
	$D->setDisplayNone(true);
	$D->render();
?>