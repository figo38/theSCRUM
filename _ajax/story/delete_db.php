<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$storyId = $_REQUEST['id'];

	$S = new Story($storyId, true);
	$res = $S->delete();
	if ($res != false) {
		echo $res;
	} else {
		echo 'FAILED';
	}	
?>