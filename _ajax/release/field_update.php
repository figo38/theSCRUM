<?php
	$R = new Release($objectid);
	switch ($fieldname) {
		case 'planneddate':
			$success = $R->updatePlannedDate($new_content);
			break;
		case 'deployeddate':
			$success = $R->updateDeployedDate($new_content);
			break;
		case 'release':
			$success = $R->updateName($new_content);
			break;
		case 'type':
			$success = $R->updateType($new_content);
			break;
		case 'comment':
			$success = $R->updateComment($new_content);
			break;					
	}
?>