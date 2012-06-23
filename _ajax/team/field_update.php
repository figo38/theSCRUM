<?php
	$S = new Sprint($idtab[3]);
	switch ($fieldname) {
		case 'percentage':
			$success = $S->updateMemberAllocation($objectid, $new_content);
			break;
	}
?>