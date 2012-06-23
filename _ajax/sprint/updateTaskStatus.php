<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$ids = $_POST['ids'];
	$idsArray = explode(',', $ids);
	$listName = $_POST['listtype'];
	
	switch ($listName) {
		case 'donelist':
			foreach ($idsArray as $key => $val) {
				$T = new Task($val);
				$T->markAsDone();			
			}
			break;
		case 'todolist':
			foreach ($idsArray as $key => $val) {
				$T = new Task($val);
				$T->markAsTodo();			
			}
			break;
		case 'inprogresslist':
			foreach ($idsArray as $key => $val) {
				$T = new Task($val);
				$T->markAsInProgress();			
			}
			break;						
	}
?>