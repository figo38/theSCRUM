<?php
	$DB = new PDO('mysql:host=' . PRODUCTBACKLOG_DB_HOST . ';dbname=' . PRODUCTBACKLOG_DB_NAME, PRODUCTBACKLOG_DB_USER, PRODUCTBACKLOG_DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

	define('DATE_FORMAT', '%Y-%m-%d');

	// Helpers
	include_once 'class_helpers.php';
	include_once 'class_helpersControl.php';
	include_once 'class_helpersDate.php';

	// Class mapped on DB objects
	include_once 'objects/class_object.php';
	include_once 'objects/class_story.php';
	include_once 'objects/class_task.php';
	include_once 'objects/class_project.php';
	include_once 'objects/class_dbuser.php';
	include_once 'objects/class_tag.php';
	include_once 'objects/class_release.php';
	include_once 'objects/class_sprint.php';
	
	// Class used to display DB objects
	include_once 'objectsdisplay/class_taskdisplay.php';
	include_once 'objectsdisplay/class_storydisplay.php';
	include_once 'objectsdisplay/class_projectdisplay.php';
	include_once 'objectsdisplay/class_tagdisplay.php';	
	include_once 'objectsdisplay/class_releasedisplay.php';
	include_once 'objectsdisplay/class_sprintdisplay.php';
	include_once 'objectsdisplay/class_dbuserdisplay.php';
	
	include_once 'class_user.php';
	include_once 'class_usermanagement.php';
	include_once 'class_sprint_snapshot.php';
	include_once 'class_scrum.php';	

	include_once 'class_SprintDays.php';
	include_once 'class_dateIterator.php';

	include_once '_functions.php';
	
	session_start();
	$USERAUTH = new UserManagement();	
	$USERRIGHTS = $USERAUTH->getRights();	
?>