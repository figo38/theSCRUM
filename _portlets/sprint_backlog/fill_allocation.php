






<?php
	if ($USERAUTH->isScrumMasterOf($projectId)) {
		HelpersControl::infoMsg('Please define the amount of time each team member will be able to work on this sprint. Then <a href="' . $projectUrl . '/sprintbacklog">start building the sprint backlog &raquo;</a>');
		include '_portlets/teamallocation/team.php';
	} else { 
		// TO BE DONE
		HelpersControl::infoMsg('Sprint not yet initialized');
	} 
?>