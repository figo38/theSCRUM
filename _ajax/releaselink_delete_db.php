<?php
	include_once '../global.php';
	include_once '../_classes/classloader.php';
	$storyId = $_REQUEST['id'];
	
	$PBH = new ProductBacklogHelpers();
	$res = $PBH->deleteLinkRelease($storyId);
	if ($res == $storyId) {
		echo $res;
	} else {
		echo 'FAILED';
	}	
?>