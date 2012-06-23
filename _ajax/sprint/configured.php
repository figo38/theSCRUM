<?php
	/**
	  * Mark the sprint as configured
	  * @param sprintId The sprint ID
	  */
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$sprintId = $_REQUEST['sprintId'];
	
	$S = new Sprint($sprintId);
	$S->updateConfigured(1);
?>	