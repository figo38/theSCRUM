<?php
	$pageTitle = 'No sprint';
	$menu = 2;
	include '_portlets/project_header.php';
?>

<div id="subsubmenuheader">
	<div class="subsubmenu">
        <ul>
        	<li class="selected">First time configuration</li>
		</ul>
	</div>
</div>

<div class="page">

<?php
	// If an admin is logged, check if there is a product owner ; if not, then display an help message
	$flag = false;
	if ($USERAUTH->isAdmin()) {
		if (!$P->hasScrumMaster()) {
			$flag = true; // No need to display any further message
			HelpersControl::infoMsg('There is no scrum master assigned to this project. <a href="' . $projectUrl . '/team">You should choose someone &raquo;</a>');
		}
	}
	
	// If the scrum master is logged, check if there is some team members ; if not, then display an help message
	if ($USERAUTH->isScrumMasterOf($projectId)) {		
		if (!$P->hasTeamMember()) {
			$flag = true; // No need to display any further message
			HelpersControl::infoMsg('There is no team member assigned to this project. <a href="' . $projectUrl . '/team">You should choose someone &raquo;</a>');
		} else {
			$flag = true; // No need to display any further message
			HelpersControl::infoMsg('Dear scrum master, it\'s time to <a href="' . $projectUrl . '/firstsprint">start your first sprint &raquo;</a>');		
		}
	} else {
		if (!$flag) {
			HelpersControl::infoMsg('There is no sprint yet for this project.');
		}
	} 
?>