<?php
	include_once '../global.php';
	include_once '../_classes/classloader.php';
	$storyId = $_REQUEST['id'];
	
	$S = new Story($storyId);
		
	if ($S->removeFromRelease()) {
		echo $storyId;
	} else {
		echo 'FAILED';
	}	
?>