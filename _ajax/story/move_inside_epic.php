<?php
	/**
	  * Move a standalone story inside an epic
	  * @param id ID of the story to move
	  * @param eid Epic ID
	  * @return Write "false" if the epic ID is not valid
	  */

	include_once '../../global.php';
	include_once '../../_classes/classloader.php';

	$storyId = isset($_REQUEST['id']) ? $_REQUEST['id'] : NULL;
	$epicId = isset($_REQUEST['eid']) ? $_REQUEST['eid'] : NULL;

	if ($epicId == NULL) {
		echo 'false';
	} else if (is_numeric($epicId)) {
		if (Story::isValidEpicId($epicId)) {
			// Epic ID is valid
			// Proceeed to move
			$S = new Story($storyId);
			$S->moveToEpic($epicId);
			echo 'true';
		} else {
			echo 'false';
		}
	} else {
		echo 'false';
	}
?>