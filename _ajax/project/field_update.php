<?php
	$P = new Project($objectid);
	switch ($fieldname) {
		case 'name':
			$success = $P->updateName($new_content);
			break;	
		case 'closed':
			$success = $P->updateClosed($new_content);
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
		case 'generationhour':
			// Controlling the format is 'HH:MM'
			$t = explode(':',$new_content);
			if (strlen($new_content) == 5 && sizeof($t) == 2) {			
				if (is_numeric($t[0]) && (integer)$t[0] < 24 && is_numeric($t[1]) && (integer)$t[1] < 60) {				
					$success = $P->updateGenerationHour($new_content);
				} else {
					$success = false;
				}
			} else {
				$success = false;
			}
					
			break;
	}
?>