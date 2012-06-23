<?php
	include_once '../global.php';
	include_once '../_classes/classloader.php';
	
	// ID of the HTML element that contains the value to be updated, as sent by Scriptaculous InPlaceEditor
	// Those IDs are standardized as: "[objectname]-[fieldname]-[objectid]"
	// I.E: "task-title-34"
	$fieldId = $_REQUEST["id"];

	// Exploding the fieldID
	$idtab = explode('-', $fieldId);
	$objectname = $idtab[0];
	$fieldname = $idtab[1];
	$objectid = $idtab[2];

	$old_content = isset($_REQUEST["old_content"]) ? trim(urldecode($_REQUEST["old_content"])) : '';
	$new_content = trim(strip_tags(urldecode($_REQUEST["new_content"])));

	$success = false;
	include $objectname . '/field_update.php';
	
	if ($success == 1) {
		echo(nl2br(rawurldecode($new_content)));
	} else {
		echo(rawurldecode($old_content));
	}
?>