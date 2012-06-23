<?php
	$pageTitle = 'No sprint';
	$menu = 2;
	include '_portlets/project_header.php';

	if ($USERAUTH->isProductOwnerOf($projectId) || $USERAUTH->isScrumMasterOf($projectId)) {
?>
<div class="infoMsg">
	<div class="inner">Go to the <a href="<?php echo $projectUrl?>/sprints">sprint configuration</a> to create the first sprint for this project &raquo;</div>
</div>
<?php } else { ?>
<div class="infoMsg">
	<div class="inner">No sprint created yet for this project.</div>
</div>
<?php } ?>