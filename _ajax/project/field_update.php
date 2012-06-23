<?php
	$P = new Project($objectid);
	switch ($fieldname) {
		case 'name':
			$success = $P->updateName($new_content);
			break;	
		case 'velocity':
			$success = $P->updateVelocity($new_content);
			break;
		case 'sprintbyquarter':
			$success = $P->updateSprintByQuarter($new_content);
			break;
		case 'goal':
			$success = $P->updateGoal($new_content);
			break;
		case 'hassprint':
			$success = $P->updateHasSprints($new_content);
			break;
		case 'unit':	
			$success = $P->updateUnit($new_content);
			break;			
	}
?>