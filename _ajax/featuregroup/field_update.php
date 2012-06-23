<?php
	$F = new FeatureGroup($objectid);
	switch ($fieldname) {
		case 'name':
			$success = $F->updateName($new_content);
			break;
	}
?>