<?php
	/**
	  * Register a working day for a sprint
	  * @param sprintId Sprint ID
	  * @param dayAsString The day to register as a string: yyyymmdd
	  */
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$sprintId = $_REQUEST['sprintId'];
	$dayAsString = $_REQUEST['dayAsString'];
	
	SprintDays::unregisterDay($sprintId, $dayAsString);
?>