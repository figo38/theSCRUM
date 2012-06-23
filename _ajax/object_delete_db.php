<?php
	include_once '../global.php';;
	include_once '../_classes/classloader.php';
	
	// ID of the HTML element that contains the value to be updated
	// Those IDs are standardized as: "[objectname]-delete-[objectid]"
	// I.E: "delete-sprint-34"
	$fieldId = $_REQUEST["id"];

	// Exploding the fieldID
	$idtab = explode('-', $fieldId);
	$objectname = $idtab[0];
	$objectid = $idtab[2];
	
	include $objectname . '/delete_db.php';	
?>