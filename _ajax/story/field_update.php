<?php
	$S = new Story($objectid);
	switch ($fieldname) {
		case 'prio':
			$i = (integer)($new_content);
			if ($i < 0) { $i = 0; }
			$success = $S->updatePriority($i);
			$new_content = (string)($i);
			break;
		case 'estim':
			$i = (integer)($new_content);
			if ($i < 0) { $i = 0; }
			$success = $S->updateEstimation($new_content);
			$new_content = (string)($i);
			break;
		case 'percentage':
			$i = (integer)($new_content);
			if ($i < 0) { $i = 0; }
			if ($i > 100) { $i = 100; }
			$success = $S->updatePercentage($new_content);
			$new_content = (string)($i);
			break;
		case 'story':
			$success = $S->updateStory($new_content);
			break;
		case 'criteria':
			$success = $S->updateAcceptance($new_content);
			break;
		case 'title':
			$success = $S->updateTitle($new_content);
			break;
		case 'isroadmapdisplayed':
			$success = $S->updateIsRoadmpaDisplayed($new_content);
			break;
	}
?>