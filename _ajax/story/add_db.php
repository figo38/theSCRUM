<?php
	/**
	  * Add a new standalone story
	  * @param id Project ID
	  * @param eid Epic ID
	  * @param story Story
	  * @param acceptance Acceptance criteria
	  * @param storytype Story type
	  */

	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$projectId = getRequestIntParameter('id');
	$storyStory = getRequestParameter('story');
	$storyAcceptance = getRequestParameter('acceptance');
	$url = getRequestParameter('url');
	$storyTypeID = getRequestIntParameter('storytype', Story::STORY);

	// Url field only makes sense for "bug" story type
	if ($storyTypeID != Story::BUG) {
		$url = NULL;
	}
	// Create the story in the DB
	$P = new Project($projectId, true);	
	$storyId = $P->addStory($storyStory, $storyAcceptance, $storyTypeID, NULL, $url);	
	$projectUnit = $P->getUnit();

	// Display the created story
	$D = new StoryDisplay($storyId);
	$D->setDisplayNone(true);
	$D->render();
?>