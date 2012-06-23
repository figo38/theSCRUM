<?php
	include_once '../global.php';
	include_once '../_classes/classloader.php';

	$objectname = $_REQUEST['objname'];
	include $objectname . '/add_db.php';
?>