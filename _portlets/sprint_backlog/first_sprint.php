<?php
	/**
	  * Create the first sprint of the project, then display the sprint dashboard
	  * @param $projectID ProjectID
	  */

	// Create the first sprint
	$P = new Project($projectId, true);
	$P->addSprint();
	
	// Helper message to fill the time allocation of the team
	HelpersControl::infoMsg('The first sprint of this project has been initiated. Please review start and end date, and <a href="' . $projectUrl . '/sprintbacklog/">define the time allocation for your team &raquo;</a>');
	
	// Display the sprint dashboard
	include 'sprints.php';
?>