<?php
	/**
	  * Move a sub-story outside an epic
	  * @param id ID of the story to move outside
	  * @return Write "false" if the epic ID is not valid
	  */

	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$storyId = isset($_REQUEST['id']) ? $_REQUEST['id'] : NULL;

	$S = new Story($storyId);
	$S->moveOutsideEpic();

	echo 'true';
?>