<?php
	/**
	  * Delete a story id
	  * @param id Story ID
	  * @return A list of storyIDs as a string (i.e: if we delete an epic and all its sub-stories) 
	  */

	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$storyId = getRequestIntParameter('id');

	$S = new Story($storyId, true);
	$res = $S->delete();
	if ($res != false) {
		echo $res;
	} else {
		echo 'FAILED';
	}	
?>