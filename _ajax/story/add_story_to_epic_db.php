<?php
	/**
	  * Add a new sub-story to an existing epic
	  * @param id Project ID
	  * @param eid Epic ID
	  * @param story Story
	  * @param acceptance Acceptance criteria
	  * @param storytype Story type
	  */

	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$projectId = getRequestIntParameter('id');	
	$epicId = getRequestIntParameter('eid');
	$storyStory = getRequestParameter('story');
	$storyAcceptance = getRequestParameter('acceptance');
	$storyTypeID = getRequestIntParameter('storytype', Story::STORY);

	// Create the story in the DB	
	$P = new Project($projectId, true);
	$storyId = $P->addStory($storyStory, $storyAcceptance, $storyTypeID, $epicId);
	$projectUnit = $P->getUnit();

	// Display the created story
	$D = new StoryDisplay($storyId);
	$D->setDisplayNone(true);
	$D->render();
?>