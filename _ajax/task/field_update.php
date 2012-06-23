<?php
	$T = new Task($objectid);
	switch ($fieldname) {
		case 'title':
			$success = $T->updateTitle($new_content);
			break;
		case 'estim':
			$i = (integer)($new_content);
			if ($i < 0) { $i = 0; }
			$new_content = (string)($i);		
			$success = $T->updateEstim($new_content);
			break;
		case 'reestim':
			$i = (integer)($new_content);
			if ($i < 0) { $i = 0; }
			$new_content = (string)($i);			
			$success = $T->updateReestim($new_content);
			break;
		case 'worked':
			$i = (integer)($new_content);
			if ($i < 0) { $i = 0; }
			$new_content = (string)($i);		
			$success = $T->updateWorked($new_content);
			break;
		case 'owner':
			$success = $T->updateOwner($new_content);
			break;
		case 'prio':
			$i = (integer)($new_content);
			if ($i < 0) { $i = 0; }
			$success = $T->updatePriority($i);
			$new_content = (string)($i);
			break;			
	}
?>