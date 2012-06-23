<?php
	try {
		$projectUnit = $P->getUnit();
		$projectName = $P->getName();
		$projectUrl = PATH_TO_ROOT . 'project/' . string2url($projectName);
	} catch (Exception $e) {
		redirectToHomepage();	
	}
		
	$pageTitle .= ' - ' . $projectName;
	include '_include/header.php';
?>
</div>

<ul class="submenu">
	<li class="title">&laquo;<?php echo $P->getName()?>&raquo;</li>
	<li<?php if ($menu == 1) {?> class="selected"<?php } ?>><a href="<?php echo $projectUrl?>">Product backlog</a></li>
<?php
	if ($P->hasSprints()) {
?>
	<li<?php if ($menu == 2) {?> class="selected"<?php } ?>><a href="<?php echo $projectUrl . '/sprintbacklog'?>">Sprints</a></li>
<?php 
	} 
?>
	<li<?php if ($menu == 4) {?> class="selected"<?php } ?>><a href="<?php echo $projectUrl . '/team'?>">Team</a></li>
<?php
	if ($P->hasSprints()) {
?>
	<li<?php if ($menu == 6) {?> class="selected"<?php } ?>><a href="<?php echo $projectUrl . '/stats'?>">Statistics</a></li>
<?php 
	} 
?>	
</ul>