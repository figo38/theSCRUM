<?php
	$JS = array('teamallocation');
	$pageTitle = 'Team allocation';
	$menu = 2;
	include '_portlets/project_header.php';

	if ($USERAUTH->isProductOwnerOf($projectId) || $USERAUTH->isScrumMasterOf($projectId)) {
?>
<div class="infoMsg">
	<div class="inner">
		Pls fill the team allocation for this sprint. <a href="<?php echo $projectUrl?>/sprintbacklog">Start building the sprint backlog when it's done &raquo;</a></a>
	</div>
</div>
<?php
		include '_portlets/teamallocation/team.php';
	} else { ?>
<div class="infoMsg">
	<div class="inner">Sprint not yet initialized.</div>
</div>
<?php } ?>