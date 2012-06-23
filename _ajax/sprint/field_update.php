<?php
	$S = new Sprint($objectid);
	switch ($fieldname) {
		case 'goal':
			$success = $S->updateGoal($new_content);
			break;
		case 'startdate':
			$success = $S->updateStartDate($new_content);
			break;
		case 'enddate':
			$success = $S->updateEndDate($new_content);
			break;
		case 'nrdays':
			$success = $S->updateNrDays($new_content);
			break;
		case 'nbhours':
			$success = $S->updateNrHoursPerDay($new_content);
			break;	
		case 'closed':	
			$success = $S->updateClosed($new_content);
			break;
		case 'unit':	
			$success = $S->updateUnit($new_content);
			break;	
		case 'copytask':
			$success = $S->updateCopiedFromPrevious($new_content);
			break;
		case 'retro1':
			$success = $S->updateRetro1($new_content);
			break;
		case 'retro2':
			$success = $S->updateRetro2($new_content);
			break;			
	}
?>