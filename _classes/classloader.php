<?php
	$DB = new PDO('mysql:host=' . PRODUCTBACKLOG_DB_HOST . ';dbname=' . PRODUCTBACKLOG_DB_NAME, PRODUCTBACKLOG_DB_USER, PRODUCTBACKLOG_DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

	include_once 'class_helpers.php';
	include_once 'class_scrum.php';	
	include_once 'class_object.php';
	include_once 'class_story.php';
	include_once 'class_task.php';
	include_once 'class_project.php';
	include_once 'class_user.php';
	include_once 'class_usermanagement.php';
	include_once 'class_productbacklog.php';
	include_once 'class_featuregroup.php';
	include_once 'class_storydisplay.php';
	include_once 'class_projectdisplay.php';
	include_once 'class_featuregroupdisplay.php';
	include_once 'class_release.php';
	include_once 'class_releasedisplay.php';
	include_once 'class_sprint.php';
	include_once 'class_sprintdisplay.php';
	include_once 'class_taskdisplay.php';
	include_once 'class_sprint_snapshot.php';
	
	include_once '_functions.php';

	session_start();
	$USERAUTH = new UserManagement();
?>