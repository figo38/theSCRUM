<?php
	/**
	  * Update an existing story
	  * @param id Story ID
	  * @param releaseId Release ID
	  * @param tags List of tags separated by ";"
	  * @param url URL if the story type is "bug"
	  * @param storytype Story type
	  */

	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$storyId = getRequestParameter('id');
	$releaseId = getRequestParameter('releaseId', 0);
	$tags = getRequestParameter('tags');
	$url = getRequestParameter('url');
	$storyTypeID = getRequestIntParameter('storytype', Story::STORY);

	// Url field only makes sense for "bug" story type
	if ($storyTypeID != Story::BUG) {
		$url = NULL;
	}

	// Parse the list of tags into an array
	$tagIDs = array();
	$tab = explode(';', $tags);
	foreach ($tab as $key=>$val) {
		if (is_numeric($val)) {
			$tagIDs[] = $val;
		}
	}

	// Update the story
	$S = new Story($storyId);
	$S->updateType($storyTypeID);
	$S->updateURL($url);
	$S->updateTags($tagIDs);
	$S->updateRelease($releaseId);
?>