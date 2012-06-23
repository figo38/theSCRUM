<?php
	/**
	  * Check if the copy tasks screen has been actioned by the user (yes or no)
	  * @param sprintId The sprint ID
	  */
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$sprintId = $_REQUEST['sprintId'];
	
	$S = new Sprint($sprintId, true);
	if ($S->hasTasksCopiedFromPrevious()) echo 'true'; else echo 'false';
?>