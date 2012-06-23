<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$projectId = $_REQUEST['id'];
	$storyStory = trim($_REQUEST['story']);
	$storyAcceptance = trim($_REQUEST['acceptance']);
	$storyTypeID = isset($_REQUEST['storytype']) && is_numeric($_REQUEST['storytype']) ? (integer)$_REQUEST['storytype'] : 1;
	$url = isset($_REQUEST['url']) ? trim($_REQUEST['url']) : NULL;
	if ($storyTypeID != 4) {
		$url = NULL;
	}

	$P = new Project($projectId, true);	
	$storyId = $P->addStory($storyStory, $storyAcceptance, $storyTypeID, NULL, $url);
	$projectUnit = $P->getUnit();

	$D = new StoryDisplay($storyId);
	$D->setDisplayNone(true);
	$D->render();
?>